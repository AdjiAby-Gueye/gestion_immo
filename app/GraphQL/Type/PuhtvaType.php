<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use App\RefactoringItems\RefactGraphQLType;

class PuhtvaType extends RefactGraphQLType
{
    protected $attributes = [
        "name" => "Puhtva",
        'description' => 'description',
    ];


    public function fields(): array
    {
        return [
            'id' => ['type' => Type::id()],
            'puhtva' => ['type' => Type::string()],
        ];
    }
}
