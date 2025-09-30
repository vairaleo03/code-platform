<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'plan' => 'required|in:starter,professional,enterprise',
            'terms' => 'required|accepted'
        ]);
    
        // CORREZIONE: Piano correttamente mappato ai verbali
        $verbaliLimits = [
            'starter' => 5,
            'professional' => 25, 
            'enterprise' => 100
        ];
    
        // CORREZIONE: Usa il piano selezionato dall'utente
        $selectedPlan = $request->input('plan');
        $verbaliRemaining = $verbaliLimits[$selectedPlan];
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plan' => $selectedPlan,  // CORREZIONE: Piano dalla request
            'verbali_remaining' => $verbaliRemaining  // CORREZIONE: Verbali basati sul piano
        ]);
    
        Auth::login($user);
        return redirect('/dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Credenziali non valide']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}