<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reply on Ticket</title>
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
            background-color: #4f46e5;
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
        .reply-box {
            background-color: #f9fafb;
            border-left: 4px solid #4f46e5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #374151;
            font-style: italic;
        }
        .ticket_details {
            margin-top: 25px;
            margin-bottom: 25px;
            background-color: #f4f4f7;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #eaeaec;
        }
        .ticket_details table {
            width: 100%;
        }
        .ticket_details td {
            padding: 5px 0;
            font-size: 15px;
            color: #51545e;
        }
        .ticket_details .label {
            font-weight: bold;
            width: 30%;
        }
        .ticket_details .value {
            text-align: right;
            font-family: monospace;
            font-size: 16px;
            color: #333;
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
                    <h1>New Reply on Ticket #{{ $ticket_id }}</h1>
                    <p>Hi {{ $user_name }},</p>
                    <p>A new reply has been added to your support ticket.</p>
                    
                    <div class="reply-box">
                        "{{ \Illuminate\Support\Str::limit($reply_content, 200) }}"
                    </div>
                    
                    <div class="ticket_details">
                        <table>
                            <tr>
                                <td class="label">Ticket ID</td>
                                <td class="value">#{{ $ticket_id }}</td>
                            </tr>
                            <tr>
                                <td class="label">Subject</td>
                                <td class="value">{{ $ticket_subject }}</td>
                            </tr>
                            <tr>
                                <td class="label">Status</td>
                                <td class="value" style="text-transform: capitalize;">{{ $ticket_status }}</td>
                            </tr>
                        </table>
                    </div>

                    <p>Click the button below to view the full conversation and reply.</p>
                    
                    <div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
                        <a href="{{ url('/user/support') }}" class="button">View Ticket</a>
                    </div>

                    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
