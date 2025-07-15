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

class EtatencaissementdetailType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Etatencaissementdetail',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'totalAmortissement'        => ['type' => Type::int(), 'description' => ''],
                'totalFraisgestion'                  => ['type' => Type::int(), 'description' => ''],
                'totalFraislocatif'                  => ['type' => Type::int(), 'description' => ''],
                'penalite'                      => ['type' => Type::float(), 'description' => ''],
                'total'                      => ['type' => Type::int(), 'description' => ''],

            ];
    }

}

