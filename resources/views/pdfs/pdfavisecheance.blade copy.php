
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>AVIS ECHEANCE {{date('d/m/Y')}}</title>

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
        p.right-offset {
            margin-right: 20%; /* Vous pouvez ajuster la valeur selon vos besoins */
        }
        p.left-offset {
            margin-left: 20%; /* Vous pouvez ajuster la valeur selon vos besoins */
        }
        .uppercase {
            text-transform: uppercase
        }
        .center {
  margin-left: auto;
  margin-right: auto;
}

    </style>

</head>
{{-- {{dd($data)}} --}}
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 20%" src="assets/images/sertem-logo.png" alt="1">
    </div>
</header>
{{-- <div class="footer mb-60" style="width: 700px">
    <div>
        <img style="width: 20%;" src="assets/images/sertem-logo.png" alt="">
    </div>
</div> --}}
<body>

<div style="font-size:13px;text-align: right; margin-right: 30px !important;">
    <div>Dakar, {{date('d F Y')}}</div>
</div>
<div style="font-size: 15px;font-weight: bold;text-align: center;margin: 1px 0 5px;text-transform: uppercase">


    <p style="text-decoration:underline;">
        {{-- Paiement loyer du {{date_format(date_create($data[0]['contrat']['datepaiement_format']) , "d/m/Y")}} --}}
       </p>

 </div>
 <div style="font-size: 13px;text-align: right;font-weight: bold;margin: 1px 0 5px;margin-right:20%">
  <p>
    <b style="text-decoration: underline">  Destinataire : </b>
    @if ($data[0]['contrat']['locataire'] && $data[0]['contrat']['locataire']['nomentreprise'])
    {{$data[0]['contrat']['locataire']['nomentreprise']}}
    @elseif ($data[0]['contrat']['locataire'] && $data[0]['contrat']['locataire']['nom'])
    {{$data[0]['contrat']['locataire']['prenom']}}   {{$data[0]['contrat']['locataire']['nom']}}
    @endif
 </p> 
 <p style="margin-right:-5%">
    <b style="text-decoration: underline;font-style: italic">  Adresse : </b>
    @if ($data[0]['contrat']['appartement'] && $data[0]['contrat']['appartement']['lot'] && $data[0]['contrat']['appartement']['ilot'])
    {{$data[0]['contrat']['appartement']['lot']}} {{$data[0]['contrat']['appartement']['ilot']['adresse']}} 
    @endif
 </p> 
  
    {{-- MERIDIAM WEST AFRICA SASU <br>
    Immeuble la Rotonde 2ème étage<br>
    Rue Amadou Assane NDOYE X rue St-Michel <br>
    Dakar Sénégal --}}
 </div>
 <div style="font-size: 18px;font-weight: bold;text-align: center;margin: 1px 0 10px;">
    <p style="text-decoration:underline;">Avis d'échéance</p>
    <p style="font-style: italic">
        {{ ($data[0]['contrat']['appartement']  &&  $data[0]['contrat']['appartement']['lot']) ?  date("d/m/Y")."-".$data[0]['contrat']['appartement']['lot'] : "........................." }}
    </p>
 </div>


 <div style="font-size: 15px;text-align: left;margin: 1px 0 5px;font-weight: bold;">

    <p >
        <b style="text-decoration: underline">
            Adresse du bien immobilier loué :
        </b>
        
    </p>
    <p style="margin-left:5%">
        <i>
            Lot {{ $data[0]['contrat']['appartement'] && $data[0]['contrat']['appartement']['lot']  ? $data[0]['contrat']['appartement']['lot'] : "............"}}
           {{ $data[0]['contrat']['appartement']['ilot'] ? $data[0]['contrat']['appartement']['ilot']['numero']." ". $data[0]['contrat']['appartement']['ilot']['adresse'] : "......." }}</i>
    </p>

   
 </div>
 <div style="font-size: 15px;margin: 3px 0 5px;font-weight: bold;">
    <p style="text-align: center">
        Nous avons l'honneur de vous informer que vous êtes redevable du montant de
    </p>
    <p style="text-align: center">
        {{-- {{ $data[0]['contrat']['montantloyer'] ?  ucwords(NumberToLetter($data[0]['contrat']['montantloyer'])) : "............................................................" }} --}}
       
        @if ($data[0]['montant_total'])
         <x-nombre-en-lettre :nombre="$data[0]['total_montant']"/> Francs CFA  ({{$data[0]['montant_total']}})
        @else
        <i>no</i>
        @endif
    </p>
    <p style="margin-left:21%">
        dont détail ci-dessous. 

    </p>
    <p style="text-align: center" class="left-offset">
        Nous vous remercions de bien vouloir régler cette somme dès réception du présent avis 

    </p>
    <p class="left-offset" style="display:flex;justify-content:space-between">
        et au plus tard le {{ $data[0]['date_echeance_format'] ? $data[0]['date_echeance_format'] : "....................."  }}
        sur notre compte suivant le RIB ci-dessous. 
    </p>
    
 
    {{-- <p>- {{count($data[0]['contrat']['periodes_non_payes']) > 1 ? "Loyers" : "Loyer"}}  @foreach ( $data[0]['contrat']['periodes_non_payes'] as $item)
                    {{$item['designation']}},
                @endforeach
                {{date_format(now() , "Y")}}
     </p> --}}
     <table class="table table-sm center" style="width:50%">
        <thead>
            <tr>
                <td class="uppercase" >RECAPITULATIF DU MOIS DE </td>
                <td class="uppercase" >{{ $data[0]['periodes'] ? $data[0]['periodes'] : "............."}}</td>
                <td class="uppercase" >{{ $data[0]['annee_echeance_format'] ? $data[0]['annee_echeance_format']  : date('Y') }}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td scope="row"> - Amortissement du bien</td>
                <td>{{ $data[0]['amortissement'] ? $data[0]['amortissement'] : "......................"}}</td>
                <td>F CFA</td>
            </tr>
            <tr>
                <td scope="row"> - Frais de gestion</td>
                <td>{{ $data[0]['fraisgestion'] ? $data[0]['fraisgestion'] : "......................"}}</td>
                <td>F CFA</td>
            </tr>
            
            <tr >
                <td style="border:  1px solid #000" scope="row" class="uppercase">  Montant Net a payer</td>
                <td style="border:  1px solid #000">
                        {{ $data[0]['montant_total']  ? $data[0]['montant_total']  : ".........." }}
                </td >
                <td style="border:  1px solid #000">F CFA</td>
            </tr>
        </tbody>
     </table>
   
     <p>
        <b style="text-decoration:underline"> Date d'éxigibilité : </b> {{ $data[0]['date_echeance_format'] ? $data[0]['date_echeance_format']: "........................"}}
     </p>
