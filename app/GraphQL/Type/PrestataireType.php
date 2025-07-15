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

class PrestataireType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Prestataire',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'nom' => ['type' => Type::string(), 'description' => ''],
                'adresse' => ['type' => Type::string(), 'description' => ''],
                'email' => ['type' => Type::string(), 'description' => ''],
                'telephone1' => ['type' => Type::string(), 'description' => ''],
                'telephone2' => ['type' => Type::string(), 'description' => ''],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention')), 'description' => ''],
                'contacts' => ['type' => Type::listOf(GraphQL::type('Contactprestataire')), 'description' => ''],
                'contratprestations' => ['type' => Type::listOf(GraphQL::type('Contratprestation')), 'description' => ''],
                'categorieprestataire' => ['type' =>  GraphQL::type('Categorieprestataire')],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

