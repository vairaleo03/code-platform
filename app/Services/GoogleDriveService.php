<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Exception;

class GoogleDriveService
{
    private Client $client;
    private Drive $service;
    private int $maxRetries;
    private int $timeout;
    
    public function __construct()
    {
        $this->maxRetries = config('services.google.retry_attempts', 3);
        $this->timeout = config('services.google.timeout', 120);
        
        $this->initializeClient();
    }
    
    private function initializeClient(): void
    {
        try {
            // Fix: Risolvi il path correttamente
            $credentialsPath = config('services.google.credentials_path');
            
            // Se è un path relativo, risolvilo dalla root di Laravel
            if (!str_starts_with($credentialsPath, '/')) {
                $credentialsPath = base_path($credentialsPath);
            }
            
            if (!file_exists($credentialsPath)) {
                throw new Exception("Google Drive credentials file not found at: {$credentialsPath}");
            }
            
            $this->client = new Client();
            $this->client->setAuthConfig($credentialsPath);
            $this->client->addScope([Drive::DRIVE_FILE, Drive::DRIVE]);
            $this->client->setAccessType('offline');
            $this->client->setApplicationName('CO.DE Platform');
            
            // Set timeout for requests (pass options to Google Client)
            $this->client->setHttpClient(
                new \GuzzleHttp\Client(['timeout' => $this->timeout])
            );
            
            $this->service = new Drive($this->client);
            
            // Test connection
            $this->testConnection();
            
        } catch (Exception $e) {
            Log::error('Google Drive initialization failed', [
                'error' => $e->getMessage(),
                'credentials_path' => $credentialsPath ?? 'not set'
            ]);
            throw new Exception('Google Drive service initialization failed: ' . $e->getMessage());
        }
    }
    
    private function testConnection(): void
    {
        try {
            $this->service->about->get(['fields' => 'user']);
        } catch (Exception $e) {
            throw new Exception('Google Drive connection test failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Execute a Google Drive operation with retry logic
     */
    private function executeWithRetry(callable $operation, string $operationName): mixed
    {
        $lastException = null;
        
        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            try {
                return $operation();
                
            } catch (Exception $e) {
                $lastException = $e;
                
                Log::warning("Google Drive operation '{$operationName}' failed (attempt {$attempt}/{$this->maxRetries})", [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt
                ]);
                
                if ($attempt < $this->maxRetries) {
                    // Exponential backoff: 1s, 2s, 4s
                    $delay = pow(2, $attempt - 1);
                    sleep($delay);
                }
            }
        }
        
        throw new Exception("Google Drive operation '{$operationName}' failed after {$this->maxRetries} attempts: " . $lastException->getMessage());
    }
    
    /**
     * Create folder structure for user upload
     */
    public function createFolderStructure(string $userEmail, int $uploadId, string $timestamp): string
    {
        return $this->executeWithRetry(function () use ($userEmail, $uploadId, $timestamp) {
            // 1. Find or create main folder
            $mainFolderId = $this->findOrCreateFolder(config('services.google.main_folder', 'CO.DE Platform'), null);
            
            // 2. Find or create user folder
            $sanitizedEmail = $this->sanitizeFileName($userEmail);
            $userFolderId = $this->findOrCreateFolder($sanitizedEmail, $mainFolderId);
            
            // 3. Create upload-specific folder
            $uploadFolderName = "Upload_{$uploadId}_{$timestamp}";
            $uploadFolderId = $this->findOrCreateFolder($uploadFolderName, $userFolderId);
            
            // 4. Create subfolders
            $this->findOrCreateFolder('Audio', $uploadFolderId);
            $this->findOrCreateFolder('Documenti', $uploadFolderId);
            
            Log::info('Google Drive folder structure created', [
                'user_email' => $userEmail,
                'upload_id' => $uploadId,
                'upload_folder_id' => $uploadFolderId
            ]);
            
            return $uploadFolderId;
            
        }, 'createFolderStructure');
    }
    
    /**
     * Upload a file to Google Drive
     * FIXED: Explicit nullable type for $fileName
     */
    public function uploadFile(UploadedFile $file, string $folderId, ?string $fileName = null): string
    {
        return $this->executeWithRetry(function () use ($file, $folderId, $fileName) {
            $fileName = $fileName ?: $file->getClientOriginalName();
            $fileName = $this->sanitizeFileName($fileName);

            $maxSize = config('services.google.max_file_size', 52428800);
            if ($file->getSize() > $maxSize) {
                throw new Exception("File size ({$file->getSize()} bytes) exceeds maximum allowed ({$maxSize} bytes)");
            }

            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [$folderId]
            ]);

            $content = file_get_contents($file->getRealPath());
            if ($content === false) {
                throw new Exception("Failed to read file content: " . $file->getRealPath());
            }

            $mimeType = $file->getMimeType();

            $options = [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id,name,webViewLink,size'
            ];

            $driveId = config('services.google.shared_drive_id');
            if ($driveId) {
                $options['supportsAllDrives'] = true;
            }

            $createdFile = $this->service->files->create($fileMetadata, $options);

            Log::info('File uploaded to Google Drive', [
                'file_name' => $fileName,
                'file_id' => $createdFile->id,
                'folder_id' => $folderId,
                'size' => $file->getSize(),
                'mime_type' => $mimeType
            ]);

            return $createdFile->id;

        }, 'uploadFile');
    }
    
