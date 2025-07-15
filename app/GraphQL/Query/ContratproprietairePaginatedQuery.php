<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ContratproprietairePaginatedQuery extends Query{

    protected $attributes = [
        'name' => 'contratproprietairespaginated',
        'description' => ''
    ];

    public function type():type{

        return GraphQL::type('Contratproprietairespaginated');
    }

    public function args():array{
        return [
            'id' => ['type' => Type::id(), 'description' => '' ],
            'date' => ['type' => Type::string(), 'description' => '' ],
            'descriptif' => ['type' => Type::string(), 'description' => ''],
            'commissionvaleur' => ['type' => Type::int(), 'description' => ''],
            'commissionpourcentage' => ['type' => Type::int(), 'description' => ''],
            'is_tva' => ['type' => Type::int(), 'description' => ''],
            'is_brs' => ['type' => Type::int(), 'description' => ''],
            'is_tlv' => ['type' => Type::int(), 'description' => ''],
            'entite_id' => ['type' => Type::id(), 'description' => ''],
            'proprietaire_id' => ['type' => Type::id(), 'description' => ''],
            'modelcontrat_id' => ['type' => Type::id(), 'description' => ''],

            'page' => ['type' => Type::int()],
            'count' => ['type' => Type::int()],
        ];
    }
    public function resolve($root, $args){

        $query = QueryModel::getQueryContratproprietaire($args);
        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }

}
