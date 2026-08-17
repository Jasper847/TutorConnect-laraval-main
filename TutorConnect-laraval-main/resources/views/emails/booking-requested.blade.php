<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 30px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: #1e40af; padding: 30px; text-align: center; color: white; }
        .content { padding: 30px; }
        .box { background: #f1f5f9; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .btn { display: inline-block; background: #059669; color: white !important; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; margin-top: 15px; }
        .footer { padding: 20px 30px; text-align: center; font-size: 13px; color: #64748b; background: #f8fafc; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 24px;">New Booking Request</h1>
            <p style="margin: 5px 0 0; opacity: 0.85;">TutorConnect Marketplace</p>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $booking->tutor->name }}</strong>,</p>
            <p>You have received a new tutoring booking request from <strong>{{ $booking->student->name }}</strong>.</p>
            <div class="box">
                <p style="margin: 0 0 8px;"><strong>Booking Reference:</strong> #{{ $booking->booking_code }}</p>
                <p style="margin: 0 0 8px;"><strong>Subject:</strong> {{ $booking->subject?->name ?? 'General Tutoring' }}</p>
                <p style="margin: 0 0 8px;"><strong>Date:</strong> {{ $booking->booking_date->format('M d, Y') }}</p>
                <p style="margin: 0 0 8px;"><strong>Time:</strong> {{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</p>
                <p style="margin: 0 0 8px;"><strong>Mode:</strong> {{ ucfirst($booking->mode) }}</p>
                <p style="margin: 0;"><strong>Total Amount:</strong> ${{ number_format($booking->total_amount, 2) }}</p>
            </div>
            @if($booking->student_notes)
                <p><strong>Student Note:</strong> "{{ $booking->student_notes }}"</p>
            @endif
            <p>Please log in to your tutor portal to confirm or reschedule this request.</p>
            <a href="{{ url('/tutor/bookings') }}" class="btn">View & Respond to Booking</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} TutorConnect Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
