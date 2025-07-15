<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\EtatlieuApiController;
use App\Http\Controllers\SignaturePDFController;
use App\Http\Controllers\AppartementApiController;
use App\Http\Controllers\ConstituantApiController;
use App\Http\Controllers\ObservationApiController;
use App\Http\Controllers\InterventionApiController;
use App\Http\Controllers\EtatappartementApiController;
use App\Http\Controllers\EquipementgeneraleApiController;
use App\Http\Controllers\CategorieinterventionApiController;
use App\Http\Controllers\CompositionappartementApiController;
use App\Http\Controllers\Typeapportponctuel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//******** PROPRE A scireyhan**************//
//markme-AJOUT
Route::get('/bar-chart', [ChartController::class, 'barChart']);

Route::get('/', 'HomeController@index');

Route::post('/villa/import', 'VillaController@import');

//entite
Route::get('/entite-affectations-reyhan', 'EntiteController@links');
Route::post('/entite', 'EntiteController@save');
Route::delete('/entite/{id}', 'EntiteController@delete');
Route::post('/entite/import', 'EntiteController@import');
Route::get('/generate-excel-entite', 'PdfExcelController@generate_excel_typeappartement');
Route::get('/generate-pdf-entite', 'PdfExcelController@generate_pdf_typeappartement');

//ilot
Route::post('/ilot', 'IlotController@save');
Route::delete('/ilot/{id}', 'IlotController@delete');
Route::post('/ilot/import', 'IlotController@import');
Route::get('/generate-excel-entite', 'PdfExcelController@generate_excel_typeappartement');
Route::get('/generate-pdf-entite', 'PdfExcelController@generate_pdf_typeappartement');

//type appartement
Route::post('/typeappartement', 'TypeappartementController@save');
Route::post('/typeappartement/statut', 'TypeappartementController@statut');
Route::delete('/typeappartement/{id}', 'TypeappartementController@delete');
Route::post('/typeappartement/import', 'TypeappartementController@import');
Route::get('/generate-excel-typeappartement', 'PdfExcelController@generate_excel_typeappartement');
Route::get('/generate-pdf-typeappartement', 'PdfExcelController@generate_pdf_typeappartement');

//periodicite
Route::post('/periodicite', 'PeriodiciteController@save');
Route::post('/periodicite/statut', 'PeriodiciteController@statut');
Route::delete('/periodicite/{id}', 'PeriodiciteController@delete');
Route::post('/periodicite/import', 'PeriodiciteController@import');

//securite immeuble
Route::post('/securite/import', 'SecuriteController@import');

//type structureimmeuble
Route::post('/structureimmeuble', 'StructureimmeubleController@save');
Route::post('/structureimmeuble/statut', 'StructureimmeubleController@statut');
Route::delete('/structureimmeuble/{id}', 'StructureimmeubleController@delete');
Route::post('/structureimmeuble/import', 'StructureimmeubleController@import');
Route::get('/generate-excel-structureimmeuble', 'PdfExcelController@generate_excel_structureimmeuble');
Route::get('/generate-pdf-structureimmeuble', 'PdfExcelController@generate_pdf_structureimmeuble');


//secteur activite
Route::post('/secteuractivite', 'SecteuractiviteController@save');
Route::post('/secteuractivite/statut', 'SecteuractiviteController@statut');
Route::delete('/secteuractivite/{id}', 'SecteuractiviteController@delete');

//categorie prestataire
Route::post('/categorieprestataire', 'CategorieprestataireController@save');
Route::post('/categorieprestataire/statut', 'CategorieprestataireController@statut');
Route::delete('/categorieprestataire/{id}', 'CategorieprestataireController@delete');
Route::post('/categorieprestataire/import', 'CategorieprestataireController@import');
Route::get('/generate-excel-structureimmeuble', 'PdfExcelController@generate_excel_structureimmeuble');
Route::get('/generate-pdf-structureimmeuble', 'PdfExcelController@generate_pdf_structureimmeuble');

//categorie intervention
Route::post('/categorieintervention', 'CategorieinterventionController@save');
Route::post('/categorieintervention/statut', 'CategorieinterventionController@statut');
Route::delete('/categorieintervention/{id}', 'CategorieinterventionController@delete');
Route::post('/categorieintervention/import', 'CategorieinterventionController@import');
Route::get('/generate-excel-structureimmeuble', 'PdfExcelController@generate_excel_structureimmeuble');
Route::get('/generate-pdf-structureimmeuble', 'PdfExcelController@generate_pdf_structureimmeuble');

//horaire
Route::post('/horaire', 'HoraireController@save');
Route::post('/horaire/statut', 'HoraireController@statut');
Route::delete('/horaire/{id}', 'HoraireController@delete');
Route::post('/horaire/import', 'HoraireController@import');
Route::get('/generate-excel-structureimmeuble', 'PdfExcelController@generate_excel_structureimmeuble');
Route::get('/generate-pdf-structureimmeuble', 'PdfExcelController@generate_pdf_structureimmeuble');


//type assurance
Route::post('/typeassurance', 'TypeassuranceController@save');
Route::post('/typeassurance/statut', 'TypeassuranceController@statut');
Route::delete('/typeassurance/{id}', 'TypeassuranceController@delete');
Route::post('/typeassurance/import', 'TypeassuranceController@import');
Route::get('/generate-excel-typeassurance', 'PdfExcelController@generate_excel_typeassurance');
Route::get('/generate-pdf-typeassurance', 'PdfExcelController@generate_pdf_typeassurance');

//type contrat
Route::post('/typecontrat', 'TypecontratController@save');
Route::post('/typecontrat/statut', 'TypecontratController@statut');
Route::delete('/typecontrat/{id}', 'TypecontratController@delete');
Route::post('/typecontrat/import', 'TypecontratController@import');
Route::get('/generate-excel-typecontrat', 'PdfExcelController@generate_excel_typecontrat');
Route::get('/generate-pdf-typecontrat', 'PdfExcelController@generate_pdf_typecontrat');

//type document
Route::post('/typedocument', 'TypedocumentController@save');
Route::post('/typedocument/statut', 'TypedocumentController@statut');
Route::delete('/typedocument/{id}', 'TypedocumentController@delete');
Route::post('/typedocument/import', 'TypedocumentController@import');
Route::get('/generate-excel-typedocument', 'PdfExcelController@generate_excel_typedocument');
Route::get('/generate-pdf-typedocument', 'PdfExcelController@generate_pdf_typedocument');
//type facture
Route::post('/typefacture', 'TypefactureController@save');
Route::post('/typefacture/statut', 'TypefactureController@statut');
Route::delete('/typefacture/{id}', 'TypefactureController@delete');
Route::post('/typefacture/import', 'TypefactureController@import');
Route::get('/generate-excel-typefacture', 'PdfExcelController@generate_excel_typefacture');
Route::get('/generate-pdf-typefacture', 'PdfExcelController@generate_pdf_typefacture');

//type intervention
Route::post('/typeintervention', 'TypeinterventionController@save');
Route::post('/typeintervention/statut', 'TypeinterventionController@statut');
Route::delete('/typeintervention/{id}', 'TypeinterventionController@delete');
Route::post('/typeintervention/import', 'TypeinterventionController@import');
Route::get('/generate-excel-typeintervention', 'PdfExcelController@generate_excel_typeintervention');
Route::get('/generate-pdf-typeintervention', 'PdfExcelController@generate_pdf_typeintervention');

//rapport intervention
Route::post('/rapportintervention', 'RapportinterventionController@save');
Route::post('/rapportintervention/statut', 'RapportinterventionController@statut');
Route::delete('/rapportintervention/{id}', 'RapportinterventionController@delete');
Route::post('/rapportintervention/import', 'RapportinterventionController@import');

//type locataire
Route::post('/typelocataire', 'TypelocataireController@save');
Route::post('/typelocataire/statut', 'TypelocataireController@statut');
Route::delete('/typelocataire/{id}', 'TypelocataireController@delete');
Route::post('/typelocataire/import', 'TypelocataireController@import');
Route::get('/generate-excel-typelocataire', 'PdfExcelController@generate_excel_typelocataire');
Route::get('/generate-pdf-typelocataire', 'PdfExcelController@generate_pdf_typelocataire');

//type piece
Route::post('/typepiece', 'TypepieceController@save');
Route::post('/typepiece/statut', 'TypepieceController@statut');
Route::delete('/typepiece/{id}', 'TypepieceController@delete');
Route::post('/typepiece/import', 'TypepieceController@import');
Route::get('/generate-excel-typepiece', 'PdfExcelController@generate_excel_typepiece');
Route::get('/generate-pdf-typepiece', 'PdfExcelController@generate_pdf_typepiece');

//type renouvellement
Route::post('/typerenouvellement', 'TyperenouvellementController@save');
Route::post('/typerenouvellement/statut', 'TyperenouvellementController@statut');
Route::delete('/typerenouvellement/{id}', 'TyperenouvellementController@delete');
Route::post('/typerenouvellement/import', 'TyperenouvellementController@import');
Route::get('/generate-excel-typerenouvellement', 'PdfExcelController@generate_excel_typerenouvellement');
Route::get('/generate-pdf-typerenouvellement', 'PdfExcelController@generate_pdf_typerenouvellement');

//type obligation administrative
Route::post('/typeobligationadministrative', 'TypeobligationadministrativeController@save');
Route::post('/typeobligationadministrative/statut', 'TypeobligationadministrativeController@statut');
Route::delete('/typeobligationadministrative/{id}', 'TypeobligationadministrativeController@delete');
Route::post('/typeobligationadministrative/import', 'TypeobligationadministrativeController@import');
Route::get('/generate-excel-typeobligationadministrative', 'PdfExcelController@generate_excel_typeobligationadministrative');
Route::get('/generate-pdf-typeobligationadministrative', 'PdfExcelController@generate_pdf_typeobligationadministrative');

//piece appartement
Route::post('/pieceappartement', 'PieceappartementController@save');
Route::post('/pieceappartement/statut', 'PieceappartementController@statut');
Route::delete('/pieceappartement/{id}', 'PieceappartementController@delete');
Route::post('/pieceappartement/import', 'PieceappartementController@import');

//appartement
Route::post('/appartement', 'AppartementController@save');
Route::post('/appartement/statut', 'AppartementController@statut');
Route::delete('/appartement/{id}', 'AppartementController@delete');
Route::post('/appartement/import', 'AppartementController@import');
Route::get('/generate-excel-appartement', 'PdfExcelController@generate_excel_appartement');
Route::get('/generate-excel-appartement/{filters}', 'PdfExcelController@generate_excel_appartement');
Route::get('/generate-pdf-appartement', 'PdfExcelController@generate_pdf_appartement');
Route::get('/generate-pdf-appartement/{filters}', 'PdfExcelController@generate_pdf_appartement');


//immeuble
Route::post('/immeuble', 'ImmeubleController@save');
Route::post('/immeuble/statut', 'ImmeubleController@statut');
Route::delete('/immeuble/{id}', 'ImmeubleController@delete');
Route::post('/immeuble/import', 'ImmeubleController@import');
Route::get('/generate-excel-immeuble', 'PdfExcelController@generate_excel_immeuble');
Route::get('/generate-pdf-immeuble', 'PdfExcelController@generate_pdf_immeuble');
Route::get('/generate-pdf-immeuble/{request}', 'PdfExcelController@generate_pdf_immeuble');


//proprietaire
Route::post('/proprietaire', 'ProprietaireController@save');
Route::post('/proprietaire/statut', 'ProprietaireController@statut');
Route::delete('/proprietaire/{id}', 'ProprietaireController@delete');
Route::post('/proprietaire/import', 'ProprietaireController@import');
Route::get('/generate-excel-proprietaire', 'PdfExcelController@generate_excel_proprietaire');
Route::get('/generate-pdf-proprietaire', 'PdfExcelController@generate_pdf_proprietaire');

//! gestionnaire proprietaire
Route::post('/gestionnaire/import', 'GestionnaireController@import');
//! gestionnaire proprietaire


//questionnaire
Route::post('/questionnaire', 'QuestionnaireController@save');
Route::post('/questionnaire/statut', 'QuestionnaireController@statut');
Route::delete('/questionnaire/{id}', 'QuestionnaireController@delete');
Route::post('/questionnaire/import', 'QuestionnaireController@import');

//equipe de gestion
Route::post('/equipegestion', 'EquipegestionController@save');
Route::post('/equipegestion/statut', 'EquipegestionController@statut');
Route::delete('/equipegestion/{id}', 'EquipegestionController@delete');
Route::post('/equipegestion/import', 'EquipegestionController@import');

//equipementpiece
Route::post('/equipementpiece', 'EquipementpieceController@save');
Route::post('/equipementpiece/statut', 'EquipementpieceController@statut');
Route::delete('/equipementpiece/{id}', 'EquipementpieceController@delete');
Route::post('/equipementpiece/import', 'EquipementpieceController@import');

// ! equipement appartement start
Route::post('/equipementappartement/import', 'EquipementAppController@import');
// ! equipement appartement end

// ! composition appartement start
Route::post('/compositionappartement/import', 'CompositionAppartementController@import');
// ! composition appartement end

//inbox
Route::post('/inbox', 'InboxController@save');
Route::post('/inbox/delete/{id}', 'InboxController@delete');
Route::post('/inbox/send-echeance-encours', 'InboxController@sendEcheanceEncours');

//contrat
Route::post('contrat/send-mail-director', 'ContratController@sendMailDirector');

Route::post('contrat/send-mail-director-ridwan', 'ContratController@validationContratRidwanByJuriste');
Route::post('contrat/annuler-soumission-contrat-ridwan', 'ContratController@annulerEnvoieContratRidwan');


