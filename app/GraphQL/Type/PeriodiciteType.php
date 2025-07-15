<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Appartement;
use GraphQL\Type\Definition\Type;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PeriodiciteType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Periodicite',
        'description' => 'A type'
    ];


    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string(), 'designation' => ''],
                'nbr_mois' => ['type' => Type::string()],
                'description' => ['type' => Type::string(), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

 
}
