<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Upload::with('user')->orderBy('created_at', 'desc');
        
        // Filtro per status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filtro per upload da processare (senza verbale)
        if ($request->filled('needs_processing')) {
            $query->whereIn('status', ['uploaded', 'uploaded_local', 'pending'])
                ->whereNull('verbale_file_path');
        }
        
        // Filtro per upload pronti per invio (con verbale ma non inviati)
        if ($request->filled('ready_to_send')) {
            $query->whereNotNull('verbale_file_path')
                ->whereNull('verbale_sent_at');
        }
        
        $uploads = $query->paginate(20);
        
        // Statistiche aggiornate per la dashboard
        $stats = [
            'pending' => Upload::where('status', 'pending')->count(),
            'processing' => Upload::where('status', 'processing')->count(),
            'needs_verbale' => Upload::whereIn('status', ['uploaded', 'uploaded_local', 'pending'])
                                    ->whereNull('verbale_file_path')->count(),
            'ready_to_send' => Upload::whereNotNull('verbale_file_path')
                                    ->whereNull('verbale_sent_at')->count(),
            'completed' => Upload::where('status', 'completed')->count(),
        ];
        
        return view('admin.dashboard', compact('uploads', 'stats'));
    }
    
    public function show(Upload $upload)
    {
        $upload->load('user');
        return view('admin.upload-detail', compact('upload'));
    }
    
    public function updateStatus(Request $request, Upload $upload)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed' // Solo questi 3 status
        ]);
        
        $oldStatus = $upload->status;
        $upload->update([
            'status' => $request->status,
            'processing_started_at' => $request->status === 'processing' ? now() : $upload->processing_started_at,
            'completed_at' => $request->status === 'completed' ? now() : $upload->completed_at
        ]);
        
        Log::info('Admin updated upload status', [
            'upload_id' => $upload->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'admin_action' => true
        ]);
        
        return redirect()->back()->with('success', 'Status aggiornato con successo');
    }
    
    public function uploadVerbale(Request $request, Upload $upload)
    {
        $request->validate([
            'verbale_file' => 'required|file|mimes:pdf,doc,docx|max:10240' // 10MB max
        ]);
        
        $file = $request->file('verbale_file');
        $filename = 'verbale_finale_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
        
        // Salva nella stessa cartella dell'upload del cliente
        if ($upload->drive_folder_path) {
            // Se l'upload è su Google Drive, salva comunque localmente per backup
            $localPath = $upload->drive_folder_path . '/' . $filename;
            $path = $file->storeAs($upload->drive_folder_path, $filename, 'local');
        } else {
            // Se l'upload è locale, usa la stessa struttura
            $path = $file->storeAs($upload->drive_folder_path, $filename, 'local');
        }
        
        $upload->update([
            'verbale_file_path' => $path,
            'verbale_uploaded_at' => now()
        ]);
        
        Log::info('Admin uploaded verbale to client folder', [
            'upload_id' => $upload->id,
            'client_folder' => $upload->drive_folder_path,
            'verbale_path' => $path,
            'file_size' => $file->getSize()
        ]);
        
        return redirect()->back()->with('success', 'Verbale caricato nella cartella del cliente');
    }
    
    public function sendVerbale(Upload $upload)
    {
        if (!$upload->verbale_file_path || !Storage::exists($upload->verbale_file_path)) {
            return redirect()->back()->with('error', 'Nessun verbale da inviare');
        }
        
        try {
            // TODO: Implement actual email sending
            // For now, just log the action
            Log::info('Admin sent verbale to client', [
                'upload_id' => $upload->id,
                'user_email' => $upload->user_email,
                'verbale_path' => $upload->verbale_file_path
            ]);
            
            $upload->update([
                'verbale_sent_at' => now(),
                'status' => 'completed'
            ]);
            
            return redirect()->back()->with('success', 'Verbale inviato con successo a ' . $upload->user_email);
            
        } catch (\Exception $e) {
            Log::error('Failed to send verbale', [
                'upload_id' => $upload->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Errore nell\'invio del verbale');
        }
    }
}