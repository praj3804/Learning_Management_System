<!DOCTYPE html>
<html>

<head>
    <title>Certificate Of Completion</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            text-align: center;
        }

        .certificate-container {
            border: 10px solid #4f46e5;
            padding: 50px;
            margin: 20px;
            text-align: center;
            background-color: #f9f9ff;
        }

        .header {
            font-size: 50px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 30px;
        }

        .subheader {
            font-size: 25px;
            margin-bottom: 50px;
            color: #555;
        }

        .name {
            font-size: 40px;
            font-weight: bold;
            color: #333;
            margin-bottom: 50px;
        }

        .details {
            font-size: 20px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 50px;
        }

        .footer {
            font-size: 16px;
            margin-top: 50px;
            font-style: italic;
            color: #777;
        }

        .signature-line {
            border-top: 2px solid #555;
            width: 300px;
            margin: 50px auto 10px auto;
        }

        .seal {
            font-size: 80px;
            color: gold;
            display: inline-block;
            border: 5px solid gold;
            border-radius: 50%;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="header">Certificate of Completion</div>
        <div class="subheader">This is to certify that</div>

        <div class="name">{{ $user->name }}</div>

        <div class="details">
            has successfully completed all required course videos Phase Training<br>
            and passed all required quizzes with an average score of:<br><br>
            <strong>
                @php
                    $avg = $results->avg('percentage') ?? 0;
                @endphp
                {{ number_format($avg, 1) }}%
            </strong>
        </div>

        <div>
            <span style="font-size: 60px; color: #eab308; display: inline-block;">✦</span>
        </div>

        <div class="signature-line"></div>
        <div class="footer">
            Authorized Signature <br>
            Date: {{ \Carbon\Carbon::now()->format('F j, Y') }}<br>
            LMS Platform
        </div>
    </div>
</body>

</html>