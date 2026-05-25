<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP — ZLM.ID</title>
    <style>
        body { font-family: 'Inter', -apple-system, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; }
        .header { background: #363230; padding: 32px 24px; text-align: center; }
        .header h1 { color: #DF5E1D; font-size: 20px; margin: 0; font-weight: 700; letter-spacing: 1px; }
        .body { padding: 32px 24px; }
        .greeting { font-size: 16px; color: #363230; margin-bottom: 8px; }
        .text { font-size: 14px; color: #6b7280; line-height: 1.6; margin-bottom: 24px; }
        .code-wrapper { background: #fafafa; border: 2px dashed #DF5E1D; border-radius: 12px; padding: 24px; text-align: center; margin-bottom: 24px; }
        .code { font-size: 36px; font-weight: 700; letter-spacing: 12px; color: #363230; font-family: 'Courier New', monospace; }
        .note { font-size: 12px; color: #9ca3af; text-align: center; }
        .footer { padding: 24px; text-align: center; border-top: 1px solid #f0f0f0; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ZLM.ID</h1>
        </div>
        <div class="body">
            <p class="greeting">Halo, {{ $name }}!</p>
            <p class="text">
                Gunakan kode OTP di bawah ini untuk memverifikasi akun Anda.
                Kode ini berlaku selama <strong>5 menit</strong>.
            </p>
            <div class="code-wrapper">
                <div class="code">{{ $otp }}</div>
            </div>
            <p class="note">
                Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ZLM.ID — All rights reserved.</p>
        </div>
    </div>
</body>
</html>
