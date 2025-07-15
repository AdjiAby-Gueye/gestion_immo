<?php

namespace App\GraphQL\Type;

use App\Outil;


use App\Paiementecheance;
use Psy\Util\Str;
use App\Avisecheance;
use Illuminate\Support\Carbon;
use App\Helpers\NombreEnLettre;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AvisecheanceType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Avisecheance',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],

                'objet' => ['type' => Type::string(), 'description' => ''],
                'fraisdelocation' => ['type' => Type::string()],
                'amortissement' => ['type' => Type::string()],
                'copreneur' => ['type' => Type::string()],
                'code' => ['type' => Type::string()],
                'fraisgestion' => ['type' => Type::string()],
                'numero' => ['type' => Type::string(), 'description' => ''],
                'signature' => ['type' => Type::string(), 'description' => ''],
                'est_signer' => ['type' => Type::int()],
                'datesignature' => ['type' => Type::string()],

                'date' => ['type' => Type::string()],
                'date_fr' => ['type' => Type::string()],
                'date_echeance' => ['type' => Type::string()],
                'periodes' => ['type' => Type::string()],
                'est_activer' => ['type' => Type::int()],

                'contrat_id' => ['type' => Type::int()],
                'contrat' => ['type' => GraphQL::type('Contrat')],
                'paiementecheance'=> ['type' => GraphQL::type('Paiementecheance')],
                'periodicite_id' => ['type' => Type::int()],
                'periodicite' => ['type' => GraphQL::type('Periodicite')],


                'date_echeance_format' => ['type' => Type::string(), 'description' => ''],
                'mois_echeance_format' => ['type' => Type::string(), 'description' => ''],
                'annee_echeance_format' => ['type' => Type::string(), 'description' => ''],

                'delai_facture_format' => ['type' => Type::string(), 'description' => ''],
                'total_montant' => ['type' => Type::int(), 'description' => ''],
                'montant_total' => ['type' => Type::string(), 'description' => ''],
                'montant_letter' => ['type' => Type::string(), 'description' => ''],

                'etat_text' => ['type' => Type::string()],
                'etat_badge' => ['type' => Type::string()],
                'get_montantenattente' => ['type' => Type::string()],
                'get_montantenattente_format' => ['type' => Type::string()],
                'get_all_paiementecheances' => ['type' => Type::listOf(GraphQL::type('Paiementecheance'))],

                'fraisdelocation_format' => ['type' => Type::string()],
                'motif_annulation_paiement' => ['type' => Type::string()],
                'date_annulation_paiement' => ['type' => Type::string()],
                'date_annulation_paiement_format' => ['type' => Type::string()],
                'justificatif_paiement' => ['type' => Type::string()],
                'id_paiement' => ['type' => Type::int()],
                'fraisupplementaires'             => ['type' => type::listOf(GraphQL::type('Fraisupplementaire'))],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveFraisdelocationFormatField($root, $args)
    {
       return  number_format($root['fraisdelocation'], 0, ' ', ' ');

    }
    protected function resolveJustificatifPaiementField($root, $args)
    {
        $avis = $this->getAvis($root['id']);
        if (isset($avis->paiementecheance) && isset($avis->paiementecheance->id)) {
            return  $avis->paiementecheance->justificatif;
        }
      return null;

    }
    protected function resolveIdPaiementField($root, $args)
    {
        $avis = $this->getAvis($root['id']);
        if (isset($avis->paiementecheance) && isset($avis->paiementecheance->id)) {
            return  $avis->paiementecheance->id;
        }
      return null;

    }

    protected function resolveCodeField($root, $args)
    {
        $avis = $this->getAvis($root['id']);
        return $avis->code ? $avis->code : null;
    }
    private function getAvis($id) {
       return Avisecheance::find($id);
    }
    protected function resolveCopreneurField($root , $args) {
        $avis = $this->getAvis($root['id']);
        return $avis->copreneur ? $avis->copreneur : null;
    }
    protected function resolveDateEcheanceFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date_echeance']);
    }

    protected function resolveDateAnnulationPaiementFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date_annulation_paiement']);
    }

    protected function resolveTotalMontantField($root, $args)
    {
        $total = intval($root['fraisgestion']) + intval($root['amortissement']);

        return $total;
    }
    protected function resolveMontantTotalField($root, $args)
    {
        $item = Avisecheance::find($root['id']);

        return $item->montant_total;
    }

    protected function resolveMontantLetterField($root, $args)
    {
        $total = $root['amortissement'] + $root['fraisgestion'];
        $text = NombreEnLettre::convertirEnLettres($total);
        return $text;
    }
    protected function resolveDelaiFactureFormatField($root, $args)
    {
        $mois = 5; // Remplacez cette valeur par le mois de votre choix
        $tab = explode("-", $root['date_echeance']);
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
        $tab = explode("-", $root['date_echeance']);
        $mois = $tab ? $tab[0] : null;
        return $mois;
    }
    protected function resolveMoisEcheanceFormatField($root, $args)
    {

        return Outil::donneMoisEnLettres($root['date_echeance'], true);
    }

    protected function resolveEtatTextField($root, $args)
    {

        $itemArray = array("etat" => $root['est_activer']);
        $retour = Outil::donneEtatGeneral("avisecheance", $itemArray)['texte'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveEtatBadgeField($root, $args)
    {

        $itemArray = array("etat" => $root['est_activer']);
        $retour = Outil::donneEtatGeneral("avisecheance", $itemArray)['badge'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveGetAllPaiementecheancesField($root, $args)
    {
        return $paiementEcheances = Paiementecheance::where('avisecheance_id', $root['id'])
            ->orderBy('created_at', 'desc')
            ->get();

    }
    protected function resolveGetMontantenattenteField($root, $args)
    {
        $montantenattente = 0;
        $montantRegler = 0;

        // on recupere les paiements liés à l'avis avec etat = null
        $paiementEcheances = Paiementecheance::where('avisecheance_id', $root['id'])
            ->whereNull('etat')
            ->orderBy('created_at', 'desc')
            ->get();

        // montant total de l'avis
        $totalFacture = (int) str_replace(' ', '', self::resolveMontantTotalField($root, $args));

        if(count($paiementEcheances) > 0){
            $montantRegler = $paiementEcheances->sum('montant');
            if ($totalFacture > 0) {
                $montantenattente = $totalFacture - $montantRegler;
            }
        } else{
            $montantenattente = $totalFacture;
        }

        return $montantenattente;
    }

    protected function resolveGetMontantenattenteFormatField($root, $args)
    {
        $montantenattente = self::resolveGetMontantenattenteField($root, $args);

        return Outil::formatPrixToMonetaire($montantenattente) ?: "0";
    }
}
