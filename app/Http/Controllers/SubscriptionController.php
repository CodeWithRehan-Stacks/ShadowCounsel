<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:subscriptions,email'],
        ]);

        Subscription::create($validated);

        return Redirect::back()->with('success', 'Thank you for subscribing! You will receive updates from us.');
    }
}
