<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #001f3f;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .info-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #001f3f;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Petroleum Management System</h1>
            <p>Report Generated</p>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <p>Your requested <strong>{{ $report_type }} Report</strong> has been generated and is attached to this email.</p>
            
            <div class="info-box">
                <p><strong>Report Details:</strong></p>
                <ul>
                    <li>Report Type: {{ $report_type }}</li>
                    <li>Date Range: {{ date('M d, Y', strtotime($from_date)) }} - {{ date('M d, Y', strtotime($to_date)) }}</li>
                    <li>Generated: {{ date('F j, Y \a\t g:i A') }}</li>
                </ul>
            </div>
            
            <p>Please find the PDF report attached to this email.</p>
            
            <p>If you have any questions or need further assistance, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>
            Petroleum Management System Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email from Petroleum Management System.</p>
            <p>&copy; {{ date('Y') }} Petroleum Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
