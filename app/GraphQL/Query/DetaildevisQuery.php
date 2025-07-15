<?php


namespace App\GraphQL\Query;

use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetaildevisQuery extends Query
{
    protected $attributes = [
        'name' => 'detaildevis',
        'description' => 'Une query de la table detaildevi'
    ];


    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Detaildevi'));
    }


    public function  args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'devi_id' => ['type' => Type::int()],
                'categorieintervention_id' => ['type' => Type::int()],
                'detaildevisdetail_id' => ['type'=> Type::int(),'description' =>''],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }


    
    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetaildevi($args);
        return $query->get();
    }
}
