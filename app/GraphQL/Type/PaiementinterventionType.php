<?php

namespace App\GraphQL\Type;

use App\Outil;


use Psy\Util\Str;
use App\Detailpaiement;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PaiementinterventionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Paiementintervention',
        'description' => ''
    ];

    public function fields(): array
    {
        return
           [
            'id' => ['type' => Type::id(), 'description' => ''],
            'factureintervention_id' => ['type' => Type::id(), 'description' => ''],
            'factureintervention' => ['type' => GraphQL::type('Factureintervention'), 'description' => ''],
            'modepaiement_id' => ['type' => Type::int(), 'description' => ''],
            'modepaiement' => ['type' => GraphQL::type('Modepaiement'), 'description' => ''],
            'est_activer' => ['type' => Type::int(), 'description' => ''],
            
            
            'date'=> ['type'=> Type::string(), 'description'=> ''],
            'montant'=>['type'=> Type::string(), 'description'=> ''],
            'cheque'=>['type'=> Type::string(), 'description'=> ''],
           ];
    }
}

