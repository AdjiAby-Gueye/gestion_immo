<!-- BEGIN: Side Menu -->
<nav class="side-nav relative flex flex-col  bg" ng-class="isActiveMenu ? 'd-none' : ''" ng-cloak="">
    <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
        <div class="col-span-6 sm:col-span-6 ">
            <img alt="LOGO" class="hpx-70 text-center shadow p-1 w-30 mb-20" style="border: 1px solid #BD311D; border-radius: 10px;" ng-class="theme.getCurrent() == 'theme-Groupe' ? 'w-30' : 'w-24'" src="assets/images/LOGO-API.png">
        </div>
    </div>

    <ul class="relative flex flex-col h-full">
        <li ng-repeat="item in menusearchs | filter:searchSideMenu" class="mb-2">
            <a href="@{{ item.url == 'javascript:;' ? item.url : '#!/' + item.url }}" class="side-menu uppercase" ng-class="{ 'active': openmenu[item.id] }" ng-if="checkPermision(item.permission)" ng-click="openMenuSearch(item.id, false, false, item)">
                <div class="side-menu__icon">
                    <i class="fas icon-i fa-lg @{{ item.icon }}"></i>
                </div>
                <div class="side-menu__title">
                    @{{ item.designation }}
                    <i ng-if="item.parent_id" data-feather="chevron-down" class="side-menu__sub-icon"></i>
                </div>
            </a>

            <ul class="" id="open_menu_pc_@{{ item.id }}">
                <li ng-repeat="item2 in item.parent | filter:searchSideMenu">
                    <a href="@{{ item2.url == 'javascript:;' ? item2.url : '#!/' + item2.url }}" ng-if="checkPermision(item2.permission)" class="side-menu" ng-click="openMenuSearch(item2.id, false, false, item2)">
                        <div class="side-menu__icon">
                            <i class="fas icon-i fa-lg @{{ item2.icon }}"></i>
                        </div>
                        <div class="side-menu__title">
                            @{{ item2.designation }}
                            <i ng-if="item2.parent_id" data-feather="chevron-down" class="side-menu__sub-icon"></i>
                        </div>
                    </a>
                    <ul class="" id="open_menu_pc_@{{ item2.id }}">
                        <li ng-repeat="item3 in item2.parent | filter:searchSideMenu">
                            <a href="@{{ item3.url == 'javascript:;' ? item3.url : '#!/' + item3.url }}" ng-if="checkPermision(item3.permission)" class="side-menu" ng-click="openMenuSearch(item3.id, true, false, item3)">
                                <div class="side-menu__icon">
                                    <i class="fas icon-i fa-lg @{{ item3.icon }}"></i>
                                </div>
                                <div class="side-menu__title">
                                    @{{ item3.designation }}
                                    <i ng-if="item3.parent_id" data-feather="chevron-down" class="side-menu__sub-icon"></i>
                                </div>
                            </a>
                            <ul class="" id="open_menu_pc_second_@{{ item3.id }}">
                                <li>
                                    <a href="@{{ item3.parent !== null ? '#!/' + item3.url : item3.url }}" class="side-menu">
                                        <div class="side-menu__icon">
                                            <i class="fa icon-i fa-lg @{{ item3.icon }}"></i>
                                        </div>
                                        <div class="side-menu__title">
                                            @{{ item3.designation }}
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <br>

    </ul>

</nav>
<!-- END: Side Menu -->