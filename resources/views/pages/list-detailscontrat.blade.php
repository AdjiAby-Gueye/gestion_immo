@if (auth()->user()->can('liste-contrat') ||
auth()->user()->can('modification-contrat') ||
auth()->user()->can('suppression-contrat'))
<div class="grid grid-cols-12 gap-6 subcontent classe_generale">
    <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-5">
            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infos" href="javascript:void(0);" class="flex-1 py-2 rounded-md text-center active">Infos générale</a>
                        <a data-toggle="tab" data-target="#appartement" href="javascript:void(0);" class="flex-1 py-2 rounded-md text-center">Appartement & locataire</a>
                        <a data-toggle="tab" data-target="#facture" href="javascript:void(0);" class="flex-1 py-2 rounded-md text-center">Factures & paiements loyers</a>
                        <!-- <a data-toggle="tab" data-target="#avenants" href="javascript:void(0);" class="flex-1 py-2 rounded-md text-center">Avenants</a> -->
                        <a ng-if="dataPage['demanderesiliations'][0].contrat.id !=null" data-toggle="tab" data-target="#resiliation" href="javascript:void(0);" class="flex-1 py-2 rounded-md text-center">Résiliation</a>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <input type="hidden" value="@{{ dataPage['contrats'][0]['id'] }}" id="contrat_id_detailscontrat">
                <input type="hidden" value="@{{ dataPage['contrats'][0]['locataire']['id'] }}" id="locataire_id_detailscontrat">
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
                                            @{{ dataPage['contrats'][0]['descriptif'] }}
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
                                            @{{ dataPage['contrats'][0]['periodicite'] ? dataPage['contrats'][0]['periodicite']['designation'] : "-------------------" }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Taux de révision(%) : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['tauxrevision'] }}%
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Fréquence de révision (en année) : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['frequencerevision'] }} ans
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
                                            @{{ dataPage['contrats'][0]['dateenregistrement_format'] }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Délai de préavis : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['delaipreavi'] ? dataPage['contrats'][0]['delaipreavi']['designation'] : "-----------------------------" }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Montant loyer base : </h5>
                                            </strong>

                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['montantloyerbaseformat'] }} F CFA
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
                                            @{{ dataPage['contrats'][0]['datedebutcontrat_format'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Date du premier paiement : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['datepremierpaiement_format'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Date de renouvellement : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['daterenouvellement_format'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Type de renouvellement : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['typerenouvellement'] ? dataPage['contrats'][0]['typerenouvellement']['designation'] : "-----------------------------" }}
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Date de renouvellement : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['daterenouvellement_format'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Montant loyer base : </h5>
                                            </strong>

                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['montantloyerbaseformat'] }} F CFA
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Montant loyer base : </h5>
                                            </strong>

                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['montantloyerbaseformat'] }} F CFA
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                            </div>
                            <div class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                <div class="col-span-6 sm:col-span-6 ">
                                    <strong>
                                        <h5>Montant loyer total : </h5>
                                    </strong>

                                </div>
                                <div class="col-span-6 sm:col-span-6">
                                    @{{ dataPage['contrats'][0]['total_loyer_format'] }} F CFA
                                </div>
                            </div>
                            <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                <div class="col-span-3 sm:col-span-3">
                                    <a type="button" href="generate-pdf-contratById/@{{ dataPage['contrats'][0]['id'] }}" target="_blank" class="btn btn-primay button w-70" title="pdf du contrat" style="background-color: #31D2F2 ">
                                        voir le document du contrat
                                        <span class="fas fa-file-pdf"></span>
                                    </a>
                                </div>
                                <div class="col-span-7 sm:col-span-7 ">

                                </div>
                                <div class="col-span-2 sm:col-span-2" ng-if="!dataPage['contrats'][0]['signaturedirecteur']">
                                    <button type="button" ng-click="senddirectormail(dataPage['contrats'][0]['id'],dataPage['contrats'][0]['locataire']['id'])" class="button w-30 btn btn-success text-white shadow" style="background-color: #157347 ">Soumettre le contrat <i class="fa fa-thumbs-up"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <input type="hidden" id="appartement_detailscontrat" value="@{{dataPage['contrats'][0]['appartement']['id']}}">
                <input type="hidden" id="contrat_detailscontrat" value="@{{dataPage['contrats'][0]['id']}}">
                <input type="hidden" id="locataire_detailscontrat" value="@{{dataPage['contrats'][0]['locataire']['id']}}">

                <div class="tab-content__pane" id="appartement">
                    <div class="intro-y grid grid-cols-12 mt-5 gap-6">
                        <div class=" col-span-7 sm:col-span-7">
                            <div class="card  ">
                                <div class="card-body">
                                    <h5 class="card-title text-center">APPARTEMENT</h5>
                                    <div class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Nom de l'appartement : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['appartement'] ? dataPage['contrats'][0]['appartement']['nom'] : "--------" }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Nom de l'immeuble : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ (dataPage['contrats'][0]['appartement'] && dataPage['contrats'][0]['appartement']['immeuble']) ? dataPage['contrats'][0]['appartement']['immeuble']['nom']: "-----------------------" }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Adresse de l'appartement : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ (dataPage['contrats'][0]['appartement'] && dataPage['contrats'][0]['appartement']['immeuble']) ? dataPage['contrats'][0]['appartement']['immeuble']['adresse']: "-----------------------" }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Propriétaire : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ (dataPage['contrats'][0]['appartement'] && dataPage['contrats'][0]['appartement']['proprietaire']) ? dataPage['contrats'][0]['appartement']['proprietaire']['prenom']: "-----------------------" }}
                                            @{{ (dataPage['contrats'][0]['appartement'] && dataPage['contrats'][0]['appartement']['proprietaire']) ? dataPage['contrats'][0]['appartement']['proprietaire']['nom']: "-----------------------" }}
                                        </div>
                                    </div>
                                    <hr>

                                </div>
                            </div>
                        </div>

                        <div class=" col-span-5 sm:col-span-5">
                            <div class="card ">
                                <div class="card-body" ng-if="dataPage['contrats'][0]['locataire']">
                                    <h5 class="card-title text-center">LOCATAIRE</h5>
                                    {{-- locataire physique start --}}
                                    <div ng-if="dataPage['contrats'][0]['locataire']['nom']" class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Prénom & nom: </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['locataire']['prenom'] }}
                                            @{{ dataPage['contrats'][0]['locataire']['nom'] }}

                                        </div>
                                    </div>
                                    <hr>
                                    <div ng-if="dataPage['contrats'][0]['locataire']['nom']" class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Email: </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6 text-wrap">
                                            @{{ dataPage['contrats'][0]['locataire']['email'] }}

                                        </div>
                                    </div>
                                    <hr>

                                    <div ng-if="dataPage['contrats'][0]['locataire']['nom']" class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Téléphone portable: </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['locataire']['telephoneportable1'] }}

                                        </div>
                                    </div>
                                    {{-- <hr> --}}
                                    {{-- locataire physique end --}}

                                    {{-- locataire moral start --}}
                                    <div ng-if="dataPage['contrats'][0]['locataire']['nomentreprise']" class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Nom entreprise: </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['locataire']['nomentreprise'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div ng-if="dataPage['contrats'][0]['locataire']['nomentreprise']" class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Email: </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['locataire']['email'] ?? "-----" }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div ng-if="dataPage['contrats'][0]['locataire']['nomentreprise']" class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Adresse de l'entreprise: </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['contrats'][0]['locataire']['adresseentreprise'] ?? "-------" }}
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

                    <div class="intro-y grid grid-cols-12 mt-5 gap-4 ">
                        <div class="col-span-8 sm:col-span-8">

                            <div class="grid grid-cols-12 gap-3">
                                {{-- <div class="col-span-3 sm:col-span-3">
                                        <a target="_blank" href="generate-pdf-appelloyer/@{{ dataPage['contrats'][0].id }}"
                                type="button" class="btn btn-primay bg-danger text-white button w-70"
                                title="pdf du contrat">
                                Voir l'appel loyer
                                <span class="fas fa-paperclip"></span>
                                </a>
                            </div> --}}
                            <div class="col-span-3 sm:col-span-3">
                                <button ng-if="infosUserConnected && infosUserConnected.roles[0] && infosUserConnected.roles[0].name != 'resident'" ng-click="showModalAdd('facturelocation',{is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0]['locataire'].id)" type="button" class="btn btn-primay bg-primary text-white button w-70" title="pdf du contrat" style="background-color: #31D2F2 ">
                                    Ajouter facture
                                    <span class="fas fa-plus"></span>
                                </button>
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                <button ng-if="infosUserConnected && infosUserConnected.roles[0] && infosUserConnected.roles[0].name != 'resident'" ng-click="showModalAdd('factureeaux',{is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0]['locataire'].id)" type="button" class="btn btn-primay bg-primary text-white button w-70" title="pdf du contrat" style="background-color: rgb(6, 4, 103) ">
                                    Facture d'eaux
                                    <span class="fas fa-plus"></span>
                                </button>
                            </div>

                            <!-- <div class="col-span-3 sm:col-span-3">

                                <div class="dropdown-toggle notification notification--bullet cursor-pointer">
                                    <button type="button" ng-if="infosUserConnected && infosUserConnected.roles[0] && infosUserConnected.roles[0].name != 'resident'" ng-click="showModalAdd('inbox',optionals={is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0].id)" class="btn btn-danger bg-warning text-white button w-70" title="relance paimement">

                                        Relance paiement

                                        <span class="fas fa-paper-plane"></span>
                                    </button>
                                    <div class="item-notif-number mt-1 mr-2">@{{ dataPage['contrats'][0]['nombre_relance_loyer'] ? dataPage['contrats'][0]['nombre_relance_loyer'] : "0" }}</div>
                                </div>
                            </div> -->
                        </div>
                        <div class="grig grig-cols-12 p-2">


                            <div class="col-span-12 sm:col-span-12">

                                <table class="table table-report ">
                                    <b>Toutes les factures</b>
                                    <thead>
                                        <th class="whitespace-no-wrap text-center">Type de facture</th>
                                        <th class="whitespace-no-wrap text-center">Date</th>
                                        <th class="whitespace-no-wrap text-center">Reglé</th>
                                        <th class="whitespace-no-wrap text-center">Actions</th>

                                    </thead>


                                    <tbody>

                                        <tr class="intro-x" style="cursor: pointer;" ng-repeat="itemfact in dataPage['contrats'][0]['factureeauxs']">
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center" style="text-decoration: underline">
                                                    <a target="_blank" href="generate-pdf-factureeaux/@{{ itemfact.id }}">
                                                        eaux
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ itemfact.finperiode }}
                                                </div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap text-center ">
                                                        <span ng-if="itemfact.is_paid == 0" ng-click="showModalAdd('paiementloyereaux',{is_file_excel:false, title:null, fromUpdate: false}, itemfact.id )" class="px-2 rounded-full @{{ itemfact.is_paid_badge }} text-white">@{{ itemfact.is_paid_text }}</span>
                                                        <span ng-if="itemfact.is_paid == 1" class="px-2 rounded-full @{{ itemfact.is_paid_badge }} text-white">@{{ itemfact.is_paid_text }}
                                                        </span>
                                                        <span ng-if="itemfact.is_paid == 3" class="px-2 rounded-full @{{ itemfact.is_paid_badge }} text-white">@{{ itemfact.is_paid_text }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-report__action w-56">
                                                <nav class="menu-leftToRight uk-flex text-center">
                                                    <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1us-@{{ itemfact.id }}">
                                                    <label class="menu-open-button bg-white" for="menu-open1us-@{{ itemfact.id }}">
                                                        <span class="hamburger bg-template-1 hamburger-1"></span>
                                                        <span class="hamburger bg-template-1 hamburger-2"></span>
                                                        <span class="hamburger bg-template-1 hamburger-3"></span>
                                                    </label>
                                                    <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-if="itemfact.justificatif_paiement" ng-click="redirectPdf(itemfact.justificatif_paiement)" title="Justificatif de paiement">
                                                        <span class="fas fa-eye"></span>
                                                    </button>
                                                    <button ng-if="itemfact.is_paid == 0" class="menu-item btn border-0 bg-danger text-white fsize-16 " ng-click="deleteElement('factureeaux',itemfact.id)" title="Supprimer la facture">
                                                        <span class="fas fa-trash-alt"></span>
                                                    </button>
                                                    <button ng-if="itemfact.is_paid == 1" class="menu-item btn border-0 bg-warning text-white fsize-16 " ng-click="showModalAdd('annulationpaiementloyer',{
                                                                    is_file_excel: false,
                                                                    title: null,
                                                                    fromUpdate: false
                                                                    },itemfact.paiement_id)" title="Annuler paiement">
                                                        <span class="fas fa-xmark"></span>
                                                    </button>
                                                    <button ng-if="itemfact.is_paid == 3" class="menu-item btn border-0 bg-info text-white fsize-16 " ng-click="traiterPaiementLoyer(itemfact.paiement_id,2,'Voulez-vous réactiver le paiement ?')" title="Réactiver le paiement">
                                                        <span class="fas fa-check"></span>
                                                    </button>
                                                </nav>
                                            </td>
                                        </tr>

                                        <tr class="intro-x" ng-repeat="item in dataPage['facturelocations']" style="cursor: pointer;">
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center text-primary" style="text-decoration: underline">
                                                    <a target="_blank" href="generate-pdf-one-facturelocation/@{{ item.id }}">
                                                        @{{ item.typefacture.designation }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.datefacture_format }}
                                                </div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap text-center ">
                                                        <span ng-if="item.is_paid == 0" ng-click="showModalAdd('paiementloyer',{is_file_excel:false, title:null, fromUpdate: false}, item.id,item.periodicite_id)" class="px-2 rounded-full @{{ item.is_paid_badge }} text-white">@{{ item.is_paid_text }}</span>
                                                        <span ng-if="item.is_paid == 1" class="px-2 rounded-full @{{ item.is_paid_badge }} text-white">@{{ item.is_paid_text }}
                                                        </span>
                                                        <span ng-if="item.is_paid == 3" class="px-2 rounded-full @{{ item.is_paid_badge }} text-white">@{{ item.is_paid_text }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-report__action w-56">
                                                <nav class="menu-leftToRight uk-flex text-center">
                                                    <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1us-@{{ item.id }}">
                                                    <label class="menu-open-button bg-white" for="menu-open1us-@{{ item.id }}">
                                                        <span class="hamburger bg-template-1 hamburger-1"></span>
                                                        <span class="hamburger bg-template-1 hamburger-2"></span>
                                                        <span class="hamburger bg-template-1 hamburger-3"></span>
                                                    </label>
                                                    <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-if="item.justificatif_paiement" ng-click="redirectPdf(item.justificatif_paiement)" title="Justificatif de paiement">
                                                        <span class="fas fa-eye"></span>
                                                    </button>
                                                    <button ng-if="item.is_paid == 0 && infosUserConnected.roles[0].name != 'resident' " class="menu-item btn border-0 bg-danger text-white fsize-16 " ng-click="deleteElement('facturelocation',item.id)" title="Supprimer la facture">
                                                        <span class="fas fa-trash-alt"></span>
                                                    </button>
                                                    <button ng-if="item.is_paid == 1 && infosUserConnected.roles[0].name != 'resident'" class="menu-item btn border-0 bg-warning text-white fsize-16 " ng-click="showModalAdd('annulationpaiementloyer',{
                                                                    is_file_excel: false,
                                                                    title: null,
                                                                    fromUpdate: false
                                                                    },item.paiement_id)" title="Annuler paiement">
                                                        <span class="fas fa-xmark"></span>
                                                    </button>
                                                    <button ng-if="item.is_paid == 3 && infosUserConnected.roles[0].name != 'resident'" class="menu-item btn border-0 bg-info text-white fsize-16 " ng-click="traiterPaiementLoyer(item.paiement_id,2,'Voulez-vous réactiver le paiement ?')" title="Réactiver le paiement">
                                                        <span class="fas fa-check"></span>
                                                    </button>
                                                    <a type="button" href="generate-pdf-one-facturelocation/@{{item.id}}" target="_blank" class="menu-item btn border-0 bg-danger text-white fsize-16" title="loyer">
                                                        <span class="fas fa-file-pdf mt-2"></span>
                                                    </a>
                                                    {{-- <a type="button" href="generate-pdf-loyerbbi/@{{item.id}}" target="_blank" class="menu-item btn border-0 bg-danger text-white fsize-16" title="loyer_bbi">
                                                    <span class="fas fa-file-pdf mt-2"></span>
                                                    </a> --}}
                                                    {{-- <a type="button" href="generate-pdf-quitancebbi/@{{item.id}}" target="_blank" class="menu-item btn border-0 bg-primary text-white fsize-16" title="quitance">
                                                    <span class="fas fa-file-pdf mt-2"></span>
                                                    </a> --}}
                                                    <a type="button" ng-if="item.paiement_id" href="paiementloyer/recu/@{{item.paiement_id}}" target="_blank" class="menu-item btn border-0 bg-primary text-white fsize-16" title="quitance">
                                                        <span class="fas fa-file-pdf mt-2"></span>
                                                    </a>
                                                </nav>
                                            </td>


                                        </tr>
                                    </tbody>
                                </table>



                            </div>
                        </div>
                    </div>
                    <div class="col-span-4 sm:col-span-4 ml-4">

                        <div class="card ">
                            <div class="card-body">
                                <h5 class="card-title text-center bolder"><b>Facture en cours</b></h5>
                                <div ng-if="dataPage['contrats'][0]['derniere_facture_loyer'] && dataPage['contrats'][0]['derniere_facture_loyer'].id" class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                    <div class="col-span-6 sm:col-span-6 ">
                                        <strong>
                                            <h5>Date facture: </h5>
                                        </strong>

                                    </div>
                                    <div class="col-span-6 sm:col-span-6">
                                        @{{ dataPage['contrats'][0]['derniere_facture_loyer']['datefacture'] }}
                                    </div>
                                </div>
                                <div ng-if="dataPage['contrats'][0]['derniere_facture_loyer'] && dataPage['contrats'][0]['derniere_facture_loyer'].id" class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                    <div class="col-span-6 sm:col-span-6 ">
                                        <strong>
                                            <h5>Objet facture : </h5>
                                        </strong>

                                    </div>
                                    <div class="col-span-6 sm:col-span-6">
                                        @{{ dataPage['contrats'][0]['derniere_facture_loyer']['objetfacture'] }}
                                    </div>
                                </div>
                                <div ng-if="dataPage['contrats'][0]['derniere_facture_loyer'] && dataPage['contrats'][0]['derniere_facture_loyer'].id" class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                    <div class="col-span-6 sm:col-span-6 ">
                                        <strong>
                                            <h5>Montant facture : </h5>
                                        </strong>

                                    </div>
                                    <div ng-if="dataPage['contrats'][0]['derniere_facture_loyer'] && dataPage['contrats'][0]['derniere_facture_loyer']['montant']" class="col-span-6 sm:col-span-6">
                                        @{{ dataPage['contrats'][0]['derniere_facture_loyer']['montant'] }}

                                        F CFA

                                    </div>
                                    <div ng-if="!dataPage['contrats'][0]['derniere_facture_loyer']['montant']" class="col-span-6 sm:col-span-6">

                                        @{{ dataPage['contrats'][0]['total_loyer_format'] }}



                                        F CFA

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card my-3 ">
                            <div class="card-body">
                                <h5 class="card-title text-center bolder"><b> Derniere Facture d'eaux en cours</b></h5>
                                <div ng-if="dataPage['contrats'][0]['derniere_facture_loyer'] && dataPage['contrats'][0]['derniere_facture_loyer'].id" class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                    <div class="col-span-6 sm:col-span-6 ">
                                        <strong>
                                            <h5>Date facture: </h5>
                                        </strong>
                                    </div>
                                    <div class="col-span-6 sm:col-span-6">
                                        @{{ dataPage['contrats'][0]['date_dernier_facture_eau']}}
                                    </div>
                                </div>

                                <div ng-if="dataPage['contrats'][0]['derniere_facture_loyer'] && dataPage['contrats'][0]['derniere_facture_loyer'].id" class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                    <div class="col-span-6 sm:col-span-6 ">
                                        <strong>
                                            <h5>Montant facture : </h5>
                                        </strong>

                                    </div>
                                    <div class="col-span-6 sm:col-span-6">
                                        @{{ dataPage['contrats'][0]['montant_dernier_facture_eau'] }} F CFA


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- </div> --}}



            {{-- avenants tab pane --}}
            <!-- <div class="tab-content__pane" id="avenants">
                <div class="intro-y grid grid-cols-12 mt-5 gap-6">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="intro-y block sm:flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5 uppercase">
                                Avenants du contrat
                                {{-- <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{paginations['contrat'].totalItems}}</span> --}}
                            </h2>
                            <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                                <div class="" id="basic-dropdown">
                                    <div class="preview flex justify-center">
                                        <div class="dropdown relative">
                                            <button class="dropdown-toggle button button box flex items-center text-gray-700" ng-if="infosUserConnected && infosUserConnected.roles[0] && infosUserConnected.roles[0].name != 'resident'" ng-click="showModalAdd('avenant',{is_file_excel:false, title:null},dataPage['contrats'][0].id)"> <i class="fa fa-plus mr-2"></i> Ajouter </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- table list avenanr --}}
                        <div class="overflow-table">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                                <table class="table table-report sm:mt-2">
                                    <thead>
                                        <tr>
                                            <th class="whitespace-no-wrap">Date de l'avenant</th>
                                            <th class="whitespace-no-wrap text-center">Loyer base</th>
                                            <th class="whitespace-no-wrap text-center">Montant des charges</th>
                                            <th class="whitespace-no-wrap text-center">Loyer tom</th>
                                            <th class="whitespace-no-wrap text-center">Montant du loyer </th>
                                            <th class="whitespace-no-wrap text-center">Etat de l'avenant</th>
                                            <th class="text-center whitespace-no-wrap">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataPage['avenants']">
                                            <td>
                                                <div class="font-medium whitespace-no-wrap">@{{ item.dateenregistrement_format }}</div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.montantloyerbaseformat }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.montantchargeformat }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.montantloyertomformat }}</div>
                                            </td>


                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.montantloyerformat }}</div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap text-center">
                                                        <span class="px-2 rounded-full @{{ item.etat_badge }} text-white">@{{ item.etat_text }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="table-report__action w-56">
                                                <nav class="menu-leftToRight uk-flex text-center">
                                                    <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1us-@{{ item.id }}">
                                                    <label class="menu-open-button bg-white" for="menu-open1us-@{{ item.id }}">
                                                        <span class="hamburger bg-template-1 hamburger-1"></span>
                                                        <span class="hamburger bg-template-1 hamburger-2"></span>
                                                        <span class="hamburger bg-template-1 hamburger-3"></span>
                                                    </label>
                                                    <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('avenant',item.id)" title="Modifier les infos">
                                                        <span class="fal fa-edit"></span>
                                                    </button>

                                                    <button type="button" ng-if="item.est_activer == 2" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="showModalStatut($event,'avenant',1, item, 'Desactiver ')" title="Desactiver l'avenant">
                                                        <i class="fa fa-thumbs-down"></i>
                                                    </button>
                                                    <button type="button" ng-if="item.est_activer == 1" class="menu-item btn border-0 bg-success text-white fsize-16" ng-click="showModalStatut($event,'avenant',2, item, 'Activation ')" title="Activer l'avenant">
                                                        <i class="fa fa-thumbs-up"></i>
                                                    </button>

                                                    <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('avenant',item.id)" title="Supprimer">
                                                        <span class="fa fa-trash-alt"></span>
                                                    </button>


                                                </nav>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            {{-- avenants tab pane --}}

            <div class="tab-content__pane" ng-if="dataPage['demanderesiliations'][0].contrat.id !=null" id="resiliation">

                <div class="intro-y grid grid-cols-12 mt-5 gap-6">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="grig grig-cols-12 p-2">
                            <div class="col-span-12 sm:col-span-12">
                                <h1 class="text-center " style="font-weight: bold;font-size: 20px">Demande
                                    resiliation du contrat faite le @{{ dataPage['demanderesiliations'][0]['dateeffectivite_format'] }}</h1>
                                <h3 class="text-center " style="font-size: 20px">Date debut du contrat le
                                    @{{ dataPage['demanderesiliations'][0]['datedebutcontrat'] }}</h3>

                                <div class="col-span-3 my-4 sm:col-span-3">
                                    <div class="dropdown-toggle cursor-pointer">

                                        <button type="button" ng-if="infosUserConnected && infosUserConnected.roles[0] && infosUserConnected.roles[0].name != 'resident'" ng-click="showModalAdd('inbox_resiliation',optionals={is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0].id)" class="btn btn-danger bg-warning text-white button w-70" title="relance paimement">
                                            Envoie de mail
                                            <span class="fas fa-paper-plane"></span>
                                        </button>
                                    </div>
                                </div>
                                <table class="table table-report ">
                                    <b>Tous les Process </b>
                                    <thead>
                                        <th class="whitespace-no-wrap text-center">Nom Process</th>
                                        <th class="whitespace-no-wrap text-center">Action</th>
                                        <th class="whitespace-no-wrap text-center">Etat</th>
                                    </thead>


                                    <tbody>

                                        <tr class="intro-x" style="cursor: pointer;">

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    Résiliation du contrat de Bail
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <a target="_blank" href="generate-pdf-one-demanderesiliation/@{{ dataPage['demanderesiliations'][0].id }}" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap text-center">
                                                        <span ng-if="dataPage['demanderesiliations'][0].contrat.id !=null" class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                        </span>
                                                        <span ng-if="dataPage['demanderesiliations'][0].contrat.id ==null" class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
                                                            Non disponible
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    Etat Lieu de Sortie
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <a target="_blank" ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null" href="generate-pdf-rapport-etatlieu/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                    <a target="_blank" ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id == null" ng-click="showToast('', 'Etat Lieu de Sortie non disponible', 'error')" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <span ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null" class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                    </span>
                                                    <span ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id == null" class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
                                                        Non disponible
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    Facture d'eaux
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <a target="_blank" href="generate-pdf-factureeaux/@{{ dataPage['contrats'][0].id }}" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <span ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null " class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                    </span>
                                                    <span ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id == null" class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
                                                        Non disponible
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    Devis
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <a target="_blank" ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].devi" href="generate-pdf-one-devi/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}/etatlieu" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                    <a target="_blank" ng-if="( dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' )&& dataPage['contrats'][0]['etatlieu_sortie'].id == null || !dataPage['contrats'][0]['etatlieu_sortie'].devi" ng-click="showToast('', 'Devis non disponible', 'error')" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <span ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].devi" class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                    </span>
                                                    <span ng-if="( dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' )&& dataPage['contrats'][0]['etatlieu_sortie'].id == null || !dataPage['contrats'][0]['etatlieu_sortie'].devi" class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
                                                        Aucun devis
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    Situation dépôt de garantie
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <a target="_blank" ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" href="generate-pdf-situationdepotgarentie/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                    <a target="_blank" ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" ng-click="showToast('', 'Situation dépôt de garantie non disponible', 'error')" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <span ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                    </span>
                                                    <span ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
                                                        Non disponible
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    Situation Globale
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <a target="_blank" ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" href="generate-pdf-piecejoint/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                    <a target="_blank" ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" ng-click="showToast('', 'Situation Globale non disponible', 'error')" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <span ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                    </span>
                                                    <span ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
                                                        Non disponible
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    Bordereau de remise de chéque
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <a target="_blank" ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" href="generate-pdf-bordereauremisecheque/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>

                                                    <a ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" ng-click="showToast('', 'Bordereau de remise de chéque non disponible', 'error')" type="button" class="btn btn-primay bg-danger text-white button w-70" title="pdf du contrat">
                                                        Voir pdf
                                                        <span class="fas fa-paperclip"></span>
                                                    </a>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <span ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                    </span>
                                                    <span ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention" class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
                                                        Non disponible
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>

    </div>
</div>
</div>
@endif