<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EntiteuserType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Entiteuser',
        'description' => 'A type'
    ];


    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],

                'user' => ['type' => GraphQL::type('User'), 'description' => ''],
                'entite' => ['type' => GraphQL::type('Entite'), 'description' => ''],
                'entite_id' => ['type' => Type::int(), 'designation' => ''],
                'user_id' => ['type' => Type::int(), 'designation' => ''],
                'email' => ['type' => Type::string(), 'description' => ''],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],



            ];
    }
}
