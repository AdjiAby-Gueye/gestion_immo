<?php

namespace App\GraphQL\Type;

use App\Categorieintervention;
use App\Detaildevi;
use App\RefactoringItems\RefactGraphQLType;

use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetaildevisdetailType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Detaildevisdetail',
        'description' => ''
    ];



    public function fields(): array
    {
        return [
            'id' => ['type' => Type::id(), 'description' => ''],

            'detaildevi_id' => ['type' => Type::int(), 'description' => ''],
            'detaildevi' => ['type' => GraphQL::type('Detaildevi'), 'description' => ''],
            'unite_id' => ['type' => Type::string(), 'description' => ''],
            'unite' => ['type' => GraphQL::type('Unite'), 'description' => ''],
            'quantite' => ['type' => Type::string(), 'description' => ''],
            'prixunitaire_format' => ['type' => Type::int(), 'description' => ''],
            'prixunitaire' => ['type' => Type::string(), 'description' => ''],
            'soustypeintervention_id' => ['type' => Type::int(), 'description' => ''],
            'soustypeintervention' => ['type' => GraphQL::type('Soustypeintervention'), 'description' => ''],




            'created_at' => ['type' => Type::string(), 'description' => ''],
            'created_at_fr' => ['type' => Type::string(), 'description' => ''],
            'updated_at' => ['type' => Type::string(), 'description' => ''],
            'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
            'deleted_at' => ['type' => Type::string(), 'description' => ''],
            'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],
        ];
    }

    // crer un resole qui vas contenir le montant total de chaque categorie 
    protected function resolvePrixUnitaireFormatField($root, $args)
    {
        $quantiteParCategorie = DB::table('detaildevisdetails')
            ->selectRaw('SUM(detaildevisdetails.prixunitaire * detaildevisdetails.quantite) as quantite_totale')
            ->join('detaildevis', 'detaildevis.id', '=', 'detaildevisdetails.detaildevi_id')
            ->join('devis', 'detaildevis.devi_id', '=', 'devis.id')
            ->join('categorieinterventions', 'detaildevis.categorieintervention_id', '=', 'categorieinterventions.id')
            ->where('devis.id', $root['detaildevi']['devi']['id'])
            ->where('categorieinterventions.id', $root['detaildevi']['categorieintervention']['id'])
            ->groupBy('devis.id', 'categorieinterventions.id')
            ->first();

        // dd($quantiteParCategorie);
        if ($quantiteParCategorie) {
            return intval($quantiteParCategorie->quantite_totale); // Convertir en entier
        } else {
            return 0;
        }
    }
}
