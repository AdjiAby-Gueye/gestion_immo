<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call([
      PermissionTableSeeder::class,
      InitialisationBaseSeeder::class,
      RoleSeeder::class,
      UserSeeder::class,
      EtatappartementSeeder::class,
      AnnonceSeeder::class,
      // AppartementSeeder::class,
      // AssuranceSeeder::class,
      // AssureurSeeder::class,
      // CategorieinterventionSeeder::class,
      // CategorieprestationSeeder::class,
      // CategorieprestataireSeeder::class,
      // ConstituantpieceSeeder::class,
      // ContactprestataireSeeder::class,
      // ContratprestationSeeder::class,
      // ContratSeeder::class,
      // DelaipreaviSeeder::class,
      // HoraireSeeder::class,
      // DemandeinterventionSeeder::class,
      // DemanderesiliationSeeder::class,
      // DocumentSeeder::class,
      EquipegestionSeeder::class,
      EquipementpieceSeeder::class,
      // EtatassuranceSeeder::class,
      // EtatlieuSeeder::class,
      // FactureSeeder::class,
      FonctionSeeder::class,
      // FactureSeeder::class,
      FrequencepaiementappartementSeeder::class,
      // ImmeubleSeeder::class,
      // InterventionSeeder::class,
      // LocataireSeeder::class,
      EntiteSeeder::class,
      MembreequipegestionSeeder::class,
      // MessageSeeder::class,
      // ObligationadministrativeSeeder::class,
      ObservationsSeeder::class,
      // PaiementloyerSeeder::class,
      PieceappartementSeeder::class,
      // PrestataireSeeder::class,
      // ProduitsutiliseSeeder::class,
      // ProprietaireSeeder::class,
      // QuestionnairesatisfactionSeeder::class,
      // RapportinterventionSeeder::class,
      TypeappartementSeeder::class,
      TypecontratSeeder::class,
      TypedocumentSeeder::class,
      TypefactureSeeder::class,
      // TypeinterventionSeeder::class,
      TypelocataireSeeder::class,
      // TypeobligationadministrativeSeeder::class,
      TypepieceSeeder::class,
      TyperenouvellementSeeder::class,
      TypequestionnaireSeeder::class,
      QuestionnaireSeeder::class,
      PeriodiciteSeeder::class,
      // AppartementSeeder::class,
      // AppartementSeeder::class,
      InitialisationBaseSeeder::class,
      EntiteSeeder::class,
      ModepaiementSeeder::class,
      // ConstituantpieceSeeder::class,
      // NiveauAppartementSeeder::class,
      PeriodeSeeder::class
    ]);
  }
}
