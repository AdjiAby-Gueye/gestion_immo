<?php

namespace App\GraphQL\Type;

use DateTime;
use App\Outil;


use Exception;
use App\Avenant;
use App\Contrat;
use App\Periode;
use App\Etatlieu;
use DateInterval;
use Psy\Util\Str;
use App\Appartement;
use App\Periodicite;
use NumberFormatter;
use App\Avisecheance;
use App\Paiementloyer;
use App\Facturelocation;
use App\Paiementecheance;
use App\Historiquerelance;
use Illuminate\Support\Carbon;
use App\Helpers\NombreEnLettre;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ContratType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Contrat',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'est_soumis' => ['type' => Type::int(), 'description' => ''],
                'est_copreuneur' => ['type' => Type::int(), 'description' => ''],
                'copreneur_id' => ['type' => Type::int() ,  'description' => 'copreneur id'],
                'copreneur' => ['type' => GraphQl::type('Copreneur') , 'description' => ''],
                'codeappartement' => ['type' => Type::string(), 'description' => ''],
                'retourcaution' => ['type' => Type::string(), 'description' => ''],
                'status' => ['type' => Type::string(), 'description' => ''],
                'document' => ['type' => Type::string(), 'description' => ''],
                'scanpreavis' => ['type' => Type::string(), 'description' => ''],
                'descriptif' => ['type' => Type::string(), 'description' => ''],
                'documentretourcaution' => ['type' => Type::string(), 'description' => ''],
                'documentrecucaution' => ['type' => Type::string(), 'description' => ''],
                'montantloyer' => ['type' => Type::string(), 'description' => ''],
                'montantloyer_avenant' => ['type' => Type::string(), 'description' => ''],
                'montantloyer_avenant_letter' => ['type' => Type::string(), 'description' => ''],
                'montantloyerformat' => ['type' => Type::string(), 'description' => ''],
                'montantloyerformatletter' => ['type' => Type::string(), 'description' => ''],
                'montantloyerbase' => ['type' => Type::string(), 'description' => ''],
                'montantloyerbase_avenant' => ['type' => Type::string(), 'description' => ''],
                'montantloyerbaseformat' => ['type' => Type::string(), 'description' => ''],
                'montantloyertom' => ['type' => Type::string(), 'description' => ''],
                'montantloyertom_avenant' => ['type' => Type::string(), 'description' => ''],
                'montantloyertomformat' => ['type' => Type::string(), 'description' => ''],
                'montantcharge' => ['type' => Type::string(), 'description' => ''],
                'montantcharge_avenant' => ['type' => Type::string(), 'description' => ''],
                'montantchargeformat' => ['type' => Type::string(), 'description' => ''],
                'tauxrevision' => ['type' => Type::string(), 'description' => ''],
                'tauxrevision_format' => ['type' => Type::string(), 'description' => ''],
                'frequencerevision' => ['type' => Type::string(), 'description' => ''],
                'frequencerevision_format' => ['type' => Type::string(), 'description' => ''],
                'dateenregistrement' => ['type' => Type::string(), 'description' => ''],
                'dateenregistrement_format' => ['type' => Type::string(), 'description' => ''],
                'daterenouvellement' => ['type' => Type::string(), 'description' => ''],
                'daterenouvellement_format' => ['type' => Type::string(), 'description' => ''],
                'datepremierpaiement' => ['type' => Type::string(), 'description' => ''],
                'rappelpaiement' => ['type' => Type::int(), 'description' => ''],
                // 'rappelpaiement_format' => ['type' => Type::string(), 'description' => ''],
                'datepremierpaiement_format' => ['type' => Type::string(), 'description' => ''],
                'dateretourcaution' => ['type' => Type::string(), 'description' => ''],
                'datedebutcontrat' => ['type' => Type::string(), 'description' => ''],
                'datedebutcontrat_format' => ['type' => Type::string(), 'description' => ''],
                'etat' => ['type' => Type::int(), 'description' => ''],
                'etat_text' => ['type' => Type::string()],
                'etat_badge' => ['type' => Type::string()],
                'typecontrat' => ['type' =>  GraphQL::type('Typecontrat')],
                'typerenouvellement' => ['type' =>  GraphQL::type('Typerenouvellement')],
                'delaipreavi' => ['type' =>  GraphQL::type('Delaipreavi')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'caution' => ['type' =>  GraphQL::type('Caution')],
                'caution_id' => ['type' => Type::int(), 'description' => ''],


                'facturelocation_id' => ['type' => Type::int(), 'description' => ''],
                'facturelocations' => ['type' => Type::listOf(GraphQL::type('Facturelocation')), 'description' => ''],

                'factureeaux_id' => ['type' => Type::int(), 'description' => ''],
                'factureeauxs' => ['type' => Type::listOf(GraphQL::type('Factureeaux'))],


                'caution_format' => ['type' => Type::string(), 'description' => ''],
                'typecontrat_id' => ['type' => Type::string(), 'description' => ''],
                'typerenouvellement_id' => ['type' => Type::string(), 'description' => ''],
                'delaipreavi_id' => ['type' => Type::string(), 'description' => ''],
                'appartement_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'assurances' => ['type' => Type::listOf(GraphQL::type('Assurance')), 'description' => ''],
                'versementloyers' => ['type' => Type::listOf(GraphQL::type('Versementloyer')), 'description' => ''],
                'versementchargecoproprietes' => ['type' => Type::listOf(GraphQL::type('Versementchargecopropriete')), 'description' => ''],
                'paiementloyers' => ['type' => Type::listOf(GraphQL::type('Paiementloyer')), 'description' => ''],
                'demanderesiliations' => ['type' => Type::listOf(GraphQL::type('Demanderesiliation')), 'description' => ''],
                'historiquerelances' => ['type' => Type::listOf(GraphQL::type('Historiquerelance')), 'description' => ''],
                // Historiquerelance
                //new fileds

                'clausepenale_words' => ['type' => Type::string(), 'description' => ''],
                'dateremisecles' => ['type' => Type::string(), 'description' => ''],
                'dateremiseclesformat' => ['type' => Type::string(), 'description' => ''],
                'apportinitial_format' => ['type' => Type::string(), 'description' => ''],
                'apportinitial_format_lettre' => ['type' => Type::string(), 'description' => ''],
                'apportinitial' => ['type' => Type::string(), 'description' => ''],
                'apportiponctuel' => ['type' => Type::string(), 'description' => ''],
                'apportiponctuel_format' => ['type' => Type::string(), 'description' => ''],
                'dateecheance' => ['type' => Type::string(), 'description' => ''],
                'dateecheanceformat' => ['type' => Type::string(), 'description' => ''],
                'dureelocationvente' => ['type' => Type::int(), 'description' => ''],
                'clausepenale' => ['type' => Type::string(), 'description' => ''],
                'fraiscoutlocationvente' => ['type' => Type::string(), 'description' => ''],
                'fraiscoutlocationvente_format' => ['type' => Type::string(), 'description' => ''],
                'acompteinitial' => ['type' => Type::string(), 'description' => ''],
                'acompteinitial_format' => ['type' => Type::string(), 'description' => ''],
                'acompteinitial_format_lettre' => ['type' => Type::string(), 'description' => ''],
                'acompteinitial_words' => ['type' => Type::string(), 'description' => ''],
                'prixvilla' => ['type' => Type::string(), 'description' => ''],
                'prixvillaformat' => ['type' => Type::string(), 'description' => ''],
                'prixvillaformat_lettre' => ['type' => Type::string(), 'description' => ''],
                'indemnite' => ['type' => Type::int(), 'description' => ''],
                'acompte_valeur' => ['type' => Type::string()],
                'acompte_percent' => ['type' => Type::string()],
                'reliquat' => ['type' => Type::string()],
                'depot_initial' => ['type' => Type::string()],
                'depot_initial_format' => ['type' => Type::string()],
                'total_loyer_format' => ['type' => Type::string()],
                'total_loyer' => ['type' => Type::string()],
                'nbr_loyer_payes_ridwan' => ['type' => Type::string()],
                'total_loyer_verser_ridwan' => ['type' => Type::string()],
                'preavis_format' => ['type' => Type::string()],
                'periodes_non_payes' => ['type' => Type::listOf(GraphQL::type('Periode'))],
                'show_echeance' => ['type' => Type::int()],
                'echeance_encours' => ['type' => Type::string()],

                'montant_dernier_facture_eau' => ['type'=> Type::string(), 'description'=> ''],
                'somme_a_restituer' => ['type'=> Type::string(), 'description'=> ''],
                'date_dernier_facture_eau' => ['type'=> Type::string(), 'description'=> ''],
                'somme_total_a_restituer' => ['type'=> Type::string(), 'description'=> ''],

                'signaturedirecteur' => ['type' => Type::string()],
                'signatureclient' => ['type' => Type::string()],
                'usersigned_id' => ['type' => Type::int()],
               

                'maturite' => ['type' => Type::int()],
                'periodicite_id' => ['type' => Type::int()],
                'periodicite' => ['type' => GraphQL::type('Periodicite')],
                'message_rappel_paiement' => ['type' => Type::string()],
                'derniere_facture_loyer' => ['type' => GraphQL::type('Facturelocation')],
                'historiquerelances' => ['type' => Type::listOf(GraphQL::type('Historiquerelance'))],
                'annexes' => ['type' => Type::listOf(GraphQL::type('Annexe'))],
                'derniere_facture_echeance' => ['type' => GraphQL::type('Avisecheance')],
                // 'factureacompte' => ['type' => GraphQL::type('Factureacompte')],

                'nombre_relance' => ['type' => Type::int()],



                'nombre_relance_echeance' => ['type' => Type::int()],
                'nombre_relance_loyer' => ['type' => Type::int()],
                'frais_gestion' => ['type' => Type::int()],
                'frais_gestion_format' => ['type' => Type::string()],
                'recap_amount_ridwan' => ['type' => Type::string()],
                'recap_amount_ridwan_format' => ['type' => Type::string()],
                'recap_amount_ridwan_format' => ['type' => Type::string()],

                'numerodossier' => ['type' => Type::string()],

                'ridwan_montant_verse' => ['type' => Type::string()],
                'ridwan_montant_restant' => ['type' => Type::string()],
                'prixtotalvilla' => ['type' => Type::string()],
                'prixtotalvilla_format' => ['type' => Type::string()],
                'prixtotalvilla_words' => ['type' => Type::string()],

                'ville' => ['type' => Type::string()],
                'situationfamiliale' => ['type' => Type::string()],
                'codepostal' => ['type' => Type::string()],
                'nationalite' => ['type' => Type::string()],
                'njf' => ['type' => Type::string()],
                'relance_type' => ['type' => Type::string()],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],
                // avisecheances
                'avisecheances' => ['type' => Type::listOf(GraphQL::type('Avisecheance')), 'description' => ''],


                // new retours

                // montantcommission refactor
                'montantcommission' => ['type' => Type::string(), 'description' => ''],


                'fraisdegestion' => ['type' => Type::string(), 'description' => ''],
                'fraislocative' => ['type' => Type::string(), 'description' => ''],
                'codepartamortissemnt' => ['type' => Type::string(), 'description' => ''],

                'fraisdegestion_format' => ['type' => Type::string(), 'description' => ''],
                'fraislocative_format' => ['type' => Type::string(), 'description' => ''],
                'codepartamortissemnt_format' => ['type' => Type::string(), 'description' => ''],

                'ilot' => ['type' => Type::int()],
                'lot' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],

                //Infos beneficiaire
                'nomcompletbeneficiaire' => ['type' => Type::string()],
                'telephonebeneficiaire' => ['type' => Type::string()],
                'emailbeneficiaire'     => ['type' => Type::string()],

            ];
    }


    // montantcommission resolve

    protected function resolveMontantcommissionField($root, $args)
    {
        // appartement commition

        $montantcommission = 0;
        $appartement = Appartement::find($root['appartement_id']);
        
        if ($appartement) {
            
            $commipourcentage = $appartement->commissionpourcentage;
            if ($commipourcentage > 0) {
                $montantcommission = $root['montantloyer'] * $commipourcentage / 100;
                return $montantcommission;

            }
            return  $appartement->commissionvaleur;
        }


        return $montantcommission;



    }

    protected function resolveRelanceTypeField($root , $args) {
        $valeur = "Bonjour cher client,\nPour non règlement de votre loyer à date échue, une pénalité de 10% du montant vous sera appliquée à votre prochain paiement.";
        return $valeur;
    }
    protected function resolveFraisdegestionFormatField($root , $args){
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['fraisdegestion']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }
    protected function resolveFraislocativeFormatField($root , $args){
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['fraislocative']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }
    protected function resolveApportiponctuelFormatField($root , $args){
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['apportiponctuel']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }
    protected function resolveCodepartamortissemntFormatField($root , $args){
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['codepartamortissemnt']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }

    // resolve qui retoune la date de la derniere facture d'eaux en format FR(jeudi 7 novembre 2019)
    protected function resolveDateDernierFactureEauField($root, $args) {
        $factureeaux = DB::table('factureeauxs')
        ->where('contrat_id', $root['id'])
        ->orderBy('id', 'desc')
        ->first();
        if ($factureeaux) {
            return $this->getFromDateAttribute($factureeaux->finperiode);
        } else {
            return "";
        }
    }



    protected function getFromDateAttribute($value) {
        $date= \Carbon\Carbon::parse($value);
        return $date->translatedFormat(' j F Y');
    }



    // resolve qui prend la caution - la dernier facture eau - montant facture intervention

    protected function resolveSommeARestituerField($root, $args) {
        $factureeaux = DB::table('factureeauxs')
        ->where('contrat_id', $root['id'])
        ->orderBy('id', 'desc')
        ->first();
        if ($factureeaux) {
            return  $root['caution']['montantcaution']-$factureeaux->montantfacture  -$factureeaux->soldeanterieur ;
        } else {
            return 0;
        }
    }

    protected function resolveSommeTotalARestituerField($root, $args) {
        error_log('Contenu de $root : ' . print_r($root, true));
        return  $root['etatlieu_sortie'];
    }


    // un resolve qui retourne le montant total de la facture de la dernier facture d'eau
    protected function resolveMontantDernierFactureEauField($root, $args)
    {
        $factureeaux = DB::table('factureeauxs')
            ->where('contrat_id', $root['id'])
            ->orderBy('id', 'desc')
            ->first();
        if ($factureeaux) {
            return $factureeaux->montantfacture + $factureeaux->soldeanterieur;
        } else {
            return 0;
        }
    }


    protected function resolveRidwanMontantVerseField($root, $args) {

        $total = self::getAmountVerseRiwan($root);
        // dd($total);
        return number_format($total,0,' ' , ' ');
    }
    static function getAmountVerseRiwan($root) {
        $contratId = $root['id'];

        // Récupérer le montant total versé en convertissant explicitement la colonne en nombre
        $sommeDesMontants = Avisecheance::join("paiementecheances" , 'paiementecheances.avisecheance_id', '=', 'avisecheances.id')
                            ->where('avisecheances.contrat_id', $contratId)
                            ->where('avisecheances.est_activer', 2)
                            ->selectRaw('SUM(CAST(avisecheances.amortissement AS INTEGER)) AS total')
                            ->first()->total;

         $total = $sommeDesMontants + intval($root['apportinitial']) + intval($root['apportiponctuel']);
         return $total;
    }
    protected function resolveNbrLoyerPayesRidwanField($root , $args) {
        $contratId = $root['id'];
        $nbr = Paiementecheance::join('avisecheances', 'paiementecheances.avisecheance_id', '=', 'avisecheances.id')
        ->where('avisecheances.contrat_id', $contratId)
        ->where('avisecheances.est_activer', 2)
        ->count('paiementecheances.*');
        return $nbr;
    }
    protected function resolveTotalLoyerVerserRidwanField($root , $args) {
        $contratId = $root['id'];
        $sommeDesMontants = Paiementecheance::join('avisecheances', 'paiementecheances.avisecheance_id', '=', 'avisecheances.id')
            ->where('avisecheances.contrat_id', $contratId)
            ->where('avisecheances.est_activer', 2)
            ->sum('paiementecheances.montant');
         return number_format($sommeDesMontants,0,' ' , ' ');
    }
    protected function resolveRidwanMontantRestantField($root, $args) {
         $verse = self::getAmountVerseRiwan($root);
         $total = intval($root['prixvilla']) - $verse ;
         $total = number_format($total,0,' ' , ' ');
         return $total;
    }
    protected function resolveEcheanceEncoursField($root, $args)
    {

        // $dateSignatureContrat = new DateTime($root['dateenregistrement']);
        // $frequence = Periodicite::find($root['periodicite_id']);
        // // Fréquence de paiement en mois
        // $frequencePaiementMois = $frequence->nbr_mois;
        // $aujourdhui = new DateTime();

        // // Comparaison de la date d'aujourd'hui avec la date de signature du contrat
        // if ($aujourdhui >= $dateSignatureContrat) {
        //     // Calcul de la différence en mois entre aujourd'hui et la date de signature du contrat
        //     $differenceEnMois = $aujourdhui->diff($dateSignatureContrat)->m;
        //     // Calcul de l'échéance actuelle
        //     $echeanceActuelle = clone $dateSignatureContrat;
        //     $echeanceActuelle->add(new DateInterval("P{$differenceEnMois}M"));
        //     // Formatage de la date d'échéance actuelle
        //     $dateEcheanceActuelle = $echeanceActuelle->format('d/m/Y');
        // } else {
        //     // La date de signature du contrat est dans le futur, donc l'échéance actuelle est la date de signature elle-même.
        //     $dateEcheanceActuelle = $dateSignatureContrat->format('d/m/Y');
        // }

        // return $dateEcheanceActuelle;


    //     $contratID = $root['id'];

    //     $echeanceActuelle = DB::table('contrats as C')
    //     ->select('C.id as ContratID', 'C.dateenregistrement as DateSignatureContrat')

    //     ->join('periodicites as P', 'C.periodicite_id', '=', 'P.id')
    //     ->leftJoin('paiementloyers as PL', 'C.id', '=', 'PL.contrat_id')
    //     ->selectRaw('P.id AS FrequencePaiementMois')
    //     ->selectRaw('COUNT(PL.id) as NombrePaiementsEffectues')
    //     ->selectRaw("C.dateenregistrement + interval '1 month' * COUNT(PL.id) as DateEcheanceActuelle")
    //     ->where('C.id', $contratID)
    //     ->groupBy('C.id')
    //     ->first();
    // return $echeanceActuelle;


    $dateSignatureContrat = new DateTime($root['dateenregistrement']);
    $frequence = Periodicite::find($root['periodicite_id']);
    // Fréquence de paiement en mois
    $frequencePaiementMois = $frequence->nbr_mois;
    $aujourdhui = new DateTime();

    // Calcul de la différence en mois entre aujourd'hui et la date de signature du contrat
    $differenceEnMois = $aujourdhui->diff($dateSignatureContrat)->m;

    // Vérifier les paiements effectués
    $paiements = Paiementloyer::where('contrat_id', $root['id'])->get();
    $nombrePaiementsEffectues = count($paiements);
        // dd($nombrePaiementsEffectues);
    // Si des paiements ont été  effectués
    $echeanceActuelle = clone $dateSignatureContrat;
    if ($nombrePaiementsEffectues > 0) {
        // Calcul de l'échéance actuelle en tenant compte des paiements
        $moisRestants = $differenceEnMois + $nombrePaiementsEffectues * $frequencePaiementMois;

        $echeanceActuelle->add(new DateInterval("P{$moisRestants}M"));
    } else {
        // Aucun paiement n'a été effectué, l'échéance actuelle reste la même que la date de signature
        $echeanceActuelle->add(new DateInterval("P"."1"."M"));
    }

    // Formatage de la date d'échéance actuelle
    $dateEcheanceActuelle = $echeanceActuelle->format('d/m/Y');

    return $dateEcheanceActuelle;


    }
