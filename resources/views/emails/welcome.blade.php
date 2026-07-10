{{-- resources/views/emails/welcome.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden;">
        <div style="background-color: #ea580c; padding: 24px; text-align: center;">
            <h1 style="color: white; margin: 0;">🛒 Quantum Commerce</h1>
        </div>
        <div style="padding: 32px;">
            <h2 style="color: #1f2937;">Welcome, {{ $user->name }}! 👋</h2>
            <p style="color: #4b5563; line-height: 1.6;">
                Thank you for registering with Quantum Commerce as a <strong>{{ $user->roleLabel() }}</strong>.
                Your account has been created successfully.
            </p>

            @if ($user->isCustomer())
                <p style="color: #4b5563; line-height: 1.6;">
                    You can now log in and start browsing products from our vendors right away.
                </p>
            @else
                <p style="color: #4b5563; line-height: 1.6;">
                    Your account is currently <strong>pending admin approval</strong>. We'll notify you
                    by email as soon as it's reviewed and approved.
                </p>
            @endif

            <a href="{{ url('/login') }}" style="display: inline-block; margin-top: 16px; background-color: #ea580c; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;">
                Log In to Your Account
            </a>
        </div>
        <div style="padding: 16px; text-align: center; color: #9ca3af; font-size: 12px;">
            Quantum Commerce &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>