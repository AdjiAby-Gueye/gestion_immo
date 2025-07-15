<?php

namespace App\Http\Controllers;

use App\Entite;
use App\Exports\EtatencaissementExport;
use App\Fraisupplementaire;
use App\Infobancaire;
use PDF;
use App\Outil;
use App\Etatlieu;
use App\Immeuble;
use App\Locataire;
use App\Typepiece;
use App\Appartement;
use App\Prestataire;
use App\Typecontrat;
use App\Typefacture;
use App\Avisecheance;
use App\Contrat;
use App\Intervention;
use App\Proprietaire;
use App\Typedocument;
use App\Paiementloyer;
use App\Typeassurance;
use App\Typelocataire;
use Complex\Functions;
use App\Typeappartement;
use App\Typeintervention;
use App\Exports\RoleExport;
use App\Exports\UserExport;
use App\Typerenouvellement;
use App\Exports\ExcelExport;
use App\Exports\AnnoncesExport;
use App\Exports\ContratsExport;
use App\Exports\MessagesExport;
use koolreport\core\DataSource;



use App\Exports\ImmeublesExport;
use App\Exports\LocatairesExport;
use App\Exports\PermissionExport;
use App\Exports\TypepiecesExport;
use App\Exports\AppartementsExport;
use App\Exports\PrestatairesExport;
use App\Exports\TypecontratsExport;
use App\Exports\TypefacturesExport;
use App\Exports\ProprietairesExport;
use App\Exports\TypedocumentsExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocationventesExport;
use App\Exports\paiementloyersExport;
use App\Exports\TypeassurancesExport;
use App\Exports\TypelocatairesExport;
use App\Typeobligationadministrative;
use App\Exports\TypeappartementsExport;
use App\Exports\TypeinterventionsExport;
use App\Exports\ContratprestationsExport;
use App\Exports\TyperenouvellementsExport;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Exports\TypeobligationadministrativesExport;
use App\Facture;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class PdfExcelController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // DEBUT EXPORT EXCEL ET PDF TYPE APPARTEMENT

    public function generate_excel_typeappartement($filters = null)
    {
        $data  = self::outil_typeappartement($filters, "typeappartement");

        return Excel::download(new TypeappartementsExport($data), 'typeappartement.xlsx');
    }


    public function outil_typeappartement($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_typeappartement($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_typeappartement($filters, "typeappartement");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typeappartement', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE APPARTEMENT
    // DEBUT EXPORT PDF APPEL CAUTION
    public function generate_pdf_one_appel_caution($id)
    {

        $user = Auth::user();
        $data  = self::outil_contrat("id:" . $id, "contrats2");
        //  dd($data);
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($data, 'pdfoneappelcaution', $customPaper);
    }
    // FIN EXPORT PDF APPEL CAUTION

    // DEBUT EXPORT PDF APPEL LOYER
    public function generate_pdf_one_appel_loyer($id)
    {

        $user = Auth::user();
        $data  = self::outil_contrat("id:" . $id, "contrats2");
        //  dd($data);
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($data, 'pdfoneappelloyer', $customPaper);
    }
    // FIN EXPORT PDF APPEL LOYER

    public function generate_pdf_one_appel_echeance($id)
    {

        $user = Auth::user();
        $data  = self::outil_facture("id:" . $id, "facturelocations");
        //    dd($data);
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($data, 'pdfoneappelecheance', $customPaper);
    }

    public function generate_pdf_one_avis_echeance($id)
    {

        $user = Auth::user();
        // $data  = self::outil_facture("id:" . $id, "avisecheances");
        //    dd($data);
        $data               = Avisecheance::find($id);
        // dd($data);
        $periode            = null;
        $periodeAvis        = null;
        //        if(isset($data->periodes)){
        //            $periodes = explode(',',$data->periodes);
        //            if(isset($periodes) && count($periodes) == 1){
        //                $periode        = Outil::getMonthNumber($data->periodes);
        //                if($periode){
        //                    $dateecheances  = explode('-',$data->date_echeance);
        //                    if(isset($dateecheances) && count($dateecheances) > 0){
        //                        $periodeAvis      = '01-' . $periode . "-" . $dateecheances[2];
        //                    }
        //                }
        //
        //            }else{
        //
        //            }
        //
        //        }


        $frais         = Fraisupplementaire::where('avisecheance_id', $id)->get();
        $entite = Entite::where("code", "RID")->first();
        $infosbancaire = null;

        if ($entite && $entite->code && isset($data->date_echeance)) {

            $infosbancaire = Infobancaire::where('entite_id', $entite->id)
                ->whereDate('datedebut', '<=', $data->date_echeance)
                ->whereDate('datefin', '>=', $data->date_echeance)
                ->first();
        }
        // dd($data , $infosbancaire);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
            'frais'                         => $frais,
            'infosbancaire'                 => $infosbancaire
        );
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfavisecheance', $customPaper);
    }

    // DEBUT EXPORT PDF  Paiement loyer


    /// FIN EXPORT PDF Paiement loyer
    // DEBUT EXPORT PDF EXCEL FCATURE


    public function generate_pdf_one_paiementloyer($id)
    {

        $user = Auth::user();
        //    self::outil_paiementloyer("id:".$id, "paiementloyers");
        $data  = Outil::getAllItemsWithGraphQl("paiementloyers", " id:" . $id,);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        // dd($retour);
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonepaiementloyer', $customPaper);
    }
    /// FIN EXPORT PDF Paiement loyer


    //  debut devis pdf
    public function generate_pdf_one_devi($id, $type = null)
    {
        //dd($type);
        $user = Auth::user();
        $allData = [];
        $requestQrph = ($type === "etatlieu") ? "etatlieu_id:"  : "demandeintervention_id:";
        $data  = Outil::getAllItemsWithGraphQl("devis", $requestQrph . $id,);
        $data2  = Outil::getAllItemsWithGraphQl("detaildevis", "devi_id:" . $data[0]["id"],);
        foreach ($data2 as $key => $value) {
            $data3  = Outil::getAllItemsWithGraphQl("detaildevisdetails", "detaildevi_id:" . $value["id"],);
            $allData[$key]["detaildevisdetails"] = $data3;
        }
        $retour = array(
            'item'                          => $data,
            'data'                          => $allData,

        );
        // dd($retour,$type);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfdevis', $customPaper);
    }

    // fin devi pdf

    public function generate_pdf_one_factureintervention($id)
    {

        $data  = Outil::getAllItemsWithGraphQl("factureinterventions", " id:" . $id,);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        // dd($retour['data']);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonefactureintervention', $customPaper);
    }

    public function generate_pdf_factureeaux($id)
    {
        //dd($id);
        $data  = Outil::getAllItemsWithGraphQl("factureeauxs", " id:" . $id,);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        // dd($retour['data'][0]);
        // dd($retour);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonefactureeaux', $customPaper);
    }

    // generate_pdf_loyer_bbi

    public function generate_pdf_loyer_bbi($id)
    {
        //dd($id);
        $data  = self::outil_facture("id:" . $id, "facturelocations");
        //dd($data);
        $retour = array();
        $retour = array(
            'item'                          => '',
            'data'                          => $data['data'],
        );

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfoneloyerbbi', $customPaper);
    }

    // generate_pdf_quitance_bbi

    public function generate_pdf_quitance_bbi($id)
    {
        //  dd($id);
        // $data  = Outil::getAllItemsWithGraphQl("paiementloyers", " id:" . $id,);
        $data  = self::outil_facture("id:" . $id, "facturelocations");
        //dd($data);
        $retour = array();
        $retour = array(
            'item'                          => '',
            'data'                          => $data['data'],
        );

        // dd($retour['data'][0]);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonequitancebbi', $customPaper);
    }

    //generate_pdf_commissionentredeuxdate

    public function generate_pdf_commissionentredeuxdate($filters = null)
    {
        // datedeb
        $text = explode(',', ltrim($filters, ','));
        $datedeb = explode(':', $text[0])[1];
        $datefin = explode(':', $text[1])[1];
        // dd($datedeb, $datefin);


        $data  = Outil::getAllItemsWithGraphQl("facturelocations", $filters);
       // dd($data);
        $retour = array();

        $retour = array(
            'item'                          => '',
            'datedeb'                       => $datedeb ?? '',
            'datefin'                       => $datefin ?? '',
            'data'                          => $data,
        );
       // dd($retour);
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfcommissionentredeuxdate', $customPaper);
    }

    // // generate_pdf_encaissement

    // public function generate_pdf_encaissement($ids)
    // {
    //     dd($ids);
    //     // $ids = explode(',', $ids);
    //     // $factureResult = [];

    //     // foreach ($ids as $id) {
    //     //     $data = Outil::getAllItemsWithGraphQl("facturelocations", "id:" . $id);

    //     //     if (!empty($data)) {
    //     //         $factureResult[] = $data;
    //     //     }
    //     // }
    //     // // dd($factureResult);

    //     // $customPaper = array(0, 0, 700, 700);
    //     // return self::pdfnumberpage(['factureResult' => $factureResult], 'pdfencaissement', $customPaper);
    // }



    public function generate_pdf_situasimplecompte($filters = null)
    {
        //dd($filters);
        // $data  = self::outil_Proprietaire($filters, "proprietaire");

        $data  = Outil::getAllItemsWithGraphQl("proprietaires", $filters);
        //dd($data);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        // if (!empty($data['data'])) {
        //     foreach ($data['data'] as &$proprietaire) {
        //         $proprietaireId = $proprietaire['id'];
        //         $appartementIds = Appartement::where('proprietaire_id', $proprietaireId)
        //             ->pluck('id');
        //         $sommeDesLoyers = Contrat::whereIn('appartement_id', $appartementIds)
        //             ->sum(DB::raw('montantloyer::decimal'));
        //         $sommeDesDepenses = Facture::where('proprietaire_id', $proprietaireId)
        //             ->sum(DB::raw('montant::decimal'));
        //         $proprietaire['loyer_total'] = number_format($sommeDesLoyers, 0, ' ', ' ');
        //         $proprietaire['depense_total'] = number_format($sommeDesDepenses, 0, ' ', ' ');
        //     }
        // }
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfsituasimplecompte', $customPaper);
    }

    public function generate_pdf_situacompteprop($filters = null)
    {
        // datedeb
        $text = explode(',', ltrim($filters, ','));

        //$dates = implode(',', array_slice($text, -1));
        $datedeb = explode(':', $text[0])[1];
        $datefin = explode(':', $text[1])[1];


        $data  = Outil::getAllItemsWithGraphQl("appartements", $filters);
        $factureinterventions  = Outil::getAllItemsWithGraphQl("factureinterventions", $filters);
        // dd($factureinterventions);
        $retour = array();

        $retour = array(
            'item'                          => '',
            'datedeb'                       => $datedeb ?? '',
            'datefin'                       => $datefin ?? '',
            'data'                          => $data,
            'factureintervention'           => $factureinterventions,
        );
        //dd($retour);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfsituacomptprop', $customPaper);
    }

    public function generate_pdf_tablearrieres($filters = null)
    {

        // datedeb
        $text = explode(',', ltrim($filters, ','));
        $datedeb = explode(':', $text[0])[1];
        $datefin = explode(':', $text[1])[1];
        // dd($datedeb, $datefin);


        $data  = Outil::getAllItemsWithGraphQl("facturelocations", $filters);
        $retour = array();

        $retour = array(
            'item'                          => '',
            'datedeb'                       => $datedeb ?? '',
            'datefin'                       => $datefin ?? '',
            'data'                          => $data,
        );


        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdftablearriere', $customPaper);
    }


    public function generate_pdf_balanceclients($filters = null)
    {
        // datedeb
        $text = explode(',', ltrim($filters, ','));
       // dd($text);
        $datedeb = explode(':', $text[1])[1];
        $datefin = explode(':', $text[2])[1];
        // dd($datedeb, $datefin);


        $data  = Outil::getAllItemsWithGraphQl("facturelocations", $filters);
        $retour = array();

        $retour = array(
            'item'                          => '',
            'datedeb'                       => $datedeb ?? '',
            'datefin'                       => $datefin ?? '',
            'data'                          => $data,
        );

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfbalanceclients', $customPaper);
    }

    public function generate_pdf_tlv($filters = null)
    {
        // dd($filters);

         // datedeb
         $text = explode(',', ltrim($filters, ','));
         $datedeb = explode(':', $text[1])[1];
         $datefin = explode(':', $text[2])[1];

        $retour          =  Outil::getAllItemsWithGraphQl('facturelocations', $filters);
        $data = array(
            'item'                          => '',
            'datedeb'                       => $datedeb ?? '',
            'datefin'                       => $datefin ?? '',
            'data'                          => $retour,
        );
    //    dd($data);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($data, 'pdftlv', $customPaper);
    }

    public function generate_pdf_ter($filters = null)
    {
        // $data  = self::outil_commissionentredeuxdate($filters, "commissionentredeuxdates");
        $retour = array();

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfter', $customPaper);
    }
    public function generate_pdf_tva($filters = null)
    {
        // datedeb
        $text = explode(',', ltrim($filters, ','));
        $datedeb = explode(':', $text[1])[1];
        $datefin = explode(':', $text[2])[1];
        // dd($datedeb, $datefin);

        // dd($filters);
        $data  = Outil::getAllItemsWithGraphQl("facturelocations", $filters);
        $retour = array();

        $retour = array(
            'item'                          => '',
            'datedeb'                       => $datedeb ?? '',
            'datefin'                       => $datefin ?? '',
            'data'                          => $data,
        );
        //dd($retour);
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdftva', $customPaper);
    }


    public function generate_pdf_situationdepotgarentie($id)
    {

        $data  = Outil::getAllItemsWithGraphQl("etatlieus", " id:" . $id,);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        //dd($retour['data'][0]);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonesituationdepotgarentie', $customPaper);
    }

    public function generate_pdf_bordereauremisecheque($id)
    {

        $data  = Outil::getAllItemsWithGraphQl("etatlieus", " id:" . $id,);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        //  dd($retour['data'][0]);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonebordereauremisecheque', $customPaper);
    }

    public function generate_pdf_piecejoint($id)
    {
        $data  = Outil::getAllItemsWithGraphQl("etatlieus", " id:" . $id,);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        //dd($retour['data'][0]);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonesituationglobal', $customPaper);
    }


    public function generate_pdf_one_demanderesiliation($id)
    {

        $data  = Outil::getAllItemsWithGraphQl("demanderesiliations", " id:" . $id,);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        // dd($retour['data'][0]);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonedemanderesiliation', $customPaper);
    }



    // DEBUT EXPORT PDF  facture location

    public function generate_pdf_one_facturelocation($id)
    {

        $user = Auth::user();
        //    self::outil_paiementloyer("id:".$id, "paiementloyers");
        $data  = Outil::getAllItemsWithGraphQl("facturelocations", " id:" . $id);

        // dd($data);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        // dd($retour);


        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfonefacturelocation', $customPaper);
    }

    public function generate_pdf_one_factureacompte($id)
    {

        $user = Auth::user();
        //    self::outil_paiementloyer("id:".$id, "paiementloyers");
        $data  = Outil::getAllItemsWithGraphQl("factureacomptes", " id:" . $id,);
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        // dd($retour);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($retour, 'pdfacompte', $customPaper);
    }
    /// FIN EXPORT PDF facture location


    public function generate_pdf_one_recupaiement_ridwan($id)
    {

        $user = Auth::user();
        //    self::outil_paiementloyer("id:".$id, "paiementloyers");
        // $data  = Outil::getAllItemsWithGraphQl("factureacomptes", " id:" . $id,);
        // $retour = array(
        //     'item'                          => '',
        //     'data'                          => $data,
        // );
        // dd($retour);

        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage([], 'recuPdf', $customPaper);
    }


    // DEBUT EXPORT PDF EXCEL FCATURE

    public function outil_facture($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithGraphQl($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_one_facture($id)
    {

        $user = Auth::user();

        $data  = self::outil_facture("id:" . $id, "factures");
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($data, 'factureonepdf', $customPaper);
    }
    /// FIN EXPORT PDF FACTURE
    // DEBUT EXPORT EXCEL PDF CONTRAT
    public function generate_excel_locationvente($filters = null)
    {
        $data  = self::outil_locationvente($filters, "locationventes");

        // dd($data);
        return Excel::download(new LocationventesExport($data), 'locationvente.xlsx');
    }

    public function generate_excel_etatencaissement($filters = null)
    {
        $data           =  Outil::getAllItemsWithGraphQl("etatencaissements", $filters);
        //dd($data);

        return Excel::download(new EtatencaissementExport($data), 'etatencaissement.xlsx');
    }
    public function generate_excel_contrat($filters = null)
    {
        $data  = self::outil_contrat($filters, "contrat");

        return Excel::download(new ContratsExport($data), 'contrat.xlsx');
    }
    public function outil_locationvente($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithGraphQl($type, $filters);
        $totalApportInitial = $totalApportPonctuel = 0;
        foreach ($data as $value) {
            $totalApportInitial += intval($value['apportinitial']);
            $totalApportPonctuel += intval($value['apportiponctuel']);
        }

        $totalApportInitial = number_format($totalApportInitial, 0, ' ', ' ');
        $totalApportPonctuel = number_format($totalApportPonctuel, 0, ' ', ' ');
        $retour = array(
            'item'                          => '',
            'data'                          => $data,
            'total_apport_initial'          => $totalApportInitial,
            'total_apport_ponctuel'         => $totalApportPonctuel
        );
        return $retour;
    }
    public function outil_contrat($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithGraphQl($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_contrat($filters = null)
    {

        $user = Auth::user();

        $data  = self::outil_contrat($filters, "contrats");
        $customPaper = array(0, 0, 700, 900);
        // dd($data);
        return self::pdfnumberpage($data, 'contrat', $customPaper);
    }
    public function generate_pdf_contratById($id)
    {

        $user = Auth::user();

        // $data  = Outil::getOneItemWithGraphQl("contrats" , $id);
        $data  = self::outil_contrat("id:" . $id, "contrats2");
        $customPaper = array(0, 0, 700, 900);
        // dd($data);
        // return self::pdfnumberpage($data, 'contratone', $customPaper);
        return self::pdfnumberpage($data, 'contrattext', $customPaper);
    }


    // page de signature externe
    public function signature_page($id)
    {
        return redirect("https://signature.erp.h-tsoft.com/?data=" . $id);
    }


    public function generate_pdf_contratLocationVenteById($id)
    {

        $user = Auth::user();

        // $data  = Outil::getOneItemWithGraphQl("contrats" , $id);
        $data  = self::outil_contrat("id:" . $id, "locationventes");
        $data['niveauappartements'] = Outil::getAllItemsWithGraphQl("niveauappartements", null);
        $customPaper = array(0, 0, 700, 900);
        // dd($data);
        return self::pdfnumberpage($data, 'contratlocationvente', $customPaper);
    }


    // FIN EXPORT EXCEL PDF CONTRAT

    // DEBUT EXPORT EXCEL PDF CONTRATPRESTATION
    public function generate_excel_contratprestation($filters = null)
    {
        $data  = self::outil_contratprestation($filters, "contratprestation");

        return Excel::download(new ContratprestationsExport($data), 'contratprestation.xlsx');
    }
    public function outil_contratprestation($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_contratprestation($filters = null)
    {

        $user = Auth::user();

        $data  = self::outil_contratprestation($filters, "contratprestation");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'contratprestation', $customPaper);
    }
    // FIN EXPORT EXCEL PDF CONTRATPRESTATION

    // DEBUT EXPORT EXCEL PDF ANNONCE
    public function generate_excel_annonce($filters = null)
    {
        $data  = self::outil_annonce($filters, "annonce");

        return Excel::download(new AnnoncesExport($data), 'annonce.xlsx');
    }
    public function outil_annonce($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_annonce($filters = null)
    {

        $user = Auth::user();

        $data  = self::outil_contratprestation($filters, "annonce");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'annonce', $customPaper);
    }
    // FIN EXPORT EXCEL PDF ANNONCE

    // DEBUT EXPORT EXCEL PDF MESSAGE
    public function generate_excel_message($filters = null)
    {
        $data  = self::outil_message($filters, "message");

        return Excel::download(new MessagesExport($data), 'message.xlsx');
    }
    public function outil_message($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_message($filters = null)
    {

        $user = Auth::user();

        $data  = self::outil_message($filters, "message");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'message', $customPaper);
    }
    // FIN EXPORT EXCEL PDF MESSAGE

    // DEBUT EXPORT EXCEL ET PDF TYPE ASSURANCE

    public function generate_excel_typeassurance($filters = null)
    {
        $data  = self::outil_typeassurance($filters, "typeassurance");

        return Excel::download(new TypeassurancesExport($data), 'typeassurance.xlsx');
    }

    public function outil_typeassurance($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }

    public function generate_pdf_typeassurance($filters = null)
    {
        $user = Auth::user();
        $data  = self::outil_typeassurance($filters, "typeassurance");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typeassurance', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE ASSURANCE

    // DEBUT EXPORT EXCEL ET PDF TYPE CONTRAT

    public function generate_excel_typecontrat($filters = null)
    {
        $data  = self::outil_typecontrat($filters, "typecontrat");

        return Excel::download(new TypecontratsExport($data), 'typecontrat.xlsx');
    }
    public function outil_typecontrat($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );

        return $retour;
    }
    public function generate_pdf_typecontrat($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_typecontrat($filters, "typecontrat");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typecontrat', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE CONTRAT

    // DEBUT EXPORT EXCEL ET PDF TYPE DOCUMENT

    public function generate_excel_typedocument($filters = null)
    {
        $data  = self::outil_typedocument($filters, "typedocument");

        return Excel::download(new TypedocumentsExport($data), 'typedocument.xlsx');
    }
    public function outil_typedocument($filters = null, $type = null)
    {
        $user = Auth::user();

        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_typedocument($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_typedocument($filters, "typedocument");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typedocument', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE DOCUMENT


    // DEBUT EXPORT EXCEL ET PDF TYPE FACTURE

    public function generate_excel_typefacture($filters = null)
    {
        $data  = self::outil_typefacture($filters, "typefacture");

        return Excel::download(new TypefacturesExport($data), 'typefacture.xlsx');
    }
    public function outil_typefacture($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_typefacture($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_typefacture($filters, "typefacture");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typefacture', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE FACTURE


    // DEBUT EXPORT EXCEL ET PDF TYPE INTERVENTION

    public function generate_excel_typeintervention($filters = null)
    {
        $data  = self::outil_typeintervention($filters, "typeintervention");

        return Excel::download(new TypeinterventionsExport($data), 'typeintervention.xlsx');
    }
    public function outil_typeintervention($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_typeintervention($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_typeintervention($filters, "typeintervention");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typeintervention', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE INTERVENTION

    // DEBUT EXPORT EXCEL ET PDF TYPE Typelocataire

    public function generate_excel_Typelocataire($filters = null)
    {
        $data  = self::outil_Typelocataire($filters, "typelocataire");

        return Excel::download(new TypelocatairesExport($data), 'Typelocataire.xlsx');
    }
    public function outil_Typelocataire($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_Typelocataire($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Typelocataire($filters, "typelocataire");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typelocataire', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE Typelocataire



    // DEBUT EXPORT EXCEL ET PDF TYPE OBLIGATION ADMINISTRATIVE

    public function generate_excel_Typeobligationadministrative($filters = null)
    {
        $data  = self::outil_Typeobligationadministrative($filters, "typeobligationadministrative");

        return Excel::download(new TypeobligationadministrativesExport($data), 'Typeobligationadministrative.xlsx');
    }
    public function outil_Typeobligationadministrative($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_Typeobligationadministrative($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Typeobligationadministrative($filters, "typeobligationadministrative");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typeobligationadministrative', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE OBLIGATION ADMINISTRATIVE


    // DEBUT EXPORT EXCEL ET PDF TYPE PIECE

    public function generate_excel_Typepiece($filters = null)
    {
        $data  = self::outil_Typepiece($filters, "typepiece");

        return Excel::download(new TypepiecesExport($data), 'Typepiece.xlsx');
    }
    public function outil_Typepiece($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_Typepiece($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Typepiece($filters, "typepiece");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'typepiece', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE PIECE




    // DEBUT EXPORT EXCEL ET PDF TYPE RENOUVELLEMENT

    public function generate_excel_Typerenouvellement($filters = null)
    {
        $data  = self::outil_Typerenouvellement($filters, "typerenouvellement");

        return Excel::download(new TyperenouvellementsExport($data), 'Typerenouvellement.xlsx');
    }
    public function outil_Typerenouvellement($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_Typerenouvellement($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Typerenouvellement($filters, "typerenouvellement");
        $customPaper = array(0, 0, 700, 900);
        return self::pdfnumberpage($data, 'typerenouvellement', $customPaper);
    }

    // FIN EXPORT EXCEL ET PDF TYPE RENOUVELLEMENT


    // DEBUT EXPORT EXCEL ET PDF TYPE IMMEUBLE

    public function generate_excel_Immeuble($filters = null)
    {
        $data  = self::outil_Immeuble($filters, "immeuble");

        return Excel::download(new ImmeublesExport($data), 'Immeuble.xlsx');
    }
    public function outil_Immeuble($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);
        // $data           = Immeuble::all();

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        //    dd($retour) ;
        return $retour;
    }
    public function generate_pdf_Immeuble($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Immeuble($filters, "immeuble");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'immeuble', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE IMMEUBLE


    // DEBUT EXPORT EXCEL ET PDF TYPE IMMEUBLE

    public function generate_excel_Appartement($filters = null)
    {
        // dd($filters);
        $data  = Outil::getAllItemsWithGraphQl("appartements", $filters);
        // dd($data);
        return Excel::download(new AppartementsExport($data), 'Appartement.xlsx');
    }
    public function outil_Appartement($filters = null, $type = null)
    {
        $user = Auth::user();
        //  $data           = Appartement::all();

        $query        =  Outil::getAllItemsWithModel($type, $filters);
        $retour = array(
            'item'                          => '',
            'data'                          => $query,
        );
        return $retour;
    }
    public function generate_pdf_Appartement($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Appartement($filters, 'appartement');
        $customPaper = array(0, 0, 700, 900);
        // dd($data);

        return self::pdfnumberpage($data, 'appartement', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE APPARTEMENT


    // DEBUT EXPORT EXCEL ET PDF TYPE etatloyer

    public function generate_excel_Etatloyer($filters = null)
    {
        $data  = self::outil_Appartement($filters, "appartement");

        return Excel::download(new AppartementsExport($data), 'Appartement.xlsx');
    }
    public function outil_Etatloyer($filters = null, $type = null)
    {
        $user = Auth::user();
        //  $data           = Appartement::all();

        $query        =  Outil::getAllItemsWithEtat($type, $filters);
        $retour = array(
            'item'                          => '',
            'data'                          => $query,
        );
        return $retour;
    }
    public function generate_pdf_Etatloyer($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Etatloyer($filters, 'etatloyer');
        dd($data);
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'appartement', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE etatloyer



    // DEBUT EXPORT EXCEL ET PROPRIETAIRE

    public function generate_excel_Proprietaire($filters = null)
    {
        $data  = self::outil_Proprietaire($filters, "proprietaire");

        return Excel::download(new ProprietairesExport($data), 'Proprietaire.xlsx');
    }
    public function outil_Proprietaire($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_Proprietaire($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Proprietaire($filters, "proprietaire");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'proprietaire', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE Proprietaire


    // DEBUT EXPORT EXCEL ET PRESTATAIRE

    public function generate_excel_Prestataire($filters = null)
    {
        $data  = self::outil_Prestataire($filters, "prestataire");

        return Excel::download(new PrestatairesExport($data), 'Prestataire.xlsx');
    }
    public function outil_Prestataire($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_Prestataire($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Prestataire($filters, "prestataire");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'prestataire', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE Prestataire


    // DEBUT EXPORT EXCEL ET LOCATAIRE

    public function generate_excel_Locataire($filters = null)
    {
        $data  = self::outil_Locataire($filters, "locataire");

        return Excel::download(new LocatairesExport($data), 'Locataire.xlsx');
    }
    public function outil_Locataire($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_Locataire($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Locataire($filters, "locataire");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'locataire', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE LOCATAIRE

    // * DEBUT EXPORT EXCEL ET PDF ETAT LIEUX

    public function outil_Etatlieu($filters = null, $type = null)
    {
        $user = Auth::user();

        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function outil_Etatlieu_rapport($filters = null, $type = null)
    {
        $user = Auth::user();

        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $detailconstituant = Outil::getAllItemsWithModel("detailconstituant");
        $constituantpieces = Outil::getAllItemsWithModel("constituantpiece");
        $detailequipement = Outil::getAllItemsWithModel("detailequipement");
        $equipements = Outil::getAllItemsWithModel("equipementpiece");


        $retour = array(
            'item'                          => '',
            'data'                          => $data,
            'detailconstituant'                          => $detailconstituant,
            'constituantpieces'                          => $constituantpieces,
            'detailequipement'                          => $detailequipement,
            'equipements'                          => $equipements,

        );
        // dd($data[0]->etatlieu_pieces);

        return $retour;
    }

    public function generate_pdf_rapport_etatlieu($id)
    {
        $user = Auth::user();
        // $query = Etatlieu::query();
        // $query = $query->where("id" , $id);
        // $data  = $query->get();
        $data  = self::outil_Etatlieu_rapport(",id:" . $id, "etatlieu");
        $customPaper = array(0, 0, 700, 900);
        //  dd($data);
        return self::pdfnumberpage($data, 'rapport-etatlieu', $customPaper);
    }

    // * FIN EXPORT EXCEL ET PDF ETAT LIEUX

    // DEBUT EXPORT EXCEL PDF INTERVATION
    public function generate_excel_Intervation($filters = null)
    {
        $data  = self::outil_Intervation($filters, "intervention");

        return Excel::download(new PaiementloyersExport($data), 'Paiementloyer.xlsx');
    }
    public function outil_Intervation($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);


        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    // FIN  EXPORT EXCEL PDF INTERVATION


    // DEBUT EXPORT EXCEL ET PAIEMENT LOYER

    public function generate_excel_Paiementloyer($filters = null)
    {
        $data  = self::outil_Paiementloyer($filters, "paiement");

        return Excel::download(new PaiementloyersExport($data), 'Paiementloyer.xlsx');
    }
    public function outil_Paiementloyer($filters = null, $type = null)
    {
        $user = Auth::user();
        $data           =  Outil::getAllItemsWithModel($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }
    public function generate_pdf_Paiementloyer($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_Paiementloyer($filters, "paiement");
        $customPaper = array(0, 0, 700, 900);

        return self::pdfnumberpage($data, 'paiementloyer', $customPaper);
    }
    // FIN EXPORT EXCEL ET PDF TYPE PAIEMENT LOYER

    public function generate_pdf_fichetechnique($id)
    {

        $item             = Outil::getOneItemWithGraphQl("produits", $id);

        $filtre_detail    = "produit_id:$id";

        $queryName_detail = "fichetechniques";

        $details          = Outil::getOneItemWithFilterGraphQl($queryName_detail, $filtre_detail);

        $cout_total       = 0;
        if (isset($details) && count($details) > 0) {
            foreach ($details as $key => $value) {
                //dd($value);
                $cout_total  +=  $value['portion_unitaire'] * $value['pru'];
            }

            // array_push($itemstest, array("designation" => "Traiteur", "nombre_portion" => 4));
            $data = array('item' => $item, 'details' => $details, 'cout_total'  => $cout_total);
            //	dd($data);

            return self::pdfnumberpage($data, 'fichetechnique');
            //    $pdf = \PDF::loadView('pdfs.fichetechnique', $data);
            //
            //            return $pdf->stream();
        }
    }

    public function generate_pdf_planing($id)
    {
        //get datas by GraphQL
        $item = Outil::getOneItemWithGraphQl("planings", $id);
        $jours = Outil::getOneItemWithFilterGraphQl("jours", "");

        $details = json_decode($item["tableau"], true);

        $data = array('item' => $item, 'details' => $details, 'jours' => $jours);
        //dd($data);

        // Send data to the view using loadView function of PDF facade
        //        $pdf = \PDF::loadView('pdfs.planing', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'planing');
    }

    public function generate_pdf_facture_interne($filters)
    {
        return self::generate_pdf_facture($filters, true);
    }

    public function outil_factureold($filters = null, $interne = false, $type = null)
    {
        $data           = null;
        $ca             = 0;
        $ca_ht             = 0;
        $ca_tva             = 0;
        $modepaiements  = Modepaiement::query()->where('credit', '!=', 1)->selectRaw('mode_paiements.*')->get();

        if (isset($filters)) {
            $query        = Outil::getAllItemsWithGraphQl("factures", $filters);
        } else {
            $query        = Outil::getAllItemsWithGraphQl("factures");
        }

        if (isset($query)) {
            //        //get datas by GraphQL
            $prevu_le       = null;

            if (count($query) > 1 || isset($type) && $type == 'all') {
                foreach ($query as $key => $val) {
                    $ca_ht  += $val['montant_ht'];
                    $ca     += $val['montant'];
                    $ca_tva += $val['montant_tva'];
                }
                $data = array(
                    'data'              => $query,
                    "interne"           => $interne,
                    'prevule'           => $prevu_le,
                    "pdf"               => 'all',
                    'ca'                => $ca,
                    'ca_ht'             => $ca_ht,
                    'ca_tva'             => $ca_tva,
                    "modepaiement"     => $modepaiements

                );
            } else if (count($query) == 1) {

                $facture  = $query[0];
                $filter = 'facture_id:' . $facture['id'];

                //$item                  = Outil::getOneItemWithGraphQl("factures", $id);
                $item  = $query[0];
                $filtre_detail         = "facture_id:" . $item['id'];
                $queryName_detail      = "detailfactures";
                $details               = Outil::getOneItemWithFilterGraphQl($queryName_detail, $filtre_detail);

                if (isset($details) && count($details) == 1) {
                    $prevu_le          = $details[0]['commande']['date_fr'];
                } else {
                    $prevu_le          = $item['date_echeance_fr'];
                }

                //Détail paiements
                $filtre_detail_paie    = "facture_id:" . $item['id'];
                $queryName_detail_paie = "paiementfactures";
                // $detailspaiements      = Outil::getOneItemWithFilterGraphQl($queryName_detail_paie, $filtre_detail_paie);
                $detailspaiements = Paiement::query()
                    ->join('mode_paiements', 'mode_paiements.id', 'paiements.mode_paiement_id')
                    ->join('commandes', 'commandes.id', 'paiements.commande_id')
                    ->join('detail_factures', 'detail_factures.commande_id', 'commandes.id')
                    ->join('factures', 'factures.id', 'detail_factures.facture_id')
                    ->where('factures.id', $item['id'])
                    ->selectRaw('paiements.montant as montant, paiements.date as date, mode_paiements.designation as modepaiement')->get();

                $data = array(
                    'item'              => $item,
                    'details'           => $details,
                    'detailspaiements'  => $detailspaiements,
                    "interne"           => $interne,
                    'prevule'           => $prevu_le,
                    "pdf"               => 'one',
                    'ca'                => $ca
                );
            }
        }

        return $data;
    }

    public function generate_excel_facture($filters  = null)
    {
        $data  = self::outil_factureold($filters);

        return Excel::download(new FactureRestautExport($data), 'facturerestaut.xlsx');
    }

    // public function generate_pdf_facture($filters = null, $interne = false)
    // {

    //     $data  = self::outil_factureold($filters, $interne);

    //     if (isset($data) && isset($data['pdf'])) {
    //         if ($data['pdf'] == 'all') {

    //             $customPaper = array(0, 0, 780, 900);
    //             return self::pdfnumberpage($data, 'all-facture-restau', $customPaper);
    //         } else {

    //             $customPaper = array(0, 0, 780, 900);
    //             return self::pdfnumberpage($data, 'facture');
    //         }
    //     }
    // }

    public function generate_pdf_facture_avoir_interne($id)
    {
        return self::generate_pdf_facture_avoir($id, true);
    }


    public function generate_pdf_facture_avoir($id, $interne = false)
    {

        //get datas by GraphQL
        $entite                = null;
        $item                  = Outil::getOneItemWithGraphQl("reguleclients", $id);
        if (isset($item) && isset($item['facture']) && isset($item['facture_id'])) {
            $facture           = Facture::find($item['facture_id']);

            if (isset($facture) && isset($facture->entite_id)) {
                $entite        = Entite::find($facture->entite_id);
            }
        }

        // dd($entite);

        $data = array(
            'item'              => $item,
            "interne"           => $interne,
            "entite"            => $entite
        );
        //dd($data);

        // Send data to the view using loadView function of PDF facade
        //        $pdf = \PDF::loadView('pdfs.facture-avoir', $data);

        //Voir le pdf sans download
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'facture-avoir');
    }


    public function generate_pdf_fichetechnique_cuisine($id)
    {

        $item = Outil::getOneItemWithGraphQl("produits", $id);

        $filtre_detail = "produit_id:$id";
        $queryName_detail = "fichetechniques";
        $details = Outil::getOneItemWithFilterGraphQl($queryName_detail, $filtre_detail);

        // array_push($itemstest, array("designation" => "Traiteur", "nombre_portion" => 4));
        $data = array('item' => $item, 'details' => $details,);
        //	dd($data);

        // Send data to the view using loadView function of PDF facade
        //        $pdf = \PDF::loadView('pdfs.fichetechnique', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'fichetechnique');
    }

    public function generate_pdf_fichetechnique_prop($request)
    {
        // dd($request);
        $traiteur  = Outil::getOneItemWithGraphQl("traiteurs", $request);

        if (isset($traiteur)) {
            $fiches = $traiteur['cuisine_stock_proformas'];
        }
        $details = array();
        $cout_total = 0;
        //  $fiches = json_decode($request, true);
        if (isset($fiches) && count($fiches) > 0) {
            //  dd($fiches);
            foreach ($fiches as $key => $value) {

                $produit = isset($value["produit_id"]) ? Produit::find($value["produit_id"]) : null;
                if (isset($produit)) {
                    if (!isset($value['pru'])) {
                        if (isset($produit->prix_achat_ttc)) {
                            $value['pru'] = $produit->prix_achat_ttc;
                        } else {
                            $value['pru'] = $produit->prix_achat_unitaire;
                        }
                    }
                    array_push($details, array(
                        "designation" => $produit->designation,
                        "portion" => $value['quantite_relle'],
                        "portion_unitaire" => $value['quantite_relle'],
                        "cost" => $value['quantite_relle'] * $value['pru'],
                        "unite_de_mesure" => isset($produit->unite_de_mesure) ? $produit->unite_de_mesure->designation : '',
                        "pru" => $value['pru'],
                    ));
                    $cout_total  +=  $value['quantite_relle'] * $value['pru'];
                }
            }
        }
        //   $item = array("designation" => "Traiteur(" . isset($fiches[0]['proposition']) ? $fiches[0]['proposition'] : "" . ")", "nombre_portion" => isset($fiches[0]['nombre_couvert']) ? $fiches[0]['nombre_couvert'] : '');
        $date = Outil::resolveAllDateCompletFRSlash($traiteur['date_debut_evenement'], false);

        $data = array(
            'item' => '',
            'traiteur' => $traiteur,
            'details' => $details,
            'date'    => $date,
            'cout_total'  => $cout_total
        );

        // $data = array('item' => '', 'details' => $details,);

        //        $pdf = \PDF::loadView('pdfs.fichetechnique-proforma', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'fichetechnique-proforma');
    }

    public function generate_pdf_fichetechnique_prop_bci($request)
    {

        $details = array();
        if (isset($request)) {
            // $proforma  = Proforma::find((int)$request);
            $proforma  = Outil::getOneItemWithGraphQl("traiteurs", $request);

            //dd($proforma->date_debut_evenement);

            if (isset($proforma)) {
                $fiches   = ProformaProduit::where('proforma_id', $proforma['id'])->get();
                //  $fiches =$proforma->first()["cuisine_stock_proformas"];
                if (isset($fiches)) {
                    foreach ($fiches as $key => $pf) {
                        if ($pf->produit->nature == 1) {
                            $pa = 0;
                            if (isset($pf->produit->prix_achat_ttc)) {
                                $pa = $pf->produit->prix_achat_ttc;
                            } else {
                                if (isset($pf->produit->prix_achat_unitaire)) {
                                    $pa = $pf->produit->prix_achat_unitaire;
                                }
                            }
                            array_push(
                                $details,
                                array(
                                    "designation" => $pf->produit->designation,
                                    "produit_compose_id" => $pf->produit->id,
                                    "portion" => $pf->quantite_relle,
                                    "portion_unitaire" => $pf->quantite_relle,
                                    "quantite" => $pf->quantite_relle,
                                    "cost" => '',
                                    "unite_de_mesure" => isset($pf->produit->unite_de_mesure) ? $pf->produit->unite_de_mesure->designation : '',
                                    "pru" => '',
                                    "pa"  => $pa,
                                    "traiteur" => $proforma
                                )
                            );
                        }
                    }

                    $entite = Entite::query()->where('designation', 'Traiteur')->first();
                    if (isset($entite)) {
                        $item = Bci::query()->where('proforma_id', $proforma['id'])->first();
                        if (!isset($item)) {
                            $item                 = new Bci();
                            $item->code           = '';
                            $item->date_operation = now();
                        }
                        $item->designation        = 'Traiteur';
                        $item->commentaire        = 'Generation de bci pour le traiteur N°:' . $proforma['code'];
                        $item->entite_id          = $entite->id;
                        $item->proforma_id        = $proforma['id'];
                        //dd($details[0]["traiteur"]["code"]);
                        $pdf = Outil::enregistrerBci($item, $details, $proforma);
                    }
                }
            }
        }

        // $data = array('item' => '', 'details' => $details,);
        // $pdf = \PDF::loadView('pdfs.fichetechnique', $data);
        return isset($pdf) ? $pdf->stream() : null;
    }

    public function generate_pdf_rh_prop($request)
    {
        $traiteur  = Outil::getOneItemWithGraphQl("traiteurs", $request);

        $details = array();
        $details_test = array();

        //        $fiches = json_decode($request, true);
        //
        //        if (isset($fiches) && count($fiches) > 0) {
        //            foreach ($fiches as $key => $value) {
        //                array_push($details_test, array(
        //                    "operateur" => $value['operateur']['designation'],
        //                    "tarif" => $value['tarif'],
        //                    "signature" => '',
        //                ));
        //
        //            }
        //        }
        //
        //        foreach ($fiches as $key => $value) {
        //
        //            $details_op = array();
        //
        //            $search = false;
        //            $date = Outil::resolveDateFr($value['date']);
        //            $montant_total = 0;
        //            foreach ($details as $keysearch => $searchop) {
        //                if ($date == $searchop['date_rh']) {
        //                    $search = true;
        //                }
        //
        //            }
        //            if ($search == false) {
        //                foreach ($fiches as $keyop => $op) {
        //                    if ($date == Outil::resolveDateFr($op['date'])) {
        //                        array_push($details_op, array(
        //                            "operateur" => $op['operateur']['designation'],
        //                            "tarif" => $op['tarif'],
        //                            "signature" => '',
        //                        ));
        //                        $montant_total = $montant_total + $op['tarif'];
        //                    }
        //                }
        //
        //                array_push($details, array(
        //                    "date_rh" => Outil::resolveDateFr($date), "details_rh" => $details_op, "montant" => $montant_total
        //                ));
        //                $montant_total_rh += $montant_total;
        //            }
        //
        //        }

        $item = array("designation" => "RH", "montant_total_rh" => 0);

        // $item = Outil::getOneItemWithGraphQl("produits", $id);

        // $filtre_detail = "produit_id:$id";
        //$queryName_detail = "fichetechniques";
        // $details = Outil::getOneItemWithFilterGraphQl($queryName_detail, $filtre_detail);

        $programmes   =  $traiteur['programme_rh'];
        $programmes_rh = array();
        $departements_employe = array();

        if (isset($programmes) && count($programmes) > 0) {
            $montant_total_rh = 0;
            foreach ($programmes as $key => $value) {
                $filters  = 'proforma_id:' . $traiteur['id'];
                $filters  .= ',date_traiteur:"' . $value['date'] . '"';
                $departements = Outil::getAllItemsWithGraphQl("departements", $filters);
                $montant_journalier = 0;
                if (isset($departements) && count($departements) > 0) {
                    foreach ($departements as $key => $valuedepart) {
                        $filters  = 'proforma_id:' . $traiteur['id'];
                        $filters  .= ',date:"' . $value['date'] . '"';
                        $filters  .= ',departement_id:' . $valuedepart['id'];
                        $employes = Outil::getAllItemsWithGraphQl("proformaoperateurs", $filters);
                        $montant = 0;
                        if (isset($employes) && count($employes) > 0) {
                            foreach ($employes as $key => $valueEmpl) {
                                $montant += (int) $valueEmpl['tarif'];
                            }
                        }
                        array_push($departements_employe, array(
                            "employes"      => $employes,
                            "departement"   => $valuedepart,
                            "date"          => $value["date"],
                            'montant'       => $montant
                        ));

                        $montant_journalier += $montant;
                    }
                }

                array_push($programmes_rh, array(
                    "date"                => $value["date"],
                    'montant_total'       => $montant_journalier
                ));
                $montant_total_rh += $montant_journalier;
            }
        }

        $data = array('item' => $item, 'details' => $details, 'operateurs' => $traiteur['proforma_operateurs'], 'programme_rh' => $programmes_rh, "departement_employe" => $departements_employe, 'traiteur' => $traiteur, 'montant_total_rh' => $montant_total_rh);
        // dd($details);

        //        $pdf = \PDF::loadView('pdfs.rh', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'rh');
    }

    public function generate_pdf_logistique_prop($id)
    {
        $details = array();
        $montant_total_ttc = 0;
        $montant_total_ht  = 0;
        //$proposition  = Outil::getOneItemWithGraphQl("propositioncommerciales", $id);

        $traiteur  = Outil::getOneItemWithGraphQl("traiteurs", $id);
        // dd($traiteur);
        if (isset($traiteur)) {
            $fiches = $traiteur['logistique_proformas'];
            //  dd($traiteur);
            if (isset($fiches) && count($fiches) > 0) {
                foreach ($fiches as $key => $value) {
                    if ($value['produit_id']) {
                        $produit = Produit::find($value["produit_id"]);

                        if (isset($produit)) {
                            $montant_ttc = 0;
                            if ($produit->prix_vente_unitaire) {
                                $montant_ttc += $produit->prix_vente_unitaire  + (($produit->prix_vente_unitaire * 18) / 100);

                                $montant_total_ttc += ($montant_ttc * (int) $value['quantite']);
                                $montant_total_ht  += ($produit->prix_vente_unitaire * (int) $value['quantite']);
                            }
                            array_push($details, array(
                                "logistique"   => $produit->designation,
                                "quantite"     => $value['quantite'],
                                "pu_ht"        => $produit->prix_vente_unitaire,
                                "pu_ttc"       => $montant_ttc,
                            ));
                        }
                    }
                }
            }
        }
        //       if(isset($proposition) && isset($proposition->option_materiel) && count($proposition->option_materiel) > 0){
        //           $fiches   = $proposition->option_materiel;
        //           dd($fiches);
        //       }
        //$fiches = json_decode($fiches, true);


        $item = array();
        $item = array(
            "designation"       => 'Traiteur',
            "montant_total_ttc" => $montant_total_ttc,
            "montant_total_ht"  => $montant_total_ht
        );
        $date = Outil::resolveAllDateCompletFRSlash($traiteur['date_debut_evenement'], false);

        $data = array(
            'item' => $item,
            'traiteur' => $traiteur,
            'details' => $details,
            'date'    => $date
        );

        //        $pdf = \PDF::loadView('pdfs.logistique-traiteur', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'logistique-traiteur');
    }

    public function generate_pdf_fichetechnique_custom($id, $nbre_portion)
    {
        if (isset($id) && isset($nbre_portion)) {
            $item = Outil::getOneItemWithGraphQl("produits", $id);

            $filtre_detail = "produit_id:$id";
            $queryName_detail = "fichetechniques";
            $details = Outil::getOneItemWithFilterGraphQl($queryName_detail, $filtre_detail);

            $data = array('item' => $item, 'details' => $details, 'nbre_portion' => $nbre_portion);

            //            $pdf = \PDF::loadView('pdfs.fichetechniquecustom', $data);
            //
            //            return $pdf->stream();

            $customPaper = array(0, 0, 780, 900);
            return self::pdfnumberpage($data, 'fichetechniquecustom', $customPaper);
        }
    }
    public function generate_pdf_carte($id)
    {
        if (isset($id)) {
            $carte  = Carte::find($id);
            if (isset($carte)) {
                $carte_produit         = Produit::query();
                $carte_produit         = $carte_produit->join('carte_produits', 'carte_produits.produit_id', '=', "produits.id")
                    ->join('cartes', 'cartes.id', '=', 'carte_produits.carte_id')
                    ->join('prix_de_ventes', 'prix_de_ventes.produit_id', '=', 'produits.id')
                    ->join('type_prix_de_ventes', 'type_prix_de_ventes.id', '=', 'prix_de_ventes.type_prix_de_vente_id')
                    ->where('type_prix_de_ventes.id', $carte->type_prix_vente_id)
                    ->where('cartes.id', $carte->id)
                    ->selectRaw('produits.*,prix_de_ventes.montant')
                    ->get();

                $data = array('item' => '', 'details' => $carte, 'carte_produit' => $carte_produit);
            }
            //            $pdf = \PDF::loadView('pdfs.carte', $data);
        }
        //        return $pdf->stream();
        return $this->pdfnumberpage($data, 'carte');
    }
    public function generate_reservation_jour($id = null)
    {
        if (isset($id)) {
        } else {
            $date = now();
            $data = null;
            // $reservation  = Reservation::query()->where('date_reservation', );
            //   dd($date);
            if (isset($carte)) {
                //  $data = array('item' => '', 'details' => $carte, 'carte_produit'=>$carte_produit);
            }
        }
        $pdf = \PDF::loadView('pdfs.reservation', []);
        return $pdf->stream();
    }

    public function generateExcel()
    {
        return Excel::download(new ExcelExport, 'nom_excel.xlsx');
    }
    public function generate_excel_commande($filters = null)
    {
        //dd($filters);
        return Excel::download(new CommandeExport($filters), 'commande.xlsx');
    }
    public function generate_pdf_commande($filters = null)
    {

        $attribus  = 'id,code,entite{id,designation,alias},date_fr,montant_total_commande,restant_payer,tables{id,designation},nombre_couvert,montant_total_format,restant_payer_format,etat_commande_text,modepaiement_commande{id,designation,montant},date_start,date_end,client_id,nom_du_client,etat_commande,entite_selected{id,designation},c_interne,credit';

        $data  = Outil::getAllItemsWithGraphQl("commandes", $filters, $attribus);

        $modepaiements  = Modepaiement::query()->where('credit', '!=', 1)->selectRaw('mode_paiements.*')->get();


        $data = array(
            'item'                  => '',
            'data'                  => $data,
            'modepaiement'          => $modepaiements
        );

        $customPaper = array(0, 0, 950, 950);
        return self::pdfnumberpage($data, 'commande', $customPaper);


        //        $pdf = \PDF::loadView('pdfs.commande', $data);
        //        $customPaper = array(0,0,950,950);
        //        return $pdf->setPaper($customPaper)->stream();
        //
        //        return $pdf->stream();

    }

    public function generate_excel_proforma($filters = null)
    {
        return Excel::download(new ProformaExport($filters), 'proforma.xlsx');
    }
    public function generate_pdf_proforma($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("proformas", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.proforma', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'proforma');
    }


    public function generate_excel_traiteur($filters = null)
    {
        return Excel::download(new TraiteurExport($filters), 'traiteur.xlsx');
    }
    public function generate_pdf_traiteur($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("traiteurs", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.traiteur', $data);
        //        return $pdf->setPaper($customPaper)->stream();
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'traiteur', $customPaper);
    }

    public function generate_pdf_proforma_traiteur_interne($id)
    {
        $data       = self::outil_proforma_traiteur($id, true);
        //        $pdf = \PDF::loadView('pdfs.proforma-traiteur', $data);
        //        $pdf        = \App::make('dompdf.wrapper');
        //        $pdf->getDomPDF()->set_option("enable_php", true);
        //        $pdf->loadView('pdfs.proforma-traiteur', $data);
        //        return $pdf->stream('proforma-traiteur.pdf');
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'proforma-traiteur', $customPaper);
    }
    public function generate_pdf_proforma_traiteur($id)
    {
        $data = self::outil_proforma_traiteur($id);

        //        $pdf = \PDF::loadView('pdfs.proforma-traiteur', $data);
        //
        //        return $pdf->stream();

        //        $customPaper = array(0,0,780,900);
        return self::pdfnumberpage($data, 'proforma-traiteur');
    }

    public function outil_proforma_traiteur($id, $interne = false)
    {
        $proposition  = Outil::getOneItemWithGraphQl("propositioncommerciales", $id);
        //  dd($proposition['forfait']);
        if (isset($proposition) && $proposition['proforma_id']) {
            $proforma = Outil::getOneItemWithGraphQl("proformas", $proposition['proforma_id']);
            if (!isset($proforma)) {
                $proforma = Outil::getOneItemWithGraphQl("traiteurs", $proposition['proforma_id']);
            }
        }


        $montant_total_ht = 0;
        $montant_total_ttc = 0;
        $montant_total_ht_remise = 0;
        $montant_tva = 0;
        $montant_remise = 0;
        if (isset($proposition['forfait_direct_menu']) && $proposition['forfait_direct_menu'] == true) {
            $montant_total_ht = $proposition['forfait'];
        } else {
            if ($proposition['nombre_personne'] == 0) {
                $proposition['nombre_personne'] = $proforma['nombre_personne'];
            }
            $montant_total_ht = $proposition['montant_par_personne'] * $proposition['nombre_personne'];
        }

        //Forfait materiel
        if (isset($proposition['forfait_option_materiel'])) {
            $montant_total_ht += $proposition['forfait_option_materiel'];
        }

        //Remise
        if (isset($proposition['remise'])) {
            $remise                  = ($montant_total_ht * $proposition['remise']) / 100;
            if ($montant_total_ht > $remise) {
                $montant_remise          = $remise;
                $montant_total_ht_remise = $montant_total_ht - $montant_remise;
            }
        }

        //exotva
        if (!isset($proposition['exotva']) || $proposition['exotva'] == false) {
            $montant_tva = ($montant_total_ht_remise * 18) / 100;
        }

        $montant_total_ttc = $montant_total_ht_remise + $montant_tva;

        $data = array(
            'item' => '',
            'proforma'                => $proforma,
            'proposition'             => $proposition,
            'montant_total_ht'        => isset($montant_total_ht) ? Outil::formatPrixToMonetaire($montant_total_ht, true) : $montant_total_ht,
            'montant_total_ht_remise' => isset($montant_total_ht_remise) ? Outil::formatPrixToMonetaire($montant_total_ht_remise, true) : $montant_total_ht_remise,
            'montant_total_ttc'       => isset($montant_total_ttc) ? Outil::formatPrixToMonetaire($montant_total_ttc, true) : $montant_total_ttc,
            'forfait_option_materiel' => Outil::formatPrixToMonetaire($proposition['forfait_option_materiel'], true),
            'montant_tva'             => isset($montant_tva) ? Outil::formatPrixToMonetaire($montant_tva, true) : $montant_tva,
            "interne"                 => $interne


        );


        return $data;
    }

    public function generate_excel_facture_traiteur($filters  = null)
    {
        $data  = self::outil_facture_traiteur($filters);

        return Excel::download(new FactureTraiteurExport($data), 'facturetraiteur.xlsx');
    }

    public function outil_facture_traiteur($filters, $interne = false)
    {

        $data                                = null;
        $total                               = 0;
        $detailspaiements                    = null;

        if (isset($filters)) {
            $facture                         = Outil::getAllItemsWithGraphQl("facturetraiteurs", $filters);
        } else {
            $facture                         = Outil::getAllItemsWithGraphQl("facturetraiteurs");
        }

        if (isset($facture)) {

            $filters                        .= ',designation:"facturetraiteur"';

            $autres                          = Outil::getAllItemsWithGraphQl("autres", $filters);

            if (isset($autres) && count($autres) == 1) {

                $autres                  =  $autres[0];
            }
            if (count($facture) == 1) {

                $facture                     = $facture[0];
                $filter                      = 'facture_id:' . $facture['id'];
                $detail_facture              = Outil::getAllItemsWithGraphQl("detailfactures", $filter);

                if (isset($detail_facture) && count($detail_facture) == 1) {
                    $proforma                = null;
                    foreach ($detail_facture as $key => $d) {
                        if (isset($d['proforma_id'])) {

                            $proforma        = Outil::getOneItemWithGraphQl("traiteurs", $d['proforma_id']);

                            if (isset($proforma) && isset($proforma['id'])) {
                                $filter      = 'est_activer:1,proforma_id:' . $proforma['id'];
                                $proposition = Outil::getOneItemWithGraphQl("propositioncommerciales", $filter);

                                if (isset($proposition) && $proposition['proforma_id']) {
                                    $montant_total_ht_remise = 0;
                                    $montant_tva             = 0;
                                    if (isset($proposition['forfait_direct_menu']) && $proposition['forfait_direct_menu'] == true) {
                                        $montant_total_ht    = $proposition['forfait'];
                                    } else {
                                        $montant_total_ht    = $proposition['montant_par_personne'] * $proposition['nombre_personne'];
                                    }

                                    //Forfait materiel
                                    if (isset($proposition['forfait_option_materiel'])) {
                                        $montant_total_ht   += $proposition['forfait_option_materiel'];
                                    }
                                    //Remise
                                    if (isset($proposition['remise'])) {
                                        $remise              = ($montant_total_ht * $proposition['remise']) / 100;
                                        if ($montant_total_ht > $remise) {
                                            $montant_remise          = $remise;
                                            $montant_total_ht_remise = $montant_total_ht - $montant_remise;
                                        }
                                    }
                                    //exotva
                                    if (!isset($proposition['exotva']) || $proposition['exotva'] == false) {
                                        $montant_tva                 = ($montant_total_ht_remise * 18) / 100;
                                    }
                                    $montant_total_ttc               = $montant_total_ht_remise + $montant_tva;

                                    //Détail paiements
                                    $filtre_detail_paie    = "facture_id:" . $facture['id'];
                                    $queryName_detail_paie = "paiementfactures";
                                    $detailspaiements      = Outil::getOneItemWithFilterGraphQl($queryName_detail_paie, $filtre_detail_paie);

                                    $data = array(
                                        'item'                         => '',
                                        'facture'                      => $facture,
                                        'detail_facture'               => $detail_facture,
                                        'proforma'                     => $proforma,
                                        'proposition'                  => $proposition,
                                        'montant_total_ht'             => isset($montant_total_ht) ? Outil::formatPrixToMonetaire($montant_total_ht, true) : $montant_total_ht,
                                        'montant_total_ht_remise'      => isset($montant_total_ht_remise) ? Outil::formatPrixToMonetaire($montant_total_ht_remise, true) : $montant_total_ht_remise,
                                        'montant_total_ttc'            => isset($montant_total_ttc) ? Outil::formatPrixToMonetaire($montant_total_ttc, true) : $montant_total_ttc,
                                        'forfait_option_materiel'      => Outil::formatPrixToMonetaire($proposition['forfait_option_materiel'], true),
                                        'montant_tva'                  => isset($montant_tva) ? Outil::formatPrixToMonetaire($montant_tva, true) : $montant_tva,
                                        'montant_total_ttc_not_format' => $montant_total_ttc,
                                        'pdf'                          => 'one',
                                        'autres'                       => $autres,
                                        "interne"                      => $interne,
                                        "detailspaiements"             => $detailspaiements
                                    );
                                }
                            }
                        }
                    }
                }
            } else {
                $data = array(
                    'item'      => '',
                    'data'      => $facture,
                    'pdf'       => 'all',
                    'autres'    => $autres
                );
            }
        }

        return $data;
    }

    public function generate_pdf_facture_traiteur($filters  = null)
    {

        $data  = self::outil_facture_traiteur($filters);
        if (isset($data) && isset($data['pdf'])) {
            if ($data['pdf'] == 'all') {
                $pdf  = 'all-facture-traiteur';

                //                $pdf = \PDF::loadView('pdfs.'.$pdf, $data);
                //                $customPaper = array(0,0,780,900);
                //                return $pdf->setPaper($customPaper)->stream();
                $customPaper = array(0, 0, 780, 900);
                return self::pdfnumberpage($data, $pdf);
            } else {
                //                $pdf = \PDF::loadView('pdfs.facture-traiteur', $data);
                //
                //                return $pdf->stream();
                return self::pdfnumberpage($data, 'facture-traiteur');
            }
        }
    }

    public function generate_pdf_facture_traiteur_interne($filters  = null)
    {

        $data  = self::outil_facture_traiteur($filters, true);
        if (isset($data) && isset($data['pdf'])) {
            if ($data['pdf'] == 'all') {
                //                $pdf  = 'all-facture-traiteur';
                //
                //                $pdf = \PDF::loadView('pdfs.'.$pdf, $data);
                //                $customPaper = array(0,0,780,900);
                //                return $pdf->setPaper($customPaper)->stream();
                $customPaper = array(0, 0, 780, 900);
                return self::pdfnumberpage($data, 'all-facture-traiteur', $customPaper);
            } else {

                //                $pdf = \PDF::loadView('pdfs.facture-traiteur', $data);
                //
                //                return $pdf->stream();
                return self::pdfnumberpage($data, 'facture-traiteur');
            }
        }
    }

    public function generate_excel_activite($filters = null)
    {
        return Excel::download(new ActiviteExport($filters), 'activite.xlsx');
    }
    public function generate_pdf_activite($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("activites", $filters);

        $data = array('item' => '', 'data' => $data);

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'activite');

        //        $pdf = \PDF::loadView('pdfs.activite', $data);
        //
        //        return $pdf->stream();

    }

    public function generate_excel_societefacturation($filters = null)
    {
        $user_id = Outil::donneUserId();
        if (!isset($filters)) {
            $filters = '';
        }
        $filters .= ",user_id:" . $user_id;
        return Excel::download(new SocietefacturationExport($filters), 'societefacturation.xlsx');
    }
    public function generate_pdf_societefacturation($filters = null)
    {
        $user_id = Outil::donneUserId();
        if (!isset($filters)) {
            $filters = '';
        }
        $filters .= ",user_id:" . $user_id;
        $data  = Outil::getAllItemsWithGraphQl("societefacturations", $filters);

        $data = array('item' => '', 'data' => $data);

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'societefacturation');

        //        $pdf = \PDF::loadView('pdfs.societefacturation', $data);
        //
        //        return $pdf->stream();

    }

    public function generate_excel_entite($filters = null)
    {
        $user_id = Outil::donneUserId();
        if (!isset($filters)) {
            $filters = '';
        }
        $filters .= ",user_id:" . $user_id;

        return Excel::download(new EntiteExport($filters), 'entite.xlsx');
    }
    public function generate_pdf_entite($filters = null)
    {
        $user_id = Outil::donneUserId();
        if (!isset($filters)) {
            $filters = '';
        }
        $filters .= ",user_id:" . $user_id;
        $data  = Outil::getAllItemsWithGraphQl("entites", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.entite', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'entite', $customPaper);
    }

    public function generate_excel_typedepot($filters = null)
    {

        return Excel::download(new TypedepotExport($filters), 'typedepot.xlsx');
    }
    public function generate_pdf_typedepot($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typedepots", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typedepot', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typedepot');
    }

    public function generate_excel_depot($filters = null)
    {
        $user_id = Outil::donneUserId();
        if (!isset($filters)) {
            $filters = '';
        }
        $filters .= ",user_id:" . $user_id;

        return Excel::download(new DepotExport($filters), 'depot.xlsx');
    }
    public function generate_pdf_depot($filters = null)
    {
        $user_id = Outil::donneUserId();
        if (!isset($filters)) {
            $filters = '';
        }
        $filters .= ",user_id:" . $user_id;
        $data  = Outil::getAllItemsWithGraphQl("depots", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.depot', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'depot');
    }

    public function generate_excel_departement($filters = null)
    {

        return Excel::download(new DepartementExport($filters), 'departement.xlsx');
    }
    public function generate_pdf_departement($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("departements", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.departement', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'departement');
    }

    public function generate_excel_sousdepartement($filters = null)
    {

        return Excel::download(new SousdepartementExport($filters), 'sousdepartement.xlsx');
    }
    public function generate_pdf_sousdepartement($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("sousdepartements", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.sousdepartement', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'sousdepartement');
    }

    public function generate_excel_zonedestockage($filters = null)
    {

        return Excel::download(new ZonedestockagesExport($filters), 'zonedestockage.xlsx');
    }
    public function generate_pdf_zonedestockage($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("zonedestockages", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.zonedestockage', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'zonedestockage');
    }

    public function generate_excel_typeevenement($filters = null)
    {

        return Excel::download(new TypeevenementExport($filters), 'typeevenement.xlsx');
    }
    public function generate_pdf_typeevenement($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typeevenements", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typeevenement', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typeevenement');
    }

    public function generate_excel_typeproduit($filters = null)
    {

        return Excel::download(new TypeproduitExport($filters), 'typeproduit.xlsx');
    }
    public function generate_pdf_typeproduit($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typeproduits", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typeproduit', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typeproduit');
    }

    public function generate_excel_modepaiement($filters = null)
    {

        return Excel::download(new ModepaiementExport($filters), 'modepaiement.xlsx');
    }
    public function generate_pdf_modepaiement($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("modepaiements", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.modepaiement', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'modepaiement');
    }
    public function generate_excel_banque($filters = null)
    {
        return Excel::download(new BanqueExport($filters), 'banque.xlsx');
    }
    public function generate_pdf_banque($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("banques", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.banque', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'banque');
    }

    public function generate_excel_typebillet($filters = null)
    {
        return Excel::download(new TypebilletExport($filters), 'typebillet.xlsx');
    }
    public function generate_pdf_typebillet($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typebillets", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typebillet', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typebillet');
    }

    public function generate_excel_conditionreglement($filters = null)
    {
        return Excel::download(new ConditionreglementExport($filters), 'conditionreglement.xlsx');
    }
    public function generate_pdf_conditionreglement($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("conditionreglements", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.conditionreglement', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'conditionreglement');
    }

    public function generate_excel_zonedelivraison($filters = null)
    {
        return Excel::download(new ZonedelivraisonExport($filters), 'zonedelivraison.xlsx');
    }
    public function generate_pdf_zonedelivraison($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("zonedelivraisons", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.zonedelivraison', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'zonedelivraison');
    }

    public function generate_excel_tranchehoraire($filters = null)
    {
        return Excel::download(new TranchehoraireExport($filters), 'tranchehoraire.xlsx');
    }
    public function generate_pdf_tranchehoraire($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("tranchehoraires", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.tranchehoraire', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'tranchehoraire');
    }

    public function generate_excel_typefaitdiver($filters = null)
    {
        return Excel::download(new TypefaitdiverExport($filters), 'typefaitdiver.xlsx');
    }
    public function generate_pdf_typefaitdiver($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typefaitdivers", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typefaitdiver', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typefaitdiver');
    }

    public function generate_excel_typeclient($filters = null)
    {
        return Excel::download(new TypeclientExport($filters), 'typeclient.xlsx');
    }
    public function generate_pdf_typeclient($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typeclients", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typeclient', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typeclient');
    }

    public function generate_excel_client($filters = null)
    {
        return Excel::download(new ClientExport($filters), 'client.xlsx');
    }
    public function generate_pdf_client($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("clients", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.client', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 900, 900);
        return self::pdfnumberpage($data, 'client', $customPaper);
    }

    public function generate_excel_typetier($filters = null)
    {
        return Excel::download(new TypetierExport($filters), 'typetier.xlsx');
    }
    public function generate_pdf_typetier($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typetiers", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typetier', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typetier');
    }

    public function generate_excel_categoriefournisseur($filters = null)
    {
        return Excel::download(new CategoriefournisseurExport($filters), 'categoriefournisseur.xlsx');
    }
    public function generate_pdf_categoriefournisseur($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("categoriefournisseurs", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.categoriefournisseur', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'categoriefournisseur');
    }

    public function generate_excel_fournisseur($filters = null)
    {
        return Excel::download(new FournisseurExport($filters), 'fournisseur.xlsx');
    }
    public function generate_pdf_fournisseur($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("fournisseurs", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.fournisseur', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'fournisseur');
    }

    public function generate_excel_categorieproduit($filters = null)
    {
        return Excel::download(new CategorieproduitExport($filters), 'categorieproduit.xlsx');
    }
    public function generate_pdf_categorieproduit($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("categorieproduits", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.categorieproduit', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'categorieproduit');
    }

    public function generate_excel_famille($filters = null)
    {
        if (isset($filters)) {
        } else {
            $filters = '';
        }

        $ressource = "list-famille";
        $filters .= 'ressource:"' . $ressource . '"';
        return Excel::download(new FamilleExport($filters), 'famille.xlsx');
    }
    public function generate_pdf_famille($filters = null)
    {
        if (isset($filters)) {
        } else {
            $filters = '';
        }

        $ressource = "list-famille";
        $filters .= 'ressource:"' . $ressource . '"';
        $data  = Outil::getAllItemsWithGraphQl("familles", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.famille', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'famille');
    }

    public function generate_excel_sousfamille($filters = null)
    {
        return Excel::download(new SousfamilleExport($filters), 'sousfamille.xlsx');
    }
    public function generate_pdf_sousfamille($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("sousfamilles", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.sousfamille', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'sousfamille');
    }

    public function generate_excel_typeprixdevente($filters = null)
    {
        return Excel::download(new TypeprixdeventeExport($filters), 'typeprixdevente.xlsx');
    }
    public function generate_pdf_typeprixdevente($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typeprixdeventes", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typeprixdevente', $data);
        //
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typeprixdevente');
    }

    public function generate_excel_nomenclature($filters = null)
    {
        return Excel::download(new NomenclatureExport($filters), 'nomenclature.xlsx');
    }
    public function generate_pdf_nomenclature($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("nomenclatures", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.nomenclature', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'nomenclature');
    }

    public function generate_excel_unitedemesure($filters = null)
    {
        return Excel::download(new UnitedemesureExport($filters), 'unitedemesure.xlsx');
    }
    public function generate_pdf_unitedemesure($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("unitedemesures", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.unitedemesure', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'unitedemesure');
    }

    public function generate_excel_typedeconservation($filters = null)
    {
        return Excel::download(new TypedeconservationExport($filters), 'typedeconservation.xlsx');
    }
    public function generate_pdf_typedeconservation($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("typedeconservations", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.typedeconservation', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typedeconservation');
    }

    public function generate_excel_produit($filters = null)
    {
        return Excel::download(new ProduitExport($filters), 'produit.xlsx');
    }
    public function generate_pdf_produit($filters = null)
    {
        $data  = Outil::getAllItemsWithGraphQl("produits", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.produit', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'produit', $customPaper);
    }

    public function generate_excel_reservation($filters = null)
    {
        return Excel::download(new ReservationExport($filters), 'reservation.xlsx');
    }
    public function generate_pdf_reservation($filters = null)
    {
        $user = Auth::user();
        if (isset($filters)) {
            if (isset($user) && isset($user->id)) {
            }
            $filters .= ',user_id:' . $user->id;
        }
        $data  = Outil::getAllItemsWithGraphQl("reservations", $filters);

        $data = array('item' => '', 'data' => $data);

        //        $pdf = \PDF::loadView('pdfs.reservation', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'reservation');
    }

    public function generate_excel_bci($filters = null)
    {
        $data  = self::outil_bci($filters);

        return Excel::download(new BciExport($data), 'bci.xlsx');
    }
    public function outil_bci($filters = null)
    {
        $user = Auth::user();

        $data  = Outil::getAllItemsWithGraphQl("bcis", $filters);
        $article = null;
        $retour  = null;

        if (isset($data) && count($data) > 0) {
            $total_ht                         = 0;
            $total_ttc                        = 0;
            $nbre_produit                     = 0;
            $quantite_total                   = 0;
            $details_familles                 = array();
            $quantite_total_finale            = 0;
            $total_finale_ht                  = 0;
            $total_finale_ttc                 = 0;

            //Voir si la requette est liee juste a une seule entite
            $entite = null;
            if (isset($data[0]) && isset($data[0]['is_entite']) && $data[0]['is_entite'] == true) {
                $entite  = Outil::getOneItemWithGraphQl("entites", $data[0]['entite_id']);
            }
            foreach ($data as $key => $ligne) {
                $nbre_produit                += (float) $ligne['nombre_produit'];

                $quantite_total              += (int) $ligne['quantite'];         //Quantite totale initiale
                $total_ht                    += (float) $ligne['valorisation_ht']; //Valorisation HT total initiale
                $total_ttc                   += (float) $ligne['valorisation_ttc']; //Valorisation TTC total initiale

                $quantite_total_finale       += (int) $ligne['quantite_finale'];  //Quantite total  finale
                $total_finale_ht             += (float) $ligne['valorisation_finale_ht']; //Valorisation HT total initiale
                $total_finale_ttc            += (float) $ligne['valorisation_finale_ttc']; //Valorisation TTC total initiale

                if (isset($ligne['bciproduits']) && count($ligne['bciproduits']) > 0) {
                    foreach ($ligne['bciproduits'] as $key => $detail) {
                        array_push($details_familles, $detail);
                    }
                }
            }
        }

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
            'details_familles'              => $details_familles,
            'article'                       => $article,
            'nbre_produit'                  => $nbre_produit,

            'quantite_total'                => $quantite_total,
            'valorisation_total_ht'         => round($total_ht),
            'valorisation_total_ttc'        => round($total_ttc),

            'quantite_total_finale'         => $quantite_total_finale,
            'valorisation_total_finale_ht'  => round($total_finale_ht),
            'valorisation_total_finale_ttc' => round($total_finale_ttc),

            'entite'                        => $entite,
        );


        return $retour;
    }
    public function generate_pdf_bci($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_bci($filters);


        //        $pdf = \PDF::loadView('pdfs.bcientite', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'bcientite');
    }

    public function generate_pdf_bci_ligne($filters = null)
    {
        $user = Auth::user();

        $data  = Outil::getAllItemsWithGraphQl("bcis", $filters);
        if (isset($data) && count($data) > 0) {
            $bci_id  = $data[0]['id'];
            $filters = ",bci_id:" . $bci_id;
            if ($data[0]['famille']) {
                $filters .= 'famille_id:' . $data[0]['famille']['id'];
            }
            $article = Outil::getAllItemsWithGraphQl("bciproduits", $filters);
            $entite  = Outil::getOneItemWithGraphQl("entites", $data[0]['entite_id']);
            $total_ht = 0;
            foreach ($data as $key => $ligne) {
                $total_ht  += (float) ($ligne['valorisation_ht']);
            }
        }

        $data = array(
            'item'                  => '',
            'data'                  => $data,
            'valorisation_total_ht' => $total_ht,
            'article'               => $article,
            'entite'                => $entite,
            'details_familles'      => null,
        );


        //        $pdf = \PDF::loadView('pdfs.bcientite', $data);
        //
        //        return $pdf->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'bcientite');
    }

    public function generate_excel_bce($filters = null)
    {
        $data  = self::outil_bce($filters);

        return Excel::download(new BceExport($data), 'bce.xlsx');
    }
    public function outil_bce($filters = null)
    {
        $user = Auth::user();

        $data  = Outil::getAllItemsWithGraphQl("bces", $filters);
        $article = null;
        $retour  = null;
        $entite = null;

        if (isset($data) && count($data) > 0) {
            $total_ht                         = 0;
            $total_ttc                        = 0;
            $nbre_produit                     = 0;
            $quantite_total                   = 0;
            $details_familles                 = array();
            $quantite_total_finale            = 0;
            $total_finale_ht                  = 0;
            $total_finale_ttc                 = 0;

            //Voir si la requette est liee juste a une seule entite

            if (isset($data[0]) && isset($data[0]['is_entite']) && $data[0]['is_entite'] == true) {
                $entite  = Outil::getOneItemWithGraphQl("entites", $data[0]['entite_id']);
            }
            foreach ($data as $key => $ligne) {
                $nbre_produit                += (float) $ligne['nbre_produit'];

                $quantite_total              += (int) $ligne['quantite'];         //Quantite totale initiale
                $total_ht                    += (float) $ligne['valorisation_ht']; //Valorisation HT total initiale
                $total_ttc                   += (float) $ligne['valorisation_ttc']; //Valorisation TTC total initiale

                $quantite_total_finale       += (int) $ligne['quantite_finale'];  //Quantite total  finale
                $total_finale_ht             += (float) $ligne['valorisation_finale_ht']; //Valorisation HT total initiale
                $total_finale_ttc            += (float) $ligne['valorisation_finale_ttc']; //Valorisation TTC total initiale

                if (isset($ligne['bce_produits']) && count($ligne['bce_produits']) > 0) {
                    foreach ($ligne['bce_produits'] as $key => $detail) {
                        array_push($details_familles, $detail);
                    }
                }
            }
        }

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
            'details_familles'              => $details_familles,
            'article'                       => $article,
            'nbre_produit'                  => $nbre_produit,

            'quantite_total'                => $quantite_total,
            'valorisation_total_ht'         => round($total_ht),
            'valorisation_total_ttc'        => round($total_ttc),

            'quantite_total_finale'         => $quantite_total_finale,
            'valorisation_total_finale_ht'  => round($total_finale_ht),
            'valorisation_total_finale_ttc' => round($total_finale_ttc),
            'entite'                        => $entite,
        );


        return $retour;
    }
    public function generate_pdf_bce($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_bce($filters);

        //        $pdf = \PDF::loadView('pdfs.bce', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'bce', $customPaper);
    }

    public function generate_pdf_bce_ligne($filters = null)
    {
        $user = Auth::user();

        $data  = Outil::getAllItemsWithGraphQl("bces", $filters);
        if (isset($data) && count($data) > 0) {
            $bce_id  = $data[0]['id'];
            $filters = ",bce_id:" . $bce_id;
            if ($data[0]['famille']) {
                $filters .= 'famille_id:' . $data[0]['famille']['id'];
            }
            $article = Outil::getAllItemsWithGraphQl("bceproduits", $filters);
            //            $entite  = Outil::getOneItemWithGraphQl("entites", $data[0]['entite_id']);
            $total_ht = 0;
            foreach ($data as $key => $ligne) {
                $total_ht  += (float) ($ligne['valorisation_ht']);
            }
        }

        $data = array(
            'item'                  => '',
            'data'                  => $data,
            'valorisation_total_ht' => $total_ht,
            'article'               => $article,
            'entite'                => null,
            'details_familles'      => null,
        );

        //        $pdf = \PDF::loadView('pdfs.bce', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'bce', $customPaper);
    }

    public function generate_excel_be($filters = null)
    {
        $data  = self::outil_be($filters);

        return Excel::download(new BeExport($data), 'be.xlsx');
    }
    public function outil_be($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("bes", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $total_ht                         = 0;
        $total_ttc                        = 0;
        $nbre_produit                     = 0;
        $quantite_total                   = 0;
        $details_familles                 = array();
        $quantite_total_finale            = 0;
        $total_finale_ht                  = 0;
        $total_finale_ttc                 = 0;
        $valorisation_total               = 0;

        if (isset($data) && count($data) > 0) {


            //Voir si la requette est liee juste a une seule entite

            if (isset($data[0]) && isset($data[0]['is_fournisseur']) && $data[0]['is_fournisseur'] == true) {
                $fournisseur  = Outil::getOneItemWithGraphQl("fournisseurs", $data[0]['fournisseur_id']);
            }
            foreach ($data as $key => $ligne) {
                $nbre_produit                += (float) $ligne['nbre_produit'];

                $quantite_total              += (int) $ligne['quantite'];         //Quantite totale initiale
                $total_ht                    += (float) $ligne['valorisation_ht']; //Valorisation HT total initiale
                $total_ttc                   += (float) $ligne['valorisation_ttc']; //Valorisation TTC total initiale

                if (isset($ligne['beproduits']) && count($ligne['beproduits']) > 0) {
                    foreach ($ligne['beproduits'] as $key => $detail) {
                        array_push($details_familles, $detail);
                    }
                }
            }
            $valorisation_total              = ($total_ttc + $total_ht);
        }

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
            'details_familles'              => $details_familles,
            'article'                       => $article,
            'nbre_produit'                  => $nbre_produit,

            'quantite_total'                => $quantite_total,
            'valorisation_total_ht'         => round($total_ht),
            'valorisation_total_ttc'        => round($total_ttc),

            'quantite_total_finale'         => $quantite_total_finale,
            'valorisation_total_finale_ht'  => round($total_finale_ht),
            'valorisation_total_finale_ttc' => round($total_finale_ttc),
            'valorisation_total'            => round($valorisation_total),

            'entite'                        => $entite,
            'fournisseur'                   => $fournisseur,
        );


        return $retour;
    }
    public function generate_pdf_be($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_be($filters);

        /*$pdf = \PDF::loadView('pdfs.be', $data);
        $customPaper = array(0,0,780,900);
        return $pdf->setPaper($customPaper)->stream();*/
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'be', $customPaper);
    }

    public function generate_pdf_be_ligne($filters = null)
    {
        $user = Auth::user();

        $data  = Outil::getAllItemsWithGraphQl("bes", $filters);
        if (isset($data) && count($data) > 0) {
            $be_id  = $data[0]['id'];
            $filters = ",be_id:" . $be_id;
            if ($data[0]['famille']) {
                $filters .= 'famille_id:' . $data[0]['famille']['id'];
            }
            $article = Outil::getAllItemsWithGraphQl("beproduits", $filters);
            //            $entite  = Outil::getOneItemWithGraphQl("entites", $data[0]['entite_id']);
            $total_ht = 0;
            foreach ($data as $key => $ligne) {
                $total_ht  += (float) ($ligne['valorisation_ht']);
            }
        }


        $data = array(
            'item'                  => '',
            'data'                  => $data,
            'valorisation_total_ht' => $total_ht,
            'article'               => $article,
            'entite'                => null,
            'details_familles'      => null,
            'fournisseur'           => null
        );

        /*        $pdf = \PDF::loadView('pdfs.be', $data);
        $customPaper = array(0,0,780,900);
        return $pdf->setPaper($customPaper)->stream();*/
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'be', $customPaper);
    }


    public function generate_excel_permission($filters = null)
    {
        $data  = self::outil_permission($filters);

        return Excel::download(new PermissionExport($data), 'permission.xlsx');
    }
    public function outil_permission($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("permissions", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_permission($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_permission($filters);

        /*$pdf = \PDF::loadView('pdfs.permission', $data);
        $customPaper = array(0,0,780,900);
        return $pdf->setPaper($customPaper)->stream();*/
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'permission', $customPaper);
    }


    public function generate_excel_role($filters = null)
    {
        $data  = self::outil_role($filters);

        return Excel::download(new RoleExport($data), 'role.xlsx');
    }
    public function outil_role($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("roles", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_role($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_role($filters);

        //        $pdf = \PDF::loadView('pdfs.role', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'role', $customPaper);
    }

    public function generate_excel_user($filters = null)
    {
        $data  = self::outil_user($filters, "user");

        return Excel::download(new UserExport($data), 'user.xlsx');
    }
    public function outil_user($filters = null, $type = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithModel($type, $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_user($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_user($filters, "user");

        //        $pdf = \PDF::loadView('pdfs.user', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'user', $customPaper);
    }

    public function generate_excel_cloturecaisse($filters = null)
    {
        $data  = self::outil_cloturecaisse($filters);
        $data = self::detailClotureCaisse($data);

        return Excel::download(new ClotureCaisseExport($data), 'cloturecaisse.xlsx');
    }
    public function detailClotureCaisse($data)
    {
        $billetages = null;
        $encaissements = null;
        $total_encaissement = null;
        $total_billetage = null;
        $entite         = null;
        $total_fond         = 0;
        $total_encaissement         = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {

            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                $cc_id = $data['data'][0]['id'];
                if (isset($data['data'][0]['caisse_id'])) {
                    $caisse  = Caisse::find($data['data'][0]['caisse_id']);
                    if (isset($caisse) && isset($caisse->entite_id)) {
                        $entite = Entite::find($caisse->entite_id);
                    }
                }
                $filters_billetage = "cloture_caisse_id:" . $cc_id;
                $billetages            = Outil::getAllItemsWithGraphQl("billetages", $filters_billetage);
                $encaissements         = Outil::getAllItemsWithGraphQl("encaissements", $filters_billetage);
                $total_encaissement    = 0;
                $total_encaissement_theo    = 0;
                $total_encaissement_reel    = 0;
                if (isset($billetages) && count($billetages) > 0) {
                    foreach ($billetages as $key => $b) {
                        if (isset($b['type_billet']) && isset($b['type_billet']['nombre'])) {
                            $total_billetage += ($b['nombre'] * $b['type_billet']['nombre']);
                        }
                    }
                }

                if (isset($encaissements) && count($encaissements) > 0) {
                    foreach ($encaissements as $key => $enc) {
                        if ($enc['montant']) {
                            $total_encaissement += $enc['montant'];
                        }
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $item) {
            $total_fond += $item['fond_caisse'];
            //$total_encaissement_theo +=isset($item['total_theorique_encaissement']) ? $item['total_theorique_encaissement'] : 0;
            // $total_encaissement_reel +=isset($item['total_ecaissement']) ? $item['total_ecaissement'] : 0;
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'billetages'                     => $billetages,
            'encaissements'                  => $encaissements,
            'total_encaissement'             => $total_encaissement,
            'total_billetage'                => $total_billetage,
            'entite'                         => $entite,
            'fond'                           => $total_fond,
        );
        return $data;
    }
    public function outil_cloturecaisse($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("cloturecaisses", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_cloturecaisse($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_cloturecaisse($filters);
        $data = self::detailClotureCaisse($data);
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'cloturecaisse', $customPaper);

        //        $pdf = \PDF::loadView('pdfs.cloturecaisse', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
    }

    public function generate_excel_caisse($filters = null)
    {
        $data  = self::outil_caisse($filters);
        //        $data = self::detailCaisse($data);

        return Excel::download(new CaisseExport($data), 'caisse.xlsx');
    }
    public function detailCaisse($data)
    {
        $billetages = null;
        $encaissements = null;
        $total_encaissement = null;
        $total_billetage = null;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                $cc_id = $data['data'][0]['id'];
                $filters_billetage = "cloture_caisse_id:" . $cc_id;
                $billetages            = Outil::getAllItemsWithGraphQl("billetages", $filters_billetage);
                $encaissements         = Outil::getAllItemsWithGraphQl("encaissements", $filters_billetage);
                $total_encaissement    = 0;
                if (isset($billetages) && count($billetages) > 0) {
                    foreach ($billetages as $key => $b) {
                        if (isset($b['type_billet']) && isset($b['type_billet']['nombre'])) {
                            $total_billetage += ($b['nombre'] * $b['type_billet']['nombre']);
                        }
                    }
                }

                if (isset($encaissements) && count($encaissements) > 0) {
                    foreach ($encaissements as $key => $enc) {
                        if ($enc['montant']) {
                            $total_encaissement += $enc['montant'];
                        }
                    }
                }
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'billetages'                     => $billetages,
            'encaissements'                  => $encaissements,
            'total_encaissement'             => $total_encaissement,
            'total_billetage'                => $total_billetage
        );
        return $data;
    }
    public function outil_caisse($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("caisses", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_caisse($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_caisse($filters);
        // $data = self::detailCaisse($data);

        //        $pdf = \PDF::loadView('pdfs.caisse', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'caisse', $customPaper);
    }

    public function generate_excel_bt($filters = null)
    {

        $user = Auth::user();
        $details = null;
        $data  = self::outil_bt($filters);

        $data = self::detailbt($data);

        return Excel::download(new BtExport($data), 'bt.xlsx');
    }
    public function outil_bt($filters = null)
    {
        $user = Auth::user();
        //        dd($filters);

        $data           = Outil::getAllItemsWithGraphQl("bts", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function detailbt($data)
    {
        $details = null;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                $bt_id = $data['data'][0]['id'];
                $filters_detail = "bt_id:" . $bt_id;
                $details           = Outil::getAllItemsWithGraphQl("btproduits", $filters_detail);
            }
        }
        $data = array(
            'item'                          => '',
            'data'                          => $data['data'],
            'details'                       => $details
        );
        return $data;
    }
    public function generate_pdf_bt($filters = null)
    {
        $user = Auth::user();
        $details = null;
        $data  = self::outil_bt($filters);

        $data = self::detailbt($data);

        //        $pdf = \PDF::loadView('pdfs.bt', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'bt', $customPaper);
    }

    public function generate_excel_approcash($filters = null)
    {
        $data  = self::outil_approcash($filters);
        //        $data = self::detailCaisse($data);

        return Excel::download(new ApprocashExport($data), 'approcash.xlsx');
    }
    public function detailApprocash($data)
    {
        $billetages = null;
        $encaissements = null;
        $total_encaissement = null;
        $total_billetage = null;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                $cc_id = $data['data'][0]['id'];
                $filters_billetage = "cloture_caisse_id:" . $cc_id;
                $billetages            = Outil::getAllItemsWithGraphQl("billetages", $filters_billetage);
                $encaissements         = Outil::getAllItemsWithGraphQl("encaissements", $filters_billetage);
                $total_encaissement    = 0;
                if (isset($billetages) && count($billetages) > 0) {
                    foreach ($billetages as $key => $b) {
                        if (isset($b['type_billet']) && isset($b['type_billet']['nombre'])) {
                            $total_billetage += ($b['nombre'] * $b['type_billet']['nombre']);
                        }
                    }
                }

                if (isset($encaissements) && count($encaissements) > 0) {
                    foreach ($encaissements as $key => $enc) {
                        if ($enc['montant']) {
                            $total_encaissement += $enc['montant'];
                        }
                    }
                }
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'billetages'                     => $billetages,
            'encaissements'                  => $encaissements,
            'total_encaissement'             => $total_encaissement,
            'total_billetage'                => $total_billetage
        );
        return $data;
    }
    public function outil_approcash($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("approcashs", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_approcash($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_approcash($filters);
        // $data = self::detailCaisse($data);

        //        $pdf = \PDF::loadView('pdfs.approcash', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'approcash', $customPaper);
    }

    public function generate_excel_sortiecash($filters = null)
    {
        $data  = self::outil_sortiecash($filters);
        //        $data = self::detailCaisse($data);

        return Excel::download(new SortiecashExport($data), 'sortiecash.xlsx');
    }
    public function detailSortiecash($data)
    {
        $billetages = null;
        $encaissements = null;
        $total_encaissement = null;
        $total_billetage = null;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                $cc_id = $data['data'][0]['id'];
                $filters_billetage = "cloture_caisse_id:" . $cc_id;
                $billetages            = Outil::getAllItemsWithGraphQl("billetages", $filters_billetage);
                $encaissements         = Outil::getAllItemsWithGraphQl("encaissements", $filters_billetage);
                $total_encaissement    = 0;
                if (isset($billetages) && count($billetages) > 0) {
                    foreach ($billetages as $key => $b) {
                        if (isset($b['type_billet']) && isset($b['type_billet']['nombre'])) {
                            $total_billetage += ($b['nombre'] * $b['type_billet']['nombre']);
                        }
                    }
                }

                if (isset($encaissements) && count($encaissements) > 0) {
                    foreach ($encaissements as $key => $enc) {
                        if ($enc['montant']) {
                            $total_encaissement += $enc['montant'];
                        }
                    }
                }
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'billetages'                     => $billetages,
            'encaissements'                  => $encaissements,
            'total_encaissement'             => $total_encaissement,
            'total_billetage'                => $total_billetage
        );
        return $data;
    }
    public function outil_sortiecash($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("sortiecashs", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_sortiecash($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_sortiecash($filters);
        // $data = self::detailCaisse($data);

        //        $pdf = \PDF::loadView('pdfs.sortiecash', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'sortiecash', $customPaper);
    }

    public function generate_excel_versement($filters = null)
    {
        $data  = self::outil_versement($filters);
        //        $data = self::detailCaisse($data);

        return Excel::download(new VersementExport($data), 'versement.xlsx');
    }
    public function detailVersement($data)
    {
        $billetages = null;
        $encaissements = null;
        $total_encaissement = null;
        $total_billetage = null;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                $cc_id = $data['data'][0]['id'];
                $filters_billetage = "cloture_caisse_id:" . $cc_id;
                $billetages            = Outil::getAllItemsWithGraphQl("billetages", $filters_billetage);
                $encaissements         = Outil::getAllItemsWithGraphQl("encaissements", $filters_billetage);
                $total_encaissement    = 0;
                if (isset($billetages) && count($billetages) > 0) {
                    foreach ($billetages as $key => $b) {
                        if (isset($b['type_billet']) && isset($b['type_billet']['nombre'])) {
                            $total_billetage += ($b['nombre'] * $b['type_billet']['nombre']);
                        }
                    }
                }

                if (isset($encaissements) && count($encaissements) > 0) {
                    foreach ($encaissements as $key => $enc) {
                        if ($enc['montant']) {
                            $total_encaissement += $enc['montant'];
                        }
                    }
                }
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'billetages'                     => $billetages,
            'encaissements'                  => $encaissements,
            'total_encaissement'             => $total_encaissement,
            'total_billetage'                => $total_billetage
        );
        return $data;
    }
    public function outil_versement($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("versements", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_versement($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_versement($filters);
        // $data = self::detailCaisse($data);

        //        $pdf = \PDF::loadView('pdfs.versement', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'versement', $customPaper);
    }

    public function generate_excel_depense($filters = null)
    {
        $data  = self::outil_depense($filters);
        //        $data = self::detailCaisse($data);

        return Excel::download(new DepenseExport($data), 'depense.xlsx');
    }
    public function detailDepense($data)
    {
        $depensepostedepenses = null;
        $entitetransactioncaisses = null;
        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_depense            = 0;
        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['entite_id'])) {
                    $entite  = Entite::find($data['data'][0]['entite_id']);
                }
                $cc_id = $data['data'][0]['id'];
                $filters_depense_post = "depense_id:" . $cc_id;
                $depensepostedepenses            = Outil::getAllItemsWithGraphQl("depensepostedepenses", $filters_depense_post);

                $entitetransactioncaisses         = Outil::getAllItemsWithGraphQl("entitetransactioncaisses", $filters_depense_post);

                if (isset($depensepostedepenses) && count($depensepostedepenses) > 0) {
                    foreach ($depensepostedepenses as $key => $b) {
                        $total_postdepense_ht += $b['montant'];
                        $total_postdepense_ttc += $b['montant_ttc'];
                    }
                }

                if (isset($entitetransactioncaisses) && count($entitetransactioncaisses) > 0) {
                    foreach ($entitetransactioncaisses as $key => $b) {
                        $total += $b['montant'];
                    }
                }
            }
        } else {

            foreach ($data['data'] as $key => $b) {
                $total_depense += $b['montant'];
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'depensepostedepenses'           => $depensepostedepenses,
            'total_postdepense_ht'           => $total_postdepense_ht,
            'total_postdepense_ttc'          => $total_postdepense_ttc,
            'entitetransactioncaisses'       => $entitetransactioncaisses,
            'total'                          => $total,
            'total_depense'                  => $total_depense,
            "entite"                         => $entite
        );

        return $data;
    }
    public function outil_depense($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("depenses", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_depense($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_depense($filters);
        $data = self::detailDepense($data);
        //        $pdf = \PDF::loadView('pdfs.depense', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'depense', $customPaper);
    }


    public function generate_excel_postedepense($filters = null)
    {
        $data  = self::outil_postedepense($filters);
        //        $data = self::detailCaisse($data);

        return Excel::download(new PostDepenseExport($data), 'postedepense.xlsx');
    }
    public function detailPostedepense($data)
    {
        $depensepostedepenses = null;
        $entitetransactioncaisses = null;
        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_depense            = 0;
        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['entite_id'])) {
                    $entite  = Entite::find($data['data'][0]['entite_id']);
                }
                $cc_id = $data['data'][0]['id'];
                $filters_depense_post = "depense_id:" . $cc_id;
                $depensepostedepenses            = Outil::getAllItemsWithGraphQl("depensepostedepenses", $filters_depense_post);

                $entitetransactioncaisses         = Outil::getAllItemsWithGraphQl("entitetransactioncaisses", $filters_depense_post);

                if (isset($depensepostedepenses) && count($depensepostedepenses) > 0) {
                    foreach ($depensepostedepenses as $key => $b) {
                        $total_postdepense_ht += $b['montant'];
                        $total_postdepense_ttc += $b['montant_ttc'];
                    }
                }

                if (isset($entitetransactioncaisses) && count($entitetransactioncaisses) > 0) {
                    foreach ($entitetransactioncaisses as $key => $b) {
                        $total += $b['montant'];
                    }
                }
            }
        } else {

            foreach ($data['data'] as $key => $b) {
                $total_depense += $b['montant'];
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'depensepostedepenses'           => $depensepostedepenses,
            'total_postdepense_ht'           => $total_postdepense_ht,
            'total_postdepense_ttc'          => $total_postdepense_ttc,
            'entitetransactioncaisses'       => $entitetransactioncaisses,
            'total'                          => $total,
            'total_depense'                  => $total_depense,
            "entite"                         => $entite
        );

        return $data;
    }
    public function outil_postedepense($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("postedepenses", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );

        return $retour;
    }
    public function generate_pdf_postedepense($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_postedepense($filters);
        // $data = self::detail($data);
        //  dd($data);
        //        $pdf = \PDF::loadView('pdfs.postedepense', $data);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'postedepense', $customPaper);
    }


    public function generate_excel_souspostedepense($filters = null)
    {
        $data  = self::outil_souspostedepense($filters);

        return Excel::download(new SousPostDepenseExport($data), 'souspostedepense.xlsx');
    }
    public function outil_souspostedepense($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("souspostedepenses", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );

        return $retour;
    }
    public function generate_pdf_souspostedepense($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_souspostedepense($filters);


        //        $pdf = \PDF::loadView('pdfs.souspostedepense', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'souspostedepense', $customPaper);
    }

    public function generate_excel_inventaire($filters = null)
    {
        $data  = self::outil_inventaire($filters);
        $data = self::detailInventaire($data);

        return Excel::download(new InventaireExport($data), 'inventaire.xlsx');
    }
    public function detailInventaire($data)
    {

        $detail_inventaires       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_inventaires_ht     = 0;
        $total_inventaires_ttc    = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                $cc_id = $data['data'][0]['id'];
                $filters_inventaire = "inventaire_id:" . $cc_id;
                $detail_inventaires            = Outil::getAllItemsWithGraphQl("inventaireproduits", $filters_inventaire);
            }
        }
        //        foreach ($data['data'] as $key=>$i)
        ////        {
        ////            $total_inventaires_ht  +=$i['manquant_ht'];
        ////            $total_inventaires_ttc +=$i['manquant_ttc'];
        ////        }

        foreach ($detail_inventaires as $key => $i) {
            $total_inventaires_ht  += $i['quantite_reel'] * $i['pa_ht'];
            $total_inventaires_ttc += $i['quantite_reel'] * $i['pa_ttc'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'detailinventaire'               => $detail_inventaires,
            "entite"                         => $entite,
            "total_inventaires_ht"           => $total_inventaires_ht,
            "total_inventaires_ttc"          => $total_inventaires_ttc
        );

        return $data;
    }
    public function outil_inventaire($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("inventaires", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_inventaire($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_inventaire($filters);
        $data = self::detailInventaire($data);

        //        $pdf = \PDF::loadView('pdfs.inventaire', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'inventaire', $customPaper);
    }

    public function generate_excel_production($filters = null)
    {
        $data  = self::outil_production($filters);
        $data = self::detailProduction($data);

        return Excel::download(new ProductionExport($data), 'production.xlsx');
    }
    public function detailProduction($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_production($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("productions", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_production($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_production($filters);
        $data = self::detailProduction($data);

        //        $pdf = \PDF::loadView('pdfs.production', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'production', $customPaper);
    }

    public function generate_excel_employe($filters = null)
    {
        $data  = self::outil_employe($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new EmployeExport($data), 'employe.xlsx');
    }
    public function detailEmploye($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_employe($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("employes", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_employe($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_employe($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.employe', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'employe', $customPaper);
    }

    public function generate_excel_operateur($filters = null)
    {
        $data  = self::outil_operateur($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new OperateurExport($data), 'operateur.xlsx');
    }
    public function detailOperateur($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_operateur($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("operateurs", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_operateur($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_operateur($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.operateur', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'operateur', $customPaper);
    }

    //typedecaisse
    public function generate_excel_typedecaisse($filters = null)
    {
        $data  = self::outil_typedecaisse($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new TypeDeCaisseExport($data), 'typedecaisse.xlsx');
    }
    public function detailTypedecaisse($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_typedecaisse($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("typedecaisses", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_typedecaisse($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_typedecaisse($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.typedecaisse', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typedecaisse', $customPaper);
    }

    //entreestock
    public function generate_excel_entreestock($filters = null)
    {
        $data  = self::outil_entreestock($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new EntreeStockExport($data), 'entreestock.xlsx');
    }
    public function detailEntreestock($data)
    {

        $details_entrees          = null;
        $total_entrestock         = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]) && count($data['data'][0]) > 0) {
                    if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                        $cc_id = $data['data'][0]['id'];

                        $filters_prod             = "entre_sortie_stock_id:" . $cc_id;
                        $details_entrees         = Outil::getAllItemsWithGraphQl("entresortiestockproduits", $filters_prod);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_entrestock += $i['valeur'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'details_entrees'                => $details_entrees,
            "entite"                         => $entite,
            "total_entrestock"               => $total_entrestock,
        );

        return $data;
    }
    public function outil_entreestock($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("entreestocks", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_entreestock($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_entreestock($filters);
        $data = self::detailEntreestock($data);

        //        $pdf = \PDF::loadView('pdfs.entreestock', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'entreestock', $customPaper);
    }

    //sortiestock
    public function generate_excel_sortiestock($filters = null)
    {
        $data  = self::outil_sortiestock($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new SortieStockExport($data), 'sortiestock.xlsx');
    }
    public function detailSortiestock($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_sortiestock($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("sortiestocks", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_sortiestock($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_sortiestock($filters);
        $data = self::detailEntreestock($data);

        //        $pdf = \PDF::loadView('pdfs.sortiestock', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'sortiestock', $customPaper);
    }

    //stockactuelproduitdepot
    public function generate_excel_stockactuelproduitdepot($filters = null)
    {
        $data  = self::outil_stockactuelproduitdepot($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new StockActuelProduitDepotExport($data), 'stockactuelproduitdepot.xlsx');
    }
    public function detailStockactuelproduitdepot($data)
    {

        $sortie_production       = null;

        $valorisation_stock_ht        = 0;
        $valorisation_stock_ttc        = 0;

        $entite                   = 0;
        $produit                  = null;
        $depot                    = null;

        if (isset($data['data']) && count($data['data']) > 0) {
            //dd($data[0]['is_select_produit']);
            $is_select_produit = isset($data['data'][0]['is_select_produit']) ? $data['data'][0]['is_select_produit'] : null;
            $is_select_depot   = isset($data['data'][0]['is_select_depot']) ? $data['data'][0]['is_select_depot'] : null;
            $is_select_famille = isset($data['data'][0]['is_select_famille']) ? $data['data'][0]['is_select_famille'] : null;

            if (isset($data['data'])  && count($data['data']) == 1) {
                if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                    if (isset($data['data'][0]['depot_id'])) {
                        $depots  = Depot::find($data['data'][0]['depot_id']);
                        $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                        //$produit  = Produit::find($data['data'][0]['produit_id']);
                    }
                }
            }
            foreach ($data['data'] as $key => $i) {

                if (isset($i['produit'])) {
                    $pa_ht =  isset($i['produit']['prix_achat_unitaire']) ? $i['produit']['prix_achat_unitaire'] : 0;
                    $pa_ttc = isset($i['produit']['prix_achat_ttc']) ? $i['produit']['prix_achat_ttc'] : 0;

                    $valorisation_stock_ht  += $i['quantite'] * $i['produit']['prix_achat_unitaire'];
                    $valorisation_stock_ttc += $i['quantite'] * $i['produit']['prix_achat_ttc'];
                }
            }
        }



        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            "entite"                         => $entite,
            "depot"                          => $depot,
            "produit"                        => $produit,
            "valorisation_stock_ht"          => $valorisation_stock_ht,
            "valorisation_stock_ttc"         => $valorisation_stock_ttc,
            'is_select_produit'              => $is_select_produit,
            'is_select_depot'                => $is_select_depot,
            'is_select_famille'              => $is_select_famille
        );

        return $data;
    }
    public function outil_stockactuelproduitdepot($filters = null)
    {
        $user = Auth::user();


        $data           = Outil::getAllItemsWithGraphQl("stockactuelproduitdepots", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_stockactuelproduitdepot($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_stockactuelproduitdepot($filters);
        $data = self::detailStockactuelproduitdepot($data);

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'stockactuelproduitdepot', $customPaper);

        //        $pdf = \PDF::loadView('pdfs.stockactuelproduitdepot', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
    }

    //paiement
    public function generate_excel_paiement($filters = null)
    {
        $data  = self::outil_paiement($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new PaiementExport($data), 'paiement.xlsx');
    }
    public function detailPaiement($data)
    {

        $total                    = 0;
        $total        = 0;

        $entite                   = null;
        $caisse                   = null;
        $is_select_caisse = null;
        $is_select_entite =  null;
        $is_select_date_start = null;
        $is_select_date_end = null;
        $is_select_mode_paiement = null;
        $is_select_client = null;

        if (isset($data['data']) && count($data['data']) > 0) {

            $is_select_caisse = isset($data['data'][0]['is_select_caisse']) ? $data['data'][0]['is_select_caisse'] : null;
            $is_select_entite = isset($data['data'][0]['is_select_entite']) ? $data['data'][0]['is_select_entite'] : null;
            $is_select_mode_paiement = isset($data['data'][0]['is_select_mode_paiement']) ? $data['data'][0]['is_select_mode_paiement'] : null;
            $is_select_client = isset($data['data'][0]['is_select_client']) ? $data['data'][0]['is_select_client'] : null;
            if (isset($is_select_entite)) {

                $entite = Entite::find($is_select_entite);
            }
            if (isset($is_select_caisse)) {
                $caisse = Caisse::find($is_select_caisse);
                if (isset($caisse) && isset($caisse->entite_id)) {
                    $entite = Entite::find($caisse->entite_id);
                }
            }
            //            if(!empty($is_select_mode_paiement)){
            //                $is_select_mode_paiement = Modepaiement::find($is_select_mode_paiement);
            //            }
            //            if(!empty($is_select_client)){
            //                $is_select_client = Client::find($is_select_client);
            //            }

            $is_select_date_start = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_start'] : null;
            $is_select_date_end = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_end'] : null;

            foreach ($data['data'] as $key => $i) {
                $total += $i['montant'];
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            "entite"                         => $entite,
            "caisse"                         => $caisse,
            "total"                          => $total,
            "is_select_caisse"               => $is_select_caisse,
            "is_select_entite"               => $is_select_entite,
            "is_select_date_start"           => $is_select_date_start,
            "is_select_date_end"             => $is_select_date_end,
            "is_select_client"               => $is_select_client,
            "is_select_mode_paiement"        => $is_select_mode_paiement
        );

        return $data;
    }
    public function outil_paiement($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("paiements", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_paiement($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_paiement($filters);
        $data = self::detailPaiement($data);

        //        $pdf = \PDF::loadView('pdfs.paiement', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'paiement', $customPaper);
    }


    //paiement credit
    public function generate_excel_paiementcredit($filters = null)
    {
        $data  = self::outil_paiement($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new PaiementCreditExport($data), 'paiementcredit.xlsx');
    }
    public function detailPaiementCredit($data)
    {

        $total                    = 0;
        $total        = 0;

        $entite                   = null;
        $caisse                   = null;
        $is_select_caisse = null;
        $is_select_entite =  null;
        $is_select_date_start = null;
        $is_select_date_end = null;

        if (isset($data['data']) && count($data['data']) > 0) {

            $is_select_caisse = isset($data['data'][0]['is_select_caisse']) ? $data['data'][0]['is_select_caisse'] : null;
            $is_select_entite = isset($data['data'][0]['is_select_entite']) ? $data['data'][0]['is_select_entite'] : null;
            if (isset($is_select_entite)) {

                $entite = Entite::find($is_select_entite);
            }
            if (isset($is_select_caisse)) {
                $caisse = Caisse::find($is_select_caisse);
                if (isset($caisse) && isset($caisse->entite_id)) {
                    $entite = Entite::find($caisse->entite_id);
                }
            }

            $is_select_date_start = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_start'] : null;
            $is_select_date_end = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_end'] : null;

            foreach ($data['data'] as $key => $i) {
                $total += $i['montant'];
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            "entite"                         => $entite,
            "caisse"                         => $caisse,
            "total"                          => $total,
            "is_select_caisse"               => $is_select_caisse,
            "is_select_entite"               => $is_select_entite,
            "is_select_date_start"           => $is_select_date_start,
            "is_select_date_end"             => $is_select_date_end
        );

        return $data;
    }
    public function outil_paiementcredit($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("paiementcredits", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_paiementcredit($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_paiementcredit($filters);
        $data = self::detailPaiementCredit($data);

        //        $pdf = \PDF::loadView('pdfs.paiementcredit', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'paiementcredit', $customPaper);
    }

    //categoriedepense
    public function generate_excel_categoriedepense($filters = null)
    {
        $data  = self::outil_categoriedepense($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new CategorieDepenseExport($data), 'categoriedepense.xlsx');
    }
    public function detailCategoriedepense($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_categoriedepense($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("categoriedepenses", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_categoriedepense($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_categoriedepense($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.categoriedepense', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'categoriedepense', $customPaper);
    }

    //reglement
    public function generate_excel_reglement($filters = null)
    {
        $data  = self::outil_reglement($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new ReglementExport($data), 'reglement.xlsx');
    }
    public function detailReglement($data)
    {

        $entite                   = null;
        $caisse                   = null;
        $total                    = 0;
        $total_cash               = 0;
        $is_select_caisse = null;
        $is_select_entite =  null;
        $is_select_date_start = null;
        $is_select_date_end = null;

        $entite                   = 0;
        if (isset($data['data']) && count($data['data']) > 0) {
            $is_select_caisse = isset($data['data'][0]['is_select_caisse']) ? $data['data'][0]['is_select_caisse'] : null;
            $is_select_entite = isset($data['data'][0]['is_select_entite']) ? $data['data'][0]['is_select_entite'] : null;
            if (isset($is_select_entite)) {

                $entite = Entite::find($is_select_entite);
            }
            if (isset($is_select_caisse)) {
                $caisse = Caisse::find($is_select_caisse);
                if (isset($caisse) && isset($caisse->entite_id)) {
                    $entite = Entite::find($caisse->entite_id);
                }
            }


            // dd($data['data']);

            $is_select_date_start = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_start'] : null;
            $is_select_date_end = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_end'] : null;
            foreach ($data['data'] as $key => $i) {

                $total += $i['montant'];
                if (isset($i['mode_paiement'])) {
                    if ($i['mode_paiement']['est_cash'] == 1) {
                        $total_cash += $i['montant'];
                    }
                }
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'total'                          => $total,
            'total_cash'                     => $total_cash,
            "entite"                         => $entite,
            "caisse"                         => $caisse,
            "is_select_caisse"               => $is_select_caisse,
            "is_select_entite"               => $is_select_entite,
            "is_select_date_start"           => $is_select_date_start,
            "is_select_date_end"             => $is_select_date_end
        );

        return $data;
    }
    public function outil_reglement($filters = null)
    {
        $user = Auth::user();

        $attributs = 'id,date,entite_id,entite{id,designation},mode_paiement_id,mode_paiement{id,designation,est_cash},depense_id,depense{id,code,date,montant,motif,entite_id,entite{id,designation},fournisseur{id,designation}},caisse_id,caisse{id,designation},created_at_user{id,name},updated_at_user{id,name},montant';

        $data           = Outil::getAllItemsWithGraphQl("reglements", $filters, $attributs);
        //dd($data);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_reglement($filters = null)
    {
        $user = Auth::user();



        $data  = self::outil_reglement($filters);
        $data = self::detailReglement($data);



        //        $pdf = \PDF::loadView('pdfs.reglement', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'reglement', $customPaper);
    }

    // //typecontrat
    // public function generate_excel_typecontrat($filters = null)
    // {
    //     $data  = self::outil_typecontrat($filters);
    //     // $data = self::detailProduction($data);

    //     return Excel::download(new TypeContratExport($data), 'typecontrat.xlsx');
    // }
    public function detailTypecontrat($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    // public function outil_typecontrat($filters = null)
    // {
    //     $user = Auth::user();

    //     $data           = Outil::getAllItemsWithGraphQl("categoriedepenses", $filters);
    //     $article        = null;
    //     $retour         = null;
    //     $entite         = null;
    //     $fournisseur    = null;

    //     $retour = array(
    //         'item'                          => '',
    //         'data'                          => $data,
    //     );


    //     return $retour;
    // }
    // public function generate_pdf_typecontrat($filters = null)
    // {
    //     $user = Auth::user();

    //     $data  = self::outil_typecontrat($filters);
    //     // $data = self::detailEmploye($data);

    //     //        $pdf = \PDF::loadView('pdfs.typecontrat', $data);
    //     //        $customPaper = array(0,0,780,900);
    //     //        return $pdf->setPaper($customPaper)->stream();

    //     $customPaper = array(0, 0, 780, 900);
    //     return self::pdfnumberpage($data, 'typecontrat', $customPaper);
    // }

    //brigade
    public function generate_excel_brigade($filters = null)
    {
        $data  = self::outil_brigade($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new BrigadeExport($data), 'brigade.xlsx');
    }
    public function detailBrigade($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_brigade($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("brigades", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_brigade($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_brigade($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.brigade', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'brigade', $customPaper);
    }

    //fonction
    public function generate_excel_fonction($filters = null)
    {
        $data  = self::outil_fonction($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new FonctionExport($data), 'fonction.xlsx');
    }
    public function detailFonction($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_fonction($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("fonctions", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_fonction($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_fonction($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.fonction', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'fonction', $customPaper);
    }

    //familleaction
    public function generate_excel_familleaction($filters = null)
    {
        $data  = self::outil_familleaction($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new FamilleActionExport($data), 'familleaction.xlsx');
    }
    public function detailFamilleaction($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_familleaction($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("familleactions", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_familleaction($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_familleaction($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.familleaction', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'familleaction', $customPaper);
    }

    //zone
    public function generate_excel_zone($filters = null)
    {
        $data  = self::outil_zone($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new ZoneExport($data), 'zone.xlsx');
    }
    public function detailZone($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_zone($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("zones", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_zone($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_zone($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.zone', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'zone', $customPaper);
    }

    //typeoperateur
    public function generate_excel_typeoperateur($filters = null)
    {
        $data  = self::outil_typeoperateur($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new TypeOperateurExport($data), 'typeoperateur.xlsx');
    }
    public function detailTypeoperateur($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_typeoperateur($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("typeoperateurs", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_typeoperateur($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_typeoperateur($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.typeoperateur', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'typeoperateur', $customPaper);
    }

    //action
    public function generate_excel_action($filters = null)
    {
        $data  = self::outil_action($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new ActionExport($data), 'action.xlsx');
    }
    public function detailAction($data)
    {

        $sortie_production       = null;

        $total_postdepense_ht     = 0;
        $total_postdepense_ttc    = 0;
        $total                    = 0;
        $total_production        = 0;

        $entite                   = 0;
        if (isset($data['data'])  && count($data['data']) == 1) {
            if (isset($data['data'][0]) && isset($data['data'][0]['id'])) {
                if (isset($data['data'][0]['depot_id'])) {
                    $depots  = Depot::find($data['data'][0]['depot_id']);
                    $entite  = isset($depots) && isset($depots->entite_id) ? Entite::find($depots->entite_id) : null;
                }
                //dd($data['data'][0]['detail_assemblages'][0]['id']);
                if (isset($data['data'][0]['detail_assemblages']) && count($data['data'][0]['detail_assemblages']) > 0) {
                    if (isset($data['data'][0]['detail_assemblages'][0]) && isset($data['data'][0]['detail_assemblages'][0]['id'])) {
                        $cc_id = $data['data'][0]['detail_assemblages'][0]['id'];

                        $filters_prod = "detail_assemblage_id:" . $cc_id;
                        $sortie_production         = Outil::getAllItemsWithGraphQl("detaildetailassemblages", $filters_prod);
                        // dd($cc_id);
                        // dd($sortie_production);
                    }
                }
            }
        }
        foreach ($data['data'] as $key => $i) {
            $total_production += $i['prix_achat_unitaire'];
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            'sortie_production'              => $sortie_production,
            "entite"                         => $entite,
            "total_production"               => $total_production,
        );

        return $data;
    }
    public function outil_action($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("actions", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_action($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_action($filters);
        // $data = self::detailEmploye($data);

        //        $pdf = \PDF::loadView('pdfs.action', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'action', $customPaper);
    }

    //paiementfacture
    public function generate_excel_paiementfacture($filters = null)
    {
        $data  = self::outil_paiementfacture($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new PaiementFactureExport($data), 'paiementfactures.xlsx');
    }
    public function detailPaiementfacture($data)
    {
        $total                    = 0;
        $total        = 0;

        $entite                   = null;
        $caisse                   = null;
        $is_select_caisse = null;
        $is_select_entite =  null;
        $is_select_date_start = null;
        $is_select_date_end = null;
        //dd($data['data']);
        if (isset($data['data']) && count($data['data']) > 0) {

            $is_select_caisse = isset($data['data'][0]['is_select_caisse']) ? $data['data'][0]['is_select_caisse'] : null;
            $is_select_entite = isset($data['data'][0]['is_select_entite']) ? $data['data'][0]['is_select_entite'] : null;
            if (isset($is_select_entite)) {

                $entite = Entite::find($is_select_entite);
            }
            if (isset($is_select_caisse)) {
                $caisse = Caisse::find($is_select_caisse);
                if (isset($caisse) && isset($caisse->entite_id)) {
                    $entite = Entite::find($caisse->entite_id);
                }
            }

            $is_select_date_start = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_start'] : null;
            $is_select_date_end = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_end'] : null;

            foreach ($data['data'] as $key => $i) {
                $total += $i['montant'];
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            "entite"                         => $entite,
            "caisse"                         => $caisse,
            "total"                          => $total,
            "is_select_caisse"               => $is_select_caisse,
            "is_select_entite"               => $is_select_entite,
            "is_select_date_start"           => $is_select_date_start,
            "is_select_date_end"             => $is_select_date_end
        );

        return $data;
    }
    public function outil_paiementfacture($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("paiementfactures", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_paiementfacture($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_paiementfacture($filters);
        $data = self::detailPaiementfacture($data);
        //        $pdf = \PDF::loadView('pdfs.paiementfacture', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'paiementfacture', $customPaper);
    }

    //recouvrement
    public function generate_excel_recouvrement($filters = null)
    {
        $data  = self::outil_recouvrement($filters);
        // $data = self::detailProduction($data);

        return Excel::download(new RecouvrementExport($data), 'recouvrement.xlsx');
    }
    public function detailRecouvrement($data)
    {
        $total_restant_paye                    = 0;
        $total_recouvrement        = 0;

        $entite                   = null;
        $client                   = null;
        $societe_facturation      = null;
        $is_select_entite =  null;
        $is_select_client =  null;
        $is_select_societe =  null;
        $is_select_date_start = null;
        $is_select_date_end = null;
        //        dd($data['data']);
        if (isset($data['data']) && count($data['data']) > 0) {

            $is_select_entite = isset($data['data'][0]['is_select_entite']) ? $data['data'][0]['is_select_entite'] : null;
            $is_select_client = isset($data['data'][0]['is_select_client']) ? $data['data'][0]['is_select_client'] : null;
            $is_select_societe = isset($data['data'][0]['is_select_societe']) ? $data['data'][0]['is_select_societe'] : null;
            if (isset($is_select_entite)) {
                $entite = Entite::find($is_select_entite);
            }
            if (isset($is_select_client)) {
                $client = \App\Client::find($is_select_client);
            }
            if (isset($is_select_societe)) {
                $societe_facturation = Societefacturation::find($is_select_societe);
            }

            $is_select_date_start = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_start'] : null;
            $is_select_date_end = isset($data['data'][0]['is_select_date_start']) ? $data['data'][0]['is_select_date_end'] : null;

            foreach ($data['data'] as $key => $i) {
                $total_recouvrement += $i['montant'];
                $total_restant_paye += $i['restant_paye'];
            }
        }
        $data = array(
            'item'                           => '',
            'data'                           => $data['data'],
            "entite"                         => $entite,
            "client"                         => $client,
            "societe_facturation"            => $societe_facturation,
            "total_recouvrement"             => $total_recouvrement,
            "total_restant_paye"             => $total_restant_paye,
            "is_select_entite"               => $is_select_entite,
            "is_select_client"               => $is_select_client,
            "is_select_societe"              => $is_select_societe,
            "is_select_date_start"           => $is_select_date_start,
            "is_select_date_end"             => $is_select_date_end
        );

        return $data;
    }
    public function outil_recouvrement($filters = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl("recouvrements", $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }
    public function generate_pdf_recouvrement($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_recouvrement($filters);
        $data = self::detailRecouvrement($data);

        //        $pdf = \PDF::loadView('pdfs.recouvrement', $data);
        //        $customPaper = array(0,0,780,900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'recouvrement', $customPaper);
    }

    public function outil_etat_pdf($filters = null, $query = null)
    {
        $user = Auth::user();

        $data           = Outil::getAllItemsWithGraphQl($query, $filters);
        $article        = null;
        $retour         = null;
        $entite         = null;
        $fournisseur    = null;

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );


        return $retour;
    }

    public function generate_excel_etat_produit($filters = null)
    {
        $data  = self::outil_etat_pdf($filters, 'produits');
        // $data = self::detailProduction($data);

        return Excel::download(new RecouvrementExport($data), 'etat-produit.xlsx');
    }

    public function generate_pdf_etat_produit($filters)
    {
        //        $user = Auth::user();
        ////
        ////        $data  = self::outil_etat_pdf($filters, 'produits');
        ////
        ////        $pdf = \PDF::loadView('pdfs.eta-produit', $data);
        ////        $customPaper = array(0,0,780,900);
        ////        return $pdf->setPaper($customPaper)->stream();

        dd($filters);
    }
    // pdf
    public function generate_ticket_depense($id)
    {
        $entite              = null;
        $details             = null;
        $mode_reglements     = array();
        if (isset($id)) {

            $depense = Outil::getOneItemWithGraphQl('depenses', $id);
            if (isset($depense)) {
                // dd('sf,gvkn');
                if (isset($depense['entite_id'])) {
                    $entite        = Entite::find($depense['entite_id']);
                }
                $details           = DepensePosteDepense::where('depense_id', $depense['id'])->get();
                $mode_reglements   = Modepaiement::query()
                    ->join('paiements', 'paiements.mode_paiement_id', '=', 'mode_paiements.id')
                    ->join('depenses', 'depenses.id', '=', 'paiements.depense_id')
                    ->where('depenses.id', $depense['id'])
                    ->groupBy(['mode_paiements.id', 'paiements.montant', 'paiements.date'])
                    ->selectRaw('mode_paiements.*, paiements.montant as montant, paiements.date as date')
                    ->get();
            }
        }

        $data = array(
            'item'                        => $depense,
            'details'                     => $details,
            'mode_reglements'             => $mode_reglements,
            'entite'                      => $entite
        );

        $pdf = PDF::loadView("pdfs.ticket-depense", $data);

        if (isset($pdf)) {
            $measure = array(0, 0, 170.772, 650.197);
            return $pdf->setPaper($measure, 'orientation')->stream();
        }
    }

    // pdf
    public function generate_ticket_commande($id)
    {
        if (isset($id)) {
            $commande = Commande::find($id);
            if (isset($commande)) {

                $total_livraison = null;
                $filtre_detail = "id:$id";
                $table = null;

                if (isset($commande) && isset($commande->type_commande)) {
                    if ($commande->type_commande->designation == 'à livrer') {
                        $total_livraison = $commande->total_livraison;
                    }

                    if ($commande->type_commande->designation == 'sur place') {
                        $table   = Outil::getTableCommande($id);
                    }

                    $commande    = Outil::regulePaiement($commande->id);
                }

                $commandeProduit = Produit::query()
                    ->join('commande_produits', 'commande_produits.produit_id', '=', 'produits.id')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commandes.id', $commande->id)
                    ->whereNull('commande_produits.menu_commande_id')
                    ->groupBy(['produits.id', 'commande_produits.montant', 'commande_produits.offre'])
                    ->selectRaw("produits.*, count(produits.id) as nombre, commande_produits.montant as montant, commande_produits.offre as offre")
                    ->get();

                $commandeProduit_menu  = CommandeMenu::query()
                    ->join('produits', 'produits.id', '=', 'commande_menus.menu_id')
                    ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id')
                    ->where('commande_id', $commande->id)
                    ->groupBy(['produits.id', 'commande_menus.offre'])
                    ->selectRaw('produits.*, count(produits.id) as nb_menu,commande_menus.offre as offre ')->get();

                $montant_total_offert = Commandeproduit::query()
                    ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commandes.id', $commande->id)
                    ->where('commande_produits.offre', 1)
                    ->selectRaw('SUM(commande_produits.montant) as montant_total_offre')
                    ->first();

                $montant_total_offert_menu = CommandeMenu::where('commande_menus.commande_id', $commande->id)
                    ->where('commande_menus.offre', true)
                    ->selectRaw('sum(commande_menus.montant) as montant_offert_menu')
                    ->first();

                $montant_offert                  = 0;
                $montant_total_ttc_apaye         = 0;

                if (isset($montant_total_offert->montant_total_offre)) {
                    $montant_offert           = $montant_total_offert->montant_total_offre;
                }
                if (isset($montant_total_offert_menu->montant_offert_menu)) {
                    $montant_offert           += $montant_total_offert_menu->montant_offert_menu;
                }

                if (isset($commande->restant_payer)) {
                    $montant_total_ttc_apaye          = Outil::formatPrixToMonetaire($commande->restant_payer, true);
                }
                $montant_offert                   = Outil::formatPrixToMonetaire($montant_offert, true);

                $mode_reglements = Modepaiement::query()
                    ->join('paiements', 'paiements.mode_paiement_id', '=', 'mode_paiements.id')
                    ->join('commandes', 'commandes.id', '=', 'paiements.commande_id')
                    ->where('commandes.id', $commande->id)
                    ->groupBy('mode_paiements.id', 'paiements.montant')
                    ->selectRaw('mode_paiements.*, paiements.montant as montant')
                    ->get();

                $client = null;
                if (isset($commande->client_id)) {
                    $client = $commande->client;
                }

                $date      =  Outil::resolveAllDateCompletFR($commande->date, true);
                $item = array();

                $data = array(
                    'item'                        => $item,
                    'details'                     => $commandeProduit,
                    'detailsmenu'                 => $commandeProduit_menu,
                    'commande'                    => $commande,
                    'montant_total_ttc_apaye'     => $montant_total_ttc_apaye,
                    'montant_total_offert'        => $montant_offert,
                    'date_commande'               => $date,
                    'mode_reglements'             => $mode_reglements,
                    'client'                      => $client,
                    'total_livraison'             => $total_livraison,
                    'table'                       => $table
                );

                $pdf = PDF::loadView("pdfs.ticket-commande", $data);
            }
        }

        $measure = array(0, 0, 170.772, 650.197);
        return $pdf->setPaper($measure, 'orientation')->stream();
    }
    public function generate_pdf_proposition_commerciale($id)
    {
        $data = self::outil_proposition_commerciale($id);

        //        $pdf = \App::make('dompdf.wrapper');
        //        $pdf->getDomPDF()->set_option("enable_php", true);
        //        $pdf->loadView('pdfs.proposition-commerciale', $data);
        //        return $pdf->stream('proposition-commerciale.pdf');
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'proposition-commerciale', $customPaper);
        //        $pdf = \PDF::loadView('pdfs.proposition-commerciale', $data);
        //        return $pdf->stream();
    }
    public function generate_pdf_proposition_commerciale_interne($id)
    {
        $data = self::outil_proposition_commerciale($id, true);
        //        $pdf = \App::make('dompdf.wrapper');
        /* Careful: use "enable_php" option only with local html & script tags you control.
        used with remote html or scripts is a major security problem (remote php injection) */
        //        $pdf->getDomPDF()->set_option("enable_php", true);
        //        $pdf->loadView('pdfs.proposition-commerciale', $data);
        //        return $pdf->stream('proposition-commerciale.pdf');

        //        $pdf = \PDF::loadView('pdfs.proposition-commerciale', $data);
        //        return $pdf->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'proposition-commerciale', $customPaper);
    }

    public function outil_proposition_commerciale($id, $interne  = false)
    {
        $user                           = Auth::user();
        $data                           = [];
        $proposition_commerciale        = Outil::getOneItemWithGraphQl('propositioncommerciales', $id);
        $titre                          = null;

        if (isset($proposition_commerciale) && isset($proposition_commerciale['id'])) {
            $proforma = Proforma::where('id', $proposition_commerciale['proforma_id'])->first();

            $famille_option_materiel_traiteur    = Famille::query()->where('designation', 'Traiteur matriel')->first();
            $proposition_commerciale_famille = PropositionCommercialeFamille::where('proposition_commericale_id', $proposition_commerciale['id']);

            $proposition_commerciale_famille_option_materiel = PropositionCommercialeFamille::where('proposition_commericale_id', $proposition_commerciale['id'])
                ->join('familles', 'familles.id', '=', 'proposition_commericale_familles.famille_id')
                ->where('familles.id', $famille_option_materiel_traiteur->id)
                ->groupBy('proposition_commericale_familles.id')
                ->selectRaw('proposition_commericale_familles.*')->get();

            if (isset($proposition_commerciale_famille)) {

                $proposition_commerciale_famille =  $proposition_commerciale_famille
                    ->join('familles', 'familles.id', '=', 'proposition_commericale_familles.famille_id')
                    ->whereNotIn('familles.id', [$famille_option_materiel_traiteur->id])
                    ->groupBy('proposition_commericale_familles.id')
                    ->selectRaw('proposition_commericale_familles.*')->get();
            } else {
                $proposition_commerciale_famille = $proposition_commerciale_famille->get();
            }
            $dateDebut    =  Outil::resolveAllDateCompletFR($proforma->date_debut_evenement);
            $dateFin      =  Outil::resolveAllDateCompletFR($proforma->date_debut_fin);
            $date         =  Outil::resolveAllDateCompletFR(now(), false);
            if (isset($proforma->client)) {
                if ($proforma->client->civilite == 'Mr') {
                    $civilite   = 'Monsieur';
                } else if ($proforma->client->civilite == 'Mr') {
                    $civilite  = 'Madame';
                } else {
                    $civilite = '';
                }
            }
            if (isset($proposition_commerciale['remise'])) {
                $montant_remise                     = ($proposition_commerciale['montant_par_personne'] * $proposition_commerciale['remise']) / 100;
                $montant_apres_remise               = $proposition_commerciale['montant_par_personne'] - $montant_remise;
            }
            if (isset($proposition_commerciale['categorie_service_id'])) {
                $categorie    = CategorieService::find($proposition_commerciale['categorie_service_id']);
                if (isset($categorie)) {
                    $titre       =    $categorie->designation;
                }
            } else {
                $titre       =    $proposition_commerciale['titre'];
            }

            $date_prevu = Outil::resolveAllDateCompletFR($proforma->date_debut_evenement, true);
            $data = array(
                'proforma'                      => $proforma,
                'proposition'                   => $proposition_commerciale,
                'details'                       => $proposition_commerciale_famille,
                'option_materiel'               => $proposition_commerciale_famille_option_materiel,
                'date_debut'                    => $dateDebut,
                'date_fin'                      => $dateFin,
                'civilite'                      => $civilite,
                'date'                          => $date,
                'user'                          => $user,
                'date_prevu'                    => $date_prevu,
                'titre'                         => $titre,
                'montant_apres_remise'          => $montant_apres_remise,
                "interne"                       => $interne
            );
        }
        return $data;
    }

    //---------------------------Etat cloture caisse-------------------
    //---------------------------Etat cloture caisse-------------------
    //---------------------------Etat cloture caisse-------------------
    public function outil_etat_cloture_pdf_vente($filters, $type)
    {
        $user = Auth::user();

        //dd($filters);
        //$filters_exp = explode(',', $filters);
        $array_filter = array();
        // dd($filters_exp);

        if (isset($filters)) {
        }


        $items            = array();
        $ca               = null;
        $ca_non_offerts    = null;
        $approcash        = array();
        $sorties          = array();
        $approcashemetteur = array();
        $entites          = array();
        $depenses         = array();
        $retour           = null;
        $total_depense           = null;
        $total_depense_entite    = array();
        $context    =  null;
        $pdf    =  null;
        $ca_menu    =  null;
        $offert   = null;
        $perte   = 0;
        $conso   = 0;
        $menus    =  null;
        $nomenclatures = null;

        //Filtre
        $caisse           = null;
        $entite_selected  = null;
        $type_commande    = null;
        $tranche_horaire  = null;
        $date_start       = null;
        $date_end         = null;
        $date_brute       = null;
        $solde_caisse     = 0;
        $palmares         = null;
        //Pour cloture hebdomadaire
        $jours = null;

        //Palmares
        $produits_conso             = array();
        $ca_sur_place               = null;
        $ca_emporter                = null;
        $ca_livraison               = null;
        $nombre_couvert             = null;
        $nombre_emporter            = null;
        $nombre_livraison           = null;

        if ($type == 'vente-offert-perte') {

            //$data           = Outil::getAllItemsWithGraphQl("autres", $filters);
            $attributs  = 'id,designation,montant_vente_famille,montant_perte_famille,montant_offert_famille,montant_conso_famille,produits_vente{id,designation,quantite,montant},produits_offert{id,designation,quantite,montant},produits_perte{id,designation,quantite,montant},produits_conso{id,designation,quantite,montant}';

            $attributs_menu  = 'id,designation,montant_vente,montant_perte,montant_offert,montant_conso,quantite_vente,quantite_offert,quantite_perte,quantite_conso';
            $data           = Outil::getAllItemsWithGraphQl("familles", $filters, $attributs);
            $menus          = Outil::getAllItemsWithGraphQl("menus", $filters, $attributs_menu);
            //$menus          = null;

            // dd($filters);

            $attributs_nomenclature  = 'id,designation,montant_vente,montant_perte,montant_offert,montant_conso';

            $nomenclatures     = Outil::getAllItemsWithGraphQl("nomenclatures", $filters, $attributs_nomenclature);

            $filters_autres_commande    = $filters . ',designation:' . '"commande"';
            $attributs_autres           = 'total_commandes_serveur,total_commandes_offert,total_commandes_perte,total_commandes_conso_interne';
            $autres                     = Outil::getAllItemsWithGraphQl("autres", $filters_autres_commande);

            $attributs_recap            = 'id,designation,montant_vente,nombre_vente';

            $recap                      = Outil::getAllItemsWithGraphQl("typecommandes", $filters, $attributs_recap);

            if (isset($recap)) {
                $surplace   = Typecommande::surplace();
                $emporter   = Typecommande::emporter();
                $livraison  = Typecommande::livraison();
                foreach ($recap as $key => $value) {

                    if (isset($surplace)) {
                        if ($surplace->designation == $value['designation']) {
                            $ca_sur_place   = $value['montant_vente'];
                            $nombre_couvert = $value['nombre_vente'];
                        }
                    }
                    if (isset($emporter)) {
                        if ($emporter->designation == $value['designation']) {
                            $ca_emporter     = $value['montant_vente'];
                            $nombre_emporter = $value['nombre_vente'];
                        }
                    }
                    if (isset($livraison)) {
                        if ($livraison->designation == $value['designation']) {
                            $ca_livraison     = $value['montant_vente'];
                            $nombre_livraison = $value['nombre_vente'];
                        }
                    }

                    $couvert_moyen = 0;
                    $panier_moyen_livraison = 0;
                    $panier_moyen_emporter = 0;
                    if ($nombre_couvert > 0) {
                        $couvert_moyen = round($ca_sur_place / $nombre_couvert, 2);
                    }
                    if ($nombre_livraison > 0) {
                        $panier_moyen_livraison = round($ca_livraison / $nombre_livraison, 2);
                    }
                    if ($ca_emporter > 0) {
                        $panier_moyen_emporter = round($ca_emporter / $nombre_emporter, 2);
                    }
                }
            }

            //dd($recap);

            if (isset($autres) && count($autres) > 0) {
                $ca    = isset($autres[0]['total_commandes_serveur'])       ? $autres[0]['total_commandes_serveur']       : 0;
                $conso = isset($autres[0]['total_commandes_conso_interne']) ? $autres[0]['total_commandes_conso_interne'] : 0;
                $perte = isset($autres[0]['total_commandes_perte']) ? $autres[0]['total_commandes_perte'] : 0;
                $offert = isset($autres[0]['total_commandes_offert']) ? $autres[0]['total_commandes_offert'] : 0;

                $date_start              = isset($autres[0]['date_start'])        ? $autres[0]['date_start'] : null;
                $date_end                = isset($autres[0]['date_end'])          ? $autres[0]['date_end']   : null;
                $entite_selected         = isset($autres[0]['entite_selected'])   ? $autres[0]['entite_selected']   : null;
                $type_commande           = isset($autres[0]['type_commande'])     ? $autres[0]['type_commande']   : null;
                $tranche_horaire         = isset($autres[0]['tranche_horaire'])   ? $autres[0]['tranche_horaire']   : null;
            }
            $date_brute        = $data;
            $pdf  = 'etatcloturecaissevente';


            if (isset($data) && count($data) > 0) {

                $retour = array(
                    'item'                          => 'Cloture caisse',
                    'data'                          => $data,
                    'ca'                            => $ca,
                    'pdf'                           => $pdf,
                    "produits_conso"                => $produits_conso,
                    'menus'                         => $menus,
                    'nomenclatures'                 => $nomenclatures,
                    'conso'                         => $conso,
                    'perte'                         => $perte,
                    'offert'                        => $offert,
                    'date_debut'                    => $date_start,
                    'date_fin'                      => $date_end,
                    'entite_filter'                 => $entite_selected,
                    'type_commande_filter'          => $type_commande,
                    'tranche_horaire_filter'        => $tranche_horaire,
                    'ca_sur_place'                  => $ca_sur_place,
                    'ca_emporter'                   => $ca_emporter,
                    'ca_livraison'                  => $ca_livraison,
                    'nombre_couvert'                => $nombre_couvert,
                    'nombre_emporter'               => $nombre_emporter,
                    'nombre_livraison'              => $nombre_livraison,
                    'couvert_moyen'                 => $couvert_moyen,
                    'panier_moyen_livraison'        => $panier_moyen_livraison,
                    'panier_moyen_emporter'         => $panier_moyen_emporter
                );

                //dd($retour);
            }
        } else if ($type == 'cloturehebdomadaire') {
            $data           = Outil::getAllItemsWithGraphQl("autres", $filters);
            $date_brute     = $data;

            if (isset($data) && count($data) > 0) {

                if (isset($data[0]["cloture_hebdomadaire"])) {
                    $cloture_hebdomadaire  = json_decode($data[0]["cloture_hebdomadaire"]);
                    // dd($cloture_hebdomadaire);
                    //                    $ca  = json_decode($data[0]["ca"]);
                    //                    $ca_liquide  = json_decode($data[0]["ca_liquide"]);
                    //                    $ca_solide  = json_decode($data[0]["ca_solide"]);
                    //dd($cloture_hebdomadaire);
                    $jours                  = $cloture_hebdomadaire->jours;
                    $cv                     = $cloture_hebdomadaire->nbre_couvert;
                    $livraisons             = $cloture_hebdomadaire->nbre_livraison;
                    $emportes               = $cloture_hebdomadaire->nbre_a_emporter;
                    $offerts                = $cloture_hebdomadaire->ca_total_offert;
                    $ventes                 = $cloture_hebdomadaire->ca_total_non_offert;
                    $manquants              = $cloture_hebdomadaire->manquant;
                    $envaissements          = $cloture_hebdomadaire->encaissements;
                    $billetages             = $cloture_hebdomadaire->billetages;

                    //   dd($billetages);

                    $items_jours            = array();
                    $items_cv               = array();
                    $items_livraison        = array();
                    $items_emporter         = array();
                    $items_offert           = array();
                    $items_vente            = array();
                    $items_manquant         = array();
                    $items_envaissement     = array();
                    $items_billetages       = array();
                    //Les jours
                    foreach ($jours as $key => $v) {
                        $j = array(
                            "date_fr" => $v->date_fr,
                            "date" => $v->date,
                        );
                        array_push($items_jours, $j);
                    }
                    //Les couvertsd
                    foreach ($cv as $key => $v) {
                        $couvert = array(
                            "date" => $v->date,
                            "total" => $v->total,
                        );
                        array_push($items_cv, $couvert);
                    }

                    //Les livraison
                    foreach ($livraisons as $key => $liv) {
                        $lvraison = array(
                            "date" => $liv->date,
                            "total" => $liv->total,
                        );
                        array_push($items_livraison, $lvraison);
                    }
                    //Les emportes
                    foreach ($emportes as $key => $emp) {
                        $emporte = array(
                            "date" => $emp->date,
                            "total" => $emp->total,
                        );
                        array_push($items_emporter, $emporte);
                    }
                    //Les offerts
                    foreach ($offerts as $key => $of) {
                        $offert = array(
                            "date" => $of->date,
                            "total" => $of->total,
                        );
                        array_push($items_offert, $offert);
                    }
                    //Les ventes
                    foreach ($ventes as $key => $vent) {
                        $vente = array(
                            "date" => $vent->date,
                            "total" => $vent->total,
                        );
                        array_push($items_vente, $vente);
                    }
                    //Les manquants
                    foreach ($manquants as $key => $m) {
                        $mqt = array(
                            "date" => $m->date,
                            "total" => $m->total,
                        );
                        array_push($items_manquant, $mqt);
                    }

                    $mode_paiements = Modepaiement::query()->get();

                    // Les encaissements
                    $enc_modes = array();
                    foreach ($envaissements as $key => $enc) {


                        $modepaiements = $enc->modepaiements;
                        foreach ($modepaiements as $key => $mp) {
                            array_push($enc_modes, array(
                                "date"          => $mp->date,
                                "modepaiement"  => $mp->modepaiement,
                                "total"         => $mp->total,
                            ));
                        }

                        array_push($items_envaissement, array(
                            "date" => $enc->date,
                            "date_fr" => $enc->date_fr
                        ));
                    }
                    // Les billetages



                    foreach ($billetages as $key => $bil) {
                        array_push($items_billetages, array(
                            "typebillet"          => $bil->typebillet,
                            "nombre"              => $bil->nombre,
                            "total"               => $bil->total,
                        ));
                    }

                    //  dd($billetages);
                    $retour = array(
                        'item'                          => '',
                        'data'                          => $data,
                        "jours"                         => $items_jours,
                        "couverts"                      => $items_cv,
                        "livraisons"                    => $items_livraison,
                        "emportes"                      => $items_emporter,
                        "offerts"                       => $items_offert,
                        "ventes"                        => $items_vente,
                        "manquants"                     => $items_manquant,
                        "encaissements"                 => $items_envaissement,
                        "mode_paiements"                => $mode_paiements,
                        "mode_paiement_encaissements"   => $enc_modes,
                        "billetages"                    => $items_billetages,
                    );
                }
            }
        } else if ($type == 'depense') {
            $items           = Outil::getAllItemsWithGraphQl("reglements", $filters);
            $data            = $items;
            $date_brute      = $data;

            $retour = array(
                'item'                          => 'Dépense',
                'data'                          => $data,
            );
        } else if ($type == 'depensecaisse') {

            $data                   = Outil::getAllItemsWithGraphQl("autres", $filters);
            $date_brute             = $data;
            $date_debut             = null;
            $date_fin               = null;
            $fournisseur            = null;
            $caisse                 = null;
            $solde_caisse           = 0;
            $date_veille            = null;
            $entites_selected       = null;


            if (isset($data) && count($data) > 0) {

                if (isset($data[0]['entites_select'])) {
                    $entites_selected   = $data[0]['entites_select'];
                }
                if (isset($data[0]["depense_caisse"])) {
                    $depenses_caisse  = json_decode($data[0]["depense_caisse"]);
                    $total_depense    = $depenses_caisse->total_depense;

                    $caisse           = isset($depenses_caisse->caisse) ? $depenses_caisse->caisse : null;
                    $fournisseur      = isset($depenses_caisse->fournisseur) ? $depenses_caisse->fournisseur : null;
                    $date_debut       = isset($depenses_caisse->date_debut) ? $depenses_caisse->date_debut : null;
                    $date_fin         = isset($depenses_caisse->date_fin) ? $depenses_caisse->date_fin : null;
                    $depense_caisse_veille = null;
                    $date_veille_start  = null;
                    $date_veille_end    = null;
                    $date_veille        = null;

                    $solde_caisse     = isset($depenses_caisse->solde_caisse) ? $depenses_caisse->solde_caisse : null;


                    if (isset($solde_caisse)) {

                        if (isset($solde_caisse->date_debut)) {
                            $date_veille =  date('Y-m-d', strtotime($solde_caisse->date_debut . ' - 1 days')); // On enleve 1 jour

                            //dd($solde_caisse->date_debut . ' '. $date_veille);
                            if (isset($date_veille)) {
                                $date_veille_start               = '2020-01-01' . ' 00:00:00';
                                $date_veille_end                 = $date_veille . ' 23:59:59';
                            }
                        }

                        //dd($date_veille_start,$date_veille_end);


                        //dd($date_veille_start, $date_veille_end, $solde_caisse->caisse_id);
                        if (isset($solde_caisse->caisse_id) && isset($date_veille)) {

                            $solde = Outil::donneSoldeCalculei($solde_caisse->caisse_id, false, "societefacturation", $date_veille_start, $date_veille_end);
                            //dd($date_veille_start, $date_veille_end);
                            //dd(round($solde));

                        }
                        if (isset($solde)) {
                            $solde_caisse  = $solde;
                        }
                    }

                    // dd($depenses_caisse);

                    //Restructurer les appros de la caisse

                    foreach ($depenses_caisse->approcahs as $key => $v) {

                        $app = array(
                            "motif" => $v->motif,
                            "montant" => $v->montant,
                            "date"    => Outil::resolveAllDateCompletFR($v->date, true)
                        );
                        array_push($approcash, $app);
                    }

                    //Restructurer les appros sortie de la caisse
                    if (isset($depenses_caisse->approcahs_emetteur)) {
                        foreach ($depenses_caisse->approcahs_emetteur as $key => $v) {

                            $app = array(
                                "motif" => $v->motif,
                                "montant" => $v->montant,
                                "date"    => Outil::resolveAllDateCompletFR($v->date, true)
                            );
                            array_push($approcashemetteur, $app);
                        }
                    }

                    //Restructurer les sorties de la caisse
                    if (isset($depenses_caisse->sortie)) {
                        foreach ($depenses_caisse->sortie as $key => $v) {

                            $app = array(
                                "motif" => $v->motif,
                                "montant" => $v->montant,
                                "date"    => Outil::resolveAllDateCompletFR($v->date, true)
                            );
                            array_push($sorties, $app);
                        }
                    }

                    // dd($depenses_caisse->reglements);


                    foreach ($depenses_caisse->reglements as $key => $v) {

                        $depense                = Depense::find($v->depense->id);

                        $be_id                  = null;
                        $fournisseur_depense    = null;
                        if (isset($depense)) {
                            $be_id                            = $depense->be_id;
                            if (!isset($be_id)) {
                                if (isset($depense->fournisseur_id)) {
                                    $fournisseur_depense      = Fournisseur::find($depense->fournisseur_id);
                                }
                            }
                        }


                        $app                    = array(
                            "motif"             => $v->depense->motif,
                            "entite_id"         => $v->depense->entite_id,
                            "montant"           => $v->montant,
                            "date"              => Outil::resolveAllDateCompletFR($v->date, true),
                            "fournisseur"       => $fournisseur_depense,
                            "be_id"             => $be_id
                        );
                        array_push($depenses, $app);
                    }

                    foreach ($depenses_caisse->entites as $key => $v) {

                        $app = array(
                            "designation" => $v->designation,
                            "id"          => $v->id
                        );
                        array_push($entites, $app);
                    }

                    //Total depense par entite

                    foreach ($depenses_caisse->totaux_entites as $key => $v) {

                        $app = array(
                            "designation"           => $v->entite,
                            "id"                    => $v->entite_id,
                            "total_global"          => $v->total_global,
                            "total_compta"          => $v->total_compta,
                            "total_hors_compta"     => $v->total_hors_compta,

                        );
                        array_push($total_depense_entite, $app);
                    }
                }
            }
            // dd($caisse);
            $data  = array(
                "approcash"             => $approcash,
                "entites"               => $entites,
                "depenses"              => $depenses,
                "nbentites"             => count($entites),
                "total_depense"         => $total_depense,
                "total_depense_entite"  => $total_depense_entite,
                "caisse"                => $caisse,
                "date_debut"            => $date_debut,
                "date_fin"              => $date_fin,
                "fournisseur"           => $fournisseur,
                "solde_caisse"          => $solde_caisse,
                "date_veille"           => Outil::resolveAllDateCompletFR($date_veille, false),
                "approemetteur"         => $approcashemetteur,
                "sorties"               => $sorties,
                "entites_select"        => $entites_selected
            );

            // dd($data);

            $retour = array(
                'item'                          => 'Dépense caisse',
                'data'                          => $data,
            );
        } else if ($type == 'recap-cloture-caisse') {
            // dd($filters);
            $data           = Outil::getAllItemsWithGraphQl("autres", $filters);
            $date_brute     = $data;

            // dd($data);
            if (isset($data) && count($data) > 0) {

                if (isset($data[0]['context'])) {
                    $context  = $data[0]['context'];
                }
                if (isset($data[0]['pdf'])) {
                    $pdf  = $data[0]['pdf'];
                }

                if (isset($data[0]["recap_cloture_caisse"])) {

                    $recap  = json_decode($data[0]["recap_cloture_caisse"]);

                    if (isset($recap)) {
                        $dataRwrite  = array(
                            "ca_non_offerts"      => $recap->ca_non_offerts,
                            "ca_sur_place"        => $recap->ca_sur_place,
                            "nombre_de_couverts"        => $recap->nombre_de_couverts,
                            "couvert_moyen"        => $recap->couvert_moyen,
                            "ca_a_livrer"        => $recap->ca_a_livrer,
                            "nombre_livraison"        => $recap->nombre_livraison,
                            "panier_moyen_livraison"        => $recap->panier_moyen_livraison,
                            "ca_a_emporter"        => $recap->ca_a_emporter,
                            "nombre_emporter"        => $recap->nombre_emporter,
                            "panier_moyen_emporter"        => $recap->panier_moyen_emporter,
                        );

                        $retour = array(
                            'item'                          => 'Recap cloture caisse',
                            'data'                          => $dataRwrite,
                            "ca_non_offerts"      => $recap->ca_non_offerts,
                            "ca_sur_place"        => $recap->ca_sur_place,
                            "nombre_de_couverts"        => $recap->nombre_de_couverts,
                            "couvert_moyen"        => $recap->couvert_moyen,
                            "ca_a_livrer"        => $recap->ca_a_livrer,
                            "nombre_livraison"        => $recap->nombre_livraison,
                            "panier_moyen_livraison"        => $recap->panier_moyen_livraison,
                            "ca_a_emporter"        => $recap->ca_a_emporter,
                            "nombre_emporter"        => $recap->nombre_emporter,
                            "panier_moyen_emporter"        => $recap->panier_moyen_emporter,
                        );
                    }
                }
            }
        }
        if ($type !== 'depense' && isset($date_brute) && count($date_brute) > 0) {

            $retour['caisse']            = isset($date_brute[0]['caisse']) ? $date_brute[0]['caisse'] : null;
            $retour['entite_selected']   = isset($date_brute[0]['entite_selected']) ? $date_brute[0]['entite_selected'] : null;
            $retour['type_commande']     = isset($date_brute[0]['type_commande']) ? $date_brute[0]['type_commande'] : null;
            $retour['tranche_horaire']   = isset($date_brute[0]['tranche_horaire']) ? $date_brute[0]['tranche_horaire'] : null;
            $retour['date_start']        = isset($date_brute[0]['date_start']) ? $date_brute[0]['date_start'] : null;
            $retour['date_end']          = isset($date_brute[0]['date_end']) ? $date_brute[0]['date_end'] : null;
        }

        $article        = null;

        $entite         = null;
        $fournisseur    = null;

        return $retour;
    }

    public function generate_pdf_etatcloturecaisse_vente($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_etat_cloture_pdf_vente($filters, 'vente-offert-perte');

        //dd($data);

        if (isset($data)) {
            //           $pdf = \PDF::loadView($data['pdf'], $data);
            //           $customPaper = array(0,0,780,1800);
            // $pdf->Cell(0, 5, "Page " . 1 . "/5", 0, 1);
            //           return $pdf->setPaper($customPaper)->stream();
            $customPaper = array(0, 0, 780, 900);
            return self::pdfnumberpage($data, $data['pdf'], $customPaper);
        }
    }

    public function generate_pdf_etatcloturecaisse_offert($filters = null)
    {

        $data  = self::outil_etat_cloture_pdf_vente($filters, 'offert');

        if (isset($data)) {
            //            $pdf = \PDF::loadView('pdfs.etatcloturecaisseoffert', $data);
            //            $customPaper = array(0, 0, 780, 900);
            //            return $pdf->setPaper($customPaper)->stream();
            $customPaper = array(0, 0, 780, 900);
            return self::pdfnumberpage($data, 'etatcloturecaisseoffert', $customPaper);
        }
    }

    public function generate_pdf_etatcloturecaisse_perte($filters = null)
    {

        $data  = self::outil_etat_cloture_pdf_vente($filters, 'perte');

        if (isset($data)) {
            $pdf = \PDF::loadView('pdfs.etatcloturecaisseperte', $data);
            $customPaper = array(0, 0, 780, 900);
            return $pdf->setPaper($customPaper)->stream();
        }
    }

    public function generate_pdf_etatcloturecaisse_depense($filters = null)
    {

        $data  = self::outil_etat_cloture_pdf_vente($filters, 'depense');

        if (isset($data)) {
            //            $pdf = \PDF::loadView('pdfs.etatcloturecaissedepense', $data);
            //            $customPaper = array(0, 0, 780, 900);
            //            return $pdf->setPaper($customPaper)->stream();
            $customPaper = array(0, 0, 780, 900);
            return self::pdfnumberpage($data, 'etatcloturecaissedepense', $customPaper);
        }
    }

    public function generate_pdf_etatdepensecaisse($filters = null)
    {
        //dd('dfd');

        $data  = self::outil_etat_cloture_pdf_vente($filters, 'depensecaisse');
        // dd($data['data']['caisse']->designation);
        if (isset($data)) {
            //            $pdf = \PDF::loadView('pdfs.etatdepensecaisse', $data);
            //            $customPaper = array(0, 0, 780, 900);
            //            return $pdf->setPaper($customPaper)->stream();
            $customPaper = array(0, 0, 780, 900);
            return self::pdfnumberpage($data, 'etatdepensecaisse', $customPaper);
        }
    }

    //Outil pour recap depense par fournisseur
    public function outil_recap_depense_fournisseur($filters = null, $type = null)
    {

        //dd(explode(',',$filters));

        $entites                           = null;
        $societe_facturations              = null;
        $data                              = null;
        $total                             = 0;

        $attributs                         = Outil::$queries['fournisseurs'];

        if (isset($attributs) && $type  == 'entite') {
            $attributs                     = $attributs . ",entite_depenses{id,designation,montant}";
            $entites                       = Entite::query()->get();
        }

        if (isset($attributs) && $type  == 'societe_facturation') {
            $attributs                     = $attributs . ",societe_facturations{id,denominationsociale,montant}";
            $societe_facturations          = Societefacturation::query()->get();
        }

        if (isset($attributs)) {
            $attributs                     = $attributs . 'total_depense' . ',date_start_filter,date_end_filter';
        }

        $fournisseurs                      = Outil::getAllItemsWithGraphQl('fournisseurs', $filters, $attributs);

        if (isset($fournisseurs)) {
            foreach ($fournisseurs as $key => $four) {
                if (isset($four)) {
                    $total  += $four['total_depense'];
                }
            }
            if (isset($fournisseurs) && $type == 'entite') {
                $data             = array(
                    "data"        => $fournisseurs,
                    "entites"     => $entites,
                    "total"       => $total
                );
            }
            if (isset($fournisseurs) && $type == 'societe_facturation') {
                $data                          = array(
                    "data"                     => $fournisseurs,
                    "societe_facturations"     => $societe_facturations,
                    "total"       => $total
                );
            }
        }


        return $data;
    }

    //Outil pour recap depense par post de depense
    public function outil_recap_depense_poste_depense($filters = null, $type = null)
    {

        //dd(explode(',',$filters));

        $entites                           = null;
        $societe_facturations              = null;
        $data                              = null;
        $total                             = 0;

        $attributs                         = Outil::$queries['postedepenses'];

        if (isset($attributs) && $type  == 'entite') {
            $attributs                     = $attributs . ",entite_depenses{id,designation,montant}";
            $entites                       = Entite::query()->get();
        }

        if (isset($attributs) && $type  == 'societe_facturation') {
            $attributs                     = $attributs . ",societe_facturations{id,denominationsociale,montant}";
            $societe_facturations          = Societefacturation::query()->get();
        }

        if (isset($attributs)) {
            $attributs                     = $attributs . 'total_depense' . ',date_start_filter,date_end_filter';
        }

        $poste_depenses                    = Outil::getAllItemsWithGraphQl('postedepenses', $filters, $attributs);

        if (isset($poste_depenses)) {
            foreach ($poste_depenses as $key => $dept) {
                if (isset($dept)) {
                    $total  += $dept['total_depense'];
                }
            }

            if ($type == 'entite') {
                $data             = array(
                    "data"        => $poste_depenses,
                    "entites"     => $entites,
                    "total"       => $total
                );
            }
            if ($type == 'societe_facturation') {
                $data                          = array(
                    "data"                     => $poste_depenses,
                    "societe_facturations"     => $societe_facturations,
                    "total"       => $total
                );
            }
        }


        // dd($data);

        return $data;
    }

    //Outil pour recap depense par Categorie depense
    public function outil_recap_depense_categorie_depense($filters = null)
    {

        //dd(explode(',',$filters));

        $entites                           = null;
        $societe_facturations              = null;
        $data                              = null;
        $total                             = 0;

        $attributs                         = Outil::$queries['categoriedepenses'];

        $attributs                     = $attributs . ",entite_depenses{id,designation,montant}";
        $entites                       = Entite::query()->get();

        $attributs                     = $attributs . ",societe_facturations{id,denominationsociale,montant}";
        $societe_facturations          = Societefacturation::query()->get();

        if (isset($attributs)) {
            $attributs                     = $attributs . 'total_depense' . ',date_start_filter,date_end_filter';
        }

        $categorie_depenses                = Outil::getAllItemsWithGraphQl('categoriedepenses', $filters, $attributs);


        if (isset($categorie_depenses)) {

            foreach ($categorie_depenses as $key => $dept) {
                if (isset($dept)) {
                    $total  += $dept['total_depense'];
                }
            }
            $data             = array(
                "data"                     => $categorie_depenses,
                "entites"                  => $entites,
                "societe_facturations"     => $societe_facturations,
                "total"                    => $total
            );
        }

        return $data;
    }

    //Outil pour recap depense par post de depense
    public function outil_recap_depense_compta($filters = null, $type = null)
    {

        //dd(explode(',',$filters));

        $entites                           = null;
        $societe_facturations              = null;
        $data                              = null;
        $total                             = 0;

        $attributs                         = Outil::$queries['entites'];


        //        if(isset($attributs) && $type  == 'entite'){
        //            $attributs                     = $attributs.",entite_depenses{id,designation,montant}";
        //            $entites                       = Entite::query()->get();
        //        }

        //        if(isset($attributs) && $type  == 'societe_facturation'){
        //            $attributs                     = $attributs.",societe_facturations{id,denominationsociale,montant}";
        //            $societe_facturations          = Societefacturation::query()->get();
        //        }

        if (isset($attributs)) {
            $attributs                     = $attributs . ",fournisseurs{id,designation,code,total_depense,total_paye_depense,poste_depenses{poste_depense{designation},depense{id,motif,date_piece_fr,deja_paye,numero_piece},montant_ttc,montant,montant_tva,tva}}";
            $attributs                     = $attributs . ',date_start_filter,date_end_filter,total_depense,total_paye_depense';
        }

        $entites                           = Outil::getAllItemsWithGraphQl('entites', $filters, $attributs);

        if (isset($entites)) {
            //            foreach ($entites as $key=>$dept)
            //            {
            //                if(isset($dept)){
            //                    $total  += $dept['total_depense'];
            //                }
            //            }
            $data             = array(
                "data"        => $entites,
                "total"       => $total
            );
        }

        // dd($data);

        return $data;
    }

    //Recapitualtif depense par fournisseur /Entite
    public function generate_pdf_etatdepense_recap_entite($filters = null)
    {


        $data  = self::outil_recap_depense_fournisseur($filters, 'entite');
        //        $pdf = \PDF::loadView('pdfs.etat-depense-fournisseur-recap-entite', $data);
        //        $customPaper = array(0, 0, 780, 900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'etat-depense-fournisseur-recap-entite', $customPaper);
    }

    public function generate_excel_etatdepense_recap_entite($filters  = null)
    {


        $data  = self::outil_recap_depense_fournisseur($filters, 'entite');
        return Excel::download(new EtatDepenseFournisseurRecapEntiteExport($data), 'depensefournisseurrecapentite.xlsx');
    }

    //Recapitualtif depense par fournisseur /Societe facturation
    public function generate_pdf_etatdepense_recap_societe($filters = null)
    {

        $data  = self::outil_recap_depense_fournisseur($filters, 'societe_facturation');
        //        $pdf = \PDF::loadView('pdfs.etat-depense-fournisseur-recap-societe_facturation', $data);
        //        $customPaper = array(0, 0, 780, 900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'etat-depense-fournisseur-recap-societe_facturation', $customPaper);
    }

    public function generate_excel_etatdepense_recap_societe($filters = null)
    {

        $data  = self::outil_recap_depense_fournisseur($filters, 'societe_facturation');
        return Excel::download(new EtatDepenseFournisseurRecapSocieteExport($data), 'depensefournisseurrecapsocietefacturation.xlsx');
    }

    //Recapitualtif depense par poste de depense /Entite
    public function generate_pdf_etatdepense_recap_post_entite($filters = null)
    {


        $data  = self::outil_recap_depense_poste_depense($filters, 'entite');
        //        $pdf = \PDF::loadView('pdfs.etat-depense-poste-depense-recap-entite', $data);
        //        $customPaper = array(0, 0, 780, 900);
        //        return $pdf->setPaper($customPaper)->stream();
        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'etat-depense-poste-depense-recap-entite', $customPaper);
    }
    public function generate_excel_etatdepense_recap_post_entite($filters  = null)
    {

        $data  = self::outil_recap_depense_poste_depense($filters, 'entite');
        return Excel::download(new EtatDepensePosteDepenseRecapEntiteExport($data), 'depenseposterecapentite.xlsx');
    }

    //Recapitualtif depense par poste de depense /Societe
    public function generate_pdf_etatdepense_recap_post_societe($filters = null)
    {

        $data  = self::outil_recap_depense_poste_depense($filters, 'societe_facturation');
        //        $pdf = \PDF::loadView('pdfs.etat-depense-poste-depense-recap-societe', $data);
        //        $customPaper = array(0, 0, 780, 900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'etat-depense-poste-depense-recap-societe', $customPaper);
    }
    public function generate_excel_etatdepense_recap_post_societe($filters  = null)
    {

        $data  = self::outil_recap_depense_poste_depense($filters, 'societe_facturation');
        return Excel::download(new EtatDepensePosteDepenseRecapSocieteExport($data), 'depenseposterecapsociete.xlsx');
    }

    //Recapitualtif depense par Categorie depense /Entite
    public function generate_pdf_etatdepense_recap_categorie($filters = null)
    {

        $data  = self::outil_recap_depense_categorie_depense($filters);
        //        $pdf = \PDF::loadView('pdfs.etat-depense-categorie-depense-recap-entite', $data);
        //        $customPaper = array(0, 0, 780, 900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'etat-depense-categorie-depense-recap-entite', $customPaper);
    }
    public function generate_excel_etatdepense_recap_categorie($filters  = null)
    {

        $data  = self::outil_recap_depense_categorie_depense($filters);

        return Excel::download(new EtatDepenseCategorieDepenseRecapExport($data), 'depensecategoriedepenserecap.xlsx');
    }

    public function generate_pdf_etatdepense_recap_compta_entite($filters = null)
    {

        $data  = self::outil_recap_depense_compta($filters, 'entite');
        //dd($data);
        //        $pdf = \PDF::loadView('pdfs.etat-depense-compta-recap-entite', $data);
        //        $customPaper = array(0, 0, 780, 900);
        //        return $pdf->setPaper($customPaper)->stream();

        $customPaper = array(0, 0, 780, 900);
        return self::pdfnumberpage($data, 'etat-depense-compta-recap-entite', $customPaper);
    }
    public function generate_excel_etatdepense_recap_compta_entite($filters  = null)
    {

        $data  = self::outil_recap_depense_compta($filters, 'entite');

        return Excel::download(new EtatDepenseComptaRecapEntiteExport($data), 'depensecomptarecapentite.xlsx');
    }

    public function generate_pdf_etatcloturehebdomadairecaisse($filters = null)
    {


        $data  = self::outil_etat_cloture_pdf_vente($filters, 'cloturehebdomadaire');
        if (isset($data)) {
            //            $pdf = \PDF::loadView('pdfs.etatcloturehebdomadaire', $data);
            //            $customPaper = array(0, 0, 1000, 1000);
            //            return $pdf->setPaper($customPaper)->stream();

            $customPaper = array(0, 0, 780, 900);
            return self::pdfnumberpage($data, 'etatcloturehebdomadaire', $customPaper);
        }
    }

    public function generate_pdf_etatcloturecaisse_recap($filters = null)
    {
        $user = Auth::user();

        $data  = self::outil_etat_cloture_pdf_vente($filters, 'recap-cloture-caisse');

        //dd($data);

        if (isset($data)) {
            //            $pdf = \PDF::loadView('pdfs.recapcloturecaisse', $data);
            //            $customPaper = array(0,0,780,900);
            //            return $pdf->setPaper($customPaper)->stream();

            $customPaper = array(0, 0, 780, 900);
            return self::pdfnumberpage($data, 'recapcloturecaisse', $customPaper);
        }
    }

    public function generate_pdf_etatfacturation($filters = null, $interne = false)
    {
        $filters = $filters . ',all:1';
        $data    = self::outil_factureold($filters, $interne, 'all');

        // dd($data);

        if (isset($data)) {
            $pdf  = 'etatfacturation';

            //                $pdf = \PDF::loadView('pdfs.'.$pdf, $data);
            //                $customPaper = array(0,0,1000,950);
            //                return $pdf->setPaper($customPaper)->stream();

            $customPaper = array(0, 0, 1100, 900);
            return self::pdfnumberpage($data, 'etatfacturation', $customPaper);
        }
    }
    public function generate_excel_etatfacturation($filters = null, $interne = false)
    {
        $filters = $filters . ',all:1';
        $data    = self::outil_factureold($filters, $interne, 'all');

        return Excel::download(new FacturationExport($data), 'facturation.xlsx');
    }

    public function generate_excel_transactionproduit($filters = null)
    {
        $data   = self::outil_transactionproduit($filters);

        //dd($data);

        return Excel::download(new TransactionProduitExport($data), 'transactionproduit.xlsx');
    }

    public function outil_transactionproduit($filters)
    {

        $data                = Outil::getAllItemsWithGraphQl('etattransactionproduits', $filters);

        if (isset($data) && count($data) > 0) {
            $date1            = $data[0]['date_start_filter'];
            $date2            = $data[0]['date_end_filter'];
            $produit_filter   = $data[0]['produit_filter'];
            $famille_filter   = $data[0]['famille_filter'];
            $depot_filter     = $data[0]['depot_filter'];

            $output           = [];
            $time             = strtotime($date1);
            $last             = date('m/Y', strtotime($date2));

            do {
                $month = date('m/Y', $time);
                $total = date('t', $time);

                $output[] = [
                    'month' => $month
                ];

                $time = strtotime('+1 month', $time);
            } while ($month != $last);

            $months       = array();
            $totalEntree  = 0;
            $totalSortie  = 0;
            $totalHt  = 0;
            $totalTtc  = 0;

            if (isset($data) && count($data) > 0) {
                foreach ($output as $key => $val) {
                    // dd($val['month']);
                    $ligne = array();
                    foreach ($data as $key => $vall) {
                        if ($val['month'] == $vall['mois']) {
                            if ($vall['type'] == 'entree') {
                                $totalEntree += $vall['quantite'];
                            }
                            if ($vall['type'] == 'sortie') {
                                $totalSortie += $vall['quantite'];
                            }

                            if ($vall['valeur']) {
                                $totalTtc += $vall['valeur'];
                            }
                            if ($vall['valeur_ht']) {
                                $totalHt += $vall['valeur_ht'];
                            }

                            array_push($ligne, array(
                                "date_f"       => $vall['date_fr'],
                                "produit"      => $vall['produit']['designation'],
                                "type"         => $vall['type'],
                                "quantite"     => $vall['quantite'],
                                "observation"  => $vall['observation'],
                                "valeur"       => $vall['valeur'],
                                "valeur_ht"    => $vall['valeur_ht'],
                            ));
                        }
                    }

                    if (count($ligne) > 0) {
                        array_push($months, array(
                            "moi"                => $val['month'],
                            "linges"             => $ligne,
                            "total_entre"        => $totalEntree,
                            "total_sortie"       => $totalSortie,
                            "total_ht"           => $totalHt,
                            "total_ttc"          => $totalTtc,
                        ));
                    }
                }

                $data = array(
                    'item'              => '',
                    'data'              => $months,
                    'produit_filter'    => $produit_filter,
                    'famille_filter'    => $famille_filter,
                    'depot_filter'      => $depot_filter,
                    'date_debut'        => $date1,
                    'date_fin'          => $date2
                );
            }
        }

        return $data;
    }
    public function generate_pdf_transactionproduit($filters = null)
    {
        $data   = self::outil_transactionproduit($filters);

        if (isset($data)) {

            $customPaper = array(0, 0, 780, 900);
            return self::pdfnumberpage($data, 'transactionproduit');
        }

        //        $data                = Outil::getAllItemsWithGraphQl('etattransactionproduits',$filters);
        //
        //        if(isset($data) && count($data) > 0){
        //            $date1            = $data[0]['date_start_filter'];
        //            $date2            = $data[0]['date_end_filter'];
        //            $produit_filter   = $data[0]['produit_filter'];
        //            $famille_filter   = $data[0]['famille_filter'];
        //            $depot_filter     = $data[0]['depot_filter'];
        //
        //            $output           = [];
        //            $time             = strtotime($date1);
        //            $last             = date('m/Y', strtotime($date2));
        //
        //            do {
        //                $month = date('m/Y', $time);
        //                $total = date('t', $time);
        //
        //                $output[] = [
        //                    'month' => $month
        //                ];
        //
        //                $time = strtotime('+1 month', $time);
        //            } while ($month != $last);
        //
        //            $months       = array();
        //            $totalEntree  = 0;
        //            $totalSortie  = 0;
        //
        //            if(isset($data) && count($data) > 0){
        //                foreach ($output as $key=>$val)
        //                {
        //                    // dd($val['month']);
        //                    $ligne = array();
        //                    foreach ($data as $key=>$vall)
        //                    {
        //                        if($val['month'] == $vall['mois']){
        //                            if($vall['type'] =='entree'){
        //                                $totalEntree += $vall['quantite'];
        //                            }
        //                            if($vall['type'] =='sortie'){
        //                                $totalSortie += $vall['quantite'];
        //                            }
        //
        //                            array_push($ligne, array(
        //                                "date_f"       =>$vall['date_fr'],
        //                                "produit"      =>$vall['produit']['designation'],
        //                                "type"         =>$vall['type'],
        //                                "quantite"     =>$vall['quantite'],
        //                                "observation"  =>$vall['observation'],
        //                                "valeur"       =>$vall['valeur'],
        //                                "valeur_ht"    =>$vall['valeur_ht'],
        //                            ));
        //                        }
        //                    }
        //
        //                    if(count($ligne) > 0){
        //                        array_push($months,array(
        //                            "moi"                => $val['month'],
        //                            "linges"             => $ligne,
        //                            "total_entre"        => $totalEntree,
        //                            "total_sortie"       => $totalSortie,
        //                        ));
        //                    }
        //
        //                }
        //
        //                $data = array(
        //                    'item'              => '',
        //                    'data'              => $months,
        //                    'produit_filter'    => $produit_filter,
        //                    'famille_filter'    => $famille_filter,
        //                    'depot_filter'      => $depot_filter,
        //                    'date_debut'        => $date1,
        //                    'date_fin'          => $date2
        //                );
        //        }
        //
        //
        //
        //            if(isset($data)){
        //
        //                $customPaper = array(0,0,780,900);
        //                return self::pdfnumberpage($data,'transactionproduit');
        //            }
        //        }
    }

    public function pdf()
    {
        $data = array();
        $pdf = \App::make('dompdf.wrapper');
        /* Careful: use "enable_php" option only with local html & script tags you control.
        used with remote html or scripts is a major security problem (remote php injection) */
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdfs.invoice', $data);
        return $pdf->stream('invoice.pdf');
    }

    public function pdfnumberpage($data, $page, $customPaper = null)
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdfs.' . $page, $data);
        if (isset($customPaper)) {
            $pdf->setPaper($customPaper);
        }
        return $pdf->stream($page . '.pdf');
    }
}
