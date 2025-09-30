@extends('layouts.app')

@section('title', 'Registrazione - CO.DE Platform')

@section('head')
<link href="{{ asset('css/auth-styles.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{ asset('js/auth-scripts.js') }}"></script>
@endsection

@section('content')
<div class="min-h-screen code-gradient flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <div class="w-16 h-16 bg-white rounded-2xl mx-auto flex items-center justify-center mb-4">
                <i class="fas fa-user-plus text-code-blue text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">Registrati a CO.DE</h2>
            <p class="text-white opacity-90">Scegli il tuo piano e inizia a generare verbali ODV professionali</p>
        </div>

        <div class="bg-white rounded-2xl card-shadow overflow-hidden" x-data="initPlanSelection()">
            
            <!-- Plan Selection -->
            <div class="p-8 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                    Scegli il tuo piano
                </h3>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <!-- Starter Plan -->
                    <div class="border-2 rounded-xl p-4 transition-all cursor-pointer"
                         :class="getPlanClass('starter')"
                         @click="selectPlan('starter')">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg mx-auto flex items-center justify-center mb-3">
                                <i class="fas fa-play text-gray-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Starter</h4>
                            <p class="text-2xl font-bold text-gray-900 my-2">&euro;49<span class="text-sm font-normal">/mese</span></p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>5 verbali ODV/mese</li>
                                <li>Storage sicuro</li>
                                <li>Email support</li>
                                <li>Conformità D.Lgs 231/2001</li>
                                <li>Consegna 48h garantita</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Professional Plan -->
                    <div class="border-2 rounded-xl p-4 transition-all cursor-pointer relative"
                         :class="getPlanClass('professional')"
                         @click="selectPlan('professional')">
                        <div class="absolute -top-2 left-1/2 transform -translate-x-1/2">
                            <span class="bg-code-blue text-white text-xs px-3 py-1 rounded-full">Popolare</span>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg mx-auto flex items-center justify-center mb-3">
                                <i class="fas fa-star text-code-blue"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Professional</h4>
                            <p class="text-2xl font-bold text-gray-900 my-2">&euro;149<span class="text-sm font-normal">/mese</span></p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>20 verbali ODV/mese</li>
                                <li>Storage sicuro</li>
                                <li>Priority Email support</li>
                                <li>Conformità D.Lgs 231/2001</li>
                                <li>Consegna 48h garantita</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Enterprise Plan -->
                    <div class="border-2 rounded-xl p-4 transition-all cursor-pointer"
                         :class="getPlanClass('enterprise')"
                         @click="selectPlan('enterprise')">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gold-100 rounded-lg mx-auto flex items-center justify-center mb-3">
                                <i class="fas fa-crown text-yellow-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Enterprise</h4>
                            <p class="text-2xl font-bold text-gray-900 my-2">&euro;349<span class="text-sm font-normal">/mese</span></p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>50 verbali ODV/mese</li>
                                <li>Storage sicuro</li>
                                <li>Priority Email support</li>
                                <li>Phone support</li>
                                <li>Conformità D.Lgs 231/2001</li>
                                <li>Consegna 48h garantita</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" class="p-8">
                @csrf
                
                <!-- Hidden input che riceve il piano selezionato -->
                <input type="hidden" name="plan" value="professional">
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome completo *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-code-blue focus:border-code-blue"
                                   placeholder="Mario Rossi"
                                   required>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-code-blue focus:border-code-blue"
                                   placeholder="mario@studiolegale.it"
                                   required>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-code-blue focus:border-code-blue"
                                   placeholder="Minimo 8 caratteri"
                                   required>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Conferma Password *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-code-blue focus:border-code-blue"
                                   placeholder="Ripeti la password"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="mt-6">
                    <div class="flex items-start">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               class="h-4 w-4 text-code-blue focus:ring-code-blue border-gray-300 rounded mt-1"
                               required>
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            Accetto i <a href="#" class="text-code-blue hover:text-code-light-blue">Termini di Servizio</a> 
                            e l'<a href="#" class="text-code-blue hover:text-code-light-blue">Informativa Privacy</a>
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="mt-8">
                    <button type="submit" 
                            class="w-full bg-code-blue hover:bg-code-dark-blue text-white font-semibold py-3 px-4 rounded-lg transition duration-300 btn-professional">
                        <i class="fas fa-user-plus mr-2"></i>
                        Crea Account - Piano <span x-text="selectedPlan.charAt(0).toUpperCase() + selectedPlan.slice(1)"></span>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Hai già un account? 
                        <a href="{{ route('login') }}" class="text-code-blue hover:text-code-light-blue font-medium">
                            Accedi qui
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection