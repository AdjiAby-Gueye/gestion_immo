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

class QuestionnairesatisfactionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Questionnairesatisfaction',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'titre' => ['type' => Type::string(), 'description' => ''],
                'contenu' => ['type' => Type::string(), 'description' => ''],
                'intervention_id' => ['type' => Type::string(), 'description' => ''],
                'locataires' => ['type' => Type::listOf(GraphQL::type('Locataire')), 'description' => ''],
                'proprietaires' => ['type' => Type::listOf(GraphQL::type('Proprietaire')), 'description' => ''],
                'reponsequestionnaires' => ['type' => Type::listOf(GraphQL::type('Reponsequestionnaire')), 'description' => ''],
                'documents' => ['type' => Type::listOf(GraphQL::type('Document')), 'description' => ''],
                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