</div>

<div style="font-size: 13px;text-align: left;margin: 10px 0 10px;">

</div>
<div style="font-size: 13px;font-weight: bold;text-align: left;margin: 10px 0 10px;">

    <p style="font-weight: bold;"><b style="text-decoration: underline">Banque</b> : BDK  </p>
    <p style="font-weight: bold;"><b style="text-decoration: underline">Agence</b> : Siege </p>
    <p style="font-weight: bold;"><b style="text-decoration: underline">Code Banque</b> : SN191 </p>
    <p style="font-weight: bold;"><b style="text-decoration: underline">Code Guichet</b> : 01002  </p>
    <p style="font-weight: bold;"><b style="text-decoration: underline">Clé RIB</b> : 96  </p>
 
</div>

<div style="font-size: 13px;font-weight: bold;text-align: right;margin: -40% 0 10px;text-transform: uppercase">
    <p style="text-decoration:underline;">Le caissier Général </p>

    <br>
    <br>
    <br>

   
</div>

{{-- <p  style="color:#515100;margin-left:20%">
    Fann Résidence, 41 Rue C x Leon Gontron Damas, Dakar <br>
    B.P. 6640 Dakar - Sénégal <br>
    Tél.: 33 869 77 68 <br>
    secretariat@sertemimmo.com <br>
    sertemgroupe.com <br>
    R.C.: SN-DKR 2012 B14744 - NINEA: 0004677946 
</p>
 --}}

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









