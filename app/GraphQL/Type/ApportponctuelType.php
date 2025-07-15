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

class ApportponctuelType extends RefactGraphQLType{
    protected $attributes = [
        'name' => 'Apportponctuel',
        'description' => ''
        ];


    public function fields():array{
        return [
            'id' => ['type' => Type::id(), 'description' => '' ],
            'date' => ['type' => Type::string(), 'description' => '' ],
            'montant' => ['type' => Type::float(), 'description' => ''],
            'contrat_id' => ['type' => Type::id(), 'description' => ''],
            'contrat' => ['type' =>  GraphQL::type('Contrat')],
            'typeapportponctuel' => ['type' =>  GraphQL::type('Typeapportponctuel')],
            'typeapportponctuel_id' => ['type' => Type::id(), 'description' => ''],
            'observations' => ['type' => Type::string(), 'description' => '']
        ];
    }
}
