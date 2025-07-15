@if(auth()->user()->can('liste-role') || auth()->user()->can('modification-role') || auth()->user()->can('suppression-role'))
<div class="col-span-12 lg:col-span-4 classe_generale">
    <div class="intro-y pr-1 mt-4">
        <div class="box p-2">
            <div class="pos__tabs nav-tabs justify-center flex">
                @if(auth()->user()->can('creation-role'))
                <a data-toggle="tab" data-target="#profil" href="javascript:;" class="flex-1 py-2  text-center active" ng-click="pageChanged('role')">Profils</a>
                <a data-toggle="tab" data-target="#permission" href="javascript:;" class="flex-1 py-2  text-center" ng-click="pageChanged('permission')">Permissions</a>
                @endif
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-content__pane active" id="profil">
            <div class="grid grid-cols-12 gap-6 subcontent">
                <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
                    <div class="col-span-12 mt-6">
                        <div class="sm:flex  items-center">
                            <div class="p-3 intro-y theme-sombre  rounded-2xl">
                                <i class="fa tooltip image_fit fa-gavel" aria-hidden="true"></i>

                            </div>
                            <div class="intro-y block sm:flex  flex-1 items-center theme-dark border-raduis-right">
                                <h2 class="text-lg font-medium truncate mr-5 uppercase ml-2">
                                    Profils
                                    <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{paginations['role'].totalItems}}</span>
                                </h2>
                                <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                                    <div class="" id="basic-dropdown">
                                        <div class="preview flex justify-center">
                                            <div class="dropdown relative">
                                                <button class="dropdown-toggle   custom-btn box flex items-center text-gray-700"> <i class="fa fa-plus mr-2"></i> Ajouter </button>
                                                <div class="dropdown-box mt-40 absolute left-1/2 transform  top-20 z-50 " style="width: 140px">
                                                    <div class="dropdown-box__content box  p-2">
                                                        <button class="button dropdown-box-btn   flex items-center text-gray-700" ng-click="showModalAdd('role')" title="Ajouter"> Un Element</button>
                                                        <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('role', {is_file_excel:true, title: 'role'})" title="Import"> Fichier Excel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
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
                                                                <div class="w-full md:w-1/2 px-3 mb-36">
                                                                    <div class="inline-block relative w-full">
                                                                        <select class="select2 block  appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="searchoption_list_role" ng-model="searchoption_list_role" name="searchoption">
                                                                            <option value="">Rechercher par</option>
                                                                            <option value="name">Désignation</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="w-full md:w-1/2 px-3 mb-2">
                                                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="searchtexte_list_role" ng-model="searchtexte_list_role" autocomplete="off" type="text" placeholder="Texte ... ">
                                                                </div>

                                                                <div class="w-full md:w-1/1 px-3 md:mb-0  mt-3">
                                                                    <div class="flex flex-wrap -mx-3">
                                                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                                            <a ng-if="filters" href="generate-pdf-role/@{{filters}}" target="_blank" class="custom-btn rounded-full small   bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                            <a ng-if="!filters" href="generate-pdf-role" target="_blank" class="custom-btn rounded-full small   bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                            <a ng-if="filters" href="generate-excel-role/@{{filters}}" target="_blank" class="custom-btn rounded-full   btn-shadow" title="Excel"><span class="fas fa-file-excel mr-2"></span>EXCEL</a>
                                                                            <a ng-if="!filters" href="generate-excel-role" target="_blank" class="custom-btn rounded-full   btn-shadow" title="Excel"><span class="fas text-success fa-file-excel mr-2"></span>EXCEL</a>
                                                                        </div>

                                                                        <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                                                            <button type="button" class="custom-btn small border mr-1 btn-shadow-dark" ng-click="emptyform('role', true)"><span class="fa icon-i fa-filter mr-1"></span>Annuler</button></button>
                                                                            <button type="button" class="custom-btn bg-theme-101  btn-shadow" ng-click="pageChanged('role')"><span class="fa icon-i  fa-search mr-2"></span>Filtrer</button>
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
                                            <th class="whitespace-no-wrap">Désignation</th>
                                            <th class="whitespace-no-wrap text-center">Nombres de permissions</th>
                                            <th class="text-center whitespace-no-wrap">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataPage['roles']">
                                            <td>
                                                <div class="font-medium whitespace-no-wrap">@{{item.name}}</div>
                                            </td>
                                            <td class="text-center text-center">@{{item.permissions.length}}</td>


                                            <td class="table-report__action w-56">
                                                <nav class="menu-leftToRight uk-flex text-center">
                                                    <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1us-@{{ item.id }}">
                                                    <label class="menu-open-button theme-sombre" for="menu-open1us-@{{ item.id }}">
                                                        <span class="hamburger bg-white hamburger-1"></span>
                                                        <span class="hamburger bg-white hamburger-2"></span>
                                                        <span class="hamburger bg-white hamburger-3"></span>
                                                    </label>
                                                    <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('role',item.id)" title="Supprimer">
                                                        <span class="fa fa-trash-alt"></span>
                                                    </button>
                                                    <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('role',item.id)" title="Modifier les infos">
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
                                <strong class="mr-2">Affichage par</strong>
                                <select class="w-20 input box mt-1" ng-model="paginations['role'].entryLimit" ng-change="pageChanged('role')">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                                <nav aria-label="Page navigation">
                                    <ul class="uk-pagination float-right" uib-pagination total-items="paginations['role'].totalItems" ng-model="paginations['role'].currentPage" max-size="paginations['role'].maxSize" items-per-page="paginations['role'].entryLimit" ng-change="pageChanged('role')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                                </nav>
                            </div>
                        </div>
                        <!-- /PAGINATION -->
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content__pane" id="permission">
            <div class="grid grid-cols-12 gap-6 subcontent">
                <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
                    <div class="col-span-12 mt-6">


                        <div class="sm:flex  items-center">
                            <div class="p-3 intro-y theme-sombre  rounded-2xl">
                                <i class="fa tooltip image_fit fa-gavel" aria-hidden="true"></i>
                            </div>
                            <div class="intro-y block sm:flex py-1  flex-1 items-center theme-dark border-raduis-right">
                                <h2 class="text-lg font-medium truncate mr-5 uppercase ml-2">
                                    Permissions
                                    <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{dataPage['permissions'].length}}</span>
                                </h2>
                                <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                                    <div class="" id="basic-dropdown">
                                        <div class="preview flex justify-center">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                        </div>

                        <div class="intro-y grid grid-cols-12 gap-6 mt-52 sm:mt-5 md:mt-5">
                            <div class="col-span-12 lg:col-span-12">
                                <div class="intro-y box">
                                    <div class="p-3" id="basic-accordion">
                                        <div class="preview">
                                            <div class="accordion">
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
                                                                    <div class="inline-block relative w-full">
                                                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="searchoption_list_permission" ng-model="searchoption_list_permission" name="searchoption">
                                                                            <option value="">Rechercher par</option>
                                                                            <option value="designation">Désignation</option>
                                                                        </select>
                                                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="w-full md:w-1/2 px-3 mb-2">
                                                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none   focus:shadow-outline-none" id="searchtexte_list_permission" ng-model="searchtexte_list_permission" autocomplete="off" type="text" placeholder="Texte ... ">
                                                                </div>

                                                                <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                                                    <div class="flex flex-wrap -mx-3">
                                                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                                            <a ng-if="filters" href="generate-pdf-permission/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                            <a ng-if="!filters" href="generate-pdf-permission" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                            <a ng-if="filters" href="generate-excel-permission/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                            <a ng-if="!filters" href="generate-excel-permission" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                        </div>
                                                                        <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                                                            <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('permission', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                                                            <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="pageChanged('permission')"><span class="fa fa-search mr-1"></span>Filtrer</button>
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
                                            <th class="whitespace-no-wrap">Appellation</th>
                                            <th class="whitespace-no-wrap text-center">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataPage['permissions']">
                                            <td>
                                                <div class="font-medium whitespace-no-wrap">@{{item.name}}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{item.display_name}}</div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>




                        <!-- PAGINATION -->
                        <div class="col-span-12 grid grid-cols-12 gap-4 mt-3">

                            <div class="col-span-12 sm:col-span-12 md:col-span-3">
                                <strong class="mr-2">Affichage par</strong>
                                <select class="w-20 input box mt-1" ng-model="paginations['permission'].entryLimit" ng-change="pageChanged('permission')">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                                <nav aria-label="Page navigation">
                                    <ul class="uk-pagination float-right" uib-pagination total-items="paginations['permission'].totalItems" ng-model="paginations['permission'].currentPage" max-size="paginations['permission'].maxSize" items-per-page="paginations['permission'].entryLimit" ng-change="pageChanged('permission')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                                </nav>
                            </div>
                        </div>
                        <!-- /PAGINATION -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif