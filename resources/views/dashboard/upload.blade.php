@extends('layouts.app')

@section('title', 'Dashboard - CO.DE Platform')

@section('head')
<style>
.code-gradient { 
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); 
}

.code-blue { 
    color: #1e3a8a; 
}

.code-dark-blue { 
    background-color: #1e40af; 
}

.code-light-blue { 
    color: #3b82f6; 
}

.code-accent { 
    color: #f97316; 
}

.bg-code-blue { 
    background-color: #1e3a8a; 
}

.bg-code-accent { 
    background-color: #f97316; 
}

.bg-code-light-blue { 
    background-color: #3b82f6; 
}

/* Standardized card dimensions */
.dashboard-card {
    width: 100%;
    max-width: 48rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.dashboard-card.assistant-card {
    border-top-left-radius: 0.125rem;
}

.dashboard-card.upload-card {
    border: 2px dashed #d1d5db;
    transition: border-color 0.3s ease;
}

.dashboard-card.upload-card:hover {
    border-color: #3b82f6;
}

.dashboard-card.confirmation-card {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
}

.dashboard-card.summary-card {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
}

/* Step indicator consistency */
.step-indicator {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Auto-scroll smooth animation */
.auto-scroll-target {
    scroll-margin-top: 2rem;
}

html {
    scroll-behavior: smooth;
}

/* Upload Progress Bar - Compact */
.upload-progress-compact {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: 1px solid #e2e8f0;
    animation: slideInDown 0.3s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.upload-header-compact {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.upload-title-compact {
    font-size: 1rem;
    font-weight: 600;
    color: #1e40af;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.upload-progress-container-compact {
    margin-bottom: 0.75rem;
}

.upload-progress-bar-compact {
    width: 100%;
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.upload-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 6px;
    transition: width 0.3s ease;
    position: relative;
}

.upload-progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.upload-stats-compact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
}

.upload-stat-compact {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #475569;
}

.upload-stat-value-compact {
    font-weight: 600;
    color: #1e40af;
}

.upload-percentage-compact {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e40af;
}

.upload-status-compact {
    color: #059669;
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Animations */
@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in {
    from { 
        opacity: 0; 
    }
    to { 
        opacity: 1; 
    }
}

.animate-slide-in {
    animation: slide-in 0.5s ease-out;
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

/* Mobile adjustments */
@media (max-width: 768px) {
    .pl-14 {
        padding-left: 2rem;
    }
    
    .max-w-2xl {
        max-width: 100%;
    }
    
    .flex.items-start.space-x-4 {
        flex-direction: column;
        gap: 1rem;
    }
    
    .flex.items-start.space-x-4 > div:first-child {
        align-self: flex-start;
    }
    
    .rounded-tl-sm {
        border-top-left-radius: 1rem;
    }
    
    .upload-progress-compact {
        padding: 0.75rem;
    }
    
    .upload-stats-compact {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}

@media (max-width: 640px) {
    .pl-14 {
        padding-left: 0;
        margin-top: 1rem;
    }
    
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .p-6 {
        padding: 1rem;
    }
    
    .text-right {
        text-align: center;
    }
}
</style>
@endsection

@section('scripts')
<script>
// CO.DE Platform Upload Scripts - Inline Version per compatibilità internet
function chatUploadFlow() {
    return {
        // State management
        currentStep: 0,
        audioFile: null,
        audioUploaded: false,
        audioUploading: false,
        documents: [],
        documentsCompleted: false,
        showDocumentsUpload: false,
        notesCompleted: false,
        notesCharCount: 0,
        submitting: false,
        
        // Form data
        meetingDetails: {
            date: '',
            location: '',
            startTime: '',
            endTime: ''
        },
        
        odvMembers: [
            { name: '', role: '' }
        ],
        
        otherAttendees: [],
        
        notesContent: '',
        structuredNotes: '',
        
        // Upload tracking
        showUploadModal: false,
        uploadProgress: 0,
        uploadSpeed: 0,
        estimatedTime: 0,
        uploadedBytes: 0,
        totalBytes: 0,
        startTime: null,
        
        // Computed properties - methods that return values for templates
        checkTimeValidity() {
            if (!this.meetingDetails.startTime || !this.meetingDetails.endTime) {
                return true; // No validation error if times not set yet
            }
            return this.meetingDetails.endTime >= this.meetingDetails.startTime;
        },
        
        canConfirmNotes() {
            return this.meetingDetails.date && 
                   this.meetingDetails.location && 
                   this.meetingDetails.startTime && 
                   this.meetingDetails.endTime && 
                   this.checkTimeValidity() &&
                   this.odvMembers.some(m => m.name && m.role) &&
                   this.notesContent.length >= 100;
        },
        
        // Format functions
        formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        formatTime(seconds) {
            if (seconds < 60) return Math.round(seconds) + 's';
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.round(seconds % 60);
            return minutes + 'm ' + remainingSeconds + 's';
        },
        
        formatSpeed(mbps) {
            if (mbps < 1) return (mbps * 1024).toFixed(1) + ' KB/s';
            return mbps.toFixed(1) + ' MB/s';
        },
        
        formatFileSize(bytes) {
            if (!bytes) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        // Auto-scroll functions
        scrollToStep(stepNumber) {
            this.$nextTick(() => {
                const targetId = `#step-${stepNumber}`;
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    setTimeout(() => {
                        const offset = 80;
                        const elementPosition = targetElement.offsetTop - offset;
                        
                        window.scrollTo({
                            top: elementPosition,
                            behavior: 'smooth'
                        });
                    }, 400);
                }
            });
        },
        
        // Initialization
        startFlow() {
            setTimeout(() => {
                this.currentStep = 1;
                this.scrollToStep(1);
            }, 1000);
        },
        
        // Audio handling
        handleAudioUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.audioFile = file;
                this.audioUploading = true;
                setTimeout(() => {
                    this.audioUploading = false;
                    this.audioUploaded = true;
                    setTimeout(() => {
                        this.currentStep = 2;
                        this.scrollToStep(2);
                    }, 500);
                }, 1500);
            }
        },
        
        // Documents handling
        openDocuments() {
            this.showDocumentsUpload = true;
            
            // Use Alpine.js $nextTick to wait for DOM update
            this.$nextTick(() => {
                // Wait for Alpine.js transition to complete
                setTimeout(() => {
                    // Find the documents upload area by ID
                    const documentsArea = document.getElementById('documents-upload-area');
                    
                    if (documentsArea) {
                        // Scroll to the upload area and position it well in view
                        documentsArea.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start' // Position at top of viewport
                        });
                        
                        // Additional scroll down to ensure the upload area is fully visible
                        setTimeout(() => {
                            window.scrollBy({
                                top: 150, // Scroll down to show entire upload area
                                behavior: 'smooth'
                            });
                        }, 800);
                    }
                }, 500); // Wait longer for x-transition to complete
            });
        },
        
        handleDocumentsUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (!this.documents.find(d => d.name === file.name)) {
                    this.documents.push(file);
                }
            });
        },
        
        removeDocument(index) {
            this.documents.splice(index, 1);
        },
        
        clearAllDocuments() {
            this.documents = [];
        },
        
        confirmDocuments() {
            if (this.documents.length === 0) {
                alert('È necessario caricare almeno un documento di supporto per procedere.');
                return;
            }
            this.documentsCompleted = true;
            this.showDocumentsUpload = false;
            this.copyDocumentsToForm();
            setTimeout(() => {
                this.currentStep = 3;
                this.scrollToStep(3);
            }, 500);
        },
        
        copyDocumentsToForm() {
            const hiddenInput = this.$refs.hiddenDocsInput;
            if (hiddenInput && this.documents.length > 0) {
                // Svuota eventuali file precedenti
                hiddenInput.value = '';
                // Crea un nuovo FileList
                const dt = new DataTransfer();
                this.documents.forEach(file => dt.items.add(file));
                hiddenInput.files = dt.files;
            }
        },
        
        // ODV Members management
        addOdvMember() {
            this.odvMembers.push({ name: '', role: '' });
        },
        
        removeOdvMember(index) {
            if (this.odvMembers.length > 1) {
                this.odvMembers.splice(index, 1);
            }
        },
        
        // Other attendees management
        addOtherAttendee() {
            this.otherAttendees.push({ name: '', role: '' });
        },
        
        removeOtherAttendee(index) {
            this.otherAttendees.splice(index, 1);
        },
        
        // Notes handling
        updateNotesCharCount() {
            this.notesCharCount = this.notesContent.length;
        },
        
        confirmNotes() {
            if (!this.checkTimeValidity()) {
                alert('L\'ora di fine non può essere precedente all\'ora di inizio della riunione.');
                return;
            }
            if (!this.canConfirmNotes()) {
                alert('Completa tutti i campi obbligatori prima di procedere.');
                return;
            }
            // COPIA IL CONTENUTO DELLE NOTE NEL CAMPO CHE VA NEL FORM
            this.structuredNotes = this.notesContent;
            this.notesCompleted = true;
            setTimeout(() => {
                this.currentStep = 4;
                setTimeout(() => {
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });
                }, 400);
            }, 500);
        },
        
        // Form submission
        submitForm() {
            if (this.submitting) return;
            this.submitting = true;
            
            if (!this.audioUploaded || !this.documentsCompleted || !this.notesCompleted) {
                alert('Completa tutti i passaggi prima di inviare.');
                this.submitting = false;
                return;
            }
            
            // Show upload modal
            this.showUploadModal = true;
            this.uploadProgress = 0;
            this.uploadedBytes = 0;
            this.totalBytes = 1000000; // 1MB simulato
            this.startTime = Date.now();
            
            // Auto-scroll to upload progress bar
            setTimeout(() => {
                const uploadBar = document.querySelector('.upload-progress-compact');
                if (uploadBar) {
                    uploadBar.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }, 300);
            
            // Simulate upload
            const uploadInterval = setInterval(() => {
                this.uploadedBytes += 50000; // 50KB per step
                this.uploadProgress = Math.round((this.uploadedBytes / this.totalBytes) * 100);
                this.uploadSpeed = 2.5; // MB/s simulato
                this.estimatedTime = (this.totalBytes - this.uploadedBytes) / (this.uploadSpeed * 1024 * 1024);
                
                if (this.uploadedBytes >= this.totalBytes) {
                    clearInterval(uploadInterval);
                    setTimeout(() => {
                        this.showUploadModal = false;
                        // Submit form for real
                        this.$refs.uploadForm?.submit();
                    }, 2000);
                }
            }, 100);
        }
    }
}
</script>
@endsection

