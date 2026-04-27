<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('manage users');

        return Inertia::render('Admin/Users/Index', [
            'users' => User::with('roles')->latest()->get(),
            'roles' => Role::all()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('manage users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|array'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['roles']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'User Created',
            'model_type' => User::class,
            'model_id' => $user->id,
            'details' => ['email' => $user->email, 'roles' => $validated['roles']],
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'User created successfully');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('manage users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'required|array'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($validated['roles']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'User Updated',
            'model_type' => User::class,
            'model_id' => $user->id,
            'details' => ['email' => $user->email, 'roles' => $validated['roles']],
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $this->authorize('manage users');

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot delete self');
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'User Deleted',
            'model_type' => User::class,
            'model_id' => $user->id,
            'details' => ['email' => $user->email],
            'ip_address' => request()->ip()
        ]);

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
