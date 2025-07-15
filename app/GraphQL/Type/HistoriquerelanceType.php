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

class HistoriquerelanceType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Historiquerelance',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                
                'id' => ['type' => Type::id(), 'description' => ''],
                'date_envoie' => ['type' => Type::string(), 'description' => ''],

                'contrat_id' => ['type' => Type::string(), 'description' => ''],
                'locataire_id' => ['type' => Type::string(), 'description' => ''],
                'user_id' => ['type' => Type::string(), 'description' => ''],
                'inbox_id' => ['type' => Type::string(), 'description' => ''],
                'avisecheance_id' => ['type' => Type::int(), 'description' => ''],

                'avisecheance' => ['type' => GraphQL::type('Avisecheance'), 'description' => ''],
                'contrat' => ['type' => GraphQL::type('Contrat'), 'description' => ''],
                'locataire' => ['type' => GraphQL::type('Locataire'), 'description' => ''],
                'user' => ['type' => GraphQL::type('User'), 'description' => ''],
                'inbox' => ['type' => GraphQL::type('Inbox'), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

