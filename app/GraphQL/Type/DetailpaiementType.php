<?php

namespace App\GraphQL\Type;

use App\Outil;


use App\Periode;
use Psy\Util\Str;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class DetailpaiementType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Detailpaiement',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],

                'date_paiement' => ['type' => Type::string(), 'description' => ''],
                'periode_id' => ['type' => Type::int(), 'description' => ''],
                'paiementloyer_id' => ['type' => Type::string(), 'description' => ''],
                'periode' => ['type' =>  GraphQL::type('Periode')],
                'paiementloyer' => ['type' =>  GraphQL::type('Paiementloyer')],

                'montant' => ['type' => Type::int(), 'description' => ''],
                'montant_format' => ['type' => Type::string(), 'description' => ''],
                'periode_text' => ['type' => Type::string(), 'description' => ''],
                


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveMontantFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montant']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }

 

    

    protected function resolvePeriodeTextField($root, $args)
    {
       $periode = Periode::find($root['periode_id']);
       if ($periode) {
        return $periode->designation;
       }
       return "";
    }
}

