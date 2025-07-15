@php
    use App\Outil;
    use App\Helpers\NombreEnLettre;

@endphp
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
            height: 1cm;
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
            /* float: right; */
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
{{-- {{dd($data->contrat->appartement)}} --}}
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 20%" src="assets/images/pm.png" alt="1">
    </div>
</header>
{{-- <div class="footer mb-60" style="width: 700px">
    <div>
        <img style="width: 20%;display:none;" src="assets/images/pm.png" alt="1">
    </div>
</div> --}}
<body>

<div style="font-size:13px;text-align: right; margin-right: 30px !important;">

    <div>Dakar, {{Outil::resolveDateFrDField($data['date'])}} {{Outil::getMonthString(intval(Outil::resolveDateFrMField($data['date'])))}} {{Outil::resolveDateFrYField($data['date'])}}</div>
</div>
<div style="font-size: 15px;font-weight: bold;text-align: center;margin: 1px 0 5px;text-transform: uppercase">


    <p style="text-decoration:underline;">
        {{-- Paiement loyer du {{date_format(date_create($data[0]['contrat']['datepaiement_format']) , "d/m/Y")}} --}}
       </p>

 </div>
 <div style="font-size: 13px;text-align: right;font-weight: bold;margin: 1px 0 5px;margin-right:20%">
  <p>
    <b style="text-decoration: underline">  Destinataire : </b>
    @if ($data->contrat->locataire && $data->contrat->locataire->nomentreprise)
    {{$data->contrat->locataire->nomentreprise}}
    @elseif ($data->contrat->locataire && $data->contrat->locataire->nom)
    {{$data->contrat->locataire->prenom}}   {{$data->contrat->locataire->nom}}
    @endif
    @if ($data->contrat->copreneur && $data->contrat->copreneur->id)
     {{" & ".$data->contrat->copreneur->prenom}}   {{$data->contrat->copreneur->nom}}
    @endif
 </p>
 <p style="margin-right:-5%">
    <b style="text-decoration: underline;font-style: italic">  Adresse : </b>
    @if ($data->contrat->appartement && $data->contrat->appartement->lot )
    {{  $data->contrat->appartement->lot }} {{ $data->contrat->appartement->ilot->adresse}}
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
        {{   $data->code_genere ? $data->code_genere : 0  }}
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
            Lot {{  $data->contrat->appartement &&  $data->contrat->appartement->lot &&  $data->contrat->appartement->ilot  ?  $data->contrat->appartement->lot : "............"}}
           {{  $data->contrat->appartement->lot ?  $data->contrat->appartement->ilot->numero." ".  $data->contrat->appartement->ilot->adresse : "......." }}</i>
    </p>


 </div>
 <div style="font-size: 15px;margin: 3px 0 5px;font-weight: bold;">
    <p style="text-align: center">
        Nous avons l'honneur de vous informer que vous êtes redevable du montant de
    </p>
    <p style="text-align: center">

        @if ($data->montant)
         {{-- {{Outil::convertirEnLettres(($data->montant*$data->periodicite->nbr_mois))}}  Francs CFA  ({{ $data->montant_total_periodicite }}) --}}
         {{Outil::numberToLetter(($data->montant*$data->periodicite->nbr_mois))}}  Francs CFA  ({{ $data->montant_total_periodicite }})
         {{-- {{ }} --}}
         {{-- < ?php  echo \App\Outil::numberToLetter(($data->montant*$data->periodicite->nbr_mois)) ."\r\n";?>FCFA ({{ $data->montant_total_periodicite }}) --}}
         {{-- {{Outil::numberToLetter(($data->montant*$data->periodicite->nbr_mois)) }} --}}
        @else
        {{-- <x-nombre-en-lettre :nombre="$data->montant"/> --}}
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
        et au plus tard le {{ $data->date_echeance_fr ? $data->date_echeance_fr : "....................."  }}
        sur notre compte suivant le RIB ci-dessous.
    </p>

    @php
        $amortissement_format = Outil::formatPrixToMonetaire($data->amortissement);
        if (empty($amortissement_format)) {
            $amortissement_format = 0;
        }

        $fraisdelocation_format = Outil::formatPrixToMonetaire($data->fraisdelocation);
        if (empty($fraisdelocation_format)) {
            $fraisdelocation_format = 0;
        }

        $fraisgestion_format = Outil::formatPrixToMonetaire($data->fraisgestion);
        if (empty($fraisgestion_format)) {
            $fraisgestion_format = 0;
        }
    @endphp


     <table class="table table-sm center" style="width:80%">
        <thead>
            <tr>
                <td class="uppercase" >RECAPITULATIF MOIS DE </td>
                <td class="uppercase" >{{ $data->periodes ? $data->periodes : "............."}}</td>
                <td class="uppercase" >{{ $data->annee_echeance ? $data->annee_echeance  : date('Y') }}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td scope="row"> - Quote part amortissement</td>
                <td>{{ $data->amortissement ? $amortissement_format : "......................"}}</td>
                {{-- <td>{{ $data->amortissement ? $data->amortissement : "......................"}}</td> --}}
                <td>F CFA</td>
            </tr>
            <tr>
                <td scope="row"> - Frais de location</td>
                <td>{{ $data->fraisdelocation ? $fraisdelocation_format : "......................"}}</td>
                <td>F CFA</td>
            </tr>
            <tr>
                <td scope="row"> - Frais de gestion</td>
                <td>{{ $data->fraisgestion ? $fraisgestion_format : "......................"}}</td>
                <td>F CFA</td>
            </tr>
            @if ($data->periodicite->nbr_mois > 1)
                <tr>
                    <td scope="row"> - Détail  </td>
                    <td>{{ $data->montant_total }} * {{$data->periodicite->nbr_mois}}  = {{$data->montant_total_periodicite}}</td>
                    <td>F CFA</td>
                </tr>
            @endif

            @if($frais)
            @for ($e = 0; $e < count($frais); $e++)
            <tr>
                <td scope="row"> - {{ $frais[$e]['designation'] }}   </td>
                <td>{{ $frais[$e]['frais'] }}</td>
                <td>F CFA</td>
            </tr>
            @endfor
            @endif

            <tr >
                <td style="border:  1px solid #000" scope="row" class="uppercase">  Montant Net a payer</td>
                <td style="border:  1px solid #000">
                        {{ $data->montant_total_periodicite  ? $data->montant_total_periodicite  : ".........." }}
                </td >
                <td style="border:  1px solid #000">F CFA</td>
            </tr>
        </tbody>
     </table>