Route::post('contrat/change-statut', 'ContratController@changeStatut');
Route::post('contrat/init-appartement', 'ContratController@initAppartement');
Route::post('contrat/signature', 'ContratController@signature');
Route::post('/contrat', 'ContratController@save');
Route::post('/contrat/statut', 'ContratController@statut');
Route::delete('/contrat/{id}', 'ContratController@delete');
Route::post('/contrat/import', 'ContratController@import');
Route::get('/generate-pdf-appelcaution/{id}', 'PdfExcelController@generate_pdf_one_appel_caution');
Route::get('/generate-pdf-appelloyer/{id}', 'PdfExcelController@generate_pdf_one_appel_loyer');
Route::get('/generate-pdf-appelecheance/{id}', 'PdfExcelController@generate_pdf_one_appel_echeance');
Route::get('/generate-pdf-avisecheance/{id}', 'PdfExcelController@generate_pdf_one_avis_echeance');
Route::get('/contratlocationvente/repaire', 'ContratController@contratlocationventerepaire');





//prestataire
Route::post('/prestataire', 'PrestataireController@save');
Route::post('/prestataire/statut', 'PrestataireController@statut');
Route::delete('/prestataire/{id}', 'PrestataireController@delete');
Route::post('/prestataire/import', 'PrestataireController@import');
Route::get('/generate-excel-prestataire', 'PdfExcelController@generate_excel_prestataire');
Route::get('/generate-pdf-prestataire', 'PdfExcelController@generate_pdf_prestataire');

//demandeintervention
Route::post('/demandeintervention', 'DemandeinterventionController@save');
Route::post('/demandeintervention/statut', 'DemandeinterventionController@statut');
Route::delete('/demandeintervention/{id}', 'DemandeinterventionController@delete');
Route::post('/demandeintervention/import', 'DemandeinterventionController@import');
Route::get('/generate-pdf-one-devi/{id}/{type}', 'PdfExcelController@generate_pdf_one_devi');

//devi
Route::post('/devi', 'DeviController@save');
Route::post('/devi/statut', 'DeviController@statut');
Route::delete('/devi/{id}', 'DeviController@delete');
Route::post('/devi/import', 'DeviController@import');
Route::post('/changeEtatDevi/{id}', 'DeviController@changeEtatDevi');

//intervention
Route::post('/intervention', 'InterventionController@save');
Route::post('/intervention/statut', 'InterventionController@statut');
Route::delete('/intervention/{id}', 'InterventionController@delete');
Route::post('/intervention/import', 'InterventionController@import');

//facture
Route::post('/facture', 'FactureController@save');
Route::post('/facture/statut', 'FactureController@statut');
Route::delete('/facture/{id}', 'FactureController@delete');
Route::post('/facture/import', 'FactureController@import');
Route::get('/generate-pdf-one-facture/{id}', 'PdfExcelController@generate_pdf_one_facture');


//factureintervention
Route::post('/factureintervention', 'FactureinterventionController@save');
Route::post('/factureintervention/statut', 'FactureinterventionController@statut');
Route::delete('/factureintervention/{id}', 'FactureinterventionController@delete');
Route::post('/factureintervention/import', 'FactureinterventionController@import');
Route::get('/generate-pdf-one-factureintervention/{id}', 'PdfExcelController@generate_pdf_one_factureintervention');

// paiement facture intervention
Route::post('/paiementintervention', 'PaiementinterventionController@save');
Route::post('/paiementintervention/statut', 'PaiementinterventionController@statut');
Route::delete('/paiementintervention/{id}', 'PaiementinterventionController@delete');
Route::post('/paiementintervention/import', 'PaiementinterventionController@import');
//assurance
Route::post('/assurance', 'AssuranceController@save');
Route::post('/assurance/statut', 'AssuranceController@statut');
Route::delete('/assurance/{id}', 'AssuranceController@delete');
Route::post('/assurance/import', 'AssuranceController@import');

//annonce
Route::post('/annonce', 'AnnonceController@save');
Route::post('/annonce/statut', 'AnnonceController@statut');
Route::delete('/annonce/{id}', 'AnnonceController@delete');
Route::post('/annonce/import', 'AnnonceController@import');

//message
Route::post('/message', 'MessageController@save');
Route::post('/message/statut', 'MessageController@statut');
Route::delete('/message/{id}', 'MessageController@delete');
Route::post('/message/import', 'MessageController@import');

//membreequipegestion
Route::post('/membreequipegestion', 'MembreequipegestionController@save');
Route::post('/membreequipegestion/statut', 'MembreequipegestionController@statut');
Route::delete('/membreequipegestion/{id}', 'MembreequipegestionController@delete');
Route::post('/membreequipegestion/import', 'MembreequipegestionController@import');

//questionnairesatisfaction
Route::post('/questionnairesatisfaction', 'QuestionnairesatisfactionController@save');
Route::post('/questionnairesatisfaction/statut', 'QuestionnairesatisfactionController@statut');
Route::delete('/questionnairesatisfaction/{id}', 'QuestionnairesatisfactionController@delete');
Route::post('/questionnairesatisfaction/import', 'QuestionnairesatisfactionController@import');

//contratprestation
Route::post('/contratprestation', 'ContratprestationController@save');
Route::post('/contratprestation/statut', 'ContratprestationController@statut');
Route::delete('/contratprestation/{id}', 'ContratprestationController@delete');
Route::post('/contratprestation/import', 'ContratprestationController@import');

//obligationadministrative
Route::post('/obligationadministrative', 'ObligationadministrativeController@save');
Route::post('/obligationadministrative/statut', 'ObligationadministrativeController@statut');
Route::delete('/obligationadministrative/{id}', 'ObligationadministrativeController@delete');
Route::post('/obligationadministrative/import', 'ObligationadministrativeController@import');

//versementchargecopropriete
Route::post('/versementchargecopropriete', 'VersementchargecoproprieteController@save');
Route::post('/versementchargecopropriete/statut', 'VersementchargecoproprieteController@statut');
Route::delete('/versementchargecopropriete/{id}', 'VersementchargecoproprieteController@delete');
Route::post('/versementchargecopropriete/import', 'VersementchargecoproprieteController@import');

//versementloyer
Route::post('/versementloyer', 'VersementloyerController@save');
Route::post('/versementloyer/statut', 'VersementloyerController@statut');
Route::delete('/versementloyer/{id}', 'VersementloyerController@delete');
Route::post('/versementloyer/import', 'VersementloyerController@import');

//caution
Route::post('/caution', 'CautionController@save');
Route::post('/caution/statut', 'CautionController@statut');
Route::delete('/caution/{id}', 'CautionController@delete');
Route::post('/caution/import', 'CautionController@import');


//paiementloyer
Route::post('/annulationpaiementloyer', 'PaiementloyerController@annulerPaiment');
Route::post('/paiementloyer', 'PaiementloyerController@save');
Route::post('/paiementloyer/statut', 'PaiementloyerController@statut');
Route::delete('/paiementloyer/{id}', 'PaiementloyerController@delete');
Route::post('/paiementloyer/import', 'PaiementloyerController@import');
Route::get('/paiementloyer/recu/{id}', 'PaiementloyerController@recu');


Route::get('/generate-excel-paiementloyer', 'PdfExcelController@generate_excel_paiementloyer');
Route::get('/generate-pdf-paiementloyer', 'PdfExcelController@generate_pdf_paiementloyer');
//one
Route::get('/generate-pdf-one-paiementloyer/{id}', 'PdfExcelController@generate_pdf_one_paiementloyer');



//locataire
Route::post('/locataire', 'LocataireController@save');
Route::get('/locataire/sendmail', 'LocataireController@mailToLocataire')->name("mailToLocataire");
Route::post('/locataire/statut', 'LocataireController@statut');
Route::delete('/locataire/{id}', 'LocataireController@delete');
Route::post('/locataire/import', 'LocataireController@import');
Route::get('/generate-excel-locataire', 'PdfExcelController@generate_excel_locataire');
Route::get('/generate-pdf-locataire', 'PdfExcelController@generate_pdf_locataire');
Route::get('locataire/getlocataireimpayebyperiode', 'LocataireController@getlocataireimpayebyperiode');

//demanderesiliation
Route::post('/demanderesiliation', 'DemanderesiliationController@save');
Route::post('/demanderesiliation/statut', 'DemanderesiliationController@statut');
Route::delete('/demanderesiliation/{id}', 'DemanderesiliationController@delete');
Route::post('/demanderesiliation/import', 'DemanderesiliationController@import');
Route::get('/generate-pdf-one-demanderesiliation/{id}', 'PdfExcelController@generate_pdf_one_demanderesiliation');

//etatlieu
Route::post('/etatlieu', 'EtatlieuController@save');
Route::post('/etatlieu/statut', 'EtatlieuController@statut');
Route::delete('/etatlieu/{id}', 'EtatlieuController@delete');
Route::post('/etatlieu/import', 'EtatlieuController@import');
Route::get('/generate-pdf-rapport-etatlieu/{id}', 'PdfExcelController@generate_pdf_rapport_etatlieu');
Route::get('/generate-pdf-situationdepotgarentie/{id}', 'PdfExcelController@generate_pdf_situationdepotgarentie');
Route::get('/generate-pdf-bordereauremisecheque/{id}', 'PdfExcelController@generate_pdf_bordereauremisecheque');
Route::get('/generate-pdf-piecejoint/{id}', 'PdfExcelController@generate_pdf_piecejoint');


Route::get('/page/{namepage}', 'HomeController@namepage');

Auth::routes();

Route::post('/activite', 'ActiviteController@save');
Route::post('/activite/statut', 'ActiviteController@statut');
Route::delete('/activite/{id}', 'ActiviteController@delete');
Route::post('/activite/import', 'ActiviteController@import');

Route::post('/rubrique', 'RubriqueController@save');
Route::post('/rubrique/statut', 'RubriqueController@statut');
Route::delete('/rubrique/{id}', 'RubriqueController@delete');
Route::post('/rubrique/import', 'RubriqueController@import');

Route::post('/newsletters ', 'NewsletterController@save');
Route::post('/newsletters /statut', 'NewsletterController@statut');
Route::delete('/newsletters /{id}', 'NewsletterController@delete');
Route::post('/newsletters /import', 'NewsletterController@import');

Route::post('/societefacturation', 'SocieteFacturationController@save');
Route::post('/societefacturation/statut', 'SocieteFacturationController@statut');
Route::delete('/societefacturation/{id}', 'SocieteFacturationController@deleteOld');
Route::post('/societefacturation/import', 'SocieteFacturationController@import');

Route::post('/entite', 'EntiteController@save');
Route::post('/entite/statut', 'EntiteController@statut');
Route::delete('/entite/{id}', 'EntiteController@delete');
Route::post('/entite/import', 'EntiteController@import');

Route::post('/assemblage', 'AssemblageController@save');
Route::post('/assemblage/statut', 'AssemblageController@statut');
Route::delete('/assemblage/{id}', 'AssemblageController@delete');
Route::post('/assemblage/import', 'AssemblageController@import');

Route::post('/production', 'ProductionController@save');
Route::post('/production/statut', 'ProductionController@statut');
Route::delete('/production/{id}', 'ProductionController@delete');
Route::post('/production/import', 'ProductionController@import');

Route::post('/typeevenement', 'TypeEvenementController@save');
Route::post('/typeevenement/statut', 'TypeEvenementController@statut');
Route::delete('/typeevenement/{id}', 'TypeEvenementController@delete');
Route::post('/typeevenement/import', 'TypeEvenementController@import');

Route::post('/typefaitdiver', 'TypeFaitDiverController@save');
Route::post('/typefaitdiver/statut', 'TypeFaitDiverController@statut');
Route::delete('/typefaitdiver/{id}', 'TypeFaitDiverController@delete');
Route::post('/typefaitdiver/import', 'TypeFaitDiverController@import');

Route::post('/evenement', 'EvenementController@save');
Route::post('/evenement/statut', 'EvenementController@statut');
Route::delete('/evenement/{id}', 'EvenementController@delete');
Route::post('/evenement/import', 'EvenementController@import');

Route::post('/inventaire', 'InventaireController@save');
Route::post('/inventaire/statut', 'InventaireController@statut');
Route::delete('/inventaire/{id}', 'InventaireController@delete');
Route::post('/inventaire/import', 'InventaireController@import');

Route::post('/planing', 'PlaningController@save');
Route::post('/planing/statut', 'PlaningController@statut');
Route::delete('/planing/{id}', 'PlaningController@delete');
Route::post('/planing/import', 'PlaningController@import');

Route::get('/update-villa-etatlieu', 'TypeappartementController@updateAppartementTRidwan');
Route::post('/modepaiement', 'ModepaiementController@save');
Route::post('/modepaiement/statut', 'ModepaiementController@statut');
Route::delete('/modepaiement/{id}', 'ModepaiementController@delete');
Route::post('/modepaiement/import', 'ModepaiementController@import');

Route::post('/paiement', 'PaiementController@save');
Route::post('/paiement/statut', 'PaiementController@statut');
Route::delete('/paiement/{id}', 'PaiementController@deleteOld');
Route::post('/paiement/import', 'PaiementController@import');

Route::post('/reglement', 'ReglementController@save');
Route::post('/reglement/statut', 'ReglementController@statut');
Route::delete('/reglement/{id}', 'ReglementController@delete');
Route::post('/reglement/import', 'ReglementController@import');

Route::post('/paiementfacture', 'PaiementFactureController@save');
Route::post('/paiementfacture/statut', 'PaiementFactureController@statut');
Route::delete('/paiementfacture/{id}', 'PaiementFactureController@delete');
Route::post('/paiementfacture/import', 'PaiementFactureController@import');


