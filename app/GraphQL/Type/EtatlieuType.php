<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EtatlieuType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Etatlieu',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string(), 'description' => ''],
                'type' => ['type' => Type::string(), 'description' => ''],
                'dateredaction' => ['type' => Type::string(), 'description' => ''],
                'particularite' => ['type' => Type::string(), 'description' => ''],
                'devi_id' => ['type' => Type::int(), 'description' => ''],
                'devi' => ['type' => GraphQL::type('Devi'), 'description' => ''],
                'devis' => ['type' => Type::listOf(GraphQL::type('Devi')), 'description' => ''],
                'etatgenerale' => ['type' => Type::string(), 'description' => ''],
                'pieceappartement_id' => ['type' => Type::string(), 'description' => ''],
                'appartement_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'constituantpieces' => ['type' => Type::listOf(GraphQL::type('Constituantpiece')), 'description' => ''],
                'equipementpieces' => ['type' => Type::listOf(GraphQL::type('Equipementpiece')), 'description' => ''],

                'factureintervention_id' => ['type' => Type::int(), 'description' => ''],
                'factureintervention' => ['type' =>  GraphQL::type('Factureintervention')],
                //'factureinterventions' => ['type' => Type::listOf(GraphQL::type('Factureintervention')), 'description' => ''],

                'intervention_id' => ['type' => Type::int(), 'description' => ''],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention')), 'description' => ''],
                
                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }
}
