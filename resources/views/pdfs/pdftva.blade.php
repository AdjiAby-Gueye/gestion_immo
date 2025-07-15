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
    <title>PDF FACTURE LOCATION {{date('d/m/Y')}}</title>

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


<body>



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
        Etat de declaration de la TVA
        du {{$datedeb}} au {{$datefin}}
    </div>
  
    <div style="font-size: 15px;text-align: right;margin: 10px;">
        D A K A R, le 10/07/2024
    </div>
 


    @foreach( $data as $arriere)
    <div style="font-size: 15px;margin-top: 5px;">

        <p style="font-size: 20px;"><strong>Proprietaire</strong> : 1016 --- {{$arriere['contrat']['appartement']['proprietaire']['nom']}} {{$arriere['contrat']['appartement']['proprietaire']['prenom']}}</p>

        <table style="font-size: 12px;" class="table">
            <thead style="border-bottom: 1px solid black;">
                <tr>
                    <td style="font-size: 12px;">Logement</td>
                    <td style="font-size: 12px;">Loyer de base</td>
                    <td style="font-size: 12px;">TLV</td>
                </tr>
            </thead>
            <tbody>
                <tr style="font-size: 12px">
                    <td style="font-size: 12px;">1016 -- {{$arriere['contrat']['appartement']['immeuble']['adresse']}}</td>
                    <td style="font-size: 12px;">{{$arriere['montantloyerbase_avenant']}}</td>
                    <td style="font-size: 12px;">{{$arriere['contrat']['appartement']['tvamountant'] ?? 0}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{$arriere['montantloyerbase_avenant']}}</td>
                    <td style="font-size: 12px;"><b>{{$arriere['contrat']['appartement']['tvamountant']  ?? 0 }}</b></td>
                </tr>
            </tbody>
            <br>
        </table>
    </div>
    @endforeach

  









    <p>Nombre de lignes : {{sizeof($data)}}</p>



    <!-- 
    <p style="margin-top: 10px;font-size: 17px;">
        Arrêtée la présente facture à la somme de <span style="font-weight: bold;">df</span>
        Francs CFA
    </p>
    <p style="font-size: 20px;text-align: left;">En votre aimable règlement.</p> -->

    <!-- 

    <footer style="position: fixed;bottom: 23%;width: 90%;">
        <div style="width: 100%;position: relative;">
            <div style="float: right;font-weight: bold">
                <p style="text-decoration:underline;">La gerante </p>
                <br>
                <br>
                <br>
                <br>

                <p style="text-decoration:underline;">Marieme Ngom </p>
            </div>
            <div style="float: left;">
                <p style="font-weight: bold;font-size: 12px;">Coordonnées Bancaires de la SCI REYHAN : </p>
                <p style="font-weight: bold;font-size: 12px;">Code Banque : SN012 </p>
                <p style="font-weight: bold;font-size: 12px;">Code Guichet : 01217 </p>
                <p style="font-weight: bold;font-size: 12px;">Numéro de Compte : 036158967501 </p>
                <p style="font-weight: bold;font-size: 12px;">RIB : 96 </p>
                <p style="font-weight: bold;font-size: 12px;">Banque : CBAO </p>
                <p style="font-weight: bold;font-size: 12px;">Adresse : Soubedioune </p>
            </div>

        </div>

    </footer> -->



    <?php


    ?>
</body>



</html>