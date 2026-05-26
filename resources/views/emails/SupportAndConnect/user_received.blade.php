<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Message Received</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05); overflow: hidden;">

        <div style="background-color: #4a90e2; color: white; padding: 20px 30px;">
            <h2 style="margin: 0;">✅ Contact Message Received</h2>
        </div>

        <div style="padding: 30px;">
            <p style="font-size: 16px; color: #333;">Hello {{ $name }},</p>

            <p style="font-size: 15px; color: #555;">
                Thank you for contacting us. We have received your message and will get back to you shortly.
            </p>

            <h4 style="margin-top: 20px; color: #333;">Your Message:</h4>

            <table style="width: 100%; margin-top: 10px; font-size: 14px; color: #333;">
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
                We will contact you as soon as possible.
            </p>

            <p style="font-size: 14px; color: #777;">
                Best regards,<br>
                <strong>Support Team</strong>
            </p>
        </div>

        <div style="background-color: #f0f0f0; text-align: center; padding: 15px; font-size: 13px; color: #999;">
            This is an automated message from your contact system.
        </div>
    </div>
</body>
</html>
