<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class VillaPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'villaspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('appartementspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'codeappartement' => ['type' => Type::string()],
                'isdemanderesiliation' => ['type' => Type::string()],
                'nom' => ['type' => Type::string()],
                'etatlieu' => ['type' => Type::string()],
                'isassurance' => ['type' => Type::string()],
                'superficie' => ['type' => Type::string()],
                'niveau' => ['type' => Type::string()],
                'iscontrat' => ['type' => Type::string()],
                'image' => ['type' => Type::string()],
                'immeuble_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'proprietaire_id' => ['type' => Type::int()],
                'typeappartement_id' => ['type' => Type::int()],
                'frequencepaiementappartement_id' => ['type' => Type::string()],
                'etatappartement_id' => ['type' => Type::int()],
                'niveauappartement' => ['type' =>  GraphQL::type('Niveauappartement')],
                'imageappartements' => ['type' => Type::listOf(GraphQL::type('Imageappartement')), 'description' => ''],
                'pieceappartements' => ['type' => Type::listOf(GraphQL::type('Pieceappartement'))],
                'locataires' => ['type' => Type::listOf(GraphQL::type('Locataire'))],
                'contrats' => ['type' => Type::listOf(GraphQL::type('Contrat'))],
                'obligationadministratives' => ['type' => Type::listOf(GraphQL::type('Obligationadministrative'))],
                'paiementloyers' => ['type' => Type::listOf(GraphQL::type('Paiementloyer'))],
                'factures' => ['type' => Type::listOf(GraphQL::type('Facture'))],
                'annonces' => ['type' => Type::listOf(GraphQL::type('Annonce'))],
                'rapportinterventions' => ['type' => Type::listOf(GraphQL::type('Rapportintervention'))],
                'entite_id' => ['type' => Type::int()],
                'lot' => ['type' => Type::string()],
                'prixvilla' => ['type' => Type::string()],
                'acomptevilla' => ['type' => Type::string()],
                'maturite' => ['type' => Type::int()],
                'ilot_id' => ['type' => Type::int()],
                'periodicite_id' => ['type' => Type::int()],
                
                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAppartementVilla($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
