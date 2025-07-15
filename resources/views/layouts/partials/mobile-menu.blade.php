<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu mb-1 md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto mt-20">
            <img alt="GLF" class="w-20" ng-if="getLogoApp() != 'assets/images/logo/Logoglf-2.svg'" src="@{{getLogoApp()}}">
            <img alt="GLF" class="w-56" ng-if="getLogoApp() == 'assets/images/logo/Logoglf-2.svg'" src="@{{getLogoApp()}}">
        </a>
        <a href="javascript:;" id="mobile-menu-toggler"> <i data-feather="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
    </div>

    <div class="intro-x relative mt-2 mb-3">
        <div class="search sm:block px-3">
            <input type="text" class="input w-full placeholder-theme-13" ng-model="searchSideMenu" style="border-radius: 30px" placeholder="Search...">
            <i data-feather="search" class="search__icon"></i>
        </div>
    </div>

    <ul class="border-t border-theme-124 py-5 hidden">
        <!--debut search menu-->
        <li ng-repeat="item in menusearchs | filter:searchSideMenu" style="padding: 3px 0">
            <a href="@{{ item.url }}" class="menu" ng-click="openMenuSearch(item.id, false, true, item)">
                <div class="menu__icon"> <i class="fas fa-lg @{{ item.icon }}"></i>  </div>
                <div class="menu__title"> @{{ item.designation }} <i ng-if="item.parent != null" data-feather="chevron-down" class="menu__sub-icon"></i> </div>
            </a>
            <ul class="" id="open_menu_@{{ item.id }}">
                <li  ng-repeat="item2 in item.parent | filter:searchSideMenu" style="padding: 2px 0">
                    <a href="@{{item.parent != null ? '#!/'+item2.url : item2.url }}" class="menu" ng-click="openMenuSearch(item2.id, false, true, item2)">
                        <div class="menu__icon"> <i class="fas fa-lg @{{ item2.icon }}"></i> </div>
                        <div class="menu__title"> @{{ item2.designation }} <i ng-if="item2.parent != null" data-feather="chevron-down" class="menu__sub-icon"></i></div>
                    </a>
                    <ul class="" id="open_menu_@{{ item2.id }}">
                        <li ng-repeat="item3 in item2.parent | filter:searchSideMenu" style="padding: 1px 0">
                            <a href="@{{item2.parent != null ? '#!/'+item3.url : item3.url }}" class="menu" ng-click="openMenuSearch(item3.id, true, true, item3)">
                                <div class="menu__icon"> <i class="fas fa-lg @{{ item3.icon }}"></i>  </div>
                                <div class="menu__title"> @{{ item3.designation }} <i ng-if="item3.parent != null" data-feather="chevron-down" class="menu__sub-icon"></i> </div>
                            </a>
                            <ul class="" id="open_menu_second_@{{ item3.id }}">
                                <li>
                                    <a href="@{{item3.parent != null ? '#!/'+item3.url : item3.url }}" class="menu">
                                        <div class="menu__icon"> <i class="fa fa-lg @{{ item3.icon }}"></i> </div>
                                        <div class="menu__title"> @{{ item3.designation }} </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>
        </li>

        <br>
        <!--fin search menu-->

    </ul>

</div>
<!-- END: Mobile Menu -->
