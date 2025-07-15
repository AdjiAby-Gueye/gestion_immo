<?php

namespace App\GraphQL\Type;

use App\Outil;
use App\Avenant;
use App\Contrat;
use App\Facturelocation;
use Psy\Util\Str;
use App\Paiementloyer;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;


class FacturelocationType extends RefactGraphQLType
{
    // les attributs exposés

    protected $attributes = [
        'name' => 'Facturelocation',
        'description' => 'Facturelocation'
    ];


    public function fields(): array
    {
        return [
            'id' => ['type' => Type::id(), 'description' => ''],

            'typefacture_id' => ['type' => Type::int()],
            'typefacture' => ['type' => GraphQL::type('Typefacture'), 'description' => ''],

            'periodicite_id' => ['type' => Type::int()],
            'periodicite' => ['type' => GraphQL::type('Periodicite'), 'description' => ''],

            'paiementloyer_id' => ['type' => Type::int()],
            // 'paiementloyer' => ['type' => GraphQL::type('Paiementloyer'), 'description' => ''],
            'paiementloyers' => ['type' => Type::listOf(GraphQL::type('Paiementloyer')), 'description' => ''],

            'contrat_id' => ['type' => Type::int()],
            'contrat' => ['type' => GraphQL::type('Contrat'), 'description' => ''],
            'user' => ['type' => GraphQL::type('User'), 'description' => ''],
            'users' => ['type' => Type::listOf(GraphQL::type('User')), 'description' => ''],
            'periodicites' => ['type' => Type::listOf(GraphQL::type('Periodicite')), 'description' => ''],
            'typefactures' => ['type' => Type::listOf(GraphQL::type('Typefacture')), 'description' => ''],
            'contrats' => ['type' => Type::listOf(GraphQL::type('Contrat')), 'description' => ''],
            'montant' =>  ['type' => Type::string(), 'description' => ''],
            'objetfacture' => ['type' => Type::string(), 'description' => ''],
            'datefacture' => ['type' => Type::string(), 'description' => ''],
            'datefacture_format' => ['type' => Type::string(), 'description' => ''],

            'date_echeance' => ['type' => Type::string(), 'description' => ''],
            'date_echeance_format' => ['type' => Type::string(), 'description' => ''],
            'mois_echeance_format' => ['type' => Type::string(), 'description' => ''],
            'annee_echeance_format' => ['type' => Type::string(), 'description' => ''],
            'delai_facture_format' => ['type' => Type::string(), 'description' => ''],
            'periodes_text' => ['type' => Type::string(), 'description' => ''],

            'nbremoiscausion' => ['type' => Type::int(), 'description' => ''],
            'montantloyer_avenant' => ['type' => Type::int(), 'description' => ''],
            'montantloyerbase_avenant' => ['type' => Type::int(), 'description' => ''],
            'montantloyertom_avenant' => ['type' => Type::int(), 'description' => ''],
            'montantcharge_avenant' => ['type' => Type::int(), 'description' => ''],

            'est_activer' => ['type' => Type::int(), 'description' => ''],
            'is_paid' => ['type' => Type::int(), 'description' => ''],
            'is_paid_text' => ['type' => Type::string()],
            'is_paid_badge' => ['type' => Type::string()],

            // start : proviens de paiementloyers table
            'paiement_id' => ['type' => Type::int(), 'description' => ''],
            'motif_annulation_paiement' => ['type' => Type::string()],
            'date_annulation_paiement_format' => ['type' => Type::string()],
            'date_reactivation_paiement_format' => ['type' => Type::string()],
            'justificatif_paiement' => ['type' => Type::string()],
            //  end
            'created_at' => ['type' => Type::string(), 'description' => ''],
            'created_at_fr' => ['type' => Type::string(), 'description' => ''],
            'updated_at' => ['type' => Type::string(), 'description' => ''],
            'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
            'deleted_at' => ['type' => Type::string(), 'description' => ''],
            'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            'demanderesiliation_id' => ['type' => Type::int(), 'description' => ''],
            'montant_total' =>  ['type' => Type::string(), 'description' => ''],

            'proprietaire_id' => ['type' => Type::int()],

        ];
    }


