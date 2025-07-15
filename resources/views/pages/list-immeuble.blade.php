@if(auth()->user()->can('liste-immeuble') || auth()->user()->can('modification-immeuble') || auth()->user()->can('suppression-immeuble'))
<div class="grid grid-cols-12 gap-6 subcontent classe_generale">
    <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-6 ">
            <div class="sm:flex items-center">
                <div class="p-3 intro-y theme-sombre rounded-2xl">
                    <i class="fa tooltip image_fit fa-building" aria-hidden="true"></i>
                </div>
                <div class="intro-y block sm:flex  flex-1 items-center theme-dark border-raduis-right">
                    <h2 class="text-lg font-medium truncate mr-5 uppercase ml-2">
                        Immeubles
                        <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{paginations['immeuble'].totalItems}}</span>
                    </h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                        @if(auth()->user()->can('creation-immeuble'))
                        <div id="basic-dropdown" class="relative z-50" style="z-index: 1000 !important;">
                            <div class="preview flex justify-center">
                                <div class="dropdown relative">
                                    <button class="dropdown-toggle   custom-btn box flex items-center text-gray-700"><i class="fa fa-plus mr-2"></i> Ajouter</button>
                                    <div class="dropdown-box mt-40 absolute left-1/2 transform top-20 z-50" style="width: 140px">
                                        <div class="dropdown-box__content box p-2">
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('immeuble')" title="Ajouter">Un Element</button>
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('immeuble', {is_file_excel:true, title: 'immeuble'})" title="Import">Fichier Excel</button>
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('securite', {is_file_excel:true, title: 'securite immeuble'})" title="Import Securite">Securite Excel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div>
            </div>


            <div class="intro-y grid zn-1 grid-cols-12 gap-6 mt-52 sm:mt-5 md:mt-5">
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box">
                        <div class="p-3" id="basic-accordion">
                            <div class="preview">
                                <div class="accordion" style="z-index: -1 !important;">
                                    <div class="accordion__pane border-gray-200">
                                        <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                            <div class="flex flex-wrap">
                                                <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter icon-i mr-1"></span>LES FILTRES</div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="custom-btn shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center"> <i class="fa icon-i fa-chevron-down"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <form class="bg-white grid p-3 mt-3">
                                                <div class="flex flex-wrap -mx-3">
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <select class="select2 block w-full rounded-full shadow" id="searchoption_list_immeuble">
                                                            <option value="">Rechercher par</option>
                                                            <option value="nom">Désignation</option>
                                                            <option value="adresse">Adresse</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <input class="shadow border w-full rounded py-2 px-3" id="searchtexte_list_immeuble" ng-model="searchtexte_list_immeuble" type="text" placeholder="Texte ...">
                                                    </div>
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <select class="select2 block w-full shadow" id="structureimmeuble_list_immeuble">
                                                            <option value="">Structure</option>
                                                            <option ng-repeat="item in dataPage['structureimmeubles']" value="@{{ item.id }}"> @{{ item.designation }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-full md:w-1/1 px-3 mt-3">
                                                        <div class="flex flex-wrap -mx-3">
                                                            <div class="w-full md:w-1/2 px-3">
                                                                <a ng-if="filters" href="generate-pdf-immeuble/@{{filters}}" target="_blank" class="custom-btn rounded-full small bg-danger mr-1 btn-shadow-dark"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                <a ng-if="!filters" href="generate-pdf-immeuble" target="_blank" class="custom-btn rounded-full small bg-danger mr-1 btn-shadow-dark"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                <a ng-if="filters" href="generate-excel-immeuble/@{{filters}}" target="_blank" class="custom-btn rounded-full btn-shadow"><span class="fas fa-file-excel mr-2"></span>EXCEL</a>
                                                                <a ng-if="!filters" href="generate-excel-immeuble" target="_blank" class="custom-btn rounded-full btn-shadow"><span class="fas text-success fa-file-excel mr-2"></span>EXCEL</a>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 text-right">
                                                                <button type="button" class="custom-btn small border mr-1 btn-shadow-dark" ng-click="emptyform('immeuble', true)"><span class="fa icon-i fa-filter mr-1"></span>Annuler</button>
                                                                <button type="button" class="custom-btn bg-theme-101 btn-shadow" ng-click="pageChanged('immeuble')"><span class="fa icon-i fa-search mr-2"></span>Filtrer</button>
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
            </div>

            <div class="overflow-table">
                <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                    <table class="table table-report sm:mt-2">
                        <thead class="rounded">
                            <tr class="thead rounded">
                                <th>Désignation</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Adresse</th>
                                <th class="text-center">Nombre d'appartements</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x" ng-repeat="item in dataPage['immeubles']">
                                <td class="font-medium">@{{ item.nom }}</td>
                                <td class="font-medium text-center">@{{ item.structureimmeuble.designation }}</td>
                                <td class="font-medium text-center">@{{ item.adresse }}</td>
                                <td class="font-medium text-center">@{{ item['appartements'].length }}</td>
                                <td class="table-report__action w-56">
                                    <nav class="menu-leftToRight uk-flex text-center">
                                        <input type="checkbox" class="menu-open" id="menu-open1im-@{{ item.id }}">
                                        <label class="menu-open-button theme-sombre" for="menu-open1im-@{{ item.id }}">
                                            <span class="hamburger bg-white hamburger-1"></span>
                                            <span class="hamburger bg-white hamburger-2"></span>
                                            <span class="hamburger bg-white hamburger-3"></span>
                                        </label>
                                        <button class="menu-item btn border-0 bg-secondary text-white fsize-16" ng-click="showModalAdd('appartement',{is_file_excel:false, title:null},item.id)" title="Ajout appartement">
                                            <span class="fa fa-building"></span>
                                        </button>
                                        <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('immeuble',item.id)" title="Supprimer">
                                            <span class="fa fa-trash-alt"></span>
                                        </button>
                                        <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('immeuble',item.id)" title="Modifier">
                                            <span class="fal fa-edit"></span>
                                        </button>
                                        <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalDetail('immeuble',item.id)" title="Détails">
                                            <span class="fas fa-info"></span>
                                        </button>
                                    </nav>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PAGINATION -->
            <div class="col-span-12 grid grid-cols-12 gap-4 my-3">
                <div class="col-span-12 sm:col-span-12 md:col-span-3">
                    <strong class="mr-2">Affichage par</strong>
                    <select class="w-20 input box mt-1" ng-model="paginations['immeuble'].entryLimit" ng-change="pageChanged('immeuble')">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-span-12 sm:col-span-12 md:col-span-9">
                    <nav aria-label="Page navigation">
                        <ul class="uk-pagination float-right" uib-pagination total-items="paginations['immeuble'].totalItems" ng-model="paginations['immeuble'].currentPage" max-size="paginations['immeuble'].maxSize" items-per-page="paginations['immeuble'].entryLimit" ng-change="pageChanged('immeuble')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                    </nav>
                </div>
            </div>
            <!-- /PAGINATION -->

        </div>
    </div>
</div>
@endif