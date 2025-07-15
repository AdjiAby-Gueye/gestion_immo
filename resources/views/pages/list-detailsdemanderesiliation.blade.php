@if (auth()->user()->can('liste-demanderesiliation') ||
        auth()->user()->can('modification-demanderesiliation') ||
        auth()->user()->can('suppression-demanderesiliation'))
    <div class="grid grid-cols-12 gap-6 subcontent classe_generale">
        <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-5">
                <div class="intro-y pr-1 mt-1">
                    <div class="box p-2 item-tabs-produit">
                        <div class="pos__tabs nav-tabs justify-center flex">
                            <a data-toggle="tab" data-target="#infos" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center active">Infos générale</a>
                            <a data-toggle="tab" data-target="#appartement" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Appartement & locataire</a>
                            <a data-toggle="tab" data-target="#facture" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Factures (loyers / eaux)</a>


                            <!-- <a data-toggle="tab" data-target="#etatlieux" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Etat des lieux sortie / dévis</a>
                                <a data-toggle="tab" data-target="#realisations" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Réalisation</a> -->
                            {{-- <a data-toggle="tab" data-target="#actions" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Actions a faire</a> --}}
                            {{-- <a ng-if="dataPage['demanderesiliations'][0].contrat.id !=null" data-toggle="tab"
                                data-target="#resiliation" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Résiliation</a> --}}
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    {{-- <input type="hidden" value="@{{ dataPage['contrats'][0]['id'] }}" id="contrat_id_detailscontrat">
                    <input type="hidden" value="@{{ dataPage['contrats'][0]['locataire']['id'] }}" id="locataire_id_detailscontrat"> --}}
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
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['descriptif']}}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date de début du contrat : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['datedebutcontrat_format']  }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date de la demande : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['datedemande_format']  }}
                                            </div>
                                        </div>


                                        <hr>
                                    </div>
                                    <div class="col-span-6 sm:col-span-6">



                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Délai de préavis  : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['delaipreavi'] ? dataPage['demanderesiliations'][0]['delaipreavi']['designation'] : "...................."}}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Date d'éffectivité : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['dateeffectivite_format'] }}
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Respect du préavis : </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ (dataPage['demanderesiliations'][0].delaipreavisrespecte == '0' ) ? "Non" : "Oui" }}
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
                                    <div class="col-span-5 sm:col-span-5">
                                        <a type="button" href="generate-pdf-one-demanderesiliation/@{{ dataPage['demanderesiliations'][0]['id'] }}"
                                            target="_blank" class="btn btn-primay button w-70" title="Pdf de la demande de rélisiliation"
                                            style="background-color: #31D2F2 ">
                                            Pdf de la demande de rélisiliation
                                            <span class="fas fa-file-pdf"></span>
                                        </a>
                                    </div>
                                    <div class="col-span-2 sm:col-span-2">

                                    </div>
                                    <div class="col-span-5 sm:col-span-5">
                                        <a ng-if="dataPage['demanderesiliations'][0]['document']"  type="button" href="@{{ dataPage['demanderesiliations'][0]['document'] }}"
                                            target="_blank" class="btn btn-primay button w-70" title="Justificatif"
                                            style="background-color: #08c24c ;color:#fff">
                                            Justificatif de la demande de rélisiliation
                                            <span class="fas fa-file-pdf"></span>
                                        </a>
                                    </div>
                                    {{-- <div class="col-span-2 sm:col-span-2"
                                        ng-if="!dataPage['demanderesiliations'][0].id">
                                        <button type="button"
                                            ng-click="showModalAdd('demanderesiliation',{is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0]['locataire'].id)"
                                            class="button w-30 btn btn-success text-white shadow"
                                            style="background-color: red ">Résilié le contrat <i
                                                class="fa fa-thumbs-down"></i></button>
                                    </div> --}}
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- <input type="hidden" id="appartement_detailscontrat" value="@{{dataPage['contrats'][0]['appartement']['id']}}" >
                    <input type="hidden" id="contrat_detailscontrat" value="@{{dataPage['contrats'][0]['id']}}" >
                    <input type="hidden" id="locataire_detailscontrat" value="@{{dataPage['contrats'][0]['locataire']['id']}}" > --}}

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
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['appartement'] ? dataPage['demanderesiliations'][0]['contrat']['appartement']['nom'] : "--------" }}
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
                                                @{{ (dataPage['demanderesiliations'][0]['contrat']['appartement'] && dataPage['demanderesiliations'][0]['contrat']['appartement']['immeuble']) ? dataPage['demanderesiliations'][0]['contrat']['appartement']['immeuble']['nom']: "-----------------------" }}
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
                                                @{{ (dataPage['demanderesiliations'][0]['contrat']['appartement'] && dataPage['demanderesiliations'][0]['contrat']['appartement']['immeuble']) ? dataPage['demanderesiliations'][0]['contrat']['appartement']['immeuble']['adresse']: "-----------------------" }}
                                            </div>
                                        </div>
                                        <hr>


                                    </div>
                                </div>
                            </div>

                            <div class=" col-span-5 sm:col-span-5">
                                <div class="card ">
                                    <div class="card-body" ng-if="dataPage['demanderesiliations'][0]['contrat']['locataire']">
                                        <h5 class="card-title text-center">LOCATAIRE</h5>
                                        {{-- locataire physique start --}}
                                        <div ng-if="dataPage['demanderesiliations'][0]['contrat']['locataire']['nom']"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Prénom & nom: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['locataire']['prenom'] }}
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['locataire']['nom'] }}

                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['demanderesiliations'][0]['contrat']['locataire']['nom']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Email: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6 text-wrap">
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['locataire']['email'] }}

                                            </div>
                                        </div>
                                        <hr>

                                        <div ng-if="dataPage['demanderesiliations'][0]['contrat']['locataire']['nom']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Téléphone portable: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['locataire']['telephoneportable1'] }}

                                            </div>
                                        </div>
                                        {{-- <hr> --}}
                                        {{-- locataire physique end --}}

                                        {{-- locataire moral start --}}
                                        <div ng-if="dataPage['demanderesiliations'][0]['contrat']['locataire']['nomentreprise']"
                                            class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Nom entreprise: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['locataire']['nomentreprise'] }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['demanderesiliations'][0]['contrat']['locataire']['nomentreprise']"
                                            class="grid grid-cols-12  p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Email: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['locataire']['email'] ?? "-----" }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div ng-if="dataPage['demanderesiliations'][0]['contrat']['locataire']['nomentreprise']"
                                            class="grid grid-cols-12 p-2 d-flex justify-content-between">
                                            <div class="col-span-6 sm:col-span-6 ">
                                                <strong>
                                                    <h5>Adresse de l'entreprise: </h5>
                                                </strong>
                                            </div>
                                            <div class="col-span-6 sm:col-span-6">
                                                @{{ dataPage['demanderesiliations'][0]['contrat']['locataire']['adresseentreprise'] ?? "-------" }}
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
                            <div class="col-span-12 sm:col-span-12">

                                <div class="grid grid-cols-12 gap-3">
                                    {{-- <div class="col-span-3 sm:col-span-3">
                                        <a target="_blank" href="generate-pdf-appelloyer/@{{ dataPage['contrats'][0].id }}"
                                            type="button" class="btn btn-primay bg-danger text-white button w-70"
                                            title="pdf du contrat">
                                            Voir l'appel loyer
                                            <span class="fas fa-paperclip"></span>
                                        </a>
                                    </div> --}}
                                    {{-- <div class="col-span-3 sm:col-span-3">
                                        <button
                                            ng-click="showModalAdd('facturelocation',{is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0]['locataire'].id)"
                                            type="button" class="btn btn-primay bg-primary text-white button w-70"
                                            title="pdf du contrat" style="background-color: #31D2F2 ">
                                            Ajouter facture
                                            <span class="fas fa-plus"></span>
                                        </button>
                                    </div>
                                    <div class="col-span-3 sm:col-span-3">
                                        <button
                                            ng-click="showModalAdd('factureeaux',{is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0]['locataire'].id)"
                                            type="button" class="btn btn-primay bg-primary text-white button w-70"
                                            title="pdf du contrat" style="background-color: rgb(6, 4, 103) ">
                                            Facture d'eaux
                                            <span class="fas fa-plus"></span>
                                        </button>
                                    </div> --}}

                                    {{-- <div class="col-span-3 sm:col-span-3">

                                        <div class="dropdown-toggle notification notification--bullet cursor-pointer">
                                            <button type="button"
                                                ng-click="showModalAdd('inbox',optionals={is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0].id)"
                                                class="btn btn-danger bg-warning text-white button w-70"
                                                title="relance paimement">

                                                Relance paiement

                                                <span class="fas fa-paper-plane"></span>
                                            </button>
                                            <div class="item-notif-number mt-1 mr-2">@{{ dataPage['contrats'][0]['nombre_relance_loyer'] ? dataPage['contrats'][0]['nombre_relance_loyer'] : "0" }}</div>
                                        </div>
                                    </div> --}}



                                </div>
                                <div class="grig grig-cols-12 p-2">


                                    <div class="col-span-8 sm:col-span-8">

                                        <table class="table table-report ">
                                            <b>Toutes les factures non réglées (eaux/loyers)</b>
                                            <thead>
                                                <th class="whitespace-no-wrap text-center">Type de facture</th>
                                                <th class="whitespace-no-wrap text-center">Date</th>
                                                <th class="whitespace-no-wrap text-center">Montant total</th>
                                                <th class="whitespace-no-wrap text-center">Reglé</th>
                                                {{-- <th class="whitespace-no-wrap text-center">Actions</th> --}}

                                            </thead>


                                            <tbody>

                                                <tr  class="intro-x"  style="cursor: pointer;"   ng-repeat="item in dataPage['factureeauxs']">
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                           <p>eaux</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            @{{ item.finperiode }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            @{{ item.montanttotalfacture_format }} Fcfa
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

                                                <tr class="intro-x" ng-repeat="item in dataPage['facturelocations']"
                                                    style="cursor: pointer;"    >
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center text-primary" style="text-decoration: underline">
                                                            <a target="_blank"
                                                                href="generate-pdf-one-facturelocation/@{{ item.id }}">
                                                                @{{ item.typefacture.designation }}
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            @{{ item.datefacture }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            @{{ item.montant_total }} Fcfa 
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
                    {{-- </div> --}}
                    {{-- facture et paiement start --}}


                    
                    <div class="tab-content__pane" id="etatlieux">
                        <div class="intro-y grid grid-cols-12 mt-5 gap-6">
                            <div class="col-span-12 my-4 sm:col-span-12">
                                <div class="dropdown-toggle cursor-pointer">

                                    <button type="button"
                                        ng-click="showModalAdd('inbox_resiliation',optionals={is_file_excel:false, title:null, fromUpdate: false},dataPage['demanderesiliations'][0]['contrat'].id)"
                                        class="btn btn-danger bg-warning text-white button w-70"
                                        title="envoie mail">
                                        Envoie de mail
                                        <span class="fas fa-paper-plane"></span>
                                    </button>

                                </div>
                            </div>


                            <div class=" col-span-6 sm:col-span-6">
                                <div class="card ">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"><b>Etat des lieux de sortie </b></h5>
                                        <div class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between"
                                            ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'] && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id">
                                            <div class="col-span-12 sm:col-span-12 gap-3">
                                                <a type="button"
                                                    href="generate-pdf-rapport-etatlieu/@{{ dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id }}"
                                                    target="_blank" class=" btn  bg-success button w-70  text-white"
                                                    title="appel caution">
                                                    Voir le document
                                                    <span class="fas fa-file-pdf mt-2"></span>
                                                </a>
                                            </div>
                                            <hr>
                                        </div>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between"
                                            ng-if="!dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']">
                                            <div class="col-span-12 sm:col-span-12 d-flex justify-content-between">
                                                <em>Aucun etat des lieux de sortie</em>
                                                <button
                                                    ng-if="!dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']"
                                                    ng-click="showModalAdd('etatlieu',{is_file_excel:false, title:null},dataPage['demanderesiliations'][0]['contrat']['appartement'].id)"
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

                            {{-- devi --}}
                            <div class=" col-span-6 sm:col-span-6">
                                <div class="card ">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"><b>Dévis </b></h5>
                                        <div class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between"
                                            ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'] && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].devi ">
                                            <div class="col-span-6 sm:col-span-6 gap-3">
                                                <a type="button"
                                                ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['devi'].est_activer == 1"
                                                    href="generate-pdf-one-devi/@{{ dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id }}/etatlieu"
                                                    target="_blank" class=" btn  bg-warning button w-70  text-white"
                                                    title="appel caution">
                                                    Voir le dévis
                                                    <span class="fas fa-file-invoice mt-2"></span>
                                                </a>
                                                <a
                                                ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['devi'].est_activer == 0"
                                                 type="button"
                                                    href="generate-pdf-one-devi/@{{ dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id }}/etatlieu"
                                                    target="_blank" class=" btn  bg-success button w-70  text-white"
                                                    title="appel caution">
                                                    Voir le dévis
                                                    <span class="fas fa-file-invoice mt-2"></span>
                                                </a>

                                            </div>
                                            <div class="col-span-6 sm:col-span-6 gap-3">


                                                <button
                                                ng-click="showModalStatut($event,'devi',0,dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].devi, 'Valider le devis de sortie')"
                                                ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['devi'] && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['devi'].est_activer == 1"
                                                     class=" btn  bg-success button w-70  text-white"
                                                    title="validation dévis">
                                                    Valider le dévis
                                                    <span class="fa fa-thumbs-up mt-2"></span>
                                                </button>
                                            </div>
                                            <hr>
                                        </div>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between"
                                            ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']">

                                            <div
                                            ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'] && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].devi == null"

                                             class="col-span-12 sm:col-span-12 d-flex justify-content-between">
                                                <em>Aucun dévis  </em>
                                                <button
                                                    target="_blank" class=" btn  bg-danger button w-70  text-white"
                                                    ng-click="
                                                    showModalAdd('devi', optionals = {
                                                        is_file_excel: false,
                                                        title: null,
                                                        fromUpdate: false,
                                                    },dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id)"
                                                    title="créer un dévis">
                                                    Ajouter un dévis
                                                    <span class="fa fa-file-invoice"></span>
                                                </button>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- devi --}}

                            {{-- facture intervention --}}
                            <div class=" col-span-6 sm:col-span-6">
                                <div class="card ">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"><b>Facture d'intervention </b></h5>
                                        <div class="grid grid-cols-12 mt-2 p-2 d-flex justify-content-between"
                                            ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['factureintervention']">
                                            <div class="col-span-12 sm:col-span-12 gap-3">
                                                <a type="button"
                                                    href="generate-pdf-one-factureintervention/@{{ dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['factureintervention'].id }}"
                                                    target="_blank" class=" btn  bg-warning button w-70  text-white"
                                                    title="appel caution">
                                                    Voir la facture d'intervention
                                                    <span class="fas fa-file-pdf mt-2"></span>
                                                </a>
                                            </div>
                                            <hr>
                                        </div>
                                        <div class="grid grid-cols-12  p-2 d-flex justify-content-between"
                                        ng-if="!dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['factureintervention'] && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['devi'] && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['devi'].est_activer == 0 "

                                            {{-- ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'] && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].devi != null &&  dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie']['devi'].est_activer == 0" --}}
                                            >
                                            <div class="col-span-12 sm:col-span-12 d-flex justify-content-between">
                                                <em>Aucune facture d'intervention

                                                </em>
                                                <button
                                                    ng-click="showModalAdd('factureintervention',{is_file_excel:false, title:null},dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id)"
                                                    type="button"
                                                    class="btn btn-warning bg-primary button text-white  w-70"
                                                    title="facture d'intervention">
                                                    Ajouter la facture
                                                    <span class="fa fa-credit-card"></span>
                                                </button>
                                            </div>
                                            <hr>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- facture intervention --}}

                        </div>

                    </div>
                    {{-- facture et paiement end --}}
                    {{-- <span ng-if="item.type === 'sortie' && item.devi">
                        <a target="_blank"
                            href="generate-pdf-one-devi/@{{ item.id }}/etatlieu"
                            ng-if="item.devi.est_activer == 1"
                            class="px-2 rounded-full bg-warning text-white">@{{ item.devi.code }}</a>
                    </span>
                    <span ng-if="item.type === 'sortie' && item.devi">
                        <a target="_blank"
                            href="generate-pdf-one-devi/@{{ item.id }}/etatlieu"
                            ng-if="item.devi.est_activer == 0"
                            class="px-2 rounded-full bg-success text-white">@{{ item.devi.code }}</a>
                    </span> --}}

                    {{-- actions tab pane --}}
                    <div class="tab-content__pane" id="realisations">
                        <div class="intro-y grid grid-cols-12 mt-5 gap-6">
                            <div class="col-span-12 sm:col-span-12">
                                <div class="grig grig-cols-12 p-2">
                                    <div class="col-span-12 sm:col-span-12">
                                        <h1 class="text-center " style="font-weight: bold;font-size: 20px">Demande
                                            résiliation du contrat faite le @{{ dataPage['demanderesiliations'][0]['dateeffectivite_format'] }}</h1>
                                        <h3 class="text-center " style="font-size: 20px">Date de début du contrat :
                                            @{{ dataPage['demanderesiliations'][0]['datedebutcontrat'] }}</h3>

                                        <div class="col-span-3 my-4 sm:col-span-3">
                                            <div class="dropdown-toggle cursor-pointer">

                                                <button type="button"
                                                    ng-click="showModalAdd('inbox_resiliation',optionals={is_file_excel:false, title:null, fromUpdate: false},dataPage['demanderesiliations'][0]['contrat'].id)"
                                                    class="btn btn-danger bg-warning text-white button w-70"
                                                    title="envoie mail">
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
                                                <tr>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            Situation dépôt de garantie
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <a target="_blank"
                                                            ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id !=null && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie' && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                href="generate-pdf-situationdepotgarentie/@{{ dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                            <a target="_blank"
                                                            ng-if="(dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie')&& dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id == null    || !dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                            ng-click="showToast('', 'Situation dépôt de garantie non disponible', 'error')"

                                                            type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id !=null && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie' && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full  bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="(dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie')&& dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id == null    || !dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full  bg-danger text-white">
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
                                                            <a target="_blank"
                                                            ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id !=null && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie' && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                href="generate-pdf-piecejoint/@{{ dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                            <a target="_blank"
                                                            ng-if="(dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie')&& dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id == null || !dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                            ng-click="showToast('', 'Situation Globale non disponible', 'error')"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id !=null && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie' && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full  bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="(dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie')&& dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id == null    || !dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full  bg-danger text-white">
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
                                                            <a target="_blank"
                                                            ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id !=null &&
                                                            dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie' &&
                                                            dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                href="generate-pdf-bordereauremisecheque/@{{ dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>

                                                            <a ng-if="(dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie')&&
                                                            dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id == null  || !dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                ng-click="showToast('', 'Bordereau de remise de chéque non disponible', 'error')"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id !=null &&
                                                                dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie' && dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="(dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].type === 'sortie')&& dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].id == null  || !dataPage['demanderesiliations'][0]['contrat']['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full  bg-danger text-white">
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
                    {{-- actions tab pane --}}

                    {{-- <div class="tab-content__pane" id="resiliation">

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

                                                <button type="button"
                                                    ng-click="showModalAdd('inbox_resiliation',optionals={is_file_excel:false, title:null, fromUpdate: false},dataPage['contrats'][0].id)"
                                                    class="btn btn-danger bg-warning text-white button w-70"
                                                    title="relance paimement">
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
                                                            <a target="_blank"
                                                                href="generate-pdf-one-demanderesiliation/@{{ dataPage['demanderesiliations'][0].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <div class="font-medium whitespace-no-wrap text-center">
                                                                <span
                                                                    ng-if="dataPage['demanderesiliations'][0].contrat.id !=null"
                                                                    class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                                </span>
                                                                <span
                                                                    ng-if="dataPage['demanderesiliations'][0].contrat.id ==null"
                                                                    class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
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
                                                            <a target="_blank"
                                                            ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null"
                                                                href="generate-pdf-rapport-etatlieu/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                            <a target="_blank"
                                                            ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id == null"
                                                            ng-click="showToast('', 'Etat Lieu de Sortie non disponible', 'error')"
                                                            type="button"
                                                            class="btn btn-primay bg-danger text-white button w-70"
                                                            title="pdf du contrat">
                                                            Voir pdf
                                                            <span class="fas fa-paperclip"></span>
                                                        </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id == null"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
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
                                                            <a target="_blank"
                                                                href="generate-pdf-factureeaux/@{{ dataPage['contrats'][0].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null "
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id == null"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
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
                                                            <a target="_blank"
                                                            ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].devi"
                                                                href="generate-pdf-one-devi/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}/etatlieu"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                            <a target="_blank"
                                                            ng-if="( dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' )&& dataPage['contrats'][0]['etatlieu_sortie'].id == null || !dataPage['contrats'][0]['etatlieu_sortie'].devi"
                                                            ng-click="showToast('', 'Devis non disponible', 'error')"

                                                            type="button"
                                                            class="btn btn-primay bg-danger text-white button w-70"
                                                            title="pdf du contrat">
                                                            Voir pdf
                                                            <span class="fas fa-paperclip"></span>
                                                        </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].devi"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="( dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' )&& dataPage['contrats'][0]['etatlieu_sortie'].id == null || !dataPage['contrats'][0]['etatlieu_sortie'].devi"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
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
                                                            <a target="_blank"
                                                            ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                href="generate-pdf-situationdepotgarentie/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                            <a target="_blank"
                                                            ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                            ng-click="showToast('', 'Situation dépôt de garantie non disponible', 'error')"

                                                            type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
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
                                                            <a target="_blank"
                                                            ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                href="generate-pdf-piecejoint/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                            <a target="_blank"
                                                            ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                            ng-click="showToast('', 'Situation Globale non disponible', 'error')"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
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
                                                            <a target="_blank"
                                                            ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                href="generate-pdf-bordereauremisecheque/@{{ dataPage['contrats'][0]['etatlieu_sortie'].id }}"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>

                                                            <a ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                ng-click="showToast('', 'Bordereau de remise de chéque non disponible', 'error')"
                                                                type="button"
                                                                class="btn btn-primay bg-danger text-white button w-70"
                                                                title="pdf du contrat">
                                                                Voir pdf
                                                                <span class="fas fa-paperclip"></span>
                                                            </a>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-medium whitespace-no-wrap text-center">
                                                            <span
                                                                ng-if="dataPage['contrats'][0]['etatlieu_sortie'].id !=null && dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie' && dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-success text-white">Disponible
                                                            </span>
                                                            <span
                                                                ng-if="(dataPage['contrats'][0]['etatlieu_sortie'].type === 'sortie')&& dataPage['contrats'][0]['etatlieu_sortie'].id == null    || !dataPage['contrats'][0]['etatlieu_sortie'].factureintervention"
                                                                class="px-2 rounded-full @{{ item.etat_badge }} bg-danger text-white">
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
                    </div> --}}

                </div>

            </div>
        </div>
    </div>
@endif
