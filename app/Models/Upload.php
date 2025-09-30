<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Upload extends Model
{
    protected $fillable = [
        'user_id',
        'user_email', 
        'drive_folder_path',
        'drive_folder_link',
        'audio_file_id',
        'documents_file_ids',
        'notes_file_id',
        'notes',
        'status',
        'uploaded_at',
        'processing_started_at',
        'completed_at',
        'verbale_file_path',
        'verbale_uploaded_at',
        'verbale_sent_at'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'processing_started_at' => 'datetime',
        'completed_at' => 'datetime',
        'verbale_uploaded_at' => 'datetime',
        'verbale_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'uploaded' => 'bg-blue-100 text-blue-800',           // AGGIUNTO
            'uploaded_local' => 'bg-blue-100 text-blue-800',     // AGGIUNTO
            'processing' => 'bg-orange-100 text-orange-800',     // MODIFICATO
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
    
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'In Attesa',
            'uploaded' => 'Caricato su Drive',                   // AGGIUNTO
            'uploaded_local' => 'Caricato Localmente',           // AGGIUNTO
            'processing' => 'In Elaborazione',
            'completed' => 'Completato',
            'failed' => 'Fallito',
            default => 'Sconosciuto'
        };
    }
}