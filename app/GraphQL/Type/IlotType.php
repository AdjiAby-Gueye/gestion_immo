<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Appartement;
use GraphQL\Type\Definition\Type;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class IlotType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Ilot',
        'description' => 'A type'
    ];


    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'numero' => ['type' => Type::int(), 'designation' => ''],
                'adresse' => ['type' => Type::string(), 'description' => ''],
                'nombrevilla' => ['type' => Type::int(), 'description' => ''],
                
                'numerotitrefoncier' => ['type' => Type::string(), 'description' => ''],
                'datetitrefoncier' => ['type' => Type::string(), 'description' => ''],
                'adressetitrefoncier' => ['type' => Type::string(), 'description' => ''],

                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement')), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveNombrevillaField($root , $args) {
        return Appartement::where("ilot_id" , $root['id'])->get()->count();
    }
}
