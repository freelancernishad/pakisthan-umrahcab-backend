<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            width: 100% !important;
        }
        .email-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
        }
        .email-content {
            width: 100%;
            margin: 0;
            padding: 0;
            max-width: 600px;
            margin: 0 auto;
        }
        .email-masthead {
            padding: 25px 0;
            text-align: center;
            background-color: #4f46e5; /* Indigo-600 */
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .email-masthead_name {
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
            text-shadow: 0 1px 0 rgba(0,0,0,0.1);
        }
        .email-body {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            border-bottom: 1px solid #eaeaec;
            border-left: 1px solid #eaeaec;
            border-right: 1px solid #eaeaec;
        }
        .email-body_inner {
            width: 570px;
            margin: 0 auto;
            padding: 35px;
        }
        .button {
            background-color: #4f46e5;
            border-top: 10px solid #4f46e5;
            border-right: 18px solid #4f46e5;
            border-bottom: 10px solid #4f46e5;
            border-left: 18px solid #4f46e5;
            display: inline-block;
            color: #FFF;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            font-weight: bold;
        }
        h1 {
            font-size: 22px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
            color: #333333;
        }
        p {
            margin-top: 0;
            color: #51545e;
            font-size: 16px;
            line-height: 1.625em;
        }
        .purchase_details {
            margin-top: 25px;
            margin-bottom: 25px;
            background-color: #f4f4f7;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #eaeaec;
        }
        .purchase_details table {
            width: 100%;
        }
        .purchase_details td {
            padding: 5px 0;
            font-size: 15px;
            color: #51545e;
        }
        .purchase_details .label {
            font-weight: bold;
            width: 40%;
        }
        .purchase_details .value {
            text-align: right;
            font-family: monospace;
            font-size: 16px;
            color: #333;
        }
        .total-row td {
            border-top: 1px solid #d1d5db;
            padding-top: 10px;
            font-weight: bold;
            color: #111827;
        }
        .footer {
            width: 570px;
            margin: 0 auto;
            padding: 25px;
            text-align: center;
        }
        .footer p {
            color: #aeaeae;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <!-- Masthead -->
            <div class="email-masthead">
                <a href="{{ config('app.url') }}" class="email-masthead_name">
                    {{ config('app.name') }}
                </a>
            </div>

            <!-- Body -->
            <div class="email-body">
                <div class="email-body_inner">
                    <h1>Order Confirmed!</h1>
                    <p>Hi {{ $user_name }},</p>
                    <p>Thank you for your purchase. We're excited to have you on board! Your plan has been activated successfully.</p>
                    
                    <div class="purchase_details">
                        <table>
                            <tr>
                                <td class="label">Effected Plan</td>
                                <td class="value">{{ $plan_name }}</td>
                            </tr>
                            <tr>
                                <td class="label">Billing Cycle</td>
                                <td class="value" style="text-transform: capitalize;">{{ $interval ?? 'one-time' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Start Date</td>
                                <td class="value">{{ $start_date }}</td>
                            </tr>
                            <tr>
                                <td class="label">Next Payment</td>
                                <td class="value">{{ $next_payment_date ?? 'N/A' }}</td>
                            </tr>
                            <tr class="total-row">
                                <td>Amount Paid</td>
                                <td class="value" style="color: #4f46e5;">${{ number_format($amount, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                    <p>You can manage your subscription and view your invoice history from your dashboard.</p>
                    
                    <div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
                        <a href="{{ url('/user/dashboard') }}" class="button">Go to Dashboard</a>
                    </div>

                    <p>If you have any questions, feel free to reply to this email or contact our support team.</p>
                    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>You received this email because you purchased a plan on our platform.</p>
            </div>
        </div>
    </div>
</body>
</html>
