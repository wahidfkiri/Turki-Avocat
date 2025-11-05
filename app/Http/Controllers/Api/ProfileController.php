<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'fonction' => ['required', 'string', 'in:admin,avocat,secrétaire,clerc,stagiaire'],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'fonction.required' => 'La fonction est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs ci-dessous.');
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'fonction' => $request->fonction,
            ]);

            return redirect()->route('profile.edit')
                ->with('success', 'Profil mis à jour avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du profil.');
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'password')
                ->with('error', 'Veuillez corriger les erreurs ci-dessous.');
        }

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Le mot de passe actuel est incorrect.')
                ->with('tab', 'password');
        }

        try {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

             // Mettre à jour les mots de passe dans les paramètres email
        $emailSetting = $user->emailSetting;
        if ($emailSetting) {
            $emailSetting->imap_password = $validated['password'];
            $emailSetting->smtp_password = $validated['password'];
            $emailSetting->save();      
        }else{
             $user->emailSetting()->create([
                'imap_host' => 'mailbox.nextstep-it.com',
                'imap_port' => '993',
                'imap_encryption' => 'ssl',
                'imap_username' => $user->email,
                'imap_password' => $request->password,
                'smtp_host' => 'smtp.nextstep-it.com',
                'smtp_port' => '465',
                'smtp_encryption' => 'ssl',
                'smtp_username' => $user->email,
                'smtp_password' => $request->password,
            ]);
        }

            return redirect()->route('profile.edit')
                ->with('success', 'Mot de passe mis à jour avec succès!')
                ->with('tab', 'profile');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du mot de passe.')
                ->with('tab', 'password');
        }
    }
}