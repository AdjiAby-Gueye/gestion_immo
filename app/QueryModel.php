<?php

namespace App;

use App\GraphQL\Query\FraisupplementairesQuery;
use App\Ilot;
use App\Inbox;
use App\Annexe;
use App\Entite;
use App\Avenant;
use App\Copreneur;
use Carbon\Carbon;
use App\Attachement;
use App\Periodicite;
use App\Avisecheance;
use App\Modepaiement;
use App\Detailpaiement;
use App\Factureacompte;
use App\Paiementecheance;
use App\Historiquerelance;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class QueryModel extends Model
{

    public static function joined($query, $table)
    {
        $joins = $query->getQuery()->joins;
        if ($joins == null) {
            return false;
        }
        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }
        return false;
    }

    public static function getOderDynamic($query, $args, $model)
    {

        $table_name  = $model->table;
        if (isset($args['order'])) {
            $order  = explode(',', $args['order']);

            if (isset($order)) {
                foreach ($order as $key => $val) {

                    if (isset($val) && !empty($val)) {
                        $direction = 'DESC';

                        if (isset($args['direction'])) {
                            $direction  = $args['direction'];
                        }

                        if (Schema::hasColumn($table_name, $val . "_id")) {
                            $table_foreign      = $val . 's';
                            $idForeign          = $val . "_id";
                            $collumnForeign     = 'id';

                            if (Schema::hasTable($table_foreign)) {
                                if (Schema::hasColumn($table_foreign, "designation")) {
                                    $collumnForeign = 'designation';
                                } else {
                                    if ($table_foreign == 'clients') {
                                        $collumnForeign = 'raison_sociale';
                                    }
                                }
                                if (!self::joined($query, $table_foreign)) {
                                    $query          = $query->join($table_foreign, $table_foreign . '.id', $table_name . '.' . $idForeign);
                                }

                                $query->orderBy($table_foreign . '.' . $collumnForeign, $direction);
                            }
                        } else if (Schema::hasColumn($table_name, $val)) {
                            $query->orderBy($table_name . '.' . $val, $direction);
                        }
                    }
                }
            }
        } else {
            $query->orderBy($table_name . '.id', 'desc');
        }

        return $query;
    }


    //********************************************************************************** */

    public static function getQueryPermission($args)
    {
        $query = Permission::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['name'])) {
            $query = $query = $query->where('name', Outil::getOperateurLikeDB(), '%' . $args['name'] . '%');
        }

        if (isset($args['display_name'])) {
            $query = $query = $query->where('display_name', Outil::getOperateurLikeDB(), '%' . $args['display_name'] . '%');
        }

        if (isset($args['designation'])) {
            $query = $query = $query->where('display_name', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }

        if (isset($args['activer'])) {
            $query = $query = $query->where('activer', $args['activer']);
        }
        if (isset($args['search'])) {
            $motRecherche  = $args['search'];
            $query->where(function ($query) use ($motRecherche) {
                return $query->where('name', Outil::getOperateurLikeDB(), '%' . $motRecherche . '%')
                    ->orWhere('display_name', Outil::getOperateurLikeDB(), '%' . $motRecherche . '%');
            });
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryAnnonce($args)
    {
        $query = Annonce::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['titre'])) {
            $query = $query->where('titre', Outil::getOperateurLikeDB(), '%' . $args['titre'] . '%');
        }
        if (isset($args['debut'])) {
            $query = $query->where('debut', Outil::getOperateurLikeDB(), '%' . $args['debut'] . '%');
        }
        if (isset($args['fin'])) {
            $query = $query->where('fin', $args['fin']);
        }
        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }
        if (isset($args['immeuble_id'])) {
            $query = $query->where('immeuble_id', $args['immeuble_id']);
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        if (isset($args['documents'])) {
            $query = $query->where('documents', $args['documents']);
        }
        if (isset($args['locataire_id'])) {
            $appartement = Contrat::where('locataire_id',$args['locataire_id'])->pluck('appartement_id');
            $query = $query->where('appartement_id',$appartement);
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryLocataire_message($args)
    {
        $query = Locataire_message::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', Outil::getOperateurLikeDB(), '%' . $args['locataire_id'] . '%');
        }
        if (isset($args['message_id'])) {
            $query = $query->where('message_id', Outil::getOperateurLikeDB(), '%' . $args['message_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryImageappartement($args)
    {
        $query = Imageappartement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['image'])) {
            $query = $query->where('image', Outil::getOperateurLikeDB(), '%' . $args['image'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        if (isset($args['appartement'])) {
            $query = $query->where('appartement', $args['appartement']);
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryImagecomposition($args)
    {
        $query = Imagecomposition::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['image'])) {
            $query = $query->where('image', Outil::getOperateurLikeDB(), '%' . $args['image'] . '%');
        }
        if (isset($args['composition_id'])) {
            $query = $query->where('composition_id', Outil::getOperateurLikeDB(), '%' . $args['composition_id'] . '%');
        }
        if (isset($args['composition'])) {
            $query = $query->where('composition', $args['composition']);
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryImageetatlieupiece($args)
    {
        $query = Imageetatlieupiece::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['image'])) {
            $query = $query->where('image', Outil::getOperateurLikeDB(), '%' . $args['image'] . '%');
        }
        if (isset($args['etatlieupiece_id'])) {
            $query = $query->where('etatlieupiece_id', Outil::getOperateurLikeDB(), '%' . $args['etatlieupiece_id'] . '%');
        }
        if (isset($args['etatlieu_piece'])) {
            $query = $query->where('etatlieu_piece', $args['etatlieu_piece']);
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQuerySecurite($args)
    {
        $query = Securite::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['adresse'])) {
            $query = $query->where('adresse', Outil::getOperateurLikeDB(), '%' . $args['adresse'] . '%');
        }
        if (isset($args['telephone1'])) {
            $query = $query->where('telephone1', Outil::getOperateurLikeDB(), '%' . $args['telephone1'] . '%');
        }
        if (isset($args['telephone2'])) {
            $query = $query->where('telephone2', Outil::getOperateurLikeDB(), '%' . $args['telephone2'] . '%');
        }
        if (isset($args['immeuble_id'])) {
            $query = $query->where('immeuble_id', $args['immeuble_id']);
        }
        if (isset($args['horaire_id'])) {
            $query = $query->where('horaire_id', $args['horaire_id']);
        }
        if (isset($args['prestataire_id'])) {
            $query = $query->where('prestataire_id', $args['prestataire_id']);
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryDetailconstituant($args)
    {
        $query = Detailconstituant::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['commentaire'])) {
            $query = $query->where('commentaire', Outil::getOperateurLikeDB(), '%' . $args['commentaire'] . '%');
        }
        if (isset($args['etatlieu_piece'])) {
            $query = $query->where('etatlieu_piece', Outil::getOperateurLikeDB(), '%' . $args['etatlieu_piece'] . '%');
        }
        if (isset($args['constituantpiece'])) {
            $query = $query->where('constituantpiece', $args['constituantpiece']);
        }
        if (isset($args['observation'])) {
            $query = $query->where('observation', Outil::getOperateurLikeDB(), '%' . $args['observation'] . '%');
        }
        if (isset($args['etatlieu_piece_id'])) {
            $query = $query->where('etatlieu_piece_id', $args['etatlieu_piece_id']);
        }
        if (isset($args['constituantpiece_id'])) {
            $query = $query->where('constituantpiece_id', Outil::getOperateurLikeDB(), '%' . $args['constituantpiece_id'] . '%');
        }
        if (isset($args['observation_id'])) {
            $query = $query->where('observation_id', $args['observation_id']);
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryDetailintervention($args)
    {
        $query = Detailintervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['intervention_id'])) {
            $query = $query->where('intervention_id', Outil::getOperateurLikeDB(), '%' . $args['intervention_id'] . '%');
        }
        if (isset($args['detailconstituant_id'])) {
            $query = $query->where('detailconstituant_id', Outil::getOperateurLikeDB(), '%' . $args['detailconstituant_id'] . '%');
        }
        if (isset($args['detailequipement_id'])) {
            $query = $query->where('detailequipement_id', $args['detailequipement_id']);
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryDetailequipement($args)
    {
        $query = Detailequipement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['commentaire'])) {
            $query = $query->where('commentaire', Outil::getOperateurLikeDB(), '%' . $args['commentaire'] . '%');
        }
        if (isset($args['etatlieu_piece'])) {
            $query = $query->where('etatlieu_piece', Outil::getOperateurLikeDB(), '%' . $args['etatlieu_piece'] . '%');
        }
        if (isset($args['equipementpiece'])) {
            $query = $query->where('equipementpiece', $args['equipementpiece']);
        }
        if (isset($args['observation'])) {
            $query = $query->where('observation', Outil::getOperateurLikeDB(), '%' . $args['observation'] . '%');
        }
        if (isset($args['etatlieu_piece_id'])) {
            $query = $query->where('etatlieu_piece_id', $args['etatlieu_piece_id']);
        }
        if (isset($args['equipementpiece_id'])) {
            $query = $query->where('equipementpiece_id', Outil::getOperateurLikeDB(), '%' . $args['equipementpiece_id'] . '%');
        }
        if (isset($args['observation_id'])) {
            $query = $query->where('observation_id', $args['observation_id']);
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryEtatlieu_piece($args)
    {
        $query = Etatlieu_piece::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['etatlieu'])) {
            $query = $query->where('etatlieu', Outil::getOperateurLikeDB(), '%' . $args['etatlieu'] . '%');
        }
        if (isset($args['pieceappartement'])) {
            $query = $query->where('pieceappartement', Outil::getOperateurLikeDB(), '%' . $args['pieceappartement'] . '%');
        }
        if (isset($args['composition'])) {
            $query = $query->where('composition', $args['composition']);
        }
        if (isset($args['etatlieu_id'])) {
            $query = $query->where('etatlieu_id', Outil::getOperateurLikeDB(), '%' . $args['etatlieu_id'] . '%');
        }
        if (isset($args['composition_id'])) {
            $query = $query->where('composition_id', $args['composition_id']);
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryFactureeaux($args)
    {
        $query = Factureeaux::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', $args['contrat_id']);
        }

        if (isset($args['est_activer'])) {
            $query = $query->where('est_activer', $args['est_activer']);
        }


        if (isset($args['demanderesiliation_id'])) {
            $demandeResiliation = Demanderesiliation::find($args['demanderesiliation_id']);
            if (isset($demandeResiliation) && isset($demandeResiliation->id)) {
                $query = $query->where('contrat_id', $demandeResiliation->contrat_id);
            }
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryComposition($args)
    {
        $query = Composition::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['typeappartement_piece'])) {
            $query = $query->where('typeappartement_piece', Outil::getOperateurLikeDB(), '%' . $args['typeappartement_piece'] . '%');
        }
        if (isset($args['appartement'])) {
            $query = $query->where('appartement', Outil::getOperateurLikeDB(), '%' . $args['appartement'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id',  $args['appartement_id']);
        }

        if (isset($args['niveauappartement_id'])) {
            $query = $query->where('niveauappartement_id', $args['niveauappartement_id']);
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryDetailcomposition($args)
    {
        $query = Detailcomposition::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['idDetailtypeappartement'])) {
            $query = $query->where('idDetailtypeappartement', Outil::getOperateurLikeDB(), '%' . $args['idDetailtypeappartement'] . '%');
        }
        if (isset($args['composition'])) {
            $query = $query->where('composition', Outil::getOperateurLikeDB(), '%' . $args['composition'] . '%');
        }
        if (isset($args['equipementpiece'])) {
            $query = $query->where('equipementpiece', $args['equipementpiece']);
        }
        if (isset($args['composition_id'])) {
            $query = $query->where('composition_id', $args['composition_id']);
        }
        if (isset($args['equipement_id'])) {
            $query = $query->where('equipement_id', $args['equipement_id']);
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', $args['appartement_id']);
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryGardien($args)
    {
        $query = Gardien::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['prenom'])) {
            $query = $query->where('prenom', Outil::getOperateurLikeDB(), '%' . $args['prenom'] . '%');
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['adresse'])) {
            $query = $query->where('adresse', $args['adresse']);
        }
        if (isset($args['telephone1'])) {
            $query = $query->where('telephone1', Outil::getOperateurLikeDB(), '%' . $args['telephone1'] . '%');
        }
        if (isset($args['telephone2'])) {
            $query = $query->where('telephone2', $args['telephone2']);
        }
        if (isset($args['immeuble'])) {
            $query = $query->where('immeuble', Outil::getOperateurLikeDB(), '%' . $args['immeuble'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryAppartement_locataire($args)
    {
        $query = Appartement_locataire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['appartement'])) {
            $query = $query->where('appartement', Outil::getOperateurLikeDB(), '%' . $args['appartement'] . '%');
        }
        if (isset($args['locataire'])) {
            $query = $query->where('locataire', Outil::getOperateurLikeDB(), '%' . $args['locataire'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryImmeuble_proprietaire($args)
    {
        $query = Immeuble_proprietaire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['immeuble'])) {
            $query = $query->where('immeuble', Outil::getOperateurLikeDB(), '%' . $args['immeuble'] . '%');
        }
        if (isset($args['proprietaire'])) {
            $query = $query->where('proprietaire', Outil::getOperateurLikeDB(), '%' . $args['proprietaire'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryEquipegestion_membreequipegestion($args)
    {
        $query = Equipegestion_membreequipegestion::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['equipegestion'])) {
            $query = $query->where('equipegestion', Outil::getOperateurLikeDB(), '%' . $args['equipegestion'] . '%');
        }
        if (isset($args['membreequipegestion'])) {
            $query = $query->where('membreequipegestion', Outil::getOperateurLikeDB(), '%' . $args['membreequipegestion'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryAssureur($args)
    {
        $query = Assureur::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryTypeappartement_piece($args)
    {
        $query = Typeappartement_piece::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['typeappartement'])) {
            $query = $query->where('typeappartement', Outil::getOperateurLikeDB(), '%' . $args['typeappartement'] . '%');
        }
        if (isset($args['typepiece'])) {
            $query = $query->where('typepiece', Outil::getOperateurLikeDB(), '%' . $args['typepiece'] . '%');
        }
        if (isset($args['commentaire'])) {
            $query = $query->where('commentaire', Outil::getOperateurLikeDB(), '%' . $args['commentaire'] . '%');
        }
        if (isset($args['typeappartement_id'])) {
            $query = $query->where('typeappartement_id', Outil::getOperateurLikeDB(), '%' . $args['typeappartement_id'] . '%');
        }
        if (isset($args['niveauappartement_id'])) {
            $query = $query->where('niveauappartement_id',  $args['niveauappartement_id']);
        }
        if (isset($args['typepiece_id'])) {
            $query = $query->where('typepiece_id',  $args['typepiece_id']);
        }


        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryAssurance($args)
    {
        $query = Assurance::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['debut'])) {
            $query = $query->where('debut', Outil::getOperateurLikeDB(), '%' . $args['debut'] . '%');
        }
        if (isset($args['fin'])) {
            $query = $query->where('fin', $args['fin']);
        }
        if (isset($args['typeassurance'])) {
            $query = $query->where('typeassurance', $args['typeassurance']);
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['assureur_id'])) {
            $query = $query->where('assureur_id', $args['assureur_id']);
        }
        if (isset($args['etatassurance_id'])) {
            $query = $query->where('etatassurance_id', Outil::getOperateurLikeDB(), '%' . $args['etatassurance_id'] . '%');
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', $args['contrat_id']);
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryStructureimmeuble($args)
    {
        $query = Structureimmeuble::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['etages'])) {
            $query = $query->where('etages', Outil::getOperateurLikeDB(), '%' . $args['etages'] . '%');
        }
        if (isset($args['immeubles'])) {
            $query = $query->where('immeubles', Outil::getOperateurLikeDB(), '%' . $args['immeubles'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryNiveauappartement($args)
    {
        $query = Niveauappartement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['nombre'])) {
            $query = $query->where('nombre', $args['nombre']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['appartements'])) {
            $query = $query->where('appartements', Outil::getOperateurLikeDB(), '%' . $args['appartements'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryCategorieintervention($args)
    {
        $query = Categorieintervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['interventions'])) {
            $query = $query->where('interventions', Outil::getOperateurLikeDB(), '%' . $args['interventions'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }


    public static function getQueryCategorieprestation($args)
    {
        $query = Categorieprestation::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['contratprestations'])) {
            $query = $query->where('contratprestations', Outil::getOperateurLikeDB(), '%' . $args['contratprestations'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryCategorieprestataire($args)
    {
        $query = Categorieprestataire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['prestataire'])) {
            $query = $query->where('prestataire', Outil::getOperateurLikeDB(), '%' . $args['prestataire'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryHoraire($args)
    {
        $query = Horaire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['debut'])) {
            $query = $query->where('debut', Outil::getOperateurLikeDB(), '%' . $args['debut'] . '%');
        }
        if (isset($args['fin'])) {
            $query = $query->where('fin', Outil::getOperateurLikeDB(), '%' . $args['fin'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryHistoriqueRelance($args)
    {
        $query = Historiquerelance::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['date_envoie'])) {
            $query = $query->whereDate('date_envoie', $args['date_envoie']);
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', $args['contrat_id']);
        }
        if (isset($args['user_id'])) {
            $query = $query->where('user_id', $args['user_id']);
        }
        if (isset($args['inbox_id'])) {
            $query = $query->where('inbox_id', $args['inbox_id']);
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', $args['locataire_id']);
        }

        if (isset($args['avisecheance_id'])) {
            $query = $query->where('avisecheance_id', $args['avisecheance_id']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryConstituantpiece($args)
    {
        $query = Constituantpiece::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['commentaire'])) {
            $query = $query->where('commentaire', Outil::getOperateurLikeDB(), '%' . $args['commentaire'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', Outil::getOperateurLikeDB(), '%' . $args['etat'] . '%');
        }
        if (isset($args['etatlieu_id'])) {
            $query = $query->where('etatlieu_id', Outil::getOperateurLikeDB(), '%' . $args['etatlieu_id'] . '%');
        }
        if (isset($args['observation_id'])) {
            $query = $query->where('observation_id', Outil::getOperateurLikeDB(), '%' . $args['observation_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryContactprestataire($args)
    {
        $query = Contactprestataire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['prenom'])) {
            $query = $query->where('prenom', Outil::getOperateurLikeDB(), '%' . $args['prenom'] . '%');
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['telephone'])) {
            $query = $query->where('telephone', Outil::getOperateurLikeDB(), '%' . $args['telephone'] . '%');
        }
        if (isset($args['email'])) {
            $query = $query->where('email', Outil::getOperateurLikeDB(), '%' . $args['email'] . '%');
        }
        if (isset($args['prestataire_id'])) {
            $query = $query->where('prestataire_id', Outil::getOperateurLikeDB(), '%' . $args['prestataire_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryContratprestation($args)
    {
        $query = Contratprestation::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['datesignaturecontrat'])) {
            $query = $query->where('datesignaturecontrat', Outil::getOperateurLikeDB(), '%' . $args['datesignaturecontrat'] . '%');
        }
        if (isset($args['datedemarragecontrat'])) {
            $query = $query->where('datedemarragecontrat', Outil::getOperateurLikeDB(), '%' . $args['datedemarragecontrat'] . '%');
        }
        if (isset($args['daterenouvellementcontrat'])) {
            $query = $query->where('daterenouvellementcontrat', Outil::getOperateurLikeDB(), '%' . $args['daterenouvellementcontrat'] . '%');
        }
        if (isset($args['frequenceprestation'])) {
            $query = $query->where('frequenceprestation', Outil::getOperateurLikeDB(), '%' . $args['frequenceprestation'] . '%');
        }
        if (isset($args['datepremiereprestation'])) {
            $query = $query->where('datepremiereprestation', Outil::getOperateurLikeDB(), '%' . $args['datepremiereprestation'] . '%');
        }
        if (isset($args['datepremierefacture'])) {
            $query = $query->where('datepremierefacture', Outil::getOperateurLikeDB(), '%' . $args['datepremierefacture'] . '%');
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['prestataire'])) {
            $query = $query->where('prestataire', Outil::getOperateurLikeDB(), '%' . $args['prestataire'] . '%');
        }
        if (isset($args['categorieprestation'])) {
            $query = $query->where('categorieprestation', Outil::getOperateurLikeDB(), '%' . $args['categorieprestation'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryCaution($args)
    {
        $query = Caution::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['montantloyer'])) {
            $query = $query->where('montantloyer', Outil::getOperateurLikeDB(), '%' . $args['montantloyer'] . '%');
        }
        if (isset($args['montantcaution'])) {
            $query = $query->where('montantcaution', Outil::getOperateurLikeDB(), '%' . $args['montantcaution'] . '%');
        }
        if (isset($args['codeappartement'])) {
            $query = $query->where('codeappartement', Outil::getOperateurLikeDB(), '%' . $args['codeappartement'] . '%');
        }
        if (isset($args['dateversement'])) {
            $query = $query->where('dateversement', Outil::getOperateurLikeDB(), '%' . $args['dateversement'] . '%');
        }
        if (isset($args['datepaiement'])) {
            $query = $query->where('datepaiement', Outil::getOperateurLikeDB(), '%' . $args['datepaiement'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', Outil::getOperateurLikeDB(), '%' . $args['etat'] . '%');
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', Outil::getOperateurLikeDB(), '%' . $args['contrat_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryAppartement($args)
    {
        $query = Appartement::query();
        $user = Auth::user();
        $entite = $user->entite ?? null;

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        } else if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', $args['locataire_id']);
        }
        else {
            $query = $query->whereDoesntHave('etatappartement', function ($q) {
                $q->where('designation', 'Archive');
            });
        }

        if (isset($entite)) {
            $query = $query->where('entite_id', $entite->id);
        }

        // immeuble

        if (isset($args['immeuble_id'])) {
            $query = $query->where('immeuble_id', $args['immeuble_id']);
        }





        if (isset($args['code']) && isset($args['etat'])) {
            $query = $query->whereHas('entite', function ($q) use ($args) {
                $q->where('code', $args['code']);
            });

            if ($args['etat'] == 0) {
                $query = $query->whereHas('etatappartement', function ($q) {
                    $q->where('designation', 'Libre');
                });

            } else {
                $query = $query->whereHas('etatappartement', function ($q) {
                    $q->where('designation', 'En location');
                });
                // et dont le dernier contrat de cette app a un etat diferent de zero


            }
        }




        if (isset($args['location'])) {
            $query = $query->join("entites", "entites.id", "appartements.entite_id")
                ->where('entites.location', 1)
                ->select("appartements.*");
        }

        if (isset($args['vente'])) {
            $query = $query->join("entites", "entites.id", "appartements.entite_id")
                ->where('entites.vente', 1)
                ->select("appartements.*");
        }

        if (isset($args['datedeb']) && isset($args['datefin'])) {
            $query = $query->whereHas('contrats', function ($q) use ($args) {
                $q->whereHas('paiementloyers', function ($q) use ($args) {
                    $q->whereHas('facturelocation', function ($q) use ($args) {
                        $q->whereBetween('datefacture', [$args['datedeb'], $args['datefin']]);
                    });
                });
            });
        }



        if (isset($args['codeappartement'])) {
            $query = $query->where('codeappartement', Outil::getOperateurLikeDB(), '%' . $args['codeappartement'] . '%');
        }
        if (isset($args['etatlieu'])) {
            $query = $query->where('etatlieu', Outil::getOperateurLikeDB(), '%' . $args['etatlieu'] . '%');
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['superficie'])) {
            $query = $query->where('superficie', Outil::getOperateurLikeDB(), '%' . $args['superficie'] . '%');
        }
        if (isset($args['niveau'])) {
            $query = $query->where('niveau', Outil::getOperateurLikeDB(), '%' . $args['niveau'] . '%');
        }
        if (isset($args['isassurance'])) {
            $query = $query->where('isassurance', $args['isassurance']);
        }
        if (isset($args['iscontrat'])) {
            $query = $query->where('iscontrat', Outil::getOperateurLikeDB(), '%' . $args['iscontrat'] . '%');
        }
        if (isset($args['islocataire'])) {
            $query = $query->where('islocataire', $args['islocataire']);
        }
        if (isset($args['immeuble_id'])) {
            $query = $query->where('immeuble_id',  $args['immeuble_id']);
        }

        if (isset($args['proprietaire_id'])) {
            $query = $query->where('proprietaire_id', Outil::getOperateurLikeDB(), '%' . $args['proprietaire_id'] . '%');
        }
        if (isset($args['typeappartement_id'])) {
            $query = $query->where('typeappartement_id', Outil::getOperateurLikeDB(), '%' . $args['typeappartement_id'] . '%');
        }
        if (isset($args['frequencepaiementappartement_id'])) {
            $query = $query->where('frequencepaiementappartement_id', Outil::getOperateurLikeDB(), '%' . $args['frequencepaiementappartement_id'] . '%');
        }
        if (isset($args['etatappartement_id'])) {
            $query = $query->where('etatappartement_id', Outil::getOperateurLikeDB(), '%' . $args['etatappartement_id'] . '%');
        }
        if (isset($args['pieceappartements'])) {
            $query = $query->where('pieceappartements', $args['pieceappartements']);
        }
        if (isset($args['locataires'])) {
            $query = $query->where('locataires', $args['locataires']);
        }
        if (isset($args['contrats'])) {
            $query = $query->where('contrats', $args['contrats']);
        }
        if (isset($args['obligationadministratives'])) {
            $query = $query->where('obligationadministratives', $args['obligationadministratives']);
        }
        if (isset($args['paiementloyers'])) {
            $query = $query->where('paiementloyers', $args['paiementloyers']);
        }
        if (isset($args['factures'])) {
            $query = $query->where('factures', $args['factures']);
        }
        if (isset($args['annonces'])) {
            $query = $query->where('annonces', $args['annonces']);
        }
        if (isset($args['rapportinterventions'])) {
            $query = $query->where('rapportinterventions', $args['rapportinterventions']);
        }
        if (isset($args['entite_id'])) {
            $query = $query->where('entite_id', $args['entite_id']);
        }
        if (isset($args['lot'])) {
            $query = $query->where('lot', $args['lot']);
        }
        if (isset($args['prixvilla'])) {
            $query = $query->where('prixvilla', $args['prixvilla']);
        }
        if (isset($args['acomptevilla'])) {
            $query = $query->where('acomptevilla', $args['acomptevilla']);
        }
        if (isset($args['maturite'])) {
            $query = $query->where('maturite', $args['maturite']);
        }
        if (isset($args['ilot_id'])) {
            $query = $query->where('ilot_id', $args['ilot_id']);
        }
        if (isset($args['periodicite_id'])) {
            $query = $query->where('periodicite_id', $args['periodicite_id']);
        }
        if (isset($args['contratproprietaire_id'])) {
            $query = $query->where('contratproprietaire_id', $args['contratproprietaire_id']);
        }
        if (isset($args['commissionvaleur'])) {
            $query = $query->where('commissionvaleur', $args['commissionvaleur']);
        }
        if (isset($args['commissionpourcentage'])) {
            $query = $query->where('commissionpourcentage', $args['commissionpourcentage']);
        }
        if (isset($args['tva'])) {
            $query = $query->where('tva', $args['tva']);
        }
        if (isset($args['brs'])) {
            $query = $query->where('brs', $args['brs']);
        }
        if (isset($args['tlv'])) {
            $query = $query->where('tlv', $args['tlv']);
        }
        if (isset($args['montantloyer'])) {
            $query = $query->where('montantloyer', $args['montantloyer']);
        }
        if (isset($args['montantcaution'])) {
            $query = $query->where('montantcaution', $args['montantcaution']);
        }
        if (isset($args['search'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['search'] . '%');
        }
        if (isset($args['montantvilla'])) {
            $query = $query->where('montantvilla', Outil::getOperateurLikeDB(), '%' . $args['montantvilla'] . '%');
        }
        if (isset($args['prixappartement'])) {
            $query = $query->where('prixappartement', Outil::getOperateurLikeDB(), '%' . $args['prixappartement'] . '%');
        }



        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryAppartementVilla($args)
    {
        $query = Appartement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        // $user_id = Auth::user()->id;
        $entite = Entite::where("code", "RID")->first();
        if ($entite && $entite->code) {
            $query = $query->where('entite_id', $entite->id);
        }
        if (isset($args['codeappartement'])) {
            $query = $query->where('codeappartement', Outil::getOperateurLikeDB(), '%' . $args['codeappartement'] . '%');
        }
        if (isset($args['etatlieu'])) {
            $query = $query->where('etatlieu', Outil::getOperateurLikeDB(), '%' . $args['etatlieu'] . '%');
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['superficie'])) {
            $query = $query->where('superficie', Outil::getOperateurLikeDB(), '%' . $args['superficie'] . '%');
        }
        if (isset($args['niveau'])) {
            $query = $query->where('niveau', Outil::getOperateurLikeDB(), '%' . $args['niveau'] . '%');
        }
        if (isset($args['isassurance'])) {
            $query = $query->where('isassurance', $args['isassurance']);
        }
        if (isset($args['iscontrat'])) {
            $query = $query->where('iscontrat', Outil::getOperateurLikeDB(), '%' . $args['iscontrat'] . '%');
        }
        if (isset($args['islocataire'])) {
            $query = $query->where('islocataire', $args['islocataire']);
        }

        if (isset($args['locataire_id'])) {
            $query = $query->where('appartements.locataire_id', $args['locataire_id']);
        }
        if (isset($args['proprietaire_id'])) {
            $query = $query->where('proprietaire_id', Outil::getOperateurLikeDB(), '%' . $args['proprietaire_id'] . '%');
        }
        if (isset($args['typeappartement_id'])) {
            $query = $query->where('typeappartement_id', Outil::getOperateurLikeDB(), '%' . $args['typeappartement_id'] . '%');
        }
        if (isset($args['frequencepaiementappartement_id'])) {
            $query = $query->where('frequencepaiementappartement_id', Outil::getOperateurLikeDB(), '%' . $args['frequencepaiementappartement_id'] . '%');
        }
        if (isset($args['etatappartement_id'])) {
            $query = $query->where('etatappartement_id', Outil::getOperateurLikeDB(), '%' . $args['etatappartement_id'] . '%');
        }

        if (isset($args['contrats'])) {
            $query = $query->where('contrats', $args['contrats']);
        }


        if (isset($args['entite_id'])) {
            // $query = $query->where('entite_id', $args['entite_id']);
        }
        if (isset($args['lot'])) {
            $query = $query->where('lot', $args['lot']);
        }
        if (isset($args['prixvilla'])) {
            $query = $query->where('prixvilla', $args['prixvilla']);
        }
        if (isset($args['acomptevilla'])) {
            $query = $query->where('acomptevilla', $args['acomptevilla']);
        }
        if (isset($args['maturite'])) {
            $query = $query->where('maturite', $args['maturite']);
        }
        if (isset($args['ilot_id'])) {
            $query = $query->where('ilot_id', $args['ilot_id']);
        }
        if (isset($args['periodicite_id'])) {
            $query = $query->where('periodicite_id', $args['periodicite_id']);
        }



        $query = $query->orderBy('id', 'DESC');
        return $query;
    }


    public static function getQueryContrat($args)
    {
        $query = Contrat::query();
        $user = Auth::user();
        $entite = $user->entite ?? null;
        if (isset($args['id'])) {
            $query = $query->where('contrats.id', $args['id']);
        } else
        if (isset($args['locataire_id'])) {
            $query = $query->where('contrats.locataire_id',  $args['locataire_id']);
        } else  if (isset($args['appartement_id'])) {
            $query = $query->where('contrats.appartement_id', $args['appartement_id']);
        } else {
            if(isset($entite)){
            // dd($entite->code);
                $query = $query->join("appartements", "appartements.id", "contrats.appartement_id")
                    ->join("entites", "entites.id", "appartements.entite_id")
                    ->where('entites.code', $entite->code)->select("contrats.*");
            }else{

                $query = $query->join("appartements", "appartements.id", "contrats.appartement_id")
                    ->join("entites", "entites.id", "appartements.entite_id")
                    ->where('entites.code', "SCI")
                    ->select("contrats.*");
            }

        }
        if (isset($args['copreneur_id'])) {
            $query = $query->where("copreneur_id", $args['copreneur_id']);
        }

        if (isset($args['factureeaux_id'])) {
            $query = $query->where('factureeaux_id', $args['factureeaux_id']);
        }

        if (isset($args['est_copreuneur'])) {
            $query = $query->where('est_copreuneur', $args['est_copreuneur']);
        }
        if (isset($args['est_soumis'])) {
            $query = $query->where('est_soumis', $args['est_soumis']);
        }
        if (isset($args['codeappartement'])) {
            $query = $query->where('codeappartement', Outil::getOperateurLikeDB(), '%' . $args['codeappartement'] . '%');
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['scanpreavis'])) {
            $query = $query->where('scanpreavis', $args['scanpreavis']);
        }
        if (isset($args['descriptif'])) {
            $query = $query->where('descriptif', Outil::getOperateurLikeDB(), '%' . $args['descriptif'] . '%');
        }
        if (isset($args['documentretourcaution'])) {
            $query = $query->where('documentretourcaution', Outil::getOperateurLikeDB(), '%' . $args['documentretourcaution'] . '%');
        }
        if (isset($args['documentrecucaution'])) {
            $query = $query->where('documentrecucaution', Outil::getOperateurLikeDB(), '%' . $args['documentrecucaution'] . '%');
        }
        if (isset($args['montantloyer'])) {
            $query = $query->where('montantloyer', Outil::getOperateurLikeDB(), '%' . $args['montantloyer'] . '%');
        }
        if (isset($args['montantloyerbase'])) {
            $query = $query->where('montantloyerbase', Outil::getOperateurLikeDB(), '%' . $args['montantloyerbase'] . '%');
        }
        if (isset($args['montantloyertom'])) {
            $query = $query->where('montantloyertom', Outil::getOperateurLikeDB(), '%' . $args['montantloyertom'] . '%');
        }
        if (isset($args['montantcharge'])) {
            $query = $query->where('montantcharge', Outil::getOperateurLikeDB(), '%' . $args['montantcharge'] . '%');
        }
        if (isset($args['tauxrevision'])) {
            $query = $query->where('tauxrevision', Outil::getOperateurLikeDB(), '%' . $args['tauxrevision'] . '%');
        }
        if (isset($args['frequencerevision'])) {
            $query = $query->where('frequencerevision', Outil::getOperateurLikeDB(), '%' . $args['frequencerevision'] . '%');
        }
        if (isset($args['dateenregistrement'])) {
            $query = $query->where('dateenregistrement', Outil::getOperateurLikeDB(), '%' . $args['dateenregistrement'] . '%');
        }
        if (isset($args['daterenouvellement'])) {
            $query = $query->where('daterenouvellement', Outil::getOperateurLikeDB(), '%' . $args['daterenouvellement'] . '%');
        }
        if (isset($args['datepremierpaiement'])) {
            $query = $query->where('datepremierpaiement', Outil::getOperateurLikeDB(), '%' . $args['datepremierpaiement'] . '%');
        }
        if (isset($args['dateretourcaution'])) {
            $query = $query->where('dateretourcaution', Outil::getOperateurLikeDB(), '%' . $args['dateretourcaution'] . '%');
        }
        if (isset($args['daterenouvellementcontrat'])) {
            $query = $query->where('daterenouvellementcontrat', Outil::getOperateurLikeDB(), '%' . $args['daterenouvellementcontrat'] . '%');
        }
        if (isset($args['datedebutcontrat'])) {
            $query = $query->where('datedebutcontrat', Outil::getOperateurLikeDB(), '%' . $args['datedebutcontrat'] . '%');
        }
        if (isset($args['typecontrat'])) {
            $query = $query->where('typecontrat', Outil::getOperateurLikeDB(), '%' . $args['typecontrat'] . '%');
        }
        if (isset($args['typerenouvellement'])) {
            $query = $query->where('typerenouvellement', Outil::getOperateurLikeDB(), '%' . $args['typerenouvellement'] . '%');
        }
        if (isset($args['delaipreavi'])) {
            $query = $query->where('delaipreavi', Outil::getOperateurLikeDB(), '%' . $args['delaipreavi'] . '%');
        }
        if (isset($args['appartement'])) {
            $query = $query->where('appartement', Outil::getOperateurLikeDB(), '%' . $args['appartement'] . '%');
        }
        if (isset($args['locataire'])) {
            $query = $query->where('locataire', Outil::getOperateurLikeDB(), '%' . $args['locataire'] . '%');
        }
        if (isset($args['caution'])) {
            $query = $query->where('caution', Outil::getOperateurLikeDB(), '%' . $args['caution'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', $args['etat']);
        }
        if (isset($args['typecontrat_id'])) {
            $query = $query->where('typecontrat_id', Outil::getOperateurLikeDB(), '%' . $args['typecontrat_id'] . '%');
        }
        if (isset($args['typerenouvellement_id'])) {
            $query = $query->where('typerenouvellement_id', Outil::getOperateurLikeDB(), '%' . $args['typerenouvellement_id'] . '%');
        }
        if (isset($args['delaipreavi_id'])) {
            $query = $query->where('delaipreavi_id', Outil::getOperateurLikeDB(), '%' . $args['delaipreavi_id'] . '%');
        }

        if (isset($args['assurances'])) {
            $query = $query->where('assurances', Outil::getOperateurLikeDB(), '%' . $args['assurances'] . '%');
        }
        if (isset($args['versementloyers'])) {
            $query = $query->where('versementloyers', Outil::getOperateurLikeDB(), '%' . $args['versementloyers'] . '%');
        }
        if (isset($args['versementchargecoproprietes'])) {
            $query = $query->where('versementchargecoproprietes', Outil::getOperateurLikeDB(), '%' . $args['versementchargecoproprietes'] . '%');
        }
        if (isset($args['paiementloyers'])) {
            $query = $query->where('paiementloyers', Outil::getOperateurLikeDB(), '%' . $args['paiementloyers'] . '%');
        }
        if (isset($args['demanderesiliations'])) {
            $query = $query->where('demanderesiliations', Outil::getOperateurLikeDB(), '%' . $args['demanderesiliations'] . '%');
        }
        if (isset($args['rappelpaiement'])) {
            $query = $query->where('rappelpaiement', Outil::getOperateurLikeDB(), '%' . $args['rappelpaiement'] . '%');
        }
        if (isset($args['usersigned_id'])) {
            $query = $query->where('usersigned_id', $args['usersigned_id']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryAvenant($args)
    {
        $query = Avenant::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }



        if (isset($args['descriptif'])) {
            $query = $query->where('descriptif', Outil::getOperateurLikeDB(), '%' . $args['descriptif'] . '%');
        }

        if (isset($args['montantloyer'])) {
            $query = $query->where('montantloyer', Outil::getOperateurLikeDB(), '%' . $args['montantloyer'] . '%');
        }
        if (isset($args['montantloyerbase'])) {
            $query = $query->where('montantloyerbase', Outil::getOperateurLikeDB(), '%' . $args['montantloyerbase'] . '%');
        }
        if (isset($args['montantloyertom'])) {
            $query = $query->where('montantloyertom', Outil::getOperateurLikeDB(), '%' . $args['montantloyertom'] . '%');
        }
        if (isset($args['montantcharge'])) {
            $query = $query->where('montantcharge', Outil::getOperateurLikeDB(), '%' . $args['montantcharge'] . '%');
        }

        if (isset($args['dateenregistrement'])) {
            $query = $query->where('dateenregistrement', Outil::getOperateurLikeDB(), '%' . $args['dateenregistrement'] . '%');
        }
        if (isset($args['daterenouvellement'])) {
            $query = $query->where('daterenouvellement', Outil::getOperateurLikeDB(), '%' . $args['daterenouvellement'] . '%');
        }

        if (isset($args['typecontrat_id'])) {
            $query = $query->where('typecontrat_id',  $args['typecontrat_id']);
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id',  $args['contrat_id']);
        }

        if (isset($args['etat'])) {
            $query = $query->where('etat', $args['etat']);
        }


        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryContratLocationVente($args)
    {
        $query = Contrat::query();
        if (isset($args['id'])) {
            // $query = $query->where('id', $args['id']);
            $query = $query->where('contrats.id', $args['id']);
        }
        if (isset($args['copreneur_id'])) {
            $query = $query->where("copreneur_id", $args['copreneur_id']);
        }

        $query = $query->join("appartements", "appartements.id", "contrats.appartement_id")
            ->join("entites", "entites.id", "appartements.entite_id")
            ->where('entites.code', "RID")->select("contrats.*");

        if (isset($args['codeappartement'])) {
            $query = $query->where('codeappartement', Outil::getOperateurLikeDB(), '%' . $args['codeappartement'] . '%');
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['scanpreavis'])) {
            $query = $query->where('scanpreavis', $args['scanpreavis']);
        }
        if (isset($args['est_copreuneur'])) {
            $query = $query->where('est_copreuneur', $args['est_copreuneur']);
        }
        if (isset($args['est_soumis'])) {
            $query = $query->where('est_soumis', $args['est_soumis']);
        }

        if (isset($args['descriptif'])) {
            $query = $query->where('descriptif', Outil::getOperateurLikeDB(), '%' . $args['descriptif'] . '%');
        }
        if (isset($args['documentretourcaution'])) {
            $query = $query->where('documentretourcaution', Outil::getOperateurLikeDB(), '%' . $args['documentretourcaution'] . '%');
        }
        if (isset($args['documentrecucaution'])) {
            $query = $query->where('documentrecucaution', Outil::getOperateurLikeDB(), '%' . $args['documentrecucaution'] . '%');
        }
        if (isset($args['montantloyer'])) {
            $query = $query->where('montantloyer', Outil::getOperateurLikeDB(), '%' . $args['montantloyer'] . '%');
        }
        if (isset($args['montantloyerbase'])) {
            $query = $query->where('montantloyerbase', Outil::getOperateurLikeDB(), '%' . $args['montantloyerbase'] . '%');
        }
        if (isset($args['montantloyertom'])) {
            $query = $query->where('montantloyertom', Outil::getOperateurLikeDB(), '%' . $args['montantloyertom'] . '%');
        }
        if (isset($args['montantcharge'])) {
            $query = $query->where('montantcharge', Outil::getOperateurLikeDB(), '%' . $args['montantcharge'] . '%');
        }
        if (isset($args['tauxrevision'])) {
            $query = $query->where('tauxrevision', Outil::getOperateurLikeDB(), '%' . $args['tauxrevision'] . '%');
        }
        if (isset($args['frequencerevision'])) {
            $query = $query->where('frequencerevision', Outil::getOperateurLikeDB(), '%' . $args['frequencerevision'] . '%');
        }
        if (isset($args['dateenregistrement'])) {
            $query = $query->where('dateenregistrement', Outil::getOperateurLikeDB(), '%' . $args['dateenregistrement'] . '%');
        }
        if (isset($args['daterenouvellement'])) {
            $query = $query->where('daterenouvellement', Outil::getOperateurLikeDB(), '%' . $args['daterenouvellement'] . '%');
        }
        if (isset($args['datepremierpaiement'])) {
            $query = $query->where('datepremierpaiement', Outil::getOperateurLikeDB(), '%' . $args['datepremierpaiement'] . '%');
        }
        if (isset($args['dateretourcaution'])) {
            $query = $query->where('dateretourcaution', Outil::getOperateurLikeDB(), '%' . $args['dateretourcaution'] . '%');
        }
        if (isset($args['daterenouvellementcontrat'])) {
            $query = $query->where('daterenouvellementcontrat', Outil::getOperateurLikeDB(), '%' . $args['daterenouvellementcontrat'] . '%');
        }
        if (isset($args['datedebutcontrat'])) {
            $query = $query->where('datedebutcontrat', Outil::getOperateurLikeDB(), '%' . $args['datedebutcontrat'] . '%');
        }
        if (isset($args['typecontrat'])) {
            $query = $query->where('typecontrat', Outil::getOperateurLikeDB(), '%' . $args['typecontrat'] . '%');
        }
        if (isset($args['typerenouvellement'])) {
            $query = $query->where('typerenouvellement', Outil::getOperateurLikeDB(), '%' . $args['typerenouvellement'] . '%');
        }
        if (isset($args['delaipreavi'])) {
            $query = $query->where('delaipreavi', Outil::getOperateurLikeDB(), '%' . $args['delaipreavi'] . '%');
        }
        if (isset($args['appartement'])) {
            $query = $query->where('appartement', Outil::getOperateurLikeDB(), '%' . $args['appartement'] . '%');
        }
        if (isset($args['locataire'])) {
            $query = $query->where('locataire', Outil::getOperateurLikeDB(), '%' . $args['locataire'] . '%');
        }
        if (isset($args['caution'])) {
            $query = $query->where('caution', Outil::getOperateurLikeDB(), '%' . $args['caution'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', Outil::getOperateurLikeDB(), '%' . $args['etat'] . '%');
        }
        if (isset($args['typecontrat_id'])) {
            $query = $query->where('typecontrat_id', Outil::getOperateurLikeDB(), '%' . $args['typecontrat_id'] . '%');
        }
        if (isset($args['typerenouvellement_id'])) {
            $query = $query->where('typerenouvellement_id', Outil::getOperateurLikeDB(), '%' . $args['typerenouvellement_id'] . '%');
        }
        if (isset($args['delaipreavi_id'])) {
            $query = $query->where('delaipreavi_id', Outil::getOperateurLikeDB(), '%' . $args['delaipreavi_id'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('contrats.appartement_id', $args['appartement_id']);
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('contrats.locataire_id', $args['locataire_id']);
        }
        if (isset($args['assurances'])) {
            $query = $query->where('assurances', Outil::getOperateurLikeDB(), '%' . $args['assurances'] . '%');
        }
        if (isset($args['versementloyers'])) {
            $query = $query->where('versementloyers', Outil::getOperateurLikeDB(), '%' . $args['versementloyers'] . '%');
        }
        if (isset($args['versementchargecoproprietes'])) {
            $query = $query->where('versementchargecoproprietes', Outil::getOperateurLikeDB(), '%' . $args['versementchargecoproprietes'] . '%');
        }
        // if (isset($args['paiementloyers'])) {
        //     $query = $query->where('paiementloyers', Outil::getOperateurLikeDB(), '%' . $args['paiementloyers'] . '%');
        // }
        if (isset($args['demanderesiliations'])) {
            $query = $query->where('demanderesiliations', Outil::getOperateurLikeDB(), '%' . $args['demanderesiliations'] . '%');
        }
        if (isset($args['rappelpaiement'])) {
            $query = $query->where('rappelpaiement', Outil::getOperateurLikeDB(), '%' . $args['rappelpaiement'] . '%');
        }

        // newwes
        if (isset($args['dateremisecles'])) {
            $query = $query->where('dateremisecles', Outil::getOperateurLikeDB(), '%' . $args['dateremisecles'] . '%');
        }
        if (isset($args['apportinitial'])) {
            $query = $query->where('apportinitial', Outil::getOperateurLikeDB(), '%' . $args['apportinitial'] . '%');
        }
        if (isset($args['apportiponctuel'])) {
            $query = $query->where('apportiponctuel', Outil::getOperateurLikeDB(), '%' . $args['apportiponctuel'] . '%');
        }
        if (isset($args['dateecheance'])) {
            $query = $query->where('dateecheance', Outil::getOperateurLikeDB(), '%' . $args['dateecheance'] . '%');
        }
        if (isset($args['dureelocationvente'])) {
            $query = $query->where('dureelocationvente', Outil::getOperateurLikeDB(), '%' . $args['dureelocationvente'] . '%');
        }
        if (isset($args['clausepenale'])) {
            $query = $query->where('clausepenale',  $args['clausepenale']);
        }
        if (isset($args['fraiscoutlocationvente'])) {
            $query = $query->where('fraiscoutlocationvente', Outil::getOperateurLikeDB(), '%' . $args['fraiscoutlocationvente'] . '%');
        }
        if (isset($args['acompteinitial'])) {
            $query = $query->where('acompteinitial', Outil::getOperateurLikeDB(), '%' . $args['acompteinitial'] . '%');
        }
        if (isset($args['indemnite'])) {
            $query = $query->where('indemnite', $args['indemnite']);
        }
        if (isset($args['prixvilla'])) {
            $query = $query->where('prixvilla', $args['prixvilla']);
        }
        if (isset($args['periodicite_id'])) {
            $query = $query->where('periodicite_id', $args['periodicite_id']);
        }
        if (isset($args['depot_initial'])) {
            $query = $query->where('depot_initial', Outil::getOperateurLikeDB(), '%' . $args['depot_initial'] . '%');
        }
        if (isset($args['ilot_id'])) {
            $query =  $query->join("appartements as ap", "ap.id", "contrats.appartement_id")
                ->join("ilots", "ilots.id", "ap.ilot_id")
                ->where('ilots.id', $args['ilot_id'])->select("contrats.*");
            // dd("args " , $args['ilot']);
        }
        if (isset($args['lot'])) {
            $query =  $query->join("appartements as apl", "apl.id", "contrats.appartement_id")
                ->where('apl.lot', $args['lot'])->select("contrats.*");
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryFactureintervention($args)
    {
        $query = Factureintervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['paiementintervention_id'])) {
            $query = $query->where('paiementintervention_id', $args['paiementintervention_id']);
        }
        if (isset($args['etatlieu_id'])) {
            $query = $query->where('etatlieu_id', $args['etatlieu_id']);
        }
        if (isset($args['datefacture'])) {
            $query = $query->where('datefacture', Outil::getOperateurLikeDB(), '%' . $args['datefacture'] . '%');
        }
        if (isset($args['datedeb']) && isset($args['datefin'])) {
            $query = $query->whereBetween('datefacture', [$args['datedeb'], $args['datefin']]);
        }

        //proprietaire_id dans appartement

        if (isset($args['proprietaire_id'])) {
            $query = $query->whereHas('appartement', function ($query) use ($args) {
                $query->wherehas('proprietaire', function ($query) use ($args) {
                    $query->where('proprietaire_id', $args['proprietaire_id']);
                });
            });
        }



        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['intervenantassocie'])) {
            $query = $query->where('intervenantassocie', Outil::getOperateurLikeDB(), '%' . $args['intervenantassocie'] . '%');
        }
        if (isset($args['intervention_id'])) {
            $query = $query->where('intervention_id', Outil::getOperateurLikeDB(), '%' . $args['intervention_id'] . '%');
        }
        if (isset($args['typefacture_id'])) {
            $query = $query->where('typefacture_id', Outil::getOperateurLikeDB(), '%' . $args['typefacture_id'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', Outil::getOperateurLikeDB(), '%' . $args['locataire_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryDetailfactureintervention($args)
    {
        $query = Detailfactureintervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['montant'])) {
            $query = $query->where('montant',   $args['montant']);
        }

        if (isset($args['intervention_id'])) {
            $query = $query->where('intervention_id', Outil::getOperateurLikeDB(), '%' . $args['intervention_id'] . '%');
        }
        if (isset($args['factureintervention_id'])) {
            $query = $query->where('factureintervention_id', Outil::getOperateurLikeDB(), '%' . $args['factureintervention_id'] . '%');
        }


        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryFacture($args)
    {
        $query = Facture::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['datefacture'])) {
            $query = $query->where('datefacture', Outil::getOperateurLikeDB(), '%' . $args['datefacture'] . '%');
        }
        if (isset($args['moisfacture'])) {
            $query = $query->where('moisfacture', Outil::getOperateurLikeDB(), '%' . $args['moisfacture'] . '%');
        }
        if (isset($args['documentfacture'])) {
            $query = $query->where('documentfacture', $args['documentfacture']);
        }
        if (isset($args['recupaiement'])) {
            $query = $query->where('recupaiement', Outil::getOperateurLikeDB(), '%' . $args['recupaiement'] . '%');
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['intervenantassocie'])) {
            $query = $query->where('intervenantassocie', Outil::getOperateurLikeDB(), '%' . $args['intervenantassocie'] . '%');
        }
        if (isset($args['periode'])) {
            $query = $query->where('periode', Outil::getOperateurLikeDB(), '%' . $args['periode'] . '%');
        }
        if (isset($args['partiecommune'])) {
            $query = $query->where('partiecommune', Outil::getOperateurLikeDB(), '%' . $args['partiecommune'] . '%');
        }
        if (isset($args['intervention_id'])) {
            $query = $query->where('intervention_id', Outil::getOperateurLikeDB(), '%' . $args['intervention_id'] . '%');
        }
        if (isset($args['typefacture_id'])) {
            $query = $query->where('typefacture_id', Outil::getOperateurLikeDB(), '%' . $args['typefacture_id'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryFactureacompte($args)
    {
        $query = Factureacompte::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['date'])) {
            $query = $query->whereDate('date',  $args['date']);
        }
        if (isset($args['date_echeance'])) {
            $query = $query->whereDate('date_echeance',  $args['date_echeance']);
        }

        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['intervenantassocie'])) {
            $query = $query->where('intervenantassocie', Outil::getOperateurLikeDB(), '%' . $args['intervenantassocie'] . '%');
        }

        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', $args['contrat_id']);
        }
        if (isset($args['est_activer'])) {
            $query = $query->where('est_activer', $args['est_activer']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryDocument($args)
    {
        $query = Document::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['chemin'])) {
            $query = $query->where('chemin', Outil::getOperateurLikeDB(), '%' . $args['chemin'] . '%');
        }
        if (isset($args['typedocument_id'])) {
            $query = $query->where('typedocument_id', Outil::getOperateurLikeDB(), '%' . $args['typedocument_id'] . '%');
        }
        if (isset($args['message_id'])) {
            $query = $query->where('message_id', $args['message_id']);
        }
        if (isset($args['annonce_id'])) {
            $query = $query->where('annonce_id', Outil::getOperateurLikeDB(), '%' . $args['annonce_id'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryEntite($args)
    {
        $query = Entite::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }

        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }

        if (isset($args['code'])) {
            $query = $query->where('code', Outil::getOperateurLikeDB(), '%' . $args['code'] . '%');
        }

        if (isset($args['gestionnaire_id'])) {
            $query = $query->where('gestionnaire_id', $args['gestionnaire_id']);
        }







        if (isset($args['nomcompletnotaire'])) {
            $query = $query->where('nomcompletnotaire',  $args['nomcompletnotaire']);
        }
        if (isset($args['emailnotaire'])) {
            $query = $query->where('emailnotaire',  $args['emailnotaire']);
        }
        if (isset($args['telephone1notaire'])) {
            $query = $query->where('telephone1notaire',  $args['telephone1notaire']);
        }
        if (isset($args['nometudenotaire'])) {
            $query = $query->where('nometudenotaire',  $args['nometudenotaire']);
        }
        if (isset($args['emailetudenotaire'])) {
            $query = $query->where('emailetudenotaire',  $args['emailetudenotaire']);
        }
        if (isset($args['telephoneetudenotaire'])) {
            $query = $query->where('telephoneetudenotaire',  $args['telephoneetudenotaire']);
        }
        if (isset($args['assistantetudenotaire'])) {
            $query = $query->where('assistantetudenotaire',  $args['assistantetudenotaire']);
        }
        if (isset($args['activite_id'])) {
            $query = $query->where('activite_id',  $args['activite_id']);
        }




        $query = $query->orderBy('id');

        return $query;
    }

    public static function getQueryIlot($args)
    {
        $query = Ilot::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['numero'])) {
            $query = $query->where('numero', $args['numero']);
        }

        if (isset($args['adresse'])) {
            $query = $query->where('adresse', Outil::getOperateurLikeDB(), '%' . $args['adresse'] . '%');
        }

        if (isset($args['numerotitrefoncier'])) {
            $query = $query->where('numerotitrefoncier', $args['numerotitrefoncier']);
        }
        if (isset($args['datetitrefoncier'])) {
            $query = $query->where('datetitrefoncier', $args['datetitrefoncier']);
        }
        if (isset($args['adressetitrefoncier'])) {
            $query = $query->where('adressetitrefoncier', $args['adressetitrefoncier']);
        }


        $query = $query->orderBy('id');

        return $query;
    }

    public static function getQueryPeriodicite($args)
    {
        $query = Periodicite::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['nbr_mois'])) {
            $query = $query->where('nbr_mois', $args['nbr_mois']);
        }

        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }

        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }

        $query = $query->orderBy('id');

        return $query;
    }

    public static function getQueryDelaipreavi($args)
    {
        $query = Delaipreavi::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['contrats'])) {
            $query = $query->where('contrats', Outil::getOperateurLikeDB(), '%' . $args['contrats'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryDemandeintervention($args)
    {
        $query = Demandeintervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['image'])) {
            $query = $query->where('image', Outil::getOperateurLikeDB(), '%' . $args['image'] . '%');
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', Outil::getOperateurLikeDB(), '%' . $args['locataire_id'] . '%');
        }
        if (isset($args['typepiece'])) {
            $query = $query->where('typepiece', Outil::getOperateurLikeDB(), '%' . $args['typepiece'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        if (isset($args['membreequipegestion_id'])) {
            $query = $query->where('membreequipegestion_id', Outil::getOperateurLikeDB(), '%' . $args['membreequipegestion_id'] . '%');
        }
        if (isset($args['immeublie_id'])) {
            $query = $query->where('immeublie_id', Outil::getOperateurLikeDB(), '%' . $args['immeublie_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryEquipegestion($args)
    {
        $query = Equipegestion::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['immeubles'])) {
            $query = $query->where('immeubles', Outil::getOperateurLikeDB(), '%' . $args['immeubles'] . '%');
        }
        if (isset($args['fonction'])) {
            $query = $query->where('fonction', Outil::getOperateurLikeDB(), '%' . $args['fonction'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryEquimentpiece($args)
    {
        $query = Equipementpiece::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['commentaire'])) {
            $query = $query->where('commentaire', Outil::getOperateurLikeDB(), '%' . $args['commentaire'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', Outil::getOperateurLikeDB(), '%' . $args['etat'] . '%');
        }
        if (isset($args['generale'])) {
            $query = $query->where('generale', Outil::getOperateurLikeDB(), '%' . $args['generale'] . '%');
        }
        if (isset($args['etatlieu_id'])) {
            $query = $query->where('etatlieu_id', Outil::getOperateurLikeDB(), '%' . $args['etatlieu_id'] . '%');
        }
        if (isset($args['observation_id'])) {
            $query = $query->where('observation_id', Outil::getOperateurLikeDB(), '%' . $args['observation_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryEtatappartement($args)
    {
        $query = Etatappartement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['appartements'])) {
            $query = $query->where('appartements', Outil::getOperateurLikeDB(), '%' . $args['appartements'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryMembreequipegestion($args)
    {
        $query = Membreequipegestion::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['prenom'])) {
            $query = $query->where('prenom', Outil::getOperateurLikeDB(), '%' . $args['prenom'] . '%');
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['email'])) {
            $query = $query->where('email', Outil::getOperateurLikeDB(), '%' . $args['email'] . '%');
        }
        if (isset($args['telephone'])) {
            $query = $query->where('telephone', Outil::getOperateurLikeDB(), '%' . $args['telephone'] . '%');
        }
        if (isset($args['equipegestions'])) {
            $query = $query->where('equipegestions', Outil::getOperateurLikeDB(), '%' . $args['equipegestions'] . '%');
        }
        if (isset($args['fonctions'])) {
            $query = $query->where('fonctions', Outil::getOperateurLikeDB(), '%' . $args['fonctions'] . '%');
        }
        if (isset($args['interventions'])) {
            $query = $query->where('interventions', Outil::getOperateurLikeDB(), '%' . $args['interventions'] . '%');
        }
        if (isset($args['demandeinterventions'])) {
            $query = $query->where('demandeinterventions', Outil::getOperateurLikeDB(), '%' . $args['demandeinterventions'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryMessage($args)
    {
        $query = Message::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['objet'])) {
            $query = $query->where('objet', Outil::getOperateurLikeDB(), '%' . $args['objet'] . '%');
        }
        if (isset($args['contenu'])) {
            $query = $query->where('contenu', Outil::getOperateurLikeDB(), '%' . $args['contenu'] . '%');
        }
        if (isset($args['typedocument_id'])) {
            $query = $query->where('typedocument_id', Outil::getOperateurLikeDB(), '%' . $args['typedocument_id'] . '%');
        }
        if (isset($args['locataires'])) {
            $query = $query->where('locataires', Outil::getOperateurLikeDB(), '%' . $args['locataires'] . '%');
        }
        if (isset($args['proprietaires'])) {
            $query = $query->where('proprietaires', Outil::getOperateurLikeDB(), '%' . $args['proprietaires'] . '%');
        }
        if (isset($args['documents'])) {
            $query = $query->where('documents', Outil::getOperateurLikeDB(), '%' . $args['documents'] . '%');
        }
        if (isset($args['locataire_id'])) {
            $query = $query->whereIn('id', Locataire_message::where('locataire_id',$args['locataire_id'])
            ->pluck('message_id'));
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryPrestataire($args)
    {
        $query = Prestataire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['adresse'])) {
            $query = $query->where('adresse', Outil::getOperateurLikeDB(), '%' . $args['adresse'] . '%');
        }
        if (isset($args['telephone1'])) {
            $query = $query->where('telephone1', Outil::getOperateurLikeDB(), '%' . $args['telephone1'] . '%');
        }
        if (isset($args['telephone2'])) {
            $query = $query->where('telephone2', Outil::getOperateurLikeDB(), '%' . $args['telephone2'] . '%');
        }
        if (isset($args['interventions'])) {
            $query = $query->where('interventions', Outil::getOperateurLikeDB(), '%' . $args['interventions'] . '%');
        }
        if (isset($args['contacts'])) {
            $query = $query->where('contacts', Outil::getOperateurLikeDB(), '%' . $args['contacts'] . '%');
        }
        if (isset($args['contratprestations'])) {
            $query = $query->where('contratprestations', Outil::getOperateurLikeDB(), '%' . $args['contratprestations'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryPaiementloyer($args)
    {
        $query = Paiementloyer::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['datepaiement'])) {
            $query = $query->where('datepaiement', Outil::getOperateurLikeDB(), '%' . $args['datepaiement'] . '%');
        }
        if (isset($args['codefacture'])) {
            $query = $query->where('codefacture', Outil::getOperateurLikeDB(), '%' . $args['codefacture'] . '%');
        }
        if (isset($args['montantfacture'])) {
            $query = $query->where('montantfacture', Outil::getOperateurLikeDB(), '%' . $args['montantfacture'] . '%');
        }
        if (isset($args['debutperiodevalide'])) {
            $query = $query->where('debutperiodevalide', Outil::getOperateurLikeDB(), '%' . $args['debutperiodevalide'] . '%');
        }
        if (isset($args['finperiodevalide'])) {
            $query = $query->where('finperiodevalide', Outil::getOperateurLikeDB(), '%' . $args['finperiodevalide'] . '%');
        }

        if (isset($args['factureeaux_id'])) {
            $query = $query->where('factureeaux_id', $args['factureeaux_id']);
        }

        if (isset($args['periode'])) {
            $query = $query->where('periode', Outil::getOperateurLikeDB(), '%' . $args['periode'] . '%');
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id',  $args['contrat_id']);
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', Outil::getOperateurLikeDB(), '%' . $args['locataire_id'] . '%');
        }
        //
        if (isset($args['facturelocation_id'])) {
            $query = $query->where('facturelocation_id', $args['facturelocation_id']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryPaiementecheance($args)
    {
        $query = Paiementecheance::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['date'])) {
            $query = $query->whereDate('date', $args['date']);
        }
        if (isset($args['numero'])) {
            $query = $query->where('numero', $args['codefacture']);
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['montantenattente'])) {
            $query = $query->where('montantenattente', Outil::getOperateurLikeDB(), '%' . $args['montantenattente'] . '%');
        }

        if (isset($args['periodes'])) {
            $query = $query->where('periodes',  $args['periodes']);
        }

        // if(isset($args['lot'])){
        //     //
        // }

        if (isset($args['locataire_id'])) {

            $locataire_id = $args['locataire_id'];

            $aviesecheance = Avisecheance::whereHas('contrat', function ($query) use ($locataire_id) {
                $query->where('locataire_id', $locataire_id);
            })->get();

            $query = $query->whereIn('avisecheance_id', $aviesecheance->pluck('id'));
        }


        if (isset($args['avisecheance_id'])) {
            $query = $query->where('avisecheance_id', $args['avisecheance_id']);
        }

        if (isset($args['paiementecheance_id'])) {
            $query = $query->where('paiementecheance_id', $args['paiementecheance_id']);
        }

        if (isset($args['modepaiement_id'])) {
            $query = $query->where('modepaiement_id', $args['modepaiement_id']);
        }

        if (isset($args['etat'])) {
            $query = $query->where('etat', $args['etat']);
        }

        if (isset($args['montantencaisse'])) {
            $query = $query->where('montantencaisse', Outil::getOperateurLikeDB(), '%' . $args['montantencaisse'] . '%');
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryPaiementintervention($args)
    {
        $query = Paiementintervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['factureintervention_id'])) {
            $query = $query->where('factureintervention_id', $args['factureintervention_id']);
        }
        if (isset($args['modepaiement_id'])) {
            $query = $query->where('modepaiement_id', $args['modepaiement_id']);
        }
        if (isset($args['cheque'])) {
            $query = $query->where('cheque', $args['cheque']);
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryVersementloyer($args)
    {
        $query = Versementloyer::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['dateversement'])) {
            $query = $query->where('dateversement', Outil::getOperateurLikeDB(), '%' . $args['dateversement'] . '%');
        }
        if (isset($args['datedebut'])) {
            $query = $query->where('debut', Outil::getOperateurLikeDB(), '%' . $args['datedebut'] . '%');
        }
        if (isset($args['datefin'])) {
            $query = $query->where('fin', Outil::getOperateurLikeDB(), '%' . $args['datefin'] . '%');
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['proprietaire_id'])) {
            $query = $query->where('proprietaire_id', Outil::getOperateurLikeDB(), '%' . $args['proprietaire_id'] . '%');
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', Outil::getOperateurLikeDB(), '%' . $args['contrat_id'] . '%');
        }
        if (isset($args['contrat'])) {
            $query = $query->where('contrat', Outil::getOperateurLikeDB(), '%' . $args['contrat'] . '%');
        }
        if (isset($args['proprietaire'])) {
            $query = $query->where('proprietaire', Outil::getOperateurLikeDB(), '%' . $args['proprietaire'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryVersementchargecopropriete($args)
    {
        $query = Versementchargecopropriete::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['dateversement'])) {
            $query = $query->where('dateversement', Outil::getOperateurLikeDB(), '%' . $args['dateversement'] . '%');
        }
        if (isset($args['anneecouverte'])) {
            $query = $query->where('anneecouverte', Outil::getOperateurLikeDB(), '%' . $args['anneecouverte'] . '%');
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['proprietaire_id'])) {
            $query = $query->where('proprietaire_id', Outil::getOperateurLikeDB(), '%' . $args['proprietaire_id'] . '%');
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', Outil::getOperateurLikeDB(), '%' . $args['contrat_id'] . '%');
        }
        if (isset($args['contrat'])) {
            $query = $query->where('contrat', Outil::getOperateurLikeDB(), '%' . $args['contrat'] . '%');
        }
        if (isset($args['proprietaire'])) {
            $query = $query->where('proprietaire', Outil::getOperateurLikeDB(), '%' . $args['proprietaire'] . '%');
        }
        if (isset($args['contrat'])) {
            $query = $query->where('contrat', Outil::getOperateurLikeDB(), '%' . $args['contrat'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryObligationadministrative($args)
    {
        $query = Obligationadministrative::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['debut'])) {
            $query = $query->where('debut', Outil::getOperateurLikeDB(), '%' . $args['debut'] . '%');
        }
        if (isset($args['fin'])) {
            $query = $query->where('fin', Outil::getOperateurLikeDB(), '%' . $args['fin'] . '%');
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['typeobligationadministrative_id'])) {
            $query = $query->where('typeobligationadministrative_id', Outil::getOperateurLikeDB(), '%' . $args['typeobligationadministrative_id'] . '%');
        }
        if (isset($args['immeuble_id'])) {
            $query = $query->where('immeuble_id', Outil::getOperateurLikeDB(), '%' . $args['immeuble_id'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryEtatassurance($args)
    {
        $query = Etatassurance::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['assurances'])) {
            $query = $query->where('assurances', Outil::getOperateurLikeDB(), '%' . $args['assurances'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryTypeappartement($args)
    {
        $query = Typeappartement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['appartements'])) {
            $query = $query->where('appartements', Outil::getOperateurLikeDB(), '%' . $args['appartements'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryTypecontrat($args)
    {
        $query = Typecontrat::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['contrats'])) {
            $query = $query->where('contrats', Outil::getOperateurLikeDB(), '%' . $args['contrats'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryModePaiement($args)
    {
        $query = Modepaiement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }
        if (isset($args['code'])) {
            $query = $query->where('code', $args['code']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryDetailPaiement($args)
    {
        $query = Detailpaiement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', $args['montant']);
        }

        if (isset($args['periode_id'])) {
            $query = $query->where('periode_id', $args['periode_id']);
        }

        if (isset($args['paiementloyer_id'])) {
            $query = $query->where('paiementloyer_id', $args['paiementloyer_id']);
        }


        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryParametrage($args, $classe)
    {
        $query = $classe::query(); // Utilise la classe passée en paramètre pour créer la requête.

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }

        $query = $query->orderBy('id', 'ASC');
        return $query;
    }


    public static function getQueryTypeassurance($args)
    {
        $query = Typeassurance::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['assurances'])) {
            $query = $query->where('assurances', Outil::getOperateurLikeDB(), '%' . $args['assurances'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryTypedocument($args)
    {
        $query = Typedocument::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['documents'])) {
            $query = $query->where('documents', Outil::getOperateurLikeDB(), '%' . $args['documents'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryTypefacture($args)
    {
        $query = Typefacture::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['factures'])) {
            $query = $query->where('factures', Outil::getOperateurLikeDB(), '%' . $args['factures'] . '%');
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryTypeintervention($args)
    {
        $query = Typeintervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['code'])) {
            $query = $query->where('code', $args['code']);
        }
        if (isset($args['interventions'])) {
            $query = $query->where('interventions', Outil::getOperateurLikeDB(), '%' . $args['interventions'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryTypelocataire($args)
    {
        $query = Typelocataire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['locataires'])) {
            $query = $query->where('locataires', Outil::getOperateurLikeDB(), '%' . $args['locataires'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryTypeobligationadministrative($args)
    {
        $query = Typeobligationadministrative::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['obligationadministratives'])) {
            $query = $query->where('obligationadministratives', Outil::getOperateurLikeDB(), '%' . $args['obligationadministratives'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryTypepiece($args)
    {
        $query = Typepiece::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['immeubles'])) {
            $query = $query->where('immeubles', Outil::getOperateurLikeDB(), '%' . $args['immeubles'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryTypepieceniveauappartement($args)
    {
        // $query = Typepiece::query();
        $query = DB::table('typepiece_niveauappartement')->query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['niveauappartement_id'])) {
            $query = $query->where('niveauappartement_id', $args['niveauappartement_id']);
        }
        if (isset($args['typepiece_id'])) {
            $query = $query->where('typepiece_id', $args['typepiece_id']);
        }

        $query = $query->orderBy('id');
        return $query;
    }
    public static function getQueryEntiteuser($args)
    {
        $query = DB::table('entite_user');
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['entite_id'])) {
            $query = $query->where('entite_id', $args['entite_id']);
        }
        if (isset($args['user_id'])) {
            $query = $query->where('user_id', $args['user_id']);
        }
        $query->join("users", "users.id", "entite_user.user_id");
        $query = $query->orderBy('entite_user.id');
        return $query;
    }
    public static function getQueryTyperenouvellement($args)
    {
        $query = Typerenouvellement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['contrats'])) {
            $query = $query->where('contrats', Outil::getOperateurLikeDB(), '%' . $args['contrats'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }
    public static function getQueryTypequestionnaire($args)
    {
        $query = Typequestionnaire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['questionnaires'])) {
            $query = $query->where('questionnaires', Outil::getOperateurLikeDB(), '%' . $args['questionnaires'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryProduitsutilise($args)
    {
        $query = Produitsutilise::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['rapportinterventions'])) {
            $query = $query->where('rapportinterventions', Outil::getOperateurLikeDB(), '%' . $args['rapportinterventions'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryProprietaire($args)
    {
        $query = Proprietaire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        // proprietaire_id
        if (isset($args['proprietaire_id'])) {
            $query = $query->where('id', $args['proprietaire_id']);
        }

        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['immeubles'])) {
            $query = $query->where('immeubles', Outil::getOperateurLikeDB(), '%' . $args['immeubles'] . '%');
        }
        if (isset($args['appartements'])) {
            $query = $query->where('appartements', Outil::getOperateurLikeDB(), '%' . $args['appartements'] . '%');
        }
        if (isset($args['versementloyers'])) {
            $query = $query->where('versementloyers', Outil::getOperateurLikeDB(), '%' . $args['versementloyers'] . '%');
        }
        if (isset($args['versementchargecoproprietes'])) {
            $query = $query->where('versementchargecoproprietes', Outil::getOperateurLikeDB(), '%' . $args['versementchargecoproprietes'] . '%');
        }
        if (isset($args['messages'])) {
            $query = $query->where('messages', Outil::getOperateurLikeDB(), '%' . $args['messages'] . '%');
        }
        if (isset($args['questionnairesatisfaction'])) {
            $query = $query->where('questionnairesatisfaction', Outil::getOperateurLikeDB(), '%' . $args['questionnairesatisfaction'] . '%');
        }
        if (isset($args['search'])) {
            // $query = $query->where('nomcomplet', Outil::getOperateurLikeDB(), '%' . $args['search'] . '%')
            // ->orWhere(DB::raw("CONCAT(prenom, ' ', nom)"), Outil::getOperateurLikeDB(),'%' . $args['search'] . '%');
            $query = $query->Where(DB::raw("CONCAT(prenom, ' ', nom)"), Outil::getOperateurLikeDB(),'%' . $args['search'] . '%');

        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryObservation($args)
    {
        $query = Observation::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['equipementpieces'])) {
            $query = $query->where('equipementpieces', Outil::getOperateurLikeDB(), '%' . $args['equipementpieces'] . '%');
        }
        if (isset($args['constituantpieces'])) {
            $query = $query->where('constituantpieces', Outil::getOperateurLikeDB(), '%' . $args['constituantpieces'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryQuestionnaire($args)
    {
        $query = Questionnaire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['nombre'])) {
            $query = $query->where('nombre', Outil::getOperateurLikeDB(), '%' . $args['nombre'] . '%');
        }
        if (isset($args['receptionniste'])) {
            $query = $query->where('receptionniste', Outil::getOperateurLikeDB(), '%' . $args['receptionniste'] . '%');
        }
        if (isset($args['jardin'])) {
            $query = $query->where('jardin', Outil::getOperateurLikeDB(), '%' . $args['jardin'] . '%');
        }
        if (isset($args['parkingsousterrain'])) {
            $query = $query->where('parkingsousterrain', Outil::getOperateurLikeDB(), '%' . $args['parkingsousterrain'] . '%');
        }
        if (isset($args['parkingexterne'])) {
            $query = $query->where('parkingexterne', Outil::getOperateurLikeDB(), '%' . $args['parkingexterne'] . '%');
        }
        if (isset($args['entrepot'])) {
            $query = $query->where('entrepot', Outil::getOperateurLikeDB(), '%' . $args['entrepot'] . '%');
        }
        if (isset($args['syndic'])) {
            $query = $query->where('syndic', Outil::getOperateurLikeDB(), '%' . $args['syndic'] . '%');
        }
        if (isset($args['typequestionnaire'])) {
            $query = $query->where('typequestionnaire', Outil::getOperateurLikeDB(), '%' . $args['typequestionnaire'] . '%');
        }
        if (isset($args['immeuble'])) {
            $query = $query->where('immeuble', Outil::getOperateurLikeDB(), '%' . $args['immeuble'] . '%');
        }
        if (isset($args['appartement'])) {
            $query = $query->where('appartement', Outil::getOperateurLikeDB(), '%' . $args['appartement'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryFonction($args)
    {
        $query = Fonction::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['membreequipegestions'])) {
            $query = $query->where('membreequipegestions', Outil::getOperateurLikeDB(), '%' . $args['membreequipegestions'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryPieceappartement($args)
    {
        $query = Pieceappartement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['appartement'])) {
            $query = $query->where('appartement', Outil::getOperateurLikeDB(), '%' . $args['appartement'] . '%');
        }
        if (isset($args['immeuble'])) {
            $query = $query->where('immeuble', Outil::getOperateurLikeDB(), '%' . $args['immeuble'] . '%');
        }
        if (isset($args['typepiece'])) {
            $query = $query->where('typepiece', Outil::getOperateurLikeDB(), '%' . $args['typepiece'] . '%');
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        if (isset($args['immeuble_id'])) {
            $query = $query->where('immeuble_id', Outil::getOperateurLikeDB(), '%' . $args['immeuble_id'] . '%');
        }
        if (isset($args['typepiece_id'])) {
            $query = $query->where('typepiece_id', Outil::getOperateurLikeDB(), '%' . $args['typepiece_id'] . '%');
        }
        if (isset($args['etatlieus'])) {
            $query = $query->where('etatlieus', Outil::getOperateurLikeDB(), '%' . $args['etatlieus'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryPieceimmeuble($args)
    {
        $query = Pieceimmeuble::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['immeuble'])) {
            $query = $query->where('immeuble', Outil::getOperateurLikeDB(), '%' . $args['immeuble'] . '%');
        }
        if (isset($args['typepiece'])) {
            $query = $query->where('typepiece', Outil::getOperateurLikeDB(), '%' . $args['typepiece'] . '%');
        }
        if (isset($args['immeuble_id'])) {
            $query = $query->where('immeuble_id', Outil::getOperateurLikeDB(), '%' . $args['immeuble_id'] . '%');
        }
        if (isset($args['typepiece_id'])) {
            $query = $query->where('typepiece_id', Outil::getOperateurLikeDB(), '%' . $args['typepiece_id'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryFrequencepaiementappartement($args)
    {
        $query = Frequencepaiementappartement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['appartements'])) {
            $query = $query->where('appartements', Outil::getOperateurLikeDB(), '%' . $args['appartements'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryEtatlieu($args)
    {
        $query = Etatlieu::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['dateredaction'])) {
            $query = $query->where('dateredaction', Outil::getOperateurLikeDB(), '%' . $args['dateredaction'] . '%');
        }
        if (isset($args['particularite'])) {
            $query = $query->where('particularite', Outil::getOperateurLikeDB(), '%' . $args['particularite'] . '%');
        }
        if (isset($args['etatgenerale'])) {
            $query = $query->where('etatgenerale', Outil::getOperateurLikeDB(), '%' . $args['etatgenerale'] . '%');
        }
        if (isset($args['pieceappartement_id'])) {
            $query = $query->where('pieceappartement_id', Outil::getOperateurLikeDB(), '%' . $args['pieceappartement_id'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', $args['appartement_id']);
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', $args['locataire_id']);
        }
        if (isset($args['constituantpieces'])) {
            $query = $query->where('constituantpieces', Outil::getOperateurLikeDB(), '%' . $args['constituantpieces'] . '%');
        }
        if (isset($args['equipementpieces'])) {
            $query = $query->where('equipementpieces', Outil::getOperateurLikeDB(), '%' . $args['equipementpieces'] . '%');
        }
        if (isset($args['type'])) {
            $query = $query->where('type',  $args['type']);
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryQuestionnairesatisfaction($args)
    {
        $query = Questionnairesatisfaction::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['titre'])) {
            $query = $query->where('titre', Outil::getOperateurLikeDB(), '%' . $args['titre'] . '%');
        }
        if (isset($args['contenu'])) {
            $query = $query->where('contenu', Outil::getOperateurLikeDB(), '%' . $args['contenu'] . '%');
        }
        if (isset($args['intervention_id'])) {
            $query = $query->where('intervention_id', Outil::getOperateurLikeDB(), '%' . $args['intervention_id'] . '%');
        }
        if (isset($args['locataires'])) {
            $query = $query->where('locataires', Outil::getOperateurLikeDB(), '%' . $args['locataires'] . '%');
        }
        if (isset($args['proprietaires'])) {
            $query = $query->where('proprietaires', Outil::getOperateurLikeDB(), '%' . $args['proprietaires'] . '%');
        }
        if (isset($args['reponsequestionnaires'])) {
            $query = $query->where('reponsequestionnaires', Outil::getOperateurLikeDB(), '%' . $args['reponsequestionnaires'] . '%');
        }
        if (isset($args['documents'])) {
            $query = $query->where('documents', Outil::getOperateurLikeDB(), '%' . $args['documents'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryReponsequestionnaire($args)
    {
        $query = Reponsequestionnaire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['contenu'])) {
            $query = $query->where('contenu', Outil::getOperateurLikeDB(), '%' . $args['contenu'] . '%');
        }
        if (isset($args['questionnairesatisfaction'])) {
            $query = $query->where('questionnairesatisfaction', Outil::getOperateurLikeDB(), '%' . $args['questionnairesatisfaction'] . '%');
        }
        if (isset($args['locataire'])) {
            $query = $query->where('locataire', Outil::getOperateurLikeDB(), '%' . $args['locataire'] . '%');
        }
        if (isset($args['proprietaire'])) {
            $query = $query->where('proprietaire', Outil::getOperateurLikeDB(), '%' . $args['proprietaire'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryRapportintervention($args)
    {
        $query = Rapportintervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['prenom'])) {
            $query = $query->where('prenom', Outil::getOperateurLikeDB(), '%' . $args['prenom'] . '%');
        }
        if (isset($args['compagnietechnicien'])) {
            $query = $query->where('compagnietechnicien', Outil::getOperateurLikeDB(), '%' . $args['compagnietechnicien'] . '%');
        }
        if (isset($args['debut'])) {
            $query = $query->where('debut', Outil::getOperateurLikeDB(), '%' . $args['debut'] . '%');
        }
        if (isset($args['fin'])) {
            $query = $query->where('fin', Outil::getOperateurLikeDB(), '%' . $args['fin'] . '%');
        }
        if (isset($args['duree'])) {
            $query = $query->where('duree', Outil::getOperateurLikeDB(), '%' . $args['duree'] . '%');
        }
        if (isset($args['observations'])) {
            $query = $query->where('observations', Outil::getOperateurLikeDB(), '%' . $args['observations'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', Outil::getOperateurLikeDB(), '%' . $args['etat'] . '%');
        }
        if (isset($args['recommandations'])) {
            $query = $query->where('recommandations', Outil::getOperateurLikeDB(), '%' . $args['recommandations'] . '%');
        }
        if (isset($args['immeuble_id'])) {
            $query = $query->where('immeuble_id', Outil::getOperateurLikeDB(), '%' . $args['immeuble_id'] . '%');
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', Outil::getOperateurLikeDB(), '%' . $args['appartement_id'] . '%');
        }
        if (isset($args['produitsutilises'])) {
            $query = $query->where('produitsutilises', Outil::getOperateurLikeDB(), '%' . $args['produitsutilises'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryIntervention($args)
    {
        $query = Intervention::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['partiecommune'])) {
            $query = $query->where('partiecommune', Outil::getOperateurLikeDB(), '%' . $args['partiecommune'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', Outil::getOperateurLikeDB(), '%' . $args['etat'] . '%');
        }
        if (isset($args['categorieintervention_id'])) {
            $query = $query->where('categorieintervention_id', Outil::getOperateurLikeDB(), '%' . $args['categorieintervention_id'] . '%');
        }
        if (isset($args['typeintervention_id'])) {
            $query = $query->where('typeintervention_id', Outil::getOperateurLikeDB(), '%' . $args['typeintervention_id'] . '%');
        }
        if (isset($args['demandeintervention_id'])) {
            $query = $query->where('demandeintervention_id', Outil::getOperateurLikeDB(), '%' . $args['demandeintervention_id'] . '%');
        }
        if (isset($args['etatlieu_id'])) {
            $query = $query->where('etatlieu_id', Outil::getOperateurLikeDB(), '%' . $args['etatlieu_id'] . '%');
        }
        if (isset($args['prestataire_id'])) {
            $query = $query->where('prestataire_id', Outil::getOperateurLikeDB(), '%' . $args['prestataire_id'] . '%');
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', Outil::getOperateurLikeDB(), '%' . $args['locataire_id'] . '%');
        }
        if (isset($args['facture_id'])) {
            $query = $query->where('facture_id', Outil::getOperateurLikeDB(), '%' . $args['facture_id'] . '%');
        }
        if (isset($args['membreequipegestions'])) {
            $query = $query->where('membreequipegestions', Outil::getOperateurLikeDB(), '%' . $args['membreequipegestions'] . '%');
        }
        if (isset($args['questionnairesatisfactions'])) {
            $query = $query->where('questionnairesatisfactions', Outil::getOperateurLikeDB(), '%' . $args['questionnairesatisfactions'] . '%');
        }
        if (isset($args['dateintervention'])) {
            $query = $query->where('dateintervention', Outil::getOperateurLikeDB(), '%' . $args['dateintervention'] . '%');
        }
        if (isset($args['getLocataire'])) {
            $query = $query->whereIn('demandeintervention_id', Demandeintervention::where('locataire_id', $args['getLocataire'])->pluck('id'));
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryCompteclient($args){
        $query = Compteclient::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', Outil::getOperateurLikeDB(), '%' . $args['locataire_id'] . '%');
        }
        // date

        if (isset($args['date'])) {
            $query = $query->where('date', Outil::getOperateurLikeDB(), '%' . $args['date'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', $args['etat']);
        }
        if (isset($args['paiementecheance_id'])) {
            $query = $query->where('paiementecheance_id', $args['paiementecheance_id']);
        }
        if (isset($args['user_id'])) {
            $query = $query->where('user_id', $args['user_id']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryInbox($args)
    {
        $query = Inbox::query();
        $user = Auth::user();
        $entite = $user->entite;

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        } else {
            if (isset($entite) && isset($entite->id)) {
                $query = $query->join('appartements', 'appartements.id', 'inboxs.appartement_id')
                    ->where('appartements.entite_id', $entite->id);
            }
        }
        if (isset($args['subject'])) {
            $query = $query->where('subject', Outil::getOperateurLikeDB(), '%' . $args['subject'] . '%');
        }
        if (isset($args['subject'])) {
            $query = $query->where('subject', Outil::getOperateurLikeDB(), '%' . $args['subject'] . '%');
        }
        if (isset($args['body'])) {
            $query = $query->where('body', Outil::getOperateurLikeDB(), '%' . $args['body'] . '%');
        }
        if (isset($args['sender_email'])) {
            $query = $query->where('sender_email', Outil::getOperateurLikeDB(), '%' . $args['sender_email'] . '%');
        }
        if (isset($args['user_id'])) {
            $query = $query->where('user_id', $args['user_id']);
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('inboxs.locataire_id', $args['locataire_id']);
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', $args['appartement_id']);
        }


        $query = $query->orderBy('inboxs.id', 'DESC');
        return $query;
    }
    public static function getQueryAttachement($args)
    {
        $query = Attachement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['filename'])) {
            $query = $query->where('filename', Outil::getOperateurLikeDB(), '%' . $args['filename'] . '%');
        }

        if (isset($args['inbox_id'])) {
            $query = $query->where('inbox_id', $args['inbox_id']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryAnnexe($args)
    {
        $query = Annexe::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['filename'])) {
            $query = $query->where('filename', Outil::getOperateurLikeDB(), '%' . $args['filename'] . '%');
        }
        if (isset($args['numero'])) {
            $query = $query->where('numero', Outil::getOperateurLikeDB(), '%' . $args['numero'] . '%');
        }

        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', $args['contrat_id']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryAvisecheance($args)
    {
        $query = Avisecheance::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['objet'])) {
            $query = $query->where('objet', Outil::getOperateurLikeDB(), '%' . $args['objet'] . '%');
        }
        if (isset($args['date'])) {
            $query = $query->whereDate('date', $args['date']);
        }
        if (isset($args['date_echeance'])) {
            $query = $query->whereDate('date_echeance', $args['date_echeance']);
        }
        if (isset($args['date_annulation_paiement'])) {
            $query = $query->whereDate('date_annulation_paiement', $args['date_annulation_paiement']);
        }

        if (isset($args['est_activer'])) {
            $query = $query->where('est_activer', $args['est_activer']);
        }
        if (isset($args['est_signer'])) {
            $query = $query->where('est_signer', $args['est_signer']);
        }

        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', $args['contrat_id']);
        }
        if (isset($args['periodicite_id'])) {
            $query = $query->where('periodicite_id', $args['periodicite_id']);
        }

        $query = $query->orderBy('date', 'DESC');
        return $query;
    }
    public static function getQueryImmeuble($args)
    {
        $query = Immeuble::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['adresse'])) {
            $query = $query->where('adresse', Outil::getOperateurLikeDB(), '%' . $args['adresse'] . '%');
        }
        if (isset($args['structureimmeuble_id'])) {
            $query = $query->where('structureimmeuble_id', Outil::getOperateurLikeDB(), '%' . $args['structureimmeuble_id'] . '%');
        }
        if (isset($args['gardien'])) {
            $query = $query->where('gardien', Outil::getOperateurLikeDB(), '%' . $args['gardien'] . '%');
        }
        if (isset($args['iscopropriete'])) {
            $query = $query->where('iscopropriete', Outil::getOperateurLikeDB(), '%' . $args['iscopropriete'] . '%');
        }
        if (isset($args['equipegestion_id'])) {
            $query = $query->where('equipegestion_id', Outil::getOperateurLikeDB(), '%' . $args['equipegestion_id'] . '%');
        }

        if (isset($args['code'])) {
            // dont les appartements ont  entite qui a code
            $query = $query->whereHas('appartements', function ($query) use ($args) {
                $query->whereHas('entite', function ($query) use ($args) {
                    $query->where('code', $args['code']);
                });
            });
        }


        if (isset($args['pieceappartements'])) {
            $query = $query->where('pieceappartements', Outil::getOperateurLikeDB(), '%' . $args['pieceappartements'] . '%');
        }
        if (isset($args['proprietaires'])) {
            $query = $query->where('proprietaires', Outil::getOperateurLikeDB(), '%' . $args['proprietaires'] . '%');
        }
        if (isset($args['appartements'])) {
            $query = $query->where('appartements', Outil::getOperateurLikeDB(), '%' . $args['appartements'] . '%');
        }
        if (isset($args['obligationadministratives'])) {
            $query = $query->where('obligationadministratives', Outil::getOperateurLikeDB(), '%' . $args['obligationadministratives'] . '%');
        }
        if (isset($args['annonces'])) {
            $query = $query->where('annonces', Outil::getOperateurLikeDB(), '%' . $args['annonces'] . '%');
        }
        if (isset($args['factures'])) {
            $query = $query->where('factures', Outil::getOperateurLikeDB(), '%' . $args['factures'] . '%');
        }
        if (isset($args['rapportinterventions'])) {
            $query = $query->where('rapportinterventions', Outil::getOperateurLikeDB(), '%' . $args['rapportinterventions'] . '%');
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }



    // Ajouter par moi

    public static function getQueryFacturelocation($args)
    {
        $query = Facturelocation::query();
        //dd($query);
        if (isset($args['id'])) {
            // dd($args['id']);
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['typefacture_id'])) {
            $query = $query->where('typefacture_id', $args['typefacture_id']);
        }
        if (isset($args['periodicite_id'])) {
            $query = $query->where('periodicite_id', $args['periodicite_id']);
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', $args['contrat_id']);
        }
        if (isset($args['proprietaire_id'])) {
            $query = $query->whereIn('contrat_id', Contrat::whereIn('appartement_id', Appartement::where('proprietaire_id', $args['proprietaire_id'])->get(['id']))->get(['id']));
        }

        if (isset($args['est_activer'])) {
            $query = $query->where('est_activer', $args['est_activer']);
        }




        if (isset($args['objetfacture'])) {
            $query = $query->where('objetfacture', Outil::getOperateurLikeDB(), '%' . $args['objetfacture'] . '%');
        }

        if (isset($args['datefacture'])) {
            $query = $query->where('datefacture', Outil::getOperateurLikeDB(), '%' . $args['datefacture'] . '%');
        }

        // datedeb datefin

        if (isset($args['datedeb']) && isset($args['datefin'])) {
            $query = $query->whereBetween('datefacture', [$args['datedeb'], $args['datefin']]);
        }


        //locataire_id

        if (isset($args['locataire_id'])) {
            $query = $query->whereHas('contrat', function ($query) use ($args) {
                $query->where('locataire_id', $args['locataire_id']);
            });
        }




        if (isset($args['nbremoiscausion'])) {
            $query = $query->where('nbremoiscausion', $args['nbremoiscausion']);
        }

        if (isset($args['date_echeance'])) {
            $query = $query->whereDate('date_echeance', $args['date_echeance']);
        }

        if (isset($args['demanderesiliation_id'])) {
            $demandeResiliation = Demanderesiliation::find($args['demanderesiliation_id']);
            if (isset($demandeResiliation) && isset($demandeResiliation->id)) {
                $query = $query->where('contrat_id', $demandeResiliation->contrat_id);
            }
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryUnite($args)
    {
        $query = Unite::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryPuhtva($args)
    {
        $query = Puhtva::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['puhtva'])) {
            $query = $query->where('puhtva', Outil::getOperateurLikeDB(), '%' . $args['puhtva'] . '%');
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryQuantite($args)
    {
        $query = \App\Quantite::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['qunatite'])) {
            $query = $query->where('qunatite', Outil::getOperateurLikeDB(), '%' . $args['qunatite'] . '%');
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryDetaildevi($args)
    {
        $query = Detaildevi::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['categorieintervention_id'])) {
            $query = $query->where('categorieintervention_id', $args['categorieintervention_id']);
        }
        if (isset($args['devi_id'])) {
            $query = $query->where('devi_id', $args['devi_id']);
        }

        $query = $query->orderBy('id');
        return $query;
    }




    public static function getQuerySoustypeintervention($args)
    {
        $query = Soustypeintervention::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }

        if (isset($args['categorieintervention_id'])) {
            $query = $query->where('categorieintervention', $args['categorieintervention']);
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryDetaildevisdetail($args)
    {
        $query = Detaildevisdetail::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }


        if (isset($args['detaildevi_id'])) {
            $query = $query->where('detaildevi_id', $args['detaildevi_id']);
        }

        if (isset($args['soustypeintervention_id'])) {
            $query = $query->where('soustypeintervention_id', $args['soustypeintervention_id']);
        }


        $query = $query->orderBy('id', 'DESC');
        return $query;
    }



    public static function getQueryDevi($args)
    {
        $query = Devi::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['code'])) {
            $query = $query->where('code', Outil::getOperateurLikeDB(), '%' . $args['code'] . '%');
        }

        if (isset($args['etatlieu_id'])) {
            $query = $query->where('etatlieu_id', $args['etatlieu_id']);
        }

        if (isset($args['object'])) {
            $query = $query->where('object', Outil::getOperateurLikeDB(), '%' . $args['object'] . '%');
        }
        if (isset($args['demandeintervention_id'])) {
            $query = $query->where('demandeintervention_id', $args['demandeintervention_id']);
        }

        if (isset($args['date'])) {
            $query = $query->where('date', Outil::getOperateurLikeDB(), '%' . $args['date'] . '%');
        }

        $query = $query->orderBy('id');
        return $query;
    }
















    public static function getQueryLocataire($args)
    {
        $query = Locataire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['user_id'])) {
            $query = $query->where('user_id', Outil::getOperateurLikeDB(), '%' . $args['user_id'] . '%');
        }
        if (isset($args['user'])) {
            $query = $query->where('user', Outil::getOperateurLikeDB(), '%' . $args['user'] . '%');
        }
        if (isset($args['prenom'])) {
            $query = $query->where('prenom', Outil::getOperateurLikeDB(), '%' . $args['prenom'] . '%');
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['telephoneportable1'])) {
            $query = $query->where('telephoneportable1', Outil::getOperateurLikeDB(), '%' . $args['telephoneportable1'] . '%');
        }
        if (isset($args['telephoneportable2'])) {
            $query = $query->where('telephoneportable2', Outil::getOperateurLikeDB(), '%' . $args['telephoneportable2'] . '%');
        }
        if (isset($args['telephonebureau'])) {
            $query = $query->where('telephonebureau', Outil::getOperateurLikeDB(), '%' . $args['telephonebureau'] . '%');
        }
        if (isset($args['email'])) {
            $query = $query->where('email', Outil::getOperateurLikeDB(), '%' . $args['email'] . '%');
        }
        if (isset($args['profession'])) {
            $query = $query->where('profession', Outil::getOperateurLikeDB(), '%' . $args['profession'] . '%');
        }
        if (isset($args['age'])) {
            $query = $query->where('age', Outil::getOperateurLikeDB(), '%' . $args['age'] . '%');
        }
        if (isset($args['cni'])) {
            $query = $query->where('cni', Outil::getOperateurLikeDB(), '%' . $args['cni'] . '%');
        }
        if (isset($args['passeport'])) {
            $query = $query->where('passeport', Outil::getOperateurLikeDB(), '%' . $args['passeport'] . '%');
        }
        if (isset($args['age'])) {
            $query = $query->where('age', Outil::getOperateurLikeDB(), '%' . $args['age'] . '%');
        }
        if (isset($args['cni'])) {
            $query = $query->where('cni', Outil::getOperateurLikeDB(), '%' . $args['cni'] . '%');
        }
        if (isset($args['passeport'])) {
            $query = $query->where('passeport', Outil::getOperateurLikeDB(), '%' . $args['passeport'] . '%');
        }
        if (isset($args['age'])) {
            $query = $query->where('age', Outil::getOperateurLikeDB(), '%' . $args['age'] . '%');
        }
        if (isset($args['cni'])) {
            $query = $query->where('cni', Outil::getOperateurLikeDB(), '%' . $args['cni'] . '%');
        }
        if (isset($args['passeport'])) {
            $query = $query->where('passeport', Outil::getOperateurLikeDB(), '%' . $args['passeport'] . '%');
        }
        if (isset($args['revenus'])) {
            $query = $query->where('revenus', Outil::getOperateurLikeDB(), '%' . $args['revenus'] . '%');
        }
        if (isset($args['contrattravail'])) {
            $query = $query->where('contrattravail', Outil::getOperateurLikeDB(), '%' . $args['contrattravail'] . '%');
        }
        if (isset($args['expatlocale'])) {
            $query = $query->where('expatlocale', Outil::getOperateurLikeDB(), '%' . $args['expatlocale'] . '%');
        }
        if (isset($args['nomcompletpersonnepriseencharge'])) {
            $query = $query->where('nomcompletpersonnepriseencharge', Outil::getOperateurLikeDB(), '%' . $args['nomcompletpersonnepriseencharge'] . '%');
        }
        if (isset($args['telephonepersonnepriseencharge'])) {
            $query = $query->where('telephonepersonnepriseencharge', Outil::getOperateurLikeDB(), '%' . $args['telephonepersonnepriseencharge'] . '%');
        }
        if (isset($args['nomentreprise'])) {
            $query = $query->where('nomentreprise', Outil::getOperateurLikeDB(), '%' . $args['nomentreprise'] . '%');
        }
        if (isset($args['adresseentreprise'])) {
            $query = $query->where('adresseentreprise', Outil::getOperateurLikeDB(), '%' . $args['adresseentreprise'] . '%');
        }
        if (isset($args['ninea'])) {
            $query = $query->where('ninea', Outil::getOperateurLikeDB(), '%' . $args['ninea'] . '%');
        }
        if (isset($args['documentninea'])) {
            $query = $query->where('documentninea', Outil::getOperateurLikeDB(), '%' . $args['documentninea'] . '%');
        }
        if (isset($args['numerorg'])) {
            $query = $query->where('numerorg', Outil::getOperateurLikeDB(), '%' . $args['numerorg'] . '%');
        }
        if (isset($args['documentnumerorg'])) {
            $query = $query->where('documentnumerorg', Outil::getOperateurLikeDB(), '%' . $args['documentnumerorg'] . '%');
        }
        if (isset($args['documentstatut'])) {
            $query = $query->where('documentstatut', Outil::getOperateurLikeDB(), '%' . $args['documentstatut'] . '%');
        }
        if (isset($args['personnehabiliteasigner'])) {
            $query = $query->where('personnehabiliteasigner', Outil::getOperateurLikeDB(), '%' . $args['personnehabiliteasigner'] . '%');
        }
        if (isset($args['fonctionpersonnehabilite'])) {
            $query = $query->where('fonctionpersonnehabilite', Outil::getOperateurLikeDB(), '%' . $args['fonctionpersonnehabilite'] . '%');
        }
        if (isset($args['nompersonneacontacter'])) {
            $query = $query->where('nompersonneacontacter', Outil::getOperateurLikeDB(), '%' . $args['nompersonneacontacter'] . '%');
        }
        if (isset($args['prenompersonneacontacter'])) {
            $query = $query->where('prenompersonneacontacter', Outil::getOperateurLikeDB(), '%' . $args['prenompersonneacontacter'] . '%');
        }
        if (isset($args['emailpersonneacontacter'])) {
            $query = $query->where('emailpersonneacontacter', Outil::getOperateurLikeDB(), '%' . $args['emailpersonneacontacter'] . '%');
        }
        if (isset($args['telephone1personneacontacter'])) {
            $query = $query->where('telephone1personneacontacter', Outil::getOperateurLikeDB(), '%' . $args['telephone1personneacontacter'] . '%');
        }
        if (isset($args['telephone2personneacontacter'])) {
            $query = $query->where('telephone2personneacontacter', Outil::getOperateurLikeDB(), '%' . $args['telephone2personneacontacter'] . '%');
        }
        if (isset($args['etatlocataire'])) {
            $query = $query->where('etatlocataire', Outil::getOperateurLikeDB(), '%' . $args['etatlocataire'] . '%');
        }
        if (isset($args['typelocataire_id'])) {
            $query = $query->where('typelocataire_id', Outil::getOperateurLikeDB(), '%' . $args['typelocataire_id'] . '%');
        }
        if (isset($args['observation_id'])) {
            $query = $query->where('observation_id', Outil::getOperateurLikeDB(), '%' . $args['observation_id'] . '%');
        }
        if (isset($args['appartements'])) {
            $query = $query->where('appartements', Outil::getOperateurLikeDB(), '%' . $args['appartements'] . '%');
        }
        if (isset($args['contrats'])) {
            $query = $query->where('contrats', Outil::getOperateurLikeDB(), '%' . $args['contrats'] . '%');
        }
        if (isset($args['interventions'])) {
            $query = $query->where('interventions', Outil::getOperateurLikeDB(), '%' . $args['interventions'] . '%');
        }
        if (isset($args['messages'])) {
            $query = $query->where('messages', Outil::getOperateurLikeDB(), '%' . $args['messages'] . '%');
        }
        if (isset($args['questionnairesatisfactions'])) {
            $query = $query->where('questionnairesatisfactions', Outil::getOperateurLikeDB(), '%' . $args['questionnairesatisfactions'] . '%');
        }
        if (isset($args['entite_id'])) {
            $query = $query->where('entite_id', $args['entite_id']);
        }
        if (isset($args['secteuractivite_id'])) {
            $query = $query->where('secteuractivite_id', $args['secteuractivite_id']);
        }
        if (isset($args['date_naissance'])) {
            $query = $query->whereDate('date_naissance', $args['date_naissance']);
        }
        if (isset($args['mandataire'])) {
            $query = $query->where('mandataire', $args['mandataire']);
        }
        if (isset($args['lieux_naissance'])) {
            $query = $query->where('lieux_naissance', Outil::getOperateurLikeDB(), '%' . $args['lieux_naissance'] . '%');
        }
        if (isset($args['pays_naissance'])) {
            $query = $query->where('pays_naissance', $args['pays_naissance']);
        }
        if (isset($args['search'])) {
            // $query = $query->Where(DB::raw("CONCAT(prenom, ' ', nom)"), Outil::getOperateurLikeDB(),'%' . $args['search'] . '%');
            $motRecherche  = $args['search'];
            $query->where(function ($query) use ($motRecherche) {
                return $query->where('nom', Outil::getOperateurLikeDB(), '%' . $motRecherche . '%')
                    ->orWhere('prenom', Outil::getOperateurLikeDB(), '%' . $motRecherche . '%')
                    ->orWhere('nomentreprise', Outil::getOperateurLikeDB(), '%' . $motRecherche . '%');
            });
        }


        if (isset($args['est_copreuneur'])) {
            $query = $query->where('est_copreuneur', $args['est_copreuneur']);
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function getQueryCopreneur($args)
    {
        $query = Copreneur::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['prenom'])) {
            $query = $query->where('prenom', Outil::getOperateurLikeDB(), '%' . $args['prenom'] . '%');
        }
        if (isset($args['nom'])) {
            $query = $query->where('nom', Outil::getOperateurLikeDB(), '%' . $args['nom'] . '%');
        }
        if (isset($args['telephone1'])) {
            $query = $query->where('telephone1', Outil::getOperateurLikeDB(), '%' . $args['telephone1'] . '%');
        }
        if (isset($args['telephone2'])) {
            $query = $query->where('telephone2', Outil::getOperateurLikeDB(), '%' . $args['telephone2'] . '%');
        }

        if (isset($args['email'])) {
            $query = $query->where('email', $args['email']);
        }

        if (isset($args['cni'])) {
            $query = $query->where('cni', $args['cni']);
        }
        if (isset($args['passeport'])) {
            $query = $query->where('passeport',  $args['passeport']);
        }


        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', $args['locataire_id']);
        }



        $query = $query->orderBy('id', 'DESC');
        return $query;
    }
    public static function filterByEntite($query)
    {
        $userId = Auth::user();
        $user = User::find($userId);
        if ($user && $user->entite_id) {
            $query = $query->where('entite_id', $user->entite_id);
        }
    }

    public static function getQueryDemanderesiliation($args)
    {
        $query = Demanderesiliation::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['datedebutcontrat'])) {
            $query = $query->where('datedebutcontrat', Outil::getOperateurLikeDB(), '%' . $args['datedebutcontrat'] . '%');
        }
        if (isset($args['etat'])) {
            $query = $query->where('etat', Outil::getOperateurLikeDB(), '%' . $args['etat'] . '%');
        }
        if (isset($args['datedemande'])) {
            $query = $query->where('datedemande', Outil::getOperateurLikeDB(), '%' . $args['datedemande'] . '%');
        }
        if (isset($args['delaipreavisrespecte'])) {
            $query = $query->where('delaipreavisrespecte', Outil::getOperateurLikeDB(), '%' . $args['delaipreavisrespecte'] . '%');
        }
        if (isset($args['raisonnonrespectdelai'])) {
            $query = $query->where('raisonnonrespectdelai', Outil::getOperateurLikeDB(), '%' . $args['raisonnonrespectdelai'] . '%');
        }
        if (isset($args['delaipreavis'])) {
            $query = $query->where('delaipreavis', Outil::getOperateurLikeDB(), '%' . $args['delaipreavis'] . '%');
        }
        if (isset($args['dateeffectivite'])) {
            $query = $query->where('dateeffectivite', Outil::getOperateurLikeDB(), '%' . $args['dateeffectivite'] . '%');
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', $args['contrat_id']);
        }
        if (isset($args['document'])) {
            $query = $query->where('document', Outil::getOperateurLikeDB(), '%' . $args['document'] . '%');
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('contrat_id',Contrat::where('locataire_id',$args['locataire_id'])
        ->pluck('id'));
        }
        $query = $query->orderBy('id', 'DESC');
        return $query;
    }





    public static function getQueryRole($args)
    {
        $query = Role::with('permissions');

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['name'])) {
            $query = $query->where('name', 'like', '%' . $args['name'] . '%');
        }
        if (isset($args['connected_user'])) {
            $user = Auth::user();
            $roleId = $user->roles->first()->id;
            $roles  = Role::find($roleId);
            if (isset($roles) && isset($roles->name) && $roles->name !== 'super-admin') {
                $query = $query->where('id', $roleId);
            }
        }


        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryFraisupplementaires($args)
    {
        $query = Fraisupplementaire::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }

        if (isset($args['avisecheance_id'])) {
            $query = $query->where('avisecheance_id', $args['avisecheance_id']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryInfobancaire($args)
    {
        $query = Infobancaire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['datedebut'])) {
            $query = $query->whereDate('datedebut',  $args['datedebut']);
        }
        if (isset($args['datefin'])) {
            $query = $query->whereDate('datefin',  $args['datefin']);
        }

        if (isset($args['entite_id'])) {
            $query = $query->where('entite_id', $args['entite_id']);
        }

        $query = $query->orderBy('id', 'DESC');
        return $query;
    }

    public static function getQueryEtatencaissement($args)
    {
        $query = self::getQueryContratLocationVente($args);

        if (isset($args['top'])) {
            $query = Outil::meillleurpayeur($query, $args);
        }

        if (isset($args['anticipation'])) {
            $query = Outil::meillleurpayeur($query, $args);
        }


        if (isset($args['top'])) {
            $query = $query->orderBy('nbpaiement', 'DESC');
        }

        return $query;
    }
    public static function getQueryTypeapportponctuel($args)
    {
        $query = Typeapportponctuel::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryApportponctuel($args)
    {
        $query = Apportponctuel::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['montant'])) {
            $query = $query->where('montant', Outil::getOperateurLikeDB(), '%' . $args['montant'] . '%');
        }
        if (isset($args['date'])) {
            $query = $query->where('date', Outil::getOperateurLikeDB(), '%' . $args['date'] . '%');
        }
        if (isset($args['contrat_id'])) {
            $query = $query->where('contrat_id', Outil::getOperateurLikeDB(), '%' . $args['contrat_id'] . '%');
        }
        if (isset($args['observations'])) {
            $query = $query->where('observations', Outil::getOperateurLikeDB(), '%' . $args['observations'] . '%');
        }
        if (isset($args['typeapportponctuel_id'])) {
            $query = $query->where('typeapportponctuel_id', Outil::getOperateurLikeDB(), '%' . $args['typeapportponctuel_id'] . '%');
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryContratproprietaire($args)
    {
        $query = Contratproprietaire::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['date'])) {
            $query = $query->where('date', Outil::getOperateurLikeDB(), '%' . $args['date'] . '%');
        }
        if (isset($args['descriptif'])) {
            $query = $query->where('descriptif', Outil::getOperateurLikeDB(), '%' . $args['descriptif'] . '%');
        }
        if (isset($args['commissionvaleur'])) {
            $query = $query->where('commissionvaleur', Outil::getOperateurLikeDB(), '%' . $args['commissionvaleur'] . '%');
        }
        if (isset($args['commissionpourcentage'])) {
            $query = $query->where('commissionpourcentage', Outil::getOperateurLikeDB(), '%' . $args['commissionpourcentage'] . '%');
        }
        if (isset($args['is_tva'])) {
            $query = $query->where('is_tva', Outil::getOperateurLikeDB(), '%' . $args['is_tva'] . '%');
        }
        if (isset($args['is_brs'])) {
            $query = $query->where('is_brs', Outil::getOperateurLikeDB(), '%' . $args['is_brs'] . '%');
        }
        if (isset($args['datedeb']) && isset($args['datefin'])) {
            $query = $query->whereBetween('date', [$args['datedeb'], $args['datefin']]);
        }
        if (isset($args['is_tlv'])) {
            $query = $query->where('is_tlv', Outil::getOperateurLikeDB(), '%' . $args['is_tlv'] . '%');
        }
        if (isset($args['entite_id'])) {
            $query = $query->where('entite_id', Outil::getOperateurLikeDB(), '%' . $args['entite_id'] . '%');
        }
        if (isset($args['proprietaire_id'])) {
            $query = $query->where('proprietaire_id', $args['proprietaire_id']);
        }
        if (isset($args['modelcontrat_id'])) {
            $query = $query->where('modelcontrat_id', Outil::getOperateurLikeDB(), '%' . $args['modelcontrat_id'] . '%');
        }

        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryModelcontrat($args)
    {
        $query = Modelcontrat::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }
    public static function getQueryActivite($args)
    {
        $query = Activite::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryTaxe($args)
    {
        $query = Taxe::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['designation'])) {
            $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
        }
        if (isset($args['description'])) {
            $query = $query->where('description', Outil::getOperateurLikeDB(), '%' . $args['description'] . '%');
        }
        if (isset($args['valeur'])) {
            $query = $query->where('valeur', $args['valeur']);
        }
        $query = $query->orderBy('id');
        return $query;
    }

    public static function getQueryDocumentappartement($args)
    {
        $query = Documentappartement::query();
        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['appartement_id'])) {
            $query = $query->where('appartement_id', $args['appartement_id']);
        }

        $query = $query->orderBy('id');
        return $query;
    }
}
