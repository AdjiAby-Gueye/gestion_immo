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

class ContratprestationType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Contratprestation',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'datesignaturecontrat' => ['type' => Type::string(), 'description' => ''],
                'datesignaturecontrat_format' => ['type' => Type::string(), 'description' => ''],
                'datedemarragecontrat' => ['type' => Type::string(), 'description' => ''],
                'datedemarragecontrat_format' => ['type' => Type::string(), 'description' => ''],
                'daterenouvellementcontrat' => ['type' => Type::string(), 'description' => ''],
                'daterenouvellementcontrat_format' => ['type' => Type::string(), 'description' => ''],
                'datepremiereprestation' => ['type' => Type::string(), 'description' => ''],
                'datepremiereprestation_format' => ['type' => Type::string(), 'description' => ''],
                'datepremierefacture' => ['type' => Type::string(), 'description' => ''],
                'datepremierefacture_format' => ['type' => Type::string(), 'description' => ''],
                'document' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::string(), 'description' => ''],
                'montant_format' => ['type' => Type::string(), 'description' => ''],
                'frequencepaiementappartement' => ['type' =>  GraphQL::type('Frequencepaiementappartement')],
                'categorieprestation' => ['type' =>  GraphQL::type('Categorieprestation')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveDatesignaturecontratFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datesignaturecontrat']);
    }
    protected function resolveDatedemarragecontratFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datedemarragecontrat']);
    }
    protected function resolveDaterenouvellementcontratFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['daterenouvellementcontrat']);
    }
    protected function resolveDatepremiereprestationFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datepremiereprestation']);
    }
    protected function resolveDatepremierefactureFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datepremiereprestation']);
    }

    protected function resolveMontantFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montant']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
}

