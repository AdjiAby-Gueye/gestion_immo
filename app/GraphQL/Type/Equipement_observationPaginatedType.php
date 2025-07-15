<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Equipement_observationPaginatedType extends RefactGraphQLType
{

    protected $attributes = [
        'name' => 'equipement_observationspaginated'
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
                'type' => Type::listOf(GraphQL::type('Equipement_observation')),
                'resolve' => function ($root) {
                    return $root;
                }
            ]
        ];
    }
}
