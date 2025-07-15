<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FraisupplementaireType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Fraisupplementaire',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string(), 'description' => ''],
                'frais' => ['type' => Type::int()],
                'avisecheance_id' => ['type' => Type::int()],
                'avisecheance' => ['type' => GraphQL::type('Avisecheance')],

            ];
    }

}

