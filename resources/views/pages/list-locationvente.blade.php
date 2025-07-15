@if(auth()->user()->can('liste-locationvente') || auth()->user()->can('modification-locationvente') || auth()->user()->can('suppression-locationvente'))
<div class="grid grid-cols-12 gap-6 subcontent classe_generale">
    <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-6">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5 uppercase">
                    Contrat de location / vente
                    <span class="inline-block bg-white text-theme-106 ml-2 px-3 rounded-full">@{{paginations['locationvente'].totalItems}}</span>
                </h2>

                <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                    @if(auth()->user()->can('creation-locationvente'))
                    <div class="" id="basic-dropdown">
                        <div class="preview flex justify-center">
                            <div class="dropdown relative">
                                <button class="dropdown-toggle button button box flex items-center text-gray-700"> <i class="fa fa-plus mr-2"></i> Ajouter </button>
                                <div class="dropdown-box mt-40 absolute top-0 right-0 z-20" style="width: 125px">
                                    <div class="dropdown-box__content box p-2">
                                        <button class="button  flex items-center text-gray-700" ng-click="showModalAdd('locationvente')" title="Ajouter"> Un Element</button>
                                        <button class="button  flex items-center text-gray-700" ng-click="showModalAdd('contrat', {is_file_excel:true, title: 'contrat'})" title="Import"> Fichier Excel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3">
                        <button type="button" ng-click="myRedirectUrl('list-echeanceencours')"  class="button w-30 btn btn-success text-white shadow" style="background-color: #892301 "><i class="fa fa-paper-plane"></i> échéance encours  </button>
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
                                                            <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                                                    id="searchoption_list_locationvente" name="searchoption">
                                                                <option value="">Rechercher par</option>
                                                                <option selected value="descriptif">Descriptif</option>
                                                                <option value="lot">Numéro de lot</option>
                                                                <option value="montantloyer">Montant du loyer</option>
                                                            </select>
                                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="searchtexte_list_locationvente" ng-model="searchtexte_list_locationvente" autocomplete="off" type="text" placeholder="Texte ... ">
                                                    </div>
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <div class="inline-block relative w-full">
                                                            <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                                                    id="locataire_list_locationvente">
                                                                <option value="">Locataire</option>
                                                                <option ng-repeat="item in dataPage['locataires']" ng-if="item.typelocataire_id == '1' " value="@{{ item.id }}"> @{{ item.prenom }} @{{ item.nom }}</option>
                                                                <option ng-repeat="item in dataPage['locataires']" ng-if="item.typelocataire_id == '2' " value="@{{ item.id }}"> @{{ item.nomentreprise }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="w-full md:w-1/2 px-3 mb-2">
                                                        <div class="inline-block relative w-full">
                                                            <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                                                    id="ilot_list_locationvente">
                                                                <option value="">Ilot</option>
                                                                <option ng-repeat="item in dataPage['ilots']" value="@{{ item.id }}">N° @{{ item.numero }}</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                                        <div class="flex flex-wrap -mx-3">
                                                            <div class="w-full md:w-1/2 px-3 md:mb-0">
                                                                <a ng-if="filters" href="generate-pdf-contrat/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                <a ng-if="!filters" href="generate-pdf-contrat" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                                                <a ng-if="filters" href="generate-excel-locationvente/@{{filters}}" target="_blank"   class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                                <a ng-if="!filters" href="generate-excel-locationvente" target="_blank"   class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                                            </div>
                                                            <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                                                <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('locationvente', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                                                <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="pageChanged('locationvente')"><span class="fa fa-search mr-1"></span>Filtrer</button>
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
                            <th class="whitespace-no-wrap">Descriptif</th>
                            <th class="whitespace-no-wrap text-center">Locataire</th>
                            <th class="whitespace-no-wrap text-center">Ilot</th>
                            <th class="whitespace-no-wrap text-center">Lot</th>
                            {{-- <th class="whitespace-no-wrap text-center">Adresse du vila</th> --}}
                            {{-- <th class="whitespace-no-wrap text-center">Etat lieux</th> --}}
                            {{-- <th class="whitespace-no-wrap text-center">Date remise clés</th> --}}
                            <th class="whitespace-no-wrap text-center">Prix villa</th>
                            <th class="whitespace-no-wrap text-center">Apport initial</th>
                            <th class="whitespace-no-wrap text-center">Etat du contrat</th>
                            <th class="whitespace-no-wrap text-center">Périodicité</th>

                            <th class="text-center whitespace-no-wrap">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x" ng-repeat="item in dataPage['locationventes']" >
                                <td>
                                    <div class="font-medium whitespace-no-wrap">@{{ item.descriptif }}</div>
                                </td>
                                <td>
                                    <div ng-if="item.locataire.prenom" class="font-medium whitespace-no-wrap text-center">@{{ item['locataire'].prenom }} @{{ item['locataire'].nom }}</div>
                                    <div ng-if="item.locataire.nomentreprise" class="font-medium whitespace-no-wrap text-center">@{{ item['locataire'].nomentreprise }}</div>
                                    <div  class="font-medium whitespace-no-wrap text-center">@{{ item.email }}</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">N° @{{ item.appartement.ilot.numero }} </div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">N° @{{ item.appartement.lot }}  </div>
                                </td>
                                {{-- <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.appartement.ilot.adresse }}</div>
                                </td> --}}

                                {{-- <td>
                                    <div class="font-medium whitespace-no-wrap text-center">hh  hh </div>
                                    <div ng-if="!item.appartement.etatlieux" class="font-medium whitespace-no-wrap text-center">Pas d'etat lieux</div>
                                </td> --}}
                                {{-- <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.dateremiseclesformat }}</div>
                                </td> --}}

                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.prixvillaformat }} FCFA</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.apportinitial_format ? item.apportinitial_format : 0 }} FCFA</div>
                                </td>
                                {{-- loyerpercusuite  mensualitesuite  prixvilla mensualite apportinitial apportipontuel --}}
                                {{-- <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.apportinitial }}</div>
                                </td> --}}


                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">
                                        <div class="font-medium whitespace-no-wrap text-center">
                                            <span class="px-2 rounded-full @{{ item.etat_badge }} text-white">@{{ item.etat_text }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">@{{ item.periodicite.designation }}</div>
                                </td>



                                <td class="table-report__action w-56">
                                    <nav class="menu-leftToRight uk-flex text-center">
                                        <input type="checkbox" href="#" class="menu-open" name="menu-open"  id="menu-open1us-@{{ item.id }}">
                                        <label class="menu-open-button bg-white" for="menu-open1us-@{{ item.id }}">
                                            <span class="hamburger bg-template-1 hamburger-1"></span>
                                            <span class="hamburger bg-template-1 hamburger-2"></span>
                                            <span class="hamburger bg-template-1 hamburger-3"></span>
                                        </label>
                                            <button class="menu-item btn border-0 bg-info text-white fsize-16"   ng-click="showModalUpdate('locationvente',item.id)" title="Modifier les infos">
                                                <span class="fal fa-edit"></span>
                                            </button>
                                            {{-- <button class="menu-item btn border-0 bg-primary text-white fsize-16" ng-if="infosUserConnected.roles[0].id != 2 && item.status == '1'"  ng-click="showModalAdd('caution',{is_file_excel:false, title:null},item.id)" title="ajouter caution">
                                                <span class="fad fa-money-check-alt"></span>
                                            </button> --}}
                                            <button class="menu-item btn border-0 bg-dmd text-white fsize-16" ng-if="infosUserConnected.roles[0].id != 2 && item.status == '1'"  ng-click="showModalAdd('assurance',{is_file_excel:false, title:null}, item.id)" title="ajouter assurance">
                                                <span class="far fa-house-damage"></span>
                                            </button>
                                            {{-- <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-if="item['appartement'].etatlieu == '0' && infosUserConnected.roles[0].id != 2" ng-click="showModalAdd('etatlieu',{is_file_excel:false, title:null},item.appartement.id)" title="Etat des lieux d'entrée">
                                                <span class="fa fa-file-exclamation"></span>
                                            </button> --}}
                                            {{-- <button class="menu-item btn border-0 bg-warning text-white fsize-16" ng-if="item['appartement'].etatlieu == '1' && item['appartement'].iscontrat == '1'  && item['appartement'].isdemanderesiliation == '1' && infosUserConnected.roles[0].id != 2"  ng-click="showModalAdd('etatlieu',{is_file_excel:false, title:null},item.appartement.id)" title="Etat des lieux de sortie">
                                                <span class="fa fa-file-exclamation"></span>
                                            </button> --}}
                                            <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalDetail('locationvente',item.id)"  title="details">
                                                <span class="fas fa-info"></span>
                                            </button>
                                            {{-- <button class="menu-item btn border-0 bg-success text-white fsize-16"  ng-if="item.etat == 1"  ng-click="senddirectormail(item.id,item.locataire.id)"  title="soumettre le contrat">
                                                <i class="fa fa-thumbs-up"></i>
                                            </button> --}}

                                            {{-- <a href="generate-pdf-contratById/@{{item.id}}" target="_blank"> --}}
                                            {{-- </a> --}}
                                            {{-- <a type="button" href="generate-pdf-locationventeById/@{{item.id}}" target="_blank" class="menu-item btn border-0 bg-danger text-white fsize-16"  title="pdf">
                                                <span class="fas fa-file-pdf mt-2"></span>
                                            </a> --}}
                                            {{-- <button ng-click="redirectPdf('generate-pdf-contratById/item.id')" class="menu-item btn border-0 bg-danger text-white fsize-16"  title="pdf">
                                                <span class="fas fa-file-pdf mt-2"></span>
                                            </button> --}}

                                            {{-- <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="showModalStatut($event,'contrat',0, item, 'Résilier le contrat')" ng-if="item.retourcaution && item.status == '1'" title="Résilier le contrat">
                                                <i class="fa fa-thumbs-down"></i>
                                            </button> --}}
                                            <button type="button" ng-click="redirectUrl('list-detailslocationvente',item.id)" class="menu-item btn border-0 bg-info text-white fsize-16"  title="Détails du contrat">
                                                <span class="fas fa-eye mt-2"></span>
                                            </button>
                                            @if (auth()->user()->can('suppression-locationvente'))
                                                <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('contrat',item.id)"  title="Supprimer">
                                                    <span class="fa fa-trash-alt"></span>
                                                </button>
                                            @endif
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
                    <select class="w-20 input box mt-1" ng-model="paginations['locationvente'].entryLimit" ng-change="pageChanged('locationvente')">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                    <nav aria-label="Page navigation">
                        <ul class="uk-pagination float-right" uib-pagination total-items="paginations['locationvente'].totalItems" ng-model="paginations['locationvente'].currentPage" max-size="paginations['locationvente'].maxSize" items-per-page="paginations['locationvente'].entryLimit" ng-change="pageChanged('locationvente')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                    </nav>
                </div>
            </div>
            <!-- /PAGINATION -->
        </div>
    </div>
</div>
@endif
