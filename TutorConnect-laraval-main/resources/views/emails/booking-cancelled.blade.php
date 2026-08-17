<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 30px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: #dc2626; padding: 30px; text-align: center; color: white; }
        .content { padding: 30px; }
        .box { background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 8px; padding: 15px 20px; margin: 20px 0; }
        .footer { padding: 20px 30px; text-align: center; font-size: 13px; color: #64748b; background: #f8fafc; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 24px;">Booking Cancelled</h1>
            <p style="margin: 5px 0 0; opacity: 0.9;">TutorConnect Marketplace</p>
        </div>
        <div class="content">
            <p>This is to inform you that booking <strong>#{{ $booking->booking_code }}</strong> scheduled for <strong>{{ $booking->booking_date->format('M d, Y') }}</strong> has been cancelled.</p>
            @if($reason || $booking->cancellation_reason)
                <div class="box">
                    <p style="margin: 0;"><strong>Reason:</strong> {{ $reason ?: $booking->cancellation_reason }}</p>
                </div>
            @endif
            <p>If you have any questions or would like to re-schedule, please visit TutorConnect.</p>
            <p><a href="{{ url('/') }}" style="color: #1e40af; font-weight: 600;">Go to TutorConnect Homepage</a></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} TutorConnect Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
