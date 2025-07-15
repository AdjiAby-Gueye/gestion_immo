<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use App\RefactoringItems\RefactGraphQLType;

class UniteType extends RefactGraphQLType
{
    protected $attributes = [
        "name" => "Unite",
        'description' => 'description',
    ];


    public function fields(): array
    {
        return [
            'id' => ['type' => Type::id()],
            'designation' => ['type' => Type::string()],
        ];
    }
}
