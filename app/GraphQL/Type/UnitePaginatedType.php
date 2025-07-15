<?php

namespace App\GraphQL\Type;

use App\QueryModel;
use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UnitePaginatedType extends RefactGraphQLType
{


    protected $attributes = [
        'name' => 'unitespaginated'
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
                'type' => Type::listOf(GraphQL::type('Unite')),
                'resolve' => function ($root) {
                    return $root;
                }
            ]
        ];
    }
}
