<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            
            // Relazione utente
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_email');
            
            // Percorsi e metadata Google Drive
            $table->string('drive_folder_path')->nullable();
            $table->string('drive_folder_link')->nullable();
            $table->string('audio_file_id')->nullable();
            $table->json('documents_file_ids')->nullable();
            $table->string('notes_file_id')->nullable();
            
            // Contenuto verbale
            $table->text('notes')->nullable();
            
            // Status tracking con tutti gli stati necessari
            $table->enum('status', [
                'pending',
                'uploaded', 
                'uploaded_local',  // Fallback storage locale
                'processing', 
                'completed', 
                'failed'
            ])->default('pending');
            
            // Timestamp tracking del processo
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('processing_started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Timestamp standard Laravel
            $table->timestamps();
            
            // Indici per performance
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};