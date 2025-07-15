<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>PDF des Contrats {{date('d/m/Y')}}</title>

    <style>
        @page {
            margin: 20px 0px;
        }

        table,
        th,
        td {
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
            border: 1px solid black;
            letter-spacing: 1px;
            font-size: 0.6rem;
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

        * {

            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
            text-align: justify;
        }



        /* Stylisation pour les éléments de liste (li) */
        li {
            font-size: 16px;
            /* Taille de police */
            color: #333;
            /* Couleur du texte */
            margin-bottom: 5px;
            /* Marge en bas pour espacer les éléments */
        }
    </style>

</head>
<!--<header style="width:100%;">
    <div class="header" style="">
    </div>
</header>-->
<header style="width:100%;margin-top: -30px">
    <div class="header" style="">
        <img style="width: 10%" src="{{$data[0]['appartement']['entite']['image']}}" alt="1">

    </div>
</header>
<!-- footer -->
<div class="footer mb-60" style="width: 700px">
    <div>
        <img style="width: 10%" src="{{$data[0]['appartement']['entite']['image']}}" alt="1">

    </div>
</div>

<body>
    <div style="text-align:center;margin-bottom:18px"><b>CONTRAT DE LOCATION</b></div>
    <p style="text-align:left;margin-bottom:8px">Entre les soussignés: <b>SCI REYHAN dont les bureaux sont à Dakar</b>, au 41 Rue C, X Rue Léon Gontran Damas, Fann Résidence titulaire du NINEA 30865942 V7, représentée par sa Gérante, Madame Marieme Ngom,
    </p>
    <p style="text-align:left;margin-bottom:8px"> Ci-après dénommée <b>« {{ ($data[0]['appartement'] && $data[0]['appartement']['proprietaire']) ? $data[0]['appartement']['proprietaire']['prenom']." ".$data[0]['appartement']['proprietaire']['nom'] : "Le bailleur" }} »</b> </p>
    <p style="text-align:right;margin-bottom:8px">
        D'une part, </p>
    <p style="text-align:left;margin-bottom:8px">
        @if ($data[0]['locataire']['nomentreprise'])
        Et <b>{{$data[0]['locataire']['nomentreprise'] ? $data[0]['locataire']['nomentreprise'] : $data[0]['locataire']['prenom'].' '.$data[0]['locataire']['nom']}}</b>, {{$data[0]['locataire']['nomentreprise'] ? $data[0]['locataire']['adresseentreprise'].',' : ' '}}{{$data[0]['locataire']['numerorg'] ? ' Immatriculée au registre de commerce sous le n°'.$data[0]['locataire']['numerorg'].', ' : ' '}} {{$data[0]['locataire']['ninea'] ? 'NINEA '.$data[0]['locataire']['ninea'].', ' : ""}} {{$data[0]['locataire']['fonctionpersonnehabilite'] ? ' représentée par son '.$data[0]['locataire']['fonctionpersonnehabilite'].', ' : "" }} {{$data[0]['locataire']['personnehabiliteasigner'] ? $data[0]['locataire']['personnehabiliteasigner'] : "" }}, dûment habilité aux fins des présentes.

        @endif
        @if ($data[0]['locataire']['nom'])
        Et <b>{{$data[0]['locataire']['prenom'].' '.$data[0]['locataire']['nom']}}</b>, {{"adresse ".$data[0]['appartement']['immeuble']['adresse'].','}} {{$data[0]['locataire']['telephoneportable1'] ? ' Téléphone '.$data[0]['locataire']['telephoneportable1'].', ' : ' '}} <b>{{$data[0]['locataire']['cni'] ? 'CNI '.$data[0]['locataire']['cni'].', ' : ""}}</b> dûment habilité aux fins des présentes.
        @endif

    </p>
    <p style="text-align:right;margin-bottom:8px">
        @if ($data[0]['locataire']['nomentreprise'])
        Ci-après dénommée <b>« {{ $data[0]["locataire"]["nomentreprise"] }} » </b>
        @endif

        @if ($data[0]['locataire']['nom'])
        Ci-après dénommée <b>« {{ $data[0]["locataire"]["nom"] }} {{ $data[0]["locataire"]["prenom"] }} » </b>
        @endif

    </p>

    <p>
        D'autre part,
    </p>
    <p>
        IL A ETE CONVENU ET ARRETE CE QUI SUIT:

    </p>
    <p>
        @if ($data[0]['appartement']['entite']['code'] == "RID")
        Le Bailleur loue au Preneur qui accepte un appartement sis à {{$data[0]['appartement']['immeuble']['adresse']}}, {{$data[0]['appartement']['ilot']['adresse']}} et objet du <b>lot {{$data[0]['appartement']['lot']}} </b> de ladite Résidence dont

        :

        @endif
        @if ($data[0]['appartement']['entite']['code'] == "SCI")
        Le Bailleur loue au Preneur qui accepte un appartement sis à {{$data[0]['appartement']['immeuble']['adresse']}}, dont le descriptif est le suivant :
        @endif

    </p>
    <br>
    @foreach ($data[0]['appartement']['typeappartement']['typeappartement_pieces'] as $item)
    <p>
        - {{$item['typepiece']['designation']}}

    </p>
    @endforeach
    <br>

    <p>
        Tel que le tout se poursuit, s'étend et se comporte et sans qu'il soit besoin d'en établir une description plus détaillée, le Preneur déclarant connaître parfaitement les lieux pour les avoir visités. Un état des lieux sera fait contradictoirement avec le Preneur lors de la prise de possession des lieux.
    </p>
    <br>
    <p style="text-decoration : underline;">
        LOYER
    </p>
    <br>
    <p>
        La présente location est consentie et acceptée moyennant un loyer mensuel de <b>{{ $data[0]['montantloyerformatletter']}} ({{ $data[0]['montantloyerformat']}}) F CFA</b> TOM et charges comprises. Le loyer est payable trimestriellement et d'avance au plus tard le 05 de chaque mois débutant un trimestre. Il est précisé que le loyer est portable et non quérable c'est-à-dire qu'il est payable aux bureaux de la SCI REYHAN.

    </p>

    <p style="text-align:right;font-weight:bold;">
        {{$data[0]['appartement']['nom']}}
    </p>
    <br>
    <p style="text-decoration : underline;">
        DETAIL DU LOYER
    </p>
    <br>
    <p>
        <p style="text-align:left">
            <b> Loyer de base </b>
        </p>
        <p style="text-align:right">
            <b>{{$data[0]['montantloyerbaseformat']}}</b> F CFA
        </p>
    </p>
    <p>
        <p style="text-align:left">
            TOM
        </p>
        <p style="text-align:right">
            <b>{{$data[0]['montantloyertomformat']}}</b> F CFA
        </p>
    </p>
    <p>
        <p style="text-align:left">
            Charges
        </p>
        <p style="text-align:right">
            <b>{{$data[0]['montantchargeformat']}}</b> F CFA
        </p>
    </p>
    <p style="text-decoration : underline;">

    </p>
    <p>
        <p style="text-align:left;text-decoration : underline">
            TOTAL
        </p>
        <p style="text-align:right;font-weight:bold">
            {{$data[0]['total_loyer_format']}} F CFA
        </p>
    </p>

    <br>
    <br>
  
  

  
   
   
    <p style="text-align: right;font-size:20px">
        Fait a DAKAR le {{date('d/m/Y')}}
    </p>


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
</body>

</html>