// test

Route::post('/facturelocation', 'FacturelocationController@save');
Route::post('/facturelocation/statut', 'FacturelocationController@statut');
Route::delete('/facturelocation/{id}', 'FacturelocationController@delete');
Route::post('/facturelocation/import', 'FacturelocationController@import');

Route::get('/generate-pdf-facturelocation', 'PdfExcelController@generate_pdf_facturelocation');
//one
Route::get('/generate-pdf-one-facturelocation/{id}', 'PdfExcelController@generate_pdf_one_facturelocation');
Route::get('/generate-pdf-acompte/{id}', 'PdfExcelController@generate_pdf_one_factureacompte');

// facture eaux
Route::post('/factureeaux', 'FactureeauxController@save');
Route::post('/factureeaux/statut', 'FactureeauxController@statut');
Route::delete('/factureeaux/{id}', 'FactureeauxController@delete');
Route::post('/factureeaux/import', 'FactureeauxController@import');
Route::get('/generate-pdf-factureeaux/{id}', 'PdfExcelController@generate_pdf_factureeaux');
Route::get('/generate-pdf-loyerbbi/{id}', 'PdfExcelController@generate_pdf_loyer_bbi');
Route::get('/generate-pdf-quitancebbi/{id}', 'PdfExcelController@generate_pdf_quitance_bbi');

Route::get('/generate-pdf-commissionentredeuxdate/{filter}', 'PdfExcelController@generate_pdf_commissionentredeuxdate');
// generate_pdf_situasimplecompte
Route::get('/generate-pdf-situasimplecompte/{id}', 'PdfExcelController@generate_pdf_situasimplecompte');
Route::get('/generate-pdf-situasimplecompte', 'PdfExcelController@generate_pdf_situasimplecompte');

// generate_pdf_situacompteclient
Route::get('/generate-pdf-situacompteclient', 'PdfExcelController@generate_pdf_situacompteclient');
// generate_pdf_situacompteprop
Route::get('/generate-pdf-situacompteprop/{filter}', 'PdfExcelController@generate_pdf_situacompteprop');
// generate_pdf_tablearrieres
Route::get('/generate-pdf-tablearrieres/{filter}', 'PdfExcelController@generate_pdf_tablearrieres');
// generate_pdf_balanceclients
Route::get('/generate-pdf-balanceclients/{filter}', 'PdfExcelController@generate_pdf_balanceclients');
// generate_pdf_tlv
Route::get('/generate-pdf-tlv/{filter}', 'PdfExcelController@generate_pdf_tlv');
// generate_pdf_ter
Route::get('/generate-pdf-ter', 'PdfExcelController@generate_pdf_ter');
// generate_pdf_tva
Route::get('/generate-pdf-tva/{filter}', 'PdfExcelController@generate_pdf_tva');

/// reinit solde compte client
Route::post('/reinitcompteclient', 'LocataireController@reinitcompteclient');

/// desactiver compte client
Route::post('/desactivecompteclient', 'LocataireController@desactiveCompteClient');

/// activer compte client
Route::post('/activecompteclient', 'LocataireController@activeCompteClient');


/// avis d'echeance
Route::post('/annulationpaiementavis', 'AvisecheanceController@annulerPaiment');
Route::post('/avisecheance', 'AvisecheanceController@save');
Route::post('/avisecheance/statut', 'AvisecheanceController@statut');
Route::delete('/avisecheance/{id}', 'AvisecheanceController@delete');
Route::post('/avisecheance/import', 'AvisecheanceController@import');
Route::get('/avisecheance/order/{id}' , 'AvisecheanceController@orderRecipNumber');
Route::get('/avisecheance/delete-all/{id}' , 'AvisecheanceController@deleteAvisIntervaldate');
Route::post('/avisecheance/signature', 'AvisecheanceController@signature')->name("avisecheance.signature");

/// paiement d'echeance
Route::post('/paiementecheance', 'PaiementecheanceController@save');
Route::post('/paiementecheance/statut', 'PaiementecheanceController@statut');
Route::delete('/paiementecheance/{id}', 'PaiementecheanceController@delete');
Route::get('/paiementecheance/recu/{id}', 'PaiementecheanceController@recu');

/// facture acompte
Route::post('/factureacompte', 'FactureacompteController@save');
Route::post('/factureacompte/statut', 'FactureacompteController@statut');
Route::delete('/factureacompte/{id}', 'FactureacompteController@delete');

// test
Route::get('signature-pdf', [SignaturePDFController::class, 'index']);
Route::get('/signature-avis/{id}', [SignaturePDFController::class, 'signatureAvis']);
Route::get('/success-page', [SignaturePDFController::class, 'successPage']);
Route::get('/not-found-page', [SignaturePDFController::class, 'notFound']);
Route::post('signature-pdf', [SignaturePDFController::class, 'upload'])->name('signature.upload');
Route::get('/annexes/contrat/{id}', 'SignaturePDFController@viewAnnexeMergedPDF')->name('view.merged.pdf');


Route::delete('/annexe/{id}', 'AnnexeController@delete');
// avenant
Route::post('/avenant', 'AvenantController@save');
Route::delete('/avenant/{id}', 'AvenantController@delete');
Route::post('/avenant/statut', 'AvenantController@statut');


Route::post('/typebillet', 'TypeBilletController@save');
Route::post('/typebillet/statut', 'TypeBilletController@statut');
Route::delete('/typebillet/{id}', 'TypeBilletController@delete');
Route::post('/typebillet/import', 'TypeBilletController@import');

Route::post('/caisse', 'CaisseController@save');
Route::post('/caisse/statut', 'CaisseController@statut');
Route::delete('/caisse/{id}', 'CaisseController@delete');
Route::post('/caisse/import', 'CaisseController@import');

Route::post('/typedecaisse', 'TypeCaisseController@save');
Route::post('/typedecaisse/statut', 'TypeCaisseController@statut');
Route::delete('/typedecaisse/{id}', 'TypeCaisseController@delete');
Route::post('/typedecaisse/import', 'TypeCaisseController@import');

Route::post('/banque', 'BanqueController@save');
Route::post('/banque/statut', 'BanqueController@statut');
Route::delete('/banque/{id}', 'BanqueController@delete');
Route::post('/banque/import', 'BanqueController@import');

Route::post('/approcash', 'ApproCashController@save');
Route::post('/approcash/statut', 'ApproCashController@statut');
Route::delete('/approcash/{id}', 'ApproCashController@delete');
Route::post('/approcash/import', 'ApproCashController@import');

Route::post('/sortiecash', 'SortieCashController@save');
Route::post('/sortiecash/statut', 'SortieCashController@statut');
Route::delete('/sortiecash/{id}', 'SortieCashController@delete');
Route::post('/sortiecash/import', 'SortieCashController@import');

Route::post('/cloturecaisse', 'ClotureCaisseController@save');
Route::post('/cloturecaisse/statut', 'ClotureCaisseController@statut');
Route::delete('/cloturecaisse/{id}', 'ClotureCaisseController@delete');
Route::post('/cloturecaisse/import', 'ClotureCaisseController@import');
Route::post('/validationcloturecaisse', 'ClotureCaisseController@statut');

Route::post('/versement', 'VersementController@save');
Route::post('/versement/statut', 'VersementController@statut');
Route::delete('/versement/{id}', 'VersementController@delete');
Route::post('/versement/import', 'VersementController@import');

Route::post('/brigade', 'BrigadeController@save');
Route::post('/brigade/statut', 'BrigadeController@statut');
Route::delete('/brigade/{id}', 'BrigadeController@delete');
Route::post('/brigade/import', 'BrigadeController@import');

Route::post('/fonction', 'FonctionController@save');
Route::post('/fonction/statut', 'FonctionController@statut');
Route::delete('/fonction/{id}', 'FonctionController@delete');
Route::post('/fonction/import', 'FonctionController@import');

Route::post('/zone', 'ZoneController@save');
Route::post('/zone/statut', 'ZoneController@statut');
Route::delete('/zone/{id}', 'ZoneController@delete');
Route::post('/zone/import', 'ZoneController@import');
Route::post('/souszone', 'ZoneController@save');
Route::post('/souszone/statut', 'ZoneController@statut');
Route::delete('/souszone/{id}', 'ZoneController@delete');
Route::post('/souszone/import', 'ZoneController@import');

Route::post('/employe', 'EmployeController@save');
Route::post('/employe/statut', 'EmployeController@statut');
Route::delete('/employe/{id}', 'EmployeController@delete');
Route::post('/employe/import', 'EmployeController@import');

Route::post('/familleaction', 'FamilleActionController@save');
Route::post('/familleaction/statut', 'FamilleActionController@statut');
Route::delete('/familleaction/{id}', 'FamilleActionController@delete');
Route::post('/familleaction/import', 'FamilleActionController@import');

Route::post('/operateur', 'OperateurController@save');
Route::post('/operateur/statut', 'OperateurController@statut');
Route::delete('/operateur/{id}', 'OperateurController@delete');
Route::post('/operateur/import', 'OperateurController@import');

Route::post('/typeprixdevente', 'TypePrixDeVenteController@save');
Route::post('/typeprixdevente/statut', 'TypePrixDeVenteController@statut');
Route::delete('/typeprixdevente/{id}', 'TypePrixDeVenteController@delete');
Route::post('/typeprixdevente/import', 'TypePrixDeVenteController@import');

Route::post('/nomenclature', 'NomenclatureController@save');
Route::post('/nomenclature/statut', 'NomenclatureController@statut');
Route::delete('/nomenclature/{id}', 'NomenclatureController@delete');
Route::post('/nomenclature/import', 'NomenclatureController@import');

Route::post('/unitedemesure', 'UniteDeMesureController@save');
Route::post('/unitedemesure/statut', 'UniteDeMesureController@statut');
Route::delete('/unitedemesure/{id}', 'UniteDeMesureController@delete');
Route::post('/unitedemesure/import', 'UniteDeMesureController@import');

Route::post('/zonedestockage', 'ZoneDeStockageController@save');
Route::post('/zonedestockage/statut', 'ZoneDeStockageController@statut');
Route::delete('/zonedestockage/{id}', 'ZoneDeStockageController@delete');
Route::post('/zonedestockage/import', 'ZoneDeStockageController@import');

Route::post('/typedeconservation', 'TypeDeConservationController@save');
Route::post('/typedeconservation/statut', 'TypeDeConservationController@statut');
Route::delete('/typedeconservation/{id}', 'TypeDeConservationController@delete');
Route::post('/typedeconservation/import', 'TypeDeConservationController@import');

Route::post('/conditionreglement', 'ConditionReglementController@save');
Route::post('/conditionreglement/statut', 'ConditionReglementController@statut');
Route::delete('/conditionreglement/{id}', 'ConditionReglementController@delete');
Route::post('/conditionreglement/import', 'ConditionReglementController@import');

Route::post('/zonedelivraison', 'ZoneDeLivraisonController@save');
Route::post('/zonedelivraison/statut', 'ZoneDeLivraisonController@statut');
Route::delete('/zonedelivraison/{id}', 'ZoneDeLivraisonController@delete');
Route::post('/zonedelivraison/import', 'ZoneDeLivraisonController@import');

Route::post('/typeoperateur', 'TypeOperateurController@save');
Route::post('/typeoperateur/statut', 'TypeOperateurController@statut');
Route::delete('/typeoperateur/{id}', 'TypeOperateurController@delete');
Route::post('/typeoperateur/import', 'TypeOperateurController@import');

Route::post('/typecontrat', 'TypecontratController@save');
Route::post('/typecontrat/statut', 'TypecontratController@statut');
Route::delete('/typecontrat/{id}', 'TypecontratController@delete');
Route::post('/typecontrat/import', 'TypecontratController@import');

Route::post('/typedepot', 'TypeDepotController@save');
Route::post('/typedepot/statut', 'TypeDepotController@statut');
Route::delete('/typedepot/{id}', 'TypeDepotController@delete');
Route::post('/typedepot/import', 'TypeDepotController@import');

Route::post('/depot', 'DepotController@save');
Route::post('/depot/statut', 'DepotController@statut');
Route::delete('/depot/{id}', 'DepotController@delete');
Route::post('/depot/import', 'DepotController@import');

Route::post('/typeclient', 'TypeClientController@save');
Route::post('/typeclient/statut', 'TypeClientController@statut');
Route::delete('/typeclient/{id}', 'TypeClientController@delete');
Route::post('/typeclient/import', 'TypeClientController@import');

Route::post('/connexion_client', 'ClientController@connexion_client');
Route::post('/client', 'ClientController@save');
Route::post('/client/statut', 'ClientController@statut');
Route::delete('/client/{id}', 'ClientController@deleteOld');
Route::post('/client/import', 'ClientController@import');

Route::post('/clientmarket', 'ClientController@save');
Route::post('/clientmarket/statut', 'ClientController@statut');
Route::delete('/clientmarket/{id}', 'ClientController@delete');

Route::post('/tag', 'TagController@save');
Route::post('/tag/statut', 'TagController@statut');
Route::delete('/tag/{id}', 'TagController@delete');
Route::post('/tag/import', 'TagController@import');

Route::post('/tagclient', 'TagClientController@save');
Route::post('/tagclient/statut', 'TagClientController@statut');
Route::delete('/tagclient/{id}', 'TagClientController@delete');
Route::post('/tagclient/import', 'TagClientController@import');

