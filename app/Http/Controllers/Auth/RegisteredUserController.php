<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'no_telp' => ['nullable', 'string', 'max:20'], // ✅ Ubah jadi nullable
        'alamat' => ['nullable', 'string'],
    ]);

    DB::beginTransaction();
    try {
        // Get role Pelanggan
        $pelangganRole = Role::where('nama_role', 'Pelanggan')->first();
        
        if (!$pelangganRole) {
            throw new \Exception('Role Pelanggan tidak ditemukan');
        }

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp ?? '-', // ✅ Default value
            'alamat' => $request->alamat ?? '-',   // ✅ Default value
            'role_id' => $pelangganRole->id,
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Create Pelanggan (link ke user)
        Pelanggan::create([
            'user_id' => $user->id,
            'nama_pelanggan' => $user->name,
            'alamat' => $user->alamat,
            'no_telp' => $user->no_telp,
            'email' => $user->email,
            'tanggal_daftar' => now(),
        ]);

        event(new Registered($user));
        Auth::login($user);

        DB::commit();

        return redirect()->route('shop.index'); // Redirect ke shop

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->withInput()
            ->with('error', 'Registrasi gagal: ' . $e->getMessage());
    }
}
}
