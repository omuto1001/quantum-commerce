{{-- resources/views/emails/approved.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden;">
        <div style="background-color: #16a34a; padding: 24px; text-align: center;">
            <h1 style="color: white; margin: 0;">✅ Account Approved!</h1>
        </div>
        <div style="padding: 32px;">
            <h2 style="color: #1f2937;">Congratulations, {{ $user->name }}!</h2>
            <p style="color: #4b5563; line-height: 1.6;">
                Great news — your {{ $user->roleLabel() }} account on Quantum Commerce has been
                reviewed and <strong>approved</strong> by our team.
            </p>
            <p style="color: #4b5563; line-height: 1.6;">
                @if ($user->isVendor())
                    You can now log in and start listing your products for sale.
                @elseif ($user->isRider())
                    You can now log in and start accepting delivery requests.
                @endif
            </p>

            <a href="{{ url('/login') }}" style="display: inline-block; margin-top: 16px; background-color: #16a34a; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;">
                Log In Now
            </a>
        </div>
        <div style="padding: 16px; text-align: center; color: #9ca3af; font-size: 12px;">
            Quantum Commerce &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>