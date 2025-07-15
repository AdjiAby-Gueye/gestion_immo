<?php

namespace App\GraphQL\Type;

use App\Outil;


use Psy\Util\Str;
use App\Detailpaiement;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PaiementloyerType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Paiementloyer',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'datepaiement' => ['type' => Type::string(), 'description' => ''],
                'datepaiement_format' => ['type' => Type::string(), 'description' => ''],
                'codefacture' => ['type' => Type::string(), 'description' => ''],
                'numero_cheque' => ['type' => Type::string(), 'description' => ''],
                'montantfacture' => ['type' => Type::string(), 'description' => ''],
                'montantfacture_format' => ['type' => Type::string(), 'description' => ''],
                'montant_paiement' => ['type' => Type::string(), 'description' => ''],
                'montant_paiement_format' => ['type' => Type::string(), 'description' => ''],
                

                // ajout recent
                'facturelocation_id' => ['type' => Type::int(), 'description' => ''],
                'facturelocation' => ['type' => GraphQL::type('Facturelocation'), 'description' => ''],

                'factureeaux_id' => ['type' => Type::int(), 'description' => ''],
                'factureeaux' => ['type' => GraphQL::type('Facturelocation'), 'description' => ''],

                
                'debutperiodevalide' => ['type' => Type::string(), 'description' => ''],
                'finperiodevalide' => ['type' => Type::string(), 'description' => ''],
                'periode' => ['type' => Type::string(), 'description' => ''],
                'contrat' => ['type' =>  GraphQL::type('Contrat')],
                'appartement_id' => ['type' => Type::int(), 'description' => ''],
                'contrat_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement'))],
                'modepaiement_id' => ['type' => Type::int()],
                'modepaiement' => ['type' =>  GraphQL::type('Modepaiement')],
                'detailpaiements' => ['type' => Type::listOf(GraphQl::type('Detailpaiement'))],

                'motif_annulation_paiement' => ['type' => Type::string()],
                'date_annulation_paiement' => ['type' => Type::string()],
                'date_reactivation_paiement' => ['type' => Type::string()],
                'justificatif_paiement' => ['type' => Type::string()],
                'date_annulation_paiement_format' => ['type' => Type::string()],
                'date_reactivation_paiement_format' => ['type' => Type::string()],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveDateReactivationPaiementFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date_reactivation_paiement']);
    }
    protected function resolveDateAnnulationPaiementFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date_annulation_paiement']);
    }
    protected function resolveDatePaiementFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datepaiement']);
    }

    protected function resolveMontantPaiementFormatField($root, $args)
    {

        $valeur_ht_format = Outil::numberToLetter(self::sumAmount($root));
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveMontantPaiementField($root, $args)
    {
        $amount = self::sumAmount($root);
        $valeur_ht_format = Outil::formatPrixToMonetaire($amount);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }

    protected static function sumAmount($root) {
        return Detailpaiement::where("paiementloyer_id" , $root['id'])
            ->sum("montant");
    }
}