    protected function isRegler($root, $args) :int{
        $derniereFacture = Paiementloyer::where('facturelocation_id' , $root->id)->first();
        $etat = 0;
        if (isset($derniereFacture)) {
            $etat = 1;
            if($derniereFacture->est_activer == 3) {
                $etat = 3;
            }
        }
        return $etat;
    }

    protected function getPaiementLoyer($idFacture) {
        return Paiementloyer::where('facturelocation_id' , $idFacture)->first();
    }


    protected function resolvePaiementIdField($root, $args)
    {
        $paiement = $this->getPaiementLoyer($root['id']);
        $element = null;
        if (isset($paiement) && isset($paiement->id)) {
            $element =  $paiement->id;
        }
        return  $element;
    }
    protected function resolvePeriodesTextField($root, $args)
    {
        $facture =  Facturelocation::find($root['id']);
        // dd($paiement);
        $element = null;
        if (isset($facture) && isset($facture->id)) {
            $idsP = [];
            foreach ($facture->facturelocationperiodes as  $value) {
                $idsP[]= $value['periode']['designation'];
            }
            $element = count($idsP) > 1 ? "Loyers " : "Loyer ";
            $element .= implode(", " , $idsP);
            $element .= " ";
        }
        return  $element;
    }
    protected function resolveMontantTotalField($root, $args)
    {
        $contrat = Contrat::find($root['contrat_id']);
        $element = null;
        if (isset($contrat) && isset($contrat->id)) {
            $valeur = intval($contrat->montantloyerbase) + intval($contrat->montantloyertom) + intval($contrat->montantcharge);
            $element =  number_format(($valeur * $contrat->periodicite->nbr_mois), 0, '', ' ');
        }
        return  $element;
    }
    protected function resolveMotifAnnulationPaiementField($root, $args)
    {
        $paiement = $this->getPaiementLoyer($root['id']);
        $element = null;
        if (isset($paiement) && isset($paiement->id)) {
            $element =  isset($paiement->motif_annulation_paiement) ? $paiement->motif_annulation_paiement : null;
        }
        return  $element;
    }
    protected function resolveJustificatifPaiementField($root, $args)
    {
        $paiement = $this->getPaiementLoyer($root['id']);
        $element = null;
        if (isset($paiement) && isset($paiement->id)) {
            $element = isset($paiement->justificatif_paiement) ? $paiement->justificatif_paiement : null;
        }
        return  $element;
    }

    protected function resolveDateAnnulationPaiementFormatField($root, $args)
    {
        $paiement = $this->getPaiementLoyer($root['id']);
        $date = null;
        if (isset($paiement) && isset($paiement->id)) {
            if (isset($paiement->date_annulation_paiement)) {
                $date = $this->resolveAllDateFR($paiement->date_annulation_paiement);
            }
        }
        return  $date;
    }
    protected function resolveDateReactivationPaiementFormatField($root, $args)
    {
        $paiement = $this->getPaiementLoyer($root['id']);
        $date = null;
        if (isset($paiement) && isset($paiement->id)) {
            if (isset($paiement->date_reactivation_paiement)) {
                $date = $this->resolveAllDateFR($paiement->date_reactivation_paiement);
            }
        }
        return  $date;
    }

    protected function resolveIsPaidField($root, $args)
    {
        return  $this->isRegler($root, $args);
    }

