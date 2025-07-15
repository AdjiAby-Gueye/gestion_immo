<?php

namespace App\GraphQL\Type;

use App\Outil;


use App\Paiementloyer;
use Psy\Util\Str;
use App\Detailpaiement;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FactureeauxType  extends RefactGraphQLType
{

    protected $attributes = [
        'name' => 'Factureeaux',
        'description' => ''
    ];


    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],

                'paiementloyer_id' => ['type' => Type::int()],
                'paiementloyer' => ['type' => GraphQL::type('Paiementloyer'), 'description' => ''],
                'paiementloyers' => ['type' => Type::listOf(GraphQL::type('Paiementloyer')), 'description' => ''],


                'contrat_id' => ['type' => Type::int(), 'description' => ''],
                'contrat' => ['type' => GraphQL::type('Contrat'), 'description' => ''],
                'debutperiode' => ['type' => Type::string(), 'description' => ''],
                'finperiode' => ['type' => Type::string(), 'description' => ''],
                'finperiode_fr' => ['type' => Type::string(), 'description' => ''],

                'debutperiode_format' => ['type' => Type::string(), 'description' => ''],
                'finperiode_format' => ['type' => Type::string(), 'description' => ''],

                'finperiode_text' => ['type' => Type::string(), 'description' => ''],
                'dateecheance' => ['type' => Type::string(), 'description' => ''],
                'dateecheance_fr' => ['type' => Type::string(), 'description' => ''],
                'quantitedebut' => ['type' => Type::string(),  'description' => ''],
                'quantitefin' => ['type' => Type::string(), 'description' => ''],
                'consommation' => ['type' => Type::string(), 'description' => ''],
                'prixmetrecube' => ['type' => Type::string(), 'description' => ''],
                'soldeanterieur' => ['type' => Type::string(), 'description' => ''],
                'montantfacture' => ['type' => Type::string(), 'description' => ''],
                'montanttotalfacture' => ['type' => Type::string(), 'description' => ''],
                'montanttotalfacture_format' => ['type' => Type::string(), 'description' => ''],
                'montantfacture_format' => ['type' => Type::string(), 'description' => ''],

                // start : proviens de paiementloyers table
                'paiement_id' => ['type' => Type::int(), 'description' => ''],
                'motif_annulation_paiement' => ['type' => Type::string()],
                'date_annulation_paiement_format' => ['type' => Type::string()],
                'date_reactivation_paiement_format' => ['type' => Type::string()],
                'justificatif_paiement' => ['type' => Type::string()],
                //  end

                'is_paid' => ['type' => Type::int(), 'description' => ''],
                'is_paid_text' => ['type' => Type::string()],
                'is_paid_badge' => ['type' => Type::string()],
                'demanderesiliation_id' => ['type' => Type::int(), 'description' => ''],
                'est_activer' => ['type' => Type::int()],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveFinperiodeFrField($root, $args)
    {
        return  $this->getFromDateAttribute($root->finperiode);
    }
    protected function resolveDebutperiodeFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['debutperiode']);
    }
    protected function resolveFinperiodeFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['finperiode']);
    }
    protected function getFromDateAttribute($value) {
        $date= \Carbon\Carbon::parse($value);
        return $date->translatedFormat(' j F Y');
    }


    protected function resolveMontantTotalFactureField($root, $args)
    {

        return  intval($root->montantfacture) + intval($root->soldeanterieur);
    }
    protected function resolveMontantTotalFactureFormatField($root, $args)
    {
        return  number_format(intval($root->montantfacture) + intval($root->soldeanterieur), 0, '', ' ');
    }

    protected function resolveMontantfactureFormatField($root, $args)
    {
        $total = intval($root->montantfacture) + intval($root->soldeanterieur);
        $valeur_ht_format = Outil::convertirEnLettres($total);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }

    protected function isRegler($root, $args)
    {
        $derniereFacture = $this->getPaiementLoyer($root->id);
        $etat = 0;
        if (isset($derniereFacture)) {
            $etat = 1;
            if ($derniereFacture->est_activer == 3) {
                $etat = 3;
            }
        }
        return $etat;
    }

    protected function resolveIsPaidField($root, $args)
    {
        return  $this->isRegler($root, $args);
    }

    protected function resolveDateecheanceFrField($root, $args)
    {
        return  Outil::resolveAllDateCompletFR($root['dateecheance'], false);
    }

    protected function resolveIsPaidTextField($root, $args)
    {
        $etat = $this->isRegler($root, $args);
        $itemArray = array("etat" => $etat);
        $retour = Outil::donneEtatGeneral("factureeaux", $itemArray)['texte'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveIsPaidBadgeField($root, $args)
    {

        $etat = $this->isRegler($root, $args);
        $itemArray = array("etat" => $etat);
        $retour = Outil::donneEtatGeneral("factureeaux", $itemArray)['badge'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function getPaiementLoyer($idFacture)
    {
        return Paiementloyer::where('factureeaux_id', $idFacture)->first();
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
}
