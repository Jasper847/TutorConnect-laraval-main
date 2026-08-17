<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 30px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: #1e40af; padding: 30px; text-align: center; color: white; }
        .content { padding: 30px; text-align: center; }
        .btn { display: inline-block; background: #059669; color: white !important; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; margin-top: 20px; }
        .footer { padding: 20px 30px; text-align: center; font-size: 13px; color: #64748b; background: #f8fafc; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 24px;">Your Session is Complete!</h1>
            <p style="margin: 5px 0 0; opacity: 0.9;">TutorConnect Marketplace</p>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $booking->student->name }}</strong>,</p>
            <p>Your tutoring session for <strong>{{ $booking->subject?->name ?? 'Tutoring' }}</strong> with <strong>{{ $booking->tutor->name }}</strong> has concluded.</p>
            <p>How was your learning experience? Your feedback helps students find the best mentors and allows tutors to excel.</p>
            <a href="{{ url('/student/bookings/' . $booking->id . '/review') }}" class="btn">⭐ Rate & Review Tutor</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} TutorConnect Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
