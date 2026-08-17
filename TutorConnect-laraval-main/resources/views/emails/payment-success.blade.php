<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking & Payment Confirmed</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #1e293b; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #1e40af, #059669); padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; }
        .content { padding: 30px; }
        .badge { display: inline-block; background: #ecfdf5; color: #047857; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; margin-top: 8px; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        .table th { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .total-row { font-weight: 700; font-size: 16px; color: #059669; }
        .demo-notice { background: #fefce8; border: 1px solid #fef08a; border-radius: 12px; padding: 15px; font-size: 12px; color: #854d0e; margin-top: 20px; }
        .footer { padding: 20px 30px; background: #f8fafc; border-top: 1px solid #f1f5f9; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Tutor<span style="color: #6ee7b7;">Connect</span></h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">1-on-1 Personalized Tutoring</p>
        </div>

        <div class="content">
            <h2 style="font-size: 18px; margin-top: 0;">Your Session is Confirmed! 🎉</h2>
            <p style="font-size: 14px; line-height: 1.6; color: #475569;">
                Hello <strong>{{ $booking->student->name }}</strong>, your tutoring session with <strong>{{ $booking->tutor->name }}</strong> has been successfully booked and paid for in Sandbox mode.
            </p>

            <table class="table">
                <tr>
                    <th>Booking Code</th>
                    <td><strong>#{{ $booking->booking_code }}</strong></td>
                </tr>
                <tr>
                    <th>Subject</th>
                    <td>{{ $booking->subject }}</td>
                </tr>
                <tr>
                    <th>Date & Time</th>
                    <td>{{ $booking->booking_date->format('l, F j, Y') }} at {{ date('g:i A', strtotime($booking->start_time)) }}</td>
                </tr>
                <tr>
                    <th>Session Mode</th>
                    <td>{{ ucfirst($booking->mode) }} (1.0 Hour)</td>
                </tr>
                <tr>
                    <th>Tutor Email</th>
                    <td>{{ $booking->tutor->email }}</td>
                </tr>
                <tr class="total-row">
                    <th>Total Amount</th>
                    <td>PKR {{ number_format($booking->total_amount, 0) }} (~${{ number_format($booking->total_amount / 280, 2) }} USD)</td>
                </tr>
            </table>

            <div class="demo-notice">
                ⚠️ <strong>Demo Sandbox Transaction:</strong> This was processed as a demonstration transaction. No real credit card charges occurred.
            </div>
        </div>

        <div class="footer">
            &copy; 2025 TutorConnect Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
