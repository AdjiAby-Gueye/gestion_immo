<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DemanderesiliationsQuery extends Query
{
    protected $attributes = [
        'name' => 'demanderesiliations',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Demanderesiliation'));

    }

    // arguments to filter query
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
                'intervention_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDemanderesiliation($args);
        return $query->get();

    }
}
