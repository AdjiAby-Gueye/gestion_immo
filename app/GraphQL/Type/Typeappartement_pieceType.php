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

class Typeappartement_pieceType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Typeappartement_piece',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string()],
                'commentaire' => ['type' => Type::string()],
                'typeappartement' => ['type' =>  GraphQL::type('Typeappartement')],
                'typepiece' => ['type' =>  GraphQL::type('Typepiece')],
                'typeappartement_id' => ['type' => Type::int(), 'description' => ''],
                'typepiece_id' => ['type' => Type::int(), 'description' => ''],

                
                'niveauappartement' => ['type' =>  GraphQL::type('Niveauappartement')],
                'niveauappartement_id' => ['type' => Type::int()],

                
                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