Route::post('/dateclemotif', 'DateCleMotifController@save');
Route::post('/dateclemotif/statut', 'DateCleMotifController@statut');
Route::delete('/dateclemotif/{id}', 'DateCleMotifController@delete');
Route::post('/dateclemotif/import', 'DateCleMotifController@import');

Route::post('/typeproduit', 'TypeProduitController@save');
Route::post('/typeproduit/statut', 'TypeProduitController@statut');
Route::delete('/typeproduit/{id}', 'TypeProduitController@delete');
Route::post('/typeproduit/import', 'TypeProduitController@import');

Route::post('/categorieproduit', 'CategorieProduitController@save');
Route::post('/categorieproduit/statut', 'CategorieProduitController@statut');
Route::delete('/categorieproduit/{id}', 'CategorieProduitController@delete');
Route::post('/categorieproduit/import', 'CategorieProduitController@import');

Route::post('/famille', 'FamilleController@save');
Route::post('/famille/statut', 'FamilleController@statut');
Route::delete('/famille/{id}', 'FamilleController@delete');
Route::post('/famille/import', 'FamilleController@import');

Route::post('/sousfamille', 'SousFamilleController@save');
Route::post('/sousfamille/statut', 'SousFamilleController@statut');
Route::delete('/sousfamille/{id}', 'SousFamilleController@delete');
Route::post('/sousfamille/import', 'SousFamilleController@import');

Route::post('/departement', 'DepartementController@save');
Route::post('/departement/statut', 'DepartementController@statut');
Route::delete('/departement/{id}', 'DepartementController@delete');
Route::post('/departement/import', 'DepartementController@import');

Route::post('/sousdepartement', 'SousDepartementController@save');
Route::post('/sousdepartement/statut', 'SousDepartementController@statut');
Route::delete('/sousdepartement/{id}', 'SousDepartementController@delete');
Route::post('/sousdepartement/import', 'SousDepartementController@import');

Route::post('/fournisseur', 'FournisseurController@save');
Route::post('/fournisseur/statut', 'FournisseurController@statut');
Route::delete('/fournisseur/{id}', 'FournisseurController@deleteOld');
Route::post('/fournisseur/import', 'FournisseurController@import');

Route::post('/typetier', 'TypeTierController@save');
Route::post('/typetier/statut', 'TypeTierController@statut');
Route::delete('/typetier/{id}', 'TypeTierController@delete');
Route::post('/typetier/import', 'TypeTierController@import');

Route::post('/categoriefournisseur', 'CategorieFournisseurController@save');
Route::post('/categoriefournisseur/statut', 'CategorieFournisseurController@statut');
Route::delete('/categoriefournisseur/{id}', 'CategorieFournisseurController@delete');
Route::post('/categoriefournisseur/import', 'CategorieFournisseurController@import');

Route::post('/categorieservice',        'CategorieServiceController@save');
Route::post('/categorieservice/statut', 'CategorieServiceController@statut');
Route::delete('/categorieservice/{id}', 'CategorieServiceController@delete');
Route::post('/categorieservice/import', 'CategorieServiceController@import');

Route::post('/bci',        'BciController@save');
Route::post('/bci/statut', 'BciController@statut');
Route::delete('/bci/{id}', 'BciController@deleteOld');
Route::post('/bci/import', 'BciController@import');

Route::post('/bce',        'BceController@save');
Route::post('/bce/statut', 'BceController@statut');
Route::delete('/bce/{id}', 'BceController@deleteOld');
Route::post('/bce/import', 'BceController@import');

Route::post('/action',        'ActionController@save');
Route::post('/action/statut', 'ActionController@statut');
Route::delete('/action/{id}', 'ActionController@delete');
Route::post('/action/import', 'ActionController@import');

Route::post('/detailaction',        'DetailActionController@save');
Route::post('/detailaction/statut', 'DetailActionController@statut');
Route::delete('/detailaction/{id}', 'DetailActionController@delete');
Route::post('/detailaction/import', 'DetailActionController@import');

Route::post('/categoriedepense',        'CategorieDepenseController@save');
Route::post('/categoriedepense/statut', 'CategorieDepenseController@statut');
Route::delete('/categoriedepense/{id}', 'CategorieDepenseController@delete');
Route::post('/categoriedepense/import', 'CategorieDepenseController@import');

Route::post('/postedepense',        'PosteDepenseController@save');
Route::post('/postedepense/statut', 'PosteDepenseController@statut');
Route::delete('/postedepense/{id}', 'PosteDepenseController@deleteOld');
Route::post('/postedepense/import', 'PosteDepenseController@import');

Route::post('/depense',        'DepenseController@save');
Route::post('/depense/statut', 'DepenseController@statut');
Route::delete('/depense/{id}', 'DepenseController@delete');
Route::post('/depense/import', 'DepenseController@import');

Route::post('/entreestock',        'EntreeStockController@save');
Route::post('/entreestock/statut', 'EntreeStockController@statut');
Route::delete('/entreestock/{id}', 'EntreeStockController@delete');
Route::post('/entreestock/import', 'EntreeStockController@import');

Route::post('/sortiestock',        'SortieStockController@save');
Route::post('/sortiestock/statut', 'SortieStockController@statut');
Route::delete('/sortiestock/{id}', 'SortieStockController@delete');
Route::post('/sortiestock/import', 'SortieStockController@import');

Route::post('/be',        'BeController@save');
Route::post('/be/statut', 'BeController@statut');
Route::delete('/be/{id}', 'BeController@delete');
Route::post('/be/import', 'BeController@import');

Route::post('/bt',        'BtController@save');
Route::post('/bt/statut', 'BtController@statut');
Route::delete('/bt/{id}', 'BtController@deleteOld');
Route::post('/bt/import', 'BtController@import');

Route::post('/formetable',        'FormeTableController@save');
Route::post('/formetable/statut', 'FormeTableController@statut');
Route::delete('/formetable/{id}', 'FormeTableController@delete');
Route::post('/formetable/import', 'FormeTableController@import');

Route::post('/table',        'TableController@save');
Route::post('/table/statut', 'TableController@statut');
Route::delete('/table/{id}', 'TableController@delete');
Route::post('/table/import', 'TableController@import');

Route::post('/suivimarketing',        'SuiviMarketingController@save');
Route::post('/suivimarketing/statut', 'SuiviMarketingController@statut');
Route::delete('/suivimarketing/{id}', 'SuiviMarketingController@delete');
Route::post('/suivimarketing/import', 'SuiviMarketingController@import');

Route::post('/lignedecredit',        'LigneCreditController@save');
Route::post('/lignedecredit/statut', 'LigneCreditController@statut');
Route::delete('/lignedecredit/{id}', 'LigneCreditController@delete');
Route::post('/lignedecredit/import', 'LigneCreditController@import');

Route::post('/tranchehoraire',        'TrancheHoraireController@save');
Route::post('/tranchehoraire/statut', 'TrancheHoraireController@statut');
Route::delete('/tranchehoraire/{id}', 'TrancheHoraireController@delete');
Route::post('/tranchehoraire/import', 'TrancheHoraireController@import');

Route::post('/typecommande',        'TypeCommandeController@save');
Route::post('/typecommande/statut', 'TypeCommandeController@statut');
Route::delete('/typecommande/{id}', 'TypeCommandeController@delete');
Route::post('/typecommande/import', 'TypeCommandeController@import');

Route::post('/commande',        'CommandeController@save');
Route::post('/commande/statut', 'CommandeController@statut');
Route::post('/commande/terminerTouteLaCommande', 'CommandeController@terminerTouteLaCommande');
Route::post('/commande/perteCommande', 'CommandeController@perteCommande');
Route::delete('/commande/{id}', 'CommandeController@deleteOld');
Route::post('/commande/import', 'CommandeController@import');
Route::post('/creation_facture/statut', 'CommandeController@creation_facture');

Route::post('/proforma',        'ProformaController@save');
Route::post('/proforma/statut', 'ProformaController@statut');
Route::delete('/proforma/{id}', 'ProformaController@deleteOld');
Route::delete('/traiteur/{id}', 'ProformaController@deleteTraiteur');
Route::post('/proforma/import', 'ProformaController@import');
Route::post('/traiteur/statut', 'TraiteurController@statut');
Route::post('/creation_facture_traiteur/statut', 'ProformaController@creation_facture_traiteur');

Route::post('/menu',        'MenuController@save');
Route::post('/menu/statut', 'MenuController@statut');
Route::delete('/menu/{id}', 'MenuController@delete');
Route::post('/menu/import', 'MenuController@import');

Route::post('/carte',        'CarteController@save');
Route::post('/carte/statut', 'CarteController@statut');
Route::delete('/carte/{id}', 'CarteController@delete');
Route::post('/carte/import', 'CarteController@import');

Route::post('/logistique',        'LogistiqueController@save');
Route::post('/logistique/statut', 'LogistiqueController@statut');
Route::delete('/logistique/{id}', 'LogistiqueController@delete');
Route::post('/logistique/import', 'LogistiqueController@import');

Route::post('/facture',        'FactureController@save');
Route::post('/facture/statut', 'FactureController@statut');
Route::delete('/facture/{id}', 'FactureController@delete');
Route::post('/facture/import', 'FactureController@import');


Route::post('/facturetraiteur',        'FactureTraiteurController@save');
Route::post('/facturetraiteur/statut', 'FactureTraiteurController@statut');
Route::delete('/facturetraiteur/{id}', 'FactureTraiteurController@delete');
Route::post('/facturetraiteur/import', 'FactureTraiteurController@import');

Route::post('/reguleclient',        'ReguleClientController@save');
Route::post('/reguleclient/statut', 'ReguleClientController@statut');
Route::delete('/reguleclient/{id}', 'ReguleClientController@delete');
Route::post('/reguleclient/import', 'ReguleClientController@import');

Route::post('/regulefournisseur',        'ReguleFournisseurController@save');
Route::post('/regulefournisseur/statut', 'ReguleFournisseurController@statut');
Route::delete('/regulefournisseur/{id}', 'ReguleFournisseurController@delete');
Route::post('/regulefournisseur/import', 'ReguleFournisseurController@import');

Route::post('/regulepaiement',  'RegulePaiementController@save');
Route::post('/regulepaiement/statut', 'RegulePaiementController@statut');
Route::delete('/regulepaiement/{id}', 'RegulePaiementController@delete');
Route::post('/regulepaiement/import', 'RegulePaiementController@import');

Route::post('/reservation',        'ReservationController@save');
Route::post('/reservation/statut', 'ReservationController@statut');
Route::delete('/reservation/{id}', 'ReservationController@delete');
Route::post('/reservation/import', 'ReservationController@import');
Route::post('/reservation/statut', 'ReservationController@statut');

Route::post('/commentaire', 'CommentaireController@save');
Route::post('/commentaire/statut', 'CommentaireController@statut');
Route::delete('/commentaire/{id}', 'CommentaireController@delete');
Route::post('/commentaire/import', 'CommentaireController@import');

Route::post('/typecommentaire', 'TypeCommentaireController@save');
Route::post('/typecommentaire/statut', 'TypeCommentaireController@statut');
Route::delete('/typecommentaire/{id}', 'TypeCommentaireController@delete');
Route::post('/typecommentaire/import', 'TypeCommentaireController@import');

Route::post('/stockactuelproduitdepot', 'StockActuelProduitDepotController@synchronisation');

Route::post('/commentairecommande', 'CommentaireCommandeController@save');
Route::post('/commentairecommande/statut', 'CommentaireCommandeController@statut');
Route::delete('/commentairecommande/{id}', 'CommentaireCommandeController@delete');
Route::post('/commentairecommande/import', 'CommentaireCommandeController@import');

Route::post('/comptecredit', 'CompteCreditController@save');
Route::post('/comptecredit/statut', 'CompteCreditController@statut');
Route::delete('/comptecredit/{id}', 'CompteCreditController@delete');
Route::post('/comptecredit/import', 'CompteCreditController@import');


Route::post('/paiementcredit', 'PaiementCreditController@save');
Route::post('/paiementcredit/statut', 'PaiementCreditController@statut');
Route::delete('/paiementcredit/{id}', 'PaiementCreditController@deleteOld');
Route::post('/paiementcredit/import', 'PaiementCreditController@import');

//TYPE D'APPORT PONCTUEL
Route::post('/typeapportponctuel', 'TypeapportponctuelController@save');
Route::post('/typeapportponctuel/statut', 'TypeapportponctuelController@statut');
Route::delete('/typeapportponctuel/{id}', 'TypeapportponctuelController@delete');
Route::post('/typeapportponctuel/import', 'TypeapportponctuelController@import');

//APPORT PONCTUEL
Route::post('/apportponctuel', 'ApportponctuelController@save');
Route::post('/apportponctuel/statut', 'ApportponctuelController@statut');
Route::delete('/apportponctuel/{id}', 'ApportponctuelController@delete');
Route::post('/apportponctuel/import', 'ApportponctuelController@import');

//MODEL DE CONTRAT
Route::post('/modelcontrat', 'ModelcontratController@save');
Route::post('/modelcontrat/statut', 'ModelcontratController@statut');
Route::delete('/modelcontrat/{id}', 'ModelcontratController@delete');
Route::post('/modelcontrat/import', 'ModelcontratController@import');

//CONTRAT PROPRIETAIRE
Route::post('/contratproprietaire', 'ContratproprietaireController@save');
Route::post('/contratproprietaire/statut', 'ContratproprietaireController@statut');
Route::delete('/contratproprietaire/{id}', 'ContratproprietaireController@delete');
Route::post('/contratproprietaire/import', 'ContratproprietaireController@import');

