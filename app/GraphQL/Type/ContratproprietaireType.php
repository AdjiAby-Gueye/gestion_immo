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

class ContratproprietaireType extends RefactGraphQLType{
    protected $attributes = [
        'name' => 'Contratproprietaire',
        'description' => ''
        ];


    public function fields():array{
        return [
            'id' => ['type' => Type::id(), 'description' => '' ],
            'date' => ['type' => Type::string(), 'description' => '' ],
            'descriptif' => ['type' => Type::string(), 'description' => ''],
            'commissionvaleur' => ['type' => Type::int(), 'description' => ''],
            'commissionpourcentage' => ['type' => Type::int(), 'description' => ''],
            'is_tva' => ['type' => Type::int(), 'description' => ''],
            'is_brs' => ['type' => Type::int(), 'description' => ''],
            'is_tlv' => ['type' => Type::int(), 'description' => ''],

            'is_tva_text' => ['type' => Type::string(), 'description' => ''],
            'is_brs_text' => ['type' => Type::string(), 'description' => ''],
            'is_tlv_text' => ['type' => Type::string(), 'description' => ''],

            'entite_id' => ['type' => Type::id(), 'description' => ''],
            'entite' => ['type' =>  GraphQL::type('Entite')],
            'proprietaire_id' => ['type' => Type::id(), 'description' => ''],
            'proprietaire' => ['type' =>  GraphQL::type('Proprietaire')],
            'modelcontrat_id' => ['type' => Type::id(), 'description' => ''],
            'modelcontrat' => ['type' =>  GraphQL::type('Modelcontrat')],
        ];
    }

    protected function resolveIsTvaTextField($root , $args) {
        $tva_text = 'NON';
        if($root['is_tva'] == 1){
            $tva_text = 'OUI';
        }
        return $tva_text;
    }

    protected function resolveIsBrsTextField($root , $args) {
        $brs_text = 'NON';
        if($root['is_brs'] == 1){
            $brs_text = 'OUI';
        }
        return $brs_text;
    }
    protected function resolveIsTlvTextField($root , $args) {
        $tlv_text = 'NON';
        if($root['is_tlv'] == 1){
            $tlv_text = 'OUI';
        }
        return $tlv_text;
    }

}
