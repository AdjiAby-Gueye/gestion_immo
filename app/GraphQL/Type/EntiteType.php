<?php

namespace App\GraphQL\Type;

use App\Appartement;
use App\Avisecheance;
use App\Contrat;
use App\Entite;
use App\Paiementecheance;
use App\Periode;
use GraphQL\Type\Definition\Type;
use App\RefactoringItems\RefactGraphQLType;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EntiteType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Entite',
        'description' => 'A type'
    ];


    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string(), 'designation' => ''],
                'description' => ['type' => Type::string(), 'description' => ''],
                'location' => ['type' => Type::int(), 'description' => ''],
                'vente' => ['type' => Type::int(), 'description' => ''],
                'code' => ['type' => Type::string(), 'description' => ''],
                'users' => ['type' => Type::listOf(GraphQL::type('User')), 'description' => ''],
                'entiteusers' => ['type' => Type::listOf(GraphQL::type('Entiteuser')), 'description' => ''],
                // 'usersentite' => ['type' => Type::listOf(GraphQL::type('Entiteuser')), 'description' => ''],

                'gestionnaire' => ['type' => GraphQL::type('User'), 'description' => ''],
                'image' => ['type' => Type::string(), 'description' => ''],
                'gestionnaire_id' => ['type' => Type::int(), 'designation' => ''],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement')), 'description' => ''],
                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

                'nomcompletnotaire' => ['type' => Type::string(), 'description' => ''],
                'emailnotaire' => ['type' => Type::string(), 'description' => ''],
                'telephone1notaire' => ['type' => Type::string(), 'description' => ''],
                'nometudenotaire' => ['type' => Type::string(), 'description' => ''],
                'emailetudenotaire' => ['type' => Type::string(), 'description' => ''],
                'telephoneetudenotaire' => ['type' => Type::string(), 'description' => ''],
                'assistantetudenotaire' => ['type' => Type::string(), 'description' => ''],
                'adressenotaire' => ['type' => Type::string(), 'description' => ''],
                'adresseetudenotaire' => ['type' => Type::string(), 'description' => ''],
                'infobancaires' => ['type' => Type::listOf(GraphQL::type('Infobancaire')), 'description' => ''],
                'activite_id' => ['type' => Type::id(), 'description' => ''],
                'activite' => ['type' =>  GraphQL::type('Activite')],

                // nbre app
                'nbreappartements' => ['type' => Type::int(), 'description' => ''],

                // nbreapplouer
                'nbreappartementslouer' => ['type' => Type::int(), 'description' => ''],

                // nbreappvide
                'nbreappartementsvide' => ['type' => Type::int(), 'description' => ''],
                // Taux d’occupation par immeuble et par entité (Nb loué / Nb libre)

                // autres champs
                'nbreretardloyer' => [
                    'type' => Type::listOf(GraphQL::type('RetardLoyer')),
                    'description' => 'Le nombre de retards de loyer par mois',
                ],


                // fraisdelocation
                'fraisdelocation' => ['type' => Type::string(), 'description' => ''],
                // amortissement
                'amortissement' => ['type' => Type::string(), 'description' => ''],

                // fraisgestion
                'fraisgestion' => ['type' => Type::string(), 'description' => ''],

                'tauxoccupation' => ['type' => Type::float(), 'description' => ''],


            ];
    }

    protected function resolveFraisdelocationField($root, $args)
    {
        $app = $root->appartements
            ->where('entite_id', 2)
            ->pluck("id")->toArray();
        $totalfrais = Avisecheance::whereHas('contrat', function ($query) use ($root, $app) {
            $query->whereIn('appartement_id', $app);
        })->selectRaw('SUM(CAST(fraisdelocation AS NUMERIC)) as totalfrais')->get();
        return $totalfrais[0]->totalfrais;
    }

    protected function resolveAmortissementField($root, $args)
    {
        $app = $root->appartements
            ->where('entite_id', 2)
            ->pluck("id")->toArray();
        $totalamortissement = Avisecheance::whereHas('contrat', function ($query) use ($root, $app) {
            $query->whereIn('appartement_id', $app);
        })->selectRaw('SUM(CAST(amortissement AS NUMERIC)) as totalamortissement')->get();
        return $totalamortissement[0]->totalamortissement;
    }

    protected function resolveFraisgestionField($root, $args)
    {
        $app = $root->appartements
            ->where('entite_id', 2)
            ->pluck("id")->toArray();

        $totalfraisgestion = Avisecheance::whereHas('contrat', function ($query) use ($root, $app) {
            $query->whereIn('appartement_id', $app);
        })->selectRaw('SUM(CAST(fraisgestion AS NUMERIC)) as totalfraisgestion')->get();
        return $totalfraisgestion[0]->totalfraisgestion;
    }

    protected function resolveNbreretardloyerField($root, $args)
    {

        $entite = Entite::where("code", "RID")->first();
        $app = Appartement::where('entite_id', $entite->id)->whereHas('contrats', function ($query) {
            $query->whereIn('etat', [1, 2]);
        })->pluck("id")->toArray();

        $mois = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre',
        ];

        // Récupérer les statistiques
        $sql = Avisecheance::whereHas('contrat', function ($query) use ($app) {
            $query->whereIn('appartement_id', $app);
        });




        // Calculer les statistiques sur les paiements en retard ou à l'heure
        $statSurloyer = $sql->leftJoin('paiementecheances', 'avisecheances.id', '=', 'paiementecheances.avisecheance_id')
            ->whereRaw('EXTRACT(YEAR FROM avisecheances.date) = ?', [date('Y')])
            ->selectRaw('
        avisecheances.periodes as periode,
        SUM(CASE WHEN paiementecheances.date > avisecheances.date_echeance THEN 1 ELSE 0 END) as retard_count,
        SUM(CASE WHEN paiementecheances.date <= avisecheances.date_echeance THEN 1 ELSE 0 END) as ontime_count,
        SUM(CAST(paiementecheances.montant AS NUMERIC)) as montantfacture
    ')
            ->groupBy('avisecheances.periodes')
            ->orderBy('periode')
            ->get();

        // dd($statSurloyer);

        // Calculer le montant total de l'échéance par mois
        $totalloyer = $sql->whereRaw('EXTRACT(YEAR FROM avisecheances.date) = ?', [date('Y')])
            ->selectRaw('
        avisecheances.periodes as periode,
        SUM(CAST(avisecheances.amortissement AS NUMERIC) + CAST(avisecheances.fraisgestion AS NUMERIC) + CAST(avisecheances.fraisdelocation AS NUMERIC)) as montantecheance
    ')
            ->groupBy('avisecheances.periodes')
            ->orderBy('periode')
            ->get();


;



        // Créer un tableau qui contient tous les mois de l'année
        $resultats = [];
        $dernierMontantEcheance = 0;

        foreach ($mois as $key => $moisLibelle) {
            $moisStat = $statSurloyer->firstWhere('periode', $mois[$key]);
            $totalloyerparmoi = $totalloyer->firstWhere('periode', $mois[$key]);

            $montantecheance = $totalloyerparmoi->montantecheance ?? $dernierMontantEcheance;

            $resultats[] = [
                'mois' => $moisLibelle,
                'nombre_de_retards' => $moisStat->retard_count ?? 0,
                'nombre_payer_a_temps' => $moisStat->ontime_count ?? 0,
                'total' => $moisStat->montantfacture ?? 0,
                'totalecheance' => $montantecheance,
            ];

            // Mettre à jour le dernier montant d'échéance si le mois a une valeur
            if (isset($totalloyerparmoi->montantecheance)) {
                $dernierMontantEcheance = $totalloyerparmoi->montantecheance;
            }
        }
        return $resultats;
    }







    // nbreappartements refactored

    protected function resolveNbreappartementsField($root, $args)
    {
        return $root->appartements->count();
    }

    // nbreappartementslouer refactored

    protected function resolveNbreappartementslouerField($root, $args)
    {
        return $root->appartements->filter(function ($appartement) {
            return optional($appartement->etatappartement)->designation === 'En location' &&
                optional($appartement->contrats->last())->etat !== 0;
        })->count();
    }










    // nbreappartementsvide refactored

    protected function resolveNbreappartementsvideField($root, $args)
    {
        return $root->appartements->filter(function ($appartement) {
            return optional($appartement->etatappartement)->designation === 'Libre';
        })->count();
    }


    // tauxoccupation refactored

    protected function resolveTauxoccupationField($root, $args)
    {

        $appLouer =  $this->resolveNbreappartementslouerField($root, $args);
        $totalapp = $this->resolveNbreappartementsField($root, $args);

        //
        if ($totalapp == 0) {
            return 0;
        }
        $taux = $appLouer / $totalapp;
        return round($taux * 100, 2);
    }
}
