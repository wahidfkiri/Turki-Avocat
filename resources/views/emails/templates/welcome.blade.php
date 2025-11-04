{{-- resources/views/emails/templates/welcome.blade.php --}}
@extends('emails.template')

@section('content')
<h2>Bienvenue chez Eaboutify !</h2>

<p>Cher(e) <strong>{{ $data['name'] ?? 'Client' }}</strong>,</p>

<p>Nous sommes ravis de vous accueillir dans notre communauté. Chez Eaboutify, nous nous engageons à vous fournir les meilleures solutions digitales.</p>

<p><strong>Voici vos informations de compte :</strong></p>
<ul>
    <li><strong>Email :</strong> {{ $data['email'] ?? '' }}</li>
    <li><strong>Date d'inscription :</strong> {{ $data['registration_date'] ?? now()->format('d/m/Y') }}</li>
</ul>

<a href="{{ $data['login_url'] ?? 'https://eaboutify.com/login' }}" class="button">
    Accéder à votre compte
</a>

<p>Si vous avez des questions, n'hésitez pas à répondre à cet email.</p>
@endsection