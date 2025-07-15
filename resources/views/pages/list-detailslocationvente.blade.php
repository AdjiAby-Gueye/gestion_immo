@if (auth()->user()->can('liste-locationvente') ||
        auth()->user()->can('modification-locationvente') ||
        auth()->user()->can('suppression-locationvente'))
    <div class="grid grid-cols-12 gap-6 subcontent classe_generale">
        <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-5">
                <div class="intro-y pr-1 mt-1">
                    <div class="box p-2 item-tabs-produit">
                        <div class="pos__tabs nav-tabs justify-center flex">
                            <a data-toggle="tab" data-target="#infos" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center active">Infos générale</a>
                            <a data-toggle="tab" data-target="#appartement" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Villa & réservataire</a>
                            <a data-toggle="tab" data-target="#facture" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center"> Echéances & paiements</a>
                            <a data-toggle="tab" data-target="#etatlieux" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Etat des lieux</a>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <input type="hidden" value="@{{ dataPage['locationventes'][0]['id'] }}" id="contrat_id_detailslocationvente">
                    <input type="hidden" value="@{{ dataPage['locationventes'][0]['locataire']['id'] }}" id="locataire_id_detailscontrat">
                    <input type="hidden" value="@{{ dataPage['locationventes'][0]['periodicite']['id'] }}" id="periodicite_id_detailscontrat">
                    <div class="tab-content__pane active" id="infos">
                        {{-- infos start --}}
                        <div class="card mt-5">
                            <div class="card-body">
                                {{-- <h5 class="card-title text-center">INFOS</h5> --}}
                                <div class="intro-y grid grid-cols-12 ">
                                    <div class="mr-2 col-span-6 sm:col-span-6">

                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Descrptif du contrat : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['descriptif'] }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Fréquence de paiement : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['periodicite'] ? dataPage['locationventes'][0]['periodicite']['designation'] : "-------------------" }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Prix villa : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['prixvilla'] }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Maturité (en année) : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['maturite'] }} ans
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date d'enregistrement : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['dateenregistrement_format'] }}
                                            </div>
                                        </div>
                                        <hr>

                                        {{-- <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                                        <div class="col-span-6 sm:col-span-6 ">
                                                            <strong><h5>Délai de préavis : </h5> </strong>
                                                        </div>
                                                        <div class="col-span-6 sm:col-span-6">
                                                            @{{ dataPage['locationventes'][0]['delaipreavi'] ? dataPage['locationventes'][0]['delaipreavi']['designation'] : "-----------------------------" }}
                                                        </div>
                                                    </div>
                                                    <hr> --}}
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Apport initial : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['apportinitial_format'] }} F CFA
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Quote part amortissement : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['codepartamortissemnt_format'] ? dataPage['locationventes'][0]['codepartamortissemnt_format'] : "........................................." }} F CFA
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Durée de location vente (mois) : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['dureelocationvente'] }} mois
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Total montant versé : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <span class="bg-success rounded p-1">@{{ dataPage['locationventes'][0]['ridwan_montant_verse'] }} F
                                                    CFA</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Solde du compte client : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <span class="bg-warning rounded p-1">@{{ dataPage['locationventes'][0]['locataire']['soldeclient_format'] }} F
                                                    CFA</span>
                                                    <button class="menu-item btn border-0 bg-danger text-white fsize-14 ml-20" ng-click="reinitcompteclient(dataPage['locationventes'][0]['locataire']['id'])"
                                                                title="reinitialiser compte client">
                                                                <span class="fas fa-sync-alt"></span>
                                                    </button>
                                                    {{-- <button class="menu-item btn border-0 bg-danger text-white fsize-14 ml-20" ng-click="desactivecompteclient(dataPage['locationventes'][0]['locataire']['id'])"
                                                                title="desactiver compte client">
                                                                <span class="fas fa-sync-alt"></span>
                                                    </button>
                                                    <button class="menu-item btn border-0 bg-danger text-white fsize-14 ml-20" ng-click="activecompteclient(dataPage['locationventes'][0]['locataire']['id'])"
                                                                title="activer compte client">
                                                                <span class="fas fa-sync-alt"></span>
                                                    </button> --}}
                                            </div>
                                        </div>
                                        <hr>

                                    </div>
                                    <div class="col-span-6 sm:col-span-6">


                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date début du contrat : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['datedebutcontrat_format'] }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date de fin de contrat : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['dateecheanceformat'] }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date de remise des clé : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['dateremiseclesformat'] }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Clause pénale : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['clausepenale'] ? dataPage['locationventes'][0]['clausepenale'] : "................." }} %
                                            </div>
                                        </div>
                                        <hr>

                                        {{-- <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                                        <div class="col-span-6 sm:col-span-6 ">
                                                            <strong><h5>Acompte villa: </h5> </strong>
                                                        </div>
                                                        <div class="col-span-6 sm:col-span-6">
                                                            @{{ dataPage['locationventes'][0]['acompteinitial_format'] }} F FCA
                                                        </div>
                                                    </div> --}}
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Pourcentage de l'acompte (%) : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['acompte_percent'] }} %
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Apport ponctuel : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['apportiponctuel'] ? dataPage['locationventes'][0]['apportiponctuel'] + " F CFA" : "......................" }}
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Frais de location: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['fraislocative_format'] ? dataPage['locationventes'][0]['fraislocative_format'] : "........................." }} F CFA
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Frais de gestion : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['fraisdegestion_format'] ? dataPage['locationventes'][0]['fraisdegestion_format'] : "..........................." }} F CFA
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Total montant restant : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <span class="bg-danger rounded p-1">@{{ dataPage['locationventes'][0]['ridwan_montant_restant'] }} F CFA</span>

                                            </div>
                                        </div>
                                        <hr>

                                    </div>

                                </div>



                                <div class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                    <div class="col-span-6 sm:col-span-6 ">
                                        <strong>
                                            <h5>Loyer mensuel : </h5>
                                        </strong>
                                    </div>
                                    <div class="col-span-6 sm:col-span-6">
                                        @{{ dataPage['locationventes'][0]['montantloyerformat'] }} F CFA
                                    </div>
                                </div>
                                <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                    <div class="col-span-3 sm:col-span-3">
                                        <a type="button"
                                            href="generate-pdf-locationventeById/@{{ dataPage['locationventes'][0]['id'] }}"
                                            target="_blank" class="btn btn-primay button w-70" title="pdf du contrat"
                                            style="background-color: #31D2F2 ">
                                            voir le document du contrat
                                            <span class="fas fa-file-pdf"></span>
                                        </a>
                                    </div>
                                    <div class="col-span-5 sm:col-span-7 ">

                                    </div>
                                    @if (auth()->user()->can('valider-contrat-locationvente'))
                                        <div class="col-span-2 sm:col-span-2"
                                            ng-if="dataPage['locationventes'][0]['etat'] == 1">
                                            <button type="button"
                                                ng-click="senddirectormailridwan(dataPage['locationventes'][0]['id'],dataPage['locationventes'][0]['locataire']['id'])"
                                                class="button w-40 btn btn-success text-white shadow"
                                                style="background-color: #157347 ">
                                                Valider le contrat <i class="fa fa-thumbs-up"></i></button>
                                        </div>
                                    @endif

                                    {{-- <div class="col-span-2 sm:col-span-2" ng-if="dataPage['locationventes'][0]['est_soumis'] == 1 && !dataPage['locationventes'][0]['signaturedirecteur']">
                                                    <button type="button"   ng-click="annulerSoumissionContratRidwan(dataPage['locationventes'][0]['id'])"  class="button w-40 btn btn-success text-white shadow" style="background-color: red ">Annulé la soumission</button>
                                                </div> --}}
                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="tab-content__pane" id="appartement">
                        <div class="intro-y grid grid-cols-12 mt-5 gap-6">
                            <div class=" col-span-7 sm:col-span-7">
                                <div class="card  ">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Villa</h5>
                                        <div class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Lot : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                N° @{{ dataPage['locationventes'][0]['appartement'] ? dataPage['locationventes'][0]['appartement']['lot'] : "--------" }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Ilot : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                N° @{{ (dataPage['locationventes'][0]['appartement'] && dataPage['locationventes'][0]['appartement']['ilot']) ? dataPage['locationventes'][0]['appartement']['ilot']['numero']: "-----------------------" }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Adresse de l'ilot : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ (dataPage['locationventes'][0]['appartement'] && dataPage['locationventes'][0]['appartement']['ilot']) ? dataPage['locationventes'][0]['appartement']['ilot']['adresse']: "-----------------------" }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            {{-- <div class="col-span-6 sm:col-span-6 ">
                                                <strong><h5>Propriétaire  : </h5> </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ (dataPage['locationventes'][0]['appartement'] && dataPage['locationventes'][0]['appartement']['proprietaire']) ? dataPage['locationventes'][0]['appartement']['proprietaire']['prenom']: "-----------------------" }}
                                                @{{ (dataPage['locationventes'][0]['appartement'] && dataPage['locationventes'][0]['appartement']['proprietaire']) ? dataPage['locationventes'][0]['appartement']['proprietaire']['nom']: "-----------------------" }}
                                            </div> --}}
                                        </div>
                                        <hr>

                                    </div>
                                </div>
                            </div>

                            <div class=" col-span-5 sm:col-span-5">
                                <div class="card ">
                                    <div class="card-body" ng-if="dataPage['locationventes'][0]['locataire']">
                                        <h5 class="card-title text-center">RESERVATAIRE</h5>
                                        {{-- locataire physique start --}}
                                        <div ng-if="dataPage['locationventes'][0]['locataire']['nom']"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Prénom & nom: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['locataire']['prenom'] }}
                                                @{{ dataPage['locationventes'][0]['locataire']['nom'] }}

                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['locationventes'][0]['locataire']['nom']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Email: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6 text-wrap">
                                                @{{ dataPage['locationventes'][0]['locataire']['email'] }}

                                            </div>
                                        </div>
                                        <hr>

                                        <div ng-if="dataPage['locationventes'][0]['locataire']['nom']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Téléphone portable: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['locataire']['telephoneportable1'] }}

                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['locationventes'][0]['locataire']['date_naissance']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date de naissance: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['locataire']['date_naissance'] }}

                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['locationventes'][0]['locataire']['pays_naissance'] && dataPage['locationventes'][0]['locataire']['lieux_naissance']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Pays & lieux de naissance: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['locataire']['pays_naissance'] }}
                                                @{{ dataPage['locationventes'][0]['locataire']['lieux_naissance'] }}

                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['locationventes'][0]['locataire']['mandataire']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Mandataire: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['locataire']['mandataire'] }}

                                            </div>
                                        </div>
                                        <hr>

                                        {{-- locataire physique end --}}

                                        {{-- locataire moral start --}}
                                        <div ng-if="dataPage['locationventes'][0]['locataire']['nomentreprise']"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Nom entreprise: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['locataire']['nomentreprise'] }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['locationventes'][0]['locataire']['nomentreprise']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Email: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['locataire']['email'] ?? "-----" }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['locationventes'][0]['locataire']['nomentreprise']"
                                            class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Adresse de l'entreprise: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['locataire']['adresseentreprise'] ?? "-------" }}
                                            </div>
                                        </div>
                                        <hr>

                                        {{-- locataire moral end --}}


                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="tab-content__pane" id="facture">

                        <div class="intro-y grid grid-cols-12 mt-5 gap-6">
                            <div class="col-span-8 sm:col-span-8">

                                <div class="grid grid-cols-12 gap-3">
                                    {{-- ng-if="dataPage['locationventes'][0]['show_echeance']"  --}}
                                    {{-- <div class="col-span-3 sm:col-span-3" >
                                    <a target="_blank"  href="generate-pdf-appelecheance/@{{ dataPage['locationventes'][0].id }}"

                                     type="button"  class="btn btn-primay bg-danger text-white button w-70"
                                       title="pdf échéance" >
                                        Appel d'échéance
                                        <span class="fas fa-paperclip"></span>
                                    </a>

                                </div> --}}
                                    <div class="col-span-3 sm:col-span-3">

                                        <button
                                            ng-click="showModalAdd('avisecheance',{is_file_excel:false, title:null, fromUpdate: false},dataPage['locationventes'][0].id)"
                                            type="button" class="btn btn-primay bg-primary text-white button w-70"
                                            title="pdf du contrat" style="background-color: #31D2F2 ">
                                            Ajouter un avis
                                            <span class="fas fa-plus"></span>
                                        </button>
                                    </div>
                                    <div class="col-span-3 sm:col-span-3">

                                        <button
                                            ng-click="showModalAdd('avisecheance',{is_file_excel:true, title:'avis echeance'})"
                                            type="button" class="btn  text-white button w-70" title="pdf du contrat"
                                            style="background-color: green ">
                                            Import excel
                                            <span class="fas fa-file-excel"></span>
                                        </button>
                                    </div>
                                    <div ng-if="!dataPage['factureacomptes'][0] && !dataPage['factureacomptes'][0].id"
                                        class="col-span-3 sm:col-span-3">

                                        <button
                                            ng-click="showModalAdd('factureacompte',{is_file_excel:false, title:null, fromUpdate: false},dataPage['locationventes'][0].id)"
                                            type="button" class="btn btn-primay bg-primary text-white button w-70"
                                            title="pdf du contrat" style="background-color: #31D2F2 ">
                                            Facture acompte
                                            <span class="fas fa-plus"></span>
                                        </button>
                                    </div>

                                    {{--  ng-if="dataPage['locationventes'][0]['show_echeance']" --}}
                                    <div ng-if="dataPage['locationventes'][0]['derniere_facture_echeance']"
                                        class="col-span-3 sm:col-span-3">

                                        <div class="dropdown-toggle notification notification--bullet cursor-pointer">
                                            <button type="button"
                                                ng-click="showModalAdd('inbox',optionals={is_file_excel:false, title:null, fromUpdate: false},dataPage['locationventes'][0].id)"
                                                class="btn btn-danger bg-warning text-white button w-70"
                                                title="relance paimement">

                                                Relance paiement

                                                <span class="fas fa-paper-plane"></span>
                                            </button>
                                            <div class="item-notif-number mt-1 mr-2">@{{ dataPage['locationventes'][0]['nombre_relance_echeance'] ? dataPage['locationventes'][0]['nombre_relance_echeance'] : "0" }}</div>
                                        </div>
                                    </div>
                                    <div class="col-span-3 sm:col-span-3">

                                        <button
                                            ng-click="showModalAdd('apportponctuel',{is_file_excel:false, title:null, fromUpdate: false},dataPage['locationventes'][0].id)"
                                            type="button" class="btn  text-white button w-70" title="apport ponctuel"
                                            style="background-color: green ">
                                            Apport ponctuel
                                            <span class="fas fa-plus"></span>
                                        </button>
                                    </div>


                                    {{-- <div class="col-span-3 sm:col-span-3 ">
                                    <button  ng-click="senddirectormail(dataPage['locationventes'][0]['id'],dataPage['locationventes'][0]['locataire']['id'])" type="button"  class="btn btn-success bg-success button text-white  w-70"  title="relance paimement" >
                                        Relance paiement
                                        <span class="fas fa-paper-plane"></span>
                                    </button>
                                </div> --}}


                                </div>
                                <div class="grig grig-cols-12 p-2">


                                    <div class="col-span-12 sm:col-span-12">

                                        <table class="table table-report ">
                                            <b>Toutes les factures d'échéances
                                                <span
                                                    class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{ paginations['avisecheance'].totalItems }}</span>
                                            </b>
                                            <thead>
                                                {{-- <th class="whitespace-no-wrap text-center">Type de facture</th> --}}
                                                <th class="whitespace-no-wrap text-center">Objet / période</th>
                                                <th class="whitespace-no-wrap text-center">Date</th>
                                                <th class="whitespace-no-wrap text-center">Reglé</th>
                                                {{-- <th class="whitespace-no-wrap text-center">Historique paiement</th> --}}
                                                <th class="whitespace-no-wrap text-center">Action</th>

                                            </thead>


                                            <tbody>
                                                {{-- <a type="button"
                                            href="generate-pdf-one-facturelocation/@{{ item.id }}"
                                            target="_blank"
                                            class="menu-item btn border-0 bg-danger text-white fsize-16"
                                            title="pdf">
                                            <span class="fas fa-file-pdf mt-2"></span>
                                        </a> --}}
                                                {{-- <button class="menu-item btn border-0 bg-info text-white fsize-16"

                                            ng-click="showModalAdd('paiementloyer',{is_file_excel:false, title:null, fromUpdate: false}, item.id )"
                                            title="Effectuer les paiements">
                                            <span class="fas fa-money-bill"></span>
                                            ng-click="redirectPdf('generate-pdf-one-facturelocation',item.id)"
                                        </button> --}}
                                                <tr class="intro-x" ng-repeat="item in dataPage['avisecheances']"
                                                    style="cursor: pointer;">
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center text-primary"
                                                            style="text-decoration: underline">
                                                            <a target="_blank"
                                                                href="generate-pdf-avisecheance/@{{ item.id }}">
                                                                @{{ item.objet }}
                                                            </a>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            @{{ item.date_fr }}
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <div class="font-medium whitespace-no-wrap text-center ">
                                                                <span ng-if="item.est_activer == 1"
                                                                    ng-click="showModalAdd('paiementecheance',{is_file_excel:false, title:null, fromUpdate: false}, item.id )"
                                                                    class="px-2 rounded-full @{{ item.etat_badge }} text-white">@{{ item.montant_total }}
                                                                    F CFA</span>
                                                                <span ng-if="item.est_activer == 2"
                                                                    ng-click="showModalDetail('avisecheance',item.id)"
                                                                    class="px-2 rounded-full @{{ item.etat_badge }} text-white">@{{ item.montant_total }}
                                                                    F CFA </span>
                                                                <span ng-if="item.est_activer == 3"
                                                                    class="px-2 rounded-full @{{ item.etat_badge }} text-white">@{{ item.montant_total }}
                                                                    F CFA </span>
                                                                <span ng-if="item.est_activer == 4"
                                                                    ng-click="showModalAdd('paiementecheance',{is_file_excel:false, title:null, fromUpdate: false}, item.id )"
                                                                    class="px-2 rounded-full @{{ item.etat_badge }} text-white">@{{ item.montant_total }}
                                                                    F CFA </span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    {{-- <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <div class="font-medium whitespace-no-wrap text-center ">
                                                                ---
                                                            </div>
                                                        </div>
                                                    </td> --}}
                                                    {{-- <td>
                                                    <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="redirectPdf(item.justificatif_paiement)"  title="details">
                                                        <span class="fas fa-info"></span>
                                                    </button>
                                                    <button ng-if="item.est_activer == 1" class="menu-item btn border-0 bg-danger text-white fsize-16 " style="margin-top: -2%"
                                                    ng-click="deleteElement('avisecheance',item.id)"
                                                        title="Effectuer les paiements">
                                                        <span class="fas fa-trash-alt"></span>
                                                    </button>
                                                    <button ng-if="item.est_activer == 2" class="menu-item btn border-0 bg-warning text-white fsize-16 " style="margin-top: -2%"
                                                    ng-click="showModalAdd('annulationpaiementavis',{
                                                        is_file_excel: false,
                                                        title: null,
                                                        fromUpdate: false
                                                    },item.id)"
                                                        title="Annuler paiement">
                                                        <span class="fas fa-xmark"></span>
                                                    </button>
                                                    <button ng-if="item.est_activer == 3" class="menu-item btn border-0 bg-info text-white fsize-16 " style="margin-top: -2%"
                                                    ng-click="annulerPaiementEcheance(item.id,2,'Voulez-vous réactiver le paiement ?')"
                                                        title="Réactiver le paiement">
                                                        <span class="fas fa-check"></span>
                                                    </button>
                                                </td> --}}
                                                    <td class="table-report__action w-56">
                                                        <nav class="menu-leftToRight uk-flex text-center">
                                                            <input type="checkbox" href="#" class="menu-open"
                                                                name="menu-open"
                                                                id="menu-open1us-@{{ item.id }}">
                                                            <label class="menu-open-button bg-white"
                                                                for="menu-open1us-@{{ item.id }}">
                                                                <span
                                                                    class="hamburger bg-template-1 hamburger-1"></span>
                                                                <span
                                                                    class="hamburger bg-template-1 hamburger-2"></span>
                                                                <span
                                                                    class="hamburger bg-template-1 hamburger-3"></span>
                                                            </label>
                                                            <button
                                                                class="menu-item btn border-0 bg-info text-white fsize-16"
                                                                ng-if="item.justificatif_paiement"
                                                                ng-click="redirectPdf(item.justificatif_paiement)"
                                                                title="Justificatif de paiement">
                                                                <span class="fas fa-eye"></span>
                                                            </button>
                                                            <button
                                                                class="menu-item btn border-0 bg-danger text-white fsize-16 "
                                                                ng-if="item.est_activer == 1"
                                                                ng-click="deleteElement('avisecheance',item.id)"
                                                                title="supprimer le paiement">
                                                                <span class="fas fa-trash-alt"></span>
                                                            </button>
                                                            {{-- <button ng-if="item.est_activer == 2"
                                                                class="menu-item btn border-0 bg-warning text-white fsize-16 "
                                                                ng-click="showModalAdd('annulationpaiementavis',{
                                                            is_file_excel: false,
                                                            title: null,
                                                            fromUpdate: false
                                                        },item.id)"
                                                                title="Annuler paiement">
                                                                <span class="fa fa-ban"  aria-hidden="true"></span>
                                                            </button> --}}
                                                            {{-- <button ng-if="item.est_activer == 3"
                                                                class="menu-item btn border-0 bg-info text-white fsize-16 "
                                                                ng-click="annulerPaiementEcheance(item.id,2,'Voulez-vous réactiver le paiement ?')"
                                                                title="Réactiver le paiement">
                                                                <span class="fas fa-check"></span>
                                                            </button> --}}
                                                            {{-- <button
                                                                class="menu-item btn border-0 bg-success text-white fsize-16"
                                                                ng-if="item.id_paiement"
                                                                ng-click="redirectPdf('paiementecheance/recu/'+item.id_paiement)"
                                                                title="Reçu de paiement">
                                                                <span class="fas fa-eye"></span>
                                                            </button> --}}
                                                            <button
                                                                class="menu-item btn border-0 bg-success text-white fsize-16"
                                                                ng-if="item.id_paiement"
                                                                ng-click="showModalDetail('avisecheance',item.id)"
                                                                title="Historique de paiement">
                                                                <span class="fas fa-dollar-sign"></span>
                                                            </button>
                                                            {{-- <button
                                                                class="menu-item btn border-0 bg-success text-white fsize-16"
                                                                ng-if="item.id_paiement"
                                                                ng-click="showModalUpdate('paiementecheance', item.id_paiement )"
                                                                title="Modifier paiement">
                                                                <span class="fa fa-pencil"></span>
                                                            </button> --}}
                                                            <button
                                                                class="menu-item btn border-0 bg-danger text-white fsize-16"
                                                                ng-if="!item.signature"
                                                                ng-click="redirectPdf('signature-avis/'+item.id)"
                                                                title="Signez l'avis">
                                                                <span class="fas fa-check"></span>
                                                            </button>
                                                        </nav>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>



                                    </div>
                                    <!-- PAGINATION -->
                                    <div class="col-span-12 grid grid-cols-12 gap-4 ">
                                        <div class="col-span-12 sm:col-span-12 md:col-span-3">
                                            <span>Affichage par</span>
                                            <select class="w-20 input box mt-1"
                                                ng-model="paginations['avisecheance'].entryLimit"
                                                ng-change="pageChanged('avisecheance', optionals = {
                                justWriteUrl: null,
                                option: null,
                                saveStateOfFilters: false,
                            },dataPage['locationventes'][0].id)">
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                                            <nav aria-label="Page navigation">
                                                <ul class="uk-pagination float-right" uib-pagination
                                                    total-items="paginations['avisecheance'].totalItems"
                                                    ng-model="paginations['avisecheance'].currentPage"
                                                    max-size="paginations['avisecheance'].maxSize"
                                                    items-per-page="paginations['avisecheance'].entryLimit"
                                                    ng-change="pageChanged('avisecheance', optionals = {
                                    justWriteUrl: null,
                                    option: null,
                                    saveStateOfFilters: false,
                                },dataPage['locationventes'][0].id)"
                                                    previous-text="‹" next-text="›" first-text="«" last-text="»"
                                                    boundary-link-numbers="true" rotate="false"></ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <!-- /PAGINATION -->
                                </div>
                            </div>
                            <div class="col-span-4 sm:col-span-4">

                                <div class="card ">
                                    <div class="card-body">
                                        <h5 class="card-title text-center bolder"><b>Echéance en cours</b></h5>
                                        <div ng-if="dataPage['locationventes'][0]['derniere_facture_echeance'] && dataPage['locationventes'][0]['derniere_facture_echeance'].id"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date facture: </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['derniere_facture_echeance']['date'] }}
                                            </div>
                                        </div>
                                        <div ng-if="dataPage['locationventes'][0]['derniere_facture_echeance'] && dataPage['locationventes'][0]['derniere_facture_echeance'].id"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Objet facture : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['derniere_facture_echeance']['objet'] }}
                                            </div>
                                        </div>
                                        <div ng-if="dataPage['locationventes'][0]['derniere_facture_echeance'] && dataPage['locationventes'][0]['derniere_facture_echeance'].id"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Quote part amortissement : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['derniere_facture_echeance']['amortissement'] }} F CFA
                                            </div>
                                        </div>
                                        <div ng-if="dataPage['locationventes'][0]['derniere_facture_echeance'] && dataPage['locationventes'][0]['derniere_facture_echeance'].id"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Frais de gestion : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['derniere_facture_echeance']['fraisgestion'] }} F CFA

                                            </div>
                                        </div>
                                        <div ng-if="dataPage['locationventes'][0]['derniere_facture_echeance'] && dataPage['locationventes'][0]['derniere_facture_echeance'].id"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Frais de location : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['locationventes'][0]['derniere_facture_echeance']['fraisdelocation'] }} F CFA

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <div class="card ">
                                    <div class="card-body">
                                        <h5 class="card-title text-center bolder"><b>Facture d'acompte </b>

                                            <button ng-if="dataPage['factureacomptes'][0]['est_activer'] == 1"
                                                class="menu-item btn border-0 bg-danger text-white fsize-16 "
                                                ng-click="deleteElement('factureacompte',dataPage['factureacomptes'][0].id)"
                                                title="Supprimer">
                                                <span class="fas fa-trash-alt"></span>
                                            </button>
                                        </h5>
                                        <div ng-if="dataPage['factureacomptes'][0].id"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date facture: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['factureacomptes'][0]['datefacture_format'] }}
                                            </div>
                                        </div>

                                        <div ng-if="dataPage['factureacomptes'][0].id && dataPage['factureacomptes'][0].commentaire"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date facture: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['factureacomptes'][0]['commentaire'] }}
                                            </div>
                                        </div>
                                        <div ng-if="dataPage['factureacomptes'][0].id && dataPage['factureacomptes'][0]['montant_format']"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Montant net : </h5>
                                                </strong>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                <span ng-if="dataPage['factureacomptes'][0].est_activer == 1"
                                                    class="bg-danger rounded p-1">
                                                    @{{ dataPage['factureacomptes'][0]['montant_format'] }} F CFA
                                                </span>
                                                <span ng-if="dataPage['factureacomptes'][0].est_activer == 2"
                                                    class="bg-success rounded p-1">
                                                    @{{ dataPage['factureacomptes'][0]['montant_format'] }} F CFA
                                                </span>

                                            </div>
                                        </div>
                                        <div ng-if="dataPage['factureacomptes'][0] && dataPage['factureacomptes'][0].id"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6">
                                                <a type="button" href="generate-pdf-acompte/@{{ dataPage['factureacomptes'][0].id }}"
                                                    target="_blank" class=" btn  bg-primary button w-70  text-white"
                                                    title="acompte ">
                                                    Voir la facture
                                                    <span class="fas fa-file-pdf mt-2"></span>
                                                </a>
                                            </div>

                                            <div ng-if="dataPage['factureacomptes'][0] && dataPage['factureacomptes'][0].id && dataPage['factureacomptes'][0].est_activer == 1"
                                                class="col-span-6 sm:col-span-6">
                                                <button type="button"
                                                    ng-click="showModalAddPaiementEcheance('paiementecheance',{is_file_excel:false, title:null, fromUpdate: false}, dataPage['factureacomptes'][0].id )"
                                                    class=" btn  bg-success button w-70  text-white"
                                                    title="reglement ">
                                                    régler la facture
                                                    <span class="fas fa-money-bill mt-2"></span>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>




                            </div>

                        </div>
                    </div>
                    {{-- </div> --}}
                    {{-- facture et paiement start --}}
                    <div class="tab-content__pane" id="etatlieux">
                        <div class="intro-y grid grid-cols-12 mt-5 gap-6">


                            <div class=" col-span-6 sm:col-span-6">
                                <div class="card ">
                                    <div class="card-body">
                                        <h5 class="card-title text-center bolder"><b>Etat des lieux d'entrée</b></h5>
                                        <div ng-if="dataPage['locationventes'][0]['etatlieu_entree'] && dataPage['locationventes'][0]['etatlieu_entree']['id']"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">

                                            <div class="col-span-6 sm:col-span-6">
                                                <a type="button"
                                                    href="generate-pdf-rapport-etatlieu/@{{ dataPage['locationventes'][0]['etatlieu_entree'].id }}"
                                                    target="_blank" class=" btn  bg-primary button w-70  text-white"
                                                    title="etat lieu d'entree">
                                                    Voir le document
                                                    <span class="fas fa-file-pdf mt-2"></span>
                                                </a>

                                            </div>
                                        </div>
                                        <div ng-if="!dataPage['locationventes'][0]['etatlieu_entree']"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-7 ">
                                                <em>Aucun etat des lieux d'entre</em>
                                                <button
                                                    ng-click="showModalAdd('etatlieu',{is_file_excel:false, title:null},dataPage['locationventes'][0]['appartement'].id)"
                                                    type="button"
                                                    class="btn btn-success bg-primary button text-white  w-70"
                                                    title="Etat des lieux d'entreé">
                                                    Ajouter
                                                    <span class="fas fa-plus"></span>
                                                </button>
                                            </div>

                                        </div>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-span-6 sm:col-span-6">
                                <div class="card ">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"><b>Etat des lieux de sortie </b></h5>
                                        <div class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between"
                                            ng-if="dataPage['locationventes'][0]['etatlieu_sortie'] && dataPage['locationventes'][0]['etatlieu_sortie'].id">
                                            <div class="col-span-9 sm:col-span-9">
                                                <a type="button"
                                                    href="generate-pdf-rapport-etatlieu/@{{ dataPage['locationventes'][0]['etatlieu_sortie'].id }}"
                                                    target="_blank" class=" btn  bg-warning button w-70  text-white"
                                                    title="appel caution">
                                                    Voir le document
                                                    <span class="fas fa-file-pdf mt-2"></span>
                                                </a>

                                            </div>

                                            <hr>
                                        </div>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between"
                                            ng-if="!dataPage['locationventes'][0]['etatlieu_sortie']">

                                            <div class="col-span-12 sm:col-span-12 d-flex justify-content-between">
                                                <em>Aucun etat des lieux de sortie</em>
                                                <button
                                                    ng-if="dataPage['locationventes'][0]['etatlieu_sortie'] && dataPage['locationventes'][0]['etatlieu_sortie']['id']"
                                                    ng-click="showModalAdd('etatlieu',{is_file_excel:false, title:null},dataPage['locationventes'][0]['appartement'].id)"
                                                    type="button"
                                                    class="btn btn-warning bg-warning button text-white  w-70"
                                                    title="Etat des lieux de sortie">
                                                    Ajouter
                                                    <span class="fas fa-plus"></span>
                                                </button>
                                            </div>

                                            <hr>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    {{-- facture et paiement end --}}

                </div>

            </div>
        </div>
    </div>

@endif
