<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>PDF PAIEMENT LOYER {{date('d/m/Y')}}</title>

    <style>
        @page {
            margin: 20px 0px;
        }

        table, th, td {
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
{{-- {{dd($data)}} --}}
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 20%" src="{{$data[0]['contrat']['appartement']['entite']['image']}}" alt="1">
    </div>
</header>
<div class="footer mb-60" style="width: 700px">

        <img style="width: 20%;" src="{{$data[0]['contrat']['appartement']['entite']['image']}}" alt="">
</div>
<body>

<div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -60px">
    <div>Dakar, {{date('d/m/Y')}}<</div>
</div>
<div style="font-size: 15px;font-weight: bold;text-align: center;margin: 30px 0 5px;text-transform: uppercase">

    <?php
        $date = $date = DateTime::createFromFormat('d/m/Y', $data[0]['datepaiement_format']);
        // dd($date);
    ?>
    <p style="text-decoration:underline;">
        Paiement loyer du
         {{-- {{$data[0]['datepaiement_format'] ?? ''}} --}}
         @php
            $date = DateTime::createFromFormat('d/m/Y', $data[0]['datepaiement_format'] ?? '');
        @endphp
        {{ $date ? $date->format('d/m/Y') : 'Date invalide' }}

        {{-- {{date_format(date_create($data[0]['datepaiement_format']) , "d/m/Y")}} --}}
       </p>

 </div>
 <div style="font-size: 13px;font-weight: bold;text-align: right;margin: 30px 0 10px">
    {{-- MERIDIAM WEST AFRICA SASU <br>
    Immeuble la Rotonde 2ème étage<br>
    Rue Amadou Assane NDOYE X rue St-Michel <br>
    Dakar Sénégal --}}
  Adresse : {{ $data[0]['contrat']['locataire']['nomentreprise'] ? $data[0]['contrat']['locataire']['adresseentreprise'] : $data[0]['contrat']['appartement']['immeuble']['adresse'] }}
 </div>

{{-- <div style="font-size: 13px;font-weight: bold;text-align: left;margin: 30px 0 10px;text-transform: uppercase">
   <p style="text-decoration:underline;">
    Infos Appartement

   </p>
   <p>
    Nom : {{$data[0]['contrat']['appartement']['nom']}}
    </p>
    <p>
        Immeuble : {{$data[0]['contrat']['appartement']['immeuble']['nom']}}
        </p>
    <p>
        Adresse : {{$data[0]['contrat']['appartement']['immeuble']['adresse']}}
        </p>
</div>
<br>

<div style="font-size: 13px;font-weight: bold;text-align: right;margin: 30px 0 10px;text-transform: uppercase;margin-top:-20%">
    <p style="text-decoration:underline;">
    Infos Locataire
    </p>
    @if ($data[0]['contrat']['locataire']['nomentreprise'])
    <p>
        Nom entreprise : {{$data[0]['contrat']['locataire']['nomentreprise']}}
        </p>

<p>

    Téléphone personne a contacter :  {{$data[0]['contrat']['locataire']['telephone1personneacontacter']}}
   </p>
   <p>

    Email personne a contacter :  {{$data[0]['contrat']['locataire']['emailpersonneacontacter']}}
   </p>
    @else
    <p>
        Nom & Prénom : {{$data[0]['contrat']['locataire']['nom']}} {{$data[0]['contrat']['locataire']['prenom']}}
        </p>
        <p>

            Téléphone : {{$data[0]['contrat']['locataire']['telephoneportable1']}}
           </p>
           <p>
               Email :  {{$data[0]['contrat']['locataire']['email']}}
              </p>
    @endif





 </div> --}}


 <div style="font-size: 15px;text-align: left;margin: 30px 0 5px;">


    <p style="">
        <i class="fa fa-underline" style="text-decoration:underline;font-weight: bold;text-transform: uppercase" aria-hidden="true">
            OBJET :
            </i> {{$data[0]['contrat']['appartement']['nom']}}
            <p style="margin-left: 8%">{{$data[0]['contrat']['appartement']['immeuble']['adresse']}}</p>
            <p style="margin-left: 8%">Pour le compte de {{ $data[0]['contrat']['locataire']['nomentreprise'] ? $data[0]['contrat']['locataire']['nomentreprise'] : $data[0]['contrat']['locataire']['prenom']." ".$data[0]['contrat']['locataire']['nom'] }}</p>
    </p>


 </div>
 <div style="font-size: 15px;text-align: left;margin: 30px 0 5px;">


    <p style="text-decoration:underline;font-weight: bold;">
        DESIGNATION
    </p>
    <p>- {{count($data[0]['detailpaiements']) > 1 ? "Loyers" : "Loyer"}}  @foreach ( $data[0]['detailpaiements'] as $item)
                    {{$item['periode']['designation']}},
                @endforeach
                {{-- {{ $date ? $date->format('Y') : 'Date invalide' }} --}}
                {{-- {{date_format(date_create($data[0]['datepaiement_format']) , "Y")}} --}}
     </p>
     <p style="font-size: 13px;margin-left: 4%">DETAIL DU LOYER MENSUEL</p>
    <table style="font-size: 13px;margin-left: 8%" class="table">
        <tbody>
        @if ($data[0]['contrat']['montantloyerbaseformat'])
          <tr style="font-size: 13px">
            <td colspan="2">- Loyer base</td>
            <td>{{$data[0]['contrat']['montantloyerbaseformat']}} F</td>
          </tr>
        @endif
        @if ($data[0]['contrat']['montantloyertomformat'])
          <tr style="font-size: 13px">
            <td colspan="2">- Tom</td>
            <td>{{$data[0]['contrat']['montantloyertomformat']}} F</td>
          </tr>
        @endif
        @if ($data[0]['contrat']['montantchargeformat'])
          <tr>
            <td colspan="2">- Charges</td>
            <td>{{$data[0]['contrat']['montantchargeformat']}} F</td>
          </tr>
        @endif
          <tr>
            <td colspan=""> </td>
            <td colspan=""> </td>
            <td colspan=""><b>{{$data[0]['contrat']['total_loyer_format']}} F</b></td>
          </tr>
        </tbody>
        <br>

      </table>
      <table class="table">
        <tbody>
            <tr>
                <td colspan="2" style="font-size:15px;">
                    Soit <b>{{$data[0]['contrat']['total_loyer_format']}} F x {{count($data[0]['detailpaiements'])}} </b>
                </td>
                <td colspan="" style="font-size:15px;font-weight:bold"> = </td>
                <td colspan="" style="font-size:15px;font-weight:bold">
                    {{ number_format(($data[0]['contrat']['total_loyer'] * count($data[0]['detailpaiements']) ), 0, '', ' ') }} F
                </td>
            </tr>
        </tbody>
    </table>
      {{-- <p style="text-align:center">
        Soit <b>{{$data[0]['total_loyer_format']}} F x 2   =   {{$data[0]['total_loyer_format']}}</b>
    </p> --}}
</div>

{{-- <div>


    <table class="table table-sm">
        <thead>

          <tr>
            <th scope="col">#</th>
            <th scope="col">Période </th>
            <th scope="col">Montant</th>
          </tr>
        </thead>
        <tbody>
            {{ $i = 0}}
            @foreach ( $data[0]['detailpaiements'] as $item)
            {{ $i++  }}

          <tr>
            <th scope="row"> {{ $i }}</th>
            <td>
                {{$item['periode']['designation']}}
            </td>
            <td>
                {{$item['montant_format']}} F
            </td>

          </tr>
          @endforeach
          <tr>
            <td  scope="row" colspan="2">Total</td>
            <td style="font-weight: bold">   {{$data[0]['montant_paiement']}} F CFA</td>
          </tr>
        </tbody>
      </table>



    </div> --}}

<div style="font-size: 13px;text-align: left;margin: 10px 0 10px;">
    <p style="">Arrêtée à la somme de : {{ucfirst($data[0]['montant_paiement_format'])}} (<b>{{$data[0]['montant_paiement']}}</b>) Francs CFA </p>
 <p style="font-weight: bold;">Mode de paiement : {{$data[0]['modepaiement']['designation']}} {{$data[0]['modepaiement']['code'] == "CH" ? $data[0]['numero_cheque'] : ""}}</p>

</div>


<div style="font-size: 13px;font-weight: bold;text-align: right;text-transform: uppercase">
    <p style="text-decoration:underline;">La gerante </p>

    <br>


    <p style="">Marieme Ngom </p>
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









