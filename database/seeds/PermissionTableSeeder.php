<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $permissions = [

      // raf permission upload signature
      array("name" => "upload-signature", "guard_name" => "web", "display_name" => "Ajouter Signature"),
      array("name" => "view-signature-recu", "guard_name" => "web", "display_name" => "view signature recu"),
      array("name" => "signature-avis-echeance", "guard_name" => "web", "display_name" => "Signer un avis"),
      array("name" => "assistante-reseaux", "guard_name" => "web", "display_name" => "Assistante reseaux"),
      // juriste permission
      array("name" => "valider-contrat-locationvente", "guard_name" => "web", "display_name" => "valider contrat location vente"),

      // Permissions facturelocation

      array("name" => "liste-devi", "guard_name" => "web", "display_name" => "Voir liste des factures devi "),
      array("name" => "creation-devi", "guard_name" => "web", "display_name" => "Créer  factures devi "),
      array("name" => "modification-devi", "guard_name" => "web", "display_name" => "Modifier factures devi"),
      array("name" => "suppression-devi", "guard_name" => "web", "display_name" => "supprimer factures location "),

      // Permissions modepaiement

      array("name" => "liste-modepaiement", "guard_name" => "web", "display_name" => "Voir liste des modepaiement"),
      array("name" => "creation-modepaiement", "guard_name" => "web", "display_name" => "Créer modepaiement  "),
      array("name" => "modification-modepaiement", "guard_name" => "web", "display_name" => "Modifier modepaiement"),
      array("name" => "suppression-modepaiement", "guard_name" => "web", "display_name" => "supprimer modepaiement "),

      // Permissions echeance

      array("name" => "liste-paiementecheance", "guard_name" => "web", "display_name" => "Voir liste des paiements"),
      array("name" => "creation-paiementecheance", "guard_name" => "web", "display_name" => "Créer paiement echeance  "),
      array("name" => "modification-paiementecheance", "guard_name" => "web", "display_name" => "Modifier paiement echeance"),
      array("name" => "suppression-paiementecheance", "guard_name" => "web", "display_name" => "supprimer paiement echeance "),

      // Permissions facturelocation

      array("name" => "liste-facturelocation", "guard_name" => "web", "display_name" => "Voir liste des factures location "),
      array("name" => "creation-facturelocation", "guard_name" => "web", "display_name" => "Créer  factures location "),
      array("name" => "modification-facturelocation", "guard_name" => "web", "display_name" => "Modifier factures location"),
      array("name" => "suppression-facturelocation", "guard_name" => "web", "display_name" => "supprimer factures location "),

      // Permissions inbox

      array("name" => "liste-inbox", "guard_name" => "web", "display_name" => "Voir liste des inboxs "),
      array("name" => "creation-inbox", "guard_name" => "web", "display_name" => "Créer  inboxn "),
      array("name" => "modification-inbox", "guard_name" => "web", "display_name" => "Modifier inbox"),
      array("name" => "suppression-inbox", "guard_name" => "web", "display_name" => "supprimer inbox "),


      // Permissions Role
      array("name" => "liste-role", "guard_name" => "web", "display_name" => "Voir liste des profils"),
      array("name" => "creation-role", "guard_name" => "web", "display_name" => "Créer profil"),
      array("name" => "modification-role", "guard_name" => "web", "display_name" => "Modifier profil"),
      array("name" => "suppression-role", "guard_name" => "web", "display_name" => "supprimer profil"),

      // Permissions entite
      array("name" => "liste-entite", "guard_name" => "web", "display_name" => "Voir liste des entites"),
      array("name" => "creation-entite", "guard_name" => "web", "display_name" => "Créer entite"),
      array("name" => "modification-entite", "guard_name" => "web", "display_name" => "Modifier entite"),
      array("name" => "suppression-entite", "guard_name" => "web", "display_name" => "supprimer entite"),
      // Permissions ilot
      array("name" => "liste-ilot", "guard_name" => "web", "display_name" => "Voir liste des ilots"),
      array("name" => "creation-ilot", "guard_name" => "web", "display_name" => "Créer ilot"),
      array("name" => "modification-ilot", "guard_name" => "web", "display_name" => "Modifier ilot"),
      array("name" => "suppression-ilot", "guard_name" => "web", "display_name" => "supprimer ilot"),
      // Permissions Dashboard
      array("name" => "liste-dashboard", "guard_name" => "web", "display_name" => "Voir le dashboard"),
      array("name" => "liste-accueil", "guard_name" => "web", "display_name" => "Voir l'acceuil"),

      // Permissions Utilisateur
      array("name" => "liste-user", "guard_name" => "web", "display_name" => "Voir liste des utilisateurs"),
      array("name" => "creation-user", "guard_name" => "web", "display_name" => "Créer utilisateur"),
      array("name" => "modification-user", "guard_name" => "web", "display_name" => "Modifier utilisateur"),
      array("name" => "suppression-user", "guard_name" => "web", "display_name" => "Supprimer utilisateurs"),


      // Permissions Typeappartement
      array("name" => "liste-typeappartement", "guard_name" => "web", "display_name" => "Voir liste des types d'appartement"),
      array("name" => "creation-typeappartement", "guard_name" => "web", "display_name" => "Créer type d'appartement"),
      array("name" => "modification-typeappartement", "guard_name" => "web", "display_name" => "Modifier type d'appartement"),
      array("name" => "suppression-typeappartement", "guard_name" => "web", "display_name" => "Supprimer type d'appartement"),

      // Permissions Typeassurance
      array("name" => "liste-typeassurance", "guard_name" => "web", "display_name" => "Voir liste des types d'assurances"),
      array("name" => "creation-typeassurance", "guard_name" => "web", "display_name" => "Créer type d'assurance"),
      array("name" => "modification-typeassurance", "guard_name" => "web", "display_name" => "Modifier type d'assurance"),
      array("name" => "suppression-typeassurance", "guard_name" => "web", "display_name" => "Supprimer type d'assurance"),

      // Permissions Typecontrat
      array("name" => "liste-typecontrat", "guard_name" => "web", "display_name" => "Voir liste des types de contrats"),
      array("name" => "creation-typecontrat", "guard_name" => "web", "display_name" => "Créer un type de contrat "),
      array("name" => "modification-typecontrat", "guard_name" => "web", "display_name" => "Modifier un type de contrat"),
      array("name" => "suppression-typecontrat", "guard_name" => "web", "display_name" => "Supprimer un type de contrat"),

      // Permissions constituantpiece
      array("name" => "liste-constituantpiece", "guard_name" => "web", "display_name" => "Voir liste des constituant piece"),
      array("name" => "creation-constituantpiece", "guard_name" => "web", "display_name" => "Créer un constituant piece "),
      array("name" => "modification-constituantpiece", "guard_name" => "web", "display_name" => "Modifier un constituant piece"),
      array("name" => "suppression-constituantpiece", "guard_name" => "web", "display_name" => "Supprimer un constituant piece"),

      // Permissions Typedocument
      array("name" => "liste-typedocument", "guard_name" => "web", "display_name" => "Voir liste des types de documents"),
      array("name" => "creation-typedocument", "guard_name" => "web", "display_name" => "Créer un type de document "),
      array("name" => "modification-typedocument", "guard_name" => "web", "display_name" => "Modifier un type de document"),
      array("name" => "suppression-typedocument", "guard_name" => "web", "display_name" => "Supprimer un type de document"),

      // Permissions Typefacture
      array("name" => "liste-typefacture", "guard_name" => "web", "display_name" => "Voir liste des types de factures"),
      array("name" => "creation-typefacture", "guard_name" => "web", "display_name" => "Créer un type de facture "),
      array("name" => "modification-typefacture", "guard_name" => "web", "display_name" => "Modifier un type de facture"),
      array("name" => "suppression-typefacture", "guard_name" => "web", "display_name" => "Supprimer un type de facture"),

      // Permissions Typeintervention
      array("name" => "liste-typeintervention", "guard_name" => "web", "display_name" => "Voir liste des types d'interventions"),
      array("name" => "creation-typeintervention", "guard_name" => "web", "display_name" => "Créer un type d'intervention"),
      array("name" => "modification-typeintervention", "guard_name" => "web", "display_name" => "Modifier un type d'intervention"),
      array("name" => "suppression-typeintervention", "guard_name" => "web", "display_name" => "Supprimer un type d'intervention"),

      // Permissions Typelocataire
      array("name" => "liste-typelocataire", "guard_name" => "web", "display_name" => "Voir liste des types de locataires"),
      array("name" => "creation-typelocataire", "guard_name" => "web", "display_name" => "Créer un type de locataire"),
      array("name" => "modification-typelocataire", "guard_name" => "web", "display_name" => "Modifier un type de locataire"),
      array("name" => "suppression-typelocataire", "guard_name" => "web", "display_name" => "Supprimer un type locataire"),

      // Permissions Typepiece
      array("name" => "liste-typepiece", "guard_name" => "web", "display_name" => "Voir liste des types de pieces"),
      array("name" => "creation-typepiece", "guard_name" => "web", "display_name" => "Créer un type de piece"),
      array("name" => "modification-typepiece", "guard_name" => "web", "display_name" => "Modifier un type de piece"),
      array("name" => "suppression-typepiece", "guard_name" => "web", "display_name" => "Supprimer un type de piece"),

      // Permissions Typerenouvellement
      array("name" => "liste-typerenouvellement", "guard_name" => "web", "display_name" => "Voir liste des types de renouvellements"),
      array("name" => "creation-typerenouvellement", "guard_name" => "web", "display_name" => "Créer un type de renouvellement"),
      array("name" => "modification-typerenouvellement", "guard_name" => "web", "display_name" => "Modifier un type de renouvellement"),
      array("name" => "suppression-typerenouvellement", "guard_name" => "web", "display_name" => "Supprimer un type de renouvellement"),

      // Permissions Typeobligationadministrative
      array("name" => "liste-typeobligationadministrative", "guard_name" => "web", "display_name" => "Voir liste des types d'obligations administratives"),
      array("name" => "creation-typeobligationadministrative", "guard_name" => "web", "display_name" => "Créer un type de d'obligation administrative"),
      array("name" => "modification-typeobligationadministrative", "guard_name" => "web", "display_name" => "Modifier un type d'obligation administrative"),
      array("name" => "suppression-typeobligationadministrative", "guard_name" => "web", "display_name" => "Supprimer un type de d'obligation administrative"),


      // Permissions pieceappartement
      array("name" => "liste-pieceappartement", "guard_name" => "web", "display_name" => "Voir liste des pieces d'appartements"),
      array("name" => "creation-pieceappartement", "guard_name" => "web", "display_name" => "Créer une piece d'appartement"),
      array("name" => "modification-pieceappartement", "guard_name" => "web", "display_name" => "Modifier une piece d'appartement"),
      array("name" => "suppression-pieceappartement", "guard_name" => "web", "display_name" => "Supprimer une piece d'appartement"),

      // Permissions Immeuble
      array("name" => "liste-immeuble", "guard_name" => "web", "display_name" => "Voir liste des immeubles"),
      array("name" => "creation-immeuble", "guard_name" => "web", "display_name" => "Créer immeuble"),
      array("name" => "modification-immeuble", "guard_name" => "web", "display_name" => "Modifier immeuble"),
      array("name" => "suppression-immeuble", "guard_name" => "web", "display_name" => "Supprimer immeubles"),

      // Permissions Appartement
      array("name" => "liste-appartement", "guard_name" => "web", "display_name" => "Voir liste des appartements"),
      array("name" => "creation-appartement", "guard_name" => "web", "display_name" => "Créer appartement"),
      array("name" => "modification-appartement", "guard_name" => "web", "display_name" => "Modifier appartement"),
      array("name" => "suppression-appartement", "guard_name" => "web", "display_name" => "Supprimer appartements"),

      // Permissions Villa
      array("name" => "liste-villa", "guard_name" => "web", "display_name" => "Voir liste des villas"),
      array("name" => "creation-villa", "guard_name" => "web", "display_name" => "Créer villa"),
      array("name" => "modification-villa", "guard_name" => "web", "display_name" => "Modifier villa"),
      array("name" => "suppression-villa", "guard_name" => "web", "display_name" => "Supprimer villas"),

      // Permissions Prestataire
      array("name" => "liste-prestataire", "guard_name" => "web", "display_name" => "Voir liste des prestataires"),
      array("name" => "creation-prestataire", "guard_name" => "web", "display_name" => "Créer un prestataire"),
      array("name" => "modification-prestataire", "guard_name" => "web", "display_name" => "Modifier un prestataire"),
      array("name" => "suppression-prestataire", "guard_name" => "web", "display_name" => "Supprimer un prestataire"),

      // Permissions Proprietaire
      array("name" => "liste-proprietaire", "guard_name" => "web", "display_name" => "Voir liste des proprietaires"),
      array("name" => "creation-proprietaire", "guard_name" => "web", "display_name" => "Créer un proprietaire"),
      array("name" => "modification-proprietaire", "guard_name" => "web", "display_name" => "Modifier un proprietaire"),
      array("name" => "suppression-proprietaire", "guard_name" => "web", "display_name" => "Supprimer un proprietaire"),

      // Permissions état des lieux
      array("name" => "liste-etatlieux", "guard_name" => "web", "display_name" => "Voir liste état des lieux"),
      array("name" => "creation-etatlieux", "guard_name" => "web", "display_name" => "Créer état des lieux"),
      array("name" => "modification-etatlieux", "guard_name" => "web", "display_name" => "Modifier état des lieux"),
      array("name" => "suppression-etatlieux", "guard_name" => "web", "display_name" => "Supprimer état des lieux"),

      // Permissions état d'un appartement
      array("name" => "liste-etatappartement", "guard_name" => "web", "display_name" => "Voir liste etatappartement"),
      array("name" => "creation-etatappartement", "guard_name" => "web", "display_name" => "Créer etatappartement"),
      array("name" => "modification-etatappartement", "guard_name" => "web", "display_name" => "Modifier etatappartement"),
      array("name" => "suppression-etatappartement", "guard_name" => "web", "display_name" => "Supprimer etatappartement"),

      // Permissions état d'une assurance
      array("name" => "liste-etatassurance", "guard_name" => "web", "display_name" => "Voir liste etatassurances"),
      array("name" => "creation-etatassurance", "guard_name" => "web", "display_name" => "Créer etatassurance"),
      array("name" => "modification-etatassurance", "guard_name" => "web", "display_name" => "Modifier etatassurance"),
      array("name" => "suppression-etatassurance", "guard_name" => "web", "display_name" => "Supprimer etatassurance"),

      // Permissions état d'un contrat
      array("name" => "liste-etatcontrat", "guard_name" => "web", "display_name" => "Voir liste etatcontrats"),
      array("name" => "creation-etatcontrat", "guard_name" => "web", "display_name" => "Créer etatcontrat"),
      array("name" => "modification-etatcontrat", "guard_name" => "web", "display_name" => "Modifier etatcontrat"),
      array("name" => "suppression-etatcontrat", "guard_name" => "web", "display_name" => "Supprimer etatcontrat"),

      // Permissions intervention appartement
      array("name" => "liste-interventionappartement", "guard_name" => "web", "display_name" => "Voir liste intervention appartement"),
      array("name" => "creation-interventionappartement", "guard_name" => "web", "display_name" => "Créer intervention appartement"),
      array("name" => "modification-interventionappartement", "guard_name" => "web", "display_name" => "Modifier intervention appartement"),
      array("name" => "suppression-interventionappartement", "guard_name" => "web", "display_name" => "Supprimer intervention appartement"),

      // Permissions intervention immeuble
      array("name" => "liste-interventionimmeuble", "guard_name" => "web", "display_name" => "Voir liste intervention immeuble"),
      array("name" => "creation-interventionimmeuble", "guard_name" => "web", "display_name" => "Créer intervention immeuble"),
      array("name" => "modification-interventionimmeuble", "guard_name" => "web", "display_name" => "Modifier intervention immeuble"),
      array("name" => "suppression-interventionimmeuble", "guard_name" => "web", "display_name" => "Supprimer intervention immeuble"),

      // Permissions rapport d'intervention
      array("name" => "liste-rapportintervention", "guard_name" => "web", "display_name" => "Voir liste rapport d'intervention"),
      array("name" => "creation-rapportintervention", "guard_name" => "web", "display_name" => "Créer rapport d'intervention"),
      array("name" => "modification-rapportintervention", "guard_name" => "web", "display_name" => "Modifier rapport d'intervention"),
      array("name" => "suppression-rapportintervention", "guard_name" => "web", "display_name" => "Supprimer rapport d'intervention"),

      // Permissions locataire
      array("name" => "liste-locataire", "guard_name" => "web", "display_name" => "Voir liste locataire"),
      array("name" => "creation-locataire", "guard_name" => "web", "display_name" => "Créer locataire"),
      array("name" => "modification-locataire", "guard_name" => "web", "display_name" => "Modifier locataire"),
      array("name" => "suppression-locataire", "guard_name" => "web", "display_name" => "Supprimer locataire"),

      // Permissions contrat prestation
      array("name" => "liste-contratprestation", "guard_name" => "web", "display_name" => "Voir liste contrats prestation"),
      array("name" => "creation-contratprestation", "guard_name" => "web", "display_name" => "Créer contrat prestation"),
      array("name" => "modification-contratprestation", "guard_name" => "web", "display_name" => "Modifier contrat prestation"),
      array("name" => "suppression-contratprestation", "guard_name" => "web", "display_name" => "Supprimer contrat prestation"),

      // Permissions delaipreavi
      array("name" => "liste-delaipreavi", "guard_name" => "web", "display_name" => "Voir liste delais preavi"),
      array("name" => "creation-delaipreavi", "guard_name" => "web", "display_name" => "Créer delai preavi"),
      array("name" => "modification-delaipreavi", "guard_name" => "web", "display_name" => "Modifier delai preavi"),
      array("name" => "suppression-delaipreavi", "guard_name" => "web", "display_name" => "Supprimer delai preavi"),

      // Permissions equipementpiece
      array("name" => "liste-equipementpiece", "guard_name" => "web", "display_name" => "Voir liste equipementpieces"),
      array("name" => "creation-equipementpiece", "guard_name" => "web", "display_name" => "Créer equipementpiece"),
      array("name" => "modification-equipementpiece", "guard_name" => "web", "display_name" => "Modifier equipementpiece"),
      array("name" => "suppression-equipementpiece", "guard_name" => "web", "display_name" => "Supprimer equipementpiece"),

      // Permissions contactprestataire
      array("name" => "liste-contactprestataire", "guard_name" => "web", "display_name" => "Voir liste contact prestataire"),
      array("name" => "creation-contactprestataire", "guard_name" => "web", "display_name" => "Créer contact prestataire"),
      array("name" => "modification-contactprestataire", "guard_name" => "web", "display_name" => "Modifier contact prestataire"),
      array("name" => "suppression-contactprestataire", "guard_name" => "web", "display_name" => "Supprimer contact prestataire"),

      // Permissions obligation administrative
      array("name" => "liste-obligationadministrative", "guard_name" => "web", "display_name" => "Voir liste obligation administrative"),
      array("name" => "creation-obligationadministrative", "guard_name" => "web", "display_name" => "Créer obligation administrative"),
      array("name" => "modification-obligationadministrative", "guard_name" => "web", "display_name" => "Modifier obligation administrative"),
      array("name" => "suppression-obligationadministrative", "guard_name" => "web", "display_name" => "Supprimer obligation administrative"),

      // Permissions facture
      array("name" => "liste-facture", "guard_name" => "web", "display_name" => "Voir liste factures"),
      array("name" => "creation-facture", "guard_name" => "web", "display_name" => "Créer facture"),
      array("name" => "modification-facture", "guard_name" => "web", "display_name" => "Modifier facture"),
      array("name" => "suppression-facture", "guard_name" => "web", "display_name" => "Supprimer facture"),

      // Permissions facture intervention
      array("name" => "liste-factureintervention", "guard_name" => "web", "display_name" => "Voir liste factures intervention"),
      array("name" => "creation-factureintervention", "guard_name" => "web", "display_name" => "Créer facture intervention"),
      array("name" => "modification-factureintervention", "guard_name" => "web", "display_name" => "Modifier facture intervention"),
      array("name" => "suppression-factureintervention", "guard_name" => "web", "display_name" => "Supprimer facture intervention"),

      // Permissions observation
      array("name" => "liste-observation", "guard_name" => "web", "display_name" => "Voir liste observations"),
      array("name" => "creation-observation", "guard_name" => "web", "display_name" => "Créer observation"),
      array("name" => "modification-observation", "guard_name" => "web", "display_name" => "Modifier observation"),
      array("name" => "suppression-observation", "guard_name" => "web", "display_name" => "Supprimer observation"),

      // Permissions fonction
      array("name" => "liste-fonction", "guard_name" => "web", "display_name" => "Voir liste fonctions"),
      array("name" => "creation-fonction", "guard_name" => "web", "display_name" => "Créer fonction"),
      array("name" => "modification-fonction", "guard_name" => "web", "display_name" => "Modifier fonction"),
      array("name" => "suppression-fonction", "guard_name" => "web", "display_name" => "Supprimer fonction"),

      // Permissions calendrier
      array("name" => "liste-calendrier", "guard_name" => "web", "display_name" => "Voir liste calendrier"),
      array("name" => "creation-calendrier", "guard_name" => "web", "display_name" => "Créer calendrier"),
      array("name" => "modification-calendrier", "guard_name" => "web", "display_name" => "Modifier calendrier"),
      array("name" => "suppression-calendrier", "guard_name" => "web", "display_name" => "Supprimer calendrier"),

      // Permissions frequencepaiementappartement
      array("name" => "liste-frequencepaiementappartement", "guard_name" => "web", "display_name" => "Voir liste frequencepaiementappartement"),
      array("name" => "creation-frequencepaiementappartement", "guard_name" => "web", "display_name" => "Créer frequencepaiementappartement"),
      array("name" => "modification-frequencepaiementappartement", "guard_name" => "web", "display_name" => "Modifier frequencepaiementappartement"),
      array("name" => "suppression-frequencepaiementappartement", "guard_name" => "web", "display_name" => "Supprimer frequencepaiementappartement"),

      // Permissions résiliation de bail
      array("name" => "liste-resiliationbail", "guard_name" => "web", "display_name" => "Voir liste résiliation de bail"),
      array("name" => "creation-resiliationbail", "guard_name" => "web", "display_name" => "Créer résiliation de bail"),
      array("name" => "modification-resiliationbail", "guard_name" => "web", "display_name" => "Modifier résiliation de bail"),
      array("name" => "suppression-resiliationbail", "guard_name" => "web", "display_name" => "Supprimer résiliation de bail"),

      // Permissions contrat
      array("name" => "liste-contrat", "guard_name" => "web", "display_name" => "Voir liste contrat"),
      array("name" => "creation-contrat", "guard_name" => "web", "display_name" => "Créer un contrat"),
      array("name" => "modification-contrat", "guard_name" => "web", "display_name" => "Modifier un contrat"),
      array("name" => "suppression-contrat", "guard_name" => "web", "display_name" => "Supprimer un contrat"),

      // Permissions locationvente
      array("name" => "liste-locationvente", "guard_name" => "web", "display_name" => "Voir liste contrat de location vente"),
      array("name" => "creation-locationvente", "guard_name" => "web", "display_name" => "Créer un contrat de location vente"),
      array("name" => "modification-locationvente", "guard_name" => "web", "display_name" => "Modifier un contrat de location vente"),
      array("name" => "suppression-locationvente", "guard_name" => "web", "display_name" => "Supprimer un contrat de location vente"),

      // Permissions paiementloyer
      array("name" => "liste-paiementloyer", "guard_name" => "web", "display_name" => "Voir liste des paiements loyers"),
      array("name" => "creation-paiementloyer", "guard_name" => "web", "display_name" => "Créer un paiement loyer"),
      array("name" => "modification-paiementloyer", "guard_name" => "web", "display_name" => "Modifier un paiement loyer"),
      array("name" => "suppression-paiementloyer", "guard_name" => "web", "display_name" => "Supprimer un paiement loyer"),

      // Permissions produitsutilise
      array("name" => "liste-produitsutilise", "guard_name" => "web", "display_name" => "Voir liste des produitsutilises"),
      array("name" => "creation-produitsutilise", "guard_name" => "web", "display_name" => "Créer un produitsutilise"),
      array("name" => "modification-produitsutilise", "guard_name" => "web", "display_name" => "Modifier un produitsutilise"),
      array("name" => "suppression-produitsutilise", "guard_name" => "web", "display_name" => "Supprimer un produitsutilise"),

      // Permissions finance immeuble
      array("name" => "liste-financeimmeuble", "guard_name" => "web", "display_name" => "Voir liste finance immeuble"),
      array("name" => "creation-financeimmeuble", "guard_name" => "web", "display_name" => "Créer finance immeuble"),
      array("name" => "modification-financeimmeuble", "guard_name" => "web", "display_name" => "Modifier finance immeuble"),
      array("name" => "suppression-financeimmeuble", "guard_name" => "web", "display_name" => "Supprimer finance immeuble"),

      // Permissions demanderesiliation
      array("name" => "liste-demanderesiliation", "guard_name" => "web", "display_name" => "Voir liste des demandes de resiliations"),
      array("name" => "creation-demanderesiliation", "guard_name" => "web", "display_name" => "Créer une demande de resiliation"),
      array("name" => "modification-demanderesiliation", "guard_name" => "web", "display_name" => "Modifier une demande de resiliation"),
      array("name" => "suppression-demanderesiliation", "guard_name" => "web", "display_name" => "Supprimer une demande de resiliation"),

      // Permissions demandeintervention
      array("name" => "liste-demandeintervention", "guard_name" => "web", "display_name" => "Voir liste des demandes d'interventions"),
      array("name" => "creation-demandeintervention", "guard_name" => "web", "display_name" => "Créer une demande d'intervention"),
      array("name" => "modification-demandeintervention", "guard_name" => "web", "display_name" => "Modifier une demande d'intervention"),
      array("name" => "suppression-demandeintervention", "guard_name" => "web", "display_name" => "Supprimer une demande d'intervention"),

      // Permissions intervention
      array("name" => "liste-intervention", "guard_name" => "web", "display_name" => "Voir liste des interventions"),
      array("name" => "creation-intervention", "guard_name" => "web", "display_name" => "Créer une intervention"),
      array("name" => "modification-intervention", "guard_name" => "web", "display_name" => "Modifier une intervention"),
      array("name" => "suppression-intervention", "guard_name" => "web", "display_name" => "Supprimer une intervention"),

      // Permissions assurance
      array("name" => "liste-assurance", "guard_name" => "web", "display_name" => "Voir liste des assurances"),
      array("name" => "creation-assurance", "guard_name" => "web", "display_name" => "Créer une assurances"),
      array("name" => "modification-assurance", "guard_name" => "web", "display_name" => "Modifier une assurances"),
      array("name" => "suppression-assurance", "guard_name" => "web", "display_name" => "Supprimer une assurances"),

      // Permissions assureur
      array("name" => "liste-assureur", "guard_name" => "web", "display_name" => "Voir liste des assureurs"),
      array("name" => "creation-assureur", "guard_name" => "web", "display_name" => "Créer un assureur"),
      array("name" => "modification-assureur", "guard_name" => "web", "display_name" => "Modifier un assureur"),
      array("name" => "suppression-assureur", "guard_name" => "web", "display_name" => "Supprimer un assureur"),

      // Permissions facture
      array("name" => "liste-facture", "guard_name" => "web", "display_name" => "Voir liste des factures"),
      array("name" => "creation-facture", "guard_name" => "web", "display_name" => "Créer une facture "),
      array("name" => "modification-facture", "guard_name" => "web", "display_name" => "Modifier une facture"),
      array("name" => "suppression-facture", "guard_name" => "web", "display_name" => "Supprimer une facture"),

      // Permissions categorieintervention
      array("name" => "liste-categorieintervention", "guard_name" => "web", "display_name" => "Voir liste des categories d'intervention"),
      array("name" => "creation-categorieintervention", "guard_name" => "web", "display_name" => "Créer une categorie d'intervention "),
      array("name" => "modification-categorieintervention", "guard_name" => "web", "display_name" => "Modifier une categorie d'intervention"),
      array("name" => "suppression-categorieintervention", "guard_name" => "web", "display_name" => "Supprimer une categorie d'intervention"),

      // Permissions categorieprestataire
      array("name" => "liste-categorieprestataire", "guard_name" => "web", "display_name" => "Voir liste des categories de prestataire"),
      array("name" => "creation-categorieprestataire", "guard_name" => "web", "display_name" => "Créer une categorie prestataire "),
      array("name" => "modification-categorieprestataire", "guard_name" => "web", "display_name" => "Modifier une categorie prestataire"),
      array("name" => "suppression-categorieprestataire", "guard_name" => "web", "display_name" => "Supprimer une categorie prestataire"),

      // Permissions horaire
      array("name" => "liste-horaire", "guard_name" => "web", "display_name" => "Voir liste des horaires"),
      array("name" => "creation-horaire", "guard_name" => "web", "display_name" => "Créer un horaire "),
      array("name" => "modification-horaire", "guard_name" => "web", "display_name" => "Modifier un horaire"),
      array("name" => "suppression-horaire", "guard_name" => "web", "display_name" => "Supprimer un horaire"),

      // Permissions finance appartement
      array("name" => "liste-financeappartement", "guard_name" => "web", "display_name" => "Voir liste finance appartement"),
      array("name" => "creation-financeappartement", "guard_name" => "web", "display_name" => "Créer finance appartement"),
      array("name" => "modification-financeappartement", "guard_name" => "web", "display_name" => "Modifier finance appartement"),
      array("name" => "suppression-financeappartement", "guard_name" => "web", "display_name" => "Supprimer finance appartement"),

      // Permissions caution
      array("name" => "liste-caution", "guard_name" => "web", "display_name" => "Voir liste des cautions"),
      array("name" => "creation-caution", "guard_name" => "web", "display_name" => "Créer une caution"),
      array("name" => "modification-caution", "guard_name" => "web", "display_name" => "Modifier une caution"),
      array("name" => "suppression-caution", "guard_name" => "web", "display_name" => "Supprimer une caution"),

      // Permissions situation compte client
      array("name" => "liste-situationcompteclient", "guard_name" => "web", "display_name" => "Voir liste situation compte client"),
      array("name" => "creation-situationcompteclient", "guard_name" => "web", "display_name" => "Créer situation compte client"),
      array("name" => "modification-situationcompteclient", "guard_name" => "web", "display_name" => "Modifier situation compte client"),
      array("name" => "suppression-situationcompteclient", "guard_name" => "web", "display_name" => "Supprimer situation compte client"),

      // Permissions versement loyer
      array("name" => "liste-versementloyer", "guard_name" => "web", "display_name" => "Voir liste versement loyer"),
      array("name" => "creation-versementloyer", "guard_name" => "web", "display_name" => "Créer versement loyer"),
      array("name" => "modification-versementloyer", "guard_name" => "web", "display_name" => "Modifier versement loyer"),
      array("name" => "suppression-versementloyer", "guard_name" => "web", "display_name" => "Supprimer versement loyer"),

      // Permissions versement charge de copropriete
      array("name" => "liste-versementchargecopropriete", "guard_name" => "web", "display_name" => "Voir liste versement charge de copropriété"),
      array("name" => "creation-versementchargecopropriete", "guard_name" => "web", "display_name" => "Créer versement charge de copropriété"),
      array("name" => "modification-versementchargecopropriete", "guard_name" => "web", "display_name" => "Modifier versement charge de copropriété"),
      array("name" => "suppression-versementchargecopropriete", "guard_name" => "web", "display_name" => "Supprimer versement charge de copropriété"),

      // Permissions message
      array("name" => "liste-message", "guard_name" => "web", "display_name" => "Voir liste message"),
      array("name" => "creation-message", "guard_name" => "web", "display_name" => "Créer message"),
      array("name" => "modification-message", "guard_name" => "web", "display_name" => "Modifier message"),
      array("name" => "suppression-message", "guard_name" => "web", "display_name" => "Supprimer message"),

      // Permissions annonce
      array("name" => "liste-annonce", "guard_name" => "web", "display_name" => "Voir liste annonce"),
      array("name" => "creation-annonce", "guard_name" => "web", "display_name" => "Créer annonce"),
      array("name" => "modification-annonce", "guard_name" => "web", "display_name" => "Modifier annonce"),
      array("name" => "suppression-annonce", "guard_name" => "web", "display_name" => "Supprimer annonce"),

      // Permissions membreequipegestion
      array("name" => "liste-membreequipegestion", "guard_name" => "web", "display_name" => "Voir liste membreequipegestion"),
      array("name" => "creation-membreequipegestion", "guard_name" => "web", "display_name" => "Créer membreequipegestion"),
      array("name" => "modification-membreequipegestion", "guard_name" => "web", "display_name" => "Modifier membreequipegestion"),
      array("name" => "suppression-membreequipegestion", "guard_name" => "web", "display_name" => "Supprimer membreequipegestion"),

      // Permissions questionnaire
      array("name" => "liste-questionnaire", "guard_name" => "web", "display_name" => "Voir liste des questionnaire"),
      array("name" => "creation-questionnaire", "guard_name" => "web", "display_name" => "Créer un questionnaire"),
      array("name" => "modification-questionnaire", "guard_name" => "web", "display_name" => "Modifier un questionnaire"),
      array("name" => "suppression-questionnaire", "guard_name" => "web", "display_name" => "Supprimer un questionnaire"),

      // Permissions gardien
      array("name" => "liste-gardien", "guard_name" => "web", "display_name" => "Voir liste des gardiens"),
      array("name" => "creation-gardien", "guard_name" => "web", "display_name" => "Créer un gardiens"),
      array("name" => "modification-gardien", "guard_name" => "web", "display_name" => "Modifier un gardiens"),
      array("name" => "suppression-gardien", "guard_name" => "web", "display_name" => "Supprimer un gardiens"),

      // Permissions niveauappartement
      array("name" => "liste-niveauappartement", "guard_name" => "web", "display_name" => "Voir liste des niveauappartements"),
      array("name" => "creation-niveauappartement", "guard_name" => "web", "display_name" => "Créer un niveauappartement"),
      array("name" => "modification-niveauappartement", "guard_name" => "web", "display_name" => "Modifier un niveauappartement"),
      array("name" => "suppression-niveauappartement", "guard_name" => "web", "display_name" => "Supprimer un niveauappartement"),

      // Permissions structureimmeuble
      array("name" => "liste-structureimmeuble", "guard_name" => "web", "display_name" => "Voir liste des structureimmeubles"),
      array("name" => "creation-structureimmeuble", "guard_name" => "web", "display_name" => "Créer une structureimmeuble"),
      array("name" => "modification-structureimmeuble", "guard_name" => "web", "display_name" => "Modifier une structureimmeuble"),
      array("name" => "suppression-structureimmeuble", "guard_name" => "web", "display_name" => "Supprimer une structureimmeuble"),

      // Permissions type questionnaire
      array("name" => "liste-typequestionnaire", "guard_name" => "web", "display_name" => "Voir liste des typequestionnaires"),
      array("name" => "creation-typequestionnaire", "guard_name" => "web", "display_name" => "Créer un typequestionnaire"),
      array("name" => "modification-typequestionnaire", "guard_name" => "web", "display_name" => "Modifier un typequestionnaire"),
      array("name" => "suppression-typequestionnaire", "guard_name" => "web", "display_name" => "Supprimer un typequestionnaire"),

      // Permissions equipegestion
      array("name" => "liste-equipegestion", "guard_name" => "web", "display_name" => "Voir liste des equipes de gestion"),
      array("name" => "creation-equipegestion", "guard_name" => "web", "display_name" => "Créer une equipe de gestion"),
      array("name" => "modification-equipegestion", "guard_name" => "web", "display_name" => "Modifier une equipe de gestion"),
      array("name" => "suppression-equipegestion", "guard_name" => "web", "display_name" => "Supprimer une equipe de gestion"),

      // Permissions questionnaire-satisfaction
      array("name" => "liste-questionnairesatisfaction", "guard_name" => "web", "display_name" => "Voir liste questionnaire de satisfaction"),
      array("name" => "creation-questionnairesatisfaction", "guard_name" => "web", "display_name" => "Créer questionnaire de satisfaction"),
      array("name" => "modification-questionnairesatisfaction", "guard_name" => "web", "display_name" => "Modifier questionnaire de satisfaction"),
      array("name" => "suppression-questionnairesatisfaction", "guard_name" => "web", "display_name" => "Supprimer questionnaire de satisfaction"),

      // Permissions travaux immeuble initié par le résident
      array("name" => "liste-travauximmresident", "guard_name" => "web", "display_name" => "Voir liste travaux immeuble initié par le résident"),
      array("name" => "creation-travauximmresident", "guard_name" => "web", "display_name" => "Créer travaux immeuble initié par le résident"),
      array("name" => "modification-travauximmresident", "guard_name" => "web", "display_name" => "Modifier travaux immeuble initié par le résident"),
      array("name" => "suppression-travauximmresident", "guard_name" => "web", "display_name" => "Supprimer travaux immeuble initié par le résident"),

      // Permissions travaux immeuble initié par le gestionnaire
      array("name" => "liste-travauximmgestionnaire", "guard_name" => "web", "display_name" => "Voir liste travaux immeuble initié par le gestionnaire"),
      array("name" => "creation-travauximmgestionnaire", "guard_name" => "web", "display_name" => "Créer travaux immeuble initié par le gestionnaire"),
      array("name" => "modification-travauximmgestionnaire", "guard_name" => "web", "display_name" => "Modifier travaux immeuble initié par le gestionnaire"),
      array("name" => "suppression-travauximmgestionnaire", "guard_name" => "web", "display_name" => "Supprimer travaux immeuble initié par le gestionnaire"),

      // Permissions travaux appartement initié par le résident
      array("name" => "liste-travauxappresident", "guard_name" => "web", "display_name" => "Voir liste travaux appartement initié par le résident"),
      array("name" => "creation-travauxappresident", "guard_name" => "web", "display_name" => "Créer travaux appartement initié par le résident"),
      array("name" => "modification-travauxappresident", "guard_name" => "web", "display_name" => "Modifier travaux appartement initié par le résident"),
      array("name" => "suppression-travauxappresident", "guard_name" => "web", "display_name" => "Supprimer travaux appartement initié par le résident"),

      // Permissions travaux appartement initié par le gestionnaire
      array("name" => "liste-travauxappgestionnaire", "guard_name" => "web", "display_name" => "Voir liste travaux appartement initié par le gestionnaire"),
      array("name" => "creation-travauxappgestionnaire", "guard_name" => "web", "display_name" => "Créer travaux appartement initié par le gestionnaire"),
      array("name" => "modification-travauxappgestionnaire", "guard_name" => "web", "display_name" => "Modifier travaux appartement initié par le gestionnaire"),
      array("name" => "suppression-travauxappgestionnaire", "guard_name" => "web", "display_name" => "Supprimer travaux appartement initié par le gestionnaire"),

      // Permissions répertoire résident
      array("name" => "liste-repertoireresident", "guard_name" => "web", "display_name" => "Voir liste répertoire résident"),
      array("name" => "creation-repertoireresident", "guard_name" => "web", "display_name" => "Créer répertoire résident"),
      array("name" => "modification-repertoireresident", "guard_name" => "web", "display_name" => "Modifier répertoire résident"),
      array("name" => "suppression-repertoireresident", "guard_name" => "web", "display_name" => "Supprimer répertoire résident"),

      // Permissions répertoire propriétaire
      array("name" => "liste-repertoireproprietaire", "guard_name" => "web", "display_name" => "Voir liste répertoire propriétaire"),
      array("name" => "creation-repertoireproprietaire", "guard_name" => "web", "display_name" => "Créer répertoire propriétaire"),
      array("name" => "modification-repertoireproprietaire", "guard_name" => "web", "display_name" => "Modifier répertoire propriétaire"),
      array("name" => "suppression-repertoireproprietaire", "guard_name" => "web", "display_name" => "Supprimer répertoire propriétaire"),

      // Permissions répertoire prestataire
      array("name" => "liste-repertoireprestataire", "guard_name" => "web", "display_name" => "Voir liste répertoire prestataire"),
      array("name" => "creation-repertoireprestataire", "guard_name" => "web", "display_name" => "Créer répertoire prestataire"),
      array("name" => "modification-repertoireprestataire", "guard_name" => "web", "display_name" => "Modifier répertoire prestataire"),
      array("name" => "suppression-repertoireprestataire", "guard_name" => "web", "display_name" => "Supprimer répertoire prestataire"),

      // Permissions répertoire employé
      array("name" => "liste-repertoireemploye", "guard_name" => "web", "display_name" => "Voir liste répertoire employé"),
      array("name" => "creation-repertoireemploye", "guard_name" => "web", "display_name" => "Créer répertoire employé"),
      array("name" => "modification-repertoireemploye", "guard_name" => "web", "display_name" => "Modifier répertoire employé"),
      array("name" => "suppression-repertoireemploye", "guard_name" => "web", "display_name" => "Supprimer répertoire employé"),

      // Permissions Module
      array("name" => "module-outil-admin", "guard_name" => "web", "display_name" => "Voir le module OUTILS ADMIN"),
      array("name" => "module-parametrage", "guard_name" => "web", "display_name" => "Voir le module paramètrage"),
      array("name" => "module-gestion-immeuble", "guard_name" => "web", "display_name" => "Voir le module gestion immeuble"),
      array("name" => "module-gestion-bien", "guard_name" => "web", "display_name" => "Voir le module gestion bien"),
      array("name" => "module-gestion-acteur", "guard_name" => "web", "display_name" => "Voir le module gestion acteur"),

      array("name" => "module-gestion-location", "guard_name" => "web", "display_name" => "Voir le module gestion location"),
      array("name" => "module-administration", "guard_name" => "web", "display_name" => "Voir le module gestion administration"),
      array("name" => "module-finance", "guard_name" => "web", "display_name" => "Voir le module finance"),
      array("name" => "module-finance-proprietaire", "guard_name" => "web", "display_name" => "Voir le module finance propriétaire"),
      array("name" => "module-communication", "guard_name" => "web", "display_name" => "Voir le module communication"),
      array("name" => "module-suivi-travaux", "guard_name" => "web", "display_name" => "Voir le module suivi travaux"),
      array("name" => "module-suivi-travaux-immeuble", "guard_name" => "web", "display_name" => "Voir le module suivi travaux immeuble"),
      array("name" => "module-suivi-travaux-appartement", "guard_name" => "web", "display_name" => "Voir le module suivi travaux appartement"),
      array("name" => "module-repertoire", "guard_name" => "web", "display_name" => "Voir le module répertoire"),
      array("name" => "valider-signature", "guard_name" => "web", "display_name" => "Valider signature"),


      // Permissions typeapportponctuel
      array("name" => "liste-typeapportponctuel", "guard_name" => "web", "display_name" => "Voir liste des type d'apport ponctuel"),
      array("name" => "creation-typeapportponctuel", "guard_name" => "web", "display_name" => "Ajouter un type d'apport ponctuel"),
      array("name" => "modification-typeapportponctuel", "guard_name" => "web", "display_name" => "Modifier un type d'apport ponctuel"),
      array("name" => "suppression-typeapportponctuel", "guard_name" => "web", "display_name" => "Supprimer un type d'apport ponctuel"),

      // Permissions apportponctuel
      array("name" => "liste-apportponctuel", "guard_name" => "web", "display_name" => "Voir liste des apports ponctuel"),
      array("name" => "creation-apportponctuel", "guard_name" => "web", "display_name" => "Ajouter un nouvel apport ponctuel"),
      array("name" => "modification-apportponctuel", "guard_name" => "web", "display_name" => "Modifier un apport ponctuel"),
      array("name" => "suppression-apportponctuel", "guard_name" => "web", "display_name" => "Supprimer un apport ponctuel"),

      // Permissions mandatgerance (Contrat proprietaire)
      array("name" => "liste-contratproprietaire", "guard_name" => "web", "display_name" => "Voir liste des contrats prorietaire"),
      array("name" => "creation-contratproprietaire", "guard_name" => "web", "display_name" => "Ajouter un contrat prorietaire"),
      array("name" => "modification-contratproprietaire", "guard_name" => "web", "display_name" => "Modifier un contrat prorietaire"),
      array("name" => "suppression-contratproprietaire", "guard_name" => "web", "display_name" => "Supprimer un contrat prorietaire"),

    ];

    foreach ($permissions as $permission) {
      $activer = 1;
      if (isset($permission['activer'])) {
        $activer = $permission['activer'];
      }
      $newitem = Permission::where('name', $permission['name'])->first();
      if (!isset($newitem)) {
        $newitem = new Permission();
      }
      $newitem->name = $permission['name'];
      $newitem->guard_name = $permission['guard_name'];
      $newitem->display_name = $permission['display_name'];
      $newitem->save();
    }
  }
}
