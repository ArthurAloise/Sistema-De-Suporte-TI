<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1" />
</head>
<body style="margin:0;background:#f4f4f7;">

@php
  // usa config/brand.php -> ORG_NAME / ORG_UNIT do .env
  $org  = config('brand.org');
  $unit = trim(config('brand.unit') ?? '');
  $brandLine = $org && $unit ? "$org • $unit" : ($org ?: $unit);
@endphp

<!-- preheader (texto oculto no preview do cliente de e-mail) -->
<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
  Notificação — {{ $brandLine }}
</div>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f4f4f7;">
  <tr>
    <td align="center" style="padding:24px;">
      <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"
             style="width:600px;max-width:600px;background:#ffffff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);font-family:Arial,Helvetica,sans-serif;">
        <!-- header -->
        <tr>
          <td style="padding:16px 20px;border-bottom:1px solid #eee;">
            <div style="font-weight:700;font-size:18px;color:#e63946;">
              {{ $brandLine }}
            </div>
          </td>
        </tr>

        <!-- content -->
        <tr>
          <td style="padding:20px;">
            @yield('content')
          </td>
        </tr>

        <!-- footer -->
        <tr>
          <td style="padding:14px 20px;border-top:1px solid #eee;color:#888;font-size:12px;text-align:center;">
            Este é um e-mail automático. Não responda.
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
