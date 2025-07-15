<div class="grid grid-cols-12 gap-6 subcontent">
    <div class="col-span-12 xxl:col-span-9 grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="grid grid-cols-12 gap-6 mt-2">
                <div ng-repeat="item in -" ng-if="checkPermision(item.permission)" class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <a href="@{{item.url == 'javascript:;' ? item.url : '#!/'+item.url  }}">
                        <div class="report-box zoom-in">
                            <div class="box p-5 text-center">
                                <div class="text-center">
                                    <img ng-src="https://immo.erp.h-tsoft.com/assets/images/@{{item.icon}}" style="width: 120px;height: 120px;margin: 0 auto" alt="Logo">
                                </div>
                                <div class="font-bold leading-8 mt-8">@{{item.designation}}</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>