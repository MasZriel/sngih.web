<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Handle the contact form submission.
     */
    public function submitForm(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Send the email
        Mail::to(config('app.contact_email'))->send(new ContactFormSubmitted(
            $validated['name'],
            $validated['email'],
            $validated['message']
        ));

        // Redirect back with a success message
        return redirect()->route('contact')->with('success', 'Pesan Anda telah berhasil terkirim! Kami akan segera menghubungi Anda.');
    }
}
