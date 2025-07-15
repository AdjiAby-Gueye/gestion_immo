<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="...">
    <meta name="keywords" content="...">
    <title>PDF rapport d'etat lieu {{date('d/m/Y')}}</title>

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
        <img style="width: 20%" src="{{$data[0]->appartement->entite->image }}" alt="1">
    </div>
</header>
<!-- footer -->
<div class="footer mb-60" style="width: 700px">
    <div>
        <img style="width: 20%" src="{{$data[0]->appartement->entite->image }}" alt="1">
    </div>
</div>
<body>
    {{-- <div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -60px">
        <div>Dakar, {{date('d/m/Y')}}<</div>
    </div> --}}
    <div style="font-size: 22px;font-weight: bold;text-align: center;margin: 30px 0 10px;text-transform: uppercase;margin-top: -60px">
        <div style="font-weight: 500;font-size: 18px;">ETAT DES LIEUX {{$data[0]->type != "sortie" ? "D' ".$data[0]->type : "DE ".$data[0]->type }} </div>
    </div>
    <br><br>
    <br><br>
    <br><br>

    <div style="margin-top: -120px">
        @if ($data[0]->appartement->nom)
            <div  style="font-size: 13px;font-weight: bold;text-align: left;margin-left: 30px !important;">

                <h3 style="text-transform: uppercase;">Infos apartement</h3>
                <div class="" style="">

                    <h5 ><b style="text-transform: uppercase;">Nom appartement : </b> {{ $data[0]->appartement->nom ?? "" }} </h5>
                    {{-- <h5 ><b style="text-transform: uppercase;">Immeuble : </b> {{ $data[0]->appartement->immeuble->nom }} </h5> --}}
                    <h5 ><b style="text-transform: uppercase;">Adresse : </b> {{ $data[0]->appartement->immeuble->adresse ?? "" }} </h5>
                    <h5 ><b style="text-transform: uppercase;">Propriétaire : </b> {{ $data[0]->appartement->proprietaire->prenom ?? "" }} {{ $data[0]->appartement->proprietaire->nom ?? "" }} </h5>
                </div>
            </div>
        @endif
        @if (!$data[0]->appartement->nom)
        <div  style="font-size: 13px;font-weight: bold;text-align: left;margin-left: 30px !important;">

            <h3 style="text-transform: uppercase;">Infos villa</h3>
            <div class="" style="">

                <h5 ><b style="text-transform: uppercase;">Lot : </b> {{ $data[0]->appartement->lot ?? "" }} </h5>
                {{-- <h5 ><b style="text-transform: uppercase;">Immeuble : </b> {{ $data[0]->appartement->immeuble->nom }} </h5> --}}
                <h5 ><b style="text-transform: uppercase;">Ilot : </b> {{ $data[0]->appartement && $data[0]->appartement->ilot ? ' N '.$data[0]->appartement->ilot->numero  : ".............."}} </h5>
                <h5 ><b style="text-transform: uppercase;">Adresse : </b> {{ $data[0]->appartement && $data[0]->appartement->ilot ? $data[0]->appartement->ilot->adresse  : ".............." }} </h5>
            </div>
        </div> 
        @endif
        <div style="font-size:13px;text-align: right; margin-right: 30px !important;margin-top: -220px;font-weight: bold;" class="mb-30">
            <h3 style="text-transform: uppercase;" >Infos locataire / réservataire</h3>
            @if ($data[0]->locataire->nomentreprise)
            <div >
                <h5 ><b style="text-transform: uppercase;">Nom entreprise : </b> {{ $data[0]->locataire->nomentreprise ?? "" }} </h5>
                <h5 ><b style="text-transform: uppercase;">Adresse : </b> {{ $data[0]->locataire->adresseentreprise ?? "" }} </h5>
                <h5 ><b style="text-transform: uppercase;">Email personne a contacter : </b> {{ $data[0]->locataire->emailpersonneacontacter ?? "" }} </h5>
            </div>
            @endif

            @if ($data[0]->locataire->nom)
            <div >
                <h5 ><b style="text-transform: uppercase;">Nom : </b> {{ $data[0]->locataire->nom ?? "" }} </h5>
                <h5 ><b style="text-transform: uppercase;">Prenom : </b> {{ $data[0]->locataire->prenom ?? "" }} </h5>
                <h5 ><b style="text-transform: uppercase;">Email : </b> {{ $data[0]->locataire->email ?? "" }} </h5>

            </div>
            @endif




        </div>

        <div style="text-align: left;margin-left: 30px !important;font-size:12px" class="mb-30">
            <div class="" style="color:#c3c6c9">
                <em>
                    L’état des lieux doit être établi de façon contradictoire entre les deux parties lors de la remise des clés au locataire et lors de leur
                    restitution en fin de bail. L'état des lieux prévu à l'article 3-2 de la loi du 6 juillet 1989 doit porter sur l'ensemble des locaux et
                    équipements d'usage privatif mentionnés au contrat de bail et dont le locataire a la jouissance exclusive.
                </em>
            </div>
        </div>
        {{-- @dd($data[0]->appartement->detailcompositions) --}}
        @if ($data[0]->etatlieu_pieces[0])
        @foreach ($data[0]->etatlieu_pieces as $item1)

        <div style="text-align: left;margin-left: 30px !important;">
            <h3 style="text-transform: uppercase;" > {{$item1->composition->Typeappartement_piece->typepiece->designation}} </h3>
            {{-- <h5 style="text-transform: uppercase;margin-bottom:0%;margin-left:2%;" >Constituants </h5> --}}
            {{-- <div class="" > --}}


                {{-- @dd($data[0]->etatlieu_pieces[0]->detailequipements[1]) --}}
                {{-- @dd($data2[0]) --}}
                {{-- {{ $arraytest = Outil::getAllItemsWithGraphQl('detailconstituants' , "etatlieu_piece_id:{$item1->id}")}} --}}
                {{-- @dd($data[0]->appartement->detailcompositions[0]->equipement) --}}
                <span style="margin-left: 4px">Constituants</span>
                <table class="table mb-20">
                    <tr class="tr">
                        <th class="whitespace-no-wrap">Elément</th>
                        <th class="whitespace-no-wrap">Observation</th>
                        <th class="whitespace-no-wrap">Commentaire</th>
                    </tr>
                    @if (count($item1->detailconstituants) > 0)
                        @foreach ($item1->detailconstituants as $item2)

                            <tr class="tr">
                                <td class="td">{{ $item2->constituantpiece->designation }}</td>
                                <td class="td">{{ $item2->observation->designation ?? "" }}</td>
                                <td class="td">{{ $item2->commentaire ?? "" }}</td>
                            </tr>

                        @endforeach
                    @else
                        @foreach ($constituantpieces as $item2)

                            <tr class="tr">
                                <td class="td">{{ $item2->designation }}</td>
                                <td class="td">  </td>
                                <td class="td"> </td>
                            </tr>

                        @endforeach
                    @endif

                    {{-- {{ dump($data[0]->etatlieu_pieces[0]->detailequipements) }} --}}

                        {{-- @dd($item1->detailconstituants) --}}
                    {{-- @foreach ($constituantpieces as $item2)
                        @if ($detailconstituant)
                            @foreach ($detailconstituant as $item5)
                                @if ($item2->id == $item5->constituantpiece_id && $item1->id == $item5->etatlieu_piece_id)
                                <tr class="tr">
                                    <td class="td">{{ $item2->designation }}</td>
                                    <td class="td">{{$item5->observation->designation}}</td>
                                    <td class="td">{{$item5->commentaire}}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach --}}




                </table>
                <span style="margin-left: 4px">Equipements</span>
                <table class="table mb-20">
                    <tr class="tr">
                        <th class="whitespace-no-wrap">Elément</th>
                        <th class="whitespace-no-wrap">Observation</th>
                        <th class="whitespace-no-wrap">Commentaire</th>
                    </tr>
                    {{-- @dd($item1->detailequipements) --}}
                    @if (count($item1->detailequipements) > 0)
                        @foreach ($item1->detailequipements as $item3)
                            @if ($item3->equipementpiece->generale == 0)
                                <tr class="tr">
                                    <td class="td">{{ $item3->equipementpiece->designation }}</td>
                                    <td class="td">{{$item3->observation->designation ?? ""}}</td>
                                    <td class="td">{{$item3->commentaire ?? ""}}</td>
                                </tr>

                            @endif

                        @endforeach
                    {{-- @else

                        @foreach ($equipements as $item3)
                            @if ($item3->generale == 0)
                                <tr class="tr">
                                    <td class="td">{{ $item3->designation }}</td>
                                    <td class="td"> </td>
                                    <td class="td"> </td>
                                </tr>

                            @endif
                        @endforeach --}}

                    @endif

                </table>




            {{-- </div> --}}
        </div>

        @endforeach
        {{-- @dd($item1->id) --}}

        <div  style="text-align: left;margin-left: 30px !important;font-size:12px" class="mb-30">
            <span style="margin-left: 4px">Equipements générales</span>
                <table class="table mb-20">
                    <tr class="tr">
                        <th class="whitespace-no-wrap">Elément</th>
                        <th class="whitespace-no-wrap">Observation</th>
                        <th class="whitespace-no-wrap">Commentaire</th>
                    </tr>
                    {{-- @dd($data[0]->Equipementpieces) --}}
                    @if (count($data[0]->etatlieu_pieces[0]->detailequipements) > 0)
                        @foreach ($data[0]->etatlieu_pieces[0]->detailequipements as $item5)
                            @if ($item5->equipementpiece->generale == 1)
                            <tr class="tr">
                                <td class="td">{{ $item5->equipementpiece->designation }}</td>
                                <td class="td">{{$item5->observation->designation ?? ""}}</td>
                                <td class="td">{{$item5->commentaire ?? ""}}</td>
                            </tr>
                            @endif
                        @endforeach

                    @endif


                </table>
        </div>
        @endif
    </div>
<div>

    {{-- <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">N°</th>
            <th class="whitespace-no-wrap">Descriptif</th>
            <th class="whitespace-no-wrap">Locataire</th>
            <th class="whitespace-no-wrap">Adresse appartement </th>
            <th class="whitespace-no-wrap">Montant loyer </th>
        </tr>


    </table> --}}

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









