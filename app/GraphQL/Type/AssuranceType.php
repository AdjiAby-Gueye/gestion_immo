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

class AssuranceType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Assurance',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'descriptif' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::string(), 'description' => ''],
                'debut' => ['type' => Type::string(), 'description' => ''],
                'fin' => ['type' => Type::string(), 'description' => ''],
                'document' => ['type' => Type::string(), 'description' => ''],
                'assureur_id' => ['type' => Type::string(), 'description' => ''],
                'etatassurance_id' => ['type' => Type::string(), 'description' => ''],
                'contrat_id' => ['type' => Type::string(), 'description' => ''],
                'assureur' => ['type' =>  GraphQL::type('Assureur')],
                'typeassurance' => ['type' =>  GraphQL::type('Typeassurance')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'etatassurance' => ['type' =>  GraphQL::type('Etatassurance')],
                'contrat' => ['type' =>  GraphQL::type('Contrat')],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

