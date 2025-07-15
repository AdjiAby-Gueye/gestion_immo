<!DOCTYPE html>
<html>
    <head>
        <title>QR Code CARTE</title>
    </head>
    <body>
        <div class="visible-print text-center">
            <h1>QR code pour carte</h1>

            {!! QrCode::size(250)->generate($qrcode); !!}

        </div>
    </body>
</html>
