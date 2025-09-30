<?php $__env->startSection('title', 'Login - CO.DE Platform'); ?>

<?php $__env->startSection('head'); ?>
<link href="<?php echo e(asset('css/auth-styles.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/auth-scripts.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen code-gradient flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center animate-fade-in">
            <div class="w-16 h-16 bg-white rounded-2xl mx-auto flex items-center justify-center mb-4">
                <i class="fas fa-gavel text-code-blue text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">Accedi a CO.DE</h2>
            <p class="text-blue-100">Compliance &amp; Development Platform</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl card-shadow p-8">
            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo e(old('email')); ?>"
                               class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-code-blue focus:border-code-blue"
                               placeholder="inserisci@tua-email.com"
                               required>
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-code-blue focus:border-code-blue"
                               placeholder="La tua password"
                               required>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="h-4 w-4 text-code-blue focus:ring-code-blue border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-600">
                            Ricordami
                        </label>
                    </div>
                    <a href="#" class="text-sm text-code-blue hover:text-code-light-blue">
                        Password dimenticata?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-code-blue hover:bg-code-dark-blue text-white font-semibold py-3 px-4 rounded-lg transition duration-300 btn-professional">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Accedi
                </button>
            </form>

            <!-- Registration Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Non hai un account? 
                    <a href="<?php echo e(route('register')); ?>" class="text-code-blue hover:text-code-light-blue font-medium">
                        Registrati ora
                    </a>
                </p>
            </div>
        </div>

        <!-- Features Preview -->
        <div class="text-center text-blue-100 text-sm space-y-2">
            <div class="flex items-center justify-center space-x-6">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt"></i>
                    <span>Sicurezza GDPR</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-clock"></i>
                    <span>24-48h Processing</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-certificate"></i>
                    <span>D.Lgs 231/2001</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/auth/login.blade.php ENDPATH**/ ?>