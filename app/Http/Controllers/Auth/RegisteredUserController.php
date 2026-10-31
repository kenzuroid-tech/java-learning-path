<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users', 'alpha_dash'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'username.alpha_dash' => 'Username hanya boleh mengandung huruf, angka, strip, dan underscore.',
            'username.unique'     => 'Username sudah digunakan, coba yang lain.',
            'email.unique'        => 'Email sudah terdaftar.',
        ]);

        $user = User::create([
            'name'            => $request->name,
            'username'        => $request->username,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role'            => 'siswa',
            'xp'              => 0,
            'level'           => 1,
            'placement_done'  => false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Siswa must complete placement test first
        return redirect()->route('placement.show')
            ->with('status', 'Akun berhasil dibuat! Selesaikan Placement Test untuk menentukan level awalmu.');
    }
}
