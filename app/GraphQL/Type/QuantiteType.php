<?php

namespace App\GraphQL\Type;

use App\Outil;


use Psy\Util\Str;
use App\Detailpaiement;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class QuantiteType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Quantite',
        'description' => ''
    ];

    public function  fields(): array
    {
        return[
            'id' => ['type' => Type::id(), 'description' => ''],
            'qunatite' => ['type' => Type::string(), 'description' => ''],
        ];

    }

}
       