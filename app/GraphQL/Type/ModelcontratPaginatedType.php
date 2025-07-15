<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ModelcontratPaginatedType extends RefactGraphQLType
{

    protected $attributes = [
        'name' => 'Modelcontratspaginated'
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
                'type' => Type::listOf(GraphQL::type('Modelcontrat')),
                'resolve' => function ($root) {
                    return $root;
                }
            ]
        ];
    }
}
