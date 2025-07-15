@php
use App\Helpers\NombreEnLettre;
@endphp

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>SITUATION DES SOLDES PROPRIETAIRES</title>

    <style>
        @page {
            margin: 0px 0px;
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
            margin: auto;
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
            margin-top: 2.1cm;
            margin-left: 2.0cm;
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

<!-- <header style="width:100%;">
    <div class="header" style="">
    </div>
</header> -->


<!--
<div class="footer mb-60" style="width: 100%;font-size:12px;">
    <div>
        <p style="font-weight: 100; color: rgb(50, 177, 255)">Route de la corniche Ouest , en face ARTP</p>
        <p style="font-weight: 100;color: rgb(50, 177, 255)">NINEA :30865942 -CBAO Soumbedioune -sN012 01217 036158967501 96
        </p>
        <p style="font-weight: 100;color: rgb(50, 177, 255)">Tel : 33 869 82 20 - 77 819 20 54 -BP 6640 Dakar - Sénegal</p>
    </div>
</div> -->

<body>
    <!-- <div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -90px">
        <div>Dakar, </div>
    </div> -->
    <!-- <div style="font-size: 15px;font-weight: bold;text-align: center;text-transform: uppercase">

        <p style="text-decoration:underline;">


        </p>
        <br>

    </div> -->


    <!-- <div style="font-size: 20px;font-weight: bold;text-align: right;margin: 10px">
        tes

    </div> -->



    <div style="font-size: 15px;text-align: left;">


        <p style="">
            <i class="fa fa-underline" style="font-weight: bold;text-transform: uppercase" aria-hidden="true">
                B.B. IMMOBILIER

            </i>
        </p>

        <p style="">
            <i class="fa fa-underline" style="font-weight: bold;text-transform: uppercase" aria-hidden="true">
                63, Rond-Point VDN Ouest Foire

            </i>
        </p>

        <p style="">
            <i class="fa fa-underline" style="font-weight: bold;text-transform: uppercase" aria-hidden="true">

                TEL. 33 867 01 06

            </i>
        </p>
        <p style="">
            <i class="fa fa-underline" style="font-weight: bold;text-transform: uppercase" aria-hidden="true">

                BP: 5258 CP 10 700
                D A K A R
            </i>
        </p>
        <p style="">
            <i class="fa fa-underline" style="font-weight: bold;text-transform: uppercase" aria-hidden="true">

                D A K A R
            </i>
        </p>

    </div>
    <div style="font-size: 20px;font-weight: bold;text-align: center;background: grey;padding: 15px 70px;">
        SITUATION DES SOLDES PROPRIETAIRES

    </div>
    <div style="font-size: 20px;font-weight: bold;text-align: center;margin: 10px">
        A la date du 01/01/2024
    </div>
    <div style="font-size: 20px;;text-align: right;margin: 10px">
        D A K A R, le {{date('d/m/Y')}}
    </div>

    <div style="font-size: 15px;margin-top: 5px;">

        <table style="font-size: 12px;" class="table">
            <thead style="border-bottom: 1px solid black;">
                <tr>
                    <td style="font-size: 12px;">CodeProprietaire</td>
                    <td style="font-size: 12px;">Nom & Adresse Proprietaire</td>
                    <td style="font-size: 12px;">DEBIT</td>
                    <td style="font-size: 12px;">CREDIT</td>

                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr class="tr">
                    <td class="td"> {{$data[$i]["id"]}} </td>
                    <td class="td"> {{$data[$i]["prenom"]}} {{ $data[$i]["nom"]}} ---- {{$data[$i]["adresse"]}}</td>
                    <td class="td"> {{$data[$i]["sommedepense '0' }} </td>
                    <td class="td"> {{$data[$i]["montanttotalloyer"] ?? '0' }}</td>

                    </tr>
                    @endfor



dy>
            <br>
        </table>


    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $
                    <td class="td"> {{$data[$i]["montanttotalloyer"] ?? '0' }} </td>
                    <td class="td"> {{$data[$i]["montanttotalloyer"] ?? '0' }}</td>

                    </tr>
                    @endfor




            </tbody>
            <br>
        </table>


    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM}
 / {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>

</body>



</html>
