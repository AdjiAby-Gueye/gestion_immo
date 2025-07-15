<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>PDF facture {{date('d/m/Y')}}</title>

    <style>
        @page {
            margin: 20px 0px;
        }

        table, th, td {
            border: 1px solid #585b5e;
            border-collapse: collapse;
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
            border: 1px  solid black;
            letter-spacing: 1px;
            font-size: 0.6rem;
            width: 100%;
        }

        .td, .th {
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
            margin-top: 5.1cm;
            margin-left: 1.0cm;
            margin-right: 1.0cm;
            margin-bottom: 5cm;
            /*margin-bottom: 1.2cm;*/
            /*font-size: 1.2em;*/
            /*font: 12pt/1.5 'Raleway','Cambria', sans-serif;*/
            font-weight: 400;
            background:  #fff;
            color: black;
            -webkit-print-color-adjust:  exact;
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
            display:inline;
        }
        #break:after {
            content:"\a";
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

        .break-auto
        {
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
    </style>

</head>
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 10%" src="{{$data[0]['appartement']['entite']['image']}}" alt="1">
    </div>
</header>
<div class="footer mb-60" style="width: 700px">
    <div>
        <img style="width: 10%;" src="{{$data[0]['appartement']['entite']['image']}}" alt="">
    </div>
</div>
{{-- {{dd($data)}} --}}
<body>
<div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -60px">
    <div>Dakar, {{date('d/m/Y')}}<</div>
</div>
<div style="font-size: 15px;font-weight: bold;text-align: center;margin: 30px 0 5px;text-transform: uppercase">

    <p style="text-decoration:underline;">
     facture {{$data[0]['typefacture']['designation']}} du {{$data[0]['datefacture_format']}}
    </p>

 </div>
<div style="font-size: 13px;font-weight: bold;text-align: left;margin: 30px 0 10px;text-transform: uppercase">
   <p style="text-decoration:underline;">
    Infos Appartement

   </p>
   <p>
    Nom : {{$data[0]['appartement']['nom']}}
    </p>
    <p>
        Immeuble : {{$data[0]['appartement']['immeuble']['nom']}}
        </p>
    <p>
        Adresse : {{$data[0]['appartement']['immeuble']['adresse']}}
        </p>
</div>
<br>

<div style="font-size: 13px;font-weight: bold;text-align: right;margin: 30px 0 10px;text-transform: uppercase;margin-top:-10%">
    <p style="text-decoration:underline;">
    Infos Locataire
    </p>
    @if ($data[0]['appartement']['locataire']['nomentreprise'])
    <p>
        Nom entreprise : {{$data[0]['appartement']['locataire']['nomentreprise']}}
        </p>

<p>

    Téléphone personne a contacter :  {{$data[0]['appartement']['locataire']['telephone1personneacontacter']}}
   </p>
   <p>

    Email personne a contacter :  {{$data[0]['appartement']['locataire']['emailpersonneacontacter']}}
   </p>
    @else
    <p>
        Nom & Prénom : {{$data[0]['appartement']['locataire']['nom']}} {{$data[0]['appartement']['locataire']['prenom']}}
        </p>
        <p>

            Téléphone : {{$data[0]['appartement']['locataire']['telephoneportable1']}}
           </p>
           <p>
               Email :  {{$data[0]['appartement']['locataire']['email']}}
              </p>
    @endif





 </div>

 <div style="font-size: 13px;font-weight: bold;text-align: left;margin: 30px 0 10px;text-transform: uppercase">
    <p style="text-decoration:underline;">Occuppant {{$data[0]['appartement']['nom']}} : </p>
    @if ($data[0]['appartement']['locataire']['nomentreprise'])
     <p  style="margin-left:10%">  {{$data[0]['appartement']['locataire']['nomentreprise']}}</p>
     @else
     <p  style="margin-left:10%">  {{$data[0]['appartement']['locataire']['prenom']}} {{$data[0]['appartement']['locataire']['nom']}}</p>

    @endif

</div>


<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap text-center">Période </th>
            <th class="whitespace-no-wrap text-center">Montant</th>
            <th class="whitespace-no-wrap text-center">Total</th>
        </tr>

        <tr class="tr">
            <td class="td">
              Janvier
            </td>
            <td class="td">
                200000
              </td>
              <td class="td">
            </td>
        </tr>
        <tr class="tr">
            <td class="td">
            </td>
            <td class="td">
            </td>
            <td class="td">
                200000
              </td>
        </tr>
    </table>


</div>

<div style="font-size: 13px;font-weight: bold;text-align: left;margin: 10px 0 10px;">
    <p style="">Arrete a la somme de .... FCFA </p>
</div>
<div style="font-size: 13px;font-weight: bold;text-align: right;margin: 30px 0 10px;text-transform: uppercase">
    <p style="text-decoration:underline;">La gerante </p>


</div>

<script type="text/php">
    if (isset($pdf)) {
        $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
<?php


?>
</body>



</html>









