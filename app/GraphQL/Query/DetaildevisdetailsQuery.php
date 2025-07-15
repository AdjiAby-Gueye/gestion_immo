<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetaildevisdetailsQuery extends Query
{
     protected $attributes = [
        'name' => 'detaildevisdetails',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Detaildevisdetail'));

    }
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                
                'detaildevi_id' => ['type' => Type::int()],
                'soustypeintervention_id'=> ['type' => Type::int()],
                'prixunitaire'=> ['type' => Type::string()],
                'quantite'=> ['type' => Type::string()],
                'unite_id'=> ['type' => Type::string()],
       

             
                'created_at' => ['type' => Type::string()],
                'created_at_fr' => ['type' => Type::string()],
                'updated_at' => ['type' => Type::string()],
                'updated_at_fr' => ['type' => Type::string()],
                'deleted_at' => ['type' => Type::string()],
                'deleted_at_fr' => ['type' => Type::string()],
                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetaildevisdetail($args);
        return $query->get();

    }


}


























































































































































