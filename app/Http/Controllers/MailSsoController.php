<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MailSsoController extends Controller
{
    public function login()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        // MUST be a valid IMAP username
        $email = $user->email;

        // Pass email to Roundcube via header
        header("X-Authenticated-User: $email");

        return redirect()->away('http://localhost:8082');
    }
}
