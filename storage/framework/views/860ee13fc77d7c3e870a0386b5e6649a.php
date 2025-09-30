<?php $__env->startSection('title', 'CO.DE - Soluzioni Digitali per la Compliance Aziendale'); ?>

<?php $__env->startSection('head'); ?>
<link href="<?php echo e(asset('css/welcome-styles.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen code-gradient">
    <!-- Header with Logo -->
    <header class="relative bg-white bg-opacity-10 backdrop-blur-md border-b border-white border-opacity-20">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo CO.DE -->
                <div class="flex items-center flex-shrink-0">
                    <div class="w-48 h-12 flex items-center">
                        <img src="<?php echo e(asset('images/logo-code.png')); ?>" 
                             alt="CO.DE Logo" 
                             class="h-full w-auto object-contain transition-opacity duration-300"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <!-- Fallback logo testuale professionale -->
                        <div class="hidden bg-gradient-to-r from-tiffany to-aqua px-4 py-2 rounded-lg shadow-lg">
                            <span class="text-xl font-bold text-white tracking-wider">CO.DE</span>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex space-x-4 flex-shrink-0">
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('login')); ?>" 
                           class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-lg font-semibold hover:bg-opacity-30 transition duration-300 backdrop-blur-sm text-sm">
                            <i class="fas fa-sign-in-alt mr-1"></i>
                            <span class="hidden sm:inline">Accedi</span>
                            <span class="sm:hidden">Login</span>
                        </a>
                        <a href="<?php echo e(route('register')); ?>" 
                           class="bg-white text-tiffany px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 hover:text-code-blue transition duration-300 shadow-lg text-sm">
                            <i class="fas fa-user-plus mr-1"></i>
                            <span class="hidden sm:inline">Registrati</span>
                            <span class="sm:hidden">Sign Up</span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('dashboard')); ?>" 
                           class="bg-white text-tiffany px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition duration-300 shadow-lg text-sm">
                            <i class="fas fa-tachometer-alt mr-1"></i>
                            <span class="hidden sm:inline">Dashboard</span>
                            <span class="sm:hidden">App</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center text-white pt-12">
            
            <h1 class="text-6xl font-bold mb-6 leading-tight text-white drop-shadow-lg">
                Digitalizzazione<br>
                <span class="text-code-accent drop-shadow-lg">Sicurezza e Compliance</span>
            </h1>
            
            <!-- Sottotitolo professionale -->
            <p class="text-xl text-white mb-6 max-w-3xl mx-auto leading-relaxed opacity-90">
                CO.DE trasforma la gestione documentale della tua azienda attraverso soluzioni digitali avanzate.
            </p>
            
            <p class="text-lg text-white mb-10 max-w-3xl mx-auto leading-relaxed opacity-80">
                Dalla registrazione audio alla produzione di verbali conformi al D.Lgs 231/2001, 
                garantiamo conformità normativa e sicurezza dei dati con standard enterprise.
            </p>
            
            <!-- Call to Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center mb-20">
                <a href="<?php echo e(route('register')); ?>" 
                   class="bg-white text-tiffany px-12 py-6 rounded-lg font-bold text-xl hover:bg-gray-50 transition duration-300 btn-professional shadow-xl">
                    <i class="fas fa-play mr-3"></i>
                    Registrati
                    <span class="block text-sm font-normal text-gray-500 mt-1">Crea il tuo account personale</span>
                </a>
                <a href="<?php echo e(route('login')); ?>" 
                   class="border-2 border-white text-white px-12 py-6 rounded-lg font-bold text-xl hover:bg-white hover:text-tiffany transition duration-300 btn-professional shadow-lg backdrop-blur-sm">
                    <i class="fas fa-sign-in-alt mr-3"></i>
                    Accedi alla Piattaforma
                    <span class="block text-sm font-normal opacity-80 mt-1">Area clienti</span>
                </a>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-16 mb-8">
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6 border border-white border-opacity-30 card-hover text-center">
                    <i class="fas fa-users text-4xl text-code-accent mb-3 drop-shadow-lg"></i>
                    <div class="text-3xl font-bold mb-1 text-white">500+</div>
                    <div class="text-sm text-white opacity-80">Aziende Clienti</div>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6 border border-white border-opacity-30 card-hover text-center">
                    <i class="fas fa-file-alt text-4xl text-white mb-3 drop-shadow-lg"></i>
                    <div class="text-3xl font-bold mb-1 text-white">15K+</div>
                    <div class="text-sm text-white opacity-80">Verbali Processati</div>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6 border border-white border-opacity-30 card-hover text-center">
                    <i class="fas fa-clock text-4xl text-code-accent mb-3 drop-shadow-lg"></i>
                    <div class="text-3xl font-bold mb-1 text-white">24h</div>
                    <div class="text-sm text-white opacity-80">Tempo Medio</div>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6 border border-white border-opacity-30 card-hover text-center">
                    <i class="fas fa-star text-4xl text-white mb-3 drop-shadow-lg"></i>
                    <div class="text-3xl font-bold mb-1 text-white">99.8%</div>
                    <div class="text-sm text-white opacity-80">Soddisfazione</div>
                </div>
            </div>
        </div>

        <!-- Value Proposition -->
        <div class="bg-white bg-opacity-15 backdrop-blur-lg rounded-2xl p-12 mb-20 border border-white border-opacity-30">
            <div class="text-center text-white mb-16">
                <h2 class="text-4xl font-bold mb-6 drop-shadow-lg">Perché Scegliere CO.DE</h2>
                <p class="text-xl text-white opacity-90 max-w-4xl mx-auto">
                    La nostra piattaforma combina tecnologia avanzata e competenza umana per garantire 
                    la massima qualità e conformità normativa dei vostri documenti aziendali.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white bg-opacity-25 backdrop-blur-lg rounded-xl p-8 text-center text-white border border-white border-opacity-40 card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-code-blue to-code-light-blue rounded-xl mx-auto flex items-center justify-center mb-6 shadow-xl">
                        <i class="fas fa-shield-alt text-3xl text-white drop-shadow-lg"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white drop-shadow-sm">Conformità Garantita</h3>
                    <p class="text-white opacity-85 leading-relaxed">
                        Documenti sempre conformi al D.Lgs 231/2001, GDPR e normative di settore. 
                        Revisione legale e controllo qualità su ogni verbale prodotto.
                    </p>
                </div>

                <div class="bg-white bg-opacity-25 backdrop-blur-lg rounded-xl p-8 text-center text-white border border-white border-opacity-40 card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-code-accent to-code-light-blue rounded-xl mx-auto flex items-center justify-center mb-6 shadow-xl">
                        <i class="fas fa-user-graduate text-3xl text-white drop-shadow-lg"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white drop-shadow-sm">Expertise Professionale</h3>
                    <p class="text-white opacity-85 leading-relaxed">
                        Team di esperti legali e compliance che garantisce precisione e aderenza 
                        alle best practice aziendali per ogni settore di attività.
                    </p>
                </div>

                <div class="bg-white bg-opacity-25 backdrop-blur-lg rounded-xl p-8 text-center text-white border border-white border-opacity-40 card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-code-dark-blue to-code-blue rounded-xl mx-auto flex items-center justify-center mb-6 shadow-xl">
                        <i class="fas fa-clock text-3xl text-white drop-shadow-lg"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white drop-shadow-sm">Efficienza Temporale</h3>
                    <p class="text-white opacity-85 leading-relaxed">
                        Consegna garantita in 24-48 ore lavorative. Processo ottimizzato che 
                        riduce i tempi burocratici senza compromettere la qualità del risultato.
                    </p>
                </div>
            </div>
        </div>

        <!-- Process Section -->
        <div class="bg-white bg-opacity-15 backdrop-blur-lg rounded-2xl p-12 border border-white border-opacity-30">
            <div class="text-center text-white mb-16">
                <h2 class="text-4xl font-bold mb-6 drop-shadow-lg">Il Nostro Processo</h2>
                <p class="text-xl text-white opacity-90 max-w-3xl mx-auto">
                    Un workflow consolidato che garantisce precisione, efficienza e conformità normativa 
                    per ogni documento aziendale prodotto.
                </p>
            </div>
            
            <div class="grid md:grid-cols-5 gap-8">
                <!-- Step 1: Upload -->
                <div class="text-center text-white">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-code-blue to-code-light-blue rounded-xl mx-auto flex items-center justify-center mb-4 shadow-xl">
                            <i class="fas fa-upload text-3xl text-white drop-shadow-lg"></i>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-br from-code-accent to-code-light-blue rounded-full flex items-center justify-center font-bold text-sm mx-auto border-2 border-white">
                            1
                        </div>
                    </div>
                    <h4 class="text-xl font-bold mb-4 text-white drop-shadow-sm">Upload Sicuro</h4>
                    <p class="text-white opacity-85 leading-relaxed">
                        Caricamento crittografato di registrazioni audio e documenti correlati 
                        con conformità GDPR e protezione end-to-end.
                    </p>
                </div>

                <!-- Step 2: Analisi -->
                <div class="text-center text-white">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-code-accent to-code-light-blue rounded-xl mx-auto flex items-center justify-center mb-4 shadow-xl">
                            <i class="fas fa-brain text-3xl text-white drop-shadow-lg"></i>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-br from-code-light-blue to-code-accent rounded-full flex items-center justify-center font-bold text-sm mx-auto border-2 border-white">
                            2
                        </div>
                    </div>
                    <h4 class="text-xl font-bold mb-4 text-white drop-shadow-sm">Analisi Intelligente</h4>
                    <p class="text-white opacity-85 leading-relaxed">
                        Integrazione e analisi avanzata di DVR, certificazioni, procedure operative 
                        e documenti di supporto per un quadro completo.
                    </p>
                </div>

                <!-- Step 3: Revisione -->
                <div class="text-center text-white">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-code-dark-blue to-code-blue rounded-xl mx-auto flex items-center justify-center mb-4 shadow-xl">
                            <i class="fas fa-user-tie text-3xl text-white drop-shadow-lg"></i>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-br from-code-blue to-code-dark-blue rounded-full flex items-center justify-center font-bold text-sm mx-auto border-2 border-white">
                            3
                        </div>
                    </div>
                    <h4 class="text-xl font-bold mb-4 text-white drop-shadow-sm">Revisione Esperta</h4>
                    <p class="text-white opacity-85 leading-relaxed">
                        Controllo qualità da parte di specialisti legali e compliance 
                        con verifica di conformità alle normative vigenti.
                    </p>
                </div>

                <!-- Step 4: Consegna -->
                <div class="text-center text-white">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-code-accent to-code-blue rounded-xl mx-auto flex items-center justify-center mb-4 shadow-xl">
                            <i class="fas fa-certificate text-3xl text-white drop-shadow-lg"></i>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-br from-code-blue to-code-accent rounded-full flex items-center justify-center font-bold text-sm mx-auto border-2 border-white">
                            4
                        </div>
                    </div>
                    <h4 class="text-xl font-bold mb-4 text-white drop-shadow-sm">Consegna Certificata</h4>
                    <p class="text-white opacity-85 leading-relaxed">
                        Verbale completo con firma digitale, timestamp e tracciabilità 
                        consegnato tramite canali sicuri entro 48 ore.
                    </p>
                </div>

                <!-- Step 5: Cancellazione -->
                <div class="text-center text-white">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-code-dark-blue to-code-blue rounded-xl mx-auto flex items-center justify-center mb-4 shadow-xl">
                            <i class="fas fa-trash-alt text-3xl text-white drop-shadow-lg"></i>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-br from-code-blue to-code-dark-blue rounded-full flex items-center justify-center font-bold text-sm mx-auto border-2 border-white">
                            5
                        </div>
                    </div>
                    <h4 class="text-xl font-bold mb-4 text-white drop-shadow-sm">Cancellazione Sicura</h4>
                    <p class="text-white opacity-85 leading-relaxed">
                        Gestione certificata della cancellazione documentale con tracciabilità, 
                        conformità GDPR e verifica di sicurezza post-eliminazione.
                    </p>
                </div>
            </div>
        </div>

        <!-- Pricing Section -->
        <div class="mt-20 bg-white bg-opacity-15 backdrop-blur-lg rounded-2xl p-12 border border-white border-opacity-30">
            <div class="text-center text-white mb-16">
                <h2 class="text-4xl font-bold mb-6 drop-shadow-lg">Soluzioni Su Misura</h2>
                <p class="text-xl text-white opacity-90 max-w-3xl mx-auto">
                    Piani flessibili progettati per ogni dimensione aziendale, 
                    dalla startup all'enterprise multinazionale.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white bg-opacity-25 backdrop-blur-lg rounded-xl p-8 text-white border border-white border-opacity-40 card-hover">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-code-blue to-code-light-blue rounded-lg mx-auto flex items-center justify-center mb-6 shadow-lg">
                            <i class="fas fa-rocket text-2xl text-white drop-shadow-lg"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Starter</h3>
                        <p class="text-sm text-white opacity-70 mb-4">Ideale per PMI e studi professionali</p>
                        <p class="text-4xl font-bold mb-6">€49<span class="text-lg">/mese</span></p>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>5 verbali ODV/mese</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Storage sicuro</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Support via email</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Conformità D.Lgs 231/2001</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Consegna 48h garantita</li>
                        </ul>
                        <a href="<?php echo e(route('register')); ?>" class="block bg-gradient-to-r from-aqua to-tiffany hover:from-tiffany hover:to-celeste text-white py-3 px-6 rounded-lg font-semibold btn-professional">
                            Inizia Ora
                        </a>
                    </div>
                </div>

                <div class="bg-white bg-opacity-30 backdrop-blur-lg rounded-xl p-8 text-white border-2 border-code-accent relative shadow-2xl">
                    <div class="text-center relative">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-code-accent to-code-light-blue text-white text-sm px-4 py-2 rounded-full font-bold shadow-lg">
                            • Più Scelto
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-code-accent to-code-light-blue rounded-lg mx-auto flex items-center justify-center mb-6 mt-2 shadow-lg">
                            <i class="fas fa-crown text-2xl text-white drop-shadow-lg"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Professional</h3>
                        <p class="text-sm text-white opacity-70 mb-4">Per aziende in crescita</p>
                        <p class="text-4xl font-bold mb-6">€149<span class="text-lg">/mese</span></p>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>20 verbali ODV/mese</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Storage sicuro</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Support via email prioritario</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Conformità D.Lgs 231/2001</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Consegna 48h garantita</li>
                        </ul>
                        <a href="<?php echo e(route('register')); ?>" class="block bg-gradient-to-r from-code-accent to-acquamarina hover:from-acquamarina hover:to-celeste text-white py-3 px-6 rounded-lg font-semibold btn-professional">
                            Scegli Professional
                        </a>
                    </div>
                </div>

                <div class="bg-white bg-opacity-25 backdrop-blur-lg rounded-xl p-8 text-white border border-white border-opacity-40 card-hover">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-code-dark-blue to-code-blue rounded-lg mx-auto flex items-center justify-center mb-6 shadow-lg">
                            <i class="fas fa-building text-2xl text-white drop-shadow-lg"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Enterprise</h3>
                        <p class="text-sm text-white opacity-70 mb-4">Soluzioni enterprise scalabili</p>
                        <p class="text-4xl font-bold mb-6">€349<span class="text-lg">/mese</span></p>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>50 verbali ODV/mese</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Storage sicuro</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Support via email prioritario</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Support telefonico</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Conformità D.Lgs 231/2001</li>
                            <li class="flex items-center"><i class="fas fa-check-circle text-code-accent mr-3"></i>Consegna 48h garantita</li>
                        </ul>
                        <a href="<?php echo e(route('register')); ?>" class="block bg-gradient-to-r from-tiffany to-code-accent hover:from-code-accent hover:to-acquamarina text-white py-3 px-6 rounded-lg font-semibold btn-professional">
                            Contatta Vendite
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <p class="text-blue-100 mb-6 text-lg">
                    <i class="fas fa-gem text-code-accent mr-2"></i>
                    Tutti i piani includono: Conformità GDPR • Firma digitale • Backup automatico • Assistenza specializzata • Cancellazione sicura documenti 
                    <i class="fas fa-gem text-code-accent ml-2"></i>
                </p>
                <div class="flex flex-wrap justify-center gap-6 text-sm text-blue-200">
                    <span class="flex items-center bg-white bg-opacity-10 px-4 py-2 rounded-full backdrop-blur-sm">
                        <i class="fas fa-gift text-code-accent mr-2"></i>Prova gratuita 14 giorni
                    </span>
                    <span class="flex items-center bg-white bg-opacity-10 px-4 py-2 rounded-full backdrop-blur-sm">
                        <i class="fas fa-handshake text-tiffany mr-2"></i>Nessun vincolo
                    </span>
                    <span class="flex items-center bg-white bg-opacity-10 px-4 py-2 rounded-full backdrop-blur-sm">
                        <i class="fas fa-door-open text-celeste mr-2"></i>Disdetta in qualsiasi momento
                    </span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-20 bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-12 border-t border-white border-opacity-20">
            <div class="grid md:grid-cols-4 gap-8 text-blue-100">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center mb-6">
                        <div class="w-32 h-8 flex items-center">
                            <img src="<?php echo e(asset('images/logo-code.png')); ?>" 
                                 alt="CO.DE Logo" 
                                 class="h-full w-auto object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <!-- Fallback logo testuale -->
                            <div class="hidden bg-white rounded-lg px-4 py-2">
                                <span class="text-2xl font-bold text-code-blue">CO.DE</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-lg mb-4 leading-relaxed">
                        <strong class="text-white">CO.DE</strong> è leader nelle soluzioni digitali per la compliance aziendale, 
                        specializzata in servizi di digitalizzazione documentale conformi alle normative europee.
                    </p>
                    <p class="text-sm">
                        Trasformiamo la gestione documentale delle aziende attraverso tecnologie 
                        avanzate e competenze specialistiche certificate.
                    </p>
                    
                    <!-- Social Media -->
                    <div class="flex space-x-3 mt-6">
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition duration-300">
                            <i class="fab fa-linkedin text-lg text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition duration-300">
                            <i class="fab fa-twitter text-lg text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition duration-300">
                            <i class="fas fa-envelope text-lg text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition duration-300">
                            <i class="fab fa-instagram text-lg text-white"></i>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-4">Servizi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Verbali ODV</a></li>
                        <li><a href="#" class="hover:text-white transition">Consulenza Compliance</a></li>
                        <li><a href="#" class="hover:text-white transition">Digitalizzazione Documenti</a></li>
                        <li><a href="#" class="hover:text-white transition">Formazione GDPR</a></li>
                        <li><a href="#" class="hover:text-white transition">Audit Digitale</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-4">Supporto</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Centro Assistenza</a></li>
                        <li><a href="#" class="hover:text-white transition">Documentazione API (coming soon)</a></li>
                        <li><a href="#" class="hover:text-white transition">Guide Utente</a></li>
                        <li><a href="#" class="hover:text-white transition">Stato Servizi</a></li>
                        <li><a href="#" class="hover:text-white transition">Contatta l'Assistenza</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-white border-opacity-10 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm text-blue-200">
                    <div class="mb-4 md:mb-0">
                        <p>&copy; 2025 <strong>CO.DE Platform</strong> - Tutti i diritti riservati.</p>
                        <p class="mt-1">P.IVA: IT12345678901 | Sede legale: Via Roma 123, 20121 Milano (MI)</p>
                    </div>
                    
                    <div class="flex flex-wrap gap-6 text-xs">
                        <a href="#" class="hover:text-white transition">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition">Termini di Servizio</a>
                        <a href="#" class="hover:text-white transition">Cookie Policy</a>
                        <a href="#" class="hover:text-white transition">Conformità GDPR</a>
                    </div>
                </div>
                
                <!-- Certifications -->
                <div class="flex flex-wrap justify-center items-center mt-8 gap-4 text-xs text-blue-200">
                    <span class="flex items-center bg-white bg-opacity-15 px-3 py-2 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-check-circle mr-2 text-acquamarina"></i>
                        GDPR Compliant
                    </span>
                </div>
            </div>
        </footer>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/welcome.blade.php ENDPATH**/ ?>