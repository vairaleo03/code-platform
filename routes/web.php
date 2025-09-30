<?php

use App\Http\Controllers\UploadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;

// Health check for Docker
Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});

Route::get('/diagnostic', function () {
    try {
        $checks = [
            'database' => DB::connection()->getPdo() ? 'connected' : 'failed',
            'users_table' => DB::table('users')->count() . ' users',
            'uploads_table' => DB::table('uploads')->count() . ' uploads',
            'storage_writable' => is_writable(storage_path()) ? 'yes' : 'no',
        ];
        return response()->json($checks);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Welcome page - redirect to dashboard if authenticated
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected user routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UploadController::class, 'index'])->name('dashboard');
    Route::post('/upload', [UploadController::class, 'store'])->name('upload');
});

// Admin routes - CORREGGI QUESTA SEZIONE
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/upload/{upload}', [AdminController::class, 'show'])->name('upload.show');
    Route::patch('/upload/{upload}/status', [AdminController::class, 'updateStatus'])->name('upload.status');
    Route::post('/upload/{upload}/verbale', [AdminController::class, 'uploadVerbale'])->name('upload.verbale');
    Route::post('/upload/{upload}/send', [AdminController::class, 'sendVerbale'])->name('upload.send');
});