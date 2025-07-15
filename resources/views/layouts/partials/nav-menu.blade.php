<div class="top-bar">
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex" ng-click="toggleTabMenu()" >
        <div class="fa fa-bars point-hover mr-2" style="font-size: 19px" title="Agrandir"></div>
        <a href="" class="">Bienvenue <u class="font-bolder text-theme-106 border-0 uppercase">{{Auth::user()->name}}</u></a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i>
        <a href="" class="breadcrumb--active">@{{titlePage}}</a>

    </div>


    <div class="intro-x dropdown relative mr-auto sm:mr-6">
        <div class="dropdown-toggle notification notification--bullet cursor-pointer">
            <i data-feather="bell" class="notification__icon animated infinite"></i>
            <div class="item-notif-number" ng-if="notification_commande">@{{notification_commande.length}}</div>
            <div class="item-notif-number" ng-if="notifications">@{{notifications.length}}</div>
        </div>
        <div class="notification-content dropdown-box mt-8 absolute top-0 left-0 sm:left-auto sm:right-0 z-20 -ml-10 sm:ml-0" style="width: 500px !important;">
            <div class="notification-content__box dropdown-box__content box">
                <div class="notification-content__title">Notifications</div>

                <div style="max-height: 400px;overflow: auto;">
                    <div class="cursor-pointer" ng-if="notifications.length==0">AUCUNE NOTIFICATION</div>

                    <div ng-if="notification_commande" class="cursor-pointer relative flex items-center mb-3 p-2 active_notif" ng-repeat="item in notification_commande track by $index">
                        <div class="ml-2 w-full">
                            <div class="flex items-center overflow-hidden">
                                <a href="javascript:;" ng-if="item.action==7" class="font-medium truncate mr-5">Reclamation</a>
                                <a href="javascript:;" ng-if="item.action==4" class="font-medium truncate mr-5">Préparation terminée</a>
                                <div class="text-xs text-gray-500 ml-auto whitespace-no-wrap">@{{item.heure}}</div>
                            </div>
                            <div class="w-full text-gray-600" ng-if="item.etat==0">@{{item.description}}</div>
                            <div class="w-full text-light-600" ng-if="item.etat==1">@{{item.description}}</div>
                        </div>
                    </div>
                    <div ng-if="notifications" class="cursor-pointer relative flex items-center mb-3 p-2 active_notif" ng-repeat="item in notifications track by $index">
                        <div class="ml-2 w-full">
                            <div class="flex items-center overflow-hidden">
                                <a href="javascript:;" class="font-medium truncate mr-5">@{{item.titre}}</a>
                            </div>
                            <div class="w-full text-gray-600" >@{{item.description}}</div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="intro-x dropdown w-8 h-8 relative">
        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in">
            <img alt="Newlook Admin Theme" src="{{Auth::user()->image}}">
        </div>
        <div class="dropdown-box mt-10 absolute w-56 top-0 right-0 z-20">
            <div class="dropdown-box__content box bg-theme-138 text-white">
                <div class="p-4 border-b border-theme-140">
                    @if(Auth::user() && Auth::user()->name)
                    <div class="font-medium">{{Auth::user()->name}}</div>
                    <div class="text-xs text-theme-141">{{Auth::user()->roles->first()->name}}</div>
                    @endif

                </div>
                @if(auth()->user()->can('modification-user'))
                <div class="p-2">
                    <a href=""  ng-click="showModalUpdate('user',{{Auth::user()->id}})" title="Modifier les infos" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-101 rounded-md"> <i data-feather="edit" class="w-4 h-4 mr-2"></i> Modifier </a>
                </div>
                @endif

                <div class="p-2 border-t border-theme-140">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-101 rounded-md"> <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Déconnexion </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