//ACTIVITE
Route::post('/activite', 'ActiviteController@save');
Route::post('/activite/statut', 'ActiviteController@statut');
Route::delete('/activite/{id}', 'ActiviteController@delete');
Route::post('/activite/import', 'ActiviteController@import');

//TAXE
Route::post('/taxe', 'TaxeController@save');
Route::post('/taxe/statut', 'TaxeController@statut');
Route::delete('/taxe/{id}', 'TaxeController@delete');
Route::post('/taxe/import', 'TaxeController@import');




//Routes secondaires
Route::get('/generate-pdf-etatfacturation', 'PdfExcelController@generate_pdf_etatfacturation');
Route::get('/generate-pdf-etatfacturation/{request}', 'PdfExcelController@generate_pdf_etatfacturation');
Route::get('/generate-excel-etatfacturation/{request}', 'PdfExcelController@generate_excel_etatfacturation');
Route::get('/generate-excel-etatfacturation', 'PdfExcelController@generate_excel_etatfacturation');




//test mail
Route::get('/test-maileur', 'MaileurController@store');

//******** TEST **************//
//test.pdf sera le nom du pdf une fois téléchargé
Route::get('/generate-pdf-test', 'TestController@generatePdf')->name('test.pdf');
Route::get('/generate-excel-test', 'TestController@generateExcel');
Route::get('/generate-excel-test-produit', 'TestController@generateExcelProduit');
Route::get('/number-to-words', 'TestController@numberToWords');
Route::get('/view-graphql-query', 'TestController@viewGraphqlQuery');
Route::post('/test_jwt', 'TestController@test_jwt');
Route::get('/test_base_64', 'TestController@test_base_64');
Route::get('/test_query', 'TestController@test_query');

Route::get('/majTrancheHoraireCommande',                        'TestController@majTrancheHoraireCommande');
Route::get('/majCaissePaiement',                                'TestController@reguleCaissePaiement');
Route::get('/compilationConsoCommande',                         'TestController@compilationPaiementConsoCommande');
Route::get('/compilationPaiementFactureCommande',               'TestController@compilationPaiementFactureCommande');
Route::get('/compileMontantFactureCommande',                    'TestController@reguleMontantFactureCommande');
Route::get('/compilePlafondCompteCreditConsoClient',            'TestController@compilePlafondCompteCreditConsoClient');
Route::get('/compileDispatchingDepenseEntite',                  'TestController@compileDispatchingDepenseEntite');
Route::get('/compileTitreTraiteur',                             'TestController@compileTitreTraiteur');
Route::get('/compileCodeBe',                                    'TestController@compileCodeBe');
Route::get('/compileMotifDepenseBe',                            'TestController@compileMotifDepenseBe');
Route::get('/compileCodeBci',                                   'TestController@compileCodeBci');
Route::get('/depenseMotifCodeBeSansBe',                         'TestController@depenseMotifCodeBeSansBe');
Route::get('/archiveMenuEchu',                                  'TestController@archiveMenuEchu');
Route::get('/compileRegule',                                    'TestController@compileRegule');
Route::get('/compileLigneCredit',                               'TestController@compileLigneCredit');
Route::get('/compileObservationEntreSortie',                    'TestController@compileObservationEntreSortie');
Route::get('/getCommandeNoSortieStock',                         'TestController@getCommandeNoSortieStock');



//******** AUTHENTIFICATION BACK**************//
Route::post('/login', 'Auth\LoginController@login'); //Connexion
Route::get('logout', 'Auth\LoginController@logout'); //Déconnexion


//******** API GESTIMMO MOBILE**************//
Route::post('api_login', [AuthController::class, 'login']) ;
Route::post('token_notif', [AuthController::class, 'tokeninsert']) ;

Route::get('api_interventions',[InterventionApiController::class, 'index']) ;
Route::get('api_intervention/{id}',[InterventionApiController::class, 'getOne']) ;

Route::get('api_etatlieus',[EtatlieuApiController::class, 'index']) ;
Route::get('api_etatlieu/{id}',[EtatlieuApiController::class, 'getOne']) ;

Route::get('api_categorieinterventions',[CategorieinterventionApiController::class, 'index']) ;
Route::get('api_categorieintervention/{id}',[CategorieinterventionApiController::class, 'getOne']) ;

Route::get('api_appartements',[AppartementApiController::class, 'index']) ;
Route::get('api_appartement/{id}',[AppartementApiController::class, 'getOne']) ;

Route::get('api_etatappartements',[EtatappartementApiController::class, 'index']) ;
Route::get('api_etatappartement/{id}',[EtatappartementApiController::class, 'getOne']) ;

Route::get('api_constituantpieces',[ConstituantApiController::class, 'index']) ;
Route::get('api_apiconstituantpiece/{id}',[ConstituantApiController::class, 'getOne']) ;

Route::get('api_observations',[ObservationApiController::class, 'index']) ;
Route::get('api_observation/{id}',[ObservationApiController::class, 'getOne']) ;

Route::get('api_equipementsgenerales/{id}',[EquipementgeneraleApiController::class, 'getOne']) ;

Route::get('api_compositionappartement/{id}',[CompositionappartementApiController::class, 'getOne']) ;

//******** AUTHENTIFICATION JWT FRONT**************//
//Route::post('/login_client', 'JWTAuthController@login'); //Connexion
Route::group(['middleware' => ['api']], function () {
    Route::post('/login_client', ['as'=>'api.user.login', 'uses' => 'JWTAuthController@loginOld']); //Test login jwt
   // Route::post('/connexion_client', ['as'=>'api.user.login', 'uses' => 'JWTAuthController@login']); //Vrai connexion client jwt
});

//******** APIS CLIENT **************//
Route::post('/inscription', 'ClientController@inscription');
Route::post('/connexion', 'ClientController@login');
Route::post('/edit_client', 'ClientController@edit_client');
Route::post('/panier_client', 'ClientController@panier_client');
Route::post('/commande_client', 'ClientController@commande_client');
Route::post('/adresse_client', 'ClientController@adresse_client');
Route::post('/liste_envie', 'ClientController@liste_envie');
Route::post('/newsletters', 'ClientController@newsletters');
Route::post('/vus_recemment', 'ClientController@vus_recemment');
Route::post('/delete_recherche_client', 'ClientController@delete_recherche_client');
Route::post('/prix_zone_livraison', 'ClientController@prix_zone_livraison');
Route::post('/rappel_rupture', 'ClientController@rappel_rupture');
Route::post('/transformer_en_commande', 'ClientController@transformer_en_commande');
Route::post('/password_oublie', 'ClientController@password_oublie');

//******** SAVE & EDIT **************//
Route::post('/produit', 'ProduitController@save');
Route::post('/produit/import', 'ProduitController@import');
Route::get('/produitnml', 'ProduitController@produitnml');
Route::post('/produitnml', 'ProduitController@produitnml');

Route::post('/produitbce', 'ProduitController@produitbce');

Route::post('/role', 'RoleController@save');
Route::post('/role/import', 'RoleController@import');
Route::post('/user', 'UserController@save');
Route::post('/user-connect', 'UserController@user_permission');
Route::post('/notifuser', 'UserController@user_permission');
Route::post('/user/import', 'UserController@import');
Route::post('/pointrelais', 'PointRelaisController@save');
Route::post('/marque', 'MarqueController@save');
Route::post('/commercial', 'CommercialController@save');
Route::post('/preference', 'PreferenceController@save');
Route::post('/banniere', 'BanniereController@save');
Route::post('/categorie', 'CategorieController@save');
Route::post('/metier', 'MetierController@save');
Route::post('/prixvente', 'PrixVenteController@save');
Route::post('/partenaire', 'PartenaireController@save');
Route::post('/actualite', 'ActualiteController@save');
Route::post('/typetuto', 'TypeTutoController@save');
Route::post('/tuto', 'TutoController@save');
Route::post('/newsletter', 'NewsletterController@save');
Route::post('/recherche', 'RechercheController@save');
Route::post('/edit_client', 'ClientController@edit_client');
Route::post('/vehicule', 'VehiculeController@save');
Route::post('/contact', 'ContactController@save');
Route::post('/filiale', 'FilialeController@save');
Route::post('/catalogue', 'CatalogueController@save');
Route::post('/banque', 'BanqueController@save');
Route::post('/modereglement', 'ModeReglementController@save');



//************* DELETE *****************//
Route::delete('/produit/{id}', 'ProduitController@delete');
Route::delete('/role/{id}', 'RoleController@delete');
Route::delete('/user/{id}', 'UserController@delete');
Route::delete('/pointrelais/{id}', 'PointRelaisController@delete');
Route::delete('/marque/{id}', 'MarqueController@delete');
Route::delete('/commercial/{id}', 'CommercialController@delete');
Route::delete('/preference/{id}', 'PreferenceController@delete');
Route::delete('/banniere/{id}', 'BanniereController@delete');
Route::delete('/categorie/{id}', 'CategorieController@delete');
Route::delete('/prixvente/{id}', 'PrixVenteController@delete');
Route::delete('/partenaire/{id}', 'PartenaireController@delete');
Route::delete('/actualite/{id}', 'ActualiteController@delete');
Route::delete('/typetuto/{id}', 'TypeTutoController@delete');
//Route::delete('/categorie_tuto/{id}', 'CategorieTutoController@delete');
Route::delete('/tuto/{id}', 'TutoController@delete');
Route::delete('/newsletter/{id}', 'NewsletterController@delete');
Route::delete('/recherche/{id}', 'RechercheController@delete');
Route::delete('/demandeacces/{id}', 'DemandeAccesController@delete');
Route::delete('/vehicule/{id}', 'VehiculeController@delete');
Route::delete('/filiale/{id}', 'FilialeController@delete');
Route::delete('/catalogue/{id}', 'CatalogueController@delete');
Route::delete('/metier/{id}', 'MetierController@delete');
Route::delete('/banque/{id}', 'BanqueController@delete');
Route::delete('/modereglement/{id}', 'ModeReglementController@modereglement');
Route::delete('/fichecomm_proforma/{id}', 'ProformaController@fichecomm_proforma');


Route::delete('/conditionpaiement/{id}', 'SuppressionController@conditionpaiement');
Route::delete('/metierproduit/{id}', 'SuppressionController@metierproduit');
//Route::delete('/commande/{id}', 'SuppressionController@commande');


//************* VALIDATION, ACTIVATION, DESACTIVATION, ANNULATION *****************//
Route::delete('/activer_demandeacces/{id}', 'DemandeAccesController@activer');
Route::delete('/desactiver_demandeacces/{id}', 'DemandeAccesController@desactiver');
Route::delete('/activer_client/{id}', 'ClientController@activer');
Route::delete('/desactiver_client/{id}', 'ClientController@desactiver');
Route::delete('/annuler_date_derniere_synchro/{id}', 'SynchronisationController@annuler_date_derniere_synchro');
Route::delete('/activer_commande/{id}', 'CommandeController@activer');


//************* DONNES NAV *****************//
Route::post('/donnees_nav', 'ClientController@donnees_nav');
Route::get('/save_donnees_nav', 'ClientController@save_donnees_nav');


//************* PDF ET EXCEL *****************//
Route::get('/generate-pdf-fichetechnique/{id}', 'PdfExcelController@generate_pdf_fichetechnique');
Route::get('/generate-pdf-fichetechnique-prop/{request}', 'PdfExcelController@generate_pdf_fichetechnique_prop');
Route::get('/generate-pdf-fichetechnique-prop-bci/{request}', 'PdfExcelController@generate_pdf_fichetechnique_prop_bci');
Route::get('/generate-pdf-logistique-prop/{request}', 'PdfExcelController@generate_pdf_logistique_prop');
Route::get('/generate-pdf-rh-prop/{request}', 'PdfExcelController@generate_pdf_rh_prop');
Route::get('/generate-pdf-fichetechnique-custom/{id}/{nbre_portion}', 'PdfExcelController@generate_pdf_fichetechnique_custom');
Route::get('/generate-pdf-planing/{id}', 'PdfExcelController@generate_pdf_planing');

Route::get('/generate-pdf-facture-interne/{request}', 'PdfExcelController@generate_pdf_facture_interne');

Route::get('/generate-pdf-facture/{request}', 'PdfExcelController@generate_pdf_facture');
Route::get('/generate-pdf-facture', 'PdfExcelController@generate_pdf_facture');
Route::get('/generate-excel-facture/{request}', 'PdfExcelController@generate_excel_facture');
Route::get('/generate-excel-facture', 'PdfExcelController@generate_excel_facture');

Route::get('/generate-pdf-facture-avoir/{id}', 'PdfExcelController@generate_pdf_facture_avoir');
Route::get('/generate-pdf-facture-avoir-interne/{id}', 'PdfExcelController@generate_pdf_facture_avoir_interne');


Route::get('/generate-pdf-proposition-commerciale/{id}', 'PdfExcelController@generate_pdf_proposition_commerciale');
Route::get('/generate-pdf-proposition-commerciale-interne/{id}', 'PdfExcelController@generate_pdf_proposition_commerciale_interne');
Route::get('/generate-ticket-commande/{id}', 'PdfExcelController@generate_ticket_commande');
Route::get('/generate-pdf-carte/{id}', 'PdfExcelController@generate_pdf_carte');
Route::get('/generate-excel-carte/{id}', 'TestController@generateExcelCarte');
Route::get('/generate-pdf-reservation-jour/', 'PdfExcelController@generate_reservation_jour');

Route::get('/generate-excel-commande/{filter}', 'PdfExcelController@generate_excel_commande');
Route::get('/generate-excel-commande', 'PdfExcelController@generate_excel_commande');
Route::get('/generate-pdf-commande/{filter}', 'PdfExcelController@generate_pdf_commande');
Route::get('/generate-pdf-commande/', 'PdfExcelController@generate_pdf_commande');

