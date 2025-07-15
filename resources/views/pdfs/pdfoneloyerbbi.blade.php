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
            bottom: 100px;
            border-top: 1px solid red;
            width: 86%;
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



<div class="footer mb-60" style="font-size:12px;">
    <div>
        <p style="font-weight: 100; color: rgb(50, 177, 255)">Société unipersonel à Responsabilité Limité F.CFA 1.000.000 -. Siège Social : 53, Rond- Point VDN Ouest Foire Immeuble BP 582 cp 10 700 Tél. : (221) 33 867 01 06 - E-mail :bbimmobilier@orange.sn - R.C. n∞SN.DKR.2015.B.20990 - NINEA : 005677234 2Y2 - BANQUE: CNCAS N∞SN048 01002 0001078739 01 R 43 // BICIS VDN N∞06352 0077243 000 16
        </p>

    </div>
</div>
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 10%" src="{{asset('assets/images/logobbi.jpeg')}}" alt="1">
    </div>
</header>

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




    <div style="font-size: 20px;font-weight: bold;text-align: center;color: red;">
        B.B. IMMOBILIER
    </div>
    <div style="font-size: 20px;font-weight: bold;text-align: center;">
        63, Rond-Point VDN Ouest Foire
    </div>
    <hr>
    <div style="font-size: 20px;font-weight: bold;text-align: center;background: grey;padding: 15px 70px;">
        AVIS D'ECHEANCE
    </div>
    <div style="font-size: 10px;font-weight: bold;text-align: left;border: 1px solid black;padding: 5px;width:40%;float: right;margin-top: 10px;">
        <p>1 001 550---  {{
            $data[0]['contrat']['locataire']['nomentreprise'] ?
              $data[0]['contrat']['locataire']['nomentreprise'] :

              $data[0]['contrat']['locataire']['nom']}} {{$data[0]['contrat']['locataire']['prenom'] }}</p>
        <p>Immeuble / Villa : 2228 ---  {{$data[0]['contrat']['appartement']['immeuble'] ? $data[0]['contrat']['appartement']['immeuble']['adresse']  : ""}}</p>
    </div>

    <div style="font-size: 10px;text-align: left;margin-top: 120px;">

        <p style="">
            <i class="fa fa-underline" style="text-transform: uppercase" aria-hidden="true">
                <strong>GÈrance</strong> : 1020
            </i>
        </p>

        <p style="">
            <i class="fa fa-underline" style="text-transform: uppercase" aria-hidden="true">
                <strong>Consistance</strong> : HABITATION

            </i>
        </p>

        <p style="">
            <i class="fa fa-underline" style="text-transform: uppercase" aria-hidden="true">

                <strong>Appartement</strong> : {{$data[0]['contrat']['appartement'] ? $data[0]['contrat']['appartement']['nom'] : ""}}

            </i>
        </p>

    </div>
    <div style="font-size: 15px;text-align: right;margin: 10px">
        D A K A R, le 10/07/2024
    </div>



    <div style="font-size: 16px;font-weight: bold;text-align: left;">
        DOIT : LOYER DU {{$data[0]['datefacture_format']}} au {{$data[0]['date_echeance_format']}}
    </div>
    <table class="table mb-10">
        <tr class="tr">
            <th class="th" colspan="1" class="whitespace-no-wrap" style="font-size: 20px;">Description</th>
            <th class="th" class="whitespace-no-wrap" style="font-size: 20px;">Montant </th>
        </tr>
        <tr class="tr">
            <td class="td" style="font-size: 20px;">Loyer de base</td>
            <td class="td" style="font-size: 20px;">{{$data[0]['montantloyerbase_avenant']}}</td>
        </tr>
        <tr class="tr">
            <td class="td" style="font-size: 20px;">TVA ( 0.18 ) </td>
            <td class="td" style="font-size: 20px;">{{$data[0]['contrat']['appartement']['tva']?
            $data[0]['contrat']['appartement']['tva'] : 0   
            }}
            </td>
        </tr>
        <tr class="tr">
            <td class="td" style="font-size: 20px;">TOM ( 0.036 ) </td>
            <td class="td" style="font-size: 20px;">{{$data[0]['montantloyertom_avenant']}}</td>
        </tr>
        
        <tr class="tr">
            <td class="td" style="font-size: 20px;">TOTAL </td>
            <td class="td" style="font-size: 20px;">{{$data[0]['contrat']['appartement']['tvamountant']? $data[0]['montantloyerbase_avenant']  + $data[0]['montantloyertom_avenant'] + $data[0]['contrat']['appartement']['tvamountant'] :  $data[0]['montantloyerbase_avenant']  + $data[0]['montantloyertom_avenant'] }}
            </td>
        </tr>
    </table>
    <div style="font-size: 15px;text-align: left;">
        Arrete la presente quittance la somme de :
    </div>
    <div style="font-size: 15px;text-align: left;margin-top: 10px;">
        <strong> {{$data[0]['contrat']['appartement']['tvamountant']? $data[0]['montantloyerbase_avenant']  + $data[0]['montantloyertom_avenant'] + $data[0]['contrat']['appartement']['tvamountant'] :  $data[0]['montantloyerbase_avenant']  + $data[0]['montantloyertom_avenant'] }}
        </strong>
        <p style="margin-left: 30px;margin-top: 5px;">Valeur en votre aimable rÈglement</p>
    </div>

    <div style="font-size: 15px;text-align: right;margin: 10px">
        La Direction
    </div>






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