<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Debug log per verificare che il middleware venga chiamato
        Log::info('AdminMiddleware called', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'request_path' => $request->path()
        ]);

        // Verifica che l'utente sia autenticato
        if (!auth()->check()) {
            Log::warning('AdminMiddleware: User not authenticated');
            abort(403, 'Accesso non autorizzato - Login richiesto');
        }

        // Lista delle email admin autorizzate
        $adminEmails = [
            'admin@code.example',
            'marco@code-platform.it',
            'amministratore@lrsec.it', // Aggiungi le email reali
            // Aggiungi qui le email degli admin autorizzati
        ];

        $userEmail = auth()->user()->email;

        // Verifica che l'email sia in lista admin
        if (!in_array($userEmail, $adminEmails)) {
            Log::warning('AdminMiddleware: Access denied', [
                'user_email' => $userEmail,
                'admin_emails' => $adminEmails
            ]);
            abort(403, 'Accesso non autorizzato - Privilegi amministratore richiesti');
        }

        Log::info('AdminMiddleware: Access granted', [
            'admin_email' => $userEmail
        ]);

        return $next($request);
    }
}