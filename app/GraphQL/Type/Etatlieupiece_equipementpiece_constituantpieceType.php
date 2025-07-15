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

class Etatlieupiece_equipementpiece_constituantpieceType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Etatlieupiece_equipementpiece_constituantpiece',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'etatlieupiece' => ['type' =>  GraphQL::type('Etatlieu_piece')],
                'pieceequipementobservation' => ['type' =>  GraphQL::type('Piece_equipement_observation')],
                'piececonstituantobservation' => ['type' =>  GraphQL::type('Piece_constituant_observation')],
                'equipementobservation' => ['type' =>  GraphQL::type('Equipement_observation')],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

