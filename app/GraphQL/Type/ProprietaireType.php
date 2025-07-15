<?php

namespace App\GraphQL\Type;

use App\Appartement;
use App\Contrat;
use App\Facture;
use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use App\Proprietaire;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ProprietaireType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Proprietaire',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'nom' => ['type' => Type::string(), 'description' => ''],
                'prenom' => ['type' => Type::string(), 'description' => ''],
                'adresse' => ['type' => Type::string(), 'description' => ''],
                'telephone' => ['type' => Type::string(), 'description' => ''],
                'profession' => ['type' => Type::string(), 'description' => ''],
                'age' => ['type' => Type::string(), 'description' => ''],
                'telephoneportable' => ['type' => Type::string(), 'description' => ''],
                'telephonebureau' => ['type' => Type::string(), 'description' => ''],
                'immeubles' => ['type' => Type::listOf(GraphQL::type('Immeuble')), 'description' => ''],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement')), 'description' => ''],
                'versementloyers' => ['type' => Type::listOf(GraphQL::type('Versementloyer')), 'description' => ''],
                'questionnairesatisfactions' => ['type' => Type::listOf(GraphQL::type('Questionnairesatisfaction')), 'description' => ''],

                'montanttotalloyer' => ['type' => Type::string(), 'description' => ''],

                // sommedepense
                'sommedepense' => ['type' => Type::string(), 'description' => ''],
                //Factures

                'factures' => ['type' => Type::listOf(GraphQL::type('Facture')), 'description' => ''],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    //  montanttotalloyer resolve

    public function resolveMontanttotalloyerField($root, $args)
    {
        $montanttotalloyer = 0;
        $proprietaire = Proprietaire::find($root->id);

        if ($proprietaire) {
            $appartements = Appartement::where('proprietaire_id', $proprietaire->id)->get("id")->toArray();
            $contrats = Contrat::whereIn('appartement_id', $appartements)->get();
            $montanttotalloyer= $contrats->sum('montantloyer');
        }

        return $montanttotalloyer;
    }


    //sommedepense

    public function resolveSommedepenseField($root, $args)
    {
        $sommedepense = 0;
        $proprietaire = Proprietaire::find($root->id);
       // dd($proprietaire);

       if($proprietaire){
        $factures = Facture::where('proprietaire_id', $proprietaire->id)->get();
        $sommedepense= $factures->sum('montant');
       }
       


        return $sommedepense;
    }
}