@section('content')
<!-- Header -->
<header class="code-gradient shadow-lg">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-gavel text-blue-800 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-white text-2xl font-bold">CO.DE Platform</h1>
                    <p class="text-blue-100 text-sm">Compliance & Development</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4 text-white">
                <div class="text-right">
                    <p class="text-sm opacity-90">Piano {{ ucfirst(auth()->user()->plan) }}</p>
                    <p class="font-semibold">{{ auth()->user()->verbali_remaining }} verbali rimanenti</p>
                </div>
                
                {{-- Link Admin Panel - Solo per utenti autorizzati --}}
                @if(in_array(auth()->user()->email, ['admin@code.example', 'marco@code-platform.it', 'amministratore@lrsec.it']))
                    <a href="{{ route('admin.dashboard') }}" 
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-shield-alt mr-1"></i>Admin
                    </a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 animate-fade-in">
                {{ session('error') }}
            </div>
        @endif

        <!-- Chat Interface -->
        <div class="space-y-6" x-data="{
            ...chatUploadFlow(),
            // Stati per validazione inline
            startTimeTouched: false,
            endTimeTouched: false,
            // Validazione computed
            get isTimeInvalid() {
                return this.startTimeTouched && this.endTimeTouched && 
                       this.meetingDetails.startTime && this.meetingDetails.endTime &&
                       this.meetingDetails.endTime <= this.meetingDetails.startTime;
            }
        }" x-init="startFlow()">
            
            <!-- Welcome Message -->
            <div class="flex items-start space-x-4 animate-slide-in auto-scroll-target" x-show="currentStep >= 0">
                <div class="step-indicator bg-blue-800 text-white">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="dashboard-card assistant-card">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="font-semibold text-blue-800">Assistente CO.DE</h3>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Sistema</span>
                    </div>
                    <p class="text-gray-800 leading-relaxed">
                        Benvenuto nella nuova sessione ODV. Procederemo step-by-step per creare il suo verbale professionale.
                        <br><br>
                        <strong>Processo previsto:</strong><br>
                        - Caricamento registrazione audio<br>
                        - Documentazione di supporto obbligatoria<br>
                        - Note e informazioni specifiche<br>
                        - Invio per elaborazione qualificata
                    </p>
                </div>
            </div>

            <!-- Step 1: Audio Upload Request -->
            <div id="step-1" class="flex items-start space-x-4 animate-slide-in auto-scroll-target" 
                 x-show="currentStep >= 1" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="step-indicator bg-blue-800 text-white">
                    <i class="fas fa-microphone"></i>
                </div>
                <div class="dashboard-card assistant-card">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="font-semibold text-blue-800">Assistente CO.DE</h3>
                        <span class="text-xs text-blue-800 bg-blue-100 px-2 py-1 rounded-full">Step 1/3</span>
                    </div>
                    <p class="text-gray-800 mb-4">
                        Iniziamo con la registrazione audio della riunione ODV. Puo caricare file nei formati:
                        <span class="font-medium">MP3, WAV, M4A, AAC</span> (massimo 50MB).
                    </p>
                </div>
            </div>

            <!-- Step 1: Audio Upload Area -->
            <div class="pl-14" x-show="currentStep >= 1 && !audioUploaded">
                <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data" x-ref="uploadForm">
                    @csrf
                    
                    <div class="dashboard-card upload-card cursor-pointer"
                         @click="$refs.audioInput.click()">
                        
                        <input type="file" 
                               x-ref="audioInput" 
                               accept=".mp3,.wav,.m4a,.aac" 
                               class="hidden"
                               name="audio_file"
                               @change="handleAudioUpload"
                               required>
                        
                        <div x-show="!audioFile" class="text-center py-8">
                            <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto flex items-center justify-center mb-4">
                                <i class="fas fa-cloud-upload-alt text-blue-800 text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-800 mb-2">Carica Registrazione Audio</h4>
                            <p class="text-gray-600 text-sm">Clicchi qui o trascini il file audio della riunione</p>
                            <p class="text-gray-500 text-xs mt-2">Formati supportati: MP3, WAV, M4A, AAC - Max 50MB</p>
                        </div>

                        <div x-show="audioFile && !audioUploading" class="text-center py-4">
                            <div class="flex items-center justify-center space-x-3">
                                <i class="fas fa-file-audio text-green-500 text-2xl"></i>
                                <div>
                                    <p class="font-medium text-gray-800" x-text="audioFile?.name"></p>
                                    <p class="text-sm text-gray-600" x-text="formatFileSize(audioFile?.size)"></p>
                                </div>
                            </div>
                        </div>

                        <div x-show="audioUploading" class="text-center py-4">
                            <div class="flex items-center justify-center space-x-3">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-800"></div>
                                <p class="text-blue-800 font-medium">Verifica file in corso...</p>
                            </div>
                        </div>
                    </div>
                    
                    @error('audio_file')
                        <p class="text-red-500 text-sm mt-2 pl-4">{{ $message }}</p>
                    @enderror

                    <!-- Hidden inputs for later steps -->
                    <input type="hidden" name="notes" x-model="structuredNotes">
                    <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.txt" class="hidden" x-ref="hiddenDocsInput">
                </form>
            </div>

            <!-- Step 1: Audio Confirmation -->
            <div class="flex items-start space-x-4 animate-slide-in auto-scroll-target" 
                 x-show="audioUploaded && currentStep >= 2"
                 x-transition:enter="transition ease-out duration-500 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="step-indicator bg-green-500 text-white">
                    <i class="fas fa-check"></i>
                </div>
                <div class="dashboard-card confirmation-card">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="font-semibold text-green-700">File Audio Confermato</h3>
                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Completato</span>
                    </div>
                    <p class="text-green-800">
                        Registrazione audio caricata correttamente. Procediamo con la documentazione di supporto obbligatoria.
                    </p>
                </div>
            </div>

            <!-- Step 2: Documents Request -->
            <div id="step-2" class="flex items-start space-x-4 animate-slide-in auto-scroll-target" 
                 x-show="currentStep >= 2"
                 x-transition:enter="transition ease-out duration-500 delay-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="step-indicator bg-orange-500 text-white">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="dashboard-card assistant-card">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="font-semibold text-blue-800">Assistente CO.DE</h3>
                        <span class="text-xs text-orange-600 bg-orange-100 px-2 py-1 rounded-full">Step 2/3</span>
                    </div>
                    <p class="text-gray-800 mb-4">
                        Bisogna allegare la documentazione di supporto <span class="text-red-500 font-semibold">(obbligatorio)</span>:
                        DVR, certificati, nomine, policy aziendali o altro materiale rilevante per il verbale.
                    </p>
                    <div class="flex space-x-3">
                        <button type="button" 
                                @click="openDocuments()"
                                class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition">
                            <i class="fas fa-paperclip mr-2"></i>Carica Documenti Obbligatori
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Documents Upload Area -->
            <div id="documents-upload-area" class="pl-14" x-show="currentStep >= 2 && showDocumentsUpload && !documentsCompleted" x-transition>
                <div class="dashboard-card upload-card cursor-pointer"
                     @click="$refs.docsInput.click()">
                    
                    <input type="file" 
                           x-ref="docsInput" 
                           accept=".pdf,.doc,.docx,.txt" 
                           class="hidden"
                           multiple
                           @change="handleDocumentsUpload">
                    
                    <div x-show="documents.length === 0" class="text-center py-6">
                        <div class="w-14 h-14 bg-orange-100 rounded-full mx-auto flex items-center justify-center mb-3">
                            <i class="fas fa-file-plus text-orange-500 text-xl"></i>
                        </div>
                        <h4 class="font-medium text-gray-800 mb-1">Carica Documenti Obbligatori</h4>
                        <p class="text-gray-600 text-sm">PDF, DOC, DOCX, TXT (max 10MB per file)</p>
                        <p class="text-gray-500 text-xs mt-1">Puoi selezionare piu file contemporaneamente</p>
                    </div>

                    <div x-show="documents.length > 0" class="space-y-3">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-medium text-gray-700">Documenti selezionati:</h4>
                            <button type="button"
                                    @click.stop="$refs.docsInput.click()"
                                    class="text-orange-500 hover:text-orange-600 text-sm font-medium flex items-center">
                                <i class="fas fa-plus mr-1"></i>Aggiungi altri
                            </button>
                        </div>
                        
                        <div class="max-h-64 overflow-y-auto space-y-2">
                            <template x-for="(doc, index) in documents" :key="index">
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        <i class="fas fa-file text-orange-500 flex-shrink-0"></i>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium truncate" x-text="doc.name" :title="doc.name"></p>
                                            <p class="text-xs text-gray-500" x-text="formatFileSize(doc.size)"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeDocument(index)" class="text-red-500 hover:text-red-700 p-1 flex-shrink-0 ml-2">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                        
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="button" 
                                    @click.stop="confirmDocuments()"
                                    class="flex-1 bg-orange-500 text-white py-2 px-4 rounded-lg font-medium hover:bg-orange-600 transition">
                                    Conferma <span x-text="documents.length"></span> Documento/i
                                </button>
                                <button type="button" 
                                        @click="clearAllDocuments()"
                                        class="sm:flex-none bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 transition">
                                    Rimuovi Tutti
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Documents Confirmation -->
            <div class="flex items-start space-x-4 animate-slide-in auto-scroll-target" 
                 x-show="documentsCompleted && currentStep >= 3"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="step-indicator bg-green-500 text-white">
                    <i class="fas fa-check"></i>
                </div>
                <div class="dashboard-card confirmation-card">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="font-semibold text-green-700">Documentazione Confermata</h3>
                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Completato</span>
                    </div>
                    <p class="text-green-800">
                        <span x-text="documents.length"></span> documento/i caricato/i con successo.
                        Procediamo con le note della riunione.
                    </p>
                </div>
            </div>

            <!-- Step 3: Notes Request -->
            <div id="step-3" class="flex items-start space-x-4 animate-slide-in auto-scroll-target" 
                 x-show="currentStep >= 3"
                 x-transition:enter="transition ease-out duration-500 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="step-indicator bg-green-600 text-white">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="dashboard-card assistant-card">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="font-semibold text-blue-800">Assistente CO.DE</h3>
                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Step 3/3</span>
                    </div>
                    <p class="text-gray-800 mb-4">
                        Compili le informazioni strutturate della riunione ODV. I campi contrassegnati con 
                        <span class="text-red-500">*</span> sono obbligatori per la conformita normativa.
                    </p>
                </div>
            </div>

            <!-- Step 3: Structured Form -->
            <div class="pl-14" x-show="currentStep >= 3 && !notesCompleted">
                <div class="dashboard-card">
                    
                    <!-- Meeting Details Section -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-orange-500"></i>
                            Dettagli Riunione
                        </h4>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data Riunione <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       x-model="meetingDetails.date"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                       required>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Luogo Riunione <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       x-model="meetingDetails.location"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                       placeholder="es. Sala Riunioni, Via Roma 123, Milano"
                                       required>
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ora Inizio <span class="text-red-500">*</span>
                                </label>
                                <input type="time" 
                                       x-model="meetingDetails.startTime"
                                       @blur="startTimeTouched = true"
                                       @change="startTimeTouched = true"
                                       :class="isTimeInvalid ? 'w-full border-2 border-red-400 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-400 focus:border-transparent bg-red-50' : 'w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent'"
                                       required>
                            </div>

                            <!-- End Time -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ora Fine <span class="text-red-500">*</span>
                                </label>
                                <input type="time" 
                                       x-model="meetingDetails.endTime"
                                       @blur="endTimeTouched = true"
                                       @change="endTimeTouched = true"
                                       :class="isTimeInvalid ? 'w-full border-2 border-red-400 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-400 focus:border-transparent bg-red-50' : 'w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent'"
                                       required>
                                
                                <!-- Messaggio di errore per validazione tempo -->
                                <div x-show="isTimeInvalid" 
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95"
                                    class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                        <p class="text-red-700 text-sm font-medium">
                                            L'ora di fine deve essere successiva all'ora di inizio
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ODV Components Section -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-users mr-2 text-orange-500"></i>
                            Componenti Organismo di Vigilanza
                        </h4>
                        
                        <div class="space-y-4">
                            <template x-for="(member, index) in odvMembers" :key="index">
                                <div class="flex flex-col sm:flex-row sm:items-end gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nome e Cognome <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               x-model="member.name"
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                               placeholder="Mario Rossi"
                                               required>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Ruolo nell'ODV <span class="text-red-500">*</span>
                                        </label>
                                        <select x-model="member.role"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                                            <option value="">Seleziona ruolo</option>
                                            <option value="Presidente">Presidente</option>
                                            <option value="Componente">Componente</option>
                                            <option value="Componente esterno">Componente esterno</option>
                                        </select>
                                    </div>
                                    <div class="flex-none">
                                        <button type="button" 
                                                @click="removeOdvMember(index)"
                                                x-show="odvMembers.length > 1"
                                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <button type="button" 
                                    @click="addOdvMember()"
                                    class="w-full border-2 border-dashed border-blue-300 text-blue-800 py-3 rounded-lg hover:bg-blue-50 transition flex items-center justify-center">
                                <i class="fas fa-plus mr-2"></i>
                                Aggiungi Componente ODV
                            </button>
                        </div>
                    </div>

                    <!-- Other Attendees Section -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-user-tie mr-2 text-orange-500"></i>
                            Altre Funzioni Aziendali Presenti
                        </h4>
                        
                        <div class="space-y-4">
                            <template x-for="(attendee, index) in otherAttendees" :key="index">
                                <div class="flex flex-col sm:flex-row sm:items-end gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nome e Cognome
                                        </label>
                                        <input type="text" 
                                               x-model="attendee.name"
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                               placeholder="Mario Rossi">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Funzione Aziendale
                                        </label>
                                        <select x-model="attendee.role"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                                            <option value="">Seleziona funzione</option>
                                            <option value="Datore di Lavoro">Datore di Lavoro</option>
                                            <option value="RSPP">RSPP</option>
                                            <option value="RLS">RLS</option>
                                            <option value="Medico Competente">Medico Competente</option>
                                            <option value="HR Manager">HR Manager</option>
                                            <option value="Dirigente">Dirigente</option>
                                            <option value="Preposto">Preposto</option>
                                            <option value="Altro">Altro</option>
                                        </select>
                                    </div>
                                    <div class="flex-none">
                                        <button type="button" 
                                                @click="removeOtherAttendee(index)"
                                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <button type="button" 
                                    @click="addOtherAttendee()"
                                    class="w-full border-2 border-dashed border-gray-300 text-gray-600 py-3 rounded-lg hover:border-gray-400 hover:text-gray-700 transition flex items-center justify-center">
                                <i class="fas fa-plus mr-2"></i>
                                Aggiungi Partecipante
                            </button>
                        </div>
                    </div>

                    <!-- Meeting Notes Section -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-orange-500"></i>
                            Note e Contenuti della Riunione
                        </h4>
                        
                        <textarea 
                            x-model="notesContent"
                            @input="updateNotesCharCount()"
                            rows="8" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none"
                            placeholder="ARGOMENTI TRATTATI:&#10;- Revisione DVR aziendale secondo D.Lgs 81/08&#10;- Analisi infortuni e near miss ultimo trimestre&#10;- Valutazione efficacia formazione erogata&#10;- Aggiornamento procedure operative di sicurezza&#10;&#10;CRITICITA EMERSE:&#10;- [Descrizione dettagliata delle criticita individuate]&#10;- [Analisi delle cause e dei fattori di rischio]&#10;&#10;AZIONI CORRETTIVE CONCORDATE:&#10;- [Descrizione azioni con responsabili e tempistiche]&#10;- [Follow-up e modalita di verifica]&#10;&#10;PROSSIMA RIUNIONE:&#10;- Data prevista per il follow-up"
                            required></textarea>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-4 gap-4">
                            <p class="text-sm text-gray-500">
                                <span x-text="notesCharCount"></span> caratteri
                                <span x-show="notesCharCount < 100" class="text-red-500">(minimo 100 per un verbale completo)</span>
                            </p>
                            <button type="button" 
                                @click="confirmNotes()"
                                :disabled="!canConfirmNotes() || isTimeInvalid"
                                :class="canConfirmNotes() && !isTimeInvalid ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                class="px-6 py-2 rounded-lg font-medium transition w-full sm:w-auto">
                            Conferma Dati Riunione
                        </button>
                        </div>
                    </div>
                    
                    @error('notes')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Step 3: Notes Confirmation -->
            <div class="flex items-start space-x-4 animate-slide-in auto-scroll-target" 
                 x-show="notesCompleted && currentStep >= 4"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="step-indicator bg-green-500 text-white">
                    <i class="fas fa-check"></i>
                </div>
                <div class="dashboard-card confirmation-card">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="font-semibold text-green-700">Note Confermate</h3>
                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Completato</span>
                    </div>
                    <p class="text-green-800">
                        Informazioni della riunione acquisite correttamente. Tutti i dati sono pronti per l'elaborazione.
                    </p>
                </div>
            </div>

            <!-- Final Step: Submit -->
            <div class="flex items-start space-x-4 animate-slide-in auto-scroll-target" 
                 x-show="currentStep >= 4"
                 x-transition:enter="transition ease-out duration-500 delay-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="step-indicator bg-blue-800 text-white">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="dashboard-card summary-card">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="font-semibold text-blue-800">Riepilogo ed Invio</h3>
                        <span class="text-xs text-blue-800 bg-blue-100 px-2 py-1 rounded-full">Pronto</span>
                    </div>
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-700">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Registrazione audio caricata</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-700">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span x-text="documents.length + ' documento/i allegato/i'"></span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-700">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Note della riunione complete</span>
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <h4 class="font-medium text-blue-900 mb-2">Processo di Elaborazione</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>- Revisione manuale qualificata</li>
                            <li>- Tempo di elaborazione: 24-48 ore lavorative</li>
                            <li>- Notifica di completamento via email</li>
                            <li>- Verbale conforme D.Lgs 231/2001</li>
                        </ul>
                    </div>
                    <button type="button" 
                            @click="submitForm()"
                            :disabled="submitting"
                            class="w-full bg-blue-800 hover:bg-blue-900 disabled:bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold transition duration-300 flex items-center justify-center">
                        <span x-show="!submitting" class="flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Invia per Elaborazione Professionale
                        </span>
                        <span x-show="submitting" class="flex items-center">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                            Invio in corso...
                        </span>
                    </button>
                    
                    <!-- Upload Progress Bar - Compact -->
                    <div x-show="showUploadModal" class="upload-progress-compact" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <div class="upload-header-compact">
                            <div class="upload-title-compact">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Invio in corso
                            </div>
                            <div class="upload-percentage-compact" x-text="uploadProgress + '%'"></div>
                        </div>
                        
                        <div class="upload-progress-container-compact">
                            <div class="upload-progress-bar-compact">
                                <div class="upload-progress-fill" :style="{ width: uploadProgress + '%' }"></div>
                            </div>
                        </div>
                        
                        <div x-show="uploadProgress < 100" class="upload-stats-compact">
                            <div class="upload-stat-compact">
                                <i class="fas fa-tachometer-alt"></i>
                                <span x-text="formatSpeed(uploadSpeed)"></span>
                            </div>
                            <div class="upload-stat-compact">
                                <i class="fas fa-clock"></i>
                                <span x-text="estimatedTime > 0 ? formatTime(estimatedTime) : '--'"></span>
                            </div>
                            <div class="upload-stat-compact">
                                <i class="fas fa-database"></i>
                                <span x-text="formatBytes(uploadedBytes) + ' / ' + formatBytes(totalBytes)"></span>
                            </div>
                        </div>
                        
                        <div x-show="uploadProgress >= 100" class="upload-status-compact">
                            <i class="fas fa-check-circle"></i>
                            Upload completato - Elaborazione in corso...
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection