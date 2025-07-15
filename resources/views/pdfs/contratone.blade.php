<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>PDF des Contrats {{date('d/m/Y')}}</title>

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
<!--<header style="width:100%;">
    <div class="header" style="">
    </div>
</header>-->
<header style="width:100%;margin-top: -30px">
    <div class="header" style="">
        <img style="width: 10%" src="assets/images/maison.png" alt="">
    </div>
</header>
<!-- footer -->
<div class="footer mb-60" style="width: 700px">
    <div>
        <img style="width: 10%;" src="assets/images/maison.png" alt="">
    </div>
</div>
<body>
    <div style="text-align:center;margin-bottom:8px">Dakar, {{date('d/m/Y')}}<</div>

    <div style="margin-top: -30px">
        <div style="font-size: 13px;font-weight: bold;text-align: left;margin-left: 30px !important;">

            <h3 >Infos apartement</h3>
            <div class="" style="">

                <h5 ><b style="text-transform: uppercase;">Nom : </b> {{ $data[0]["appartement"]["nom"] }} </h5>
                <h5 ><b style="text-transform: uppercase;">Immeuble : </b> {{ $data[0]["appartement"]['immeuble']["nom"] }} </h5>
                <h5 ><b style="text-transform: uppercase;">Adresse : </b> {{ $data[0]["appartement"]['immeuble']["adresse"] }} </h5>
                {{-- <h4 class="float-start"><b>Date de naissance : </b> 22/11/1998 </h4> --}}
            </div>
        </div>
        <div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -220px;font-weight: bold;">
            <h3 >Infos locataire</h3>
            @if ($data[0]['locataire']['nomentreprise'])
            <div>
                <h5 ><b style="text-transform: uppercase;">Nom entreprise : </b> {{ $data[0]["locataire"]["nomentreprise"] }} </h5>
                <h5 ><b style="text-transform: uppercase;">Adresse : </b> {{ $data[0]["locataire"]["adresseentreprise"] }} </h5>
                <h5 ><b style="text-transform: uppercase;">Email personne a contacter : </b> {{ $data[0]["locataire"]["emailpersonneacontacter"] }} </h5>
                {{-- <h4 ><b>Date de naissance : </b> 22/11/1998 </h4> --}}
            </div>
            @endif
            {{-- @dd($data[0]['locataire']) --}}
            @if ($data[0]['locataire']['nom'])
            <div >
                <h5 ><b style="text-transform: uppercase;">Nom : </b> {{ $data[0]["locataire"]["nom"] }} </h5>
                <h5 ><b style="text-transform: uppercase;">Prenom : </b> {{ $data[0]["locataire"]["prenom"] }} </h5>
                {{-- <h5 ><b style="text-transform: uppercase;">Prenom : </b> {{ $data[0]["locataire"]["prenom"] }} </h5> --}}
                <h5 ><b style="text-transform: uppercase;">Email : </b> {{ $data[0]["locataire"]["email"] }} </h5>
                {{-- <h4 ><b>Date de naissance : </b> 22/11/1998 </h4> --}}
            </div>
            @endif


        </div>
    </div>
    <br><br>

    {{-- <div class="container">
        <div class="row">
            <div class="col-md-6 float-start" >

            </div>
            <div class="col-md-6 float-end">
                <h3>Info</h3>
            </div>
        </div>
    </div> --}}
<div>
    <h3 style="text-align: left;" >Infos loyer</h3>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">Descriptif contrat</th>
            <th class="whitespace-no-wrap">Date d'enregistrement</th>
            <th class="whitespace-no-wrap">Date de debut contrat</th>
            <th class="whitespace-no-wrap">Montant loyer base</th>
            <th class="whitespace-no-wrap">Montant loyer</th>
        </tr>

        {{-- @for ($i = 0; $i < count($data); $i++)>

        @endfor --}}
        <tr class="tr">

            <td class="td">{{ $data[0]["descriptif"] }}</td>
            <td class="td">{{ $data[0]["dateenregistrement"]}}</td>
            <td class="td">{{ $data[0]["datedebutcontrat"]}}</td>
            <td class="td">{{ $data[0]["montantloyerbase"]}}</td>
            <td class="td">{{ $data[0]["montantloyer"]}}</td>
        </tr>
    </table>

</div>
<div>
    <h3 style="text-align: left;"> Caution</h3>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">Date de versement caution</th>
            <th class="whitespace-no-wrap">Montant caution</th>
        </tr>

        <tr class="tr">
            <td class="td">
                @if ($data[0]["caution"])
                    {{ $data[0]["caution"]["dateversement"]}}
                @endif

            </td>
            <td class="td">
                @if ( $data[0]["caution"])
                {{ $data[0]["caution"]["montantcaution"] }}
                @endif
            </td>
        </tr>
    </table>

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
</body>
</html>









