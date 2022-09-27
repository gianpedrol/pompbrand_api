<!--------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senne Mail</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        tr.linha {
            max-width: 500px !important;
        }
    </style>
</head>

<body topmargin="0" marginwidth="0" marginheight="0">

    <table align="center" width="500">
        <tr style=" font-family: Montserrat; color: #343A40">
            <td align="center">
                @if (!empty($data['name']) )
                <h2 style="font-size: 18px; font-weight: bold">Olá {{$data['name']}}, tudo bem?</h2>
                @else
                <h2 style="font-size: 18px; font-weight: bold">Olá, tudo bem?</h2>
                @endif
            </td>
        </tr>
    </table>
    <table align="center" width="550">
        <tr style=" font-family: Montserrat; color: #343A40; font-weight: 500">
            <td align="center">
                <h3 style="text-align:center; max-width: 350h3x;">
                    login: {{$data['email']}}
                </h3>
                <h3 style="text-align:center; max-width: 350h3x;">
                    login: {{$senha}}
                </h3>
            </td>
        </tr>
    </table>

</body>

</html>