//     protected function resolveEcheanceEncoursField($root, $args)
// {
//     $dateSignatureContrat = new DateTime($root['dateenregistrement']);
//     $frequence = Periodicite::find($root['periodicite_id']);
//     $frequencePaiementMois = $frequence->nbr_mois;
//     $aujourdhui = new DateTime();

//     if ($aujourdhui >= $dateSignatureContrat) {
//         $differenceEnMois = $aujourdhui->diff($dateSignatureContrat)->m;
//         $nombreEcheances = floor($differenceEnMois / $frequencePaiementMois);

//         // Calcul de l'échéance actuelle
//         $echeanceActuelle = clone $dateSignatureContrat;
//         $var = $nombreEcheances * $frequencePaiementMois;
//         $echeanceActuelle->add(new DateInterval("P".$var."M"));

//         // Formatage de la date d'échéance actuelle
//         $dateEcheanceActuelle = $echeanceActuelle->format('d/m/Y');
//     } else {
//         $dateEcheanceActuelle = $dateSignatureContrat->format('d/m/Y');
//     }

//     return $dateEcheanceActuelle;
// }

    protected function resolveShowEcheanceField($root, $args)
    {
        // (au format "YYYY-MM-DD")
        $dayOfEcheance = new DateTime($root['dateecheance']); // jour d'échéance
        $dayOfday =new DateTime();
        return $dayOfday->diff($dayOfEcheance)->days <= 7;
    }
    protected function resolveNombreRelanceField($root, $args)
    {
        return Historiquerelance::where("contrat_id", $root['id'])->count();
    }
    protected function resolveNombreRelanceEcheanceField($root, $args)
    {
        $derniereFacture = DB::table('avisecheances')
        ->where("contrat_id", $root['id'])
        ->where("est_activer", 1)
        ->orderBy('created_at', 'desc')
        ->first();
        if ($derniereFacture == null) {
            return 0;
        }
        return Historiquerelance::where("avisecheance_id", $derniereFacture->id)->count();
    }
    protected function resolveNombreRelanceLoyerField($root, $args)
    {
        $derniereFacture = Facturelocation::where("contrat_id", $root['id'])
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('paiementloyers')
                ->whereRaw('facturelocations.id = paiementloyers.facturelocation_id');
        })
        ->orderBy('created_at', 'desc')
        ->first();
        if ($derniereFacture == null) {
            return 0;
        }
        return Historiquerelance::where("facturelocation_id", $derniereFacture->id)->count();
    }

    // protected function resolvePeriodesNonPayesField($root, $args)
    // {

    //     $periodicite = Periodicite::find($root['periodicite_id']);
    //     if ($periodicite != null) {
    //         $periodicite = strtolower($periodicite->designation);
    //         // Comparez les périodes avec les paiements et filtrez les périodes non payées
    //         $periodes = Periode::all();
    //         $paiements = Paiementloyer::where("contrat_id", $root['id'])->get();

    //         $periodesNonPayes = collect();
    //         if ($paiements->isEmpty()) {
    //             if ($periodicite === "trimestrielle") {
    //                 // Si la périodicité est "trimestrielle", retournez les 3 premières périodes
    //                 return $periodes->take(3);
    //             } elseif ($periodicite === "mensuelle") {
    //                 // Si la périodicité est "mensuelle", retournez toutes les périodes
    //                 return $periodes->take(1);
    //             }
    //         }

    //         if ($periodicite === "trimestrielle") {
    //             // Si la périodicité est "trimestrielle", retournez les 3 premières périodes non payées
    //             foreach ($paiements as $paiement) {
    //                 $detailsPaiement = $paiement->detailpaiements;
    //                 $periodesNonPayes = $periodesNonPayes->merge(
    //                     $periodes->filter(function ($periode) use ($detailsPaiement) {
    //                         return !$detailsPaiement->contains(function ($detailPaiement) use ($periode) {
    //                             return $detailPaiement->periode_id == $periode->id;
    //                         });
    //                     })
    //                 );
    //                 if ($periodesNonPayes->count() >= 3) {
    //                     break;
    //                 }
    //             }

    //             $troisPremiersMois = $periodesNonPayes->take(3);
    //             return $troisPremiersMois;
    //         } elseif ($periodicite === "mensuelle") {
    //             // Si la périodicité est "mensuelle", retournez le premier mois non payé
    //             foreach ($periodes as $periode) {
    //                 if (!$paiements->flatMap->detailpaiements->contains('periode_id', $periode->id)) {
    //                     return [$periode];
    //                 }
    //             }
    //         }
    //     }
    //    return null;

    // }

    protected function resolveAcomptePercentField($root, $args)
    {
        $prixvilla = intval($root['prixvilla']);
        $acompteinitial = intval($root['acompteinitial']);
        $apportinitial = intval($root['apportinitial']);

        if ($apportinitial === 0) {
            return 0; // To avoid division by zero
        }

        // $percentage = round(($apportinitial / $prixvilla) * 100, 2);
        $percentage = round(($apportinitial / $prixvilla) * 100, 2);

        return $percentage;
    }

    protected function resolvePeriodesNonPayesField($root, $args)
    {
        $periodicite = Periodicite::find($root['periodicite_id']);

        if ($periodicite != null) {
            $periodicite = strtolower($periodicite->designation);
            $periodes = Periode::all();
            $paiements = Paiementloyer::where("contrat_id", $root['id'])->get();

            $periodesNonPayes = collect();

            // Obtenez le mois actuel
            $moisEnCours = date('n'); // n donne le mois actuel (1 pour janvier, 2 pour février, etc.)

            if ($periodicite === "trimestrielle") {
                // Si la périodicité est "trimestrielle", retournez les 3 prochains mois non payés
                $trimestreEnCours = ceil($moisEnCours / 3); // Calculez le trimestre en cours

                $moisPayes = $paiements->flatMap->detailpaiements->pluck('periode_id')->map(function ($periodeId) {
                    return Periode::find($periodeId)->id;
                });

                $moisNonPayes = $periodes->filter(function ($periode) use ($moisPayes, $moisEnCours, $trimestreEnCours) {
                    return $periode->id > $moisEnCours && ceil($periode->id / 3) == $trimestreEnCours && !$moisPayes->contains($periode->id);
                });

                return $moisNonPayes;
            } elseif ($periodicite === "mensuelle") {
                // Si la périodicité est "mensuelle", retournez les prochains mois non payés
                $moisPayes = $paiements->flatMap->detailpaiements->pluck('periode_id')->map(function ($periodeId) {
                    return Periode::find($periodeId)->id;
                });

                $moisNonPayes = $periodes->filter(function ($periode) use ($moisPayes, $moisEnCours) {
                    return $periode->id > $moisEnCours && !$moisPayes->contains($periode->id);
                });

                return $moisNonPayes->take(1);
            }

            return $periodesNonPayes->isEmpty() ? null : $periodesNonPayes;
        }

        return null;
    }


    protected function resolveEtatlieuSortieField($root, $args)
    {
        $contratid = $root['appartement_id'];
        $locataireid = $root['locataire_id'];
        $sortie = Etatlieu::where([['appartement_id', $contratid], ['locataire_id', $locataireid], ['type', 'sortie']])->first();
        return $sortie;
    }
    protected function resolveEtatlieuEntreeField($root, $args)
    {
        $contratid = $root['appartement_id'];
        $locataireid = $root['locataire_id'];
        $sortie = Etatlieu::where([['appartement_id', $contratid], ['locataire_id', $locataireid], ['type', '!=', 'sortie']])->first();
        return $sortie;
    }
    protected function resolveEtatTextField($root, $args)
    {

        $contrat = Contrat::find($root['id']);

        if (isset($contrat->locataire) && isset($contrat->locataire->id)) {
            if (isset($contrat->locataire->entite) && isset($contrat->locataire->entite->id)) {
                if ($contrat->locataire->entite->code == "RID") {
                    $itemArray = array("etat" => $root['etat']);
                    $retour = Outil::donneEtatGeneral("locationvente", $itemArray)['texte'];
                    if (empty($retour)) {
                        $retour = "";
                    }
                    return $retour;
                }
            }
        }

        $itemArray = array("etat" => $root['etat']);
        $retour = Outil::donneEtatGeneral("contrat", $itemArray)['texte'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveEtatBadgeField($root, $args)
    {

        $contrat = Contrat::find($root['id']);

            if (isset($contrat->locataire) && isset($contrat->locataire->id)) {
                if (isset($contrat->locataire->entite) && isset($contrat->locataire->entite->id)) {
                    if ($contrat->locataire->entite->code == "RID") {
                        $itemArray = array("etat" => $root['etat']);
                        $retour = Outil::donneEtatGeneral("locationvente", $itemArray)['badge'];
                        if (empty($retour)) {
                            $retour = "";
                        }
                        return $retour;
                    }
                }
            }

        $itemArray = array("etat" => $root['etat']);
        $retour = Outil::donneEtatGeneral("contrat", $itemArray)['badge'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveDateenregistrementFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['dateenregistrement']);
    }


    protected function resolveDaterenouvellementFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['daterenouvellement']);
    }

    protected function resolveDatepremierpaiementFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datepremierpaiement']);
    }
    protected function resolveRappelpaiementFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['rappelpaiement']);
    }

    protected function resolveDatedebutcontratFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datedebutcontrat']);
    }
    protected function resolveDateremiseclesformatField($root, $args)
    {
        return $this->resolveAllDateFR($root['dateremisecles']);
    }
    protected function resolveDateecheanceformatField($root, $args)
    {
        return $this->resolveAllDateFR($root['dateecheance']);
    }

    protected function resolveMontantloyerformatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montantloyer']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveFraisGestionFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['frais_gestion']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolvePrixtotalvillaFormatField($root, $args)
    {
        $valeur = intval($root['fraiscoutlocationvente']) + intval($root['prixvilla']);
        $valeur_ht_format = Outil::formatPrixToMonetaire($valeur);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolvePrixtotalvillaWordsField($root, $args)
    {
        $valeur = intval($root['fraiscoutlocationvente']) + intval($root['prixvilla']);
        $valeur_ht_format = NombreEnLettre::CustomNumberToWords($valeur);

        return $valeur_ht_format;
    }
    protected function resolveClausepenaleWordsField($root, $args)
    {
        $valeur = intval($root['clausepenale']);
        $valeur_ht_format = NombreEnLettre::CustomNumberToWords($valeur);

        return $valeur_ht_format;
    }
    protected function resolveAcompteinitialWordsField($root, $args)
    {
        $valeur_ht_format = NombreEnLettre::CustomNumberToWords(intval($root['acompteinitial']));

        return $valeur_ht_format;
    }
    protected function resolvePrixtotalvillaField($root, $args)
    {
        $valeur = intval($root['fraiscoutlocationvente']) + intval($root['prixvilla']);

        return $valeur;
    }
    protected function resolveTotalLoyerFormatField($root, $args)
    {
        $valeur = intval($root['montantloyerbase']) + intval($root['montantloyertom']) + intval($root['montantcharge']);
        $valeur_ht_format = Outil::formatPrixToMonetaire($valeur);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }

    protected function resolveTotalLoyerField($root, $args)
    {
        $valeur = intval($root['montantloyerbase']) + intval($root['montantloyertom']) + intval($root['montantcharge']);
        return $valeur;
    }


    protected function resolveTauxrevisionFormatField($root, $args)
    {
        $valeur_ht_format = Outil::convertirEnLettres($root['tauxrevision']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }

    protected function resolveCautionFormatField($root, $args)
    {
        if (isset($root['caution']) && isset($root['caution']['montantcaution'])) {
            $valeur_ht_format = Outil::convertirEnLettres($root['caution']['montantcaution']);
            if (empty($valeur_ht_format)) {
                $valeur_ht_format = "";
            }

            return $valeur_ht_format;
        }
        return null;

    }

    protected function resolvePreavisFormatField($root, $args)
    {
        $valeur =  explode(" ", '', $root['delaipreavi']['designation']);
        // dd($valeur);
        $valeur = intval($valeur[0]);
        $valeur_ht_format = Outil::convertirEnLettres($valeur);
        if (!$valeur_ht_format) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveFrequencerevisionFormatField($root, $args)
    {
        $valeur_ht_format = Outil::convertirEnLettres($root['frequencerevision']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveMontantloyerformatletterField($root, $args)
    {
        $valeur_ht_format = Outil::convertirEnLettres($root['montantloyer']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }


    protected function resolveMontantloyerbaseformatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montantloyerbase']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    private function getAvenantActive($idContrat) {
        $existing = Avenant::where([["contrat_id" , $idContrat],["est_activer" , 2]])->first();
        return ($existing) ? $existing :  null;
    }
    protected function resolveMontantloyerAvenantLetterField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['id']);
        if ($avenant != null) {

            $v = Outil::convertirEnLettres($avenant->montantloyer);
            if (empty($v)) {
                $v = "";
            }
            return $v;
        }
        $valeur_ht_format = Outil::convertirEnLettres($root['montantloyer']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveMontantloyerAvenantField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['id']);
        // dd($avenant);
        if ($avenant != null) {
            $yearAvenat =  explode("-",$avenant->dateenregistrement)[0];
            $yearAvenat =  explode("-",$root['dateenregistrement'])[0];
            return intval($avenant->montantloyer);
        }
        $valeur = intval($root['montantloyer']);
        if (empty($valeur)) {
            $valeur = "";
        }

        return $valeur;
    }
    protected function resolveMontantloyerbaseAvenantField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['id']);
        if ($avenant != null) {

            $valeur_ht_format = Outil::formatPrixToMonetaire($avenant->montantloyerbase);
            if (empty($valeur_ht_format)) {
                $valeur_ht_format = "";
            }
            return $valeur_ht_format;
        }
        $valeur = Outil::formatPrixToMonetaire($root['montantloyerbase']);
        if (empty($valeur)) {
            $valeur = "";
        }

        return $valeur;
    }
    protected function resolveMontantloyertomAvenantField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['id']);
        if ($avenant != null) {
            $valeur_ht_format = Outil::formatPrixToMonetaire($avenant->montantloyertom);
            if (empty($valeur_ht_format)) {
                $valeur_ht_format = "";
            }
            return $valeur_ht_format;
        }
        $valeur = Outil::formatPrixToMonetaire($root['montantloyertom']);
        if (empty($valeur)) {
            $valeur = "";
        }
        return $valeur;
    }
    protected function resolveMontantchargeAvenantField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['id']);
        if ($avenant != null) {
            $valeur_ht_format = Outil::formatPrixToMonetaire($avenant->montantcharge);
            if (empty($valeur_ht_format)) {
                $valeur_ht_format = "";
            }
            return $valeur_ht_format;
        }
        $valeur = Outil::formatPrixToMonetaire($root['montantcharge']);
        if (empty($valeur)) {
            $valeur = "";
        }
        return $valeur;
    }
    protected function resolveMontantloyertomformatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montantloyertom']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }


    protected function resolveMontantchargeformatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montantcharge']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolvePrixvillaformatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['prixvilla']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolvePrixvillaformatLettreField($root, $args)
    {
        $valeur_ht_format = Outil::convertirEnLettres($root['prixvilla']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveApportinitialFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['apportinitial']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }

    protected function resolveFraiscoutlocationventeFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['fraiscoutlocationvente']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }


    protected function resolveAcompteValeurField($root, $args)
    {
        // $valeur =  self::calculAcomptValue($root['prixvilla'] , $root['acompteinitial']);
        $valeur = ($root['prixvilla'] * 20) / 100;
        $valeur_ht_format = Outil::formatPrixToMonetaire(round($valeur, 1));
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }
    protected function resolveAcompteinitialFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['acompteinitial']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }
    function numberToWords($number)
    {
        // Create a NumberFormatter instance for the desired locale (en_US in this example)
        $formatter = new NumberFormatter('fr_FR', NumberFormatter::SPELLOUT);

        // Format the number into words
        $words = $formatter->format($number);

        return $words;
    }
    protected function resolveAcompteinitialFormatLettreField($root, $args)
    {
        App::setLocale("fr");

        $valeur_ht_format = $this->numberToWords(intval($root['acompteinitial']));
        // NumberToWord::convertNumberToWord(1364, 'en');

    //    $result =  NumberToWord::convertNumberToWord($valeur_ht_format, 'en'); // outputs "five thousand one hundred twenty"

        return $valeur_ht_format;
    }
    // apportinitial_format_lettre
    protected function resolveApportinitialFormatLettreField($root, $args)
    {
        App::setLocale("fr");

        $valeur_ht_format = $this->numberToWords(intval($root['apportinitial']));

        return $valeur_ht_format;
    }

    protected function resolveReliquatField($root, $args)
    {
        $valeur = intval($root['prixvilla']) - intval($root['depot_initial']);
        $valeur_ht_format = Outil::formatPrixToMonetaire($valeur);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }
    protected function resolveRecapAmountRidwanField($root, $args)
    {
        $valeur = intval($root['montantloyer']) + intval($root['frais_gestion']);

        return $valeur;
    }
    protected function resolveRecapAmountRidwanFormatField($root, $args)
    {
        $valeur = intval($root['montantloyer']) + intval($root['frais_gestion']);
        $valeur_ht_format = Outil::formatPrixToMonetaire($valeur);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }
    protected function resolveRecapAmountRidwanLetterField($root, $args)
    {
        $valeur = intval($root['montantloyer']) + intval($root['frais_gestion']);
        $valeur_ht_format = Outil::convertirEnLettres($valeur);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }

    protected function resolveDepotInitialFormatField($root, $args)
    {
        $valeur = $root['fraiscoutlocationvente'] + self::calculAcomptValue($root['prixvilla'], 20);
        $valeur_ht_format = Outil::formatPrixToMonetaire($valeur);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        return $valeur_ht_format;
    }

    static function calculAcomptValue($prixvilla, $acomptePercent)
    {
        return $prixvilla * $acomptePercent / 100;
    }

    protected function ResolveMessageRappelPaiementField($root, $args)
    {

        // try{

        // }catch(Exception $e){
        //     return "";
        // }

        $appartement = Appartement::find($root['appartement_id']);
        $periodicite = Periodicite::find($root['periodicite_id']);
        $getPerText = $periodicite->designation == "Mensuelle" ? "mois" : "trimestre";
        $periodes =  $this->resolvePeriodesNonPayesField($root, $args);
        // Créez un tableau pour stocker les désignations
        $moisapayes = [];
        foreach ($periodes as $periode) {
            if (isset($periode->designation)) {
                $moisapayes[] = $periode->designation;
            }
        }
        $date = date('d');
        $year = date('Y');
        $text = "Bonjour \n Nous sommes le $date du mois et les loyers du $getPerText ";
        foreach ($moisapayes as $key => $mois) {
            $text .= $mois;
            if ($key < count($moisapayes) - 1) {
                $text .= ', ';
            }
        }
        $textAppartement = "";
        if ($appartement->nom) {
            $text .= " $year pour l'appartement que nous vous louons à la Résidence " . $appartement->immeuble->nom . " / " . $appartement->nom;
        } else {
            $text .= " $year pour la villa que nous vous louons à " . $appartement->ilot->adresse . " / lot N° " . $appartement->lot . "/ ilot N° " . $appartement->ilot->numero;
        }

        // $text .= " $year pour l'appartement que nous vous louons à la Résidence ".$appartement->immeuble->nom." / ".$appartement->nom;
        $text .= " ne sont toujours pas réglés. \n";
        $text .= "Merci de prendre les dispositions nécessaires pour un règlement immédiat.";

        return $text;
    }

    // protected function resolveDerniereFactureLoyerField($root, $args) {
    //    return DB::table('facturelocations')->where("contrat_id" , $root['id'])->orderBy('created_at', 'desc')->first();
    // }

    protected function resolveDerniereFactureLoyerField($root, $args)
    {
        $derniereFacture = DB::table('facturelocations')
            ->where("contrat_id", $root['id'])
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('paiementloyers')
                    ->whereRaw('facturelocations.id = paiementloyers.facturelocation_id');
            })
            ->orderBy('created_at', 'desc')
            ->first();

        return $derniereFacture;
    }

    protected function resolveDerniereFactureEcheanceField($root, $args)
    {
        // Outil::getAllItemsWithGraphQl('avisecheances',"contrat_id:".$root['id'].",est_activer:1");
        // $derniereFacture = DB::table('avisecheances')
        //     ->where("contrat_id", $root['id'])
        //     ->where("est_activer", 1)
        //     ->orderBy('created_at', 'desc')
        //     ->first();

        // return json_encode($derniereFacture);

        //$avisecheances = Outil::getAllItemsWithGraphQl('avisecheances', "contrat_id:" . $root['id'] . ",est_activer:1");
        $avisecheances = Avisecheance::where('contrat_id',$root['id'])->where('est_activer',1)->orderBy('created_at', 'DESC')->first();




//        // dd($avisecheances);
//        // Check if the array is not empty
//        if (!empty($avisecheances) && count($avisecheances) > 0) {
//            // Sort the array by 'created_at' in descending order
//            usort($avisecheances, function ($a, $b) {
//                if(isset($a['created_at']) && isset($b['created_at'])){
//                    return strtotime($b['created_at']) - strtotime($a['created_at']);
//                }
//            });
//            // Retrieve the first item after sorting
//            $query = Outil::getAllItemsWithGraphQl('avisecheances', "id:".$avisecheances[0]['id']);
//
//            $derniereFacture = $query[0];
//            // Return the first item
//            return $derniereFacture;
//            //return null;
//        } else {
//            // Return null or handle the case when the array is empty
//            return null;
//        }
        return $avisecheances;

    }
}
