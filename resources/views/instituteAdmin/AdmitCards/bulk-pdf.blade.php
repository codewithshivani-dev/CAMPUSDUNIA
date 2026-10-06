<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admit Cards - Bulk Download</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>
</head>

<body>
    @forelse($cards as $cardData)

        @include('instituteAdmin.AdmitCards._document', array_merge(
            $cardData,
            [
                'isLastPage' => $loop->last,
            ]
        ))

    @empty
        <div style="
            width: 194mm;
            height: 281mm;
            box-sizing: border-box;
            text-align: center;
            padding-top: 120mm;
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
        ">
            No admit cards available.
        </div>
    @endforelse
</body>
</html>
