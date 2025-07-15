<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CopreneurPaginatedType extends RefactGraphQLType
{

    protected $attributes = [
        'name' => 'copreneurspaginated'
        // copreneurspaginated
    ];

    public function fields(): array
    {
        return [
            'metadata' => [
                'type' => GraphQL::type('Metadata'),
                'resolve' => function ($root) {
                    return array_except($root->toArray(), ['data']);
                }
            ],
            'data' => [
                'type' => Type::listOf(GraphQL::type('Copreneur')),
                'resolve' => function ($root) {
                    return $root;
                }
            ]
        ];
    }
}
