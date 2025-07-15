<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class CautionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'cautionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('cautionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'document' => ['type' => Type::string()],
                'montantloyer' => ['type' => Type::string()],
                'montantcaution' => ['type' => Type::string()],
                'codeappartement' => ['type' => Type::string()],
                'dateversement' => ['type' => Type::int()],
                'datepaiement' => ['type' => Type::int()],
                'etat' => ['type' => Type::string()],
                'contrat_id' => ['type' => Type::string()],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryCaution($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
