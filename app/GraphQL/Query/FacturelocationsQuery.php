<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FacturelocationsQuery extends Query
{

    // les attributs de la classe Facture_LocationQuery

    protected $attributes = [
        'name' => 'facturelocations',
        'description' => 'Une query de la table facture_location'
    ];


    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Facturelocation'));
    }


    // argument filter to query

    public function args(): array
    {
        return [
            'id' => ['type' => Type::id()],
            'typefacture_id' => ['type' => Type::int()],
            'periodicite_id' => ['type' =>  Type::int()],
            'contrat_id' => ['type' => Type::int()],
            'objetfacture' => ['type' => Type::string()],
            'datefacture' => ['type' => Type::string()],
            'nbremoiscausion' => ['type' => Type::int()],
            'montant' => ['type' => Type::string()],
            'contrats' => ['type' => Type::listOf(Type::int())],
            'typefactures' => ['type' => Type::listOf(Type::int())],
            'periodicites' => ['type' => Type::listOf(Type::int())],
            'date_echeance' => ['type' => Type::string(), 'description' => ''],
            'demanderesiliation_id' => ['type' => Type::int(), 'description' => ''],
            'est_activer' => ['type' => Type::int(), 'description' => ''],
            'proprietaire_id' => ['type' => Type::int()],

            // datedeb
            'datedeb' => ['type' => Type::string()], // datedeb
            // datefin
            'datefin' => ['type' => Type::string()], // datefin

            'order'          => ['type' => Type::string()],
            'direction'      => ['type' => Type::string()],

        ];
    }

    public function resolve($root, $args)
    {
        //dd($args);
        $query = QueryModel::getQueryFacturelocation($args);
        return $query->get();
    }
}
