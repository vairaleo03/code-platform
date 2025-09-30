{{-- resources/views/admin/upload-detail.blade.php --}}
@extends('layouts.app')

@section('title', 'Dettaglio Upload #' . $upload->id . ' - Admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Upload #{{ $upload->id }}</h1>
                        <p class="text-sm text-gray-500">{{ $upload->user_email }}</p>
                    </div>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Torna alla Lista
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Upload Info -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informazioni Upload</h3>
                    
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $upload->user->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $upload->user_email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Data Upload</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $upload->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status Attuale</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $upload->getStatusBadgeClass() }}">
                                    {{ $upload->getStatusLabel() }}
                                </span>
                            </dd>
                        </div>
                        @if($upload->drive_folder_link)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Google Drive</dt>
                            <dd class="mt-1 text-sm">
                                <a href="{{ $upload->drive_folder_link }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                    <i class="fas fa-external-link-alt mr-1"></i>Apri Cartella
                                </a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Notes -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Note della Riunione</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-700 whitespace-pre-wrap break-words overflow-wrap-anywhere leading-relaxed">
                            {{ $upload->notes }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                
                <!-- Status Management -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gestione Status</h3>
                    
                    <form method="POST" action="{{ route('admin.upload.status', $upload) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Cambia Status
                            </label>
                            <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="pending" {{ $upload->status === 'pending' || $upload->status === 'uploaded' || $upload->status === 'uploaded_local' ? 'selected' : '' }}>In Attesa</option>
                                <option value="processing" {{ $upload->status === 'processing' ? 'selected' : '' }}>In Elaborazione</option>
                                <option value="completed" {{ $upload->status === 'completed' ? 'selected' : '' }}>Completato</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition">
                            Aggiorna Status
                        </button>
                    </form>
                </div>

                <!-- Verbale Upload -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gestione Verbale</h3>
                    
                    @if($upload->verbale_file_path)
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-green-600 mr-2"></i>
                                <div>
                                    <p class="text-sm font-medium text-green-900">Verbale Caricato</p>
                                    <p class="text-xs text-green-700">{{ $upload->verbale_uploaded_at?->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.upload.verbale', $upload) }}" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="verbale_file" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $upload->verbale_file_path ? 'Sostituisci Verbale' : 'Carica Verbale' }}
                            </label>
                            <input type="file" 
                                   name="verbale_file" 
                                   id="verbale_file" 
                                   accept=".pdf,.doc,.docx"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX - Max 10MB</p>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition">
                            <i class="fas fa-upload mr-2"></i>Carica Verbale
                        </button>
                    </form>
                    
                    @if($upload->verbale_file_path)
                        <form method="POST" action="{{ route('admin.upload.send', $upload) }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition"
                                    onclick="return confirm('Sei sicuro di voler inviare il verbale al cliente?')">
                                <i class="fas fa-paper-plane mr-2"></i>Invia al Cliente
                            </button>
                        </form>
                        
                        @if($upload->verbale_sent_at)
                            <div class="mt-3 p-2 bg-green-50 border border-green-200 rounded text-center">
                                <p class="text-xs text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Inviato il {{ $upload->verbale_sent_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
                
                <!-- Timeline -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Upload creato</p>
                                <p class="text-xs text-gray-500">{{ $upload->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($upload->processing_started_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 bg-yellow-600 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Elaborazione iniziata</p>
                                <p class="text-xs text-gray-500">{{ $upload->processing_started_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($upload->verbale_uploaded_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 bg-purple-600 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Verbale caricato</p>
                                <p class="text-xs text-gray-500">{{ $upload->verbale_uploaded_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($upload->completed_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Completato</p>
                                <p class="text-xs text-gray-500">{{ $upload->completed_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($upload->verbale_sent_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-800 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Verbale inviato</p>
                                <p class="text-xs text-gray-500">{{ $upload->verbale_sent_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection