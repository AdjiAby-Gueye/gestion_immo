@if (auth()->user()->can('liste-proprietaire') ||
auth()->user()->can('modification-proprietaire') ||
auth()->user()->can('suppression-proprietaire'))
<div class="grid grid-cols-12 gap-6 subcontent classe_generale">
    <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-5">
            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infoproprietaire" href="javascript:;"
                           class="flex-1 py-2 rounded-md text-center active">Infos générale</a>
                        <a data-toggle="tab" data-target="#mandatproprietaire" href="javascript:;"
                           class="flex-1 py-2 rounded-md text-center">Mandats</a>
                        <a data-toggle="tab" data-target="#appartementproprietaire" href="javascript:;"
                           class="flex-1 py-2 rounded-md text-center">Appartements</a>
                        <a data-toggle="tab" data-target="#factureloyerproprietaire" href="javascript:;"
                           class="flex-1 py-2 rounded-md text-center">Factures Loyers</a>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <input type="hidden" value="@{{ dataPage['proprietaires'][0]['id'] }}" id="id_proprietaire">
                <div class="tab-content__pane active" id="infoproprietaire">
                    {{-- infos start --}}
                    <div class="card mt-5">
                        <div class="card-body">
                            {{-- <h5 class="card-title text-center">INFOS</h5> --}}
                            <div class="intro-y grid grid-cols-12 ">
                                <div class="mr-2 col-span-6 sm:col-span-6">

                                    <div class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Prenom du proprietaire : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['proprietaires'][0]['prenom'] }}
                                        </div>
                                    </div>
                                    <hr>

                                    
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Nom du proprietaire : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['proprietaires'][0]['nom'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Adresse du proprietaire : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['proprietaires'][0]['adresse'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Telephone 1 : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['proprietaires'][0]['telephone'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Telephone 2 : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['proprietaires'][0]['telephoneportable'] }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                        <div class="col-span-6 sm:col-span-6 ">
                                            <strong>
                                                <h5>Telephone Bureau : </h5>
                                            </strong>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            @{{ dataPage['proprietaires'][0]['telephonebureau'] }}
                                        </div>
                                    </div>
                                    <hr>
                            </div>
                        </div>

                    </div>
                    </div>
                </div>

                {{-- mandat proprietaire start --}}
                <div class="tab-content__pane" id="mandatproprietaire">
                    
                    <div class="grig grig-cols-12 p-2">
                        <div class="overflow-table">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                                <table class="table table-report sm:mt-2">
                                    <thead>
                                    <tr>
                                        <th class="whitespace-no-wrap text-center">Model de contrat</th>
                                        <th class="whitespace-no-wrap text-center">Date</th>
                                        <th class="whitespace-no-wrap text-center">Valeur Commission</th>
                                        <th class="whitespace-no-wrap text-center">Pourcentage Commission</th>
                                        <th class="whitespace-no-wrap text-center">TVA</th>
                                        <th class="whitespace-no-wrap text-center">BRS</th>
                                        <th class="whitespace-no-wrap text-center">TLV</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataPage['contratproprietaires']">
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.modelcontrat.designation }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.date }}</div>
                                            </td>
            
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.commissionvaleur }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.commissionpourcentage }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.is_tva_text }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.is_brs_text }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.is_tlv_text }}</div>
                                            </td>
                                        </tr>
            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- mandats proprietaire end --}}

                <div class="tab-content__pane" id="appartementproprietaire">
                    <div class="overflow-table">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                            <table class="table table-report sm:mt-2">
                                <thead>
                                <tr>
                                    <th class="whitespace-no-wrap">Nom</th>
                                    <th class="whitespace-no-wrap text-center">Immeuble</th>
                                    <th class="whitespace-no-wrap text-center">Niveau</th>
                                    <th class="whitespace-no-wrap text-center">Type</th>
                                    <th class="whitespace-no-wrap text-center">Etat</th>
                                    <th class="whitespace-no-wrap text-center">Montant caution</th>
                                    <th class="whitespace-no-wrap text-center">montant loyer</th>
                                    <th class="whitespace-no-wrap text-center">Fréquence paiement</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataPage['appartements']" >
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.nom }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.immeuble_id ? item['immeuble'].nom : "" }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.niveau }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item['typeappartement'].designation }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center px-2 rounded-full text-white @{{ item['etatappartement'].etat_badge }}">@{{ item['etatappartement'].designation }}</div>
                                        </td>
                                        <td>
                                            <div ng-repeat="contrat in item['contrats']" ng-if="contrat.etat == 1" class="font-medium whitespace-no-wrap text-center">@{{ contrat.caution.montantcaution }}</div>
                                            <div ng-repeat="contrat in item['contrats']" ng-if="contrat.etat == 1 && !contrat.caution" class="font-medium whitespace-no-wrap text-center">caution non versé</div>
                                            <div ng-if="item.iscontrat == '0' && item.montantcaution" class="font-medium whitespace-no-wrap text-center">@{{item.montantcaution}}</div>
                                        </td>
                                        <td>
                                            <div ng-repeat="contrat in item['contrats']" ng-if="contrat.etat == 1" class="font-medium whitespace-no-wrap text-center">@{{ contrat.montantloyer }}</div>
                                            <div ng-if="item.iscontrat == '0' && item.montantloyer" class="font-medium whitespace-no-wrap text-center">@{{item.montantcaution}}</div>
                                        </td>
                                        <td>
                                            <div ng-repeat="contrat in item['contrats']" ng-if="contrat.etat == 1" class="font-medium whitespace-no-wrap text-center">@{{ item.frequencepaiementappartement.designation }}</div>
                                            <div ng-if="item.iscontrat == '0'" class="font-medium whitespace-no-wrap text-center">Neant</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                
            {{-- facture loyer start --}}
            <div class="tab-content__pane" id="factureloyerproprietaire"> 

                <div class="intro-y grid grid-cols-12 mt-5 gap-4 ">
                    <div class="col-span-8 sm:col-span-8">
                        <div class="grig grig-cols-12 p-2">
                            <div class="col-span-12 sm:col-span-12">

                                <table class="table table-report ">
                                    <b>Factures loyers</b>
                                    <thead>
                                        <th class="whitespace-no-wrap text-center">Date</th>
                                        <th class="whitespace-no-wrap text-center">Loyer</th>
                                        <th class="whitespace-no-wrap text-center">Locataire</th>
                                        <th class="whitespace-no-wrap text-center">Appartement</th>
                                        <th class="whitespace-no-wrap text-center">Reglé</th>
                                    
                                    </thead>

                                    <tbody>

                                        <tr class="intro-x" ng-repeat="item in dataPage['facturelocations']"
                                            style="cursor: pointer;"    >
                                            
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.datefacture_format }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.contrat.montantloyerformat }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.contrat.locataire.prenom }} @{{ item.contrat.locataire.nom }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.contrat.appartement.nom }}
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap text-center ">
                                                        <span ng-if="item.is_paid == 0"
                                                            
                                                            class="px-2 rounded-full @{{ item.is_paid_badge }} text-white">@{{ item.is_paid_text }}</span>
                                                        <span ng-if="item.is_paid == 1"

                                                            class="px-2 rounded-full @{{ item.is_paid_badge }} text-white">@{{ item.is_paid_text }}
                                                        </span>
                                                        <span ng-if="item.is_paid == 3"
                                                            class="px-2 rounded-full @{{ item.is_paid_badge }} text-white">@{{ item.is_paid_text }}
                                                        </span>
                                                    </div>
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
            {{-- facture loyer end --}}
    </div>

    </div>
</div>
</div>
@endif
