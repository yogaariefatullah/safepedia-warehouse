<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsermanagentController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        $roles = Role::all();

        return view('usermanagement.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('usermanagement.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id'  => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Isian password opsional
        ], [
            'name.required'     => 'Nama pengguna wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah digunakan oleh pengguna lain.',
            'role_id.required'  => 'Role wajib dipilih.',
            'password.min'      => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $updateData = [
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'role_id' => $validated['role_id'],
        ];

        // Hash & update password HANYA jika field password diisi
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()
            ->route('usermanagement.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }
}
