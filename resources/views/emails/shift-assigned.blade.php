<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Assignment Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .shift-details {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 35%;
            background-color: #f8f9fa;
        }
        .info-value {
            color: #333;
            width: 65%;
        }
        .schedule-card {
            background-color: #e8eefd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-individual {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-department {
            background-color: #fff3cd;
            color: #856404;
        }
        .days-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }
        .day-badge {
            background-color: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #5a67d8;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
        }
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .info-label {
                width: 100%;
                display: block;
            }
            .info-value {
                width: 100%;
                display: block;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🕒 Shift Assignment Notification</h1>
            <p>Your work schedule has been updated</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>We are pleased to inform you that the following shift has been assigned to you.</p>
            
            <div class="shift-details">
                <h3 style="margin-top: 0; color: #667eea;">📋 Shift Details</h3>
                
                <table class="info-table">
                    <tr>
                        <td class="info-label">Shift Name:</td>
                        <td class="info-value"><strong>{{ $shiftName }}</strong></td>
                    </tr>
                    <tr>
                        <td class="info-label">Shift Timing:</td>
                        <td class="info-value">{{ $startTime }} - {{ $endTime }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Working Hours:</td>
                        <td class="info-value">{{ $workingHours }} hours</td>
                    </tr>
                    @if($breakStartTime && $breakEndTime)
                    <tr>
                        <td class="info-label">Break Time:</td>
                        <td class="info-value">{{ $breakStartTime }} - {{ $breakEndTime }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-label">Grace Minutes:</td>
                        <td class="info-value">{{ $graceMinutes }} minutes</td>
                    </tr>
                    @if($weeklyOffDays && count($weeklyOffDays) > 0)
                    <tr>
                        <td class="info-label">Weekly Off:</td>
                        <td class="info-value">
                            <div class="days-list">
                                @foreach($weeklyOffDays as $day)
                                    <span class="day-badge">
                                        @if($day === 'monday') Mon
                                        @elseif($day === 'tuesday') Tue
                                        @elseif($day === 'wednesday') Wed
                                        @elseif($day === 'thursday') Thu
                                        @elseif($day === 'friday') Fri
                                        @elseif($day === 'saturday') Sat
                                        @elseif($day === 'sunday') Sun
                                        @else {{ ucfirst($day) }}
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-label">Valid From:</td>
                        <td class="info-value">{{ $startDate }}</td>
                    </tr>
                    @if($endDate)
                    <tr>
                        <td class="info-label">Valid To:</td>
                        <td class="info-value">{{ $endDate }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-label">Assignment Type:</td>
                        <td class="info-value">
                            @if($assignmentType === 'individual')
                                <span class="badge badge-individual">👤 Individual Assignment</span>
                            @else
                                <span class="badge badge-department">🏢 Department Assignment</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="schedule-card" style="display: none;">
                <h4>⏰ Shift Schedule Information</h4>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Half Day Hours: {{ $halfDayHours }} hours</li>
                    <li>Short Leave Hours: {{ $shortLeaveHours }} hours</li>
                    <li>Break Duration: {{ $breakMinutes }} minutes</li>
                </ul>
            </div>
            
            <!-- Important Instructions -->
            <div class="warning">
                <strong>📌 Important Notes:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Please report on time as per your shift schedule.</li>
                    <li>Mark your attendance using the institute's attendance system.</li>
                    <li>For shift change requests, please contact your department head.</li>
                    <li>Late attendance beyond grace period will be marked as late arrival.</li>
                    <li>Notify HR in case of any shift-related issues.</li>
                </ul>
            </div>
            
           
        </div>
        
       
    </div>
</body>
</html>