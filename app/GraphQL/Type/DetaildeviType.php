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

class DetaildeviType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Detaildevi',
        'description' => ''
    ];

    public function  fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'devi_id' => ['type' => Type::int(), 'description' => ''],
                'devi' => ['type' => GraphQL::type('Devi'), 'description' => ''],
                
                'categorieintervention_id' => ['type' => Type::int(), 'description' => ''],
                'categorieintervention' => ['type' => GraphQL::type('Categorieintervention'), 'description' => ''],
                'detaildevisdetail_id' => ['type'=> Type::int(),'description' =>''],
            'detaildevisdetail'=> ['type'=> Type::int(), GraphQL::type('Detaildevisdetail'),  'description'=> ''],
            'detaildevisdetails'=> ['type'=> Type::listOf(GraphQL::type('Detaildevisdetail')), 'description'=> ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],
            ];
    }
}
