<?php

namespace App\GraphQL\Type;

use App\Avisecheance;
use App\Outil;


use Psy\Util\Str;
use App\Detailpaiement;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PaiementecheanceType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Paiementecheance',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],

                'date' => ['type' => Type::string(), 'description' => ''],

                'datepaiement_format' => ['type' => Type::string(), 'description' => ''],

                'numero' => ['type' => Type::string(), 'description' => ''],

                'numero_cheque' => ['type' => Type::string(), 'description' => ''],

                'montant' => ['type' => Type::string(), 'description' => ''],

                'etat' => ['type' => Type::int(), 'description' => ''],

                'montantencaisse' => ['type' => Type::string(), 'description' => ''],

                'montantenattente' => ['type' => Type::string(), 'description' => ''],

                'montant_format' => ['type' => Type::string(), 'description' => ''],

                'montant_format_letter' => ['type' => Type::string(), 'description' => ''],

                'avisecheance_id' => ['type' => Type::int(), 'description' => ''],
                'avisecheance' => ['type' => GraphQL::type('Avisecheance'), 'description' => ''],

                'periodes' => ['type' => Type::string(), 'description' => ''],
                'locataire' => ['type' => GraphQL::type('Locataire') , 'description' => ''],

                'modepaiement_id' => ['type' => Type::int()],
                'modepaiement' => ['type' =>  GraphQL::type('Modepaiement')],



                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveDateFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date']);
    }

    // protected function resolveMontantFormatLetterField($root, $args)
    // {

    //     $valeur_ht_format = Outil::convertirEnLettres($root['montant']);
    //     if (empty($valeur_ht_format)) {
    //         $valeur_ht_format = "";
    //     }

    //     return $valeur_ht_format;
    // }

    protected function resolveMontantFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montant']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }

    // protected static function sumAmount($root) {
    //     $avis =  Avisecheance::find($root['avisecheance_id']);
    //     if ($avis ) {
    //         $sum = intval($avis->amortissement) + intval($avis->fraisgestion);

    //     }

    // }



}

