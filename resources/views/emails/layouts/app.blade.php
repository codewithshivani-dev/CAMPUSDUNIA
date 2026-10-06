<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
</head>
<body style="font-family: Arial; background:#f6f6f6; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" style="background:#ffffff; padding:20px;">
                    <tr>
                        <td style="text-align:center; padding-bottom:20px;">
                            <h2>{{ config('app.name') }}</h2>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            @yield('content')
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:30px; font-size:12px; color:#999; text-align:center;">
                            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
