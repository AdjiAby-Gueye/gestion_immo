<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DevisQuery extends Query
{
    protected $attributes = [
        'name' => 'devis',
        'description' => 'Une query de la table devi'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Devi'));
    }

    public function args(): array
    {
        return  [
            'id' => ['type' => Type::id()],
            'demandeintervention_id' => ['type' => Type::int()],
            'dateenregistrement' => ['type' => Type::string()],
            'detaildevi_id' => ['type'=> Type::int(),'description' =>''],
            'detaildevisdetail_id' => ['type'=> Type::int(),'description' =>''],
            'etatlieu_id'=>['type'=> Type::int(),'description'=> ''],
            'object' => ['type' => Type::string()],
            'code'=> ['type' => Type::string()],
            'date' => ['type' => Type::string()],
            'est_activer' => ['type' => Type::int()],

            'order'          => ['type' => Type::string()],
            'direction'      => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDevi($args);
        return $query->get();
    }
}
