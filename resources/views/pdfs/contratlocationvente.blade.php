@php
use App\Helpers\NombreEnLettre;
use App\Outil;

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
    <title>PDF des Contrats {{date('d/m/Y')}}</title>

    <style>
        body{
            text-align: justify;
             text-justify: inter-word;
             line-height: 1.6;
        }

    .decaler-premiere-ligne {
      text-indent: 20px; /* Ajustez la valeur selon vos besoins */
      margin-right: 20px; /* Ajustez la valeur selon vos besoins */
      word-wrap: break-word;
    }
        @page {
            margin: 0px 0px;
        }


        /* table, th, td {
            border: 1px solid #585b5e;
            border-collapse: collapse;
            padding: .4rem;
        } */

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
            margin-top: 4.1cm;
            margin-left: 1.0cm;
            margin-right: 1.0cm;
            margin-bottom: 1cm;
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
{{-- @dd($niveauappartements); --}}
{{-- @dd($data); --}}
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 30%" src="assets/images/pm.png" alt="1">
    </div>
</header>
{{-- <div class="footer mb-20" style="width: 700px">
    <div>
        <img style="width: 20%;" src="assets/images/sertem-logo.png" alt="">
    </div>
</div> --}}
<body style="">
    <div style="text-align:center;margin-bottom:2px;text-decoration:underline;margin-top: 80%"><b>CONTRAT DE LOCATION VENTE</b></div>

    <div style="">
        <p> N&deg; Dossier: {{ $data[0]["numerodossier"] ?  $data[0]["numerodossier"].'…………' : "………………………………………………………………………………………………………" }}           N&deg; Compte Client : {{ $data[0]["locataire"] ?  $data[0]["locataire"]["numeroclient"].'…………' : "………………………………………………………………………………………………………" }} </p>

        <p style="text-decoration : underline;">
            ENTRE LES SOUSSIGNES
        </p>

        <p>
           <b>1.</b>
           « SERTEM PROPERTY MANAGEMENT » S.A.S.U. représentée par sa Directrice Générale Madame <b>Marième NGOM</b> ; <br>
           Mamelles - Route de la Corniche Ouest en face ARTP, et immatriculée au Registre du Commerce et du Crédit Mobilier sous le n° SN DKR 2020 B 32018. <br>
           BP 6640 Dakar Téléphone (+221) 33 869 77 67 <br>
           Fax (221) 33 824 83 00 <br>
        </p>
        <p>
            Agissant au nom et pour le compte de la société dénommée « SERTEM IMMO » S.A.,
             Société Anonyme avec Conseil d’Administration au capital de <b>Dix Millions (10.000.000) de FCFA</b>,
              ayant son siège social à Dakar (Sénégal), Mamelles - Route de la Corniche Ouest en face ARTP ;
               et immatriculée au Registre du Commerce et du Crédit Mobilier sous le numéro : <b>SN DKR 2012 B 14744</b> ;
        </p>
        <p>
            En vertu d’un Mandat de commercialisation signé le 13 septembre 2022.
        </p>

        <p style="text-align : center;">
            Ci-après dénommée <b>"BAILLEUR-CEDANT"</b>
        </p>
        <p style="text-align:right;margin-bottom:8px;text-decoration:underline;text-transform:uppercase">
            <b>D’UNE PART </b> </p>
        <p>
         <b>2.</b>

        </p>
        {{-- <p> --}}
            <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input mt-2" name="" id="" value="checkedValue" >
                La SCI* …………………………………………………………………………..représentée par :
              </label>
            </div>


        {{-- </p> --}}
        <p>
            (*A cocher uniquement si le réservataire est une SCI et non une personne physique)
        </p>
        <p>
            <b>Nom :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["nom"].'…………………………' : "………………………………………………………………………………………………………" }}
        </p>

        <p>
            <b>Prénom :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["prenom"].'…………………………' : "………………………………………………………………………………………………………" }}

        </p>
        <p>
            <b>NJF :</b>{{ $data[0]["locataire"] ?  $data[0]["locataire"]["njf"].'…………………………' : "………………………………………………………………………………………………………" }}

        </p>

        <p>
            <b>Date et lieu de naissance :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["date_naissance_format"].' , '.$data[0]["locataire"]["lieux_naissance"] : "………………………………………………………………………………………………………" }}
        </p>
        <p>
            <b>Nationalité :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["nationalite"].'…………………………' : "………………………………………………………………………………………………………" }}
        </p>
        <p>
            <b>Profession :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["profession"].'…………………………' : "……………………………………………………………………………." }}

        </p>
        <p>
            <b>Tél portable :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["telephoneportable1"].'…………………………'  : "……………………………………………………………………………." }}

        </p>
        <p>
            <b>E-Mail :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["email"].'…………………………'  : "……………………………………………………………………………." }}.

        </p>
        <p>
            <b>Adresse  :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["adresseentreprise"].'…………………………'  : "……………………………………………………………………………." }}

        </p>
        <p>
            <b>Code postal :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["codepostal"].'…………' : "…………" }} <b>Ville</b> : {{ $data[0]["locataire"] ?  $data[0]["locataire"]["ville"].'…………' : "…………" }} <b>Pays</b> : {{ $data[0]["locataire"] ?  $data[0]["locataire"]["pays_naissance"].'…………' : "…………" }}.
        </p>
        <p>
            <b>Situation familiale :</b> {{ $data[0]["locataire"] ?  $data[0]["locataire"]["situationfamiliale"].'…………' : "…………" }}
        </p>

            {{-- @if ($data[0]["locataire"] && $data[0]["locataire"]['nom'])
                <p >
                    2) Mr/Mme : <b>{{ $data[0]["locataire"] ?  $data[0]["locataire"]["prenom"]." ".$data[0]["locataire"]["nom"] : ".................. ..................." }}</b> <br>
                    Pays de résidence: ................ <br>
                    Date et lieu de naissance: ...........<br>
                    Profession : {{ $data[0]["locataire"] ?  $data[0]["locataire"]["profession"] : ".................." }} <br>
                    Adresse: ........................ <br>
                    Mobile: {{ $data[0]["locataire"] ?  $data[0]["locataire"]["telephoneportable1"] : "...................." }} <br>
                    Email: {{ $data[0]["locataire"] ?  $data[0]["locataire"]["email"] : "....................." }} <br>
                    Mandataire : .............................. <br>
                </p>
            @else
                <p >
                    2) Mr/Mme : ................</b> <br>
                    Pays de résidence: ............... <br>
                    Date et lieu de naissance: ...........<br>
                    Profession : ................... <br>
                    Adresse: ........................ <br>
                    Mobile: ................... <br>
                    Email: ............... <br>
                    Mandataire : ................... <br>
                </p>
            @endif --}}
            <p style="text-align : center;">
                Ci-après dénommée <b>« LE PRENEUR – CESSIONNAIRE » </b>
            </p>
            <p>
                <b>ET :</b>
            </p>

            <p>
                <b>Nom :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["nom"].'…………………………' : "………………………………………………………………………………………………………" }}
            </p>

            <p>
                <b>Prénom :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["prenom"].'…………………………' : "………………………………………………………………………………………………………" }}
            </p>

            <p>
                <b>NJF :</b>{{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["njf"].'…………………………' : "………………………………………………………………………………………………………" }}
            </p>


            <p>
                <b>Date et lieu de naissance :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["datenaissance_format"].''.(($data[0]["copreneur"] && $data[0]["copreneur"]["lieunaissance"]) ? " , ".$data[0]["copreneur"]["lieunaissance"] : '') : "………………………………………………………………………………………………………" }}
            </p>
            <p>
                <b>Nationalité :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["nationalite"].'…………………………' : "………………………………………………………………………………………………………" }}
            </p>
            <p>
                <b>Profession :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["profession"].'…………………………' : "……………………………………………………………………………." }}

            </p>
            <p>
                <b>Tél portable :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["telephone1"].'…………………………'  : "……………………………………………………………………………." }}

            </p>
            <p>
                <b>E-Mail :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["email"].'…………………………'  : "……………………………………………………………………………." }}.

            </p>
            <p>
                <b>Adresse  :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["adresse"].'…………………………'  : "……………………………………………………………………………." }}

            </p>
            <p>
                <b>Code postal :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["codepostal"].'…………' : "…………" }} <b>Ville</b> : {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["ville"].'…………' : "…………" }} <b>Pays</b> : {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["pays"].'…………' : "…………" }}.
            </p>
            <p>
                <b>Situation familiale :</b> {{ $data[0]["copreneur"] ?  $data[0]["copreneur"]["situationfamiliale"].'…………' : "………………………………………………………………" }}
            </p>
            <p style="text-align : center;">
                Ci-après nommé(e) <b>« LE CO-PRENEUR – CO-CESSIONNAIRE » </b>
            </p>
            <p style="text-align:right;margin-bottom:8px;text-decoration:underline;text-transform:uppercase">
            <b>D'autre part,</b> </p>

            <p style="text-decoration : underline;text-transform:uppercase;">
                <b>I / EXPOSE</b>
            </p>
            <p style="text-decoration : underline;">
                <b>1/ Assiette Foncière du Projet :</b>
            </p>
                <p>
                    Le bailleur-cédant est propriétaire d'un terrain sis à {{($data[0]['appartement'] && $data[0]['appartement']['ilot']) ? $data[0]['appartement']['ilot']['adresse'] : "……………………………………………………………………………"}},
                     d'une superficie de NEUF HECTARES QUATRE VINGT DEUX ARES QUATRE VINGT HUIT CENTIARES (9ha. 82a. 88ca)
                      formant l'îlot <b>numéro {{ ($data[0]['appartement'] && $data[0]['appartement']['ilot']) ? $data[0]['appartement']['ilot']['numero'] : "……………………………………………………………………………"}}</b> objet du  Titre Foncier
                      numéro VINGT ET UN MILLE HUIT CENT SOIXANTE CINQ  de la commune de <b>DAGOUDANE PIKINE (TF n°21.865/DP)</b>.
                </p>
                <p style="text-decoration : underline;">
                    <b>2/ Projet de construction :</b>
                </p>
                <p>
                    Le bailleur-cédant envisage d'édifier sur ledit terrain, un ensemble immobilier dénommé <b>"{{$data[0]['appartement']['ilot']['adresse']}}"</b>
                     comprenant des villas et des appartements dépendant d'immeubles en copropriété.
                </p>
                <p style="text-decoration : underline;">
                    <b>3/ Contrat sous condition résolutoire :</b>
                </p>
                <p>
                    Il est expressément stipulé aux présentes que ce projet est réalisé avec un
                    <b>financement de la BANQUE DE L’HABITAT DU SENEGAL (BHS)</b> et que cette dernière a
                    été consultée par « SERTEM IMMO » SA pour agréer la commercialisation du projet
                    « {{$data[0]['appartement'] && $data[0]['appartement']['ilot'] ? $data[0]['appartement']['ilot']['adresse'] : "……………………………………………………………………………"}} » sous forme de <b style="text-decoration:underline">contrat en location vente.</b>
                </p>
                <p>
                    La réponse de la banque n’ayant pas été obtenue à ce jour, le présent contrat sera signé
                    avec l’accord du preneur-cessionnaire <b>sous la condition résolutoire</b> du refus de la
                    BHS et qui aura pour conséquence la résiliation du présent contrat.
                </p>
                <p>
                    La portée de la présente <b>clause résolutoire</b> a été expliquée au preneur-cessionnaire
                    qui y souscrit entièrement et il lui a été expliqué que cette clause est une condition
                    impulsive et déterminante sans laquelle le bailleur-cédant n’aurait jamais signé le
                    présent contrat.
                </p>

                <p>
                    En cas de refus de la BHS de la commercialisation en location-vente des villas de
                    « {{$data[0]['appartement'] && $data[0]['appartement']['ilot'] ? $data[0]['appartement']['ilot']['adresse'] : "……………………………………………………………………………"}} »
                    , le preneur-cessionnaire s’oblige à libérer la villa de toute
                    {{-- occupation de personne ou de biens mobiliers dans un délai <b>d'<x-nombre-en-lettre :nombre="explode(' ',$data[0]['delaipreavi']['designation'])[0]"></x-nombre-en-lettre> mois ({{$data[0]['delaipreavi'] ? $data[0]['delaipreavi']['designation'] : "1 mois "}})</b>. Un état --}}
                    occupation de personne ou de biens mobiliers dans un délai <b>d'un mois (01 mois)</b>. Un état
                    des lieux contradictoire devra être effectué au moment de la libération des lieux par le
                    preneur-cessionnaire. Si le bailleur-cédant constate des dégradations après avoir
                    comparé l’état des lieux d’entrée et l’état des lieux de sortie, il peut appliquer une retenue
                    pour la remise en état du logement sur le montant versé par le preneur-cessionnaire.
                </p>
                <p>
                    Le remboursement des sommes perçues déduction faite du montant des loyers, des frais
                    de gestion et de dossier et éventuellement des frais de remise en état, interviendra dans
                    un délai de 90 jours à compter du procès-verbal d’état des lieux de sortie et de remise
                    des clés.
                </p>
                <p>
                   <b> CECI EXPOSE, il a été convenu et arrêté ce qui suit :</b>
                </p>

                <p style="text-decoration : underline;">
                    <b>OBJET DU CONTRAT</b>
                </p>
                <p>
                    Le bailleur-cédant promet formellement de vendre au preneur-cessionnaire susnommé,
                    qui accepte, un bien immobilier dont la désignation suit :
                </p>
                <p class="" style="margin-bottom: 3.6%">

                    Une villa de type <b>DALIA</b> d’une superficie approximative de <b>{{$data[0]['appartement']['superficie'] ? $data[0]['appartement']['superficie'] : "150.00" }}</b> mètres carrés utiles,
                    calculée en considération du périmètre intérieur, mur, cloison intérieure et gaines non
                    comprises et comprenant :

                </p>
                <p>

                    <ul>
                        <li>
                            Un bâtiment de type : rez-de-chaussée + 1 étage comprenant :
                         </li>
                            <ul>
                                @php
                                $niveauxAffiches = []; // Tableau pour suivre les niveaux déjà affichés
                            @endphp



                            @foreach ($data[0]['appartement']['compositions'] as $type)
                                @if ($type['niveauappartement_id'] && !in_array($type['niveauappartement_id'], $niveauxAffiches))
                                    @php
                                        // Ajouter le niveau actuel au tableau des niveaux affichés
                                        $niveauxAffiches[] = $type['niveauappartement_id'];
                                    @endphp

                                    @foreach ($niveauappartements as $niveau)
                                        @if ($type['niveauappartement_id'] == $niveau['id'])
                                            <li>
                                                <b>{{ ucfirst($niveau['designation']) }} :</b>
                                                @php
                                                    $piecesNiveau = [];
                                                @endphp

                                                @foreach ($data[0]['appartement']['compositions'] as $piece)
                                                    @if ($piece['niveauappartement_id'] == $type['niveauappartement_id'])
                                                        @php
                                                            $piecesNiveau[] = $piece['typeappartement_piece']['typepiece']['designation'];
                                                        @endphp
                                                    @endif
                                                @endforeach

                                                {{ implode(' – ', $piecesNiveau) }}
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            </ul>

                    </ul>

                </p>
                <p style="">
                    Elle est édifiée sur une parcelle de terrain formant le lot n°{{$data[0]['appartement']
                    ? $data[0]['appartement']['lot'] : "………………………………………"}} dans <b>
                        l’îlot {{$data[0]['appartement'] &&
                        $data[0]['appartement']['ilot'] ? $data[0]['appartement']['ilot']['numero'] : "………………………………………"}}
                    </b>
                     du lotissement à constituer. Ce lot a une superficie approximative  de <b>150.00 mètres</b>
                     carrés (dans la limite de trois pour cent en plus ou en moins, selon les règles de
                    tolérance édictées par la réglementation en vigueur des poids et mesures de la
                    République du SENEGAL) et sera détaché par voie de morcellement du <b>titre foncier n°21.865/DP.</b>
                </p>
                <p>
                    Aux présentes, sont annexés après avoir été visés par les parties :
                </p>
                <p>
                    <ul>
                        <li>
                            Le plan de masse sur lequel est édifiée la villa objet du présent contrat;
                        </li>
                        <li>
                            Le plan commercial de la villa de base indiquant les coupes et élévations
                            avec les cotes utiles et l’indication des surfaces de chacune des pièces et des
                            dégagements,
                        </li>
                        <li>
                            Une notice descriptive sommaire indiquant la nature et la qualité tant des
                            matériaux utilisés que des éléments d'équipements indispensables à l’implantation,
                            à l’utilisation ou à l’habitation de l'immeuble.
                        </li>
                    </ul>
                </p>
                <p>
                    Le bailleur-cédant se réserve le droit d’apporter de légères modifications au bien vendu
                    en fonction des détails d’exécution. Les plans et descriptifs définitifs feront l’objet d’un
                    dépôt chez le notaire de l’opération où ils pourront être consultés avant la signature de
                    l’acte de vente notarié.
                </p>
{{-- hhhh --}}
                <p>
                   <b>
                    La date de livraison des locaux faisant l’objet du présent contrat est prévue
                    : le {{$data[0]['dateremiseclesformat'] ? $data[0]['dateremiseclesformat'] .'………………………………………' : "yyyy-MM-dd"}}
                   </b>
                </p>
                <p style="text-decoration : underline;text-transform:uppercase">
                    <b>II / PRIX</b>
                </p>
                <p style="text-decoration: underline">
                    <b>
                       a) Montant du prix
                    </b>
                </p>
                <p>
                    La vente sera faite au preneur - cessionnaire au prix dès à présent fixé
                    par le bailleur-cédant de
                    ………
                    {{$data[0]['prixtotalvilla_format'] ?? "………………………………………"}} F CFA…………
                    {{-- ( <b sty><x-nombre-en-lettre :nombre="$data[0]['prixtotalvilla']"></x-nombre-en-lettre></b> ) FCFA HT se décomposant comme suit : --}}
                    ( <b>{{Outil::numberToLetter($data[0]['prixtotalvilla'] )}} </b> ) FCFA HT se décomposant comme suit :
                </p>
                <p>
                    -	Prix de vente de la villa au comptant :
                     {{$data[0]['prixvillaformat'] ?? "………………………………………"}} F HT <br>
                    -	Frais et coûts de la location-vente : {{$data[0]['fraiscoutlocationvente_format'] ?? "..................."}} F

                </p>
                <p>
                    Le prix de vente est fixé en fonction des données économiques connues à ce jour et sur
                    la base de 1 Euro égal à 655,957 F CFA.
                </p>
                <p>
                    Ce prix est réputé ferme, sauf modifications des conditions économiques actuellement
                    en vigueur et notamment en cas de modification du régime fiscal propre à la présente
                    opération. De telles modifications pouvant entraîner une révision du prix du contrat.
                </p>
                <p>
                    Par contre ce prix ne tient pas compte des frais à la charge du preneur - cessionnaire,
                    résultant :
                </p>
                <p>
                    <ul>
                        <li>
                            Des frais de dossier
                        </li>
                        <li>
                            De l'acte de vente notarié et des formalités de mutation y afférentes ;
                        </li>
                        <li>
                            Des charges de copropriété
                        </li>
                    </ul>


                </p>
                <p style="text-decoration : underline;text-transform:uppercase">
                    <b>III / CONDITIONS GENERALES</b>
                </p>
                <p style="text-decoration: underline">
                    <b>
                      Acompte initial :
                    </b>
                </p>
                <p>
                    Le preneur - cessionnaire a d’ores et déjà versé, à titre d’apport, un montant de
                    <b>{{ Outil::numberToLetter($data[0]['apportinitial']) ?? "................"}}  ({{ $data[0]['apportinitial_format'] }}) de FCFA</b>,
                    correspondant à <b>{{ $data[0]['acompte_percent'] ?? "………………………"}}</b> % du prix HT
                     de la villa au comptant dans le compte de la société <b >SERTEM PROPERTY MANAGEMENT SASU</b> ouvert dans les
                      livres de la Banque de Dakar (BDK) sous la référence  suivante :
                </p>
                <div>
                    <table class="table mb-20">
                        <tr class="tr">
                            <th class="whitespace-no-wrap td" colspan="5">
                                SERTEM PROPERTY MANAGEMENT
                            </th>

                        </tr>
                        <tr class="tr">
                            <th class="whitespace-no-wrap td" colspan="5">BDK</th>

                        </tr>
                        <tr class="tr">
                            <th class="whitespace-no-wrap td">Code banque</th>
                            <th class="whitespace-no-wrap td">Code Guichet</th>
                            <th class="whitespace-no-wrap td">N° Compte</th>
                            <th class="whitespace-no-wrap td">Clé RIB</th>
                            <th class="whitespace-no-wrap td">Code SWIFT </th>
                        </tr>



                        <tr class="tr">

                            <td class="td">SN191</td>
                            <td class="td">01002</td>
                            <td class="td">050601022002</td>
                            <td class="td">66</td>
                            <td class="td">BDKRSNDA</td>
                        </tr>

                    </table>
                </div>

                <p style="text-decoration: underline">
                    <b>
                      Versement du reliquat :
                    </b>
                </p>
                <p>
                    Quant au solde du prix, productif de frais définis tels que suit
                </p>
                <p>
                    -   Frais de dossier exigibles lors de la signature des présentes et non remboursables ; <br>
                    -   Échéances mensuelles à savoir un total de <b>{{ $data[0]['montantloyerformat'] ? $data[0]['montantloyerformat'] : "................."  }} ({{Outil::numberToLetter($data[0]['montantloyer'])}}  ) F CFA </b>, le preneur - cessionnaire s’oblige à le payer à la société venderesse suivant
                    l’échéancier indiqué dans le simulateur en annexe.

                </p>
                <p>
                    Il est expressément précisé que les échéances mensuelles pourront être payées aussi
                    par bimestre, trimestre ou par d’autres modalités convenues d’accord parties.
                </p>
                {{-- <p>
                    -	Loyer meusuel : {{ $data[0]['montantloyerformat'] ?? "....................." }}({{ $data[0]['montantloyerformat'] ?? "................." }}) FCFA dont 92,5% de foyer on redevaaee jouiasance
                    et 7,5 % de quote-part redevaince prix.
                </p> --}}
                <p style="text-align: center">
                    <b>II demeure expressément convenu entre les parties les conditions ci-après :</b>
                </p>

                <p>
                    <b>1ere)</b> Que tous autres versements auront lieu à la comptabilité de la société <b>SERTEM PROPERTY MANAGEMENT SASU </b>
                     ou par virement dans son compte ouvert dans les livres de Banque de Dakar
                    (BDK) sous la référence ci-dessus (cf page .......) .
                </p>
                {{-- <div>
                    (cf page4).
                    <table class="table mb-20">
                        <tr class="tr">
                            <th class="whitespace-no-wrap" colspan="5">SERTEM IMMO SA</th>

                        </tr>
                        <tr class="tr">
                            <th class="whitespace-no-wrap" colspan="5">Agence BDK / 7 Av. Léopold Sédar Senghor</th>

                        </tr>
                        <tr class="tr">
                            <th class="whitespace-no-wrap">Code banque</th>
                            <th class="whitespace-no-wrap">Code Guichet</th>
                            <th class="whitespace-no-wrap">N° Compte</th>
                            <th class="whitespace-no-wrap">Clé RIB</th>
                            <th class="whitespace-no-wrap">Code SWIFT </th>
                        </tr>



                        <tr class="tr">

                            <td class="td">SN191</td>
                            <td class="td">01002</td>
                            <td class="td">050600884301</td>
                            <td class="td">46</td>
                            <td class="td">BDKRSNDA</td>
                        </tr>

                    </table>
                </div> --}}
                <p>
                    Qu'ils ne pourront être valablement effectués que suivant les modes libératoires légaux.
                </p>
                <p>
                    <b>2ème)</b> Que le preneur-cessionnaire pourra se libérer par anticipation par tranches ou en
                        totalité si bon lui en semble.
                </p>
                <p>
                    Les autres versements effectués par le preneur-cessionnaire à titre de consolidation
                    d’apport modifieront <b>le montant des mensualités</b> ainsi que <b>
                        la durée de la location-vente.
                    </b>
                </p>
                <p>
                    Dans ce cas de figure, un nouvel échéancier sera défini en fonction du versement
                    effectué et ainsi annexé comme avenant au présent contrat.
                </p>
                <p style="text-decoration : underline;text-transform:uppercase">
                    <b>IV / OBLIGATIONS PARTICULIERES  </b>
                </p>
                <p>
                    Le preneur - cessionnaire fera son affaire personnelle de l’abonnement en eau et en
                    électricité. Il devra payer lui-même ses quittances d’eau et d’électricité.

                </p>

                <p>
                    Les frais de réparations, d’entretien et de déplacement, même en cas d’usure et de force
                    majeure, du matériel et des conduites d’eau et d’électricité sont à sa charge. L’entretien
                    des peintures et badigeons sont à sa charge, ainsi que les réparations locatives.
                </p>
                <p>

                </p>
                <p>
                    Jusqu’au paiement intégral des échéances contractuelles, le preneur- cessionnaire ne
                    fera aucun changement, démolition, construction, distribution, ni percement dans la villa
                    sans le consentement exprès et par écrit du bailleur - cédant et sous son contrôle. Il ne
                    fera aucune addition à la villa sans autorisation écrite du bailleur - cédant.
                </p>
                <p>
                    Toute demande de modification sera adressée par écrit au bailleur-cédant, qui statuera
                    sur son opportunité.
                </p>
                <p>
                    Cette demande devra être accompagnée d’un croquis et d’un devis descriptif détaillé.
                </p>
                <p>
                    Si des travaux modificatifs doivent être effectués, le preneur - cessionnaire sera tenu en
                    cas de cessation du présent contrat avant l’échéance contractuelle, de remettre les lieux
                    dans l’état où il les a trouvés.
                </p>
    </div>

    <p style="text-decoration : underline;text-transform:uppercase">
        <b>V / ACCEPTATION DU PRENEUR - CESSIONNAIRE
        </b>
    </p>
    <p>
        Le preneur - cessionnaire déclare par ces présentes accepter la faculté que lui confère
        le promettant-bailleur, de se porter acquéreur, par préférence à tout autre, du bien ci-
        dessus désigné et s’oblige à s’acquitter du prix de vente suivant l’échéancier ci-dessus
        indiqué et des frais et émoluments d’acte de vente.
    </p>
    <p>
        D'ores et déjà, le bénéficiaire-locataire s'oblige au strict respect du cahier des charges
        du lotissement qui est déposé au rang des minutes du Notaire et notamment aux
        obligations ci-après, qui y seront contenues, à savoir :
    </p>
    <p>
        - Souscrire aux contrats d'entretien et de gardiennage proposés par l'association
        syndicale dans l'intérêt de tous les propriétaires. <br>
        - Payer sa quote-part sur les charges syndicales d’entretien des parties communes.

    </p>
    <p>
        - Les matériaux et matériels destinés à des travaux ne devront pas être déversés ou
        stockés sur les voies, caniveaux, trottoirs, ou espaces verts, mais exclusivement dans
        l'enceinte de la villa objet des présentes.
    </p>
    <p>
        - Afin d'assurer la pérennité des voiries et réseaux divers des parties communes et
        d'éviter toute nuisance aux autres propriétaires, toute contravention au règlement
        d'urbanisme et au cahier des charges sera, après constat d'huissier et sommation restée
        infructueuse, déférée à la censure du juge des référés, tous frais et honoraires à la
        charge du contrevenant et sans préjudice de tous dommages et intérêts.
    </p>
    <p style="text-decoration : underline;text-transform:uppercase;">
        <b>VI / CARENCE DU PRENEUR - CESSIONNAIRE
        </b>
    </p>
    <p>
        Lorsque le preneur - cessionnaire ne paie pas les échéances exigibles, le bailleur
        – cédant aura le loisir soit de lui appliquer des pénalités de retard, soit de prononcer la
        résolution (annulation) pure et simple du présent contrat de location-vente.
    </p>
    <p style="text-decoration: underline">
        <b>
           a) Intérêts de retard - Indemnités :
        </b>
    </p>
    <p class="decaler-premiere-ligne">
        {{-- <bdo dir="ltr" class="decaler-paragraphe" > --}}
            Toute somme formant partie du prix
          {{-- </bdo> --}}
         qui ne serait pas payée à son exacte échéance
        serait, de plein droit et sans qu'il soit besoin d'une mise en demeure, passible d'une
        pénalité de retard fixée à <b>{{Outil::numberToLetter($data[0]['indemnite'])}} pour cent ({{$data[0]['indemnite'] ?? 10}}%)</b> du montant exigible par mois de retard.
        Tout mois entamé suivant la date d'exigibilité ouvre droit à cette pénalité en entier.
    </p>
    <p class="decaler-premiere-ligne">
      {{-- <bdo dir="ltr" class="" > --}}
        Ces dispositions s'appliqueraient,
      {{-- </bdo> --}}
      1e cas échéant, au cours des délais de paiement qui seraient judiciairement alloués au preneur - cessionnaire.
        Les sommes dues sont stipulées indivisibles.

    </p>
    <p class="decaler-premiere-ligne">
        {{-- <bdo dir="ltr" class="" > --}}
            En conséquence, en cas de décès
          {{-- </bdo> --}}
        du preneur - cessionnaire avant sa complète libération, il y aura solidarité
        entre ses héritiers et représentants pour le paiement tant de ce qui resterait alors dû,
         que des frais de la signification judiciaire.
    </p>

    <p class="decaler-premiere-ligne">
        {{-- <bdo dir="ltr" class="" > --}}
            Ils devront, dans un délai de
          {{-- </bdo> --}}
          trois (3) mois à compter du décès du preneur-
          cessionnaire, justifier de leur qualité d’héritiers ou ayants droit, sans que ledit délai ne
          les dispense du paiement du loyer. A défaut du paiement des échéances ou d’une telle
          justification dans ledit délai, la convention sera résiliée de plein droit.

         <p>
    </p>
    <p class="decaler-premiere-ligne">
        {{-- <bdo dir="ltr" class="" > --}}
            Pendant toute la durée du présent contrat,
          {{-- </bdo> --}}
        le preneur-cessionnaire pourra souscrire
        directement à son nom, une police d’assurance - vie sur sa tête et jusqu’au paiement
        intégral du prix de la villa, intérêts et frais compris, auprès de toutes compagnies
        d’assurances agréées.

    </p>
    <p class="decaler-premiere-ligne">
        {{-- <bdo dir="ltr" class=""> --}}
            Le preneur - cessionnaire pourra
          {{-- </bdo> --}}
       subroger le bailleur -cédant dans tous ses droits
        et actions contre la Compagnie d’Assurance. En pareil cas, les sommes dues par la ou
        les Compagnies d’Assurances seront versées au bailleur - cédant, sans le concours ni
        la participation et hors la présence du preneur-cessionnaire ce jusqu’à concurrence du
        prix de la villa, des intérêts, commissions, frais et accessoires.
    </p>

    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Pour l’application des dispositions
          </bdo>
        du paragraphe ci-dessus, notification des
        présentes pourra être faite à toutes les Compagnies d’Assurances intéressées par tout
        écrit (acte extrajudiciaire et aux frais du Preneur - Cessionnaire).
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Des avenants de délégation
          </bdo>
           seront établis par lesdites Compagnies au profit du
          bailleur - cédant aux frais du preneur-cessionnaire.
    </p>
    <p style="text-decoration: underline">
        <b>
           b) Résolution de plein droit faute de paiement du prix à son échéance
        </b>
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Il est expressément stipulé
          </bdo>
        qu'à défaut de paiement d'une somme quelconque
        formant partie du prix, <b>trois (03) mois</b> après son exacte échéance, le présent contrat
        sera résolu de plein droit, si le bailleur-cédant le souhaite. Dans ce cas, ce dernier devra
        notifier la rupture du contrat au preneur-cessionnaire par lettre au porteur avec accusé
        de réception ou par exploit d’huissier contenant l'intention du bailleur-cédant de se
        prévaloir de ladite clause résolutoire.
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="">
            La résolution du présent contrat
          </bdo>
        pour les causes ci-dessus énoncées, donnera lieu
        au paiement par le preneur-cessionnaire des indemnités de retard cumulées et du
        montant de l'indemnité d'immobilisation fixée à <b>{{Outil::numberToLetter($data[0]['indemnite'])}} pour cent ({{$data[0]['indemnite'] ?? 10}}%)</b> du prix de vente.
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Le preneur-cessionnaire
          </bdo>
       aura l’obligation de libérer les lieux dans un délai
        <b>d'un mois (01 mois)</b>, après réception du constat d’huissier.
        {{-- <b>d'<x-nombre-en-lettre :nombre="explode(' ',$data[0]['delaipreavi']['designation'])[0]"></x-nombre-en-lettre> mois ({{$data[0]['delaipreavi'] ? $data[0]['delaipreavi']['designation'] : "1 mois "}})</b>, après réception du constat d’huissier. --}}

    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Un état des lieux contradictoire
          </bdo>
        devra être effectué en cas de libération des lieux
        par le preneur-cessionnaire. Si le bailleur-cédant constate des dégradations après avoir
        comparé l’état des lieux d’entrée et l’état des lieux de sortie, il peut appliquer une retenue
        pour la remise en état du logement sur le montant versé par le preneur-cessionnaire.

    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Le remboursement des sommes
          </bdo>
          versées par le preneur-cessionnaire, déduction
          faite des pénalités de retard, des indemnités d'immobilisation et du montant du loyer et
          autres ne pourra intervenir que dans un délai de <b>120 jours</b> après rupture du contrat.

    </p>
    <p style="text-decoration: underline;">
        <b>
           VII / CLAUSE PENALE
        </b>
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Au cas où l’une quelconque
          </bdo>
        des parties ne respecterait pas les engagements résultant à sa
         charge en vertu des présentes ou n'exécuterait pas les obligations qui en découlent, en tout
          ou en partie,  elle serait,  de plein droit et du seul fait du non-respect ou de la non-exécution,
          passible, au profit de l'autre partie,
           de dommages-intérêts fixes forfaitairement à <b>{{Outil::numberToLetter($data[0]['clausepenale'])}} pour cent ({{$data[0]['clausepenale'] ?? ".............."}}%)</b> du prix de vente du bien.
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            En cas de défaillance du
          </bdo>
        preneur-cessionnaire, cette pénalité  {{intval($data[0]['clausepenale']) == 1 ? "d'" : 'de' }}

        <b>{{Outil::numberToLetter($data[0]['clausepenale'])}} pour cent ({{$data[0]['clausepenale'] ?? ".............."}}%)</b> s’ajoutera aux éventuelles indemnités de retard dues par ledit preneur-
cessionnaire et sera déduite du montant des acomptes versés par ce dernier.
    </p>
    <p style="text-decoration: underline;">
        <b>
           VIII / REALISATION DE VENTE
        </b>
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            La réalisation de la vente
          </bdo>
          sera constatée par l'Etude de <bdo  > {{ $data[0]['appartement']['entite']['nomcompletnotaire'] ? $data[0]['appartement']['entite']['nomcompletnotaire'] : "Thierno Mamadou KANE" }} </bdo>, Notaire
          titulaire de la Charge de Dakar <bdo  >{{ $data[0]['appartement']['entite']['adressenotaire'] ? $data[0]['appartement']['entite']['adressenotaire'] : "XXXI sise à la Zac de Mbao"}}</bdo> après paiement intégral du prix de vente.
    </p>
    <p style="text-decoration: underline;">
        <b>
            IX / FACULTE DE  SUBSTITUTION
        </b>
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Il est toutefois convenu que
          </bdo>
        la réalisation par acte authentique pourra avoir lieu soit
        au profit du preneur-cessionnaire aux présentes soit au profit de toute autre personne
        physique ou morale que ce dernier se réserve de désigner; mais dans ce cas, il restera
        solidairement obligé, avec la personne désignée, au paiement du prix et à l'exécution de
        toutes les conditions de la vente.
    </p>
    <p style="text-decoration: underline;">
        <b>
            X / CLAUSE DE RESERVE DE PROPRIETE
        </b>
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Le bailleur-cédant se réserve
          </bdo>
        la propriété du bien objet des présentes jusqu’au
        paiement intégral du prix convenu.
    </p>
    <p style="text-decoration: underline;">
        <b>
            XI / CLAUSE DE TRANSFERT DES RISQUES
        </b>
    </p>

    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Bien que la villa reste
          </bdo>
       la propriété du bailleur-cédant jusqu’au paiement intégral du
          prix convenu, la remise des clés au preneur-cessionnaire entraine transfert des risques,
          ce dernier devenant responsable de la villa avec toutes les obligations qui s’y attachent.
          Il devra souscrire à cet effet une police d’assurance.
    </p>

    <p style="text-decoration: underline;">
        <b>
            XII / LITIGES
        </b>
    </p>

    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            En cas de litiges sur l'interprétation
          </bdo>
          ou l'exécution des présentes, le juge des référés
          sera seul compétent comme en matière de location.
    </p>
    {{-- <p style="text-decoration: underline;"> --}}
        <b style="text-decoration: underline;">
            XIII / LEVEE D'OPTION
        </b>
    {{-- </p> --}}
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Le preneur-cessionnaire
          </bdo>
          qui aura satisfait à toutes ses obligations pendant la
          période du bail et qui sera à jour concernant le paiement des loyers, redevances et
          charges, pourra à l’expiration de la période de jouissance, demander que la propriété de
          l’immeuble objet du présent contrat lui soit transférée. Cette levée d’option devra
          être notifiée à <bdo >SERTEM PROPPERTY MANAGEMENT SASU</bdo> par lettre recommandée avec accusé de réception ou
          par lettre au porteur contre décharge ou par tout moyen accepté par <bdo >SERTEM PROPPERTY MANAGEMENT SASU</bdo>.

    </p>
    <p style="text-decoration: underline;">
        <b>
            XIV / CESSION DU CONTRAT A UN TIERS
        </b>
    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Lorsque le preneur–cessionnaire
          </bdo>
        pour une raison quelconque, décide de céder ses
        droits personnels qu’il tient du contrat à un tiers, il devra notifier son projet de cession à
        <bdo >SERTEM PROPPERTY MANAGEMENT SASU</bdo>.

    </p>
    <p class="decaler-premiere-ligne">
        <bdo dir="ltr" class="" >
            Cette dernière se réserve
          </bdo>
         la possibilité de donner suite audit projet en fixant les
        modalités de la cession.
    </p>

    <p style="text-decoration: underline;">
        <b>
            XV / DECES DU PRENEUR
        </b>
    </p>
    <p class="decaler-premiere-ligne">
        {{-- <bdo dir="ltr" class="" style="margin-left: 8%"> --}}
            En cas de décès du preneur-cessionnaire,
          {{-- </bdo> --}}
        il y aura solidarité et indivisibilité entre
        ses héritiers pour l’exécution du contrat jusqu’au terme dudit contrat avec la levée de
        l’option précitée.
    </p>
    <p style="text-decoration: underline;">
        <b>
            XVI / ELECTION DE DOMICILE
        </b>
    </p>
    <p style="">
        Pour l'exécution des présentes et de leurs suites, les parties font élection de domicile
        auprès de :
    </p>
    <p>
        Etude de Maître {{ $data[0]['appartement']['entite']['nomcompletnotaire'] ? $data[0]['appartement']['entite']['nomcompletnotaire'] : "Thierno Mamadou KANE"}} – Notaire <br>
        Tel. : {{$data[0]['appartement']['entite']['telephone1notaire'] ? $data[0]['appartement']['entite']['telephone1notaire'] : "+221 77 639 39 24"  }} / {{$data[0]['appartement']['entite']['telephoneetudenotaire'] ? $data[0]['appartement']['entite']['telephoneetudenotaire'] : "+221 33 871 35 00"  }} ({{$data[0]['appartement']['entite']['assistantetudenotaire'] ? $data[0]['appartement']['entite']['assistantetudenotaire'] : "Me BA Mame Younouss"}}) <br>
        Adresse : {{$data[0]['appartement']['entite']['adresseetudenotaire'] ? $data[0]['appartement']['entite']['adresseetudenotaire'] : "ZAC DE MBAO CITE SAGEF 2 DAKAR (SENEGAL)" }} <br>
        E-mail : <bdo style="text-decoration: underline">{{ $data[0]['appartement']['entite']['emailnotaire'] ? $data[0]['appartement']['entite']['emailnotaire'] : "thierno.mamadou.kane@gmail.com"}}</bdo> <br>
        <bdo style="text-decoration: underline">{{ $data[0]['appartement']['entite']['emailetudenotaire'] ? $data[0]['appartement']['entite']['emailetudenotaire'] : "notaire@etudetmk.com"}}</bdo>
    </p>
    <p>
        Si le preneur-cessionnaire le désire, il peut se faire assister par son notaire.
    </p>
    <p>
        Le bailleur-cédant s'autorise à prendre contact avec le preneur-cessionnaire, aux
        coordonnées fournies par lui-même, pour tous problèmes ou changements afférant au
        présent contrat.
    </p>

    <p style="text-decoration: underline;">
        <b>
            XVII / FRAIS DE VENTE ET HONORAIRES
        </b>
    </p>
    <p>
        Les frais et honoraires notariés consécutifs au contrat de vente et ceux qui en seraient
        la suite et la conséquence sont à la charge du preneur-cessionnaire qui devra au
        moment de la signature de l’acte de vente disposer de la somme nécessaire pour la
        perfection de la vente. Les frais d’actes sont estimés à plus ou moins 10 % du prix du
        bien.
    </p>
    <p>
     <b> Fait à Dakar {{$data[0]['datedebutcontrat_format'] ?? "..........................."}}</b>
    </p>

    <div class="" style="margin-top:5%">
        <table class="table mb-20" style="border: none;border-color:#fff">
            <tr class="tr">
                <th class="whitespace-no-wrap" colspan="3">
                    LE BAILLEUR - CEDANT <br>
                    <em style="font-size: 10px;color:#fff">ffff</em>
                </th>
                <th class="whitespace-no-wrap" colspan="3">
                    LE PRENEUR - CESSIONNAIRE <br>
                    <em style="font-size: 10px">« Lu et approuvé Bon pour accord »</em>
                </th>

            </tr>

            <tr class="tr" style="border: none;border-color:#fff">

                <td class="td" colspan="3" style="border: none;border-color:#fff">
                    @if ($data[0]['signaturedirecteur'])
                        <img src="{{asset($data[0]['signaturedirecteur'])}}" class="img-fluid " alt="dkkdkdk">

                    @endif

                </td>
                {{-- {{$data[0]['signaturedirecteur']}} --}}
                <td class="td" colspan="3" style="border: none;border-color:#fff">
                    @if ($data[0]['signatureclient'])
                    <img src="{{asset($data[0]['signatureclient'])}}" class="img-fluid " alt="dkkdkdk">

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









