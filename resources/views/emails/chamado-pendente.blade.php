@extends('emails.layout')

@section('content')
  <h2 style="margin:0 0 12px 0;color:#111;font-size:20px;">Chamado marcado como Pendente ⏸️</h2>
  <p style="margin:0 0 8px 0;color:#222;font-size:15px;">Chamado <strong>#{{ $chamado->id }}</strong></p>
  <p style="margin:0 0 8px 0;color:#222;font-size:15px;">Título: <strong>{{ $chamado->titulo }}</strong></p>
  <p style="margin:0 0 12px 0;color:#222;font-size:15px;">Motivo da pendência:<br>{!! nl2br(e($chamado->pendencia)) !!}</p>
  <p style="margin:0 0 16px 0;">
    <span style="display:inline-block;background:#f39c12;color:#fff;border-radius:12px;padding:4px 10px;font-size:12px;font-weight:700;">
      Status: Pendente
    </span>
  </p>
  <p>
    <a href="{{ route('tickets.show', $chamado->id) }}"
       style="display:inline-block;background:#0d6efd;color:#fff;text-decoration:none;padding:10px 16px;border-radius:6px;font-weight:700;">
      Ver no sistema
    </a>
  </p>
@endsection
