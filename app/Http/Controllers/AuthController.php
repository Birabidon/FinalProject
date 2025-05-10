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
            'password' => ['required', 'string', 'min:3', 'confirmed'],
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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        try {
            if (Auth::attempt($fields, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }

            return back()->with([
                'error' => 'The provided credentials do not match our records.',
            ])->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        } catch (\Exception $e) {
            return back()->with([
                'error' => 'Login failed: ' . $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request){
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
