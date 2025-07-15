<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class TypeapportponctuelPaginatedType extends RefactGraphQLType
{

    protected $attributes = [
        'name' => 'Typeapportponctuelspaginated'
    ];

    public function fields(): array
    {
        return [
            'metadata' => [
                'type' => GraphQL::type('Metadata'),
                'resolve' => function ($root) {
                    return arr::except($root->toArray(), ['data']);
                }
            ],
            'data' => [
                'type' => Type::listOf(GraphQL::type('Typeapportponctuel')),
                'resolve' => function ($root) {
                    return $root;
                }
            ]
        ];
    }
}
