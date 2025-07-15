@if(auth()->user()->can('liste-villa') || auth()->user()->can('modification-villa') || auth()->user()->can('suppression-villa'))
<div class="grid grid-cols-12 gap-6 subcontent classe_generale">
    <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-6">
            <div class="sm:flex items-center">
                <div class="p-3 intro-y theme-sombre rounded-2xl">
                    <i class="fa tooltip image_fit fa-home" aria-hidden="true"></i>
                </div>
                <div class="intro-y block sm:flex flex-1 items-center theme-dark border-raduis-right">
                    <h2 class="text-lg font-medium truncate mr-5 uppercase ml-2">
                        Gestion des Villas
                        <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{paginations['villa'].totalItems}}</span>
                    </h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                        @if(auth()->user()->can('creation-villa'))
                        <div class="" id="basic-dropdown">
                            <div class="preview flex justify-center">
                                <div class="dropdown relative">
                                    <button class="dropdown-toggle custom-btn box flex items-center text-gray-700">
                                        <i class="fa fa-plus mr-2"></i> Ajouter
                                    </button>
                                    <div class="dropdown-box mt-40 absolute left-1/2 transform top-20 z-50" style="width: 140px">
                                        <div class="dropdown-box__content box p-2">
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('villa')" title="Ajouter">
                                                Un Element
                                            </button>
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('villa', {is_file_excel:true, title: 'villa'})" title="Import">
                                                Fichier Excel
                                            </button>
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
                                                <div class="w-full md:w-1/2 px-3 self-center">
                                                    <span class="fa fa-filter icon-i mr-1"></span>LES FILTRES
                                                </div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="custom-btn shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center">
                                                            <i class="fa icon-i fa-chevron-down"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <form class="bg-white grid p-3 mt-3">
                                                <div class="flex flex-wrap -mx-3">
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <div class="inline-block relative w-full">
                                                            <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline" id="searchoption_list_villa" name="searchoption">
                                                                <option value="">Rechercher par</option>
                                                                <option selected value="lot">Lot</option>
                                                                <option value="maturite">Maturité</option>
                                                                <option value="prixvilla">Prix villa</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <input class="shadow appearance-none border w-full rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline-none" id="searchtexte_list_villa" ng-model="searchtexte_list_villa" autocomplete="off" type="text" placeholder="Texte ...">
                                                    </div>
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <div class="inline-block relative w-full">
                                                            <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="ilot_list_villa">
                                                                <option value="">Ilot</option>
                                                                <option ng-repeat="item in dataPage['ilots']" value="@{{ item.id }}">Nº @{{ item.numero }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <div class="inline-block relative w-full">
                                                            <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typevilla_list_villa">
                                                                <option value="">Type Villa</option>
                                                                <option ng-repeat="item in dataPage['typevillas']" value="@{{ item.id }}">@{{ item.designation }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="w-full md:w-1/1 px-3 md:mb-0 mt-3">
                                                        <div class="flex flex-wrap -mx-3">
                                                            <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                                <a ng-if="filters" href="generate-pdf-villa/@{{filters}}" target="_blank" class="custom-btn rounded-full small bg-danger mr-1 btn-shadow-dark" title="PDF">
                                                                    <span class="fas fa-file-pdf mr-1"></span>PDF
                                                                </a>
                                                                <a ng-if="!filters" href="generate-pdf-villa" target="_blank" class="custom-btn rounded-full small bg-danger mr-1 btn-shadow-dark" title="PDF">
                                                                    <span class="fas fa-file-pdf mr-1"></span>PDF
                                                                </a>
                                                                <a ng-if="filters" href="generate-excel-villa/@{{filters}}" target="_blank" class="custom-btn rounded-full btn-shadow" title="Excel">
                                                                    <span class="fas text-success fa-file-excel mr-2"></span>EXCEL
                                                                </a>
                                                                <a ng-if="!filters" href="generate-excel-villa" target="_blank" class="custom-btn rounded-full btn-shadow" title="Excel">
                                                                    <span class="fas text-success fa-file-excel mr-2"></span>EXCEL
                                                                </a>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                                                <button type="button" class="custom-btn small border mr-1 btn-shadow-dark" ng-click="emptyform('villa', true)">
                                                                    <span class="fa icon-i fa-filter mr-1"></span>Annuler
                                                                </button>
                                                                <button type="button" class="custom-btn bg-theme-101 btn-shadow" ng-click="pageChanged('villa')">
                                                                    <span class="fa icon-i fa-search mr-2"></span>Filtrer
                                                                </button>
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
                        <thead class="rounded">
                            <tr class="thead rounded">
                                <th class="whitespace-no-wrap uppercase">Entité</th>
                                <th class="whitespace-no-wrap text-center uppercase">Ilot</th>
                                <th class="whitespace-no-wrap text-center uppercase">Lot</th>
                                <th class="whitespace-no-wrap text-center uppercase">Propriétaire</th>
                                <th class="whitespace-no-wrap text-center uppercase">Type Villa</th>
                                <th class="whitespace-no-wrap text-center uppercase">Prix</th>
                                <th class="text-center whitespace-no-wrap uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x" ng-repeat="item in dataPage['villas']">
                                <td>
                                    <div class="font-medium whitespace-no-wrap">@{{ item.entite_id ? item['entite'].designation : "" }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">Nº @{{ item.ilot_id ? item['ilot'].numero : "" }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.lot }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item['proprietaire'].prenom }} @{{ item['proprietaire'].nom }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item['typevilla'].designation }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.prixvillaformat }} FCFA</div>
                                </td>
                                <td class="table-report__action w-56">
                                    <nav class="menu-leftToRight uk-flex text-center">
                                        <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1villa-@{{ item.id }}">
                                        <label class="menu-open-button theme-sombre" for="menu-open1villa-@{{ item.id }}">
                                            <span class="hamburger bg-white hamburger-1"></span>
                                            <span class="hamburger bg-white hamburger-2"></span>
                                            <span class="hamburger bg-white hamburger-3"></span>
                                        </label>
                                        <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('villa',item.id)" title="Supprimer">
                                            <span class="fa fa-trash-alt"></span>
                                        </button>
                                        <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('villa',item.id)" title="Modifier">
                                            <span class="fal fa-edit"></span>
                                        </button>
                                        <button class="menu-item btn border-0 bg-primary text-white fsize-16" ng-click="showModalDetail('villa',item.id)" title="Détails">
                                            <span class="fas fa-info-circle"></span>
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
                    <select class="w-20 input box mt-1" ng-model="paginations['villa'].entryLimit" ng-change="pageChanged('villa')">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                    <nav aria-label="Page navigation">
                        <ul class="uk-pagination float-right" uib-pagination total-items="paginations['villa'].totalItems" ng-model="paginations['villa'].currentPage" max-size="paginations['villa'].maxSize" items-per-page="paginations['villa'].entryLimit" ng-change="pageChanged('villa')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false">
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /PAGINATION -->
        </div>
    </div>
</div>
@endif