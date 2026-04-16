<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f6f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #00a65a, #008d4c); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600;">
                                🎉 Welcome Aboard!
                            </h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 16px;">
                                {{ config('app.name') }} — Point of Sale System
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="font-size: 18px; color: #333; margin: 0 0 15px;">
                                Hello <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="font-size: 15px; color: #555; line-height: 1.7; margin: 0 0 20px;">
                                Thank you for registering with our POS system! Your account has been created successfully and you can now start using all the features available to you.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f9fafb; border-radius: 6px; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 20px 25px;">
                                        <p style="font-size: 14px; color: #666; margin: 0 0 8px;"><strong>Your Account Details:</strong></p>
                                        <p style="font-size: 14px; color: #555; margin: 0 0 5px;">📧 Email: <strong>{{ $user->email }}</strong></p>
                                        <p style="font-size: 14px; color: #555; margin: 0;">👤 Name: <strong>{{ $user->name }}</strong></p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 30px auto;">
                                <tr>
                                    <td style="background-color: #00a65a; border-radius: 6px;">
                                        <a href="{{ url('/login') }}" style="display: inline-block; padding: 14px 40px; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 600;">
                                            Log In to Your Account →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #888; line-height: 1.6; margin: 20px 0 0;">
                                If you did not create this account, please ignore this email.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-top: 1px solid #eee;">
                            <p style="font-size: 13px; color: #999; margin: 0;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
