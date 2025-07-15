<?php

namespace App\GraphQL\Type;

use App\Intervention;
use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetailfactureinterventionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Detailfactureintervention',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id'                    => ['type' => Type::id()],
                'montant'               => ['type' => Type::int()],
                'intervention_id'       => ['type' => Type::id()],
                'interventiondetail_id'       => ['type' => Type::id()],
                'factureintervention_id'=> ['type' => Type::id()],
                'intervention'          => ['type' =>  GraphQL::type('Intervention')],
                'factureintervention'          => ['type' =>  GraphQL::type('Factureintervention')],
                'interventiondetail_text' => ['type' => Type::string(), 'description' => ''],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveInterventiondetailTextField($root, $args)
    {
        $intervention = Intervention::find($root['intervention_id']);

        return isset($intervention) ? $intervention->descriptif : '';
    }
    protected function resolveInterventiondetailIdField($root, $args)
    {

        return $root['intervention_id'];
    }

}

