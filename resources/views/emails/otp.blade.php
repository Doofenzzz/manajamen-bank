<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0b63f6 0%, #084dcc 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700;">Verifikasi Email Anda</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #1e293b; font-size: 16px; line-height: 1.6;">
                                Halo <strong>{{ $name }}</strong>,
                            </p>
                            
                            <p style="margin: 0 0 30px; color: #6b7280; font-size: 15px; line-height: 1.6;">
                                Terima kasih telah mendaftar di PT BPR Sarimadu. Untuk melanjutkan, silakan gunakan kode OTP berikut:
                            </p>
                            
                            <!-- OTP Box -->
                            <div style="background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%); border: 2px dashed #0b63f6; border-radius: 12px; padding: 30px; text-align: center; margin: 30px 0;">
                                <p style="margin: 0 0 10px; color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">
                                    Kode Verifikasi
                                </p>
                                <p style="margin: 0; color: #003366; font-size: 42px; font-weight: 700; letter-spacing: 8px;">
                                    {{ $otp }}
                                </p>
                            </div>
                            
                            <p style="margin: 0 0 20px; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                <strong>Kode ini akan kadaluarsa dalam 5 menit.</strong> Jangan bagikan kode ini kepada siapapun.
                            </p>
                            
                            <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Jika Anda tidak melakukan pendaftaran, abaikan email ini.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 10px; color: #6b7280; font-size: 13px;">
                                © {{ date('Y') }} PT BPR Sarimadu. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                Email ini dikirim secara otomatis, mohon tidak membalas.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
