<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Lecture Assignment</title>
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
            max-width: 700px;
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
        .lecture-details {
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
            padding: 8px;
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
        .schedule-card h4 {
            margin-top: 0;
            color: #4a5568;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-online {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-offline {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-subject {
            background-color: #667eea;
            color: white;
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
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #721c24;
        }
        .lecture-count {
            background-color: #667eea;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 15px;
        }
        hr {
            margin: 20px 0;
            border: none;
            border-top: 2px solid #e0e0e0;
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
            <h1>📚 New Lecture Assignment{{ $lecturesCount > 1 ? 's' : '' }}</h1>
            <p>You have been assigned {{ $lecturesCount }} new lecture{{ $lecturesCount > 1 ? 's' : '' }}</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $employeeName }}</strong>,</p>
            
            <p>We are pleased to inform you that the following lecture{{ $lecturesCount > 1 ? 's have' : ' has' }} been assigned to you.</p>
            
            <div class="lecture-count">
                📖 Total Lectures Assigned: {{ $lecturesCount }}
            </div>
            
            @foreach($lectures as $index => $lecture)
            <div class="lecture-details">
                <h3 style="margin-top: 0; color: #667eea; display: flex; justify-content: space-between; align-items: center;">
                    <span>Lecture #{{ $index + 1 }}</span>
                    @if($lecture['lecture_mode'] === 'online')
                        <span class="badge badge-online">🖥️ Online</span>
                    @else
                        <span class="badge badge-offline">🏢 Offline</span>
                    @endif
                </h3>
                
                <!-- Subject Details Table -->
                <table class="info-table">
                    <tr>
                        <td class="info-label">📖 Subject:</td>
                        <td class="info-value"><strong>{{ $lecture['subject_name'] }}</strong></td>
                    </tr>
                    @if($lecture['course_type'])
                    <tr>
                        <td class="info-label">📚 Course Type:</td>
                        <td class="info-value">{{ $lecture['course_type'] }}</td>
                    </tr>
                    @endif
                    @if($lecture['sub_type'])
                    <tr>
                        <td class="info-label">🎓 Sub Type/Branch:</td>
                        <td class="info-value">{{ $lecture['sub_type'] }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-label">📋 Subject/Class:</td>
                        <td class="info-value">{{ $lecture['display_name'] }}</td>
                    </tr>
                    @if($lecture['section_name'])
                    <tr>
                        <td class="info-label">👥 Section:</td>
                        <td class="info-value"><strong>{{ $lecture['section_name'] }}</strong></td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-label">📅 Semester:</td>
                        <td class="info-value">
                            @if($lecture['semester_id'] === 'all_semesters')
                                All Semesters
                            @else
                                Semester {{ $lecture['semester_id'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">📆 Assignment Date:</td>
                        <td class="info-value">{{ $lecture['assigned_date'] }}</td>
                    </tr>
                </table>
                
                <!-- Schedule Details -->
                <div class="schedule-card">
                    <h4>⏰ Schedule Details</h4>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">🔄 Frequency:</td>
                            <td class="info-value">
                                @if($lecture['frequency'] === 'one_time')
                                    One-time Lecture
                                @elseif($lecture['frequency'] === 'daily')
                                    Daily
                                @elseif($lecture['frequency'] === 'weekly')
                                    Weekly
                                @elseif($lecture['frequency'] === 'monthly')
                                    Monthly
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">⏰ Time:</td>
                            <td class="info-value">
                                {{ date('h:i A', strtotime($lecture['start_time'])) }} - {{ date('h:i A', strtotime($lecture['end_time'])) }}
                            </td>
                        </tr>
                        @if($lecture['frequency'] === 'weekly' && !empty($lecture['days_of_week']))
                        <tr>
                            <td class="info-label">📅 Days:</td>
                            <td class="info-value">
                                <div class="days-list">
                                    @foreach($lecture['days_of_week'] as $day)
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
                        
                        @if($lecture['frequency'] === 'monthly' && $lecture['day_of_month'])
                        <tr>
                            <td class="info-label">📅 Day of Month:</td>
                            <td class="info-value">{{ $lecture['day_of_month'] }} of each month</td>
                        </tr>
                        @endif
                        
                        <tr>
                            <td class="info-label">✅ Valid From:</td>
                            <td class="info-value">{{ $lecture['valid_from'] }}</td>
                        </tr>
                        
                        @if($lecture['valid_to'])
                        <tr>
                            <td class="info-label">⏹️ Valid To:</td>
                            <td class="info-value">{{ $lecture['valid_to'] }}</td>
                        </tr>
                        @endif
                        
                        @if($lecture['location'])
                        <tr>
                            <td class="info-label">📍 Location:</td>
                            <td class="info-value">{{ $lecture['location'] }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                
                <!-- Online Meeting Details (if applicable) -->
                @if($lecture['lecture_mode'] === 'online' && $lecture['meeting_link'])
                <div style="background-color: #d4edda; padding: 15px; border-radius: 8px; margin-top: 15px;">
                    <h4 style="margin-top: 0;">🔗 Join Online Session</h4>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Meeting Link:</td>
                            <td class="info-value"><a href="{{ $lecture['meeting_link'] }}" target="_blank">{{ $lecture['meeting_link'] }}</a></td>
                        </tr>
                        @if($lecture['meeting_password'])
                        <tr>
                            <td class="info-label">Password:</td>
                            <td class="info-value"><code>{{ $lecture['meeting_password'] }}</code></td>
                        </tr>
                        @endif
                        @if($lecture['meeting_instructions'])
                        <tr>
                            <td class="info-label">Instructions:</td>
                            <td class="info-value">{{ $lecture['meeting_instructions'] }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                @endif
            </div>
            @if(!$loop->last)
            <hr>
            @endif
            @endforeach
            
            <!-- Important Instructions -->
            <div class="warning">
                <strong>⚠️ Important Notes:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Please check your schedule regularly for updates.</li>
                    <li>Ensure you are prepared before each lecture.</li>
                    <li>Contact the academic coordinator for any schedule conflicts.</li>
                    <li>Mark attendance as per institute guidelines.</li>
                    <li>For online lectures, use the provided meeting link to join.</li>
                </ul>
            </div>
        
        </div>
        
        
    </div>
</body>
</html>