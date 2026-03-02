<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Welcome, {{ $lead->name }}!</div>
        
        <p>Hi {{ $lead->name }},</p>
        
        <p>Thank you for reaching out to <strong>{{ $lead->tenant->name }}</strong>. We have received your information and a member of our team will be in touch with you shortly.</p>
        
        <p>Here are the details we received:</p>
        <ul>
            <li><strong>Name:</strong> {{ $lead->name }}</li>
            <li><strong>Email:</strong> {{ $lead->email }}</li>
            @if($lead->phone)
                <li><strong>Phone:</strong> {{ $lead->phone }}</li>
            @endif
        </ul>
        
        <p>We appreciate your interest!</p>
        
        <p>Best regards,<br>The Team at {{ $lead->tenant->name }}</p>

        <div class="footer">
            This is an automated email. You are receiving this because you filled out a contact form.
        </div>
    </div>
</body>
</html>
