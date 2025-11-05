@extends('emails.layout')

@section('content')
  <h2 style="margin:0 0 12px 0;color:#111;font-size:20px;">Verifique seu endereço de e-mail 📧</h2>

  <p style="margin:0 0 16px 0;color:#222;font-size:15px;">
    Olá! Seja bem-vindo(a) ao Sistema de Suporte TI.
  </p>

  <p style="margin:0 0 16px 0;color:#222;font-size:15px;">
    Por favor, clique no botão abaixo para confirmar seu e-mail e ativar sua conta.
  </p>

  <p style="margin:0 0 24px 0;">
    <a href="{{ $verificationUrl }}"
       style="display:inline-block;background:#0d6efd;color:#fff;text-decoration:none;padding:10px 16px;border-radius:6px;font-weight:700;">
      Confirmar meu E-mail
    </a>
  </p>

  <p style="margin:0;color:#222;font-size:15px;">
    Se você não criou esta conta, pode ignorar este e-mail com segurança.
  </p>
@endsection