    /**
     * Upload audio file to Audio subfolder
     */
    public function uploadAudioFile(UploadedFile $audioFile, string $uploadFolderId): string
    {
        $audioFolderId = $this->findSubfolder($uploadFolderId, 'Audio');
        $timestamp = now()->format('Y-m-d_H-i-s');
        $extension = $audioFile->getClientOriginalExtension();
        $fileName = "registrazione_riunione_{$timestamp}.{$extension}";
        
        return $this->uploadFile($audioFile, $audioFolderId, $fileName);
    }
    
    /**
     * Upload multiple documents to Documents subfolder
     */
    public function uploadDocuments(array $documents, string $uploadFolderId): array
    {
        $documentsFolderId = $this->findSubfolder($uploadFolderId, 'Documenti');
        $uploadedDocuments = [];
        
        foreach ($documents as $index => $document) {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $extension = $document->getClientOriginalExtension();
            $fileName = "documento_" . ($index + 1) . "_{$timestamp}.{$extension}";
            
            $fileId = $this->uploadFile($document, $documentsFolderId, $fileName);
            $uploadedDocuments[] = [
                'file_id' => $fileId,
                'original_name' => $document->getClientOriginalName(),
                'stored_name' => $fileName,
                'size' => $document->getSize()
            ];
        }
        
        return $uploadedDocuments;
    }
    
