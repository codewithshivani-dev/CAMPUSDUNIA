<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lesson Planner Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0a1e3c; font-size: 11px; }
        h1 { margin: 0 0 4px; font-size: 20px; }
        p { color: #4b6a8b; margin: 0 0 18px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2563eb; color: white; text-align: left; padding: 8px; }
        td { border-bottom: 1px solid #e6ecf3; padding: 8px; }
        .empty { text-align: center; padding: 20px; color: #6b8aaa; }
    </style>
</head>
<body>
    <h1>Lesson Planner Report</h1>
    <p>Generated {{ $generatedAt }}</p>
    <table>
        <thead>
            <tr><th>Class</th><th>Subject</th><th>Month</th><th>Plan</th><th>Type</th><th>Coverage</th><th>Topics</th></tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['class'] }}</td><td>{{ $row['subject'] }}</td><td>{{ $row['month'] }}</td><td>{{ $row['title'] }}</td>
                    <td>{{ $row['plan_type'] }}</td><td>{{ $row['coverage'] }}%</td>
                    <td>{{ $row['covered_topics'] }}/{{ $row['total_topics'] }}</td>
                </tr>
            @empty
                <tr><td class="empty" colspan="7">No report data found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>