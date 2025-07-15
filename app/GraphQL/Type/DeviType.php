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

class DeviType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Devi',
        'description' => ''
    ];

    public function  fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],

                'demandeintervention_id' => ['type' => Type::int(), 'description' => ''],
                'demandeintervention' => ['type' => GraphQL::type('Demandeintervention'), 'description' => ''],
                'object'=> ['type'=> Type::string(), 'description'=> ''],
                'date'=> ['type'=> Type::string(), 'description'=> ''],
                'date_fr'=> ['type'=> Type::string(), 'description'=> ''],
                'detaildevi_id' => ['type'=> Type::int(),'description' =>''],
                'detaildevi'=> ['type'=>  GraphQL::type('Detaildevi'),  'description'=> ''],
                'detaildevis'=> ['type'=> Type::listOf(GraphQL::type('Detaildevi')), 'description'=> ''],
                'etatlieu_id' => ['type'=> Type::int(),'description' =>''],
                'etatlieu'=> ['type'=>  GraphQL::type('Etatlieu'),  'description'=> ''],
                'detaildevisdetail_id' => ['type'=> Type::int(),'description' =>''],
                'detaildevisdetail'=> ['type'=>  GraphQL::type('Detaildevisdetail'),  'description'=> ''],
                'detaildevisdetails'=> ['type'=> Type::listOf(GraphQL::type('Detaildevisdetail')), 'description'=> ''],

                'code' => ['type' => Type::string(), 'description' => ''],
                'est_activer'=> ['type'=> Type::int(), 'description'=> ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],
            ];
    }


}
