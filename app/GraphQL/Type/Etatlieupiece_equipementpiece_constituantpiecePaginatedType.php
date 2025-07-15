<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Etatlieupiece_equipementpiece_constituantpiecePaginatedType extends RefactGraphQLType
{

    protected $attributes = [
        'name' => 'etatlieupiece_equipementpiece_constituantpiecespaginated'
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
                'type' => Type::listOf(GraphQL::type('Etatlieupiece_equipementpiece_constituantpiece')),
                'resolve' => function ($root) {
                    return $root;
                }
            ]
        ];
    }
}
