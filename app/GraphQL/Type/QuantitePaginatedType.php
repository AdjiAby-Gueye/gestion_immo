<?php

namespace App\GraphQL\Type;

use App\QueryModel;
use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class QuantitePaginatedType extends RefactGraphQLType
{


    protected $attributes = [
        'name' => 'quantitespaginated'
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
                'type' => Type::listOf(GraphQL::type('Quantite')),
                'resolve' => function ($root) {
                    return $root;
                }
            ]
        ];
    }
}
