<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ContratproprietairesQuery extends Query{

    protected $attributes = [
        'name' => 'contratproprietaires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Contratproprietaire'));

    }

    public function args():array{
        return [
            'id' => ['type' => Type::id(), 'description' => '' ],
            'date' => ['type' => Type::string(), 'description' => '' ],
            'descriptif' => ['type' => Type::string(), 'description' => ''],
            // datedeb
            'datedeb' => ['type' => Type::string()], // datedeb
            // datefin
            'datefin' => ['type' => Type::string()], // datefin

            'commissionvaleur' => ['type' => Type::int(), 'description' => ''],
            'commissionpourcentage' => ['type' => Type::int(), 'description' => ''],
            'is_tva' => ['type' => Type::int(), 'description' => ''],
            'is_brs' => ['type' => Type::int(), 'description' => ''],
            'is_tlv' => ['type' => Type::int(), 'description' => ''],
            'entite_id' => ['type' => Type::id(), 'description' => ''],
            'proprietaire_id' => ['type' => Type::id(), 'description' => ''],
            'modelcontrat_id' => ['type' => Type::id(), 'description' => ''],
        ];
    }
    public function resolve($root, $args){

        $query = QueryModel::getQueryContratproprietaire($args);
        return $query->get();
    }

}