Route::get('/generate-excel-proforma/{filter}', 'PdfExcelController@generate_excel_proforma');
Route::get('/generate-excel-proforma', 'PdfExcelController@generate_excel_proforma');
Route::get('/generate-pdf-proforma/{filter}', 'PdfExcelController@generate_pdf_proforma');
Route::get('/generate-pdf-proforma/', 'PdfExcelController@generate_pdf_proforma');

Route::get('/generate-excel-traiteur/{filter}', 'PdfExcelController@generate_excel_traiteur');
Route::get('/generate-excel-traiteur', 'PdfExcelController@generate_excel_traiteur');
Route::get('/generate-pdf-traiteur/{filter}', 'PdfExcelController@generate_pdf_traiteur');
Route::get('/generate-pdf-traiteur/', 'PdfExcelController@generate_pdf_traiteur');

Route::get('/generate-pdf-proforma-traiteur/{filter}', 'PdfExcelController@generate_pdf_proforma_traiteur');
Route::get('/generate-pdf-proforma-traiteur-interne/{filter}', 'PdfExcelController@generate_pdf_proforma_traiteur_interne');
Route::get('/generate-pdf-facture-traiteur/{request}', 'PdfExcelController@generate_pdf_facture_traiteur');
Route::get('/generate-pdf-facture-traiteur-interne/{request}', 'PdfExcelController@generate_pdf_facture_traiteur_interne');
Route::get('/generate-pdf-facture-traiteur', 'PdfExcelController@generate_pdf_facture_traiteur');
Route::get('/generate-excel-facture-traiteur/{request}', 'PdfExcelController@generate_excel_facture_traiteur');
Route::get('/generate-excel-facture-traiteur', 'PdfExcelController@generate_excel_facture_traiteur');

Route::get('/generate-excel-activite/{filter}', 'PdfExcelController@generate_excel_activite');
Route::get('/generate-excel-activite', 'PdfExcelController@generate_excel_activite');
Route::get('/generate-pdf-activite/{filter}', 'PdfExcelController@generate_pdf_activite');
Route::get('/generate-pdf-activite/', 'PdfExcelController@generate_pdf_activite');

Route::get('/generate-excel-societefacturation/{filter}', 'PdfExcelController@generate_excel_societefacturation');
Route::get('/generate-excel-societefacturation', 'PdfExcelController@generate_excel_societefacturation');
Route::get('/generate-pdf-societefacturation/{filter}', 'PdfExcelController@generate_pdf_societefacturation');
Route::get('/generate-pdf-societefacturation/', 'PdfExcelController@generate_pdf_societefacturation');

Route::get('/generate-excel-entite/{filter}', 'PdfExcelController@generate_excel_entite');
Route::get('/generate-excel-entite', 'PdfExcelController@generate_excel_entite');
Route::get('/generate-pdf-entite/{filter}', 'PdfExcelController@generate_pdf_entite');
Route::get('/generate-pdf-entite/', 'PdfExcelController@generate_pdf_entite');

Route::get('/generate-excel-typedepot/{filter}', 'PdfExcelController@generate_excel_typedepot');
Route::get('/generate-excel-typedepot', 'PdfExcelController@generate_excel_typedepot');
Route::get('/generate-pdf-typedepot/{filter}', 'PdfExcelController@generate_pdf_typedepot');
Route::get('/generate-pdf-typedepot/', 'PdfExcelController@generate_pdf_typedepot');

Route::get('/generate-excel-depot/{filter}', 'PdfExcelController@generate_excel_depot');
Route::get('/generate-excel-depot', 'PdfExcelController@generate_excel_depot');
Route::get('/generate-pdf-depot/{filter}', 'PdfExcelController@generate_pdf_depot');
Route::get('/generate-pdf-depot/', 'PdfExcelController@generate_pdf_depot');

Route::get('/generate-excel-departement/{filter}', 'PdfExcelController@generate_excel_departement');
Route::get('/generate-excel-departement', 'PdfExcelController@generate_excel_departement');
Route::get('/generate-pdf-departement/{filter}', 'PdfExcelController@generate_pdf_departement');
Route::get('/generate-pdf-departement/', 'PdfExcelController@generate_pdf_departement');

Route::get('/generate-excel-sousdepartement/{filter}', 'PdfExcelController@generate_excel_sousdepartement');
Route::get('/generate-excel-sousdepartement', 'PdfExcelController@generate_excel_sousdepartement');
Route::get('/generate-pdf-sousdepartement/{filter}', 'PdfExcelController@generate_pdf_sousdepartement');
Route::get('/generate-pdf-sousdepartement/', 'PdfExcelController@generate_pdf_sousdepartement');

Route::get('/generate-excel-zonedestockage/{filter}', 'PdfExcelController@generate_excel_zonedestockage');
Route::get('/generate-excel-zonedestockage', 'PdfExcelController@generate_excel_zonedestockage');
Route::get('/generate-pdf-zonedestockage/{filter}', 'PdfExcelController@generate_pdf_zonedestockage');
Route::get('/generate-pdf-zonedestockage/', 'PdfExcelController@generate_pdf_zonedestockage');

Route::get('/generate-excel-typeevenement/{filter}', 'PdfExcelController@generate_excel_typeevenement');
Route::get('/generate-excel-typeevenement', 'PdfExcelController@generate_excel_typeevenement');
Route::get('/generate-pdf-typeevenement/{filter}', 'PdfExcelController@generate_pdf_typeevenement');
Route::get('/generate-pdf-typeevenement/', 'PdfExcelController@generate_pdf_typeevenement');

Route::get('/generate-excel-typeproduit/{filter}', 'PdfExcelController@generate_excel_typeproduit');
Route::get('/generate-excel-typeproduit', 'PdfExcelController@generate_excel_typeproduit');
Route::get('/generate-pdf-typeproduit/{filter}', 'PdfExcelController@generate_pdf_typeproduit');
Route::get('/generate-pdf-typeproduit/', 'PdfExcelController@generate_pdf_typeproduit');

Route::get('/generate-excel-modepaiement/{filter}', 'PdfExcelController@generate_excel_modepaiement');
Route::get('/generate-excel-modepaiement', 'PdfExcelController@generate_excel_modepaiement');
Route::get('/generate-pdf-modepaiement/{filter}', 'PdfExcelController@generate_pdf_modepaiement');
Route::get('/generate-pdf-modepaiement/', 'PdfExcelController@generate_pdf_modepaiement');

Route::get('/generate-excel-banque/{filter}', 'PdfExcelController@generate_excel_banque');
Route::get('/generate-excel-banque', 'PdfExcelController@generate_excel_banque');
Route::get('/generate-pdf-banque/{filter}', 'PdfExcelController@generate_pdf_banque');
Route::get('/generate-pdf-banque/', 'PdfExcelController@generate_pdf_banque');

Route::get('/generate-excel-typebillet/{filter}', 'PdfExcelController@generate_excel_typebillet');
Route::get('/generate-excel-typebillet', 'PdfExcelController@generate_excel_typebillet');
Route::get('/generate-pdf-typebillet/{filter}', 'PdfExcelController@generate_pdf_typebillet');
Route::get('/generate-pdf-typebillet/', 'PdfExcelController@generate_pdf_typebillet');

Route::get('/generate-excel-conditionreglement/{filter}', 'PdfExcelController@generate_excel_conditionreglement');
Route::get('/generate-excel-conditionreglement', 'PdfExcelController@generate_excel_conditionreglement');
Route::get('/generate-pdf-conditionreglement/{filter}', 'PdfExcelController@generate_pdf_conditionreglement');
Route::get('/generate-pdf-conditionreglement/', 'PdfExcelController@generate_pdf_conditionreglement');

Route::get('/generate-excel-zonedelivraison/{filter}', 'PdfExcelController@generate_excel_zonedelivraison');
Route::get('/generate-excel-zonedelivraison', 'PdfExcelController@generate_excel_zonedelivraison');
Route::get('/generate-pdf-zonedelivraison/{filter}', 'PdfExcelController@generate_pdf_zonedelivraison');
Route::get('/generate-pdf-zonedelivraison/', 'PdfExcelController@generate_pdf_zonedelivraison');

Route::get('/generate-excel-tranchehoraire/{filter}', 'PdfExcelController@generate_excel_tranchehoraire');
Route::get('/generate-excel-tranchehoraire', 'PdfExcelController@generate_excel_tranchehoraire');
Route::get('/generate-pdf-tranchehoraire/{filter}', 'PdfExcelController@generate_pdf_tranchehoraire');
Route::get('/generate-pdf-tranchehoraire/', 'PdfExcelController@generate_pdf_tranchehoraire');

Route::get('/generate-excel-typefaitdiver/{filter}', 'PdfExcelController@generate_excel_typefaitdiver');
Route::get('/generate-excel-typefaitdiver', 'PdfExcelController@generate_excel_typefaitdiver');
Route::get('/generate-pdf-typefaitdiver/{filter}', 'PdfExcelController@generate_pdf_typefaitdiver');
Route::get('/generate-pdf-typefaitdiver/', 'PdfExcelController@generate_pdf_typefaitdiver');

Route::get('/generate-excel-typeclient/{filter}', 'PdfExcelController@generate_excel_typeclient');
Route::get('/generate-excel-typeclient', 'PdfExcelController@generate_excel_typeclient');
Route::get('/generate-pdf-typeclient/{filter}', 'PdfExcelController@generate_pdf_typeclient');
Route::get('/generate-pdf-typeclient/', 'PdfExcelController@generate_pdf_typeclient');

Route::get('/generate-excel-client/{filter}', 'PdfExcelController@generate_excel_client');
Route::get('/generate-excel-client', 'PdfExcelController@generate_excel_client');
Route::get('/generate-pdf-client/{filter}', 'PdfExcelController@generate_pdf_client');
Route::get('/generate-pdf-client/', 'PdfExcelController@generate_pdf_client');

Route::get('/generate-excel-categoriefournisseur/{filter}', 'PdfExcelController@generate_excel_categoriefournisseur');
Route::get('/generate-excel-categoriefournisseur', 'PdfExcelController@generate_excel_categoriefournisseur');
Route::get('/generate-pdf-categoriefournisseur/{filter}', 'PdfExcelController@generate_pdf_categoriefournisseur');
Route::get('/generate-pdf-categoriefournisseur/', 'PdfExcelController@generate_pdf_categoriefournisseur');

Route::get('/generate-excel-fournisseur/{filter}', 'PdfExcelController@generate_excel_fournisseur');
Route::get('/generate-excel-fournisseur', 'PdfExcelController@generate_excel_fournisseur');
Route::get('/generate-pdf-fournisseur/{filter}', 'PdfExcelController@generate_pdf_fournisseur');
Route::get('/generate-pdf-fournisseur/', 'PdfExcelController@generate_pdf_fournisseur');

Route::get('/generate-excel-categorieproduit/{filter}', 'PdfExcelController@generate_excel_categorieproduit');
Route::get('/generate-excel-categorieproduit', 'PdfExcelController@generate_excel_categorieproduit');
Route::get('/generate-pdf-categorieproduit/{filter}', 'PdfExcelController@generate_pdf_categorieproduit');
Route::get('/generate-pdf-categorieproduit/', 'PdfExcelController@generate_pdf_categorieproduit');

Route::get('/generate-excel-famille/{filter}', 'PdfExcelController@generate_excel_famille');
Route::get('/generate-excel-famille', 'PdfExcelController@generate_excel_famille');
Route::get('/generate-pdf-famille/{filter}', 'PdfExcelController@generate_pdf_famille');
Route::get('/generate-pdf-famille/', 'PdfExcelController@generate_pdf_famille');

Route::get('/generate-excel-sousfamille/{filter}', 'PdfExcelController@generate_excel_sousfamille');
Route::get('/generate-excel-sousfamille', 'PdfExcelController@generate_excel_sousfamille');
Route::get('/generate-pdf-sousfamille/{filter}', 'PdfExcelController@generate_pdf_sousfamille');
Route::get('/generate-pdf-sousfamille/', 'PdfExcelController@generate_pdf_sousfamille');

Route::get('/generate-excel-typeprixdevente/{filter}', 'PdfExcelController@generate_excel_typeprixdevente');
Route::get('/generate-excel-typeprixdevente', 'PdfExcelController@generate_excel_typeprixdevente');
Route::get('/generate-pdf-typeprixdevente/{filter}', 'PdfExcelController@generate_pdf_typeprixdevente');
Route::get('/generate-pdf-typeprixdevente/', 'PdfExcelController@generate_pdf_typeprixdevente');

Route::get('/generate-excel-nomenclature/{filter}', 'PdfExcelController@generate_excel_nomenclature');
Route::get('/generate-excel-nomenclature', 'PdfExcelController@generate_excel_nomenclature');
Route::get('/generate-pdf-nomenclature/{filter}', 'PdfExcelController@generate_pdf_nomenclature');
Route::get('/generate-pdf-nomenclature/', 'PdfExcelController@generate_pdf_nomenclature');

Route::get('/generate-excel-unitedemesure/{filter}', 'PdfExcelController@generate_excel_unitedemesure');
Route::get('/generate-excel-unitedemesure', 'PdfExcelController@generate_excel_unitedemesure');
Route::get('/generate-pdf-unitedemesure/{filter}', 'PdfExcelController@generate_pdf_unitedemesure');
Route::get('/generate-pdf-unitedemesure/', 'PdfExcelController@generate_pdf_unitedemesure');

