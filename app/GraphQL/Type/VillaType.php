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

class VillaType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Villa',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'codeappartement' => ['type' => Type::string(), 'description' => ''],
                'isdemanderesiliation' => ['type' => Type::string(), 'description' => ''],
                'nom' => ['type' => Type::string(), 'description' => ''],
                'etatlieu' => ['type' => Type::string(), 'description' => ''],
                // 'etatlieu_text' => ['type' => Type::string(), 'description' => ''],
                // 'etatlieu_badge'=> ['type' => Type::string(), 'description' => ''],
                // 'etatlieux' => ['type' =>  GraphQL::type('Etatlieu')],
                
                'entite_id' => ['type' => Type::int()],
                'entite' => ['type' =>  GraphQL::type('Entite')],
                
                'lot' => ['type' => Type::string()],
                'prixvilla' => ['type' => Type::string()],
                'acomptevilla' => ['type' => Type::string()],
                'maturite' => ['type' => Type::int()],
                'ilot_id' => ['type' => Type::int()],
                'ilot' => ['type' => GraphQL::type('Ilot')],
                'periodicite_id' => ['type' => Type::int()],
                'periodicite' => ['type' => GraphQL::type('Periodicite')],
                

                'isassurance' => ['type' => Type::string(), 'description' => ''],
                'niveau' => ['type' => Type::string(), 'description' => ''],
                'iscontrat' => ['type' => Type::string(), 'description' => ''],
                'islocataire' => ['type' => Type::string(), 'description' => ''],
                'image' => ['type' => Type::string(), 'description' => ''],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'superficie' => ['type' => Type::string()],
                'niveauappartement' => ['type' =>  GraphQL::type('Niveauappartement')],
                'proprietaire' => ['type' =>  GraphQL::type('Proprietaire')],
                'typeappartement' => ['type' =>  GraphQL::type('Typeappartement')],
                'frequencepaiementappartement' => ['type' =>  GraphQL::type('Frequencepaiementappartement')],
                'etatappartement' => ['type' =>  GraphQL::type('Etatappartement')],
                'immeuble_id' => ['type' => Type::string(), 'description' => ''],
                'proprietaire_id' => ['type' => Type::string(), 'description' => ''],
                'typeappartement_id' => ['type' => Type::int(), 'description' => ''],
                'frequencepaiementappartement_id' => ['type' => Type::string(), 'description' => ''],
                'etatappartement_id' => ['type' => Type::int(), 'description' => ''],
                'locataire_id' => ['type' => Type::int(), 'description' => ''],
                'pieceappartements' => ['type' => Type::listOf(GraphQL::type('Pieceappartement')), 'description' => ''],
                'imageappartements' => ['type' => Type::listOf(GraphQL::type('Imageappartement')), 'description' => ''],
                'locataires' => ['type' => Type::listOf(GraphQL::type('Locataire')), 'description' => ''],
                'contrats' => ['type' => Type::listOf(GraphQL::type('Contrat')), 'description' => ''],
                'obligationadministratives' => ['type' => Type::listOf(GraphQL::type('Obligationadministrative')), 'description' => ''],
                'paiementloyers' => ['type' => Type::listOf(GraphQL::type('Paiementloyer')), 'description' => ''],
                'factures' => ['type' => Type::listOf(GraphQL::type('Facture')), 'description' => ''],
                'annonces' => ['type' => Type::listOf(GraphQL::type('Annonce')), 'description' => ''],
                'rapportinterventions' => ['type' => Type::listOf(GraphQL::type('Rapportintervention')), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }


    // protected function resolveEtatlieuTextField($root, $args)
    // {

    //     $itemArray = array("etatlieu" => $root['etatlieu']);
    //     $retour = Outil::donneEtatGeneral("appartement_etatlieu", $itemArray)['texte'];
    //     if (empty($retour)) {
    //         $retour = "";
    //     }
    //     return $retour;
    // }

    // protected function resolveEtatlieuBadgeField($root, $args)
    // {
    //     $itemArray = array("etatlieu" => $root['etatlieu']);
    //     $retour = Outil::donneEtatGeneral("appartement_etatlieu", $itemArray)['badge'];
    //     if (empty($retour)) {
    //         $retour = "";
    //     }
    //     return $retour;
    // }

}

