<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $redirectPath = $user && $user->role === 'employee' ? '/employee' : '/dashboard';

            return redirect($redirectPath)->with('success', 'Login Successful! Welcome back!');
        }

        // Debug: Check if user exists
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'No user found with this email'
            ]);
        }

        return back()->withErrors([
            'email' => 'Invalid email or password'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        return redirect('/')->with('success', 'Password changed successfully!  Please login with your new password.');
    }

    public function listUsers()
    {
        $users = User::select('id', 'name', 'email', 'role')
            ->orderBy('name')
            ->get()
            ->map(fn($u) => array_merge($u->toArray(), ['__backendId' => (string) $u->id]));

        return response()->json(['isOk' => true, 'data' => $users]);
    }

    public function createUser(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255|regex:/^[A-Za-z][A-Za-z\s\.\'\-]*$/',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,operator,employee',
            'password' => 'required|string|min:6',
            'phoneNumber' => 'nullable|regex:/^[0-9]{7,15}$/',
            'address'  => 'nullable|string|max:500',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return response()->json([
            'isOk' => true,
            'data' => array_merge($user->only(['id', 'name', 'email', 'role']), ['__backendId' => (string) $user->id])
        ]);
    }

    public function deleteUser($id)
    {
        if ((int) $id === auth()->id()) {
            return response()->json(['isOk' => false, 'error' => 'Cannot delete your own account'], 403);
        }

        User::findOrFail($id)->delete();

        return response()->json(['isOk' => true]);
    }

    public function updateUser(Request $request, $id)
    {
        if ((int) $id === auth()->id()) {
            return response()->json(['isOk' => false, 'error' => 'Cannot edit your own account via this endpoint'], 403);
        }

        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255|regex:/^[A-Za-z][A-Za-z\s\.\'\-]*$/',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'required|in:admin,operator,employee',
            'phoneNumber' => 'nullable|regex:/^[0-9]{7,15}$/',
            'address'  => 'nullable|string|max:500',
        ]);

        // Only hash password if it's being updated
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validate(['password' => 'string|min:6'])['password']);
        }

        $user->update($data);

        return response()->json([
            'isOk' => true,
            'data' => array_merge($user->only(['id', 'name', 'email', 'role', 'phoneNumber', 'address']), ['__backendId' => (string) $user->id])
        ]);
    }
}
