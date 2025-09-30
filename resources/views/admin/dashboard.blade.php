{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard - CO.DE Platform')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
                        <p class="text-sm text-gray-500">Gestione Upload ODV</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ $uploads->total() }} upload totali</span>
                    <a href="{{ route('dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-arrow-left mr-2"></i>Torna al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Filtri -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Filtri Upload</h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-center transition {{ !request()->filled('status') && !request()->filled('needs_processing') && !request()->filled('ready_to_send') ? 'ring-2 ring-gray-400' : '' }}">
                <div class="font-medium">Tutti</div>
                <div class="text-sm text-gray-500">{{ $uploads->total() }}</div>
            </a>
            <a href="{{ route('admin.dashboard', ['needs_processing' => 1]) }}" 
               class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-4 py-2 rounded-lg text-center transition {{ request()->filled('needs_processing') ? 'ring-2 ring-yellow-400' : '' }}">
                <div class="font-medium">Da Processare</div>
                <div class="text-sm text-yellow-600">{{ $stats['needs_verbale'] }}</div>
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'processing']) }}" 
               class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-4 py-2 rounded-lg text-center transition {{ request('status') === 'processing' ? 'ring-2 ring-blue-400' : '' }}">
                <div class="font-medium">In Elaborazione</div>
                <div class="text-sm text-blue-600">{{ $stats['processing'] }}</div>
            </a>
            <a href="{{ route('admin.dashboard', ['ready_to_send' => 1]) }}" 
               class="bg-green-100 hover:bg-green-200 text-green-800 px-4 py-2 rounded-lg text-center transition {{ request()->filled('ready_to_send') ? 'ring-2 ring-green-400' : '' }}">
                <div class="font-medium">Pronti Invio</div>
                <div class="text-sm text-green-600">{{ $stats['ready_to_send'] }}</div>
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'completed']) }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-center transition {{ request('status') === 'completed' ? 'ring-2 ring-gray-400' : '' }}">
                <div class="font-medium">Completati</div>
                <div class="text-sm text-gray-500">{{ $stats['completed'] }}</div>
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Da Processare</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['needs_verbale'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-cog text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">In Elaborazione</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['processing'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-paper-plane text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pronti Invio</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['ready_to_send'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <i class="fas fa-check text-gray-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completati</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Upload List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Upload Recenti</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data Upload
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Verbale
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cartella
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Azioni
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($uploads as $upload)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $upload->user->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $upload->user_email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $upload->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $upload->getStatusBadgeClass() }}">
                                    {{ $upload->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($upload->verbale_file_path)
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                    <span class="text-green-600">Caricato</span>
                                @else
                                    <span class="text-gray-400">Non disponibile</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($upload->drive_folder_link)
                                    <a href="{{ $upload->drive_folder_link }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                        <i class="fas fa-cloud mr-1"></i>Google Drive
                                    </a>
                                @elseif($upload->drive_folder_path)
                                    <span class="text-gray-600">
                                        <i class="fas fa-folder mr-1"></i>Locale
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.upload.show', $upload) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    Dettagli
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Nessun upload trovato
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($uploads->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $uploads->links() }}
            </div>
        @endif
    </div>
</div>
@endsection