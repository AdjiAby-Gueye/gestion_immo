<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class HistoriquerelancePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'historiquerelancespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('Historiquerelance');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'date_envoie' => ['type' => Type::string(), 'description' => ''],

                'contrat_id' => ['type' => Type::string(), 'description' => ''],
                'locataire_id' => ['type' => Type::string(), 'description' => ''],
                'user_id' => ['type' => Type::string(), 'description' => ''],
                'inbox_id' => ['type' => Type::string(), 'description' => ''],
                'avisecheance_id' => ['type' => Type::int(), 'description' => ''],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryHistoriqueRelance($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
