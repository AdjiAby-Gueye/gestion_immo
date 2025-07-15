{{-- @php
$date = $data[0]['datedemande'];
$dateFr = date('d F Y', strtotime($date));

@endphp --}}

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>PDF FACTURE EAU {{ date('d/m/Y') }}</title>

    <style>
        @page {
            margin: 20px 0px;
        }

        table,
        th,
        td {
            /* border: 1px solid #585b5e;
            border-collapse: collapse; */
            padding: .4rem;
        }

        th {
            font-size: 14px;
        }

        td {
            font-size: 12px;
        }

        .table {
            display: table;
            border-collapse: collapse;
            /* border: 1px  solid black; */
            letter-spacing: 1px;
            font-size: 13px;
            width: 100%;
        }

        .td,
        .th {
            border: 0.6px solid black;
            padding: 10px 5px;

        }

        .th {
            background-color: rgb(0 154 191);
            text-transform: uppercase;
            padding: 10px 5px;
            /* color: white; */
            font-weight: 600;
        }

        .td {
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 5.1cm;
            margin-left: 1.0cm;
            margin-right: 1.0cm;
            margin-bottom: 5cm;
            /*margin-bottom: 1.2cm;*/
            /*font-size: 1.2em;*/
            /*font: 12pt/1.5 'Raleway','Cambria', sans-serif;*/
            font-weight: 400;
            background: #fff;
            color: black;
            -webkit-print-color-adjust: exact;
        }

        /** Define the header rules **/
        .header {
            position: fixed;
            top: 0.8cm;
            height: 2cm;
            left: 1cm;
            right: 1cm;
            width: 100%;
        }

        /** Define the footer rules **/
        .footer {
            position: fixed;
            bottom: -5px;
            height: 2.3cm;
            margin-left: 1.0cm;
            margin-right: 1.0cm;
        }

        .pagenum:before {
            content: counter(page);
        }

        #break {
            display: inline;
        }

        #break:after {
            content: "\a";
            white-space: pre;
        }

        .box {
            display: flex;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .titre-top-0 {
            font-size: 24px;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .titre-top-1 {
            font-size: 20px;
            text-decoration: underline;
        }


        .titre-top-2 {
            font-size: 18px;
            line-height: 25px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mt-50 {
            margin-top: 50px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-20 {
            margin-bottom: 10px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .lh-25 {
            line-height: 25px;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .wd-40 {
            width: 40%;
        }

        .wd-50 {
            width: 50%;
        }

        .wd-80 {
            width: 80%;
        }

        .wd-100 {
            width: 100%;
        }

        .mx-auto {
            margin: 0 auto;
        }

        .break-auto {
            page-break-inside: auto;
        }

        .break-before {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        .clearfix:after {
            content: " ";
            visibility: hidden;
            display: block;
            height: 0;
            clear: both;
        }

        .item-border-b {
            border-bottom: 2px solid #000000;
            padding-bottom: 3px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .facture {
            border: 1px solid #ccc;
            padding: 20px;
        }

        .ligne {
            display: flex;
            justify-content: space-between;
        }

        .designation {
            flex: 1;
        }

        .montant {
            flex: 0 0 100px;
        }

        .total {
            text-align: right;
            margin-top: 20px;
        }
    </style>

</head>
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 20%" src="{{ $data[0]['contrat']['locataire']['entite']['image'] }}" alt="1">
    </div>
</header>


<body>

    <div>
        <div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -120px">
            <div>Dakar, {{ date('d/m/Y') }}</div>
        </div>


        <div style="display: grid; justify-content: center; width: 100%; margin-top: 40px;">
            <div style="display: grid; grid-template-columns: 1fr; text-align: left;margin-left: 30%;">
            <div style="font-size: 20px; font-weight: bold;">
                    Facture Eau au {{$data[0]['contrat']['date_dernier_facture_eau']}}
                    <br>
                </div>
                <div style="font-size: 20px; font-weight: bold;margin-left: 140px;margin-top: 10px;">
                    {{ $data[0]['contrat']['appartement']['immeuble']['nom'] }}
                </div>

            </div>
        </div>
        <div style="display: grid; justify-content: center; width: 100%;">
            <div style="display: grid; grid-template-columns: 1fr; text-align: left;margin-left: 70%;margin-top: 10px;">
                <p><b>A</b></p>
                <div style="font-size: 20px; font-weight: bold;">
                    @if($data[0]['contrat']['locataire']['typelocataire']['designation'] != "Physique")
                    {{$data[0]['contrat']['locataire']['nomentreprise'] }}
                    @else
                    {{$data[0]['contrat']['locataire']['nom'] }} {{$data[0]['contrat']['locataire']['prenom'] }}
                    @endif
                    <br>
                </div>
            </div>
        </div>



        <div style="font-size: 20px;text-align: left;padding-left: 70px;padding-right: 70px;margin-top: 30px;">


            <p style="margin-top: 10px">
                <i class="fa fa-underline" style="text-decoration:underline;font-weight: bold;text-transform: uppercase;" aria-hidden="true">
                    Occupant {{ $data[0]['contrat']['appartement']['nom'] }} :
                </i>
                <strong>
                    <strong>
                        <?php
                        if ($data[0]['contrat']['locataire']['nomentreprise']) {
                            if (isset($data[0]['contrat']['nomcompletbeneficiaire'])) {
                                echo $data[0]['contrat']['nomcompletbeneficiaire'];
                            } else {
                                echo $data[0]['contrat']['locataire']['prenompersonneacontacter'] . " " . $data[0]['contrat']['locataire']['nompersonneacontacter'];
                            }
                        } else {
                            echo $data[0]['contrat']['locataire']['prenom'] . " " . $data[0]['contrat']['locataire']['nom'];
                        }
                        ?>
                    </strong>
                </strong>
            </p>

            <table class="table mb-10">

                <tr class="tr">
                    <td class="td" style="font-size: 20px;background-color: #157347">Index</td>
                    <td class="td" style="font-size: 20px;background-color: #157347">Quantité (m3)</td>
                </tr>
                <tr class="tr">
                    <td class="td" style="font-size: 20px;">{{ $data[0]['debutperiode_format'] }}</td>
                    <td class="td" style="font-size: 20px;"> {{ number_format(($data[0]['quantitedebut']), 0, '', ' ') }}</td>
                </tr>
                <tr class="tr">
                    <td class="td" style="font-size: 20px;">{{ $data[0]['finperiode_format'] }}</td>
                    <td class="td" style="font-size: 20px;">{{ number_format(($data[0]['quantitefin']), 0, '', ' ') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="tr">
                    <td class="td" style="font-size: 20px;">Consommation (m3)</td>
                    <td class="td" style="font-size: 20px;">

                        {{ number_format(($data[0]['consommation']), 0, '', ' ') }}
                    </td>
                </tr>

                <tr class="tr">
                    <td class="td" style="font-size: 20px;">Prix m3 /d'eau (Frs)</td>
                    <td class="td" style="font-size: 20px;">
                        {{ number_format(($data[0]['prixmetrecube']), 0, '', ' ') }}
                    </td>
                </tr>
                <tr class="tr">
                    <td class="td" style="font-size: 20px;">Montant facture (Frs)</td>
                    <td class="td" style="font-size: 20px;">
                        {{ number_format(($data[0]['montantfacture']), 0, '', ' ') }}
                    </td>
                </tr>
                <tr class="tr">
                    <td class="td" style="font-size: 20px;">Solde anterieur (Frs)</td>
                    <td class="td" style="font-size: 20px;">
                        {{ number_format(($data[0]['soldeanterieur']), 0, '', ' ') }}

                    </td>
                </tr>
                <tr class="tr">
                    <td class="td" style="font-size: 20px;">TOTAL A REGLER (Frs)</td>
                    <td class="td" style="font-size: 20px;">
                        {{ number_format(($data[0]['montantfacture'] + $data[0]['soldeanterieur']), 0, '', ' ') }}

                    </td>
                </tr>
            </table>
            <p  style="font-size: 20px;">
                Arrêtée la présente facture à la somme de {{ $data[0]['montantfacture_format'] }} Francs CFA
            </p>
        </div>

        <div style="display: grid; justify-content: center; width: 100%;">
            <div style="display: grid; grid-template-columns: 1fr; text-align: left;margin-left: 5%;">
                <div style="font-size: 17px; font-weight: bold; text-decoration: underline;color: red;margin-bottom: 10px;">
                    A payer avant le {{$data[0]['finperiode_fr']}}
                </div>
                <div style="font-size: 17px; ">
                    <strong> N.B </strong>: le defaut de paiement , passé un delai de 5 jours après la date indiquée ci-dessus , entraine l'arrêt de l'approvisionnement en eau
                    <br>
                </div>
            </div>
        </div>
    </div>



    <footer style="position: fixed;bottom: 19%;width: 90%;">
        <div style="width: 100%;position: relative;">
            <div style="float: right;font-weight: bold">
                <p style="text-decoration:underline;">LA GERANTE </p>
                <br>
                <br>

                <br>
                <br>
                <p style="text-decoration:underline;">MARIE POUYE NGOM </p>
            </div>
            <div style="float: left;">
                <p style="font-weight: bold;font-size: 12px;">Coordonnées Bancaires de la SCI REYHAN : </p>
                <p style="font-weight: bold;font-size: 12px;">Code Banque : SN012 </p>
                <p style="font-weight: bold;font-size: 12px;margin-top:-5px !important">Code Guichet : 01217 </p>
                <p style="font-weight: bold;font-size: 12px;margin-top:-5px !important">Numéro de Compte : 036158967501 </p>
                <p style="font-weight: bold;font-size: 12px;margin-top:-5px !important">RIB : 96 </p>
                <p style="font-weight: bold;font-size: 12px;margin-top:-5px !important">Banque : CBAO </p>
                <p style="font-weight: bold;font-size: 12px;margin-top:-5px !important">Adresse : Soubedioune </p>
            </div>

        </div>
    </footer>


</body>



</html>
