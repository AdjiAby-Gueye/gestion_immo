@if(auth()->user())
<div class="grid grid-cols-12 gap-6 subcontent classe_generale">
    <div class="col-span-12 xxl:col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-6">



            <div class="intro-y block  items-center h-10 mt-5">
                <h2 style="text-align: center;" class="text-lg font-medium  truncate mr-5 uppercase">
                    Etat Commission
                </h2>
            </div>

            <div class="intro-y grid grid-cols-12 gap-6 mt-40 sm:mt-1 md:mt-1">
                <!-- BEGIN: Basic Accordion -->
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box">
                        <form class="bg-white grid pt-5 p-3 mt-3">
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datedeb_com_list_etatloyer">Choisissez la date de debut</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datedeb_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datefin_com_list_etatloyer">Choisissez la date de fin</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datefin_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>
                                <!-- <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="locataire_com_list_etatloyer">Choisissez le locataire</label>
                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="locataire_com_list_etatloyer">
                                            <option value="">Locataire</option>
                                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
                                        </select>
                                    </div>
                                </div> -->

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez le proprietaire</label>
                                        <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="proprietaire_c_list_etatloyer" name="searchoption">
                                            <option value="">proprietaire</option>
                                            <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
                                        </select>

                                    </div>
                                </div>



                                <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                    <div class="flex flex-wrap -mx-3">
                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                            <a ng-if="filters" href="generate-pdf-commissionentredeuxdate/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="!filters" href="javascript::void(0)" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>

                                            <!-- <a ng-if="filters" href="generate-excel-user/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                            <a ng-if="!filters" href="generate-excel-user" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>  -->
                                        </div>
                                        <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                            <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('etatloyer', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                            <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="generateAddFiltres('etatloyer')"><span class="fa fa-search mr-1"></span>Filtrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Basic Accordion -->
            </div>


            <div class="intro-y block  items-center h-10 mt-5">
                <h2 style="text-align: center;" class="text-lg font-medium  truncate mr-5 uppercase">
                    SITUATION DES SOLDES PROPRIETAIRES
                </h2>

            </div>
            <div class="intro-y grid grid-cols-12 gap-6 mt-40 sm:mt-1 md:mt-1">
                <!-- BEGIN: Basic Accordion -->
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box">
                        <form class="bg-white grid pt-5 p-3 mt-3">
                            <div class="flex flex-wrap -mx-3">

                                <!-- <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datedeb_list_etatloyer">Choisissez la date de debut</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datedeb_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datefin_list_etatloyer">Choisissez la date de fin</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datefin_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div> -->




                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez le proprietaire</label>
                                        <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="proprietaire_ssp_list_etatloyer" name="searchoption">
                                            <option value="">proprietaire</option>
                                            <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                    <div class="flex flex-wrap -mx-3">
                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                            <a  ng-if="filters" href="generate-pdf-situasimplecompte/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a  ng-if="!filters" href="generate-pdf-situasimplecompte" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="!filters" href="javascript::void(0)" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>


                                        </div>
                                        <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                            <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('etatloyer', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                            <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="generateAddFiltres('etatloyer')"><span class="fa fa-search mr-1"></span>Filtrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Basic Accordion -->
            </div>

           


            <div class="intro-y block  items-center h-10 mt-5">
                <h2 style="text-align: center;" class="text-lg font-medium  truncate mr-5 uppercase">
                    Releve Compte Proprietaire
                </h2>

            </div>
            <div class="intro-y grid grid-cols-12 gap-6 mt-40 sm:mt-1 md:mt-1">
                <!-- BEGIN: Basic Accordion -->
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box">
                        <form class="bg-white grid pt-5 p-3 mt-3">
                            <div class="flex flex-wrap -mx-3">

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datedeb_cp_list_etatloyer">Choisissez la date de debut</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datedeb_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datefin_cp_list_etatloyer">Choisissez la date de fin</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datefin_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez le proprietaire</label>
                                        <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="proprietaire_cp_list_etatloyer" name="searchoption">
                                            <option value="">proprietaire</option>
                                            <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez l'immeuble</label>
                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="immeuble_cp_list_etatloyer">
                                            <option value="">Immeuble</option>
                                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}"> @{{ item.nom }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez l'appartement</label>
                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="appartement_cp_list_etatloyer">
                                            <option value="">appartement</option>
                                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}"> @{{ item.nom }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                    <div class="flex flex-wrap -mx-3">
                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                            <a ng-if="filters" href="generate-pdf-situacompteprop/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="!filters" href="javascript::void(0)" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>

                                            <!-- <a ng-if="!filters" href="generate-pdf-user" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="filters" href="generate-excel-user/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                            <a ng-if="!filters" href="generate-excel-user" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>  -->
                                        </div>
                                        <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                            <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('etatloyer', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                            <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="generateAddFiltres('etatloyer')"><span class="fa fa-search mr-1"></span>Filtrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Basic Accordion -->
            </div>



            <div class="intro-y block  items-center h-10  mt-5">
                <h2 style="text-align: center;" class="text-lg font-medium  truncate mr-5 uppercase">
                    Etat Table Arrieres
                </h2>

            </div>
            <div class="intro-y grid grid-cols-12 gap-6 mt-40 sm:mt-1 md:mt-1">
                <!-- BEGIN: Basic Accordion -->
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box">
                        <form class="bg-white grid pt-5 p-3 mt-3">
                            <div class="flex flex-wrap -mx-3">

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datedeb_list_etatloyer">Choisissez la date de debut</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datedeb_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datefin_list_etatloyer">Choisissez la date de fin</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datefin_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>


                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="proprietaire_list_etatloyer">Choisissez le proprietaire</label>
                                        <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="proprietaire_ta_list_etatloyer" name="searchoption">
                                            <option value="">proprietaire</option>
                                            <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez l'immeuble</label>
                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="immeuble_ta_list_etatloyer">
                                            <option value="">Immeuble</option>
                                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}"> @{{ item.nom }}</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                    <div class="flex flex-wrap -mx-3">
                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                            <a ng-if="filters" href="generate-pdf-tablearrieres/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>

                                            <a ng-if="!filters" href="javascript::void(0)" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>

                                            <!-- <a ng-if="!filters" href="generate-pdf-user" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="filters" href="generate-excel-user/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                            <a ng-if="!filters" href="generate-excel-user" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a> -->
                                        </div>
                                        <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                            <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('etatloyer', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                            <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="generateAddFiltres('etatloyer')"><span class="fa fa-search mr-1"></span>Filtrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Basic Accordion -->
            </div>

            <div class="intro-y block  items-center h-10 mt-5">
                <h2 style="text-align: center;" class="text-lg font-medium  truncate mr-5 uppercase">
                    Etat Table balance
                </h2>

            </div>
            <div class="intro-y grid grid-cols-12 gap-6 mt-40 sm:mt-1 md:mt-1">
                <!-- BEGIN: Basic Accordion -->
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box">
                        <form class="bg-white grid pt-5 p-3 mt-3">
                            <div class="flex flex-wrap -mx-3">



                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez le proprietaire</label>
                                        <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="proprietaire_list_etatloyer" name="searchoption">
                                            <option value="">proprietaire</option>
                                            <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez l'immeuble</label>
                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="immeuble_list_etatloyer">
                                            <option value="">Immeuble</option>
                                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}"> @{{ item.nom }}</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datedeb_tva_list_etatloyer">Choisissez la date de debut</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datedeb_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datefin_tva_list_etatloyer">Choisissez la date de fin</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datefin_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>
                                <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                    <div class="flex flex-wrap -mx-3">
                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                            <a ng-if="filters" href="generate-pdf-balanceclients/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="!filters" href="javascript::void(0)" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>

                                            <!-- <a ng-if="!filters" href="generate-pdf-user" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="filters" href="generate-excel-user/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                            <a ng-if="!filters" href="generate-excel-user" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>  -->
                                        </div>
                                        <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                            <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('etatloyer', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                            <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="generateAddFiltres('etatloyer')"><span class="fa fa-search mr-1"></span>Filtrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Basic Accordion -->
            </div>


            <div class="intro-y block  items-center h-10 mt-5">
                <h2 style="text-align: center;" class="text-lg font-medium  truncate mr-5 uppercase">
                    Etat TLV
                </h2>

            </div>
            <div class="intro-y grid grid-cols-12 gap-6 mt-40 sm:mt-1 md:mt-1">
                <!-- BEGIN: Basic Accordion -->
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box">
                        <form class="bg-white grid pt-5 p-3 mt-3">
                        <div class="flex flex-wrap -mx-3">
    <!-- Choisissez la période -->
    <div class="w-full md:w-1/3 px-3 mb-4">
        <div class="relative w-full">
            <label for="periode_list_etatloyer" class="block text-sm font-medium text-gray-700 mb-2">Choisissez la période</label>
            <select id="periode_list_etatloyer"
                    class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Période</option>
                <option value="janvier">Janvier</option>
                <option value="fevrier">Février</option>
                <option value="mars">Mars</option>
                <option value="avril">Avril</option>
                <option value="mai">Mai</option>
                <option value="juin">Juin</option>
                <option value="juillet">Juillet</option>
                <option value="aout">Août</option>
                <option value="septembre">Septembre</option>
                <option value="octobre">Octobre</option>
                <option value="novembre">Novembre</option>
                <option value="decembre">Décembre</option>
            </select>
        </div>
    </div>

    <!-- Choisissez l'année -->
    <div class="w-full md:w-1/3 px-3 mb-4">
        <div class="relative w-full">
            <label for="annee_list_etatloyer" class="block text-sm font-medium text-gray-700 mb-2">Choisissez l'année</label>
            <select id="annee_list_etatloyer"
                    class="block appearance-none w-full bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
            </select>
        </div>
    </div>

    <!-- Choisissez le propriétaire -->
    <div class="w-full md:w-1/3 px-3 mb-4">
        <div class="relative w-full">
            <label for="proprietaire_tlv_list_etatloyer" class="block text-sm font-medium text-gray-700 mb-2">Choisissez le propriétaire</label>
            <select id="proprietaire_tlv_list_etatloyer"
                    class="block appearance-none w-full bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Propriétaire</option>
                <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
            </select>
        </div>
    </div>

    <!-- Choisissez la date de début -->
    <div class="w-full md:w-1/2 px-3 mb-4">
        <label for="datedeb_list_etatloyer" class="block text-sm font-medium text-gray-700 mb-2">Choisissez la date de début</label>
        <input id="datedeb_list_etatloyer"
               type="date"
               class="block w-full bg-white text-gray-700 border border-gray-300 rounded py-2 px-3 leading-tight focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <!-- Choisissez la date de fin -->
    <div class="w-full md:w-1/2 px-3 mb-4">
        <label for="datefin_list_etatloyer" class="block text-sm font-medium text-gray-700 mb-2">Choisissez la date de fin</label>
        <input id="datefin_list_etatloyer"
               type="date"
               class="block w-full bg-white text-gray-700 border border-gray-300 rounded py-2 px-3 leading-tight focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <!-- Boutons d'action -->
    <div class="w-full flex justify-between px-3 mb-4">
        <div>
            <a ng-if="filters" href="generate-pdf-tlv/@{{filters}}" target="_blank" class="button small border text-white bg-red-600 px-4 py-2 rounded-md shadow mr-2" title="PDF">
                <span class="fas fa-file-pdf mr-1"></span>PDF
            </a>
            <a ng-if="!filters" href="javascript:void(0)" target="_blank" class="button bg-green-600 text-white px-4 py-2 rounded-md shadow" title="Excel">
                <span class="fas fa-file-excel mr-1"></span>EXCEL
            </a>
        </div>
        <div class="text-right">
            <button type="button" class="button border text-gray-700 px-4 py-2 rounded-md shadow mr-2" ng-click="emptyform('etatloyer', true)">
                <span class="fa fa-filter mr-1"></span>Annuler
            </button>
            <button type="button" class="button bg-blue-600 text-white px-4 py-2 rounded-md shadow" ng-click="generateAddFiltres('etatloyer')">
                <span class="fa fa-search mr-1"></span>Filtrer
            </button>
        </div>
    </div>
</div>

                        </form>
                    </div>
                </div>
                <!-- END: Basic Accordion -->
            </div>




            <div class="intro-y block  items-center h-10 mt-5">
                <h2 style="text-align: center;" class="text-lg font-medium  truncate mr-5 uppercase">
                    Etat TVA
                </h2>

            </div>
            <div class="intro-y grid grid-cols-12 gap-6 mt-40 sm:mt-1 md:mt-1">
                <!-- BEGIN: Basic Accordion -->
                <div class="col-span-12 lg:col-span-12">
                    <div class="intro-y box">
                        <form class="bg-white grid pt-5 p-3 mt-3">
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez le locataire</label>
                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="locataire_tva_list_etatloyer">
                                            <option value="">Locataire</option>
                                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez la periode</label>
                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="periode_tva_list_etatloyer">

                                            <option value="">Periode</option>
                                            <option value="janvier">Janvier</option>
                                            <option value="fevrier ">Fevrier</option>
                                            <option value="mars">Mars</option>
                                            <option value="avril">Avril</option>
                                            <option value="mai">Mai</option>
                                            <option value="juin">Juin</option>
                                            <option value="juillet">Juillet</option>
                                            <option value="aout">Aout</option>
                                            <option value="septembre">Septembre</option>
                                            <option value="octobre">Octobre</option>
                                            <option value="novembre">Novembre</option>
                                            <option value="decembre">Decembre</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez le proprietaire</label>
                                        <select class="block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="proprietaire_tva_list_etatloyer" name="searchoption">
                                            <option value="">proprietaire</option>
                                            <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">@{{ item.nom }} @{{ item.prenom }}</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <div class="inline-block relative w-full">
                                        <label for="appartement_annonce">Choisissez l'immeuble</label>
                                        <select class="select2 block appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                            id="immeuble_tva_list_etatloyer">
                                            <option value="">Immeuble</option>
                                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}"> @{{ item.nom }}</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datedeb_tva_list_etatloyer">Choisissez la date de debut</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datedeb_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>

                                <div class="w-full md:w-1/2 px-3 mb-2">
                                    <label for="datefin_tva_list_etatloyer">Choisissez la date de fin</label>
                                    <input class="shadow appearance-none border w-full  rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="datefin_list_etatloyer" autocomplete="off" type="date" placeholder="Texte ... ">
                                </div>
                                <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                                    <div class="flex flex-wrap -mx-3">
                                        <div class="w-full md:w-1/2 px-3 md:mb-0">
                                            <a ng-if="filters" href="generate-pdf-tva/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="!filters" href="javascript::void(0)" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                            <!-- <a ng-if="!filters" href="generate-pdf-user" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                            <a ng-if="filters" href="generate-excel-user/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                            <a ng-if="!filters" href="generate-excel-user" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>  -->
                                        </div>
                                        <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                            <button type="button" class="button small border text-gray-700 mr-1 btn-shadow-dark" ng-click="emptyform('etatloyer', true)"><span class="fa fa-filter mr-1"></span>Annuler</button></button>
                                            <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="generateAddFiltres('etatloyer')"><span class="fa fa-search mr-1"></span>Filtrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Basic Accordion -->
            </div>




            <div class="overflow-table">
                <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                    <!-- <div class="w-full md:w-1/1 px-3 md:mb-0 mt-2">
                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/2 px-3 md:mb-0">
                                <a ng-if="filters" href="generate-pdf-etatloyer/@{{filters}}" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                <a ng-if="!filters" href="generate-pdf-user" target="_blank" class="button small border text-white bg-danger mr-1 btn-shadow-dark" title="PDF"><span class="fas fa-file-pdf mr-1"></span>PDF</a>
                                <a ng-if="filters" href="generate-excel-etatloyer/@{{filters}}" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                                <a ng-if="!filters" href="locataire/sendmail" target="_blank" class="button bg-success text-white btn-shadow" title="Excel"><span class="fas fa-file-excel mr-1"></span>EXCEL</a>
                            </div>
                            <div class="w-full md:w-1/2 px-3 md:mb-0 text-right">
                                <button type="button" class="button bg-theme-101 text-white btn-shadow" ng-click="pageChanged('etatlieux')"><span class="fa fa-envelope mr-1"></span>Rappel paiement</button>
                            </div>
                        </div>
                    </div> -->
                    {{-- <table class="table table-report sm:mt-2">
                        <thead>
                        <tr>
                            <th class="whitespace-no-wrap">Proprietaire</th>
                            <th class="whitespace-no-wrap text-center">Appartement</th>
                            <th class="whitespace-no-wrap text-center">Locataire</th>
                            <th class="whitespace-no-wrap text-center">Etat Loyer</th>
                            <th class="whitespace-no-wrap text-center">Telephone</th>
                            <th class="whitespace-no-wrap text-center">Email</th>
                            <th class="whitespace-no-wrap text-center">Paiement Etat</th>
                            <th class="whitespace-no-wrap text-center">Periode paiement</th>
                            <th class="text-center whitespace-no-wrap">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x" ng-if="filters" ng-repeat="item in dataPage['contrats']">
                              
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center"></div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">test</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">test</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">test</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">test</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">test</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">test</div>
                                </td>
                                <td>
                                    <div class="font-medium whitespace-no-wrap text-center">test</div>
                                </td>

                                <td class="table-report__action w-56">
                                    <nav class="menu-leftToRight uk-flex text-center">
                                        <input type="checkbox" href="#" class="menu-open" name="menu-open"  id="menu-open1us-@{{ item.id }}">
                    <label class="menu-open-button bg-white" for="menu-open1us-@{{ item.id }}">
                        <span class="hamburger bg-template-1 hamburger-1"></span>
                        <span class="hamburger bg-template-1 hamburger-2"></span>
                        <span class="hamburger bg-template-1 hamburger-3"></span>
                    </label>
                    <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteElement('etatlieux',item.id)" title="Supprimer">
                        <span class="fa fa-trash-alt"></span>
                    </button>
                    <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="showModalUpdate('etatlieux',item.id)" title="Modifier les infos">
                        <span class="fal fa-edit"></span>
                    </button>
                    </nav>
                    </td>
                    </tr>

                    </tbody>
                    </table> --}}
                </div>
            </div>


            <!-- PAGINATION -->
            {{-- <div class="col-span-12 grid grid-cols-12 gap-4 mt-3">
                <div class="col-span-12 sm:col-span-12 md:col-span-3">
                    <span>Affichage par</span>
                    <select class="w-20 input box mt-1" ng-model="paginations['etatlieux'].entryLimit" ng-change="pageChanged('etatlieux')">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                    <nav aria-label="Page navigation">
                        <ul class="uk-pagination float-right" uib-pagination total-items="paginations['etatlieux'].totalItems" ng-model="paginations['etatlieux'].currentPage" max-size="paginations['etatlieux'].maxSize" items-per-page="paginations['etatlieux'].entryLimit" ng-change="pageChanged('etatlieux')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                    </nav>
                </div>
            </div> --}}
            <!-- /PAGINATION -->
        </div>
    </div>
</div>
@endif