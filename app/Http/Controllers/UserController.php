<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('role');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter berdasarkan bulan & tahun registrasi
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        // Ambil data dengan pagination
        $users = $query->withCount(['penjualan', 'pembelian'])
                      ->latest()
                      ->paginate(10)
                      ->appends($request->all());

        // Get all roles untuk filter
        $roles = Role::orderBy('nama_role')->get();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('nama_role')->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:aktif,non-aktif',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'role_id.required' => 'Role wajib dipilih',
            'role_id.exists' => 'Role tidak valid',
            'status.required' => 'Status wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'role_id' => $request->role_id,
                'status' => $request->status,
                'email_verified_at' => now(), // Auto verify
            ]);

            DB::commit();

            return redirect()->route('users.index')
                           ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load([
            'role',
            'penjualan' => function($query) {
                $query->latest()->limit(10);
            },
            'pembelian' => function($query) {
                $query->latest()->limit(10);
            }
        ]);

        // Statistik user
        $stats = [
            'total_penjualan' => $user->penjualan()->count(),
            'total_pembelian' => $user->pembelian()->count(),
            'nilai_penjualan' => $user->penjualan()->sum('grand_total'),
            'nilai_pembelian' => $user->pembelian()->sum('total_harga'),
            'bergabung' => $user->created_at->diffForHumans(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('nama_role')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:aktif,non-aktif',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'role_id.required' => 'Role wajib dipilih',
            'status.required' => 'Status wajib dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'role_id' => $request->role_id,
                'status' => $request->status,
            ];

            // Update password jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            DB::commit();

            return redirect()->route('users.index')
                           ->with('success', 'User berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Tidak bisa hapus diri sendiri
            // if ($user->id === auth()->id()) {
            //     return redirect()->back()
            //                    ->with('error', 'Tidak dapat menghapus akun Anda sendiri');
            // }

            // Cek apakah user punya transaksi
            if ($user->penjualan()->count() > 0 || $user->pembelian()->count() > 0) {
                return redirect()->back()
                               ->with('error', 'User tidak dapat dihapus karena sudah memiliki riwayat transaksi');
            }

            $user->delete();

            return redirect()->route('users.index')
                           ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus banyak user sekaligus
     */
    public function destroyAll(Request $request)
    {
        try {
            $ids = $request->ids;

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dipilih'
                ]);
            }

            // Tidak bisa hapus diri sendiri
            // if (in_array(auth()->id(), $ids)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Tidak dapat menghapus akun Anda sendiri'
            //     ]);
            // }

            // Cek apakah ada user yang punya transaksi
            $usersWithTransaksi = User::whereIn('id', $ids)
                ->where(function($q) {
                    $q->has('penjualan')->orHas('pembelian');
                })
                ->count();

            if ($usersWithTransaksi > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa user tidak dapat dihapus karena memiliki riwayat transaksi'
                ]);
            }

            User::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' user berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle status user (aktif/non-aktif)
     */
    public function toggleStatus(User $user)
    {
        try {
            // // Tidak bisa nonaktifkan diri sendiri
            // if ($user->id === auth()->id()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Tidak dapat mengubah status akun Anda sendiri'
            //     ]);
            // }

            $user->status = $user->status === 'aktif' ? 'non-aktif' : 'aktif';
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Status user berhasil diubah',
                'status' => $user->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}