@if (auth()->user()->can('liste-demanderesiliation') ||
        auth()->user()->can('modification-demanderesiliation') ||
        auth()->user()->can('suppression-demanderesiliation'))
    <div class="grid grid-cols-12 gap-6 subcontent classe_generale">
        <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-6">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5 uppercase">
                        Resiliation de bail
                        <span
                            class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{ paginations['paiementloyer'].totalItems }}</span>
                    </h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                        @if (auth()->user()->can('creation-demanderesiliation'))
                            <div class="" id="basic-dropdown">
                                <div class="preview flex justify-center">
                                    <div class="dropdown relative">
                                        <button
                                            class="dropdown-toggle button button box flex items-center text-gray-700">
                                            <i class="fa fa-plus mr-2"></i> Ajouter </button>
                                        <div class="dropdown-box mt-40 absolute top-0 right-0 z-20"
                                            style="width: 125px">
                                            <div class="dropdown-box__content box p-2">
                                                <button class="button  flex items-center text-gray-700"
                                                    ng-click="showModalAdd('demanderesiliation')" title="Ajouter"> Un
                                                    Element</button>
                                                <button class="button  flex items-center text-gray-700"
                                                    ng-click="showModalAdd('demanderesiliation', {is_file_excel:true, title: 'demanderesiliation'})"
                                                    title="Import"> Fichier Excel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="intro-y grid grid-cols-12 gap-6 mt-52 sm:mt-5 md:mt-5">
                    <!-- BEGIN: Basic Accordion -->
                    <div class="col-span-12 lg:col-span-12">
                        <div class="intro-y box">
                            <div class="p-3" id="basic-accordion">
                                <div class="preview">
                                    <div class="accordion">
                                        <div class="accordion__pane border-gray-200">
                                            <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                                <div class="flex flex-wrap">
                                                    <div class="w-full md:w-1/2 px-3 self-center"><span
                                                            class="fa fa-filter mr-1"></span>LES FILTRES</div>
                                                    <div class="w-full md:w-1/2 px-3 text-right">
                                                        <button class="button bg-theme-101 text-white btn-shadow">
                                                            <span class="w-5 h-5 flex items-center justify-center"> <i
                                                                    class="fa fa-chevron-down"></i> </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                                <form class="bg-white grid p-3 mt-3">
                                                    <div class="flex flex-wrap -mx-3">
                                                        <div class="w-full md:w-1/2 px-3 mb-2">
                                                            <div class="inline-block relative w-full">
                                                                <select
                                                                    class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                                                    id="searchoption_list_demanderesiliation"
                                                                    name="searchoption">
                                                                    <option value="">Rechercher par</option>
                                                                    <option selected value="datedemande">Date de la
                                                                        demande</option>
                                                                    <option selected value="dateeffectivite">Date
                                                                        d'effectivité</option>
                                                                </select>
                                                                <div
                                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                                    <svg class="fill-current h-4 w-4"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="w-full md:w-1/2 px-3 mb-2">
                                                            <input
                                                                class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                id="searchtexte_list_demanderesiliation"
                                                                ng-model="searchtexte_list_demanderesiliation"
                                                                autocomplete="off" type="text"
                                                                placeholder="Texte ... ">
                                                        </div>
                                                        <div class="w-full md:w-1/2 px-3 mb-2">
                                                            <div class="inline-block relative w-full">
                                                                <select
                                                                    class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                                                    id="contrat_list_demanderesiliation">
                                                                    <option value="">Contrat</option>
                                                                    <option ng-repeat="item in dataPage['contrats']"
                                                                        value="@{{ item.id }}">
                                                                        @{{ item.descriptif }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                                            <div class="flex flex-wrap -mx-3">
                                                                <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                                    <a ng-if="filters"
                                                                        href="generate-pdf-demanderesiliation/@{{ filters }}"
                                                                        target="_blank"
                                                                        class="button small border text-white bg-danger mr-1 btn-shadow-dark"
                                                                        title="PDF"><span
                                                                            class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                    <a ng-if="!filters"
                                                                        href="generate-pdf-demanderesiliation"
                                                                        target="_blank"
                                                                        class="button small border text-white bg-danger mr-1 btn-shadow-dark"
                                                                        title="PDF"><span
                                                                            class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                    <a ng-if="filters"
                                                                        href="generate-excel-demanderesiliation/@{{ filters }}"
                                                                        target="_blank"
                                                                        class="button bg-success text-white btn-shadow"
                                                                        title="Excel"><span
                                                                            class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                    <a ng-if="!filters"
                                                                        href="generate-excel-demanderesiliation"
                                                                        target="_blank"
                                                                        class="button bg-success text-white btn-shadow"
                                                                        title="Excel"><span
                                                                            class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                </div>
                                                                <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                                                    <button type="button"
                                                                        class="button small border text-gray-700 mr-1 btn-shadow-dark"
                                                                        ng-click="emptyform('demanderesiliation', true)"><span
                                                                            class="fa fa-filter mr-1"></span>Annuler</button></button>
                                                                    <button type="button"
                                                                        class="button bg-theme-101 text-white btn-shadow"
                                                                        ng-click="pageChanged('demanderesiliation')"><span
                                                                            class="fa fa-search mr-1"></span>Filtrer</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Basic Accordion -->
                </div>

                <div class="overflow-table">
                    <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                        <table class="table table-report sm:mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-no-wrap">Contrat</th>
                                    <th class="whitespace-no-wrap text-center">Date de debut du contrat</th>
                                    <th class="whitespace-no-wrap text-center">date de la demande</th>
                                    <th class="whitespace-no-wrap text-center">delai de preavis</th>
                                    <th class="whitespace-no-wrap text-center">Date d'effectivité</th>
                                    <th class="whitespace-no-wrap text-center">Respect du delai de preavi</th>
                                    <th class="whitespace-no-wrap text-center">Retour caution</th>
                                    <th class="whitespace-no-wrap text-center">Etat</th>
                                    <th class="text-center whitespace-no-wrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="intro-x" ng-repeat="item in dataPage['demanderesiliations']">
                                    <td>
                                        <div class="font-medium whitespace-no-wrap">@{{ item.contrat.descriptif }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium whitespace-no-wrap text-center">@{{ item.datedebutcontrat_format }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-medium whitespace-no-wrap text-center">@{{ item.datedemande_format }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-medium whitespace-no-wrap text-center">@{{ item.delaipreavi.designation }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-medium whitespace-no-wrap text-center">@{{ item.dateeffectivite_format }}
                                        </div>
                                    </td>
                                    <td>
                                        <div ng-if="item.delaipreavisrespecte=='0'"
                                            class="font-medium whitespace-no-wrap text-center">Non</div>
                                        <div ng-if="item.delaipreavisrespecte=='1'"
                                            class="font-medium whitespace-no-wrap text-center">Oui</div>
                                    </td>
                                    <td>
                                        <div ng-if="item.retourcaution"
                                            class="font-medium whitespace-no-wrap text-center">@{{ item.retourcaution }}
                                        </div>
                                        <div ng-if="!item.retourcaution"
                                            class="font-medium whitespace-no-wrap text-center">Pas encore disponible
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-medium whitespace-no-wrap text-center">
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                <span
                                                    class="px-2 rounded-full @{{ item.etat_badge }} text-white">@{{ item.etat_text }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!--                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.etat }}</div>
                                </td>
-->
                                    <td class="table-report__action w-56">
                                        <nav class="menu-leftToRight uk-flex text-center">
                                            <input type="checkbox" href="#" class="menu-open" name="menu-open"
                                                id="menu-open1us-@{{ item.id }}">
                                            <label class="menu-open-button bg-white"
                                                for="menu-open1us-@{{ item.id }}">
                                                <span class="hamburger bg-template-1 hamburger-1"></span>
                                                <span class="hamburger bg-template-1 hamburger-2"></span>
                                                <span class="hamburger bg-template-1 hamburger-3"></span>
                                            </label>
                                            <button type="button" ng-click="redirectUrl('list-detailsdemanderesiliation',item.id)" class="menu-item btn border-0 bg-info text-white fsize-16"  title="Détails demande résiliation">
                                                <span class="fas fa-eye mt-2"></span>
                                            </button>
                                            <button class="menu-item btn border-0 bg-danger text-white fsize-16"
                                                ng-click="deleteElement('demanderesiliation',item.id)"
                                                title="Supprimer">
                                                <span class="fa fa-trash-alt"></span>
                                            </button>
                                            <button class="menu-item btn border-0 bg-info text-white fsize-16"
                                                ng-click="showModalUpdate('demanderesiliation',item.id)"
                                                title="Modifier les infos">
                                                <span class="fal fa-edit"></span>
                                            </button>

                                            <a type="button"
                                                href="generate-pdf-one-demanderesiliation/@{{ item.id }}"
                                                target="_blank"
                                                class="menu-item btn border-0 bg-danger text-white fsize-16"
                                                title="document résilisation">
                                                <span class="fas fa-file-pdf mt-2"></span>
                                            </a>

                                            {{-- <button class="menu-item btn border-0 bg-warning text-white fsize-16"
                                                ng-if="item.contrat.appartement.etatlieu == '1' && item.contrat.appartement.iscontrat == '1'  && item.contrat.appartement.isdemanderesiliation == '1' && infosUserConnected.roles[0].id != 2"
                                                ng-click="showModalAdd('etatlieu',{is_file_excel:false, title:null},item.contrat.appartement.id)"
                                                title="Etat des lieux de sortie">
                                                <span class="fa fa-file-exclamation"></span>
                                            </button> --}}
                                            <button type="button"
                                                class="menu-item btn border-0 bg-success text-white fsize-16"
                                                ng-click="showModalStatut($event,'demanderesiliation',4,item, 'Valider la caution')"
                                                ng-if="item.etat == 3" title="valider la caution">
                                                <i class="fa fa-thumbs-up"></i>
                                            </button>
                                        </nav>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- PAGINATION -->
                <div class="col-span-12 grid grid-cols-12 gap-4 mt-3">
                    <div class="col-span-12 sm:col-span-12 md:col-span-3">
                        <span>Affichage par</span>
                        <select class="w-20 input box mt-1" ng-model="paginations['demanderesiliation'].entryLimit"
                            ng-change="pageChanged('demanderesiliation')">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                        <nav aria-label="Page navigation">
                            <ul class="uk-pagination float-right" uib-pagination
                                total-items="paginations['demanderesiliation'].totalItems"
                                ng-model="paginations['demanderesiliation'].currentPage"
                                max-size="paginations['demanderesiliation'].maxSize"
                                items-per-page="paginations['demanderesiliation'].entryLimit"
                                ng-change="pageChanged('demanderesiliation')" previous-text="‹" next-text="›"
                                first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                        </nav>
                    </div>
                </div>
                <!-- /PAGINATION -->
            </div>
        </div>
    </div>
@endif
