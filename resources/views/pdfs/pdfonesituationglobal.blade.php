

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>PDF CAUTION {{ date('d/m/Y') }}</title>

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
        <img style="width: 20%" src="{{ $data[0]['locataire']['entite']['image'] }}" alt="1">
    </div>
</header>
<div class="footer " style="width: 100%; margin-bottom: 30%">
    <div style="text-align: right;font-weight: bold">
        <p style="text-decoration:underline;">La gerante </p>
        <br>

        <br>
        <br>
        <br>

        <p style="text-decoration:underline;">Marieme Ngom </p>
    </div>
    <div>
        <p style="font-weight: 100; color: lightskyblue">Route de la corniche Ouest , en face ARTP</p>
        <p style="font-weight: 100;color: lightskyblue">NINEA :30865942 -CBAO Soumbedioune -sN012 01217 036158967501 96
        </p>
        <p style="font-weight: 100;color: lightskyblue">Tel : 33 869 82 20 - 77 819 20 54 -BP 6640 Dakar - Sénegal</p>
    </div>
</div>

<body>



    <div>
        <div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -60px">
            <div>Dakar, {{ date('d/m/Y') }}</div>
        </div>
        <div style="font-size: 15px;font-weight: bold;text-align: center;text-transform: uppercase">


            <p style="text-decoration:underline;">


            </p>
            <br>

        </div>
        <div style="font-size: 13px;font-weight: bold;text-align: right;margin: 30px 0 10px">
            A MONSIEUR 
            
            @if($data[0]['locataire']['typelocataire']['designation'] != "Physique")
                {{$data[0]['locataire']['nomentreprise'] }}
            @else
                {{$data[0]['locataire']['nom'] }} {{$data[0]['locataire']['prenom'] }}
            @endif
            
            <br>
            {{ $data[0]['locataire']['adresseentreprise'] }}
            <br>
            @if ($data[0]['locataire']['nomentreprise'] != null)
                {{ $data[0]['locataire']['nomentreprise'] }} <br>
            @endif
            <br>

        </div>
        <div style="font-size: 15px;text-align: left;margin: 30px 0 5px;">


            <p style="">
                <i class="fa fa-underline" style="text-decoration:underline;font-weight: bold;text-transform: uppercase"
                    aria-hidden="true">
                    Nos Réf :
                </i> MPN/DN/{{ date('d/m/Y') }}<br> <br>


                <i class="fa fa-underline" style="text-decoration:underline;font-weight: bold;text-transform: uppercase"
                    aria-hidden="true">
                    OBJET :
                </i> Appartement {{ $data[0]['appartement']['nom'] }}
            </p>
            <br>


            <p>
                Monsieur,
            </p>


            <p>
                Suite à la résiliation du contrat de location qui nous liait pour l'appartement cité en objet, veuillez
                trouver, ci-joint, les documents suivants:
            </p>
            <p style="margin-left: 20px">
                -Le devis des travaux de remise en état de l'appartement <br>
                -La facture d'eau au
               

                @foreach ($data[0]['locataire']['contrats'] as $contrat)
                        {{$contrat['date_dernier_facture_eau']}} 
                @endforeach

               


                <br>

                -La situation du dépôt de garantie


            </p>
            <p>
                A l'examen de ces documents, vous pouvez, donc, constater que nous devons vous restituer la somme de
                @foreach ($data[0]['locataire']['contrats'] as $contrat)
                @if (
                    $contrat['appartement']['id'] == $data[0]['appartement_id'] &&
                        $contrat['locataire']['id'] == $data[0]['locataire_id']
                )
                    {{ $contrat['somme_a_restituer'] - $contrat['etatlieu_sortie']['factureintervention']['montant'] }}
                    F
                @endif
            @endforeach
               sur la caution de l'appartement.

            </p> <br>
            <p>
                Ainsi, vous voudrez bien nous retourner le devis des travaux avec la mention "Bon pour accord" .
            </p>

            <p>
                Vous en souhaitant bonne réception, nous vous prions d'agréer, Monsieur, l'expression de nos meilleures
                salutations.
            </p>




        </div>

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