    protected function resolveIsPaidTextField($root, $args)
    {
        $etat = $this->isRegler($root, $args);
        $itemArray = array("etat" => $etat);
        $retour = Outil::donneEtatGeneral("facturelocation", $itemArray)['texte'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveIsPaidBadgeField($root, $args)
    {

        $etat = $this->isRegler($root, $args);
        $itemArray = array("etat" => $etat);
        $retour = Outil::donneEtatGeneral("facturelocation", $itemArray)['badge'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveDateEcheanceFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date_echeance']);
    }
    protected function resolveDatefactureFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datefacture']);
    }

    protected function resolveDelaiFactureFormatField($root, $args)
    {
        $mois = 5; // Remplacez cette valeur par le mois de votre choix
        $tab = explode("-" , $root['date_echeance'] );
        $mois = $tab[1];
        $dateDebutMois = \Carbon\Carbon::create(null, $mois, 1);
        // Obtenez le dixième jour du mois en ajoutant 9 jours au premier jour
        $deuxiemeJour = $dateDebutMois->addDays(9);
        // Obtenez la date au format 'Y-m-d'
        $dateDixiemeDuMois = $deuxiemeJour->format('Y-m-d');
        return $dateDixiemeDuMois;
    }
    protected function resolveAnneeEcheanceFormatField($root, $args)
    {
        $tab = explode("-" , $root['date_echeance'] );
        $mois = $tab ? $tab[0] : null;
        return $mois;
    }
    protected function resolveMoisEcheanceFormatField($root, $args)
    {

     return Outil::donneMoisEnLettres($root['date_echeance'] , true);
    }

    private function getAvenantActive($idContrat) {
        $existing = Avenant::where([["contrat_id" , $idContrat],["est_activer" , 2]])->first();
        return ($existing) ? $existing :  null;
    }
    private function getContrat($idContrat) {
        $existing = Contrat::find($idContrat);
        return ($existing) ? $existing :  null;
    }
    protected function resolveMontantloyerAvenantField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['contrat_id']);
        $contrat = $this->getContrat($root['contrat_id']);
        if ($avenant != null) {

            $yearAvenat =  explode("-",$avenant->dateenregistrement)[0];
            $yearContrat =  explode("-",$root['datefacture'])[0];
            if ($yearAvenat <= $yearContrat) {
                return ($avenant->montantloyer) ? intval($avenant->montantloyer) : null;
            }
        }
        $valeur_ht_format = intval($contrat->montantloyer);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveMontantloyerbaseAvenantField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['contrat_id']);
        $contrat = $this->getContrat($root['contrat_id']);
        if ($avenant != null) {

            $yearAvenat =  explode("-",$avenant->dateenregistrement)[0];
            $yearContrat =  explode("-",$root['datefacture'])[0];
            if ($yearAvenat <= $yearContrat) {
                return ($avenant->montantloyer) ? intval($avenant->montantloyerbase) : null;
            }
        }
        $valeur_ht_format = intval($contrat->montantloyerbase);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveMontantloyertomAvenantField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['contrat_id']);
        $contrat = $this->getContrat($root['contrat_id']);
        if ($avenant != null) {

            $yearAvenat =  explode("-",$avenant->dateenregistrement)[0];
            $yearContrat =  explode("-",$root['datefacture'])[0];
            if ($yearAvenat <= $yearContrat) {
                return ($avenant->montantloyer) ? intval($avenant->montantloyertom) : null;
            }
        }
        $valeur_ht_format = intval($contrat->montantloyertom);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveMontantchargeAvenantField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['contrat_id']);
        $contrat = $this->getContrat($root['contrat_id']);
        // dd($root['datefacture']);
        if ($avenant != null) {

            $yearAvenat =  explode("-",$avenant->dateenregistrement)[0];
            $yearContrat =  explode("-",$root['datefacture'])[0];
            if ($yearAvenat <= $yearContrat) {
                return ($avenant->montantcharge) ? intval($avenant->montantcharge) : null;
            }
        }
        $valeur_ht_format = intval($contrat->montantcharge);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }

    private function isAvenantAndGet($root) {
        $avenant = $this->getAvenantActive($root['id']);
        if ($avenant != null) {

            $yearAvenat =  explode("-",$avenant->dateenregistrement)[0];
            $yearContrat =  explode("-",$root['datefacture'])[0];
            if ($yearAvenat == $yearContrat) {
                return ($avenant->montantloyer) ? intval($avenant->montantloyer) : null;
            }
        }
        return null;
    }
}