    /**
     * Create a text file with meeting notes
     */
    public function createNotesFile(string $notesContent, string $uploadFolderId): string
    {
        return $this->executeWithRetry(function () use ($notesContent, $uploadFolderId) {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "note_riunione_{$timestamp}.txt";

            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [$uploadFolderId]
            ]);

            $options = [
                'data' => $notesContent,
                'mimeType' => 'text/plain',
                'uploadType' => 'multipart',
                'fields' => 'id,name'
            ];

            $driveId = config('services.google.shared_drive_id');
            if ($driveId) {
                $options['supportsAllDrives'] = true;
            }

            $createdFile = $this->service->files->create($fileMetadata, $options);

            Log::info('Notes file created on Google Drive', [
                'file_id' => $createdFile->id,
                'file_name' => $fileName,
                'folder_id' => $uploadFolderId
            ]);

            return $createdFile->id;

        }, 'createNotesFile');
    }
    
    /**
     * Get shareable link for a folder
     */
    public function getFolderShareableLink(string $folderId): string
    {
        return $this->executeWithRetry(function () use ($folderId) {
            $permission = new Permission([
                'role' => 'reader',
                'type' => 'anyone'
            ]);

            $driveId = config('services.google.shared_drive_id');
            $permOptions = [];
            $getOptions = ['fields' => 'webViewLink'];
            if ($driveId) {
                $permOptions['supportsAllDrives'] = true;
                $getOptions['supportsAllDrives'] = true;
            }

            $this->service->permissions->create($folderId, $permission, $permOptions);

            $folder = $this->service->files->get($folderId, $getOptions);

            return $folder->webViewLink;

        }, 'getFolderShareableLink');
    }
    
    /**
     * Delete a folder and all its contents
     */
    public function deleteFolder(string $folderId): bool
    {
        try {
            return $this->executeWithRetry(function () use ($folderId) {
                $driveId = config('services.google.shared_drive_id');
                $options = [];
                if ($driveId) {
                    $options['supportsAllDrives'] = true;
                }
                $this->service->files->delete($folderId, $options);

                Log::info('Folder deleted from Google Drive', [
                    'folder_id' => $folderId
                ]);

                return true;

            }, 'deleteFolder');

        } catch (Exception $e) {
            Log::error('Failed to delete folder from Google Drive', [
                'folder_id' => $folderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Find or create a folder
     * FIXED: Explicit nullable type for $parentId
     */
    private function findOrCreateFolder(string $name, ?string $parentId = null): string
    {
        $driveId = config('services.google.shared_drive_id');

        // Search for existing folder
        $query = "name='{$name}' and mimeType='application/vnd.google-apps.folder' and trashed=false";
        if ($parentId) {
            $query .= " and '{$parentId}' in parents";
        }

        $options = [
            'q' => $query,
            'fields' => 'files(id, name)',
            'pageSize' => 10
        ];

        if ($driveId) {
            $options['driveId'] = $driveId;
            $options['corpora'] = 'drive';
            $options['includeItemsFromAllDrives'] = true;
            $options['supportsAllDrives'] = true;
        }

        $response = $this->service->files->listFiles($options);

        if (count($response->files) > 0) {
            return $response->files[0]->id;
        }

        // Create new folder
        $fileMetadata = new DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder'
        ]);

        if ($parentId) {
            $fileMetadata->setParents([$parentId]);
        }

        $createOptions = ['fields' => 'id'];

        if ($driveId) {
            $createOptions['supportsAllDrives'] = true;
            // Se stiamo creando la prima cartella di primo livello, assegnarla allo Shared Drive
            if (!$parentId) {
                $fileMetadata->setDriveId($driveId);
            }
        }

        $folder = $this->service->files->create($fileMetadata, $createOptions);

        return $folder->id;
    }
    
    /**
     * Find a subfolder by name
     */
    private function findSubfolder(string $parentId, string $name): string
    {
        $driveId = config('services.google.shared_drive_id');
        $query = "name='{$name}' and mimeType='application/vnd.google-apps.folder' and '{$parentId}' in parents and trashed=false";

        $options = [
            'q' => $query,
            'fields' => 'files(id, name)'
        ];

        if ($driveId) {
            $options['driveId'] = $driveId;
            $options['corpora'] = 'drive';
            $options['includeItemsFromAllDrives'] = true;
            $options['supportsAllDrives'] = true;
        }

        $response = $this->service->files->listFiles($options);

        if (count($response->files) > 0) {
            return $response->files[0]->id;
        }

        throw new Exception("Subfolder '{$name}' not found in parent '{$parentId}'");
    }
    
    /**
     * Sanitize filename for Google Drive
     * FIXED: Corretto il pattern regex problematico
     */
    private function sanitizeFileName(string $fileName): string
    {
        // FIXED: Corretto il pattern regex che causava l'errore "Unknown modifier"
        // Rimuovi caratteri non supportati
        $sanitized = preg_replace('/[<>:"|?*\\\\\/]/', '_', $fileName);
        
        // Sostituisci spazi multipli con underscore
        $sanitized = preg_replace('/\s+/', '_', $sanitized ?? '');
        
        // Limita la lunghezza
        return substr($sanitized ?? '', 0, 100);
    }
    
    /**
     * Get folder contents
     */
    public function getFolderContents(string $folderId): array
    {
        try {
            return $this->executeWithRetry(function () use ($folderId) {
                $options = [
                    'q' => "'{$folderId}' in parents and trashed=false",
                    'fields' => 'files(id, name, mimeType, size, createdTime, webViewLink)',
                    'orderBy' => 'createdTime desc'
                ];

                $driveId = config('services.google.shared_drive_id');
                if ($driveId) {
                    $options['driveId'] = $driveId;
                    $options['corpora'] = 'drive';
                    $options['includeItemsFromAllDrives'] = true;
                    $options['supportsAllDrives'] = true;
                }

                $response = $this->service->files->listFiles($options);

                return $response->files;

            }, 'getFolderContents');

        } catch (Exception $e) {
            Log::error('Failed to get folder contents from Google Drive', [
                'folder_id' => $folderId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}