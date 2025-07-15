<?php

namespace App\Http\Controllers;

use App\Avisecheance;
use App\Contrat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class SignaturePDFController extends Controller
{
    //


     /**
     * Write code on Method
     *
     * @return response()
     */
    public function upload(Request $request)
    {
        $data['image'] = $this->uploadSignature($request->signed);
         $pdf = app('dompdf.wrapper');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // $data           =   Avisecheance::find($avisecheance->id);

                $pdfFileName =  'pdfavisecheance.pdf';
                info('Observer filename : ' . $pdfFileName);
                $pdf->loadView('pdfs.signaturePDFView', ['data' => $data]);
        // $pdf = Pdf::loadView('signaturePDFView', $data);
        return $pdf->download('signature.pdf');
    }


    public function uploadSignature($signed)
    {
        $folderPath = public_path('uploads/echeances/');

        $image_parts = explode(";base64,", $signed);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);

        $file = $folderPath . uniqid() . '.'.$image_type;

        file_put_contents($file, $image_base64);

        return $file;
    }

    public function index()
    {
        return view('signaturePDF');
    }
    public function notFound()
    {
        return view('signatureNotFound');
    }

    public function successPage()
    {
        return view('successPage');
    }

    public function signatureAvis(Request $request) {


        $avis = isset($request) && isset($request->id) ? Avisecheance::find($request->id) : null;
        // dd("je usis ici" , $avis);
        return view('signatureAvis',['avis' =>  $avis]);
    }

    public function viewAnnexeMergedPDF(Request $request) {


        $contrat = Contrat::find($request->id);
        if (isset($contrat) && isset($contrat->id) && isset($contrat->annexes)) {
            $annexes = $contrat->annexes;
            $pdfMerge = PDFMerger::init();
            foreach($annexes as $annex)  {
                $pdfMerge->addPDF(public_path($annex['filepath']));
            }

            // Save the merged PDF temporarily
            $outputPath = public_path("uploads/annexes/mergedannexe.pdf");
            $pdfMerge->merge();
            $pdfMerge->save($outputPath);

            // Return the merged PDF for viewing
            return Response::make(file_get_contents($outputPath), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="mergedannexe.pdf"',
            ]);
        }

        return response()->json(["data" => null, "errors" => "Aucun contrat trouvé!"]);

    }
}
