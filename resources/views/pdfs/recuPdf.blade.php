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
    <title>Reçu de paiement {{date('d/m/Y')}} </title>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.1/css/bootstrap.css">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>



</head>
<style>
     body {
            background-color: #FCFCFC;

        }
        .card {
            background-color: #FCFCFC;
        }

       p{
        color: #0C478B;
       }
       span {
        color: #0C478B;
       }
       .blue-color {
        color: #0C478B;
       }
</style>
{{-- {{dd($data->contrat->appartement)}} --}}
<header style="width:100%;">
    <div class="header" style="">
        <img style="width: 20%" src="assets/images/pm.png" alt="1">
    </div>
</header>

<div class="container">

    <div class="row">
        <div class="col-md-12 mt-5 mb-5">
            <div class="">
                <div class="">
                    {{-- <h5>Signature d'avis d'échéance </h5> --}}
                </div>
                <div class="card-body">

                 <div class=" d-flex justify-content-between">

                    <div style="font-size:12px" class="d-flex  flex-column mb-3 fw-bold">
                     <img width="200" src="{{asset('assets/images/pm.png')}}" alt="1">
                     <span>Route de la corniche Ouest en face de l'ARTP</span>
                     <span>www.sertemgroupe.com</span>
                     <span>(+221) 33 869 77 67</span>
                    </div>


                     <p> N : .............../24 ............ _ ..............</p>

               </div>


                             <div class="text-center mb-4">
                                 <span class="fw-bold fs-4 blue-color text-uppercase">Reçu Location vente</span>
                                 <br>
                                 <span class=" text-danger mr-5">
                                     N 000049
                                 </span>
                             </div>

                         <p class="lh-lg">Je soussigné Mme.M ................................................................... commercial de de SERTEM IMMO,
                              certifie avoir reçu par : .....................................................................
                              .................................... ce ......... /......./20......... la somme de F CFA ..................................
                              ............................................... #.................................... de la part de Mme/M. : ............
                              ............... au titre de loyer N° ........... de ............. .pour sa réservation à la location-vente d'une villa DALIA (N° Lot............
                              ilot n°........... .) sur notre programme RIDWAN à MBAO Villeneuve.
                             <br/><br/>
                             Fait à Dakar, le................./20.......
                            

                         </p>

                         {{-- <div class="col-md-12">
                             <strong>Signature:</strong>
                             <br/>
                             <div id="sig" ></div>
                             <br/>
                             <button id="clear" class="btn btn-danger btn-sm mt-1">Clear Signature</button>
                             <textarea id="signature64" name="signed" style="display: none"></textarea>
                         </div>
                         <br/>
                         <div class="col-md-12 text-center">
                             <button class="btn btn-success">Submit & Download PDF</button>
                         </div> --}}

                </div>
            </div>
        </div>
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









