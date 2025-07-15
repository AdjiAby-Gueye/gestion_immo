@php
use App\Helpers\NombreEnLettre;
use App\Outil;
@endphp

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>PDF FACTURE LOCATION {{date('d/m/Y')}}</title>

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
            padding: 15px 5px;

        }

        .th {
            background-color: rgb(0 154 191);
            text-transform: uppercase;
            padding: 15px 5px;
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
            margin-top: 3.1cm;
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
            bottom: -10px;
            height: 2.3cm;
            margin-left: 0cm;
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
            margin-bottom: 20px;
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
        <img style="width: 20%" src="{{$data[0]['contrat']['appartement']['entite']['image']}}" alt="1">
    </div>
</header>





<body>
    <div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -90px">
        <div>Dakar, {{$data[0]['datefacture_format']}}</div>
    </div>
    <div style="font-size: 15px;font-weight: bold;text-align: center;text-transform: uppercase">

        {{-- @dd($data) --}}
        <p style="text-decoration:underline;font-size: 30px;">

            @if ($data[0]['typefacture']['designation'] == "Caution")
            FACTURE {{$data[0]['typefacture']['designation']}}
            @else
            APPEL DE {{$data[0]['typefacture']['designation']}}S
            @endif
        </p>
        <br>

    </div>


    <div style="font-size: 20px;font-weight: bold;text-align: right;margin: 10px">
        @if ($data[0]['contrat']['locataire'] && $data[0]['contrat']['locataire']['nomentreprise'])
        {{$data[0]['contrat']['locataire']['nomentreprise']}}
        @else
        {{$data[0]['contrat']['locataire']['prenom']." ".$data[0]['contrat']['locataire']['nom']}}
        @endif
    </div>

    <div style="font-size: 18px;font-weight: bold;text-align: right;margin: 10px; word-wrap: break-word; overflow-wrap: break-word; max-width: 350px; margin-left: 520px;">
        {{$data[0]['contrat']['locataire']['adresseentreprise']}}
    </div>

<!--    <div style="font-size: 20px;font-weight: bold;text-align: right;margin: 10px">-->
{{-- <!--        {{$data[0]['contrat']['appartement']['immeuble']['adresse']}}--> --}}
<!--    </div>-->


    <div style="font-size: 15px;text-align: left;margin: 30px 10px 10px;">


        <p style="">
            <i class="fa fa-underline" style="text-decoration:underline;font-weight: bold;text-transform: uppercase" aria-hidden="true">
                OBJET :
            </i><strong>Loyers {{$data[0]['contrat']['appartement']['nom']}}</strong>
        <p style="margin-left: 8%"><strong>{{$data[0]['contrat']['appartement']['immeuble']['nom']}}</strong></p>
        <p style="margin-left: 8%">

            <strong>
                <?php
                if ($data[0]['contrat']['locataire']['nomentreprise']) {
                    if (isset($data[0]['contrat']['nomcompletbeneficiaire'])) {
                        echo "Pour le compte de " .  $data[0]['contrat']['nomcompletbeneficiaire'];
                    } else {
                        echo "Pour le compte de " . $data[0]['contrat']['locataire']['prenompersonneacontacter'] . " " . $data[0]['contrat']['locataire']['nompersonneacontacter'];
                    }
                }
                ?>
            </strong>
        </p>
        </p>


    </div>

    <?php
        $totalCumul = 0;

        // $montantloyerbaseavenant_format = Outil::formatPrixToMonetaire($data[0]['montantloyerbase_avenant']);
        $montantloyerbaseavenant_format = Outil::formatPrixToMonetaire($data[0]['montantloyerbase_avenant']);
        if (empty($montantloyerbaseavenant_format)) {
             $montantloyerbaseavenant_format = 0;
        }

        $montantloyertomavenant_format = Outil::formatPrixToMonetaire($data[0]['montantloyertom_avenant']);
        if (empty($montantloyertomavenant_format)) {
             $montantloyertomavenant_format = 0;
        }

        $montantchargeavenant_format = Outil::formatPrixToMonetaire($data[0]['montantcharge_avenant']);
        if (empty($montantchargeavenant_format)) {
             $montantchargeavenant_format = 0;
        }

        
    ?>

    <div style="font-size: 15px;text-align: left;margin: 8px;">

        <p style="text-decoration:underline;font-weight: bold;font-size: 20px;">
            DESIGNATION
        </p>
        @if ($data[0]['periodes_text'])
        <p class="" style="font-size: 20px;"> {{ $data[0]['periodes_text'] }}</p>
        @endif
        <p style="font-size: 20px;">Details du loyer mensuel</p>

        <table style="font-size: 20px;margin-left: 8%" class="table">
            <tbody>
            @if ($data[0]['montantloyerbase_avenant'])
                <tr style="font-size: 20px">
                    <td colspan="2" style="font-size: 20px;">- Loyer base</td>
                    {{-- <td style="font-size: 20px;">{{$data[0]['montantloyerbase_avenant']}} F</td> --}}
                    <td style="font-size: 20px;">{{$montantloyerbaseavenant_format}} F</td>
                </tr>
                <?php
                    $totalCumul += (int) ($data[0]['montantloyerbase_avenant']);
                ?>
            @endif
            @if ($data[0]['montantloyertom_avenant'])
                <tr style="font-size: 20px">
                    <td style="font-size: 20px;" colspan="2">- Tom</td>
                    <td style="font-size: 20px;">{{ $montantloyertomavenant_format }} F</td>
                    {{-- <td style="font-size: 20px;">{{$data[0]['montantloyertom_avenant']}} F</td> --}}
                </tr>
                <?php
                    $totalCumul += (int) ($data[0]['montantloyertom_avenant']);
                ?>
            @endif
            @if ($data[0]['montantcharge_avenant'])
                <tr>
                    <td style="font-size: 20px;" colspan="2">- Charges</td>
                    <td style="font-size: 20px;">{{ $montantchargeavenant_format }} F</td>
                    {{-- <td style="font-size: 20px;">{{$data[0]['montantcharge_avenant']}} F</td> --}}
                </tr>
                <?php
                    $totalCumul += (int) ($data[0]['montantcharge_avenant']);
                ?>
            @endif

                @php
                    $totalCumul_format = Outil::formatPrixToMonetaire($totalCumul);
                    if (empty($totalCumul_format)) {
                        $totalCumul_format = 0;
                    }
                @endphp

            @if ($totalCumul > 0)
                <tr>
                    <td colspan=""> </td>
                    <td colspan=""> </td>
                    <td colspan="" style="font-size: 20px;"><b>{{$totalCumul_format}} F</b></td>
                </tr>
            @endif
            {{-- @if ($data[0]['montantloyer_avenant'])
                <tr>
                    <td colspan=""> </td>
                    <td colspan=""> </td>
                    <td colspan="" style="font-size: 20px;"><b>{{$data[0]['montantloyer_avenant']}} F</b></td>
                </tr>
            @endif --}}
            </tbody>
            <br>

        </table>

        <table class="table" style="margin-top: -20px;">
            <tbody>
                <tr>
                    <td colspan="2" style="font-size:20px;">
                        @if(isset($data[0]['periodicite']) && isset($data[0]['periodicite']['nbr_mois']) )
                        Soit <b>{{$totalCumul_format}} F x {{$data[0]['periodicite']['nbr_mois']}} </b>
                        @else
                            Soit <b>{{$totalCumul_format}} F x </b>
                        @endif



                    </td>
                    <td colspan="" style="font-size:15px;font-weight:bold"> = </td>
                    <td colspan="" style="font-size:20px;font-weight:bold">
                        @if(isset($data[0]['periodicite']) && isset($data[0]['periodicite']['nbr_mois']) )
                        {{ number_format(($totalCumul * $data[0]['periodicite']['nbr_mois']), 0, '', ' ') }} F
                        @else
                            {{ number_format(($totalCumul), 0, '', ' ') }} F
                        @endif
                    </td>
                </tr>
            </tbody>

            <tbody>
                <tr>


                    <td colspan="2" style="text-decoration: underline;font-size:15px;font-weight:bold">
                        TOTAL
                    </td>

                    <td colspan="" style="font-size:15px;font-weight:bold"> = </td>

                    <td colspan="" style="font-size:20px;font-weight:bold">
                        @if(isset($data[0]['periodicite']) && isset($data[0]['periodicite']['nbr_mois']))
                            {{ number_format(($totalCumul * $data[0]['periodicite']['nbr_mois']), 0, '', ' ') }} F CFA
                        @else
                            {{ number_format(($totalCumul), 0, '', ' ') }} F CFA
                        @endif
                    </td>
                </tr>
            </tbody>


        </table>
    </div>

    <p style="margin-top: 5px;font-size: 18px;">
        Arrêtée la présente facture à la somme de

        {{-- {{""}} --}}

        @if(isset($data[0]['periodicite']) && isset($data[0]['periodicite']['nbr_mois']))
            <span style="font-weight: bold;">

                {{-- {{ucwords(NombreEnLettre::CustomNumberToWords($totalCumul * $data[0]['periodicite']['nbr_mois'])) }} --}}
                {{-- {{ucwords(Outil::convertirEnLettres($totalCumul * $data[0]['periodicite']['nbr_mois'])) }} --}}
                {{ucwords(Outil::numberToLetter($totalCumul * $data[0]['periodicite']['nbr_mois'])) }}

            </span>
            ( {{ number_format(($totalCumul * $data[0]['periodicite']['nbr_mois']), 0, '', ' ') }} ) Francs CFA
        @else
            <span style="font-weight: bold;">

                {{-- {{ucwords(NombreEnLettre::CustomNumberToWords($totalCumul )) }} --}}
                {{-- {{ucwords(Outil::convertirEnLettres($totalCumul )) }} --}}
                {{ucwords(Outil::numberToLetter($totalCumul )) }}
            </span>
            ( {{ number_format(($totalCumul ), 0, '', ' ') }} ) Francs CFA
        @endif

    </p>
    <p style=" font-size: 20px;text-align: left;">En votre aimable règlement.</p>



  


</body>



</html>
