<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::withCount(['users', 'permissions']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_role', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter by user count
        if ($request->filled('user_status')) {
            if ($request->user_status === 'has_users') {
                $query->has('users');
            } elseif ($request->user_status === 'no_users') {
                $query->doesntHave('users');
            }
        }

        $roles = $query->latest()->paginate(10)->appends($request->all());

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all permissions grouped by feature
        $permissions = Permission::orderBy('feature')->orderBy('action')->get();
        $groupedPermissions = $permissions->groupBy('feature');

        return view('roles.create', compact('groupedPermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_role' => 'required|string|max:100|unique:roles,nama_role',
            'deskripsi' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'nama_role.required' => 'Nama role wajib diisi',
            'nama_role.unique' => 'Nama role sudah ada',
            'nama_role.max' => 'Nama role maksimal 100 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
            'permissions.*.exists' => 'Permission tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create role
            $role = Role::create([
                'nama_role' => $request->nama_role,
                'deskripsi' => $request->deskripsi,
            ]);

            // Sync permissions
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            DB::commit();

            return redirect()->route('roles.index')
                           ->with('success', 'Role berhasil ditambahkan');
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
    public function show(Role $role)
    {
        $role->load(['users', 'permissions']);
        
        // Group permissions by feature
        $groupedPermissions = $role->permissions->groupBy('feature');

        // Stats
        $stats = [
            'total_users' => $role->users()->count(),
            'total_permissions' => $role->permissions()->count(),
            'active_users' => $role->users()->where('status', 'aktif')->count(),
        ];

        return view('roles.show', compact('role', 'groupedPermissions', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Get all permissions grouped by feature
        $permissions = Permission::orderBy('feature')->orderBy('action')->get();
        $groupedPermissions = $permissions->groupBy('feature');

        // Get current role permissions
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'nama_role' => 'required|string|max:100|unique:roles,nama_role,' . $role->id,
            'deskripsi' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'nama_role.required' => 'Nama role wajib diisi',
            'nama_role.unique' => 'Nama role sudah ada',
            'nama_role.max' => 'Nama role maksimal 100 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
            'permissions.*.exists' => 'Permission tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update role
            $role->update([
                'nama_role' => $request->nama_role,
                'deskripsi' => $request->deskripsi,
            ]);

            // Sync permissions
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            } else {
                // If no permissions selected, remove all
                $role->permissions()->detach();
            }

            DB::commit();

            return redirect()->route('roles.index')
                           ->with('success', 'Role berhasil diperbarui');
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
    public function destroy(Role $role)
    {
        try {
            // Cek apakah role masih digunakan
            if ($role->users()->count() > 0) {
                return redirect()->back()
                               ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh ' . $role->users()->count() . ' user');
            }

            // Hapus relasi permissions
            $role->permissions()->detach();

            // Hapus role
            $role->delete();

            return redirect()->route('roles.index')
                           ->with('success', 'Role berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus banyak role sekaligus
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

            // Cek apakah ada role yang masih digunakan
            $rolesWithUsers = Role::whereIn('id', $ids)
                ->has('users')
                ->count();

            if ($rolesWithUsers > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa role tidak dapat dihapus karena masih digunakan oleh user'
                ]);
            }

            // Hapus relasi permissions
            DB::table('role_permission')->whereIn('role_id', $ids)->delete();

            // Hapus roles
            Role::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' role berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clone role (duplicate)
     */
    public function clone(Role $role)
    {
        try {
            DB::beginTransaction();

            // Create new role with "Copy of" prefix
            $newRole = Role::create([
                'nama_role' => 'Copy of ' . $role->nama_role,
                'deskripsi' => $role->deskripsi,
            ]);

            // Copy permissions
            $permissions = $role->permissions->pluck('id')->toArray();
            $newRole->permissions()->sync($permissions);

            DB::commit();

            return redirect()->route('roles.edit', $newRole->id)
                           ->with('success', 'Role berhasil diduplikasi. Silakan edit nama role.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}