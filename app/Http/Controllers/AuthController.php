<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate
        $fields = $request->validate([
            'avatar' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'], // confirmed is for the password confirmation field
        ]);

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = Storage::disk('public')->put('avatars', $request->avatar);

            $fields['avatar'] = $avatarPath;
        }

        try {
            // Register
            $user = User::create($fields);

            // Login
            Auth::login($user);
        } catch (\Exception $exception) {
            // Delete the uploaded avatar if user creation failed
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            return redirect()->back()->with([
                'error' => 'Failed to register user. Please try again later.',
            ]);
        }

        return redirect('/')->with('success', 'Welcome to our site!');
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => ['required', 'email', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);


        try {
            if (Auth::attempt($fields, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/')->with('success', 'Logged in successfully');
            }

            return back()->with([
                'error' => 'The provided credentials do not match our records.',
            ])->withErrors(['email' => ' ', 'password' => ' '])->onlyInput('email');
        } catch (\Exception $e) {
            return back()->with([
                'error' => 'Login failed: ' . $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/')->with([
                'error' => 'Logout failed: ' . $e->getMessage(),
            ]);
        }
    }
}
