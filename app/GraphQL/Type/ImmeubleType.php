<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ImmeubleType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Immeuble',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'nom' => ['type' => Type::string(), 'description' => ''],
                'adresse' => ['type' => Type::string(), 'description' => ''],
                'iscopropriete' => ['type' => Type::string(), 'description' => ''],
                'structureimmeuble_id' => ['type' => Type::int(), 'description' => ''],
                'nombreascenseur' => ['type' => Type::string(), 'description' => ''],
                'nombregroupeelectrogene' => ['type' => Type::string(), 'description' => ''],
                'nombreappartement' => ['type' => Type::string(), 'description' => ''],
                'nombrepiscine' => ['type' => Type::string(), 'description' => ''],
                'equipegestion' => ['type' =>  GraphQL::type('Equipegestion')],
                'structureimmeuble' => ['type' =>  GraphQL::type('Structureimmeuble')],
                'gardien' => ['type' =>  GraphQL::type('Gardien')],
                'pieceappartements' => ['type' => Type::listOf(GraphQL::type('Pieceappartement')), 'description' => ''],
                'proprietaires' => ['type' => Type::listOf(GraphQL::type('Proprietaire')), 'description' => ''],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement')), 'description' => ''],
                'annonces' => ['type' => Type::listOf(GraphQL::type('Annonce')), 'description' => ''],
                'factures' => ['type' => Type::listOf(GraphQL::type('Facture')), 'description' => ''],

                // nbre app
                'nbreappartements' => ['type' => Type::int(), 'description' => ''],

                // nbreapplouer
                'nbreappartementslouer' => ['type' => Type::int(), 'description' => ''],

                // nbreappvide
                'nbreappartementsvide' => ['type' => Type::int(), 'description' => ''],

                'tauxoccupation' => ['type' => Type::float(), 'description' => ''],




                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }


    // nbreappartements resolver

    public function resolveNbreappartementsField($root, $args)
    {
        return $root->appartements->count();
    }

    // nbreappartementslouer resolver

    public function resolveNbreappartementslouerField($root, $args)
    {
        return $root->appartements->filter(function ($appartement) {
            return optional($appartement->etatappartement)->designation === 'En location' &&
                optional($appartement->contrats->last())->etat !== 0;
        })->count();
    }


    // nbreappartementsvide resolver

    public function resolveNbreappartementsvideField($root, $args)
    {
        return $root->appartements->filter(function ($appartement) {
            return optional($appartement->etatappartement)->designation === 'Libre';
        })->count();
    }


    // tauxoccupation

    public function resolveTauxoccupationField($root, $args)
    {
        $appLouer = $this->resolveNbreappartementslouerField($root, $args);
        $totalapp = $this->resolveNbreappartementsField($root, $args);

        if ($totalapp == 0) {
            return 0; // ou null si vous préférez
        }

        $taux = $appLouer / $totalapp;
        return round($taux * 100, 2);
    }
}
