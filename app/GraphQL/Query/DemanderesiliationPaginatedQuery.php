<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class DemanderesiliationPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'demanderesiliationspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('demanderesiliationspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'datedebutcontrat' => ['type' => Type::string()],
                'etat' => ['type' => Type::int()],
                'retourcaution' => ['type' => Type::int()],
                'datedemande' => ['type' => Type::string()],
                'delaipreavisrespecte' => ['type' => Type::string()],
                'raisonnonrespectdelai' => ['type' => Type::string()],
                'delaipreavis' => ['type' => Type::string()],
                'dateeffectivite' => ['type' => Type::string()],
                'contrat_id' => ['type' => Type::int()],
                'document' => ['type' => Type::string()],
                'motif' => ['type' => Type::string()],
                'locataire_id' => ['type' => Type::int()],
                

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDemanderesiliation($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
