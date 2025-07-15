<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AppartementsQuery extends Query
{
    protected $attributes = [
        'name' => 'appartements',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Appartement'));
    }

    // arguments to filter query
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
                'niveau' => ['type' => Type::string()],
                'superficie' => ['type' => Type::string()],
                'iscontrat' => ['type' => Type::string()],
                'image' => ['type' => Type::string()],
                'imageappartements' => ['type' => Type::listOf(GraphQL::type('Imageappartement')), 'description' => ''],
                'immeuble_id' => ['type' => Type::int()],
                'proprietaire_id' => ['type' => Type::int()],
                'typeappartement_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'frequencepaiementappartement_id' => ['type' => Type::string()],
                'etatappartement_id' => ['type' => Type::int()],
                'niveauappartement' => ['type' =>  GraphQL::type('Niveauappartement')],
                'pieceappartements' => ['type' => Type::listOf(GraphQL::type('Pieceappartement'))],
                'locataires' => ['type' => Type::listOf(GraphQL::type('Locataire'))],
                'contrats' => ['type' => Type::listOf(GraphQL::type('Contrat'))],
                'obligationadministratives' => ['type' => Type::listOf(GraphQL::type('Obligationadministrative'))],
                'paiementloyers' => ['type' => Type::listOf(GraphQL::type('Paiementloyer'))],
                'factures' => ['type' => Type::listOf(GraphQL::type('Facture'))],
                'annonces' => ['type' => Type::listOf(GraphQL::type('Annonce'))],
                'rapportinterventions' => ['type' => Type::listOf(GraphQL::type('Rapportintervention'))],
                'entite_id' => ['type' => Type::int()],
                'contratproprietaire_id' => ['type' => Type::int()],
                'commissionvaleur' => ['type' => Type::int()],
                'commissionpourcentage' => ['type' => Type::int()],
                'tva' => ['type' => Type::int()],
                'brs' => ['type' => Type::int()],
                'tlv' => ['type' => Type::int()],
                'montantloyer' => ['type' => Type::int()],
                'montantcaution' => ['type' => Type::int()],

                'lot' => ['type' => Type::string()],
                'prixvilla' => ['type' => Type::string()],
                'acomptevilla' => ['type' => Type::string()],
                'maturite' => ['type' => Type::int()],
                'ilot_id' => ['type' => Type::int()],
                'periodicite_id' => ['type' => Type::int()],
                'location' => ['type' => Type::int()],
                'vente' => ['type' => Type::int()],


                'etat' => ['type' => Type::int(), 'description' => ''],

                'code' => ['type' => Type::string()],

                'position' => ['type' => Type::int()],

                'search' => ['type' => Type::string()],
                'montantvilla' => ['type' => Type::string()],

                //prix villa
                'prixappartement' => ['type' => Type::string()],


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
        $query = QueryModel::getQueryAppartement($args);
        return $query->get();
    }
}
