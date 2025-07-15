<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Bien_questionnairePaginatedType extends RefactGraphQLType
{

    protected $attributes = [
        'name' => 'bien_questionnairespaginated'
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
                'type' => Type::listOf(GraphQL::type('Bien_questionnaire')),
                'resolve' => function ($root) {
                    return $root;
                }
            ]
        ];
    }
}