Route::get('/generate-excel-typedeconservation/{filter}', 'PdfExcelController@generate_excel_typedeconservation');
Route::get('/generate-excel-typedeconservation', 'PdfExcelController@generate_excel_typedeconservation');
Route::get('/generate-pdf-typedeconservation/{filter}', 'PdfExcelController@generate_pdf_typedeconservation');
Route::get('/generate-pdf-typedeconservation/', 'PdfExcelController@generate_pdf_typedeconservation');

Route::get('/generate-excel-produit/{filter}', 'PdfExcelController@generate_excel_produit');
Route::get('/generate-excel-produit', 'PdfExcelController@generate_excel_produit');
Route::get('/generate-pdf-produit/{filter}', 'PdfExcelController@generate_pdf_produit');
Route::get('/generate-pdf-produit/', 'PdfExcelController@generate_pdf_produit');

Route::get('/generate-excel-reservation/{filter}', 'PdfExcelController@generate_excel_reservation');
Route::get('/generate-excel-reservation', 'PdfExcelController@generate_excel_reservation');
Route::get('/generate-pdf-reservation/{filter}', 'PdfExcelController@generate_pdf_reservation');
Route::get('/generate-pdf-reservation/', 'PdfExcelController@generate_pdf_reservation');
Route::get('/generate-excel-reservation-du-jour/{etat}', 'PdfExcelController@generate_pdf_reservation');

Route::get('/generate-excel-bci/{filter}', 'PdfExcelController@generate_excel_bci');
Route::get('/generate-excel-bci', 'PdfExcelController@generate_excel_bci');
Route::get('/generate-pdf-bci/{filter}', 'PdfExcelController@generate_pdf_bci');
Route::get('/generate-pdf-bci/', 'PdfExcelController@generate_pdf_bci');
Route::get('/generate-pdf-bci-ligne/{filter}', 'PdfExcelController@generate_pdf_bci_ligne');

Route::get('/generate-excel-bce/{filter}', 'PdfExcelController@generate_excel_bce');
Route::get('/generate-excel-bce', 'PdfExcelController@generate_excel_bce');
Route::get('/generate-pdf-bce/{filter}', 'PdfExcelController@generate_pdf_bce');
Route::get('/generate-pdf-bce/', 'PdfExcelController@generate_pdf_bce');
Route::get('/generate-pdf-bce-ligne/{filter}', 'PdfExcelController@generate_pdf_bce_ligne');

Route::get('/generate-excel-be/{filter}', 'PdfExcelController@generate_excel_be');
Route::get('/generate-excel-be', 'PdfExcelController@generate_excel_be');
Route::get('/generate-pdf-be/{filter}', 'PdfExcelController@generate_pdf_be');
Route::get('/generate-pdf-be/', 'PdfExcelController@generate_pdf_be');
Route::get('/generate-pdf-be-ligne/{filter}', 'PdfExcelController@generate_pdf_be');

Route::get('/generate-excel-permission/{filter}', 'PdfExcelController@generate_excel_permission');
Route::get('/generate-excel-permission', 'PdfExcelController@generate_excel_permission');
Route::get('/generate-pdf-permission/{filter}', 'PdfExcelController@generate_pdf_permission');
Route::get('/generate-pdf-permission/', 'PdfExcelController@generate_pdf_permission');

Route::get('/generate-excel-role/{filter}', 'PdfExcelController@generate_excel_role');
Route::get('/generate-excel-role', 'PdfExcelController@generate_excel_role');
Route::get('/generate-pdf-role/{filter}', 'PdfExcelController@generate_pdf_role');
Route::get('/generate-pdf-role/', 'PdfExcelController@generate_pdf_role');

Route::get('/generate-excel-user/{filter}', 'PdfExcelController@generate_excel_user');
Route::get('/generate-excel-user', 'PdfExcelController@generate_excel_user');
Route::get('/generate-pdf-user/{filter}', 'PdfExcelController@generate_pdf_user');
Route::get('/generate-pdf-user/', 'PdfExcelController@generate_pdf_user');

Route::get('/generate-excel-contrat/{filter}', 'PdfExcelController@generate_excel_contrat');
Route::get('/generate-excel-contrat', 'PdfExcelController@generate_excel_contrat');
Route::get('/generate-excel-locationvente/{filter}', 'PdfExcelController@generate_excel_locationvente');
Route::get('/generate-excel-locationvente', 'PdfExcelController@generate_excel_locationvente');
Route::get('/generate-pdf-contrat/{filter}', 'PdfExcelController@generate_pdf_contrat');
Route::get('/generate-pdf-contratById/{id}', 'PdfExcelController@generate_pdf_contratById');
Route::get('/generate-pdf-locationventeById/{id}', 'PdfExcelController@generate_pdf_contratLocationVenteById');
Route::get('/generate-pdf-contrat/', 'PdfExcelController@generate_pdf_contrat');

// page de signature externe
Route::get('/signature-page/{id}', 'PdfExcelController@signature_page');

// etatloyer
Route::get('/generate-excel-etatloyer/{filter}', 'PdfExcelController@generate_excel_etatloyer');
Route::get('/generate-pdf-etatloyer/{filter}', 'PdfExcelController@generate_pdf_etatloyer');
//etatloyer

Route::get('/generate-excel-contratprestation/{filter}', 'PdfExcelController@generate_excel_contratprestation');
Route::get('/generate-excel-contratprestation', 'PdfExcelController@generate_excel_contratprestation');
Route::get('/generate-pdf-contratprestation/{filter}', 'PdfExcelController@generate_pdf_contratprestation');
Route::get('/generate-pdf-contratprestation/', 'PdfExcelController@generate_pdf_contratprestation');

Route::get('/generate-excel-annonce/{filter}', 'PdfExcelController@generate_excel_annonce');
Route::get('/generate-excel-annonce', 'PdfExcelController@generate_excel_annonce');
Route::get('/generate-pdf-annonce/{filter}', 'PdfExcelController@generate_pdf_annonce');
Route::get('/generate-pdf-annonce/', 'PdfExcelController@generate_pdf_annonce');

Route::get('/generate-excel-message/{filter}', 'PdfExcelController@generate_excel_message');
Route::get('/generate-excel-message', 'PdfExcelController@generate_excel_message');
Route::get('/generate-pdf-message/{filter}', 'PdfExcelController@generate_pdf_message');
Route::get('/generate-pdf-message/', 'PdfExcelController@generate_pdf_message');

Route::get('/generate-excel-cloturecaisse/{filter}', 'PdfExcelController@generate_excel_cloturecaisse');
Route::get('/generate-excel-cloturecaisse', 'PdfExcelController@generate_excel_cloturecaisse');
Route::get('/generate-pdf-cloturecaisse/{filter}', 'PdfExcelController@generate_pdf_cloturecaisse');
Route::get('/generate-pdf-cloturecaisse/', 'PdfExcelController@generate_pdf_cloturecaisse');
Route::get('/generate-pdf-cloturecaisse-ligne/{filter}', 'PdfExcelController@generate_pdf_cloturecaisse');
Route::get('/generate-ecxcel-cloturecaisse-ligne/{filter}', 'PdfExcelController@generate_excel_cloturecaisse');

Route::get('/generate-excel-bt/{filter}', 'PdfExcelController@generate_excel_bt');
Route::get('/generate-excel-bt', 'PdfExcelController@generate_excel_bt');
Route::get('/generate-pdf-bt/{filter}', 'PdfExcelController@generate_pdf_bt');
Route::get('/generate-pdf-bt/', 'PdfExcelController@generate_pdf_bt');

Route::get('/generate-excel-caisse/{filter}', 'PdfExcelController@generate_excel_caisse');
Route::get('/generate-excel-caisse', 'PdfExcelController@generate_excel_caisse');
Route::get('/generate-pdf-caisse/{filter}', 'PdfExcelController@generate_pdf_caisse');
Route::get('/generate-pdf-caisse/', 'PdfExcelController@generate_pdf_caisse');
Route::get('/generate-pdf-caisse-ligne/{filter}', 'PdfExcelController@generate_pdf_caisse');
Route::get('/generate-ecxcel-caisse-ligne/{filter}', 'PdfExcelController@generate_excel_caisse');

Route::get('/generate-excel-approcash/{filter}', 'PdfExcelController@generate_excel_approcash');
Route::get('/generate-excel-approcash', 'PdfExcelController@generate_excel_approcash');
Route::get('/generate-pdf-approcash/{filter}', 'PdfExcelController@generate_pdf_approcash');
Route::get('/generate-pdf-approcash/', 'PdfExcelController@generate_pdf_approcash');
Route::get('/generate-pdf-approcash-ligne/{filter}', 'PdfExcelController@generate_pdf_approcash');
Route::get('/generate-ecxcel-approcash-ligne/{filter}', 'PdfExcelController@generate_excel_approcash');

Route::get('/generate-excel-sortiecash/{filter}', 'PdfExcelController@generate_excel_sortiecash');
Route::get('/generate-excel-sortiecash', 'PdfExcelController@generate_excel_sortiecash');
Route::get('/generate-pdf-sortiecash/{filter}', 'PdfExcelController@generate_pdf_sortiecash');
Route::get('/generate-pdf-sortiecash/', 'PdfExcelController@generate_pdf_sortiecash');
Route::get('/generate-pdf-sortiecash-ligne/{filter}', 'PdfExcelController@generate_pdf_sortiecash');
Route::get('/generate-ecxcel-sortiecash-ligne/{filter}', 'PdfExcelController@generate_excel_sortiecash');

Route::get('/generate-excel-versement/{filter}', 'PdfExcelController@generate_excel_versement');
Route::get('/generate-excel-versement', 'PdfExcelController@generate_excel_versement');
Route::get('/generate-pdf-versement/{filter}', 'PdfExcelController@generate_pdf_versement');
Route::get('/generate-pdf-versement/', 'PdfExcelController@generate_pdf_versement');
Route::get('/generate-pdf-versement-ligne/{filter}', 'PdfExcelController@generate_pdf_versement');
Route::get('/generate-ecxcel-versement-ligne/{filter}', 'PdfExcelController@generate_excel_versement');

Route::get('/generate-excel-depense/{filter}', 'PdfExcelController@generate_excel_depense');
Route::get('/generate-excel-depense', 'PdfExcelController@generate_excel_depense');
Route::get('/generate-pdf-depense/{filter}', 'PdfExcelController@generate_pdf_depense');
Route::get('/generate-pdf-depense/', 'PdfExcelController@generate_pdf_depense');
Route::get('/generate-pdf-depense-ligne/{filter}', 'PdfExcelController@generate_pdf_depense');
Route::get('/generate-ecxcel-depense-ligne/{filter}', 'PdfExcelController@generate_excel_depense');
Route::get('/generate-ticket-depense/{id}', 'PdfExcelController@generate_ticket_depense');

Route::get('/generate-excel-inventaire/{filter}', 'PdfExcelController@generate_excel_inventaire');
Route::get('/generate-excel-inventaire', 'PdfExcelController@generate_excel_inventaire');
Route::get('/generate-pdf-inventaire/{filter}', 'PdfExcelController@generate_pdf_inventaire');
Route::get('/generate-pdf-inventaire/', 'PdfExcelController@generate_pdf_inventaire');
Route::get('/generate-pdf-inventaire-ligne/{filter}', 'PdfExcelController@generate_pdf_inventaire');
Route::get('/generate-ecxcel-inventaire-ligne/{filter}', 'PdfExcelController@generate_excel_inventaire');

Route::get('/generate-excel-production/{filter}', 'PdfExcelController@generate_excel_production');
Route::get('/generate-excel-production', 'PdfExcelController@generate_excel_production');
Route::get('/generate-pdf-production/{filter}', 'PdfExcelController@generate_pdf_production');
Route::get('/generate-pdf-production/', 'PdfExcelController@generate_pdf_production');
Route::get('/generate-pdf-production-ligne/{filter}', 'PdfExcelController@generate_pdf_production');
Route::get('/generate-ecxcel-production-ligne/{filter}', 'PdfExcelController@generate_excel_production');

Route::get('/generate-excel-employe/{filter}', 'PdfExcelController@generate_excel_employe');
Route::get('/generate-excel-employe', 'PdfExcelController@generate_excel_employe');
Route::get('/generate-pdf-employe/{filter}', 'PdfExcelController@generate_pdf_employe');
Route::get('/generate-pdf-employe/', 'PdfExcelController@generate_pdf_employe');

Route::get('/generate-excel-operateur/{filter}', 'PdfExcelController@generate_excel_operateur');
Route::get('/generate-excel-operateur', 'PdfExcelController@generate_excel_operateur');
Route::get('/generate-pdf-operateur/{filter}', 'PdfExcelController@generate_pdf_operateur');
Route::get('/generate-pdf-operateur/', 'PdfExcelController@generate_pdf_operateur');

Route::get('/generate-excel-typedecaisse/{filter}', 'PdfExcelController@generate_excel_typedecaisse');
Route::get('/generate-excel-typedecaisse', 'PdfExcelController@generate_excel_typedecaisse');
Route::get('/generate-pdf-typedecaisse/{filter}', 'PdfExcelController@generate_pdf_typedecaisse');
Route::get('/generate-pdf-typedecaisse/', 'PdfExcelController@generate_pdf_typedecaisse');

Route::get('/generate-excel-entreestock/{filter}', 'PdfExcelController@generate_excel_entreestock');
Route::get('/generate-excel-entreestock', 'PdfExcelController@generate_excel_entreestock');
Route::get('/generate-pdf-entreestock/{filter}', 'PdfExcelController@generate_pdf_entreestock');
Route::get('/generate-pdf-entreestock/', 'PdfExcelController@generate_pdf_entreestock');

