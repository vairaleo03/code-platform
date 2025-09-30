<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\User;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    private GoogleDriveService $googleDriveService;
    
    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    public function index()
    {
        return view('dashboard.upload');
    }

    public function store(Request $request)
    {
        try {
            Log::info('Upload request ricevuta', [
                'user_id' => auth()->id(),
                'has_audio' => $request->hasFile('audio_file'),
                'has_documents' => $request->hasFile('documents'),
                'notes_length' => strlen($request->notes ?? ''),
                'notes_preview' => substr($request->notes ?? '', 0, 100)
            ]);

            $user = auth()->user();

            // Check plan limits
            if ($user->verbali_remaining <= 0) {
                return redirect()->back()->with('error', 'Crediti esauriti. Aggiorna il tuo piano.');
            }

            // FIXED: Validazione più specifica
            $request->validate([
                'audio_file' => 'required|file|mimes:mp3,wav,m4a,aac|max:51200', // 50MB max
                'documents.*' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240', // 10MB per doc
                'notes' => 'required|string|min:10|max:5000' // Almeno 10 caratteri
            ], [
                'audio_file.required' => 'Il file audio è obbligatorio',
                'audio_file.mimes' => 'Il file audio deve essere in formato MP3, WAV, M4A o AAC',
                'audio_file.max' => 'Il file audio non può superare i 50MB',
                'notes.required' => 'Le note della riunione sono obbligatorie',
                'notes.min' => 'Le note devono contenere almeno 10 caratteri',
                'documents.*.mimes' => 'I documenti devono essere in formato PDF, DOC, DOCX o TXT',
                'documents.*.max' => 'Ogni documento non può superare i 10MB'
            ]);

            // Gestione avanzata del payload notes
            $notesContent = $request->notes;
            
            // FIXED: Migliore gestione delle note strutturate
            if (is_string($notesContent) && $this->isJson($notesContent)) {
                $notesData = json_decode($notesContent, true);
                $formattedNotes = $this->formatStructuredNotes($notesData);
                Log::info('Note strutturate processate', [
                    'original_length' => strlen($notesContent),
                    'formatted_length' => strlen($formattedNotes)
                ]);
            } else {
                $formattedNotes = $request->notes;
            }

            // Crea upload record con le note formattate
            $upload = Upload::create([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'notes' => $formattedNotes,
                'status' => 'pending'
            ]);

            Log::info('Upload record creato', [
                'upload_id' => $upload->id,
                'user_id' => $user->id,
                'notes_length' => strlen($formattedNotes)
            ]);

            try {
                // FIXED: Migliore gestione degli errori Google Drive
                $result = $this->processGoogleDriveUpload($upload, $request);

                if ($result['success']) {
                    // Update upload record with Google Drive info
                    $upload->update([
                        'drive_folder_path' => $result['folder_id'],
                        'drive_folder_link' => $result['folder_link'] ?? null,
                        'audio_file_id' => $result['audio_file_id'] ?? null,
                        'documents_file_ids' => !empty($result['document_ids']) ? json_encode($result['document_ids']) : null,
                        'notes_file_id' => $result['notes_file_id'] ?? null,
                        'status' => 'uploaded',
                        'uploaded_at' => now()
                    ]);

                    // Notify admin
                    $this->notifyAdminNewUpload($upload);

                    // Decrease user credits
                    $user->decrement('verbali_remaining');

                    Log::info('Upload completato con successo su Google Drive', [
                        'upload_id' => $upload->id,
                        'folder_id' => $result['folder_id'],
                        'credits_remaining' => $user->verbali_remaining
                    ]);

                    return redirect()->back()->with('success',
                        'File caricati con successo su Google Drive. Riceverai il verbale via email entro 24-48 ore.'
                    );
                    
                } else {
                    Log::warning('Google Drive upload fallito, uso storage locale', [
                        'upload_id' => $upload->id,
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                    
                    // Fallback to local storage
                    $folderPath = $this->storeFilesLocally($upload, $request);
                    $upload->update([
                        'drive_folder_path' => $folderPath,
                        'status' => 'uploaded_local', // FIXED: Stato valido per enum
                        'uploaded_at' => now()
                    ]);

                    $this->notifyAdminNewUpload($upload);
                    $user->decrement('verbali_remaining');

                    Log::info('Upload completato con storage locale', [
                        'upload_id' => $upload->id,
                        'folder_path' => $folderPath,
                        'credits_remaining' => $user->verbali_remaining
                    ]);

                    return redirect()->back()->with('success',
                        'File caricati localmente. Riceverai il verbale via email entro 24-48 ore.'
                    );
                }

            } catch (\Exception $e) {
                // Log dell'errore dettagliato
                Log::error('Errore durante upload', [
                    'upload_id' => $upload->id,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);

                // FIXED: Non cancellare l'upload record, aggiorna solo lo stato
                $upload->update([
                    'status' => 'failed'
                ]);

                return redirect()->back()->with('error',
                    'Errore durante il caricamento dei file. Il nostro team è stato notificato. Riprova o contatta il supporto.'
                );
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Errori di validazione', [
                'errors' => $e->errors(),
                'user_id' => auth()->id()
            ]);
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Errore generale nel processo di upload', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 
                'Si è verificato un errore imprevisto. Il nostro team è stato notificato.'
            );
        }
    }

    /**
     * Process upload with Google Drive
     */
    public function processGoogleDriveUpload($upload, $request): array
    {
        try {
            $timestamp = now()->format('Y-m-d_H-i-s');

            // Create folder structure
            $uploadFolderId = $this->googleDriveService->createFolderStructure(
                $upload->user_email,
                $upload->id,
                $timestamp
            );

            // Upload audio file
            $audioFileId = $this->googleDriveService->uploadAudioFile(
                $request->file('audio_file'),
                $uploadFolderId
            );

            // Upload documents if any
            $documentIds = [];
            if ($request->hasFile('documents')) {
                $documentIds = $this->googleDriveService->uploadDocuments(
                    $request->file('documents'),
                    $uploadFolderId
                );
            }

            // Create notes file
            $notesFileId = $this->googleDriveService->createNotesFile(
                $upload->notes, // Usa le note già formattate
                $uploadFolderId
            );

            // Get shareable link
            $folderLink = $this->googleDriveService->getFolderShareableLink($uploadFolderId);

            Log::info('Google Drive upload successful', [
                'upload_id' => $upload->id,
                'folder_id' => $uploadFolderId,
                'audio_file_id' => $audioFileId,
                'documents_count' => count($documentIds),
                'notes_file_id' => $notesFileId,
                'folder_link' => $folderLink
            ]);

            return [
                'success' => true,
                'folder_id' => $uploadFolderId,
                'folder_link' => $folderLink,
                'audio_file_id' => $audioFileId,
                'document_ids' => $documentIds,
                'notes_file_id' => $notesFileId
            ];

        } catch (\Exception $e) {
            Log::error('Google Drive upload failed', [
                'upload_id' => $upload->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false, 
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Fallback: store files locally
     */
    private function storeFilesLocally($upload, $request): string
    {
        // Create folder structure: uploads/email_uploadID_timestamp
        $folderName = 'uploads/' . $upload->user_email . '_' . $upload->id . '_' . now()->format('Ymd_His');
        
        // Store audio file
        $audioPath = $request->file('audio_file')->store($folderName . '/audio');
        
        // Store documents if any
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $document) {
                $document->store($folderName . '/documents');
            }
        }
        
        // Create notes.txt file
        Storage::put($folderName . '/note_riunione.txt', $upload->notes);
        
        Log::info('Local storage upload completed', [
            'upload_id' => $upload->id,
            'folder_path' => $folderName,
            'audio_path' => $audioPath
        ]);
        
        return $folderName;
    }
    
    private function notifyAdminNewUpload($upload)
    {
        Log::info('New upload created - Admin notification', [
            'upload_id' => $upload->id,
            'user_email' => $upload->user_email,
            'folder_path' => $upload->drive_folder_path,
            'status' => $upload->status,
            'has_drive_link' => !empty($upload->drive_folder_link),
            'timestamp' => now()->toISOString()
        ]);
        
        // TODO: Implement email notification
        /*
        try {
            Mail::send('emails.new-upload', compact('upload'), function($message) use ($upload) {
                $message->to(config('app.admin_email'))
                       ->subject('Nuovo upload ODV - ' . $upload->user_email);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification', [
                'upload_id' => $upload->id,
                'error' => $e->getMessage()
            ]);
        }
        */
    }

    // Metodi di supporto
    private function isJson($string): bool 
    {
        if (!is_string($string)) {
            return false;
        }
        
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function formatStructuredNotes($data): string 
    {
        $output = "";

        if (isset($data['meeting_details'])) {
            $md = $data['meeting_details'];
            $output .= "DETTAGLI RIUNIONE:\n";
            $output .= "Data: " . ($md['date'] ?? 'N/A') . "\n";
            $output .= "Luogo: " . ($md['location'] ?? 'N/A') . "\n";
            $output .= "Orario: " . ($md['startTime'] ?? '00:00') . " - " . ($md['endTime'] ?? '00:00') . "\n\n";
        }

        if (isset($data['odv_members']) && !empty($data['odv_members'])) {
            $output .= "COMPONENTI ODV:\n";
            foreach ($data['odv_members'] as $member) {
                if (!empty($member['name'])) {
                    $output .= "- " . $member['name'] . " (" . ($member['role'] ?? 'Componente') . ")\n";
                }
            }
            $output .= "\n";
        }

        if (isset($data['other_attendees']) && !empty($data['other_attendees'])) {
            $output .= "ALTRI PARTECIPANTI:\n";
            foreach ($data['other_attendees'] as $attendee) {
                if (!empty($attendee['name']) || !empty($attendee['role'])) {
                    $output .= "- " . ($attendee['name'] ?? '') . " " . (!empty($attendee['role']) ? "(" . $attendee['role'] . ")" : "") . "\n";
                }
            }
            $output .= "\n";
        }

        if (isset($data['notes_content'])) {
            $output .= "CONTENUTO RIUNIONE:\n" . $data['notes_content'];
        }

        return $output;
    }
}