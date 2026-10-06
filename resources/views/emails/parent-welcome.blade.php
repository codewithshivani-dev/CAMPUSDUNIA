<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Child's Enrollment Confirmation</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: auto; padding: 20px; }
        .header { background: #4caf50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .info-box { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #4caf50; }
        .button { background: #4caf50; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Enrollment Confirmation</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $parentName }}</strong>,</p>
            <p>We are pleased to inform you that your child <strong>{{ $studentName }}</strong> has been successfully enrolled at <strong>{{ $instituteName }}</strong>.</p>
            
            <div class="info-box">
                <h3>Enrollment Details:</h3>
                <p><strong>Student Name:</strong> {{ $studentName }}</p>
                <p><strong>Registration Number:</strong> {{ $registrationNumber }}</p>
                <p><strong>Enrollment Date:</strong> {{ $enrollmentDate }}</p>
                <p><strong>Student Email:</strong> {{ $studentEmail }}</p>
                @if($studentPassword)
                <p><strong>Temporary Password:</strong> {{ $studentPassword }}</p>
                @endif
            </div>
            
            <p>Your child can now access the student portal using the credentials above.</p>
            
            <div style="text-align: center; margin-top: 20px; display:none;">
                <a href="{{ url('/login') }}" class="button">Go to Portal</a>
            </div>
        </div>
    </div>
</body>
</html>