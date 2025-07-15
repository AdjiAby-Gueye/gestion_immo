@if(auth()->user()->can('liste-contratproprietaire') || auth()->user()->can('modification-contratproprietaire') || auth()->user()->can('suppression-contratproprietaire'))
<div class="grid grid-cols-12 gap-6 subcontent classe_generale">
    <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-6">

            <div class="sm:flex  items-center">
                <div class="p-3 intro-y theme-sombre  rounded-2xl">
                    <i class="fa tooltip image_fit  fa-user" aria-hidden="true"></i>
                </div>
                <div class="intro-y block sm:flex  flex-1 items-center theme-dark border-raduis-right">
                    <h2 class="text-lg font-medium truncate mr-5 uppercase ml-2">
                        @{{titlePage}}
                        <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{paginations['contratproprietaire'].totalItems}}</span>
                    </h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                        @if(auth()->user()->can('creation-contratproprietaire'))
                        <div class="" id="basic-dropdown">
                            <div class="preview flex justify-center">
                                <div class="dropdown relative">
                                    <button class="dropdown-toggle   custom-btn box flex items-center text-gray-700"> <i class="fa fa-plus mr-2"></i> Ajouter </button>
                                    <div class="dropdown-box mt-40 absolute left-1/2 transform  top-20 z-50 " style="width: 140px">
                                        <div class="dropdown-box__content box  p-2">
                                            <button class="button dropdown-box-btn   flex items-center text-gray-700" ng-click="showModalAdd('contratproprietaire')" title="Ajouter"> Un Element</button>
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('contratproprietaire', {is_file_excel:true, title: 'contratproprietaire'})" title="Import"> Fichier Excel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
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
                                                <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>LES FILTRES</div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="button bg-theme-101 text-white btn-shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <form class="bg-white grid p-3 mt-3">
                                                <div class="flex flex-wrap -mx-3">
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <div class="inline-block relative w-full">
                                                            <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="proprietaire_list_contratproprietaire">
                                                                <option value="">Proprietaire</option>
                                                                <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">@{{ item.prenom }} @{{ item.nom }}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <div class="inline-block relative w-full">
                                                            <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="modelcontrat_list_contratproprietaire">
                                                                <option value="">Model de contrat</option>
                                                                <option ng-repeat="item in dataPage['modelcontrats']" value="@{{ item.id }}">@{{ item.designation }}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                                        <div class="flex flex-wrap -mx-3">
                                                            <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                                <a ng-if="filters" href="generate-pdf-entite/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                <a ng-if="!filters" href="generate-pdf-entite" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                <a ng-if="filters" href="generate-excel-entite/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                <a ng-if="!filters" href="generate-excel-entite" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                                                <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('contratproprietaire', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                                                <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="pageChanged('contratproprietaire')"><span class="fa fa-search mr-1"></span>Filtrer</button>
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
                                <th class="whitespace-no-wrap">Entite</th>
                                <th class="whitespace-no-wrap text-center">Proprietaire</th>
                                <th class="whitespace-no-wrap text-center">Model de contrat</th>
                                <th class="whitespace-no-wrap text-center">Date</th>

                                <th class="whitespace-no-wrap text-center">Valeur Commission</th>
                                <th class="whitespace-no-wrap text-center">Pourcentage Commission</th>
                                <th class="whitespace-no-wrap text-center">TVA</th>
                                <th class="whitespace-no-wrap text-center">BRS</th>
                                <th class="whitespace-no-wrap text-center">TLV</th>
                                <th class="text-center whitespace-no-wrap text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x" ng-repeat="item in dataPage['contratproprietaires']">
                                <td>
                                    <div class="font-medium whitespace-no-wrap">@{{ item.entite.designation }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center text-center">@{{ item.proprietaire.prenom }} @{{ item.proprietaire.nom }} </div>
                                </td>
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

                                <td class="table-report__action w-56 ">
                                    <nav class="menu-leftToRight uk-flex text-center">
                                        <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1us-@{{ item.id }}">
                                        <label class="menu-open-button bg-white" for="menu-open1us-@{{ item.id }}">
                                            <span class="hamburger bg-template-1 hamburger-1"></span>
                                            <span class="hamburger bg-template-1 hamburger-2"></span>
                                            <span class="hamburger bg-template-1 hamburger-3"></span>
                                        </label>
                                        <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('contratproprietaire',item.id)" title="Supprimer">
                                            <span class="fa fa-trash-alt"></span>
                                        </button>
                                        <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalAdd('appartement')" title="Creation appartement">
                                            <span class="fal fa-plus"></span>
                                        </button>
                                        <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('contratproprietaire',item.id)" title="Modifier les infos">
                                            <span class="fal fa-edit"></span>
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
                    <select class="w-20 input box mt-1" ng-model="paginations['contratproprietaire'].entryLimit" ng-change="pageChanged('contratproprietaire')">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                    <nav aria-label="Page navigation">
                        <ul class="uk-pagination float-right" uib-pagination total-items="paginations['contratproprietaire'].totalItems" ng-model="paginations['contratproprietaire'].currentPage" max-size="paginations['contratproprietaire'].maxSize" items-per-page="paginations['contratproprietaire'].entryLimit" ng-change="pageChanged('contratproprietaire')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                    </nav>
                </div>
            </div>
            <!-- /PAGINATION -->
        </div>
    </div>
</div>
@endif