// CO.DE Platform Upload Scripts - Fixed Version

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
        
        // Upload progress calculation
        calculateUploadStats() {
            if (!this.startTime) return;
            
            const now = Date.now();
            const elapsedSeconds = (now - this.startTime) / 1000;
            
            if (elapsedSeconds > 0) {
                // Calculate speed in MB/s
                this.uploadSpeed = (this.uploadedBytes / (1024 * 1024)) / elapsedSeconds;
                
                // Calculate estimated time remaining
                if (this.uploadSpeed > 0) {
                    const remainingBytes = this.totalBytes - this.uploadedBytes;
                    this.estimatedTime = remainingBytes / (this.uploadSpeed * 1024 * 1024);
                }
            }
        },
        
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
        
        // Auto-scroll to specific step sections (1/3, 2/3, 3/3)
        scrollToStep(stepNumber) {
            this.$nextTick(() => {
                const targetId = `#step-${stepNumber}`;
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    setTimeout(() => {
                        // Calculate offset to account for fixed progress bar
                        const offset = 80; // Space for progress bar and status panel
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
        
        // Auto-scroll functionality
        scrollToCurrentStep() {
            this.$nextTick(() => {
                const stepElements = document.querySelectorAll('.auto-scroll-target');
                const currentStepElement = stepElements[this.currentStep - 1];
                if (currentStepElement) {
                    setTimeout(() => {
                        currentStepElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 300);
                }
            });
        },
        
        // Audio handling
        handleAudioUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.audioFile = file;
                this.audioUploading = true;
                
                // Simulate upload validation
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
            
            // Copy documents to hidden form input
            this.copyDocumentsToForm();
            
            setTimeout(() => {
                this.currentStep = 3;
                this.scrollToStep(3);
            }, 500);
        },
        
        copyDocumentsToForm() {
            const hiddenInput = this.$refs.hiddenDocsInput;
            if (hiddenInput && this.documents.length > 0) {
                // Clear existing files
                hiddenInput.value = '';
                
                // Create a new FileList-like object
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
            
            // Build structured notes
            this.buildStructuredNotes();
            
            this.notesCompleted = true;
            setTimeout(() => {
                this.currentStep = 4;
                // Auto-scroll to bottom of page for final step
                setTimeout(() => {
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });
                }, 400);
            }, 500);
        },
        
        buildStructuredNotes() {
            // Formatta i dati in formato JSON strutturato
            const notes = {
                meeting_details: this.meetingDetails,
                odv_members: this.odvMembers.filter(m => m.name && m.role),
                other_attendees: this.otherAttendees.filter(a => a.name || a.role),
                notes_content: this.notesContent
            };
            
            // Aggiorna il campo hidden con i dati JSON
            this.structuredNotes = JSON.stringify(notes);
            console.log("Note strutturate preparate:", this.structuredNotes);
        },
        
        // Form submission with upload progress
        submitForm() {
            if (this.submitting) return;
            this.submitting = true;

            if (!this.audioUploaded || !this.documentsCompleted || !this.notesCompleted) {
                alert('Completa tutti i passaggi prima di inviare.');
                this.submitting = false;
                return;
            }

            // Aggiorna input hidden con i documenti
            this.copyDocumentsToForm();

            // Calcola dimensione totale
            this.totalBytes = 0;
            if (this.audioFile) this.totalBytes += this.audioFile.size;
            this.documents.forEach(file => this.totalBytes += file.size);

            this.showUploadModal = true;
            this.uploadProgress = 0;
            this.uploadedBytes = 0;
            this.startTime = Date.now();

            setTimeout(() => {
                const uploadBar = document.querySelector('.upload-progress-compact');
                if (uploadBar) {
                    uploadBar.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }, 300);

            // Attendi che Alpine aggiorni il DOM prima di simulare l'upload
            this.$nextTick(() => {
                this.simulateUpload();
            });
        },
        
        // Simulate realistic upload progress
        simulateUpload() {
            const uploadInterval = setInterval(() => {
                // Simulate network fluctuations
                const baseSpeed = 2 + Math.random() * 3;
                const fluctuation = 0.8 + Math.random() * 0.4;
                const currentSpeed = baseSpeed * fluctuation;
                
                const bytesToAdd = (currentSpeed * 1024 * 1024 * 0.1);
                
                this.uploadedBytes = Math.min(this.uploadedBytes + bytesToAdd, this.totalBytes);
                this.uploadProgress = Math.round((this.uploadedBytes / this.totalBytes) * 100);
                
                this.calculateUploadStats();
                
                if (this.uploadedBytes >= this.totalBytes) {
                    clearInterval(uploadInterval);
                    
                    // CORREZIONE CRUCIALE: Usa setTimeout ma assicurati che il form venga inviato
                    setTimeout(() => {
                        console.log("Upload simulato completato, invio il form...");
                        // Assicurati che uploadForm sia accessibile e definito
                        if (this.$refs.uploadForm) {
                            this.$refs.uploadForm.submit();
                        } else {
                            console.error("Riferimento al form mancante!");
                            alert("Errore nell'invio del form. Ricarica la pagina e riprova.");
                        }
                    }, 2000);
                }
            }, 100);
        },
        
        // Utility functions
        formatFileSize(bytes) {
            if (!bytes) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }
}