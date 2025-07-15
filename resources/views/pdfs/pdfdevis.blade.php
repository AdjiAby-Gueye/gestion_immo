@php
                            use App\Outil;
                                $montanttotaltva = 0;
                                $montanttva18 = 0;
                                $montanttotalettc = 0;
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
    <title>PDF facture {{ date('d/m/Y') }}</title>

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
            bottom: 120px;
            height: 2.3cm;
            left: 0%;
            text-align: left;
            /* Aligner le texte à gauche */
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

        .titre-top-3 {
            font-weight: bold ;
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

        .bordure {
            font-weight: bold;

        }
    </style>

</head>
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 30%" src="assets/images/sertem-logo.png" alt="1">
    </div>
</header>

<body>
    <div style="font-size: 13px; text-align: right; margin-right: 30px !important; margin-top: -60px">
        <div>
            Dakar, le {{$item[0]["date_fr"]}}
        </div>
    </div>
    

    <div style="font-size: 13px;font-weight: bold;text-align: left;margin: 30px 0 10px;text-transform: uppercase">
        <p>
            Devis : {{ $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['code'] }}
        </p>
        <p>
            Affaire : {{ $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['object'] }}
        </p>

    </div>
    <br>

    <div
        style="font-size: 13px;font-weight: bold;text-align: right;margin: 30px 0 10px;text-transform: uppercase;margin-top:-10%">
        <p>
            @if($data[0]['detaildevisdetails'][0]['detaildevi']['devi']['demandeintervention'] != null && $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['etatlieu'] == null)
            {{ $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['demandeintervention']['appartement']['entite']['designation'] }}
            @endif

            @if($data[0]['detaildevisdetails'][0]['detaildevi']['devi']['demandeintervention'] == null && $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['etatlieu'] != null)
            {{ $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['etatlieu']['appartement']['entite']['designation'] }}
            @endif
        </p>

        <p>
            @if($data[0]['detaildevisdetails'][0]['detaildevi']['devi']['demandeintervention'] != null && $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['etatlieu'] == null)
            {{ $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['demandeintervention']['immeuble']['adresse'] }}
            @endif

            @if($data[0]['detaildevisdetails'][0]['detaildevi']['devi']['demandeintervention'] == null && $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['etatlieu'] != null)
            {{ $data[0]['detaildevisdetails'][0]['detaildevi']['devi']['etatlieu']['appartement']['immeuble']['adresse'] }}
            @endif

        </p>

    </div>
    <div>






        <table class="table mb-20" style="border: none">
            <thead>
                <tr class="tr header" style="background-color: lightgray;">
                    <th>N*</th>
                    <th>TRAVEAUX A REALISER </th>
                    <th>U</th>
                    <th>QTE</th>
                    <th>P.U HTVA</th>
                    <th>P.T HTVA</th>
                </tr>
            </thead>
            <tbody style="border: 1.1px solid black ">


                @foreach ($data as $item)
                    <tr style="border: none ">
                        <td style="border-top: 1px solid white;border-bottom: 1px solid white;"></td>
                        <td style="border-top: 1px solid white;border-bottom: 1px solid white;">
                            <span
                                class="titre-top-3">{{ $item['detaildevisdetails'][0]['detaildevi']['categorieintervention']['designation'] }}
                            </span> <br>
                            @foreach ($item['detaildevisdetails'] as $elm)
                                <span style="margin-left: 10px">{{ $elm['soustypeintervention']['designation'] }}</span>
                                <br>
                            @endforeach
                        </td>
                        <td class="text-center" style="border-top: 1px solid white;border-bottom: 1px solid white;">
                            <span></span> <br>
                            @foreach ($item['detaildevisdetails'] as $elm)
                                <span style="margin-left: 10px">{{ $elm['unite']['designation'] }}</span> <br>
                            @endforeach
                        </td>
                        <td class="text-center" style="border-top: 1px solid white;border-bottom: 1px solid white;">

                            <span></span> <br>
                            @foreach ($item['detaildevisdetails'] as $elm)
                                <span style="margin-left: 10px">{{ $elm['quantite'] }}</span> <br>
                            @endforeach

                        </td>
                        <td class="text-center" style="border-top: 1px solid white;border-bottom: 1px solid white;">

                            <span></span> <br>
                            @foreach ($item['detaildevisdetails'] as $elm)
                                <span style="margin-left: 10px">{{ $elm['prixunitaire'] }}</span> <br>
                            @endforeach

                        </td>
                        <td  class="text-center"  style="border-top: 1px solid white;border-bottom: 1px solid white;">
                            

                            <span></span> <br>
                            @foreach ($item['detaildevisdetails'] as $elm)
                                @php
                                    $prixTotalHTVA = $elm['quantite'] * $elm['prixunitaire'];
                                    $montanttotaltva += $prixTotalHTVA;
                                    $montanttva18 = $montanttotaltva * 0.18;
                                    $montanttotalettc = $montanttotaltva + $montanttva18;
                                @endphp
                                <span style="margin-left: 10px">{{ $prixTotalHTVA }}</span> <br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach




            </tbody>
            <tfoot>
                <tr>
                    <td style="border: none">
                    </td>
                    <td style="border: none">
                    </td>
                    <td style="border: none">

                    </td>
                    <td style="border: none">

                    </td>
                    <td style="border: none">

                    </td>
                    <td style="border: none">

                    </td>

                </tr>
               

                <tr style="border: none ">
                    <td style="border: none">
                    </td>
                    <td class="text-center bordure "style="background: lightgray;">
                        MONTAN TOTAL HTVA
                    </td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td class="text-center bordure "style="background: lightgray;">
                        @php
                        echo number_format($montanttotaltva, 2);
                    @endphp
                    </td>
                </tr>

                <tr style="border: none">
                    <td style="border: none">
                    </td>
                    <td class="text-center bordure "style="background: lightgray;">
                        MONTANT TVA 18%
                    </td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td class="text-center bordure "style="background: lightgray;">
                        @php
                        echo number_format($montanttva18, 2);
                    @endphp
                    </td>
                </tr>
                <tr style="border: none">
                    <td style="border: none">
                    </td>
                    <td class="text-center bordure "style="background: lightgray;">
                        MONTANT TOTAL TTC
                    </td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td class="text-center   bordure" style="background: lightgray">
                     
                        @php
                        echo number_format($montanttotalettc, 2);
                    @endphp
                    </td>
                </tr>

            </tfoot>
        </table>





        <div style="font-size: 13px;font-weight: bold;text-align: left;margin: 10px 0 10px;">
            <p style="">Arrete a le present devis a la somme de {{ Outil::convertirEnLettres($montanttotalettc)}}  francs CFA </p>
        </div>

        <div class="footer" style="font-size: 13px;font-weight: bold;">
            <p style="font-weight: bold;">Route de la Corniche Ouest , en face ARTP</p>
            <p style="font-weight: bold;">B.P 6640 Dakar - Sénegal</p>
            <p style="font-weight: bold;">Tel : 33 820 20 20</p>
            <p style="font-weight: bold;">info@sertemgroupe.com</p>
            <p style="font-weight: bold;">www.sertem.sn</p>
            <p style="font-weight: bold;">R.C : SN-DKR 1990 -NINEA : 000400565</p>
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
