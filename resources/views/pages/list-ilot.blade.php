@if(auth()->user()->can('liste-ilot') || auth()->user()->can('modification-ilot') || auth()->user()->can('suppression-ilot'))
<div class="grid grid-cols-12 gap-6 subcontent classe_generale">
    <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-6">
            <div class="sm:flex items-center">
                <div class="p-3 intro-y theme-sombre rounded-2xl">
                    <i class="fa tooltip image_fit fa-map-marked-alt" aria-hidden="true"></i>
                </div>
                <div class="intro-y block sm:flex flex-1 items-center theme-dark border-raduis-right">
                    <h2 class="text-lg font-medium truncate mr-5 uppercase ml-2">
                        Ilots
                        <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{paginations['ilots'].totalItems}}</span>
                    </h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                        @if(auth()->user()->can('creation-ilot'))
                        <div id="basic-dropdown" class="relative z-30">
                            <div class="preview flex justify-center">
                                <div class="dropdown relative">
                                    <button class="dropdown-toggle custom-btn box flex items-center text-gray-700"><i class="fa fa-plus mr-2"></i> Ajouter</button>
                                    <div class="dropdown-box mt-40 absolute left-1/2 transform top-20 z-50" style="width: 140px" style="z-index: 50;" class="relative">
                                        <div class="dropdown-box__content box p-2" style="z-index: 50;" class="relative">
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('ilot')" title="Ajouter">Un Element</button>
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('ilot', {is_file_excel:true, title: 'ilot'})" title="Import">Fichier Excel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="intro-y grid grid-cols-12 gap-6 mt-52 sm:mt-5 md:mt-5">
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box zn-1">
                        <div class="p-3 zn-1" id="basic-accordion">
                            <div class="accordion">
                                <div class="accordion__pane border-gray-200">
                                    <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                        <div class="flex flex-wrap">
                                            <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter icon-i mr-1"></span>LES FILTRES</div>
                                            <div class="w-full md:w-1/2 px-3 text-right">
                                                <button class="custom-btn shadow">
                                                    <span class="w-5 h-5 flex items-center justify-center"><i class="fa icon-i fa-chevron-down"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                        <form class="bg-white grid p-3 mt-3">
                                            <div class="flex flex-wrap -mx-3">
                                                <div class="w-full md:w-1/2 px-3 mb-2">
                                                    <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline" id="searchoption_list_ilot">
                                                        <option value="">Rechercher par</option>
                                                        <option selected value="designation">Designation</option>
                                                    </select>
                                                </div>
                                                <div class="w-full md:w-1/2 px-3 mb-2">
                                                    <input class="shadow appearance-none border w-full rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="searchtexte_list_ilot" ng-model="searchtexte_list_ilot" autocomplete="off" type="text" placeholder="Texte ...">
                                                </div>

                                                <div class="w-full md:w-1/1 px-3 md:mb-0 mt-3">
                                                    <div class="flex flex-wrap -mx-3">
                                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                            <a ng-if="filters" href="generate-pdf-ilot/@{{filters}}" target="_blank" class="custom-btn rounded-full small bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                            <a ng-if="!filters" href="generate-pdf-ilot" target="_blank" class="custom-btn rounded-full small bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                            <a ng-if="filters" href="generate-excel-ilot/@{{filters}}" target="_blank" class="custom-btn rounded-full btn-shadow" title="Excel"><span class="fas fa-file-excel mr-2"></span>EXCEL</a>
                                                            <a ng-if="!filters" href="generate-excel-ilot" target="_blank" class="custom-btn rounded-full btn-shadow" title="Excel"><span class="fas fa-file-excel mr-2 text-success"></span>EXCEL</a>
                                                        </div>
                                                        <div class="w-full md:w-1/2 px-3 text-right">
                                                            <button type="button" class="custom-btn small border mr-1 btn-shadow-dark" ng-click="emptyform('ilot', true)"><span class="fa icon-i fa-filter mr-1"></span>Annuler</button>
                                                            <button type="button" class="custom-btn bg-theme-101 btn-shadow" ng-click="pageChanged('ilot')"><span class="fa icon-i fa-search mr-2"></span>Filtrer</button>
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

            <!-- TABLE -->
            <div class="overflow-table">
                <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                    <table class="table table-report sm:mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-no-wrap">Numero</th>
                                <th class="whitespace-no-wrap text-center">Adresse</th>
                                <th class="whitespace-no-wrap text-center">Nombre de villa</th>
                                <th class="text-center whitespace-no-wrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x" ng-repeat="item in dataPage['ilots']">
                                <td>
                                    <div class="font-medium whitespace-no-wrap">@{{ item.numero }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.adresse }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.nombrevilla }}</div>
                                </td>
                                <td class="table-report__action w-56">
                                    <nav class="menu-leftToRight uk-flex text-center">
                                        <input type="checkbox" class="menu-open" name="menu-open" id="menu-open1us-@{{ item.id }}">
                                        <label class="menu-open-button theme-sombre" for="menu-open1us-@{{ item.id }}">
                                            <span class="hamburger bg-white hamburger-1"></span>
                                            <span class="hamburger bg-white hamburger-2"></span>
                                            <span class="hamburger bg-white hamburger-3"></span>
                                        </label>
                                        <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('ilot',item.id)" title="Supprimer"><span class="fa fa-trash-alt"></span></button>
                                        <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('ilot',item.id)" title="Modifier les infos"><span class="fal fa-edit"></span></button>
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
                    <strong>Affichage par</strong>
                    <select class="w-20 input box mt-1" ng-model="paginations['ilots'].entryLimit" ng-change="pageChanged('ilot')">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-span-12 sm:col-span-12 md:col-span-9">
                    <nav aria-label="Page navigation">
                        <ul class="uk-pagination float-right" uib-pagination total-items="paginations['ilots'].totalItems" ng-model="paginations['ilots'].currentPage" max-size="paginations['ilots'].maxSize" items-per-page="paginations['ilots'].entryLimit" ng-change="pageChanged('ilot')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                    </nav>
                </div>
            </div>
            <!-- /PAGINATION -->
        </div>
    </div>
</div>
@endif