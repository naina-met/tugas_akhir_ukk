<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = User::where('role', 'Admin');
        $search = null;

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'Admin',
            'status'   => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Admin account created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'status'   => 'required|boolean',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // ======================
        // UPDATE DATA UTAMA
        // ======================
        $user->username = $request->username;
        $user->email    = $request->email;
        $user->status   = $request->status;

        // ======================
        // UPDATE PASSWORD
        // HANYA BOLEH OLEH SUPERADMIN
        // ======================
        if (
            $request->filled('password') &&
            Auth::user()->role === 'Superadmin'
        ) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Admin account updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Admin account deleted successfully.');
    }

    /**
     * Show admin self-registration form
     */
    public function showAdminRegister()
    {
        return view('auth.admin-register');
    }

    /**
     * Handle admin self-registration
     */
    public function adminRegister(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string',
        ]);

        // Create admin with pending approval
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Admin',
            'status' => true,
            'approved' => false, // Pending approval
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Admin account created successfully. Please wait for superadmin approval.');
    }

    /**
     * Approve admin account (Superadmin only)
     */
    public function approve(User $user)
    {
        $this->authorize('manage-users');

        if ($user->role !== 'Admin') {
            return back()->with('error', 'Only admin accounts can be approved.');
        }

        // Approve dan set status active otomatis
        $user->update(['approved' => true, 'status' => true]);

        return back()->with('success', 'Admin account approved and activated successfully.');
    }

    /**
     * Reject admin account (Superadmin only)
     */
    public function reject(User $user)
    {
        $this->authorize('manage-users');

        if ($user->role !== 'Admin') {
            return back()->with('error', 'Only admin accounts can be rejected.');
        }

        $user->update(['status' => false, 'approved' => false]);

        return back()->with('success', 'Admin account rejected.');
    }
}
