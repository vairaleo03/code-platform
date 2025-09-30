// CO.DE Platform Authentication Scripts

// Plan selection functionality - ORIGINALE (migliore)
function initPlanSelection() {
    return {
        selectedPlan: 'professional',
        
        selectPlan(plan) {
            this.selectedPlan = plan;
            // Update hidden input if exists
            const hiddenInput = document.querySelector('input[name="plan"]');
            if (hiddenInput) {
                hiddenInput.value = plan;
            }
            console.log('Piano selezionato:', plan); // Debug
        },
        
        isPlanSelected(plan) {
            return this.selectedPlan === plan;
        },
        
        getPlanClass(plan) {
            return this.isPlanSelected(plan) 
                ? 'border-plan-selected' 
                : 'border-plan-default plan-card';
        }
    }
}

// Form validation
function validateRegistrationForm() {
    const form = document.querySelector('form');
    const password = document.querySelector('#password');
    const confirmPassword = document.querySelector('#password_confirmation');
    const terms = document.querySelector('#terms');
    
    if (password && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (this.value !== password.value) {
                this.setCustomValidity('Le password non corrispondono');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            if (terms && !terms.checked) {
                e.preventDefault();
                alert('È necessario accettare i termini di servizio per continuare.');
                return false;
            }
            
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Le password non corrispondono.');
                return false;
            }
        });
    }
}

// Gestione campo notes per upload form
function initNotesBinding() {
    const form = document.querySelector('#uploadForm');
    const notesField = document.querySelector('#notesField');
    const confirmNotesBtn = document.querySelector('#confirmNotesBtn');
    const notesEditor = document.querySelector('#notesEditor'); // adatta l'id se usi un editor diverso

    if (!form || !notesField || !confirmNotesBtn || !notesEditor) return;

    // Quando l'utente conferma le note, aggiorna il campo nascosto
    confirmNotesBtn.addEventListener('click', function() {
        notesField.value = notesEditor.value || notesEditor.innerText || '';
    });

    // Blocca invio se notes è richiesto ma vuoto
    form.addEventListener('submit', function(e) {
        if (notesField.required && notesField.value.trim().length === 0) {
            e.preventDefault();
            alert('Devi confermare le note prima di inviare!');
        }
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    validateRegistrationForm();
    
    // Add visual feedback for form inputs
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-400');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-400');
        });
    });
    
    initNotesBinding();
});