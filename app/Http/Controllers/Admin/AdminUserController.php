<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $rolAdmin = Role::where('nombre', 'admin')->first();

        if ($rolAdmin) {
            $admins = $rolAdmin->users()->paginate(10);
        } else {
            $admins = collect();
        }

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:4',
        ]);

        $admin = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
        ]);

        $rolAdmin = Role::firstOrCreate([
            'nombre' => 'admin',
        ]);

        $admin->roles()->attach($rolAdmin->id);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrador creado correctamente.');
    }

    public function edit(User $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:4',
        ]);

        $admin->name = $datos['name'];
        $admin->email = $datos['email'];

        if (!empty($datos['password'])) {
            $admin->password = Hash::make($datos['password']);
        }

        $admin->save();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrador actualizado correctamente.');
    }

    public function destroy(User $admin)
    {
        if (auth()->id() == $admin->id) {
            return redirect()
                ->route('admin.admins.index')
                ->with('error', 'No puedes eliminar tu propio usuario administrador.');
        }

        $rolAdmin = Role::where('nombre', 'admin')->first();

        if ($rolAdmin) {
            $admin->roles()->detach($rolAdmin->id);
        }

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrador eliminado correctamente.');
    }
}