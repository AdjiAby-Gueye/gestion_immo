<!-- markme-AJOUT -->
@extends('layouts.app')

@section('content')

<body ng-controller="BackEndCtl" class="app @{{ currentTheme }}" ng-cloak="">

    <input type="hidden" id="userLogged" value="{{ Auth::user() }}">
    <input type="hidden" id="userLogged_id" value="{{ Auth::user()->id }}">

    @include('layouts.partials.mobile-menu')

    <div class="flex">
        @include('layouts.partials.sidebar')

        <div class="content">
            @include('layouts.partials.nav-menu')
            <div id="ngview" ng-view></div>
        </div>
    </div>

    @include('layouts.partials.footer')



</body>

<!--debut modal-->

<!-- debut modal user -->
<div class="modal" id="modal_adduser">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-user mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Utilisateur
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

        </div>
        <form id="form_adduser" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'user')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_user" name="id">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infogeneuser" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos générales</a>
                        <a data-toggle="tab" data-target="#connexionuser" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Compte</a>
                    </div>
                </div>
            </div>

            <div class="tab-content">

                <div class="tab-content__pane active" id="infogeneuser">
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-info-circle text-blue-400"></i> Informations générales
                    </div>

                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-6 sm:col-span-12 md:col-span-6">

                            <div class="grid grid-cols-12 gap-4 row-gap-3">

                                <div class="col-span-12 sm:col-span-12">
                                    <label for="categorietuto_typetuto">Programme</label>
                                    <div class="inline-block relative w-full">
                                        <select class="block select2 select2-user modal appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="entite_user" name="entite">
                                            <option value="" class="">Programme</option>
                                            <option ng-repeat="item in dataPage['entites']" value="@{{ item.id }}">
                                                @{{ item.designation }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-12">
                                    <label for="name_user">Nom d'utilisateur</label>
                                    <input type="text" id="name_user" name="name" class="input w-full border mt-2 flex-1" placeholder="Nom utilisateur">
                                </div>
                                <div class="col-span-12 sm:col-span-12">
                                    <label for="categorietuto_typetuto">Profil</label>
                                    <div class="inline-block relative w-full">
                                        <select class="block select2 select2-user modal appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="role_user" name="role">
                                            <option value="">Profil</option>
                                            <option ng-repeat="item in dataPage['roles']" value="@{{ item.id }}">@{{ item.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="prestataireuser col-span-12 sm:col-span-12">
                                    <label for="categorietuto_typetuto">Prestataire</label>
                                    <div class="inline-block relative w-full">
                                        <select class="block select2 select2-user modal appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="prestataire_user" name="prestataire">
                                            <option value="">Prestataire</option>
                                            <option ng-repeat="item in dataPage['prestataires']" value="@{{ item.id }}">@{{ item.nom }}</option>
                                        </select>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-12 md:col-span-6">
                            <div class="form-group text-center class-form">
                                <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->
                                <div>
                                    <label for="imguser" class="cursor-pointer">
                                        <img id="affimguser" src="{{ asset('assets/images/upload.jpg') }}" alt="..." class="image-hover shadow" style="width: 150px;height: 150px;border-radius: 50%;margin: 0 auto">
                                        <div style="display: none;">
                                            <input type="file" accept='image/*' id="imguser" name="image" onchange='Chargerimage("user")' class="required">
                                            <input type="hidden" id="erase_imguser" name="image_erase" value="">
                                        </div>
                                    </label>
                                </div>
                                <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile('imguser')">
                                    <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="tab-content__pane" id="connexionuser">
                    <div class="form-section animated fadeInDown text-xl pl-5">
                        <i class="fas fa-id-card text-blue-400"></i>
                        Compte
                    </div>

                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-12 sm:col-span-12">
                            <label for="email_user">Login(Email)</label>
                            <input type="email" id="email_user" name="email" class="input w-full border mt-2 flex-1" placeholder="Email">
                        </div>
                        <div class="col-span-6 sm:col-span-12 md:col-span-6">
                            <label for="password_user">Mot de passe</label>
                            <input type="password" id="password_user" name="password" class="input w-full border mt-2 flex-1 genererPassword" placeholder="Mot de passe">
                        </div>
                        <div class="col-span-6 sm:col-span-12 md:col-span-6">
                            <label for="confirmpassword_user">Confirmation mot de passe</label>
                            <input type="password" id="confirmpassword_user" name="confirmpassword" class="input w-full border mt-2 flex-1 genererPassword" placeholder="Confirmation mot de passe">
                        </div>
                        @if(auth()->user()->can('upload-signature'))
                        <div class="col-span-6 sm:col-span-12 md:col-span-6">
                            <div class="form-group text-center class-form">
                                <label for="uploadsignature" class="text-black font-bold">Upload signature</label>
                                <div>
                                    <label for="imguploadsignature" class="cursor-pointer">
                                        <img id="affimguploadsignature" src="{{ asset('assets/images/upload.jpg') }}" alt="..." class="image-hover shadow" style="width: 250px;height: 250px;border-radius: 10%!important;margin: 0 auto">
                                        <div style="display: none;">
                                            <input type="file" accept='image/*' id="imguploadsignature" name="image_signature" onchange='Chargerimage("uploadsignature")' class="required">
                                            <input type="hidden" id="erase_imguploadsignature" name="image_erase2" value="">
                                        </div>
                                    </label>
                                </div>

                                <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile('imguploadsignature')">
                                    <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>
                                </button>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

            </div>



            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>

        </form>

    </div>
</div>
<!-- fin modal user-->

<!-- debut modal role -->
<div class="modal" id="modal_addrole">
    <div class="modal__content modal__content--xl">

        <div class="flex items-center px-5 py-5 sm:py-3 header">
            <i class="fa fa-user mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Profil
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <div class="py-5 px-3 modal__content_body  border-raduis-top">

            <form id=" form_addrole" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'role')" style="max-height: 80vh!important;overflow: auto">
                <!--  @csrf-->
                <input type="hidden" id="id_role" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-12 sm:col-span-12">
                        <label for="name_role">Nom du profil</label>
                        <input type="text" id="name_role" name="name" class="input w-full border mt-2 flex-1 required" placeholder="Nom du profil">
                        <input type="hidden" name="permissions" value="@{{ role_permissions }}">
                    </div>

                    <div class="col-span-12 sm:col-span-12">
                        <label for="name_role">Nouveau nom</label>
                        <input type="text" id="nouveauname_role" name="nouveauname" class="input w-full border mt-2 flex-1" placeholder="Nom du profil">
                    </div>
                    <div class="col-span-4 sm:col-span-4 mt-3">
                        <div class="inline-block relative w-full">
                            <select class="block appearance-none w-full bg-white text-gray-700
                        border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline mt-3" id="searchoption_list_permission" name="searchoption">
                                <!--                            <option value="">Rechercher par</option>-->
                                <option selected value="designation">Désignation</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4 sm:col-span-4 mt-3">
                        <input class="shadow appearance-none border w-full mt-3 rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="searchtexte_list_permission" ng-model="searchtexte_list_permission" autocomplete="off" type="text" placeholder="Texte ... ">
                    </div>

                    <div class="col-span-4 sm:col-span-4 mt-3">
                        <!--                    <button type="button" class="button w-10 bg-theme-101 text-white mt-3" ng-click="pageChanged('permission')"><span class="fa fa-search"></span></button>-->
                        <button type="button" class="button w-10 bg-theme-101 text-white mt-3" ng-click="searchPermission($event)"><span class="fa fa-search"></span></button>
                        <!--                    <button type="button" class="button w-30 border text-gray-700 mr-1 btn-shadow-dark ml-3" ng-click="emptyform('permission', true)">Annuler filtre</button>-->
                        <button type="button" class="button w-30 border text-gray-700 mr-1 btn-shadow-dark ml-3" ng-click="searchPermission($event,false)">Annuler filtre</button>
                    </div>



                    <div class="px-5 text-right my-3">
                        <button type="button" data-dismiss="modal" class="custom-btn text-white bg-danger">Annuler</button>
                        <button type="submit" class="custom-btn  btn-shadow">Valider</button>
                    </div>

                    <div class="col-span-12 sm:col-span-12">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                            <table class="table table-report sm:mt-2">
                                <thead>
                                    <tr class="bg-theme-101 text-white">
                                        <th class="whitespace-no-wrap">
                                            <div class="form-inline">
                                                <label for="permission_all_role" id="labelCocherTout"> Tout cocher
                                                </label>
                                                <input type="checkbox" id="permission_all_role" name="permission_all" class="ml-2" ng-model="cocherTout" ng-click="checkAllOruncheckAll('role')">
                                            </div>
                                        </th>
                                        <th class="text-center whitespace-no-wrap">Permission</th>
                                    </tr>
                                </thead>
                                <tbody id="lespermissions">
                                    <tr class="intro-x animated fadeIn" ng-repeat="item in dataPage['permissions']">
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">
                                                <input type="checkbox" id="permission_role_@{{ $index }}" data-permission-id="@{{ item.id }}" data-permission-name="@{{ item.name }}" name="selectedpermissions" class="skin skin-flat mycheckbox" ng-click="addToRole($event, item.id)" ng-checked="isInArrayData($event,item.id,roleview.permissions,'role')">
                                            </div>
                                        </td>
                                        <td class="text-center whitespace-no-wrap">@{{ item.display_name }}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- PAGINATION -->
                    <div class="col-span-12 grid grid-cols-12 gap-4 mt-3">
                        <div class="col-span-12 sm:col-span-12 md:col-span-3">
                            <span>Affichage par</span>
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



            </form>
        </div>

    </div>
</div>
<!-- fin modal role-->

<!-- debut modal structureimmeuble -->
<div class="modal" id="modal_addstructureimmeuble">
    <div class="modal__content modal__content--sm">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Structure d'un immeuble
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addstructureimmeuble" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'structureimmeuble')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_structureimmeuble" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_structureimmeuble">Nombre d'etage</label>
                        <input type="number" id="designation_structureimmeuble" name="designation" class="input w-full border mt-2 flex-1" placeholder="nombre">
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal structureimmeuble-->

<!-- debut modal typeappartement -->
<!--<div class="modal" id="modal_addtypeappartement">
        <div class="modal__content modal__content--md">
            <div  class="flex items-center px-5 py-5 sm:py-3 header "
>
                <i class="fas fa-tags mr-2"></i>
                <h2 class="font-medium text-base mr-auto">
                    Type d'appartement
                </h2>
                <div class="pull-right">
                    <button class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <form id="form_addtypeappartement" class="form" accept-charset="UTF-8"
                  ng-submit="addElement($event,'typeappartement')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typeappartement" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <label for="designation_typeappartement">Désignation</label>
                        <input type="text" id="designation_typeappartement" name="designation"
                               class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="nombrechambre_typeappartement">Nombre de chambres</label>
                        <input type="number" id="nombrechambre_typeappartement" name="nombrechambre"
                               class="input w-full border mt-2 flex-1" placeholder="nombre">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="nombrechambresalledebain_typeappartement">Nombre de chambre + salles de bains</label>
                        <input type="text" id="nombrechambre_typeappartement" name="nombrechambresalledebain"
                               class="input w-full border mt-2 flex-1" placeholder="nombre">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="nombrecuisine_typeappartement">Nombre de cuisines</label>
                        <input type="number" id="nombrecuisine_typeappartement" name="nombrecuisine"
                               class="input w-full border mt-2 flex-1" placeholder="Nombre de cuisine">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="nombresallon_typeappartement">Nombre de sallons</label>
                        <input type="number" id="nombresallon_typeappartement" name="nombresallon"
                               class="input w-full border mt-2 flex-1" placeholder="nombre">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="nombredoucheexterne_typeappartement">Nombre de douches externes</label>
                        <input type="number" id="nombredoucheexterne_typeappartement" name="nombredoucheexterne"
                               class="input w-full border mt-2 flex-1" placeholder="nombre">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="nombreespacefamilliale_typeappartement">Nombre d'espace familliale</label>
                        <input type="number" id="nombreespacefamilliale_typeappartement" name="nombreespacefamilliale"
                               class="input w-full border mt-2 flex-1" placeholder="nombre">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="nombrecouloire_typeappartement">Nombre de couloires</label>
                        <input type="number" id="nombrecouloire_typeappartement" name="nombrecouloire"
                               class="input w-full border mt-2 flex-1" placeholder="nombre">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="nombremezzanine_typeappartement">Nombre de mezzanine</label>
                        <input type="number" id="nombremezzanine_typeappartement" name="nombremezzanine"
                               class="input w-full border mt-2 flex-1" placeholder="nombre">
                    </div>
                </div>

                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal"
                            class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                    </button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>-->
<!-- fin modal typeappartement-->

<!-- debut modal typeappartement -->
<div class="modal" id="modal_addtypeappartement">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type d'appartement
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypeappartement" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typeappartement')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typeappartement" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-6 sm:col-span-6">
                        <label for="designation_typeappartement">Désignation</label>
                        <input type="text" id="designation_typeappartement" name="designation" class="input w-full border  flex-1" placeholder="Désignation">
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="produit_logistique_proforma ">Usage</label>
                        <div class="col-span-12 sm:col-span-12">
                            <select name="usage" class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="usage_typeappartement" style="width: 100% !important;">
                                <option value="">type usage</option>
                                <option value="1">Commercial</option>
                                <option value="2">Habittation</option>
                            </select>
                        </div>
                    </div>



                </div>

                <div class="form-section pl-5 pt-3  text-xl">
                    <i class="fa fa-info-circle text-blue-400"></i> Composants
                </div>
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">



                    <div class="col-span-10 sm:col-span-10">
                        <label for="produit_logistique_proforma">Pieces</label>
                        <div class="col-span-12 sm:col-span-12">
                            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typepiece_typeappartement_typepiece_typeappartement" style="width: 100% !important;">
                                <option value="">piece</option>
                                <option ng-repeat="item in dataPage['typepieces']" value="@{{ item.id }}">
                                    @{{ item.designation }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-2 sm:col-span-2 text-right">
                        <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="actionSurTabPaneTypeAppart('add','typeappartement_typepiece_typeappartement')"><span class="fa fa-plus"></span></button>
                    </div>

                    <div class="col-span-12 sm:col-span-12">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                            <table class="table table-report sm:mt-1">
                                <thead>
                                    <tr class="bg-theme-101 text-white">
                                        <th hidden class="whitespace-no-wrap">#</th>
                                        <th class="whitespace-no-wrap">Piece</th>
                                        <!--                                        <th class="whitespace-no-wrap text-center">Etat</th>-->
                                        <th class="text-center whitespace-no-wrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataInTabPane['typeappartement_typepiece_typeappartement']['data']">

                                        <td hidden class="">
                                            <div class="font-medium whitespace-no-wrap">@{{ item.id }}</div>
                                        </td>

                                        <td class="">
                                            <div class="font-medium whitespace-no-wrap">@{{ item.typepiece_text }}</div>
                                        </td>

                                        <!--                                        <td class="">-->
                                        <!---->
                                        <!--                                            <div class="font-medium whitespace-no-wrap text-center">-->
                                        <!---->
                                        <!--                                                <span ng-if="item.etat == 0" class="px-2 rounded-full bg-danger text-white font-medium text-center">desactivé</span>-->
                                        <!--                                                <span ng-if="item.etat == 1" class="px-2 rounded-full bg-success text-white font-medium text-center">activé</span>-->
                                        <!---->
                                        <!--                                            </div>-->
                                        <!--                                        </td>-->

                                        <td class="table-report__action w-56">
                                            <nav class="menu-leftToRight uk-flex text-center">
                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                                <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                </label>

                                                <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('delete', 'typeappartement_typepiece_typeappartement', $index)" title="Supprimer">
                                                    <span class="fa fa-trash-alt"></span>
                                                </button>
                                                <button type="button" class="menu-item btn border-0 bg-success text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('update', 'typeappartement_typepiece_typeappartement', $index, '', null, 'etat', 1)" ng-if="item.etat == 0" title="Activé">
                                                    <span class="fa fa-thumbs-up"></span>
                                                </button>
                                                <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('update', 'typeappartement_typepiece_typeappartement', $index, '', null, 'etat', 0)" ng-if="item.etat == 1" title="Desactivé">
                                                    <span class="fa fa-thumbs-down"></span>
                                                </button>

                                            </nav>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal typeappartement-->

<!-- debut modal typeassurance -->
<div class="modal" id="modal_addtypeassurance">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type d'assurance
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypeassurance" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typeassurance')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typeassurance" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_typeassurance">Désignation</label>
                        <input type="text" id="designation_typeassurance" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal typeassurance-->


<!-- debut modal typecontrat -->
<div class="modal" id="modal_addtypecontrat">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type de contrat
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypecontrat" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typecontrat')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typecontrat" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_typecontrat">Désignation</label>
                        <input type="text" id="designation_typecontrat" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal typecontrat-->

<!-- debut modal typedocument -->
<div class="modal" id="modal_addtypedocument">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type de document
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypedocument" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typedocument')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typedocument" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_typecontrat">Désignation</label>
                        <input type="text" id="designation_typecontrat" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                </div>

                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                    </button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal typedocument-->

<!-- debut modal categorieprestataire -->
<div class="modal" id="modal_addcategorieprestataire">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Categorie prestataire
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addcategorieprestataire" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'categorieprestataire')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_categorieprestataire" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_categorieprestataire">Désignation</label>
                        <input type="text" id="designation_categorieprestataire" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal categorieprestataire-->

<!-- debut modal categorieintervention -->
<div class="modal" id="modal_addcategorieintervention">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Categorie intervention
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addcategorieintervention" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'categorieintervention')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_categorieintervention" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_categorieprestataire">Désignation</label>
                        <input type="text" id="designation_categorieintervention" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                    <div class="col-span-12 sm:col-span-12 md:col-span-12 text-center">
                        <div class="mt-4" style="font-size: 13px!important;">Image</div>
                        <div class="form-group text-center class-form">
                            <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->
                            <div>
                                <label for="imgcategorieintervention" class="cursor-pointer">
                                    <img id="affimgcategorieintervention" src="{{ asset('assets/images/upload.jpg') }}" alt="..." class="image-hover shadow" style="width: 250px;height: 250px;border-radius: 10%!important;margin: 0 auto">
                                    <div style="display: none;">
                                        <input type="file" accept='image/*' id="imgcategorieintervention" name="image" onchange='Chargerimage("categorieintervention")' class="required">
                                        <input type="hidden" id="erase_imgcategorieintervention" name="image_erase" value="">
                                    </div>
                                </label>
                            </div>
                            <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile('imgcategorieintervention')">
                                <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal categorieintervention-->

<!-- debut modal horaire -->
<div class="modal" id="modal_addhoraire">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Horaire
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addhoraire" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'horaire')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_horaire" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_horaire">Désignation</label>
                        <input type="text" id="designation_horaire" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="debut_horaire">Heure de début</label>
                        <input type="time" id="debut_horaire" name="debut" class="input w-full border mt-2 flex-1" placeholder="Début">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="fin_horaire">Heure de fin</label>
                        <input type="time" id="fin_horaire" name="fin" class="input w-full border mt-2 flex-1" placeholder="Fin">
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal horaire-->

<!-- debut modal typefacture -->
<div class="modal" id="modal_addtypefacture">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type de facture
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypefacture" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typefacture')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typefacture" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_typefacture">Désignation</label>
                        <input type="text" id="designation_typefacture" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- fin modal typefacture-->

<!-- debut modal typeintervention -->
<div class="modal" id="modal_addtypeintervention">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type d'intervention
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypeintervention" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typeintervention')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typeintervention" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_typeintervention">Désignation</label>
                        <input type="text" id="designation_typeintervention" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal typeintervention-->

<!-- debut modal typelocataire -->
<div class="modal" id="modal_addtypelocataire">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type de locataire
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypelocataire" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typelocataire')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typelocataire" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <label for="designation_typecontrat">Désignation</label>
                        <input type="text" id="designation_typelocataire" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                </div>



                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal typelocataire-->


<!-- debut modal typeapportponctuel -->
<div class="modal" id="modal_addtypeapportponctuel">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type d'apport ponctuel
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypeapportponctuel" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typeapportponctuel')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typeapportponctuel" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <label for="designation_typeapportponctuel">Désignation</label>
                        <input type="text" id="designation_typeapportponctuel" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="designation_typeapportponctuel">Description</label>
                        <input type="text" id="description_typeapportponctuel" name="description" class="input w-full border mt-2 flex-1" placeholder="Description">
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal typeapportponctuel-->

<!-- debut modal apportponctuel -->
<div class="modal" id="modal_addapportponctuel">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Apport ponctuel
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addapportponctuel" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'apportponctuel')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_apportponctuel" name="id">
                <input type="hidden" id="contrat_id_apportponctuel" name="contrat_id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-6 sm:col-span-6">
                        <label for="date_apportponctuel" class="required">Date</label>
                        <input type="date" id="date_apportponctuel" name="date" class="input w-full border mt-2 flex-1" placeholder="Date">
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="montant_apportponctuel" class="required">Montant</label>
                        <input type="text" id="montant_apportponctuel" name="montant" class="input w-full border mt-2 flex-1" placeholder="Montant">
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="typeapportponctuel_id_apportponctuel" class="required">Type apport ponctuel</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typeapportponctuel_id_apportponctuel" name="typeapportponctuel_id">
                                <option value="">Type apport ponctuel</option>
                                <option ng-repeat="item in dataPage['typeapportponctuels']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="observations_apportponctuel">Observations</label>
                        <input type="textarea" id="observations_apportponctuel" name="observations" class="input w-full border mt-2 flex-1 required" placeholder="Observations">
                    </div>

                </div>
                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal apportponctuel-->

<!-- debut modal contratproprietaire -->
<div class="modal" id="modal_addcontratproprietaire">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Mandat Gerance
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addcontratproprietaire" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'contratproprietaire')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_contratproprietaire" name="id">


                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-6 sm:col-span-6">
                        <label for="entite_id_contratproprietaire" class="required">Entite</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="entite_id_contratproprietaire" name="entite_id">
                                <option value="">Entite</option>
                                <option ng-repeat="item in dataPage['entites']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="proprietaire_id_contratproprietaire" class="required">Proprietaire</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_proprietaire" id="proprietaire_id_contratproprietaire" name="proprietaire_id">
                                <option value="">Proprietaire</option>
                                <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">
                                    @{{ item.prenom }} @{{ item.nom }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="modelcontrat_id_contratproprietaire" class="required">Model de contrat</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="modelcontrat_id_contratproprietaire" name="modelcontrat_id">
                                <option value="">Model de contrat</option>
                                <option ng-repeat="item in dataPage['modelcontrats']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="date_contratproprietaire" class="required">Date</label>
                        <input type="date" id="date_contratproprietaire" name="date" class="input w-full border mt-2 flex-1" placeholder="Date">
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="descriptif_contratproprietaire">Descriptif</label>
                        <input type="text" id="descriptif_contratproprietaire" name="descriptif" class="input w-full border mt-2 flex-1" placeholder="Descriptif">
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="commissionvaleur_contratproprietaire">Valeur Commission</label>
                        <input type="number" id="commissionvaleur_contratproprietaire" name="commissionvaleur" class="input w-full border mt-2 flex-1" placeholder="Valeur Commission">
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <label for="commissionpourcentage_contratproprietaire">Pourcentage Commission</label>
                        <input type="number" id="commissionpourcentage_contratproprietaire" name="commissionpourcentage" class="input w-full border mt-2 flex-1" placeholder="Pourcentage Commission">
                    </div>



                    <div class="col-span-2 sm:col-span-2">
                        <label for="direct" class="font-bold mb-2 pb-2">TVA</label><br />
                        <input class="input input--switch ml-auto border w-full border flex-1 ml-3 mt-3" name="is_tva" id="is_tva_contratproprietaire" ng-checked="updateCheck('direct_bce','hide_class','checkbox',0,'show_class')" type="checkbox">
                    </div>
                    <div class="col-span-2 sm:col-span-2">
                        <label for="direct" class="font-bold mb-2 pb-2">BRS</label><br />
                        <input class="input input--switch ml-auto border w-full border flex-1 ml-3 mt-3" name="is_brs" id="is_brs_contratproprietaire" ng-checked="updateCheck('direct_bce','hide_class','checkbox',0,'show_class')" type="checkbox">
                    </div>
                    <div class="col-span-2 sm:col-span-2">
                        <label for="direct" class="font-bold mb-2 pb-2">TLV</label><br />
                        <input class="input input--switch ml-auto border w-full border flex-1 ml-3 mt-3" name="is_tlv" id="is_tlv_contratproprietaire" ng-checked="updateCheck('direct_bce','hide_class','checkbox',0,'show_class')" type="checkbox">
                    </div>



                </div>

                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal contratproprietaire-->

<!-- debut modal typeobligationadministrative -->
<div class="modal" id="modal_addtypeobligationadministrative">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type d'obligation administrative
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addtypeobligationadministrative" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typeobligationadministrative')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_typeobligationadministrative" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-12 sm:col-span-12">
                    <label for="designation_typeobligationadministrative">Désignation</label>
                    <input type="text" id="designation_typeobligationadministrative" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                </div>
            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal typeobligationadministrative-->

<!-- debut modal typepiece -->
<div class="modal" id="modal_addtypepiece">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type de piece
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addtypepiece" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typepiece')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_typepiece" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <label for="designation_typepiece">Désignation</label>
                        <input type="text" id="designation_typepiece" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="categorietuto_typetuto" class="required">Type</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="iscommun_typepiece" name="iscommun">
                                <option value="">type</option>
                                <option value="1">commune</option>
                                <option value="0">séparée</option>
                            </select>
                        </div>
                    </div>
                </div>



                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>


            </form>
        </div>
    </div>
</div>
<!-- fin modal typepiece-->

<!-- debut modal typerenouvellement -->
<div class="modal" id="modal_addtyperenouvellement">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Type de renouvellement
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addtyperenouvellement" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'typerenouvellement')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_typerenouvellement" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-12 sm:col-span-12">
                    <label for="designation_typerenouvellement">Désignation</label>
                    <input type="text" id="designation_typerenouvellement" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                </div>
            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal typerenouvellement-->

<!-- debut modal questionnaire -->
<div class="modal" id="modal_addquestionnaire">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Questionnaire
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addquestionnaire" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'questionnaire')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_questionnaire" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-6 sm:col-span-6">
                    <label for="designation_typerenouvellement">Désignation</label>
                    <input type="text" id="designation_questionnaire" name="designation" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Type de questionnaire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typequestionnaire_questionnaire" name="typequestionnaire">
                            <option value="" class="required">Type</option>
                            <option ng-repeat="item in dataPage['typequestionnaires']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto" class="required">Type de reponse</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="reponsetype_questionnaire" name="reponsetype">
                            <option value="" class="required">reponse</option>
                            <option value="text" class="required">texte</option>
                            <option value="nombre" class="required">Chiffre</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal questionnaire-->

<!-- debut modal piece appartement -->
<div class="modal" id="modal_addpieceappartement">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Piece d'un Appartement
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addpieceappartement" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'pieceappartement')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_pieceappartement" name="id">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="designation_pieceappartement" class="required">Désignation</label>
                    <input type="text" id="designation_pieceappartement" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="désignation">
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Type de piece</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typepiece_pieceappartement" name="typepiece">
                            <option value="" class="required">Type</option>
                            <option ng-repeat="item in dataPage['typepieces']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="appartementpieceappartement col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Appartement</label>
                    <div class="inline-block relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="appartement_pieceappartement" name="appartement">
                            <option value="" class="required">Appartement</option>
                            <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Immeuble</label>
                    <div class="inline-block relative w-full">
                        <select class="block mt-2 select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="immeuble_pieceappartement" name="immeuble">
                            <option value="" class="required">Immeuble</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal piece appartement-->

<!-- debut modal demande d'intervention -->
<div class="modal" id="modal_adddemandeintervention">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Demande d'intervention
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_adddemandeintervention" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'demandeintervention')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_demandeintervention" name="id">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="designation_demandeintervention" class="required">Déscriptif</label>
                    <input type="text" id="designation_demandeintervention" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="déscriptif">
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Type d'intervention</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typeintervention_demandeintervention" name="typeintervention">
                            <option value="">Type</option>
                            <option ng-repeat="item in dataPage['typeinterventions']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Immeuble</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="immeuble_demandeintervention" name="immeuble">
                            <option value="" class="required">Immeuble</option>
                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>

                <div id="typelocatairediv" class="appintervention col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Locataire qui paye</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_locataire" id="locataire_demandeintervention" name="locataire">
                            <option value="">Locataire</option>
                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                            </option>
                        </select>
                    </div>
                </div>

                <div id="typeappartementdiv" class="appintervention col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Appartement</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_appartement" id="appartement_demandeintervention" name="appartement">
                            <option selected value="">Appartement</option>
                            <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div id="typeimmeublediv" class="appintervention col-span-6 sm:col-span-6">
                    <label for="partiecommune_intervention">Partie commune</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typepiece_demandeintervention" name="partiecommune">
                            <option value="">Partie</option>
                            <option ng-repeat="item in dataPage['typepieces']" ng-if="item.iscommun == '1'" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 md:col-span-12">
                    <div class="form-group text-center class-form">
                        <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->
                        <div>
                            <label for="imgdemandeintervention" class="cursor-pointer">
                                <img id="affimgdemandeintervention" src="{{ asset('assets/images/upload.jpg') }}" alt="..." class="image-hover shadow" style="width: 250px;height: 250px;border-radius: 10%!important;margin: 0 auto">
                                <div style="display: none;">
                                    <input type="file" accept='image/*' id="imgdemandeintervention" name="image" onchange='Chargerimage("demandeintervention")' class="required">
                                    <input type="hidden" id="erase_imgdemandeintervention" name="image_erase" value="">
                                </div>
                            </label>
                        </div>
                        <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile('imgdemandeintervention')">
                            <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>
                        </button>
                    </div>
                </div>

            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal demande d'intervention-->

<!-- debut modal devis -->
<div class="modal" id="modal_adddevi">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Formulaire Devis
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_adddevi" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'devi')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="demandeintervention_id" name="demandeintervention_id">
            <input type="hidden" id="etatlieu_id" name="etatlieu_id">
            <input type="hidden" id="id_devi" name="id">
            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#ajouterdevis" href="javascript:;" class="flex-1 py-2 rounded-md text-center active"> Devis</a>
                        <a data-toggle="tab" data-target="#detaildevis" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Détails Devis</a>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-content__pane active" id="ajouterdevis">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-3 sm:col-span-3">
                            <label for="affaireobjet_demandeintervention">Objet </label>
                            <input type="text" id="objet_demandeintervention" name="objet" class="input w-full border mt-2 flex-1" placeholder="intervenant">
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="dateenregistrement_demandeintervention">Date devis</label>
                            <input type="date" id="date_demandeintervention" name="date" class="input w-full border mt-2 flex-1" placeholder="date">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_typetuto">Locataire</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="devi_locataire_demandeintervention" name="locataire">
                                    <option value="" class="required">locataire</option>
                                    <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                        @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="appartement_etatlieu">Choisissez l'appartement</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="devi_appartement_demandeintervention" name="appartement">
                                    <option value="" class="required">appartement</option>
                                    <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">
                                        @{{ item.nom }}
                                        @{{ item.lot_ilot_refact }}
                                        {{-- <span ng-if="item.nom">
                                                @{{ item.nom }}
                                        </span>
                                        <span ng-if="item.lot">
                                            Ilot : @{{ item.ilot.numero }} / @{{ item.ilot.adresse }}
                                        </span> --}}

                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="detaildevis">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div id="selectParent" class="col-span-6 sm:col-span-6">
                            <label for="categorietuto_typetuto" style="font-weight: bold;font-size: 15px">Categorie
                                d'intervention</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white  text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="categorieintervention_detaildevis">
                                    <option selected disabled value="">Categorie</option>
                                    <option ng-repeat="item in dataPage['categorieinterventions']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-1 sm:col-span-1 text-right">
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-7" ng-click="addListeCategorie()">
                                <span class="fa fa-plus"></span>
                            </button>
                        </div>
                        <input type="hidden" id="categories_demandeintervention" name="categories" value="@{{ categoriesData }}" />

                        <!-- Inside the "detaildevis" tab -->

                        <!-- Inside the "detaildevis" tab -->
                        <div class="col-span-12 sm:col-span-12" id="container">
                            <p style="font-weight: bold;font-size: 15px" class="w-100 bg-dark text-white py-2 rounded px-2 fw-bold my-3">Listes Categories</p>
                            <ul ng-repeat="categorie in dataInTabPane['detaildevis_devis']['data']">
                                <li>
                                    <p data-index-id="@{{ $index }}" data-categorie-id="@{{ categorie.categorieintervention_id }}">

                                        <span style="font-weight: bold; font-size: 16px" class="fw-bold">
                                            @{{ $index + 1 }} </span> <span class=" mx-2" style="font-weight: bold; font-size: 16px">@{{ categorie.categorieintervention.designation }}</span>
                                        <a class="shadow my-3 p-2 bg-dark text-white rounded shadow leading-tight focus:outline-none focus:shadow-outline  border border-gray-400 hover:border-gray-500" ng-click="addListeCategorie(categorie,action='modifiercategorie',$index)">

                                            <span id="deletecategorie_detaildevisdetails_@{{ $index }}_@{{ categorie.categorieintervention_id }}" class="fa fa-trash text-danger"></span>
                                        </a>
                                    </p>



                                    <select style="width: 100%; max-width: 400px;" class="select2 my-3 p-2  rounded shadow leading-tight focus:outline-none focus:shadow-outline bg-white text-gray-700 border border-gray-400 hover:border-gray-500" id="soustypeintervention_detaildevisdetails_@{{ categorie.categorieintervention.id }}">
                                        <option selected disabled value="">Sous-type d'interventions</option>
                                        {{-- | filter: { 'itemsoustype.categorieintervention.id': categorie.categorieintervention.id } --}}
                                        <option ng-repeat="itemsoustype in dataPage['soustypeinterventions']" ng-if="itemsoustype.categorieintervention.id === categorie.categorieintervention.id" value="@{{ itemsoustype.id }}">
                                            @{{ itemsoustype.designation }}
                                        </option>





                                    </select>


                                    <a class="shadow my-3 px-4 py-2 mx-2 bg-dark text-white rounded shadow leading-tight focus:outline-none focus:shadow-outline  border border-gray-400 hover:border-gray-500" ng-click="addListeCategorie(categorie,action='add',$index)">Ajouter</a>
                                    <table class="table">
                                        <tbody ng-repeat="subcategory in categorie.subcategories.data">
                                            <tr>
                                                <td class="common-cell" style="width: 40%; height: 60px; padding: 10px;">
                                                    <span style="font-weight: bold;">&#x2B9A;</span> @{{ subcategory.designation }}
                                                </td>

                                                <td class="common-cell" style="width: 20%; height: 60px; padding: 10px; text-align: center;">
                                                    <select class="form-control unit-select" id="unite_detaildevisdetails_@{{ $index }}_@{{ categorie.categorieintervention_id }}">
                                                        <option selected disabled value="">Unités</option>
                                                        <option ng-repeat="item in dataPage['unites']" value="@{{ item.id }}">
                                                            @{{ item.designation }}
                                                        </option>
                                                    </select>
                                                </td>

                                                <td class="common-cell" style="width: 20%; height: 60px; padding: 10px; text-align: center;">
                                                    <input required type="number" class="form-control quantity-input" placeholder="Quantité" id="quantite_detaildevisdetails_@{{ $index }}_@{{ categorie.categorieintervention_id }}">
                                                </td>

                                                <td class="common-cell" style="width: 20%; height: 60px; padding: 10px; text-align: center;">
                                                    <input required type="number" class="form-control price-input" placeholder="P.U HTVA" id="puhtva_detaildevisdetails_@{{ $index }}_@{{ categorie.categorieintervention_id }}">
                                                </td>

                                                <td class="special-cell" style="width: 20%; height: 60px; padding: 10px; text-align: center;">
                                                    <a class="btn btn-danger" ng-click="addListeCategorie(categorie, action='modifiersouscategorie', $index)">
                                                        <span class="fa fa-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </li>

                            </ul>
                        </div>




                    </div>
                </div>

            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal devis-->


{{-- <div class="modal" id="modal_addsituationdepot">
        <div class="modal__content modal__content--lg">
            <div  class="flex items-center px-5 py-5 sm:py-3 header "
>
                <i class="fad fa-money-check-edit-alt mr-2"></i>
                <h2 class="font-medium text-base mr-auto">
                    Formulaire Situation Depot Garantie
                </h2>
                <div class="pull-right">
                    <button class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <form id="form_addsituationdepot" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'situationdepot')"
                style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_situationdepot" name="id">

                <input type="hidden" id="etatlieu_situationdepot" name="etatlieu">
                <input type="hidden" id="facturelocation_situationdepot" name="facturelocation">
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-4 sm:col-span-4">
                        <label for="date_situationdepot">Date </label>
                        <input type="date" id="date_situationdepot" name="date"
                        class="input w-full border mt-2 flex-1" placeholder="date">
                    </div>


                    <div class="col-span-4 sm:col-span-4"  >
                        <label for="montant_situationdepot">Montant Facture eau</label>
                        <input type="text" id="montant_situationdepot" name="montant"
                            class="input w-full border mt-2 flex-1" placeholder="montant">
                    </div>
                    <div class="col-span-4 sm:col-span-4">
                        <label for="date_situationdepot">Date Facture eau</label>
                        <input type="date" id="date_situationdepot" name="date"
                        class="input w-full border mt-2 flex-1" placeholder="date">
                    </div>
                    <div class="col-span-4 sm:col-span-4"  >
                        <label for="montant_situationdepot">Montant total devis</label>
                        <input type="text" id="montant_situationdepot" name="montant"
                            class="input w-full border mt-2 flex-1" placeholder="montant">
                    </div>



                    <div class="col-span-3 sm:col-span-3">
                        <label for="appartement_etatlieu">Choisissez l'appartement</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select
                                class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required"
                                id="devi_appartement_demandeintervention" name="appartement">
                                <option value="" class="required">appartement</option>
                                <option ng-repeat="item in dataPage['appartements']"
                                    value="@{{ item.id }}">
<span ng-if="item.nom">
    @{{ item.nom }}
</span>
<span ng-if="item.lot">
    Ilot : @{{ item.ilot.numero }} / @{{ item.ilot.adresse }}
</span>

</option>
</select>
</div>
</div>

<div class="col-span-3 sm:col-span-3">
    <label for="categorietuto_typetuto">Locataire</label>
    <div class="inline-block mt-2 relative w-full">
        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="devi_locataire_demandeintervention" name="locataire">
            <option value="" class="required">locataire</option>
            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
            </option>
        </select>
    </div>
</div>

</div>




<div class="px-5 py-3 text-right border-t border-gray-200">
    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
    </button>
    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
</div>


</form>
</div>
</div> --}}


<div class="modal" id="modal_addfactureeaux">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Formulaire Facture eaux
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addfactureeaux" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'factureeaux')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_factureeaux" name="id">

            <input type="hidden" id="contrat_factureeaux" name="contrat">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-4 sm:col-span-4">
                    <label for="debutperiode_factureeaux">Date Debut Facture </label>
                    <input type="date" id="debutperiode_factureeaux" name="debutperiode" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>
                <div class="col-span-4 sm:col-span-4">
                    <label for="quantitedebut_factureeaux">Quantité initial (m3)</label>
                    <input type="text" id="quantitedebut_factureeaux" name="quantitedebut" class="input w-full border mt-2 flex-1" placeholder="Quantité">
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <label for="finperiode_factureeaux">Date fin Facture eau</label>
                    <input type="date" id="finperiode_factureeaux" name="finperiode" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>
                <div class="col-span-4 sm:col-span-4">
                    <label for="quantitefin_factureeaux">Quantité Final (m3)</label>
                    <input type="text" id="quantitefin_factureeaux" name="quantitefin" class="input w-full border mt-2 flex-1" placeholder="Quantité">
                </div>
                <div class="col-span-4 sm:col-span-4">
                    <label for="montantfacture_factureeaux">Montant Facture (Frs)</label>
                    <input type="text" id="montantfacture_factureeaux" name="montantfacture" class="input w-full border mt-2 flex-1" placeholder="montant">
                </div>
                <div class="col-span-4 sm:col-span-4">
                    <label for="prixmetrecube_factureeaux">Prix m3 /d'eau (Frs)</label>
                    <input type="text" id="prixmetrecube_factureeaux" name="prixmetrecube" class="input w-full border mt-2 flex-1" placeholder="montant">
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <label for="consommation_factureeaux">Consommation (m3)</label>
                    <input type="text" id="consommation_factureeaux" name="consommation" class="input w-full border mt-2 flex-1" placeholder="montant">
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <label for="soldeanterieur_factureeaux">Solde anterieur (Frs)</label>
                    <input type="text" id="soldeanterieur_factureeaux" name="soldeanterieur" class="input w-full border mt-2 flex-1" placeholder="montant">
                </div>




                <div class="col-span-6 sm:col-span-6">

                    <label for="locataire_contrat">Locataire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="locataire_factureeaux" name="locataire">
                            <option value="" class="required">Choisir le locataire</option>
                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                <span ng-if="item.prenom"> @{{ item.prenom }} @{{ item.nom }} </span>
                                <span ng-if="item.nomentreprise"> @{{ item.nomentreprise }} </span>
                            </option>
                        </select>
                    </div>
                </div>

                <div class="appartementfactureeaux col-span-6 sm:col-span-6">
                    <label for="categorietuto_factureeaux">Choisissez le Contrat</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="contrat_factureeaux" name="contrat">
                            <option disabled value="">contrat</option>
                            <option ng-repeat="item in dataPage['contrats']" value="@{{ item.id }}">
                                @{{ item.descriptif }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <label for="debutperiode_factureeaux">Date echeance </label>
                    <input type="date" id="dateecheance_factureeaux" name="dateecheance" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>

            </div>




            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>


        </form>
    </div>
</div>



<!-- debut modal devis -->
<div class="modal" id="modal_addpaiementintervention">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Formulaire Paiment facture intervention
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addpaiementintervention" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'paiementintervention')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_paiementintervention" name="id">

            <input type="hidden" id="factureinterventionid_paiementintervention" name="factureinterventionid">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-4 sm:col-span-4">
                    <label for="date_paiementintervention">Date Facture</label>
                    <input type="date" id="date_paiementintervention" name="date" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <label for="categorietuto_typetuto">Choisissez le mode de Paiement</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="modepaiement_paiementintervention" name="modepaiement">
                            <option value="" class="required">Mode paiement</option>
                            <option ng-repeat="item in dataPage['modepaiements']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>

                    </div>
                </div>

                <div class="col-span-4 sm:col-span-4" id="hidde_montant_paiementintervention">
                    <label for="montant_paiementintervention">Montant </label>
                    <input type="text" id="montant_paiementintervention" name="montant" class="input w-full border mt-2 flex-1" placeholder="montant">
                </div>

                <div class="col-span-4 sm:col-span-4" style="display: none" id="hidde_cheque_paiementintervention">

                    <label for="cheque_paiementintervention">Numero cheque </label>
                    <input type="text" id="cheque_paiementintervention" name="cheque" class="input w-full border mt-2 flex-1" placeholder="Numero cheque">
                </div>

            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>



<!-- fin modal devis-->




<!-- debut modal intervention -->
<div class="modal" id="modal_addintervention">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Intervention
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addintervention" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'intervention')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_intervention" name="id">
            <input type="hidden" id="demandeintervention_intervention_id" name="id_demandeintervention">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-12 sm:col-span-12">
                    <label for="categorietuto_typetuto">Demande d'intervention</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="demandeintervention_intervention" name="demandeintervention">
                            <option value="">Demande</option>
                            <option ng-repeat="item in dataPage['demandeinterventions']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="dateintervention_intervention">Date d'intervention</label>
                    <input type="date" id="dateintervention_intervention" name="dateintervention" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="datefinintervention_intervention">Date fin intervention</label>
                    <input type="date" id="datefinintervention_intervention" name="datefinintervention" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="descriptif_intervention" class="required">Déscriptif</label>
                    <input type="text" id="descriptif_intervention" name="descriptif" class="input w-full border mt-2 flex-1 required" placeholder="déscriptif">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Categorie d'intervention</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="categorieintervention_intervention" name="categorieintervention">
                            <option value="">Categorie</option>
                            <option ng-repeat="item in dataPage['categorieinterventions']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="etat_intervention">Etat d'avancement</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="etat_intervention" name="etat">
                            <option value="">Etat</option>
                            <option value="En attente">En attente</option>
                            <option value="En cours de traitement">En cours de traitement</option>
                            <option value="Traité">Traité</option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Type d'intervenant</label><br><br>
                    <label>
                        <input id="check_typeintervenantemploye" required="" name="type_intervenant" onchange="showInput(this,'employe','typeintervenant')" value="employé" type="radio" />
                        <span>Employé</span>
                    </label>
                    <label>
                        <input id="check_typeintervenantprestataire" name="type_intervenant" onchange="showInput(this,'prestataire','typeintervenant')" value="prestataire" type="radio" />
                        <span>Prestataire</span>
                    </label>
                </div>
                <div class="prestataireintervention col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Prestataire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="prestataire_intervention" name="prestataire">
                            <option value="" class="required">prestataire</option>
                            <option ng-repeat="item in dataPage['prestataires']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="employeintervention col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Employé</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="user_intervention" name="user">
                            <option value="" class="required">employé</option>
                            <option ng-repeat="item in dataPage['users']" ng-if="item.roles[0].name == 'technicien'" value="@{{ item.id }}">
                                @{{ item.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal intervention-->


<!-- debut modal rapportintervention -->
<div class="modal" id="modal_addrapportintervention">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Rapport intervention
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addrapportintervention" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'rapportintervention')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_rapportintervention" name="id">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infos" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos</a>
                        <a data-toggle="tab" data-target="#produits" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Produits</a>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-content__pane active" id="infos">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_typetuto">Intervention</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="intervention_rapportintervention" name="intervention">
                                    <option value="">intervention</option>
                                    <option ng-repeat="item in dataPage['interventions']" value="@{{ item.id }}">
                                        @{{ item.descriptif }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_typetuto">Immeuble</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="immeuble_rapportintervention" name="immeuble">
                                    <option value="">immeuble</option>
                                </select>
                            </div>
                        </div>
                        <div id="divappartement_rapportintervention" class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_typetuto">Appartement</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="appartement_rapportintervention" name="appartement">
                                    <option value="">appartement</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="prenom_rapportintervention" class="required">Nom du technicien</label>
                            <input type="text" id="prenom_rapportintervention" name="prenom" class="input w-full border mt-2 flex-1 required" placeholder="prénom">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="compagnietechnicien_rapportintervention" class="required">Compagnie du
                                technicien</label>
                            <input type="text" id="compagnietechnicien_rapportintervention" name="compagnietechnicien" class="input w-full border mt-2 flex-1 required" placeholder="compagnietechnicien">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="debut_rapportintervention" class="required">Date de debut</label>
                            <input type="date" id="debut_rapportintervention" name="debut" class="input w-full border mt-2 flex-1 required" placeholder="date">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="fin_rapportintervention" class="required">Date de fin</label>
                            <input type="date" id="fin_rapportintervention" name="fin" class="input w-full border mt-2 flex-1 required" placeholder="date">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="observations_rapportintervention" class="required">Observations</label>
                            <input type="text" id="observations_rapportintervention" name="observations" class="input w-full border mt-2 flex-1 required" placeholder="observation">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="etat_rapportintervention" class="required">Etat</label>
                            <input type="text" id="etat_rapportintervention" name="etat" class="input w-full border mt-2 flex-1 required" placeholder="etat">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="recommandations_rapportintervention" class="required">Recommandations</label>
                            <input type="text" id="recommandations_rapportintervention" name="recommandations" class="input w-full border mt-2 flex-1 required" placeholder="recommandations">
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="produits">

                    <div id="produitsutilisesdiv" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-1 sm:col-span-1 mr-12">
                            <label>Produit</label>
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('produit_rapportintervention')"><span class="fa fa-plus"></span></button>
                        </div>
                        <div class="col-span-11 sm:col-span-11 mr-12">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal rapportintervention-->



<!-- debut modal etat lieu -->
<div class="modal" id="modal_addetatlieu">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Etat des lieux
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addetatlieu" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'etatlieu')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_etatlieu" name="id">
            <input type="hidden" id="id_appartement_etatlieu" name="appartement">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infogeneraletat" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos générales</a>
                        <a data-toggle="tab" data-target="#pieceetat" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Etat des pieces</a>
                        <a data-toggle="tab" data-target="#equipementsgenerales" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Equipements generales</a>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-content__pane active" id="infogeneraletat">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <input type="hidden" name="imagesource2" value="{{ asset('assets/images/upload.jpg') }}" id="imagesource2" />
                        <input type="hidden" id="compteurimage_etatlieu" name="compteurimageetatlieu">
                        <div class="col-span-3 sm:col-span-3">
                            <label for="appartement_etatlieu">Choisissez l'appartement</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="appartement_etatlieu" name="appartement">
                                    <option value="" class="required">appartement</option>
                                    <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">
                                        {{-- <span ng-if="item.nom">
                                                @{{ item.nom }}
                                        </span>
                                        <span ng-if="!item.nom && item.ilot && item.ilot.numero">

                                            Ilot : @{{ item.ilot.numero }} / @{{ item.ilot.adresse }}
                                        </span> --}}
                                        @{{ item.nom }}
                                        @{{ item.lot_ilot_refact }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="designation_etatlieu" class="required">Désignation</label>
                            <input type="text" id="designation_etatlieu" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="désignation">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_typetuto">Type</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="type_etatlieu" name="type">
                                    <option value="" class="required">type</option>
                                    <option value="entrée" selected>Entrée</option>
                                    <option value="sortie">Sortie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_typetuto">Locataire</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="locataire_etatlieu" name="locataire">
                                    <option value="" class="required">locataire</option>
                                    <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                        @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="dateredaction_etatlieu" class="required">Date de redaction</label>
                            <input type="date" id="dateredaction_etatlieu" name="dateredaction" class="input w-full border mt-2 flex-1 required" placeholder="date">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="etatgenerale_etatlieu" class="required">Etat de l'appartement</label>
                            <input type="text" id="etatgenerale_etatlieu" name="etatgenerale" class="input w-full border mt-2 flex-1 required" placeholder="etat">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="particularite_etatlieu">Particularité de l'appartement</label>
                            <input type="text" id="particularite_etatlieu" name="particularite" class="input w-full border mt-2 flex-1" placeholder="particularité">
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="pieceetat">

                    <div ng-if="compositionappartement" class="intro-y pr-1 mt-1">
                        <div class="box p-2 item-tabs-produit">
                            <div class="pos__tabs nav-tabs justify-center flex">
                                <a ng-repeat="composition in compositionappartement" data-toggle="tab" data-target="#composition_@{{ composition.id }}" href="javascript:;" class="flex-1 py-2 rounded-md text-center">@{{ composition.typeappartement_piece.typepiece.designation }}</a>
                            </div>
                        </div>
                    </div>


                    <div ng-if="compositionappartement" class="tab-content">

                        <div ng-repeat="composition in compositionappartement" class="tab-content__pane" id="composition_@{{ composition.id }}">
                            <input type="hidden" id="@{{ composition.id }}_composition_etatlieu" value="@{{ composition.id }}" name="composition_@{{ composition.id }}">
                            <input type="hidden" id="compositionEtatlieu_@{{ composition.id }}" name="compositionEtatlieu_@{{ composition.id }}">

                            <div class="form-section pl-5 pt-3  text-xl">
                                <i class="fa fa-info-circle text-blue-400"></i> Photo de la piece
                            </div>
                            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                                <div class="col-span-10 sm:col-span-10">
                                    <button type="button" class="button w-10 bg-theme-101 text-white" ng-click="addfields('photo_pieceetatlieu',composition.id)"><span class="fa fa-plus"></span></button>
                                </div>
                            </div>
                            <div id="photopieceetatlieu@{{ composition.id }}" class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                            </div>
                            <div class="form-section pl-5 pt-3  text-xl">
                                <i class="fa fa-info-circle text-blue-400"></i> Constituants
                            </div>
                            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                                <div ng-repeat="item2 in dataPage['constituantpieces']" class="col-span-4 sm:col-span-4" style=" padding: 10px;border: 1px solid rgba(0, 0, 0, 0.05)">
                                    <label for="categorietuto_typetuto">@{{ item2.designation }}</label>
                                    <div>
                                        <select id="@{{ item2.id }}_@{{ composition.id }}_observation_etatlieu" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" name="observation_@{{ item2.id }}_@{{ composition.id }}">
                                            <option value="" selected class="required">observation</option>
                                            <option ng-repeat="item3 in dataPage['observations']" value="@{{ item3.id }}">
                                                @{{ item3.designation }}
                                            </option>
                                        </select>
                                    </div>
                                    <input type="text" name="observation_@{{ item2.id }}_@{{ composition.id }}_commentaire" id="id_observation_@{{ item2.id }}_@{{ composition.id }}_commentaire" class="input w-full border mt-2 flex-1" placeholder="commentaire...">
                                </div>

                            </div>

                            <div class="form-section pl-5 pt-3  text-xl">
                                <i class="fa fa-info-circle text-blue-400"></i> Equipements
                            </div>
                            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                                <div ng-repeat="detailcomposition in detailcompositionappartement" ng-if="composition.typeappartement_piece.id == detailcomposition.idDetailtypeappartement && detailcomposition.equipement.generale == '0'" class="col-span-4 sm:col-span-4" style=" padding: 10px;border: 1px solid rgba(0, 0, 0, 0.05)">
                                    <input type="hidden" id="@{{ detailcomposition.equipement.id }}_detailcomposition_etatlieu" value="@{{ detailcomposition.equipement.id }}" name="detailcomposition_@{{ detailcomposition.equipement.id }}">
                                    <label for="categorietuto_typetuto">@{{ detailcomposition.equipement.designation }}</label>
                                    <div>
                                        <select id="@{{ detailcomposition.equipement.id }}_@{{ composition.id }}composition_observation_etatlieu" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" name="equipement_observation_@{{ detailcomposition.equipement.id }}_@{{ composition.id }}">
                                            <option value="" selected class="required">observation</option>
                                            <option ng-repeat="item3 in dataPage['observations']" value="@{{ item3.id }}">
                                                @{{ item3.designation }}
                                            </option>
                                        </select>
                                    </div>
                                    <input type="text" id="id_equipement_observation_@{{ detailcomposition.equipement.id }}_@{{ composition.id }}_commentaire" name="equipement_observation_@{{ detailcomposition.equipement.id }}_@{{ composition.id }}_commentaire" class="input w-full border mt-2 flex-1" placeholder="commentaire...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  <div id="pieceetatlieudiv" class="p-5 grid grid-cols-12 gap-4 row-gap-3" ng-repeat="item in dataPage['pieceappartements']" ng-if="item.appartement.id && item.appartement.id == showpieceId">
                                <div class="col-span-12 sm:col-span-12">
                                    <h2 style="background-color: black ; color: white ; border-radius: 5px" class="text-center"  for="categorietuto_typetuto">@{{ item.designation }}</h2>
                                </div>
                                <div ng-repeat="item2 in dataPage['constituantpieces']"  class="col-span-4 sm:col-span-4" style=" padding: 10px;border: 1px solid rgba(0, 0, 0, 0.05)">
                                    <label for="categorietuto_typetuto">@{{ item2.designation }}</label>
                                    <div>
                                        <select id="item.id item2.id '_etatlieu'" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" name="typepiece'+identifiant+'_constituant_observation_'+elmt.id+'" >
                                            <option value="" selected class="required">observation</option>
                                            <option ng-repeat="item3 in dataPage['observations']" value="@{{ item3.id }}">
                                                @{{ item3.designation }}
                                            </option>
                                        </select>
                                    </div>
                                    <input type="text" name="typepiece'+identifiant+'_constituant_commentaire_'+elmt.id+'" class="input w-full border mt-2 flex-1" placeholder="commentaire...">
                                </div>
                                <div class="col-span-10 sm:col-span-10">
                                    <label for="produit_logistique_proforma" class="required">Equipement</label>
                                    <div class="col-span-12 sm:col-span-12">
                                        <select class="form-control block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="equipementpiece_etatlieu_equipementpiece_etatlieu" style="width: 100% !important;">
                                            <option value="">equipement</option>
                                            <option ng-repeat="item in dataPage['equipementpieces']" value="@{{ item.id }}">@{{ item.designation }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-span-2 sm:col-span-2 text-right">
                                    <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="actionSurTabPaneTagData('add','user_departement_user')"><span class="fa fa-plus"></span></button>
                                </div>

                                <div class="col-span-12 sm:col-span-12">
                                    <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                        <table class="table table-report sm:mt-1">
                                            <thead>
                                            <tr style="background-color: #f3f7f8">
                                                <th hidden class="whitespace-no-wrap">#</th>
                                                <th class="whitespace-no-wrap">Equipement</th>
                                                <th class="whitespace-no-wrap text-center">Etat</th>
                                                <th class="text-center whitespace-no-wrap">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="intro-x" ng-repeat="item in dataInTabPane['user_departement_user']['data']">

                                                <td hidden class="">
                                                    <div class="font-medium whitespace-no-wrap">@{{ item.id }}</div>
                                                </td>

                                                <td class="">
                                                    <div class="font-medium whitespace-no-wrap">@{{ item.departement_text }}</div>
                                                </td>

                                                <td class="table-report__action w-56">
                                                    <nav class="menu-leftToRight uk-flex text-center">
                                                        <input type="checkbox" href="#" class="menu-open" name="menu-open"  id="menu-open1-@{{ $index }}">
                                                        <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                            <span class="hamburger bg-template-1 hamburger-1"></span>
                                                            <span class="hamburger bg-template-1 hamburger-2"></span>
                                                            <span class="hamburger bg-template-1 hamburger-3"></span>
                                                        </label>

                                                        <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'user_departement_user', $index)"  title="Supprimer">
                                                            <span class="fa fa-trash-alt"></span>
                                                        </button>
                                                        <button type="button" class="menu-item btn border-0 bg-success text-white fsize-16" ng-click="actionSurTabPaneTagData('update', 'user_departement_user', $index, '', null, 'etat', 1)" ng-if="item.etat == 0" title="Activé">
                                                            <span class="fa fa-thumbs-up"></span>
                                                        </button>
                                                        <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('update', 'user_departement_user', $index, '', null, 'etat', 0)" ng-if="item.etat == 1" title="Desactivé">
                                                            <span class="fa fa-thumbs-down"></span>
                                                        </button>

                                                    </nav>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>
                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-12 sm:col-span-12 text-right mr-5">
                                <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('etatpiece')"><span class="fa fa-plus"></span></button>
                            </div>
                        </div>-->
                </div>
                <div class="tab-content__pane" id="equipementsgenerales">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-4 sm:col-span-4" style=" padding: 10px;border: 1px solid rgba(0, 0, 0, 0.05)" ng-repeat="detailcomposition in detailcompositionappartement" ng-if="detailcomposition.equipement.generale == '1'">
                            <label for="categorietuto_typetuto">@{{ detailcomposition.equipement.designation }}</label>
                            <input type="hidden" id="@{{ detailcomposition.equipement.id }}_detailcomposition_etatlieu" value="@{{ detailcomposition.equipement.id }}" name="detailcomposition_@{{ detailcomposition.equipement.id }}">

                            <div>
                                <select id="@{{ detailcomposition.equipement.id }}_@{{ detailcomposition.appartement.id }}composition_observationgenerale_etatlieu" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" name="equipementgenerale_observation_@{{ detailcomposition.equipement.id }}_@{{ detailcomposition.appartement.id }}">
                                    <option value="" selected class="required">observation</option>

                                    <option ng-repeat="item3 in dataPage['observations']" value="@{{ item3.id }}">
                                        @{{ item3.designation }}
                                    </option>
                                </select>
                            </div>
                            <input type="text" id="id_equipementgenerale_observation_@{{ detailcomposition.equipement.id }}_@{{ detailcomposition.appartement.id }}_commentaire" name="equipementgenerale_observation_@{{ detailcomposition.equipement.id }}_@{{ detailcomposition.appartement.id }}_commentaire" class="input w-full border mt-2 flex-1" placeholder="commentaire...">

                            <!--
                                <div class="inline-block mt-2 relative w-full">
                                    <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="observationequipementgeneral_@{{ item.id }}_etatlieu" name="observationequipementgeneral_@{{ item.id }}" >
                                        <option value="" class="required">observation</option>
                                        <option ng-repeat="item2 in dataPage['observations']" value="@{{ item2.id }}">
                                            @{{ item2.designation }}
                                        </option>
                                    </select>
                                </div>
                            <input type="text" id="commentaireequipementgeneral_@{{ item.id }}_etatlieu" name="commentaireequipementgeneral_@{{ item.id }}" class="input w-full border mt-2 flex-1" placeholder="commentaire...">-->
                        </div>
                        <div class="col-span-4 sm:col-span-4" style=" padding: 10px;border: 1px solid rgba(0, 0, 0, 0.05)" ng-repeat="detailcomposition in detailcompositionappartementchange" ng-if="detailcomposition.equipement.generale == '1'">
                            <label for="categorietuto_typetuto">@{{ detailcomposition.equipement.designation }}</label>
                            <input type="hidden" id="@{{ detailcomposition.equipement.id }}_detailcomposition_etatlieu" value="@{{ detailcomposition.equipement.id }}" name="detailcomposition_@{{ detailcomposition.equipement.id }}">

                            <div>
                                <select id="@{{ detailcomposition.equipement.id }}_@{{ detailcomposition.appartement.id }}composition_observationgenerale_etatlieu" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" name="equipementgenerale_observation_@{{ detailcomposition.equipement.id }}_@{{ detailcomposition.appartement.id }}">
                                    <option value="" selected class="required">observation</option>
                                    <option ng-repeat="item3 in dataPage['observations']" value="@{{ item3.id }}">
                                        @{{ item3.designation }}
                                    </option>
                                </select>
                            </div>
                            <input type="text" id="id_equipementgenerale_observation_@{{ detailcomposition.equipement.id }}_@{{ detailcomposition.appartement.id }}_commentaire" name="equipementgenerale_observation_@{{ detailcomposition.equipement.id }}_@{{ detailcomposition.appartement.id }}_commentaire" class="input w-full border mt-2 flex-1" placeholder="commentaire...">

                            <!--
                                <div class="inline-block mt-2 relative w-full">
                                    <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="observationequipementgeneral_@{{ item.id }}_etatlieu" name="observationequipementgeneral_@{{ item.id }}" >
                                        <option value="" class="required">observation</option>
                                        <option ng-repeat="item2 in dataPage['observations']" value="@{{ item2.id }}">
                                            @{{ item2.designation }}
                                        </option>
                                    </select>
                                </div>
                            <input type="text" id="commentaireequipementgeneral_@{{ item.id }}_etatlieu" name="commentaireequipementgeneral_@{{ item.id }}" class="input w-full border mt-2 flex-1" placeholder="commentaire...">-->
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                    </button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
        </form>
    </div>
</div>
<!-- fin modal etat des lieux-->

<!-- debut modal membreequipegestion -->
<div class="modal" id="modal_addmembreequipegestion">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-user-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Membre equipe de gestion
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addmembreequipegestion" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'membreequipegestion')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_membreequipegestion" name="id">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-6 sm:col-span-6">
                    <label for="prenom_membreequipegestion" class="required">Prenom</label>
                    <input type="text" id="prenom_membreequipegestion" name="prenom" class="input w-full border mt-2 flex-1 required" placeholder="prénom">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="nom_membreequipegestion" class="required">Nom</label>
                    <input type="text" id="nom_membreequipegestion" name="nom" class="input w-full border mt-2 flex-1 required" placeholder="nom">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="email_membreequipegestion">Email</label>
                    <input type="text" id="email_membreequipegestion" name="email" class="input w-full border mt-2 flex-1" placeholder="email">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="telephone_membreequipegestion" class="required">Telephone</label>
                    <input type="text" id="telephone_membreequipegestion" name="telephone" class="input w-full border mt-2 flex-1 required" placeholder="telephone">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal membreequipegestion-->

<!-- debut modal equipegestion -->
<div class="modal" id="modal_addequipegestion">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Equipe de gestion
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addequipegestion" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'equipegestion')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_equipegestion" name="id">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#designation" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Designation</a>
                        <a data-toggle="tab" data-target="#immeubles" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Immeubles gérés</a>
                        <a data-toggle="tab" data-target="#membres" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Membres</a>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-content__pane active" id="designation">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-12 sm:col-span-12">
                            <label for="designation_equipegestion" class="required">Désignation</label>
                            <input type="text" id="designation_equipegestion" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="désignation">
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="immeubles">
                    <div id="immeubleequipegestiondiv" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-1 sm:col-span-1 mr-12">
                            <label>Immeuble</label>
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('immeuble_equipegestion')"><span class="fa fa-plus"></span></button>
                        </div>
                        <div class="col-span-11 sm:col-span-11 mr-12">
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="membres">
                    <div id="membreequipegestiondiv" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-6 sm:col-span-6 mr-12">
                            <label>Membres de l'equipe</label><br>
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('membreequipegestion_equipegestion')"><span class="fa fa-plus"></span></button>
                        </div>
                        <div class="col-span-6 sm:col-span-6 mr-12">
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                    </button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
        </form>
    </div>
</div>
<!-- fin modal equipegestion-->

<!-- debut modal message -->
<div class="modal" id="modal_addmessage">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Message
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addmessage" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'message')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_message" name="id">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#contenue" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Contenue</a>
                        <a data-toggle="tab" data-target="#destinataires" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Destinataires</a>
                        <a data-toggle="tab" data-target="#documents" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Documents</a>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-content__pane active" id="contenue">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-3 sm:col-span-3">
                            <label for="objet_message" class="required">Objet</label>
                            <input type="text" id="objet_message" name="objet" class="input w-full border mt-2 flex-1 required" placeholder="objet">
                        </div>
                        <div class="col-span-9 sm:col-span-9">
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="contenu_message" class="required">Contenu</label>
                            <input type="textarea" id="contenu_message" name="contenu" class="input w-full border mt-2 flex-1 required" placeholder="contenu">
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="destinataires">
                    <div id="messagelocatairediv" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-1 sm:col-span-1 mr-12">
                            <label>Locataires</label>
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('locataire_message')"><span class="fa fa-plus"></span></button>
                        </div>
                        <div class="col-span-1 sm:col-span-1 mr-12">
                            <label>Proprietaires</label>
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('proprietaire_message')"><span class="fa fa-plus"></span></button>
                        </div>
                        <div class="col-span-10 sm:col-span-10 mr-12">
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="documents">
                    <div id="messagedocumentdiv" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-6 sm:col-span-6 mr-12">
                            <label>Ajouter un document</label><br>
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('document_message')"><span class="fa fa-plus"></span></button>
                        </div>
                        <div class="col-span-6 sm:col-span-6 mr-12">
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                    </button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
        </form>
    </div>
</div>
<!-- fin modal message-->

<!-- debut modal questionnairesatisfaction -->
<div class="modal" id="modal_addquestionnairesatisfaction">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Questionnaire de satisfaction
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addquestionnairesatisfaction" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'questionnairesatisfaction')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_questionnairesatisfaction" name="id">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#contenuequestionnaire" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Contenue</a>
                        <a data-toggle="tab" data-target="#destinatairesquestionnaire" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Destinataires</a>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-content__pane active" id="contenuequestionnaire">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-3 sm:col-span-3">
                            <label for="titre_questionnairesatisfaction" class="required">Titre</label>
                            <input type="text" id="titre_questionnairesatisfaction" name="titre" class="input w-full border mt-2 flex-1 required" placeholder="titre">
                        </div>
                        <div class="col-span-9 sm:col-span-9">
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <label for="contenu_questionnairesatisfaction" class="required">Question</label>
                            <input type="textarea" id="contenu_questionnairesatisfaction" name="contenu" class="input w-full border mt-2 flex-1 required" placeholder="contenu">
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="destinatairesquestionnaire">
                    <div id="questionnairelocatairediv" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-1 sm:col-span-1 mr-12">
                            <label>Locataires</label>
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('locataire_questionnairesatisfaction')"><span class="fa fa-plus"></span></button>
                        </div>
                        <div class="col-span-1 sm:col-span-1 mr-12">
                            <label>Proprietaires</label>
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('proprietaire_questionnairesatisfaction')"><span class="fa fa-plus"></span></button>
                        </div>
                        <div class="col-span-10 sm:col-span-10 mr-12">
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                    </button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
        </form>
    </div>
</div>
<!-- fin modal message-->

<!-- debut modal appartement -->
<!-- <div class="modal" id="modal_addappartement">
        <div class="modal__content modal__content--md">
            <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 bg-scireyhan text-white">
                <i class="fa fa-house mr-2"></i>
                <h2 class="font-medium text-base mr-auto">
                    Appartement
                </h2>
                <div class="pull-right">
                    <button class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <form id="form_addappartement" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'appartement')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_appartement" name="id">
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-6 sm:col-span-6">
                        <label for="nom_appartement" class="required">Nom</label>
                        <input type="text" id="nom_appartement" name="nom" class="input w-full border mt-2 flex-1 required" placeholder="nom">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="categorietuto_typetuto">Immeuble</label>
                        <div class="inline-block relative w-full">
                            <select class="block mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="immeuble_appartement" name="immeuble" >
                                <option value="" class="required">Immeuble</option>
                                <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}">
                                    @{{ item.nom }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="categorietuto_typetuto">Proprietaire</label>
                        <div class="inline-block relative w-full">
                            <select class="block mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="proprietaire_appartement" name="proprietaire" >
                                <option value="" class="required">Proprietaire</option>
                                <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">
                                    @{{ item.nom }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="categorietuto_typetuto">Type d'appartement</label>
                        <div class="inline-block relative w-full">
                            <select class="block mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typeappartement_appartement" name="typeappartement" >
                                <option value="" class="required">Type</option>
                                <option ng-repeat="item in dataPage['typeappartements']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="categorietuto_typetuto">Frenquence de paiement</label>
                        <div class="inline-block relative w-full">
                            <select class="block mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="frequencepaiementappartement_appartement" name="frequencepaiementappartement" >
                                <option value="" class="required">Type</option>
                                <option ng-repeat="item in dataPage['frequencepaiementappartements']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="categorietuto_typetuto">Etat de l'appartement</label>
                        <div class="inline-block relative w-full">
                            <select class="block mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="etatappartement_appartement" name="etatappartement" >
                                <option value="" class="required">Etat</option>
                                <option ng-repeat="item in dataPage['etatappartements']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                    </button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div> -->
<!-- fin modal appartement-->

<!-- debut modal locataire -->
<div class="modal" id="modal_addlocataire">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-house mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Locataire / Reservataire
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addlocataire" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'locataire')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_locataire" name="id">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infolocataires" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos générales</a>
                        <a data-toggle="tab" data-target="#copreneurs" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Co-preneurs</a>

                    </div>
                </div>
            </div>
            <div class="tab-content">

                <div class="tab-content__pane active" id="infolocataires">
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-info-circle text-blue-400"></i>Informations générales
                        <div class="ml-2 custom-control custom-switch" style="cursor:pointer;">
                            <input type="checkbox" onchange="onActivateCopreneur(this)" style="cursor:pointer;" name="est_copreuneur" class="custom-control-input" id="est_copreuneur_locataire">
                            <label class="custom-control-label" for="est_copreuneur_locataire"> <em>A cocher seulement en cas de co-réservation</em> </label>
                        </div>

                    </div>
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-4 sm:col-span-4">
                            <label for="categorietuto_typetuto" class="required">Choisissez le type de locataire</label>
                            <div class="inline-block relative w-full mt-2">
                                <select onchange="typeLocataire(this)" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typelocataire_locataire" name="typelocataire">
                                    <option value="" class="required">Type</option>
                                    <option ng-repeat="item in dataPage['typelocataires']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-4 sm:col-span-4">
                            <label for="categorietuto_typetuto" class="required">Choisissez le programme</label>
                            <div class="inline-block relative w-full mt-2">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="entite_locataire" name="entite">
                                    <option value="" class="required">Programme</option>
                                    <option ng-repeat="item in dataPage['entites']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-4 sm:col-span-4">
                            <label for="categorietuto_typetuto" class="required">Choisissez le pays de résidence</label>
                            <x-country-list :idName="'paysnaissance_locataire'" :name="'paysnaissance'" />
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="prenomlocataire_locataire" class="required">Prenom</label>
                            <input type="text" id="prenomlocataire_locataire" name="prenom" class=" input w-full inline-block relative border mt-2 flex-1" placeholder="prenom">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="nomlocatairelocataire_locataire" class="required">Nom</label>
                            <input type="text" id="nomlocataire_locataire" name="nom" class=" input w-full inline-block relative border mt-2 flex-1" placeholder="nom">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="emaillocataire_locataire" class="required">Email</label>
                            <input type="text" id="emaillocataire_locataire" name="email" class="required input w-full border mt-2 flex-1" placeholder="email">
                        </div>

                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="numeroclient_locataire" class="">Numéro client</label>
                            <input type="text" id="numeroclient_locataire" name="numeroclient" class=" input w-full border mt-2 flex-1" placeholder="numero client">
                        </div>

                        <div class="1 col-span-3 sm:col-span-3 ">
                            <label for="telephoneportable1locataire_locataire" class="required">Telephone 1</label>

                            <input type="tel" id="telephoneportable1locataire_locataire" name="telephoneportable1" class="required input w-full border mt-2 flex-1 " placeholder="portable 1">
                            {{-- <input type="hidden" name="countryCode1telephone1" id="telephoneportable1"> --}}

                        </div>


                        <div class="1 col-span-3 sm:col-span-3 mt-2">
                            <label for="telephoneportable2locataire_locataire">Telephone 2</label>
                            <input type="tel" id="telephoneportable2locataire_locataire" name="telephoneportable2" class="input w-full border mt-2 flex-1 " placeholder="">
                            {{-- <input type="hidden" name="telephoneportable2_dialCode" id="telephoneportable2_dialCode"> --}}
                        </div>
                        <div class="1 col-span-3 sm:col-span-3 mt-2">
                            <label for="telephonebureaulocataire_locataire">Telephone bureau</label>
                            <input type="tel" id="telephonebureaulocataire_locataire" name="telephonebureau" class="input w-full border mt-2 flex-1 " placeholder="">
                            {{-- <input type="hidden" name="countryCode3telephone3" id="telephoneportable3"> --}}
                        </div>

                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="professionlocataire_locataire">Profession</label>
                            <input type="text" id="professionlocataire_locataire" name="profession" class="input w-full border mt-2 flex-1" placeholder="profession">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="datenaissance_contrat">Date de naissance</label>
                            <input type="date" id="datenaissance_contrat" name="date_naissance" class="input w-full border flex-1" placeholder="datenaissance">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="lieuxnaissance_locataire" class="">Lieux de naissance</label>
                            <input type="text" id="lieuxnaissance_locataire" name="lieux_naissance" class=" input w-full inline-block relative border mt-2 flex-1" placeholder="lieux naissance">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="nationalite_locataire" class="">Nationalité</label>
                            <input type="text" id="nationalite_locataire" name="nationalite" class=" input w-full border mt-2 flex-1" placeholder="nationalité">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="ville_locataire" class="">Ville</label>
                            <input type="text" id="ville_locataire" name="ville" class=" input w-full border mt-2 flex-1" placeholder="ville">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="codepostal_locataire" class="">Code postal</label>
                            <input type="text" id="codepostal_locataire" name="codepostal" class=" input w-full border mt-2 flex-1" placeholder="code postal">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="njf_locataire" class="">Njf</label>
                            <input type="text" id="njf_locataire" name="njf" class=" input w-full border mt-2 flex-1" placeholder="njf">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="situationfamiliale_locataire" class="">Situation familiale</label>
                            <input type="text" id="situationfamiliale_locataire" name="situationfamiliale" class=" input w-full border mt-2 flex-1" placeholder="situation familiale">
                        </div>

                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="adresselocataire_locataire" class="">Adresse</label>
                            <input type="text" id="adresselocataire_locataire" name="adresse" class="input w-full border mt-2 flex-1" placeholder="adresse">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="mandataire_locataire" class="">Mandataire</label>
                            <input type="text" id="mandataire_locataire" name="mandataire" class=" input w-full inline-block relative border mt-2 flex-1" placeholder="mandataire">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="cnilocataire_locataire">CNI</label>
                            <input type="text" id="cnilocataire_locataire" name="cni" class="input w-full border mt-2 flex-1" placeholder="cni">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="passeportlocataire_locataire">Passeport</label>
                            <input type="text" id="passeportlocataire_locataire" name="passeport" class="input w-full border mt-2 flex-1" placeholder="passeport">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="revenulocataire_locataire">Revenus</label>
                            <input type="text" id="revenulocataire_locataire" name="revenus" class="required input w-full border mt-2 flex-1" placeholder="revenu">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="documentcontrattravaillocataire_locataire">Contrat de travail ou justificatif de
                                revenus</label>
                            <input type="file" id="documentcontrattravaillocataire_locataire" name="documentcontrattravail" class="input w-full border mt-2 flex-1" placeholder="document">
                        </div>
                        <div class="1 col-span-1 sm:col-span-1">
                            <input type="radio" id="expatlocataire_locataire" name="expatlocale" value="Expatrié" class="input w-full border mt-2 flex-1">
                            <label for="expatlocataire_locataire">Expatrié</label><br>
                        </div>
                        <div class="1 col-span-1 sm:col-span-1">
                            <input type="radio" id="localelocataire_locataire" name="expatlocale" value="Locale" class="input w-full border mt-2 flex-1">
                            <label for="localelocataire_locataire">Locale</label><br>
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <input type="checkbox" id="priseencharge_locataire" name="priseencharge" value="Oui" class="input w-full border mt-2 flex-1">
                            <label for="priseencharge_locataire">Prise en charge</label><br>
                            <input type="text" id="nomcompletpersonnepriseencharge_locataire" name="nomcompletpersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="nom complet de la personne responsable">
                            <input type="text" id="telephonepersonnepriseencharge_locataire" name="telephonepersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="telephone de la personne responsable">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="categorietuto_typetuto" class="">Choisissez le secteur d'activité</label>
                            <div class="inline-block relative w-full mt-2">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="secteuractivite_locataire" name="secteuractivite">
                                    <option value="" class="required">Secteur</option>
                                    <option ng-repeat="item in dataPage['secteuractivites']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="nomentrepriselocataire_locataire" class="required">Nom entreprise</label>
                            <input type="text" id="nomentrepriselocataire_locataire" name="nomentreprise" class="input w-full border mt-2 flex-1" placeholder="nom de l'entreprise">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="email2locataire_locataire" class="required">Email</label>
                            <input type="email" id="email2locataire_locataire" name="email2" class="input w-full border mt-2 flex-1" placeholder="email">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="adresseentrepriselocataire_locataire" class="required">Adresse</label>
                            <input type="text" id="adresseentrepriselocataire_locataire" name="adresseentreprise" class="input w-full border mt-2 flex-1" placeholder="adresse">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="ninealocataire_locataire">Ninea</label>
                            <input type="text" id="ninealocataire_locataire" name="ninea" class="input w-full border mt-2 flex-1" placeholder="ninea">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="documentninealocataire_locataire">Document Ninea</label>
                            <input type="file" id="documentninealocataire_locataire" name="documentninea" class="input w-full border mt-2 flex-1" placeholder="document ninea">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="numerorglocataire_locataire">Numero RG</label>
                            <input type="text" id="numerorglocataire_locataire" name="numerorg" class="input w-full border mt-2 flex-1" placeholder="numerorg">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="documentnumerorglocataire_locataire">Document num RG</label>
                            <input type="file" id="documentnumerorglocataire_locataire" name="documentnumerorg" class="input w-full border mt-2 flex-1" placeholder="document numero rg">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="documentstatutlocataire_locataire">Document Statut</label>
                            <input type="file" id="documentstatutlocataire_locataire" name="documentstatut" class="input w-full border mt-2 flex-1" placeholder="document statut">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="personnehabiliteasignerlocataire_locataire" class="required">Personne habilite a
                                signer</label>
                            <input type="text" id="personnehabiliteasignerlocataire_locataire" name="personnehabiliteasigner" class="input w-full border mt-2 flex-1" placeholder="personne habilite a signer">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="fonctionpersonnehabilitelocataire_locataire" class="required">Fonction personne
                                habilleté a signer</label>
                            <input type="text" id="fonctionpersonnehabilitelocataire_locataire" name="fonctionpersonnehabilite" class="input w-full border mt-2 flex-1" placeholder="fonction personne habilite">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="prenompersonneacontacterlocataire_locataire" class="required">Prenom personne a
                                contacter</label>
                            <input type="text" id="prenompersonneacontacterlocataire_locataire" name="prenompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="prenom personne a contacter">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="nompersonneacontacterlocataire_locataire" class="required">Nom personne a
                                contacter</label>
                            <input type="text" id="nompersonneacontacterlocataire_locataire" name="nompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="nom personne a contacter">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="emailpersonneacontacterlocataire_locataire" class="required">Email personne a
                                contacter</label>
                            <input type="text" id="emailpersonneacontacterlocataire_locataire" name="emailpersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="email personne a contacter">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3 mt-2">
                            <label for="telephone1personneacontacterlocataire_locataire" class="required">telephone 1
                                personne a contacter</label>
                            <input type="tel" id="telephone1personneacontacterlocataire_locataire" data-country-code-name="telephone1personneacontacter" name="telephone1personneacontacter" class="input w-full border mt-2 flex-1 phone" placeholder="">
                        </div>

                        <div class="2 col-span-3 sm:col-span-3 mt-2">
                            <label for="telephone2personneacontacterlocataire_locataire">Telephone 2 personne a
                                contacter</label>
                            <input type="tel" id="telephone2personneacontacterlocataire_locataire" name="telephone2personneacontacter" class="input w-full border mt-2 flex-1 " placeholder="">
                        </div>
                    </div>


                </div>
                <div class="tab-content__pane " id="copreneurs">
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-info-circle text-blue-400"></i>Co-preneurs
                    </div>

                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="3 col-span-4 sm:col-span-4">
                            <label for="paysnaissancecopreneur_locataire" class="required">Choisissez le pays de résidence</label>
                            <x-country-list :idName="'paysnaissance_locataire_copreneurs_locataire'" :name="'copreneurpaysnaissance'" />
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="prenomcopreneur_locataire" class="required">Prénom</label>
                            <input type="text" id="prenom_locataire_copreneurs_locataire" name="copreneurprenom" class=" input w-full inline-block relative border mt-2 flex-1" placeholder="prénom">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="nomcopreneur_locataire" class="required">Nom</label>
                            <input type="text" id="nom_locataire_copreneurs_locataire" name="copreneurnom" class=" input w-full inline-block relative border mt-2 flex-1" placeholder="nom">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="email_locataire" class="required">Email</label>
                            <input type="text" id="email_locataire_copreneurs_locataire" name="copreneuremail" class="required input w-full border flex-1 mt-2" placeholder="email">
                        </div>



                        <div class="3 col-span-3 sm:col-span-3 ">
                            <label for="telephone1_locataire_copreneurs_locataire" class="required">Téléphone 1</label>

                            <input type="tel" id="telephone1_locataire_copreneurs_locataire" name="copreneurtelephone1" class="required input w-full border flex-1 mt-2 " placeholder="portable 1">

                        </div>


                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="telephone2_locataire_copreneurs_locataire">Téléphone 2</label>
                            <input type="tel" id="telephone2_locataire_copreneurs_locataire" name="copreneurtelephone2" class="input w-full border  flex-1 mt-2 " placeholder="Téléphone portable 2">
                        </div>


                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="professioncopreneur_locataire">Profession</label>
                            <input type="text" id="profession_locataire_copreneurs_locataire" name="copreneurprofession" class="input w-full border mt-2  flex-1" placeholder="profession">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="datenaissancecopreneur_contrat">Date de naissance</label>
                            <input type="date" id="datenaissance_locataire_copreneurs_locataire" name="copreneurdatenaissance" class="input w-full border mt-2 flex-1" placeholder="datenaissance">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="lieunaissancecopreneur_locataire" class="">Lieux de naissance</label>
                            <input type="text" id="lieunaissance_locataire_copreneurs_locataire" name="copreneurlieunaissance" class=" input w-full inline-block relative border mt-2 flex-1" placeholder="lieux naissance">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="nationalitecopreneur_locataire" class="">Nationalité</label>
                            <input type="text" id="nationalite_locataire_copreneurs_locataire" name="copreneurnationalite" class=" input w-full border mt-2 flex-1" placeholder="nationalité">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="villcopreneure_locataire" class="">Ville</label>
                            <input type="text" id="ville_locataire_copreneurs_locataire" name="copreneurville" class=" input w-full border mt-2 flex-1" placeholder="ville">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="codepostalcopreneur_locataire" class="">Code postal</label>
                            <input type="text" id="codepostal_locataire_copreneurs_locataire" name="copreneurcodepostal" class=" input w-full border mt-2 flex-1" placeholder="code postal">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="njf_locataire_copreneurs_locataire" class="">Njf</label>
                            <input type="text" id="njf_locataire_copreneurs_locataire" name="copreneurnjf" class=" input w-full border mt-2 flex-1" placeholder="njf">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="situationfamiliale_locataire_copreneurs_locataire" class="">Situation familiale</label>
                            <input type="text" id="situationfamiliale_locataire_copreneurs_locataire" name="copreneursituationfamiliale" class=" input w-full border mt-2 flex-1" placeholder="situation familiale">
                        </div>

                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="adresse_locataire_copreneurs_locataire" class="">Adresse</label>
                            <input type="text" id="adresse_locataire_copreneurs_locataire" name="copreneuradresse" class="input w-full border mt-2 flex-1" placeholder="adresse">
                        </div>

                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="cni_locataire_copreneurs_locataire">CNI</label>
                            <input type="text" id="cni_locataire_copreneurs_locataire" name="copreneurcni" class="input w-full border mt-2 flex-1" placeholder="cni">
                        </div>
                        <div class="3 col-span-3 sm:col-span-3">
                            <label for="passeport_locataire_copreneurs_locataire">Passeport</label>
                            <input type="text" id="passeport_locataire_copreneurs_locataire" name="copreneurpasseport" class="input w-full border mt-2 flex-1" placeholder="passeport">
                        </div>
                        <div class="3 col-span-2 sm:col-span-2 mt-4">
                            <button type="button" ng-click="actionSurTabPaneTagData('add','locataire_copreneurs_locataire')" class="btn btn-primay bg-primary text-white button mt-3 " title="ajouter">
                                <span class="fas fa-plus"></span>
                            </button>
                        </div>

                        <div class="3 col-span-12 sm:col-span-12">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                <table class="table table-report sm:mt-1">
                                    <thead>
                                        <tr class="bg-theme-101 text-white">
                                            <th class="whitespace-no-wrap">Prénom & nom</th>
                                            <th class="whitespace-no-wrap text-center">Email</th>
                                            <th class="whitespace-no-wrap text-center">Téléphone</th>
                                            <th class="text-center whitespace-no-wrap">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataInTabPane['locataire_copreneurs_locataire']['data']">

                                            <td class="">
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.prenom }} @{{ item.nom }}
                                                </div>
                                            </td>


                                            <td class="">
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.email }}
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.telephone1 }}
                                                </div>
                                            </td>

                                            <td class="table-report__action w-56">
                                                <nav class="menu-leftToRight uk-flex text-center">
                                                    <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                                    <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                        <span class="hamburger bg-template-1 hamburger-1"></span>
                                                        <span class="hamburger bg-template-1 hamburger-2"></span>
                                                        <span class="hamburger bg-template-1 hamburger-3"></span>
                                                    </label>

                                                    <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'locataire_copreneurs_locataire', $index)" title="Supprimer">
                                                        <span class="fa fa-trash-alt"></span>
                                                    </button>
                                                    {{-- <button type="button"
                                                        class="menu-item btn border-0 bg-primary text-white fsize-16"
                                                        ng-click="actionSurTabPaneTagData('update', 'locataire_copreneurs_locataire', $index ,'' , null ,'test','1' )"
                                                        title="Modifier">
                                                        <span class="fal fa-edit"></span>
                                                    </button> --}}
                                                </nav>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal locataire-->

<!-- debut modal immeuble -->
<!--  <div class="modal" id="modal_addimmeuble">
        <div class="modal__content modal__content--md">
            <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 bg-scireyhan text-white">
                <i class="fa fa-building mr-2"></i>
                <h2 class="font-medium text-base mr-auto">
                    Immeuble
                </h2>
                <div class="pull-right">
                    <button class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <form id="form_addimmeuble" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'immeuble')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_immeuble" name="id">
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-6 sm:col-span-6">
                        <label for="nom_immeuble" class="required">Nom</label>
                        <input type="text" id="nom_immeuble" name="nom" class="input w-full border mt-2 flex-1 required" placeholder="nom">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="adresse_immeuble" class="required">Adresse</label>
                        <input type="text" id="adresse_immeuble" name="adresse" class="input w-full border mt-2 flex-1 required" placeholder="adresse">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="categorietuto_typetuto">Equipe de gestion</label>
                        <div class="inline-block relative w-full">
                            <select class="block mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="equipegestion_immeuble" name="equipegestion" >
                                <option value="" class="required">Equipe</option>
                                <option ng-repeat="item in dataPage['equipegestions']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                    </button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div> -->
<!-- fin modal immeuble-->


<!-- debut modal proprietaire -->
<div class="modal" id="modal_addproprietaire">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-user-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Proprietaire
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addproprietaire" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'proprietaire')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_proprietaire" name="id">
            <div class="divproprietaire p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-3 sm:col-span-3">
                    <label for="prenom_proprietaire" class="required">Prenom</label>
                    <input type="text" id="prenom_proprietaire" name="prenom" class="input w-full border mt-2 flex-1 required" placeholder="prenom">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="nom_proprietaire" class="required">Nom</label>
                    <input type="text" id="nom_proprietaire" name="nom" class="input w-full border mt-2 flex-1 required" placeholder="nom">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="adresse_proprietaire" class="required">Adresse</label>
                    <input type="text" id="adresse_proprietaire" name="adresse" class="input w-full border mt-2 flex-1 required" placeholder="adresse">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="telephone_proprietaire" class="required">Telephone 1</label>
                    <input type="text" id="telephone_proprietaire" name="telephone" class="input w-full border mt-2 flex-1 required" placeholder="telephone">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="telephoneportable_proprietaire">Telephone 2</label>
                    <input type="text" id="telephoneportable_proprietaire" name="telephoneportable" class="input w-full border mt-2 flex-1" placeholder="telephone">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="telephonebureau_proprietaire">Telephone bureau</label>
                    <input type="text" id="telephonebureau_proprietaire" name="telephonebureau" class="input w-full border mt-2 flex-1" placeholder="telephone">
                </div>
                <div class="col-span-12 sm:col-span-12 text-left mr-5">
                    <label>Ajout gestionnaire</label><br>
                    <button id="addgestionnaire" type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('gestionnaire')"><span class="fa fa-plus"></span></button>
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal proprietaire-->


<!-- debut modal detailproprietaire -->
<!--
    <div class="modal" id="modal_adddetailproprietaire">
        <div class="modal__content modal__content--lg">
            <div  class="flex items-center px-5 py-5 sm:py-3 header "
>
                <i class="fa fa-building mr-2"></i>
                <h2 class="font-medium text-base mr-auto">
                    Details Proprietaire
                </h2>
                <div class="pull-right">
                    <button class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

            </div>
            <form id="form_adddetailproprietaire" class="form" accept-charset="UTF-8"
                ng-submit="addElement($event,'proprietaire')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_proprietaire" name="id">

                <div class="intro-y pr-1 mt-1">
                    <div class="box p-2 item-tabs-produit">
                        <div class="pos__tabs nav-tabs justify-center flex">
                            <a data-toggle="tab" data-target="#infoproprietaire" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center active">Infos Generales</a>
                            <a data-toggle="tab" data-target="#mandatproprietaire" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center ">Mandats</a>
                            <a data-toggle="tab" data-target="#appartementproprietaire" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Appartements</a>
                            <a data-toggle="tab" data-target="#factureloyerproprietaire" href="javascript:;"
                                class="flex-1 py-2 rounded-md text-center">Factures Loyers</a>
                        </div>
                    </div>
                </div>

                <div class="tab-content">

                    <div class="tab-content__pane active" id="infoproprietaire">
                        <div class="form-section pl-5 pt-3  text-xl">
                            <i class="fa fa-info-circle text-blue-400"></i> Infos Generales
                        </div>

                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-3 sm:col-span-3 ">
                                <strong>
                                    <h5>Prenom: </h5>
                                </strong>
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                @{{ dataPage['proprietaires'][0]['prenom'] }}
                            </div>

                            <div class="col-span-3 sm:col-span-3 ">
                                <strong>
                                    <h5>Nom: </h5>
                                </strong>
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                @{{ dataPage['proprietaires'][0]['nom'] }}
                            </div>

                            <div class="col-span-3 sm:col-span-3 ">
                                <strong>
                                    <h5>Adresse: </h5>
                                </strong>
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                @{{ dataPage['proprietaires'][0]['adresse'] }}
                            </div>
                            <div class="col-span-3 sm:col-span-3 ">
                                <strong>
                                    <h5>Telephone 1: </h5>
                                </strong>
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                @{{ dataPage['proprietaires'][0]['telephone'] }}
                            </div>
                            <div class="col-span-3 sm:col-span-3 ">
                                <strong>
                                    <h5>Telephone 2: </h5>
                                </strong>
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                @{{ dataPage['proprietaires'][0]['telephoneportable'] }}
                            </div>
                            <div class="col-span-3 sm:col-span-3 ">
                                <strong>
                                    <h5>Telephone Bureau: </h5>
                                </strong>
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                @{{ dataPage['proprietaires'][0]['telephonebureau'] }}
                            </div>

                        </div>

                    </div>

                    <div class="tab-content__pane " id="mandatproprietaire">
                        <div class="form-section pl-5 pt-3  text-xl">
                            <i class="fa fa-info-circle text-blue-400"></i> Mandats Proprietaire
                        </div>

                        <div class="overflow-table">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                                <table class="table table-report sm:mt-2">
                                    <thead>
                                    <tr>
                                        <th class="whitespace-no-wrap text-center">Model de contrat</th>
                                        <th class="whitespace-no-wrap text-center">Date</th>
                                        <th class="whitespace-no-wrap text-center">Valeur Commission</th>
                                        <th class="whitespace-no-wrap text-center">Pourcentage Commission</th>
                                        <th class="whitespace-no-wrap text-center">TVA</th>
                                        <th class="whitespace-no-wrap text-center">BRS</th>
                                        <th class="whitespace-no-wrap text-center">TLV</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataPage['contratproprietaires']">
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
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="tab-content__pane " id="appartementproprietaire">

                        <div class="form-section pl-5 pt-3  text-xl">
                            <i class="fa fa-info-circle text-blue-400"></i> Appartements Proprietaire
                        </div>

                        <div class="overflow-table">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                                <table class="table table-report sm:mt-2">
                                    <thead>
                                    <tr>
                                        <th class="whitespace-no-wrap">Nom</th>
                                        <th class="whitespace-no-wrap text-center">Immeuble</th>
                                        <th class="whitespace-no-wrap text-center">Niveau</th>
                                        <th class="whitespace-no-wrap text-center">Type</th>
                                        <th class="whitespace-no-wrap text-center">Etat</th>
                                        <th class="whitespace-no-wrap text-center">Montant caution</th>
                                        <th class="whitespace-no-wrap text-center">montant loyer</th>
                                        <th class="whitespace-no-wrap text-center">Fréquence paiement</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataPage['appartements']" >
                                            <td>
                                                <div class="font-medium whitespace-no-wrap">@{{ item.nom }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.immeuble_id ? item['immeuble'].nom : "" }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item.niveau }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">@{{ item['typeappartement'].designation }}</div>
                                            </td>
                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center px-2 rounded-full text-white @{{ item['etatappartement'].etat_badge }}">@{{ item['etatappartement'].designation }}</div>
                                            </td>
                                            <td>
                                                <div ng-repeat="contrat in item['contrats']" ng-if="contrat.etat == 1" class="font-medium whitespace-no-wrap text-center">@{{ contrat.caution.montantcaution }}</div>
                                                <div ng-repeat="contrat in item['contrats']" ng-if="contrat.etat == 1 && !contrat.caution" class="font-medium whitespace-no-wrap text-center">caution non versé</div>
                                                <div ng-if="item.iscontrat == '0' && item.montantcaution" class="font-medium whitespace-no-wrap text-center">@{{item.montantcaution}}</div>
                                            </td>
                                            <td>
                                                <div ng-repeat="contrat in item['contrats']" ng-if="contrat.etat == 1" class="font-medium whitespace-no-wrap text-center">@{{ contrat.montantloyer }}</div>
                                                <div ng-if="item.iscontrat == '0' && item.montantloyer" class="font-medium whitespace-no-wrap text-center">@{{item.montantcaution}}</div>
                                            </td>
                                            <td>
                                                <div ng-repeat="contrat in item['contrats']" ng-if="contrat.etat == 1" class="font-medium whitespace-no-wrap text-center">@{{ item.frequencepaiementappartement.designation }}</div>
                                                <div ng-if="item.iscontrat == '0'" class="font-medium whitespace-no-wrap text-center">Neant</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                    <div class="tab-content__pane " id="factureloyerproprietaire">

                        <div class="form-section pl-5 pt-3  text-xl">
                            <i class="fa fa-info-circle text-blue-400"></i> Facture Loyer Proprietaire
                        </div>

                    </div>


                </div>
                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal"
                        class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>

        </div>
    </div>
            -->
<!-- fin modal detailproprietaire-->

<!-- debut modal prestataire -->
<div class="modal" id="modal_addprestataire">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-user-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Prestataire
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addprestataire" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'prestataire')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_prestataire" name="id">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-6 sm:col-span-6">
                    <label for="nom_prestataire" class="required">Designation</label>
                    <input type="text" id="nom_prestataire" name="nom" class="input w-full border mt-2 flex-1 required" placeholder="nom">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Catégorie</label>
                    <div class="inline-block relative w-full mt-2">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="categorieprestataire_prestataire" name="categorieprestataire">
                            <option value="" class="required">categorie</option>
                            <option ng-repeat="item in dataPage['categorieprestataires']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="adresse_prestataire" class="required">Adresse</label>
                    <input type="text" id="adresse_prestataire" name="adresse" class="input w-full border mt-2 flex-1 required" placeholder="adresse">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="email_prestataire" class="required">Email</label>
                    <input type="text" id="email_prestataire" name="email" class="input w-full border mt-2 flex-1 required" placeholder="email">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="telephone1_prestataire" class="required">Telephone 1</label>
                    <input type="text" id="telephone1_prestataire" name="telephone1" class="input w-full border mt-2 flex-1 required" placeholder="telephone">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="telephone2_prestataire">Telephone 2</label>
                    <input type="text" id="telephone2_prestataire" name="telephone2" class="input w-full border mt-2 flex-1" placeholder="telephone">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal proprietaire-->

<!-- debut modal paiementloyer -->
<div class="modal" id="modal_addpaiementloyer">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-user-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Paiement loyer
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addpaiementloyer" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'paiementloyer')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_paiementloyer" name="id">

            <input type="hidden" id="facturelocation_id_paiementloyer" name="facturelocation_id">
            <input type="hidden" id="factureeaux_id_paiementloyer" name="factureeaux_id">
            <input type="hidden" id="appartement_paiementloyer" name="appartement">
            <input type="hidden" id="locataire_paiementloyer" name="locataire">

            <div class="pl-5 pt-5 pr-5  grid grid-cols-12 gap-4 row-gap-3 mb-3">
                {{-- <div class="col-span-4 sm:col-span-4">
                        <label for="categorietuto_typetuto">Locataire / reservataire</label>
                        <div class="inline-block mt-2 relative w-full" class="required">
                            <select
                                class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required"
                                id="locataire_paiementloyer" name="locataire">
                                <option value="" class="required">locataire / reservataire</option>
                                <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                </option>
                </select>
            </div>
    </div>
    <div class="col-span-4 sm:col-span-4">
        <label for="categorietuto_typetuto" class="required">Choisissez l'appartement / villa</label>
        <div class="inline-block mt-2 relative w-full">
            <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="appartement_paiementloyer" name="appartement">

                <option value="" class="required">appartement</option>
                <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">
                    @{{ item.nom }}
                    @{{ item.lot_ilot_refact }}

                </option>

            </select>
        </div>
    </div>
    <div class="col-span-4 sm:col-span-4">
        <label for="categorietuto_typetuto" class="required">Contrat</label>
        <div class="inline-block mt-2 relative w-full">
            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="contrat_paiementloyer" name="contrat">
                <option value="" class="required">contrat</option>
                <option ng-repeat="item in contratsLocatairePaiementloyer" value="@{{ item.id }}">
                    @{{ item.descriptif }}
                </option>
            </select>
        </div>
    </div> --}}
    <div class="col-span-4 sm:col-span-4">
        <label for="categorietuto_typetuto" class="required">Contrat</label>
        <div class="inline-block mt-2 relative w-full">
            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="contrat_paiementloyer" name="contrat">
                <option value="" class="required">contrat</option>
                {{-- <option ng-repeat="item in contratsLocatairePaiementloyer" --}}
                <option ng-repeat="item in dataPage['contrats']" value="@{{ item.id }}">
                    @{{ item.descriptif }}
                </option>
            </select>
        </div>
    </div>
    <div class="col-span-4 sm:col-span-4">
        <label for="categorietuto_typetuto" class="required">Fréquence de paiement</label>
        <div class="inline-block mt-2 relative w-full">
            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="periodicite_paiementloyer" name="periodicite">
                <option value="" class="required">Périodicité</option>
                <option ng-repeat="item in dataPage['periodicites']" value="@{{ item.id }}">
                    @{{ item.designation }}
                </option>
            </select>
        </div>
    </div>
    <div class="col-span-4 sm:col-span-4">
        <label for="categorietuto_typetuto" class="required">Mode paiement</label>
        <div class="inline-block mt-2 relative w-full">

            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="modepaiement_paiementloyer" name="modepaiement">
                <option value="" class="required">Mode paiement</option>
                <option ng-repeat="item in dataPage['modepaiements']" value="@{{ item.id }}">
                    @{{ item.designation }}
                </option>
            </select>
        </div>
    </div>
    <div class="col-span-4 sm:col-span-4">
        <label for="montantfacture_paiementloyer" class="required">Montant</label>
        <input type="number" id="montantfacture_paiementloyer" name="montantfacture" class="input w-full border mt-2 flex-1 required" placeholder="montant">
    </div>
    <div class="col-span-4 sm:col-span-4 ">
        <label for="datepaiement_paiementloyer" class="required">Date de paiement</label>
        <input type="date" id="datepaiement_paiementloyer" name="datepaiement" class="input w-full border mt-2 flex-1 required" placeholder="date">
    </div>
    <div class=" col-span-4 sm:col-span-4 ">
        <label for="numerocheque_paiementloyer">Référence de paiement</label>
        <input type="text" id="numerocheque_paiementloyer" name="numero_cheque" class="input w-full border mt-2 flex-1 required" placeholder="référence de paiement">
    </div>
    <div class="col-span-4 sm:col-span-4">
        <label for="justificatif_paiementloyer">Justificatif de
            paiement</label>
        <input type="file" id="justificatif_paiementloyer" name="justificatif" class="input w-full border mt-2 flex-1" placeholder="justificatif">
    </div>
    {{-- <div class="col-span-4 sm:col-span-4">
                    <label for="categorietuto_typetuto">Période</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select  class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required"
                         id="periode_paiementloyer" name="periode" >
                            <option value="" class="required">Périodes</option>
                            <option ng-repeat="item in dataPage['periodes']" value="@{{ item.id }}">
    @{{ item.designation }}
    </option>
    </select>
</div>
</div> --}}
{{-- <div class="col-span-8 sm:col-span-8">
                        <label for="categorietuto_typetuto" class="">Périodes</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select multiple
                                class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                id="periodes_paiementloyer" name="periodes">
                                <option ng-repeat="item in dataPage['periodes']" value="@{{ item.id }}">
@{{ item.designation }}
</option>
</select>
</div>
</div> --}}
{{-- <div class="periodepaiement col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Periode payé</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="periode_paiementloyer" name="periode" >
                            <option value="" selected class="required">periode</option>
                        </select>
                    </div>
                </div> --}}
</div>
{{-- <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto" class="required">Période</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                         id="periode_periodepaiementloyer_paiementloyer" name="periode" >
                            <option value="">Période</option>
                            <option ng-repeat="item in dataPage['periodes']" value="@{{ item.id }}">
@{{ item.designation }}
</option>
</select>
</div>
</div>

<input type="hidden" id="montant_periodepaiementloyer_paiementloyer">
<div class="col-span-1 sm:col-span-1 text-right">
    <button type="button" class="button w-10 bg-theme-101 text-white mt-7" ng-click="actionSurTabPaneTagData('add','periodepaiementloyer_paiementloyer')"><span class="fa fa-plus"></span></button>
</div>

<div class="col-span-12 sm:col-span-12">
    <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
        <table class="table table-report sm:mt-1">
            <thead>
                <tr class="bg-theme-101 text-white">
                    <th hidden class="whitespace-no-wrap">#</th>
                    <th class="whitespace-no-wrap">Période</th>
                    <th class="whitespace-no-wrap text-center">Montant</th>
                    <th class="text-center whitespace-no-wrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="intro-x" ng-repeat="item in dataInTabPane['periodepaiementloyer_paiementloyer']['data']">

                    <td hidden class="">
                        <div class="font-medium whitespace-no-wrap">@{{ item.id }}</div>
                    </td>

                    <td class="">
                        <div class="font-medium whitespace-no-wrap ">@{{ item.periode_text }}</div>
                    </td>
                    <td class="">
                        <div class="font-medium whitespace-no-wrap text-center">@{{ item.montant }}</div>
                    </td>

                    <td class="table-report__action w-56">
                        <nav class="menu-leftToRight uk-flex text-center">
                            <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                            <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                <span class="hamburger bg-template-1 hamburger-1"></span>
                                <span class="hamburger bg-template-1 hamburger-2"></span>
                                <span class="hamburger bg-template-1 hamburger-3"></span>
                            </label>

                            <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'periodepaiementloyer_paiementloyer', $index)" title="Supprimer">
                                <span class="fa fa-trash-alt"></span>
                            </button>
                        </nav>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div> --}}
<div class="px-5 py-3 text-right border-t border-gray-200">
    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
    </button>
    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
</div>
</form>
</div>
</div>
<!-- fin modal paiement loyer-->

{{-- start modal paiement echeance --}}
<div class="modal" id="modal_addpaiementecheance">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-user-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Paiement échéance
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addpaiementecheance" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'paiementecheance')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_paiementecheance" name="id">

            <input type="hidden" id="avisecheance_id_paiementecheance" name="avisecheance">
            <input type="hidden" id="factureacompte_id_paiementecheance" name="factureacompte">
            <input type="hidden" id="isacompte_paiementecheance" name="isacompte">
            <div class="col-span-2 sm:col-span-2 mr-80 mt-5 text-right">
                <span id="soldeclient_paiementecheance" class="ml-auto border w-full flex-1 ml-3 text-red hidden">
                    <i class="fa fa-info-circle text-red-400"></i>
                    <span id="soldeclient_text"></span>
                </span>
            </div>

            <div class="pl-5 pt-5 pr-5  grid grid-cols-12 gap-4 row-gap-3 mb-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto" class="required">Mode paiement</label>
                    <div class="inline-block mt-2 relative w-full">

                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="modepaiement_paiementecheance" name="modepaiement">
                            <option value="" class="required">Mode paiement</option>
                            <option ng-repeat="item in dataPage['modepaiements']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                {{-- <div class="col-span-6 sm:col-span-6">
                        <label for="montant_paiementecheance" class="required">Montant</label>
                        <input type="number" id="montant_paiementecheance" name="montant"
                            class="input w-full border mt-2 flex-1 required" placeholder="montant">
                    </div> --}}
                <div class="col-span-6 sm:col-span-6 ">
                    <label for="date_paiementecheance" class="required">Date de paiement</label>
                    <input type="date" id="date_paiementecheance" name="date" class="input w-full border mt-2 flex-1 " placeholder="date">
                </div>
                <div class="col-span-3 sm:col-span-3 ">
                    <label for="montantloyer_paiementecheance">Montant avis</label>
                    <input id="montantloyer_paiementecheance" class="input w-full border mt-2 flex-1" placeholder="montant avis">
                </div>
                <div class="col-span-3 sm:col-span-3 ">
                    <label for="montantaregler_paiementecheance">Montant a regler</label>
                    <input id="montantaregler_paiementecheance" name="montantaregler" class="input w-full border mt-2 flex-1" placeholder="montant a regler">
                </div>
                <div class="col-span-6 sm:col-span-6 ">
                    <label for="montantencaissement_paiementecheance" class="required">Montant encaissement</label>
                    <input id="montantencaissement_paiementecheance" name="montantencaissement" class="input w-full border mt-2 flex-1 required" placeholder="montant encaissement">
                </div>
                <div class=" col-span-6 sm:col-span-6 ">
                    <label for="numerocheque_paiementecheance">Référence de paiement</label>
                    <input type="text" id="numerocheque_paiementecheance" name="numero_cheque" class="input w-full border mt-2 flex-1 " placeholder="référence ">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="justificatif_paiementecheance">Justificatif de
                        paiement</label>
                    <input type="file" id="justificatif_paiementecheance" name="justificatif" class="input w-full border mt-2 flex-1" placeholder="justificatif">

                    {{-- <input type="text" id="justificatifcontent_path" name="justificatifcontent_path"> --}}
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <a id="justificatifcontent_paiementecheance" class="btn text-white py-3 px-2 rounded" style="background: black" href="" target="_blank">Justificatif de paiement PDF</a>
                </div>
            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
{{-- end modal paiement echeance --}}

<!-- debut modal versementloyer -->
<div class="modal" id="modal_addversementloyer">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-money-check-edit-alt"></i>
            <h2 class="font-medium text-base mr-auto">
                Versement loyer
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addversementloyer" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'versementloyer')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_versementloyer" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Locataire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="locataire_versementloyer" name="locataire">
                            <option value="" class="required">locataire</option>
                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Choisissez l'appartement</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="appartement_versementloyer" name="appartement">
                            <option value="" class="required">appartement</option>
                            <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Contrat</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="contrat_versementloyer" name="contrat">
                            <option value="" class="required">contrat</option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Proprietaire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="proprietaire_versementloyer" name="proprietaire">
                            <option value="" class="required">proprietaire</option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="montant_versementloyer" class="required">Montant</label>
                    <input type="text" id="montant_versementloyer" name="montant" class="input w-full border mt-2 flex-1 required" placeholder="montant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="dateversement_versementloyer" class="required">Date de versement</label>
                    <input type="date" id="dateversement_versementloyer" name="dateversement" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="debut_versementloyer" class="required">Debut periode de validité</label>
                    <input type="date" id="debut_versementloyer" name="debut" class="input w-full border mt-2 flex-1 required" placeholder="debut">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="fin_versementloyer" class="required">Fin periode de validité</label>
                    <input type="date" id="fin_versementloyer" name="fin" class="input w-full border mt-2 flex-1 required" placeholder="fin">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="document_versementloyer" class="required">Document</label>
                    <input type="file" id="document_versementloyer" name="document" class="input w-full border mt-2 flex-1 required" placeholder="document">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal versementloyer-->

<!-- debut modal versementchargecopropriete -->
<div class="modal" id="modal_addversementchargecopropriete">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-money-check-edit-alt"></i>
            <h2 class="font-medium text-base mr-auto">
                Versement charge copropriete
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addversementchargecopropriete" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'versementchargecopropriete')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_versementchargecopropriete" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Choisissez le proprietaire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="proprietaire_versementchargecopropriete" name="proprietaire">
                            <option value="" class="required">proprietaire</option>
                            <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">
                                @{{ item.prenom }} @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="montant_versementchargecopropriete" class="required">Montant</label>
                    <input type="text" id="montant_versementchargecopropriete" name="montant" class="input w-full border mt-2 flex-1 required" placeholder="montant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="anneecouverte_versementchargecopropriete" class="required">Triméstre
                        concerné</label>
                    <input type="text" id="anneecouverte_versementchargecopropriete" name="anneecouverte" class="input w-full border mt-2 flex-1 required" placeholder="trimestre">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="dateversement_versementchargecopropriete" class="required">Date de versement</label>
                    <input type="date" id="dateversement_versementchargecopropriete" name="dateversement" class="input w-full border mt-2 flex-1 required" placeholder="annee">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="document_versementchargecopropriete" class="required">Document</label>
                    <input type="file" id="document_versementchargecopropriete" name="document" class="input w-full border mt-2 flex-1 required" placeholder="document">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal versementchargecopropriete-->

<!-- debut modal obligationadministrative -->
<div class="modal" id="modal_addobligationadministrative">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-money-check-edit-alt"></i>
            <h2 class="font-medium text-base mr-auto">
                Obligation administrative
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addobligationadministrative" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'obligationadministrative')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_obligationadministrative" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="designation_obligationadministrative" class="required">Designation</label>
                    <input type="text" id="designation_obligationadministrative" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="designation">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Type d'obligation</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typeobligationadministrative_obligationadministrative" name="typeobligationadministrative">
                            <option value="" class="required">type</option>
                            <option ng-repeat="item in dataPage['typeobligationadministratives']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="montant_obligationadministrative" class="required">Montant</label>
                    <input type="text" id="montant_obligationadministrative" name="montant" class="input w-full border mt-2 flex-1 required" placeholder="montant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="debut_obligationadministrative" class="required">Date de debut</label>
                    <input type="date" id="debut_obligationadministrative" name="debut" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="fin_obligationadministrative" class="required">Date de fin</label>
                    <input type="date" id="fin_obligationadministrative" name="fin" class="input w-full border mt-2 flex-1 required" placeholder="fin">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">L'obligation concerne :</label><br><br>
                    <label>
                        <input required="" name="obligation_immeuble_appartement" onchange="showInput(this,'immeuble','obligationadministrative')" value="immeuble" type="radio" />
                        <span>Tout l'immeuble </span>
                    </label>
                    <label>
                        <input name="obligation_immeuble_appartement" onchange="showInput(this,'appartement','obligationadministrative')" value="appartement" type="radio" />
                        <span>L'appartement d'un immeuble</span>
                    </label>
                </div>
                <div class="immeubleObligationadministrative col-span-6 sm:col-span-6">
                    <label for="immeuble_obligationadministrative">Choisissez l'immeuble</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="immeuble_obligationadministrative" name="immeuble">
                            <option value="" class="required">immeuble</option>
                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="appartementObligationadministrative col-span-6 sm:col-span-6">
                    <label for="appartement_obligationadministrative">Choisissez l'appartement</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="appartement_obligationadministrative" name="appartement">
                            <option value="" selected>appartement</option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="document_obligationadministrative" class="required">Document</label>
                    <input type="file" id="document_obligationadministrative" name="document" class="input w-full border mt-2 flex-1 required" placeholder="document">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal obligationadministrative-->

<!-- debut modal annonce -->
<div class="modal" id="modal_addannonce">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-money-check-edit-alt"></i>
            <h2 class="font-medium text-base mr-auto">
                Annonce
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addannonce" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'annonce')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_annonce" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="titre_annonce" class="required">Titre de l'annonce</label>
                    <input type="text" id="titre_annonce" name="titre" class="input w-full border mt-2 flex-1 required" placeholder="titre">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="description_annonce" class="required">Description</label>
                    <input type="text" id="description_annonce" name="description" class="input w-full border mt-2 flex-1 required" placeholder="description">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="debut_annonce" class="required">Date de debut</label>
                    <input type="date" id="debut_annonce" name="debut" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="fin_annonce" class="required">Date de fin</label>
                    <input type="date" id="fin_annonce" name="fin" class="input w-full border mt-2 flex-1 required" placeholder="fin">
                </div>
                <div class="col-span-12 sm:col-span-12">
                    <label for="categorietuto_typetuto">L'annonce concerne :</label><br><br>
                    <label>
                        <input required="" name="annonce_destinataire" onchange="showInput(this,'immeuble','annonce')" value="immeuble" type="radio" />
                        <span>Un immeuble/appartement </span>
                    </label>
                    <label>
                        <input required="" name="annonce_destinataire" onchange="showInput(this,'immeubles','annonce')" value="immeubles" type="radio" />
                        <span> Tous les immeubles </span>
                    </label>
                    <label>
                        <input name="annonce_destinataire" onchange="showInput(this,'marketing','annonce')" value="marketing" type="radio" />
                        <span> Marketing </span>
                    </label>
                </div>
                <div class="appartementannonce col-span-6 sm:col-span-6">
                    <label for="immeuble_annonce">Choisissez l'immeuble</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="immeuble_annonce" name="immeuble">
                            <option value="" class="required">immeuble</option>
                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="appartementannonce col-span-6 sm:col-span-6">
                    <label for="appartement_annonce">Choisissez l'appartement</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="appartement_annonce" name="appartement">
                            <option value="" selected>appartement</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal annonce-->


<!-- debut modal equipement piece -->

<div class="modal" id="modal_addequipementpiece">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="far fa-toolbox"></i>
            <h2 class="font-medium text-base mr-auto">
                Equipement
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addequipementpiece" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'equipementpiece')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_equipementpiece" name="id">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-6 sm:col-span-6">
                        <label for="titre_annonce" class="required">Désignation</label>
                        <input type="text" id="designation_equipementpiece" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="désignation">
                    </div>
                    <div class="appartementannonce col-span-6 sm:col-span-6">
                        <label for="immeuble_annonce">Choisissez le type de l'équipement</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="generale_equipementpiece" name="generale">
                                <option value="" class="required">type</option>
                                <option value="1">générale</option>
                                <option value="0">particulier</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-12 text-right">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- fin modal equipement piece-->


<!-- debut modal caution -->
<div class="modal" id="modal_addcaution">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-user-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Caution
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addcaution" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'caution')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_caution" name="id">
            <input type="hidden" id="contrat_caution" name="contrat">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div id="div_contrat_caution" class="col-span-6 sm:col-span-6">

                </div>
                <div id="div_locataire_caution" class="col-span-6 sm:col-span-6">

                </div>
                <div id="div_appartement_caution" class="col-span-6 sm:col-span-6">

                </div>
                <div id="div_montantloyer_caution" class="col-span-6 sm:col-span-6">

                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="montantcaution_caution" class="required">Montant caution</label>
                    <input type="text" id="montantcaution_caution" name="montantcaution" class="input w-full border mt-2 flex-1 required" placeholder="montant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="dateversement_caution" class="required">Date de versement</label>
                    <input type="date" id="dateversement_caution" name="dateversement" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="document_caution" class="required">Document</label>
                    <input type="file" id="document_caution" name="document" class="input w-full border mt-2 flex-1 required" placeholder="document">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal caution-->

<!-- debut modal assurance -->
<div class="modal" id="modal_addassurance">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-user-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Assurance
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addassurance" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'assurance')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_assurance" name="id">
            <input type="hidden" id="contrat_assurance" name="contrat">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div id="div_contrat_assurance" class="col-span-6 sm:col-span-6">

                </div>
                <div id="div_locataire_assurance" class="col-span-6 sm:col-span-6">

                </div>
                <div id="div_appartement_assurance" class="col-span-6 sm:col-span-6">

                </div>
                <div id="div_montantloyer_assurance" class="col-span-6 sm:col-span-6">

                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Type d'assurance</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typeassurance_assurance" name="typeassurance">
                            <option value="" class="required">Type</option>
                            <option ng-repeat="item in dataPage['typeassurances']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="debut_assurance" class="required">Date de debut</label>
                    <input type="date" id="debut_assurance" name="debut" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="fin_assurance" class="required">Date de fin</label>
                    <input type="date" id="fin_assurance" name="fin" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="montant_assurance" class="required">Montant payé</label>
                    <input type="text" id="montant_assurance" name="montant" class="input w-full border mt-2 flex-1 required" placeholder="montant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Vous voulez renouveler une assurance ?</label><br><br>
                    <label>
                        <input required="" name="assurance_type" onchange="showInput(this,'renouvelle','contratassurance')" value="renouvelle" type="radio" />
                        <span>Oui</span>
                    </label>
                    <label>
                        <input name="assurance_type" onchange="showInput(this,'nonrenouvelle','contratassurance')" value="nonrenouvelle" type="radio" />
                        <span>Non</span>
                    </label>
                </div>
                <div class="assurancerenouvelle col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Assurance a renouveller</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="assurancerenouvelle_assurance" name="assurancerenouvelle">
                            <option value="" class="required">assurance</option>
                            <option ng-repeat="item in dataPage['assurances']" ng-if="item.contrat.id == IdAjoutParent" value="@{{ item.id }}">
                                @{{ item.descriptif }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="nonrenouvelle col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Prestataire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="prestataire_assurance" name="prestataire">
                            <option value="" class="required">prestataire</option>
                            <option ng-repeat="item in dataPage['prestataires']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="document_assurance" class="required">Document</label>
                    <input type="file" id="document_assurance" name="document" class="input w-full border mt-2 flex-1 required" placeholder="document">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal assurance-->

<!-- debut modal facture -->
<div class="modal" id="modal_addfacture">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Facture
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addfacture" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'facture')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_facture" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="datefacture_facture">Date de la facture</label>
                    <input type="date" id="datefacture_facture" name="datefacture" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Choisissez le mois</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="mois_facture" name="mois">
                            <option value="" class="required">mois</option>
                            <option value="janvier" class="required">Janvier</option>
                            <option value="fevrier" class="required">Fevrier</option>
                            <option value="mars" class="required">Mars</option>
                            <option value="avril" class="required">Avril</option>
                            <option value="mais" class="required">Mais</option>
                            <option value="juin" class="required">Juin</option>
                            <option value="juillet" class="required">Juillet</option>
                            <option value="aout" class="required">Aout</option>
                            <option value="septembre" class="required">Septembre</option>
                            <option value="octobre" class="required">Octobre</option>
                            <option value="novembre" class="required">Novembre</option>
                            <option value="decembre" class="required">Decembre</option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Type de facture</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="typefacture_facture" name="typefacture">
                            <option value="" class="required">type</option>
                            <option ng-repeat="item in dataPage['typefactures']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="interventionfacture col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Proprietaire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="proprietaire_facture" name="proprietaire">
                            <option value="">Proprietaire</option>
                            <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">
                                @{{ item.prenom }} @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="interventionfacture col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Locataire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="locataire_facture" name="locataire">
                            <option value="">locataire</option>
                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="appartementfacture interventionfacture col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Choisissez l'immeuble</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="immeuble_facture" name="immeuble">
                            <option value="">immeuble</option>
                            <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="appartementfacture interventionfacture col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Choisissez l'appartement</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="appartement_facture" name="appartement">
                            <option value="">appartement</option>
                            <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">
                                @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="interventionfacture col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Choisissez l'intervention</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="intervention_facture" name="intervention">
                            <option value="">intervention</option>
                            <option ng-repeat="item in dataPage['interventions']" ng-if="item.etat == 'Traité' && !item.facture" value="@{{ item.id }}">
                                @{{ item.descriptif }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="interventionfacture col-span-6 sm:col-span-6">
                    <label for="intervenantassocie_facture">Intervenant associé </label>
                    <input type="text" id="intervenantassocie_facture" name="intervenantassocie" class="input w-full border mt-2 flex-1" placeholder="intervenant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="montant_facture">Montant</label>
                    <input type="number" id="montant_facture" name="montant" class="input w-full border mt-2 flex-1" placeholder="montant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="documentfacture_facture">Document Facture</label>
                    <input type="file" id="documentfacture_facture" name="documentfacture" class="input w-full border mt-2 flex-1" placeholder="document">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="recupaiement_facture">Recu de paiement</label>
                    <input type="file" id="recupaiement_facture" name="recupaiement" class="input w-full border mt-2 flex-1" placeholder="recu">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal facture-->

<!-- debut modal facturelocation -->
<div class="modal" id="modal_addfacturelocation">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                facture location
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addfacturelocation" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'facturelocation')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_facturelocation" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">


                <div class="col-span-6 sm:col-span-6">
                    <label for="typefacturelocation_facturelocation">Choisissez le Type facture </label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="typefacture_facturelocation" name="typefacture">
                            <option value="" class="required">Type Facture</option>
                            <option ng-repeat="item in dataPage['typefactures']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-6">

                    <label for="locataire_contrat">Locataire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_locataire" id="locataire_facturelocation" name="locataire">
                            <option value="" class="required">Choisir le locataire</option>
                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                <span ng-if="item.prenom"> @{{ item.prenom }} @{{ item.nom }} </span>
                                <span ng-if="item.nomentreprise"> @{{ item.nomentreprise }} </span>
                            </option>
                        </select>
                    </div>
                </div>

                <div class="appartementfacturelocation col-span-6 sm:col-span-6">
                    <label for="periodicite_facturelocation">Choisissez la periodicite</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="periodicite_facturelocation" name="periodicite">
                            <option value="">periodictie</option>
                            <option ng-repeat="item in dataPage['periodicites']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="appartementfacturelocation col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Choisissez le Contrat</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="contrat_facturelocation" name="contrat">
                            <option disabled value="">contrat</option>
                            <option ng-repeat="item in dataPage['contrats']" value="@{{ item.id }}">
                                @{{ item.descriptif }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="interventionfacturelocation col-span-6 sm:col-span-6">
                    <label for="objetfacture_facturelocation">Objet De la facture </label>
                    <input type="text" id="objetfacture_facturelocation" name="objetfacture" class="input w-full border mt-2 flex-1" placeholder="intervenant">
                </div>
                <div class="col-span-6 sm:col-span-6 moiscautionfacturelocation">
                    <label for="moiscaution_facturelocation">Nombre mois Caution</label>
                    <input type="text" id="moiscaution_facturelocation" name="moiscaution" class="input w-full border mt-2 flex-1" placeholder="montant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="datefacture_facturelocation">Date de la facture location</label>
                    <input type="date" id="datefacture_facturelocation" name="datefacture" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="dateecheance_facturelocation">Date d'échéance de la facture</label>
                    <input type="date" id="dateecheance_facturelocation" name="dateecheance" class="input w-full border mt-2 flex-1" placeholder="date echeance">
                </div>
                <div class="col-span-6 sm:col-span-6" id="hidemontant_facturelocation" style="display: none">
                    <label for="montant_facturelocation">Montant de La Facture</label>
                    <input type="number" id="montant_facturelocation" name="montant" class="input w-full border mt-2 flex-1" placeholder="montant facture">
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto" class="">Périodes</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select multiple class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="periodes_facturelocation" name="periodes">
                            {{-- <option value="" class="required">Périodes</option> --}}
                            <option ng-repeat="item in dataPage['periodes']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>



            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal facturelocation-->

{{-- start modal avis echeance --}}
<div class="modal" id="modal_addavisecheance">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Avis d'échéance
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addavisecheance" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'avisecheance')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_avisecheance" name="id">
            <input type="hidden" id="contrat_avisecheance" name="contrat">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class=" col-span-6 sm:col-span-6">
                    <label for="periodicite_avisecheance" class="required">Choisissez la périodicité</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="periodicite_avisecheance" name="periodicite">
                            <option value="">périodicité</option>
                            <option ng-repeat="item in dataPage['periodicites']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                {{-- <div class="periodemensuelle col-span-6 sm:col-span-6">
                        <label for="periodicite_avisecheance">Choisissez la période</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select
                                class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                id="periodes_avisecheance" name="periodes">
                                <option value="">période</option>
                                <option ng-repeat="item in dataPage['periodes']" value="@{{ item.designation }}">
                @{{ item.designation }}
                </option>
                </select>
            </div>
    </div> --}}
    <div class="periodetrimestrielle col-span-6 sm:col-span-6">
        <label for="categorietuto_typetuto" class="required">Périodes</label>
        <div class="inline-block mt-2 relative w-full">
            <select multiple class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="periodes_avisecheance" name="periodes[]">
                <option ng-repeat="item in dataPage['periodes']" value="@{{ item.designation }}">
                    @{{ item.designation }}
                </option>
            </select>
        </div>
    </div>

    <div class=" col-span-6 sm:col-span-6">
        <label for="objetfacture_avisecheance" class="required">Objet </label>
        <input type="text" id="objet_avisecheance" name="objet" class="input w-full border mt-2 flex-1" placeholder="objet">
    </div>

    <div class="col-span-6 sm:col-span-6">
        <label for="datefacture_avisecheance" class="required">Date </label>
        <input type="date" id="date_avisecheance" name="date" class="input w-full border mt-2 flex-1" placeholder="date">
    </div>
    <div class="col-span-6 sm:col-span-6">
        <label for="amortissement_locationvente" class="required">Quote part amortissement </label>
        <input type="number" id="amortissement_avisecheance" name="amortissement" class="input w-full border flex-1 mt-2" placeholder="amortissement">
    </div>

    <div class="col-span-6 sm:col-span-6">
        <label for="fraisdelocation_locationvente" class="required">Frais de location </label>
        <input type="number" id="fraisdelocation_avisecheance" name="fraisdelocation" class="input w-full border flex-1 mt-2" placeholder="frais de location">
    </div>
    <div class="col-span-6 sm:col-span-6">
        <label for="descriptif_locationvente" class="required"> Frais de gestion </label>
        <input type="number" id="fraisgestion_avisecheance" name="fraisgestion" class="input w-full border flex-1 mt-2" placeholder="frais de gestion">
    </div>
    <div class="col-span-6 sm:col-span-6">
        <label for="descriptif_locationvente">Code de l'avis </label>
        <input type="text" id="code_avis_avisecheance" name="code_avis" class="input w-full border flex-1 mt-2" placeholder="de de l'avis">
    </div>
    <div class="col-span-6 sm:col-span-6">
        <label for="dateecheance_avisecheance" class="required">Date d'échéance </label>
        <input type="date" id="dateecheance_avisecheance" name="dateecheance" class="input w-full border mt-2 flex-1" placeholder="date echeance">
    </div>



</div>



<div class="form-section pl-5 pt-3  text-xl">
    <i class="fa fa-info-circle text-blue-400"></i> Frais supplémentaires
</div>
<div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
    <div class="col-span-7 sm:col-span-7">
        <label for="descriptif_locationvente" class="required"> Designation </label>
        <input type="text" id="designation_fraissupplementaire_avisecheance" class="input w-full border flex-1 mt-2" placeholder="frais de gestion">
    </div>

    <div class="col-span-3 sm:col-span-3">
        <label for="amortissement_locationvente" class="required">Montant </label>
        <input type="number" id="frais_fraissupplementaire_avisecheance" class="input w-full border flex-1 mt-2" placeholder="....">
    </div>

    <div class="col-span-2 sm:col-span-2 text-right">
        <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="actionSurTabPaneTagData('add','fraissupplementaire_avisecheance')"><span class="fa fa-plus"></span></button>
    </div>

    <div class="col-span-12 sm:col-span-12">
        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
            <table class="table table-report sm:mt-1">
                <thead>
                    <tr class="bg-theme-101 text-white">
                        <th hidden class="whitespace-no-wrap">#</th>
                        <th class="whitespace-no-wrap">Designation frais</th>
                        <th class="whitespace-no-wrap">Montant frais</th>
                        <th class="text-center whitespace-no-wrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-if="item.detailId == detailpiece.id " class="intro-x" ng-repeat="item in dataInTabPane['fraissupplementaire_avisecheance']['data']">

                        <td hidden class="">
                            <div class="font-medium whitespace-no-wrap">
                                @{{ item.id }}</div>
                        </td>

                        <td class="">
                            <div class="font-medium whitespace-no-wrap">
                                @{{ item.designation }}</div>
                        </td>

                        <td class="">
                            <div class="font-medium whitespace-no-wrap">
                                @{{ item.frais }}</div>
                        </td>


                        <td class="table-report__action w-56">
                            <nav class="menu-leftToRight uk-flex text-center">
                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                </label>

                                <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'fraissupplementaire_avisecheance', $index)" title="Supprimer">
                                    <span class="fa fa-trash-alt"></span>
                                </button>

                            </nav>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="px-5 py-3 text-right border-t border-gray-200">
    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
    </button>
    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
</div>
</form>
</div>
</div>
{{-- end modal avis echeance --}}

{{--start modal facture acompte --}}
<div class="modal" id="modal_addfactureacompte">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Facture d'acompte
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addfactureacompte" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'factureacompte')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_factureacompte" name="id">
            <input type="hidden" id="contrat_factureacompte" name="contrat">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">


                <div class="col-span-6 sm:col-span-6 ">
                    <label for="datefacture_factureacompte" class="required">Date </label>
                    <input type="date" id="date_factureacompte" name="date" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>



                <div class="col-span-6 sm:col-span-6">
                    <label for="dateecheance_factureacompte ">Date d'échéance </label>
                    <input type="date" id="dateecheance_factureacompte" name="dateecheance" class="input w-full border mt-2 flex-1 " placeholder="date echeance">
                </div>
                <div class="col-span-12 sm:col-span-12 ">
                    <label for="descriptif_locationvente" class="required"> Montant acompte </label>
                    <input type="number" id="montant_factureacompte" name="montant" class="input w-full border flex-1 required" placeholder="montant">
                </div>
                {{-- <div class=" col-span-12 sm:col-span-12">
                        <div class="form-floating">
                            <label for="floatingTextarea" class="">Commentaire</label>

                            <textarea rows="4" id="commentaire_factureacompte" name="commentaire" class="input w-full border mt-2 flex-1 "
                                placeholder="contenu..." ></textarea>
                        </div>

                    </div> --}}

            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
{{-- end modal facture acompte --}}




<!-- debut modal details factureintervention -->
<div class="modal" id="modal_detailsfactureintervention">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Facture intervention
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addfactureinterventionfacture" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'factureintervention')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_factureinterventionfacture" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="datefacture_facture">Date de la facture</label>
                    <input disabled type="date" id="datefacture_factureinterventionfacture" name="datefacture" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="intervenantassocie_facture">Intervenant associé </label>
                    <input disabled type="text" id="intervenantassocieintervention_factureinterventionfacture" name="intervenantassocieintervention" class="input w-full border mt-2 flex-1" placeholder="intervenant">
                </div>
                <div class="intervention col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto" class="required">Intervention</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select disabled class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="interventiondetail_factureinterventionfacture" name="interventiondetail">
                            <option value="">intervention</option>
                            <option ng-repeat="item in dataPage['interventions']" value="@{{ item.id }}">
                                @{{ item.descriptif }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="montant_facture">Montant</label>
                    <input disabled type="text" id="montant_factureinterventionfacture" name="montant" class="input w-full border mt-2 flex-1" placeholder="montant">
                </div>
                {{-- <div class="col-span-6 sm:col-span-6">
                    <label for="documentfacture_facture">Document Facture</label>
                    <input type="file" id="documentfacture_facture" name="documentfacture" class="input w-full border mt-2 flex-1" placeholder="document">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="recupaiement_facture">Recu de paiement</label>
                    <input type="file" id="recupaiement_facture" name="recupaiement" class="input w-full border mt-2 flex-1" placeholder="recu">
                </div> --}}
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Femer
                </button>
                <button hidden type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal details factureintervention-->




<!-- debut modal facture -->
<div class="modal" id="modal_addfactureintervention">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Facture intervention
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addfactureintervention" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'factureintervention')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_factureintervention" name="id">
            <input type="hidden" id="retourcaution_id" name="retourcaution">
            <input type="hidden" id="contratcaution_id" name="contratcaution">
            <input type="hidden" id="contratfacture_id" name="contratfacture">
            <input type="hidden" id="etatlieu_factureintervention" name="etatlieu">
            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infofacture" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos</a>
                        <a data-toggle="tab" data-target="#detailfacture" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Détails</a>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-content__pane active" id="infofacture">

                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-6 sm:col-span-6">
                            <label for="datefactureintervention_factureintervention">Date de la facture</label>
                            <input type="date" id="datefactureintervention_factureintervention" name="datefactureintervention" class="input w-full border mt-2 flex-1" placeholder="date">
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <label for="objet_message" class="required">Objet</label>
                            <input type="text" id="objet_factureintervention" name="objet" class="input w-full border mt-2 flex-1 required" placeholder="objet">
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="categorietuto_typetuto">Proprietaire</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_proprietaire" id="proprietaire_factureintervention" name="proprietaire">
                                    <option value="">proprietaire</option>
                                    <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">
                                        @{{ item.prenom }} @{{ item.nom }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="categorietuto_typetuto">Locataire</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_locataire" id="locataire_factureintervention" name="locataireintervention">
                                    <option value="">locataire</option>
                                    <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                        @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="categorietuto_typetuto">Prestataire-Beneficiaire-Intervenant</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="proprietaire_factureintervention" name="prestataire">
                                    <option value="">Prestataire</option>
                                    <option ng-repeat="item in dataPage['prestataires']" value="@{{ item.id }}">
                                        @{{ item.prenom }} @{{ item.nom }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="intervenantassocie_facture">Autres Prestataire-Beneficiaire-Intervenant </label>
                            <input type="text" id="intervenantassocieintervention_facture" name="intervenantassocieintervention" class="input w-full border mt-2 flex-1" placeholder="intervenant">
                        </div>



                        <div class="col-span-3 sm:col-span-3" id="etatlieuhidde_factureintervention">
                            <label for="categorietuto_typetuto">Demande d'intervention</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="demandeinterventiondetail_factureintervention" name="intervention">
                                    <option value="">demande</option>
                                    <option ng-repeat="item in dataPage['demandeinterventions']" value="@{{ item.id }}">
                                        @{{ item.designation }}

                                    </option>
                                </select>
                            </div>
                        </div>

                        <!--<div class="col-span-6 sm:col-span-6">
                                <label for="documentfacture_facture" class="required">Document Facture</label>
                                <input type="file" id="documentfacture_facture" name="documentfacture" class="input w-full border mt-2 flex-1 required" placeholder="document">
                            </div>
                            <div class="col-span-6 sm:col-span-6">
                                <label for="recupaiement_facture" class="required">Recu de paiement</label>
                                <input type="file" id="recupaiement_facture" name="recupaiement" class="input w-full border mt-2 flex-1 required" placeholder="recu">
                            </div>-->
                    </div>
                </div>







                <div class="tab-content__pane" id="detailfacture">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-6 sm:col-span-6">
                            <label for="categorietuto_typetuto" class="required">Intervention</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="interventiondetail_factureintervention_intervention_factureintervention" name="interventiondetail">
                                    <option selected value="">intervention</option>
                                    <option ng-repeat="item in dataPage['interventions']" value="@{{ item.id}}">
                                        @{{ item.categorieintervention.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-5 sm:col-span-5">
                            <label for="montant_facture_intervention_facture">Montant</label>
                            <input type="text" id="montant_factureintervention_intervention_factureintervention" name="montantintervention" class="input w-full border mt-2 flex-1 required" placeholder="montant">
                        </div>
                        <div class="col-span-1 sm:col-span-1 text-right">
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-7" ng-click="actionSurTabPaneTagData('add','factureintervention_intervention_factureintervention')"><span class="fa fa-plus"></span></button>
                        </div>
                        <!--                            <div class="col-span-2 sm:col-span-2">-->
                        <!--                                Caution : @{{ contratdata.caution.montantcaution }}-->
                        <!--                            </div>-->
                        <!--                            <div class="col-span-2 sm:col-span-2">-->
                        <!--                                Total facture : @{{ totalfacture }}-->
                        <!--                            </div>-->
                        <!--                            <div class="col-span-2 sm:col-span-2">-->
                        <!--                                Retour caution : @{{ contratdata.caution.montantcaution - totalfacture }}-->
                        <!--                            </div>-->
                        <div class="col-span-12 sm:col-span-12">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                <table class="table table-report sm:mt-1">
                                    <thead>
                                        <tr class="bg-theme-101 text-white">
                                            <th hidden class="whitespace-no-wrap">#</th>
                                            <th class="whitespace-no-wrap">Intervention</th>
                                            <th class="whitespace-no-wrap text-center">Montant</th>
                                            <th class="text-center whitespace-no-wrap">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataInTabPane['factureintervention_intervention_factureintervention']['data']">

                                            <td hidden class="">
                                                <div class="font-medium whitespace-no-wrap">@{{ item.id }}
                                                </div>
                                            </td>

                                            <td class="">
                                                <div class="font-medium whitespace-no-wrap ">@{{ item.interventiondetail_text }}
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.montant }}</div>
                                            </td>

                                            <td class="table-report__action w-56">
                                                <nav class="menu-leftToRight uk-flex text-center">
                                                    <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                                    <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                        <span class="hamburger bg-template-1 hamburger-1"></span>
                                                        <span class="hamburger bg-template-1 hamburger-2"></span>
                                                        <span class="hamburger bg-template-1 hamburger-3"></span>
                                                    </label>

                                                    <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'factureintervention_intervention_factureintervention', $index)" title="Supprimer">
                                                        <span class="fa fa-trash-alt"></span>
                                                    </button>
                                                </nav>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal facture-->

<!-- debut modal contratprestation -->
<div class="modal" id="modal_addcontratprestation">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fad fa-money-check-edit-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Contrat de prestation
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addcontratprestation" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'contratprestation')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_contratprestation" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto" class="required">Choisissez le prestataire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="prestataire_contratprestation" name="prestataire">
                            <option value="">prestataire</option>
                            <option ng-repeat="item in dataPage['prestataires']" value="@{{ item.id }}">
                                @{{ item.prenom }} @{{ item.nom }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto" class="required">Choisissez la categorie de
                        prestation</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="categorieprestation_contratprestation" name="categorieprestation">
                            <option value="">categorie</option>
                            <option ng-repeat="item in dataPage['categorieprestations']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="datesignaturecontrat_contratprestation" class="required">Date de signature</label>
                    <input type="date" id="datesignaturecontrat_contratprestation" name="datesignaturecontrat" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="datedemarragecontrat_contratprestation" class="required">Date de demarrage du
                        contrat</label>
                    <input type="date" id="datedemarragecontrat_contratprestation" name="datedemarragecontrat" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="daterenouvellementcontrat_contratprestation" class="required">Date de renouvellement
                        du contrat</label>
                    <input type="date" id="daterenouvellementcontrat_contratprestation" name="daterenouvellementcontrat" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto" class="required">Frequence prestation</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="frequenceprestation_contratprestation" name="frequenceprestation">
                            <option value="" class="required">frequence</option>
                            <option ng-repeat="item in dataPage['frequencepaiementappartements']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="datepremiereprestation_contratprestation" class="required">Date de la premiere
                        prestation</label>
                    <input type="date" id="datepremiereprestation_contratprestation" name="datepremiereprestation" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="datepremierefacture_contratprestation" class="required">Date de la premiere
                        facture</label>
                    <input type="date" id="datepremierefacture_contratprestation" name="datepremierefacture" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="montant_contratprestation" class="required">Montant</label>
                    <input type="text" id="montant_contratprestation" name="montant" class="input w-full border mt-2 flex-1 required" placeholder="montant">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="document_contratprestation" class="required">Document</label>
                    <input type="file" id="document_contratprestation" name="document" class="input w-full border mt-2 flex-1 required" placeholder="document">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal facture-->

<!-- debut modal demanderesiliation -->
<div class="modal" id="modal_adddemanderesiliation">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-user-alt mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Demande de resiliation
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_adddemanderesiliation" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'demanderesiliation')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_demanderesiliation" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Locataire</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required search_locataire" id="locataire_demanderesiliation" name="locataire">
                            <option value="" class="required">locataire</option>
                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="categorietuto_typetuto">Choisissez l'appartement</label>
                    <div class="inline-block mt-2 relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required search_appartement" id="appartement_demanderesiliation" name="appartement">
                            <option value="" class="required">appartement</option>
                            <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">
                                @{{ item.nom }}
                                @{{ item.lot_ilot_refact }}
                            </option>
                        </select>
                    </div>
                </div>
                {{-- <div class="col-span-6 sm:col-span-6">
                        <label for="categorietuto_typetuto">Contrat</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select
                                class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required"
                                id="contrat_demanderesiliation" name="contrat">
                                <option value="" class="required">contrat</option>
                            </select>
                        </div>
                    </div> --}}
                <div class="col-span-6 sm:col-span-6">
                    <label for="datedebutcontrat_demanderesiliation" class="required">Debut de debut du
                        contrat</label>
                    <input type="date" id="datedebutcontrat_demanderesiliation" name="datedebutcontrat" class="input w-full border mt-2 flex-1 required" placeholder="debut">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="datedemande_demanderesiliation" class="required">Date de la demande</label>
                    <input type="date" id="datedemande_demanderesiliation" name="datedemande" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="dateeffectivite_demanderesiliation" class="required">Date d'effectivité</label>
                    <input type="date" id="dateeffectivite_demanderesiliation" name="dateeffectivite" class="input w-full border mt-2 flex-1 required" placeholder="date">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="motif_demanderesiliation">Motif de la résiliation</label>
                    <input type="textarea" id="motif_demanderesiliation" name="motif" class="input w-full border mt-2 flex-1" placeholder="motif">
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <label for="document_demanderesiliation">Document</label>
                    <input type="file" id="document_demanderesiliation" name="document" class="input w-full border mt-2 flex-1" placeholder="document">
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label id="raison" class="text-danger" for="raisonnonrespectdelai_demanderesiliation">Delai
                        de preavis non respecté</label>
                    <input type="text" id="raisonnonrespectdelai_demanderesiliation" value="non" name="raisonnonrespectdelai" class="input w-full border mt-2 flex-1" placeholder="veillez renseigner la raison">
                </div>

                <div ng-if="item_update" class="col-span-6 sm:col-span-6">
                    <div ng-if="item_update.document" class=" font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.document)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Document</a>
                    </div>
                </div>

            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal paiement demanderesiliation-->


<!-- debut modal immeuble -->
<div class="modal" id="modal_addimmeuble">
    <div class="modal__content modal__content--md" style="width: 90%">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-building mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Immeuble
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

        </div>

        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addimmeuble" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'immeuble')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_immeuble" name="id">



                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-4 sm:col-span-4">
                        <label for="nom_immeuble">Designation</label>
                        <input type="text" id="nom_immeuble" name="nom" class="input w-full border mt-2 flex-1" placeholder="Désignation">
                    </div>
                    <div class="col-span-4 sm:col-span-4">
                        <label for="categorietuto_typetuto">Type d'immeuble</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="structureimmeuble_immeuble" name="structureimmeuble">
                                <option value="" class="required">Type</option>
                                <option ng-repeat="item in dataPage['structureimmeubles']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-4 sm:col-span-4">
                        <label for="adresse_immeuble">Adresse</label>
                        <input type="text" id="adresse_immeuble" name="adresse" class="input w-full border mt-2 flex-1" placeholder="Adresse">
                    </div>
                </div>
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div ng-repeat="item in dataPage['typepieces']" ng-if="item.iscommun == '1'" class="col-span-2 sm:col-span-2">
                        <div> @{{ item.designation }} </div>

                        <label>
                            <input id="@{{ item.id }}_id_oui" name="@{{ item.id }}_name" onchange="showInputquesionnaire(this,'oui')" value="Oui" type="radio" />
                            <span>Oui</span>
                        </label>

                        <label>
                            <input id="@{{ item.id }}_id" name="@{{ item.id }}_name" onchange="showInputquesionnaire(this,'non')" value="Non" type="radio" />
                            <span>Non</span>
                        </label>
                        <div class="col-span-3 sm:col-span-3">
                            <input id="@{{ item.id }}_id_oui_nombre" name="@{{ item.id }}" type="number" class="input w-full border mt-2 flex-1" placeholder="Entrez le nombre">
                            <!--                                <input ng-if="item.id == 14"  id="@{{ item.id }}_id_oui_nomsalledefete"  name="nomsalledefete" type="text" class="input w-full border mt-2 flex-1" placeholder="nom de la salle">
                                     -->
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>
    </div>
    <!-- fin modal immeuble-->

    <!-- debut modal details demande intervention -->
    <div class="modal" id="modal_detailsdemandeintervention">
        <div class="modal__content modal__content--md">
            <div class="flex items-center px-5 py-5 sm:py-3 header ">
                <h2 class="font-medium text-base mr-auto">
                    Demande d'intervention
                </h2>
                <div class="pull-right">
                    <button class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            @csrf
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-6 sm:col-span-6 border border-gray-200 p-4 rounded-lg d-flex flex-column justify-content-between" style="height: 100%;">
                    <div style="margin-bottom: 20px;">
                        <div style="margin-bottom: 15px;">
                            <span style="font-weight: bold;">DESCRIPTIF:</span>
                            <span id="detail_descriptif_demandeintervention" style="margin-left: 10px;"></span>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <span style="font-weight: bold;">IMMEUBLE:</span>
                            <span id="detail_immeuble_demandeintervention" style="margin-left: 10px;"></span>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <span style="font-weight: bold;">CONCERNÉS:</span>
                            <span id="detail_demandeur_demandeintervention" style="margin-left: 10px;"></span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center" style='margin-bottom: 20px;'>
                        <a target="blanck_" href="generate-pdf-one-devi/@{{ detailParentId }}/devi" class="btn btn-primary rounded shadow py-2 px-4 bg-dark text-white" style="text-decoration: none;">
                            Voir PDF du devis
                            <span class=" fa fa-file-pdf" style="line-height: 50px;"></span>
                        </a>
                        <a ng-click="showModalAdd('intervention',{is_file_excel:false, title:null},detailParentId)" class="btn rounded shadow btn-primary mx-1 py-2 px-4 bg-dark text-white" style="text-decoration: none; cursor: pointer;">
                            Intervenir
                            <span class="fa fa-tools" style="line-height: 50px;"></span>
                        </a>
                    </div>
                </div>


                <div class="col-span-6 sm:col-span-6 text-center">
                    <label for="detail_image_demandeintervention" class="cursor-pointer">
                        <img id="detail_image_demandeintervention" src="" alt="..." class="image-hover shadow" style="width: 250px;height: 250px;border-radius: 10%!important;margin: 0 auto">
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- fin modal details demande intervention-->

<!-- debut modal details avis echeance -->
<div class="modal" id="modal_detailsavisecheance">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <h2 class="font-medium text-base mr-auto">
                Details Paiement Echeance
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        @csrf
        {{-- <div class="p-5 grid grid-cols-12 gap-4 row-gap-3"> --}}
        <div class="pl-5 pt-5 pr-5  grid grid-cols-12 gap-4 row-gap-3 mb-3">

            <div class="col-span-12 sm:col-span-12 border border-gray-200 p-4 rounded-lg d-flex flex-column justify-content-between" style="height: 100%;">
                <div class="col-span-12 sm:col-span-12">

                    <b>
                        Tous les paiements d'échéances
                    </b>

                    <div class="overflow-table">

                        <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                            <table class="table table-report sm:mt-2">
                                <thead>
                                    <tr>
                                        <th class="whitespace-no-wrap">Reservataire/lot/iilot</th>
                                        <th class="whitespace-no-wrap">Date</th>
                                        <th class="whitespace-no-wrap">Periode</th>
                                        <th class="whitespace-no-wrap text-center">Montant</th>
                                        <th class="whitespace-no-wrap text-center">Modepaiement</th>
                                        <th class="whitespace-no-wrap text-center">Action</th>


                                    </tr>
                                </thead>
                                <tbody id="body_detail_paiementecheance">
                                    <tr ng-repeat="item in dataPage['paiementecheances']">
                                        <td>@{{ item.avisecheance.contrat.locataire.prenom}} @{{ item.avisecheance.contrat.locataire.nom}}</td>
                                        <td>@{{ item.date }}</td>
                                        <td>@{{ item.periodes }}</td>
                                        <td>@{{ item.montant_format }}</td>
                                        <td>@{{ item.modepaiement.designation }}</td>
                                        <td class="table-report__action w-56">
                                            <nav class="menu-leftToRight uk-flex text-center">
                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1us-detail-@{{ item.id }}">
                                                <label class="menu-open-button bg-white" for="menu-open1us-detail-@{{ item.id }}">
                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                </label>
                                                <button class="menu-item btn border-0 bg-info text-white fsize-16" title="Ajouter" ng-if="item.avisecheance.est_activer !==2 " ng-click="showModalAdd('paiementecheance', {is_file_excel: false, title: null, fromUpdate: false}, item.avisecheance.id)">
                                                    <span class="fal fa-plus"></span>
                                                </button>
                                                <button class="menu-item btn border-0 bg-danger text-white fsize-16" title="Supprimer le paiement" ng-click="deleteElement('paiementecheance', item.id)">
                                                    <span class="fas fa-trash-alt"></span>
                                                </button>
                                                <button class="menu-item btn border-0 bg-warning text-white fsize-16" ng-if="!item.etat" title="Annuler paiement" ng-click="showModalAdd('annulationpaiementavis', {is_file_excel: false, title: null, fromUpdate: false}, item.id)">
                                                    <span class="fa fa-ban"></span>
                                                </button>
                                                <button class="menu-item btn border-0 bg-success text-white fsize-16" title="Reçu de paiement" ng-click="redirectPdf('paiementecheance/recu/' + item.id)">
                                                    <span class="fas fa-eye"></span>
                                                </button>
                                                {{-- <button class="menu-item btn border-0 bg-success text-white fsize-16" title="Modifier paiement" ng-click="showModalUpdate('paiementecheance', item.id)">
                                                    <span class="fa fa-pencil"></span>
                                                </button> --}}
                                                <button class="menu-item btn border-0 bg-info text-white fsize-16" ng-if="item.etat == -1" title="Réactiver le paiement" ng-click="annulerPaiementEcheance(item.id,2,'Voulez-vous réactiver le paiement ?')">
                                                    <span class="fas fa-check"></span>
                                                </button>

                                            </nav>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- fin modal details avis echeance-->

<!-- debut modal details locataire -->
<div class="modal" id="modal_detailslocataire">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-file-invoice-dollar"></i>
            <h2 class="font-medium text-base mr-auto">
                Locataire
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        @csrf
        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

            <div class="col-span-5 sm:col-span-5 text-center">
            </div>
            <div class="col-span-5 sm:col-span-5 text-center">
            </div>
            <div class="col-span-12 sm:col-span-12" style="border-bottom: 1px solid black">

                <div class="p-3" id="basic-accordion">
                    <div class="preview">
                        <div class="accordion">
                            <div class="accordion__pane border-gray-200">
                                <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                    <div class="flex flex-wrap">
                                        <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>INFOS LOCATAIRE</div>
                                        <div class="w-full md:w-1/2 px-3 text-right">
                                            <button class="button bg-theme-101 text-white btn-shadow">
                                                <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                                <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                    <div id="detail_prenom_nom_locataire" class="col-span-2 sm:col-span-2">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3" id="basic-accordion">
                    <div class="preview">
                        <div class="accordion">
                            <div class="accordion__pane border-gray-200">
                                <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                    <div class="flex flex-wrap">
                                        <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>APPARTEMENTS LOUES</div>
                                        <div class="w-full md:w-1/2 px-3 text-right">
                                            <button class="button bg-theme-101 text-white btn-shadow">
                                                <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                                <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                    <div id="detail_appartement_locataire"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-12">
                <label><strong>Demandes d'interventions</strong></label><br>
                <div id="detail_demandeintervention_locataire"></div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- fin modal details locataire-->


<!-- debut modal details immeuble -->
<div class="modal" id="modal_detailsimmeuble">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-file-invoice-dollar"></i>
            <h2 class="font-medium text-base mr-auto">
                Details Immeuble
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        @csrf
        <div class="intro-y pr-1 mt-1">
            <div class="box p-2 item-tabs-produit">
                <div class="pos__tabs nav-tabs justify-center flex">
                    <a data-toggle="tab" data-target="#infosimmeuble" id="infosimmeublelink" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Infos immeuble</a>
                    <!-- <a data-toggle="tab" data-target="#loyers" id="loyerlink" href="javascript:;"
                               class="flex-1 py-2 rounded-md text-center">Loyers payés
                            </a>-->
                    <a data-toggle="tab" data-target="#factures" id="facturelink" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Factures</a>
                    <a data-toggle="tab" data-target="#contrats" id="contratlink" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Contrats
                    </a>
                    <a data-toggle="tab" data-target="#assurances" id="assurancelink" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Assurances / Impots</a>
                </div>
            </div>
        </div>
        <div class="tab-content">

            <div class="tab-content__pane active" id="infosimmeuble">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-12 sm:col-span-12" style="border-bottom: 1px solid black">

                        <div class="p-3" id="basic-accordion">
                            <div class="preview">
                                <div class="accordion">
                                    <div class="accordion__pane border-gray-200">
                                        <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                            <div class="flex flex-wrap">
                                                <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>INFOS IMMEUBLE</div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="button bg-theme-101 text-white btn-shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <div id="detail_immeuble" class="col-span-2 sm:col-span-2">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3" id="basic-accordion">
                            <div class="preview">
                                <div class="accordion">
                                    <div class="accordion__pane border-gray-200">
                                        <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                            <div class="flex flex-wrap">
                                                <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>EQUIPE DE GESTION</div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="button bg-theme-101 text-white btn-shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <div id="detail_equipegestion_immeuble"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3" id="basic-accordion">
                            <div class="preview">
                                <div class="accordion">
                                    <div class="accordion__pane border-gray-200">
                                        <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                            <div class="flex flex-wrap">
                                                <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>PIECES ET EQUIPEMENTS COMMUNS
                                                </div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="button bg-theme-101 text-white btn-shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <div id="detail_pieceequipements_immeuble"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="tab-content__pane" id="loyers">

                        <div class="overflow-table">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                                <table class="table table-report sm:mt-2">
                                    <thead>
                                    <tr style="background-color: #eaeaea">
                                        <th class="whitespace-no-wrap">Locataire</th>
                                        <th class="whitespace-no-wrap text-center">Appartement</th>
                                        <th class="whitespace-no-wrap text-center">Date de paiement</th>
                                        <th class="whitespace-no-wrap text-center">debut periode de validité</th>
                                        <th class="whitespace-no-wrap text-center">fin periode de validité</th>
                                        <th class="whitespace-no-wrap text-center">Montant</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataPage['paiementloyers']" ng-if="item.contrat.appartement.immeuble.id == detailParentId">
                                        <td>
                                            <div ng-if="item.contrat.locataire.prenom" class="font-medium whitespace-no-wrap">@{{ item.contrat.locataire.prenom }} @{{ item.contrat.locataire.nom }}</div>
                                            <div ng-if="item.contrat.locataire.nomentreprise" class="font-medium whitespace-no-wrap">@{{ item.contrat.locataire.nomentreprise }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.contrat.appartement.nom }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.datepaiement }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.debutperiodevalide }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.finperiodevalide }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">@{{ item.montantfacture }}</div>
                                        </td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-span-12 grid grid-cols-12 gap-4 mt-3">
                            <div class="col-span-12 sm:col-span-12 md:col-span-3">
                                <span>Affichage par</span>
                                <select class="w-20 input box mt-1" ng-model="paginations['paiementloyer'].entryLimit" ng-change="pageChanged('paiementloyer')">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-span-12 sm:col-span-12 md:col-span-9" style="float:right">
                                <nav aria-label="Page navigation">
                                    <ul class="uk-pagination float-right" uib-pagination total-items="paginations['paiementloyer'].totalItems" ng-model="paginations['paiementloyer'].currentPage" max-size="paginations['paiementloyer'].maxSize" items-per-page="paginations['paiementloyer'].entryLimit" ng-change="pageChanged('paiementloyer')" previous-text="‹" next-text="›" first-text="«" last-text="»" boundary-link-numbers="true" rotate="false"></ul>
                                </nav>
                            </div>
                        </div>
                    </div>-->

            <div class="tab-content__pane active" id="contrats">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                            <table class="table table-report sm:mt-1">
                                <thead>
                                    <tr class="bg-theme-101 text-white">
                                        <th class="whitespace-no-wrap">Descriptif</th>
                                        <th class="whitespace-no-wrap text-center">Appartement</th>
                                        <th class="whitespace-no-wrap text-center">Montant loyer</th>
                                        <th class="whitespace-no-wrap text-center">Montant loyer de base</th>
                                        <th class="whitespace-no-wrap text-center">Montant loyer tom</th>
                                        <th class="whitespace-no-wrap text-center">montant charges</th>
                                        <th class="whitespace-no-wrap text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataPage['contrats']" ng-if="item.appartement.immeuble.id == detailParentId">
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.descriptif }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.appartement.nom }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.montantloyerformat }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.montantloyerbaseformat }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.montantloyertomformat }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.montantchargeformat }}</div>
                                        </td>
                                        <td class="table-report__action w-56">
                                            <nav class="menu-leftToRight uk-flex text-center">
                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open5-@{{ $index }}">
                                                <label class="menu-open-button bg-white" for="menu-open5-@{{ $index }}">
                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                </label>
                                                <button type="button" class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="redirectPdf(item.document)" title="Voir le document">
                                                    <span class="fal fa-eye"></span>
                                                </button>
                                            </nav>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content__pane" id="factures">
                <div class="intro-y pr-1 mt-1">
                    <div class="box p-2 item-tabs-produit">
                        <div class="pos__tabs nav-tabs justify-center flex">
                            <a data-toggle="tab" ng-repeat="item in dataPage['typefactures']" data-target="#immeuble@{{ item.designation }}" id="idlinkimmzublz_@{{ item.designation }}" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">@{{ item.designation }}</a>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div ng-repeat="item in dataPage['typefactures']" class="tab-content__pane" id="immeuble@{{ item.designation }}">
                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-12 sm:col-span-12">
                                <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                    <table class="table table-report sm:mt-1">
                                        <thead>
                                            <tr class="bg-theme-101 text-white">
                                                <th hidden class="whitespace-no-wrap">#</th>
                                                <th ng-if="item.designation == 'intervention'" class="whitespace-no-wrap">Intervention</th>
                                                <th ng-if="item.designation != 'intervention'" class="whitespace-no-wrap text-center">Appartement</th>
                                                <th class="whitespace-no-wrap">Date facture</th>
                                                <th class="whitespace-no-wrap text-center">Mois facturé</th>
                                                <th class="whitespace-no-wrap text-center">Montant</th>
                                                <th class="text-center whitespace-no-wrap">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="intro-x" ng-repeat="item2 in dataPage['factures']" ng-if="(item2.typefacture.id == item.id && item2.appartement.immeuble.id == detailParentId) || (item2.intervention.demandeintervention.appartement.immeuble.id == detailParentId && item2.typefacture.id == item.id )">
                                                <td ng-if="item2.intervention" class="">
                                                    <div class="font-medium whitespace-no-wrap">
                                                        @{{ item2.intervention.descriptif }}</div>
                                                </td>
                                                <td ng-if="!item2.intervention" class="">
                                                    <div class="font-medium whitespace-no-wrap text-center">
                                                        @{{ item2.appartement.nom }}</div>
                                                </td>
                                                <td class="">
                                                    <div class="font-medium whitespace-no-wrap">
                                                        @{{ item2.datefacture_format }}</div>
                                                </td>
                                                <td class="">
                                                    <div class="font-medium whitespace-no-wrap text-center">
                                                        @{{ item2.moisfacture }}</div>
                                                </td>
                                                <td class="">
                                                    <div class="font-medium whitespace-no-wrap text-center">
                                                        @{{ item2.montant_format }}</div>
                                                </td>
                                                <td class="table-report__action w-56">
                                                    <nav class="menu-leftToRight uk-flex text-center">
                                                        <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open-immeuble@{{ $index }}">
                                                        <label class="menu-open-button bg-white" for="menu-open-immeuble@{{ $index }}">
                                                            <span class="hamburger bg-template-1 hamburger-1"></span>
                                                            <span class="hamburger bg-template-1 hamburger-2"></span>
                                                            <span class="hamburger bg-template-1 hamburger-3"></span>
                                                        </label>

                                                        <button type="button" class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="redirectPdf(item2.documentfacture)" title="Voir facture">
                                                            <span class="fal fa-eye"></span>
                                                        </button>
                                                        <button type="button" class="menu-item btn border-0 bg-success text-white fsize-16" ng-click="redirectPdf(item2.recupaiement)" title="Voir recu">
                                                            <span class="fal fa-eye"></span>
                                                        </button>
                                                    </nav>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
            <div class="tab-content__pane" id="assurances">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                            <table class="table table-report sm:mt-1">
                                <thead>
                                    <tr class="bg-theme-101 text-white">
                                        <th class="whitespace-no-wrap">Descriptif</th>
                                        <th class="whitespace-no-wrap text-center">Appartement</th>
                                        <th class="whitespace-no-wrap text-center">Montant</th>
                                        <th class="whitespace-no-wrap text-center">Date de debut</th>
                                        <th class="whitespace-no-wrap text-center">Date de fin</th>
                                        <th class="whitespace-no-wrap text-center">Type</th>
                                        <th class="whitespace-no-wrap text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataPage['obligationadministratives']" ng-if="item.immeuble.id == detailParentId">
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.designation }}</div>
                                        </td>
                                        <td>
                                            <div ng-if="item.appartement.id" class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.appartement.nom }}</div>
                                            <div ng-if="!item.appartement.id" class="font-medium whitespace-no-wrap text-center">Concerne tout
                                                l'immeuble</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.montant_format }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.debut_format }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.fin_format }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.typeobligationadministrative.designation }}</div>
                                        </td>
                                        <td class="table-report__action w-56">
                                            <nav class="menu-leftToRight uk-flex text-center">
                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open6-@{{ $index }}">
                                                <label class="menu-open-button bg-white" for="menu-open6-@{{ $index }}">
                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                </label>

                                                <button type="button" class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="redirectPdf(item.document)" title="Voir document">
                                                    <span class="fal fa-eye"></span>
                                                </button>
                                            </nav>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- fin modal details immeuble-->

<!-- debut modal details appartement -->
<div class="modal" id="modal_detailsappartement">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-hand-holding-usd"></i>
            <h2 class="font-medium text-base mr-auto">
                Details Appartement
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        @csrf
        <div class="intro-y pr-1 mt-1">
            <div class="box p-2 item-tabs-produit">
                <div class="pos__tabs nav-tabs justify-center flex">
                    <a data-toggle="tab" data-target="#infosappartement" id="infosappartementlink" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos appartement
                    </a>
                    <a data-toggle="tab" data-target="#loyerappartements" id="loyerappartementslink" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Loyers payés
                    </a>
                    <a data-toggle="tab" data-target="#factureappartements" id="factureappartementslink" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Factures</a>
                    <a data-toggle="tab" data-target="#cautionappartements" id="cautionappartementslink" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Cautions
                    </a>
                    <a data-toggle="tab" data-target="#assuranceappartements" id="assuranceappartementslink" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Assurances /
                        Impots</a>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-content__pane active" id="infosappartement">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-12 sm:col-span-12" style="border-bottom: 1px solid black">

                        <div class="p-3" id="basic-accordion">
                            <div class="preview">
                                <div class="accordion">
                                    <div class="accordion__pane border-gray-200">
                                        <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                            <div class="flex flex-wrap">
                                                <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>INFOS APPARTEMENT</div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="button bg-theme-101 text-white btn-shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <div id="detail_appartement" class="col-span-2 sm:col-span-2">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3" id="basic-accordion">
                            <div class="preview">
                                <div class="accordion">
                                    <div class="accordion__pane border-gray-200">
                                        <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                            <div class="flex flex-wrap">
                                                <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>INFOS LOCATAIRE</div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="button bg-theme-101 text-white btn-shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <div id="detail_infolocataire_appartement"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3" id="basic-accordion">
                            <div class="preview">
                                <div class="accordion">
                                    <div class="accordion__pane border-gray-200">
                                        <a href="javascript:;" class="accordion__pane__toggle font-medium block">
                                            <div class="flex flex-wrap">
                                                <div class="w-full md:w-1/2 px-3 self-center"><span class="fa fa-filter mr-1"></span>PIECES DE L'APPARTEMENT</div>
                                                <div class="w-full md:w-1/2 px-3 text-right">
                                                    <button class="button bg-theme-101 text-white btn-shadow">
                                                        <span class="w-5 h-5 flex items-center justify-center"> <i class="fa fa-chevron-down"></i> </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed">
                                            <div id="detail_pieces_appartement"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content__pane active subcontent classe_generale" id="loyerappartements">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12 font-medium text-base whitespace-no-wrap text-center">

                        @{{ contratfinance.locataire.prenom }} @{{ contratfinance.locataire.nom }} @{{ contratfinance.locataire.nomentreprise }}

                    </div>
                    <div ng-repeat="item in dataPage['paiementloyers']" ng-if="item.contrat.appartement.id == detailParentId" class="col-span-2 sm:col-span-2">
                        <input type="text" disabled style="background: #f6f7f8" value="@{{ item.periode }}" class="input w-full inline-block relative border mt-2 flex-1 text-center">
                    </div>
                </div>
            </div>

            <div class="tab-content__pane" id="factureappartements">

                <div class="intro-y pr-1 mt-1">
                    <div class="box p-2 item-tabs-produit">
                        <div class="pos__tabs nav-tabs justify-center flex">
                            <a data-toggle="tab" ng-repeat="item in dataPage['typefactures']" data-target="#@{{ item.designation }}" id="idlink_@{{ item.designation }}" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">@{{ item.designation }}</a>
                        </div>
                    </div>
                </div>
                <div class="tab-content">

                    <div ng-repeat="item in dataPage['typefactures']" class="tab-content__pane" id="@{{ item.designation }}">

                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-12 sm:col-span-12">
                                <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                    <table class="table table-report sm:mt-1">
                                        <thead>
                                            <tr class="bg-theme-101 text-white">
                                                <th hidden class="whitespace-no-wrap">#</th>
                                                <th ng-if="item.designation == 'intervention'" class="whitespace-no-wrap">Intervention</th>
                                                <th class="whitespace-no-wrap">Date facture</th>
                                                <th class="whitespace-no-wrap text-center">Mois facturé</th>
                                                <th class="whitespace-no-wrap text-center">Montant</th>
                                                <th class="text-center whitespace-no-wrap">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="intro-x" ng-repeat="item2 in dataPage['factures']" ng-if="(item2.typefacture.id == item.id && item2.appartement.id == detailParentId) || (item2.intervention.demandeintervention.appartement.id == detailParentId && item2.typefacture.id == item.id )">

                                                <td ng-if="item2.intervention" class="">
                                                    <div class="font-medium whitespace-no-wrap">
                                                        @{{ item2.intervention.descriptif }}</div>
                                                </td>
                                                <td class="">
                                                    <div class="font-medium whitespace-no-wrap">
                                                        @{{ item2.datefacture_format }}</div>
                                                </td>
                                                <td class="">
                                                    <div class="font-medium whitespace-no-wrap text-center">
                                                        @{{ item2.moisfacture }}</div>
                                                </td>
                                                <td class="">
                                                    <div class="font-medium whitespace-no-wrap text-center">
                                                        @{{ item2.montant_format }}</div>
                                                </td>
                                                <td class="table-report__action w-56">
                                                    <nav class="menu-leftToRight uk-flex text-center">
                                                        <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                                        <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                            <span class="hamburger bg-template-1 hamburger-1"></span>
                                                            <span class="hamburger bg-template-1 hamburger-2"></span>
                                                            <span class="hamburger bg-template-1 hamburger-3"></span>
                                                        </label>

                                                        <button type="button" class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="redirectPdf(item2.documentfacture)" title="Voir facture">
                                                            <span class="fal fa-eye"></span>
                                                        </button>
                                                        <button type="button" class="menu-item btn border-0 bg-success text-white fsize-16" ng-click="redirectPdf(item2.recupaiement)" title="Voir recu">
                                                            <span class="fal fa-eye"></span>
                                                        </button>
                                                    </nav>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="tab-content__pane" id="cautionappartements">
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                            <table class="table table-report sm:mt-1">
                                <thead>
                                    <tr class="bg-theme-101 text-white">
                                        <th class="whitespace-no-wrap">Contrat</th>
                                        <th class="whitespace-no-wrap text-center">Locataire</th>
                                        <th class="whitespace-no-wrap text-center">Montant</th>
                                        <th class="whitespace-no-wrap text-center">Date de versement</th>
                                        <th class="whitespace-no-wrap text-center">Etat</th>
                                        <th class="whitespace-no-wrap text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataPage['cautions']" ng-if="item.contrat.appartement.id == detailParentId">
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.contrat.descriptif }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.contrat.locataire.prenom }} @{{ item.contrat.locataire.nom }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.montantcaution }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.dateversement }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.etat }}</div>
                                        </td>
                                        <td class="table-report__action w-56">
                                            <nav class="menu-leftToRight uk-flex text-center">
                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open4-@{{ $index }}">
                                                <label class="menu-open-button bg-white" for="menu-open4-@{{ $index }}">
                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                </label>

                                                <button type="button" class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="redirectPdf(item.document)" title="Voir facture">
                                                    <span class="fal fa-eye"></span>
                                                </button>
                                            </nav>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content__pane" id="assuranceappartements">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                            <table class="table table-report sm:mt-1">
                                <thead>
                                    <tr class="bg-theme-101 text-white">
                                        <th class="whitespace-no-wrap">Descriptif</th>
                                        <th class="whitespace-no-wrap text-center">Montant</th>
                                        <th class="whitespace-no-wrap text-center">Date de debut</th>
                                        <th class="whitespace-no-wrap text-center">Date de fin</th>
                                        <th class="whitespace-no-wrap text-center">Type</th>
                                        <th class="whitespace-no-wrap text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x" ng-repeat="item in dataPage['obligationadministratives']" ng-if="item.appartement.id == detailParentId">
                                        <td>
                                            <div class="font-medium whitespace-no-wrap">@{{ item.designation }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.montant_format }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.debut_format }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.fin_format }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium whitespace-no-wrap text-center">
                                                @{{ item.typeobligationadministrative.designation }}</div>
                                        </td>
                                        <td class="table-report__action w-56">
                                            <nav class="menu-leftToRight uk-flex text-center">
                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open5-@{{ $index }}">
                                                <label class="menu-open-button bg-white" for="menu-open5-@{{ $index }}">
                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                </label>

                                                <button type="button" class="menu-item btn border-0 bg-info text-white fsize-16" ng-click="redirectPdf(item.document)" title="Voir document">
                                                    <span class="fal fa-eye"></span>
                                                </button>
                                            </nav>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- fin modal details appartement-->


<!-- debut modal contrat -->
<div class="modal" id="modal_addcontrat">
    <div class="modal__content modal__content--md" style="width: 90%">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-building mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Contrat de location
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

        </div>
        <form id="form_addcontrat" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'contrat')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_contrat" name="id">
            <input type="hidden" id="appartement_contrat_id" name="appartementcontrat_id">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infocontrat" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos générales</a>
                        <a data-toggle="tab" data-target="#locataire" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Locataire</a>
                        <a data-toggle="tab" data-target="#documentcontrat" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Documents</a>
                        <a data-toggle="tab" data-target="#caution" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Caution</a>
                        <a data-toggle="tab" data-target="#annexecontrat_contrat" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Annexes</a>

                    </div>
                </div>
            </div>

            <div class="tab-content">

                <div class="tab-content__pane active" id="infocontrat">
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-info-circle text-blue-400"></i>Informations générales
                    </div>


                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-3 sm:col-span-3">
                            <label for="descriptif_contrat">Descriptif</label>
                            <input type="text" id="descriptif_contrat" name="descriptif" class="input w-full border flex-1" placeholder="Déscriptif">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_immeuble">Périodicité</label>
                            <div class="inline-block relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="periodicite_contrat" name="periodicite">
                                    <option value="" class="required">Périodicité</option>
                                    <option ng-repeat="item in dataPage['periodicites']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="montantloyerbase_contrat">Montant du loyer de base</label>
                            <input type="number" id="montantloyerbase_contrat" name="montantloyerbase" ng-model="montantloyerBase" ng-change="montantLoyerFinal()" class="input w-full border flex-1" placeholder="montant">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="montantloyertom_contrat">Montant du loyer Tom</label>
                            <input type="number" id="montantloyertom_contrat" name="montantloyertom" ng-model="montantloyerTom" ng-change="montantLoyerFinal()" class="input w-full border flex-1" placeholder="montant">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="montantcharge_contrat">Montant des charges</label>
                            <input type="number" id="montantcharge_contrat" name="montantcharge" ng-model="montantCharge" ng-change="montantLoyerFinal()" class="input w-full border flex-1" placeholder="montant">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="montantloyer_contrat">Montant du loyer</label>
                            <input type="number" id="montantloyer_contrat" name="montantloyer" class="input w-full border flex-1" placeholder="montant">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="tauxrevision_contrat">Taux de revision</label>
                            <input type="number" id="tauxrevision_contrat" name="tauxrevision" class="input w-full border flex-1" placeholder="taux">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="frequencerevision_contrat">Frequence de revision</label>
                            <input type="number" id="frequencerevision_contrat" name="frequencerevision" class="input w-full border flex-1" placeholder="frequence">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="dateenregistrement_contrat">Date d'enregistrement</label>
                            <input type="date" id="dateenregistrement_contrat" name="dateenregistrement" class="input w-full border flex-1" placeholder="date">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="datepremierpaiement_contrat">Date du premier paiement</label>
                            <input type="date" id="datepremierpaiement_contrat" name="datepremierpaiement" class="input w-full border flex-1" placeholder="date">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="daterenouvellement_contrat">Date de renouvellement</label>
                            <input type="date" id="daterenouvellement_contrat" name="daterenouvellement" class="input w-full border flex-1" placeholder="date">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="datedebutcontrat_contrat">Date de debut du contrat</label>
                            <input type="date" id="datedebutcontrat_contrat" name="datedebutcontrat" class="input w-full border flex-1" placeholder="date">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="dateecheance_contrat">Date d'échéance</label>
                            <input type="date" id="dateecheance_contrat" name="dateecheance" class="input w-full border flex-1" placeholder="date d'échéance">
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_immeuble">Type de contrat</label>
                            <div class="inline-block relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typecontrat_contrat" name="typecontrat">
                                    <option value="" class="required">Type</option>
                                    <option ng-repeat="item in dataPage['typecontrats']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="rappelpaiement_contrat">Date de rappel paiement(Jour)</label>
                            {{-- <input type="date" id="rappelpaiement_contrat" name="rappelpaiement" class="input w-full border flex-1" placeholder="date"> --}}
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="rappelpaiement_contrat" name="rappelpaiement">
                                    <option value="">Selectionnez</option>
                                    <option ng-repeat="item in dataPage['rappelpaiementloyers']" value="@{{ item.id }}">
                                        @{{ item.libelle }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="typerenouvellement_contrat">Type de renouvellement</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typerenouvellement_contrat" name="typerenouvellement">
                                    <option value="">Type</option>
                                    <option ng-repeat="item in dataPage['typerenouvellements']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="delaipreavi_contrat">Delai de preavi</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="delaipreavi_contrat" name="delaipreavi">
                                    <option value="">Delai</option>
                                    <option ng-repeat="item in dataPage['delaipreavis']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="appartement_contrat">Appartement</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required search_appartement" id="appartement_contrat" name="appartement">
                                    <option value="" class="required">Appartement</option>
                                    {{-- <option value="@{{ item_update['appartement'].id }}" class="required" selected>@{{ item_update['appartement']['nom'] }}</option> --}}
                                    <option ng-repeat="item in dataPage['appartements']" ng-if="item.iscontrat == '0'" value="@{{ item.id }}">
                                        @{{ item.nom }} / @{{ item.immeuble.nom }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                        </div>
                    </div>
                </div>

                <div class="tab-content__pane" id="locataire">
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-house text-blue-400"></i>Locataire
                    </div>
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-6 sm:col-span-6">
                            {{-- <label>
                                <input id="check_typelocataire" required="" name="type_locataire" onchange="showInput(this,'locataireexistant','typelocataire')" value="existant" type="radio"/>
                                <span>Locataire existant</span>
                            </label>
                            <label>
                                <input id="check_typeintervenantprestataire" name="type_locataire" onchange="showInput(this,'nouveaulocataire','typelocataire')"  value="nouveau" type="radio"/>
                                <span>Nouveau locataire</span>
                            </label> --}}
                            <label for="locataire_contrat">Locataire</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_locataire" id="locataire_contrat" name="locataireexistant">
                                    <option value="" class="required">Choisir le locataire</option>
                                    <option ng-repeat="item in dataPage['locataires']" ng-if="item.entite.code == 'SCI'" value="@{{ item.id }}">
                                        <span ng-if="item.prenom"> @{{ item.prenom }} @{{ item.nom }}
                                        </span>
                                        <span ng-if="item.nomentreprise"> @{{ item.nomentreprise }} </span>
                                    </option>
                                </select>
                            </div>
                        </div>



                        <input type="hidden" name="entite" value="SCI">
                        <input type="hidden" name="type_locataire" value="existant">

                        {{-- <div class="1 col-span-3 sm:col-span-3">
                            <label for="prenom_locataire">Prenom</label>
                            <input type="text" id="prenom_locataire" name="prenom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="prenom">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="nom_locataire">Nom</label>
                            <input type="text" id="nom_locataire" name="nom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="nom">
                        </div>

                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="telephoneportable1_locataire">Telephone 1</label>
                            <input type="text" id="telephoneportable1_locataire" name="telephoneportable1" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="telephoneportable2_locataire">Telephone 2</label>
                            <input type="text" id="telephoneportable2_locataire" name="telephoneportable2" class="input w-full border mt-2 flex-1" placeholder="telephone 2">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="telephonebureau_locataire">Telephone bureau</label>
                            <input type="text" id="telephonebureau_locataire" name="telephonebureau" class="input w-full border mt-2 flex-1" placeholder="telephone bureau">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="email_locataire">Email</label>
                            <input type="text" id="email_locataire" name="email" class="input w-full border mt-2 flex-1" placeholder="email">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="profession_locataire">Profession</label>
                            <input type="text" id="profession_locataire" name="profession" class="input w-full border mt-2 flex-1" placeholder="profession">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="cni_locataire">Numéro CNI/Passport</label>
                            <input type="text" id="cni_locataire" name="cni" class="input w-full border mt-2 flex-1" placeholder="cni">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="documentcnipassport_locataire">Document CNI/Passport</label>
                            <input type="file" id="documentcnipassport_locataire" name="documentcnipassport" class="input w-full border mt-2 flex-1" placeholder="document">
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <label for="documentcontrattravail_locataire">Contrat de travail ou justificatif de revenus</label>
                            <input type="file" id="documentcontrattravail_locataire" name="documentcontrattravail" class="input w-full border mt-2 flex-1" placeholder="document">
                        </div>
                        <div class="1 col-span-1 sm:col-span-1 mt-6">
                            <input type="radio" id="expat_locataire" name="expatlocale" value="Expatrié" class="input w-full border mt-2 flex-1">
                            <label for="expat_locataire">Expatrié</label><br>
                        </div>
                        <div class="1 col-span-1 sm:col-span-1 mt-6">
                            <input type="radio" id="locale_locataire" name="expatlocale" value="Locale" class="input w-full border mt-2 flex-1">
                            <label for="locale_locataire">Locale</label><br>
                        </div>
                        <div class="1 col-span-3 sm:col-span-3">

                        </div>
                        <div class="1 col-span-3 sm:col-span-3">
                            <input type="checkbox" id="priseencharge_locataire" name="priseencharge" value="Oui" class="input w-full border mt-2 flex-1">
                            <label for="priseencharge_locataire">Prise en charge</label><br>
                            <input type="text" id="nomcompletpersonnepriseencharge_locataire" name="nomcompletpersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="nom complet de la personne responsable">
                            <input type="text" id="telephonepersonnepriseencharge_locataire" name="telephonepersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="telephone de la personne responsable">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="nomentreprise_locataire">Nom</label>
                            <input type="text" id="nomentreprise_locataire" name="nomentreprise" class="input w-full border mt-2 flex-1" placeholder="nom">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="adresseentreprise_locataire">Adresse</label>
                            <input type="text" id="adresseentreprise_locataire" name="adresseentreprise" class="input w-full border mt-2 flex-1" placeholder="adresse">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="personnehabiliteasigner_locataire">Personne habilite a signer</label>
                            <input type="text" id="personnehabiliteasigner_locataire" name="personnehabiliteasigner" class="input w-full border mt-2 flex-1" placeholder="personne habilite a signer">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="fonctionpersonnehabilite_locataire" >Fonction personne habilleté a signer</label>
                            <input type="text" id="fonctionpersonnehabilite_locataire" name="fonctionpersonnehabilite" class="input w-full border mt-2 flex-1" placeholder="fonction personne habilite">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="prenompersonneacontacter_locataire">Prenom personne a contacter</label>
                            <input type="text" id="prenompersonneacontacter_locataire" name="prenompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="prenom personne a contacter">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="nompersonneacontacter_locataire">Nom personne a contacter</label>
                            <input type="text" id="nompersonneacontacter_locataire" name="nompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="nom personne a contacter">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="emailpersonneacontacter_locataire">Email personne a contacter</label>
                            <input type="text" id="emailpersonneacontacter_locataire" name="emailpersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="email personne a contacter">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="telephone1personneacontacter_locataire">telephone 1 personne a contacter</label>
                            <input type="text" id="telephone1personneacontacter_locataire" name="telephone1personneacontacter" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3">
                            <label for="telephone2personneacontacter_locataire">Telephone 2 personne a contacter</label>
                            <input type="text" id="telephone2personneacontacter_locataire" name="telephone2personneacontacter" class="input w-full border mt-2 flex-1" placeholder="telephone2personneacontacter">
                        </div>
                        <div class="2 col-span-3 sm:col-span-3 text-center">
                            <label>
                                <span>Cochez s'il s'agit d'une entreprise</span><br>
                                <input id="check_entrepriseoui" class="mt-3 checkbox bg-theme-101" name="entrepriseautre" onchange="showInput(this,'entreprise','locataire')" type="checkbox"/>
                            </label>
                        </div>
                        <div class="entreprise col-span-3 sm:col-span-3">
                            <label for="ninea_locataire">Ninea</label>
                            <input type="text" id="ninea_locataire" name="ninea" class="input w-full border mt-2 flex-1" placeholder="ninea">
                        </div>
                        <div class="entreprise col-span-3 sm:col-span-3">
                            <label for="documentninea_locataire">Document Ninea</label>
                            <input type="file" id="documentninea_locataire" name="documentninea" class="input w-full border mt-2 flex-1" placeholder="document ninea">
                        </div>
                        <div class="entreprise col-span-3 sm:col-span-3">
                            <label for="numerorg_locataire">Numero RG</label>
                            <input type="text" id="numerorg_locataire" name="numerorg" class="input w-full border mt-2 flex-1" placeholder="numerorg">
                        </div>
                        <div class="entreprise col-span-3 sm:col-span-3">
                            <label for="documentnumerorg_locataire">Document num RG</label>
                            <input type="file" id="documentnumerorg_locataire" name="documentnumerorg" class="input w-full border mt-2 flex-1" placeholder="document numero rg">
                        </div>
                        <div class="entreprise col-span-3 sm:col-span-3">
                            <label for="documentstatut_locataire" >Document Statut</label>
                            <input type="file" id="documentstatut_locataire" name="documentstatut" class="input w-full border mt-2 flex-1" placeholder="document statut">
                        </div> --}}
                    </div>
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-info text-blue-400"></i> Infos
                    </div>
                    <div ng-if="rappelLocataireData && rappelLocataireData.typelocataire_id == '1'" class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class=" col-span-3 sm:col-span-3">
                            <label for="prenom_rappel_locataire">Prénom</label>
                            <input type="text" disabled id="prenom_rappel_locataire" value="@{{ rappelLocataireData.prenom }}" class="input w-full inline-block relative border mt-2 flex-1" placeholder="prénom">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="nom_rappel_locataire">Nom</label>
                            <input type="text" id="nom_rappel_locataire" disabled value="@{{ rappelLocataireData.nom }}" class="input w-full inline-block relative border mt-2 flex-1" placeholder="nom">
                        </div>
                        <div class=" col-span-3 sm:col-span-3">
                            <label for="email_rappel_locataire">Email</label>
                            <input type="text" id="email_rappel_locataire" disabled value="@{{ rappelLocataireData.email }}" class="input w-full border mt-2 flex-1" placeholder="Email">
                        </div>
                        <div class=" col-span-3 sm:col-span-3">
                            <label for="telephoneportable_rappel_locataire">Téléphone 1</label>
                            <input type="text" disabled value="@{{ rappelLocataireData.telephoneportable1 }}" id="telephoneportable_rappel_locataire" class="input w-full border mt-2 flex-1" placeholder="téléphone 1">
                        </div>
                        <div class=" col-span-3 sm:col-span-3">
                            <label for="telephoneportable2_rappel_locataire">Téléphone 2</label>
                            <input type="text" disabled value="@{{ rappelLocataireData.telephoneportable2 }}" id="telephoneportable2_rappel_locataire" class="input w-full border mt-2 flex-1" placeholder="téléphone é">
                        </div>
                        <div class=" col-span-3 sm:col-span-3">
                            <label for="entite_rappel_locataire">Programme</label>
                            <input type="text" disabled value="@{{ rappelLocataireData.entite.designation }}" id="entite_rappel_locataire" class="input w-full border mt-2 flex-1" placeholder="téléphone é">
                        </div>

                    </div>

                    <div ng-if="rappelLocataireData && rappelLocataireData.typelocataire_id == '2'" class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class=" col-span-3 sm:col-span-3">
                            <label for="nomentreprise_rappel_locataire">Nom entreprise</label>
                            <input type="text" disabled id="nomentreprise_rappel_locataire" value="@{{ rappelLocataireData.nomentreprise }}" class="input w-full inline-block relative border mt-2 flex-1" placeholder="prénom">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="adresseentreprise_rappel_locataire">Adresse entreprise</label>
                            <input type="text" id="adresseentreprise_rappel_locataire" disabled value="@{{ rappelLocataireData.adresseentreprise }}" name="nom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="nom">
                        </div>
                        <div class=" col-span-3 sm:col-span-3">
                            <label for="nineaentreprise_rappel_locataire">Ninéa</label>
                            <input type="text" disabled value="@{{ rappelLocataireData.ninea }}" id="nineaentreprise_rappel_locataire" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                        </div>

                        <div class=" col-span-3 sm:col-span-3">
                            <label for="prenompersonnecontact_rappel_locataire">Prénom & nom personne a
                                contacter</label>
                            <input type="text" disabled value="@{{ rappelLocataireData.prenompersonneacontacter }} @{{ rappelLocataireData.nompersonneacontacter }}" id="prenompersonnecontact_rappel_locataire" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                        </div>

                        <div class=" col-span-3 sm:col-span-3">
                            <label for="emailpersonnecontact_rappel_locataire">Email personne a contacter</label>
                            <input type="text" id="emailpersonnecontact_rappel_locataire" disabled value="@{{ rappelLocataireData.emailpersonneacontacter }}" class="input w-full border mt-2 flex-1" placeholder="Email">
                        </div>

                        <div class=" col-span-3 sm:col-span-3">
                            <label for="entite2_rappel_locataire">Programme</label>
                            <input type="text" disabled value="@{{ rappelLocataireData.entite.designation }}" id="entite2_rappel_locataire" class="input w-full border mt-2 flex-1" placeholder="téléphone é">
                        </div>


                    </div>
                    <div class="form-section pl-5 pt-3  text-xl" ng-if="rappelLocataireData && rappelLocataireData.typelocataire_id == '2'">
                        <i class="fa fa-info text-blue-400"></i> INFOS BENEFINECIAIRE
                    </div>
                    <div ng-if="rappelLocataireData && rappelLocataireData.typelocataire_id == '2'" class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class=" col-span-3 sm:col-span-3">
                            <label for="prenompersonnecontact_rappel_locataire">Nom complet</label>
                            <input type="text" id="nomcompletbeneficiaire_contrat" name="nomcompletbeneficiaire" class="input w-full border mt-2 flex-1" placeholder="Nom et Prenom beneficiaire">
                        </div>
                        <div class=" col-span-3 sm:col-span-3">Telephone</label>
                            <input type="text" id="telephonebeneficiaire_contrat" name="telephonebeneficiaire" class="input w-full border mt-2 flex-1" placeholder="Telephone">
                        </div>
                        <div class=" col-span-3 sm:col-span-3">Email</label>
                            <input type="text" id="emailbeneficiaire_contrat" name="emailbeneficiaire" class="input w-full border mt-2 flex-1" placeholder="Email">
                        </div>

                    </div>

                    {{-- <div class="col-span-12 mt-4 sm:col-span-12">

                        <table ng-hide="hideButton" class="table table-report sm:mt-2">
                            <thead>
                            <tr>
                                <th class="whitespace-no-wrap ">Document</th>
                                <th class="whitespace-no-wrap">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="intro-x" ng-if="item_update.document">
                                <input type="hidden" id="document_contrat" value="@{{ item_update.document }}" name="document">
                    <td>
                        <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.document)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Contrat</a></div>
                    </td>

                    <td class="table-report__action w-56">
                        <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('document')" title="Supprimer">
                            <span class="fa fa-trash-alt"></span>
                        </button>
                    </td>
                    </tr>
                    <tr class="intro-x" ng-if="item_update.scanpreavis">
                        <input type="hidden" id="scanpreavis_contrat" value="@{{ item_update.scanpreavis }}" name="scanpreavis">
                        <td>
                            <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.scanpreavis)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Pre-avis</a></div>
                        </td>
                        <td class="table-report__action w-56">
                            <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('scanpreavis')" title="Supprimer">
                                <span class="fa fa-trash-alt"></span>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                </div> --}}
            </div>
            <div class="tab-content__pane" id="documentcontrat">
                <div class="form-section pl-5 pt-3  text-xl">
                    <i class="fa fa-info-circle text-blue-400"></i>Document
                </div>
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-6 sm:col-span-6" ng-if="!item_update.document">
                        <label>Joindre le scan du contrat de location</label><br>
                        <input type="file" name="document" accept=".csv, .xls, .xlsx, .pdf" class="form-control filestyle required" data-buttonName="btn-shadow btn-transition btn-outline-danger p-2" data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi" data-iconName="fa fa-folder-open">
                    </div>
                    <div class="col-span-6 sm:col-span-6" ng-if="!item_update.scanpreavis">
                        <label>Joindre le scan du preavis</label><br>
                        <input type="file" name="scanpreavis" accept=".csv, .xls, .xlsx, .pdf" class="form-control filestyle" data-buttonName="btn-shadow btn-transition btn-outline-danger p-2" data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi" data-iconName="fa fa-folder-open">
                    </div>

                </div>
                <div class="col-span-12 mt-4 sm:col-span-12">

                    <table ng-hide="hideButton" class="table table-report sm:mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-no-wrap ">Document</th>
                                <th class="whitespace-no-wrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x" ng-if="item_update.document">
                                <input type="hidden" id="document_contrat" value="@{{ item_update.document }}" name="document">
                                <td>
                                    <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.document)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Contrat</a>
                                    </div>
                                </td>

                                <td class="table-report__action w-56">
                                    <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('document')" title="Supprimer">
                                        <span class="fa fa-trash-alt"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="intro-x" ng-if="item_update.scanpreavis">
                                <input type="hidden" id="scanpreavis_contrat" value="@{{ item_update.scanpreavis }}" name="scanpreavis">
                                <td>
                                    <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.scanpreavis)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Pre-avis</a>
                                    </div>
                                </td>
                                <td class="table-report__action w-56">
                                    <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('scanpreavis')" title="Supprimer">
                                        <span class="fa fa-trash-alt"></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-content__pane" id="caution">
                <div class="form-section pl-5 pt-3  text-xl">
                    <i class="fa fa-info-circle text-blue-400"></i>Caution
                </div>
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-4 sm:col-span-4">
                        <label for="montantcaution_caution">Montant caution</label>
                        <input type="text" id="montantcaution_contrat" name="montantcaution" class="input w-full border mt-2 flex-1" placeholder="montant">
                    </div>
                    <div class="col-span-4 sm:col-span-4">
                        <label for="dateversement_caution">Date de versement</label>
                        <input type="date" id="dateversement_contrat" name="dateversement" class="input w-full border mt-2 flex-1" placeholder="date">
                    </div>
                    <div class="col-span-4 sm:col-span-4">
                        <label for="document_caution">Document</label>
                        <input type="file" id="documentcaution_contrat" name="documentcaution" class="input w-full border mt-2 flex-1" placeholder="document">
                    </div>
                </div>
                <div id="caution_document_contrat" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <tr class="intro-x" ng-if="item_update.caution.document">
                        <input type="hidden" id="documentcaution_contrat2" value="@{{ item_update.caution.document }}" name="documentcaution">
                        <td>
                            <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.caution.document)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Cliquer
                                    pour voir le document de caution</a></div>
                        </td>
                    </tr>
                </div>
            </div>
            {{-- annexe start --}}
            <div class="tab-content__pane" id="annexecontrat_contrat">
                <div class="form-section pl-5 pt-3  text-xl">
                    <i class="fa fa-info-circle text-blue-400"></i>Annexes
                </div>
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-3 sm:col-span-3">
                        <label for="nomentreprise_locataire">Nom de l'annexe</label>
                        <input type="text" id="nom_contrat_annexesreyhan_contrat" name="annexe" class="input w-full border mt-2 flex-1" placeholder="nom de l'annexe">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="nomentreprise_locataire">Numéro de l'annexe</label>
                        <input type="number" id="numero_contrat_annexesreyhan_contrat" name="numero" class="input w-full border mt-2 flex-1" placeholder="numéro de l'annexe">
                    </div>
                    <div class="col-span-3 sm:col-span-3 mt-4">
                        <label>Joindre l'annexe</label><br>
                        <input type="file" accept=".csv, .pdf" class="form-control filestyle required" data-buttonName="btn-shadow btn-transition btn-outline-danger p-2" data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi" id="fichier_contrat_annexesreyhan_contrat" name="fichier" data-iconName="fa fa-folder-open">
                    </div>
                    <div class="col-span-2 sm:col-span-2 mt-4">
                        <button ng-click="actionSurTabPaneTagData('add','contrat_annexesreyhan_contrat')" type="button" class="btn btn-primay bg-primary text-white button mt-2 " title="pdf">
                            <span class="fas fa-plus"></span>
                        </button>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-12">
                    <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                        <table class="table table-report sm:mt-1">
                            <thead>
                                <tr class="bg-theme-101 text-white">
                                    <th class="whitespace-no-wrap">Fichier</th>
                                    <th class="whitespace-no-wrap text-center">Numéro</th>

                                    <th class="whitespace-no-wrap text-center">Nom</th>
                                    <th class="text-center whitespace-no-wrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="intro-x" ng-repeat="item in dataInTabPane['contrat_annexesreyhan_contrat']['data']">

                                    <td class="">

                                        <div class="font-medium whitespace-no-wrap">
                                            <a href="javascript:void(0)" class="open-file-link" data-file-url="@{{ item.fichier }}" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">
                                                voir fichier
                                            </a>
                                        </div>
                                    </td>


                                    <td class="">
                                        <div class="font-medium whitespace-no-wrap text-center">
                                            @{{ item.numero }}
                                        </div>
                                    </td>
                                    <td class="">
                                        <div class="font-medium whitespace-no-wrap text-center">
                                            @{{ item.nom }}
                                        </div>
                                    </td>

                                    <td class="table-report__action w-56">
                                        <nav class="menu-leftToRight uk-flex text-center">
                                            <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                            <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                <span class="hamburger bg-template-1 hamburger-1"></span>
                                                <span class="hamburger bg-template-1 hamburger-2"></span>
                                                <span class="hamburger bg-template-1 hamburger-3"></span>
                                            </label>

                                            <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'contrat_annexesreyhan_contrat', $index)" title="Supprimer">
                                                <span class="fa fa-trash-alt"></span>
                                            </button>
                                        </nav>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            {{-- annexe end --}}
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>

    </div>
</div>
<!-- fin modal contrat -->

{{-- modal add avenant  --}}
<div class="modal" id="modal_addavenant">
    <div class="modal__content modal__content--md" style="width: 90%">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-building mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Avenant
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

        </div>
        <form id="form_addavenant" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'avenant')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_avenant" name="id">
            <input type="hidden" id="id_contrat_avenant" name="contrat_id">


            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">


                <div class="col-span-3 sm:col-span-3">
                    <label for="periodicite_avenant">Périodicité</label>
                    <div class="inline-block relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="periodicite_avenant" name="periodicite">
                            <option value="" class="required">Périodicité</option>
                            <option ng-repeat="item in dataPage['periodicites']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="montantloyer_avenant">Montant du loyer</label>
                    <input type="number" id="montantloyer_avenant" name="montantloyer" class="input w-full border flex-1" placeholder="montant">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="montantloyerbase_avenant">Montant du loyer de base</label>
                    <input type="number" id="montantloyerbase_avenant" name="montantloyerbase" class="input w-full border flex-1" placeholder="montant">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="montantloyertom_avenant">Montant du loyer Tom</label>
                    <input type="number" id="montantloyertom_avenant" name="montantloyertom" class="input w-full border flex-1" placeholder="montant">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="montantcharge_avenant">Montant des charges</label>
                    <input type="number" id="montantcharge_avenant" name="montantcharge" class="input w-full border flex-1" placeholder="montant">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="tauxrevision_avenant">Taux de revision</label>
                    <input type="number" id="tauxrevision_avenant" name="tauxrevision" class="input w-full border flex-1" placeholder="taux">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="frequencerevision_avenant">Frequence de revision</label>
                    <input type="number" id="frequencerevision_avenant" name="frequencerevision" class="input w-full border flex-1" placeholder="frequence">
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="date_avenant">Date de l'avenant</label>
                    <input type="date" id="date_avenant" name="date" class="input w-full border flex-1" placeholder="date">
                </div>


                <div class="col-span-3 sm:col-span-3">
                    <label for="dateecheance_avenant">Date d'échéance</label>
                    <input type="date" id="dateecheance_avenant" name="dateecheance" class="input w-full border flex-1" placeholder="date d'échéance">
                </div>

                <div class="col-span-3 sm:col-span-3">
                    <label for="typecontrat_avenant">Type de contrat</label>
                    <div class="inline-block relative w-full">
                        <select class="block flex-1 select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typecontrat_avenant" name="typecontrat">
                            <option value="" class="required">Type</option>
                            <option ng-repeat="item in dataPage['typecontrats']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-3 sm:col-span-3">
                    <label for="typerenouvellement_avenant">Type de renouvellement</label>
                    <div class="inline-block  relative w-full">
                        <select class="block select2 flex-1 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typerenouvellement_avenant" name="typerenouvellement">
                            <option value="">Type</option>
                            <option ng-repeat="item in dataPage['typerenouvellements']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-3 sm:col-span-3">
                    <label for="delaipreavi_avenant">Delai de preavi</label>
                    <div class="inline-block  relative w-full">
                        <select class="block select2 flex-1 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="delaipreavi_avenant" name="delaipreavi">
                            <option value="">Delai</option>
                            <option ng-repeat="item in dataPage['delaipreavis']" value="@{{ item.id }}">
                                @{{ item.designation }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-6">
                </div>
            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>




        </form>

    </div>
</div>
{{-- modal add avenant  --}}

<!-- debut modal validation signature contrat -->
<div class="modal" id="modal_addsignaturecontrat">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fas fa-tags mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Signature contrat
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addsignaturecontrat" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'signaturecontrat')" style="max-height: 80vh!important;overflow: auto">
            <input type="hidden" name="contrat_id" id="contrat_id_signaturecontrat">
            <input type="hidden" name="signature" id="signature_signaturecontrat">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="d-flex mx-auto  align-items-center col-span-12 sm:col-span-12">
                    <canvas signature-pad id="signature_signaturecontrat" name="signature" style="border:2px solid black"></canvas>

                </div>

            </div>


            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" id="clear-button" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Effacer
                </button>
                <button id="save-button" type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal validation signature contrat-->
<!-- debut modal contrat location vente -->
<div class="modal" id="modal_addlocationvente">
    <div class="modal__content modal__content--md" style="width: 90%">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-building mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Contrat de location vente / Ridwan
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

        </div>
        @php
        $userRole = auth()->user()->roles->pluck('name')->first();
        @endphp


        <form id="form_addlocationvente" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'locationvente')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_locationvente" name="id">
            <input type="hidden" id="isRidwan_locationvente" name="isRidwan" value="1">
            <input type="hidden" id="acompteinitial_locationvente" name="acompteinitial">


            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infocontrat_lvt" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos générales</a>
                        <a data-toggle="tab" data-target="#locataire_lvt" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Réservataire</a>
                        <a data-toggle="tab" data-target="#documentcontrat_lvt" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Documents</a>
                        <a data-toggle="tab" data-target="#annexecontrat_lvt" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Annexes</a>
                    </div>
                </div>
            </div>

            <div class="tab-content">

                <div class="tab-content__pane active" id="infocontrat_lvt">
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-info-circle text-blue-400"></i>Informations générales

                    </div>

                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <div class="col-span-3 sm:col-span-3">
                            <label for="appartement_locationvente">Villa</label>
                            <div class="inline-block  relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="appartement_locationvente" name="appartement">
                                    <option value="" class="required">Villa</option>
                                    {{-- <option value="@{{ item_update['appartement'].id }}" class="required" selected>@{{ item_update['appartement']['nom'] }}</option> --}}
                                    {{-- {{ ng-if="item.iscontrat != '1' && !item_update" }} --}}
                                    <option ng-repeat="item in dataPage['villas']" ng-if="item.iscontrat != '1'" value="@{{ item.id }}">
                                        lot N°@{{ item.lot }}
                                    </option>


                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_immeuble">Périodicité</label>
                            <div class="inline-block relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="periodicite_locationvente" name="periodicite">
                                    <option value="" class="required">Périodicité</option>
                                    <option ng-repeat="item in dataPage['periodicites']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="descriptif_locationvente">Prix villa </label>
                            <input type="number" id="prixvilla_locationvente" name="prixvilla" class="input w-full border flex-1" placeholder="prix villa">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="descriptif_locationvente"> Loyer mensuel </label>
                            <input type="number" id="montantloyer_locationvente" name="montantloyer" class="input w-full border flex-1" placeholder="Loyer mensuel">
                        </div>

                        {{-- <div class="col-span-3 sm:col-span-3">
                                <label for="descriptif_locationvente"> Frais de gestion </label>
                                <input type="number" id="fraisgestion_locationvente" name="fraisgestion" class="input w-full border flex-1" placeholder="frais de gestion">
                            </div> --}}


                        {{-- <div class="col-span-3 sm:col-span-3">
                                <label for="dacompteinitial_locationvente">Acompte villa </label>
                                <input type="number" id="acompteinitial_locationvente" name="acompteinitial"
                                    class="input w-full border flex-1" placeholder="acompte villa en (%)">
                            </div> --}}
                        <div class="col-span-3 sm:col-span-3">
                            <label for="numerodossier_locationvente" class="">Numéro dossier</label>
                            <input type="text" id="numerodossier_locationvente" name="numerodossier" class=" input w-full border  flex-1" placeholder="numero dossier">
                        </div>
                        @if ($userRole && $userRole != 'Juriste')
                        <div class="col-span-3 sm:col-span-3">
                            <label for="maturite_locationvente">Maturié en année (ans)</label>
                            <input type="number" id="maturite_locationvente" name="maturite" class="input w-full border flex-1" placeholder="maturité">
                        </div>
                        @endif
                        {{-- <div class="col-span-3 sm:col-span-3">
                        <label for="depotinitial_locationvente">Dépot initial</label>
                        <input type="number" id="depotinitial_locationvente" name="depot_initial" class="input w-full border flex-1" placeholder="dépot initial ">
                    </div> --}}
                        <div class="col-span-3 sm:col-span-3">
                            <label for="indemnite_locationvente">Intérêts de retard - Indemnité</label>
                            <input type="number" id="indemnite_locationvente" name="indemnite" class="input w-full border flex-1" placeholder="intérêts de retard">
                        </div>
                        @if ($userRole && $userRole != 'Juriste')

                        <div class="col-span-3 sm:col-span-3">
                            <label for="codepartamortissemnt_locationvente">Quote part amortissement</label>
                            <input type="number" id="codepartamortissemnt_locationvente" name="codepartamortissemnt" class="input w-full border flex-1" placeholder="quote part amortissement">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="fraislocative_locationvente">Frais de location</label>
                            <input type="number" id="fraislocative_locationvente" name="fraislocative" class="input w-full border flex-1" placeholder="frais de location">
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="fraisdegestion_locationvente">Frais de gestion</label>
                            <input type="number" id="fraisdegestion_locationvente" name="fraisdegestion" class="input w-full border flex-1" placeholder="frais de gestion">
                        </div>
                        {{-- <div class="col-span-3 sm:col-span-3">
                                <label for="indemnite_locationvente">Frais et coûts de la location-vente</label>
                                <input type="text" disabled="true" id="fraiscoutlocationvente_locationvente"
                                    name="fraiscoutlocationvente" class="input w-full border flex-1 disabled"
                                    placeholder="frais cout location vente">
                            </div> --}}
                        @endif
                        {{-- @if ($userRole && $userRole != 'Juriste')
                            <div class="col-span-3 sm:col-span-3">
                                <label for="descriptif_locationvente">Descriptif du contrat</label>
                                <input type="text" id="descriptif_locationvente" name="descriptif"
                                    class="input w-full border flex-1" placeholder="Déscriptif">
                            </div>
                            @endif --}}


                        <div class="col-span-3 sm:col-span-3">
                            <label for="apportinitial_locationvente">Apport initial</label>
                            <input type="number" id="apportinitial_locationvente" name="apportinitial" class="input w-full border flex-1" placeholder="apport initial">
                        </div>
                        @if ($userRole && $userRole != 'Juriste')
                        <div class="col-span-3 sm:col-span-3">
                            <label for="apportiponctuel_locationvente">Apport ponctuel</label>
                            <input type="number" id="apportiponctuel_locationvente" name="apportiponctuel" class="input w-full border flex-1" placeholder="apport ponctuel">
                        </div>
                        @endif

                        @if ($userRole && $userRole != 'Juriste')
                        <div class="col-span-3 sm:col-span-3">
                            <label for="dureelocationvente_contrat">Durée de location vente (mois)</label>
                            <input type="number" id="dureelocationvente_locationvente" name="dureelocationvente" class="input w-full border flex-1" placeholder="durée de location vente (mois)">
                        </div>
                        @endif

                        <div class="col-span-3 sm:col-span-3">
                            <label for="clausepenale_contrat">Clause pénale (%)</label>
                            <input type="number" id="clausepenale_locationvente" name="clausepenale" class="input w-full border flex-1" placeholder="clausepenale (%)">
                        </div>
                        @if ($userRole && $userRole != 'Juriste')


                        <div class="col-span-3 sm:col-span-3">
                            <label for="dateenregistrement_contrat">Date d'enregistrement</label>
                            <input type="date" id="dateenregistrement_locationvente" name="dateenregistrement" class="input w-full border flex-1" placeholder="date">
                        </div>
                        @endif
                        <div class="col-span-3 sm:col-span-3">
                            <label for="datedebutcontrat_locationvente">Date début contrat</label>
                            <input type="date" id="datedebutcontrat_locationvente" name="datedebutcontrat" class="input w-full border flex-1" placeholder="date">
                        </div>
                        @if ($userRole && $userRole != 'Juriste')


                        <div class="col-span-3 sm:col-span-3">
                            <label for="dateecheance_contralocationventet">Date d'échéance</label>
                            <input type="date" id="dateecheance_locationvente" name="dateecheance" class="input w-full border flex-1" placeholder="date d'échéance">
                        </div>
                        @endif


                        <div class="col-span-3 sm:col-span-3">
                            <label for="dateremisecles_locationvente">Date de livraison des locaux</label>
                            <input type="date" id="dateremisecles_locationvente" name="dateremisecles" class="input w-full border flex-1" placeholder="date">
                        </div>


                        <div class="col-span-3 sm:col-span-3">
                            <label for="categorietuto_immeuble">Type de contrat</label>
                            <div class="inline-block relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typecontrat_locationvente" name="typecontrat">
                                    <option value="" class="required">Type</option>
                                    <option ng-repeat="item in dataPage['typecontrats']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        @if ($userRole && $userRole != 'Juriste')
                        <div class="col-span-3 sm:col-span-3">
                            <label for="rappelpaiement_contrat">Date de rappel paiement(Jour)</label>
                            {{-- <input type="date" id="rappelpaiement_contrat" name="rappelpaiement" class="input w-full border flex-1" placeholder="date"> --}}
                            <div class="inline-block  relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="rappelpaiement_locationvente" name="rappelpaiement">
                                    <option value="">Selectionnez</option>
                                    <option ng-repeat="item in dataPage['rappelpaiementloyers']" value="@{{ item.id }}">
                                        @{{ item.libelle }}
                                    </option>
                                </select>
                            </div>
                        </div>


                        <div class="col-span-3 sm:col-span-3">
                            <label for="delaipreavi_contrat">Delai de preavi</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="delaipreavi_locationvente" name="delaipreavi">
                                    <option value="">Delai</option>
                                    <option ng-repeat="item in dataPage['delaipreavis']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="dateremisecles_locationvente">Email</label>
                            <input type="text" id="email_locationvente" name="email" class="input w-full border flex-1" placeholder="Email">
                        </div>

                        @endif

                        <div class="col-span-6 sm:col-span-6">
                        </div>
                    </div>
                </div>

                <div class="tab-content__pane" id="locataire_lvt">
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-house text-blue-400"></i>Réservataire
                    </div>
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-6 sm:col-span-6">

                        </div>
                        <input type="hidden" name="type_locataire" value="existant">
                        <div class="locataireexistant col-span-6 sm:col-span-6">

                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <div class="custom-control custom-switch" style="cursor:pointer;">
                                <input type="checkbox" onchange="onActivateCopreneurLocationvente(this)" style="cursor:pointer;" name="est_copreuneur" class="custom-control-input" id="est_copreuneur_locationvente">
                                <label class="custom-control-label" for="est_copreuneur_locataire"> <em>A cocher seulement en cas de co-réservation</em> </label>
                            </div>
                        </div>

                        <div class="locataireexistant col-span-3 sm:col-span-3">
                            <label for="locataire_contrat">Réservataire</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="locataireexistant_locationvente" name="locataireexistant">
                                    <option value="" class="required">Choisir le Réservataire</option>
                                    {{-- ng-if="item.entite.code == 'RID'"  --}}
                                    <option ng-repeat="item in dataPage['locataires']" ng-if="item.entite.code == 'RID'" value="@{{ item.id }}">
                                        <span ng-if="item.prenom"> @{{ item.prenom }} @{{ item.nom }}
                                        </span>
                                        <span ng-if="item.nomentreprise"> @{{ item.nomentreprise }} </span>
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="locataire-copreneur displaycopreneurlvt col-span-3 sm:col-span-3">
                            <label for="copreneur_locationvente">Co-preneur</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="copreneur_locationvente" name="copreneur">
                                    <option value="" class="required">Choisir le co-preneur</option>

                                    <option ng-repeat="item in copreneursData" value="@{{item.id}}">
                                        @{{ item.prenom }} @{{ item.nom }}
                                    </option>

                                </select>
                            </div>
                        </div>

                        {{-- <div class="nouveaulocataire col-span-12 sm:col-span-12">
                            <label for="categorietuto_typetuto">Choisissez le type de locataire</label>
                            <div class="inline-block relative w-full mt-2">
                                <select onchange="typeLocataire(this)" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typelocataire_locataire_contrat" name="typelocataire" >
                                    <option value="" class="required">Type</option>
                                    <option ng-repeat="item in dataPage['typelocataires']" value="@{{ item.id }}">
                        @{{ item.designation }}
                        </option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="entite" value="RID">
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="prenom_locataire">Prenom</label>
                    <input type="text" id="prenom_locataire" name="prenom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="prenom">
                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="nom_locataire">Nom</label>
                    <input type="text" id="nom_locataire" name="nom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="nom">
                </div>

                <div class="1 col-span-3 sm:col-span-3">
                    <label for="telephoneportable1_locataire">Telephone 1</label>
                    <input type="text" id="telephoneportable1_locataire" name="telephoneportable1" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="telephoneportable2_locataire">Telephone 2</label>
                    <input type="text" id="telephoneportable2_locataire" name="telephoneportable2" class="input w-full border mt-2 flex-1" placeholder="telephone 2">
                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="telephonebureau_locataire">Telephone bureau</label>
                    <input type="text" id="telephonebureau_locataire" name="telephonebureau" class="input w-full border mt-2 flex-1" placeholder="telephone bureau">
                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="email_locataire">Email</label>
                    <input type="text" id="email_locataire" name="email" class="input w-full border mt-2 flex-1" placeholder="email">
                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="profession_locataire">Profession</label>
                    <input type="text" id="profession_locataire" name="profession" class="input w-full border mt-2 flex-1" placeholder="profession">
                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="cni_locataire">Numéro CNI/Passport</label>
                    <input type="text" id="cni_locataire" name="cni" class="input w-full border mt-2 flex-1" placeholder="cni">
                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="documentcnipassport_locataire">Document CNI/Passport</label>
                    <input type="file" id="documentcnipassport_locataire" name="documentcnipassport" class="input w-full border mt-2 flex-1" placeholder="document">
                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <label for="documentcontrattravail_locataire">Contrat de travail ou justificatif de revenus</label>
                    <input type="file" id="documentcontrattravail_locataire" name="documentcontrattravail" class="input w-full border mt-2 flex-1" placeholder="document">
                </div>
                <div class="1 col-span-1 sm:col-span-1 mt-6">
                    <input type="radio" id="expat_locataire" name="expatlocale" value="Expatrié" class="input w-full border mt-2 flex-1">
                    <label for="expat_locataire">Expatrié</label><br>
                </div>
                <div class="1 col-span-1 sm:col-span-1 mt-6">
                    <input type="radio" id="locale_locataire" name="expatlocale" value="Locale" class="input w-full border mt-2 flex-1">
                    <label for="locale_locataire">Locale</label><br>
                </div>
                <div class="1 col-span-3 sm:col-span-3">

                </div>
                <div class="1 col-span-3 sm:col-span-3">
                    <input type="checkbox" id="priseencharge_locataire" name="priseencharge" value="Oui" class="input w-full border mt-2 flex-1">
                    <label for="priseencharge_locataire">Prise en charge</label><br>
                    <input type="text" id="nomcompletpersonnepriseencharge_locataire" name="nomcompletpersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="nom complet de la personne responsable">
                    <input type="text" id="telephonepersonnepriseencharge_locataire" name="telephonepersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="telephone de la personne responsable">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="nomentreprise_locataire">Nom</label>
                    <input type="text" id="nomentreprise_locataire" name="nomentreprise" class="input w-full border mt-2 flex-1" placeholder="nom">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="adresseentreprise_locataire">Adresse</label>
                    <input type="text" id="adresseentreprise_locataire" name="adresseentreprise" class="input w-full border mt-2 flex-1" placeholder="adresse">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="personnehabiliteasigner_locataire">Personne habilite a signer</label>
                    <input type="text" id="personnehabiliteasigner_locataire" name="personnehabiliteasigner" class="input w-full border mt-2 flex-1" placeholder="personne habilite a signer">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="fonctionpersonnehabilite_locataire">Fonction personne habilleté a signer</label>
                    <input type="text" id="fonctionpersonnehabilite_locataire" name="fonctionpersonnehabilite" class="input w-full border mt-2 flex-1" placeholder="fonction personne habilite">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="prenompersonneacontacter_locataire">Prenom personne a contacter</label>
                    <input type="text" id="prenompersonneacontacter_locataire" name="prenompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="prenom personne a contacter">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="nompersonneacontacter_locataire">Nom personne a contacter</label>
                    <input type="text" id="nompersonneacontacter_locataire" name="nompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="nom personne a contacter">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="emailpersonneacontacter_locataire">Email personne a contacter</label>
                    <input type="text" id="emailpersonneacontacter_locataire" name="emailpersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="email personne a contacter">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="telephone1personneacontacter_locataire">telephone 1 personne a contacter</label>
                    <input type="text" id="telephone1personneacontacter_locataire" name="telephone1personneacontacter" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                </div>
                <div class="2 col-span-3 sm:col-span-3">
                    <label for="telephone2personneacontacter_locataire">Telephone 2 personne a contacter</label>
                    <input type="text" id="telephone2personneacontacter_locataire" name="telephone2personneacontacter" class="input w-full border mt-2 flex-1" placeholder="telephone2personneacontacter">
                </div>
                <div class="2 col-span-3 sm:col-span-3 text-center">
                    <label>
                        <span>Cochez s'il s'agit d'une entreprise</span><br>
                        <input id="check_entrepriseoui" class="mt-3 checkbox bg-theme-101" name="entrepriseautre" onchange="showInput(this,'entreprise','locataire')" type="checkbox" />
                    </label>
                </div>
                <div class="entreprise col-span-3 sm:col-span-3">
                    <label for="ninea_locataire">Ninea</label>
                    <input type="text" id="ninea_locataire" name="ninea" class="input w-full border mt-2 flex-1" placeholder="ninea">
                </div>
                <div class="entreprise col-span-3 sm:col-span-3">
                    <label for="documentninea_locataire">Document Ninea</label>
                    <input type="file" id="documentninea_locataire" name="documentninea" class="input w-full border mt-2 flex-1" placeholder="document ninea">
                </div>
                <div class="entreprise col-span-3 sm:col-span-3">
                    <label for="numerorg_locataire">Numero RG</label>
                    <input type="text" id="numerorg_locataire" name="numerorg" class="input w-full border mt-2 flex-1" placeholder="numerorg">
                </div>
                <div class="entreprise col-span-3 sm:col-span-3">
                    <label for="documentnumerorg_locataire">Document num RG</label>
                    <input type="file" id="documentnumerorg_locataire" name="documentnumerorg" class="input w-full border mt-2 flex-1" placeholder="document numero rg">
                </div>
                <div class="entreprise col-span-3 sm:col-span-3">
                    <label for="documentstatut_locataire">Document Statut</label>
                    <input type="file" id="documentstatut_locataire" name="documentstatut" class="input w-full border mt-2 flex-1" placeholder="document statut">
                </div> --}}
            </div>
            {{-- <div class="col-span-12 mt-4 sm:col-span-12">

                        <table ng-hide="hideButton" class="table table-report sm:mt-2">
                            <thead>
                            <tr>
                                <th class="whitespace-no-wrap ">Document</th>
                                <th class="whitespace-no-wrap">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="intro-x" ng-if="item_update.document">
                                <input type="hidden" id="document_contrat" value="@{{ item_update.document }}" name="document">
            <td>
                <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.document)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Contrat</a></div>
            </td>

            <td class="table-report__action w-56">
                <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('document')" title="Supprimer">
                    <span class="fa fa-trash-alt"></span>
                </button>
            </td>
            </tr>
            <tr class="intro-x" ng-if="item_update.scanpreavis">
                <input type="hidden" id="scanpreavis_contrat" value="@{{ item_update.scanpreavis }}" name="scanpreavis">
                <td>
                    <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.scanpreavis)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Pre-avis</a></div>
                </td>
                <td class="table-report__action w-56">
                    <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('scanpreavis')" title="Supprimer">
                        <span class="fa fa-trash-alt"></span>
                    </button>
                </td>
            </tr>
            </tbody>
            </table>
    </div> --}}
</div>
<div class="tab-content__pane" id="documentcontrat_lvt">
    <div class="form-section pl-5 pt-3  text-xl">
        <i class="fa fa-info-circle text-blue-400"></i>Documents
    </div>

    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
        <div class="col-span-6 sm:col-span-6" ng-if="!item_update.document">
            <label>Joindre le scan du contrat de location</label><br>
            <input type="file" name="document" accept=".csv, .xls, .xlsx, .pdf" class="form-control filestyle " data-buttonName="btn-shadow btn-transition btn-outline-danger p-2" data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi" data-iconName="fa fa-folder-open">
        </div>
        <div class="col-span-6 sm:col-span-6" ng-if="!item_update.scanpreavis">
            <label>Joindre le scan du preavis</label><br>
            <input type="file" name="scanpreavis" accept=".csv, .xls, .xlsx, .pdf" class="form-control filestyle" data-buttonName="btn-shadow btn-transition btn-outline-danger p-2" data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi" data-iconName="fa fa-folder-open">
        </div>

    </div>
    <div class="col-span-12 mt-4 sm:col-span-12">

        <table ng-hide="hideButton" class="table table-report sm:mt-2">
            <thead>
                <tr>
                    <th class="whitespace-no-wrap ">Document</th>
                    <th class="whitespace-no-wrap">Actions</th>
                </tr>

            </thead>
            <tbody>
                <tr class="intro-x">
                    <input type="hidden" id="document_contrat" value="@{{ item_update.document }}" name="document">
                    <td>
                        <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.document)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Contrat</a>
                        </div>
                    </td>

                    <td class="table-report__action w-56">
                        <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('document')" title="Supprimer">
                            <span class="fa fa-trash-alt"></span>
                        </button>
                    </td>
                </tr>
                <tr class="intro-x" ng-if="item_update.scanpreavis">
                    <input type="hidden" id="scanpreavis_contrat" value="@{{ item_update.scanpreavis }}" name="scanpreavis">
                    <td>
                        <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.scanpreavis)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Pre-avis</a>
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('scanpreavis')" title="Supprimer">
                            <span class="fa fa-trash-alt"></span>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- annexe start --}}
<div class="tab-content__pane" id="annexecontrat_lvt">
    <div class="form-section pl-5 pt-3  text-xl">
        <i class="fa fa-info-circle text-blue-400"></i>Annexes
    </div>
    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

        <div class="col-span-3 sm:col-span-3">
            <label for="nomentreprise_locataire">Nom de l'annexe</label>
            <input type="text" id="nom_contrat_annexes_contrat" name="annexe" class="input w-full border mt-2 flex-1" placeholder="nom de l'annexe">
        </div>
        <div class="col-span-3 sm:col-span-3">
            <label for="nomentreprise_locataire">Numéro de l'annexe</label>
            <input type="number" id="numero_contrat_annexes_contrat" name="numero" class="input w-full border mt-2 flex-1" placeholder="numéro de l'annexe">
        </div>
        <div class="col-span-3 sm:col-span-3 mt-4">
            <label>Joindre l'annexe</label><br>
            <input type="file" accept=".csv, .pdf" class="form-control filestyle required" data-buttonName="btn-shadow btn-transition btn-outline-danger p-2" data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi" id="fichier_contrat_annexes_contrat" name="fichier" data-iconName="fa fa-folder-open">
        </div>
        <div class="col-span-2 sm:col-span-2 mt-4">
            <button ng-click="actionSurTabPaneTagData('add','contrat_annexes_contrat')" type="button" class="btn btn-primay bg-primary text-white button mt-2 " title="pdf">
                <span class="fas fa-plus"></span>
            </button>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-12">
        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
            <table class="table table-report sm:mt-1">
                <thead>
                    <tr class="bg-theme-101 text-white">
                        <th class="whitespace-no-wrap">Fichier</th>
                        <th class="whitespace-no-wrap text-center">Numéro</th>

                        <th class="whitespace-no-wrap text-center">Nom</th>
                        <th class="text-center whitespace-no-wrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="intro-x" ng-repeat="item in dataInTabPane['contrat_annexes_contrat']['data']">

                        <td class="">

                            <div class="font-medium whitespace-no-wrap">
                                <a href="javascript:void(0)" class="open-file-link" data-file-url="@{{ item.fichier }}" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">
                                    voir fichier
                                </a>
                            </div>
                        </td>


                        <td class="">
                            <div class="font-medium whitespace-no-wrap text-center">
                                @{{ item.numero }}
                            </div>
                        </td>
                        <td class="">
                            <div class="font-medium whitespace-no-wrap text-center">
                                @{{ item.nom }}
                            </div>
                        </td>

                        <td class="table-report__action w-56">
                            <nav class="menu-leftToRight uk-flex text-center">
                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                </label>

                                <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'contrat_annexes_contrat', $index)" title="Supprimer">
                                    <span class="fa fa-trash-alt"></span>
                                </button>
                            </nav>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    {{-- <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-6 sm:col-span-6" ng-if="!item_update.document">
                                <label>Joindre le scan du contrat de location</label><br>
                                <input type="file" name="document" accept=".csv, .xls, .xlsx, .pdf"
                                    class="form-control filestyle required"
                                    data-buttonName="btn-shadow btn-transition btn-outline-danger p-2"
                                    data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi"
                                    data-iconName="fa fa-folder-open">
                            </div>
                            <div class="col-span-6 sm:col-span-6" ng-if="!item_update.scanpreavis">
                                <label>Joindre le scan du preavis</label><br>
                                <input type="file" name="scanpreavis" accept=".csv, .xls, .xlsx, .pdf"
                                    class="form-control filestyle"
                                    data-buttonName="btn-shadow btn-transition btn-outline-danger p-2"
                                    data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi"
                                    data-iconName="fa fa-folder-open">
                            </div>

                        </div>
                        <div class="col-span-12 mt-4 sm:col-span-12">

                            <table ng-hide="hideButton" class="table table-report sm:mt-2">
                                <thead>
                                    <tr>
                                        <th class="whitespace-no-wrap ">Document</th>
                                        <th class="whitespace-no-wrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="intro-x">
                                        <input type="hidden" id="document_contrat" value="@{{ item_update.document }}"
    name="document">
    <td>
        <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.document)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Contrat</a>
        </div>
    </td>

    <td class="table-report__action w-56">
        <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('document')" title="Supprimer">
            <span class="fa fa-trash-alt"></span>
        </button>
    </td>
    </tr>
    <tr class="intro-x" ng-if="item_update.scanpreavis">
        <input type="hidden" id="scanpreavis_contrat" value="@{{ item_update.scanpreavis }}" name="scanpreavis">
        <td>
            <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.scanpreavis)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Pre-avis</a>
            </div>
        </td>
        <td class="table-report__action w-56">
            <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('scanpreavis')" title="Supprimer">
                <span class="fa fa-trash-alt"></span>
            </button>
        </td>
    </tr>
    </tbody>
    </table>
</div> --}}
</div>
{{-- annexe end --}}
{{--
                <div class="tab-content__pane" id="caution_lvt">
                    <div class="form-section pl-5 pt-3  text-xl">
                        <i class="fa fa-info-circle text-blue-400"></i>Caution
                    </div>
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-4 sm:col-span-4">
                            <label for="montantcaution_caution">Montant caution</label>
                            <input type="text" id="montantcaution_contrat" name="montantcaution" class="input w-full border mt-2 flex-1" placeholder="montant">
                        </div>
                        <div class="col-span-4 sm:col-span-4">
                            <label for="dateversement_caution">Date de versement</label>
                            <input type="date" id="dateversement_contrat" name="dateversement" class="input w-full border mt-2 flex-1" placeholder="date">
                        </div>
                        <div class="col-span-4 sm:col-span-4" >
                            <label for="document_caution">Document</label>
                            <input type="file" id="documentcaution_contrat"  name="documentcaution" class="input w-full border mt-2 flex-1" placeholder="document">
                        </div>
                    </div>
                    <div id="caution_document_contrat"  class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                        <tr class="intro-x" ng-if="item_update.caution.document">
                            <input type="hidden" id="documentcaution_contrat2" value="@{{ item_update.caution.document }}" name="documentcaution">
<td>
    <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(item_update.caution.document)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Cliquer pour voir le document de caution</a></div>
</td>
</tr>
</div>
</div> --}}
<div class="px-5 py-3 text-right border-t border-gray-200">
    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
</div>
</form>

</div>
</div>
<!-- fin modal contrat location vente -->
<!-- debut modal details contrat -->
<div class="modal" id="modal_detailscontrat">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-file-invoice-dollar"></i>
            <h2 class="font-medium text-base mr-auto">
                Details Contrat
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        @csrf
        <div class="intro-y pr-1 mt-1">
            <div class="box p-2 item-tabs-produit">
                <div class="pos__tabs nav-tabs justify-center flex">
                    <a data-toggle="tab" data-target="#infosdetailscontrat" id="infoscontratlink" href="javascript:;" class="flex-1 py-2 rounded-md text-center active" title="">Infos
                        générales</a>
                    <a data-toggle="tab" data-target="#locatairecontrat" id="locatairecontratlink" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Locataire</a>
                    <a data-toggle="tab" data-target="#financescontrat" id="financescontratlink" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Loyers payés
                    </a>
                </div>
            </div>
        </div>
        <div class="tab-content">

            <div class="tab-content__pane active" id="infosdetailscontrat">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="col-span-3 sm:col-span-3">
                        <label for="descriptif_contrat">Descriptif</label>
                        <input disabled type="text" id="descriptifdetail_contrat" name="descriptif" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="Déscriptif">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="montantloyer_contrat">Montant du loyer</label>
                        <input disabled type="text" id="montantloyerdetail_contrat" name="montantloyer" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="montant">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="montantloyerbase_contrat">Montant du loyer de base</label>
                        <input disabled type="text" id="montantloyerbasedetail_contrat" name="montantloyerbase" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="montant">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="montantloyertom_contrat">Montant du loyer Tom</label>
                        <input disabled type="text" id="montantloyertomdetail_contrat" name="montantloyertom" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="montant">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="montantcharge_contrat">Montant des charges</label>
                        <input disabled type="text" id="montantchargedetail_contrat" name="montantcharge" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="montant">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="tauxrevision_contrat">Taux de revision</label>
                        <input disabled type="number" id="tauxrevisiondetail_contrat" name="tauxrevision" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="taux">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="frequencerevision_contrat">Frequence de revision</label>
                        <input disabled type="number" id="frequencerevisiondetail_contrat" name="frequencerevision" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="frequence">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="dateenregistrement_contrat">Date d'enregistrement</label>
                        <input disabled type="date" id="dateenregistrementdetail_contrat" name="dateenregistrement" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="date">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="datepremierpaiement_contrat">Date du premier paiement</label>
                        <input disabled type="date" id="datepremierpaiementdetail_contrat" name="datepremierpaiement" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="date">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="daterenouvellement_contrat">Date de renouvellement</label>
                        <input disabled type="date" id="daterenouvellementdetail_contrat" name="daterenouvellement" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="date">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="datedebutcontrat_contrat">Date de debut du contrat</label>
                        <input disabled type="date" id="datedebutcontratdetail_contrat" name="datedebutcontrat" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="date">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="categorietuto_immeuble">Type de contrat</label>
                        <input disabled type="text" id="typecontratdetail_contrat" name="typecontrat" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="type">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="typerenouvellement_contrat">Type de renouvellement</label>
                        <input disabled type="text" id="typerenouvellementdetail_contrat" name="typerenouvellement" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="type">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="delaipreavi_contrat">Delai de preavi</label>
                        <input disabled type="text" id="delaipreavidetail_contrat" name="delaipreavi" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="delai">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="appartement_contrat">Appartement</label>
                        <input disabled type="text" id="appartementdetail_contrat" name="appartement" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="appartemment">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="retourcaution_contrat">Retour caution</label>
                        <input disabled type="text" id="retourcaution_contrat" name="retourcaution" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="retourcaution">
                    </div>

                    <div class="col-span-3 sm:col-span-3">
                        {{-- <label for="rappelpaiement_contrat">Date rappel paiement</label>
                        <input disabled type="date" id="rappelpaiementdetail_contrat" name="rappelpaiement" style="background: #f6f7f8" class="input w-full border flex-1" placeholder="date"> --}}
                        <label for="rappelpaiement_contrat">Date de rappel paiement(Jour)</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select disabled class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="rappelpaiementdetail_contrat" name="rappelpaiement">
                                <option value="">Selectionnez</option>
                                <option ng-repeat="item in dataPage['rappelpaiementloyers']" value="@{{ item.id }}">
                                    @{{ item.libelle }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content__pane" id="locatairecontrat">


                <div ng-if="detailContrat.locataire.typelocataire.id == 1" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="prenom_locataire">Prenom</label>
                        <input type="text" disabled style="background: #f6f7f8" id="prenomdetail_locataire" name="prenom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="prenom">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="nom_locataire">Nom</label>
                        <input type="text" disabled style="background: #f6f7f8" id="nomdetail_locataire" name="nom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="nom">
                    </div>

                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="telephoneportable1_locataire">Telephone 1</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephoneportable1detail_locataire" name="telephoneportable1" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="telephoneportable2_locataire">Telephone 2</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephoneportable2detail_locataire" name="telephoneportable2" class="input w-full border mt-2 flex-1" placeholder="telephone 2">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="telephonebureau_locataire">Telephone bureau</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephonebureaudetail_locataire" name="telephonebureau" class="input w-full border mt-2 flex-1" placeholder="telephone bureau">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="email_locataire">Email</label>
                        <input type="text" disabled style="background: #f6f7f8" id="emaildetail_locataire" name="email" class="input w-full border mt-2 flex-1" placeholder="email">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="profession_locataire">Profession</label>
                        <input type="text" disabled style="background: #f6f7f8" id="professiondetail_locataire" name="profession" class="input w-full border mt-2 flex-1" placeholder="profession">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="cni_locataire">CNI</label>
                        <input type="text" disabled style="background: #f6f7f8" id="cnidetail_locataire" name="cni" class="input w-full border mt-2 flex-1" placeholder="cni">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="passeport_locataire">Passeport</label>
                        <input type="text" disabled style="background: #f6f7f8" id="passeportdetail_locataire" name="passeport" class="input w-full border mt-2 flex-1" placeholder="passeport">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="revenu_locataire">Revenus</label>
                        <input type="text" disabled style="background: #f6f7f8" id="revenudetail_locataire" name="revenus" class="required input w-full border mt-2 flex-1" placeholder="revenu">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">Expatrié ou locale</label><br>
                        <input type="text" disabled style="background: #f6f7f8" id="expatdetail_locataire" name="expatdetail_locataire" class="input w-full border mt-2 flex-1">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">Contrat de travail</label><br><br>
                        <a ng-click="redirectPdf(detailContrat.locataire.contrattravail)" style="cursor: pointer ; border-radius: 2px ; padding: 7px ;background-color: #eeeee4 ; height: 40px ; width: 50%">Voir
                            le contrat</a>
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="priseencharge_locataire">Prise en charge</label><br>
                        <input type="text" disabled style="background: #f6f7f8" id="nomcompletpersonnepriseenchargedetail_locataire" name="nomcompletpersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="néant">
                        <input type="text" disabled style="background: #f6f7f8" id="telephonepersonnepriseenchargedetail_locataire" name="telephonepersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="néant">
                    </div>
                </div>

                <div ng-if="detailContrat.locataire.typelocataire.id == 2" class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="nomentreprise_locataire">Nom entreprise</label>
                        <input type="text" disabled style="background: #f6f7f8" id="nomentreprisedetail_locataire" name="nomentreprise" class="input w-full border mt-2 flex-1" placeholder="nom de l'entreprise">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="adresseentreprisedetail_locataire">Adresse</label>
                        <input type="text" disabled style="background: #f6f7f8" id="adresseentreprisedetail_locataire" name="adresseentreprise" class="input w-full border mt-2 flex-1" placeholder="adresse">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="ninea_locataire">Ninea</label>
                        <input type="text" disabled style="background: #f6f7f8" id="nineadetail_locataire" name="ninea" class="input w-full border mt-2 flex-1" placeholder="ninea">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="numerorg_locataire">Numero RG</label>
                        <input type="text" disabled style="background: #f6f7f8" id="numerorgdetail_locataire" name="numerorg" class="input w-full border mt-2 flex-1" placeholder="numerorg">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="personnehabiliteasigner_locataire">Personne habilite a signer</label>
                        <input type="text" disabled style="background: #f6f7f8" id="personnehabiliteasignerdetail_locataire" name="personnehabiliteasigner" class="input w-full border mt-2 flex-1" placeholder="personne habilite a signer">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="fonctionpersonnehabilite_locataire">Fonction personne habilleté a signer</label>
                        <input type="text" disabled style="background: #f6f7f8" id="fonctionpersonnehabilitedetail_locataire" name="fonctionpersonnehabilite" class="input w-full border mt-2 flex-1" placeholder="fonction personne habilite">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="prenompersonneacontacter_locataire">Prenom personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="prenompersonneacontacterdetail_locataire" name="prenompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="prenom personne a contacter">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="nompersonneacontacter_locataire">Nom personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="nompersonneacontacterdetail_locataire" name="nompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="nom personne a contacter">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="emailpersonneacontacter_locataire">Email personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="emailpersonneacontacterdetail_locataire" name="emailpersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="email personne a contacter">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="telephone1personneacontacter_locataire">telephone 1 personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephone1personneacontacterdetail_locataire" name="telephone1personneacontacter" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="telephone2personneacontacter_locataire">Telephone 2 personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephone2personneacontacterdetail_locataire" name="telephone2personneacontacter" class="input w-full border mt-2 flex-1" placeholder="telephone2personneacontacter">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">NINEA</label><br><br>
                        <a ng-click="redirectPdf(detailContrat.locataire.documentninea)" style="cursor: pointer ; border-radius: 2px ; padding: 7px ;background-color: #eeeee4 ; height: 40px ; width: 50%">Voir
                            document</a>
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">Numéro RG</label><br><br>
                        <a ng-click="redirectPdf(detailContrat.locataire.documentnumerorg)" style="cursor: pointer ; border-radius: 2px ; padding: 7px ;background-color: #eeeee4 ; height: 40px ; width: 50%">Voir
                            document</a>
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">Statut</label><br><br>
                        <a ng-click="redirectPdf(detailContrat.locataire.documentstatut)" style="cursor: pointer ; border-radius: 2px ; padding: 7px ;background-color: #eeeee4 ; height: 40px ; width: 50%">Voir
                            document</a>
                    </div>
                </div>
            </div>
            <div class="tab-content__pane" id="financescontrat">
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div ng-repeat="loyer in detailContrat.paiementloyers" class="col-span-2 sm:col-span-2">
                        <input type="text" disabled style="background: #f6f7f8" value="@{{ loyer.periode }}" class="input w-full inline-block relative border mt-2 flex-1 text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- fin modal details contrat-->

<!-- debut modal details locationvente -->
<div class="modal" id="modal_detailslocationvente">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa-file-invoice-dollar"></i>
            <h2 class="font-medium text-base mr-auto">
                Details contrat location vente
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        @csrf
        <div class="intro-y pr-1 mt-1">
            <div class="box p-2 item-tabs-produit">
                <div class="pos__tabs nav-tabs justify-center flex">
                    <a data-toggle="tab" data-target="#infosdetailslocationvente" id="infoslocationventelink" href="javascript:;" class="flex-1 py-2 rounded-md text-center active" title="">Infos
                        générales</a>
                    <a data-toggle="tab" data-target="#locatairelocationvente" id="locatairelocationventelink" href="javascript:;" class="flex-1 py-2 rounded-md text-center" title="">Locataire</a>
                    <a data-toggle="tab" data-target="#financeslocationvente" id="financeslocationventelink" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Loyers payés
                    </a>
                </div>
            </div>
        </div>
        <div class="tab-content">

            <div class="tab-content__pane active" id="infosdetailslocationvente">

                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-3 sm:col-span-3">
                        <label for="appartement_locationvente">Villa</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="appartementdetail_locationvente" disabled name="appartement">
                                <option value="" class="required">Villa</option>
                                {{-- <option value="@{{ item_update['appartement'].id }}" class="required" selected>@{{ item_update['appartement']['nom'] }}</option> --}}
                                <option ng-repeat="item in dataPage['villas']" value="@{{ item.id }}">
                                    lot N°@{{ item.lot }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="categorietuto_immeuble">Périodicité</label>
                        <div class="inline-block relative w-full">
                            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" disabled id="periodicitedetail_locationvente" name="periodicite">
                                <option value="" class="required">Périodicité</option>
                                <option ng-repeat="item in dataPage['periodicites']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="descriptifdetail_locationvente">Prix villa </label>
                        <input type="number" disabled id="prixvilladetail_locationvente" name="prixvilla" class="input w-full border flex-1" placeholder="prix villa">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="dacompteinitialdetail_locationvente">Acompte villa (%)</label>
                        <input type="number" disabled id="acompteinitialdetail_locationvente" name="acompteinitial" class="input w-full border flex-1" placeholder="acompte villa en (%)">
                    </div>
                    @if ($userRole && $userRole != 'Juriste')
                    <div class="col-span-3 sm:col-span-3">
                        <label for="maturitedetail_locationvente">Maturié en année (ans)</label>
                        <input type="number" disabled id="maturitedetail_locationvente" name="maturite" class="input w-full border flex-1" placeholder="maturité">
                    </div>
                    @endif

                    {{-- <div class="col-span-3 sm:col-span-3">
                    <label for="depotinitial_locationvente">Dépot initial</label>
                    <input type="number" id="depotinitial_locationvente" name="depot_initial" class="input w-full border flex-1" placeholder="dépot initial ">
                </div> --}}
                    <div class="col-span-3 sm:col-span-3">
                        <label for="indemnitedetail_locationvente">Intérêts de retard - Indemnité</label>
                        <input type="number" disabled id="indemnitedetail_locationvente" name="indemnite" class="input w-full border flex-1" placeholder="intérêts de retard">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="indemnitedetail_locationvente">Frais et coûts de la location-vente</label>
                        <input type="number" disabled id="fraiscoutlocationventedetail_locationvente" name="fraiscoutlocationvente" class="input w-full border flex-1" placeholder="frais cout location vente">
                    </div>
                    @if ($userRole && $userRole != 'Juriste')
                    <div class="col-span-3 sm:col-span-3">
                        <label for="descriptifdetail_locationvente">Descriptif du contrat</label>
                        <input type="text" id="descriptifdetail_locationvente" name="descriptif" class="input w-full border flex-1" placeholder="Déscriptif">
                    </div>

                    @endif



                    <div class="col-span-3 sm:col-span-3">
                        <label for="apportinitialdetail_locationvente">Apport initial</label>
                        <input type="number" disabled id="apportinitialdetail_locationvente" name="apportinitial" class="input w-full border flex-1" placeholder="apport initial">
                    </div>
                    @if ($userRole && $userRole != 'Juriste')
                    <div class="col-span-3 sm:col-span-3">
                        <label for="apportiponctueldetail_locationvente">Apport ponctuel</label>
                        <input type="number" disabled id="apportiponctueldetail_locationvente" name="apportiponctuel" class="input w-full border flex-1" placeholder="apport ponctuel">
                    </div>



                    <div class="col-span-3 sm:col-span-3">
                        <label for="dureelocationventedetail_contrat">Durée de location vente (ans)</label>
                        <input type="number" disabled id="dureelocationventedetail_locationvente" name="dureelocationvente" class="input w-full border flex-1" placeholder="durée de location vente (ans)">
                    </div>
                    @endif
                    <div class="col-span-3 sm:col-span-3">
                        <label for="clausepenaledetail_contrat">Clause pénale (%)</label>
                        <input type="number" disabled id="clausepenaledetail_locationvente" name="clausepenale" class="input w-full border flex-1" placeholder="clausepenale (%)">
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <label for="dateenregistrementdetail_contrat">Date d'enregistrement</label>
                        <input type="date" disabled id="dateenregistrementdetail_locationvente" name="dateenregistrement" class="input w-full border flex-1" placeholder="date">
                    </div>
                    @if ($userRole && $userRole != 'Juriste')
                    <div class="col-span-3 sm:col-span-3">
                        <label for="dateecheancedetail_contralocationventet">Date d'échéance</label>
                        <input type="date" disabled id="dateecheancedetail_locationvente" name="dateecheance" class="input w-full border flex-1" placeholder="date d'échéance">
                    </div>
                    @endif

                    <div class="col-span-3 sm:col-span-3">
                        <label for="dateremiseclesdetail_locationvente">Date de remise des clés</label>
                        <input type="date" disabled id="dateremiseclesdetail_locationvente" name="dateremisecles" class="input w-full border flex-1" placeholder="date">
                    </div>

                    <div class="col-span-3 sm:col-span-3">
                        <label for="datedebutcontratdetail_locationvente">Date de début du contrat</label>
                        <input type="date" disabled id="datedebutcontratdetail_locationvente" name="datedebutcontrat" class="input w-full border flex-1" placeholder="date">
                    </div>

                    <div class="col-span-3 sm:col-span-3">
                        <label for="categorietuto_immeuble">Type de contrat</label>
                        <div class="inline-block relative w-full">
                            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="typecontratdetail_locationvente" disabled name="typecontrat">
                                <option value="" class="required">Type</option>
                                <option ng-repeat="item in dataPage['typecontrats']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>
                    @if ($userRole && $userRole != 'Juriste')
                    <div class="col-span-3 sm:col-span-3">
                        <label for="rappelpaiement_contrat">Date de rappel paiement(Jour)</label>
                        {{-- <input type="date" id="rappelpaiement_contrat" name="rappelpaiement" class="input w-full border flex-1" placeholder="date"> --}}
                        <div class="inline-block mt-2 relative w-full">
                            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="rappelpaiementdetail_locationvente" disabled name="rappelpaiement">
                                <option value="">Selectionnez</option>
                                <option ng-repeat="item in dataPage['rappelpaiementloyers']" value="@{{ item.id }}">
                                    @{{ item.libelle }}
                                </option>
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-span-3 sm:col-span-3">
                        <label for="delaipreavi_contrat">Delai de preavi</label>
                        <div class="inline-block mt-2 relative w-full">
                            <select disabled class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="delaipreavidetail_locationvente" name="delaipreavi">
                                <option value="">Delai</option>
                                <option ng-repeat="item in dataPage['delaipreavis']" value="@{{ item.id }}">
                                    @{{ item.designation }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                    </div>
                </div>
            </div>
            <div class="tab-content__pane" id="locatairelocationvente">


                <div ng-if="detailContrat.locataire.typelocataire.id == 1" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="prenom_locataire">Prenom</label>
                        <input type="text" disabled style="background: #f6f7f8" id="prenomdetail_locationvente" name="prenom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="prenom">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="nom_locataire">Nom</label>
                        <input type="text" disabled style="background: #f6f7f8" id="nomdetail_locationvente" name="nom" class="input w-full inline-block relative border mt-2 flex-1" placeholder="nom">
                    </div>

                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="telephoneportable1_locataire">Telephone 1</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephoneportable1detail_locationvente" name="telephoneportable1" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="telephoneportable2_locataire">Telephone 2</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephoneportable2detail_locationvente" name="telephoneportable2" class="input w-full border mt-2 flex-1" placeholder="telephone 2">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="telephonebureau_locataire">Telephone bureau</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephonebureaudetail_locationvente" name="telephonebureau" class="input w-full border mt-2 flex-1" placeholder="telephone bureau">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="email_locataire">Email</label>
                        <input type="text" disabled style="background: #f6f7f8" id="emaildetail_locationvente" name="email" class="input w-full border mt-2 flex-1" placeholder="email">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="profession_locataire">Profession</label>
                        <input type="text" disabled style="background: #f6f7f8" id="professiondetail_locationvente" name="profession" class="input w-full border mt-2 flex-1" placeholder="profession">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="cni_locataire">CNI</label>
                        <input type="text" disabled style="background: #f6f7f8" id="cnidetail_locationvente" name="cni" class="input w-full border mt-2 flex-1" placeholder="cni">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="passeport_locataire">Passeport</label>
                        <input type="text" disabled style="background: #f6f7f8" id="passeportdetail_locationvente" name="passeport" class="input w-full border mt-2 flex-1" placeholder="passeport">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="revenu_locataire">Revenus</label>
                        <input type="text" disabled style="background: #f6f7f8" id="revenudetail_locationvente" name="revenus" class="required input w-full border mt-2 flex-1" placeholder="revenu">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">Expatrié ou locale</label><br>
                        <input type="text" disabled style="background: #f6f7f8" id="expatdetail_locationvente" name="expatdetail_locataire" class="input w-full border mt-2 flex-1">
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">Contrat de travail</label><br><br>
                        <a ng-click="redirectPdf(detailContrat.locataire.contrattravail)" style="cursor: pointer ; border-radius: 2px ; padding: 7px ;background-color: #eeeee4 ; height: 40px ; width: 50%">Voir
                            le contrat</a>
                    </div>
                    <div class="1 col-span-3 sm:col-span-3">
                        <label for="priseencharge_locataire">Prise en charge</label><br>
                        <input type="text" disabled style="background: #f6f7f8" id="nomcompletpersonnepriseenchargedetail_locationvente" name="nomcompletpersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="néant">
                        <input type="text" disabled style="background: #f6f7f8" id="telephonepersonnepriseenchargedetail_locationvente" name="telephonepersonnepriseencharge" class="input w-full border mt-2 flex-1" placeholder="néant">
                    </div>
                </div>

                <div ng-if="detailContrat.locataire.typelocataire.id == 2" class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="nomentreprise_locataire">Nom entreprise</label>
                        <input type="text" disabled style="background: #f6f7f8" id="nomentreprisedetail_locataire" name="nomentreprise" class="input w-full border mt-2 flex-1" placeholder="nom de l'entreprise">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="adresseentreprisedetail_locataire">Adresse</label>
                        <input type="text" disabled style="background: #f6f7f8" id="adresseentreprisedetail_locataire" name="adresseentreprise" class="input w-full border mt-2 flex-1" placeholder="adresse">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="ninea_locataire">Ninea</label>
                        <input type="text" disabled style="background: #f6f7f8" id="nineadetail_locataire" name="ninea" class="input w-full border mt-2 flex-1" placeholder="ninea">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="numerorg_locataire">Numero RG</label>
                        <input type="text" disabled style="background: #f6f7f8" id="numerorgdetail_locataire" name="numerorg" class="input w-full border mt-2 flex-1" placeholder="numerorg">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="personnehabiliteasigner_locataire">Personne habilite a signer</label>
                        <input type="text" disabled style="background: #f6f7f8" id="personnehabiliteasignerdetail_locataire" name="personnehabiliteasigner" class="input w-full border mt-2 flex-1" placeholder="personne habilite a signer">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="fonctionpersonnehabilite_locataire">Fonction personne habilleté a signer</label>
                        <input type="text" disabled style="background: #f6f7f8" id="fonctionpersonnehabilitedetail_locataire" name="fonctionpersonnehabilite" class="input w-full border mt-2 flex-1" placeholder="fonction personne habilite">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="prenompersonneacontacter_locataire">Prenom personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="prenompersonneacontacterdetail_locataire" name="prenompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="prenom personne a contacter">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="nompersonneacontacter_locataire">Nom personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="nompersonneacontacterdetail_locataire" name="nompersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="nom personne a contacter">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="emailpersonneacontacter_locataire">Email personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="emailpersonneacontacterdetail_locataire" name="emailpersonneacontacter" class="input w-full border mt-2 flex-1" placeholder="email personne a contacter">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="telephone1personneacontacter_locataire">telephone 1 personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephone1personneacontacterdetail_locataire" name="telephone1personneacontacter" class="input w-full border mt-2 flex-1" placeholder="telephone 1">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="telephone2personneacontacter_locataire">Telephone 2 personne a contacter</label>
                        <input type="text" disabled style="background: #f6f7f8" id="telephone2personneacontacterdetail_locataire" name="telephone2personneacontacter" class="input w-full border mt-2 flex-1" placeholder="telephone2personneacontacter">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">NINEA</label><br><br>
                        <a ng-click="redirectPdf(detailContrat.locataire.documentninea)" style="cursor: pointer ; border-radius: 2px ; padding: 7px ;background-color: #eeeee4 ; height: 40px ; width: 50%">Voir
                            document</a>
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">Numéro RG</label><br><br>
                        <a ng-click="redirectPdf(detailContrat.locataire.documentnumerorg)" style="cursor: pointer ; border-radius: 2px ; padding: 7px ;background-color: #eeeee4 ; height: 40px ; width: 50%">Voir
                            document</a>
                    </div>
                    <div class="2 col-span-3 sm:col-span-3">
                        <label for="expat_locataire">Statut</label><br><br>
                        <a ng-click="redirectPdf(detailContrat.locataire.documentstatut)" style="cursor: pointer ; border-radius: 2px ; padding: 7px ;background-color: #eeeee4 ; height: 40px ; width: 50%">Voir
                            document</a>
                    </div>
                </div>
            </div>
            <div class="tab-content__pane" id="financeslocationvente">
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div ng-repeat="loyer in detailContrat.paiementloyers" class="col-span-2 sm:col-span-2">
                        <input type="text" disabled style="background: #f6f7f8" value="@{{ loyer.periode }}" class="input w-full inline-block relative border mt-2 flex-1 text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- fin modal details locationvente-->

<!-- debut modal appartement -->
<div class="modal" id="modal_addappartement">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-building mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Appartement / Villa
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

        </div>
        <div class="py-5 px-3 modal__content_body  border-raduis-top">
            <form id="form_addappartement" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'appartement')" style="max-height: 80vh!important;overflow: auto">
                @csrf
                <input type="hidden" id="id_appartement" name="id">
                <input type="hidden" id="immeuble_appartement_id" name="immeuble_id">

                <div class="intro-y pr-1 mt-1">
                    <div class="box p-2 item-tabs-produit">
                        <div class="pos__tabs nav-tabs justify-center flex">
                            <a data-toggle="tab" data-target="#infoappartement" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos</a>
                            <a data-toggle="tab" data-target="#geranceappartement" href="javascript:;" class="flex-1 py-2 rounded-md text-center ">Gerance</a>
                            <a data-toggle="tab" data-target="#composition" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Composotion</a>
                            <a data-toggle="tab" data-target="#equipementgenerale" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Equipements generales</a>
                            <a data-toggle="tab" data-target="#documentsappartement" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Documents</a>
                            <a data-toggle="tab" data-target="#images" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Images</a>
                        </div>
                    </div>
                </div>

                <div class="tab-content">

                    <div class="tab-content__pane active" id="infoappartement">
                        <div class="px-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class=" col-span-12 sm:col-span-12">
                                <label for="" class="font-bold mb-2 pb-2">EXTERNE ?</label><br />
                                <input class="input input--switch ml-auto border w-full border flex-1 ml-3 mt-3" name="position" id="position_appartement" type="checkbox">
                            </div>
                        </div>

                        <div class="px-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-12 sm:col-span-12">
                                <label for="categorietuto_typetuto">Programme</label>
                                <div class="inline-block relative w-full">
                                    <select onchange="typeEntite(this)" class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="entite_appartement" name="entite">
                                        <option value="" class="required">Programme</option>
                                        <option ng-repeat="item in dataPage['entites']" value="@{{ item.code }}">
                                            @{{ item.designation }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            {{-- 1 start --}}


                            <div class="1 col-span-3 sm:col-span-3">
                                <label for="nom_appartement">Désignation</label>
                                <input type="text" id="nom_appartement" name="nom" class="input w-full border flex-1" placeholder="Désignation">
                            </div>
                            <div class="1 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Immeuble</label>
                                <div class="inline-block relative w-full">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="immeuble_appartement" name="immeuble">
                                        <option value="" class="">Immeuble</option>
                                        <option ng-repeat="item in dataPage['immeubles']" value="@{{ item.id }}">
                                            @{{ item.nom }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="1 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Niveau dans l'immeuble</label>
                                <div class="inline-block relative w-full">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="niveau_appartement" name="niveau">
                                        <option value="" class="required">niveau</option>

                                    </select>
                                </div>
                            </div>
                            <div class="1 col-span-3 sm:col-span-3">
                                <label for="superficie_appartement">Superficie (en mettre carrée)</label>
                                <input type="text" id="superficie_appartement" name="superficie" class="input w-full border flex-1" placeholder="en mettre carrée">
                            </div>
                            <div class="1 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Type d'appartement</label>
                                <div class="inline-block relative w-full">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="typeappartement_appartement" name="typeappartement">
                                        <option value="" class="required">Type</option>
                                        <option ng-repeat="item in dataPage['typeappartements']" value="@{{ item.id }}">
                                            @{{ item.designation }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="1 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Frenquence de paiement</label>
                                <div class="inline-block relative w-full">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="frequencepaiementappartement_appartement" name="frequencepaiementappartement">
                                        <option value="" class="required">Type</option>
                                        <option ng-repeat="item in dataPage['frequencepaiementappartements']" value="@{{ item.id }}">
                                            @{{ item.designation }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="1 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Etat de l'appartement</label>
                                <div class="inline-block relative w-full">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="etatappartement_appartement" name="etatappartement">
                                        <option value="" class="required">Etat</option>
                                        <option ng-repeat="item in dataPage['etatappartements']" value="@{{ item.id }}">
                                            @{{ item.designation }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="1 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Choix type de vente</label>
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="typevente_appartement" name="typevente">
                                    <option value="" class="required">Type</option>
                                    <option value=1 class="required">Vente</option>
                                    <option value=2 class="required">Location</option>
                                </select>
                            </div>
                            {{-- <div class="1 col-span-3 sm:col-span-3" id="div_montantvilla_appartement">
                            <label for="categorietuto_typetuto">Montant villa</label>
                            <input type="number" id="montantvilla_appartement" name="montantvilla" class="input w-full border mt-2 flex-1" placeholder="Montant villa">
                        </div> --}}
                            <div class="1 col-span-3 sm:col-span-3" id="div_prix_appartement">
                                <label for="categorietuto_typetuto">Prix appartement</label>
                                <input type="number" id="prixappartement_appartement" name="prixappartement" class="input w-full border mt-2 flex-1" placeholder="Prix appartement">
                            </div>
                            {{-- 1 fin --}}
                            {{-- 2 start --}}
                            <div class="2 col-span-3 sm:col-span-3">
                                <label for="lot_appartement">Lot</label>
                                <input type="text" id="lot_appartement" name="lot" class="input w-full border flex-1" placeholder="Lot">
                            </div>
                            <div class="2 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Ilot</label>
                                <div class="inline-block relative w-full">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="ilot_appartement" name="ilot">
                                        <option value="" class="required">Ilot</option>
                                        <option ng-repeat="item in dataPage['ilots']" value="@{{ item.id }}">
                                            @{{ item.numero }} / @{{ item.adresse }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="2 col-span-3 sm:col-span-3">
                                <label for="superficievilla_appartement">Superficie (en mettre carrée)</label>
                                <input type="text" id="superficievilla_appartement" name="superficievilla" class="input w-full border flex-1" placeholder="en mettre carrée">
                            </div>

                            <div class="2 col-span-3 sm:col-span-3">
                                <label for="superficie_appartement">Prix villa</label>
                                <input type="number" id="prixvilla_appartement" name="prixvilla" class="input w-full border flex-1" placeholder="Prix villa">
                            </div>

                            <div class="2 col-span-3 sm:col-span-3">
                                <label for="superficie_appartement">Maturité en anneés</label>
                                <input type="number" id="maturite_appartement" name="maturite" class="input w-full border flex-1" placeholder="maturite">
                            </div>
                            <div class="2 col-span-3 sm:col-span-3">
                                <label for="superficie_appartement">Acompte</label>
                                <input type="number" id="acomptevilla_appartement" name="acomptevilla" class="input w-full border flex-1" placeholder="acomptevilla">
                            </div>

                            <div class="2 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Type de villa</label>
                                <div class="inline-block relative w-full">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="typevilla_appartement" name="typevilla">
                                        <option value="" class="required">Type</option>
                                        <option ng-repeat="item in dataPage['typeappartements']" value="@{{ item.id }}">
                                            @{{ item.designation }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="2 col-span-3 sm:col-span-3">
                                <label for="categorietuto_typetuto">Périodicité</label>
                                <div class="inline-block relative w-full">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="periodicite_appartement" name="periodicite">
                                        <option value="" class="required">Périodicité</option>
                                        <option ng-repeat="item in dataPage['periodicites']" value="@{{ item.id }}">
                                            @{{ item.designation }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- 2 fin --}}
                            <div class="col-span-12 sm:col-span-12 md:col-span-12 text-center">
                                <div class="mt-4" style="font-size: 13px!important;">Plan de l'appartement / villa
                                </div>
                                <div class="form-group text-center class-form">
                                    <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->
                                    <div>
                                        <label for="imgappartement" class="cursor-pointer">
                                            <img id="affimgappartement" src="{{ asset('assets/images/upload.jpg') }}" alt="..." class="image-hover shadow" style="width: 250px;height: 250px;border-radius: 10%!important;margin: 0 auto">
                                            <div style="display: none;">
                                                <input type="file" accept='image/*' id="imgappartement" name="image" onchange='Chargerimage("appartement")' class="required">
                                                <input type="hidden" id="erase_imgappartement" name="image_erase" value="">
                                            </div>
                                        </label>
                                    </div>
                                    <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile('imgappartement')">
                                        <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-span-6 sm:col-span-6">
                            </div>

                            <!--                  <div class="col-span-3 sm:col-span-3 mt-1">
                                                  <div>Une assurance est-elle fournie ?  </div>
                                                  <label>
                                                      <input required="" name="assurance" value="Oui" type="radio"/>
                                                      <span>Oui</span>
                                                  </label>

                                                  <label>
                                                      <input name="assurance" value="Non" type="radio"/>
                                                      <span>Non</span>
                                                  </label>
                                              </div>

                                              <div class="col-span-3 sm:col-span-3">
                                                  <label for="designation_appartements_immeuble" class="infosAssuranceJoint" style="display: none;">Date d&eacute;but assurance</label>
                                                  <input id="infosAssurance" style="display: none;" name="infosAssurance" type="date" class="input w-full border mt-2 flex-1" placeholder="Date d&eacute;but assurance">
                                              </div>
                                              <div class="col-span-3 sm:col-span-3">
                                                  <label for="infosAssurance2" class="infosAssuranceJoint" style="display: none;">Date fin assurance</label>
                                                  <input id="infosAssurance2" style="display: none;" name="infosAssurance" type="date" class="input w-full border mt-2 flex-1" placeholder="Date fin assurance">
                                              </div>
                                              <div class="col-span-3 sm:col-span-3 mt-1" >
                                                  <label for="infosAssurance3" class="infosAssuranceJoint" style="display: none;">Joindre le scan de l'assurance</label>
                                                  <input type="file" id="infosAssurance3" style="display: none;" name="" class="input w-full border flex-1" placeholder="Joindre le scan de l'assurance">
                                              </div>

                                              <div class="col-span-3 sm:col-span-3 mt-1">
                                                  <div>Le contrat est-il enregistr&eacute; ?  </div>
                                                  <label>
                                                      <input required="" name="enregistrementcontrat" value="Oui" type="radio"/>
                                                      <span>Oui</span>
                                                  </label>

                                                  <label>
                                                      <input name="enregistrementcontrat" value="Non" type="radio"/>
                                                      <span>Non</span>
                                                  </label>
                                              </div>

                                              <div class="col-span-3 sm:col-span-3">
                                                  <label for="infosEnregistrementcontrat" class="infosEnregistrementcontratJoint" style="display: none;">Date d'enregistrement</label>
                                                  <input id="infosEnregistrementcontrat" style="display: none;" name="infosAssurance" type="date" class="input w-full border mt-2 flex-1" placeholder="Date d'enregistrement">
                                              </div>
                                              <div class="col-span-3 sm:col-span-3">
                                                  <label for="infosEnregistrementcontrat" class="infosEnregistrementcontratJoint" style="display: none;">Date de renouvellement</label>
                                                  <input id="infosEnregistrementcontrat2" style="display: none;" name="infosAssurance" type="date" class="input w-full border mt-2 flex-1" placeholder="Date de renouvellement">
                                              </div>
                      -->
                            <div class="col-span-3 sm:col-span-3 mt-1">
                                <label for="infosEnregistrementcontrat3" class="infosEnregistrementcontratJoint" style="display: none;">Joindre le scan de l'enregistrement</label>
                                <input type="file" id="infosEnregistrementcontrat3" style="display: none;" name="" class="input w-full border flex-1" placeholder="Joindre le scan de l'enregistrement">
                            </div>

                        </div>

                    </div>
                    <div class="tab-content__pane " id="composition">

                        <div ng-if="detailspiece" class="divapp intro-y pr-1 mt-1">
                            <div class="box p-2 item-tabs-produit">
                                <div class="pos__tabs nav-tabs justify-center flex">
                                    <a ng-repeat="detailpiece in detailspiece" data-toggle="tab" data-target="#piece_@{{ detailpiece.id }}" href="javascript:;" class="flex-1 py-2 rounded-md text-center">@{{ detailpiece.typepiece.designation }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div ng-if="detailspiece" class="divapp tab-content">
                            <input type="hidden" name="imagesource" value="{{ asset('assets/images/upload.jpg') }}" id="imagesource" />
                            <input type="hidden" id="compteurimage_appartement" name="compteurimage">


                            <div ng-repeat="detailpiece in detailspiece" class="tab-content__pane" id="piece_@{{ detailpiece.id }}">

                                <input type="hidden" id="id_detailpiece@{{ detailpiece.id }}" name="iddetailpiece@{{ detailpiece.id }}">

                                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                                    <div class="col-span-3 sm:col-span-3">
                                        <label for="superficie_appartement">Superficie (en mettre carrée)</label>
                                        <input type="text" id="superficiecomposition_@{{ detailpiece.id }}_appartement" name="superficiecomposition_@{{ detailpiece.id }}" class="input w-full border flex-1" placeholder="en mettre carrée">
                                    </div>
                                    <div class="2 col-span-5 sm:col-span-5">
                                        <label for="categorietuto_typetuto">Niveau de la piece (<em>à remplir si c'est Ridwan</em>) </label>
                                        <div class="inline-block relative w-full">
                                            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="niveaupiece_@{{ detailpiece.id }}_appartement" name="niveaupiece_@{{ detailpiece.id }}">
                                                <option value="" class="required">Niveau</option>
                                                <option ng-repeat="item in dataPage['niveauappartements']" value="@{{ item.id }}">
                                                    @{{ item.designation }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-section pl-5 pt-3  text-xl">
                                    <i class="fa fa-info-circle text-blue-400"></i> Photo de la piece
                                </div>
                                <div id="photopieceappartement@{{ detailpiece.id }}" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                                    <div class="col-span-12 sm:col-span-12 text-right">
                                        <button type="button" class="button w-10 bg-theme-101 text-white" ng-click="addfields('photo_piece',detailpiece.id)"><span class="fa fa-plus"></span></button>
                                    </div>
                                </div>

                                <div class="form-section pl-5 pt-3  text-xl">
                                    <i class="fa fa-info-circle text-blue-400"></i> Equipements
                                </div>
                                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                                    <div class="col-span-10 sm:col-span-10">
                                        <label for="produit_logistique_proforma">Equipements</label>
                                        <div class="col-span-12 sm:col-span-12">
                                            <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="equipement@{{ detailpiece.id }}typeappartement_piece_equipepementpiece_typeappartement_piece_appartement" style="width: 100% !important;">
                                                <option value="">equipement</option>
                                                <option ng-repeat="item in dataPage['equipementpieces']" ng-if="item.generale == 0" value="@{{ item.id }}">
                                                    @{{ item.designation }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-span-2 sm:col-span-2 text-right">
                                        <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="actionSurTabPaneAppartement('add','typeappartement_piece_equipepementpiece_typeappartement_piece_appartement',detailpiece.id)"><span class="fa fa-plus"></span></button>
                                    </div>

                                    <div class="col-span-12 sm:col-span-12">
                                        <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                            <table class="table table-report sm:mt-1">
                                                <thead>
                                                    <tr class="bg-theme-101 text-white">
                                                        <th hidden class="whitespace-no-wrap">#</th>
                                                        <th class="whitespace-no-wrap">Equipement</th>
                                                        <!--                                        <th class="whitespace-no-wrap text-center">Etat</th>-->
                                                        <th class="text-center whitespace-no-wrap">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-if="item.detailId == detailpiece.id " class="intro-x" ng-repeat="item in dataInTabPane['typeappartement_piece_equipepementpiece_typeappartement_piece_appartement']['data']">

                                                        <td hidden class="">
                                                            <div class="font-medium whitespace-no-wrap">
                                                                @{{ item.id }}</div>
                                                        </td>

                                                        <td class="">
                                                            <div class="font-medium whitespace-no-wrap">
                                                                @{{ item.equipement_text }}</div>
                                                        </td>

                                                        <!--                                        <td class="">-->
                                                        <!---->
                                                        <!--                                            <div class="font-medium whitespace-no-wrap text-center">-->
                                                        <!---->
                                                        <!--                                                <span ng-if="item.etat == 0" class="px-2 rounded-full bg-danger text-white font-medium text-center">desactivé</span>-->
                                                        <!--                                                <span ng-if="item.etat == 1" class="px-2 rounded-full bg-success text-white font-medium text-center">activé</span>-->
                                                        <!---->
                                                        <!--                                            </div>-->
                                                        <!--                                        </td>-->

                                                        <td class="table-report__action w-56">
                                                            <nav class="menu-leftToRight uk-flex text-center">
                                                                <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                                                <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                                    <span class="hamburger bg-template-1 hamburger-1"></span>
                                                                    <span class="hamburger bg-template-1 hamburger-2"></span>
                                                                    <span class="hamburger bg-template-1 hamburger-3"></span>
                                                                </label>

                                                                <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('delete', 'typeappartement_piece_equipepementpiece_typeappartement_piece_appartement', $index)" title="Supprimer">
                                                                    <span class="fa fa-trash-alt"></span>
                                                                </button>
                                                                <button type="button" class="menu-item btn border-0 bg-success text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('update', 'typeappartement_piece_equipepementpiece_typeappartement_piece_appartement', $index, '', null, 'etat', 1)" ng-if="item.etat == 0" title="Activé">
                                                                    <span class="fa fa-thumbs-up"></span>
                                                                </button>
                                                                <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('update', 'typeappartement_piece_equipepementpiece_typeappartement_piece_appartement', $index, '', null, 'etat', 0)" ng-if="item.etat == 1" title="Desactivé">
                                                                    <span class="fa fa-thumbs-down"></span>
                                                                </button>

                                                            </nav>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="tab-content__pane " id="equipementgenerale">

                        <div class="form-section pl-5 pt-3  text-xl">
                            <i class="fa fa-info-circle text-blue-400"></i> Equipemments générales
                        </div>
                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-10 sm:col-span-10">
                                <label for="produit_logistique_proforma">Equipements</label>
                                <div class="col-span-12 sm:col-span-12">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="equipement@{{ detailpiece.id }}typeappartement_piece_equipepementpiece_typeappartement_piece_appartement" style="width: 100% !important;">
                                        <option value="">equipement</option>
                                        <option ng-repeat="item in dataPage['equipementpieces']" ng-if="item.generale == 1" value="@{{ item.id }}">
                                            @{{ item.designation }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-span-2 sm:col-span-2 text-right">
                                <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="actionSurTabPaneAppartement('add','typeappartement_piece_equipepementpiece_typeappartement_piece_appartement',detailpiece.id)"><span class="fa fa-plus"></span></button>
                            </div>

                            <div class="col-span-12 sm:col-span-12">
                                <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                    <table class="table table-report sm:mt-1">
                                        <thead>
                                            <tr class="bg-theme-101 text-white">
                                                <th hidden class="whitespace-no-wrap">#</th>
                                                <th class="whitespace-no-wrap">Equipement</th>
                                                <!--                                        <th class="whitespace-no-wrap text-center">Etat</th>-->
                                                <th class="text-center whitespace-no-wrap">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-if="!item.detailId" class="intro-x" ng-repeat="item in dataInTabPane['typeappartement_piece_equipepementpiece_typeappartement_piece_appartement']['data']">

                                                <td hidden class="">
                                                    <div class="font-medium whitespace-no-wrap">@{{ item.id }}
                                                    </div>
                                                </td>

                                                <td class="">
                                                    <div class="font-medium whitespace-no-wrap">@{{ item.equipement_text }}
                                                    </div>
                                                </td>

                                                <!--                                        <td class="">-->
                                                <!---->
                                                <!--                                            <div class="font-medium whitespace-no-wrap text-center">-->
                                                <!---->
                                                <!--                                                <span ng-if="item.etat == 0" class="px-2 rounded-full bg-danger text-white font-medium text-center">desactivé</span>-->
                                                <!--                                                <span ng-if="item.etat == 1" class="px-2 rounded-full bg-success text-white font-medium text-center">activé</span>-->
                                                <!---->
                                                <!--                                            </div>-->
                                                <!--                                        </td>-->

                                                <td class="table-report__action w-56">
                                                    <nav class="menu-leftToRight uk-flex text-center">
                                                        <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                                        <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                            <span class="hamburger bg-template-1 hamburger-1"></span>
                                                            <span class="hamburger bg-template-1 hamburger-2"></span>
                                                            <span class="hamburger bg-template-1 hamburger-3"></span>
                                                        </label>

                                                        <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('delete', 'typeappartement_piece_equipepementpiece_typeappartement_piece_appartement', $index)" title="Supprimer">
                                                            <span class="fa fa-trash-alt"></span>
                                                        </button>
                                                        <button type="button" class="menu-item btn border-0 bg-success text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('update', 'typeappartement_piece_equipepementpiece_typeappartement_piece_appartement', $index, '', null, 'etat', 1)" ng-if="item.etat == 0" title="Activé">
                                                            <span class="fa fa-thumbs-up"></span>
                                                        </button>
                                                        <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTypeAppart('update', 'typeappartement_piece_equipepementpiece_typeappartement_piece_appartement', $index, '', null, 'etat', 0)" ng-if="item.etat == 1" title="Desactivé">
                                                            <span class="fa fa-thumbs-down"></span>
                                                        </button>

                                                    </nav>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="tab-content__pane " id="geranceappartement">

                        <div class="form-section pl-5 pt-3  text-xl">
                            <i class="fa fa-info-circle text-blue-400"></i> Gerance
                        </div>

                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                            <div class="col-span-6 sm:col-span-6">
                                <label for="">Proprietaire</label>
                                <div class="col-span-12 sm:col-span-12">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_proprietaire" id="proprietaire_appartement" style="width: 100% !important;" name="proprietaire">
                                        <option value="">Proprietaire</option>
                                        <option ng-repeat="item in dataPage['proprietaires']" value="@{{ item.id }}">
                                            @{{ item.prenom }} @{{ item.nom }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-span-6 sm:col-span-6">
                                <label for="">Contrat Proprietaire</label>
                                <div class="col-span-12 sm:col-span-12">
                                    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="contratproprietaire_id_appartement" style="width: 100% !important;" name="contratproprietaire_id">
                                        <option value="">Contrat proprietaire</option>
                                        <option ng-repeat="item in dataPage['contratproprietaires']" value="@{{ item.id }}">
                                            @{{ item.proprietaire.prenom }} @{{ item.proprietaire.nom }} @{{ item.entite.designation }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-span-6 sm:col-span-6">
                                <label for="commissionvaleur_appartement">Valeur Commission</label>
                                <input type="number" id="commissionvaleur_appartement" name="commissionvaleur" class="input w-full border mt-2 flex-1" placeholder="Valeur Commission">
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="commissionpourcentage_appartement">Pourcentage Commission</label>
                                <input type="number" id="commissionpourcentage_appartement" name="commissionpourcentage" class="input w-full border mt-2 flex-1" placeholder="Pourcentage Commission">
                            </div>
                            <div class="col-span-6 sm:col-span-6">
                                <label for="">Montant Loyer</label><br />
                                <input type="number" id="montantloyer_appartement" name="montantloyer" class="input w-full border mt-2 flex-1" placeholder="Montant Loyer">
                            </div>
                            <div class="col-span-6 sm:col-span-6">
                                <label for="">Montant Caution</label><br />
                                <input type="number" id="montantcaution_appartement" name="montantcaution" class="input w-full border mt-2 flex-1" placeholder="Montant Caution">
                            </div>
                            <div class="col-span-2 sm:col-span-2">
                                <label for="" class="font-bold mb-2 pb-2">TVA</label><br />
                                <input class="input input--switch ml-auto border w-full border flex-1 ml-3 mt-3" name="tva" id="tva_appartement" type="checkbox">
                            </div>
                            <div class="col-span-2 sm:col-span-2">
                                <label for="" class="font-bold mb-2 pb-2">BRS</label><br />
                                <input class="input input--switch ml-auto border w-full border flex-1 ml-3 mt-3" name="brs" id="brs_appartement" type="checkbox">
                            </div>
                            <div class="col-span-2 sm:col-span-2">
                                <label for="" class="font-bold mb-2 pb-2">TLV</label><br />
                                <input class="input input--switch ml-auto border w-full border flex-1 ml-3 mt-3" name="tlv" id="tlv_appartement" type="checkbox">
                            </div>


                        </div>
                    </div>

                    {{-- Document start --}}
                    <div class="tab-content__pane" id="documentsappartement">
                        <div class="form-section pl-5 pt-3  text-xl">
                            <i class="fa fa-info-circle text-blue-400"></i>Documents
                        </div>
                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                            <div class="col-span-3 sm:col-span-3">
                                <label for="numero_document_appartement">Numéro du document</label>
                                <input type="number" id="numero_document_appartement" name="numero" class="input w-full border mt-2 flex-1" placeholder="numéro du document">
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                <label for="nom_document_appartement">Nom du document</label>
                                <input type="text" id="nom_document_appartement" name="nomdocument" class="input w-full border mt-2 flex-1" placeholder="nom du document">
                            </div>
                            <div class="col-span-3 sm:col-span-3 mt-4">
                                <label>Joindre le document</label><br>
                                <input type="file" accept=".csv, .pdf" class="form-control filestyle required" data-buttonName="btn-shadow btn-transition btn-outline-danger p-2" data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi" id="fichier_document_appartement" name="fichier" data-iconName="fa fa-folder-open">

                            </div>
                            <div class="col-span-2 sm:col-span-2 mt-4">
                                <button ng-click="actionSurTabPaneTagData('add','document_appartement')" type="button" class="btn btn-primay bg-primary text-white button mt-2 " title="pdf">
                                    <span class="fas fa-plus"></span>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                <table class="table table-report sm:mt-1">
                                    <thead>
                                        <tr class="bg-theme-101 text-white">
                                            <th class="whitespace-no-wrap">Fichier</th>
                                            <th class="whitespace-no-wrap text-center">Numéro</th>
                                            <th class="whitespace-no-wrap text-center">Nom</th>

                                            <th class="text-center whitespace-no-wrap">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataInTabPane['document_appartement']['data']">

                                            <td class="">

                                                <div class="font-medium whitespace-no-wrap">
                                                    <a href="javascript:void(0)" class="open-file-link" data-file-url="@{{ item.fichier }}" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">
                                                        voir fichier
                                                    </a>
                                                </div>
                                            </td>


                                            <td class="">
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.numero }}
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    @{{ item.nom }}
                                                </div>
                                            </td>

                                            <td class="table-report__action w-56">
                                                <nav class="menu-leftToRight uk-flex text-center">
                                                    <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open1-@{{ $index }}">
                                                    <label class="menu-open-button bg-white" for="menu-open1-@{{ $index }}">
                                                        <span class="hamburger bg-template-1 hamburger-1"></span>
                                                        <span class="hamburger bg-template-1 hamburger-2"></span>
                                                        <span class="hamburger bg-template-1 hamburger-3"></span>
                                                    </label>

                                                    <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'document_appartement', $index)" title="Supprimer">
                                                        <span class="fa fa-trash-alt"></span>
                                                    </button>
                                                </nav>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    {{-- Document end --}}

                    <div class="tab-content__pane " id="images">

                        <div class="form-section pl-5 pt-3  text-xl">
                            <i class="fa fa-info-circle text-blue-400"></i> Photo de l'appartement
                        </div>
                        <div id="photoappartement" class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <input type="hidden" id="compteurimage2_appartement" name="compteurimage2">
                            <div class="col-span-12 sm:col-span-12 text-right">
                                <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="addfields('photo_appartement')"><span class="fa fa-plus"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                    <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- fin modal appartement -->


<!-- debut modal villa -->

<!-- fin modal appartement -->

<!-- debut modal entite -->
<div class="modal" id="modal_addentite">
    <div class="modal__content modal__content--lg">

        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Programme
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <form id="form_addentite" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'entite')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_entite" name="id">

            <div class="intro-y pr-1 mt-1">
                <div class="box p-2 item-tabs-produit">
                    <div class="pos__tabs nav-tabs justify-center flex">
                        <a data-toggle="tab" data-target="#infosentite" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">Infos</a>
                        <a data-toggle="tab" data-target="#notaire" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Notaire</a>
                        <a data-toggle="tab" data-target="#bancaire" href="javascript:;" class="flex-1 py-2 rounded-md text-center">Infos bancaires</a>

                    </div>
                </div>
            </div>

            <div class="tab-content">

                <div class="tab-content__pane active" id="infosentite">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-12 sm:col-span-12 md:col-span-12">
                            <label for="direct" class="font-bold mb-2 pb-2">LOCATION</label><br />
                            <input class="input input--switch ml-auto border w-full border flex-1 ml-3" name="location" id="location_entite" type="checkbox">
                        </div>

                        <div class="col-span-12 sm:col-span-12 md:col-span-12">
                            <label for="direct" class="font-bold mb-2 pb-2">VENTE</label><br />
                            <input class="input input--switch ml-auto border w-full border flex-1 ml-3" name="vente" id="vente_entite" type="checkbox">
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="designation_entite" class="required">Désignation</label>
                            <input type="text" id="designation_entite" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="désignation">
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="description_entite" class="required">Description</label>
                            <input type="text" id="description_entite" name="description" class="input w-full border mt-2 flex-1 required" placeholder="description">
                        </div>
                        <!-- <div class="col-span-12 mt-2 sm:col-span-12">
                            <label for="categorietuto_typetuto">Gestionnaire</label>
                            <div class="inline-block relative w-full">
                                <select
                                    class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline "
                                    id="gestionnaire_entite" name="gestionnaire">
                                    <option value="" class="required">Gestionnaire</option>
                                    <option ng-repeat="item in dataPage['users']" value="@{{ item.id }}">
                                        @{{ item.name }}
                                    </option>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-span-12 sm:col-span-12">
                            <label for="equipes_entite" class="">Equipe de gestion</label>
                            <div class="inline-block mt-2 relative w-full">
                                <select multiple class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="equipes_entite" name="equipes">
                                    {{-- <option value="" class="required">Périodes</option> --}}
                                    <option ng-repeat="item in dataPage['users']" value="@{{ item.id }}">
                                        @{{ item.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-12 mt-2 sm:col-span-12">
                            <label for="">Activite</label>
                            <div class="inline-block relative w-full">
                                <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline " id="activite_entite" name="activite_id">
                                    <option value="" class="required">Activite</option>
                                    <option ng-repeat="item in dataPage['activites']" value="@{{ item.id }}">
                                        @{{ item.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-6 filesinbox">
                            <label class="required">logo entite</label><br>
                            <input type="file" id="image_entite" name="image" class="form-control filestyle">
                        </div>


                    </div>
                </div>

                <div class="tab-content__pane " id="notaire">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <div class="col-span-6 sm:col-span-6">
                            <label for="nomcompletnotaire_entite" class="">Nom complet du notaire</label>
                            <input type="text" id="nomcompletnotaire_entite" name="nomcompletnotaire" class="input w-full border mt-2 flex-1 " placeholder="nom complet notaire">
                        </div>


                        <div class="col-span-6 sm:col-span-6">
                            <label for="emailnotaire_entite" class="">Email du notaire</label>
                            <input type="email" id="emailnotaire_entite" name="emailnotaire" class="input w-full border mt-2 flex-1 " placeholder="email notaire">
                        </div>


                        <div class="col-span-6 sm:col-span-6">
                            <label for="telephone1notaire_entite" class="">Téléphone du notaire</label>
                            <input type="text" id="telephone1notaire_entite" name="telephone1notaire" class="input w-full border mt-2 flex-1 " placeholder="téléphone notaire">
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="adressenotaire_entite" class="">Adresse du notaire</label>
                            <input type="text" id="adressenotaire_entite" name="adressenotaire" class="input w-full border mt-2 flex-1 " placeholder="adresse notaire">
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="nometudenotaire_entite" class="">Nom de l'étude du notariale</label>
                            <input type="text" id="nometudenotaire_entite" name="nometudenotaire" class="input w-full border mt-2 flex-1 " placeholder="nom étude notariale">
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <label for="emailetudenotaire_entite" class="">Email de l'étude notariale</label>
                            <input type="email" id="emailetudenotaire_entite" name="emailetudenotaire" class="input w-full border mt-2 flex-1 " placeholder="email étude notariale">
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <label for="adresseetudenotaire_entite" class="">Adresse de l'étude notariale</label>
                            <input type="text" id="adresseetudenotaire_entite" name="adresseetudenotaire" class="input w-full border mt-2 flex-1 " placeholder="adresse etude">
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <label for="telephoneetudenotaire_entite" class="">Téléphone de l'étude notariale</label>
                            <input type="text" id="telephoneetudenotaire_entite" name="telephoneetudenotaire" class="input w-full border mt-2 flex-1 " placeholder="téléphone étude notariale">
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <label for="assistantetudenotaire_entite" class="">Assistant(e) l'étude du notariale</label>
                            <input type="text" id="assistantetudenotaire_entite" name="assistantetudenotaire" class="input w-full border mt-2 flex-1 " placeholder="assistant(e) étude notariale">
                        </div>
                    </div>
                </div>

                <div class="tab-content__pane " id="bancaire">
                    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                        <!--                            <div class="col-span-6 sm:col-span-6">-->
                        <!--                                <div class="col-span-12 sm:col-span-12">-->
                        <!--                                    <label for="entite_entiteproduits_produit" class="required">Periode</label>-->
                        <!--                                    <div class="inline-block relative w-full mt-2">-->
                        <!--                                        <select class="form-control select2 select2-produit modal required text-capitalize" id="anne_info_bancaires_entite"  style="width: 100% !important;">-->
                        <!--                                            <option value="2022">2022</option>-->
                        <!--                                            <option value="2023">2023</option>-->
                        <!--                                            <option value="2024">2024</option>-->
                        <!--                                            <option value="2025">2025</option>-->
                        <!--                                            <option value="2026">2026</option>-->
                        <!--                                        </select>-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </div>-->

                        <div class="col-span-3 sm:col-span-3">
                            <label for="banque_info_bancaires_entite" class="required">Banque</label>
                            <input type="text" id="banque_info_bancaires_entite" class="input w-full border mt-2 flex-1 required" placeholder="Banque">
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="agence_info_bancaires_entite" class="required">Agence</label>
                            <input type="text" id="agence_info_bancaires_entite" class="input w-full border mt-2 flex-1 required" placeholder="agence">
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="codebanque_entite" class="required">Code banque</label>
                            <input type="text" id="codebanque_info_bancaires_entite" class="input w-full border mt-2 flex-1 required" placeholder="code banque">
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="codeguichet_entite" class="required">Code guichet</label>
                            <input type="text" id="codeguichet_info_bancaires_entite" class="input w-full border mt-2 flex-1 required" placeholder="code guichet">
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <label for="clerib_entite" class="required">Cle RIB</label>
                            <input type="text" id="clerib_info_bancaires_entite" class="input w-full border mt-2 flex-1 required" placeholder="cle rib">
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <label for="numerocompte_entite" class="required">Numero compte</label>
                            <input type="text" id="numerocompte_info_bancaires_entite" class="input w-full border mt-2 flex-1 required" placeholder="numerocompte">
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <div class="grid grid-cols-12 gap-4 row-gap-3">
                                <div class="col-span-12 sm:col-span-12">
                                    <label for="date_debut_planing">Date début</label>
                                    <input type="date" id="datedebut_info_bancaires_entite" class="input w-full border flex-1" placeholder="Date début...">
                                </div>
                            </div>
                        </div>

                        <div class="col-span-3 sm:col-span-3">
                            <div class="grid grid-cols-12 gap-4 row-gap-3">
                                <div class="col-span-12 sm:col-span-12">
                                    <label for="date_debut_planing">Date fin</label>
                                    <input type="date" id="datefin_info_bancaires_entite" class="input w-full border flex-1" placeholder="Date fin...">
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 sm:col-span-2 md:col-span-1 text-right">
                            <button type="button" class="button w-10 bg-theme-101 text-white mt-5" ng-click="actionSurTabPaneTagData('add','info_bancaires_entite',0, 'entite')"><span class="fa fa-plus"></span></button>
                        </div>

                        <div class="col-span-12 sm:col-span-12">
                            <div class="intro-y overflow-auto lg:overflow-visible mt-4 sm:mt-0">
                                <table class="table table-report sm:mt-1">
                                    <thead>
                                        <tr class="bg-theme-101 text-white">
                                            <th hidden class="whitespace-no-wrap">#</th>
                                            <th class="whitespace-no-wrap">Banque</th>
                                            <th class="whitespace-no-wrap text-center">Agence </th>
                                            <th class="whitespace-no-wrap text-center">Code Banque</th>
                                            <th class="whitespace-no-wrap text-center">Code Guichet </th>
                                            <th class="whitespace-no-wrap text-center">Clé RIB </th>
                                            <th class="whitespace-no-wrap text-center">Numero compte </th>
                                            <th class="whitespace-no-wrap text-center">Periode </th>
                                            <th class="text-center whitespace-no-wrap">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="intro-x" ng-repeat="item in dataInTabPane['info_bancaires_entite']['data']">

                                            <td hidden class="">
                                                <div class="font-medium whitespace-no-wrap">@{{item.id}}</div>
                                            </td>

                                            <td class="">
                                                <div class="font-medium whitespace-no-wrap">@{{item.banque}}</div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap">@{{item.agence}}</div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap">@{{item.codebanque}}</div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap">@{{item.codeguichet}}</div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap">@{{item.clerib}}</div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap">@{{item.numerocompte}}</div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="font-medium whitespace-no-wrap text-center">
                                                    <div class="font-medium whitespace-no-wrap">@{{item.datedebut}} / @{{item.datefin}}</div>
                                                </div>
                                            </td>


                                            <td class="table-report__action w-56">
                                                <nav class="menu-leftToRight uk-flex text-center">
                                                    <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open-@{{ $index }}">
                                                    <label class="menu-open-button bg-white" for="menu-open-@{{ $index }}">
                                                        <span class="hamburger bg-template-1 hamburger-1"></span>
                                                        <span class="hamburger bg-template-1 hamburger-2"></span>
                                                        <span class="hamburger bg-template-1 hamburger-3"></span>
                                                    </label>
                                                    <button class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="actionSurTabPaneTagData('delete', 'info_bancaires_entite', $index)" title="Supprimer">
                                                        <span class="fa fa-trash-alt"></span>
                                                    </button>

                                                </nav>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>

            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal entite -->

<!-- modal annulation annulationpaiementloyer -->
<div class="modal" id="modal_addannulationpaiementloyer">
    <div class="modal__content modal__content--md">

        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Annulation paiement de facture
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <form id="form_addannulationpaiementloyer" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'annulationpaiementloyer')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" name="loyer" id="loyer_annulationpaiementloyer">
            <input type="hidden" name="etat" value="1">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-12 sm:col-span-12">
                    <div class="form-floating">
                        <label for="motif_annulationpaiementloyer" class="required">Motif annulation</label>
                        <textarea rows="4" id="motif_annulationpaiementloyer" name="motif" class="input w-full border mt-2 flex-1 required" placeholder="motif annulation..."></textarea>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12">
                    <label for="date_annulationpaiementloyer">Date d'annulation</label>
                    <input type="date" id="date_annulationpaiementloyer" name="date" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>

            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- modal annulationpaiementloyer  -->

<!-- modal annulation paiement -->
<div class="modal" id="modal_addannulationpaiementavis">
    <div class="modal__content modal__content--md">

        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Annulation paiement avis d'échéance
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <form id="form_addannulationpaiementavis" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'annulationpaiementavis')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" name="echeance" id="echeance_annulationpaiementavis">
            <input type="hidden" name="etat" value="1">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-12 sm:col-span-12">
                    <div class="form-floating">
                        <label for="motif_annulationpaiementavis" class="required">Motif annulation</label>
                        <textarea rows="4" id="motif_annulationpaiementavis" name="motif" class="input w-full border mt-2 flex-1 required" placeholder="motif annulation..."></textarea>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12">
                    <label for="date_annulationpaiementavis">Date d'annulation</label>
                    <input type="date" id="date_annulationpaiementavis" name="date" class="input w-full border mt-2 flex-1" placeholder="date">
                </div>

            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- modal annulation paiement -->

<!-- debut modal ilot -->
<div class="modal" id="modal_addilot">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Ilot
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addilot" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'ilot')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_ilot" name="id">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="numero_ilot" class="required">Numéro</label>
                    <input type="text" id="numero_ilot" name="numero" class="input w-full border mt-2 flex-1 required" placeholder="Numeéro ilot">
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="adresse_ilot" class="required">Adresse</label>
                    <input type="text" id="adresse_ilot" name="adresse" class="input w-full border mt-2 flex-1 required" placeholder="adresse">
                </div>
                {{-- <div class="col-span-6 sm:col-span-6">
                        <label for="numerotitrefoncier_ilot" class="">Numéro du titre foncier</label>
                        <input type="text" id="numerotitrefoncier_ilot" name="numerotitrefoncier"
                            class="input w-full border mt-2 flex-1 " placeholder="numéro titre foncier">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="adressetitrefoncier_ilot" class="">Adresse du titre foncier</label>
                        <input type="text" id="adressetitrefoncier_ilot" name="adressetitrefoncier"
                            class="input w-full border mt-2 flex-1 " placeholder="adresse titre foncier">
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="datetitrefoncier_ilot" class="">Date d'émission du titre foncier</label>
                        <input type="date" id="datetitrefoncier_ilot" name="datetitrefoncier"
                            class="input w-full border mt-2 flex-1 " placeholder="date d'émission ">
                    </div> --}}


            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal ilot -->

<!-- debut modal modepaiement -->
<div class="modal" id="modal_addmodepaiement">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Mode paiement
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addmodepaiement" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'modepaiement')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_modepaiement" name="id">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-6 sm:col-span-6">
                    <label for="designation_modepaiement" class="required">Désignation</label>
                    <input type="text" id="designation_modepaiement" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="désignation">
                </div>

                <div class="col-span-6 sm:col-span-6">
                    <label for="code_modepaiement" class="required">Code </label>
                    <input type="text" id="code_modepaiement" name="code" class="input w-full border mt-2 flex-1 required" placeholder="code">
                </div>

                <div class="col-span-12 sm:col-span-12">
                    <label for="description_modepaiement" class="">Description</label>
                    <input type="text" id="description_modepaiement" name="description" class="input w-full border mt-2 flex-1" placeholder="description">
                </div>

            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
<!-- fin modal modepaiement -->
{{-- secteur activite --}}
<div class="modal" id="modal_addsecteuractivite">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-window-frame mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Secteur d'activité
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addsecteuractivite" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'secteuractivite')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_secteuractivite" name="id">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-12 sm:col-span-12">
                    <label for="designation_secteuractivite" class="required">Désignation</label>
                    <input type="text" id="designation_secteuractivite" name="designation" class="input w-full border mt-2 flex-1 required" placeholder="désignation">
                </div>

                <div class="col-span-12 sm:col-span-12">
                    <label for="description_secteuractivite" class="">Description</label>
                    <input type="text" id="description_secteuractivite" name="description" class="input w-full border mt-2 flex-1" placeholder="description">
                </div>

            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
{{-- secteur activite --}}

{{-- debut modal add inbox --}}
<div class="modal" id="modal_addinbox">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-inbox mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Inbox
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addinbox" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'inbox')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_inbox" name="id">
            <input type="hidden" id="contrat_inbox" name="contrat">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-4 sm:col-span-4">
                    <label for="subject_inbox" class="required">Sujet</label>
                    <input type="text" id="subject_inbox" name="subject" class="input w-full border mt-2 flex-1 required" placeholder="sujet">
                </div>


                <div class="col-span-4 mt-2 sm:col-span-4">
                    <label for="categorietuto_typetuto">Locataire</label>
                    <div class="inline-block relative w-full">
                        <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_locataire" id="locataire_inbox" name="locataire">
                            <option value="" class="required">Locataire</option>
                            <option ng-repeat="item in dataPage['locataires']" value="@{{ item.id }}">
                                @{{ item.prenom }} @{{ item.nom }} @{{ item.nomentreprise }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-span-4 mt-2 sm:col-span-4 hidden" id="choixContrat_inbox_div">
                    <label for="categorietuto_typetuto">Choix contrat</label>
                    <div class="inline-block relative w-full">
                        <select class="block id_appartement_inbox select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" id="choixContrat_inbox" name="choixContrat_inbox">
                            <option value="" class="required">Choix contrat</option>
                            <option ng-repeat="item in dataPage['contrats']" value="@{{ item.id }}">
                                @{{ item.descriptif }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-span-4 mt-2 sm:col-span-4">
                    <label for="categorietuto_typetuto">Appartement</label>
                    <div class="inline-block relative w-full">
                        <select class="block id_appartement_inbox select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline search_appartement" id="appartement_inbox" name="appartement">
                            <option value="" class="required">Appartement / Villa</option>
                            <option ng-repeat="item in dataPage['appartements']" value="@{{ item.id }}">

                                @{{ item.nom }}
                                @{{ item.lot_ilot_refact }}
                            </option>
                        </select>
                    </div>
                </div>


                <div ng-if="item_update" class="col-span-12 sm:col-span-12 ">
                    <label for="subject_inbox" class="">Email locataire</label>
                    <input type="email" disabled id="senderemail_inbox" value="@{{ item_update.sender_email }}" class="input w-full border mt-2 flex-1" placeholder="email">
                </div>
                <div class="col-span-12 sm:col-span-12 relance_div ">
                    <input type="hidden" name="mode_relance" value="10">
                    <label>
                        <input ng-model="relance" id="check_relance1_inbox" checked="checked" name="check_relance" ng-change="showRelance(relance)" value="1" type="radio" />
                        <span>Premier relance</span>
                    </label>
                    <label>
                        <input ng-model="relance" id="check_relance2_inbox" name="check_relance" ng-change="showRelance(relance)" value="2" type="radio" />
                        <span>Deuxieme relance</span>
                    </label>


                    <label>
                        <input ng-model="relance" id="check_relance3_inbox" name="check_relance" ng-change="showRelance(relance)" value="3" type="radio" />
                        <span>Troisieme relance</span>
                    </label>
                </div>


                <div class="col-span-12 sm:col-span-12">
                    <div class="form-floating">
                        <label for="floatingTextarea" class="required">Contenu</label>

                        <textarea rows="4" id="body_inbox" name="body" class="input w-full border mt-2 flex-1 required" placeholder="contenu..." id="floatingTextarea"></textarea>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-12 allfilesinbox">
                    <label class="required">Joindre des fichiers</label><br>
                    <input type="hidden" name="is_inbox" value="10">
                    <input type="file" id="files_inbox" multiple name="files[]" accept="application/pdf" class="form-control filestyle">
                </div>
                <div class="col-span-6 sm:col-span-6 filesinbox">
                    <label class="required">Appel loyer / échéance</label><br>
                    <input type="file" id="pdfappel_inbox" name="fileappelloyer" accept="application/pdf" class="form-control filestyle">
                </div>
                <div class="col-span-6 sm:col-span-6 filesinbox">
                    <label class="required">Facture loyer / échéance</label><br>
                    <input type="file" id="pdffacture_inbox" name="filefacture" accept="application/pdf" class="form-control filestyle">
                </div>

                <div class="col-span-12  sm:col-span-12">

                    <table ng-if="item_update" class="table table-report ">
                        <thead>
                            <tr>
                                <th class="whitespace-no-wrap ">Attache</th>
                                <th class="whitespace-no-wrap"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x" ng-repeat="document in item_update.attachements">
                                <td>
                                    <div class="font-medium whitespace-no-wrap"> <a ng-click="redirectPdf(document.filepath)" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">@{{ document.filename }}</a>
                                    </div>
                                </td>

                                <td class="table-report__action w-56">
                                    {{-- <button type="button" class="menu-item btn border-0 bg-danger text-white fsize-16" ng-click="deleteDocument('document')" title="Supprimer">
                                        <span class="fa fa-trash-alt"></span>
                                    </button> --}}
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <table class="table table-report filesinbox">

                        <tbody>
                            <tr class="intro-x appelloyerpdf_item">
                                <td>
                                    <div class="font-medium whitespace-no-wrap"> <a id="pdflinkappel_inbox" href="" target="_blank" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Appel
                                            loyer</a></div>
                                </td>

                                <td class="table-report__action w-56">
                                    <button id="delete_button_appel" type="button" class="menu-item btn border-0 bg-danger text-white fsize-16 " style="margin-top:-5%" title="Supprimer">
                                        <span class="fa fa-trash-alt"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="intro-x factureloyerpdf_item">
                                <td>
                                    <div class="font-medium whitespace-no-wrap"> <a id="pdflinkfacture_inbox" href="" target="_blank" style="cursor: pointer ; margin-right: 10% ;  border-radius: 2px ; padding: 10px ; background-color: #eeeee4 ; height: 40px ; width: 20%">Facture</a>
                                    </div>
                                </td>

                                <td class="table-report__action w-56">
                                    <button type="button" id="delete_button_facture" class="menu-item btn border-0 bg-danger text-white fsize-16 " style="margin-top:-5%" title="Supprimer">
                                        <span class="fa fa-trash-alt"></span>
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>


                </div>



            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>

                <button ng-if="!item_update" type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
{{-- fin modal add inbox --}}

{{-- start modal send avis encours  --}}
<div class="modal" id="modal_addinboxecheance">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 header ">
            <i class="fa fa-inbox mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                Envoie des échéances encours
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        <form id="form_addinboxecheance" class="form" accept-charset="UTF-8" ng-submit="addElement($event,'inboxecheance')" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_inboxecheance" name="id">
            <input type="hidden" id="contrat_inboxecheance" name="contrat">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-4 sm:col-span-4">
                    <label for="subject_inboxecheance" class="required">Sujet</label>
                    <input type="text" id="subject_inboxecheance" name="subject" class="input w-full border mt-2 flex-1 required" placeholder="sujet">
                </div>


                <div class="col-span-12 sm:col-span-12">
                    <div class="form-floating">
                        <label for="floatingTextarea" class="required">Contenu</label>

                        <textarea rows="4" id="body_" name="body" class="input w-full border mt-2 flex-1 required" placeholder="contenu..." id="floatingTextarea"></textarea>
                    </div>
                </div>




            </div>

            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler
                </button>

                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>
    </div>
</div>
{{-- end modal send avis encours --}}

<!-- Modal excelclient -->
<div class="modal fade" id="modal_addlist" aria-hidden="true">
    <div class="modal__content modal__content--md">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 bg-glf text-white">
            <i class="fas fa-file-excel mr-2"></i>
            <h2 class="font-medium text-base mr-auto">
                @{{ currentTitleModal }}
            </h2>
            <div class="pull-right">
                <button class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

        </div>
        <form id="form_addliste" class="form" accept-charset="UTF-8" ng-submit="addElement($event, currentTypeModal, {is_file_excel:true})" style="max-height: 80vh!important;overflow: auto">
            @csrf
            <input type="hidden" id="id_importexcelclient" name="id">

            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">

                <div class="col-span-12 sm:col-span-12">
                    <label for="file_importexcelclient">Fichier</label>
                    <input type="file" accept=".csv, .xls, .xlsx" class="form-control filestyle required" data-buttonName="btn-shadow btn-transition btn-outline-danger p-2" data-buttonText="Choisir un fichier" data-placeholder="Aucun fichier choisi" data-iconName="fa fa-folder-open" id="file_liste" name="file">
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1 btn-shadow-dark">Annuler</button>
                <button type="submit" class="button w-20 bg-theme-101 text-white btn-shadow">Valider</button>
            </div>
        </form>

    </div>
</div>




<script type="text/javascript">
    $(document).on("change", "input[name=enregistrementcontrat]", function() {
        console.log($(this).val());
        if ($(this).val() == "Oui") {
            $("#infosEnregistrementcontrat").css("display", "block");
            $("#infosEnregistrementcontrat2").css("display", "block");
            $("#infosEnregistrementcontrat3").css("display", "block");
            $(".infosEnregistrementcontratJoint").css("display", "block");
            $("#infosEnregistrementcontrat .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else {
            $("#infosEnregistrementcontrat").css("display", "none");
            $("#infosEnregistrementcontrat2").css("display", "none");
            $("#infosEnregistrementcontrat3").css("display", "none");
            $(".infosEnregistrementcontratJoint").css("display", "none");
            $("#infosEnregistrementcontrat .addRequired").each(function() {
                $(this).attr("required", false)
            });
        }
    });

    $(document).on("change", "input[name=assurance]", function() {
        console.log($(this).val());
        if ($(this).val() == "Oui") {
            $("#infosAssurance").css("display", "block");
            $("#infosAssurance2").css("display", "block");
            $("#infosAssurance3").css("display", "block");
            $(".infosAssuranceJoint").css("display", "block");
            $("#infosAssurance .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else {
            $("#infosAssurance").css("display", "none");
            $("#infosAssurance2").css("display", "none");
            $("#infosAssurance3").css("display", "none");
            $(".infosAssuranceJoint").css("display", "none");
            $("#infosAssurance .addRequired").each(function() {
                $(this).attr("required", false)
            });
        }
    });

    $(document).on("change", "input[name=salleFete]", function() {
        console.log($(this).val());
        if ($(this).val() == "Oui") {
            $("#nombre_sallefete").css("display", "block");
            $("input[name=nombresallefete]").attr("required", true);

        } else {
            $("#nombre_sallefete").css("display", "none");
            $("input[name=nombresallefete]").attr("required", false);
        }
    });

    $(document).on("change", "input[name=salleGym]", function() {
        if ($(this).val() == "Oui") {
            $("#nombre_sallegym").css("display", "block");
            $("input[name=nombresallegym]").attr("required", true);

        } else {
            $("#nombre_sallegym").css("display", "none");
            $("input[name=nombresallegym]").attr("required", false);
        }
    });

    $(document).on("change", "input[name=receptionniste]", function() {
        if ($(this).val() == "Oui") {
            $("#nombre_receptionniste").css("display", "block");
            $("input[name=nombrereceptionniste]").attr("required", true);

        } else {
            $("#nombre_receptionniste").css("display", "none");
            $("input[name=nombrereceptionniste]").attr("required", false);
        }
    });

    $(document).on("change", "input[name=jardin]", function() {
        if ($(this).val() == "Oui") {
            $("#nombre_jardin").css("display", "block");
            $("input[name=nombrejardin]").attr("required", true);

        } else {
            $("#nombre_jardin").css("display", "none");
            $("input[name=nombrejardin]").attr("required", false);
        }
    });

    $(document).on("change", "input[name=parkingSousterrain]", function() {
        if ($(this).val() == "Oui") {
            $("#nombre_parkingsousterrain").css("display", "block");
            $("input[name=nombreparkingsousterrain]").attr("required", true);

        } else {
            $("#nombre_parkingsousterrain").css("display", "none");
            $("input[name=nombreparkingsousterrain]").attr("required", false);
        }
    });

    $(document).on("change", "input[name=parkingExterne]", function() {
        if ($(this).val() == "Oui") {
            $("#nombre_parkingexterne").css("display", "block");
            $("input[name=nombreparkingexterne]").attr("required", true);

        } else {
            $("#nombre_parkingexterne").css("display", "none");
            $("input[name=nombreparkingexterne]").attr("required", false);
        }
    });

    $(document).on("change", "input[name=entrepot]", function() {
        if ($(this).val() == "Oui") {
            $("#nombre_entrepot").css("display", "block");
            $("input[name=nombreentrepot]").attr("required", true);

        } else {
            $("#nombre_entrepot").css("display", "none");
            $("input[name=nombreentrepot]").attr("required", false);
        }
    });

    $(document).on("change", "input[name=syndic]", function() {
        if ($(this).val() == "Oui") {
            $("#nom_syndic").css("display", "block");
            $("input[name=nomsyndic]").attr("required", true);

        } else {
            $("#nom_syndic").css("display", "none");
            $("input[name=nomsyndic]").attr("required", false);
        }
    });



    // Khalifa
    $(document).on("change", "input[name=epargne]", function() {
        console.log($(this).val());
        if ($(this).val() == "Oui") {
            $("#infosEpargne").css("display", "block");
            $("#infosEpargne .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else {
            $("#infosEpargne").css("display", "none");
            $("#infosEpargne .addRequired").each(function() {
                $(this).attr("required", false)
            });
        }
    });

    $(document).on("change", "input[name=physique]", function() {
        console.log($(this).val());
        if ($(this).val() == "Physique") {

            if ($(this).val() == "Physique") {
                $("#infosPersonnaliteJuridiqueMorale").css("display", "none");
                $("#infosPersonnaliteJuridiqueMorale .addRequired").each(function() {
                    $(this).attr("required", false)
                });
            }

            $("#infosPersonnaliteJuridiquePhysique").css("display", "block");
            $("#infosPersonnaliteJuridiquePhysique .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else if ($(this).val() == "Morale") {

            if ($(this).val() == "Morale") {
                $("#infosPersonnaliteJuridiquePhysique").css("display", "none");
                $("#infosPersonnaliteJuridiquePhysique .addRequired").each(function() {
                    $(this).attr("required", false)
                });
            }
            $("#infosPersonnaliteJuridiqueMorale").css("display", "block");
            $("#infosPersonnaliteJuridiqueMorale .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else {
            $("#infosPersonnaliteJuridiquePhysique").css("display", "none");
            $("#infosPersonnaliteJuridiquePhysique .addRequired").each(function() {
                $(this).attr("required", false)
            });
        }
    });

    $(document).on("change", "input[name=typeContrat]", function() {
        console.log($(this).val());
        if ($(this).val() == "Determine") {
            if ($(this).val() == "Determine") {
                $("#infosPersonnaliteJuridiqueContratIndetermine").css("display", "none");
                $("#infosPersonnaliteJuridiqueIndetermine .addRequired").each(function() {
                    $(this).attr("required", false)
                });
            }

            $("#infosPersonnaliteJuridiqueContratDetermine").css("display", "block");
            $("#infosPersonnaliteJuridique .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else if ($(this).val() == "Indetermine") {
            if ($(this).val() == "Indetermine") {
                $("#infosPersonnaliteJuridiqueContratDetermine").css("display", "none");
                $("#infosPersonnaliteJuridique .addRequired").each(function() {
                    $(this).attr("required", false)
                });
            }

            $("#infosPersonnaliteJuridiqueContratIndetermine").css("display", "block");
            $("#infosPersonnaliteJuridiqueIndetermine .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else {
            $("#infosPersonnaliteJuridiqueContratDetermine").css("display", "none");
            $("#infosPersonnaliteJuridiqueContratDetermine .addRequired").each(function() {
                $(this).attr("required", false)
            });
        }
    });

    $(document).on("change", "input[name=typeContratMorale]", function() {
        console.log($(this).val());
        if ($(this).val() == "Determine") {
            if ($(this).val() == "Determine") {
                $("#infosPersonnaliteJuridiqueContratIndetermineMorale").css("display", "none");
                $("#infosPersonnaliteJuridiqueIndetermineMorale .addRequired").each(function() {
                    $(this).attr("required", false)
                });
            }

            $("#infosPersonnaliteJuridiqueContratDetermineMorale").css("display", "block");
            $("#infosPersonnaliteJuridiqueMorale .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else if ($(this).val() == "Indetermine") {
            if ($(this).val() == "Indetermine") {
                $("#infosPersonnaliteJuridiqueContratDetermineMorale").css("display", "none");
                $("#infosPersonnaliteJuridiqueMorale .addRequired").each(function() {
                    $(this).attr("required", false)
                });
            }

            $("#infosPersonnaliteJuridiqueContratIndetermineMorale").css("display", "block");
            $("#infosPersonnaliteJuridiqueIndetermineMorale .addRequired").each(function() {
                $(this).attr("required", true)
            });

        } else {
            $("#infosPersonnaliteJuridiqueContratDetermine").css("display", "none");
            $("#infosPersonnaliteJuridiqueContratDetermine .addRequired").each(function() {
                $(this).attr("required", false)
            });
        }
    });
    //
    $(document).on("change", "input[name=proprietaireUnique]", function() {
        if ($(this).val() == "Non") {
            $("#nom_copropriete").css("display", "block");
            $("input[name=nomcopropriete]").attr("required", true);

        } else {
            $("#nom_copropriete").css("display", "none");
            $("input[name=nomcopropriete]").attr("required", false);
        }
    });

    //   <script>
    // const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

    // const input = document.querySelectorAll(".phone");
    // const telephoneportable2locataire_locataire = document.querySelector("#telephoneportable2locataire_locataire");
    // const telInput1 = window.intlTelInput(telephoneportable2locataire_locataire, {
    //         utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
    //         initialCountry: "sn",
    //         hiddenInput: "telephoneportable2_dialCode"
    //     });


    //     const telephoneportable1locataire_locataire = document.querySelector("#telephoneportable1locataire_locataire");
    // window.intlTelInput(telephoneportable1locataire_locataire, {
    //         utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
    //         initialCountry: "sn",
    //         hiddenInput: "telephoneportable1locataire_dialCode"
    //     });
    // input.forEach(element => {
    //     const telInput =  window.intlTelInput(element, {
    //         utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
    //         initialCountry: "sn",
    //         // separateDialCode: true,
    //         // placeholderNumberType:"MOBILE",
    //         hiddenInput: "dialCode"
    //         // formatOnDisplay: true,
    //     });
    //     telInput.on("select", function (e, countryData) {
    //     const countryCodeName = element.getAttribute("data-country-code-name");
    // const hiddenInput = document.getElementById(countryCodeName + "_dialCode");
    // if (hiddenInput) {
    //     hiddenInput.value = countryData.dialCode;
    // }
    // });
    //     // const countryCodeName = element.getAttribute("data-country-code-name");
    //     // const countryCodeValue = telInput.getSelectedCountryData().dialCode;
    //     // // console.log("vlues code  o oocooco "+JSON.stringify(telInput.getSelectedCountryData()))
    //     // const hiddenInput = document.getElementById(countryCodeName);
    //     // if (hiddenInput) {
    //     //     hiddenInput.value = countryCodeValue;
    //     // }



    // });

    //     document.querySelector("form").addEventListener("submit", function (event) {

    // });
    // const inputElements = document.querySelectorAll(".phone");
    //     inputElements.forEach(inputElement => {
    //         const telInput = window.intlTelInput(inputElement);
    //         const countryCodeName = inputElement.getAttribute("data-country-code-name");
    //         const countryCodeValue = telInput.getSelectedCountryData().iso2;
    //         const hiddenInput = document.getElementById(countryCodeName);
    //         if (hiddenInput) {
    //             hiddenInput.value = countryCodeValue;
    //         }
    //     });



    // const fileInput = document.querySelector('#pdfFile');
    // const pdfPreview = document.querySelector("#pdfPreview");
    // const openPdfButton = document.querySelector("#openPdfButton");
    // const eventLog = document.querySelector(".event-log-contents");
    // const reader = new FileReader();

    $(".appelloyerpdf_item").hide();
    $(".factureloyerpdf_item").hide();

    function setupPdfViewer(inputElement, linkElement, classHide, pdfDeleteButton) {
        const fileInput = inputElement;
        //   const pdfPreview = embedElement;
        const pdfLink = linkElement;
        //   const eventLog = eventLogElement;
        const reader = new FileReader();

        let selectedFile;

        function handleEvent(event) {
            // eventLog.textContent += `${event.type}: ${event.loaded} bytes transferred\n`;

            if (event.type === "load") {
                //   pdfPreview.setAttribute('src', reader.result);
                selectedFile = fileInput.files[0];
                if (selectedFile) {
                    // Ouvrir le PDF dans un nouvel onglet
                    const pdfBlob = new Blob([selectedFile], {
                        type: "application/pdf"
                    });
                    const pdfUrl = URL.createObjectURL(pdfBlob);
                    // window.open(pdfUrl);
                    $("." + classHide).show();
                    pdfLink.setAttribute('href', pdfUrl);
                }


            }
        }

        function addListeners(reader) {
            reader.addEventListener("loadstart", handleEvent);
            reader.addEventListener("load", handleEvent);
            reader.addEventListener("loadend", handleEvent);
            reader.addEventListener("progress", handleEvent);
            reader.addEventListener("error", handleEvent);
            reader.addEventListener("abort", handleEvent);
        }

        function handleSelected() {
            // eventLog.textContent = "";
            selectedFile = fileInput.files[0];
            if (selectedFile && selectedFile.type === "application/pdf") {
                addListeners(reader);
                reader.readAsDataURL(selectedFile);
            }
        }

        function handleDelete() {
            selectedFile = null;
            pdfLink.setAttribute('href', '');
            $("." + classHide).hide();
            // pdfDeleteButton.style.display = "none";
            fileInput.value =
                null; // Réinitialiser le champ de fichier pour permettre de sélectionner à nouveau le même fichier
        }

        fileInput.addEventListener("change", handleSelected);
        pdfDeleteButton.addEventListener("click", handleDelete);

    }


    const inputElement = document.querySelector('#pdfappel_inbox');
    const linkElement = document.querySelector("#pdflinkappel_inbox");
    const deleteButton1 = document.querySelector("#delete_button_appel");
    setupPdfViewer(inputElement, linkElement, "appelloyerpdf_item", deleteButton1);

    const inputElementfa = document.querySelector('#pdffacture_inbox');
    const linkElementfa = document.querySelector("#pdflinkfacture_inbox");
    const deleteButton2 = document.querySelector("#delete_button_facture");
    setupPdfViewer(inputElementfa, linkElementfa, "factureloyerpdf_item", deleteButton2);


    // function handleEvent(event) {
    //   eventLog.textContent += `${event.type}: ${event.loaded} bytes transferred\n`;

    //   if (event.type === "load") {
    //     pdfPreview.setAttribute('src', reader.result);

    // openPdfButton.addEventListener("click", function () {
    //     selectedFile = fileInput.files[0];
    //     if (selectedFile) {
    //     // Ouvrir le PDF dans un nouvel onglet
    //         const pdfBlob = new Blob([selectedFile], { type: "application/pdf" });
    //         const pdfUrl = URL.createObjectURL(pdfBlob);
    //         window.open(pdfUrl);
    //     }
    // });
    //   }
    // }

    // function addListeners(reader) {
    //   reader.addEventListener("loadstart", handleEvent);
    //   reader.addEventListener("load", handleEvent);
    //   reader.addEventListener("loadend", handleEvent);
    //   reader.addEventListener("progress", handleEvent);
    //   reader.addEventListener("error", handleEvent);
    //   reader.addEventListener("abort", handleEvent);
    // }

    // function handleSelected(e) {
    //   eventLog.textContent = "";
    //   const selectedFile = fileInput.files[0];
    //   if (selectedFile && selectedFile.type === "application/pdf") {
    //     addListeners(reader);
    //     reader.readAsArrayBuffer(selectedFile);
    //   }
    // }

    // fileInput.addEventListener("change", handleSelected);
    $(document).on('click', '.open-file-link', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien
        var fileUrl = $(this).data('file-url');
        window.open(fileUrl, '_blank'); // Ouvrir le fichier dans une nouvelle fenêtre
    });
</script>
@endsection