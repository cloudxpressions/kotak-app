<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact.form');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            // Note: reCAPTCHA validation will be handled by middleware
        ]);

        // In a real application, you would send the email here
        // For now, we'll just redirect back with success message

        return redirect()->back()->with('success', 'Thank you for contacting us. We will get back to you soon!');
    }
}