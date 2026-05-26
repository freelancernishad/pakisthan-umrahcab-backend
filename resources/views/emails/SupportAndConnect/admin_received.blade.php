<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Contact Message</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05); overflow: hidden;">
        
        <div style="background-color: #4a90e2; color: white; padding: 20px 30px;">
            <h2 style="margin: 0;">📩 New Contact Message</h2>
        </div>

        <div style="padding: 30px;">
            <p style="font-size: 16px; color: #333;">Hello Admin,</p>
            <p style="font-size: 15px; color: #555;">
                You have received a new message via the contact form. Below are the details:
            </p>

            <table style="width: 100%; margin-top: 20px; font-size: 14px; color: #333;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">👤 Name:</td>
                    <td>{{ $name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">✉️ Email:</td>
                    <td>{{ $email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">📌 Subject:</td>
                    <td>{{ $subject }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; vertical-align: top;">📝 Message:</td>
                    <td style="white-space: pre-line;">{{ $user_message }}</td>
                </tr>
            </table>

            <p style="margin-top: 30px; font-size: 14px; color: #777;">
                Please respond to this message as soon as possible.
            </p>

            <p style="font-size: 14px; color: #777;">
                Thanks,<br>
                <strong>Your Website Bot</strong>
            </p>
        </div>

        <div style="background-color: #f0f0f0; text-align: center; padding: 15px; font-size: 13px; color: #999;">
            This is an automated message from your contact system.
        </div>
    </div>
</body>
</html>