Route::get('/generate-excel-sortiestock/{filter}', 'PdfExcelController@generate_excel_sortiestock');
Route::get('/generate-excel-sortiestock', 'PdfExcelController@generate_excel_sortiestock');
Route::get('/generate-pdf-sortiestock/{filter}', 'PdfExcelController@generate_pdf_sortiestock');
Route::get('/generate-pdf-sortiestock/', 'PdfExcelController@generate_pdf_sortiestock');

Route::get('/generate-excel-stockactuelproduitdepot/{filter}', 'PdfExcelController@generate_excel_stockactuelproduitdepot');
Route::get('/generate-excel-stockactuelproduitdepot', 'PdfExcelController@generate_excel_stockactuelproduitdepot');
Route::get('/generate-pdf-stockactuelproduitdepot/{filter}', 'PdfExcelController@generate_pdf_stockactuelproduitdepot');
Route::get('/generate-pdf-stockactuelproduitdepot/', 'PdfExcelController@generate_pdf_stockactuelproduitdepot');

Route::get('/generate-excel-paiement/{filter}', 'PdfExcelController@generate_excel_paiement');
Route::get('/generate-excel-paiement', 'PdfExcelController@generate_excel_paiement');
Route::get('/generate-pdf-paiement/{filter}', 'PdfExcelController@generate_pdf_paiement');
Route::get('/generate-pdf-paiement/', 'PdfExcelController@generate_pdf_paiement');

Route::get('/generate-excel-paiementcredit/{filter}', 'PdfExcelController@generate_excel_paiementcredit');
Route::get('/generate-excel-paiementcredit', 'PdfExcelController@generate_excel_paiementcredit');
Route::get('/generate-pdf-paiementcredit/{filter}', 'PdfExcelController@generate_pdf_paiementcredit');
Route::get('/generate-pdf-paiementcredit/', 'PdfExcelController@generate_pdf_paiementcredit');

Route::get('/generate-excel-categoriedepense/{filter}', 'PdfExcelController@generate_excel_categoriedepense');
Route::get('/generate-excel-categoriedepense', 'PdfExcelController@generate_excel_categoriedepense');
Route::get('/generate-pdf-categoriedepense/{filter}', 'PdfExcelController@generate_pdf_categoriedepense');
Route::get('/generate-pdf-categoriedepense/', 'PdfExcelController@generate_pdf_categoriedepense');

Route::get('/generate-excel-reglement/{filter}', 'PdfExcelController@generate_excel_reglement');
Route::get('/generate-excel-reglement', 'PdfExcelController@generate_excel_reglement');
Route::get('/generate-pdf-reglement/{filter}', 'PdfExcelController@generate_pdf_reglement');
Route::get('/generate-pdf-reglement/', 'PdfExcelController@generate_pdf_reglement');

Route::get('/generate-excel-typecontrat/{filter}', 'PdfExcelController@generate_excel_typecontrat');
Route::get('/generate-excel-typecontrat', 'PdfExcelController@generate_excel_typecontrat');
Route::get('/generate-pdf-typecontrat/{filter}', 'PdfExcelController@generate_pdf_typecontrat');
Route::get('/generate-pdf-typecontrat/', 'PdfExcelController@generate_pdf_typecontrat');

Route::get('/generate-excel-brigade/{filter}', 'PdfExcelController@generate_excel_brigade');
Route::get('/generate-excel-brigade', 'PdfExcelController@generate_excel_brigade');
Route::get('/generate-pdf-brigade/{filter}', 'PdfExcelController@generate_pdf_brigade');
Route::get('/generate-pdf-brigade/', 'PdfExcelController@generate_pdf_brigade');

Route::get('/generate-excel-fonction/{filter}', 'PdfExcelController@generate_excel_fonction');
Route::get('/generate-excel-fonction', 'PdfExcelController@generate_excel_fonction');
Route::get('/generate-pdf-fonction/{filter}', 'PdfExcelController@generate_pdf_fonction');
Route::get('/generate-pdf-fonction/', 'PdfExcelController@generate_pdf_fonction');

//Route::get('/generate-excel-typefaitdiver/{filter}', 'PdfExcelController@generate_excel_typefaitdiver');
//Route::get('/generate-excel-typefaitdiver', 'PdfExcelController@generate_excel_typefaitdiver');
//Route::get('/generate-pdf-typefaitdiver/{filter}', 'PdfExcelController@generate_pdf_typefaitdiver');
//Route::get('/generate-pdf-typefaitdiver/', 'PdfExcelController@generate_pdf_typefaitdiver');

Route::get('/generate-excel-familleaction/{filter}', 'PdfExcelController@generate_excel_familleaction');
Route::get('/generate-excel-familleaction', 'PdfExcelController@generate_excel_familleaction');
Route::get('/generate-pdf-familleaction/{filter}', 'PdfExcelController@generate_pdf_familleaction');
Route::get('/generate-pdf-familleaction/', 'PdfExcelController@generate_pdf_familleaction');

Route::get('/generate-excel-zone/{filter}', 'PdfExcelController@generate_excel_zone');
Route::get('/generate-excel-zone', 'PdfExcelController@generate_excel_zone');
Route::get('/generate-pdf-zone/{filter}', 'PdfExcelController@generate_pdf_zone');
Route::get('/generate-pdf-zone/', 'PdfExcelController@generate_pdf_zone');

Route::get('/generate-excel-typeoperateur/{filter}', 'PdfExcelController@generate_excel_typeoperateur');
Route::get('/generate-excel-typeoperateur', 'PdfExcelController@generate_excel_typeoperateur');
Route::get('/generate-pdf-typeoperateur/{filter}', 'PdfExcelController@generate_pdf_typeoperateur');
Route::get('/generate-pdf-typeoperateur/', 'PdfExcelController@generate_pdf_typeoperateur');

Route::get('/generate-excel-action/{filter}', 'PdfExcelController@generate_excel_action');
Route::get('/generate-excel-action', 'PdfExcelController@generate_excel_action');
Route::get('/generate-pdf-action/{filter}', 'PdfExcelController@generate_pdf_action');
Route::get('/generate-pdf-action/', 'PdfExcelController@generate_pdf_action');

Route::get('/generate-excel-paiementfacture/{filter}', 'PdfExcelController@generate_excel_paiementfacture');
Route::get('/generate-excel-paiementfacture', 'PdfExcelController@generate_excel_paiementfacture');
Route::get('/generate-pdf-paiementfacture/{filter}', 'PdfExcelController@generate_pdf_paiementfacture');
Route::get('/generate-pdf-paiementfacture/', 'PdfExcelController@generate_pdf_paiementfacture');
// ligne





Route::get('/generate-excel-recouvrement/{filter}', 'PdfExcelController@generate_excel_recouvrement');
Route::get('/generate-excel-recouvrement', 'PdfExcelController@generate_excel_recouvrement');
Route::get('/generate-pdf-recouvrement/{filter}', 'PdfExcelController@generate_pdf_recouvrement');
Route::get('/generate-pdf-recouvrement/', 'PdfExcelController@generate_pdf_recouvrement');

Route::get('/generate-excel-postedepense/{filter}', 'PdfExcelController@generate_excel_postedepense');
Route::get('/generate-excel-postedepense', 'PdfExcelController@generate_excel_postedepense');
Route::get('/generate-pdf-postedepense/{filter}', 'PdfExcelController@generate_pdf_postedepense');
Route::get('/generate-pdf-postedepense/', 'PdfExcelController@generate_pdf_postedepense');

Route::get('/generate-excel-souspostedepense/{filter}', 'PdfExcelController@generate_excel_souspostedepense');
Route::get('/generate-excel-souspostedepense', 'PdfExcelController@generate_excel_souspostedepense');
Route::get('/generate-pdf-souspostedepense/{filter}', 'PdfExcelController@generate_pdf_souspostedepense');
Route::get('/generate-pdf-souspostedepense/', 'PdfExcelController@generate_pdf_souspostedepense');


Route::get('/generate-excel-etatcloturecaisse-vente/{filter}', 'PdfExcelController@generate_excel_etatcloturecaisse_vente');
Route::get('/generate-pdf-etatcloturecaisse-vente/{filter}', 'PdfExcelController@generate_pdf_etatcloturecaisse_vente');

Route::get('/generate-excel-etatcloturecaisse-offert/{filter}', 'PdfExcelController@generate_excel_etatcloturecaisse_offert');
Route::get('/generate-pdf-etatcloturecaisse-offert/{filter}', 'PdfExcelController@generate_pdf_etatcloturecaisse_offert');

Route::get('/generate-excel-etatcloturecaisse-perte/{filter}', 'PdfExcelController@generate_excel_etatcloturecaisse_perte');
Route::get('/generate-pdf-etatcloturecaisse-perte/{filter}', 'PdfExcelController@generate_pdf_etatcloturecaisse_perte');

Route::get('/generate-excel-etatcloturecaisse-depense/{filter}', 'PdfExcelController@generate_excel_etatcloturecaisse_depense');
Route::get('/generate-pdf-etatcloturecaisse-depense/{filter}', 'PdfExcelController@generate_pdf_etatcloturecaisse_depense');

Route::get('/generate-pdf-etatcaisse-depense/{filter}', 'PdfExcelController@generate_pdf_etatdepensecaisse');
Route::get('/generate-excel-etatcaisse-depense/{filter}', 'PdfExcelController@generate_excel_etatdepensecaisse');

Route::get('/generate-pdf-etatcaisse-hebdomadaire/{filter}', 'PdfExcelController@generate_pdf_etatcloturehebdomadairecaisse');
Route::get('/generate-excel-etatcaisse-hebdomadaire/{filter}', 'PdfExcelController@generate_excel_etatcloturehebdomadairecaisse');

Route::get('/generate-pdf-etat-produit/{filter}', 'PdfExcelController@generate_pdf_etat_produit');

Route::get('/generate-excel-etatcloturecaisse-recap/{filter}', 'PdfExcelController@generate_excel_etatcloturecaisse_recap');
Route::get('/generate-pdf-etatcloturecaisse-recap/{filter}', 'PdfExcelController@generate_pdf_etatcloturecaisse_recap');

Route::get('/generate-excel-etatdepense-recap-fournisseur/{filter}', 'PdfExcelController@generate_excel_etatdepense_recap_entite');
Route::get('/generate-pdf-etatdepense-recap-fournisseur/{filter}', 'PdfExcelController@generate_pdf_etatdepense_recap_entite');

Route::get('/generate-excel-etatdepense-recap-fournisseur-societe/{filter}', 'PdfExcelController@generate_excel_etatdepense_recap_societe');
Route::get('/generate-pdf-etatdepense-recap-fournisseur-societe/{filter}', 'PdfExcelController@generate_pdf_etatdepense_recap_societe');

Route::get('/generate-excel-etatdepense-recap-post-entite/{filter}', 'PdfExcelController@generate_excel_etatdepense_recap_post_entite');
Route::get('/generate-pdf-etatdepense-recap-post-entite/{filter}', 'PdfExcelController@generate_pdf_etatdepense_recap_post_entite');

Route::get('/generate-excel-etatdepense-recap-post-societe/{filter}', 'PdfExcelController@generate_excel_etatdepense_recap_post_societe');
Route::get('/generate-pdf-etatdepense-recap-post-societe/{filter}', 'PdfExcelController@generate_pdf_etatdepense_recap_post_societe');

Route::get('/generate-excel-etatdepense-recap-categorie-entite/{filter}', 'PdfExcelController@generate_excel_etatdepense_recap_categorie');
Route::get('/generate-pdf-etatdepense-recap-categorie-entite/{filter}', 'PdfExcelController@generate_pdf_etatdepense_recap_categorie');

Route::get('/generate-excel-etatdepense-recap-compta-entite/{filter}', 'PdfExcelController@generate_excel_etatdepense_recap_compta_entite');
Route::get('/generate-pdf-etatdepense-recap-compta-entite/{filter}', 'PdfExcelController@generate_pdf_etatdepense_recap_compta_entite');

Route::get('/generate-pdf-transactionproduit/{request}', 'PdfExcelController@generate_pdf_transactionproduit');
Route::get('/generate-excel-transactionproduit/{request}', 'PdfExcelController@generate_excel_transactionproduit');
Route::get('/generate-excel-etatencaissement/{request}', 'PdfExcelController@generate_excel_etatencaissement');
Route::get('/generate-excel-etatencaissement/', 'PdfExcelController@generate_excel_etatencaissement');

// Route::get('/encaissement/{filter}', 'PdfExcelController@generate_pdf_encaissement');


Route::get('/generate-test', 'PdfExcelController@pdf');

//************* IMPORTS EXCEL *****************//
Route::post('/importexcelclient', 'ClientController@import');

//************* NOTIFICATIONS *****************//
Route::post('/marquer_vu', 'NotificationController@marquer_vu');

//************* CINETPAY *****************//
//Route::post('/notify', 'PaiementControllerold@notify');
//************* QR CODE *****************//
Route::get('barrecode', function () {
    return QrCode::size(200)->backgroundColor(255,55,0)->generate('W3Adda Laravel Tutorial');
});

// Route::get('qr-code-g', function () {

//     \QrCode::size(500)
//             ->format('png')
//             ->generate('ItSolutionStuff.com', public_path('assets/images/qrcode.png'));

//   return view('qrcodes.qrcode_menu');
// });
Route::get('/generate_qr_code_menu/{id}', 'QrCodeController@generate_qr_code_menu');
Route::get('/t', function () {
    event(new \App\Events\SendMessage());
    dd('Event Run Successfully.');
});
Route::get('/debug-sentry', function () {
    throw new Exception('Abdoulaye ciss test sentry sur KV1!');
});

