<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Admit Card</title>

    <style>

        /*
        |--------------------------------------------------------------------------
        | DOMPDF SAFE A4 PDF
        |--------------------------------------------------------------------------
        | A4 Portrait
        | Page margin = 8mm
        | Printable width  = 194mm
        | Printable height = 281mm
        |--------------------------------------------------------------------------
        */

        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 194mm;
            background: #ffffff;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

    </style>
</head>

<body>

    @include(
        'instituteAdmin.AdmitCards._document',
        [
            'instructionTexts' => $instructionTexts ?? [],
            'isLastPage' => true,
        ]
    )

</body>

</html>