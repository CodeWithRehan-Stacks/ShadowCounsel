<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Auth::user()->setting()->firstOrCreate(['user_id' => Auth::id()]);
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Auth::user()->setting()->firstOrCreate(['user_id' => Auth::id()]);

        $setting->update([
            'dark_mode' => $request->has('dark_mode'),
            'notifications' => $request->has('notifications'),
            'language' => $request->input('language', 'en'),
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function destroyAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