</div>


<div style="font-size: 13px;font-weight: bold;text-align: left;">
    <p style="margin-left:10%;color:green">
        @if ($data->est_activer == 2)
            <b style="text-decoration:underline">
                Réglement :
            </b>
            <em>
                facture réglé par : {{$data->paiementecheance->modepaiement->designation}}.
            </em>
            <br>
            @if ($data->paiementecheance->numero_cheque)
                <em style="margin-left:5%;">
                    Référence : {{$data->paiementecheance->numero_cheque}}
                </em>
            @endif





        @endif
    </p>

    <p>
        <b style="text-decoration:underline"> Date d'éxigibilité : </b> {{ $data->date_echeance_fr ? $data->date_echeance_fr : "........................"}}

    </p>

    <p style="font-weight: bold;">
        <b style="text-decoration: underline">Banque</b> :
        <?php
        if(isset($infosbancaire)){
            echo $infosbancaire['banque'];
        }
        ?>
        <br>  <br>
        <b style="text-decoration: underline">Agence</b> :
        <?php
        if(isset($infosbancaire)){
            echo $infosbancaire['agence'];
        }
        ?>
        <br> <br>
        <b style="text-decoration: underline">Code Banque</b> :
        <?php
        if(isset($infosbancaire)){
            echo $infosbancaire['codebanque'];
        }
        ?>
        <br> <br>
        <b style="text-decoration: underline">Code Guichet</b> :
        <?php
        if(isset($infosbancaire)){
            echo $infosbancaire['codeguichet'];
        }
        ?>
        <br> <br>
        <b style="text-decoration: underline">Numero compte</b> :
        <?php
        if(isset($infosbancaire)){
            echo $infosbancaire['numerocompte'];
        }
        ?>
        <br> <br>
        <b style="text-decoration: underline">Clé RIB</b> :
        <?php
        if(isset($infosbancaire)){
            echo $infosbancaire['clerib'];
        }
        ?>

    </p>


</div>

<div style="font-size: 13px;font-weight: bold;text-align: right;margin: -40% 0 10px;text-transform: uppercase">
    <p style="text-decoration:underline;">Le Responsable administratif et financier </p>

    @if ($data->signature)
    <?php
    $url = $data->signature;
    $url = explode('uploads',$url);
    if(isset($url) && count($url) > 0){
        $url = 'uploads/'.$url[1];
    }
    ?>
    <img style="width: 20%;" src="{{$url}}" alt="1">
    <p style="margin-top: -30px !important;"> {{Outil::resolveDateFr($data['datesignature'])}}</p>
    @else
    <br>
    @endif


</div>
<div style="font-size: 13px;font-weight: bold;text-align: right;margin: -40% 0 10px;text-transform: uppercase">

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









