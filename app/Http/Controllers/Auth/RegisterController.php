<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_pic' => 'nullable|image|max:2048',
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('profile_pic')) {
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $user->profile_pic = $path;
        }

        try {
            $user->save();
            auth()->login($user);
            return redirect()->route('dashboard')
                ->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            // Delete the uploaded image if user creation fails
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()->withInput()
                ->with('error', 'Registration failed. Please try again.');
        }
    }
}
