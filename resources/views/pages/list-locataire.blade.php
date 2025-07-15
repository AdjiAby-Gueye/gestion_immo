@if(auth()->user()->can('liste-locataire') || auth()->user()->can('modification-locataire') || auth()->user()->can('suppression-locataire'))
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
                        <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{paginations['locataire'].totalItems}}</span>
                    </h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                        @if(auth()->user()->can('creation-locataire'))
                        <div class="" id="basic-dropdown">
                            <div class="preview flex justify-center">
                                <div class="dropdown relative">
                                    <button class="dropdown-toggle   custom-btn box flex items-center text-gray-700"> <i class="fa fa-plus mr-2"></i> Ajouter </button>
                                    <div class="dropdown-box mt-40 absolute left-1/2 transform  top-20 z-50 " style="width: 140px">
                                        <div class="dropdown-box__content box  p-2">
                                            <button class="button dropdown-box-btn   flex items-center text-gray-700" ng-click="showModalAdd('locataire')" title="Ajouter"> Un Element</button>
                                            <button class="button dropdown-box-btn flex items-center text-gray-700" ng-click="showModalAdd('locataire', {is_file_excel:true, title: 'locataire'})" title="Import"> Fichier Excel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#locatairephysique" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Locataire physique</a>
                        <a data-toggle="tab" data-target="#locatairemorale" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Locataire morale</a>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-content__pane active" id="locatairephysique">
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
                                                                    <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="searchoption_list_locataire" name="searchoption">
                                                                        <option value="">Rechercher par</option>
                                                                        <option selected value="prenom">Prenom</option>
                                                                        <option value="nom">Nom</option>
                                                                        <option value="email">Email</option>
                                                                        <option value="telephoneportable1">Telephone portable 1</option>
                                                                        <option value="telephoneportable2">Telephone portable 2</option>
                                                                        <option value="telephonebureau">Telephone bureau</option>
                                                                    </select>
                                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 mb-2">
                                                                <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="searchtexte_list_locataire" ng-model="searchtexte_list_locataire" autocomplete="off" type="text" placeholder="Texte ... ">
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 mb-2">
                                                                <div class="inline-block relative w-full">
                                                                    <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="role_list_user">
                                                                        <option value="">Type</option>
                                                                        <option ng-repeat="item in dataPage['roles']" value="@{{ item.id }}"> @{{ item.name }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 mb-2">
                                                                <div class="inline-block relative w-full">
                                                                    <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="entite_list_locataire">
                                                                        <option value="">Entité</option>
                                                                        <option ng-repeat="item in dataPage['entites']" value="@{{ item.id }}"> @{{ item.designation }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 mb-2">
                                                                <div class="inline-block relative w-full">
                                                                    <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="secteuractivite_list_locataire">
                                                                        <option value="">Secteur d'activité</option>
                                                                        <option ng-repeat="item in dataPage['secteuractivites']" value="@{{ item.id }}"> @{{ item.designation }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                                                <div class="flex flex-wrap -mx-3">
                                                                    <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                                        <a ng-if="filters" href="generate-pdf-locataire/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                        <a ng-if="!filters" href="generate-pdf-locataire" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                        <a ng-if="filters" href="generate-excel-locataire/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                        <a ng-if="!filters" href="generate-excel-locataire" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                    </div>
                                                                    <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                                                        <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('locataire', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                                                        <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="pageChanged('locataire')"><span class="fa fa-search mr-1"></span>Filtrer</button>
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
                    {{-- <form action="{{route('mailToLocataire')}}" class="btn btn-primary " method="post">
                    <button type="submit">send Mail</button>
                    </form> --}}
                    <div class="overflow-table">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                            <table class="table table-report sm:mt-2">
                                <thead>
                                    <tr>
                                        <th class="whitespace-no-wrap">Prenom</th>
                                        <th class="whitespace-no-wrap">Nom</th>
                                        <th class="whitespace-no-wrap">Entité</th>
                                        <th class="whitespace-no-wrap text-center">Telephone portable 1</th>
                                        <th class="whitespace-no-wrap text-center">Telephone portable 2</th>
                                        <th class="whitespace-no-wrap text-center">Telephone bureau</th>
                                        <th class="whitespace-no-wrap text-center">Email</th>
                                        <th class="whitespace-no-wrap text-center">Profession</th>
                                        <th class="whitespace-no-wrap text-center">CNI</th>
                                        <th class="whitespace-no-wrap text-center">Passport</th>
                                        <th class="text-center whitespace-no-wrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataPage['locataires']" ng-if="item.typelocataire_id == '1' ">
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.prenom }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.nom }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.entite.designation }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.telephoneportable1 }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.telephoneportable2 }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.telephonebureau }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.email }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.profession }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.cni }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.passeport }}</div>
                                        </td>
                                        <td class="table-report__action w-56">
                                            <nav class="menu-leftToRight uk-flex text-center">
                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1us-@{{ item.id }}">
                                                <label class="menu-open-button bg-white" for="menu-open1us-@{{ item.id }}">
                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                </label>
                                                <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('locataire',item.id)" title="Supprimer">
                                                    <span class="fa fa-trash-alt"></span>
                                                </button>
                                                <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('locataire',item.id)" title="Modifier les infos">
                                                    <span class="fal fa-edit"></span>
                                                </button>
                                                <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalDetail('locataire',item.id)" title="details">
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
                    <div class="col-span-12 grid grid-cols-12 gap-4 mt-3">
                        <div class="col-span-12 sm:col-span-12 md:col-span-3">
                            <span>Affichage par</span>
                            <select class="w-20 input box mt-1" ng-model="paginations['locataire'].entryLimit" ng-change="pageChanged('locataire')">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                            <nav aria-label="Page navigation">
                                <ul class="uk-pagination float-right" uib-pagination total-items="paginations['locataire'].totalItems" ng-model="paginations['locataire'].currentPage" max-size="paginations['locataire'].maxSize" items-per-page="paginations['locataire'].entryLimit" ng-change="pageChanged('locataire')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                            </nav>
                        </div>
                    </div>
                    <!-- /PAGINATION -->
                </div>
                <div class="tab-content__pane" id="locatairemorale">
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
                                                                    <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="searchoption_list_locataire" name="searchoption">
                                                                        <option value="">Rechercher par</option>
                                                                        <option selected value="nomentreprise">Nom de l'entreprise</option>
                                                                        <option value="adresseentreprise">Adresse</option>
                                                                        <option value="ninea">Ninea</option>
                                                                        <option value="numerorg">Numero RG</option>
                                                                        <option value="emailpersonneacontacter">Email personne a contacter</option>
                                                                        <option value="telephone1personneacontacter">Telephone 1 personne a contacter</option>
                                                                        <option value="telephone2personneacontacter">Telephone 2 personne a contacter</option>
                                                                    </select>
                                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 mb-2">
                                                                <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="searchtexte_list_locataire" ng-model="searchtexte_list_locataire" autocomplete="off" type="text" placeholder="Texte ... ">
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 mb-2">
                                                                <div class="inline-block relative w-full">
                                                                    <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="role_list_user">
                                                                        <option value="">Type</option>
                                                                        <option ng-repeat="item in dataPage['roles']" value="@{{ item.id }}"> @{{ item.name }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 mb-2">
                                                                <div class="inline-block relative w-full">
                                                                    <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="entite_list_locataire">
                                                                        <option value="">Entité</option>
                                                                        <option ng-repeat="item in dataPage['entites']" value="@{{ item.id }}"> @{{ item.designation }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                                                <div class="flex flex-wrap -mx-3">
                                                                    <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                                        <a ng-if="filters" href="generate-pdf-locataire/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                        <a ng-if="!filters" href="generate-pdf-locataire" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                        <a ng-if="filters" href="generate-excel-locataire/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                        <a ng-if="!filters" href="generate-excel-locataire" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                    </div>
                                                                    <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                                                        <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('locataire', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                                                        <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="pageChanged('locataire')"><span class="fa fa-search mr-1"></span>Filtrer</button>
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
                                        <th class="whitespace-no-wrap">Nom de l'entreprise</th>
                                        <th class="whitespace-no-wrap">Entité</th>
                                        <th class="whitespace-no-wrap text-center">Adresse</th>
                                        <th class="whitespace-no-wrap text-center">Ninea</th>
                                        <th class="whitespace-no-wrap text-center">Numero RG</th>
                                        <th class="whitespace-no-wrap text-center">Representant</th>
                                        <th class="whitespace-no-wrap text-center">Fonction representant</th>
                                        <th class="whitespace-no-wrap text-center">Prenom personne a contacter</th>
                                        <th class="whitespace-no-wrap text-center">Email personne a contacter</th>
                                        <th class="whitespace-no-wrap text-center">Telephone 1</th>
                                        <th class="whitespace-no-wrap text-center">Telephone 2</th>
                                        <th class="text-center whitespace-no-wrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataPage['locataires']" ng-if="item.typelocataire_id == '2' ">
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.nomentreprise }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.entite.designation }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.adresseentreprise }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.ninea }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.numerorg }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.personnehabiliteasigner }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.fonctionpersonnehabilite }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.prenompersonneacontacter }} @{{ item.nompersonneacontacter }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.emailpersonneacontacter }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.telephone1personneacontacter }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.telephone2personneacontacter }}</div>
                                        </td>
                                        <td class="table-report__action w-56">
                                            <nav class="menu-leftToRight uk-flex text-center">
                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1us-@{{ item.id }}">
                                                <label class="menu-open-button bg-white" for="menu-open1us-@{{ item.id }}">
                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                </label>
                                                <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('locataire',item.id)" title="Supprimer">
                                                    <span class="fa fa-trash-alt"></span>
                                                </button>
                                                <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('locataire',item.id)" title="Modifier les infos">
                                                    <span class="fal fa-edit"></span>
                                                </button>
                                                <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalDetail('locataire',item.id)" title="details">
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
                    <div class="col-span-12 grid grid-cols-12 gap-4 mt-3">
                        <div class="col-span-12 sm:col-span-12 md:col-span-3">
                            <span>Affichage par</span>
                            <select class="w-20 input box mt-1" ng-model="paginations['locataire'].entryLimit" ng-change="pageChanged('locataire')">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                            <nav aria-label="Page navigation">
                                <ul class="uk-pagination float-right" uib-pagination total-items="paginations['locataire'].totalItems" ng-model="paginations['locataire'].currentPage" max-size="paginations['locataire'].maxSize" items-per-page="paginations['locataire'].entryLimit" ng-change="pageChanged('locataire')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                            </nav>
                        </div>
                    </div>
                    <!-- /PAGINATION -->
                </div>
            </div>
        </div>
    </div>
</div>

@endif