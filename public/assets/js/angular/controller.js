var app = angular.module("BackEnd", [
    "ngRoute",
    "ngSanitize",
    "ngLoadScript",
    "ui.bootstrap",
    "angular.filter",
    "ngCookies",
    "pr.longpress",
    "ngTouch",
]);
var BASE_URL = "//" + location.host + "/gestion_immo/public/";
var imgupload = BASE_URL + "/assets/images/upload.jpg";
var msg_erreur = "Erreur serveur";
location.host.includes("localhost") || location.host.includes("127.0.0.1")
    ? (BASE_URL = "//" + location.host + "/gestion_immo/public/")
    : (BASE_URL = "//" + location.host + "/");



function unauthenticated(error) {
    if (error.status === 401) {
        $scope.showToast("", "Votre session utilisateur a expiré...", "error");
        setTimeout(function () {
            window.location.reload();
        }, 2000);
    }
}
app.filter("tel", function () {
    return function (tel) {
        if (!tel) {
            return "";
        }

        var value = tel.toString().trim().replace(/^\+/, "");

        if (value.match(/[^0-9]/)) {
            return tel;
        }

        var country, city, number;

        switch (value.length) {
            case 10: // +1PPP####### -> C (PPP) ###-####
                country = 1;
                city = value.slice(0, 3);
                number = value.slice(3);
                break;

            case 11: // +CPPP####### -> CCC (PP) ###-####
                country = value[0];
                city = value.slice(1, 4);
                number = value.slice(4);
                break;

            case 12: // +CCCPP####### -> CCC (PP) ###-####
                country = value.slice(0, 3);
                city = value.slice(3, 5);
                number = value.slice(5);
                break;

            default:
                return tel;
        }

        if (country == 1) {
            country = "";
        }

        number = number.slice(0, 3) + "-" + number.slice(3);

        return (country + " (" + city + ") " + number).trim();
    };
});
app.directive("summernote", function () {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            $(element).summernote({
                height: 300,
                placeholder: "Type your content here...",
            });
        },
    };
});

app.filter("range", function () {
    return function (input, total) {
        total = parseInt(total);
        for (var i = 0; i < total; i++) input.push(i);
        return input;
    };
});

app.filter("formatTime", function ($filter) {
    return function (time, format) {
        var parts = time.split(" ");
        var hours;
        if (parts[1]) {
            hours = parts[1].split(":");
        } else {
            hours = parts[0].split(":");
        }

        return hours[0] + ":" + hours[1];
    };
});

app.filter("formatTimeslash", function ($filter) {
    return function (time, format) {
        var parts = time.split(" ");
        if (parts && parts.length > 0) {
            parts = parts[0];
        }
        parts = parts.split("-");
        return parts[2] + "/" + parts[1] + "/" + parts[0];
    };
});

app.filter("formatTimeHours", [
    function () {
        return function (input) {
            var timeStart = input.indexOf(" ");
            var time = input.substring(timeStart + 1, input.length - 3);
            return time;
        };
    },
]);

app.filter("convertMontant", [
    function () {
        return function (input) {
            input = input ? input + "" : 0 + "";
            return input.replace(/,/g, " ");
        };
    },
]);

app.directive("stringToNumber", function () {
    return {
        require: "ngModel",
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (value) {
                return "" + value;
            });
            ngModel.$formatters.push(function (value) {
                return parseFloat(value);
            });
        },
    };
});

app.factory("theme", function ($cookies) {
    var factory = {
        pathCookie: { path: "/" },
        nameCookie: "theme",
        data: false,
        setCurrent: function (theme) {
            $cookies.putObject(factory.nameCookie, theme, factory.pathCookie);
        },
        getCurrent: function () {
            return !$cookies.getObject(factory.nameCookie)
                ? "theme-Groupe"
                : $cookies.getObject(factory.nameCookie);
        },
        removeCurrent: function ($scope) {
            $cookies.remove(factory.nameCookie, factory.pathCookie);
        },
    };
    return factory;
});
app.directive("select2", function () {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            $(element).select2();
        },
    };
});

app.factory("userLogged", function ($http, $q, $cookies) {
    var factory = {
        pathCookie: { path: "/" },
        data: false,
        loginUser: function (userData) {
            $cookies.putObject("userData", userData, factory.pathCookie);
        },
        isLogged: function () {
            return $cookies.getObject("userData");
        },
        LogOut: function ($scope) {
            console.log("Dans LOGOUT");
            $cookies.remove("userData", factory.pathCookie);
        },
    };
    return factory;
});

app.factory("Init", function ($http, $q) {
    var factory = {
        data: false,
        getElement: function (
            element,
            listeattributs,
            listeattributs_filter = null,
            is_graphQL = true,
            dataget
        ) {
            var deferred = $q.defer();

            add_text_filter = "";

            if (listeattributs_filter != null && element.indexOf("(") !== -1) {
                args_filter = element.substr(
                    element.indexOf("("),
                    element.length + 1
                );

                $.each(listeattributs_filter, function (key, attr) {
                    add_text_filter =
                        (key === 0 ? "," : "") +
                        attr +
                        args_filter +
                        (listeattributs_filter.length - key > 1 ? "," : "") +
                        add_text_filter;
                });
                add_text_filter =
                    "," + add_text_filter.substr(0, add_text_filter.length);
            }

            var params = encodeURIComponent(element);
            $http({
                method: "GET",
                url:
                    BASE_URL +
                    (is_graphQL
                        ? "graphql?query= {" +
                        params +
                        " {" +
                        listeattributs +
                        (add_text_filter ? "," : "") +
                        add_text_filter +
                        "} }"
                        : element),
                headers: {
                    "Content-Type": "application/json",
                },
                data: dataget,
            }).then(
                function successCallback(response) {
                    if (is_graphQL) {
                        factory.data =
                            response["data"]["data"][
                            !element.indexOf("(") != -1
                                ? element.split("(")[0]
                                : element
                            ];
                    } else {
                        factory.data = response["data"];
                    }
                    deferred.resolve(factory.data);
                },
                function errorCallback(error) {
                    unauthenticated(error);
                    deferred.reject(msg_erreur);
                }
            );
            return deferred.promise;
        },
        getElementPaginated: function (
            element,
            listeattributs,
            listeattributs_filter
        ) {
            add_text_filter = "";

            if (listeattributs_filter != null) {
                args_filter = element.substr(
                    element.indexOf("("),
                    element.length + 1
                );

                $.each(listeattributs_filter, function (key, attr) {
                    $getAttr = attr;
                    $reste = "";
                    if (attr.indexOf("{") !== -1) {
                        $getAttr = attr.substr(0, attr.indexOf("{"));
                        $reste = attr.substr(
                            attr.indexOf("{"),
                            attr.length + 1
                        );
                    }
                    add_text_filter =
                        (key === 0 ? "," : "") +
                        $getAttr +
                        args_filter +
                        $reste +
                        (listeattributs_filter.length - key > 1 ? "," : "") +
                        add_text_filter;
                });
                add_text_filter =
                    "," + add_text_filter.substr(0, add_text_filter.length);
            }
            var params = encodeURIComponent(element);
            var deferred = $q.defer();
            $http({
                method: "GET",
                url:
                    BASE_URL +
                    "graphql?query={" +
                    params +
                    "{metadata{total,per_page,current_page,last_page},data{" +
                    listeattributs +
                    (add_text_filter ? "," : "") +
                    add_text_filter +
                    "}}}",
            }).then(
                function successCallback(response) {
                    factory.data =
                        response["data"]["data"][
                        !element.indexOf("(") != -1
                            ? element.split("(")[0]
                            : element
                        ];
                    deferred.resolve(factory.data);
                    console.log(
                        BASE_URL +
                        "graphql?query={" +
                        params +
                        "{metadata{total,per_page,current_page,last_page},data{" +
                        listeattributs +
                        (add_text_filter ? "," : "") +
                        add_text_filter +
                        "}}}"
                    );
                    console.log(element);
                },
                function errorCallback(error) {
                    unauthenticated(error);
                    deferred.reject(error);
                }
            );
            return deferred.promise;
        },
        saveElement: function (element, data) {
            var deferred = $q.defer();
            $http({
                method: "POST",
                url: BASE_URL + "/" + element,
                headers: {
                    "Content-Type": "application/json",
                },
                data: data,
            }).then(
                function successCallback(response) {
                    factory.data = response["data"];
                    deferred.resolve(factory.data);
                },
                function errorCallback(error) {
                    unauthenticated(error);
                    deferred.reject(msg_erreur);
                }
            );
            return deferred.promise;
        },
        generatePdf: function (element, data) {
            var deferred = $q.defer();
            $http({
                method: "POST",
                url: BASE_URL + "" + element,
                headers: {
                    "Content-Type": "application/json",
                },
                data: data,
            }).then(
                function successCallback(response) {
                    factory.data = response["data"];
                    deferred.resolve(factory.data);
                },
                function errorCallback(error) {
                    unauthenticated(error);
                    deferred.reject(msg_erreur);
                }
            );
            return deferred.promise;
        },
        changeStatut: function (element, data) {
            var deferred = $q.defer();
            $http({
                method: "POST",
                url: BASE_URL + element + "/statut",
                headers: {
                    "Content-Type": "application/json",
                },
                data: data,
            }).then(
                function successCallback(response) {
                    factory.data = response["data"];
                    console.log(response);
                    deferred.resolve(factory.data);
                },
                function errorCallback(error) {
                    unauthenticated(error);
                    deferred.reject(msg_erreur);
                }
            );
            return deferred.promise;
        },
        saveElementAjax: function (element, data, is_file_excel = false) {
            var deferred = $q.defer();
            $.ajax({
                url: BASE_URL + element + (is_file_excel ? "/import" : ""),
                type: "POST",
                contentType: false,
                processData: false,
                DataType: "text",
                data: data,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                beforeSend: function () {
                    $("#modal_add" + element).blockUI_start();
                },
                success: function (response) {
                    $("#modal_add" + element).blockUI_stop();
                    factory.data = response;
                    deferred.resolve(factory.data);
                },
                error: function (error) {
                    unauthenticated(error);
                    $("#modal_add" + element).blockUI_stop();
                    deferred.reject(msg_erreur);
                },
            });
            return deferred.promise;
        },
        removeElement: function (element, id) {
            var deferred = $q.defer();
            $http({
                method: "DELETE",
                url: BASE_URL + element + "/" + id,
                headers: {
                    "Content-Type": "application/json",
                },
            }).then(
                function successCallback(response) {
                    factory.data = response["data"];
                    deferred.resolve(factory.data);
                },
                function errorCallback(error) {
                    unauthenticated(error);
                    deferred.reject(msg_erreur);
                }
            );
            return deferred.promise;
        },
        userPermission: function (object) {
            var deferred = $q.defer();
            $http({
                method: "POST",
                url: BASE_URL + "notifuser",
                headers: {
                    "Content-Type": "application/json",
                },
                data: object,
            }).then(
                function successCallback(response) {
                    factory.data = response["data"];
                    deferred.resolve(factory.data);
                },
                function errorCallback(error) {
                    unauthenticated(error);
                    deferred.reject(msg_erreur);
                }
            );
            return deferred.promise;
        },
        generateExcel: function (element, data) {
            var deferred = $q.defer();
            $http({
                method: "GET",
                url: BASE_URL + element + "/" + data,
                headers: {
                    "Content-Type": "application/json",
                },
            }).then(
                function successCallback(response) {
                    factory.data = response["data"];
                    deferred.resolve(factory.data);
                },
                function errorCallback(error) {
                    unauthenticated(error);
                    deferred.reject(msg_erreur);
                }
            );
            return deferred.promise;
        },
    };

    return factory;
});

//--DEBUT ==> Configuration des routes--//
app.config(function ($routeProvider) {
    $routeProvider.when("/:namepage?/:itemId?", {
        templateUrl: function (elem, attrs) {
            return "page/" + (elem["namepage"] ? elem["namepage"] : "dashboard");
        },
    });
});

// app.component('signatureComponent', {
//     template: '<canvas id="signatureCanvas"></canvas>',
//     controller: BackEndCtl
// });
// app.directive('signaturePad', function () {
//     return {
//       restrict: 'A',
//       link: function (scope, element, attrs) {
//         var canvas = element[0];
//         var signaturePad = new SignaturePad(canvas);

//         // Vous pouvez ajouter des gestionnaires d'événements pour gérer la capture de la signature ici
//       }
//     };
//   });

// TODO: delete

//--FIN ==> Configuration des routes--//

// Spécification fonctionnelle du controller
app.controller(
    "BackEndCtl",
    function (
        Init,
        userLogged,
        theme,
        $location,
        $scope,
        $filter,
        $log,
        $q,
        $route,
        $routeParams,
        $timeout,
        $compile,
        $http,
        $parse
    ) {
        $scope.signatureDirecteur = null;
        $scope.imgupload = imgupload;
        userLogged.loginUser($("#userLogged").val());
        $scope.userConnected = userLogged.isLogged();
        $scope.BASE_URL = BASE_URL;
        $scope.param = null;
        $scope.currentTemplateUrl;
        $scope.titlePage;
        $scope.titleDetailsContratImmo;
        $scope.titleDetailsContratAppart;
        $scope.titleDetailsContratLocataire;

        $scope.check;

        $scope.signaturePad = null; // Initialisez la référence à SignaturePad
        angular.element(document).ready(function () {
            const canvas = document.querySelector("canvas[signature-pad]");

            if (canvas) {
                $scope.signaturePad = new SignaturePad(canvas, {
                    minWidth: 1,
                    maxWidth: 2,
                    penColor: "rgb(66, 133, 244)",
                });

                // Gestionnaire d'événement pour effacer la signature
                const clearButton = document.querySelector("#clear-button");

                if (clearButton) {
                    clearButton.addEventListener("click", function () {
                        $scope.signaturePad.clear(); // Effacez la signature sur le canvas
                    });
                }
            } else {
                console.error("Canvas element not found.");
            }

            // Gestionnaire d'événement pour enregistrer la signature
            const saveButton = document.querySelector("#save-button");

            if (saveButton) {
                saveButton.addEventListener("click", function () {
                    if ($scope.signaturePad) {
                        // Convertissez la signature en base64
                        const signatureData = $scope.signaturePad.toDataURL();
                        $("#signature_signaturecontrat").val(signatureData);
                        // Envoyez signatureData au serveur AngularJS pour le traitement
                        // Vous pouvez utiliser $http pour envoyer les données au backend.

                        console.log(
                            "Signature enregistrée en tant que base64 :",
                            signatureData
                        );
                    }
                });
            }
        });

        $(document).ready(function () {
            var dateActuelle = new Date();
            // Obtenez le mois suivant
            var moisSuivant = new Date(dateActuelle);
            moisSuivant.setMonth(moisSuivant.getMonth() + 1);
            // Fixez la date du mois suivant au 5 du mois
            var echeance = $("#echeance_contrat").val();
            moisSuivant.setDate(echeance);
            // Comparaison des dates pour décider si le bouton doit être affiché ou masqué
            var boutonFacture = $("#ajouterFactureButton");
            if (dateActuelle < moisSuivant) {
                boutonFacture.show(); // Affiche le bouton
            } else {
                boutonFacture.hide(); // Masque le bouton
            }
        });

        $scope.redirectDetails = (url, id) => {
            $location.url("/" + url + "/" + id);
        };
        $scope.redirectUrl = (url, id) => {
            $location.url("/" + url + "/" + id);
        };
        $scope.myRedirectUrl = (url) => {
            $location.url("/" + url);
        };
        $scope.redirectPdf = function (identifiant) {
            window.open(`${identifiant}`, "_blank");
        };

        // $scope.redirectPdf2 = function (identifiant) {

        //     window.open(`${identifiant}`,'_blank');
        // } ;

        $scope.showRelance = function (type) {
            console.log("ici relance type ", type);
            // $letrre = 'Bonjour cher client,Pour non règlement de votre loyer à date échue, une pénalité de 10% du montant vous sera appliquée à votre prochain paiement'
            if (type == 3) {
                console.log("ici relance type 3: ", type);
                let infosContrat = $scope.dataPage["locationventes"][0];
                console.log(
                    "ici relance type 32 : ",
                    infosContrat.relance_type
                );
                $("#body_inbox").val(infosContrat.relance_type).change();
            } else {
                $("#body_inbox")
                    .val(
                        $scope.dataPage["locationventes"][0][
                        "message_rappel_paiement"
                        ]
                    )
                    .change();
            }
        };
        document.getElementById("appartement_pieceappartement").onchange =
            function () {
                var idApp = document.getElementById(
                    "appartement_pieceappartement"
                ).value;
                for (let element of $scope.dataPage["appartements"]) {
                    if (idApp === element.id) {
                        console.log(element);
                        document.getElementById(
                            "immeuble_pieceappartement"
                        ).innerHTML =
                            "<option value=" +
                            element["immeuble"].id +
                            ' selected class="required">' +
                            element["immeuble"].nom +
                            "</option>";
                        console.log(
                            document.getElementById("immeuble_pieceappartement")
                                .value
                        );
                        //  document.getElementById('pieceappartement_immeuble').value = element['immeuble'].id ;
                    }
                }
            };

        document.getElementById("typepiece_pieceappartement").onchange =
            function () {
                var idApp = document.getElementById(
                    "typepiece_pieceappartement"
                ).value;
                console.log(idApp);
                for (let element of $scope.dataPage["typepieces"]) {
                    if (idApp === element.id) {
                        if (element.iscommun == "0") {
                            $(".appartementpieceappartement").show();
                        } else if (element.iscommun == "1") {
                            console.log("verifié");
                            $(".appartementpieceappartement").hide();
                            document.getElementById(
                                "immeuble_pieceappartement"
                            ).innerHTML = " ";
                            $("#immeuble_pieceappartement").append(
                                '<option class="required">immeuble</option>'
                            );
                            for (let element2 of $scope.dataPage["immeubles"]) {
                                console.log(element2.nom);
                                $("#immeuble_pieceappartement").append(
                                    "<option value=" +
                                    element2.id +
                                    ' class="required">' +
                                    element2.nom +
                                    "</option>"
                                );
                            }
                        }
                    }
                }
            };

        document.getElementById("priseencharge_locataire").onchange =
            function () {
                if (
                    document.getElementById("priseencharge_locataire").checked
                ) {
                    $("#nomcompletpersonnepriseencharge_locataire").show();
                    $("#telephonepersonnepriseencharge_locataire").show();
                } else {
                    $("#nomcompletpersonnepriseencharge_locataire").hide();
                    $("#telephonepersonnepriseencharge_locataire").hide();
                }
            };

        document.getElementById("immeuble_appartement").onchange = function () {
            var idImmeuble = document.getElementById(
                "immeuble_appartement"
            ).value;
            for (let element of $scope.dataPage["immeubles"]) {
                if (idImmeuble === element.id) {
                    document.getElementById("niveau_appartement").innerHTML =
                        '<option class="required">niveau</option><option value=\'Rez de chaussée\' class="required">Rez de chaussée</option>';
                    document.getElementById("niveau_appartement").innerHTML +=
                        "<option value='MEZZANINE' class=\"required\">MEZZANINE</option>";
                    let iter = 0;
                    for (
                        let pas = 1;
                        pas <= element.structureimmeuble.etages;
                        pas++
                    ) {
                        if (pas == 1) {
                            $("#niveau_appartement").append(
                                '<option value="' +
                                pas +
                                'er étage" class="required">' +
                                pas +
                                "er étage</option>"
                            );
                        } else {
                            $("#niveau_appartement").append(
                                '<option value="' +
                                pas +
                                'eme étage" class="required">' +
                                pas +
                                "eme étage</option>"
                            );
                        }
                    }
                }
            }
        };

        document.getElementById("locataire_paiementloyer").onchange =
            function () {
                var year = new Date().getFullYear();
                var idApp = document.getElementById(
                    "locataire_paiementloyer"
                ).value;
                // console.log(idApp) ;
                //    console.log("locataires ",$scope.dataPage['locataires']) ;
                for (let element of $scope.dataPage["locataires"]) {
                    console.log(element.id);
                    if (idApp === element.id) {
                        // document.getElementById('periode_paiementloyer').innerHTML = "<option value='' >periode</option>" ;
                        // document.getElementById(
                        //     "appartement_paiementloyer"
                        // ).innerHTML =
                        //     "<option value='' >appartement / villa</option>";
                        document.getElementById(
                            "contrat_paiementloyer"
                        ).innerHTML = "<option value='' >contrat</option>";
                        document.getElementById(
                            "datepaiement_paiementloyer"
                        ).valueAsDate = new Date();
                        console.log("my contrat 2023 ", element["contrats"]);
                        for (let element2 of element["contrats"]) {
                            if (element2.etat) {
                                $("#contrat_paiementloyer").append(
                                    "<option value=" +
                                    element2.id +
                                    ' selected class="required">' +
                                    element2.descriptif +
                                    "</option>"
                                );
                                // $("#montantfacture_paiementloyer").prop(
                                //     "disabled",
                                //     true
                                // );
                                // $("#montantfacture_paiementloyer").val(
                                //     element2.montantloyer
                                // );
                                console.log(
                                    document.getElementById(
                                        "contrat_paiementloyer"
                                    ).value
                                );
                            }
                        }
                    }

                    var year = new Date().getFullYear();
                    var idApp = document.getElementById(
                        "locataire_paiementloyer"
                    ).value;
                    // console.log(idApp) ;
                    //    console.log("locataires ",$scope.dataPage['locataires']) ;
                    for (let element of $scope.dataPage["locataires"]) {
                        console.log(element.id);
                        if (idApp === element.id) {
                            // document.getElementById('periode_paiementloyer').innerHTML = "<option value='' >periode</option>" ;
                            //    document.getElementById('appartement_paiementloyer').innerHTML = "<option value='' >appartement / villa</option>" ;
                            document.getElementById(
                                "contrat_paiementloyer"
                            ).innerHTML = "<option value='' >contrat</option>";
                            document.getElementById(
                                "datepaiement_paiementloyer"
                            ).valueAsDate = new Date();
                            console.log(
                                "my contrat 2023 ",
                                element["contrats"]
                            );
                            for (let element2 of element["contrats"]) {
                                if (element2.etat) {
                                    $("#contrat_paiementloyer").append(
                                        "<option value=" +
                                        element2.id +
                                        ' selected class="required">' +
                                        element2.descriptif +
                                        "</option>"
                                    );
                                    // $( "#montantfacture_paiementloyer" ).prop( "disabled", true );
                                    // $('#montantfacture_paiementloyer').val(element2.montantloyer);
                                    console.log(
                                        document.getElementById(
                                            "contrat_paiementloyer"
                                        ).value
                                    );

                                    $(
                                        "#montant_periodepaiementloyer_paiementloyer"
                                    ).val(element2.montantloyer);

                                    // if (element2.nom) {
                                    //     $('#appartement_paiementloyer').append("<option value="+element2.appartement.id+" selected class=\"required\">"+element2.appartement.nom+"</option>")  ;
                                    // }else {
                                    //     $('#appartement_paiementloyer').append("<option value="+element2.appartement.id+" selected class=\"required\">Ilot : "+element2.appartement.ilot.numero+" / "+element2.appartement.ilot.adresse+"</option>")  ;
                                    // }

                                    if (
                                        element2.appartement
                                            .frequencepaiementappartement_id ==
                                        "1"
                                    ) {
                                        // $('#periode_paiementloyer').append('<option value="janvier '+year+'">janvier '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="fevrier '+year+'">fevrier '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="mars '+year+'" >mars '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="avril '+year+'">avril '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="mai '+year+'" >mai '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="juin '+year+'" >juin '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="juillet '+year+'" >juillet '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="aout '+year+'" >aout '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="septembre '+year+'" >septembre '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="octobre '+year+'" >octobre '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="novembre '+year+'" >novembre '+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="decembre '+year+'">decembre '+year+'</option>')  ;
                                    }
                                    if (
                                        element2.appartement
                                            .frequencepaiementappartement_id ==
                                        "2"
                                    ) {
                                        // var year1 = year - 4;
                                        // var year2 = year - 3;
                                        // var year3 = year - 2;
                                        // var year4 = year - 1;
                                        // var year5 = year + 1;
                                        // var year6 = year + 2;
                                        // var year7 = year + 3;
                                        // var year8 = year + 4;
                                        // var year9 = year + 5;
                                        // var year10 = year + 6;
                                        // $('#periode_paiementloyer').append('<option value="'+year1+'">'+year1+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year2+'">'+year2+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year3+'" >'+year3+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year4+'">'+year4+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year+'">'+year+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year5+'" >'+year5+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year6+'" >'+year6+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year7+'" >'+year7+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year8+'" >'+year8+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year9+'" >'+year9+'</option>')  ;
                                        // $('#periode_paiementloyer').append('<option value="'+year10+'" >'+year10+'</option>')  ;
                                    }
                                    console.log(
                                        "element2 " + JSON.stringify(element2)
                                    );

                                    if (element2.periodicite) {
                                        // $("#periodicite_paiementloyer")
                                        //     .val(element2.periodicite.id)
                                        //     .change();
                                    }
                                    // var selectElement = $("#periodes_paiementloyer");
                                    // selectElement.val([]);
                                    // $.each(element2.periodes_non_payes, function(index, value) {
                                    //     console.log("val item "+value)
                                    //     selectElement.find('option[value="' + value.id + '"]').prop('selected', true);
                                    // });
                                    // selectElement.change();
                                }
                            }
                        }
                    }
                }
            };

        document.getElementById("intervention_rapportintervention").onchange =
            function () {
                var idIntervention = document.getElementById(
                    "intervention_rapportintervention"
                ).value;
                console.log(idIntervention);
                for (let element of $scope.dataPage["interventions"]) {
                    if (idIntervention === element.id) {
                        if (element.demandeintervention.immeuble) {
                            document.getElementById(
                                "immeuble_rapportintervention"
                            ).innerHTML =
                                "<option value=" +
                                element.demandeintervention.immeuble.id +
                                ' selected class="required">' +
                                element.demandeintervention.immeuble.nom +
                                "</option>";
                        }
                        if (element.demandeintervention.appartement) {
                            $("#divappartement_rapportintervention").show();
                            document.getElementById(
                                "appartement_rapportintervention"
                            ).innerHTML =
                                "<option value=" +
                                element.demandeintervention.appartement.id +
                                ' selected class="required">' +
                                element.demandeintervention.appartement.nom +
                                "</option>";
                        }
                        if (!element.demandeintervention.appartement) {
                            $("#divappartement_rapportintervention").hide();
                            document.getElementById(
                                "appartement_rapportintervention"
                            ).innerHTML =
                                '<option value="" selected class="required">appartement</option>';
                        }
                    }
                }
            };

        document.getElementById("appartement_versementloyer").onchange =
            function () {
                var idApp = document.getElementById(
                    "appartement_versementloyer"
                ).value;
                console.log(idApp);
                for (let element of $scope.dataPage["appartements"]) {
                    if (idApp === element.id) {
                        for (let element2 of element["contrats"]) {
                            console.log(element2);
                            if (element2.etat === 1) {
                                document.getElementById(
                                    "contrat_versementloyer"
                                ).innerHTML =
                                    "<option value=" +
                                    element2.id +
                                    ' selected class="required">' +
                                    element2.descriptif +
                                    "</option>";
                                if (element2.locataire.prenom) {
                                    document.getElementById(
                                        "locataire_versementloyer"
                                    ).innerHTML =
                                        "<option value=" +
                                        element2.locataire.id +
                                        ' selected class="required">' +
                                        element2.locataire.prenom +
                                        " " +
                                        element2.locataire.nom +
                                        "</option>";
                                }
                                if (element2.locataire.nomentreprise) {
                                    document.getElementById(
                                        "locataire_versementloyer"
                                    ).innerHTML =
                                        "<option value=" +
                                        element2.locataire.id +
                                        ' selected class="required">' +
                                        element2.locataire.nomentreprise +
                                        "</option>";
                                }
                                //  console.log(element2);
                                document.getElementById(
                                    "proprietaire_versementloyer"
                                ).innerHTML =
                                    "<option value=" +
                                    element2.appartement.proprietaire.id +
                                    ' selected class="required">' +
                                    element2.appartement.proprietaire.prenom +
                                    " " +
                                    element2.appartement.proprietaire.nom +
                                    "</option>";
                                console.log(
                                    document.getElementById(
                                        "contrat_paiementloyer"
                                    ).value
                                );
                            }
                        }
                    }
                }
            };

        document.getElementById("locataire_versementloyer").onchange =
            function () {
                document.getElementById(
                    "appartement_versementloyer"
                ).innerHTML = "<option value='' >appartement</option>";
                document.getElementById("contrat_versementloyer").innerHTML =
                    "<option value='' >contrat</option>";

                var idLocataire = document.getElementById(
                    "locataire_versementloyer"
                ).value;
                console.log(idLocataire);
                for (let element of $scope.dataPage["locataires"]) {
                    if (idLocataire === element.id) {
                        for (let element2 of element["contrats"]) {
                            console.log(element2);
                            if (element2.etat === 1) {
                                $("#contrat_versementloyer").append(
                                    "<option value=" +
                                    element2.id +
                                    ' selected class="required">' +
                                    element2.descriptif +
                                    "</option>"
                                );
                                $("#appartement_versementloyer").append(
                                    "<option value=" +
                                    element2.appartement.id +
                                    ' selected class="required">' +
                                    element2.appartement.nom +
                                    "</option>"
                                );
                                //  console.log(element2);
                                document.getElementById(
                                    "proprietaire_versementloyer"
                                ).innerHTML =
                                    "<option value=" +
                                    element2.appartement.proprietaire.id +
                                    ' selected class="required">' +
                                    element2.appartement.proprietaire.prenom +
                                    " " +
                                    element2.appartement.proprietaire.nom +
                                    "</option>";
                                //    console.log(document.getElementById('contrat_paiementloyer').value) ;
                            }
                        }
                    }
                }
            };

        /* document.getElementById('appartement_etatlieu').onchange = function(){

document.getElementById('locataire_etatlieu').innerHTML = "<option value='' >appartement</option>" ;
var idApp = document.getElementById('appartement_etatlieu').value ;
console.log($scope.showpieceId) ;
$scope.showpieceId = idApp ;
console.log($scope.showpieceId) ;
for (let element of $scope.dataPage['appartements']) {

if(idApp === element.id){

    for (let element2 of element['contrats'] ) {

        if(element2.etat === "1"){
            if(element2.locataire.prenom){
                document.getElementById('locataire_etatlieu').innerHTML = "<option value="+element2.locataire.id+" selected class=\"required\">"+element2.locataire.prenom+ ' '+element2.locataire.nom+"</option>" ;
            }
            if(element2.locataire.nomentreprise){
                document.getElementById('locataire_etatlieu').innerHTML = "<option value="+element2.locataire.id+" selected class=\"required\">"+element2.locataire.nomentreprise+"</option>" ;
            }
            console.log(document.getElementById('locataire_etatlieu').value) ;
        }
    }
}
}

for (let element of $scope.dataPage['pieceappartements']) {

if(element.appartement && idApp === element.appartement.id){
        console.log('there') ;
        console.log($scope.showpieceId) ;

        $scope.reInit();
        identifiant++ ;
}
}
console.log($scope.showpieceId) ;
};*/

        /*document.getElementById('locataire_etatlieu').onchange = function(){

document.getElementById('appartement_etatlieu').innerHTML = "<option value='' >appartement</option>" ;
//document.getElementById('contrat_versementloyer').innerHTML = "<option value='' >contrat</option>" ;

var idLocataire = document.getElementById('locataire_etatlieu').value ;
console.log(idLocataire) ;
for (let element of $scope.dataPage['locataires']) {

if(idLocataire === element.id){
    for (let element2 of element['contrats'] ) {
        console.log(element2) ;
        if(element2.etat === "1"){
           // $('#contrat_etatlieu').append("<option value="+element2.id+" selected class=\"required\">"+element2.descriptif+"</option>") ;
            $('#appartement_etatlieu').append("<option value="+element2.appartement.id+" selected class=\"required\">"+element2.appartement.nom+"</option>")  ;
            //  console.log(element2);
         //   document.getElementById('proprietaire_versementloyer').innerHTML = "<option value="+element2.appartement.proprietaire.id+" selected class=\"required\">"+element2.appartement.proprietaire.prenom+ ' '+element2.appartement.proprietaire.nom+"</option>" ;
            console.log(document.getElementById('contrat_paiementloyer').value) ;
        }
    }
}
}

};*/
        document.getElementById("immeuble_obligationadministrative").onchange =
            function () {
                document.getElementById(
                    "appartement_obligationadministrative"
                ).innerHTML = "<option value='' >appartement</option>";
                var idApp = document.getElementById(
                    "immeuble_obligationadministrative"
                ).value;
                console.log(idApp);
                for (let element of $scope.dataPage["immeubles"]) {
                    if (idApp === element.id) {
                        for (let element2 of element["appartements"]) {
                            if (element2.immeuble.id === idApp) {
                                $(
                                    "#appartement_obligationadministrative"
                                ).append(
                                    "<option value=" +
                                    element2.id +
                                    ' class="required">' +
                                    element2.nom +
                                    "</option>"
                                );
                                console.log(
                                    document.getElementById(
                                        "appartement_obligationadministrative"
                                    ).value
                                );
                            }
                        }
                    }
                }
            };

        document.getElementById("immeuble_annonce").onchange = function () {
            document.getElementById("appartement_annonce").innerHTML =
                "<option value='' >appartement</option>";
            var idApp = document.getElementById("immeuble_annonce").value;
            console.log(idApp);
            for (let element of $scope.dataPage["immeubles"]) {
                if (idApp === element.id) {
                    for (let element2 of element["appartements"]) {
                        if (element2.immeuble.id === idApp) {
                            $("#appartement_annonce").append(
                                "<option value=" +
                                element2.id +
                                ' class="required">' +
                                element2.nom +
                                "</option>"
                            );
                            console.log(
                                document.getElementById("appartement_annonce")
                                    .value
                            );
                        }
                    }
                }
            }
        };

        document.getElementById("immeuble_demandeintervention").onchange =
            function () {
                var idType = document.getElementById(
                    "typeintervention_demandeintervention"
                ).value;

                var idImmeuble = document.getElementById(
                    "immeuble_demandeintervention"
                ).value;

                // console.log(idApp) ;
                if (idType == 1) {
                    $("#typeappartementdiv").hide();
                    $("#typelocatairediv").hide();
                    $("#typeappartementdiv").val("");
                    $("#appartement_demandeintervention").val("");
                    $("#locataire_demandeintervention").val("");
                    $("#typelocatairediv").val("");

                    $("#typeimmeublediv").show();
                }
                if (idType == 2) {
                    $("#typeimmeublediv").hide();
                    $("#typeimmeublediv").val("");
                    $("#typepiece_demandeintervention").val("");
                    $("#typeappartementdiv").show();
                    $("#typelocatairediv").show();

                    var idImmeuble2 = document.getElementById(
                        "immeuble_demandeintervention"
                    ).value;
                    for (let element of $scope.dataPage["immeubles"]) {
                        if (idImmeuble2 === element.id) {
                            console.log(idImmeuble2, "test de  immeuble");
                            document.getElementById(
                                "appartement_demandeintervention"
                            ).innerHTML = '"<option>Appartement</option>"';
                            for (let element2 of element["appartements"]) {
                                console.log(element2);
                                if (element2.immeuble.id === idImmeuble) {
                                    $(
                                        "#appartement_demandeintervention"
                                    ).append(
                                        "<option value=" +
                                        element2.id +
                                        ' class="required">' +
                                        element2.nom +
                                        "</option>"
                                    );
                                    //console.log(document.getElementById('appartement_intervention').value) ;
                                }
                            }
                        }
                    }
                }
            };

        document.getElementById("typefacture_facture").onchange = function () {
            var idType = document.getElementById("typefacture_facture").value;
            // console.log(idApp) ;
            if (idType == 1) {
                $(".interventionfacture").show();
            } else {
                $("#intervention_facture").val("");
                $(".interventionfacture").hide();
                $(".appartementfacture").show();
            }
        };

        document.getElementById("appartement_demandeintervention").onchange =
            function () {
                var idAppartement = document.getElementById(
                    "appartement_demandeintervention"
                ).value;
                console.log(idAppartement);
                for (let element of $scope.dataPage["appartements"]) {
                    if (idAppartement === element.id) {
                        console.log("there");
                        for (let element2 of element["contrats"]) {
                            if (element2.etat === 2) {
                                if (element2.locataire.prenom) {
                                    document.getElementById(
                                        "locataire_demandeintervention"
                                    ).innerHTML =
                                        "<option value=" +
                                        element2.locataire.id +
                                        ' selected class="required">' +
                                        element2.locataire.prenom +
                                        " " +
                                        element2.locataire.nom +
                                        "</option>";
                                }
                                if (element2.locataire.nomentreprise) {
                                    document.getElementById(
                                        "locataire_demandeintervention"
                                    ).innerHTML =
                                        "<option value=" +
                                        element2.locataire.id +
                                        ' selected class="required">' +
                                        element2.locataire.nomentreprise +
                                        "</option>";
                                }
                            }
                        }
                    }
                }
            };

        document.getElementById("locataire_demandeintervention").onchange =
            function () {
                document.getElementById(
                    "appartement_demandeintervention"
                ).innerHTML = "<option value='' >appartement</option>";
                //document.getElementById('contrat_versementloyer').innerHTML = "<option value='' >contrat</option>" ;

                var idLocataire = document.getElementById(
                    "locataire_demandeintervention"
                ).value;
                console.log(idLocataire);
                for (let element of $scope.dataPage["locataires"]) {
                    if (idLocataire === element.id) {
                        for (let element2 of element["contrats"]) {
                            console.log(element2);
                            if (element2.etat === 2) {
                                // $('#contrat_etatlieu').append("<option value="+element2.id+" selected class=\"required\">"+element2.descriptif+"</option>") ;
                                $("#appartement_demandeintervention").append(
                                    "<option value=" +
                                    element2.appartement.id +
                                    ' selected class="required">' +
                                    element2.appartement.nom +
                                    "</option>"
                                );
                                //  console.log(element2);
                                //   document.getElementById('proprietaire_versementloyer').innerHTML = "<option value="+element2.appartement.proprietaire.id+" selected class=\"required\">"+element2.appartement.proprietaire.prenom+ ' '+element2.appartement.proprietaire.nom+"</option>" ;
                                console.log(
                                    document.getElementById(
                                        "contrat_paiementloyer"
                                    ).value
                                );
                            }
                        }
                    }
                }
            };

        document.getElementById("appartement_demanderesiliation").onchange =
            function () {
                var idAppartement = document.getElementById(
                    "appartement_demanderesiliation"
                ).value;
                console.log(idAppartement);
                for (let element of $scope.dataPage["appartements"]) {
                    if (idAppartement === element.id) {
                        console.log("there");
                        for (let element2 of element["contrats"]) {
                            if (element2.etat === "1") {
                                $("#datedebutcontrat_demanderesiliation").prop(
                                    "disabled",
                                    true
                                );
                                $("#datedebutcontrat_demanderesiliation").val(
                                    element2.datedebutcontrat
                                );
                                if (element2.locataire.prenom) {
                                    document.getElementById(
                                        "locataire_demanderesiliation"
                                    ).innerHTML =
                                        "<option value=" +
                                        element2.locataire.id +
                                        ' selected class="required">' +
                                        element2.locataire.prenom +
                                        " " +
                                        element2.locataire.nom +
                                        "</option>";
                                }
                                if (element2.locataire.nomentreprise) {
                                    document.getElementById(
                                        "locataire_demanderesiliation"
                                    ).innerHTML =
                                        "<option value=" +
                                        element2.locataire.id +
                                        ' selected class="required">' +
                                        element2.locataire.nomentreprise +
                                        "</option>";
                                }
                                document.getElementById(
                                    "contrat_demanderesiliation"
                                ).innerHTML =
                                    "<option value=" +
                                    element2.id +
                                    ' selected class="required">' +
                                    element2.descriptif +
                                    "</option>";
                                // console.log(document.getElementById('locataire_etatlieu').value) ;
                            }
                        }
                    }
                }
            };

        document.getElementById("datedemande_demanderesiliation").onchange =
            function () {
                if ($("#dateeffectivite_demanderesiliation").val()) {
                    var startdate = new Date(
                        $("#datedemande_demanderesiliation").val()
                    );
                    var enddate = new Date(
                        $("#dateeffectivite_demanderesiliation").val()
                    );
                    console.log(startdate);
                    // end - start returns difference in milliseconds

                    var millisecondsPerDay = 1000 * 60 * 60 * 24;
                    var millisBetween = enddate.getTime() - startdate.getTime();

                    var days = millisBetween / millisecondsPerDay;

                    // Round down.
                    var diff = Math.floor(days);

                    console.log($("#contrat_demanderesiliation").val());
                    for (let element of $scope.dataPage["contrats"]) {
                        if (
                            element.id ===
                            $("#contrat_demanderesiliation").val()
                        ) {
                            if (diff > 31 && element.delaipreavi.id == 1) {
                                $("#raison").show();
                                $(
                                    "#raisonnonrespectdelai_demanderesiliation"
                                ).show();
                            } else if (
                                diff < 31 &&
                                element.delaipreavi.id == 1
                            ) {
                                $("#raison").hide();
                                $(
                                    "#raisonnonrespectdelai_demanderesiliation"
                                ).hide();
                            } else if (
                                diff > 62 &&
                                element.delaipreavi.id == 2
                            ) {
                                $("#raison").show();
                                $(
                                    "#raisonnonrespectdelai_demanderesiliation"
                                ).show();
                            } else if (
                                diff < 62 &&
                                element.delaipreavi.id == 2
                            ) {
                                $("#raison").hide();
                                $(
                                    "#raisonnonrespectdelai_demanderesiliation"
                                ).hide();
                            }
                        }
                    }
                }
            };

        document.getElementById("dateeffectivite_demanderesiliation").onchange =
            function () {
                if ($("#datedemande_demanderesiliation").val()) {
                    var startdate = new Date(
                        $("#datedemande_demanderesiliation").val()
                    );
                    var enddate = new Date(
                        $("#dateeffectivite_demanderesiliation").val()
                    );
                    console.log(startdate);
                    // end - start returns difference in milliseconds

                    var millisecondsPerDay = 1000 * 60 * 60 * 24;
                    var millisBetween = enddate.getTime() - startdate.getTime();

                    var days = millisBetween / millisecondsPerDay;

                    // Round down.
                    var diff = Math.floor(days);

                    console.log($("#contrat_demanderesiliation").val());
                    for (let element of $scope.dataPage["contrats"]) {
                        if (
                            element.id ===
                            $("#contrat_demanderesiliation").val()
                        ) {
                            if (diff > 31 && element.delaipreavi.id == 1) {
                                $("#raison").show();
                                $(
                                    "#raisonnonrespectdelai_demanderesiliation"
                                ).show();
                            } else if (
                                diff < 31 &&
                                element.delaipreavi.id == 1
                            ) {
                                $("#raison").hide();
                                $(
                                    "#raisonnonrespectdelai_demanderesiliation"
                                ).hide();
                            } else if (
                                diff > 62 &&
                                element.delaipreavi.id == 2
                            ) {
                                $("#raison").show();
                                $(
                                    "#raisonnonrespectdelai_demanderesiliation"
                                ).show();
                            } else if (
                                diff < 62 &&
                                element.delaipreavi.id == 2
                            ) {
                                $("#raison").hide();
                                $(
                                    "#raisonnonrespectdelai_demanderesiliation"
                                ).hide();
                            }
                        }
                    }
                }
            };

        document.getElementById("locataire_demanderesiliation").onchange =
            function () {
                document.getElementById(
                    "appartement_demanderesiliation"
                ).innerHTML = "<option value='' >appartement</option>";
                //document.getElementById('contrat_versementloyer').innerHTML = "<option value='' >contrat</option>" ;

                var idLocataire = document.getElementById(
                    "locataire_demanderesiliation"
                ).value;
                console.log(idLocataire);
                for (let element of $scope.dataPage["locataires"]) {
                    if (idLocataire === element.id) {
                        for (let element2 of element["contrats"]) {
                            console.log(element2);
                            // if (element2.etat === 2) {
                            $("#datedebutcontrat_demanderesiliation").prop(
                                "disabled",
                                true
                            );
                            $("#datedebutcontrat_demanderesiliation").val(
                                element2.datedebutcontrat
                            );
                            $("#contrat_demanderesiliation").append(
                                "<option value=" +
                                element2.id +
                                ' selected class="required">' +
                                element2.descriptif +
                                "</option>"
                            );
                            $("#appartement_demanderesiliation").append(
                                "<option value=" +
                                element2.appartement.id +
                                ' selected class="required">' +
                                element2.appartement.nom +
                                "</option>"
                            );
                            //  console.log(element2);
                            //   document.getElementById('proprietaire_versementloyer').innerHTML = "<option value="+element2.appartement.proprietaire.id+" selected class=\"required\">"+element2.appartement.proprietaire.prenom+ ' '+element2.appartement.proprietaire.nom+"</option>" ;
                            //   console.log(document.getElementById('contrat_paiementloyer').value) ;
                            // }
                        }
                    }
                }
            };

        document.getElementById("immeuble_facture").onchange = function () {
            var idimmeuble = document.getElementById("immeuble_facture").value;
            console.log(idimmeuble);
            document.getElementById("appartement_facture").innerHTML =
                "<option value='' >appartement</option>";
            for (let element of $scope.dataPage["immeubles"]) {
                if (idimmeuble === element.id) {
                    console.log("there");
                    for (let element2 of element["appartements"]) {
                        if (element2.immeuble.id === idimmeuble) {
                            $("#appartement_facture").append(
                                "<option value=" +
                                element2.id +
                                ' class="required">' +
                                element2.nom +
                                "</option>"
                            );
                            // console.log(document.getElementById('locataire_etatlieu').value) ;
                        }
                    }
                }
            }
        };

        document.getElementById(
            "typeintervention_demandeintervention"
        ).onchange = function () {
            console.log($("#immeuble_demandeintervention").val());
            if ($("#immeuble_demandeintervention").val() !== "") {
                var idType = document.getElementById(
                    "typeintervention_demandeintervention"
                ).value;
                // console.log(idApp) ;
                if (idType == 1) {
                    $("#appartement_demandeintervention").val("");
                    $("#locataire_demandeintervention").val("");
                    $("#typeappartementdiv").hide();
                    $("#typelocatairediv").hide();
                    $("#typeappartementdiv").val("");
                    $("#typelocatairediv").val("");
                    $("#typeimmeublediv").show();
                }
                if (idType == 2) {
                    $("#typepiece_demandeintervention").val("");
                    $("#typeimmeublediv").hide();
                    $("#typeimmeublediv").val("");
                    $("#typeappartementdiv").show();
                    $("#typelocatairediv").show();
                }
            }
        };

        //nouveau element
        var listofrequests_assoc = {
            //-------------DEBUT ==> MES REQUETES PERSONNALISEES--------------------//
            //markme-LISTE

            //carteproduits{id,designation,famille_id,famille{id,designation}}
            permissions: ["id,name,display_name,guard_name,designation"],
            roles: [
                "id,name,guard_name,permissions{id,name,display_name,guard_name}",
            ],
            users: [
                "id,name,email,image,uploadsignature,locataire_id,locataire{id},roles{id,name},entite_id,entite{id,image,designation,code},created_at_fr",
            ],
            notifpermusers: [
                "id,permission_id,user_id,notif_id,notif{id,message,link,created_at_fr},link",
            ],
            notifs: ["id,message,link,created_at_fr"],
            typeappartements: [
                "id,designation,usage,appartements{id},typeappartement_pieces{id,designation,typepiece{id,designation,iscommun}}",
            ],
            typeassurances: ["id,designation,assurances{id}"],
            structureimmeubles: ["id,designation,immeubles{id},etages"],
            niveauappartements: ["id,designation,nombre,appartements{id}"],
            typecontrats: ["id,designation,contrats{id}"],
            typedocuments: ["id,designation,documents{id}"],
            typefactures: ["id,designation,factures{id}"],
            typeinterventions: ["id,designation,interventions{id},code"],
            typelocataires: ["id,designation,locataires{id}"],
            typeobligationadministratives: [
                "id,designation,obligationadministratives{id}",
            ],
            typepieces: [
                "id,designation,iscommun,immeubles{id},typepieceniveauappartements{id,typepiece_id,niveauappartement_id,niveauappartement{id,designation}}",
            ],
            pieceimmeubles: [
                "id,immeuble{id,nom},typepiece{id,designation},immeuble_id,typepiece_id",
            ],
            typerenouvellements: ["id,designation,contrats{id}"],
            imageappartements: [
                "id,image,imagecompteur,appartement{id,nom},appartement_id",
            ],
            imagecompositions: [
                "id,image,imagecompteur,composition{id},composition_id",
            ],
            imageetatlieupieces: [
                "id,image,imagecompteur,imagecompteur,etatlieu_piece{id},etatlieupiece_id",
            ],
            securites: [
                "id,designation,etat,adresse,telephone1,telephone2,prestataire{id,nom,telephone1,telephone2,adresse},immeuble{id,nom},horaire{id,designation,debut,fin},prestataire_id,immeuble_id,horaire_id",
            ],
            typeappartement_pieces: [
                "id,designation,typeappartement{id,designation},typepiece{id,designation},commentaire,typeappartement_id",
            ],
            locataire_messages: ["id,locataire_id,message_id"],
            compositions: [
                "id, image , superficie, typeappartement_piece{id,designation,typeappartement{id,designation},niveauappartement_id,niveauappartement{id,designation},typepiece_id,typepiece{id,designation},commentaire,typeappartement_id},appartement{id,nom},appartement_id,typeappartement_piece_id,niveauappartement_id,niveauappartement{id,designation}",
            ],
            typepieceniveauappartements: [
                "id,typepiece_id,typepiece{id,designation},niveauappartement_id,niveauappartement{id,designation}",
            ],
            detailcompositions: [
                "id,idDetailtypeappartement ,appartement_id,appartement{id},composition{id,typeappartement_piece{id,designation,typeappartement{id,designation},typepiece{id,designation},commentaire,typeappartement_id},appartement{id,nom},appartement_id,typeappartement_piece_id},equipement{id,designation,generale},equipement_id,composition_id",
            ],
            detailconstituants: [
                "id,commentaire,constituantpiece{id,designation},observation{id,designation},etatlieu_piece{id,image,etatlieu{id,designation},composition{id},etatlieu_id,composition_id},etatlieu_piece_id,constituantpiece_id,observation_id",
            ],
            detailequipements: [
                "id,commentaire,equipementpiece{id,designation,generale},observation{id,designation}, etatlieu_piece{id,image,etatlieu{id,designation},composition{id},etatlieu_id,composition_id},etatlieu_piece_id,equipementpiece_id,observation_id",
            ],
            observations: ["id,designation"],
            fonctions: ["id,designation"],
            cautions: [
                "id,document,montantloyer,montantcaution,codeappartement,dateversement,datepaiement,etat,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id},versementchargecoproprietes{id}}",
            ],
            pieceappartements: [
                "id,designation,appartement{id,nom} ,immeuble{id,nom},typepiece{id,designation} ,appartement_id,immeuble_id,typepiece{id,designation,iscommun},etatlieus{id}",
            ],
            immeubles: [
                "id,nom,adresse,tauxoccupation,nbreappartements,nbreappartementsvide,nbreappartementslouer,gardien{id,prenom,nom,adresse,telephone1,telephone2}, structureimmeuble_id , structureimmeuble{id,designation,etages}, equipegestion{id,designation}, nombreappartement,nombregroupeelectrogene, nombreascenseur,nombrepiscine,iscopropriete,equipegestion{id,designation},pieceappartements{id,designation},proprietaires{id,nom},appartements{id,nom,codeappartement,immeuble{id}},annonces{id,titre,debut,fin,description},created_at_fr",
            ],
            gardiens: ["id,prenom,nom,adresse,telephone1,telephone2"],
            appartements: [
                "id,dernierlocation,position,location_details,daterenouvellement,lot_ilot_refact,acompte_format,acompte_percent,codeappartement,isdemanderesiliation , locataire_id, niveau , image, etatlieu , superficie , immeuble_id,nom,isassurance,iscontrat,islocataire,immeuble{id,nom,adresse,equipegestion{id,designation}}, proprietaire{id,prenom,nom} ,typeappartement{id,designation}, frequencepaiementappartement{id,designation},etatappartement{id,designation,etat_text,etat_badge},pieceappartements{id,designation},locataires{id},contrats{id,appartement{id,nom,proprietaire{id,nom,prenom}} , codeappartement,document,scanpreavis,descriptif,documentretourcaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,typecontrat{id},typerenouvellement{id},delaipreavi{id},locataire{id,prenom,nom,nomentreprise},caution{id,document,montantloyer,montantcaution,codeappartement,dateversement,datepaiement,etat,contrat_id},etat},factures{id},annonces{id},contrats{id},entite_id,entite{id,image,designation,code},ilot_id,ilot{id,numero,adresse},lot,prixvilla,acomptevilla,maturite,periodicite_id,periodicite{id,designation},contratproprietaire_id,contratproprietaire{id,entite_id,proprietaire_id,modelcontrat_id,commissionvaleur,commissionpourcentage,is_tva,is_brs,is_tlv,descriptif},commissionvaleur,commissionpourcentage,tva,brs,tlv,montantloyer,montantcaution,montantvilla,prixappartement,typevente,documentappartements{id,document,nom}",
            ],
            villas: [
                "id,lot_ilot_refact,acompte_format,acompte_percent,codeappartement,prixvillaformat, isdemanderesiliation , locataire_id, niveau , image, etatlieu , superficie , immeuble_id,nom,isassurance,iscontrat,islocataire,immeuble{id,nom,adresse,equipegestion{id,designation}}, proprietaire{id,prenom,nom} ,typeappartement{id,designation}, frequencepaiementappartement{id,designation},etatappartement{id,designation,etat_text,etat_badge},pieceappartements{id,designation},locataires{id},contrats{id,appartement{id,nom,proprietaire{id,nom,prenom}} , codeappartement,document,scanpreavis,descriptif,documentretourcaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,typecontrat{id},typerenouvellement{id},delaipreavi{id},locataire{id,prenom,nom,nomentreprise},caution{id,document,montantloyer,montantcaution,codeappartement,dateversement,datepaiement,etat,contrat_id},etat},factures{id},annonces{id},contrats{id},entite_id,entite{id,image,designation,code},ilot_id,ilot{id,numero,adresse,numerotitrefoncier,datetitrefoncier,adressetitrefoncier},lot,prixvilla,acomptevilla,maturite,periodicite_id,periodicite{id,designation}",
            ],
            appartement_locataires: [
                "id,appartement{id,nom},locataire{id,pre},image,roles{id,name},created_at_fr",
            ],
            immeuble_proprietaires: [
                "id,immeuble{id,nom},proprietaire{id,nom}",
            ],
            equipegestion_membreequipegestions: [
                "id,equipegestion{id,designation},membreequipegestion{id,prenom,nom,telephone,email},fonction{id,designation}",
            ],
            rapportinterventions: [
                "id,prenom,compagnietechnicien, intervention{id} ,immeuble{id,nom}, debut,fin,duree,observations,etat,recommandations,appartement{id,nom,immeuble{id,nom}}",
            ],
            proprietaires: [
                "id,nom,prenom,adresse,telephone,profession,age,telephoneportable,telephonebureau,immeubles{id,nom,adresse,nombreascenseur,nombrepiscine,iscopropriete},appartements{id,nom,codeappartement},versementloyers{id}",
            ],
            prestataires: [
                "id,nom,adresse,email,telephone1,telephone2,interventions{id},contacts{id},contratprestations{id},categorieprestataire{id}",
            ],
            contratprestations: [
                "id,datesignaturecontrat,datesignaturecontrat_format,datedemarragecontrat,datedemarragecontrat_format,daterenouvellementcontrat,daterenouvellementcontrat_format,datepremiereprestation,datepremiereprestation_format,datepremierefacture,datepremierefacture_format,document,montant,montant_format,frequencepaiementappartement{id,designation},categorieprestation{id,designation},prestataire{id,nom}",
            ],
            categorieprestations: ["id,designation"],
            categorieprestataires: ["id,designation"],
            horaires: ["id,designation,debut,fin"],
            equipegestions: ["id,designation,immeubles{id,nom,adresse}"],
            membreequipegestions: [
                "id,prenom,nom,email,telephone,interventions{id,descriptif},demandeinterventions{id} ",
            ],
            factures: [
                "id,datefacture,moisfacture,documentfacture,datefacture_format,montant_format,recupaiement,montant,intervenantassocie,periode,partiecommune,typefacture_id,intervention{id,descriptif,etat,categorieintervention{id,designation},typeintervention{id,designation},demandeintervention{id,designation,locataire{prenom,nom,nomentreprise},appartement{id,nom,immeuble{id,nom}},membreequipegestion{id},immeuble{id,nom},isgeneral},prestataire{id,nom},locataire{prenom,nom,nomentreprise}},typefacture{id,designation},appartement{id,nom,immeuble{id,nom}},immeuble_id,locataire_id,proprietaire_id,locataire{prenom,nom,nomentreprise},proprietaire{id,nom,prenom}",
            ],
            factureinterventions: [
                "id,montant,est_activer,montant_format,paiementintervention{id est_activer date montant},detailfactureinterventions{id,montant,interventiondetail_text,intervention{id,descriptif}},demandeintervention{id},locataire{id,prenom,nom,nomentreprise},datefacture,montant,intervenantassocie,datefacture_format,montant_format,intervention{id,descriptif,etat,categorieintervention{id,designation},demandeintervention{id,designation,locataire{prenom,nom,nomentreprise},appartement{id,nom,immeuble{id,nom}},membreequipegestion{id},isgeneral},prestataire{id,nom},locataire{prenom,nom,nomentreprise}},typefacture{id,designation},appartement{id,nom},",
            ],
            categorieinterventions: [
                "id,designation,interventions{id etatlieu{id   devi{id  detaildevi_id detaildevis{id   detaildevisdetails{id prixunitaire prixunitaire_format } categorieintervention{id designation} }  }} demandeintervention{id   devi{id  detaildevi_id detaildevis{id   detaildevisdetails{id prixunitaire prixunitaire_format } categorieintervention{id designation} }  }}}",
            ],
            assurances: [
                "id,descriptif,montant,debut,fin,document,typeassurance{id,designation}, prestataire{id,nom}, assureur{id},etatassurance{id,designation},contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id},versementchargecoproprietes{id}}",
            ],
            paiementloyers: [
                "id,datepaiement,codefacture,numero_cheque,montantfacture,montantfacture_format,montant_paiement,periode,modepaiement{id,designation,code},detailpaiements{id,montant,periode_id,periode_text,periode{id,designation}},debutperiodevalide,finperiodevalide,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise}, locataire_id , caution{id},versementloyers{id}}",
            ],
            demanderesiliations: [
                "id,datedebutcontrat,datedebutcontrat_format,document,motif,retourcaution,datedemande_format,dateeffectivite_format,datedemande,etat,etat_text,etat_badge,delaipreavisrespecte,raisonnonrespectdelai,delaipreavi{id,designation},dateeffectivite,contrat{id, descriptif ,appartement{id,nom,etatlieu,iscontrat,isdemanderesiliation,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise,email,adresse,telephoneportable1,adresseentreprise}},document,contrat_id",
            ],
            frequencepaiementappartements: ["id,designation"],
            etatappartements: ["id,designation"],
            typequestionnaires: ["id,designation"],
            questionnaires: [
                "id,designation,nom,nombre,reponsetype,typequestionnaire{id,designation}",
            ],
            interventions: [
                "id,descriptif,rapportintervention{id},membreequipegestion{id,nom,prenom}, dateintervention,datefinintervention,etat,categorieintervention{id,designation},typeintervention{id,designation},etatlieu{id},demandeintervention{id,designation,locataire{id,prenom,nom,nomentreprise},appartement{id,nom,immeuble{id,nom}},membreequipegestion{id},immeuble{id,nom},isgeneral},prestataire{id,nom},locataire{prenom,nom,nomentreprise},facture{id},",
            ],
            commentaireinterventions: [
                "id,description,prestataire{id},user{id},locataire{id},intervention{id},prestataire_id,user_id,locataire_id,intervention_id",
            ],
            imageinterventions: ["id,image,intervention{id},intervention_id"],
            etatlieus: [
                "id,appartement_id,locataire{id,nom,prenom,adresse,adresseentreprise,nomentreprise},factureintervention{id},designation,type,devi{id,est_activer,code},dateredaction,particularite,etatgenerale,appartement{id,nom,contrats{id,caution{id},locataire{id}}},locataire{id,prenom,nom,nomentreprise,contrats{id,caution{id}}}",
            ],
            etatlieu_pieces: [
                "id,image,etatlieu{id,designation},composition{id},etatlieu_id,composition_id",
            ],
            locataires: [
                "id,est_copreuneur,copreneurs{ id , nom ,prenom ,email ,adresse, lieunaissance, datenaissance, situationfamiliale, codepostal , ville , pays , nationalite, profession , njf , cni , passeport , telephone1 , telephone2 },prenom,numeroclient,date_naissance,lieux_naissance,pays_naissance,mandataire,secteuractivite{id,designation},user_id,user{id},nom,revenus,expatlocale,nomcompletpersonnepriseencharge,telephonepersonnepriseencharge,telephoneportable1,telephoneportable2,telephonebureau,profession, email,age,cni,passeport,nomentreprise,adresseentreprise,ninea,documentninea,numerorg,documentnumerorg,documentstatut,personnehabiliteasigner,fonctionpersonnehabilite,nompersonneacontacter,prenompersonneacontacter,emailpersonneacontacter,telephone1personneacontacter,telephone2personneacontacter,etatlocataire, revenus, contrattravail , expatlocale , nomcompletpersonnepriseencharge , telephonepersonnepriseencharge ,typelocataire_id,observation_id,appartements{id,nom,lot,ilot{id,numero,adresse},proprietaire{id,prenom,nom}},contrats{id,descriptif,etat,datedebutcontrat,montantloyer,periodicite{id,designation},periodes_non_payes{id,designation},appartement{id,nom,lot,ilot{id,numero,adresse},frequencepaiementappartement_id,proprietaire{id,prenom,nom}}},entite_id,entite{id,image,designation,code}",
            ],
            copreneurs: [
                "id , locataire_id , locataire{id nom prenom email} , nom ,prenom ,email ,adresse, lieunaissance, datenaissance, situationfamiliale, codepostal , ville , pays , nationalite, profession , njf , cni , passeport , telephone1 , telephone2",
            ],
            demandeinterventions: [
                "id,designation,image,interventions{id},appartement_id,devi{id,code,est_activer},typepiece{id,designation},locataire{id,prenom,nom,nomentreprise},appartement{id,nom,immeuble{id,nom}},membreequipegestion{id},immeuble{id,nom},isgeneral",
            ],
            obligationadministratives: [
                "id,designation,debut,fin,montant,debut_format,fin_format,montant_format,document,typeobligationadministrative_id,immeuble_id,appartement_id,typeobligationadministrative{id,designation},immeuble{id,nom},appartement{id,nom}",
            ],
            factureprestataires: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            calendriers: ["id,name,email,image,roles{id,name},created_at_fr"],
            resiliationbails: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            contrats: [
                "id,nombre_relance_loyer,factureeauxs{id,paiement_id,justificatif_paiement,soldeanterieur,montantfacture,finperiode,is_paid_text,is_paid,is_paid_badge},montant_dernier_facture_eau,date_dernier_facture_eau,frais_gestion_format,frais_gestion,show_echeance,acompte_percent,nombre_relance,acompteinitial_format,message_rappel_paiement,derniere_facture_loyer{id,montant,is_paid,is_paid_text,is_paid_badge,contrat_id,datefacture,objetfacture,typefacture_id,typefacture{id,designation}},dateecheance,total_loyer_format,signaturedirecteur,signatureclient,document,periodicite_id,periodicite{id,designation,nbr_mois},retourcaution,status,etat,etat_text,etat_badge,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerformat,montantloyerbase,montantloyerbaseformat,montantloyertom,montantloyertomformat,montantcharge,montantchargeformat,tauxrevision,frequencerevision,dateenregistrement,dateenregistrement_format,daterenouvellement,daterenouvellement_format,datepremierpaiement,datepremierpaiement_format,dateretourcaution,datedebutcontrat,datedebutcontrat_format,rappelpaiement,etat,typecontrat{id,designation},typerenouvellement{id,designation},delaipreavi{id,designation},appartement{id,nom,iscontrat,isdemanderesiliation,codeappartement,etatlieu,proprietaire{id,prenom,nom},immeuble{id,nom,adresse}, ilot{id,numero,adresse} , lot},locataire{id,mandataire,date_naissance,pays_naissance,lieux_naissance,prenom,passeport,nom,telephoneportable1,telephoneportable2,telephonebureau,profession, email,age,cni,passeport,nomentreprise,adresseentreprise,ninea,documentninea,numerorg,documentnumerorg,documentstatut,personnehabiliteasigner,fonctionpersonnehabilite,nompersonneacontacter,prenompersonneacontacter,emailpersonneacontacter,telephone1personneacontacter,telephone2personneacontacter,etatlocataire, revenus, contrattravail , expatlocale , nomcompletpersonnepriseencharge , telephonepersonnepriseencharge ,typelocataire_id,typelocataire{id,designation},observation_id,appartements{id,nom,proprietaire{id,prenom,nom}},contrats{id,descriptif,etat,datedebutcontrat,montantloyer,appartement{id,nom,frequencepaiementappartement_id,proprietaire{id,prenom,nom}}}},caution{id,montantcaution,dateversement,document},assurances{id},versementloyers{id},versementchargecoproprietes{id},paiementloyers{id,datepaiement,codefacture,montantfacture,periode,debutperiodevalide,finperiodevalide,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id,designation},delaipreavi{id,designation},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id},versementchargecoproprietes{id}}},demanderesiliations{id},periodes_non_payes{id,designation},annexes{id , filename , filepath , numero ,contrat_id },nomcompletbeneficiaire,telephonebeneficiaire,emailbeneficiaire",
            ],
            avenants: [
                "id,contrat_id,dateecheance,total_loyer_format,periodicite{id,designation,nbr_mois},est_activer,etat_text,etat_badge,descriptif,montantloyer,montantloyerformat,montantloyerbase,montantloyerbaseformat,montantloyertom,montantloyertomformat,montantcharge,montantchargeformat,tauxrevision,frequencerevision,dateenregistrement,dateenregistrement_format,typecontrat{id,designation},typerenouvellement{id,designation},delaipreavi{id,designation},appartement{id,nom}",
            ],

            annexes: ["id,filename ,filepath ,numero ,contrat_id"],
            locationventes: [
                "id,est_copreuneur ,email , relance_type, est_soumis , copreneur_id,copreneur{ id , nom ,prenom ,email ,adresse, lieunaissance, datenaissance, situationfamiliale, codepostal , ville , pays , nationalite, profession , njf , cni , passeport , telephone1 , telephone2 },numerodossier,derniere_facture_echeance{id,fraisgestion,fraisdelocation,amortissement,date,date_fr,date_echeance_format,objet, montant_total , total_montant},ridwan_montant_verse,ridwan_montant_restant,etatlieu_sortie{id,appartement_id},etatlieu_entree{id,appartement_id},signaturedirecteur,signatureclient,frais_gestion_format,frais_gestion,show_echeance,acompte_percent,document,acompteinitial_format,nombre_relance,nombre_relance_echeance,scanpreavis,message_rappel_paiement,acompte_valeur,reliquat,derniere_facture_loyer{id,is_paid,is_paid_text,is_paid_badge,contrat_id,datefacture,objetfacture,typefacture_id,typefacture{id,designation}},depot_initial,depot_initial_format,prixvilla,prixvillaformat,acompteinitial,maturite,periodicite_id,periodicite{id,designation},dateremisecles,dateremiseclesformat,apportinitial,apportinitial_format,acompteinitial_format,fraisdegestion_format,fraisdegestion,fraislocative_format,fraislocative,codepartamortissemnt_format,codepartamortissemnt,apportiponctuel,dateecheance,dateecheanceformat,dureelocationvente,clausepenale,fraiscoutlocationvente,acompteinitial,prixvilla,indemnite,document,retourcaution,status,etat_text,etat_badge,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerformat,montantloyerbase,montantloyerbaseformat,montantloyertom,montantloyertomformat,montantcharge,montantchargeformat,tauxrevision,frequencerevision,dateenregistrement,dateenregistrement_format,daterenouvellement,daterenouvellement_format,datepremierpaiement,datepremierpaiement_format,dateretourcaution,datedebutcontrat,datedebutcontrat_format,rappelpaiement,etat,typecontrat{id,designation},typerenouvellement{id,designation},delaipreavi{id,designation},appartement{id,nom,acomptevilla,acompte_format,lot,ilot_id,ilot{id,numero,adresse,numerotitrefoncier,datetitrefoncier,adressetitrefoncier},iscontrat,isdemanderesiliation,codeappartement,etatlieu,proprietaire{id,prenom,nom},immeuble{id,nom,adresse}},locataire{id,mandataire,date_naissance,pays_naissance,lieux_naissance,prenom,passeport,nom,telephoneportable1,telephoneportable2,telephonebureau,profession, email,age,cni,passeport,nomentreprise,adresseentreprise,ninea,documentninea,numerorg,documentnumerorg,documentstatut,personnehabiliteasigner,fonctionpersonnehabilite,nompersonneacontacter,prenompersonneacontacter,emailpersonneacontacter,telephone1personneacontacter,telephone2personneacontacter,etatlocataire, revenus, contrattravail , expatlocale , nomcompletpersonnepriseencharge , telephonepersonnepriseencharge ,typelocataire_id,typelocataire{id,designation},observation_id,soldeclient,soldeclient_format,appartements{id,nom,proprietaire{id,prenom,nom}},contrats{id,descriptif,etat,datedebutcontrat,montantloyer,appartement{id,nom,frequencepaiementappartement_id,proprietaire{id,prenom,nom}}},interventions{id},messages{id},questionnairesatisfactions{id}},caution{id,montantcaution,dateversement,document},assurances{id},versementloyers{id},versementchargecoproprietes{id},paiementloyers{id,datepaiement,codefacture,montantfacture,periode,debutperiodevalide,finperiodevalide,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id,designation},delaipreavi{id,designation},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id},versementchargecoproprietes{id}}},demanderesiliations{id},annexes{id , filename , filepath , numero ,contrat_id }",
            ],

            constituantpieces: ["id,designation"],
            detailinterventions: [
                "id,intervention_id,detailconstituant_id,detailequipement_id,intervention{id},detailconstituant{id},detailequiment{id}",
            ],
            equipementpieces: ["id,designation,generale"],
            delaipreavis: ["id,designation"],
            produitsutilises: ["id,designation"],
            financeimmeubles: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            financeappartements: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            situationcompteclients: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            versementloyers: [
                "id,dateversement,dateversement_format,debut,debut_format,fin,fin_format,montant,montant_format,document,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id},versementchargecoproprietes{id}},proprietaire{id,nom,prenom,adresse,telephone,profession,age,telephoneportable,telephonebureau,immeubles{id,nom,adresse,nombreascenseur,nombrepiscine,iscopropriete},appartements{id,nom,codeappartement},versementloyers{id},questionnairesatisfactions{id}}",
            ],
            versementchargecoproprietes: [
                "id,dateversement,dateversement_format,anneecouverte,montant,montant_format,document,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id},versementchargecoproprietes{id}},proprietaire{id,nom,prenom,adresse,telephone,profession,age,telephoneportable,telephonebureau,immeubles{id,nom,adresse,nombreascenseur,nombrepiscine,iscopropriete},appartements{id,nom,codeappartement},versementloyers{id},versementchargecoproprietes{id},messages{id},questionnairesatisfactions{id}}",
            ],
            messages: [
                "id,objet,contenu,locataires{id,prenom,nom},proprietaires{id,prenom,nom}, documents{id,chemin}",
            ],
            annonces: [
                "id,titre,debut,concernes,fin,description,immeuble{id,nom},appartement{id,nom,immeuble{id,nom}}",
            ],
            questionnairesatisfactions: [
                "id,titre,contenu,locataires{id,prenom,nom,nomentreprise},proprietaires{id,prenom,nom}",
            ],
            travauximmresidents: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            travauximmgestionnaires: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            travauxappresidents: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            travauxappgestionnaires: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            repertoireresidents: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            repertoireproprietaires: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            repertoireprestataires: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            repertoireemployes: [
                "id,name,email,image,roles{id,name},created_at_fr",
            ],
            ilots: [
                "id,numero,adresse,nombrevilla,numerotitrefoncier,adressetitrefoncier,datetitrefoncier,created_at_fr",
            ],
            entites: [
                "id,designation,tauxoccupation,fraisgestion,amortissement,fraisdelocation,nbreretardloyer{mois,total,totalecheance,nombre_payer_a_temps,nombre_de_retards},nbreappartements,nbreappartementsvide,nbreappartementslouer,location,vente,description,image,gestionnaire_id,gestionnaire{id,name,email},code,created_at_fr,nomcompletnotaire, adressenotaire,adresseetudenotaire,emailnotaire, telephone1notaire , nometudenotaire, emailetudenotaire,  telephoneetudenotaire , assistantetudenotaire,entiteusers{user_id,entite_id,user{id,name,email}},infobancaires{id,banque,agence,codebanque,codeguichet,datedebut,datefin,clerib,numerocompte,entite_id} ,appartements{id}",
            ],
            periodicites: ["id,designation,description,created_at_fr"],
            facturelocations: [
                "id,montant,montant_total,paiement_id,justificatif_paiement,date_echeance,is_paid_badge,is_paid,is_paid_text,contrat_id,datefacture,datefacture_format,objetfacture,typefacture_id,nbremoiscausion,periodicite_id,contrat{id montantloyer montantloyerformat locataire_id locataire{id,nom,prenom} appartement_id appartement{id,nom} factureeauxs{id,finperiode,montantfacture,soldeanterieur} descriptif appartement{nom immeuble{adresse}} locataire{prenom nom id} montantloyertom montantloyerbase montantcharge}, periodicite{id description}, typefacture{id designation}",
            ],
            periodes: ["id,designation,description,created_at_fr"],
            modepaiements: ["id,designation,description,code,created_at_fr"],
            secteuractivites: ["id,designation,description,created_at_fr"],
            inboxs: [
                "id,subject,heure_envoie,body,sender_email,user_id,user{id,name,email},appartement_id,appartement{id,nom,immeuble{id,nom,adresse}},locataire_id,locataire{id,nom,prenom,nomentreprise},attachements{id,filename,filepath,inbox_id},created_at_fr",
            ],

            devis: [
                "date  object demandeintervention_id code est_activer etatlieu{id}  id date    object  detaildevis{id  categorieintervention{id designation} categorieintervention_id  detaildevisdetails{ id  unite{id designation} quantite prixunitaire  id soustypeintervention{id designation}  detaildevi{id devi{id  } categorieintervention{id  designation}} } }    id demandeintervention{id appartement{id nom entite{id image designation description } } immeuble{nom adresse}   }",
            ],
            soustypeinterventions: [
                "id,designation,categorieintervention{id},categorieintervention_id",
            ],
            detaildevisdetails: [
                "    unite{id} quantite prixunitaire prixunitaire_format id soustypeintervention{id designation}  detaildevi{id  categorieintervention{id designation} devi{id est_activer code date object demandeintervention{id appartement{id nom entite{id designation description } } immeuble{nom adresse}}} categorieintervention{id  designation}}",
            ],
            unites: ["id designation"],
            detaildevis: [
                "detaildevisdetails{id} devi_id categorieintervention{id designation}",
            ],
            paiementinterventions: ["id,montant,est_activer"],
            factureeauxs: [
                "id,dateecheance,dateecheance_fr,demanderesiliation_id,is_paid_badge,is_paid,is_paid_text,finperiode,montantfacture,soldeanterieur,montanttotalfacture,montanttotalfacture_format,contrat{id,locataire_id,appartement_id}",
            ],

            avisecheances: [
                "id,total_montant, signature,id_paiement ,montant_total,etat_text,etat_badge,est_activer,annee_echeance_format,date_echeance,date_echeance_format,mois_echeance_format,periodicite_id,contrat_id,objet,date,date_fr,total_montant,periodes, contrat{id montantloyerbase apportinitial prixvilla prixvillaformat total_loyer_format total_loyer_format total_loyer montantloyer frais_gestion_format frais_gestion echeance_encours recap_amount_ridwan recap_amount_ridwan_format dateecheanceformat montantloyerformat locataire{nomentreprise nom prenom email, soldeclient, soldeclient_format} montantloyertom appartement{id,nom,immeuble{id,nom} lot ilot{id,numero,adresse}}  montantcharge descriptif } periodicite{id designation} justificatif_paiement fraisupplementaires{id designation frais},get_montantenattente,get_montantenattente_format,paiementecheance{id,montant,date,periodes,montant_format,modepaiement{designation}},get_all_paiementecheances{id,montant,date,periodes,montant_format,modepaiement{designation},avisecheance_id,etat}",
            ],
            paiementecheances: [
                "id,montant,date,datepaiement_format,montant_format,periodes,modepaiement_id,modepaiement{id,designation},montant_format_letter,numero_cheque,avisecheance_id,avisecheance{id ,montant_total,est_activer,contrat_id,contrat{appartement{id,nom,immeuble{id,nom} lot ilot{id,numero,adresse}},locataire{nomentreprise nom prenom email}} ,justificatif_paiement, fraisgestion , amortissement , date , date_echeance},montantenattente,etat,montantencaisse",
            ],
            factureacomptes: [
                "id,montant,montant_format,datefacture_format,etat_text,etat_badge,est_activer,date_echeance,date_echeance_format,contrat_id,commentaire,date, contrat{ id montantloyerbase apportinitial prixvilla prixvillaformat total_loyer_format total_loyer_format total_loyer montantloyer frais_gestion_format frais_gestion echeance_encours recap_amount_ridwan recap_amount_ridwan_format dateecheanceformat montantloyerformat locataire{nomentreprise nom prenom email} montantloyertom appartement{id,nom,immeuble{id,nom} lot ilot{id,numero,adresse}}  montantcharge descriptif }",
            ],
            historiquerelances: ["id,avisecheance_id,contrat_id"],
            etatencaissements: [
                "id ,email,locataire{nom,prenom} ,etatencaissementdetail{totalAmortissement,totalFraisgestion,totalFraislocatif,total},appartement{ilot{numero},lot}",
            ],
            historiquerelances: ["id,avisecheance_id,contrat_id"],
            typeapportponctuels: ["id,designation,description"],
            apportponctuels: [
                "id,date,montant,contrat_id,contrat{id,descriptif},typeapportponctuel_id,typeapportponctuel{id,designation},observations",
            ],
            activites: ["id,designation,description"],
            taxes: ["id,designation,description,valeur"],
            modelcontrats: ["id,designation,description"],
            contratproprietaires: [
                "id,date,descriptif,commissionvaleur,commissionpourcentage,is_tva,is_tva_text,is_brs,is_brs_text,is_tlv,is_tlv_text,entite_id,entite{id,designation,description},proprietaire_id,proprietaire{id,nom,prenom},modelcontrat_id,modelcontrat{id,designation,description}",
            ],
            //-------------FIN ==> MES REQUETES PERSONNALISEES--------------------//
        };
        $scope.orderBy = function (type, collumn) {
            // $scope.orderby  =",odre:"+collumn;
            var direction;
            $scope.collumn = collumn;

            if (!$scope[collumn] || !$scope[collumn]["direction"]) {
                console.log(collumn);
                var the_string = collumn + ".direction";

                var model = $parse(the_string);

                model.assign($scope, 1);
            } else {
                var the_string = collumn + ".direction";

                var model = $parse(the_string);

                model.assign($scope, null);
            }
            console.log($scope[collumn]["direction"]);
            if ($scope[collumn]["direction"]) {
                direction = "ASC";
            } else {
                direction = "DESC";
            }

            var filter =
                ",order:" + `"${collumn}",direction:` + `"${direction}"`;

            $scope.orderby = filter;

            $scope.pageChanged(type);
        };

        $scope.searchPermission = function (e, action = true) {
            if (action) {
                var name = $scope.searchtexte_list_permission;
                var searchPermissions = [];
                $scope.dataPage["permissions"].forEach((item) => {
                    if (item.display_name.contains(name)) {
                        searchPermissions.push(item);
                    }
                });
                $scope.dataPage["permissions"] = [];
                $scope.dataPage["permissions"] = searchPermissions;
                $scope.dataPage["permissions"].forEach((item) => {
                    var index = $scope.dataPage["permissions"].indexOf(item);
                    var checked = $("#permission_role_" + index).prop(
                        "checked"
                    );
                    if (checked) {
                        $("#permission_role_" + index).prop("checked", true);
                    } else {
                        $("#permission_role_" + index).prop("checked", false);
                    }
                });
            } else {
                $scope.searchtexte_list_permission = null;
                var filterPermission = $scope.dataPage["permissions"];
                $scope.dataPage["permissions"] = [];
                for (var i = 0; i < filterPermission.length; i++) {
                    var search = $filter("filter")($scope.temponPermissions, {
                        display_name: filterPermission[i]["display_name"],
                    });

                    if (search && search.length == 1) {
                        var index = $scope.temponPermissions.indexOf(search[0]);
                        if (index > -1) {
                            $scope.temponPermissions[index] = search[0];
                            var checked = $("#permission_role_" + index).prop(
                                "checked"
                            );
                            if (checked) {
                                $("#permission_role_" + index).prop(
                                    "checked",
                                    true
                                );
                            } else {
                                $("#permission_role_" + index).prop(
                                    "checked",
                                    false
                                );
                            }
                        }
                    }
                }
                $scope.dataPage["permissions"] = $scope.temponPermissions;
            }
        };

        $scope.switchTheme = function (newTheme) {
            newTheme = "theme-" + newTheme;
            var begin = "theme";
            $("body")
                .removeClass(function (index, className) {
                    return (
                        className.match(
                            new RegExp("\\b" + begin + "\\S+", "g")
                        ) || []
                    ).join(" ");
                })
                .addClass(newTheme);
            theme.setCurrent(newTheme);
            $scope.currentTheme = newTheme;
            $scope.getLogoApp();
        };

        //--DE
        // BUT => Formater le prix avec des espaces--//
        $scope.arrondir = function (num) {
            var retour = Math.round(num);
            return retour;
        };
        $scope.formatPrixToMonetaire = function (
            num,
            toFixed = 2,
            round = undefined
        ) {
            //console.log('vente au cash start', num);

            //#tags: monétaire, formataire, prix, montant
            if (!num) {
                num = 0;
            }
            var numParsei = parseFloat(num).toFixed(toFixed); // always 0 decimal digit
            numParsei = parseFloat(numParsei);
            //console.log("RESTE DIVISION ==>"+(numParsei % 1));
            if (round !== undefined) {
                numParsei = round
                    ? Math.round(numParsei)
                    : Math.round(numParsei - (numParsei % 1));
            }
            return (
                numParsei
                    .toString()
                    .replace(".", ",") // replace decimal point character with ,
                    .toString()
                    .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ") + ""
            ); //
        };
        //--FIN => Formater le prix avec des espaces--//
        $scope.updateCheck = function (
            id,
            classeToHide,
            elementTypeName = "checkbox",
            value = 0,
            classeToShow
        ) {
            var valeur = value;
            if (elementTypeName == "select") {
                if (valeur) {
                    $("." + classeToHide).fadeIn("slow");
                } else {
                }
            } else {
                if ($("#" + id).prop("checked") == true) {
                } else {
                    $("." + classeToHide).fadeOut("slow");
                    $("#valeur_" + id).fadeOut("slow");
                    $("#mois_" + id).fadeOut("slow");
                    $("." + classeToShow).fadeIn("slow");
                }
            }
        };
        $scope.testupdateCheck = function (
            id,
            classeToHide,
            elementTypeName = "checkbox",
            value = 0,
            classeToShow,
            btn = false
        ) {
            //$scope.dataPage['adresse_livraison'] = [];
            console.log(
                id,
                classeToHide,
                elementTypeName,
                value,
                classeToShow,
                btn
            );
            if ($("#" + id).prop("checked") == true) {
                if (classeToHide && classeToShow) {
                    $("." + classeToHide).fadeOut("slow");
                    $("." + classeToShow).fadeIn("slow");
                }
                /*if(id=='offert_commande'){
    $scope.showToast('', 'Commande offerte', 'success');
}*/
            } else {
                if (classeToHide && classeToShow) {
                    //$scope.dataPage['adresse_livraison'] = [];
                    $("." + classeToHide).val("");
                    $("." + classeToHide).fadeIn("slow");
                    $("." + classeToShow).fadeOut("slow");
                }
            }
        };
        var identifiant = 1;
        var identifiantLocataire = 1;
        var identifiantProprietaire = 1;
        var identifiantDocument = 1;
        var identifiantImmeuble = 1;
        var identifiantMembreequipegestion = 1;
        var identifiantProduit = 1;
        $scope.compteurImage = 0;
        $scope.compteurImage2 = 0;
        $scope.compteurImage3 = 0;
        $scope.addfields = function (type = null, divId = null) {
            console.log("here");

            if (type == "gestionnaire") {
                console.log("there");
                $("#addgestionnaire").hide();
                $(".divproprietaire").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="prenomgestionnaire_gestionnaire">Prenom</label>\n' +
                        '<input type="hidden" value="1" name="isgestionnaire">' +
                        '<input type="text" id="prenomgestionnaire_gestionnaire" name="prenomgestionnaire" class="input w-full border mt-2 flex-1 required" placeholder="prenom">' +
                        "  </div>" +
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="nomgestionnaire_gestionnaire">Nom</label>\n' +
                        '<input type="text" id="nomgestionnaire_gestionnaire" name="nomgestionnaire" class="input w-full border mt-2 flex-1 required" placeholder="nom">' +
                        "  </div>" +
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="adressegestionnaire_gestionnaire">Adresse</label>\n' +
                        '<input type="text" id="adressegestionnaire_gestionnaire" name="adressegestionnaire" class="input w-full border mt-2 flex-1 required" placeholder="adresse">' +
                        "  </div>" +
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="telephone1gestionnaire_gestionnaire">Telephone</label>\n' +
                        '<input type="text" id="telephone1gestionnaire_gestionnaire" name="telephone1gestionnaire" class="input w-full border mt-2 flex-1 required" placeholder="telephone">' +
                        "  </div>" +
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="telephone2gestionnaire_gestionnaire">Telephone 2</label>\n' +
                        '<input type="text" id="telephone2gestionnaire_gestionnaire" name="telephone2gestionnaire" class="input w-full border mt-2 flex-1 required" placeholder="telephone">' +
                        "  </div> "
                    )($scope)
                );
            }

            if (type == "produit_rapportintervention") {
                console.log("there");
                $("#produitsutilisesdiv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">Choisissez un produit</label>\n' +
                        '<div class="inline-block mt-2 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="produit' +
                        identifiantProduit +
                        '_rapportintervention" name="produit' +
                        identifiantProduit +
                        '" ><option value="" class="required">produit</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["produitsutilises"].forEach((elmt) => {
                    console.log(elmt);
                    $(
                        "#produit" + identifiantProduit + "_rapportintervention"
                    ).append(
                        '<option value="' +
                        elmt.id +
                        '">' +
                        elmt.designation +
                        "</option>"
                    );
                });

                $scope.reInit();
                identifiantProduit++;
            }
            if (type == "etatpiece") {
                console.log("there");
                $("#pieceetatlieudiv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">Choisissez une piece</label>\n' +
                        '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="selectpiece' +
                        identifiant +
                        '_etatlieu" name="typepiece' +
                        identifiant +
                        '" ><option value="" class="required">piece</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["typepieces"].forEach((elmt) => {
                    console.log(elmt);
                    if (elmt.iscommun == "0") {
                        $("#selectpiece" + identifiant + "_etatlieu").append(
                            '<option value="' +
                            elmt.id +
                            '">' +
                            elmt.designation +
                            "</option>"
                        );
                    }
                });
                $scope.dataPage["constituantpieces"].forEach((elmt) => {
                    //  console.log(elmt) ;
                    $("#pieceetatlieudiv").append(
                        '<div style=" padding: 10px;border: 1px solid rgba(0, 0, 0, 0.05)" class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">' +
                        elmt.designation +
                        "</label>\n" +
                        '    <div class="inline-block mt-2 relative w-full"><select id="typepiece' +
                        identifiant +
                        "_constituant_observation_" +
                        elmt.id +
                        '_etatlieu" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" name="typepiece' +
                        identifiant +
                        "_constituant_observation_" +
                        elmt.id +
                        '" ><option value="" class="required">observation</option></select></div>\n' +
                        '    <input type="text" id="typepiece' +
                        identifiant +
                        "_constituant_commentaire_" +
                        elmt.id +
                        '_etatlieu" name="typepiece' +
                        identifiant +
                        "_constituant_commentaire_" +
                        elmt.id +
                        '" class="input w-full border mt-2 flex-1" placeholder="commentaire...">\n' +
                        "  </div> "
                    );
                    $scope.dataPage["observations"].forEach((observation) => {
                        //console.log(elmt) ;
                        $(
                            "#typepiece" +
                            identifiant +
                            "_constituant_observation_" +
                            elmt.id +
                            "_etatlieu"
                        ).append(
                            '<option value="' +
                            observation.id +
                            '">' +
                            observation.designation +
                            "</option>"
                        );
                    });
                });
                $("#pieceetatlieudiv").append(
                    '<div class="col-span-3 sm:col-span-3"></div> '
                );
                $scope.dataPage["equipementpieces"].forEach((elmt) => {
                    //  console.log(elmt) ;
                    if (elmt.generale == "0") {
                        $("#pieceetatlieudiv").append(
                            '<div style=" padding: 10px;border: 1px solid rgba(0, 0, 0, 0.05)" class="col-span-3 sm:col-span-3">\n' +
                            '    <label for="categorietuto_typetuto">' +
                            elmt.designation +
                            "</label>\n" +
                            '    <div class="inline-block mt-2 relative w-full"><select id="typepiece' +
                            identifiant +
                            "_equipement_observation_etatlieu" +
                            elmt.id +
                            '_etatlieu" class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" name="typepiece' +
                            identifiant +
                            "_equipement_observation_" +
                            elmt.id +
                            '" ><option value="" class="required">observation</option></select></div>\n' +
                            '    <input type="text" id="typepiece' +
                            identifiant +
                            "_equipement_commentaire_" +
                            elmt.id +
                            '" name="typepiece' +
                            identifiant +
                            "_equipement_commentaire_" +
                            elmt.id +
                            '" class="input w-full border mt-2 flex-1" placeholder="commentaire...">\n' +
                            "  </div> "
                        );
                        $scope.dataPage["observations"].forEach(
                            (observation) => {
                                //console.log(elmt) ;
                                $(
                                    "#typepiece" +
                                    identifiant +
                                    "_equipement_observation_etatlieu" +
                                    elmt.id +
                                    "_etatlieu"
                                ).append(
                                    '<option value="' +
                                    observation.id +
                                    '">' +
                                    observation.designation +
                                    "</option>"
                                );
                            }
                        );
                    }
                });
                $scope.reInit();
                identifiant++;
            }

            if (type == "immeuble_equipegestion") {
                console.log("there");
                $("#immeubleequipegestiondiv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">Choisissez un immeuble</label>\n' +
                        '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="immeuble' +
                        identifiantImmeuble +
                        '_equipegestion" name="immeuble' +
                        identifiantImmeuble +
                        '" ><option value="" class="required">immeuble</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["immeubles"].forEach((elmt) => {
                    console.log(elmt);
                    $(
                        "#immeuble" + identifiantImmeuble + "_equipegestion"
                    ).append(
                        '<option value="' +
                        elmt.id +
                        '">' +
                        elmt.nom +
                        "</option>"
                    );
                });
                $scope.reInit();
                identifiantImmeuble++;
            }

            if (type == "membreequipegestion_equipegestion") {
                console.log("there");
                $("#membreequipegestiondiv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">Choisissez un membre</label>\n' +
                        '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="membreequipegestion' +
                        identifiantMembreequipegestion +
                        '_equipegestion" name="membreequipegestion' +
                        identifiantMembreequipegestion +
                        '" ><option value="" class="required">membre</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["membreequipegestions"].forEach((elmt) => {
                    console.log(elmt);
                    $(
                        "#membreequipegestion" +
                        identifiantMembreequipegestion +
                        "_equipegestion"
                    ).append(
                        '<option value="' +
                        elmt.id +
                        '">' +
                        elmt.prenom +
                        " " +
                        elmt.nom +
                        "</option>"
                    );
                });

                $("#membreequipegestiondiv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto" class="required">fonction de ce membre</label>\n' +
                        '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="membreequipegestion' +
                        identifiantMembreequipegestion +
                        'fonction_equipegestion" name="membreequipegestion_fonction' +
                        identifiantMembreequipegestion +
                        '" ><option value="" class="required">fonction</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["fonctions"].forEach((elmt) => {
                    console.log(elmt);
                    $(
                        "#membreequipegestion" +
                        identifiantMembreequipegestion +
                        "fonction_equipegestion"
                    ).append(
                        '<option value="' +
                        elmt.id +
                        '">' +
                        elmt.designation +
                        "</option>"
                    );
                });

                $scope.reInit();
                identifiantMembreequipegestion++;
            }

            if (type == "locataire_message") {
                console.log("there ", $scope.infosUserConnected.locataire_id);
                $("#messagelocatairediv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">Choisissez un locataire</label>\n' +
                        '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required search_locataire" id="locataire' +
                        identifiantLocataire +
                        '_message" name="locataire' +
                        identifiantLocataire +
                        '" ><option value="" class="required">locataire</option><option ng-repeat="item in dataPage[\'locataires\']" value="{{item.id}}">\n' +
                        '{{item.prenom}} {{item.nom}} {{item.nomentreprise}}\n' +
                        '</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["locataires"].forEach((elmt) => {
                    console.log(elmt);
                    if (elmt.typelocataire_id == 1) {
                        $(
                            "#locataire" + identifiantLocataire + "_message"
                        ).append(
                            '<option value="' +
                            elmt.id +
                            '">' +
                            elmt.prenom +
                            " " +
                            elmt.nom +
                            "</option>"
                        );
                    }
                    if (elmt.typelocataire_id == 2) {
                        $(
                            "#locataire" + identifiantLocataire + "_message"
                        ).append(
                            '<option value="' +
                            elmt.id +
                            '">' +
                            elmt.nomentreprise +
                            "</option>"
                        );
                    }
                });

                if ($scope.infosUserConnected.locataire_id) {
                    var locataire_id = $scope.infosUserConnected.locataire_id;
                    console.log('ID du locataire connecté : ', locataire_id);

                    $scope.editInSelect2Costum('locataire1', locataire_id, 'message');
                }

                $scope.reInit();
                identifiantLocataire++;
            }

            if (type == "proprietaire_message") {
                console.log("there");
                $("#messagelocatairediv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">Choisissez un proprietaire</label>\n' +
                        '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required search_proprietaire" id="proprietaire' +
                        identifiantProprietaire +
                        '_message" name="proprietaire' +
                        identifiantProprietaire +
                        '" ><option value="" class="required">proprietaire</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["proprietaires"].forEach((elmt) => {
                    console.log(elmt);
                    $(
                        "#proprietaire" + identifiantProprietaire + "_message"
                    ).append(
                        '<option value="' +
                        elmt.id +
                        '">' +
                        elmt.prenom +
                        " " +
                        elmt.nom +
                        "</option>"
                    );
                });
                $scope.reInit();
                identifiantProprietaire++;
            }

            if (type == "document_message") {
                console.log("there");
                $("#messagedocumentdiv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '<label For="document_message">Document</label>\n' +
                        '<input type="file" id="document' +
                        identifiantDocument +
                        '_message" name="document' +
                        identifiantDocument +
                        '" className="input w-full border mt-2 flex-1 required" placeholder="document">\n' +
                        "  </div> "
                    )($scope)
                );
                identifiantDocument++;
            }

            if (type == "locataire_questionnairesatisfaction") {
                console.log("there");
                $("#questionnairelocatairediv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">Choisissez un locataire</label>\n' +
                        '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="locataire' +
                        identifiantLocataire +
                        '_questionnairesatisfaction" name="locataire' +
                        identifiantLocataire +
                        '" ><option value="" class="required">locataire</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["locataires"].forEach((elmt) => {
                    console.log(elmt);
                    if (elmt.typelocataire_id == 1) {
                        $(
                            "#locataire" +
                            identifiantLocataire +
                            "_questionnairesatisfaction"
                        ).append(
                            '<option value="' +
                            elmt.id +
                            '">' +
                            elmt.prenom +
                            " " +
                            elmt.nom +
                            "</option>"
                        );
                    }
                    if (elmt.typelocataire_id == 2) {
                        $(
                            "#locataire" +
                            identifiantLocataire +
                            "_questionnairesatisfaction"
                        ).append(
                            '<option value="' +
                            elmt.id +
                            '">' +
                            elmt.nomentreprise +
                            "</option>"
                        );
                    }
                });
                $scope.reInit();
                identifiantLocataire++;
            }

            if (type == "proprietaire_questionnairesatisfaction") {
                console.log("there");
                $("#questionnairelocatairediv").append(
                    $compile(
                        '<div class="col-span-3 sm:col-span-3">\n' +
                        '    <label for="categorietuto_typetuto">Choisissez un proprietaire</label>\n' +
                        '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="proprietaire' +
                        identifiantProprietaire +
                        '_questionnairesatisfaction" name="proprietaire' +
                        identifiantProprietaire +
                        '" ><option value="" class="required">proprietaire</option></select></div>' +
                        "  </div> "
                    )($scope)
                );
                $scope.dataPage["proprietaires"].forEach((elmt) => {
                    console.log(elmt);
                    $(
                        "#proprietaire" +
                        identifiantProprietaire +
                        "_questionnairesatisfaction"
                    ).append(
                        '<option value="' +
                        elmt.id +
                        '">' +
                        elmt.prenom +
                        " " +
                        elmt.nom +
                        "</option>"
                    );
                });
                $scope.reInit();
                identifiantProprietaire++;
            }

            if (type == "photo_piece") {
                var source = $("#imagesource").val();
                $scope.compteurImage++;
                //  console.log(divId) ;
                $("#photopieceappartement" + divId).append(
                    $compile(
                        '<div class="pieceappartementdivappend col-span-3 sm:col-span-3 md:col-span-3">\n' +
                        '                                    <div class="form-group text-center class-form">\n' +
                        '                                        <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->\n' +
                        "                                        <div>\n" +
                        '                                            <label for="imgpieceimage_' +
                        divId +
                        "_" +
                        $scope.compteurImage +
                        '" class="cursor-pointer">\n' +
                        '                                                <img id="affimgpieceimage_' +
                        divId +
                        "_" +
                        $scope.compteurImage +
                        '" src="' +
                        source +
                        '" alt="..." class="image-hover shadow" style="width: 200px;height: 200px;border-radius: 10%!important;margin: 0 auto">\n' +
                        '                                                <div style="display: none;">\n' +
                        '                                                    <input type="file" accept=\'image/*\' id="imgpieceimage_' +
                        divId +
                        "_" +
                        $scope.compteurImage +
                        '" name="pieceimage_' +
                        divId +
                        "_" +
                        $scope.compteurImage +
                        '" onchange=\'Chargerimage(this.name)\' class="required">\n' +
                        '                                                    <input type="hidden" id="erase_imgpieceimage_' +
                        divId +
                        "_" +
                        $scope.compteurImage +
                        '" name="image_erase" value="">\n' +
                        '                                                    <input type="hidden" id="imgpieceimageupdate_' +
                        divId +
                        "_" +
                        $scope.compteurImage +
                        '" name="imgpieceimageupdatename_' +
                        divId +
                        "_" +
                        $scope.compteurImage +
                        '">\n' +
                        "\n" +
                        "                                                </div>\n" +
                        "                                            </label>\n" +
                        "                                        </div>\n" +
                        '                                        <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile(\'imgpieceimage_' +
                        divId +
                        "_" +
                        $scope.compteurImage +
                        "')\">\n" +
                        '                                            <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>\n' +
                        "                                        </button>\n" +
                        "                                    </div>\n" +
                        "                                </div>"
                    )($scope)
                );

                $("#compteurimage_appartement").val($scope.compteurImage);
                $("#compteurimage2_appartement").val($scope.compteurImage2);
                console.log($scope.compteurImage);
            }

            if (type == "photo_appartement") {
                var source = $("#imagesource").val();
                $scope.compteurImage2++;
                //  console.log(divId) ;
                $("#photoappartement").append(
                    $compile(
                        ' <div class="photoappartementdivappend divapp2 col-span-3 sm:col-span-3 md:col-span-3">\n' +
                        '                            <div class="form-group text-center class-form">\n' +
                        '                                <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->\n' +
                        "                                <div>\n" +
                        '                                    <label for="imgappartement_' +
                        $scope.compteurImage2 +
                        '" class="cursor-pointer">\n' +
                        '                                        <img id="affimgappartement_' +
                        $scope.compteurImage2 +
                        '" src="' +
                        source +
                        '" alt="..." class="image-hover shadow" style="width: 300px;height: 300px;border-radius: 10%!important; margin: 0 auto">\n' +
                        '                                        <div style="display: none;">\n' +
                        '                                            <input type="file" accept=\'image/*\' id="imgappartement_' +
                        $scope.compteurImage2 +
                        '" name="appartement_' +
                        $scope.compteurImage2 +
                        '" onchange=\'Chargerimage(this.name)\' class="required">\n' +
                        '                                            <input type="hidden" id="erase_imgappartement_' +
                        $scope.compteurImage2 +
                        '" name="image_erase" value="">\n' +
                        '                                            <input type="hidden" id="imgappartementupdate_' +
                        $scope.compteurImage2 +
                        '" name="imgappartementupdatename_' +
                        $scope.compteurImage +
                        '">\n' +
                        "                                        </div>\n" +
                        "                                    </label>\n" +
                        "                                </div>\n" +
                        '                                <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile(\'imgappartement_' +
                        $scope.compteurImage2 +
                        "')\">\n" +
                        '                                    <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>\n' +
                        "                                </button>\n" +
                        "                            </div>\n" +
                        "                        </div>"
                    )($scope)
                );

                $("#compteurimage2_appartement").val($scope.compteurImage2);
                console.log($scope.compteurImage);
            }

            if (type == "photo_pieceetatlieu") {
                $scope.compteurImage3++;
                var source = $("#imagesource2").val();
                //  $scope.compteurImage++ ;
                //  console.log(divId) ;
                $("#photopieceetatlieu" + divId).append(
                    $compile(
                        ' <div class="col-span-3 sm:col-span-3 md:col-span-3 text-center">\n' +
                        '                                    <div class="form-group text-center class-form">\n' +
                        '                                        <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->\n' +
                        "                                        <div>\n" +
                        '                                            <label for="imgpieceimagecomposition_' +
                        divId +
                        "_" +
                        $scope.compteurImage3 +
                        '" class="cursor-pointer">\n' +
                        '                                                <img id="affimgpieceimagecomposition_' +
                        divId +
                        "_" +
                        $scope.compteurImage3 +
                        '" src="' +
                        source +
                        '" alt="..." class="image-hover shadow" style="width: 200px;height: 200px;border-radius: 10%!important;margin: 0 auto">\n' +
                        '                                                <div style="display: none;">\n' +
                        '                                                    <input type="file" accept=\'image/*\' id="imgpieceimagecomposition_' +
                        divId +
                        "_" +
                        $scope.compteurImage3 +
                        '" name="pieceimagecomposition_' +
                        divId +
                        "_" +
                        $scope.compteurImage3 +
                        '" onchange=\'Chargerimage(this.name)\' class="required">\n' +
                        '                                                    <input type="hidden" id="erase_imgpieceimagecomposition_' +
                        divId +
                        "_" +
                        $scope.compteurImage3 +
                        '" name="image_erase" value="">\n' +
                        '                                                    <input type="hidden" id="imgpieceimageupdatecomposition_' +
                        divId +
                        "_" +
                        $scope.compteurImage3 +
                        '" name="imgpieceimageupdatenamecomposition_' +
                        divId +
                        "_" +
                        $scope.compteurImage3 +
                        '">\n' +
                        "\n" +
                        "                                                </div>\n" +
                        "                                            </label>\n" +
                        "                                        </div>\n" +
                        '                                        <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile(\'imgpieceimagecomposition_' +
                        divId +
                        "_" +
                        $scope.compteurImage3 +
                        "')\">\n" +
                        '                                            <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>\n' +
                        "                                        </button>\n" +
                        "                                    </div>\n" +
                        "                                </div>"
                    )($scope)
                );

                $("#compteurimage_etatlieu").val($scope.compteurImage3);
                //       $("#compteurimage2_appartement").val($scope.compteurImage2);
                //       console.log($scope.compteurImage) ;
            }
        };
        $scope.getDatePlus = function (plus = null) {
            var d = new Date();
            var month;
            if (plus) {
                d.setDate(d.getDate() + 1);
                month = "" + (d.getMonth() + 1);
            } else {
                d.setDate(d.getDate());
                month = "" + (d.getMonth() + 1);
            }

            day = "" + d.getDate();
            year = d.getFullYear();

            if (month.length < 2) month = "0" + month;
            if (day.length < 2) day = "0" + day;

            return [year, month, day].join("-");
        };
        $scope.addDaysToDate = function (date, days) {
            var result = new Date(date);
            result.setDate(result.getDate() + days);
            var ye = new Intl.DateTimeFormat("en", {
                year: "numeric",
            }).format(result);
            var mo = new Intl.DateTimeFormat("en", {
                month: "2-digit",
            }).format(result);
            var da = new Intl.DateTimeFormat("en", {
                day: "2-digit",
            }).format(result);
            var retour = ye + "-" + mo + "-" + da;
            return retour;
        };
        /* Mes fonctions a part que je optimiser*/
        //-------------RT ECHO--------------------//
        window.Echo.channel("rt").listen("RtEvent", (e) => {
            if ($scope.linknav.indexOf("list-" + e.data.type) !== -1) {
                console.log("on fait un pageChanged", e.data);
                $scope.pageChanged(e.data.type);
            }
        });
        //-------------UTILITAIRES--------------------//
        $scope.playAudio = function () {
            var audio = new Audio(BASE_URL + "sounds/newnotif.mp3");
            audio.play();
        };
        $scope.datatoggle = function (href, addclass) {
            $(href).attr("class").match(addclass)
                ? $(href).removeClass(addclass)
                : $(href).addClass(addclass);
        };
        // Cocher tous les checkbox / Décocher tous les checkbox
        $scope.checkAllOruncheckAll = function (type) {
            var cocherOuNon = $("#toutcocher").prop("checked");

            $scope.cocherTout = cocherOuNon;
            if (cocherOuNon == true) {
                //Tout doit etre coché
                $("#labelCocherTout").html("Tout décocher");
            } else {
                //Tout doit etre décoché
                $("#labelCocherTout").html("Tout cocher");
            }
            $(".mycheckbox").prop("checked", cocherOuNon);

            if (type == "role") {
                $scope.addToRole();
            } else if (type == "be") {
                $scope.addToBe();
            }
        };
        $scope.eraseFile = function (idInput) {
            $("#" + idInput).val("");
            $("#erase_" + idInput).val("yes");
            $("#aff" + idInput).attr("src", imgupload);
        };
        $scope.menusearchs = [
            {
                id: 445,
                designation: "dashboard",
                parent_id: null,
                icon: "fas fa-home",
                url: "",
                permission: "liste-dashboard",
            },
            {
                id: 1,
                designation: "Administration",
                icon: "fas fa-user-shield",
                url: "javascript:;",
                permission: "module-outil-admin",
                parent_id: 1,
                parent: [
                    {
                        id: 2,
                        designation: "Rôles",
                        icon: "fas fa-user-tag",
                        url: "list-role",
                        permission: "liste-role",
                    },
                    {
                        id: 3,
                        designation: "Utilisateurs",
                        icon: "fas fa-users",
                        url: "list-user",
                        permission: "liste-user",
                    },
                    {
                        id: 4,
                        designation: "Paramètres",
                        icon: "fas fa-cog",
                        url: "list-preference",
                        permission: "liste-preference",
                    },
                ],
            },
            {
                id: 5,
                designation: "Configuration",
                icon: "fas fa-sliders-h",
                url: "javascript:;",
                permission: "module-parametrage",
                parent_id: 5,
                parent: [
                    {
                        id: 56,
                        designation: "Structures immo",
                        icon: "fas fa-building",
                        url: "list-structureimmeuble",
                        permission: "liste-structureimmeuble",
                    },
                    {
                        id: 55,
                        designation: "Équipements",
                        icon: "fas fa-couch",
                        url: "list-equipementpiece",
                        permission: "liste-equipementpiece",
                    },
                    {
                        id: 46,
                        designation: "Types d'appartements",
                        icon: "fas fa-door-open",
                        url: "list-typeappartement",
                        permission: "liste-typeappartement",
                    },
                    {
                        id: 47,
                        designation: "Types de contrats",
                        icon: "fas fa-file-contract",
                        url: "list-typecontrat",
                        permission: "liste-typecontrat",
                    },
                    {
                        id: 48,
                        designation: "Types de documents",
                        icon: "fas fa-file-alt",
                        url: "list-typedocument",
                        permission: "liste-typedocument",
                    },
                    {
                        id: 49,
                        designation: "Types de factures",
                        icon: "fas fa-file-invoice",
                        url: "list-typefacture",
                        permission: "liste-typefacture",
                    },
                    {
                        id: 54,
                        designation: "Types de locataires",
                        icon: "fas fa-user-friends",
                        url: "list-typelocataire",
                        permission: "liste-typelocataire",
                    },
                    {
                        id: 52,
                        designation: "Types de pièces",
                        icon: "fas fa-layer-group",
                        url: "list-typepiece",
                        permission: "liste-typepiece",
                    },
                ],
            },
            {
                id: 8,
                designation: "Biens immobiliers",
                icon: "fas fa-city",
                url: "javascript:;",
                permission: "module-gestion-bien",
                parent_id: 8,
                parent: [
                    {
                        id: 6,
                        designation: "Immeubles",
                        icon: "fas fa-building",
                        url: "list-immeuble",
                        permission: "liste-immeuble",
                    },
                    {
                        id: 7,
                        designation: "Appartements",
                        icon: "fas fa-home",
                        url: "list-appartement",
                        permission: "liste-appartement",
                    },
                ],
            },
            {
                id: 13,
                designation: "Locations",
                icon: "fas fa-key",
                url: "javascript:;",
                permission: "module-gestion-location",
                parent_id: 13,
                parent: [
                    {
                        id: 14,
                        designation: "Locataires",
                        icon: "fas fa-users",
                        url: "list-locataire",
                        permission: "liste-locataire",
                    },
                ],
            },
            {
                id: 15,
                designation: "Gestion",
                icon: "fas fa-tasks",
                url: "javascript:;",
                permission: "module-administration",
                parent_id: 15,
                parent: [
                    {
                        id: 33,
                        designation: "Résiliations",
                        icon: "fas fa-file-excel",
                        url: "list-demanderesiliation",
                        permission: "liste-demanderesiliation",
                    },
                    {
                        id: 16,
                        designation: "Contrats prestataires",
                        icon: "fas fa-handshake",
                        url: "list-contratprestataire",
                        permission: "liste-contratprestataire",
                    },
                    {
                        id: 6,
                        designation: "Propriétaires",
                        icon: "fas fa-user-tie",
                        url: "list-proprietaire",
                        permission: "liste-proprietaire",
                    }
                ],
            },
            {
                id: 21,
                designation: "Contrats",
                icon: "fas fa-file-signature",
                url: "javascript:;",
                permission: "module-finance",
                parent_id: 21,
                parent: [
                    {
                        id: 30,
                        designation: "Contrats location",
                        icon: "fas fa-file-contract",
                        url: "list-contrat",
                        permission: "liste-contrat",
                    },
                    {
                        id: 694,
                        designation: "Mandats",
                        icon: "fas fa-user-check",
                        url: "list-contratproprietaire",
                        permission: "liste-contratproprietaire",
                    },
                    {
                        id: 22,
                        designation: "Baux",
                        icon: "fas fa-key",
                        url: "list-contratlocation",
                        permission: "liste-contratlocation",
                    },
                ],
            },
        ];

        $scope.acceuilssearch = [
            {
                id: 6,
                designation: "Immeuble",
                icon: "immeuble2.png",
                url: "list-immeuble",
                permission: "liste-immeuble",
            },
            {
                id: 7,
                designation: "Appartement",
                icon: "appartement.png",
                url: "list-appartement",
                permission: "liste-appartement",
            },


            {
                id: 6,
                designation: "Proprietaire",
                icon: "proprietaire.png",
                url: "list-proprietaire",
                permission: "liste-proprietaire",
            },


            {
                id: 14,
                designation: "Locataire",
                icon: "locataire.png",
                url: "list-locataire",
                permission: "liste-locataire",
            },
            {
                id: 31,
                designation: "Paiement de loyer",
                icon: "loyer.png",
                url: "list-paiementloyer",
                permission: "liste-paiementloyer",
            },
            {
                id: 30,
                designation: "Contrat de location",
                icon: "agreement.png",
                url: "list-contrat",
                permission: "liste-contrat",
            },

            {
                id: 33,
                designation: "Resiliation de bail",
                icon: "resiliation.png",
                url: "list-demanderesiliation",
                permission: "liste-demanderesiliation",
            },



            {
                id: 34,
                designation: "Facture",
                icon: "facture.png",
                url: "list-facture",
                permission: "liste-facture",
            },
            {
                id: 230,
                designation: "Contrat de location vente",
                icon: "agreement.png",
                url: "list-locationvente",
                permission: "liste-locationvente",
            },

            {
                id: 27,
                designation: "Versement loyer",
                icon: "loyer.png",
                url: "list-versementloyer",
                permission: "liste-versementloyer",
            },




            {
                id: 31,
                designation: "Annonce",
                icon: "annonces.png",
                url: "list-annonce",
                permission: "liste-annonce",
            },
            {
                id: 30,
                designation: "Messagerie",
                icon: "messagerie.png",
                url: "list-message",
                permission: "liste-message",
            },
            {
                id: 32,
                designation: "Questionnaire de satisfaction",
                icon: "questionnaire.png",
                url: "list-questionnairesatisfaction",
                permission: "liste-questionnairesatisfaction",
            },

            {
                id: 41,
                designation: "Répertoire résident",
                icon: "locataire.png",
                url: "list-repertoireresident",
                permission: "liste-repertoireresident",
            },
            {
                id: 42,
                designation: "Répertoire propriétaire",
                icon: "proprietaire.png",
                url: "list-repertoireproprietaire",
                permission: "liste-repertoireproprietaire",
            },
            {
                id: 43,
                designation: "Répertoire prestataire",
                icon: "prestataire.png",
                url: "list-repertoireprestataire",
                permission: "liste-repertoireprestataire",
            },
            {
                id: 44,
                designation: "Répertoire employé",
                icon: "employe.png",
                url: "list-repertoireemploye",
                permission: "liste-repertoireemploye",
            },
        ];

        $scope.openMenuSearch = function (
            index,
            second = false,
            ismobile = false,
            item
        ) {
            if ($("#open_menu_pc_" + index)) {
                $("#open_menu_pc_" + index)
                    .attr("class")
                    .match("side-menu__sub-open")
                    ? $("#open_menu_pc_" + index).removeClass(
                        "side-menu__sub-open"
                    )
                    : $("#open_menu_pc_" + index).addClass(
                        "side-menu__sub-open side-menu--active animated fadeInRight"
                    );
            }
            if (ismobile) {
                if ($("#open_menu_" + index)) {
                    $("#open_menu_" + index)
                        .attr("class")
                        .match("menu__sub-open")
                        ? $("#open_menu_" + index)
                            .removeClass("menu__sub-open")
                            .css("display", "none")
                        : $("#open_menu_" + index)
                            .addClass(
                                "menu__sub-open menu--active animated fadeInRight"
                            )
                            .css("display", "block");
                }
            }
            if (item && item.parent) {
                $("html, body").animate({ scrollTop: 2060 }, 500);
            }
        };
        $scope.isActiveMenu = false;

        $scope.toggleTabMenu = function () {
            console.log('tab menu: ', $scope.isActiveMenu);
            console.log('user connected: ', $scope.infosUserConnected.roles[0].name);
            if ($scope.infosUserConnected.roles[0].name !== "resident") {
                $scope.isActiveMenu = !$scope.isActiveMenu;
            }
        };

        $scope.toggleWindows = function () {
            $scope.isActiveTab = !$scope.isActiveTab;
        };

        $scope.startSlick = function () {
            console.log(
                "ici pour voir le hasClass",
                $("#slick-carousel").hasClass("slick-initialized")
            );
            $(".slider").not(".slick-initialized").slick();
            // $(".slick-carousel").not('.slick-initialized').slick();
            if ($("#slick-carousel").hasClass("slick-initialized") == false) {
                if ($(".slick-carousel").length) {
                    $(".slick-carousel").each(function () {
                        setTimeout(function () {
                            $(".slick-carousel").slick({
                                arrows: false,
                                infinite: true,
                                autoplay: false,
                                autoplaySpeed: 3000,
                                slidesToShow: 3,
                                slidesToScroll: 3,
                            });
                        }, 2500);
                    });
                }
            }
        };
        $scope.emptyformElement = function (element, type) {
            $scope.rappelLocataireData = [];
            if (element.is("select")) {
                currentSelect = element;
                if (currentSelect.val()) {
                    currentSelect.val("").change();
                }
                $("#prix_de_revient_unitaire_produit_total").val("").change();
            } else if (element.is(":checkbox")) {
                element.prop("checked", false);

                if (element.is("[data-toggle]")) {
                    element.bootstrapToggle("destroy").bootstrapToggle();
                }
            } else if (element.is(":radio")) {
                element.prop("checked", false);
            } else if (element.is(":file")) {
                if (element.hasClass("filestyle")) {
                    setTimeout(function () {
                        element.filestyle("clear");
                    }, 200);
                } else {
                    getId = element
                        .attr("id")
                        .substring(0, element.attr("id").length - type.length);
                    $("#" + getId + type).val("");
                    $("#aff" + getId + type).attr("src", imgupload);
                }
            } else if (element.hasClass("datedropper")) {
                element.val(null).trigger("change");
            } else {
                element.val("");
            }
            if (!element.hasClass("datedropper")) {
                element.attr("disabled", false).attr("readonly", false);
            }
        };
        $scope.emptyform = function (
            type,
            fromPage = false,
            conserveFilter = false
        ) {
            $scope.orderby = null;
            $scope.inputs = [];
            $scope.radioBtn = null;
            $scope.filters = null;
            let dfd = $.Deferred();

            $(".ws-number").val("");
            $(
                "input[id$=" +
                type +
                "], textarea[id$=" +
                type +
                "], select[id$=" +
                type +
                "], button[id$=" +
                type +
                "]"
            ).each(function () {
                if (conserveFilter) {
                    if ($(this).attr("id").indexOf("_list_") == -1) {
                        $scope.emptyformElement($(this), type);
                    }
                } else {
                    $scope.emptyformElement($(this), type);
                }
            });

            // On vide le tableau des items ici
            $.each($scope.dataInTabPane, function (keyItem, valueItem) {
                tagType = "_" + type;
                if (keyItem.indexOf(tagType) !== -1) {
                    $scope.dataInTabPane[keyItem]["data"] = [];
                }
            });

            $(".checkbox-all").prop("checked", true);

            // Si on clique sur le bouton annuler
            if (fromPage) {
                $scope.pageChanged(type);
            }

            return dfd.promise();
        };
        // Permet d'ajouter une permission à la liste des permissions d'un role
        $scope.role_permissions = [];
        $scope.addToRole = function (event, itemId) {
            var all_checked = true;
            if (
                !$scope.role_permissions ||
                $scope.role_permissions.length <= 0
            ) {
                $scope.role_permissions = [];
            }
            $("[id^=permission_role]").each(function (key, value) {
                if ($(this).prop("checked")) {
                    var permission_id = $(this).attr("data-permission-id");
                    var search = false;
                    for (var i = 0; i < $scope.role_permissions.length; i++) {
                        if (
                            $scope.role_permissions[i] == permission_id &&
                            $scope.role_permissions[i] + "" == permission_id
                        ) {
                            search = true;
                        }
                    }
                    if (!search) {
                        $scope.role_permissions.push(permission_id);
                    }
                } else {
                    var permission_id = $(this).attr("data-permission-id");
                    var index = $scope.role_permissions.indexOf(permission_id);
                    if (index <= -1) {
                        index = $scope.role_permissions.indexOf(+permission_id);
                    }
                    if (index > -1) {
                        $scope.role_permissions.splice(index, 1);
                    }
                    all_checked = false;
                    console.log($scope.role_permissions);
                }
            });
            $("#permission_all_role").prop("checked", all_checked);
        };
        //--DEBUT => Permet de vérifier si un id est dans un tableau--//
        $scope.isInArrayData = function (e, idItem, data, typeItem = "menu") {
            response = false;
            $.each(data, function (key, value) {
                if (typeItem.indexOf("menu") !== -1) {
                    if (value.consommation_id == idItem) {
                        response = true;
                    }
                } else if (typeItem.indexOf("role") !== -1) {
                    if (value.id == idItem) {
                        response = true;
                    }
                } else if (typeItem.indexOf("be") !== -1) {
                    $scope.checkIfShowButtonDel();
                }
                //return response;
            });
            //console.log('ici', response);\

            return response;
        };
        $scope.checkIfShowButtonDel = function () {
            var checked = false;

            $scope.is_checked = checked;
        };
        //--FIN => Permet de vérifier si un id est dans un tableau--//

        $scope.chstat = {
            id: "",
            statut: "",
            type: "",
            title: "",
            obj: "",
        };
        $scope.chstatObj = {
            id: "",
            statut: "",
            type: "",
            title: "",
            obj: "",
        };

        $scope.showModalStatut = function (
            event,
            type,
            statut,
            obj = null,
            title = null,
            indexItem = null,
            indexItem2,
            list = false,
            tab = false
        ) {
            var id = null;
            var index = null;
            id = obj.id;
            $scope.chstat.id = id;
            $scope.chstat.statut = statut;
            $scope.chstat.type = type;
            $scope.chstat.title = title;
            console.log("ici", $scope.chstat);
            $scope.desactivElement(type, obj, null, index, list, tab);
        };

        $scope.generateAddFiltres = function (currentpage) {
            var originePage = currentpage;
            currentpage = `_list_${currentpage}`;
            var addfiltres = "";
            var title = "";
            var currentvalue = "";
            var can_add = true;
            console.log("after =====>", currentpage);
            $(
                "input[id$=" +
                currentpage +
                "], textarea[id$=" +
                currentpage +
                "], select[id$=" +
                currentpage +
                "]"
            ).each(function () {
                title = $(this).attr("id");
                title = title.substring(0, title.length - currentpage.length);
                currentvalue = $(this).val();
                // console.log('here =>', currentpage, 'titre filtre', $(this).attr("id"), title);

                if (currentvalue && title.indexOf("searchtexte") === -1) {
                    can_add = true;

                    if ($(this).is("select")) {
                        if (originePage == "etatloyer") {
                            if (title == "periode") {
                                title = `${title}`;
                                currentvalue = `"${currentvalue}"`;
                            } else {
                                title = title.split("_")[0];
                                console.log(title, "title messi mp");

                                title = `${title}_id`;
                            }
                        } else {
                            title = `${title}_id`;
                        }
                    } else if ($(this).is("input") || $(this).is("textarea")) {
                        if ($(this).attr("type") === "radio") {
                            // console.log('select222*********');
                            title = $(this).attr("name");
                            currentvalue = $(
                                "#" +
                                $(this).attr("id") +
                                "[name='" +
                                title +
                                "']:checked"
                            ).attr("data-value");
                            if (
                                addfiltres.indexOf(title) !== -1 ||
                                !currentvalue
                            ) {
                                can_add = false;
                            }
                        }
                        if ($(this).attr("type") === "checkbox") {
                            // rien pour le moment
                        }
                        if ($(this).attr("type") === "number") {
                        }
                        if (
                            $(this).attr("type") === "date" ||
                            $(this).attr("type") === "text" ||
                            $(this).is("textarea") ||
                            $(this).attr("type") === "time"
                        ) {
                            currentvalue = `"${currentvalue}"`;
                        }
                        if ($(this).attr("type") === "color") {
                            title = $(this).attr("name");
                        }
                    }

                    if (title.indexOf("searchoption") !== -1) {
                        title = currentvalue;
                        currentvalue = $("#searchtexte" + currentpage).val();
                        currentvalue = `"${currentvalue}"`;
                        if (!$("#searchtexte" + currentpage).val()) {
                            can_add = false;
                        }
                    }
                    if (can_add) {
                        if (title === "couleur") {
                            currentvalue = currentvalue.replace("#", "");
                        }
                        addfiltres = `${addfiltres},${title}:${currentvalue}`;
                    }
                }
            });

            console.log("addfiltres messi", addfiltres);

            $scope.filters = addfiltres;
            console.log("filters", $scope.filters);
            return addfiltres;
        };

        $scope.radioBtnStatus = null;
        $scope.onRadioClickStatus = function ($event, param) {
            $scope.radioBtnStatus = param;
        };

        // Pour générer les formulaires d'ajout dans les sections TabPane du modal
        $scope.dataInTabPane = {
            user_departement_user: { data: [], rules: [] },
            typeappartement_typepiece_typeappartement: {
                data: [],
                rules: [],
            },
            typeappartement_piece_equipepementpiece_typeappartement_piece_appartement:
                { data: [], rules: [] },
            immeuble_securite_immeuble: { data: [], rules: [] },
            factureintervention_intervention_factureintervention: {
                data: [],
                rules: [],
            },
            periodepaiementloyer_paiementloyer: {
                data: [],
                rules: [],
            },
            periodefacturelocation_facturelocation: {
                data: [],
                rules: [],
            },
            detaildevis_devis: { data: [], rules: [] },
            contrat_annexes_contrat: { data: [], rules: [] },
            contrat_annexesreyhan_contrat: { data: [], rules: [] },
            document_appartement: { data: [], rules: [] },
            users_entite: { data: [], rules: [] },
            locataire_copreneurs_locataire: { data: [], rules: [] },
            fraissupplementaire_avisecheance: { data: [], rules: [] },
            info_bancaires_entite: { data: [], rules: [] },
            /**N'appartient pas à l'application**/
        };

        //Pour des utilisations spécifiques
        $scope.depotSelected = [];
        $scope.firstime = true;
        $scope.prestataireData = [];
        $scope.interventions = null;
        $scope.actionSurTabPaneTagData = function (
            action,
            tagForm,
            currentIndex = 0,
            type = "",
            indextab,
            keyUpdate = null,
            valueUpdate = null
        ) {
            // console.log(action, tagForm);

            if (action == "add") {
                // cloner $scope.dataPage["interventions"] dans $scope.interventions
                $scope.interventions = JSON.parse(
                    JSON.stringify($scope.dataPage["interventions"])
                );
                console.log($scope.interventions, "def def test");
                var speciale = false;
                var filepath = "";
                var currentPosition =
                    $scope.dataInTabPane[tagForm]["data"].length;
                $scope.dataInTabPane[tagForm]["data"].push({});
                var message_duplicatevalue = "";
                var findError = false;
                if (tagForm == "immeuble_securite_immeuble") {
                    $scope.valId = $(
                        "#prestataire_immeuble_securite_immeuble"
                    ).val();
                    //  console.log($scope.valId) ;
                    if ($.isNumeric($scope.valId)) {
                        var typeAvecS = "prestataires";
                        rewriteReq = typeAvecS + "(id:" + $scope.valId + ")";
                        Init.getElement(
                            rewriteReq,
                            listofrequests_assoc[typeAvecS]
                        ).then(
                            function (data) {
                                console.log("data", data);
                                $scope.prestataireData = data;
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );
                    }
                }
                $(
                    "input[id$=" +
                    tagForm +
                    "], textarea[id$=" +
                    tagForm +
                    "], select[id$=" +
                    tagForm +
                    "], hidden[id$=" +
                    tagForm +
                    "]"
                ).each(function () {
                    getValue = $(this).val();

                    var indexNameInTab = $(this)
                        .attr("id")
                        .substring(
                            0,
                            $(this).attr("id").length - tagForm.length - 1
                        );
                    console.log("index name ", indexNameInTab);
                    if ($(this).hasClass("required") && !$(this).val()) {
                        findError = true;
                        $scope.dataInTabPane[tagForm]["data"].splice(
                            currentPosition,
                            1
                        );
                        message_duplicatevalue =
                            "Veuillez remplir tous les champs obligatoires";
                        return !findError;
                    }
                    if (
                        $scope.dataInTabPane[tagForm]["data"] &&
                        $scope.dataInTabPane[tagForm]["data"].length > 0
                    ) {
                        var trouve = false;
                        var index = null;
                        for (
                            var i = 0;
                            i < $scope.dataInTabPane[tagForm]["data"].length;
                            i++
                        ) {
                            if (
                                $scope.dataInTabPane[tagForm]["data"][i][
                                indexNameInTab + "_text"
                                ] == $(this).find("option:selected").text()
                            ) {
                                trouve = true;
                                index = i;
                            }

                            if (tagForm == "contrat_annexes_contrat") {
                                if (
                                    $scope.dataInTabPane[tagForm]["data"][i][
                                    indexNameInTab
                                    ] == $(this).val()
                                ) {
                                    trouve = true;
                                    index = i;
                                }
                            }
                            if (tagForm == "contrat_annexesreyhan_contrat") {
                                if (
                                    $scope.dataInTabPane[tagForm]["data"][i][
                                    indexNameInTab
                                    ] == $(this).val()
                                ) {
                                    trouve = true;
                                    index = i;
                                }
                            }
                            if (tagForm == "document_appartement") {
                                if (
                                    $scope.dataInTabPane[tagForm]["data"][i][
                                    indexNameInTab
                                    ] == $(this).val()
                                ) {
                                    trouve = true;
                                    index = i;
                                }
                            }
                            if (tagForm == "locataire_copreneurs_locataire") {
                                trouve = false;
                                if (
                                    $scope.dataInTabPane[tagForm]["data"][i][
                                    "email"
                                    ] == $(this).val()
                                ) {
                                    trouve = true;
                                    index = i;
                                }
                                if (
                                    $scope.dataInTabPane[tagForm]["data"][i][
                                    "telephone1"
                                    ] == $(this).val()
                                ) {
                                    trouve = true;
                                    index = i;
                                }
                            }
                        }

                        if (
                            tagForm ==
                            "factureintervention_intervention_factureintervention"
                        ) {
                            if (trouve == true) {
                                findError = true;
                                $scope.dataInTabPane[tagForm]["data"].splice(
                                    currentPosition,
                                    1
                                );
                                message_duplicatevalue =
                                    "Erreur: " +
                                    $(this).find("option:selected").text() +
                                    " existe déjà.";
                                return !findError;
                            }
                        }
                        if (tagForm == "contrat_annexes_contrat") {
                            if (trouve == true) {
                                findError = true;
                                $scope.dataInTabPane[tagForm]["data"].splice(
                                    currentPosition,
                                    1
                                );
                                message_duplicatevalue =
                                    "Erreur: " +
                                    $(this).find("option:selected").text() +
                                    " existe déjà.";
                                return !findError;
                            }
                        }
                        if (tagForm == "document_appartement") {
                            if (trouve == true) {
                                findError = true;
                                $scope.dataInTabPane[tagForm]["data"].splice(
                                    currentPosition,
                                    1
                                );
                                message_duplicatevalue =
                                    "Erreur: " +
                                    $(this).find("option:selected").text() +
                                    " existe déjà.";
                                return !findError;
                            }
                        }
                        if (tagForm == "contrat_annexesreyhan_contrat") {
                            if (trouve == true) {
                                findError = true;
                                $scope.dataInTabPane[tagForm]["data"].splice(
                                    currentPosition,
                                    1
                                );
                                message_duplicatevalue =
                                    "Erreur: " +
                                    $(this).find("option:selected").text() +
                                    " existe déjà.";
                                return !findError;
                            }
                        }

                        if (tagForm == "locataire_copreneurs_locataire") {
                            if (trouve == true) {
                                findError = true;
                                $scope.dataInTabPane[tagForm]["data"].splice(
                                    currentPosition,
                                    1
                                );
                                message_duplicatevalue =
                                    "Erreur: " +
                                    indexNameInTab +
                                    " existe déjà.";
                                return !findError;
                            }
                        }

                        if (tagForm == "periodepaiementloyer_paiementloyer") {
                            if (trouve == true) {
                                findError = true;
                                $scope.dataInTabPane[tagForm]["data"].splice(
                                    currentPosition,
                                    1
                                );
                                message_duplicatevalue =
                                    "Erreur: " +
                                    $(this).find("option:selected").text() +
                                    " existe déjà.";
                                return !findError;
                            }
                        }
                    }

                    if ($(this).is("select")) {
                        if (!findError) {
                            $scope.dataInTabPane[tagForm]["data"][
                                currentPosition
                            ][indexNameInTab + "_text"] = $(this)
                                .find("option:selected")
                                .text();
                            indexNameInTab = indexNameInTab + "_id";

                            $scope.dataPage["interventions"].forEach(
                                (element, index) => {
                                    if (element.id == getValue) {
                                        $scope.dataPage["interventions"].splice(
                                            index,
                                            1
                                        );
                                    }
                                }
                            );
                            console.log($scope.dataPage["interventions"]);
                            console.log(
                                "l'intervention a été supprimé de la liste des interventions"
                            );

                            //  console.log(indexNameInTab) ;
                        } else {
                            $scope.showToast(
                                "",
                                message_duplicatevalue,
                                "error"
                            );
                        }
                    } else if ($(this).is(":checkbox")) {
                        getValue = $(this).prop("checked");
                    } else if ($(this).is("input[type='file']")) {
                        // C'est un input de type fichier
                        // Maintenant vous pouvez obtenir le chemin local du fichier
                        var fileInput = this; // Utilisez "this" pour obtenir l'élément d'entrée de fichier

                        var selectedFile = fileInput.files[0];
                        filepath = fileInput.files[0];

                        console.log("Test type file ", selectedFile);
                        if (selectedFile) {
                            // Ouvrir le PDF dans un nouvel onglet
                            const pdfBlob2 = new Blob([selectedFile], {
                                type: "application/pdf",
                            });
                            const pdfUrl = URL.createObjectURL(pdfBlob2);
                            // window.open(pdfUrl);
                            getValue = URL.createObjectURL(pdfBlob2);
                            console.log("get value ", getValue);
                        }
                    }

                    if (!speciale) {
                        $scope.dataInTabPane[tagForm]["data"][currentPosition][
                            indexNameInTab
                        ] = getValue;
                        console.log("getValue : ", getValue);
                        if (tagForm == "contrat_annexes_contrat") {
                            if (filepath) {
                                //  $scope.dataInTabPane[tagForm]["data"][currentPosition][
                                //         "filepath"
                                // ] = filepath;

                                const newFileInput =
                                    document.createElement("input");
                                newFileInput.type = "file";
                                newFileInput.accept = ".csv, .pdf";
                                newFileInput.className =
                                    "form-control filestyle filegenerate required";
                                newFileInput.setAttribute(
                                    "data-buttonName",
                                    "btn-shadow btn-transition btn-outline-danger p-2"
                                );
                                newFileInput.setAttribute(
                                    "data-buttonText",
                                    "Choisir un fichier"
                                );
                                newFileInput.setAttribute(
                                    "data-placeholder",
                                    "Aucun fichier choisi"
                                );

                                newFileInput.id =
                                    "fichier_contrat_annexes_contrat_" +
                                    currentPosition; // Utilisez un ID unique
                                newFileInput.name =
                                    "fichier_" +
                                    $("#numero_contrat_annexes_contrat").val();
                                newFileInput.setAttribute(
                                    "data-iconName",
                                    "fa fa-folder-open"
                                );
                                // newFileInput.files = new DataTransfer().items.add(filepath);
                                // Créez un nouvel objet DataTransfer et ajoutez la valeur du premier champ de fichier
                                const dataTransfer = new DataTransfer();
                                const premierChampFichier =
                                    document.getElementById(
                                        "fichier_contrat_annexes_contrat"
                                    );

                                dataTransfer.items.add(
                                    premierChampFichier.files[0]
                                );
                                newFileInput.files = dataTransfer.files;
                                console.log(
                                    " newFileInput.files ",
                                    newFileInput.files
                                );

                                // Ajoutez le nouvel champ de fichier à l'élément avec l'ID "documentcontrat_lvt"

                                document
                                    .getElementById("documentcontrat_lvt")
                                    .appendChild(newFileInput);

                                // const documentcontrat = document.getElementById("documentcontrat");
                                // if (documentcontrat) {
                                //     document.getElementById("documentcontrat").appendChild(newFileInput);
                                // }
                                $(".filegenerate").hide();
                            }

                            //    $("#documentcontrat_lvt").append(
                            //     `
                            //         <input type="file"
                            //         accept=".csv, .pdf"
                            //         class="form-control filestyle required"
                            //         data-buttonName="btn-shadow btn-transition btn-outline-danger p-2"
                            //         data-buttonText="Choisir un fichier"
                            //         data-placeholder="Aucun fichier choisi"
                            //         id="fichier_contrat_annexes_contrat" name="fichier"
                            //         data-iconName="fa fa-folder-open">
                            //     `
                            //    )
                        }
                        if (tagForm == "document_appartement") {
                            if (filepath) {
                                //  $scope.dataInTabPane[tagForm]["data"][currentPosition][
                                //         "filepath"
                                // ] = filepath;

                                const newFileInput =
                                    document.createElement("input");
                                newFileInput.type = "file";
                                newFileInput.accept = ".csv, .pdf";
                                newFileInput.className =
                                    "form-control filestyle filegenerate required";
                                newFileInput.setAttribute(
                                    "data-buttonName",
                                    "btn-shadow btn-transition btn-outline-danger p-2"
                                );
                                newFileInput.setAttribute(
                                    "data-buttonText",
                                    "Choisir un fichier"
                                );
                                newFileInput.setAttribute(
                                    "data-placeholder",
                                    "Aucun fichier choisi"
                                );

                                newFileInput.id =
                                    "fichier_document_appartement" +
                                    currentPosition; // Utilisez un ID unique
                                newFileInput.name =
                                    "fichier_" +
                                    $("#numero_document_appartement").val();
                                newFileInput.setAttribute(
                                    "data-iconName",
                                    "fa fa-folder-open"
                                );
                                console.log("Numéro du document :", newFileInput);

                                // newFileInput.files = new DataTransfer().items.add(filepath);
                                // Créez un nouvel objet DataTransfer et ajoutez la valeur du premier champ de fichier
                                const dataTransfer = new DataTransfer();
                                const premierChampFichier =
                                    document.getElementById(
                                        "fichier_document_appartement"
                                    );

                                dataTransfer.items.add(
                                    premierChampFichier.files[0]
                                );
                                newFileInput.files = dataTransfer.files;
                                console.log(
                                    " newFileInput.files ",
                                    newFileInput.files
                                );

                                // Ajoutez le nouvel champ de fichier à l'élément avec l'ID "documentcontrat_lvt"

                                document
                                    .getElementById("documentsappartement")
                                    .appendChild(newFileInput);

                                // const documentcontrat = document.getElementById("documentcontrat");
                                // if (documentcontrat) {
                                //     document.getElementById("documentcontrat").appendChild(newFileInput);
                                // }
                                $(".filegenerate").hide();
                            }

                            //    $("#documentcontrat_lvt").append(
                            //     `
                            //         <input type="file"
                            //         accept=".csv, .pdf"
                            //         class="form-control filestyle required"
                            //         data-buttonName="btn-shadow btn-transition btn-outline-danger p-2"
                            //         data-buttonText="Choisir un fichier"
                            //         data-placeholder="Aucun fichier choisi"
                            //         id="fichier_contrat_annexes_contrat" name="fichier"
                            //         data-iconName="fa fa-folder-open">
                            //     `
                            //    )
                        }
                        if (tagForm == "contrat_annexesreyhan_contrat") {
                            if (filepath) {
                                //  $scope.dataInTabPane[tagForm]["data"][currentPosition][
                                //         "filepath"
                                // ] = filepath;

                                const newFileInput =
                                    document.createElement("input");
                                newFileInput.type = "file";
                                newFileInput.accept = ".csv, .pdf";
                                newFileInput.className =
                                    "form-control filestyle filegenerate required";
                                newFileInput.setAttribute(
                                    "data-buttonName",
                                    "btn-shadow btn-transition btn-outline-danger p-2"
                                );
                                newFileInput.setAttribute(
                                    "data-buttonText",
                                    "Choisir un fichier"
                                );
                                newFileInput.setAttribute(
                                    "data-placeholder",
                                    "Aucun fichier choisi"
                                );

                                newFileInput.id =
                                    "fichier_contrat_annexesreyhan_contrat_" +
                                    currentPosition; // Utilisez un ID unique
                                newFileInput.name =
                                    "fichier_" +
                                    $(
                                        "#numero_contrat_annexesreyhan_contrat"
                                    ).val();
                                newFileInput.setAttribute(
                                    "data-iconName",
                                    "fa fa-folder-open"
                                );
                                // newFileInput.files = new DataTransfer().items.add(filepath);
                                // Créez un nouvel objet DataTransfer et ajoutez la valeur du premier champ de fichier
                                const dataTransfer = new DataTransfer();
                                const premierChampFichier =
                                    document.getElementById(
                                        "fichier_contrat_annexesreyhan_contrat"
                                    );

                                dataTransfer.items.add(
                                    premierChampFichier.files[0]
                                );
                                newFileInput.files = dataTransfer.files;
                                console.log(
                                    " newFileInput.files ",
                                    newFileInput.files
                                );

                                // Ajoutez le nouvel champ de fichier à l'élément avec l'ID "documentcontrat_lvt"

                                document
                                    .getElementById("documentcontrat")
                                    .appendChild(newFileInput);

                                $(".filegenerate").hide();
                            }
                        }
                    }
                });

                if (!findError) {
                    console.log($scope.dataInTabPane[tagForm]["data"]);
                    $scope.totalfacture = 0;
                    $scope.dataInTabPane[tagForm]["data"].forEach((elmt) => {
                        console.log(elmt);
                        $scope.totalfacture =
                            $scope.totalfacture + parseInt(elmt.montant);
                        $("#retourcaution_id").val(+$scope.totalfacture);
                    });

                    $scope.emptyform(tagForm);

                    if (tagForm == "contrat_annexes_contrat") {
                        var fileInput = document.getElementById(
                            "fichier_contrat_annexes_contrat"
                        );
                        if (fileInput) {
                            fileInput.value = "";
                        }
                    }
                    if (tagForm == "contrat_annexesreyhan_contrat") {
                        var fileInput = document.getElementById(
                            "fichier_contrat_annexesreyhan_contrat"
                        );
                        if (fileInput) {
                            fileInput.value = "";
                        }
                    }
                    if (tagForm == "document_appartement") {
                        var fileInput = document.getElementById(
                            "fichier_document_appartement"
                        );
                        if (fileInput) {
                            fileInput.value = "";
                        }
                    }

                    $scope.reInit();
                    if (tagForm == "periodepaiementloyer_paiementloyer") {
                        console.log("iciic ciiciic ");
                        $("#montant_periodepaiementloyer_paiementloyer").val(
                            $scope.dataInTabPane[tagForm]["data"][0]["montant"]
                        );
                    }
                } else {
                    $scope.showToast("", message_duplicatevalue, "error");
                }
            } else if (action == "delete") {
                // remettre l'intervention dans la liste des interventions
                if ($scope.intervations) {
                    $scope.interventions.forEach((element, index) => {
                        if (
                            element.id ==
                            $scope.dataInTabPane[tagForm]["data"][currentIndex][
                            "interventiondetail_id"
                            ]
                        ) {
                            $scope.dataPage["interventions"].push(element);
                        }
                    });
                }

                $scope.totalfacture =
                    $scope.totalfacture -
                    parseInt(
                        $scope.dataInTabPane[tagForm]["data"][currentIndex][
                        "montant"
                        ]
                    );

                $scope.dataInTabPane[tagForm]["data"].splice(currentIndex, 1);
                $("#retourcaution_id").val(+$scope.totalfacture);
            } else if (action == "update") {
                $scope.dataInTabPane[tagForm]["data"][currentIndex][keyUpdate] =
                    valueUpdate;

                // console.log($scope.dataInTabPane[tagForm]["data"][keyUpdate]);
                console.log(
                    $scope.dataInTabPane[tagForm]["data"][currentIndex]
                );
            }
        };

        $scope.actionSurTabPaneTypeAppart = function (
            action,
            tagForm,
            currentIndex = 0,
            type = "",
            indextab,
            keyUpdate = null,
            valueUpdate = null
        ) {
            // console.log(action, tagForm);

            if (action == "add") {
                // console.log("add") ;
                var speciale = false;
                var currentPosition =
                    $scope.dataInTabPane[tagForm]["data"].length;
                $scope.dataInTabPane[tagForm]["data"].push({});
                var message_duplicatevalue = "";
                var findError = false;
                $(
                    "input[id$=" +
                    tagForm +
                    "], textarea[id$=" +
                    tagForm +
                    "], select[id$=" +
                    tagForm +
                    "]"
                ).each(function () {
                    getValue = $(this).val();
                    /*      if(tagForm == "typeappartement_piece_equipepementpiece_typeappartement_piece"){

        getdetailid = document.getElementById("detailpiece +  +").val() ;
    }*/
                    //  console.log(getValue) ;
                    var indexNameInTab = $(this)
                        .attr("id")
                        .substring(
                            0,
                            $(this).attr("id").length - tagForm.length - 1
                        );
                    console.log(indexNameInTab);
                    if ($(this).hasClass("required") && !$(this).val()) {
                        findError = true;
                        $scope.dataInTabPane[tagForm]["data"].splice(
                            currentPosition,
                            1
                        );
                        message_duplicatevalue =
                            "Veuillez remplir tous les champs obligatoires";
                        return !findError;
                    }
                    if (
                        $scope.dataInTabPane[tagForm]["data"] &&
                        $scope.dataInTabPane[tagForm]["data"].length > 0
                    ) {
                        var trouve = false;
                        var index = null;
                        for (
                            var i = 0;
                            i < $scope.dataInTabPane[tagForm]["data"].length;
                            i++
                        ) {
                            if (
                                $scope.dataInTabPane[tagForm]["data"][i][
                                indexNameInTab + "_text"
                                ] == $(this).find("option:selected").text()
                            ) {
                                trouve = true;
                                index = i;
                            }
                        }

                        /*if (trouve == true) {
            findError = true;
            $scope.dataInTabPane[tagForm]['data'].splice((currentPosition), 1);
            message_duplicatevalue = 'Erreur: ' + $(this).find("option:selected").text() + ' existe déjà.';
            return !findError;
        }*/
                    }

                    if ($(this).is("select")) {
                        if (!findError) {
                            $scope.dataInTabPane[tagForm]["data"][
                                currentPosition
                            ][indexNameInTab + "_text"] = $(this)
                                .find("option:selected")
                                .text();
                            indexNameInTab = indexNameInTab + "_id";

                            console.log($scope.dataInTabPane[tagForm]["data"]);
                        } else {
                            $scope.showToast(
                                "",
                                message_duplicatevalue,
                                "error"
                            );
                        }
                    } else if ($(this).is(":checkbox")) {
                        getValue = $(this).prop("checked");
                    }

                    if (!speciale) {
                        $scope.dataInTabPane[tagForm]["data"][currentPosition][
                            indexNameInTab
                        ] = getValue;
                    }
                });

                if (!findError) {
                    $scope.emptyform(tagForm);
                    $scope.reInit();
                } else {
                    $scope.showToast("", message_duplicatevalue, "error");
                }
            } else if (action == "delete") {
                $scope.dataInTabPane[tagForm]["data"].splice(currentIndex, 1);
            } else if (action == "update") {
                $scope.dataInTabPane[tagForm]["data"][currentIndex][keyUpdate] =
                    valueUpdate;
            }
        };

        $scope.actionSurTabPaneAppartement = function (
            action,
            tagForm,
            detailId = 0,
            currentIndex = 0,
            type = "",
            indextab,
            keyUpdate = null,
            valueUpdate = null
        ) {
            // console.log(action, tagForm);

            if (action == "add") {
                // console.log("add") ;
                var speciale = false;
                // console.log(tagForm)
                var currentPosition =
                    $scope.dataInTabPane[tagForm]["data"].length;
                $scope.dataInTabPane[tagForm]["data"].push({});
                var message_duplicatevalue = "";
                var findError = false;
                $(
                    "input[id$=" +
                    tagForm +
                    "], textarea[id$=" +
                    tagForm +
                    "], select[id$=" +
                    tagForm +
                    "]"
                ).each(function () {
                    getValue = $(this).val();
                    getdetailid = detailId;
                    //  console.log(getValue) ;
                    var indexNameInTab = $(this)
                        .attr("id")
                        .substring(
                            0,
                            $(this).attr("id").length - tagForm.length - 1
                        );
                    //  console.log($(this).attr('id').length , tagForm.length - 1) ;
                    console.log(indexNameInTab);
                    indexNameInTab = "equipement";
                    if ($(this).hasClass("required") && !$(this).val()) {
                        findError = true;
                        $scope.dataInTabPane[tagForm]["data"].splice(
                            currentPosition,
                            1
                        );
                        message_duplicatevalue =
                            "Veuillez remplir tous les champs obligatoires";
                        return !findError;
                    }
                    if (
                        $scope.dataInTabPane[tagForm]["data"] &&
                        $scope.dataInTabPane[tagForm]["data"].length > 0
                    ) {
                        var trouve = false;
                        var index = null;
                        for (
                            var i = 0;
                            i < $scope.dataInTabPane[tagForm]["data"].length;
                            i++
                        ) {
                            if (
                                $scope.dataInTabPane[tagForm]["data"][i][
                                indexNameInTab + "_text"
                                ] == $(this).find("option:selected").text()
                            ) {
                                trouve = true;
                                index = i;
                            }
                        }
                        /*if (trouve == true) {
            findError = true;
            $scope.dataInTabPane[tagForm]['data'].splice((currentPosition), 1);
            message_duplicatevalue = 'Erreur: ' + $(this).find("option:selected").text() + ' existe déjà.';
            return !findError;
        }*/
                    }
                    if ($(this).is("select")) {
                        if (!findError) {
                            if (
                                $(this).find("option:selected").text() !==
                                "equipement"
                            ) {
                                $scope.dataInTabPane[tagForm]["data"][
                                    currentPosition
                                ][indexNameInTab + "_text"] = $(this)
                                    .find("option:selected")
                                    .text();
                            }
                            indexNameInTab = indexNameInTab + "_id";
                            indexNameInTab2 = "detailId";
                            console.log(detailId);
                        } else {
                            $scope.showToast(
                                "",
                                message_duplicatevalue,
                                "error"
                            );
                        }
                    } else if ($(this).is(":checkbox")) {
                        getValue = $(this).prop("checked");
                    }

                    if (!speciale) {
                        if (getValue !== "") {
                            $scope.dataInTabPane[tagForm]["data"][
                                currentPosition
                            ][indexNameInTab] = getValue;
                        }
                        $scope.dataInTabPane[tagForm]["data"][currentPosition][
                            indexNameInTab2
                        ] = getdetailid;
                    }
                    console.log($scope.dataInTabPane[tagForm]["data"]);
                });

                if (!findError) {
                    $scope.emptyform(tagForm);
                    $scope.reInit();
                } else {
                    $scope.showToast("", message_duplicatevalue, "error");
                }
            } else if (action == "delete") {
                $scope.dataInTabPane[tagForm]["data"].splice(currentIndex, 1);
            } else if (action == "update") {
                $scope.dataInTabPane[tagForm]["data"][currentIndex][keyUpdate] =
                    valueUpdate;
            }
        };
        $scope.deletObjetInDataTabePane = function (item, tagForm, index) {
            if (
                $scope.dataInTabPane[tagForm]["data"] &&
                $scope.dataInTabPane[tagForm]["data"].length > 0
            ) {
                $scope.dataInTabPane[tagForm]["data"].splice(index, 1);
            }
        };
        $scope.trieParOrdreCroissant = function (tableau) {
            if (tableau && tableau.length > 0) {
                for (i = 0; i < tableau.length - 1; i++) {
                    for (j = i; j < tableau.length; j++) {
                        var tempon = tableau[i];
                        if (tableau[i].supplement < tableau[j].supplement) {
                            tableau[i] = tableau[j];
                            tableau[j] = tempon;
                        }
                    }
                }
            }
            return tableau;
        };
        $scope.initNotif = {
            progressBar: true,
            close: true,
            closeOnClick: true,
            timeout: false,
            title: "",
            message: "",
            position: "topRight",
            linkUrl: null,
            onClose: function (instance, toast, closedBy) {
                //$scope.openNotif(instance.linkUrl);
            },
        };
        $scope.showToast = function (
            title,
            msg,
            type = "success",
            withTimeout = 5000,
            linkUrl = null
        ) {
            console.log("!!!!!!!!!! arrive dans la fonction", type);
            $scope.initNotif.timeout = withTimeout;
            if (!(withTimeout > 0)) {
                $scope.initNotif.progressBar = false;
            }
            $scope.initNotif.title = title;
            $scope.initNotif.message = msg;
            $scope.initNotif.linkUrl = linkUrl;

            if (type.indexOf("success") !== -1) {
                iziToast.success($scope.initNotif);
            } else if (type.indexOf("warning") !== -1) {
                iziToast.warning($scope.initNotif);
            } else if (type.indexOf("error") !== -1) {
                iziToast.error($scope.initNotif);
            } else if (type.indexOf("info") !== -1) {
                iziToast.info($scope.initNotif);
            }
            if (!withTimeout) {
                $scope.playAudio();
            }
        };

        //Fonction pour modification données provenant d'un select2 dynamique
        $scope.editInSelect2 = function (
            type,
            id,
            typeForeign,
            tagForm = null
        ) {
            if (id) {
                var req = type + "s";
                rewriteReq = req + "(id:" + id + ")";
                Init.getElement(rewriteReq, listofrequests_assoc[req]).then(
                    function (data) {
                        if (data) {
                            $scope.dataPage[req] = data;
                            setTimeout(function () {
                                $("#" + type + "_id_" + typeForeign)
                                    .val(id)
                                    .trigger("change");
                            }, 1000);
                        }
                    },
                    function (msg) {
                        toastr.error(msg);
                    }
                );
            }
        };

        //Fonction pour modification données provenant d'un select2 dynamique
        $scope.editInSelect2Costum = function (type, id, typeForeign) {
            // console.log("type", type, "id", id, "typeForeign", typeForeign, '#' + type + '_id_' + typeForeign);
            var req = type + "s";

            if (type === "locataire1") {
                req = "locataires";
            }
            // console.log(req);
            rewriteReq = req + "(id:" + id + ")";
            Init.getElement(rewriteReq, listofrequests_assoc[req]).then(
                function (data) {
                    if (data) {
                        $scope.dataPage[req] = data;
                        console.log("donnees: #" + type + "_" + typeForeign);

                        setTimeout(function () {
                            $("#" + type + "_" + typeForeign)
                                .val(id)
                                .trigger("change");
                        }, 1000);
                    }
                },
                function (msg) {
                    toastr.error(msg);
                }
            );
        };

        /*** FONCTIONS PERSONNALISEES POUR LE FONCTIONNEMENT ***/

        //---DEBUT => Tester si la valeur est un entier ou pas---//
        $scope.estEntier = function (
            val,
            superieur = true,
            peutEtreEgaleAzero = false
        ) {
            //tags: isInt, tester entier
            var retour = false;
            if (val == undefined || val == null) {
                retour = false;
            } else if (val === "") {
                retour = false;
            } else if (isNaN(val) == true) {
                retour = false;
            } else if (parseInt(val) != parseFloat(val)) {
                retour = false;
            } else {
                if (superieur == false) {
                    //entier inférieur
                    if (parseInt(val) <= 0 && peutEtreEgaleAzero == true) {
                        //]-inf; 0]
                        retour = true;
                    } else if (
                        parseInt(val) < 0 &&
                        peutEtreEgaleAzero == false
                    ) {
                        //]-inf; 0[
                        retour = true;
                    } else {
                        retour = false;
                    }
                } else {
                    //entier supérieur
                    if (parseInt(val) >= 0 && peutEtreEgaleAzero == true) {
                        //[0; +inf[
                        retour = true;
                    } else if (
                        parseInt(val) > 0 &&
                        peutEtreEgaleAzero == false
                    ) {
                        //]0; +inf[
                        retour = true;
                    } else {
                        retour = false;
                    }
                }
            }
            return retour;
        };
        //---FIN => Tester si la valeur est un entier ou pas---//

        //---DEBUT => Tester si la valeur est un réel ou pas---//
        $scope.estFloat = function (
            val,
            superieur = true,
            peutEtreEgaleAzero = false
        ) {
            //tags: isFloat, tester réel
            var retour = false;
            if (val == undefined || val == null) {
                retour = false;
            } else if (val === "") {
                retour = false;
            } else if (isNaN(val) == true) {
                retour = false;
            } else {
                if (superieur == false) {
                    //entier inférieur
                    if (parseFloat(val) <= 0 && peutEtreEgaleAzero == true) {
                        //]-inf; 0]
                        retour = true;
                    } else if (
                        parseFloat(val) < 0 &&
                        peutEtreEgaleAzero == false
                    ) {
                        //]-inf; 0[
                        retour = true;
                    } else {
                        retour = false;
                    }
                } else {
                    //entier supérieur
                    if (parseFloat(val) >= 0 && peutEtreEgaleAzero == true) {
                        //[0; +inf[
                        retour = true;
                    } else if (
                        parseFloat(val) > 0 &&
                        peutEtreEgaleAzero == false
                    ) {
                        //]0; +inf[
                        retour = true;
                    } else {
                        retour = false;
                    }
                }
            }
            return retour;
        };
        //---FIN => Tester si la valeur est un réel ou pas---//

        //Avoir une valeur aleatoire
        $scope.getRandomValue = function () {
            return Math.floor(Math.random() * 6 + 1);
        };

        //Pour les boutons hamburger car si on avait 2 tableaux ca posait problème
        $scope.getIdForButtonBurger = function (type, index) {
            var retour = type + "" + index;
            return retour;
        };

        //Récupérer un élement et faire des actions
        $scope.getItemWithGraphQl = function (type, filtres = null) {
            var elementsFiltres = "";
            if (filtres) {
                elementsFiltres = "(" + filtres + ")";
            }
            var typeAvecS = type + "s";
            var rewriteReq = typeAvecS + elementsFiltres;
            Init.getElement(rewriteReq, listofrequests_assoc[typeAvecS]).then(
                function (data) {
                    if (data) {
                        $scope.dataPage[typeAvecS] = data;
                    }
                },
                function (msg) {
                    toastr.error(msg);
                }
            );
        };

        //Récupérer un élément du scope
        $scope.getOneItem = function (taleau, idElement, returnName = false) {
            console.log("getOneItem");
            var retour = [];
            $.each(taleau, function (keyItem, oneItem) {
                if (oneItem.id == idElement) {
                    if (returnName == true) {
                        retour = oneItem.designation;
                    } else {
                        retour = oneItem;
                    }
                    console.log(retour);
                    return retour;
                }
            });
            return retour;
        };

        $scope.showModalDetail = function (type, itemId, modal = null) {
            $scope.detailParentId = itemId;
            $scope.emptyform("detail" + type, true);
            $scope.pageChanged("detail" + type);

            var formatId = "id";
            var listeattributs_filter = [];
            var listeattributs = listofrequests_assoc[type + "s"];

            reqwrite = type + "s" + "(" + formatId + ":" + itemId + ")";

            Init.getElement(
                reqwrite,
                listeattributs,
                listeattributs_filter
            ).then(
                function (data) {
                    var item = data[0];
                    $scope.item_update = item;

                    $("#modal_details" + type).modal("show");

                    if (type.indexOf("locationvente") !== -1) {
                        $scope.hideButton = false;
                        $scope.detailContrat = item;
                        console.log("$scope.detailContrat.paiementloyers");
                        $scope.deleteDocument = function (document) {
                            if (document === "document") {
                                $("#document_" + type).val(null);
                                $scope.item_update.document = null;
                                console.log($("#document_" + type).val());
                            } else if (document === "scanpreavis") {
                                console.log($("#scanpreavis_" + type).val());
                                $("#scanpreavis_" + type).val("");
                                $scope.item_update.scanpreavis = "";
                                console.log($("#scanpreavis_" + type).val());
                            }
                        };
                        $("#descriptifdetail_" + type).val(item.descriptif);
                        $("#appartementdetail_" + type)
                            .val(item.appartement.id)
                            .change();
                        $("#periodicitedetail_" + type)
                            .val(item.periodicite.id)
                            .trigger("change");
                        $("#prixvilladetail_" + type).val(item.prixvilla);
                        $("#acompteinitialdetail_" + type).val(
                            item.acompteinitial
                        );
                        $("#maturitedetail_" + type).val(item.maturite);
                        $("#indemnitedetail_" + type).val(item.indemnite);
                        $("#fraiscoutlocationventedetail_" + type).val(
                            item.fraiscoutlocationvente
                        );
                        $("#apportinitialdetail_" + type).val(
                            item.apportinitial
                        );
                        $("#apportiponctueldetail_" + type).val(
                            item.apportiponctuel
                        );
                        $("#dureelocationventedetail_" + type).val(
                            item.dureelocationvente
                        );
                        $("#clausepenaledetail_" + type).val(item.clausepenale);
                        $("#datedebutcontratdetail_" + type).val(
                            item.datedebutcontrat
                        );
                        $("#dateecheancedetail_" + type).val(item.dateecheance);
                        $("#dateenregistrementdetail_" + type).val(
                            item.dateenregistrement
                        );
                        $("#dateremiseclesdetail_" + type).val(
                            item.dateremisecles
                        );
                        // $('#depotinitial_' + type).val(item.depot_initial);
                        $("#typecontratdetail_" + type)
                            .val(item.typecontrat.id)
                            .trigger("change");

                        $("#delaipreavidetail_" + type)
                            .val(item.delaipreavi.id)
                            .trigger("change");
                        if (item["rappelpaiement"]) {
                            $("#rappelpaiementdetail_" + type)
                                .val(item["rappelpaiement"])
                                .change();
                        }
                        $("#locataireexistantdetail_" + type)
                            .val(item.locataire.id)
                            .trigger("change");

                        // $('#typelocataire_' + type).val(item.typelocataire_id).change();
                        //  typelocataire = "locationvente";
                        setTimeout(function () {
                            if (item.locataire.typelocataire.id == 1) {
                                $(".2").hide();
                                console.log(
                                    "name locataire " + item.locataire.nom
                                );
                                console.log(item.locataire.nom);
                                $("#nomdetail_" + type).val(item.locataire.nom);
                                $("#prenomdetail_" + type).val(
                                    item.locataire.prenom
                                );
                                $("#telephoneportable1detail_" + type).val(
                                    item.locataire.telephoneportable1
                                );
                                $("#telephoneportable2detail_" + type).val(
                                    item.locataire.telephoneportable2
                                );
                                $("#telephonebureaudetail_" + type).val(
                                    item.locataire.telephonebureau
                                );
                                $("#emaildetail_" + type).val(
                                    item.locataire.email
                                );
                                $("#professiondetail_" + type).val(
                                    item.locataire.profession
                                );
                                $("#agedetail_" + type).val(item.locataire.age);
                                $("#cnidetail_" + type).val(item.locataire.cni);
                                $("#passeportdetail_" + type).val(
                                    item.locataire.passeport
                                );
                                $("#revenudetail_" + type).val(
                                    item.locataire.revenus
                                );
                                $(
                                    "#nomcompletpersonnepriseenchargedetail_" +
                                    type
                                ).val(
                                    item.locataire
                                        .nomcompletpersonnepriseencharge
                                );
                                $(
                                    "#telephonepersonnepriseenchargedetail_" +
                                    type
                                ).val(
                                    item.locataire
                                        .telephonepersonnepriseencharge
                                );
                                if (item.locataire.expatlocale == "Locale") {
                                    $("#expatdetail_" + typelocataire).val(
                                        "Locale"
                                    );
                                }
                                if (item.locataire.expatlocale == "Expatrié") {
                                    $("#expatdetail_" + typelocataire).val(
                                        "expatrié"
                                    );
                                }
                                $(".1").show();
                            } else if (item.locataire.typelocataire.id == 2) {
                                $(".1").hide();
                                $("#nomentreprisedetail_" + typelocataire).val(
                                    item.locataire.nomentreprise
                                );
                                $(
                                    "#adresseentreprisedetail_" + typelocataire
                                ).val(item.locataire.adresseentreprise);
                                $("#nineadetail_" + typelocataire).val(
                                    item.locataire.ninea
                                );
                                $("#numerorgdetail_" + typelocataire).val(
                                    item.locataire.numerorg
                                );
                                $(
                                    "#personnehabiliteasignerdetail_" +
                                    typelocataire
                                ).val(item.locataire.personnehabiliteasigner);
                                $(
                                    "#fonctionpersonnehabilitedetail_" +
                                    typelocataire
                                ).val(item.locataire.fonctionpersonnehabilite);
                                $(
                                    "#nompersonneacontacterdetail_" +
                                    typelocataire
                                ).val(item.locataire.nompersonneacontacter);
                                $(
                                    "#prenompersonneacontacterdetail_" +
                                    typelocataire
                                ).val(item.locataire.prenompersonneacontacter);
                                $(
                                    "#emailpersonneacontacterdetail_" +
                                    typelocataire
                                ).val(item.locataire.emailpersonneacontacter);
                                $(
                                    "#telephone1personneacontacterdetail_" +
                                    typelocataire
                                ).val(
                                    item.locataire.telephone1personneacontacter
                                );
                                $(
                                    "#telephone2personneacontacterdetail_" +
                                    typelocataire
                                ).val(
                                    item.locataire.telephone2personneacontacter
                                );
                                $("#cnidetail_" + typelocataire).val(
                                    item.locataire.cni
                                );
                                $(".2").show();
                            }
                        }, 1000);
                    }

                    if (type.indexOf("locataire") !== -1) {
                        document.getElementById(
                            "detail_prenom_nom_locataire"
                        ).innerHTML = "";
                        document.getElementById(
                            "detail_appartement_locataire"
                        ).innerHTML = "";
                        if (item.prenom) {
                            $("#detail_prenom_nom_" + type).append(
                                '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full">' +
                                item.prenom +
                                " " +
                                item.nom +
                                '</div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div><span style="margin-left: 5px">' +
                                item.profession +
                                '</span></div><div><span style="margin-left: 5px">tel: ' +
                                item.telephoneportable1 +
                                " / " +
                                item.telephoneportable2 +
                                " / " +
                                item.telephonebureau +
                                '</span></div><div><span style="margin-left: 5px">mail: ' +
                                item.email +
                                "</span></div></div></div></div></div></div>"
                            );
                        }
                        if (item.nomentreprise) {
                            $("#detail_prenom_nom_" + type).append(
                                '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full">' +
                                item.nomentreprise +
                                '</div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div><span style="margin-left: 5px">adresse: ' +
                                item.adresseentreprise +
                                '</span></div><div><span style="margin-left: 5px">ninea: ' +
                                item.ninea +
                                '</span></div><div><span style="margin-left: 5px">responsable: ' +
                                item.prenompersonneacontacter +
                                " " +
                                item.nompersonneacontacter +
                                '</span></div><div><span style="margin-left: 5px">email: ' +
                                item.emailpersonneacontacter +
                                '</span></div><div><span style="margin-left: 5px">contacte responsable: ' +
                                item.telephone1personneacontacter +
                                " / " +
                                item.telephone2personneacontacter +
                                "</span></div></div></div></div></div></div>"
                            );
                        }
                        if (item.contrats)
                            document.getElementById(
                                "detail_appartement_locataire"
                            ).innerHTML = "";
                        item.contrats.forEach((elmt) => {
                            //  console.log(elmt.appartement.id) ;
                            $("#detail_appartement_" + type).append(
                                '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full">' +
                                elmt.appartement.nom +
                                '</div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div><span style="text-decoration: underline">Debut du contrat:</span><span style="margin-left: 5px">' +
                                elmt.datedebutcontrat +
                                '</span></div><div><span style="text-decoration: underline">Montant du loyer:</span><span style="margin-left: 5px">' +
                                elmt.montantloyer +
                                '</span></div><div id="detail_loyerpaye_' +
                                elmt.appartement.id +
                                '"><span style="text-decoration: underline">Loyer payés:</span></div></div></div></div></div></div>'
                            );
                        });
                    }

                    if ($scope.dataPage["demandeinterventions"]) {
                        document.getElementById(
                            "detail_demandeintervention_locataire"
                        ).innerHTML = "";

                        $scope.dataPage["demandeinterventions"].forEach(
                            (elmt) => {
                                console.log(elmt);
                                if (
                                    elmt.locataire &&
                                    elmt.locataire.id == item.id
                                ) {
                                    $(
                                        "#detail_demandeintervention_" + type
                                    ).append(
                                        '<span style="margin-right: 5px">' +
                                        elmt.designation +
                                        "</span><br>"
                                    );
                                }
                            }
                        );
                    }

                    if ($scope.dataPage["paiementloyers"]) {
                        $scope.dataPage["paiementloyers"].forEach((elmt) => {
                            if (elmt.contrat.locataire.id == item.id) {
                                console.log("yeah");
                                if (item.contrats) {
                                    item.contrats.forEach((elmt3) => {
                                        if (
                                            elmt3.appartement.nom ==
                                            elmt.contrat.appartement.nom
                                        ) {
                                            console.log(elmt3.appartement.id);
                                            $(
                                                "#detail_loyerpaye_" +
                                                elmt3.appartement.id
                                            ).append(
                                                '<span style="margin-left: 5px">' +
                                                elmt.periode +
                                                " </span>"
                                            );
                                        }
                                    });
                                }
                            }
                        });
                    }

                    let typelocataire;

                    if (type.indexOf("contrat") !== -1) {
                        $scope.hideButton = false;
                        $scope.detailContrat = item;
                        console.log("$scope.detailContrat.paiementloyers");
                        $scope.deleteDocument = function (document) {
                            if (document === "document") {
                                $("#document_" + type).val(null);
                                $scope.item_update.document = null;
                                console.log($("#document_" + type).val());
                            } else if (document === "documentrecucaution") {
                                console.log(
                                    $("#documentrecucaution_" + type).val()
                                );
                                $("#documentrecucaution_" + type).val("");
                                $scope.item_update.documentrecucaution = "";
                                console.log(
                                    $("#documentrecucaution_" + type).val()
                                );
                            } else if (document === "documentretourcaution") {
                                $("#documentretourcaution_" + type).val("");
                                $scope.item_update.documentretourcaution = "";
                                console.log(
                                    $("#documentretourcaution_" + type).val()
                                );
                            } else if (document === "scanpreavis") {
                                console.log($("#scanpreavis_" + type).val());
                                $("#scanpreavis_" + type).val("");
                                $scope.item_update.scanpreavis = "";
                                console.log($("#scanpreavis_" + type).val());
                            }
                        };

                        $("#descriptifdetail_" + type).val(item.descriptif);
                        $("#montantloyerdetail_" + type).val(
                            item.montantloyerformat
                        );
                        $("#montantloyerbasedetail_" + type).val(
                            item.montantloyerbaseformat
                        );
                        $("#dateenregistrementdetail_" + type).val(
                            item.dateenregistrement
                        );
                        $("#datedebutcontratdetail_" + type).val(
                            item.datedebutcontrat
                        );

                        $("#daterenouvellementdetail_" + type).val(
                            item.daterenouvellement
                        );
                        $("#datepremierpaiementdetail_" + type).val(
                            item.datepremierpaiement
                        );
                        $("#montantloyertomdetail_" + type).val(
                            item.montantloyertomformat
                        );
                        $("#montantchargedetail_" + type).val(
                            item.montantchargeformat
                        );
                        $("#tauxrevisiondetail_" + type).val(item.tauxrevision);
                        $("#frequencerevisiondetail_" + type).val(
                            item.frequencerevision
                        );
                        $("#dateretourcautiondetail_" + type).val(
                            item.dateretourcaution
                        );
                        $("#typerenouvellementdetail_" + type).val(
                            item["typerenouvellement"].designation
                        );
                        //  console.log(item);
                        if (item["delaipreavi"]) {
                            $("#delaipreavidetail_" + type).val(
                                item["delaipreavi"].designation
                            );
                        }
                        if (item["rappelpaiement"]) {
                            $("#rappelpaiementdetail_" + type)
                                .val(item["rappelpaiement"])
                                .change();
                        }

                        //    $('#demanderesiliationsdetail_' + type).val(item['demanderesiliations'].designation);
                        $("#typecontratdetail_" + type).val(
                            item["typecontrat"].designation
                        );

                        // console.log(item.appartement.nom) ;
                        $("#appartementdetail_" + type).val(
                            item.appartement.nom
                        );
                        $("#locatairedetail_" + type).val(
                            item["locataire"].designation
                        );
                        $("#retourcaution_" + type).val(item.retourcaution);
                        $("#rappelpaiementdetail_" + type).val(
                            item.rappelpaiement
                        );
                        console.log(item);
                        console.log(item.rappelpaiement_format);
                        console.log($("#retourcaution_" + type).val());

                        typelocataire = "locataire";
                        // $('#typelocataire_' + type).val(item.typelocataire_id).change();
                        setTimeout(function () {
                            if (item.locataire.typelocataire.id == 1) {
                                $(".2").hide();
                                console.log(item.locataire.nom);
                                $("#nomdetail_" + typelocataire).val(
                                    item.locataire.nom
                                );
                                $("#prenomdetail_" + typelocataire).val(
                                    item.locataire.prenom
                                );
                                $(
                                    "#telephoneportable1detail_" + typelocataire
                                ).val(item.locataire.telephoneportable1);
                                $(
                                    "#telephoneportable2detail_" + typelocataire
                                ).val(item.locataire.telephoneportable2);
                                $(
                                    "#telephonebureaudetail_" + typelocataire
                                ).val(item.locataire.telephonebureau);
                                $("#emaildetail_" + typelocataire).val(
                                    item.locataire.email
                                );
                                $("#professiondetail_" + typelocataire).val(
                                    item.locataire.profession
                                );
                                $("#agedetail_" + typelocataire).val(
                                    item.locataire.age
                                );
                                $("#cnidetail_" + typelocataire).val(
                                    item.locataire.cni
                                );
                                $("#passeportdetail_" + typelocataire).val(
                                    item.locataire.passeport
                                );
                                $("#revenudetail_" + typelocataire).val(
                                    item.locataire.revenus
                                );
                                $(
                                    "#nomcompletpersonnepriseenchargedetail_" +
                                    typelocataire
                                ).val(
                                    item.locataire
                                        .nomcompletpersonnepriseencharge
                                );
                                $(
                                    "#telephonepersonnepriseenchargedetail_" +
                                    typelocataire
                                ).val(
                                    item.locataire
                                        .telephonepersonnepriseencharge
                                );
                                if (item.locataire.expatlocale == "Locale") {
                                    $("#expatdetail_" + typelocataire).val(
                                        "Locale"
                                    );
                                }
                                if (item.locataire.expatlocale == "Expatrié") {
                                    $("#expatdetail_" + typelocataire).val(
                                        "expatrié"
                                    );
                                }
                                $(".1").show();
                            } else if (item.locataire.typelocataire.id == 2) {
                                $(".1").hide();
                                $("#nomentreprisedetail_" + typelocataire).val(
                                    item.locataire.nomentreprise
                                );
                                $(
                                    "#adresseentreprisedetail_" + typelocataire
                                ).val(item.locataire.adresseentreprise);
                                $("#nineadetail_" + typelocataire).val(
                                    item.locataire.ninea
                                );
                                $("#numerorgdetail_" + typelocataire).val(
                                    item.locataire.numerorg
                                );
                                $(
                                    "#personnehabiliteasignerdetail_" +
                                    typelocataire
                                ).val(item.locataire.personnehabiliteasigner);
                                $(
                                    "#fonctionpersonnehabilitedetail_" +
                                    typelocataire
                                ).val(item.locataire.fonctionpersonnehabilite);
                                $(
                                    "#nompersonneacontacterdetail_" +
                                    typelocataire
                                ).val(item.locataire.nompersonneacontacter);
                                $(
                                    "#prenompersonneacontacterdetail_" +
                                    typelocataire
                                ).val(item.locataire.prenompersonneacontacter);
                                $(
                                    "#emailpersonneacontacterdetail_" +
                                    typelocataire
                                ).val(item.locataire.emailpersonneacontacter);
                                $(
                                    "#telephone1personneacontacterdetail_" +
                                    typelocataire
                                ).val(
                                    item.locataire.telephone1personneacontacter
                                );
                                $(
                                    "#telephone2personneacontacterdetail_" +
                                    typelocataire
                                ).val(
                                    item.locataire.telephone2personneacontacter
                                );
                                $("#cnidetail_" + typelocataire).val(
                                    item.locataire.cni
                                );
                                $(".2").show();
                            }
                        }, 1000);
                    }

                    if (type.indexOf("demandeintervention") !== -1) {
                        document.getElementById(
                            "detail_descriptif_demandeintervention"
                        ).innerHTML = "";
                        document.getElementById(
                            "detail_immeuble_demandeintervention"
                        ).innerHTML = "";
                        document.getElementById(
                            "detail_demandeur_demandeintervention"
                        ).innerHTML = "";
                        $("#detail_descriptif_" + type).append(
                            "<span>" + item.designation + "</span>"
                        );
                        $("#detail_immeuble_" + type).append(
                            "<span>" + item.immeuble.nom + "</span>"
                        );
                        if (item.locataire && item.locataire.prenom) {
                            $("#detail_demandeur_" + type).append(
                                "<span>" +
                                item.locataire.prenom +
                                " " +
                                item.locataire.nom +
                                " / " +
                                item.appartement.nom +
                                "</span>"
                            );
                        }
                        if (item.locataire && item.locataire.nomentreprise) {
                            $("#detail_demandeur_" + type).append(
                                "<span>" +
                                item.locataire.nomentreprise +
                                " / " +
                                item.appartement.nom +
                                "</span>"
                            );
                        }

                        if (item.typepiece) {
                            $("#detail_demandeur_" + type).append(
                                "<span>" +
                                item.typepiece.designation +
                                " / " +
                                item.immeuble.nom +
                                "</span>"
                            );
                        }
                        console.log(item);
                    }

                    if (type.indexOf("avisecheance") !== -1) {
                        console.log('item item: ', item);

                        $scope.getelements(
                            "paiementecheances",
                            (optionals = {
                                queries: null,
                                typeIds: null,
                                otherFilters: null,
                            }),
                            "avisecheance_id:" + item.id
                        );

                        $scope.detail_avisecheance_id = item.id;

                        // var allPaiementEcheance = item.get_all_paiementecheances;
                        // if (allPaiementEcheance && allPaiementEcheance.length > 0) {
                        //     $scope.detailsPaiementecheances = allPaiementEcheance.map(paiement => {
                        //         return {
                        //             id: paiement.id,
                        //             locataire: (item.contrat.locataire.prenom + ' ' + item.contrat.locataire.nom) || "---",
                        //             date: paiement.date || "---",
                        //             periode: paiement.periodes || "---",
                        //             etat: paiement.etat,
                        //             montant_format: paiement.montant_format || "---",
                        //             modepaiement: paiement.modepaiement.designation || "---",
                        //             avisecheance_id: paiement.avisecheance_id
                        //         };
                        //     });

                        //     console.log('detail paiement: ', $scope.detailsPaiementecheances);
                        // }
                    }


                    if (type.indexOf("immeuble") !== -1) {
                        if (
                            $scope.currentTemplateUrl
                                .toLowerCase()
                                .indexOf("list-immeuble") !== -1
                        ) {
                            $("#contrats").hide();
                            $("#loyerlink").hide();
                            $("#facturelink").hide();
                            $("#assurancelink").hide();
                            $("#contratlink").hide();

                            $("#infosimmeuble").show();
                            $("#infosimmeublelink").show();
                        }

                        if (
                            $scope.currentTemplateUrl
                                .toLowerCase()
                                .indexOf("list-financeimmeuble") !== -1
                        ) {
                            $("#infosimmeublelink").hide();
                            $("#infosimmeuble").hide();

                            $("#contrats").show();
                            $("#loyerlink").show();
                            $("#facturelink").show();
                            $("#assurancelink").show();
                            $("#contratlink").show();
                        }

                        document.getElementById("detail_immeuble").innerHTML =
                            "";
                        document.getElementById(
                            "detail_equipegestion_immeuble"
                        ).innerHTML = "";
                        document.getElementById(
                            "detail_pieceequipements_immeuble"
                        ).innerHTML = "";
                        $("#detail_" + type).append(
                            '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full">' +
                            item.nom +
                            ' ,</div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div id="infosimmeublespan" class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div><span style="margin-left: 5px">' +
                            item.adresse +
                            ' ,</span></div><div><span style="margin-left: 5px">' +
                            item.structureimmeuble.designation +
                            ' ,</span></div><div><span style="margin-left: 5px">Total appartements: ' +
                            item["appartements"].length +
                            " ,</span></div></div></div></div></div></div>"
                        );
                        $("#detail_equipegestion_" + type).append(
                            '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full"></div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div id="contentequipegestion"></div></div></div></div></div></div>'
                        );
                        $("#detail_pieceequipements_" + type).append(
                            '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full"></div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div id="contentpieceequipements"></div></div></div></div></div></div>'
                        );
                        var i = 0;
                        $scope.dataPage["contrats"].forEach((elmt) => {
                            console.log(elmt);
                            if (
                                elmt.appartement.immeuble.id == item.id &&
                                elmt.etat == "1"
                            ) {
                                i++;
                            }
                        });
                        $("#infosimmeublespan").append(
                            '<span style="margin-left: 5px">Appartements loués : ' +
                            i +
                            "</span><br>"
                        );

                        $scope.dataPage["pieceappartements"].forEach((elmt) => {
                            console.log(elmt);
                            if (
                                elmt.immeuble.id == item.id &&
                                elmt.typepiece.iscommun == "1"
                            ) {
                                $("#contentpieceequipements").append(
                                    '<span style="margin-left: 5px">' +
                                    elmt.designation +
                                    "</span><br>"
                                );
                            }
                        });

                        if (item.equipegestion.id) {
                            $scope.dataPage[
                                "equipegestion_membreequipegestions"
                            ].forEach((elmt) => {
                                //console.log(elmt) ;
                                if (
                                    elmt.equipegestion.id ==
                                    item.equipegestion.id
                                ) {
                                    $("#contentequipegestion").append(
                                        '<span style="margin-left: 5px">' +
                                        elmt.fonction.designation +
                                        " : " +
                                        elmt.membreequipegestion.prenom +
                                        " " +
                                        elmt.membreequipegestion.nom +
                                        " ," +
                                        elmt.membreequipegestion.telephone +
                                        "</span><br>"
                                    );
                                }
                            });
                        }
                    }

                    if (type.indexOf("appartement") !== -1) {
                        if (
                            $scope.currentTemplateUrl
                                .toLowerCase()
                                .indexOf("list-appartement") !== -1
                        ) {
                            $("#loyerappartements").hide();
                            $("#loyerappartementslink").hide();
                            $("#factureappartementslink").hide();
                            $("#cautionappartementslink").hide();
                            $("#assuranceappartementslink").hide();

                            $("#infosappartement").show();
                            $("#infosappartementlink").show();
                        }

                        if (
                            $scope.currentTemplateUrl
                                .toLowerCase()
                                .indexOf("list-financeappartement") !== -1
                        ) {
                            $("#infosappartementlink").hide();
                            $("#infosappartement").hide();

                            $("#loyerappartements").show();
                            $("#factureappartementslink").show();
                            $("#cautionappartementslink").show();
                            $("#assuranceappartementslink").show();
                            $("#loyerappartementslink").show();

                            var formatIdcontrat = "appartement_id";
                            var listeattributs_filter = [];
                            var listeattributs =
                                listofrequests_assoc["contrats"];

                            reqwritecontrat =
                                "contrats" +
                                "(" +
                                formatIdcontrat +
                                ":" +
                                itemId +
                                ",etat:1" +
                                ")";
                            Init.getElement(
                                reqwritecontrat,
                                listeattributs,
                                listeattributs_filter
                            ).then(
                                function (data) {
                                    $scope.contratfinance = data[0];
                                },
                                function (msg) {
                                    $scope.showToast("", msg, "error");
                                }
                            );
                        }

                        document.getElementById(
                            "detail_appartement"
                        ).innerHTML = "";
                        document.getElementById(
                            "detail_infolocataire_appartement"
                        ).innerHTML = "";
                        document.getElementById(
                            "detail_pieces_appartement"
                        ).innerHTML = "";
                        $("#detail_pieces_" + type).append(
                            '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full"></div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div id="contentpieceappartements"></div></div></div></div></div></div>'
                        );
                        $("#detail_" + type).append(
                            '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full">' +
                            item.nom +
                            ' ,</div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div id="infosappartementspan" class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div><span style="margin-left: 5px">' +
                            item.niveau +
                            " , " +
                            item.immeuble.nom +
                            ' ,</span></div><div><span style="margin-left: 5px">Type ' +
                            item.typeappartement.designation +
                            ' ,</span></div><div><span style="margin-left: 5px">Superficie:' +
                            item.superficie +
                            ' ,</span></div><div><span style="margin-left: 5px">Proprietaire: ' +
                            item.proprietaire.prenom +
                            " " +
                            item.proprietaire.nom +
                            " ,</span></div></div></div></div></div></div>"
                        );
                        var location = false;

                        $scope.dataPage["contrats"].forEach((elmt) => {
                            if (
                                elmt.appartement.id == item.id &&
                                elmt.etat == "1"
                            ) {
                                location = true;
                                if (elmt.locataire.prenom) {
                                    $("#detail_infolocataire_" + type).append(
                                        '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full">' +
                                        elmt.locataire.prenom +
                                        " " +
                                        elmt.locataire.nom +
                                        ' ,</div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div id="infoslocatairespan" class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div><span style="margin-left: 5px">' +
                                        elmt.locataire.expatlocale +
                                        ' ,</span></div><div><span style="margin-left: 5px">' +
                                        elmt.locataire.profession +
                                        ' ,</span></div><div><span style="margin-left: 5px">Tel: ' +
                                        elmt.locataire.telephoneportable1 +
                                        " - " +
                                        elmt.locataire.telephoneportable2 +
                                        " - " +
                                        elmt.locataire.telephonebureau +
                                        '  ,</span></div><div><span style="margin-left: 5px">Email:' +
                                        elmt.locataire.email +
                                        ' ,</span></div><div><span style="margin-left: 5px">CNI: ' +
                                        elmt.locataire.cni +
                                        ' ,</span></div><div><span style="margin-left: 5px">Passport: ' +
                                        elmt.locataire.passeport +
                                        " ,</span></div></div></div></div></div></div>"
                                    );
                                }
                                if (elmt.locataire.nomentreprise) {
                                    $("#detail_infolocataire_" + type).append(
                                        '<div style="border-radius: 5px ; background-color: #eceeef; margin-bottom: 10px; border: 0.1px solid black" class="p-3" id="basic-accordion"><div class="preview"><div class="accordion"><div class="accordion__pane border-gray-200"><a href="javascript:;" class="accordion__pane__toggle font-medium block"><div class="flex flex-wrap"><div class="w-full">' +
                                        elmt.locataire.nomentreprise +
                                        ' ,</div><div class="w-full md:w-1/2 px-3 text-right"></div></div></a><div id="infoslocatairespan" class="accordion__pane__content mt-2 text-gray-700 leading-relaxed"><div><span style="margin-left: 5px">Locataire morale</span></div><div><span style="margin-left: 5px">' +
                                        elmt.locataire.adresseentreprise +
                                        ' ,</span></div><div><span style="margin-left: 5px">NINEA: ' +
                                        elmt.locataire.ninea +
                                        ' ,</span></div><div><span style="margin-left: 5px">Numero RG:' +
                                        elmt.locataire.numerorg +
                                        ' ,</span></div><div><span style="margin-left: 5px">Representant: ' +
                                        elmt.locataire
                                            .personnehabiliteasigner +
                                        " ," +
                                        elmt.locataire
                                            .fonctionpersonnehabilite +
                                        '</span></div><div><span style="margin-left: 5px">Personne a contacter: ' +
                                        elmt.locataire
                                            .prenompersonneacontacter +
                                        " " +
                                        elmt.locataire
                                            .nompersonneacontacter +
                                        ' ,</span></div><div><span style="margin-left: 5px">Contactes: ' +
                                        elmt.locataire
                                            .emailpersonneacontacter +
                                        " , " +
                                        elmt.locataire
                                            .telephone1personneacontacter +
                                        " ," +
                                        elmt.locataire
                                            .telephone2personneacontacter +
                                        "</span></div></div></div></div></div></div>"
                                    );
                                }

                                if (elmt["paiementloyers"].length > 0) {
                                    var indexx =
                                        elmt["paiementloyers"].length - 1;
                                    // console.log(elmt['paiementloyers'][indexx].periode) ;
                                    $("#infoslocatairespan").append(
                                        '<div><span style="margin-left: 5px">Dernier mois payé: ' +
                                        elmt["paiementloyers"][indexx]
                                            .periode +
                                        "</span></div>"
                                    );
                                }
                            }
                        });
                        if (location == true) {
                            $("#infosappartementspan").append(
                                '<span style="margin-left: 5px">En location : OUI</span><br>'
                            );
                        } else {
                            $("#infosappartementspan").append(
                                '<span style="margin-left: 5px">En location : NON</span><br>'
                            );
                        }

                        if ($scope.dataPage["pieceappartements"]) {
                            $scope.dataPage["pieceappartements"].forEach(
                                (elmt) => {
                                    if (
                                        elmt.appartement &&
                                        elmt.appartement.id == item.id &&
                                        elmt.typepiece.iscommun == "0"
                                    ) {
                                        console.log(elmt);
                                        $("#contentpieceappartements").append(
                                            '<span style="margin-left: 5px">' +
                                            elmt.designation +
                                            "</span><br>"
                                        );
                                    }
                                }
                            );
                        }
                    }

                    if (type.indexOf("user") !== -1) {
                        //update_user
                        $("#name_" + type).val(item.name);
                        $("#email_" + type).val(item.email);
                        setTimeout(function () {
                            $("#employe_" + type)
                                .val(item.employe_id)
                                .trigger("change");
                            $("#role_" + type)
                                .val(
                                    item.roles && item.roles.length > 0
                                        ? item.roles[0].id
                                        : null
                                )
                                .trigger("change");

                            var selectedValuesEntite = new Array();
                            if (item.user_avec_entites) {
                                item.user_avec_entites.forEach((item) => {
                                    selectedValuesEntite.push(item.entite_id);
                                });
                            }
                            var selectedValuesCaisse = new Array();
                            if (item.user_caisses) {
                                item.user_caisses.forEach((item) => {
                                    selectedValuesCaisse.push(item.caisse_id);
                                });
                            }
                            $("#entite_" + type)
                                .val(selectedValuesEntite)
                                .trigger("change");
                            $("#caisse_" + type)
                                .val(selectedValuesCaisse)
                                .trigger("change");
                        }, 1500);
                    }

                    //details facture intervention

                    // if (type.indexof('factureintervention') !== -1) {
                    //     $('#id_factureinterventionfacture').val(item.id);
                    //     $('#datefacture_factureinterventionfacture').val(item.datefacture);
                    //     $('#intervenantassocieintervention_factureinterventionfacture').val(item.intervenantassocie);
                    //     $('#montant_factureinterventionfacture').val(item.montant);
                    //     $('#interventiondetail_factureinterventionfacture').val(item.intervention.id).change();
                    // }
                    //details facture intervention

                    if (type.indexof("factureintervention") !== -1) {
                        $("#id_factureinterventionfacture").val(item.id);
                        $("#datefacture_factureinterventionfacture").val(
                            item.datefacture
                        );
                        $(
                            "#intervenantassocieintervention_factureinterventionfacture"
                        ).val(item.intervenantassocie);
                        $("#montant_factureinterventionfacture").val(
                            item.montant
                        );
                        $("#interventiondetail_factureinterventionfacture")
                            .val(item.intervention.id)
                            .change();
                    }

                    // Si le model contient une image dans son formulaire
                    if (item && item.image !== undefined) {
                        document.getElementById(
                            "detail_image_demandeintervention"
                        ).src = item.image;
                        $("#img" + type)
                            .val("")
                            .attr("required", false)
                            .removeClass("required");
                        $("#affimg" + type).attr(
                            "src",
                            item.image ? item.image : imgupload
                        );
                    }
                    // $("#modal_add"+type).modal('show');
                    setTimeout(function () {
                        $("#modal_add" + type).blockUI_stop();
                    }, 1000);
                },
                function (msg) {
                    $scope.showToast("", msg, "error");
                }
            );
        };
        /*** FONCTIONS PERSONNALISEES POUR LE FONCTIONNEMENT ***/
        // to rewrite url of select2 search
        function dataUrlEntity(query, entity) {
            //$scope.cpt = 1;
            rewriteelement =
                entity +
                "s(" +
                (query.term ? ",search:" + '"' + query.term + '"' : "");

            if (rewriteelement) {
                rewriteelement += ")";
                rewriteelement = encodeURIComponent(rewriteelement);
                var attributs = listofrequests_assoc[entity + "s"];
                rewriteelement =
                    BASE_URL +
                    "graphql?query={" +
                    rewriteelement +
                    "{" +
                    attributs +
                    "}}";
            }

            return rewriteelement;
        }
        // To get Data of search select2
        function processResultsForSearchEntity(getData, entity) {
            if (entity) {
                getData = getData.data[entity + "s"];
            } else {
                getData = [];
            }

            var resultsData = [];
            $.each(getData, function (keyItem, valueItem) {
                if (entity) {

                    if (entity === "proprietaire") {
                        contentToPush = {
                            id: valueItem.id,
                            text: valueItem.prenom + " " + valueItem.nom
                        };
                    } else if (entity === "appartement") {
                        contentToPush = {
                            id: valueItem.id,
                            text: valueItem.nom + ' -- ' + valueItem.proprietaire.prenom + ' ' + valueItem.proprietaire.nom
                        };
                    } else if (entity === "locataire") {

                        contentToPush = {
                            id: valueItem.id,
                            text: valueItem.nomcomplet
                                ? valueItem.nomcomplet
                                : ((valueItem.prenom || '') + " " + (valueItem.nom || '') + (valueItem.nomentreprise ? " (" + valueItem.nomentreprise + ")" : ''))

                        };
                    } else {
                        contentToPush = {
                            id: valueItem.id,
                            text: valueItem.designation
                        };
                    }
                }
                if (contentToPush) {
                    resultsData.push(contentToPush);
                }
            });
            return {
                results: resultsData,
            };
        }

        $(".button-prod").on("dblclick", function (e) {
            alert("Handler for .dblclick() called. " + e.type);
        });

        $scope.cleanSelect = function (id) {
            $("#" + id)
                .val(null)
                .trigger("change");
        };

        $scope.cleanScope = function (nameVar) {
            $scope[nameVar] = null;
        };


        // Fonction pour calculer le montant du loyer final
        $scope.montantLoyerFinal = function () {
            var montantCharge = parseFloat($scope.montantCharge) || 0;
            var montantloyerTom = parseFloat($scope.montantloyerTom) || 0;
            var montantLoyerBase = parseFloat($scope.montantloyerBase) || 0;

            var montantloyerfinal = montantLoyerBase + montantCharge + montantloyerTom;
            $("#montantloyer_contrat").val(montantloyerfinal);

        };




        //  $scope.detailspiece = null ;

        // ecoute des changements tout les formulaires

        $scope.cpt = 1; //2
        function OnChangeSelect2(e) {
            var getId = $(this).attr("id");
            var getValue = $(this).val();
            var type = "";
            var info = true;
            var pagination = false;
            var form = null;

            if (getValue) {
                console.log(
                    "OnChangeSelect 2 id : " + getId + " value : " + getValue
                );
            }
            rewriteReq = "";
            var filters = `id:${getValue},`;

            $scope.cpt = $scope.cpt * 1;
            if (
                $scope.cpt > 0 &&
                getValue !== undefined &&
                getValue !== "" &&
                getValue !== null
            ) {
                $scope.cpt = 0;
            } else {
                getValue = null;
            }
            //console.log("boucle") ;
            // typevilla_appartement
            if (getId == "typevilla_appartement" && getValue) {
                console.log(getValue);
                console.log("get value  typevilla_appartement ");

                $(".divapp").show();
                var typeAvecS = "typeappartement_pieces";
                rewriteReq =
                    typeAvecS + "(typeappartement_id:" + getValue + ")";
                console.log("query : ", listofrequests_assoc[typeAvecS]);
                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("data :  typeappartement_pieces  : ", data);
                        $scope.detailspiece = data;
                        $scope.reInit("typeappartement_piece");
                        //    console.log($scope.detailspiece) ;
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            if (getId == "locataireexistant_locationvente" && getValue) {
                // avisecheances: [],
                console.log("get id et get value  : copreneurs  : ", getValue);
                $scope.copreneursData = [];
                if ($("#est_copreuneur_locationvente").is(":checked")) {
                    var typeAvecS = "copreneurs";
                    rewriteReq = typeAvecS + "(locataire_id:" + getValue + ")";

                    Init.getElement(
                        rewriteReq,
                        listofrequests_assoc[typeAvecS]
                    ).then(
                        function (data) {
                            console.log("data : copreneurs  : ", data);
                            $scope.copreneursData = data;

                            // $scope.reInit("typeappartement_piece");
                            //    console.log($scope.detailspiece) ;
                        },
                        function (msg) {
                            $scope.showToast("", msg, "error");
                        }
                    );
                }
            }

            if (getId == "typeappartement_appartement" && getValue) {
                console.log(getValue);
                console.log("get value  typeappartement_appartement ");
                console.log("query : ", listofrequests_assoc[typeAvecS]);
                $(".divapp").show();
                var typeAvecS = "typeappartement_pieces";
                rewriteReq =
                    typeAvecS + "(typeappartement_id:" + getValue + ")";

                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("data :  typeappartement_pieces  : ", data);
                        $scope.detailspiece = data;
                        $scope.reInit("typeappartement_piece");
                        //    console.log($scope.detailspiece) ;
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            if (getId == "typevente_appartement" && getValue) {
                console.log(getValue);

                if (getValue == 1) {
                    $("#div_prix_appartement").show();
                } else {
                    $("#div_prix_appartement").hide();
                }

            }

            // if (getId == "appartement_contrat" && getValue) {
            //     reqwrite = "appartements" + "(id:" + getValue + ")";
            //     Init.getElement(
            //         reqwrite,
            //         listofrequests_assoc["appartements"]
            //     ).then(function (data) {
            //         var montantcaution = data[0]["montantcaution"];
            //         $("#montantcaution_contrat").val(montantcaution);
            //         $("#valcaution_contrat").val(montantcaution);

            //         var loyerBase = data[0]["montantloyer"];
            //         $("#montantloyerbase_contrat")
            //             .val(loyerBase)
            //             .trigger("change");

            //         var tom = data[0]["tommountant"];
            //         var tlv = data[0]["tlvmountant"];
            //         var isTlv = data[0]["tlv"];
            //         console.log("is tlv: ", isTlv);

            //         var is_tva = data[0]["is_tva"];
            //         var montantTVA = 0;
            //         if (is_tva) {
            //             montantTVA = data[0]["tvamountant"];
            //             $("#message_text").text(
            //                 is_tva + ": " + montantTVA + " F CFA"
            //             );
            //             $("#message_contrat").removeClass("hidden");
            //         } else {
            //             $("#message_contrat").addClass("hidden");
            //         }

            //         $scope.montantLoyer = loyerBase + tom + tlv + montantTVA;

            //         console.log("Loyer de base:", loyerBase);
            //         console.log("Montant Loyer:", $scope.montantLoyer);
            //         console.log("Tom:", tom);

            //         if ($("#isdiplomate_contrat").prop("checked")) {
            //             $("#montantloyertom_contrat").val(0).trigger("change");

            //             $("#tlv_contrat").val(0).trigger("change");
            //             $("#montantcharge_contrat").val(0).trigger("change");
            //             $("#montantloyer_contrat")
            //                 .val(loyerBase)
            //                 .trigger("change");
            //         } else {
            //             $("#montantloyertom_contrat")
            //                 .val(tom)
            //                 .trigger("change");
            //             $("#tlv_contrat").val(tlv).trigger("change");
            //             $("#montantloyer_contrat")
            //                 .val($scope.montantLoyer)
            //                 .trigger("change");
            //         }
            //     });
            // }

            if (getId == "locataire_inbox" && getValue) {

                if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-detailsdemanderesiliation") === -1
                ) {
                    console.log(getValue);
                    console.log("je suis dans le select");

                    if ($scope.currentTemplateUrl === "list-inbox") {
                        var typeAvecS = "contrats";
                        rewriteReq = typeAvecS + "(locataire_id:" + getValue + ")";
                        $scope.dataPage["contrats"] = [];
                        Init.getElement(
                            rewriteReq,
                            listofrequests_assoc[typeAvecS]
                        ).then(
                            function (data) {
                                $scope.dataPage["contrats"] = data;
                                var contrat = data;
                                if (contrat && contrat.length > 1) {
                                    console.log('im here1', contrat);
                                    $("#choixContrat_inbox_div").removeClass("hidden");
                                } else if (contrat && contrat.length === 1) {
                                    $("#choixContrat_inbox_div").addClass("hidden");
                                    $("#contrat_inbox").val(contrat[0]['id']).trigger('change');
                                }
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );
                    } else {
                        var typeAvecS = "appartements";
                        rewriteReq = typeAvecS + "(locataire_id:" + getValue + ")";
                        $scope.dataPage["appartements"] = [];
                        Init.getElement(
                            rewriteReq,
                            listofrequests_assoc[typeAvecS]
                        ).then(
                            function (data) {
                                $scope.dataPage["appartements"] = data;
                                $scope.reInit("detailslocationvente");
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );
                    }
                }
            }
            if (getId == "choixContrat_inbox" && getValue) {
                console.log(getValue);
                console.log("je suis dans le select");

                var typeAvecS = "contrats";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";
                $scope.dataPage["contrats"] = [];
                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        $scope.dataPage["contrats"] = data;
                        var contrat = data;
                        if (contrat && contrat.length > 0) {
                            $("#contrat_inbox").val(contrat[0]['id']).trigger('change');
                            $("#choixContrat_inbox").val(contrat[0]['id']).trigger('change');
                        }
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }
            if (getId == "typefacture_facturelocation" && getValue) {
                var typeAvecS = "typefactures";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";
                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        if (
                            data[0].designation == "eau" ||
                            data[0].designation == "electricite"
                        ) {
                            console.log("eau ou electricite");
                            $("#hidemontant_facturelocation").show();
                        } else {
                            $("#hidemontant_facturelocation").hide();
                        }
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }
            if (getId == "locataire_facturelocation" && getValue) {
                console.log(getValue);
                console.log("je suis dans le select");

                var typeAvecS = "contrats";
                rewriteReq = typeAvecS + "(locataire_id:" + getValue + ")";
                $scope.dataPage["contrats"] = [];
                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        $scope.dataPage["contrats"] = data;
                        $scope.reInit("contrat");
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            // recharge les interventions

            if (
                getId ==
                "interventiondetail_factureintervention_intervention_factureintervention" &&
                getValue
            ) {
                console.log(getValue, "test debbbog");
                // charger le
                $scope.getelements("categorieinterventions");
                console.log($scope.dataPage["categorieinterventions"]);
                console.log($scope.dataPage["detaildevisdetails"]);
                console.log($scope.dataPage["interventions"]);

                $scope.dataPage["categorieinterventions"].forEach((elmt) => {
                    console.log("in on");
                    elmt.interventions.forEach((elmt2) => {
                        console.log("in");
                        if (elmt2.id === getValue) {
                            console.log("est egal");
                            if (elmt2.demandeintervention) {
                                elmt2.demandeintervention.devi.detaildevis.forEach(
                                    (elmt3) => {
                                        elmt3.detaildevisdetails.forEach(
                                            (elmt4) => {
                                                if (
                                                    elmt3.categorieintervention
                                                        .id === elmt.id
                                                ) {
                                                    $(
                                                        "#montant_factureintervention_intervention_factureintervention"
                                                    ).val(
                                                        elmt4.prixunitaire_format
                                                    );
                                                }
                                            }
                                        );
                                    }
                                );
                            }
                            if (elmt2.etatlieu) {
                                elmt2.etatlieu.devi.detaildevis.forEach(
                                    (elmt3) => {
                                        elmt3.detaildevisdetails.forEach(
                                            (elmt4) => {
                                                if (
                                                    elmt3.categorieintervention
                                                        .id === elmt.id
                                                ) {
                                                    $(
                                                        "#montant_factureintervention_intervention_factureintervention"
                                                    ).val(
                                                        elmt4.prixunitaire_format
                                                    );
                                                }
                                            }
                                        );
                                    }
                                );
                            }
                        }
                    });
                });

                if (
                    $(
                        "#montant_factureintervention_intervention_factureintervention"
                    ).val() === ""
                ) {
                    $scope.getelements("categorieinterventions");
                    $(
                        "#montant_factureintervention_intervention_factureintervention"
                    ).val(0);
                }
            }

            if (getId == "modepaiement_paiementintervention" && getValue) {
                console.log(getValue, "in paiement facture");
                //  si le mode de paiement est cheque return true sinon false
                $scope.dataPage["modepaiements"].forEach((elmt) => {
                    if (elmt.id === getValue) {
                        if (elmt.description === "Cheque") {
                            // afficher le div qui etait a dispay block
                            $("#hidde_cheque_paiementintervention").show();
                        } else if (elmt.description === "Especes") {
                            $("#hidde_cheque_paiementintervention").hide();
                        }
                    }
                });
            }

            if (getId == "contrat_facturelocation" && getValue) {
                console.log(getValue);
                console.log("je suis dans le select");

                var typeAvecS = "contrats";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";

                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("herreeeeeeeeeeee: ", data);

                        console.log(data[0].periodicite_id, "est chargr");
                        $("#periodicite_facturelocation")
                            .val(data[0].periodicite_id)
                            .trigger("change");
                        $scope.reInit("contrat");
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            if (getId == "role_user" && getValue) {
                var textvalue = $(this).find("option:selected").text();

                if (textvalue == "prestataire") {
                    $(".prestataireuser").show();
                } else {
                    $(".prestataireuser").hide();
                    $(".prestataireuser").val("");
                }
            }

            if (
                getId == "locataireintervention_factureintervention" &&
                getValue
            ) {
                console.log(getValue);

                var typeAvecS = "demandeinterventions";

                rewriteReq = typeAvecS + "(locataire_id:" + getValue + ")";
                // console.log(rewriteReq) ;
                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        //  console.log("data", data);
                        $scope.demandesinterventiondatas = data;
                        //     $scope.reInit("typeappartement_piece");
                        //    console.log($scope.detailspiece) ;
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }
            // if (
            //     getId == "demandeinterventiondetail_factureintervention" &&
            //     getValue
            // ) {
            //     console.log(getValue);

            //     var typeAvecS = "interventions";
            //     rewriteReq =
            //         typeAvecS + "(demandeintervention_id:" + getValue + ")";

            //     idlocataire = $(
            //         "#locataireintervention_factureintervention"
            //     ).val();
            //     // console.log(idlocataire) ;
            //     var typeAvecS2 = "contrats";
            //     rewriteReq2 = typeAvecS2 + "(locataire_id:" + idlocataire + ")";
            //     // console.log(rewriteReq) ;
            //     Init.getElement(
            //         rewriteReq,
            //         listofrequests_assoc[typeAvecS]
            //     ).then(
            //         function (data) {
            //             //   console.log("data", data);
            //             $scope.interventiondatas = data;
            //             //     $scope.reInit("typeappartement_piece");
            //             //    console.log($scope.detailspiece) ;
            //         },
            //         function (msg) {
            //             $scope.showToast("", msg, "error");
            //         }
            //     );

            //     Init.getElement(
            //         rewriteReq2,
            //         listofrequests_assoc[typeAvecS2]
            //     ).then(
            //         function (data) {
            //             console.log("data", data);
            //             data.forEach((elmt) => {
            //                 //        console.log(elmt) ;
            //                 if (elmt.etat == "1") {
            //                     $scope.contratdata = elmt;
            //                     $("#contratcaution_id").val(
            //                         elmt.caution.montantcaution
            //                     );
            //                     $("#contratfacture_id").val(elmt.id);
            //                 }
            //             });
            //             $scope.demandesinterventiondatas = data;
            //             //     $scope.reInit("typeappartement_piece");
            //             //    console.log($scope.detailspiece) ;
            //         },
            //         function (msg) {
            //             $scope.showToast("", msg, "error");
            //         }
            //     );
            // }

            if (getId == "appartement_locationvente" && getValue) {
                console.log("getValue appartement_locationvente : " + getValue);

                var typeAvecS = "villas";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";

                idlocataire = $("#appartement_locationvente").val();

                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("data data data === ", data);
                        $scope.villadatas = data;
                        $("#periodicite_locationvente")
                            .val(data[0].periodicite_id)
                            .change();
                        $("#prixvilla_locationvente").val(data[0].prixvilla);
                        $("#acompteinitial_locationvente").val(
                            data[0].acomptevilla
                        );
                        $("#maturite_locationvente").val(data[0].maturite);
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }
            if (getId == "locataire_contrat" && getValue) {
                console.log("getValue locataire_contrat : " + getValue);

                var typeAvecS = "locataires";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";

                idlocataire = $("#locataire_contrat").val();

                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("data data data === ", data);
                        $scope.rappelLocataireData = data[0];
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            if (getId == "contrat_paiementloyer" && getValue) {
                console.log("getValue contrat_paiementloyer : " + getValue);
                // periodes_non_payes
                var typeAvecS = "contrats";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";

                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("data data data === ", data);
                        // $("#montantfacture_paiementloyer").prop(
                        //     "disabled",
                        //     true
                        // );
                        $("#montantfacture_paiementloyer").prop("readonly", true).css({ "background-color": "#d6d6d6" });
                        $("#montantfacture_paiementloyer").val(
                            data[0]["montantloyer"]
                        );
                        $("#appartement_paiementloyer")
                            .val(data[0]["appartement"]["id"])
                            .trigger("change");
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            if (getId == "periodicite_paiementloyer" && getValue) {
                console.log("getValue  : " + getValue);

                var typeAvecS = "periodicites";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";

                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("data data data === ", data);
                        var periode = data[0]["designation"].toLowerCase();

                        console.log("periode " + periode);
                        console.log(
                            "periode amount " +
                            $("#montantfacture_paiementloyer").val() * 3
                        );

                        if (periode == "trimestrielle") {
                            var newamount =
                                $("#montantfacture_paiementloyer").val() * 3;
                            $("#montantfacture_paiementloyer").val(newamount);
                        }
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            if (getId == "modepaiement_paiementloyer" && getValue) {
                console.log(
                    "getValue modepaiement_paiementloyer : " + getValue
                );

                var typeAvecS = "modepaiements";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";

                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("data data data === ", data);
                        var code = data[0]["code"];

                        console.log("code " + code);

                        if (code == "CH") {
                            // $('#numerocheque_paiementloyer').prop( "disabled", false );
                            // $(".numerochequepaiementloyer").show();
                        }
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            if (getId == "modepaiement_paiementecheance" && getValue) {
                console.log(
                    "getValue modepaiement_paiementecheance : " + getValue
                );

                var typeAvecS = "modepaiements";
                rewriteReq = typeAvecS + "(id:" + getValue + ")";

                Init.getElement(
                    rewriteReq,
                    listofrequests_assoc[typeAvecS]
                ).then(
                    function (data) {
                        console.log("data data data === ", data);
                        var code = data[0]["code"];

                        console.log("code " + code);

                        if (code == 'CC') {
                            $("#soldeclient_paiementecheance").removeClass("hidden");
                            var soldeclient = $scope.soldeclient;
                            $("#montantencaissement_paiementecheance").val(soldeclient).trigger("change");
                            $("#montantencaissement_paiementecheance").prop("readonly", true).css({ "background-color": "#d6d6d6" });

                            console.log('solde client: ', soldeclient);
                        } else {
                            $("#soldeclient_paiementecheance").addClass("hidden");
                            $("#montantencaissement_paiementecheance").prop("readonly", false).css({ "background-color": "" });

                        }
                    },
                    function (msg) {
                        $scope.showToast("", msg, "error");
                    }
                );
            }

            if (getId == "proprietaire_appartement" && getValue) {
                console.log(
                    "je suis l'evenement du select proprietaire_appartement"
                );
                var filters = "proprietaire_id:" + getValue;
                $scope.getelements("contratproprietaires", {}, filters);
            }

            if (getId == "contratproprietaire_id_appartement" && getValue) {
                reqwrite = "contratproprietaires" + "(id:" + getValue + ")";
                Init.getElement(
                    reqwrite,
                    listofrequests_assoc["contratproprietaires"]
                ).then(
                    function (data) {
                        console.log(data);
                        var commissionvaleur = data[0]["commissionvaleur"];
                        $("#commissionvaleur_appartement").val(
                            commissionvaleur
                        );

                        var commissionpourcentage =
                            data[0]["commissionpourcentage"];
                        $("#commissionpourcentage_appartement").val(
                            commissionpourcentage
                        );

                        var tva = data[0]["is_tva"];
                        $("#tva_appartement").prop("checked", tva);
                        var brs = data[0]["is_brs"];
                        $("#brs_appartement").prop("checked", brs);
                        var tlv = data[0]["is_tlv"];
                        $("#tlv_appartement").prop("checked", tlv);
                    },
                    function (msg) {
                        iziToast.error({
                            message:
                                "Erreur depuis le serveur, veuillez contactez l'administrateur",
                            displayMode: "once",
                            position: "topRight",
                        });
                        // console.log('Erreur serveur ici = ' + msg);
                    }
                );
            }
        }
        // To configure ajax options of select2
        function setAjaxToSelect2OptionsForSearch(getEntity) {
            if (getEntity) {
                return {
                    url: (query) => dataUrlEntity(query, getEntity),
                    data: null,
                    dataType: "json",
                    processResults: function (getData) {
                        return processResultsForSearchEntity(
                            getData,
                            getEntity
                        );
                    },
                    cache: true,
                };
            }
        }
        $scope.reInitTabPane = function (tagForm) {
            $scope.dataInTabPane[tagForm]["data"] = [];
        };
        $scope.reInitSelect2 = function () {
            setTimeout(function () {
                // select2
                $(".select2")
                    .each(function (key, value) {
                        if ($(this).data("select2")) {
                            $(this).select2("destroy");
                        }
                        var select2Options = {
                            //width: 'resolve',
                        };
                        if ($(this).attr("class").indexOf("modal") !== -1) {
                            select2Options.dropdownParent = $(this)
                                .parent()
                                .parent();
                            $(this).css("width", "100%");
                        }

                        // Pour le initSearchEntity
                        var tagSearch = "search_";
                        if ($(this).attr("class").indexOf(tagSearch) !== -1) {
                            allClassEntity = $(this)
                                .attr("class")
                                .split(" ")
                                .filter(function (cn) {
                                    return cn.indexOf(tagSearch) === 0;
                                });
                            if (allClassEntity.length > 0) {
                                getEntity = allClassEntity[0].substring(
                                    tagSearch.length,
                                    allClassEntity[0].length
                                );
                                // console.log('getEntity********************', allClassEntity, getEntity);
                                select2Options.minimumInputLength = 2;
                                select2Options.placeholder =
                                    getEntity.toUpperCase();
                                select2Options.ajax =
                                    setAjaxToSelect2OptionsForSearch(getEntity);
                            }
                        }
                        // console.log('select2', select2Options);
                        $(this).select2(select2Options);
                    })
                    .on("change", OnChangeSelect2);
            }, 1);
        };
        $scope.testDestroy = function () {
            $scope.reInit();
        };
        //important_select2
        $scope.reInit = function (typePass = null) {
            $scope.cpt = 1; //3
            setTimeout(function () {
                // select2
                console.log("reinit icici paiementloyer " + typePass);
                var url = window.location.href;
                var aRechercher = "list-";
                if (url.indexOf(aRechercher) < 0) {
                    aRechercher = "detail-";
                }
                var positionSuffixe = null;
                var type = typePass;
                if (!type) {
                    positionSuffixe =
                        url.indexOf(aRechercher) + aRechercher.length;
                    type = url.substring(positionSuffixe, url.length);
                }

                var types = [{ type: type }];

                if (type == "immeuble") {
                    types.push({ type: "appartement" });
                }
                if (type == "avenant") {
                    types.push({ type: "avenant" });
                }
                if (type == "villa") {
                    types.push({ type: "appartement" });
                }
                if (type == "facturelocation") {
                    types.push({ type: "paiementloyer" });
                }

                if (type == "detailscontrat") {
                    console.log("reinit icici paiementloyer");
                    types.push({ type: "factureeaux" });
                    types.push({ type: "inbox" });
                    types.push({ type: "facturelocation" });
                    types.push({ type: "paiementloyer" });
                    types.push({ type: "demanderesiliation" });
                    types.push({ type: "avenant" });
                }

                if (type == "detailslocationvente") {
                    console.log("reinit icici detailslocationvente");
                    types.push({ type: "inbox" });
                    types.push({ type: "facturelocation" });
                    types.push({ type: "avisecheance" });
                    types.push({ type: "paiementloyer" });
                    types.push({ type: "demanderesiliation" });
                    types.push({ type: "paiementecheance" });
                }

                if (type == "contrat") {
                    types.push({ type: "assurance" });
                }

                if (type == "demandeintervention") {
                    types.push({ type: "devi" });
                    types.push({ type: "demandeintervention" });
                    types.push({ type: "detaildevis" });
                }
                if (type == "etatlieu") {
                    types.push({ type: "devi" });
                    types.push({ type: "demandeintervention" });
                    types.push({ type: "detaildevis" });
                    types.push({ type: "factureintervention" });
                    types.push({ type: "intervention" });
                    types.push({ type: "inbox" });
                }

                if (type == "demandeintervention") {
                    types.push({ type: "intervention" });
                    types.push({ type: "factureintervention" });
                }
                if (type == "factureintervention") {
                    types.push({ type: "paiementintervention" });
                }

                if (type == "typeappartement_piece") {
                    var idDet = "piece_" + $scope.detailspiece[0].id;
                    console.log(idDet);
                    setTimeout(function () {
                        var d = document.getElementById(idDet);
                        // d.className += "active";
                        $("#" + idDet).addClass("active");
                        console.log(d);
                        //    $('#' + idDet).load(location.href + '#' + idDet);
                    }, 1);
                }

                //$('.select2-'+type).each(function (key, value) {
                $(".select2")
                    .each(function (key, value) {
                        for (var i = 0; i < types.length; i++) {
                            if (value.id.indexOf("_" + types[i].type) !== -1) {
                                if ($(this).data("select2")) {
                                    $(this).select2("destroy");
                                }
                                var select2Options = {
                                    //width: 'resolve',
                                };
                                if (
                                    $(this).attr("class").indexOf("modal") !==
                                    -1
                                ) {
                                    //console.log('select2 modal *********************');
                                    select2Options.dropdownParent = $(this)
                                        .parent()
                                        .parent();
                                    $(this).css("width", "100%");
                                }

                                var tagSearch = "search_";

                                if (
                                    $(this).attr("class").indexOf(tagSearch) !==
                                    -1
                                ) {
                                    allClassEntity = $(this)
                                        .attr("class")
                                        .split(" ")
                                        .filter(function (cn) {
                                            return cn.indexOf(tagSearch) === 0;
                                        });
                                    if (allClassEntity.length > 0) {
                                        getEntity = allClassEntity[0].substring(
                                            tagSearch.length,
                                            allClassEntity[0].length
                                        );
                                        //console.log('getEntity********************', allClassEntity, getEntity);
                                        select2Options.minimumInputLength = 2;
                                        select2Options.placeholder =
                                            getEntity.toUpperCase();
                                        select2Options.ajax =
                                            setAjaxToSelect2OptionsForSearch(
                                                getEntity
                                            );
                                    }
                                }
                                $(this).select2(select2Options);
                            }
                        }
                    })
                    .on("change", OnChangeSelect2);

                // mobile-app-menu-btn
                $(".mobile-app-menu-btn").click(function () {
                    $(".hamburger", this).toggleClass("is-active");
                    $(".app-inner-layout").toggleClass("open-mobile-menu");
                });

                // bootstrapToggle
                if ($(this).is("[data-toggle]")) {
                    $(this).bootstrapToggle("destroy").bootstrapToggle();
                    // Format options
                    $('[data-toggle="popover"]').popover();
                }
            }, 100);
        };
        function OnChange(e) {
            console.log("Je change value", $(this).attr("id"));
        }
        //------------- /UTILITAIRES--------------------//

        //---DEBUT ==> Les tableaux de données---//
        $scope.genres = [
            { id: "Homme", libelle: "Homme" },
            { id: "Femme", libelle: "Femme" },
        ];
        $scope.civilites = [
            { id: "Mr", libelle: "Mr" },
            { id: "Mme", libelle: "Mme" },
            {
                id: "Mlle",
                libelle: "Mlle",
            },
            { id: "Société", libelle: "Société" },
        ];
        $scope.users = [];
        $scope.roles = [];
        $scope.permissions = [];
        $scope.dashboards = [];
        $scope.dashboards = [];

        //Contient la date d'aujourd'hui
        $scope.dateToday = new Date().toJSON().slice(0, 10).replace(/-/g, "-");

        //---FIN ==> Les tableaux de données---//

        // ---- personnel
        $scope.senddirectormail = (
            contrat,
            locataire,
            message = "Voulez-vous soumettre le contrat à la directrice ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                contrat: contrat,
                locataire: locataire,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL + "contrat/send-mail-director",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête Laravel
                                        var data = response.data.data;
                                        var errors = response.data.errors;

                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            // Gestion du succès
                                            console.log("Succès :", data);

                                            $scope.showToast(
                                                "Envoie mail réussi ",
                                                "succès",
                                                "success"
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };
        $scope.senddirectormailridwan = (
            contrat,
            locataire,
            message = "Voulez-vous valider le contrat de location vente ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                contrat: contrat,
                locataire: locataire,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL +
                                    "contrat/send-mail-director-ridwan",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête Laravel
                                        var data = response.data.data;
                                        var errors = response.data.errors;

                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            // Gestion du succès
                                            console.log("Succès :", data);

                                            $scope.showToast(
                                                "Validation de contrat réussi ",
                                                "succès",
                                                "success"
                                            );
                                            var contratIdId =
                                                $routeParams.itemId;
                                            $scope.getelements(
                                                "locationventes",
                                                {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                },
                                                "id:" + contratIdId
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };

        $scope.annulerSoumissionContratRidwan = (
            contrat,
            message = "Voulez-vous annuler la soumission du contrat ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                contrat: contrat,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL +
                                    // Succès de la requête Laravel
                                    "contrat/annuler-soumission-contrat-ridwan",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête Laravel
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                        var data = response.data.data;
                                        var errors = response.data.errors;

                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            // Gestion du succès
                                            console.log("Succès :", data);

                                            $scope.showToast(
                                                "Soumisson anulé avec succès ",
                                                "succès",
                                                "success"
                                            );
                                            var contratId = $routeParams.itemId;
                                            $scope.getelements(
                                                "locationventes",
                                                {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                },
                                                "id:" + contratId
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };
        $scope.sendrelancepaiement = (
            contrat,
            locataire,
            message = "Voulez-vous soumettre le contrat à la directrice ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                contrat: contrat,
                locataire: locataire,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL + "contrat/send-mail-director",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête Laravel
                                        var data = response.data.data;
                                        var errors = response.data.errors;

                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            // Gestion du succès
                                            console.log("Succès :", data);

                                            $scope.showToast(
                                                "Envoie mail réussi ",
                                                "succès",
                                                "success"
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };

        $scope.sendEcheanceEncours = (
            message = "Voulez-vous soumettre les échéances en cours ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                contrat: 0,
                locataire: 0,
                // Ajoutez d'autres données nécessaires pour envoyer les échéances en cours
            };
            $scope.isLoading = false; // Activer le loader

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL + "inbox/send-echeance-encours",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        $scope.isLoading = true;
                                        // Succès de la requête Laravel
                                        var data = response.data.data;
                                        var errors = response.data.errors;

                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (data == 1) {
                                            // Gestion du succès
                                            console.log("Succès :", data);
                                            $scope.showToast(
                                                "Envoie mail réussi ",
                                                "succès",
                                                "success"
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                )
                                .finally(function () {
                                    $scope.isLoading = false; // Désactiver le loader une fois la requête terminée
                                });
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };

        // --- perosnnel

        $scope.annulerPaiementEcheance = (
            echeance,
            etat,
            message = "Voulez-vous annuler le paiement ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                echeance: echeance,
                etat: etat,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL + "annulationpaiementavis",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête
                                        var data = response.data.data;
                                        var errors = response.data.errors;

                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            instance.hide(
                                                {
                                                    transitionOut: "fadeOut",
                                                },
                                                toast,
                                                "button"
                                            );
                                            // Gestion du succès
                                            console.log("Succès :", data);
                                            if (etat == 1) {
                                                $scope.showToast(
                                                    "Annulation de paiement réussi ",
                                                    "succès",
                                                    "success"
                                                );
                                            } else {
                                                $scope.showToast(
                                                    "Paiement réactivé avec succés ",
                                                    "succès",
                                                    "success"
                                                );
                                            }

                                            var contratIdId =
                                                $routeParams.itemId;
                                            $scope.pageChanged(
                                                "avisecheance",
                                                (optionals = {
                                                    justWriteUrl: null,
                                                    option: null,
                                                    saveStateOfFilters: false,
                                                }),
                                                contratIdId
                                            );
                                            $scope.pageChanged(
                                                "locationvente",
                                                (optionals = {
                                                    justWriteUrl: null,
                                                    option: null,
                                                    saveStateOfFilters: false,
                                                }),
                                                contratIdId
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };

        $scope.reinitcompteclient = (
            locataire_id,
            message = "Voulez-vous reinitialiser le compte client ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                locataire_id: locataire_id,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL + "reinitcompteclient",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête
                                        var data = response.data.data;
                                        var errors = response.data.errors;
                                        console.log("data :", data);


                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            instance.hide(
                                                {
                                                    transitionOut: "fadeOut",
                                                },
                                                toast,
                                                "button"
                                            );
                                            // Gestion du succès
                                            console.log("Succès :", data);
                                            $scope.showToast(
                                                "Reinitialisation réussi ",
                                                "succès",
                                                "success"
                                            );

                                            var contratIdId =
                                                $routeParams.itemId;
                                            $scope.pageChanged(
                                                "locationvente",
                                                (optionals = {
                                                    justWriteUrl: null,
                                                    option: null,
                                                    saveStateOfFilters: false,
                                                }),
                                                contratIdId
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };


        $scope.desactivecompteclient = (
            locataire_id,
            message = "Voulez-vous desactiver le compte client ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                locataire_id: locataire_id,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL + "desactivecompteclient",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête
                                        var data = response.data.data;
                                        var errors = response.data.errors;
                                        console.log("data :", data);


                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            instance.hide(
                                                {
                                                    transitionOut: "fadeOut",
                                                },
                                                toast,
                                                "button"
                                            );
                                            // Gestion du succès
                                            console.log("Succès :", data);
                                            $scope.showToast(
                                                "Desactivation réussi ",
                                                "succès",
                                                "success"
                                            );

                                            var contratIdId =
                                                $routeParams.itemId;
                                            $scope.pageChanged(
                                                "locationvente",
                                                (optionals = {
                                                    justWriteUrl: null,
                                                    option: null,
                                                    saveStateOfFilters: false,
                                                }),
                                                contratIdId
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };


        $scope.activecompteclient = (
            locataire_id,
            message = "Voulez-vous activer le compte client ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                locataire_id: locataire_id,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL + "activecompteclient",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête
                                        var data = response.data.data;
                                        var errors = response.data.errors;
                                        console.log("data :", data);


                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            instance.hide(
                                                {
                                                    transitionOut: "fadeOut",
                                                },
                                                toast,
                                                "button"
                                            );
                                            // Gestion du succès
                                            console.log("Succès :", data);
                                            $scope.showToast(
                                                "Activation réussi ",
                                                "succès",
                                                "success"
                                            );

                                            var contratIdId =
                                                $routeParams.itemId;
                                            $scope.pageChanged(
                                                "locationvente",
                                                (optionals = {
                                                    justWriteUrl: null,
                                                    option: null,
                                                    saveStateOfFilters: false,
                                                }),
                                                contratIdId
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };


        $scope.traiterPaiementLoyer = (
            loyer,
            etat,
            message = "Voulez-vous annuler le paiement ?",
            title = "Soumettre"
        ) => {
            var dataToSend = {
                loyer: loyer,
                etat: etat,
            };

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: message,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            $http
                                .post(
                                    BASE_URL + "annulationpaiementloyer",
                                    dataToSend
                                )
                                .then(
                                    function (response) {
                                        // Succès de la requête
                                        var data = response.data.data;
                                        var errors = response.data.errors;

                                        // Traitez la réponse de votre API ici (data et errors)
                                        if (!errors) {
                                            instance.hide(
                                                {
                                                    transitionOut: "fadeOut",
                                                },
                                                toast,
                                                "button"
                                            );
                                            // Gestion du succès
                                            console.log("Succès :", data);
                                            if (etat == 1) {
                                                $scope.showToast(
                                                    "Annulation de paiement réussi ",
                                                    "succès",
                                                    "success"
                                                );
                                            } else {
                                                $scope.showToast(
                                                    "Paiement réactivé avec succés ",
                                                    "succès",
                                                    "success"
                                                );
                                            }

                                            var contratIdId =
                                                $routeParams.itemId;
                                            $scope.getelements(
                                                "contrats",
                                                {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                },
                                                "id:" + contratIdId
                                            );
                                            $scope.getelements(
                                                "facturelocations",
                                                {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                },
                                                "contrat_id:" + contratIdId
                                            );
                                        } else {
                                            // Gestion des erreurs
                                            console.log("Erreurs :", errors);
                                            $scope.showToast(
                                                "Erreur",
                                                errors,
                                                "error"
                                            );
                                        }
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOut",
                                            },
                                            toast,
                                            "button"
                                        );
                                    },
                                    function (error) {
                                        // Erreur de la requête HTTP
                                        console.log(
                                            "Erreur de la requête :",
                                            error
                                        );
                                        $scope.showToast(
                                            "Erreur",
                                            error,
                                            "error"
                                        );
                                    }
                                );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        false,
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };
        $scope.getelements = function (
            type,
            optionals = {
                queries: null,
                typeIds: null,
                otherFilters: null,
            },
            filtres = null
        ) {
            var listeattributs_filter = [];
            rewriteType = type;
            var rewriteattr = listofrequests_assoc[type];

            if (optionals && optionals.queries) {
                console.log("optionals.queries", optionals.queries);
                rewriteType = rewriteType + "(";

                $.each(optionals.queries, function (KeyItem, queryItem) {
                    rewriteType = rewriteType + queryItem;
                });

                rewriteType = rewriteType + ")";
            }

            if (filtres) {
                rewriteType = rewriteType + "(" + filtres + ")";
            }

            Init.getElement(
                rewriteType,
                rewriteattr,
                listeattributs_filter
            ).then(
                function (data) {
                    $scope.dataPage[type] = data;
                    if (type == "permissions") {
                        $scope.temponPermissions = data;
                    }
                    //console.log('------------Data query' + type + '----------');
                    //console.log(data);
                    //console.log('getelements ****************** optionals => ', optionals, 'rewriteType = ', rewriteType, ' listofrequests_assoc = ', listofrequests_assoc[type], "getElements ****************** data=>");
                },
                function (msg) {
                    $scope.showToast("ERREUR", msg, "error");
                }
            );
        };

        $scope.getelements2point0 = function (
            type,
            optionals = {
                queries: null,
                typeIds: null,
                otherFilters: null,
                attributs: null,
            },
            filtres = null
        ) {
            var listeattributs_filter = [];
            rewriteType = type;
            var rewriteattr = !optionals.attributs
                ? listofrequests_assoc[type]
                : optionals.attributs;

            if (optionals && optionals.queries) {
                console.log("optionals.queries", optionals.queries);
                rewriteType = rewriteType + "(";

                $.each(optionals.queries, function (KeyItem, queryItem) {
                    rewriteType = rewriteType + queryItem;
                });

                rewriteType = rewriteType + ")";
            }

            if (filtres) {
                rewriteType = rewriteType + "(" + filtres + ")";
            }

            Init.getElement(
                rewriteType,
                rewriteattr,
                listeattributs_filter
            ).then(
                function (data) {
                    $scope.dataPage[type] = data;
                    if (type == "permissions") {
                        $scope.temponPermissions = data;
                    }
                },
                function (msg) {
                    $scope.showToast("ERREUR", msg, "error");
                }
            );
        };

        $scope.infosUserConnected = null;
        $scope.isUserLoaded = false;
        //Dans cette fonction on gere le paramettrage du theme
        $scope.getUserConnected = function () {
            reqwrite = "users" + "(,id:" + $("#userLogged_id").val() + ")";
            Init.getElement(reqwrite, listofrequests_assoc["users"]).then(
                function (data) {
                    $scope.infosUserConnected = data[0];
                    $scope.currentTheme = "theme-Groupe";
                    $scope.isUserLoaded = true;
                    $scope.$emit("userLoaded"); // Émet un événement
                },
                function (msg) {
                    iziToast.error({
                        message:
                            "Erreur depuis le serveur, veuillez contactez l'administrateur",
                        displayMode: "once",
                        position: "topRight",
                    });
                    // console.log('Erreur serveur ici = ' + msg);
                }
            );
        };
        $scope.getUserConnected();

        $scope.filterInDetailById = function (currentpage, foreign) {
            var rewriteelement = "," + foreign + ":" + $scope.param;

            return rewriteelement;
        };
        //---FIN ==> Pour récupérer les données---//
        //Utilisation du factory getelementPaginated
        //use_getelementpaginated
        $scope.getElementPaginatedUse = function (type, rewriteReq) {
            Init.getElementPaginated(
                rewriteReq,
                listofrequests_assoc[type + "s"]
            ).then(
                function (data) {
                    if (data) {
                        $scope.paginations[type].currentPage =
                            data.metadata.current_page;
                        $scope.paginations[type].totalItems =
                            data.metadata.total;
                        $scope.dataPage[type + "s"] = data.data;
                    }
                },
                function (msg) {
                    // form.parent().parent().blockUI_stop();
                    toastr.error(msg);
                }
            );
        };

        $scope.getElementUse = function (type, rewriteReq) {
            Init.getElement(rewriteReq, listofrequests_assoc[type + "s"]).then(
                function (data) {
                    if (data) {
                        // $scope.paginations[type].currentPage = data.metadata.current_page;
                        // $scope.paginations[type].totalItems = data.metadata.total;
                        $scope.dataPage[type + "s"] = data;
                    }
                },
                function (msg) {
                    // form.parent().parent().blockUI_stop();
                    toastr.error(msg);
                }
            );
        };

        $scope.writeUrl = null;
        $scope.searchtexte_client = "";
        $scope.pageChanged = function (
            currentpage,
            optionals = {
                justWriteUrl: null,
                option: null,
                saveStateOfFilters: false,
            },
            elementId = null
        ) {

            console.log($scope.infosUserConnected, "testtttttttttttttttt");

            $scope.filters = "";
            $scope.permissionResources = $scope.currentTemplateUrl;
            // console.log("pageChanged ==> currentpage", currentpage);
            var typeFilter = currentpage;
            var currentpageReal = currentpage;

            console.log("currentpage est dan d** pgae", currentpage);

            addrewriteattr = null;
            $scope.testcontrat = 0;
            var rewriteelement = "";
            var rewriteattr = listofrequests_assoc[currentpage + "s"]
                ? listofrequests_assoc[currentpage + "s"][0]
                : null;

            if (
                $scope.currentTemplateUrl
                    .toLowerCase()
                    .indexOf("list-locationvente") !== -1
            ) {
                rewriteattr =
                    "id,descriptif,email,locataire{nom,prenom,email},appartement{ilot{numero,adresse},lot},prixvillaformat,apportinitial_format,etat_badge,etat_text,periodicite{id,designation}";
            }

            if (rewriteattr) {
                var filters = $scope.generateAddFiltres(typeFilter);
                // console.log("test libasse filter 1" + filters);


                if (!$scope.paginations[currentpage]) {
                    $scope.paginations[currentpage] = {
                        currentPage: 1,
                        maxSize: 10,
                        entryLimit: 10,
                        totalItems: 0,
                    };
                }
                var page = $scope.paginations[currentpage].currentPage;
                var count = $scope.paginations[currentpage].entryLimit;
                console.log("count ****************** page = ", count);
                if ($scope.orderby) {
                    filters += $scope.orderby;
                }

                console.log($scope.infosUserConnected, "user exit");
                if ($scope.infosUserConnected != null) {
                    console.log("user exist");
                    console.log("test magatte  " + $scope.infosUserConnected.roles[0].name);
                    if (
                        typeFilter == "demandeintervention" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].name == 'resident'
                    ) {
                        $userid =
                            "locataire_id:" +
                            $scope.infosUserConnected.locataire_id;

                        filters += $userid;
                        // console.log(filters) ;
                    } if (
                        typeFilter == "intervention" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].name == 'resident'
                    ) {
                        $userid =
                            "getLocataire:" +
                            $scope.infosUserConnected.locataire_id;

                        filters += $userid;
                        // console.log(filters) ;
                    }
                    if (
                        typeFilter == "factureintervention" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].name == 'resident'
                    ) {
                        $userid =
                            "locataire_id:" +
                            $scope.infosUserConnected.locataire_id;

                        filters += $userid;
                        // console.log(filters) ;
                    } else if (
                        typeFilter == "demanderesiliation" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].name == 'resident'
                    ) {
                        $userid =
                            "locataire_id:" +
                            $scope.infosUserConnected.locataire_id;

                        filters += $userid;
                        //  console.log(filters) ;
                    } else if (
                        typeFilter == "annonce" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].name == 'resident'

                    ) {
                        $userid =
                            "locataire_id:" +
                            $scope.infosUserConnected.locataire_id;

                        filters += $userid;
                        //  console.log(filters) ;
                    }
                    else if (
                        typeFilter == "message" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].name == 'resident'

                    ) {
                        $userid =
                            "locataire_id:" +
                            $scope.infosUserConnected.locataire_id;

                        filters += $userid;
                        //  console.log(filters) ;
                    } else if (
                        typeFilter == "inbox" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].name == 'resident'

                    ) {
                        $userid =
                            "locataire_id:" +
                            $scope.infosUserConnected.locataire_id;

                        filters += $userid;
                        //  console.log(filters) ;
                    } else if (
                        typeFilter == "paiementloyer" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].id == 2
                    ) {
                        $userid =
                            "locataire_id:" +
                            $scope.infosUserConnected.locataire_id;

                        filters += $userid;
                        //  console.log(filters) ;
                    } else if (typeFilter == "contrat") {
                        if (
                            $scope.infosUserConnected &&
                            $scope.infosUserConnected.roles.length > 0 &&
                            $scope.infosUserConnected.roles[0].name == "resident"
                        ) {
                            $userid =
                                "locataire_id:" +
                                $scope.infosUserConnected.locataire_id;
                            filters += $userid;
                        }
                    } else if (
                        typeFilter == "appartement" &&
                        $scope.infosUserConnected &&
                        $scope.infosUserConnected.roles.length > 0 &&
                        $scope.infosUserConnected.roles[0].id == 2
                    ) {
                        if (
                            $scope.infosUserConnected &&
                            $scope.infosUserConnected.locataire_id
                        ) {
                            $userid =
                                "locataire_id:" +
                                $scope.infosUserConnected.locataire_id;

                            filters += $userid;
                        }
                    }
                    if (
                        typeFilter == "avisecheance" &&
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("list-detailslocationvente") !== -1
                    ) {
                        // $contratIdId = $routeParams.itemId;
                        $contrat = "contrat_id:" + elementId;
                        filters += $contrat;
                    } else if (
                        typeFilter == "appartement" &&
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("list-appartement") !== -1
                    ) {
                        if (elementId && elementId !== null) {
                            filters += elementId;
                            $scope.filters = elementId;
                            console.log(
                                "mansour pouye   test avec filtre 4",
                                $scope.filters
                            );
                        }
                    }
                    console.log("test libasse filter " + filters);

                    rewriteelement =
                        filters && filters !== "null"
                            ? currentpage +
                            "spaginated(page:" +
                            page +
                            ",count:" +
                            count +
                            "," +
                            filters +
                            ")"
                            : currentpage +
                            "spaginated(page:" +
                            page +
                            ",count:" +
                            count +
                            ")";

                    console.log("test libasse filter " + rewriteelement);
                    console.log(rewriteelement, rewriteattr);

                    if (rewriteelement && rewriteattr) {
                        console.log(rewriteelement, rewriteattr);
                        Init.getElementPaginated(
                            rewriteelement,
                            rewriteattr,
                            addrewriteattr
                        ).then(
                            function (data) {
                                console.log(data);
                                //console.log('Current page=======>', data.metadata.current_page);
                                $scope.dataPage[currentpage + "s"] = data.data;
                                $scope.paginations[currentpage].currentPage =
                                    data.metadata.current_page;
                                $scope.paginations[currentpage].totalItems =
                                    data.metadata.total;
                                // console.log('pageChanged ****************** rewriteelement =', rewriteelement, " rewriteattr =>", rewriteattr, " addrewriteattr =>", addrewriteattr, 'pageChanged ****************** data =>', currentpage);
                            },
                            function (msg) {
                                $(".item-back").blockUI_stop();
                                $scope.showToast("ERREUR", msg, "error");
                                // blockUI_stop_all('#section_listeavoirdepots');
                            }
                        );
                    }
                } else {
                    console.log("user faittt traite not exist");
                    setTimeout(function () {
                        console.log("into timeout");

                        if (
                            typeFilter == "demandeintervention" &&
                            $scope.infosUserConnected &&
                            $scope.infosUserConnected.roles.length > 0 &&
                            $scope.infosUserConnected.roles[0].id == 2
                        ) {
                            $userid =
                                "locataire_id:" +
                                $scope.infosUserConnected.locataire_id;

                            filters += $userid;
                            // console.log(filters) ;
                        } else if (
                            typeFilter == "paiementloyer" &&
                            $scope.infosUserConnected &&
                            $scope.infosUserConnected.roles.length > 0 &&
                            $scope.infosUserConnected.roles[0].id == 2
                        ) {
                            $userid =
                                "locataire_id:" +
                                $scope.infosUserConnected.locataire_id;

                            filters += $userid;
                            //  console.log(filters) ;
                        } else if (
                            typeFilter == "contrat" &&
                            $scope.infosUserConnected &&
                            $scope.infosUserConnected.roles.length > 0 &&
                            $scope.infosUserConnected.roles[0].name == "resident"
                        ) {
                            $userid =
                                "locataire_id:" +
                                $scope.infosUserConnected.locataire_id;

                            filters += $userid;

                        } else if (
                            typeFilter == "appartement" &&
                            $scope.infosUserConnected &&
                            $scope.infosUserConnected.roles.length > 0 &&
                            $scope.infosUserConnected.roles[0].id == 2
                        ) {
                            $userid =
                                "locataire_id:" +
                                $scope.infosUserConnected.locataire_id;
                            console.log("for locataire_id", $userid);
                            filters += $userid;
                        }

                        if (
                            typeFilter == "avisecheance" &&
                            $scope.currentTemplateUrl
                                .toLowerCase()
                                .indexOf("list-detailslocationvente") !== -1
                        ) {
                            $contrat = "contrat_id:" + elementId;
                            filters += $contrat;
                        } else if (
                            typeFilter == "appartement" &&
                            $scope.currentTemplateUrl
                                .toLowerCase()
                                .indexOf("list-appartement") !== -1
                        ) {
                            console.log("for appartement null", elementId);
                            if (elementId && elementId !== null) {
                                filters += elementId;
                                $scope.filters = elementId;
                                // console.log(
                                //     "mansour pouye   test avec filtre 3",
                                //     $scope.filters,
                                // );
                            }
                        }
                        console.log(
                            "mansour pouye   test avec filtre 3",
                            $scope.filters,
                            filters
                        );

                        rewriteelement =
                            filters && filters !== "null"
                                ? currentpage +
                                "spaginated(page:" +
                                page +
                                ",count:" +
                                count +
                                "," +
                                filters +
                                ")"
                                : currentpage +
                                "spaginated(page:" +
                                page +
                                ",count:" +
                                count +
                                ")";

                        console.log(
                            rewriteelement,
                            rewriteattr,
                            filters,
                            "LEO MESSIE"
                        );

                        if (rewriteelement && rewriteattr) {
                            Init.getElementPaginated(
                                rewriteelement,
                                rewriteattr,
                                addrewriteattr
                            ).then(
                                function (data) {
                                    console.log(data);
                                    console.log(
                                        "mansour pouye   test avec filtre 5",
                                        $scope.filters,
                                        filters,
                                        elementId
                                    );

                                    //console.log('Current page=======>', data.metadata.current_page);
                                    $scope.dataPage[currentpage + "s"] =
                                        data.data;
                                    $scope.paginations[
                                        currentpage
                                    ].currentPage = data.metadata.current_page;
                                    $scope.paginations[currentpage].totalItems =
                                        data.metadata.total;
                                    // console.log('pageChanged ****************** rewriteelement =', rewriteelement, " rewriteattr =>", rewriteattr, " addrewriteattr =>", addrewriteattr, 'pageChanged ****************** data =>', currentpage);
                                },
                                function (msg) {
                                    $(".item-back").blockUI_stop();
                                    $scope.showToast("ERREUR", msg, "error");
                                    // blockUI_stop_all('#section_listeavoirdepots');
                                }
                            );
                        }
                    }, 100);
                }
            } else {
                if (currentpage == "dashboard") {
                    filters = $scope.generateAddFiltres(typeFilter);
                }
            }
            $(".numbers").keyup(function () {
                this.value = this.value.replace(/[^0-9\.]/g, "");
                this.value = this.value.replace(/\./g, " ");
            });
        };

        $scope.cacheFilters = {};
        $canWrite = true;
        $scope.$watch("writeUrl", function (newValue, oldValue, scope) {
            if (!newValue) {
                //console.log("writeUrl la nouvelle valeur est vide", $scope.linknav);
            } else {
                //console.log('writeUrl old = ', oldValue, 'new = ', newValue);
            }
            $assocName = $scope.linknav.substr(1, $scope.linknav.length);

            if (
                $canWrite &&
                $scope.linknavOld.indexOf("detail") !== -1 &&
                $assocName in $scope.cacheFilters
            ) {
                $canWrite = false;
                $scope.linknavOld = $scope.linknav;
            } else $canWrite = true;

            if (
                $assocName &&
                $canWrite &&
                $scope.linknav.indexOf("detail") === -1
            ) {
                $scope.cacheFilters[$assocName] = newValue;
            }
            // console.log("writeUrl $assocName", $assocName, "cacheFilters", $scope.cacheFilters, "$canWrite", $canWrite);
        });

        //$scope.getelements("notifpermusers");
        // Pour detecter le changement des routes avec Angular
        $scope.linknav = "/";
        $scope.linknavOld = "/";
        $scope.currentTemplateUrl = "";
        $scope.client = null;
        $scope.dropIt = function () {
            console.log("dropp it");
        };

        // $scope.traceGraph = function () {
        //     // Données fictives pour les contrats réservataires
        //     var contratsReservataires = [
        //         { contrat: "Contrat 1", loyerEncaisse: 5000, partAmortissement: 2000, fraisLocatifs: 1000, fraisGestion: 500 },
        //         { contrat: "Contrat 2", loyerEncaisse: 6000, partAmortissement: 2500, fraisLocatifs: 1200, fraisGestion: 600 },
        //         // Ajoutez les données pour les autres contrats réservataires ici...
        //     ];

        //     // Préparation des données pour Chart.js
        //     var labels = [];
        //     var loyerEncaisse = [];
        //     var partAmortissement = [];
        //     var fraisLocatifs = [];
        //     var fraisGestion = [];

        //     contratsReservataires.forEach(function (contrat) {
        //         labels.push(contrat.contrat);
        //         loyerEncaisse.push(contrat.loyerEncaisse);
        //         partAmortissement.push(contrat.partAmortissement);
        //         fraisLocatifs.push(contrat.fraisLocatifs);
        //         fraisGestion.push(contrat.fraisGestion);
        //     });

        //     // Configuration du graphique
        //     var ctx = document.getElementById("graphique").getContext('2d');
        //     var myChart = new Chart(ctx, {
        //         type: 'bar',
        //         data: {
        //             labels: labels,
        //             datasets: [{
        //                 label: 'Loyer encaissé',
        //                 data: loyerEncaisse,
        //                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
        //                 borderColor: 'rgba(75, 192, 192, 1)',
        //                 borderWidth: 1
        //             }, {
        //                 label: 'Part amortissement',
        //                 data: partAmortissement,
        //                 backgroundColor: 'rgba(255, 99, 132, 0.2)',
        //                 borderColor: 'rgba(255, 99, 132, 1)',
        //                 borderWidth: 1
        //             }, {
        //                 label: 'Frais locatifs',
        //                 data: fraisLocatifs,
        //                 backgroundColor: 'rgba(54, 162, 235, 0.2)',
        //                 borderColor: 'rgba(54, 162, 235, 1)',
        //                 borderWidth: 1
        //             }, {
        //                 label: 'Frais de gestion',
        //                 data: fraisGestion,
        //                 backgroundColor: 'rgba(255, 206, 86, 0.2)',
        //                 borderColor: 'rgba(255, 206, 86, 1)',
        //                 borderWidth: 1
        //             }]
        //         },
        //         options: {
        //             scales: {
        //                 yAxes: [{
        //                     ticks: {
        //                         beginAtZero: true
        //                     }
        //                 }]
        //             }
        //         }
        //     });
        // };
        $scope.traceGraph = function () {
            $scope.options = {
                type: "bar",
                data: {
                    labels: [
                        "Janvier",
                        "Fevrier",
                        "Mars",
                        "Avril",
                        "Mai",
                        "Juin",
                        "Juillet",
                        "Aout",
                        "Septembre",
                        "Octobre",
                        "Novembre",
                        "Decembre",
                    ],
                    datasets: [
                        {
                            label: "Contrat 1 - Part amortissement",
                            data: [100, 200],
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Contrat 1 - Frais locatifs",
                            data: [90, 80],
                            backgroundColor: "rgba(54, 162, 235, 0.2)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Contrat 1 - Frais de gestion",
                            data: [399, 800],
                            backgroundColor: "rgba(255, 206, 86, 0.2)",
                            borderColor: "rgba(255, 206, 86, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Contrat 2 - Part amortissement",
                            data: [150, 250],
                            backgroundColor: "rgba(75, 192, 192, 0.2)",
                            borderColor: "rgba(75, 192, 192, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Contrat 2 - Frais locatifs",
                            data: [110, 120],
                            backgroundColor: "rgba(153, 102, 255, 0.2)",
                            borderColor: "rgba(153, 102, 255, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Contrat 2 - Frais de gestion",
                            data: [600, 700],
                            backgroundColor: "rgba(255, 159, 64, 0.2)",
                            borderColor: "rgba(255, 159, 64, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                },
            };

            var ctx = document.getElementById("myChart").getContext("2d");
            var myChart = new Chart(ctx, $scope.options);
        };

        $scope.traceGraph3 = function () {
            $scope.paymentDelayData = {
                labels: [
                    "Janvier",
                    "Février",
                    "Mars",
                    "Avril",
                    "Mai",
                    "Juin",
                    "Juillet",
                    "Août",
                    "Septembre",
                    "Octobre",
                    "Novembre",
                    "Décembre",
                ],
                datasets: [
                    {
                        label: "Contrat 1",
                        backgroundColor: "rgba(255, 99, 132, 0.5)",
                        data: [5, 10, 8, 12, 7, 15, 9, 11, 6, 13, 8, 10],
                    },
                    {
                        label: "Contrat 2",
                        backgroundColor: "rgba(54, 162, 235, 0.5)",
                        data: [8, 6, 11, 9, 13, 7, 10, 12, 8, 14, 9, 11],
                    },
                    // Ajouter des ensembles de données supplémentaires pour chaque contrat
                ],
            };

            var paymentDelayChart = {
                type: "bar",
                data: $scope.paymentDelayData,
                options: {
                    scales: {
                        xAxes: [
                            {
                                stacked: true,
                            },
                        ],
                        yAxes: [
                            {
                                stacked: true,
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                },
            };

            var ctx = document.getElementById("myChart3").getContext("2d");
            var myChart = new Chart(ctx, paymentDelayChart);
        };
        $scope.traceGraph4 = function () {
            $scope.paymentDelayData = {
                labels: [
                    "Janvier",
                    "Février",
                    "Mars",
                    "Avril",
                    "Mai",
                    "Juin",
                    "Juillet",
                    "Août",
                    "Septembre",
                    "Octobre",
                    "Novembre",
                    "Décembre",
                ],
                datasets: [
                    {
                        label: "Contrat 1",
                        fill: false,
                        borderColor: "rgba(255, 99, 132, 1)",
                        data: [5, 10, 8, 12, 7, 15, 9, 11, 6, 13, 8, 10],
                    },
                    {
                        label: "Contrat 2",
                        fill: false,
                        borderColor: "rgba(54, 162, 235, 1)",
                        data: [8, 6, 11, 9, 13, 7, 10, 12, 8, 14, 9, 11],
                    },
                    // Ajouter des ensembles de données supplémentaires pour chaque contrat
                ],
            };

            var paymentDelayChart = {
                type: "line",
                data: $scope.paymentDelayData,
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                },
            };

            var ctx = document.getElementById("myChart4").getContext("2d");
            var myChart = new Chart(ctx, paymentDelayChart);
        };
        $scope.traceGraph5 = function () {
            $scope.paymentDelayStackedData = {
                labels: ["Contrat 1", "Contrat 2", "Contrat 3"], // Exemple de noms de contrats
                datasets: [
                    {
                        label: "retard Inférieur à 1 mois",
                        backgroundColor: "rgba(255, 99, 132, 0.5)",
                        data: [5, 8, 3], // Exemple de nombre de retards inférieurs à 1 mois pour chaque contrat
                    },
                    {
                        label: "retard Entre 1 et 2 mois",
                        backgroundColor: "rgba(54, 162, 235, 0.5)",
                        data: [3, 2, 6], // Exemple de nombre de retards entre 1 et 2 mois pour chaque contrat
                    },
                    {
                        label: "retard Supérieur à 2 mois",
                        backgroundColor: "rgba(75, 192, 192, 0.5)",
                        data: [2, 5, 1], // Exemple de nombre de retards supérieurs à 2 mois pour chaque contrat
                    },
                ],
            };

            var paymentDelayChart = {
                type: "bar",
                data: $scope.paymentDelayStackedData,
                options: {
                    scales: {
                        xAxes: [{ stacked: true }],
                        yAxes: [{ stacked: true }],
                    },
                },
            };

            var ctx = document.getElementById("myChart5").getContext("2d");
            var myChart = new Chart(ctx, paymentDelayChart);
        };

        $scope.traceGraph2 = function () {
            $scope.options = {
                type: "line",
                data: {
                    labels: [
                        "Janvier",
                        "Février",
                        "Mars",
                        "Avril",
                        "Mai",
                        "Juin",
                        "Juillet",
                        "Août",
                        "Septembre",
                        "Octobre",
                        "Novembre",
                        "Décembre",
                    ],
                    datasets: [
                        {
                            label: "Revenus totaux",
                            data: [
                                1000, 1500, 2000, 1800, 2200, 2500, 2300, 2800,
                                3000, 3200, 3500, 3800,
                            ], // Exemple de données pour les revenus totaux
                            backgroundColor: "rgba(54, 162, 235, 0.2)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Dépenses totales",
                            data: [
                                800, 900, 1000, 950, 1100, 1200, 1050, 1300,
                                1400, 1500, 1600, 1700,
                            ], // Exemple de données pour les dépenses totales
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Bénéfices nets",
                            data: [
                                200, 600, 1000, 850, 1100, 1300, 1250, 1500,
                                1600, 1700, 1900, 2100,
                            ], // Exemple de données pour les bénéfices nets
                            backgroundColor: "rgba(75, 192, 192, 0.2)",
                            borderColor: "rgba(75, 192, 192, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                },
            };

            var ctx = document.getElementById("myChart2").getContext("2d");
            var myChart = new Chart(ctx, $scope.options);
        };

        $scope.traceGraph6 = function () {
            $scope.options = {
                type: "bar",
                data: {
                    labels: [
                        "Janvier",
                        "Fevrier",
                        "Mars",
                        "Avril",
                        "Mai",
                        "Juin",
                        "Juillet",
                        "Aout",
                        "Septembre",
                        "Octobre",
                        "Novembre",
                        "Decembre",
                    ],
                    datasets: [
                        {
                            label: "Part amortissement",
                            data: [
                                [100, 150],
                                [200, 250],
                            ], // Données des contrats 1 et 2 pour la part d'amortissement par mois
                            backgroundColor: "rgba(255, 99, 132, 0.5)",
                            borderWidth: 1,
                        },
                        {
                            label: "Frais locatifs",
                            data: [
                                [90, 110],
                                [80, 120],
                            ], // Données des contrats 1 et 2 pour les frais locatifs par mois
                            backgroundColor: "rgba(54, 162, 235, 0.5)",
                            borderWidth: 1,
                        },
                        {
                            label: "Frais de gestion",
                            data: [
                                [399, 600],
                                [800, 700],
                            ], // Données des contrats 1 et 2 pour les frais de gestion par mois
                            backgroundColor: "rgba(255, 206, 86, 0.5)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        xAxes: [{ stacked: true }],
                        yAxes: [{ stacked: true }],
                    },
                },
            };

            var ctx = document.getElementById("myChart6").getContext("2d");
            var myChart = new Chart(ctx, $scope.options);
        };

        $scope.circularDiagProjection = function () {
            var ctx = document
                .getElementById("projectionDiag")
                .getContext("2d");

            // Exemple de données simulées pour loyers anticipés et loyers à encaisser
            var loyerAnticipe = $scope.dataPage["loyerAnticipe"] || 15000; // Par exemple, 15 000
            var loyerAEncaisser = $scope.dataPage["loyerAEncaisser"] || 25000; // Par exemple, 25 000

            // Configuration des données pour le diagramme circulaire
            var data = {
                labels: ["Loyers Anticipés", "Loyers à Encaisser"],
                datasets: [
                    {
                        label: "Projection des loyers",
                        data: [loyerAnticipe, loyerAEncaisser], // Les deux catégories de loyers
                        backgroundColor: [
                            "rgba(153, 102, 255, 0.6)", // Violet pour loyers anticipés
                            "rgba(255, 99, 132, 0.6)", // Rouge pour loyers à encaisser
                        ],
                        borderColor: [
                            "rgba(153, 102, 255, 1)",
                            "rgba(255, 99, 132, 1)",
                        ],
                        borderWidth: 2, // Bordure du diagramme
                    },
                ],
            };

            // Options pour le diagramme
            var options = {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top", // Position de la légende
                        labels: {
                            font: {
                                size: 14, // Taille de la police pour la légende
                            },
                        },
                    },
                    title: {
                        display: true,
                        text: "Répartition des Loyers Anticipés et Loyers à Encaisser",
                        font: {
                            size: 18, // Taille de la police pour le titre
                        },
                    },
                },
            };

            // Créer ou mettre à jour le diagramme
            if (window.myChartProjection) {
                // Mettre à jour le diagramme existant
                window.myChartProjection.data = data;
                window.myChartProjection.update();
            } else {
                // Créer un nouveau diagramme
                window.myChartProjection = new Chart(ctx, {
                    type: "doughnut",
                    data: data,
                    options: options,
                });
            }
        };

        $scope.circularDiagAppartements = function () {
            var ctx = document
                .getElementById("occupationRidwan")
                .getContext("2d");

            // Exemple de données simulées pour le nombre d'appartements libres et occupés

            $scope.$watch("dataPage['entites']", function (newValue, oldValue) {
                if (newValue) {
                    var appartementsLibres =
                        newValue[1].nbreappartementsvide || 0;
                    var appartementsOccupes =
                        newValue[1].nbreappartementslouer || 0;
                    var data = {
                        labels: ["Villa Libres", "Villa Occupés"],
                        datasets: [
                            {
                                label: "Statut des Appartements",
                                data: [appartementsLibres, appartementsOccupes], // Les deux catégories d'appartements
                                backgroundColor: [
                                    "rgba(75, 192, 192, 0.6)", // Vert clair pour les appartements libres
                                    "rgba(54, 162, 235, 0.6)", // Bleu clair pour les appartements occupés
                                ],
                                borderColor: [
                                    "rgba(75, 192, 192, 1)",
                                    "rgba(54, 162, 235, 1)",
                                ],
                                borderWidth: 2, // Bordure du diagramme
                            },
                        ],
                    };
                    // Options pour le diagramme
                    var options = {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: "top", // Position de la légende
                                labels: {
                                    font: {
                                        size: 14, // Taille de la police pour la légende
                                    },
                                },
                            },
                            title: {
                                display: true,
                                text: "Répartition des Appartements Libres et Occupés",
                                font: {
                                    size: 18, // Taille de la police pour le titre
                                },
                            },
                        },
                    };

                    // Créer ou mettre à jour le diagramme
                    if (window.myChartAppartements) {
                        // Mettre à jour le diagramme existant
                        window.myChartAppartements.data = data;
                        window.myChartAppartements.update();
                    } else {
                        // Créer un nouveau diagramme
                        window.myChartAppartements = new Chart(ctx, {
                            type: "doughnut",
                            data: data,
                            options: options,
                        });
                    }
                }
            });
        };

        $scope.histogrammeRlLn = function () {
            // Initialiser le canvas pour le graphique
            const ctx = document.getElementById("myChart").getContext("2d");
            // Données des mois de janvier à décembre
            const mois = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];
            $scope.$watch("dataPage['entites']", function (newValue, oldValue) {
                if (newValue) {
                    console.log(newValue, "nez");
                    // Données pour les loyers en retard et les loyers payés à temps
                    const loyersEnRetard =
                        newValue[1]["nbreretardloyer"].map(
                            (e) => e.nombre_de_retards
                        ) || 0;
                    const loyersPayesATemps =
                        newValue[1]["nbreretardloyer"].map(
                            (e) => e.nombre_payer_a_temps
                        ) || 0;

                    console.log(loyersEnRetard, loyersPayesATemps, "nezz");
                    const myChart = new Chart(ctx, {
                        type: "bar", // Type de graphique
                        data: {
                            labels: mois, // Les mois de janvier à décembre
                            datasets: [
                                {
                                    label: "Loyers en Retard",
                                    data: loyersEnRetard,
                                    backgroundColor: "rgba(255, 99, 132, 0.5)", // Couleur des barres
                                    borderColor: "rgba(255, 99, 132, 1)", // Couleur des bordures
                                    borderWidth: 1,
                                },
                                {
                                    label: "Loyers Payés à Temps",
                                    data: loyersPayesATemps, // Données pour les loyers payés à temps
                                    backgroundColor: "rgba(54, 162, 235, 0.5)", // Couleur des barres
                                    borderColor: "rgba(54, 162, 235, 1)", // Couleur des bordures
                                    borderWidth: 1,
                                },
                            ],
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                },
                            },
                        },
                    });
                }
            });
        };
        $scope.histogrammeLoyerTotal = function () {
            // Initialiser le canvas pour le graphique
            const ctx = document.getElementById("histoloyertotal").getContext("2d");

            // Données des mois de janvier à décembre
            const mois = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];

            $scope.$watch("dataPage['entites']", function (newValue, oldValue) {
                if (newValue) {
                    console.log(newValue, "nez");

                    // Données pour les loyers présents et les loyers à attendre
                    const loyersdumoi =
                        newValue[1]["nbreretardloyer"].map((e) => e.total) || [];
                    const loyersAAttendre =
                        newValue[1]["nbreretardloyer"].map(
                            (e) => e.totalecheance
                        ) || [];

                    const myChart = new Chart(ctx, {
                        type: "bar", // Type de graphique principal (barres)
                        data: {
                            labels: mois, // Les mois de janvier à décembre
                            datasets: [
                                {
                                    label: "Loyers présent", // Légende pour le dataset des barres
                                    data: loyersdumoi, // Données pour les loyers présents
                                    type: 'bar', // Type de graphique pour le premier dataset
                                    backgroundColor: "rgba(255, 99, 132, 0.2)", // Couleur des barres
                                    borderColor: "rgba(255, 99, 132, 1)", // Couleur de la bordure
                                    borderWidth: 1, // Largeur de la bordure
                                },
                                {
                                    label: "Loyers à attendre", // Légende pour le dataset de la courbe
                                    data: loyersAAttendre, // Données pour les loyers à attendre
                                    type: 'line', // Type de graphique pour le deuxième dataset
                                    fill: false, // Ne pas remplir l'espace sous la courbe
                                    borderColor: "rgba(54, 162, 235, 1)", // Couleur de la ligne
                                    backgroundColor: "rgba(54, 162, 235, 0.2)", // Couleur de fond (si besoin)
                                    borderWidth: 2, // Épaisseur de la ligne
                                    tension: 0.4, // Courbure de la ligne
                                    pointRadius: 5, // Rayon des points de la courbe
                                    pointHoverRadius: 7, // Rayon au survol
                                },
                            ],
                        },
                        options: {
                            scales: {
                                x: {
                                    beginAtZero: true, // Commencer l'axe X à 0
                                },
                                y: {
                                    beginAtZero: true, // Commencer l'axe Y à 0
                                },
                            },
                            responsive: true, // Rendre le graphique réactif
                            plugins: {
                                legend: {
                                    display: true, // Afficher la légende
                                },
                            },
                        },
                    });
                }
            });
        };





        $scope.circularDiagEtatEncaissement = function () {
            var ctx = document
                .getElementById("etatEncaissementDiag")
                .getContext("2d");

            $scope.$watch("dataPage['entites']", function (newValue, oldValue) {
                if (newValue) {
                    var data = {
                        labels: [
                            "Frais Locatif",
                            "Frais de Gestion",
                            "Amortissement",
                        ],
                        datasets: [
                            {
                                label: "Répartition des encaissements",
                                data: [
                                    $scope.dataPage["entites"][1]
                                        .fraisdelocation,
                                    $scope.dataPage["entites"][1].fraisgestion,
                                    $scope.dataPage["entites"][1].amortissement,
                                ],
                                backgroundColor: [
                                    "rgba(54, 162, 235, 0.6)", // Bleu pour frais locatif
                                    "rgba(255, 206, 86, 0.6)", // Jaune pour frais de gestion
                                    "rgba(75, 192, 192, 0.6)", // Vert pour amortissement
                                ],
                                borderColor: [
                                    "rgba(54, 162, 235, 1)",
                                    "rgba(255, 206, 86, 1)",
                                    "rgba(75, 192, 192, 1)",
                                ],
                                borderWidth: 2, // Taille des bordures
                            },
                        ],
                    };

                    // Options pour améliorer la présentation
                    var options = {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: "top", // Position de la légende
                                labels: {
                                    font: {
                                        size: 14, // Taille de police pour la légende
                                    },
                                },
                            },
                            title: {
                                display: true,
                                text: "Répartition des frais locatifs, de gestion et amortissement",
                                font: {
                                    size: 18, // Taille du titre
                                },
                            },
                        },
                    };

                    // Créer le diagramme en doughnut
                    if (window.myChartEncaissement) {
                        // Mettre à jour le diagramme existant
                        window.myChartEncaissement.data = data;
                        window.myChartEncaissement.update();
                    } else {
                        // Créer un nouveau diagramme
                        window.myChartEncaissement = new Chart(ctx, {
                            type: "doughnut",
                            data: data,
                            options: options,
                        });
                    }
                }
            });
        };

        $scope.circularDiagBien = function () {
            var ctx = document.getElementById("bienDiag").getContext("2d");

            $scope.$watch("dataPage['entites']", function (newValue, oldValue) {
                if (newValue) {
                    var data = {
                        labels: ["Bien libre", "Bien en location"],
                        datasets: [
                            {
                                label: "Répartition des biens",
                                data: [
                                    $scope.dataPage["entites"][0][
                                    "nbreappartementsvide"
                                    ],
                                    $scope.dataPage["entites"][0][
                                    "nbreappartementslouer"
                                    ],
                                ],
                                backgroundColor: [
                                    "rgba(255, 99, 132, 0.2)", // Light Red
                                    "rgba(54, 162, 235, 0.2)", // Light Blue
                                ],
                                borderColor: [
                                    "rgba(255, 99, 132, 1)", // Solid Red
                                    "rgba(54, 162, 235, 1)", // Solid Blue
                                ],

                                borderWidth: 1,
                            },
                        ],
                    };

                    var options = {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: "top",
                            },
                            title: {
                                display: true,
                                text: "Répartition des biens libres et en location",
                            },
                        },
                        onClick: function (evt, activeElements) {
                            if (activeElements.length > 0) {
                                var index = activeElements[0]._index; // Obtenir l'index de l'élément cliqué
                                var label = data.labels[index]; // Récupérer le label (Bien libre ou Bien en location)
                                if (label === "Bien libre") {
                                    $scope.redirectCustom(
                                        '#!/list-appartement/code:"SCI",etat:0',
                                        " Biens libre "
                                    );
                                } else if (label === "Bien en location") {
                                    $scope.redirectCustom(
                                        '#!/list-appartement/code:"SCI",etat:1',
                                        " Biens libre "
                                    );
                                }
                            }
                        },
                    };

                    var myChart = new Chart(ctx, {
                        type: "doughnut",
                        data: data,
                        options: options,
                    });
                }
            });
        };

        $scope.traceGraph7 = function () {
            console.log($scope.dataPage["entites"]);
            $scope.options = {
                type: "bar",
                data: {
                    labels: [
                        "Janvier",
                        "Février",
                        "Mars",
                        "Avril",
                        "Mai",
                        "Juin",
                        "Juillet",
                        "Août",
                        "Septembre",
                        "Octobre",
                        "Novembre",
                        "Décembre",
                    ],
                    datasets: [
                        {
                            label: "Retard de paiement",
                            data: [10, 15, 8, 12, 20, 5, 18, 14, 9, 7, 11, 6], // Exemple de données de retard de paiement pour chaque mois
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: "Histogramme du nombre de  Retard de Paiement par mois",
                        },
                    },
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                },
            };

            var ctx = document.getElementById("myChart7").getContext("2d");
            var myChart = new Chart(ctx, $scope.options);
        };

        $scope.traceGraph8 = function () {
            // Données réelles de retard de paiement pour chaque contrat
            var retardPaiementParContrat = [5, 10, 2];

            $scope.options = {
                type: "bar",
                data: {
                    labels: ["Contrat 1", "Contrat 2", "Contrat 3"],
                    datasets: [
                        {
                            label: "Retard de Paiement",
                            data: retardPaiementParContrat,
                            backgroundColor: "rgba(255, 99, 132, 0.5)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: "Custom Chart Title",
                        },
                    },
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString:
                                        "Retard de Paiement (en jours)",
                                },
                            },
                        ],
                        xAxes: [
                            {
                                scaleLabel: {
                                    display: true,
                                    labelString: "Contrats",
                                },
                            },
                        ],
                    },
                },
            };

            var ctx = document.getElementById("myChart8").getContext("2d");
            var myChart = new Chart(ctx, $scope.options);
        };

        $scope.traceTopReservataires = function () {
            var topReservataires = [
                { nom: "Réservataire 1", periodePaiement: 3 },
                { nom: "Réservataire 2", periodePaiement: 2 },
                { nom: "Réservataire 3", periodePaiement: 4 },
                { nom: "Réservataire 4", periodePaiement: 1 },
                { nom: "Réservataire 5", periodePaiement: 0 },
            ];

            var labels = topReservataires.map(function (reservataire) {
                return reservataire.nom;
            });

            var data = topReservataires.map(function (reservataire) {
                return reservataire.periodePaiement;
            });

            $scope.options = {
                type: "horizontalBar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Période de Paiement",
                            data: data,
                            backgroundColor: "rgba(54, 162, 235, 0.5)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        xAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString:
                                        "Période de Paiement (en jours)",
                                },
                            },
                        ],
                        yAxes: [
                            {
                                scaleLabel: {
                                    display: true,
                                    labelString: "Réservataires",
                                },
                            },
                        ],
                    },
                },
            };

            var ctx = document.getElementById("myChart9").getContext("2d");
            var myChart = new Chart(ctx, $scope.options);
        };

        $scope.traceTendanceRelancesPaiement = function () {
            // Données pour le graphique
            var montantRembourse = 10000; // Montant total remboursé
            var montantTotal = 100000; // Montant total à rembourser
            var montantRestant = montantTotal - montantRembourse;

            // Configuration du graphique

            $scope.options = {
                type: "bar",
                data: {
                    labels: ["Montant remboursé", "Montant restant"],
                    datasets: [
                        {
                            label: "Remboursement",
                            data: [montantRembourse, montantRestant],
                            backgroundColor: [
                                "rgba(54, 162, 235, 0.5)", // Couleur pour le montant remboursé
                                "rgba(255, 99, 132, 0.5)", // Couleur pour le montant restant
                            ],
                            borderColor: [
                                "rgba(54, 162, 235, 1)", // Couleur de la bordure pour le montant remboursé
                                "rgba(255, 99, 132, 1)", // Couleur de la bordure pour le montant restant
                            ],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                },
            };
            var ctx = document.getElementById("myChart10").getContext("2d");
            var myChart = new Chart(ctx, $scope.options);
        };

        function getRandomColor() {
            var letters = "0123456789ABCDEF";
            var color = "#";
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        function getRandomColor() {
            var letters = "0123456789ABCDEF";
            var color = "#";
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        //  redirect fonction
        $scope.appTitle = "";
        $scope.redirectCustom = function (url, title) {
            $scope.appTitle = title;
            window.location.href = url;
        };

        $scope.$on("$routeChangeStart", function (next, current) {
            $('.modal[role="dialog"]').on("hide.bs.modal", function (e) { });

            $("#modal").on("hide.bs.modal", function (e) { });
            $scope.orderby = null;
            $scope.linknav = $location.path();
            $scope.currentTemplateUrl = null;
            $scope.currentTemplateUrl = current.params.namepage
                ? current.params.namepage
                : "dashboard";
            $scope.notification_commande = [];
            var originalPath = $scope.currentTemplateUrl.split("/");
            if (originalPath && originalPath.length > 0) {
                $scope.permissionResources = originalPath[1];
            }
            console.log(
                "currentTemplateUrl s'affiche iciii acc",
                $scope.currentTemplateUrl
            );
            $scope.$on("userLoaded", function () {
                if ($scope.infosUserConnected.roles[0].name === "resident") {
                    $scope.isActiveMenu = true;
                }
            });


            // Pour afficher le modal des infos details
            $scope.detailParentId = null;
            //Pour le detail d'un produit donné
            $scope.produitSelected = [];
            //console.log('/******* Réintialisation de certaines valeurs *******/');
            $(".force-disabled").attr("disabled", "disabled");
            $scope.linknav = $location.path();

            //markme-LISTE
            //nouveau element
            $scope.dataDetailPage = {
                detailproduit: {},
            };
            //Changement
            //nouveau element
            $scope.dataPage = {
                avenants: [],
                historiquerelances: [],
                copreneurs: [],
                avisecheances: [],
                inboxs: [],
                secteuractivites: [],
                modepaiements: [],
                periodes: [],
                villas: [],
                entites: [],
                ilots: [],
                periodicites: [],
                permissions: [],
                roles: [],
                users: [],
                delaipreavis: [],
                produitsutilises: [],
                typeappartements: [],
                typeassurances: [],
                structureimmeubles: [],
                niveauappartements: [],
                typecontrats: [],
                typedocuments: [],
                typefactures: [],
                typeinterventions: [],
                typelocataires: [],
                typeobligationadministratives: [],
                typepieces: [],
                constituantpieces: [],
                equipementpieces: [],
                factures: [],
                fonctions: [],
                cautions: [],
                messages: [],
                rapportinterventions: [],
                contratprestations: [],
                versementloyers: [],
                versementchargecoproprietes: [],
                assurances: [],
                typerenouvellements: [],
                pieceappartements: [],
                immeubles: [],
                appartements: [],
                equipegestions: [],
                membreequipegestions: [],
                prestataires: [],
                observations: [],
                obligationadministratives: [],
                proprietaires: [],
                frequencepaiementappartements: [],
                etatappartements: [],
                questionnaires: [],
                interventions: [],
                etatlieus: [],
                rapporttechniciens: [],
                locataires: [],
                demanderesiliations: [],
                demandeinterventions: [],
                contratprestataires: [],
                factureprestataires: [],
                calendriers: [],
                resiliationbails: [],
                contrats: [],
                locationventes: [],
                annonces: [],
                financeimmeubles: [],
                financeappartements: [],
                situationcompteclients: [],
                questionnairesatisfactions: [],
                travauximmresidents: [],
                travauximmgestionnaires: [],
                travauxappresidents: [],
                travauxappgestionnaires: [],
                repertoireresidents: [],
                repertoireproprietaires: [],
                repertoireprestataires: [],
                repertoireemployes: [],
                rappelpaiementloyers: [],
                facturelocations: [],
                typefactures: [],
                devis: [],
                paiementinterventions: [],
                taxes: [
                    { id: "aucun", designation: "Aucun" },
                    { id: "tva", designation: "TVA" },
                    { id: "brs", designation: "BRS" },
                ],
            };

            //Changement
            //nouveau element
            $scope.paginations = {
                inbox: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                avenant: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                secteuractivite: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },

                modepaiement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                periode: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },

                locationvente: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },

                villa: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                entite: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                ilot: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                pieceappartement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                role: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                user: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typeappartement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typeassurance: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                structureimmeuble: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                niveauappartement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typecontrat: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typedocument: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typefacture: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typeintervention: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typelocataire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                constituantpiece: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                equipementpiece: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                message: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                prestataire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                caution: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                rapportintervention: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                contratprestation: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                obligationadministrative: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                versementloyer: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                versementchargecopropriete: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                assurance: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typeobligationadministrative: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typepiece: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                observation: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                typerenouvellement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                immeuble: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                appartement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                equipegestion: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                membreequipegestion: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                facture: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                proprietaire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                demanderesiliation: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                demandeintervention: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                frequencepaiementappartement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                questionnaire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                etatappartement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                intervention: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                etatlieu: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                rapporttechnicien: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                locataire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                contratprestataire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                factureprestataire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                calendrier: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                permission: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                resiliationbail: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                contrat: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                financeimmeuble: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                financeappartement: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                situationcompteclient: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                versementcharge: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                messagerie: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                annonce: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                questionnairesatisfaction: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                travauximmresident: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                travauximmgestionnaire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                travauxappresident: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                travauxappgestionnaire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                repertoireresident: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                repertoireproprietaire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                repertoireprestataire: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                repertoireemploye: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                facturelocations: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                devis: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
                paiementinterventions: {
                    currentPage: 1,
                    maxSize: 10,
                    entryLimit: 10,
                    totalItems: 0,
                },
            };

            if (
                $scope.currentTemplateUrl.toLowerCase().indexOf("list-") !== -1
            ) {
                var getNameItemOld = $scope.currentTemplateUrl.toLowerCase();
                var getNameItem = getNameItemOld.substring(
                    5,
                    getNameItemOld.length
                );
                var type = getNameItem;

                if (getNameItem != "appartement") {
                    $scope.pageChanged(getNameItem);
                } else if (getNameItem == "appartement") {
                    if (current.params.itemId) {
                        $scope.$on("$viewContentLoaded", function () {
                            $scope.filters = current.params.itemId;
                            $scope.pageChanged(
                                "appartement",
                                null,
                                current.params.itemId
                            );
                        });
                    } else {
                        $scope.appTitle = "";
                        $scope.pageChanged("appartement");
                    }
                }
                //nouveau element
                $scope.getelements("entites");
                $scope.getelements("roles");
                $scope.getelements("modepaiements");
                if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-appartement") !== -1
                ) {
                    $scope.titlePage = "Appartements";
                    $scope.getelements("pieceappartements");
                    $scope.getelements("frequencepaiementappartements");
                    $scope.getelements("etatappartements");
                    $scope.getelements("typeappartements");
                    // $scope.getelements("proprietaires");
                    $scope.getelements("immeubles");
                    // $scope.getelements("locataires");
                    $scope.getelements("obligationadministratives");
                    // $scope.getelements("paiementloyers");
                    // $scope.getelements("factures");
                    $scope.getelements("annonces");
                    $scope.getelements("rapportinterventions");
                    $scope.getelements("questionnaires");
                    // $scope.getelements("contrats");
                    $scope.getelements("constituantpieces");
                    $scope.getelements("equipementpieces");
                    $scope.getelements("observations");
                    $scope.getelements("typecontrats");
                    $scope.getelements("typerenouvellements");
                    $scope.getelements("delaipreavis");
                    $scope.getelements("typelocataires");
                    $scope.getelements("typeappartement_pieces");
                    $scope.getelements("etatlieu_pieces");
                    $scope.getelements("imageappartements");
                    $scope.getelements("imagecompositions");
                    $scope.getelements("imageetatlieupieces");
                    $scope.getelements("entites");
                    $scope.getelements("periodicites");
                    $scope.getelements("ilots");
                    // $scope.getelements("contratproprietaires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-user") !== -1
                ) {
                    $scope.titlePage = "Utilisateurs";
                    $scope.getelements("prestataires");
                    $scope.getelements("entites");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-preference") !== -1
                ) {
                    $scope.titlePage = "Préferences";
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-role") !== -1
                ) {
                    $scope.titlePage = "Profils-permissions";
                } else if (
                    $scope.currentTemplateUrl == "list-facturelocation"
                ) {
                    // ajouté par moi
                    $scope.titlePage = "Factures Locations";
                    // $scope.pageChanged('facture');
                    // $scope.getelements('facturelocations');
                    $scope.getelements("contrats");
                    $scope.getelements("periodicites");
                    $scope.getelements("periodes");
                    $scope.getelements("modepaiements");
                    $scope.getelements("typefactures");
                    $scope.getelements("locataires");
                    $scope.getelements("appartements");
                    $scope.getelements("users");
                    $scope.getelements("immeubles");
                    $scope.getelements("proprietaires");
                    // $scope.getelements2point0('entites',{attributs:"id,designation,nb_commande,ca_commande"});
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-financeappartement") !== -1
                ) {
                    $scope.titlePage = "Finances appartements";
                    $scope.getelements("appartements");
                    $scope.getelements("frequencepaiementappartements");
                    $scope.getelements("etatappartements");
                    $scope.getelements("typeappartements");
                    $scope.getelements("typefactures");
                    $scope.getelements("proprietaires");
                    $scope.getelements("immeubles");
                    $scope.getelements("locataires");
                    $scope.getelements("obligationadministratives");
                    $scope.getelements("paiementloyers");
                    $scope.getelements("factures");
                    $scope.getelements("annonces");
                    $scope.getelements("rapportinterventions");
                    $scope.getelements("questionnaires");
                    $scope.getelements("assurances");
                    $scope.getelements("cautions");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-immeuble") !== -1
                ) {
                    $scope.titlePage = "Immeubles";

                    $scope.getelements("pieceappartements");
                    $scope.getelements("contrats");
                    $scope.getelements("equipegestions");
                    $scope.getelements("immeuble_proprietaires");
                    $scope.getelements("questionnaires");
                    $scope.getelements("frequencepaiementappartements");
                    $scope.getelements("etatappartements");
                    $scope.getelements("typeappartements");
                    $scope.getelements("proprietaires");
                    $scope.getelements("structureimmeubles");
                    $scope.getelements("typepieces");
                    $scope.getelements("equipegestion_membreequipegestions");
                    $scope.getelements("prestataires");
                    $scope.getelements("horaires");
                    $scope.getelements("equipementpieces");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-financeimmeuble") !== -1
                ) {
                    $scope.titlePage = "Finances immeubles";
                    $scope.getelements("immeubles");
                    $scope.getelements("immeuble_proprietaires");
                    $scope.getelements("frequencepaiementappartements");
                    $scope.getelements("etatappartements");
                    $scope.getelements("typeappartements");
                    $scope.getelements("proprietaires");
                    $scope.getelements("paiementloyers");
                    $scope.getelements("contrats");
                    $scope.getelements("factures");
                    $scope.getelements("assurances");
                    $scope.getelements("typefactures");
                    $scope.getelements("obligationadministratives");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-pieceappartement") !== -1
                ) {
                    $scope.titlePage = "Pieces des appartements";
                    $scope.getelements("appartements");
                    $scope.getelements("immeubles");
                    $scope.getelements("typepieces");
                    $scope.getelements("etatlieus");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-etatloyer") !== -1
                ) {
                    $scope.titlePage = "Etats Loyer";
                    $scope.getelements("immeubles");
                    $scope.getelements("locataires");
                    $scope.getelements("proprietaires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-structureimmeuble") !== -1
                ) {
                    $scope.titlePage = "Structures des immeubles";
                    //$scope.getelements('etatlieus');
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-niveauappartements") !== -1
                ) {
                    $scope.titlePage = "Niveaux des appartements";
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-rapportintervention") !== -1
                ) {
                    $scope.titlePage = "Rapports d'interventions";
                    $scope.getelements("interventions");
                    $scope.getelements("produitsutilises");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typeassurance") !== -1
                ) {
                    $scope.titlePage = "Type d'assurances";
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-prestataire") !== -1
                ) {
                    $scope.titlePage = "Prestataires";
                    $scope.getelements("categorieprestataires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-annonce") !== -1
                ) {
                    $scope.titlePage = "Annonces";
                    $scope.getelements("immeubles");

                    $scope.$on("userLoaded", function () {
                        $scope.pageChanged("annonce");
                    });
                    // $scope.getelements("appartements");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-message") !== -1
                ) {
                    $scope.titlePage = "Messages";

                    $scope.$on("userLoaded", function () {
                        $scope.pageChanged("message");
                    });
                    // $scope.getelements("locataires");
                    // $scope.getelements("proprietaires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-membreequipegestion") !== -1
                ) {
                    $scope.titlePage = "Membres equipe de gestion";
                    $scope.getelements("fonctions");
                    $scope.getelements("equipegestions");
                    $scope.getelements("interventions");
                    $scope.getelements("demandeinterventions");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-questionnairesatisfaction") !== -1
                ) {
                    $scope.titlePage = "Questionnaires de satisfactions";
                    $scope.getelements("locataires");
                    $scope.getelements("proprietaires");
                } else if ($scope.currentTemplateUrl == "list-facture") {
                    $scope.titlePage = "Factures";
                    $scope.getelements("interventions");
                    $scope.getelements("typefactures");
                    $scope.getelements("immeubles");
                    $scope.getelements("proprietaires");
                    $scope.getelements("locataires");
                    $scope.getelements("appartements", {}, "location:1");
                } else if (
                    $scope.currentTemplateUrl == "list-factureintervention"
                ) {
                    $scope.titlePage = "Factures interventions";
                    $scope.getelements("interventions");
                    $scope.getelements("demandeinterventions");
                    $scope.getelements("typefactures");
                    $scope.getelements("immeubles");
                    // $scope.getelements("locataires");
                    $scope.getelements("modepaiements");
                    // $scope.getelements("appartements");
                    $scope.getelements("paiementinterventions");
                    $scope.getelements("factureinterventions");
                    $scope.getelements("prestataires");
                    $scope.$on("userLoaded", function () {
                        $scope.pageChanged("factureintervention");
                    });

                } else if (
                    $scope.currentTemplateUrl == "list-contratprestation"
                ) {
                    $scope.titlePage = "Contrats prestataires";
                    $scope.getelements("prestataires");
                    $scope.getelements("categorieprestations");
                    $scope.getelements("frequencepaiementappartements");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-obligationadministrative") !== -1
                ) {
                    $scope.titlePage = "Obliigations administrtives";
                    $scope.getelements("typeobligationadministratives");
                    $scope.getelements("immeubles");
                    $scope.getelements("appartements");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-versementloyer") !== -1
                ) {
                    $scope.titlePage = "Versements Loyers";
                    $scope.getelements("appartements");
                    $scope.getelements("contrats");
                    $scope.getelements("locataires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-versementchargecopropriete") !== -1
                ) {
                    $scope.titlePage = "Versements Charges de coproprietes";
                    $scope.getelements("proprietaires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-equipegestion") !== -1
                ) {
                    $scope.titlePage = "Equipes de gestion";
                    $scope.getelements("immeubles");
                    $scope.getelements("fonctions");
                    $scope.getelements("membreequipegestions");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-proprietaire") !== -1
                ) {
                    $scope.titlePage = "Proprietaires";
                    $scope.getelements("immeubles");
                    // $scope.getelements("contratproprietaires");
                    // $scope.getelements("appartements");
                    // $scope.getelements("contrats");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-detailsproprietaire") !== -1
                ) {
                    $scope.titlePage = "Details Proprietaire";
                    $scope.getelements(
                        "proprietaires",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "id:" + current.params.itemId
                    );
                    $scope.getelements(
                        "contratproprietaires",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "proprietaire_id:" + current.params.itemId
                    );
                    $scope.getelements(
                        "appartements",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "proprietaire_id:" + current.params.itemId
                    );
                    $scope.getelements(
                        "facturelocations",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "proprietaire_id:" + current.params.itemId
                    );
                    $scope.getelements("contrats");
                    $scope.getelements("periodicites");
                    $scope.getelements("modepaiements");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-equipementpiece") !== -1
                ) {
                    $scope.titlePage = "Equipement pieces";
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-paiementloyer") !== -1
                ) {
                    $scope.titlePage = "Paiements loyers";
                    $scope.getelements("appartements");
                    $scope.getelements("locataires");
                    $scope.getelements("contrats");
                    $scope.getelements("modepaiements");
                    $scope.getelements("periodicites");
                    $scope.getelements("periodes");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-intervention") !== -1
                ) {
                    $scope.titlePage = "Interventions";
                    $scope.getelements("prestataires");
                    $scope.getelements("categorieinterventions");
                    $scope.getelements("demandeinterventions");
                    $scope.getelements("membreequipegestions");
                    $scope.getelements("users");

                    $scope.$on("userLoaded", function () {
                        $scope.pageChanged("intervention");
                    });

                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-travauximmgestionnaire") !== -1
                ) {
                    $scope.titlePage = "Travaux initiés par le gestionnaire";
                    $scope.getelements("interventions");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-secteuractivite") !== -1
                ) {
                    $scope.titlePage = "Secteurs d'activités";
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-locataire") !== -1
                ) {
                    $scope.titlePage = "Locataires";
                    $scope.getelements("typelocataires");
                    // $scope.getelements("demandeinterventions");
                    // $scope.getelements("paiementloyers");
                    // $scope.getelements("contrats");
                    $scope.getelements("entites");
                    $scope.getelements("secteuractivites");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-etatlieu") !== -1
                ) {
                    $scope.titlePage = "Etat des lieux";
                    $scope.getelements("appartements");
                    $scope.getelements("typepieces");
                    $scope.getelements("constituantpieces");
                    $scope.getelements("equipementpieces");
                    $scope.getelements("observations");
                    $scope.getelements("locataires");
                    $scope.getelements("pieceappartements");
                    $scope.getelements("categorieinterventions");
                    $scope.getelements("soustypeinterventions");
                    $scope.getelements("unites");
                    $scope.getelements("detaildevis");
                    $scope.getelements("categorieinterventions");
                    $scope.getelements("detaildevisdetails");
                    $scope.getelements("demandeinterventions");
                    $scope.getelements("locataires");
                    $scope.getelements("interventions");
                } else if ($scope.currentTemplateUrl == "list-contrat") {
                    //modulecontrat
                    $scope.titlePage = "Contrats";
                    $scope.getelements("typecontrats");
                    $scope.getelements("typerenouvellements");
                    $scope.getelements("delaipreavis");
                    // $scope.getelements("appartements");
                    var rewriteReq = "appartements(location:1)";
                    Init.getElement(
                        rewriteReq,
                        listofrequests_assoc["appartements"]
                    ).then(
                        function (data) {
                            if (data) {
                                $scope.dataPage["appartements"] = data;
                            }
                        },
                        function (msg) {
                            toastr.error(msg);
                        }
                    );
                    // $scope.getelements("locataires");
                    $scope.getelements("prestataires");
                    $scope.getelements("assurances");
                    $scope.getelements("typeassurances");
                    $scope.getelements("typelocataires");

                    $scope.$on("userLoaded", function () {
                        $scope.pageChanged("contrat");
                        if ($scope.infosUserConnected.roles[0].name === "resident") {
                            console.log('here');
                        }

                    });

                    $scope.getelements("typepieces");
                    $scope.getelements("constituantpieces");
                    $scope.getelements("equipementpieces");
                    $scope.getelements("observations");
                    $scope.getelements("pieceappartements");
                    $scope.getelements("periodicites");
                    $scope.dataPage["rappelpaiementloyers"] = [
                        {
                            id: "5",
                            libelle: "Le 05 de chaque mois",
                        },
                        {
                            id: "6",
                            libelle: "Le 06 de chaque mois",
                        },
                        {
                            id: "7",
                            libelle: "Le 07 de chaque mois",
                        },
                        {
                            id: "8",
                            libelle: "Le 08 de chaque mois",
                        },
                        {
                            id: "9",
                            libelle: "Le 09 de chaque mois",
                        },
                        {
                            id: "10",
                            libelle: "Le 10 de chaque mois",
                        },
                        {
                            id: "11",
                            libelle: "Le 11 de chaque mois",
                        },
                        {
                            id: "12",
                            libelle: "Le 12 de chaque mois",
                        },
                        {
                            id: "13",
                            libelle: "Le 13 de chaque mois",
                        },
                        {
                            id: "14",
                            libelle: "Le 14 de chaque mois",
                        },
                        {
                            id: "15",
                            libelle: "Le 15 de chaque mois",
                        },
                        {
                            id: "16",
                            libelle: "Le 16 de chaque mois",
                        },
                        {
                            id: "17",
                            libelle: "Le 17 de chaque mois",
                        },
                        {
                            id: "18",
                            libelle: "Le 18 de chaque mois",
                        },
                        {
                            id: "19",
                            libelle: "Le 19 de chaque mois",
                        },
                        {
                            id: "20",
                            libelle: "Le 20 de chaque mois",
                        },
                        {
                            id: "21",
                            libelle: "Le 21 de chaque mois",
                        },
                        {
                            id: "22",
                            libelle: "Le 22 de chaque mois",
                        },
                        {
                            id: "23",
                            libelle: "Le 23 de chaque mois",
                        },
                        {
                            id: "24",
                            libelle: "Le 24 de chaque mois",
                        },
                        {
                            id: "25",
                            libelle: "Le 25 de chaque mois",
                        },
                    ];
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typeappartement") !== -1
                ) {
                    $scope.titlePage = "Types d'appartements";
                    $scope.getelements("appartements");
                    $scope.getelements("typepieces");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typecontrat") !== -1
                ) {
                    $scope.titlePage = "Types de contrats";
                    $scope.getelements("contrats");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-demanderesiliation") !== -1
                ) {
                    $scope.titlePage = "Demande de résiliations";
                    // $scope.getelements("appartements");
                    $scope.getelements("delaipreavis");
                    // $scope.getelements("contrats");
                    // $scope.getelements("locataires");
                    $scope.getelements("constituantpieces");
                    $scope.getelements("observations");

                    $scope.$on("userLoaded", function () {
                        $scope.pageChanged("demanderesiliation");
                    });

                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-demandeintervention") !== -1
                ) {
                    $scope.titlePage = "Demande d'interventions";
                    // $scope.getelements("appartements");
                    $scope.getelements("immeubles");
                    // $scope.getelements("locataires");
                    $scope.getelements("typeinterventions");
                    $scope.getelements("equipegestion_membreequipegestions");
                    // $scope.getelements("appartements");
                    // $scope.getelements("contrats");
                    $scope.getelements("typepieces");
                    $scope.getelements("categorieinterventions");
                    $scope.getelements("prestataires");
                    $scope.getelements("membreequipegestions");
                    $scope.getelements("soustypeinterventions");
                    $scope.getelements("unites");
                    $scope.getelements("detaildevis");
                    $scope.getelements("categorieinterventions");
                    $scope.getelements("detaildevisdetails");
                    $scope.getelements("interventions");

                    // $scope.pageChanged("demandeintervention");
                    $scope.$on("userLoaded", function () {
                        $scope.pageChanged("demandeintervention");
                    });
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typedocument") !== -1
                ) {
                    $scope.titlePage = "Types de documents";
                    $scope.getelements("documents");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typefacture") !== -1
                ) {
                    $scope.titlePage = "Types de factures";
                    $scope.getelements("factures");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typeintervention") !== -1
                ) {
                    $scope.titlePage = "Types d'interventions";
                    $scope.getelements("interventions");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typelocataire") !== -1
                ) {
                    $scope.titlePage = "Types de locataires";
                    $scope.getelements("locataires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typeobligationadministrative") !== -1
                ) {
                    $scope.titlePage = "Types d'obligations administratives";
                    $scope.getelements("obligationadministratives");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typepiece") !== -1
                ) {
                    $scope.titlePage = "Types de pieces";
                    $scope.getelements("immeubles");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-questionnaire") !== -1
                ) {
                    $scope.titlePage = "Questionnaires";
                    $scope.getelements("typequestionnaires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typerenouvellement") !== -1
                ) {
                    $scope.titlePage = "Types de renouvellements";
                    $scope.getelements("contrats");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-villa") !== -1
                ) {
                    $scope.titlePage = "Villas";

                    // $scope.getelements('etatappartements');
                    $scope.getelements("typeappartements");

                    $scope.getelements("locataires");

                    $scope.getelements("factures");

                    $scope.getelements("contrats");

                    $scope.getelements("imageappartements");
                    $scope.getelements("imagecompositions");

                    $scope.getelements("entites");
                    $scope.getelements("periodicites");
                    $scope.getelements("ilots");

                    $scope.getelements("constituantpieces");
                    $scope.getelements("equipementpieces");
                    $scope.getelements("niveauappartements");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-locationvente") !== -1
                ) {
                    $scope.getelements("typecontrats");
                    $scope.getelements("ilots");
                    $scope.getelements("delaipreavis");
                    $scope.getelements("locataires");
                    $scope.getelements("villas");
                    $scope.getelements("periodicites");
                    $scope.dataPage["rappelpaiementloyers"] = [
                        {
                            id: "5",
                            libelle: "Le 05 de chaque mois",
                        },
                        {
                            id: "6",
                            libelle: "Le 06 de chaque mois",
                        },
                        {
                            id: "7",
                            libelle: "Le 07 de chaque mois",
                        },
                        {
                            id: "8",
                            libelle: "Le 08 de chaque mois",
                        },
                        {
                            id: "9",
                            libelle: "Le 09 de chaque mois",
                        },
                        {
                            id: "10",
                            libelle: "Le 10 de chaque mois",
                        },
                        {
                            id: "11",
                            libelle: "Le 11 de chaque mois",
                        },
                        {
                            id: "12",
                            libelle: "Le 12 de chaque mois",
                        },
                        {
                            id: "13",
                            libelle: "Le 13 de chaque mois",
                        },
                        {
                            id: "14",
                            libelle: "Le 14 de chaque mois",
                        },
                        {
                            id: "15",
                            libelle: "Le 15 de chaque mois",
                        },
                        {
                            id: "16",
                            libelle: "Le 16 de chaque mois",
                        },
                        {
                            id: "17",
                            libelle: "Le 17 de chaque mois",
                        },
                        {
                            id: "18",
                            libelle: "Le 18 de chaque mois",
                        },
                        {
                            id: "19",
                            libelle: "Le 19 de chaque mois",
                        },
                        {
                            id: "20",
                            libelle: "Le 20 de chaque mois",
                        },
                        {
                            id: "21",
                            libelle: "Le 21 de chaque mois",
                        },
                        {
                            id: "22",
                            libelle: "Le 22 de chaque mois",
                        },
                        {
                            id: "23",
                            libelle: "Le 23 de chaque mois",
                        },
                        {
                            id: "24",
                            libelle: "Le 24 de chaque mois",
                        },
                        {
                            id: "25",
                            libelle: "Le 25 de chaque mois",
                        },
                    ];
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-inbox") !== -1
                ) {
                    $scope.getelements("users");
                    $scope.$on("userLoaded", function () {
                        $scope.pageChanged("inbox");
                        // $scope.editInSelect2Costum('locataire', +$scope.infosUserConnected.locataire_id, 'inbox');
                        // console.log('val champ locataire : ',$("#locataire_inbox").val())
                    });
                    // $scope.getelements("locataires");
                    // $scope.getelements("appartements");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-detailscontrat") !== -1
                ) {
                    $scope.titlePage = "Détails contrat";
                    console.log(
                        "location ====-----++++ ",
                        current.params.itemId
                    );
                    $scope.getelements(
                        "contrats",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "id:" + current.params.itemId
                    );

                    $scope.getelements(
                        "paiementloyers",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "contrat_id:" + current.params.itemId
                    );
                    $scope.getelements(
                        "facturelocations",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "contrat_id:" + current.params.itemId
                    );
                    console.log(current.params.itemId, "id");
                    $scope.getelements(
                        "avenants",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "contrat_id:" + current.params.itemId
                    );
                    $scope.getelements(
                        "demanderesiliations",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "contrat_id:" + current.params.itemId
                    );

                    $scope.getelements("periodicites");
                    $scope.getelements("typefactures");
                    // $scope.getelements("locataires");
                    // $scope.getelements("appartements");
                    $scope.getelements("typepieces");
                    $scope.getelements("constituantpieces");
                    $scope.getelements("equipementpieces");
                    $scope.getelements("observations");
                    $scope.getelements("pieceappartements");
                    $scope.getelements("modepaiements");
                    $scope.getelements("periodes");
                    $scope.getelements("typecontrats");
                    $scope.getelements("typerenouvellements");
                    $scope.getelements("delaipreavis");
                    // $scope.getelements("proprietaires");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-entite") !== -1
                ) {
                    $scope.getelements("users");
                    $scope.getelements("activites");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-detailslocationvente") !== -1
                ) {
                    $scope.titlePage = "Détails contrat de location vente";
                    console.log(
                        "location ====-----++++ ",
                        current.params.itemId
                    );
                    $scope.getelements(
                        "locationventes",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "id:" + current.params.itemId
                    );

                    $scope.getelements(
                        "factureacomptes",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "contrat_id:" + current.params.itemId
                    );

                    $scope.pageChanged(
                        "avisecheance",
                        (optionals = {
                            justWriteUrl: null,
                            option: null,
                            saveStateOfFilters: false,
                        }),
                        current.params.itemId
                    );

                    $scope.getelements("periodicites");
                    $scope.getelements("typefactures");
                    $scope.getelements("locataires");
                    $scope.getelements("appartements");
                    $scope.getelements("villas");

                    $scope.getelements("typepieces");
                    $scope.getelements("constituantpieces");
                    $scope.getelements("equipementpieces");
                    $scope.getelements("observations");
                    $scope.getelements("pieceappartements");
                    $scope.getelements("modepaiements");
                    $scope.getelements("periodes");
                    $scope.getelements("typeapportponctuels");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-detailsdemanderesiliation") !== -1
                ) {
                    $scope.getelements(
                        "demanderesiliations",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "id:" + current.params.itemId
                    );
                    $scope.getelements(
                        "facturelocations",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "demanderesiliation_id:" +
                        current.params.itemId +
                        ",est_activer:" +
                        1
                    );

                    $scope.getelements(
                        "factureeauxs",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "demanderesiliation_id:" +
                        current.params.itemId +
                        ",est_activer:" +
                        1
                    );

                    $scope.getelements("appartements");
                    $scope.getelements("contrats");
                    $scope.getelements("locataires");
                    $scope.getelements("etatlieus");
                    $scope.getelements("typepieces");
                    $scope.getelements("constituantpieces");
                    $scope.getelements("equipementpieces");
                    $scope.getelements("observations");
                    $scope.getelements("pieceappartements");
                    $scope.getelements("categorieinterventions");
                    $scope.getelements("soustypeinterventions");
                    $scope.getelements("unites");
                    $scope.getelements("detaildevis");
                    $scope.getelements("categorieinterventions");
                    $scope.getelements("detaildevisdetails");
                    $scope.getelements("demandeinterventions");
                    $scope.getelements("interventions");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-echeanceencours") !== -1
                ) {
                    $scope.titlePage = "Détails & envoie des échéances encours";
                    $scope.getelements(
                        "avisecheances",
                        (optionals = {
                            queries: null,
                            typeIds: null,
                            otherFilters: null,
                        }),
                        "est_activer:" + 1
                    );
                    $scope.getelements("modepaiements");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-paiementecheance") !== -1
                ) {
                    $scope.titlePage = "Paiement Echeances";
                    console.log("mansour pouye  test");

                    $scope.getelements("modepaiements");
                    $scope.getelements("locataires");
                    $scope.getelements("ilots");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-apportponctuel") !== -1
                ) {
                    $scope.titlePage = "Apport Ponctuel";
                    $scope.getelements("typeapportponctuels");
                    $scope.getelements("locataires");
                    //$scope.getelements("contrats");
                    var rewriteReq = "locationventes";
                    Init.getElement(
                        rewriteReq,
                        "id,descriptif,locataire{nom,prenom}"
                    ).then(
                        function (data) {
                            if (data) {
                                $scope.dataPage["locationventes"] = data;
                            }
                        },
                        function (msg) {
                            toastr.error(msg);
                        }
                    );
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-typeapportponctuel") !== -1
                ) {
                    $scope.titlePage = "Type Apport Ponctuel";
                    $scope.getelements("typeapportponctuels");
                } else if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-contratproprietaire") !== -1
                ) {
                    $scope.titlePage = "Mandat Gerance";
                    // $scope.getelements("contratproprietaires");
                    $scope.getelements("entites");
                    // $scope.getelements("proprietaires");
                    $scope.getelements("modelcontrats");
                }

                $(".select2").on("select2:opening", function (e) {
                    var data = e.params.data;
                    console.log("New test");
                    $scope.cpt = 1; //1
                });
            } else if (
                $scope.currentTemplateUrl.toLowerCase().indexOf("dashboard") !==
                -1
            ) {

                $scope.$on("$viewContentLoaded", function () {
                    if (
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("dashboard") !== -1
                    ) {
                        $scope.titlePage = "Dashboard";
                        $scope.getelements("etatencaissements");
                        $scope.getelements("entites");
                        $scope.getelements("appartements");
                        $scope.getelements("immeubles");
                        $scope.getelements("locataires");
                        $scope.getelements("contrats");
                        $scope.getelements("users");
                    }
                });
            }
        });

        $scope.selectAll = false; // Initialize the "Select All" checkbox state

        $scope.toggleSelectAll = function () {
            angular.forEach($scope.dataPage["inboxs"], function (item) {
                item.selected = $scope.selectAll;
            });
        };

        $scope.updateSelectAll = function () {
            // Check if all individual checkboxes are selected
            var allSelected = true;
            angular.forEach($scope.dataPage["inboxs"], function (item) {
                if (!item.selected) {
                    allSelected = false;
                    return;
                }
            });

            $scope.selectAll = allSelected;
        };

        $scope.chargeQueriesByType = function (type, tag) {
            var rewriteReq = `${tag}(typage:"${type}")`;
            Init.getElement(rewriteReq, listofrequests_assoc[tag]).then(
                function (data) {
                    if (data) {
                        $scope.dataPage[tag] = data;
                    }
                },
                function (msg) {
                    toastr.error(msg);
                }
            );
        };

        $scope.getLogoApp = function () { };

        $scope.$on("$routeChangeSuccess", function (next, current, prev) {
            // A la fermeture du modal, on raffraichi
            $(document).on("hide.bs.modal", "#modal_addactivite", function (e) {
                alert("event fired");
            });
            $scope.isActiveTab = false;
            $scope.filters = "";

            if ($scope.firstime == true) {
                $scope.firstime = false;
            }

            $scope.getRoleByConnected();
            $scope.reInit();
        });

        $scope.getRoleByConnected = function () {
            var rewriteReq = "roles(connected_user:1)";
            Init.getElement(rewriteReq, listofrequests_assoc["roles"]).then(
                function (data) {
                    if (data && data.length > 0) {
                        $scope.dataPage["roles"] = data;
                    }
                },
                function (msg) {
                    toastr.error(msg);
                }
            );
        };


        $scope.checkPermision = function (perm) {
            if (
                perm &&
                $scope.dataPage &&
                $scope.dataPage.roles &&
                $scope.dataPage.roles.length > 0
            ) {
                return $scope.dataPage.roles.some(role =>
                    role.permissions.some(p => p.name === perm)
                );
            }
            return false;
        };

        $scope.generateExcel1 = function (type) {
            $scope.generateAddFiltres(type);
        };

        var interval = null;
        $scope.bill = {
            quantity: 0,
        };
        $scope.add = function (newVal) {
            console.log("Add");
            initInterval(newVal);
        };
        function initInterval(newVal) {
            if (!interval) {
                console.log("Interval start");
                interval = setInterval(function () {
                    $scope.$apply(function () {
                        $scope.bill.quantity += newVal;
                        if ($scope.bill.quantity >= 0) {
                            $("#nombre_couvert_commande").val(
                                $scope.bill.quantity
                            );
                        }
                    });
                }, 100);
            }
        }

        $scope.clearInterval = function () {
            console.log("Interval cleared");
            if (interval) {
                window.clearInterval(interval);
                interval = null;
            }
        };

        $scope.addListeCategorie = function (
            category,
            action = null,
            index = null
        ) {
            // partie qui ajoute une categorie  correctement implementer
            if (!category) {
                // Récupérer la catégorie sélectionnée
                var categorieintervention_id = $(
                    "#categorieintervention_detaildevis"
                ).val();

                if (categorieintervention_id) {
                    var objetCategorieintervention = {};

                    $scope.dataPage["categorieinterventions"].filter(function (
                        cat
                    ) {
                        if (cat.id == categorieintervention_id) {
                            objetCategorieintervention = cat;
                        }
                    });

                    // Vérifier si la catégorie existe déjà dans la liste  $scope.dataInTabPane["detaildevis_devis"]["data"]
                    var existingCategory = $scope.dataInTabPane[
                        "detaildevis_devis"
                    ]["data"].find(
                        (c) =>
                            c.categorieintervention_id ===
                            categorieintervention_id
                    );

                    // si la categorie n'existe pas dans la liste
                    if (!existingCategory) {
                        // Ajouter la catégorie à la liste
                        $scope.dataInTabPane["detaildevis_devis"]["data"].push({
                            categorieintervention_id: categorieintervention_id
                                ? categorieintervention_id
                                : null,
                            categorieintervention: objetCategorieintervention
                                ? objetCategorieintervention
                                : {},
                            subcategories: {
                                data: [],
                                dataElement: [],
                            },
                        });
                    } else {
                        $scope.showToast(
                            "",
                            "La catégorie existe déjà dans la liste.",
                            "error"
                        );
                    }
                } else {
                    $scope.showToast(
                        "",
                        "Veuillez sélectionner une catégorie.",
                        "error"
                    );
                }
            }

            // partie qui ajoute
            if (category && action == "add" && index != null) {
                // Récupérer la catégorie sélectionnée
                var indexcategorie =
                    $scope.dataInTabPane["detaildevis_devis"]["data"].indexOf(
                        category
                    );
                console.log($scope.dataInTabPane["detaildevis_devis"]["data"]);
                console.log(index, "index dans la sous ");
                var soustypeintervention_id = angular
                    .element(
                        document.getElementById(
                            "soustypeintervention_detaildevisdetails_" +
                            category.categorieintervention_id
                        )
                    )
                    .val();
                console.log(soustypeintervention_id, "debbog");

                if (soustypeintervention_id) {
                    var selectedSubcategory = $scope.dataPage[
                        "soustypeinterventions"
                    ].find(function (soustypeintervention) {
                        return (
                            soustypeintervention.id === soustypeintervention_id
                        );
                    });

                    console.log(selectedSubcategory, "debbbog2");

                    if (selectedSubcategory) {
                        $scope.dataInTabPane["detaildevis_devis"]["data"][
                            indexcategorie
                        ]["subcategories"]["data"].push(selectedSubcategory);

                        $scope.dataInTabPane["detaildevis_devis"]["data"][
                            indexcategorie
                        ]["subcategories"]["dataElement"].push({
                            subcategorie_id: selectedSubcategory.id,
                            prix: 0,
                            unite_id: 0,
                            quantite: 0,
                        });
                        console.log(
                            $scope.dataInTabPane["detaildevis_devis"]["data"][
                            indexcategorie
                            ]["subcategories"],
                            "test debbugs"
                        );
                    } else {
                        $scope.showToast(
                            "",
                            "Le sous-type d'intervention sélectionné est introuvable.",
                            "error"
                        );
                    }
                } else {
                    $scope.showToast(
                        "",
                        "Veuillez sélectionner un sous-type d'intervention.",
                        "error"
                    );
                }
                console.log($scope.dataInTabPane["detaildevis_devis"]["data"]);
            }

            // partie qui supprime une categorie
            if (category && action === "modifiercategorie" && index != null) {
                var indexcategorie =
                    $scope.dataInTabPane["detaildevis_devis"]["data"].indexOf(
                        category
                    );
                $scope.dataInTabPane["detaildevis_devis"]["data"].splice(
                    indexcategorie,
                    1
                );
            }
            // partie qui suppime  une sous categorie
            if (
                category &&
                action === "modifiersouscategorie" &&
                index != null
            ) {
                var indexcategorie =
                    $scope.dataInTabPane["detaildevis_devis"]["data"].indexOf(
                        category
                    );
                var indexsubcategories = $scope.dataInTabPane[
                    "detaildevis_devis"
                ]["data"][indexcategorie]["subcategories"]["data"].findIndex(
                    (c) => c.id == category.subcategories.data[index].id
                );
                $scope.dataInTabPane["detaildevis_devis"]["data"][
                    indexcategorie
                ]["subcategories"]["data"].splice(indexsubcategories, 1);
            }
        };

        $scope.currentIndex = 0; // Initialisez l'index
        console.log($scope.currentIndex, "current index");
        // ecouteur sur le changement de valeur ( quantité, prix, unité)

        $(document).ready(function () {
            $("#container").on("input", ".quantity-input", function () {
                var quantityValue = $(this).val();

                var categorieId = parseInt(
                    $(this).closest("li").find("p").data("categorie-id")
                );

                // recuprer l'element cliqué
                var element = $(this);
                var idAttribute = element.attr("id");
                var parts = idAttribute.split("_"); // Diviser la chaîne ID par le caractère "_"
                var index = parts[parts.length - 2]; // Récupérer la valeur avant le dernier "_"

                console.log("ID de la catégorie : " + index);

                var categorie = $scope.dataInTabPane["detaildevis_devis"][
                    "data"
                ].find((c) => c.categorieintervention_id == categorieId);

                var indexcategorie =
                    $scope.dataInTabPane["detaildevis_devis"]["data"].indexOf(
                        categorie
                    );

                var indexElement = $scope.dataInTabPane["detaildevis_devis"][
                    "data"
                ][indexcategorie]["subcategories"]["dataElement"].findIndex(
                    (c) =>
                        c.subcategorie_id ==
                        categorie.subcategories.data[index].id
                );

                if (indexElement != -1) {
                    $scope.dataInTabPane["detaildevis_devis"]["data"][
                        indexcategorie
                    ]["subcategories"]["dataElement"][indexElement].quantite =
                        quantityValue;
                }
            });

            $("#container").on("input", ".price-input", function () {
                var priceValue = $(this).val();
                var categorieId = parseInt(
                    $(this).closest("li").find("p").data("categorie-id")
                );
                var element = $(this);
                var idAttribute = element.attr("id");
                var parts = idAttribute.split("_"); // Diviser la chaîne ID par le caractère "_"
                var index = parts[parts.length - 2]; // Récupérer la valeur avant le dernier "_"
                console.log("ID de la catégorie : " + index);

                console.log($scope.currentIndex, "current index");

                console.log("ID de la catégorie : " + categorieId);

                var categorie = $scope.dataInTabPane["detaildevis_devis"][
                    "data"
                ].find((c) => c.categorieintervention_id == categorieId);
                var indexcategorie =
                    $scope.dataInTabPane["detaildevis_devis"]["data"].indexOf(
                        categorie
                    );
                var indexElement = $scope.dataInTabPane["detaildevis_devis"][
                    "data"
                ][indexcategorie]["subcategories"]["dataElement"].findIndex(
                    (c) =>
                        c.subcategorie_id ==
                        categorie.subcategories.data[index].id
                );

                if (indexElement != -1) {
                    $scope.dataInTabPane["detaildevis_devis"]["data"][
                        indexcategorie
                    ]["subcategories"]["dataElement"][indexElement].prix =
                        priceValue;
                }
                console.log(
                    $scope.dataInTabPane["detaildevis_devis"]["data"][
                    indexcategorie
                    ]["subcategories"]["dataElement"]
                );
            });

            $("#container").on("change", ".unit-select", function () {
                var unitValue = $(this).val();

                var categorieId = parseInt(
                    $(this).closest("li").find("p").data("categorie-id")
                );
                // recuprer l'element cliqué
                var element = $(this);
                var idAttribute = element.attr("id");
                var parts = idAttribute.split("_"); // Diviser la chaîne ID par le caractère "_"
                var index = parts[parts.length - 2]; // Récupérer la valeur avant le dernier "_"
                console.log("ID de la catégorie : " + index);
                var categorie = $scope.dataInTabPane["detaildevis_devis"][
                    "data"
                ].find((c) => c.categorieintervention_id == categorieId);
                var indexcategorie =
                    $scope.dataInTabPane["detaildevis_devis"]["data"].indexOf(
                        categorie
                    );

                // dans dataElement rechercher l'index de l'element avec l'id categorieId subcategorie_id : category.subcategories.data[index].id,
                var indexElement = $scope.dataInTabPane["detaildevis_devis"][
                    "data"
                ][indexcategorie]["subcategories"]["dataElement"].findIndex(
                    (c) =>
                        c.subcategorie_id ==
                        categorie.subcategories.data[index].id
                );

                if (indexElement != -1) {
                    $scope.dataInTabPane["detaildevis_devis"]["data"][
                        indexcategorie
                    ]["subcategories"]["dataElement"][indexElement].unite_id =
                        unitValue;
                }
                console.log(
                    $scope.dataInTabPane["detaildevis_devis"]["data"][
                    indexcategorie
                    ]["subcategories"]["dataElement"]
                );
            });
        });

        $scope.incrementerNumber = function (tagForm, signe) {
            var number = $("#" + tagForm).val();
            if (!number) {
                number = 0;
            }
            number = +number;
            if (signe > 0) {
                number++;
            } else {
                if (number > 0) {
                    number--;
                }
            }

            $("#" + tagForm).val(number);
        };

        //--DEBUT => Fonction mise à jour--//
        //markme-MODIFICATION
        $scope.isContratUpdate = false;
        $scope.showModalUpdate = function (
            type,
            itemId,
            optionals = {
                forceChangeForm: false,
                isClone: false,
                transformToType: null,
                itemIdForeign: null,
            },
            detail = false
        ) {
            $scope.detail = detail;
            console.log("in", itemId, type);
            if (
                $scope.currentTemplateUrl.indexOf("list-user") == -1 &&
                type == "user"
            ) {
                $scope.reInit(type);
            }

            var formatId = "id";
            var listeattributs_filter = [];
            var listeattributs = listofrequests_assoc[type + "s"];

            $scope.redirectPdf = function (identifiant) {
                window.open(`${identifiant}`, "_blank");
            };

            reqwrite = type + "s" + "(" + formatId + ":" + itemId + ")";

            if (optionals.transformToType) {
                tmpType = type;
                type = optionals.transformToType;
            }

            $scope.showModalAdd(type, { fromUpdate: true });
            $scope.update = true;

            Init.getElement(
                reqwrite,
                listeattributs,
                listeattributs_filter
            ).then(
                function (data) {
                    console.log(data);
                    var item = data[0];
                    $scope.item_update = item;

                    if (!optionals.isClone && !optionals.transformToType) {
                        $("#id_" + type).val(item.id);
                    }

                    $("#modal_add" + type).blockUI_start();

                    if (type.indexOf("factureintervention") !== -1) {
                        $("#locataireintervention_" + type)
                            .val(item.locataire.id)
                            .trigger("change");
                        $("#intervenantassocieintervention_" + type).val(
                            item.intervenantassocie
                        );
                        $("#datefactureintervention_" + type).val(
                            item.datefacture
                        );
                        $("#demandeinterventiondetail_" + type)
                            .val(item.demandeintervention.id)
                            .trigger("change");
                        $scope.dataInTabPane[
                            "factureintervention_intervention_factureintervention"
                        ]["data"] = item.detailfactureinterventions;
                    }
                    if (type.indexOf("inbox") !== -1) {
                        // update inboxs details
                        $("#locataire_" + type).prop("disabled", true);
                        $("#subject_" + type).prop("disabled", true);
                        $("#body_" + type).prop("disabled", true);
                        $(".allfilesinbox").hide();

                        $("#locataire_" + type)
                            .val(item.locataire.id)
                            .trigger("change");
                        $("#appartement_" + type)
                            .val(item.appartement.id)
                            .trigger("change");
                        $("#subject_" + type).val(item.subject);
                        $("#body_" + type).val(item.body);
                        // $('#senderemail_'+type).val(item.sender_email);
                        // $('#demandeinterventiondetail_' + type).val(item.demandeintervention.id).trigger("change");
                    }
                    if (type.indexOf("avenant") !== -1) {
                        // update inboxs details
                        $("#id_contrat_avenant").val(item.contrat_id);
                        $("#periodicite_" + type)
                            .val(item.periodicite.id)
                            .trigger("change");

                        $("#typecontrat_" + type)
                            .val(item.typecontrat.id)
                            .trigger("change");
                        $("#typerenouvellement_" + type)
                            .val(item.typerenouvellement.id)
                            .trigger("change");
                        $("#delaipreavi_" + type)
                            .val(item.typerenouvellement.id)
                            .trigger("change");
                        $("#dateecheance_" + type).val(item.dateecheance);
                        $("#date_" + type).val(item.dateenregistrement);
                        $("#frequencerevision_" + type).val(
                            item.frequencerevision
                        );
                        $("#tauxrevision_" + type).val(item.tauxrevision);
                        $("#montantcharge_" + type).val(item.montantcharge);
                        $("#montantloyer_" + type).val(item.montantloyer);
                        $("#montantloyerbase_" + type).val(
                            item.montantloyerbase
                        );
                        $("#montantloyertom_" + type).val(item.montantloyertom);
                    }
                    if (
                        type.indexOf("devi") !== -1 &&
                        $scope.currentTemplateUrl.indexOf("list-etatlieu") !==
                        -1
                    ) {
                        //update_devis
                        console.log("devis to update");
                        console.log(item);
                        console.log(itemId);
                        $("#objet_demandeintervention")
                            .val(item.object)
                            .trigger("change");
                        $("#date_demandeintervention")
                            .val(item.date)
                            .trigger("change");
                        $("#demandeintervention_id")
                            .val(item.demandeintervention_id)
                            .trigger("change");
                        $("#id_devi").val(item.id).trigger("change");
                        // affection des valeurs des champs du formulaire de devis au  dataInTabPane['detaildevis_devis']['data']

                        $scope.dataInTabPane["detaildevis_devis"]["data"] = [];

                        item.detaildevis.forEach((element) => {
                            console.log(element, "element");
                            element.detaildevisdetails.forEach(
                                (detaildevisdetail, index) => {
                                    console.log(detaildevisdetail);

                                    const existingCategory =
                                        $scope.dataInTabPane[
                                            "detaildevis_devis"
                                        ]["data"].find(
                                            (c) =>
                                                c.categorieintervention_id ===
                                                detaildevisdetail.detaildevi
                                                    .categorieintervention.id
                                        );

                                    if (existingCategory) {
                                        console.log("existe");
                                        const dataElement = {
                                            subcategorie_id:
                                                detaildevisdetail
                                                    .soustypeintervention.id,
                                            prix: detaildevisdetail.prixunitaire,
                                            unite_id:
                                                detaildevisdetail.unite.id,
                                            quantite:
                                                detaildevisdetail.quantite,
                                        };

                                        existingCategory.subcategories.data.push(
                                            detaildevisdetail.soustypeintervention
                                        );
                                        existingCategory.subcategories.dataElement.push(
                                            dataElement
                                        );
                                    } else {
                                        console.log(detaildevisdetail, "sds");

                                        const dataElement = {
                                            subcategorie_id:
                                                detaildevisdetail
                                                    .soustypeintervention.id,
                                            prix: detaildevisdetail.prixunitaire,
                                            unite_id:
                                                detaildevisdetail.unite.id,
                                            quantite:
                                                detaildevisdetail.quantite,
                                        };

                                        const subcategories = {
                                            data: [
                                                detaildevisdetail.soustypeintervention,
                                            ],

                                            dataElement: [dataElement],
                                        };

                                        const newCategory = {
                                            devi_id:
                                                detaildevisdetail.detaildevi.id,
                                            categorieintervention_id:
                                                detaildevisdetail.detaildevi
                                                    .categorieintervention.id,
                                            categorieintervention:
                                                detaildevisdetail.detaildevi
                                                    .categorieintervention,
                                            subcategories: subcategories,
                                        };

                                        $scope.dataInTabPane[
                                            "detaildevis_devis"
                                        ]["data"].push(newCategory);
                                    }

                                    $(document).ready(function () {
                                        // Assurez-vous que les éléments existent dans le DOM avant de les sélectionner
                                        var inputSelector =
                                            "#quantite_detaildevisdetails_" +
                                            index +
                                            "_" +
                                            detaildevisdetail.detaildevi
                                                .categorieintervention.id;
                                        var prix =
                                            "#puhtva_detaildevisdetails_" +
                                            index +
                                            "_" +
                                            detaildevisdetail.detaildevi
                                                .categorieintervention.id;
                                        var unite =
                                            "#unite_detaildevisdetails_" +
                                            index +
                                            "_" +
                                            detaildevisdetail.detaildevi
                                                .categorieintervention.id;
                                        var valider =
                                            "#valider_detaildevisdetails_" +
                                            index +
                                            "_" +
                                            detaildevisdetail.detaildevi
                                                .categorieintervention.id;

                                        var inputElement = $(inputSelector);
                                        var prixElement = $(prix);
                                        var uniteElement = $(unite);
                                        var validerElement = $(valider);

                                        if (inputElement.length > 0) {
                                            inputElement
                                                .val(detaildevisdetail.quantite)
                                                .trigger("change");
                                            prixElement
                                                .val(
                                                    detaildevisdetail.prixunitaire
                                                )
                                                .trigger("change");
                                            uniteElement
                                                .val(detaildevisdetail.unite.id)
                                                .trigger("change");
                                            validerElement.prop(
                                                "checked",
                                                true
                                            );
                                        } else {
                                            console.log(
                                                "L'élément n'a pas été trouvé dans le DOM."
                                            );
                                        }
                                    });
                                }
                            );
                        });
                    }

                    // update devi form si la page est  list-demandeintervention et le modal est devi
                    if (
                        type.indexOf("devi") !== -1 &&
                        $scope.currentTemplateUrl.indexOf(
                            "list-demandeintervention"
                        ) !== -1
                    ) {
                        //update_devis
                        console.log("devis to update");
                        console.log(item);
                        console.log(itemId);

                        $("#objet_demandeintervention")
                            .val(item.object)
                            .trigger("change");
                        $("#date_demandeintervention")
                            .val(item.date)
                            .trigger("change");
                        $("#demandeintervention_id")
                            .val(item.demandeintervention_id)
                            .trigger("change");
                        $("#id_devi").val(item.id).trigger("change");
                        // affection des valeurs des champs du formulaire de devis au  dataInTabPane['detaildevis_devis']['data']

                        $scope.dataInTabPane["detaildevis_devis"]["data"] = [];

                        item.detaildevis.forEach((element) => {
                            console.log(element, "element");
                            element.detaildevisdetails.forEach(
                                (detaildevisdetail, index) => {
                                    console.log(detaildevisdetail);
                                    const existingCategory =
                                        $scope.dataInTabPane[
                                            "detaildevis_devis"
                                        ]["data"].find(
                                            (c) =>
                                                c.categorieintervention_id ===
                                                detaildevisdetail.detaildevi
                                                    .categorieintervention.id
                                        );

                                    if (existingCategory) {
                                        console.log("existe");
                                        const dataElement = {
                                            subcategorie_id:
                                                detaildevisdetail
                                                    .soustypeintervention.id,
                                            prix: detaildevisdetail.prixunitaire,
                                            unite_id:
                                                detaildevisdetail.unite.id,
                                            quantite:
                                                detaildevisdetail.quantite,
                                        };

                                        existingCategory.subcategories.data.push(
                                            detaildevisdetail.soustypeintervention
                                        );
                                        existingCategory.subcategories.dataElement.push(
                                            dataElement
                                        );
                                    } else {
                                        console.log(detaildevisdetail, "sds");

                                        const dataElement = {
                                            subcategorie_id:
                                                detaildevisdetail
                                                    .soustypeintervention.id,
                                            prix: detaildevisdetail.prixunitaire,
                                            unite_id:
                                                detaildevisdetail.unite.id,
                                            quantite:
                                                detaildevisdetail.quantite,
                                        };

                                        const subcategories = {
                                            data: [
                                                detaildevisdetail.soustypeintervention,
                                            ],

                                            dataElement: [dataElement],
                                        };

                                        const newCategory = {
                                            devi_id:
                                                detaildevisdetail.detaildevi.id,
                                            categorieintervention_id:
                                                detaildevisdetail.detaildevi
                                                    .categorieintervention.id,
                                            categorieintervention:
                                                detaildevisdetail.detaildevi
                                                    .categorieintervention,
                                            subcategories: subcategories,
                                        };

                                        $scope.dataInTabPane[
                                            "detaildevis_devis"
                                        ]["data"].push(newCategory);
                                    }

                                    $(document).ready(function () {
                                        // Assurez-vous que les éléments existent dans le DOM avant de les sélectionner
                                        var inputSelector =
                                            "#quantite_detaildevisdetails_" +
                                            index +
                                            "_" +
                                            detaildevisdetail.detaildevi
                                                .categorieintervention.id;
                                        var prix =
                                            "#puhtva_detaildevisdetails_" +
                                            index +
                                            "_" +
                                            detaildevisdetail.detaildevi
                                                .categorieintervention.id;
                                        var unite =
                                            "#unite_detaildevisdetails_" +
                                            index +
                                            "_" +
                                            detaildevisdetail.detaildevi
                                                .categorieintervention.id;
                                        var valider =
                                            "#valider_detaildevisdetails_" +
                                            index +
                                            "_" +
                                            detaildevisdetail.detaildevi
                                                .categorieintervention.id;

                                        var inputElement = $(inputSelector);
                                        var prixElement = $(prix);
                                        var uniteElement = $(unite);
                                        var validerElement = $(valider);

                                        if (inputElement.length > 0) {
                                            inputElement
                                                .val(detaildevisdetail.quantite)
                                                .trigger("change");
                                            prixElement
                                                .val(
                                                    detaildevisdetail.prixunitaire
                                                )
                                                .trigger("change");
                                            uniteElement
                                                .val(detaildevisdetail.unite.id)
                                                .trigger("change");
                                            validerElement.prop(
                                                "checked",
                                                true
                                            );
                                            console.log(
                                                inputElement,
                                                "jQuery selector"
                                            );
                                            console.log(
                                                $scope.dataInTabPane[
                                                "detaildevis_devis"
                                                ]["data"]
                                            );
                                        } else {
                                            console.log(
                                                "L'élément n'a pas été trouvé dans le DOM."
                                            );
                                        }
                                    });
                                }
                            );
                        });
                    }

                    if (type.indexOf("paiementintervention") !== -1) {
                        $scope.dataPage["factureinterventions"].forEach(
                            (elmt) => {
                                if (
                                    elmt.paiementinterventions[0].id == itemId
                                ) {
                                    $(
                                        "#id_paiementintervention_factureintervention"
                                    )
                                        .val(elmt.id)
                                        .trigger("change");
                                    $("#locataire_paiementintervention")
                                        .val(elmt.locataire.id)
                                        .trigger("change");
                                    $("#date_paiementintervention")
                                        .val(elmt.paiementinterventions[0].date)
                                        .trigger("change");
                                    $("#appartement_paiementintervention")
                                        .val(elmt.appartement.id)
                                        .trigger("change");
                                }
                            }
                        );
                    }

                    // update facturelocation form

                    if (type.indexOf("facturelocation") !== -1) {
                        console.log(item);

                        if (item["contrat"]) {
                            $("#contrat_" + type)
                                .val(+item["contrat"].id)
                                .trigger("change");
                            $("#locataire_" + type)
                                .val(item["contrat"]["locataire"].id)
                                .trigger("change");
                            $("#typefacture_" + type)
                                .val(item["typefacture"].id)
                                .trigger("change");
                        }
                        $("#objetfacture_" + type)
                            .val(item.objetfacture)
                            .trigger("change");
                        $("#moiscaution_" + type)
                            .val(item.nbremoiscausion)
                            .trigger("change");
                        $("#datefacture_" + type)
                            .val(item.datefacture)
                            .trigger("change");
                    }

                    if (type.indexOf("user") !== -1) {
                        // update user
                        $("#name_" + type).val(item.name);
                        $("#email_" + type).val(item.email);
                        // setTimeout(function () {
                        $("#employe_" + type)
                            .val(item.employe_id)
                            .trigger("change");
                        if (item["entite"]) {
                            $("#entite_" + type)
                                .val(+item["entite"].id)
                                .change();
                        }
                        $("#role_" + type)
                            .val(
                                item.roles && item.roles.length > 0
                                    ? item.roles[0].id
                                    : null
                            )
                            .trigger("change");

                        // imguploadsignature
                        if (item.uploadsignature) {
                            console.log(
                                "imguploadsignature ",
                                item.uploadsignature
                            );
                            // $("#imguploadsignature").attr("src", `{{ asset('assets/images/upload.jpg') }}` );
                            $("#affimguploadsignature").attr(
                                "src",
                                `${item.uploadsignature}`
                            );
                        }

                        // var selectedValuesEntite = new Array();
                        // if (item.user_avec_entites) {
                        //     item.user_avec_entites.forEach((item) => {
                        //         selectedValuesEntite.push(item.entite_id);
                        //     });
                        // }
                        // var selectedValuesCaisse = new Array();
                        // if (item.user_caisses) {
                        //     item.user_caisses.forEach((item) => {
                        //         selectedValuesCaisse.push(item.caisse_id);
                        //     });
                        // }
                        // $('#entite_' + type).val(selectedValuesEntite).trigger('change');
                        // $('#caisse_' + type).val(selectedValuesCaisse).trigger('change');
                        // }, 1500);

                        var rewriteReq =
                            "userdepartements(user_id:" + item.id + ")";
                        Init.getElement(
                            rewriteReq,
                            listofrequests_assoc["userdepartements"]
                        ).then(
                            function (data) {
                                if (data && data.length > 0) {
                                    $scope.dataInTabPane[
                                        "user_departement_user"
                                    ]["data"] = data;
                                }
                            },
                            function (msg) {
                                toastr.error(msg);
                            }
                        );
                    }
                    if (type.indexOf("locationvente") !== -1) {
                        // update locationvente / ridwan
                        console.log("document: " + $scope.item_update.document);
                        console.log(
                            "scanpreavis: " + $scope.item_update.scanpreavis
                        );
                        $scope.hideButton = false;
                        $scope.isContratUpdate = true;
                        $scope.deleteDocument = function (document) {
                            if (document === "document") {
                                $("#document_" + type).val(null);
                                $scope.item_update.document = null;
                                console.log($("#document_" + type).val());
                            } else if (document === "scanpreavis") {
                                console.log($("#scanpreavis_" + type).val());
                                $("#scanpreavis_" + type).val("");
                                $scope.item_update.scanpreavis = "";
                                console.log($("#scanpreavis_" + type).val());
                            }
                        };
                        if (item.descriptif) {
                            //  $("#descriptif_" + type).val(item.descriptif);
                        }

                        // $("#appartement_" + type)
                        //     .val(item.appartement.id)
                        //     .change();

                        if (item["appartement"]) {
                            $("#appartement_" + type).append(
                                '<option value="' +
                                item["appartement"].id +
                                '" class="appartement_append" selected>' +
                                "lot N°" +
                                item["appartement"].lot +
                                "</option>"
                            );
                        }

                        if (item.periodicite_id) {
                            $("#periodicite_" + type)
                                .val(item.periodicite.id)
                                .trigger("change");
                        }

                        $("#prixvilla_" + type).val(item.prixvilla);
                        if (item.montantloyer) {
                            $("#montantloyer_" + type).val(item.montantloyer);
                        }
                        if (item.numerodossier) {
                            $("#numerodossier_" + type).val(item.numerodossier);
                        }
                        if (item.fraisdegestion) {
                            $("#fraisdegestion_" + type).val(
                                item.fraisdegestion
                            );
                        }
                        if (item.fraislocative) {
                            $("#fraislocative_" + type).val(item.fraislocative);
                        }
                        if (item.codepartamortissemnt) {
                            $("#codepartamortissemnt_" + type).val(
                                item.codepartamortissemnt
                            );
                        }

                        $("#acompteinitial_" + type).val(item.acompteinitial);
                        if (item.maturite) {
                            $("#maturite_" + type).val(item.maturite);
                        }

                        $("#indemnite_" + type).val(item.indemnite);
                        // if()
                        // $("#fraiscoutlocationvente_" + type).val(
                        //     item.fraiscoutlocationvente
                        // );
                        $("#apportinitial_" + type).val(item.apportinitial);
                        if (item.apportiponctuel) {
                            $("#apportiponctuel_" + type).val(
                                item.apportiponctuel
                            );
                        }
                        if (item.dureelocationvente) {
                            $("#dureelocationvente_" + type).val(
                                item.dureelocationvente
                            );
                        }

                        $("#clausepenale_" + type).val(item.clausepenale);
                        $("#datedebutcontrat_" + type).val(
                            item.datedebutcontrat
                        );
                        if (item.dateecheance) {
                            $("#dateecheance_" + type).val(item.dateecheance);
                        }
                        if (item.dateenregistrement) {
                            $("#dateenregistrement_" + type).val(
                                item.dateenregistrement
                            );
                        }

                        $("#dateremisecles_" + type).val(item.dateremisecles);
                        // $('#depotinitial_' + type).val(item.depot_initial);
                        $("#typecontrat_" + type)
                            .val(item.typecontrat.id)
                            .trigger("change");
                        if (item.delaipreavi) {
                            $("#delaipreavi_" + type)
                                .val(item.delaipreavi.id)
                                .trigger("change");
                        }

                        if (item["rappelpaiement"]) {
                            $("#rappelpaiement_" + type)
                                .val(item["rappelpaiement"])
                                .change();
                        }

                        $("#locataireexistant_" + type)
                            .val(item.locataire.id)
                            .trigger("change");

                        // if (item.est_copreuneur && (item.est_copreuneur == 1)) {

                        //     $("#est_copreuneur_locationvente").prop("checked" , true);
                        //       $(".displaycopreneurlvt").show();

                        var typeAvecS = "copreneurs";
                        rewriteReq =
                            typeAvecS +
                            "(locataire_id:" +
                            item.locataire.id +
                            ")";

                        Init.getElement(
                            rewriteReq,
                            listofrequests_assoc[typeAvecS]
                        ).then(
                            function (data) {
                                console.log("data : copreneurs  : ", data);
                                console.log(
                                    "data : copreneurs  : ",
                                    item.copreneur.id
                                );
                                $scope.copreneursData = data;
                                if (
                                    item.est_copreuneur &&
                                    item.est_copreuneur == 1
                                ) {
                                    $("#est_copreuneur_locationvente").prop(
                                        "checked",
                                        true
                                    );
                                    $(".displaycopreneurlvt").show();
                                    if (item.copreneur.id) {
                                        $scope.copreneursData.forEach(
                                            (element) => {
                                                console.log(element);
                                                if (
                                                    element.id ==
                                                    item.copreneur.id
                                                ) {
                                                    console.log(
                                                        "ici element ",
                                                        element
                                                    );
                                                    setTimeout(function () {
                                                        console.log(
                                                            "icici settima out "
                                                        );
                                                        $(
                                                            "#copreneur_locationvente"
                                                        )
                                                            .val(
                                                                item.copreneur
                                                                    .id
                                                            )
                                                            .trigger("change");
                                                    }, 2000); // Ret
                                                }
                                            }
                                        );
                                    }
                                }
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );

                        // if ($scope.copreneursData && $scope.copreneursData.length > 0) {
                        //     setTimeout(function() {
                        //         console.log("icici settima out ");
                        //         $("#copreneur_locationvente").val(item.copreneur.id).trigger("change");
                        //     }, 2000); // Retard de 1 seconde (ajustez si nécessaire)

                        // }
                        // if ($scope.dataPage['copreneurs'].length > 0) {
                        //     $("#copreneur_locationvente").val(item.copreneur.id).change();
                        // }
                        // }
                        console.log("annexes ", item.annexes);
                        $scope.dataInTabPane["contrat_annexes_contrat"][
                            "data"
                        ] = [];
                        if (item.annexes) {
                            console.log("annexes 2 ", item.annexes);

                            var data2 = item.annexes;
                            data2.forEach((elmt2) => {
                                $scope.dataInTabPane["contrat_annexes_contrat"][
                                    "data"
                                ].push({
                                    numero: elmt2.numero,
                                    nom: elmt2.filename,
                                    fichier: elmt2.filepath,
                                    id: elmt2.id,
                                });
                            });
                        } else {
                            console.log("annexes ici ", item.annexes);
                            $scope.dataInTabPane["contrat_annexes_contrat"][
                                "data"
                            ] = [];
                        }
                    }
                    if (type.indexOf("modepaiement") !== -1) {
                        $("#designation_" + type).val(item.designation);
                        $("#description_" + type).val(item.description);
                        $("#code_" + type).val(item.code);
                    }
                    if (type.indexOf("entite") !== -1) {
                        // update entite
                        $("#designation_" + type).val(item.designation);
                        $("#description_" + type).val(item.description);
                        $("#location_" + type)
                            .prop("checked", item.location)
                            .change();
                        $("#vente_" + type)
                            .prop("checked", item.vente)
                            .change();

                        if (item.nomcompletnotaire) {
                            $("#nomcompletnotaire_" + type).val(
                                item.nomcompletnotaire
                            );
                        }
                        if (item.adressenotaire) {
                            $("#adressenotaire_" + type).val(
                                item.adressenotaire
                            );
                        }
                        if (item.adresseetudenotaire) {
                            $("#adresseetudenotaire_" + type).val(
                                item.adresseetudenotaire
                            );
                        }

                        if (item.emailnotaire) {
                            $("#emailnotaire_" + type).val(item.emailnotaire);
                        }
                        if (item.telephone1notaire) {
                            $("#telephone1notaire_" + type).val(
                                item.telephone1notaire
                            );
                        }
                        if (item.nometudenotaire) {
                            $("#nometudenotaire_" + type).val(
                                item.nometudenotaire
                            );
                        }
                        if (item.emailetudenotaire) {
                            $("#emailetudenotaire_" + type).val(
                                item.emailetudenotaire
                            );
                        }
                        if (item.telephoneetudenotaire) {
                            $("#telephoneetudenotaire_" + type).val(
                                item.telephoneetudenotaire
                            );
                        }
                        if (item.assistantetudenotaire) {
                            $("#assistantetudenotaire_" + type).val(
                                item.assistantetudenotaire
                            );
                        }

                        if (item.gestionnaire_id) {
                            $("#gestionnaire_" + type)
                                .val(item.gestionnaire_id)
                                .trigger("change");
                        }

                        if (item.entiteusers) {
                            var periodes = item.entiteusers;
                            var newArr = [];

                            $.each(periodes, function (key, value) {
                                newArr.push(value["user_id"]);
                            });

                            $("#equipes_entite").val(newArr).trigger("change");
                        }
                        if (item.infobancaires) {
                            var infobancaires = item.infobancaires;
                            $scope.dataInTabPane["info_bancaires_entite"][
                                "data"
                            ] = infobancaires;
                        }
                    }
                    if (type.indexOf("secteuractivite") !== -1) {
                        // update secteuractivite
                        $("#designation_" + type).val(item.designation);
                        $("#description_" + type).val(item.description);
                    }
                    if (type.indexOf("ilot") !== -1) {
                        // update ilot
                        $("#numero_" + type).val(item.numero);
                        $("#adresse_" + type).val(item.adresse);

                        $("#numerotitrefoncier_" + type).val(
                            item.numerotitrefoncier
                        );
                        $("#datetitrefoncier_" + type).val(
                            item.datetitrefoncier
                        );
                        $("#adressetitrefoncier_" + type).val(
                            item.adressetitrefoncier
                        );
                    }
                    if (type.indexOf("pieceappartement") !== -1) {
                        $("#designation_" + type).val(item.designation);
                        if (item["appartement"]) {
                            $("#appartement_" + type)
                                .val(+item["appartement"].id)
                                .change();
                        }
                    }
                    if (type.indexOf("typeassurance") !== -1) {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("horaire") !== -1) {
                        $("#designation_" + type).val(item.designation);
                        $("#debut_" + type).val(item.debut);
                        $("#fin_" + type).val(item.fin);
                    }
                    if (type == "categorieprestataire") {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type == "categorieintervention") {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("questionnaire") !== -1) {
                        $("#designation_" + type).val(item.designation);
                        if (item["typequestionnaire"]) {
                            $("#typequestionnaire_" + type)
                                .val(+item["typequestionnaire"].id)
                                .change();
                        }
                    }
                    if (type.indexOf("membreequipegestion") !== -1) {
                        $("#nom_" + type).val(item.nom);
                        $("#prenom_" + type).val(item.prenom);
                        $("#email_" + type).val(item.email);
                        $("#telephone_" + type).val(item.telephone);
                    }
                    if (type.indexOf("equipegestion") !== -1) {
                        $("#designation_" + type).val(item.designation);

                        var identifiantImmeuble = 1;
                        $scope.dataPage["immeubles"].forEach((elmt) => {
                            item["immeubles"].forEach((immeuble) => {
                                if (elmt.id == immeuble.id) {
                                    $("#immeubleequipegestiondiv").append(
                                        $compile(
                                            '<div class="col-span-3 sm:col-span-3">\n' +
                                            '    <label for="categorietuto_typetuto">Choisissez un immeuble</label>\n' +
                                            '<div class="inline-block mt-4 relative w-full"><select class="block select2 mt-2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline required" id="immeuble' +
                                            identifiantImmeuble +
                                            '_equipegestion" name="immeuble' +
                                            identifiantImmeuble +
                                            '" ><option value="" class="required">immeuble</option></select></div>' +
                                            "  </div> "
                                        )($scope)
                                    );
                                    $(
                                        "#immeuble" +
                                        identifiantImmeuble +
                                        "_equipegestion"
                                    ).append(
                                        '<option selected value="' +
                                        immeuble.id +
                                        '">' +
                                        immeuble.nom +
                                        "</option>"
                                    );
                                }
                            });

                            identifiantImmeuble++;
                        });
                        $scope.reInit();
                    }
                    if (type == "prestataire") {
                        $("#nom_" + type).val(item.nom);
                        $("#adresse_" + type).val(item.adresse);
                        $("#email_" + type).val(item.email);
                        $("#telephone1_" + type).val(item.telephone1);
                        $("#telephone2_" + type).val(item.telephone2);
                        $("#categorieprestataire_" + type)
                            .val(+item.categorieprestataire.id)
                            .change();
                    }
                    if (type == "demandeintervention") {
                        $("#immeuble_" + type)
                            .val(+item["immeuble"].id)
                            .change();
                        if (item["appartement"]) {
                            $("#typeintervention_" + type)
                                .val(2)
                                .change();
                            document.getElementById(
                                "appartement_demandeintervention"
                            ).innerHTML =
                                "<option selected value=" +
                                item["appartement"].id +
                                ' class="required">' +
                                item["appartement"].nom +
                                "</option>";
                            if (item["locataire"].prenom) {
                                document.getElementById(
                                    "locataire_demandeintervention"
                                ).innerHTML =
                                    "<option selected value=" +
                                    item["locataire"].id +
                                    ' class="required">' +
                                    item["locataire"].prenom +
                                    " " +
                                    item["locataire"].nom +
                                    "</option>";
                            }
                            if (item["locataire"].nomentreprise) {
                                document.getElementById(
                                    "locataire_demandeintervention"
                                ).innerHTML =
                                    "<option selected value=" +
                                    item["locataire"].id +
                                    ' class="required">' +
                                    item["locataire"].nomentreprise +
                                    "</option>";
                            }
                        } else {
                            $("#typeintervention_" + type)
                                .val(1)
                                .change();
                            $("#typepiece_" + type)
                                .val(+item["typepiece"].id)
                                .change();
                        }
                        $("#designation_" + type).val(item.designation);

                        //  console.log(item.etat) ;
                        //   console.log($('#etat_' + type).val()) ;
                        if (item["membreequipegestion"]) {
                            $("#membreequipegestion_" + type)
                                .val(+item["membreequipegestion"].id)
                                .change();
                        }
                    }
                    if (type == "intervention") {
                        $("#demandeintervention_" + type)
                            .val(+item["demandeintervention"].id)
                            .change();
                        if (item["categorieintervention"]) {
                            $("#categorieintervention_" + type)
                                .val(+item["categorieintervention"].id)
                                .change();
                        }
                        //  console.log(item.etat) ;
                        $("#etat_" + type)
                            .val(item.etat)
                            .change();
                        //   console.log($('#etat_' + type).val()) ;
                        if (item["prestataire"]) {
                            $(".prestataireintervention").show();
                            $("#check_typeintervenantprestataire").prop(
                                "checked",
                                true
                            );
                            $("#check_typeintervenantemploye").prop(
                                "checked",
                                false
                            );
                            $("#prestataire_" + type)
                                .val(+item["prestataire"].id)
                                .change();
                        }
                        if (item["membreequipegestion"]) {
                            $(".employeintervention").show();
                            $("#check_typeintervenantemploye").prop(
                                "checked",
                                true
                            );
                            $("#check_typeintervenantprestataire").prop(
                                "checked",
                                false
                            );
                            $("#membreequipegestion_" + type)
                                .val(+item["membreequipegestion"].id)
                                .change();
                        }
                        $("#descriptif_" + type).val(item.descriptif);
                        $("#dateintervention_" + type).val(
                            item.dateintervention
                        );
                    }

                    if (type.indexOf("equipegestion") !== -1) {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("contrat") !== -1) {
                        // update_contrat
                        $scope.hideButton = false;
                        $scope.isContratUpdate = true;
                        $scope.deleteDocument = function (document) {
                            if (document === "document") {
                                $("#document_" + type).val(null);
                                $scope.item_update.document = null;
                                console.log($("#document_" + type).val());
                            } else if (document === "documentrecucaution") {
                                console.log(
                                    $("#documentrecucaution_" + type).val()
                                );
                                $("#documentrecucaution_" + type).val("");
                                $scope.item_update.documentrecucaution = "";
                                console.log(
                                    $("#documentrecucaution_" + type).val()
                                );
                            } else if (document === "documentretourcaution") {
                                $("#documentretourcaution_" + type).val("");
                                $scope.item_update.documentretourcaution = "";
                                console.log(
                                    $("#documentretourcaution_" + type).val()
                                );
                            } else if (document === "scanpreavis") {
                                console.log($("#scanpreavis_" + type).val());
                                $("#scanpreavis_" + type).val("");
                                $scope.item_update.scanpreavis = "";
                                console.log($("#scanpreavis_" + type).val());
                            }
                        };

                        $scope.montantloyerBase = parseFloat(item.montantloyerbase) || 0;
                        $scope.montantloyerTom = parseFloat(item.montantloyertom) || 0;
                        $scope.montantCharge = parseFloat(item.montantcharge) || 0;
                        $("#descriptif_" + type).val(item.descriptif);
                        $("#montantloyer_" + type).val(item.montantloyer);
                        $("#montantloyerbase_" + type).val(
                            item.montantloyerbase
                        );
                        $("#dateenregistrement_" + type).val(
                            item.dateenregistrement
                        );
                        $("#datedebutcontrat_" + type).val(
                            item.datedebutcontrat
                        );
                        $("#daterenouvellement_" + type).val(
                            item.daterenouvellement
                        );
                        $("#datepremierpaiement_" + type).val(
                            item.datepremierpaiement
                        );
                        $("#montantloyertom_" + type).val(item.montantloyertom);
                        $("#montantcharge_" + type).val(item.montantcharge);
                        $("#tauxrevision_" + type).val(item.tauxrevision);
                        if (item.dateecheance) {
                            $("#dateecheance_" + type).val(item.dateecheance);
                        }

                        $("#frequencerevision_" + type).val(
                            item.frequencerevision
                        );
                        $("#dateretourcaution_" + type).val(
                            item.dateretourcaution
                        );
                        if (item.periodicite) {
                            $("#periodicite_" + type)
                                .val(item.periodicite.id)
                                .trigger("change");
                            $("#periodicite_" + type)
                                .val(item.periodicite.id)
                                .trigger("change");
                        }

                        console.log(item);
                        if (item["caution"]) {
                            // $('#documentcaution_' + type).val(item['caution'].document);
                            console.log(
                                "caution doc " + item["caution"].document
                            );
                            $("#montantcaution_" + type).val(
                                item["caution"].montantcaution
                            );
                            $("#dateversement_" + type).val(
                                item["caution"].dateversement
                            );
                            $("#caution_document_contrat").show();
                        }

                        console.log("TESTE " + $("#appartement_contrat"));

                        //   console.log("contrat details") ;
                        console.log(data);
                        if (item["rappelpaiement"]) {
                            //    console.log("je teste"+item['rappelpaiement'])
                            $("#rappelpaiement_" + type)
                                .val(item["rappelpaiement"])
                                .change();
                        }
                        if (item["typerenouvellement"]) {
                            $("#typerenouvellement_" + type)
                                .val(+item["typerenouvellement"].id)
                                .change();
                        }
                        if (item["caution"]) {
                            $("#caution_" + type)
                                .val(+item["caution"].id)
                                .change();
                        }
                        if (item["demanderesiliations"]) {
                            $("#demanderesiliations_" + type)
                                .val(+item["demanderesiliations"].id)
                                .change();
                        }
                        if (item["typecontrat"]) {
                            $("#typecontrat_" + type)
                                .val(+item["typecontrat"].id)
                                .change();
                        }
                        if (item["appartement"]) {
                            $("#appartement_" + type).append(
                                '<option value="' +
                                item["appartement"].id +
                                '" class="appartement_append" selected>' +
                                item["appartement"].nom +
                                "</option>"
                            );
                        }
                        if (item["delaipreavi"]) {
                            $("#delaipreavi_" + type)
                                .val(+item["delaipreavi"].id)
                                .change();
                        }

                        if (item["locataire"]) {
                            $("#check_typelocataire").attr(
                                "checked",
                                "checked"
                            );
                            $(".locataireexistant").show();
                            $("#locataire_" + type)
                                .val(+item["locataire"].id)
                                .change();
                            //Edit infos beneficiaire
                            console.log(
                                "Voici beneficiare",
                                item.nomcompletbeneficiaire
                            );

                            var typeAvecS = "locataires";
                            rewriteReq =
                                typeAvecS + "(id:" + item["locataire"].id + ")";

                            Init.getElement(
                                rewriteReq,
                                listofrequests_assoc[typeAvecS]
                            ).then(
                                function (data) {
                                    $scope.rappelLocataireData = data[0];
                                    setTimeout(function () {
                                        $("#nomcompletbeneficiaire_" + type)
                                            .val(item.nomcompletbeneficiaire)
                                            .change();
                                        $("#telephonebeneficiaire_" + type)
                                            .val(item.telephonebeneficiaire)
                                            .change();
                                        $("#emailbeneficiaire_" + type)
                                            .val(item.emailbeneficiaire)
                                            .change();
                                    }, 200);
                                },
                                function (msg) {
                                    $scope.showToast("", msg, "error");
                                }
                            );
                        }
                        $scope.dataInTabPane["contrat_annexesreyhan_contrat"][
                            "data"
                        ] = [];
                        if (item.annexes) {
                            console.log("annexes 2 ", item.annexes);

                            var data2 = item.annexes;
                            data2.forEach((elmt2) => {
                                $scope.dataInTabPane[
                                    "contrat_annexesreyhan_contrat"
                                ]["data"].push({
                                    numero: elmt2.numero,
                                    nom: elmt2.filename,
                                    fichier: elmt2.filepath,
                                    id: elmt2.id,
                                });
                            });
                        } else {
                            console.log("annexes ici ", item.annexes);
                            $scope.dataInTabPane[
                                "contrat_annexesreyhan_contrat"
                            ]["data"] = [];
                        }

                        $scope.montantLoyerFinal();
                    }
                    if (type == "appartement") {
                        //update appartemnet
                        $scope.reInit("villa");
                        $(".divapp").show();
                        $(".photoappartementdivappend").remove();
                        $(".pieceappartementdivappend").remove();

                        console.log(item);
                        var Idvalue = item["typeappartement"].id;

                        var typeAvecS = "typeappartement_pieces";
                        rewriteReq =
                            typeAvecS + "(typeappartement_id:" + Idvalue + ")";
                        // console.log(rewriteReq) ;
                        Init.getElement(
                            rewriteReq,
                            listofrequests_assoc[typeAvecS]
                        ).then(
                            function (data) {
                                //  console.log("data", data);
                                $scope.detailspiece = data;
                                $scope.reInit("typeappartement_piece");
                                //    console.log($scope.detailspiece) ;
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );

                        var Idappartement = item.id;
                        var typeAvecSappimage = "imageappartements";
                        rewriteReqappimage =
                            typeAvecSappimage +
                            "(appartement_id:" +
                            Idappartement +
                            ")";
                        Init.getElement(
                            rewriteReqappimage,
                            listofrequests_assoc[typeAvecSappimage]
                        ).then(function (imgapp) {
                            $scope.compteurImage2 = 0;
                            imgapp.forEach((img) => {
                                // console.log('here we go') ;
                                if (img.image !== undefined) {
                                    //   console.log('here') ;
                                    $("#photoappartement").append(
                                        $compile(
                                            ' <div id="affimgappartementdiv_' +
                                            img.imagecompteur +
                                            '" class="divapp2 col-span-3 sm:col-span-3 md:col-span-3">\n' +
                                            '                            <div class="form-group text-center class-form">\n' +
                                            '                                <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->\n' +
                                            "                                <div>\n" +
                                            '                                    <label for="imgappartement_' +
                                            img.imagecompteur +
                                            '" class="cursor-pointer">\n' +
                                            '                                        <img id="affimgappartement_' +
                                            img.imagecompteur +
                                            '" alt="..." class="image-hover shadow" style="width: 300px;height: 300px;border-radius: 10%!important; margin: 0 auto">\n' +
                                            '                                        <div style="display: none;">\n' +
                                            '                                            <input type="file" accept=\'image/*\' id="imgappartement_' +
                                            img.imagecompteur +
                                            '" name="appartement_' +
                                            img.imagecompteur +
                                            '" onchange=\'Chargerimage(this.name)\' class="required">\n' +
                                            '                                            <input type="hidden" id="erase_imgappartement_' +
                                            img.imagecompteur +
                                            '" name="image_erase" value="">\n' +
                                            '                                            <input type="hidden" id="imgappartementupdate_' +
                                            img.imagecompteur +
                                            '" name="imgappartementupdatename_' +
                                            img.imagecompteur +
                                            '">\n' +
                                            "                                        </div>\n" +
                                            "                                    </label>\n" +
                                            "                                </div>\n" +
                                            '                                <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile(\'imgappartement_' +
                                            img.imagecompteur +
                                            "')\">\n" +
                                            '                                    <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>\n' +
                                            "                                </button>\n" +
                                            "                            </div>\n" +
                                            "                        </div>"
                                        )($scope)
                                    );

                                    $("#imgappartement_" + img.imagecompteur)
                                        .val("")
                                        .attr("required", false)
                                        .removeClass("required");
                                    console.log(
                                        $(
                                            "#imgappartement_" +
                                            img.imagecompteur
                                        )
                                    );
                                    $(
                                        "#affimgappartement_" +
                                        img.imagecompteur
                                    ).attr(
                                        "src",
                                        img.image ? img.image : imgupload
                                    );
                                    $(
                                        "#imgappartementupdate_" +
                                        img.imagecompteur
                                    ).val(img.image);
                                    console.log(img.image);
                                    console.log(
                                        $(
                                            "#imgappartementupdate_" +
                                            img.imagecompteur
                                        ).val()
                                    );
                                    $scope.compteurImage2++;
                                    console.log($scope.compteurImage2);
                                }
                            });

                            $("#compteurimage2_appartement").val(
                                $scope.compteurImage2
                            );
                            console.log($("#compteurimage2_appartement").val());
                        });

                        var Idvalue2 = item.id;
                        Init.getElement(
                            "detailcompositions(appartement_id:" +
                            Idvalue2 +
                            ")",
                            listofrequests_assoc["detailcompositions"]
                        ).then(
                            function (data2) {
                                console.log("data2", data2);
                                data2.forEach((elmt2) => {
                                    $scope.dataInTabPane[
                                        "typeappartement_piece_equipepementpiece_typeappartement_piece_appartement"
                                    ]["data"].push({
                                        detailId: 0,
                                        equipement_id: elmt2.equipement.id,
                                        equipement_text:
                                            elmt2.equipement.designation,
                                    });
                                });

                                console.log(
                                    $scope.dataInTabPane[
                                    "typeappartement_piece_equipepementpiece_typeappartement_piece_appartement"
                                    ]["data"]
                                );
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );
                        console.log(Idvalue2);
                        var typeAvecS2 = "compositions";
                        rewriteReq2 =
                            typeAvecS2 + "(appartement_id:" + Idvalue2 + ")";
                        Init.getElement(
                            rewriteReq2,
                            listofrequests_assoc[typeAvecS2]
                        ).then(
                            function (data) {
                                console.log("data", data);
                                $scope.compteurImage = 0;
                                data.forEach((comp) => {
                                    if (comp.niveauappartement_id) {
                                        $(
                                            "#niveaupiece_" +
                                            comp.typeappartement_piece_id +
                                            "_appartement"
                                        )
                                            .val(comp.niveauappartement_id)
                                            .trigger("change");
                                    }
                                    $(
                                        "#superficiecomposition_" +
                                        comp.typeappartement_piece_id +
                                        "_appartement"
                                    ).val(comp.superficie);
                                    var IdComp = comp.id;
                                    var typeAvecScomp = "imagecompositions";
                                    rewriteReqcomp =
                                        typeAvecScomp +
                                        "(composition_id:" +
                                        IdComp +
                                        ")";
                                    Init.getElement(
                                        rewriteReqcomp,
                                        listofrequests_assoc[typeAvecScomp]
                                    ).then(function (imgcomp) {
                                        imgcomp.forEach((img) => {
                                            if (img.image !== undefined) {
                                                $(
                                                    "#photopieceappartement" +
                                                    comp.typeappartement_piece_id
                                                ).append("");
                                                $(
                                                    "#photopieceappartement" +
                                                    comp.typeappartement_piece_id
                                                ).append(
                                                    $compile(
                                                        '<div class="col-span-3 sm:col-span-3 md:col-span-3">\n' +
                                                        '                                    <div class="form-group text-center class-form">\n' +
                                                        '                                        <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->\n' +
                                                        "                                        <div>\n" +
                                                        '                                            <label for="imgpieceimage_' +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur +
                                                        '" class="cursor-pointer">\n' +
                                                        '                                                <img id="affimgpieceimage_' +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur +
                                                        '" src="" alt="..." class="image-hover shadow" style="width: 200px;height: 200px;border-radius: 10%!important;margin: 0 auto">\n' +
                                                        '                                                <div style="display: none;">\n' +
                                                        '                                                    <input type="file" accept=\'image/*\' id="imgpieceimage_' +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur +
                                                        '" name="pieceimage_' +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur +
                                                        '" onchange=\'Chargerimage(this.name)\' class="required">\n' +
                                                        '                                                    <input type="hidden" id="erase_imgpieceimage_' +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur +
                                                        '" name="image_erase" value="">\n' +
                                                        '                                                    <input type="hidden" id="imgpieceimageupdate_' +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur +
                                                        '" name="imgpieceimageupdatename_' +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur +
                                                        '">\n' +
                                                        "\n" +
                                                        "                                                </div>\n" +
                                                        "                                            </label>\n" +
                                                        "                                        </div>\n" +
                                                        '                                        <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile(\'imgpieceimage_' +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur +
                                                        "')\">\n" +
                                                        '                                            <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>\n' +
                                                        "                                        </button>\n" +
                                                        "                                    </div>\n" +
                                                        "                                </div>"
                                                    )($scope)
                                                );

                                                $(
                                                    "#imgpieceimage_" +
                                                    comp.typeappartement_piece_id +
                                                    "_" +
                                                    img.imagecompteur
                                                )
                                                    .val("")
                                                    .attr("required", false)
                                                    .removeClass("required");
                                                console.log(
                                                    $(
                                                        "#imgpieceimage_" +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur
                                                    )
                                                );
                                                $(
                                                    "#affimgpieceimage_" +
                                                    comp
                                                        .typeappartement_piece
                                                        .id +
                                                    "_" +
                                                    img.imagecompteur
                                                ).attr(
                                                    "src",
                                                    img.image
                                                        ? img.image
                                                        : imgupload
                                                );
                                                $(
                                                    "#imgpieceimageupdate_" +
                                                    comp.typeappartement_piece_id +
                                                    "_" +
                                                    img.imagecompteur
                                                ).val(img.image);
                                                console.log(img.image);
                                                console.log(
                                                    $(
                                                        "#imgpieceimageupdate_" +
                                                        comp.typeappartement_piece_id +
                                                        "_" +
                                                        img.imagecompteur
                                                    ).val()
                                                );
                                                $scope.compteurImage++;
                                            }
                                            $("#compteurimage_appartement").val(
                                                $scope.compteurImage
                                            );
                                            console.log(
                                                $(
                                                    "#compteurimage_appartement"
                                                ).val()
                                            );
                                        });
                                    });
                                });
                                data.forEach((elmt) => {
                                    Init.getElement(
                                        "detailcompositions(composition_id:" +
                                        elmt.id +
                                        ")",
                                        listofrequests_assoc[
                                        "detailcompositions"
                                        ]
                                    ).then(
                                        function (data2) {
                                            console.log("data2", data2);
                                            data2.forEach((elmt2) => {
                                                $scope.dataInTabPane[
                                                    "typeappartement_piece_equipepementpiece_typeappartement_piece_appartement"
                                                ]["data"].push({
                                                    detailId:
                                                        elmt2.idDetailtypeappartement,
                                                    equipement_id:
                                                        elmt2.equipement.id,
                                                    equipement_text:
                                                        elmt2.equipement
                                                            .designation,
                                                });
                                            });

                                            console.log(
                                                $scope.dataInTabPane[
                                                "typeappartement_piece_equipepementpiece_typeappartement_piece_appartement"
                                                ]["data"]
                                            );
                                        },
                                        function (msg) {
                                            $scope.showToast("", msg, "error");
                                        }
                                    );
                                });
                                // $scope.detailspiece  = data;
                                // $scope.reInit("typeappartement_piece");
                                //    console.log($scope.detailspiece) ;
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );

                        $("#entite_" + type)
                            .val(item.entite.code)
                            .trigger("change");

                        if (item["position"]) {
                            $("#position_" + type).prop(
                                "checked",
                                item["position"]
                            );
                        }
                        // $("#entite_" + type).attr("disabled", "disabled");
                        if (item.entite.code == "SCI") {
                            console.log("entre ici //app ");
                            console.log("entre ici // " + type);
                            $(".2").hide();
                            $("#nom_" + type).val(item.nom);
                            if (item["immeuble"]) {
                                $("#immeuble_" + type)
                                    .val(+item["immeuble"].id)
                                    .change();
                            }
                            if (item["entite"]) {
                                // $("#entite_" + type)
                                //     .val(+item["entite"].id)
                                //     .change();
                            }
                            $("#niveau_" + type).val(item.niveau);
                            $("#superficie_" + type).val(item.superficie);
                            if (item["proprietaire"]) {
                                // $("#proprietaire_" + type)
                                //     .val(+item["proprietaire"].id)
                                //     .change();
                                $scope.editInSelect2Costum('proprietaire', +item["proprietaire"].id, type);

                            }
                            if (item["contratproprietaire"]) {
                                // $("#contratproprietaire_id_" + type)
                                //     .val(+item["contratproprietaire"].id)
                                //     .change();
                                $("#contratproprietaire_id_" + type).val(+item["contratproprietaire"].id).trigger("change");

                            }

                            if (item["commissionvaleur"]) {
                                $("#commissionvaleur_" + type)
                                    .val(+item["commissionvaleur"])
                                    .change();
                            }
                            if (item["commissionpourcentage"]) {
                                $("#commissionpourcentage_" + type)
                                    .val(+item["commissionpourcentage"])
                                    .change();
                            }
                            if (item["montantloyer"]) {
                                $("#montantloyer_" + type)
                                    .val(+item["montantloyer"])
                                    .change();
                            }
                            if (item["prixappartement"]) {
                                $("#prixappartement_" + type)
                                    .val(+item["prixappartement"])
                                    .change();
                            }

                            if (item["typevente"]) {
                                $("#typevente_" + type)
                                    .val(+item["typevente"])
                                    .change();
                            }
                            if (item["montantcaution"]) {
                                $("#montantcaution_" + type)
                                    .val(+item["montantcaution"])
                                    .change();
                            }
                            if (item["tva"]) {
                                $("#tva_" + type).prop("checked", item["tva"]);
                            }
                            if (item["brs"]) {
                                $("#brs_" + type).prop("checked", item["brs"]);
                            }
                            if (item["tlv"]) {
                                $("#tlv_" + type).prop("checked", item["tlv"]);
                            }
                            if (item["typeappartement"]) {
                                $("#typeappartement_" + type)
                                    .val(+item["typeappartement"].id)
                                    .change();
                            }
                            if (item["frequencepaiementappartement"]) {
                                $("#frequencepaiementappartement_" + type)
                                    .val(
                                        +item["frequencepaiementappartement"].id
                                    )
                                    .change();
                            }
                            if (item["etatappartement"]) {
                                $("#etatappartement_" + type)
                                    .val(+item["etatappartement"].id)
                                    .change();
                            }
                            $(".1").show();
                        } else if (item.entite.code == "RID") {
                            $(".1").hide();
                            $("#superficievilla_" + type).val(item.superficie);
                            $("#prixvilla_" + type).val(item.prixvilla);
                            $("#acomptevilla_" + type).val(item.acomptevilla);
                            $("#maturite_" + type).val(item.maturite);
                            $("#lot_" + type).val(item.lot);
                            $("#ilot_" + type)
                                .val(item["ilot"].id)
                                .trigger("change");

                            // if(item['ilot_']) {
                            //     $('#ilot_' + type).val(+item['ilot'].id).change();
                            // }
                            if (item["typeappartement"]) {
                                $("#typevilla_" + type)
                                    .val(+item["typeappartement"].id)
                                    .change();
                            }
                            if (item["periodicite"]) {
                                $("#periodicite_" + type)
                                    .val(+item["periodicite"].id)
                                    .change();
                            }

                            $(".2").show();
                        }

                        if (item.image !== undefined) {
                            $("#img" + type)
                                .val("")
                                .attr("required", false)
                                .removeClass("required");
                            $("#affimg" + type).attr(
                                "src",
                                item.image ? item.image : imgupload
                            );
                        }

                        $scope.dataInTabPane["document_appartement"][
                            "data"
                        ] = [];
                        if (item.documentappartements) {
                            console.log("document ", item.documentappartements);

                            var data2 = item.documentappartements;
                            data2.forEach((elmt2) => {
                                $scope.dataInTabPane[
                                    "document_appartement"
                                ]["data"].push({
                                    numero: elmt2.id,
                                    nom: elmt2.nom,
                                    fichier: elmt2.document,
                                    id: elmt2.id,
                                });
                            });
                        } else {
                            console.log("document ici ", item.documentappartements);
                            $scope.dataInTabPane[
                                "document_appartement"
                            ]["data"] = [];
                        }


                    }

                    if (type.indexOf("etatlieu") !== -1) {
                        $("#designation_" + type).val(item.designation);
                        $("#type_" + type)
                            .val(item.type)
                            .change();
                        $("#locataire_" + type)
                            .val(+item["locataire"].id)
                            .change();
                        $("#appartement_" + type)
                            .val(+item["appartement"].id)
                            .change();
                        $("#dateredaction_" + type).val(item.dateredaction);
                        $("#etatgenerale_" + type).val(item.etatgenerale);
                        $("#particularite_" + type).val(item.particularite);

                        $("#type_etatlieu").prop("disabled", true);
                        $("#appartement_etatlieu").prop("disabled", true);
                        $("#id_appartement_etatlieu").val(
                            +item["appartement"].id
                        );

                        Init.getElement(
                            "etatlieu_pieces(etatlieu_id:" + item.id + ")",
                            listofrequests_assoc["etatlieu_pieces"]
                        ).then(
                            function (data2) {
                                //console.log("data2", data2);
                                data2.forEach((elmt2) => {
                                    Init.getElement(
                                        "imageetatlieupieces(etatlieupiece_id:" +
                                        elmt2.id +
                                        ")",
                                        listofrequests_assoc[
                                        "imageetatlieupieces"
                                        ]
                                    ).then(
                                        function (data3) {
                                            // console.log("data3", data3);

                                            data3.forEach((elmt3) => {
                                                $(
                                                    "#photopieceetatlieu" +
                                                    elmt2.composition.id
                                                ).append(
                                                    $compile(
                                                        ' <div class="col-span-3 sm:col-span-3 md:col-span-3 text-center">\n' +
                                                        '                                    <div class="form-group text-center class-form">\n' +
                                                        '                                        <!-- <label for="imageuser" class="text-white font-bold">Image</label> -->\n' +
                                                        "                                        <div>\n" +
                                                        '                                            <label for="imgpieceimagecomposition_' +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur +
                                                        '" class="cursor-pointer">\n' +
                                                        '                                                <img id="affimgpieceimagecomposition_' +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur +
                                                        '" alt="..." class="image-hover shadow" style="width: 200px;height: 200px;border-radius: 10%!important;margin: 0 auto">\n' +
                                                        '                                                <div style="display: none;">\n' +
                                                        '                                                    <input type="file" accept=\'image/*\' id="imgpieceimagecomposition_' +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur +
                                                        '" name="pieceimagecomposition_' +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur +
                                                        '" onchange=\'Chargerimage(this.name)\' class="required">\n' +
                                                        '                                                    <input type="hidden" id="erase_imgpieceimagecomposition_' +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur +
                                                        '" name="image_erase" value="">\n' +
                                                        '                                                    <input type="hidden" id="imgpieceimageupdatecomposition_' +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur +
                                                        '" name="imgpieceimageupdatenamecomposition_' +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur +
                                                        '">\n' +
                                                        "\n" +
                                                        "                                                </div>\n" +
                                                        "                                            </label>\n" +
                                                        "                                        </div>\n" +
                                                        '                                        <button class="button mr-1 mb-2 inline-block bg-theme-110 text-white mt-3" type="button" ng-click="eraseFile(\'imgpieceimagecomposition_' +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur +
                                                        "')\">\n" +
                                                        '                                            <strong class="text-white text-u-c"></strong> <i class="fa fa-trash text-white"></i>\n' +
                                                        "                                        </button>\n" +
                                                        "                                    </div>\n" +
                                                        "                                </div>"
                                                    )($scope)
                                                );

                                                $(
                                                    "#imgpieceimagecomposition_" +
                                                    elmt2.composition.id +
                                                    "_" +
                                                    elmt3.imagecompteur
                                                )
                                                    .val("")
                                                    .attr("required", false)
                                                    .removeClass("required");
                                                console.log(
                                                    $(
                                                        "#imgpieceimagecomposition_" +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur
                                                    )
                                                );
                                                $(
                                                    "#affimgpieceimagecomposition_" +
                                                    elmt2.composition.id +
                                                    "_" +
                                                    elmt3.imagecompteur
                                                ).attr(
                                                    "src",
                                                    elmt3.image
                                                        ? elmt3.image
                                                        : imgupload
                                                );
                                                $(
                                                    "#imgpieceimageupdatecomposition_" +
                                                    elmt2.composition.id +
                                                    "_" +
                                                    elmt3.imagecompteur
                                                ).val(elmt3.image);
                                                console.log(elmt3.image);
                                                console.log(
                                                    $(
                                                        "#imgpieceimageupdate_" +
                                                        elmt2.composition
                                                            .id +
                                                        "_" +
                                                        elmt3.imagecompteur
                                                    ).val()
                                                );
                                                $scope.compteurImage3++;
                                            });

                                            $("#compteurimage_etatlieu").val(
                                                $scope.compteurImage3
                                            );
                                        },
                                        function (msg) {
                                            $scope.showToast("", msg, "error");
                                        }
                                    );
                                });
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );

                        var Idvalueapp = item["appartement"].id;
                        $scope.compositionappartement = [];
                        $scope.detailcompositionappartement = [];
                        var typeAvecS = "compositions";
                        rewriteReq =
                            typeAvecS + "(appartement_id:" + Idvalueapp + ")";
                        Init.getElement(
                            "detailcompositions(appartement_id:" +
                            Idvalueapp +
                            ")",
                            listofrequests_assoc["detailcompositions"]
                        ).then(
                            function (data2) {
                                console.log("data2", data2);
                                data2.forEach((elmt2) => {
                                    // $scope.detailcompositionappartement.push(elmt2) ;
                                    const resultUp1 =
                                        $scope.detailcompositionappartement.find(
                                            (d) => d.id == elmt2.id
                                        );

                                    if (!resultUp1) {
                                        $scope.detailcompositionappartement.push(
                                            elmt2
                                        );
                                    }
                                });
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );
                        // console.log(rewriteReq) ;
                        Init.getElement(
                            rewriteReq,
                            listofrequests_assoc[typeAvecS]
                        ).then(
                            function (data) {
                                //  console.log("data", data);
                                $scope.compositionappartement = data;

                                data.forEach((elmt) => {
                                    Init.getElement(
                                        "detailcompositions(composition_id:" +
                                        elmt.id +
                                        ")",
                                        listofrequests_assoc[
                                        "detailcompositions"
                                        ]
                                    ).then(
                                        function (data2) {
                                            console.log("data2", data2);
                                            data2.forEach((elmt2) => {
                                                //   $scope.detailcompositionappartement.push(elmt2) ;
                                                const resultUp =
                                                    $scope.detailcompositionappartement.find(
                                                        (d) => d.id == elmt2.id
                                                    );

                                                if (!resultUp) {
                                                    $scope.detailcompositionappartement.push(
                                                        elmt2
                                                    );
                                                }
                                            });
                                        },
                                        function (msg) {
                                            $scope.showToast("", msg, "error");
                                        }
                                    );
                                });
                                // $scope.reInit("etatlieu");
                                //    console.log($scope.detailspiece) ;
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );

                        setTimeout(function () {
                            $scope.reInit("etatlieu");
                            var Idvalue3 = item.id;
                            console.log(Idvalue3);
                            var typeAvecS3 = "etatlieu_pieces";
                            rewriteReq3 =
                                typeAvecS3 + "(etatlieu_id:" + Idvalue3 + ")";
                            Init.getElement(
                                rewriteReq3,
                                listofrequests_assoc[typeAvecS3]
                            ).then(function (data) {
                                console.log("data", data);

                                data.forEach((etat) => {
                                    console.log(etat);
                                    $(
                                        "#compositionEtatlieu_" +
                                        etat.composition_id
                                    ).val(etat.etatlieu_id);
                                    //      console.log($('#compositionEtatlieu_' + etat.composition_id ).val()) ;
                                    if (etat.image !== undefined) {
                                        $(
                                            "#imgpieceimagecomposition_" +
                                            etat.composition_id
                                        )
                                            .val("")
                                            .attr("required", false)
                                            .removeClass("required");
                                        //   console.log($('#imgpieceimagecomposition_' + etat.composition_id)) ;
                                        $(
                                            "#affimgpieceimagecomposition_" +
                                            etat.composition_id
                                        ).attr(
                                            "src",
                                            etat.image ? etat.image : imgupload
                                        );
                                        $(
                                            "#imgpieceimageupdatecomposition_" +
                                            etat.composition_id
                                        ).val(etat.image);
                                        // console.log(comp.image) ;
                                        console.log(
                                            $(
                                                "#imgpieceimageupdatecomposition_" +
                                                etat.composition_id
                                            ).val()
                                        );
                                    }

                                    Init.getElement(
                                        "detailconstituants(etatlieu_piece_id:" +
                                        etat.id +
                                        ")",
                                        listofrequests_assoc[
                                        "detailconstituants"
                                        ]
                                    ).then(function (data2) {
                                        //    console.log("data2", data2);
                                        data2.forEach(
                                            (elmt2) => {
                                                //   console.log(elmt2.commentaire) ;
                                                $(
                                                    "#id_observation_" +
                                                    elmt2.constituantpiece
                                                        .id +
                                                    "_" +
                                                    elmt2.etatlieu_piece
                                                        .composition.id +
                                                    "_commentaire"
                                                ).val(elmt2.commentaire);
                                                $(
                                                    "#" +
                                                    elmt2.constituantpiece
                                                        .id +
                                                    "_" +
                                                    elmt2.etatlieu_piece
                                                        .composition.id +
                                                    "_observation_etatlieu"
                                                )
                                                    .val(+elmt2.observation.id)
                                                    .change();
                                            },
                                            function (msg) {
                                                $scope.showToast(
                                                    "",
                                                    msg,
                                                    "error"
                                                );
                                            }
                                        );
                                    });

                                    Init.getElement(
                                        "detailequipements(etatlieu_piece_id:" +
                                        etat.id +
                                        ")",
                                        listofrequests_assoc[
                                        "detailequipements"
                                        ]
                                    ).then(function (data2) {
                                        //    console.log("data2", data2);
                                        data2.forEach(
                                            (elmt2) => {
                                                console.log(elmt2);
                                                if (
                                                    $(
                                                        elmt2.equipementpiece
                                                            .generale === 0
                                                    )
                                                ) {
                                                    $(
                                                        "#id_equipement_observation_" +
                                                        elmt2
                                                            .equipementpiece
                                                            .id +
                                                        "_" +
                                                        elmt2.etatlieu_piece
                                                            .composition
                                                            .id +
                                                        "_commentaire"
                                                    ).val(elmt2.commentaire);
                                                    $(
                                                        "#" +
                                                        elmt2
                                                            .equipementpiece
                                                            .id +
                                                        "_" +
                                                        elmt2.etatlieu_piece
                                                            .composition
                                                            .id +
                                                        "composition_observation_etatlieu"
                                                    )
                                                        .val(
                                                            +elmt2.observation
                                                                .id
                                                        )
                                                        .change();
                                                }
                                                // A REVOIRE //
                                                if (
                                                    $(
                                                        elmt2.equipementpiece
                                                            .generale === 1
                                                    )
                                                ) {
                                                    console.log(Idvalueapp);
                                                    $(
                                                        "#id_equipementgenerale_observation_" +
                                                        elmt2
                                                            .equipementpiece
                                                            .id +
                                                        "_" +
                                                        Idvalueapp +
                                                        "_commentaire"
                                                    ).val(elmt2.commentaire);
                                                    $(
                                                        "#" +
                                                        elmt2
                                                            .equipementpiece
                                                            .id +
                                                        "_" +
                                                        Idvalueapp +
                                                        "composition_observationgenerale_etatlieu"
                                                    )
                                                        .val(
                                                            +elmt2.observation
                                                                .id
                                                        )
                                                        .change();
                                                    console.log(
                                                        "#id_equipementgenerale_observation_" +
                                                        elmt2
                                                            .equipementpiece
                                                            .id +
                                                        "_" +
                                                        Idvalueapp +
                                                        "_commentaire"
                                                    );
                                                    console.log(
                                                        "#" +
                                                        elmt2
                                                            .equipementpiece
                                                            .id +
                                                        "_" +
                                                        Idvalueapp +
                                                        "composition_observationgenerale_etatlieu"
                                                    );
                                                    /* // console.log(elmt2) ;
                              $('#commentaireequipementgeneral_' + elmt2.equipementpiece.id + '_etatlieu' ).val(elmt2.commentaire) ;
                              $('#observationequipementgeneral_' + elmt2.equipementpiece.id + '_etatlieu' ).val(+elmt2.observation.id).change();*/
                                                }
                                            },
                                            function (msg) {
                                                $scope.showToast(
                                                    "",
                                                    msg,
                                                    "error"
                                                );
                                            }
                                        );
                                    });
                                });

                                if (item.image !== undefined) {
                                    $("#img" + type)
                                        .val("")
                                        .attr("required", false)
                                        .removeClass("required");
                                    $("#affimg" + type).attr(
                                        "src",
                                        item.image ? item.image : imgupload
                                    );
                                }
                            });
                        }, 4000);
                    }

                    if (type.indexOf("immeuble") !== -1) {
                        $("#nom_" + type).val(item.nom);
                        $("#adresse_" + type).val(item.adresse);
                        $("#nombreascenseur_" + type).val(item.nombreascenseur);
                        $("#nombreappartement_" + type).val(
                            item.nombreappartement
                        );
                        $("#nombregroupeelectrogene_" + type).val(
                            item.nombregroupeelectrogene
                        );
                        $("#nombrepiscine_" + type).val(item.nombrepiscine);
                        if (item["equipegestion"]) {
                            $("#equipegestion_" + type)
                                .val(+item["equipegestion"].id)
                                .change();
                        }

                        if (item["structureimmeuble_id"]) {
                            $("#structureimmeuble_" + type)
                                .val(+item["structureimmeuble"].id)
                                .change();
                        }

                        Init.getElement(
                            "pieceimmeubles(immeuble_id:" + item.id + ")",
                            listofrequests_assoc["pieceimmeubles"]
                        ).then(function (data2) {
                            // console.log("data2", data2);
                            $scope.dataPage["typepieces"].forEach((elmt) => {
                                data2.forEach((elmt2) => {
                                    $(
                                        "#" + elmt2.typepiece.id + "_id_oui"
                                    ).prop("checked", true);
                                    if (elmt2.typepiece.id == elmt.id) {
                                        $valnombre = $(
                                            "#" +
                                            elmt2.typepiece.id +
                                            "_id_oui_nombre"
                                        ).val();
                                        $valnombre++;
                                        //  console.log($('#'+elmt2.typepiece.id+'_id_oui_nombre').val()) ;
                                        //  console.log($valnombre) ;
                                        $(
                                            "#" +
                                            elmt2.typepiece.id +
                                            "_id_oui_nombre"
                                        ).val(+$valnombre);
                                    }
                                    $(
                                        "#" +
                                        elmt2.typepiece.id +
                                        "_id_oui_nombre"
                                    ).show();
                                });
                            });
                        });

                        Init.getElement(
                            "securites(immeuble_id:" + item.id + ")",
                            listofrequests_assoc["securites"]
                        ).then(
                            function (data2) {
                                console.log("data2", data2);
                                data2.forEach((elmt2) => {
                                    if (elmt2.prestataire) {
                                        $scope.prestataireData.push(
                                            elmt2.prestataire
                                        );
                                        console.log($scope.prestataireData);
                                        $scope.dataInTabPane[
                                            "immeuble_securite_immeuble"
                                        ]["data"].push({
                                            adressesecurite: "",
                                            designationsecurite: "",
                                            etatprestataire_id: elmt2.etat,
                                            etatprestataire_text: elmt2.etat,
                                            etatsecurite_id: "",
                                            etatsecurite_text: "etat",
                                            horaireprestataire_id:
                                                elmt2.horaire_id,
                                            horaireprestataire_text:
                                                "de " +
                                                elmt2.horaire.debut +
                                                " a " +
                                                elmt2.horaire.fin +
                                                " ",
                                            horairesecurite_id: "",
                                            horairesecurite_text: "",
                                            prestataire_id:
                                                elmt2.prestataire_id,
                                            prestataire_text:
                                                elmt2.prestataire.nom,
                                            telephone1securite: "",
                                            telephone2securite: "",
                                        });
                                    } else {
                                        $scope.dataInTabPane[
                                            "immeuble_securite_immeuble"
                                        ]["data"].push({
                                            adressesecurite: elmt2.adresse,
                                            designationsecurite:
                                                elmt2.designation,
                                            etatprestataire_id: "",
                                            etatprestataire_text: "etat",
                                            etatsecurite_id: elmt2.etat,
                                            etatsecurite_text: elmt2.etat,
                                            horaireprestataire_id: "",
                                            horaireprestataire_text: "",
                                            horairesecurite_id:
                                                elmt2.horaire_id,
                                            horairesecurite_text:
                                                "de " +
                                                elmt2.horaire.debut +
                                                " a " +
                                                elmt2.horaire.fin +
                                                " ",
                                            prestataire_id: "",
                                            prestataire_text: "prestataire",
                                            telephone1securite:
                                                elmt2.telephone1,
                                            telephone2securite:
                                                elmt2.telephone2,
                                        });
                                    }
                                });
                                console.log(
                                    $scope.dataInTabPane[
                                    "immeuble_securite_immeuble"
                                    ]["data"]
                                );
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );
                    }

                    if (type.indexOf("proprietaire") !== -1) {
                        $("#nom_" + type).val(item.nom);
                        $("#prenom_" + type).val(item.prenom);
                        $("#adresse_" + type).val(item.adresse);
                        $("#age_" + type).val(item.age);
                        $("#telephone_" + type).val(item.telephone);
                        $("#telephoneportable_" + type).val(
                            item.telephoneportable
                        );
                        $("#telephonebureau_" + type).val(item.telephonebureau);
                        $("#profession_" + type).val(item.profession);
                    }

                    if (type.indexOf("locataire") !== -1) {
                        //update locataire
                        console.log("update locataire " + JSON.stringify(item));
                        console.log(
                            "update locataire 2 " + item.typelocataire_id
                        );
                        $("#typelocataire_" + type)
                            .val(item.typelocataire_id)
                            .change();
                        $("#entite_" + type)
                            .val(item.entite.id)
                            .trigger("change");
                        if (item.typelocataire_id == 1) {
                            console.log("entre ici //updatelocataire");
                            console.log("entre ici // " + type);
                            $(".2").hide();
                            if (item.entite.code == "RID") {
                                if (item.est_copreuneur == 1) {
                                    $("#est_copreuneur_locataire").prop(
                                        "checked",
                                        true
                                    );
                                    $(".3").show();
                                    $scope.dataInTabPane[
                                        "locataire_copreneurs_locataire"
                                    ]["data"] = item.copreneurs;
                                }
                            }
                            $("#nomlocataire_" + type).val(item.nom);
                            console.log("entre ici nom// " + item.nom);
                            $("#prenomlocataire_" + type).val(item.prenom);
                            console.log("entre ici prenom: // " + item.prenom);
                            $("#telephoneportable1locataire_" + type).val(
                                item.telephoneportable1
                            );
                            $("#telephoneportable2locataire_" + type).val(
                                item.telephoneportable2
                            );
                            $("#telephonebureaulocataire_" + type).val(
                                item.telephonebureau
                            );
                            $("#emaillocataire_" + type).val(item.email);
                            $("#professionlocataire_" + type).val(
                                item.profession
                            );
                            $("#agelocataire_" + type).val(item.age);
                            $("#cnilocataire_" + type).val(item.cni);
                            $("#passeportlocataire_" + type).val(
                                item.passeport
                            );
                            console.log(
                                " expatlocale : // " + item.expatlocale
                            );

                            if (item.numeroclient) {
                                $("#numeroclient_" + type).val(
                                    item.numeroclient
                                );
                            }
                            if (item.expatlocale == "Expatrié") {
                                console.log("entre ici " + item.expatlocale);
                                $("#expatlocataire_" + type).prop(
                                    "checked",
                                    true
                                );
                                $("#localelocataire_" + type).prop(
                                    "checked",
                                    false
                                );
                            } else {
                                $("#expatlocataire_" + type).prop(
                                    "checked",
                                    false
                                );
                                $("#localelocataire_" + type).prop(
                                    "checked",
                                    true
                                );
                            }
                            if (item.mandataire) {
                                $("#mandataire_" + type).val(item.mandataire);
                            }
                            if (item.lieux_naissance) {
                                $("#lieuxnaissance_" + type).val(
                                    item.lieux_naissance
                                );
                            }
                            if (item.date_naissance) {
                                $("#datenaissance_" + type).val(
                                    item.date_naissance
                                );
                            }
                            if (item.pays_naissance) {
                                $("#paysnaissance_" + type)
                                    .val(item.pays_naissance)
                                    .trigger("change");
                            }

                            if (item.adresseentreprise) {
                                $("#adresse_" + type).val(
                                    item.adresseentreprise
                                );
                            }

                            $(".1").show();
                        } else if (item.typelocataire_id == 2) {
                            $(".1").hide();
                            $("#nomentrepriselocataire_" + type).val(
                                item.nomentreprise
                            );
                            $("#adresseentrepriselocataire_" + type).val(
                                item.adresseentreprise
                            );
                            $("#ninealocataire_" + type).val(item.ninea);
                            $("#numerorglocataire_" + type).val(item.numerorg);
                            $("#personnehabiliteasignerlocataire_" + type).val(
                                item.personnehabiliteasigner
                            );
                            $("#fonctionpersonnehabilitelocataire_" + type).val(
                                item.fonctionpersonnehabilite
                            );
                            $("#nompersonneacontacterlocataire_" + type).val(
                                item.nompersonneacontacter
                            );
                            $("#prenompersonneacontacterlocataire_" + type).val(
                                item.prenompersonneacontacter
                            );
                            $("#emailpersonneacontacterlocataire_" + type).val(
                                item.emailpersonneacontacter
                            );
                            $(
                                "#telephone1personneacontacterlocataire_" + type
                            ).val(item.telephone1personneacontacter);
                            $(
                                "#telephone2personneacontacterlocataire_" + type
                            ).val(item.telephone2personneacontacter);
                            if (item.secteuractivite) {
                                $("#secteuractivite_" + type)
                                    .val(item.secteuractivite.id)
                                    .trigger("change");
                            }
                            if (item.email) {
                                $("#email2locataire_" + type).val(item.email);
                            }
                            // $('#cnilocataire_' + type).val(item.cni);
                            $(".2").show();
                        }
                    }

                    if (type.indexOf("paiementloyer") !== -1) {
                        console.log("item json " + JSON.stringify(item));
                        if (item["contrat"]) {
                            $("#contrat_" + type)
                                .val(+item["contrat"].id)
                                .change();
                            $("#appartement_" + type)
                                .val(+item["contrat"]["appartement"].id)
                                .change();
                            $("#locataire_" + type)
                                .val(+item["contrat"]["locataire"].id)
                                .change();
                        }
                        $("#datepaiement_" + type).val(item.datepaiement);
                        $("#montantfacture_" + type).val(item.montantfacture);
                        $("#debutperiodevalide_" + type).val(
                            item.debutperiodevalide
                        );
                        $("#finperiodevalide_" + type).val(
                            item.finperiodevalide
                        );
                        $("#modepaiement_" + type)
                            .val(item.modepaiement.id)
                            .trigger("change");
                        // $('#periode_' + type).val(item.periode);
                        // $scope.dataInTabPane['periodepaiementloyer_paiementloyer']['data'] = item.detailpaiements;
                        var selectElement = $("#periodes_paiementloyer");
                        $.each(item.detailpaiements, function (index, value) {
                            console.log("val item " + value.periode.id);
                            selectElement
                                .find(
                                    'option[value="' + value.periode.id + '"]'
                                )
                                .prop("selected", true);
                        });
                        selectElement.change();
                    }

                    if (type.indexOf("demanderesiliation") !== -1) {
                        if (item["contrat"]) {
                            // $("#contrat_" + type)
                            //     .val(+item["contrat"].id)
                            //     .change();
                            $("#appartement_" + type)
                                .val(+item["contrat"]["appartement"].id)
                                .change();
                            $("#locataire_" + type)
                                .val(+item["contrat"]["locataire"].id)
                                .change();
                        }
                        $("#datedebutcontrat_" + type).val(
                            item.datedebutcontrat
                        );
                        $("#datedemande_" + type).val(item.datedemande);
                        $("#delaipreavisrespecte_" + type).val(
                            item.delaipreavisrespecte
                        );
                        $("#delaipreavi_" + type)
                            .val(item.delaipreavi.id)
                            .change();
                        if (item.delaipreavisrespecte == "0") {
                            $("#raisonnonrespectdelai_" + type).val(
                                item.raisonnonrespectdelai
                            );
                        }
                        $("#dateeffectivite_" + type).val(item.dateeffectivite);
                        $("#motif_" + type).val(item.motif);
                    }
                    if (type.indexOf("annonce") !== -1) {
                        console.log(item);
                        $("#titre_" + type).val(item.titre);
                        $("#description_" + type).val(item.description);
                        $("#debut_" + type).val(item.debut);
                        $("#fin_" + type).val(item.fin);
                        $("#fin_" + type).val(item.fin);
                    }
                    if (type.indexOf("contratprestation") !== -1) {
                        console.log(item);
                        $("#prestataire_" + type)
                            .val(+item["prestataire"].id)
                            .change();
                        $("#categorieprestation_" + type)
                            .val(+item["categorieprestation"].id)
                            .change();
                        $("#datesignaturecontrat_" + type).val(
                            item.datesignaturecontrat
                        );
                        $("#datedemarragecontrat_" + type).val(
                            item.datedemarragecontrat
                        );
                        $("#daterenouvellementcontrat_" + type).val(
                            item.daterenouvellementcontrat
                        );
                        $("#datepremiereprestation_" + type).val(
                            item.datepremiereprestation
                        );
                        $("#datepremierefacture_" + type).val(
                            item.datepremierefacture
                        );
                        $("#montant_" + type).val(item.montant);
                        $("#frequenceprestation_" + type)
                            .val(item["frequencepaiementappartement"].id)
                            .change();
                    }
                    if (type.indexOf("typeappartement") !== -1) {
                        console.log("entre" + JSON.stringify(item));
                        $("#designation_" + type).val(item.designation);
                        $("#usage_" + type)
                            .val(item.usage)
                            .trigger("change");
                        // $scope.dataPage['typepieces'] = [];
                        // $scope.dataInTabPane['typeappartement_typepiece_typeappartement']['data'] = [];
                        item.typeappartement_pieces.forEach((val) => {
                            console.log(
                                "this value " + JSON.stringify(val.typepiece)
                            );
                            // const result = $scope.dataInTabPane['typeappartement_typepiece_typeappartement']['data'].find(typepiece_id => typepiece_id == val.typepiece.id);
                            // console.log("THIS TAB PANE "+$scope.dataInTabPane['typeappartement_typepiece_typeappartement']['data']);
                            // if (!result) {
                            $scope.dataInTabPane[
                                "typeappartement_typepiece_typeappartement"
                            ]["data"].push({
                                typepiece_text: val.typepiece.designation,
                                typepiece_id: val.typepiece.id,
                            });
                            // }
                            console.log(
                                "TAB " +
                                JSON.stringify(
                                    $scope.dataPage["typepieces"]
                                )
                            );
                        });
                    }
                    if (type.indexOf("typecontrat") !== -1) {
                        console.log(item.designation);
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("typedocument") !== -1) {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("typefacture") !== -1) {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("typeintervention") !== -1) {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("typelocataire") !== -1) {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("typeobligationadministrative") !== -1) {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("typepiece") !== -1) {
                        $("#designation_" + type).val(item.designation);
                        $("#iscommun_" + type)
                            .val(item.iscommun)
                            .trigger("change");
                    }
                    if (type.indexOf("typerenouvellement") !== -1) {
                        $("#designation_" + type).val(item.designation);
                    }
                    if (type.indexOf("structureimmeuble") !== -1) {
                        $("#designation_" + type).val(item.etages);
                    }
                    if (type.indexOf("equipementpiece") !== -1) {
                        $("#designation_" + type).val(item.designation);
                        $("#generale_" + type)
                            .val(item.generale)
                            .trigger("change");
                    }
                    if (type.indexOf("paiementecheance") !== -1) {
                        console.log(item, "mansour updated   ");
                        $("#id_paiementecheance")
                            .val(item.id)
                            .trigger("change");
                        $("#avisecheance_id_" + type)
                            .val(item?.avisecheance_id)
                            .trigger("change");
                        $("#date_paiementecheance")
                            .val(item?.date)
                            .trigger("change");
                        //     //$('#montant_paiementecheance').val(item.montant_total);
                        $("#numerocheque_paiementecheance")
                            .val(item.numero_cheque)
                            .trigger("change");
                        $("#montantencaissement_paiementecheance")
                            .val(item.montantencaisse)
                            .trigger("change");
                        $("#modepaiement_paiementecheance")
                            .val(item.modepaiement_id)
                            .trigger("change");
                        //     //justificatif_paiementecheance input file justificatif_paiement
                        // $('#justificatif_paiementecheance').val(item?.avisecheance?.justificatif_paiement).trigger('change');

                        // afficher justificati dans img id='justificatifcontent_paiementecheance"
                        // let image = $('#justificatifcontent_paiementecheance');
                        // image.attr('src', item?.avisecheance?.justificatif_paiement);
                        // let input =$('#justificatif_paiementecheance');
                        // input.val(item?.avisecheance?.justificatif_paiement);

                        // $("#justificatifcontent_paiementecheance" ).attr(
                        //     "src", item?.avisecheance?.justificatif_paiement
                        // );
                        $("#justificatifcontent_paiementecheance").attr(
                            "href",
                            item?.avisecheance?.justificatif_paiement
                        );

                        console.log(
                            "item?.avisecheance?.justificatif_paiement",
                            $("#justificatifcontent_paiementecheance").val(),
                            item?.avisecheance?.justificatif_paiement
                        );
                        $("#justificatifcontent_path")
                            .val(item?.avisecheance?.justificatif_paiement)
                            .trigger("change");

                        $("#montantloyer_paiementecheance").prop("disabled", true);
                        // $("#montantaregler_paiementecheance").prop("disabled", true);
                        $("#montantaregler_paiementecheance").prop("readonly", true).css({ "background-color": "#d6d6d6" });


                    }

                    if (type.indexOf("factureintervention") !== -1) {
                        console.log("item factureintervention ", item);
                    }

                    if (type.indexOf("role") !== -1) {
                        //update_role
                        $("#name_" + type).val(item.name);
                        $scope.roleview = item;
                        $scope.role_permissions = [];
                        $.each(
                            $scope.roleview.permissions,
                            function (key, value) {
                                $scope.role_permissions.push(value.id);
                            }
                        );
                    }
                    if (type.indexOf("typeapportponctuel") !== -1) {
                        //update_typeapportponctuel
                        $("#designation_" + type).val(item.designation);
                        $("#description_" + type).val(item.description);
                    }
                    if (type.indexOf("apportponctuel") !== -1) {
                        //update_typeapportponctuel
                        $("#montant_" + type).val(item.montant);
                        $("#date_" + type).val(item.date);
                        $("#contrat_id_" + type).val(item.contrat_id);
                        $("#typeapportponctuel_id_" + type)
                            .val(item.typeapportponctuel_id)
                            .trigger("change");
                        $("#observations_" + type).val(item.observations);
                    }
                    if (type.indexOf("contratproprietaire") !== -1) {
                        //update_contratproprietaire
                        $("#entite_id_" + type).val(item.entite_id);
                        $("#proprietaire_id_" + type).val(item.proprietaire_id);
                        $("#modelcontrat_id_" + type).val(item.modelcontrat_id);
                        $("#date_" + type).val(item.date);
                        $("#descriptif_" + type).val(item.descriptif);
                        $("#commissionvaleur_" + type).val(
                            item.commissionvaleur
                        );
                        $("#commissionpourcentage_" + type).val(
                            item.commissionpourcentage
                        );
                    }
                    //update_facture
                    if (type.indexOf("facture") !== -1) {
                        $("#datefacture_" + type).val(item.datefacture);
                        $("#mois_" + type)
                            .val(item.moisfacture)
                            .trigger("change");
                        $("#typefacture_" + type)
                            .val(item.typefacture_id)
                            .trigger("change");
                        $("#proprietaire_" + type)
                            .val(item.proprietaire_id)
                            .trigger("change");
                        $("#locataire_" + type)
                            .val(item.locataire_id)
                            .trigger("change");
                        $("#immeuble_" + type)
                            .val(item.immeuble_id)
                            .trigger("change");
                        $("#appartement_" + type)
                            .val(item.appartement_id)
                            .trigger("change");
                        $("#intervention_" + type)
                            .val(item.intervention_id)
                            .trigger("change");
                        $("#intervenantassocie_" + type).val(
                            item.intervenantassocie
                        );
                        $("#montant_" + type).val(item.montant);
                    }

                    // Si le model contient une image dans son formulaire
                    if (item && item.image !== undefined) {
                        $("#img" + type)
                            .val("")
                            .attr("required", false)
                            .removeClass("required");
                        $("#affimg" + type).attr(
                            "src",
                            item.image ? item.image : imgupload
                        );
                    }
                    // $("#modal_add"+type).modal('show');
                    setTimeout(function () {
                        $("#modal_add" + type).blockUI_stop();
                    }, 1000);
                },
                function (msg) {
                    $scope.showToast("", msg, "error");
                }
            );
        };

        $scope.filterDashboard = function (type, page, params) {
            console.log("filterDashboard", type, page, params);
            window.location.href = "#!/" + page;
            $scope.pageChanged(type, null, params);
        };

        //--FIN => Fonction mise à jour--//

        // implémenter toutes les variations du formulaire
        $scope.changeStatut = function (e, type) {
            var form = $("#form_addchstat");
            var send_data = {
                id: $scope.chstat.id,
                status: $scope.chstat.statut,
                commentaire: $("#commentaire_chstat").val(),
            };
            form.parent().parent().blockUI_start();
            Init.changeStatut(type, send_data).then(
                function (data) {
                    form.parent().parent().blockUI_stop();
                    if (data.data != null && !data.errors) {
                        $scope.pageChanged(type);

                        title = "ACTIVATION";
                        typeToast = "success";
                        if ($scope.chstat.statut == 0) {
                            title = "DÉSACTIVATION";
                            typeToast = "warning";
                        }

                        $scope.showToast(title, "succès", typeToast);

                        $("#modal_addchstat").modal("hide");
                        $scope.closeModal("#modal_addchstat");
                    } else {
                        $scope.showToast(
                            "",
                            '<span class="h4">' + data.errors + "</span>",
                            "error"
                        );
                    }
                },
                function (msg) {
                    form.parent().parent().blockUI_stop();
                    $scope.showToast(
                        "",
                        '<span class="h4">' + msg + "</span>",
                        "error"
                    );
                }
            );
        };

        $scope.showData = function () {
            $scope.showdata = true;
        };

        $scope.makeTableScroll = function () {
            // Constant retrieved from server-side via JSP
            var maxRows = 4;

            var table = document.getElementById("tabcarte");
            var wrapper = table.parentNode;
            var rowsInTable = table.rows.length;
            var height = 0;
            if (rowsInTable > maxRows) {
                for (var i = 0; i < maxRows; i++) {
                    height += table.rows[i].clientHeight;
                }
                wrapper.style.height = height + "px";
            }
        };

        // Permet d'afficher le formulaire
        $scope.sousfamille = { reponse: false };
        $scope.currentTypeModal = null;
        $scope.currentTitleModal = null;
        $scope.initVariableScope = function () {
            $scope.update = false;
            $scope.is_checked = false;
            $scope.item_update = null;
            $scope.temponPermissions = null;
            $scope.searchoption_list_permission = null;
            $scope.conserveFilter = false;
            $scope.currentTable = null;
            $scope.showdata = null;
            $scope.showfactureinbox = null;
        };

        $scope.showModalAdd = function (
            type,
            optionals = {
                is_file_excel: false,
                title: null,
                fromUpdate: false,
            },
            itemId = null,
            type_link = null
        ) {
            $(".1").hide();
            $(".2").hide();
            $(".3").hide();
            $(".entreprise").hide();
            $(".relance_div").hide();
            $(".displaycopreneurlvt").hide();
            // $('.filesinbox').show();
            $scope.hideButton = true;
            $scope.initVariableScope();
            $scope.reInit();
            var type2 = "";
            $("#toutcocher").prop("checked", false);
            if (type === "inbox_resiliation") {
                $scope.reInit("etatlieu");
                type2 = "inbox_resiliation";
                console.log("inbox_resiliation");
                type = "inbox";
                console.log(type2);
            }
            if (type === "locationvente") {
                // $("#fraiscoutlocationvente_locationvente").prop(
                //     "disabled",
                //     true
                // );
                $scope.dataInTabPane["contrat_annexes_contrat"]["data"] = [];
            }
            if (type === "paiementloyereaux") {
                type2 = "paiementloyereaux";
                type = "paiementloyer";
            }

            $scope.currentTitleModal = optionals.title;
            $scope.currentTypeModal = type;
            var fromPage = false;
            var conserveFilter = optionals.fromUpdate ? true : false;
            $scope.emptyform(
                optionals.is_file_excel ? "liste" : type,
                fromPage,
                conserveFilter
            );

            if (!optionals.is_file_excel) {
                if (type == "paiementloyer") {
                    // $(".numerochequepaiementloyer").hide();
                    if (type_link) {
                        $("#periodicite_paiementloyer").val(type_link).change();
                    }
                }

                if (type == "devi") {
                    $scope.reInit("etatlieu");
                }
                if (type == "factureintervention") {
                    $scope.reInit("etatlieu");

                    $scope.editInSelect2Costum('locataire', +$scope.infosUserConnected.locataire_id, type);
                    console.log('val champ locataire : ', $("#locataire_factureintervention").val());
                }
                if (type == "avenant") {
                    $scope.reInit("avenant");
                }

                if (type == "inbox") {
                    $(".relance_div").hide();
                    if (
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("list-detailslocationvente") !== -1
                    ) {
                        console.log("ici relance type on init  change");
                        $(".relance_div").show();
                        $("#check_relance1_inbox")
                            .attr("checked", "checked")
                            .trigger("change");
                    }
                }
                // if (type == "paiementecheance") {
                //     $(".numerochequepaiementecheance").hide();
                // }

                if (type == "immeuble") {
                    $scope.dataPage["typepieces"].forEach((elmt) => {
                        $("#" + elmt.id + "_id_oui_nombre").hide();
                        $("#" + elmt.id + "_id_oui_nomsalledefete").hide();
                        $(".classeautre").hide();
                    });
                }
                if (type == "locationvente") {
                    $("#isRidwan_locationvente").val("1");
                }
                if (type == "annulationpaiementavis") {
                    $("#echeance_annulationpaiementavis").val(itemId).change();

                    document.getElementById(
                        "date_annulationpaiementavis"
                    ).valueAsDate = new Date();
                }
                if (type == "annulationpaiementloyer") {
                    $("#loyer_annulationpaiementloyer").val(itemId).change();

                    document.getElementById(
                        "date_annulationpaiementloyer"
                    ).valueAsDate = new Date();
                }

                if (type == "locataire") {
                    $("#nomcompletpersonnepriseencharge_locataire").hide();
                    $("#telephonepersonnepriseencharge_locataire").hide();
                }

                if (type == "demanderesiliation") {
                    $("#raisonnonrespectdelai_demanderesiliation").hide();
                    $("#raison").hide();

                    $scope.editInSelect2Costum('locataire', +$scope.infosUserConnected.locataire_id, type);
                    console.log('val champ locataire : ', $("#locataire_demanderesiliation").val());
                }
                if (type == "demandeintervention") {
                    $("#typeappartementdiv").hide();
                    $("#typelocatairediv").hide();
                    $("#typeimmeublediv").hide();

                    $(".appintervention").hide();
                    $(".immeubleintervention").hide();
                    $(".appintervention").hide();
                    $(".immeubleintervention").hide();

                    $scope.editInSelect2Costum('locataire', +$scope.infosUserConnected.locataire_id, type);
                    console.log('val champ locataire : ', $("#locataire_demandeintervention").val());
                }
                if (type == "intervention") {
                    $(".employeintervention").hide();
                    $(".prestataireintervention").hide();

                    //     $scope.editInSelect2Costum('locataire', +$scope.infosUserConnected.locataire_id, type);
                    //     console.log('val champ locataire : ',$("#locataire_intervention").val());
                }

                if (type == "contrat") {
                    $(".locataireexistant").hide();
                    $(".nouveaulocataire").hide();
                    $("#nomcompletpersonnepriseencharge_locataire").hide();
                    $("#telephonepersonnepriseencharge_locataire").hide();

                    if ($(".appartement_append")) {
                        $(".appartement_append").each(function () {
                            $(this).remove();
                        });
                    }
                    $("#caution_document_contrat").hide();
                    var check_typelocataire = $("#check_typelocataire");
                    if (check_typelocataire.attr("checked") === "checked") {
                        check_typelocataire.removeAttr("checked");
                    }
                    $("#montantloyer_contrat").prop("readonly", true).css({ "background-color": "#d6d6d6" });
                }
                if (type == "obligationadministrative") {
                    $(".immeubleObligationadministrative").hide();
                    $(".appartementObligationadministrative").hide();
                }
                if (type == "assurance") {
                    $(".assurancerenouvelle").hide();
                    $(".nonrenouvelle").hide();
                }

                if (type == "rapportintervention") {
                    $("#divappartement_rapportintervention").hide();
                }

                if (type == "annonce") {
                    $(".appartementannonce").hide();

                    // $scope.editInSelect2Costum('locataire', +$scope.infosUserConnected.locataire_id, type);
                    // console.log('val champ locataire : ',$("#locataire_annonce").val());
                }
                if (type == "message") {
                    console.log('message here');
                    // $scope.editInSelect2Costum('locataire1', +$scope.infosUserConnected.locataire_id, type);
                    // console.log('val champ locataire : ',$("#locataire1_message").val());
                }

                if (type == "facture") {
                    $(".interventionfacture").hide();
                    $(".appartementfacture").hide();
                }

                //modaladd_user
                if (type.indexOf("user") !== -1) {
                    $(".prestataireuser").hide();
                    if ($scope.currentTemplateUrl.indexOf("list-user") == -1) {
                        // $scope.getelements("roles");
                    }
                }
                //modaladd_role
                else if (type.indexOf("role") !== -1) {
                    $scope.roleview = null;
                    $scope.role_permissions = [];
                    $scope.getelements("permissions");
                }

                if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-inbox") !== -1
                ) {
                    if (type.indexOf("inbox") !== -1) {
                        // console.log("IICI ICICIIC INBOX ");
                        $(".allfilesinbox").show();
                        $(".filesinbox").hide();
                        $("#choixContrat_inbox_div").addClass("hidden");

                        $scope.editInSelect2Costum('locataire', +$scope.infosUserConnected.locataire_id, 'inbox');
                        console.log('val champ locataire : ', $("#locataire_inbox").val())

                    }
                }

                if (type.indexOf("facturelocation") !== -1) {
                    document.getElementById(
                        "datefacture_facturelocation"
                    ).valueAsDate = new Date();
                    console.log("icic paiement loyer 2023 ");

                    // $(document).ready(function() {
                    // Au chargement de la page, masquer champB
                    $(".moiscautionfacturelocation").hide();
                    // Lorsque la valeur du menu déroulant change
                    $("#typefacture_facturelocation").change(function () {
                        var selectedType = $(
                            "#typefacture_facturelocation"
                        ).text();
                        console.log("change typefcture ", selectedType);
                        if (selectedType === "caution") {
                            $(".moiscautionfacturelocation").show();
                        } else {
                            $(".moiscautionfacturelocation").hide();
                        }
                    });
                    // });
                }

                $scope.notEnterEtatLieu = false;
                $("#contrat_facturelocation").prop("disabled", false);
                $("#locataire_facturelocation").prop("disabled", false);
                console.log("id du contrat ==>", itemId);

                if (itemId) {
                    console.log("add with id " + itemId);

                    if (type.indexOf("signaturecontrat") !== -1) {
                        $("#contrat_id_signaturecontrat").val(itemId);
                    }
                    if (type.indexOf("avenant") !== -1) {
                        $("#id_contrat_avenant").val(itemId);
                    }
                    if (type.indexOf("demanderesiliation") !== -1) {
                        $("#locataire_demanderesiliation").val(itemId).change();
                    }

                    if (type.indexOf("intervention") !== -1) {
                        $scope.reInit("intervention");
                        $scope.dataPage["demandeinterventions"].forEach(
                            (elmt) => {
                                if (elmt.id == itemId) {
                                    $(
                                        "#demandeintervention_intervention_id"
                                    ).val(elmt.id);
                                    $("#demandeintervention_" + type)
                                        .val(elmt.id)
                                        .change();
                                    $("#demandeintervention_" + type).prop(
                                        "disabled",
                                        true
                                    );
                                }
                            }
                        );
                    }

                    if (
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("list-detailsdemanderesiliation") !== -1
                    ) {
                        if (type2 == "inbox_resiliation") {
                            var texteContratLocation = `Suite à la résiliation du contrat de location qui nous liait , veuillez trouver, ci-joint, les documents suivants:
                            - Le devis des travaux de remise en état de l'appartement
                            - La facture d'eau
                            - La situation du dépôt de garantie`;
                            console.log("je suis inbox_resiliation");
                            if (type.indexOf("inbox") !== -1) {
                                console.log("IICI ICICIIC INBOX ");
                                $(".allfilesinbox").show();
                                $(".filesinbox").hide();

                                let infosContrat =
                                    $scope.dataPage["demanderesiliations"][0][
                                    "contrat"
                                    ];
                                console.log(
                                    "IICI ICICIIC INBOX ",
                                    infosContrat.appartement.id
                                );
                                $("#locataire_inbox")
                                    .val(infosContrat.locataire.id)
                                    .trigger("change");
                                $("#contrat_inbox").val(infosContrat.id);

                                $("#subject_inbox").val("Situation Globale");

                                $("#body_inbox").val(texteContratLocation);

                                $("#appartement_inbox")
                                    .val(infosContrat.appartement.id)
                                    .trigger("change");
                                //$('.id_appartement_inbox').val(infosContrat.appartement.id).trigger("change");
                            }
                        }
                    }
                    if (
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("list-detailscontrat") !== -1
                    ) {

                        $scope.reInit("detailscontrat");
                        if (type.indexOf("facturelocation") !== -1) {
                            // $("#locataire_facturelocation")
                            //     .val(itemId)
                            //     .change();
                            $scope.editInSelect2Costum('locataire', itemId, type);
                            // $( '#locataire_facturelocation').prop( "disabled", true );
                            var contrat = $("#contrat_id_detailscontrat").val();
                            $("#contrat_facturelocation").val(contrat).change();
                            //    $('#contrat_facturelocation').prop( "disabled", true );

                        }

                        if (type.indexOf("factureeaux") !== -1) {
                            //update_factureeaux

                            $("#locataire_factureeaux")
                                .val(itemId)
                                .trigger("change");
                            var contrat = $("#contrat_id_detailscontrat").val();
                            console.log("contrat " + contrat);
                            $("#contrat_factureeaux")
                                .val(contrat)
                                .trigger("change");
                        }

                        if (type2 == "inbox_resiliation") {
                            var texteContratLocation = `Suite à la résiliation du contrat de location qui nous liait , veuillez trouver, ci-joint, les documents suivants:
                            - Le devis des travaux de remise en état de l'appartement
                            - La facture d'eau
                            - La situation du dépôt de garantie`;
                            console.log("je suis inbox_resiliation");
                            if (type.indexOf("inbox") !== -1) {
                                console.log("IICI ICICIIC INBOX ");
                                $(".allfilesinbox").show();
                                $(".filesinbox").hide();
                                var locataire = $(
                                    "#locataire_id_detailscontrat"
                                ).val();

                                let infosContrat =
                                    $scope.dataPage["contrats"][0];

                                $("#locataire_inbox")
                                    .val(locataire)
                                    .trigger("change");
                                $("#contrat_inbox").val(infosContrat.id);

                                $("#subject_inbox").val("Situation Globale");

                                $("#body_inbox").val(texteContratLocation);

                                $("#appartement_inbox")
                                    .val(infosContrat.appartement.id)
                                    .trigger("change");
                                //$('.id_appartement_inbox').val(infosContrat.appartement.id).trigger("change");
                            }
                        } else {
                            if (type.indexOf("inbox") !== -1) {
                                console.log("IICI ICICIIC INBOX ");
                                $(".allfilesinbox").hide();
                                $(".filesinbox").show();
                                // $('#locataire_' + type).prop("disabled" , true);
                                // $('#subject_' + type).prop("disabled" , true);
                                // $('#appartement_' + type).prop("disabled" , true);

                                var locataire = $(
                                    "#locataire_id_detailscontrat"
                                ).val();
                                $("#locataire_inbox")
                                    .val(locataire)
                                    .trigger("change");

                                let infosContrat =
                                    $scope.dataPage["contrats"][0];
                                console.log('test test :', infosContrat);
                                $("#body_inbox").val(
                                    infosContrat.message_rappel_paiement
                                );
                                $("#subject_inbox").val("Rappel Paiement");
                                if (infosContrat.derniere_facture_loyer) {
                                    $scope.showfactureinbox =
                                        infosContrat.derniere_facture_loyer;
                                }
                                $("#contrat_inbox").val(infosContrat.id);
                                $("#appartement_inbox")
                                    .val(infosContrat.appartement.id)
                                    .trigger("change");
                                // <input type="hidden" value="@{{dataPage['contrats'][0]['id']}}" id="contrat_id_detailscontrat">
                                // console.log("IICI ICICIIC INBOX "+JSON.stringify(infosContrat));
                            }
                        }

                        if (type2 == "paiementloyereaux") {
                            if (type.indexOf("paiementloyer") !== -1) {
                                var rewriteReq =
                                    "factureeauxs(id:" + itemId + ")";
                                Init.getElement(
                                    rewriteReq,
                                    listofrequests_assoc["factureeauxs"]
                                ).then(
                                    function (data) {
                                        if (data && data.length > 0) {
                                            console.log(
                                                "data factureeauxs ",
                                                data[0]
                                            );
                                            console.log(
                                                "data factureeauxs ",
                                                data[0]["contrat"][
                                                "locataire_id"
                                                ]
                                            );
                                            $("#locataire_paiementloyer")
                                                .val(
                                                    data[0]["contrat"][
                                                    "locataire_id"
                                                    ]
                                                )
                                                .trigger("change");
                                            $("#appartement_paiementloyer")
                                                .val(
                                                    data[0]["contrat"][
                                                    "appartement_id"
                                                    ]
                                                )
                                                .trigger("change");
                                            console.log(
                                                "data factureeauxs ",
                                                $(
                                                    "#appartement_paiementloyer"
                                                ).val()
                                            );
                                            $(
                                                "#montantfacture_paiementloyer"
                                            ).val(
                                                data[0]["montanttotalfacture"]
                                            ).trigger;
                                        }
                                    },
                                    function (msg) {
                                        toastr.error(msg);
                                    }
                                );
                                $("#factureeaux_id_" + type).val(itemId);
                            }
                        }

                        if (type2 != "paiementloyereaux") {
                            if (type.indexOf("paiementloyer") !== -1) {
                                var rewriteReq =
                                    "facturelocations(id:" + itemId + ")";
                                Init.getElement(
                                    rewriteReq,
                                    listofrequests_assoc["facturelocations"]
                                ).then(
                                    function (data) {
                                        if (data && data.length > 0) {
                                            console.log(
                                                data[0]["contrat"]["locataire"][
                                                "id"
                                                ]
                                            );
                                            console.log('data : ', data);
                                            $("#locataire_paiementloyer")
                                                .val(
                                                    data[0]["contrat"][
                                                    "locataire"
                                                    ]["id"]
                                                )
                                                .trigger("change");

                                            $("#appartement_paiementloyer")
                                                .val(
                                                    data[0]["contrat"][
                                                    "appartement_id"
                                                    ]
                                                )
                                                .trigger("change");

                                            $("#contrat_paiementloyer").val(data[0]["contrat"]["id"]).trigger("change");

                                            console.log(data[0], "sdskdm");

                                            if (
                                                data[0]["typefacture"][
                                                "designation"
                                                ] === "electricite"
                                            ) {
                                                $(
                                                    "#montantfacture_paiementloyer"
                                                )
                                                    .val(data[0]["montant"])
                                                    .trigger("change");
                                            } else {
                                                let nbr =
                                                    data[0]["montant_total"];
                                                nbr = nbr.replace(/\s/g, "");
                                                nbr = parseFloat(nbr);
                                                $(
                                                    "#montantfacture_paiementloyer"
                                                )
                                                    .val(nbr)
                                                    .trigger("change");
                                            }
                                        }
                                    },
                                    function (msg) {
                                        toastr.error(msg);
                                    }
                                );
                                // if (type == "paiementloyer") {
                                $("#facturelocation_id_" + type).val(itemId);
                                // }
                            }
                        }
                    }
                    if (
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("list-detailslocationvente") !== -1
                    ) {
                        console.log("facture iciic libasse   ");
                        $scope.reInit("detailslocationvente");
                        if (type.indexOf("avisecheance") !== -1) {
                            $("#contrat_" + type).val(itemId);
                            var periodicite = $(
                                "#periodicite_id_detailscontrat"
                            ).val();
                            $("#periodicite_" + type)
                                .val(periodicite)
                                .change();
                        }
                        if (type.indexOf("factureacompte") !== -1) {
                            $("#contrat_" + type).val(itemId);
                        }
                        if (type.indexOf("facturelocation") !== -1) {
                            console.log("facture iciic libasse ", itemId);
                            console.log(
                                "facture iciic contrat ",
                                $("#contrat_id_detailslocationvente").val()
                            );
                            $("#locataire_facturelocation")
                                .val(itemId)
                                .change();
                            // $( '#locataire_facturelocation').prop( "disabled", true );
                            var contrat = $(
                                "#contrat_id_detailslocationvente"
                            ).val();

                            $("#contrat_facturelocation").val(contrat).change();
                            //    $('#contrat_facturelocation').prop( "disabled", true );
                        }

                        // $scope.reInit("inbox");
                        if (type.indexOf("inbox") !== -1) {
                            // console.log("IICI ICICIIC INBOX ");
                            $(".allfilesinbox").hide();
                            $(".filesinbox").show();
                            // $('#locataire_' + type).prop("disabled" , true);
                            // $('#subject_' + type).prop("disabled" , true);
                            // $('#appartement_' + type).prop("disabled" , true);

                            var locataire = $(
                                "#locataire_id_detailscontrat"
                            ).val();
                            $("#locataire_inbox")
                                .val(locataire)
                                .trigger("change");

                            let infosContrat =
                                $scope.dataPage["locationventes"][0];
                            $("#body_inbox").val(
                                infosContrat.message_rappel_paiement
                            );
                            $("#subject_inbox").val("Rappel Paiement");
                            if (infosContrat.derniere_facture_loyer) {
                                $scope.showfactureinbox =
                                    infosContrat.derniere_facture_loyer;
                            }
                            $("#contrat_inbox").val(infosContrat.id);
                            // $('#appartement_inbox').val(infosContrat.appartement.id).trigger("change");
                            // <input type="hidden" value="@{{dataPage['contrats'][0]['id']}}" id="contrat_id_detailscontrat">
                            // console.log("IICI ICICIIC INBOX "+JSON.stringify(infosContrat));
                        }

                        if (type.indexOf("paiementecheance") !== -1) {
                            $("#avisecheance_id_" + type).val(itemId);

                            $("#soldeclient_paiementecheance").addClass("hidden");

                            //charger le montant de la facture
                            $("#montantloyer_paiementecheance").prop("disabled", true);
                            // $("#montantaregler_paiementecheance").prop("disabled", true);
                            $("#montantaregler_paiementecheance").prop("readonly", true).css({ "background-color": "#d6d6d6" });




                            var rewriteReq =
                                "avisecheances(id:" + itemId + ")";
                            Init.getElement(
                                rewriteReq,
                                listofrequests_assoc["avisecheances"]
                            ).then(
                                function (data) {
                                    if (data && data.length > 0) {
                                        console.log('data data: ', data);
                                        var montant_total = data[0]["montant_total"];
                                        $("#montantloyer_paiementecheance").val(montant_total).trigger("change");

                                        var montantenattente = parseInt(data[0]["get_montantenattente"], 10) || 0;
                                        if (montantenattente === 0) {
                                            $("#montantaregler_paiementecheance").val(montant_total).trigger("change");
                                        } else if (montantenattente > 0) {
                                            $("#montantencaissement_paiementecheance").val(montantenattente).trigger("change");
                                            $("#montantaregler_paiementecheance").val(montantenattente).trigger("change");
                                        }


                                        $scope.soldeclient = data[0]["contrat"]["locataire"]["soldeclient"] ?? 0;
                                        soldeclient = data[0]["contrat"]["locataire"]["soldeclient_format"] ?? 0;
                                        $("#soldeclient_text").text("Le solde du client: " + soldeclient + " F CFA");
                                    }
                                },
                                function (msg) {
                                    toastr.error(msg);
                                }
                            );
                            // $("#montantaregler_paiementecheance").prop("disabled", true);
                            $("#montantaregler_paiementecheance").prop("readonly", true).css({ "background-color": "#d6d6d6" });


                            // var rewriteReq2 =
                            //     "paiementecheances(avisecheance_id:" + itemId + ")";
                            // Init.getElement(
                            //     rewriteReq2,
                            //     listofrequests_assoc["paiementecheances"]
                            // ).then(
                            //     function (data) {
                            //         if (data && data.length > 0) {
                            //             // console.log('data data: ', data);

                            //             var montantenattente = parseInt(data[0]["montantenattente"], 10) || 0;
                            //             if (montantenattente > 0){
                            //                 $("#montantencaissement_paiementecheance").val(montantenattente).trigger("change");
                            //                 $("#montantaregler_paiementecheance").val(montantenattente).trigger("change");
                            //             }
                            //         }
                            //     },
                            //     function (msg) {
                            //         toastr.error(msg);
                            //     }
                            // );
                        }

                        if (type.indexOf("paiementloyer") !== -1) {
                            var rewriteReq =
                                "facturelocations(id:" + itemId + ")";
                            Init.getElement(
                                rewriteReq,
                                listofrequests_assoc["facturelocations"]
                            ).then(
                                function (data) {
                                    if (data && data.length > 0) {
                                        console.log(
                                            data[0]["contrat"]["locataire"][
                                            "id"
                                            ]
                                        );
                                        $("#locataire_paiementloyer")
                                            .val(
                                                data[0]["contrat"]["locataire"][
                                                "id"
                                                ]
                                            )
                                            .trigger("change");
                                    }
                                },
                                function (msg) {
                                    toastr.error(msg);
                                }
                            );
                            // if (type == "paiementloyer") {
                            $("#facturelocation_id_" + type).val(itemId);
                            // }
                            $("#montantfacture_paiementloyer").prop("readonly", false).css({ "background-color": "" });

                        }

                        if (type.indexOf("apportponctuel") !== -1) {
                            $("#contrat_id_" + type).val(itemId);
                        }
                    }

                    if (
                        $scope.currentTemplateUrl.indexOf("list-etatlieu") !==
                        -1 &&
                        type.indexOf("situationdepot") !== -1
                    ) {
                        console.log("situationdepot");
                        $("#etatlieu_situationdepot")
                            .val(itemId)
                            .trigger("change");
                    }

                    if (
                        $scope.currentTemplateUrl.indexOf(
                            "list-factureintervention"
                        ) !== -1 &&
                        type.indexOf("paiementintervention") !== -1
                    ) {
                        //$("#locataire_paiementintervention").val(itemId);
                        $scope.dataPage["factureinterventions"].forEach(
                            (elmt) => {
                                if (elmt.id == itemId) {
                                    $("#locataire_paiementintervention")
                                        .val(elmt.locataire.id)
                                        .trigger("change");
                                }
                            }
                        );

                        //$('#appartement_paiementintervention').val(itemId);
                        $scope.dataPage["factureinterventions"].forEach(
                            (elem) => {
                                if (elem.id == itemId) {
                                    $("#appartement_paiementintervention")
                                        .val(elem.appartement.id)
                                        .trigger("change");
                                }
                            }
                        );
                    }

                    // recharger les  interventions
                    // if (
                    //     $scope.currentTemplateUrl.indexOf(
                    //         "list-demandeintervention"
                    //     ) !== -1 &&
                    //     type.indexOf("factureintervention") !== -1
                    // ) {

                    //     var rewriteReq = "interventions(demandeintervention_id:" + itemId + ")";
                    //     $scope.dataPage['interventions'] = [];
                    //     Init.getElement(rewriteReq, listofrequests_assoc["interventions"]).then(function (data) {
                    //         if (data && data.length > 0) {
                    //             console.log("data interventions ", data);
                    //             $scope.dataPage['interventions'].push(...data);
                    //         }
                    //     }, function (msg) {
                    //         console.log(msg);
                    //     });

                    //    $('#locataireintervention_factureintervention').val(itemId).change() ;
                    //    $('#datefactureintervention_factureintervention').val(new Date().toISOString().slice(0, 10)) ;
                    //    $('#demandeinterventiondetail_factureintervention').val(itemId).trigger('change') ;

                    //     $scope.dataPage["demandeinterventions"].forEach(
                    //         function (element) {
                    //             if (element.id === itemId) {
                    //                 $(
                    //                     "#locataireintervention_factureintervention"
                    //                 )
                    //                     .val(element.locataire.id)
                    //                     .change();
                    //             }
                    //         }
                    //     );
                    // }

                    if (
                        ($scope.currentTemplateUrl.indexOf(
                            "list-demandeintervention"
                        ) !== -1 ||
                            $scope.currentTemplateUrl.indexOf(
                                "list-etatlieu"
                            ) !== -1 ||
                            $scope.currentTemplateUrl.indexOf(
                                "list-detailsdemanderesiliation"
                            ) !== -1) &&
                        type.indexOf("factureintervention") !== -1
                    ) {
                        $scope.getelements("categorieinterventions");
                        var typS =
                            $scope.currentTemplateUrl.indexOf(
                                "list-demandeintervention"
                            ) !== -1
                                ? "demandeintervention"
                                : "etatlieu";

                        var idcheck = typS + "_id";

                        var rewriteReq =
                            "interventions(" + idcheck + ":" + itemId + ")";
                        $scope.dataPage["interventions"] = [];

                        Init.getElement(
                            rewriteReq,
                            listofrequests_assoc["interventions"]
                        ).then(
                            function (data) {
                                console.log(
                                    "data interventions  debuug final",
                                    data
                                );
                                if (data && data.length > 0) {
                                    // $scope.dataPage["interventions"].push(
                                    //     data[0]
                                    // );
                                    $scope.dataPage["interventions"] = data;
                                }
                                console.log(
                                    "data interventions  interventions debuug final",
                                    $scope.dataPage["interventions"]
                                );
                            },
                            function (msg) {
                                console.log(msg);
                            }
                        );

                        $("#datefactureintervention_factureintervention").val(
                            new Date().toISOString().slice(0, 10)
                        );

                        if (
                            $scope.currentTemplateUrl.indexOf(
                                "list-etatlieu"
                            ) !== -1
                        ) {
                            $("#etatlieuhidde_factureintervention").hide();
                            $("#etatlieu_factureintervention")
                                .val(itemId)
                                .trigger("change");
                        }
                        if (
                            $scope.currentTemplateUrl.indexOf(
                                "list-detailsdemanderesiliation"
                            ) !== -1
                        ) {
                            $("#etatlieuhidde_factureintervention").hide();
                            $("#etatlieu_factureintervention")
                                .val(itemId)
                                .trigger("change");
                        }
                        if (
                            $scope.currentTemplateUrl.indexOf(
                                "list-demandeintervention"
                            ) !== -1
                        ) {
                            $("#etatlieuhidde_factureintervention").show();
                            $("#demandeinterventiondetail_factureintervention")
                                .val(itemId)
                                .trigger("change");
                        }

                        $scope.dataPage[typS + "s"].forEach(function (element) {
                            if (element.id === itemId) {
                                $("#locataireintervention_factureintervention")
                                    .val(element.locataire.id)
                                    .change();
                            }
                        });
                    }

                    // moi
                    if (
                        $scope.currentTemplateUrl.indexOf(
                            "list-detailsdemanderesiliation"
                        ) !== -1 &&
                        type.indexOf("devi") !== -1
                    ) {
                        $("#etatlieu_id").val(itemId).trigger("change");
                    }

                    if (
                        $scope.currentTemplateUrl.indexOf("list-etatlieu") !==
                        -1 &&
                        type.indexOf("devi") !== -1
                    ) {
                        $("#etatlieu_id").val(itemId).trigger("change");
                    }

                    if (
                        $scope.currentTemplateUrl.indexOf(
                            "list-demandeintervention"
                        ) !== -1 &&
                        type.indexOf("devi") !== -1
                    ) {
                        console.log(
                            $scope.dataPage["soustypeinterventions"],
                            "liste soustypesinterventions"
                        );

                        $("#demandeintervention_id")
                            .val(itemId)
                            .trigger("change");

                        $scope.dataPage["demandeinterventions"].filter(
                            (element) => {
                                if (element.id == itemId) {
                                    $("#devi_appartement_demandeintervention")
                                        .val(element.appartement.id)
                                        .trigger("change");
                                }
                            }
                        );
                        $scope.dataPage["demandeinterventions"].filter(
                            (element) => {
                                if (element.id == itemId) {
                                    $("#devi_locataire_demandeintervention")
                                        .val(element.locataire.id)
                                        .trigger("change");
                                }
                            }
                        );
                    }

                    if (
                        ($scope.currentTemplateUrl.indexOf("list-etatlieu") !==
                            -1 ||
                            $scope.currentTemplateUrl.indexOf(
                                "list-detailsdemanderesiliation"
                            ) !== -1) &&
                        type.indexOf("devi") !== -1
                    ) {
                        console.log("je suis dans etatlieu devi");

                        // $("#demandeintervention_id")
                        //     .val(itemId)
                        //     .trigger("change");
                        console.log("etatlieu id ", itemId);
                        console.log(
                            "etatlieu id ",
                            $scope.dataPage["etatlieus"]
                        );
                        $scope.dataPage["etatlieus"].filter((element) => {
                            if (element.id == itemId) {
                                console.log("element ", element.appartement_id);
                                $("#devi_appartement_demandeintervention")
                                    .val(element.appartement_id)
                                    .trigger("change");
                            }
                        });

                        $scope.dataPage["etatlieus"].filter((element) => {
                            if (element.id == itemId) {
                                $("#devi_locataire_demandeintervention")
                                    .val(element.locataire.id)
                                    .trigger("change");
                            }
                        });
                    }

                    // moi paiement intervention
                    if (
                        $scope.currentTemplateUrl.indexOf(
                            "list-factureintervention"
                        ) !== -1 &&
                        type.indexOf("paiementintervention") !== -1
                    ) {
                        $("#factureinterventionid_paiementintervention")
                            .val(itemId)
                            .trigger("change");

                        $scope.dataPage["factureinterventions"].filter(
                            (element) => {
                                if (element.id == itemId) {
                                    $("#montant_paiementintervention").val(
                                        parseInt(
                                            element.montant_format.replace(
                                                /\s/g,
                                                ""
                                            )
                                        )
                                    );
                                }
                            }
                        );

                        console.log(
                            "factureinterventionid_paiementintervention " +
                            itemId
                        );
                    }

                    if (type.indexOf("appartement") !== -1) {
                        $("#immeuble_appartement_id").val(itemId);
                        $("#immeuble_appartement").val(itemId).change();

                        //    $('#'+item.id+'_oui_nombre').hide();

                        $("#immeuble_appartement").prop("disabled", true);
                    }

                    if (type.indexOf("etatlieu") !== -1) {
                        $scope.getelements("locataires");

                        $("#appartement_etatlieu").val(itemId).change();
                        console.log("appartement id : ", itemId);
                        // console.log("apartements list ", $scope.dataPage['villas']);
                        if (
                            $scope.currentTemplateUrl
                                .toLowerCase()
                                .indexOf("list-detailslocationvente") !== -1
                        ) {
                            console.log("is location vente 1");
                            $scope.dataPage["villas"].forEach((elmt) => {
                                if (elmt.id == itemId) {
                                    if (
                                        elmt.etatlieu == "0" ||
                                        elmt.etatlieu == null
                                    ) {
                                        $("#type_etatlieu")
                                            .val("entrée")
                                            .change();
                                    } else if (elmt.etatlieu == "1") {
                                        $("#type_etatlieu")
                                            .val("sortie")
                                            .change();
                                    }

                                    console.log(
                                        "location vente lis : ",
                                        $scope.dataPage["locationventes"]
                                    );
                                    console.log("appartement ");

                                    $scope.dataPage["locationventes"].forEach(
                                        (elmt2) => {
                                            console.log(
                                                "appartement contrat id  : ",
                                                elmt2.appartement.id
                                            );

                                            if (
                                                itemId == elmt2.appartement.id
                                            ) {
                                                // $( '#locataire_etatlieu').prop( "disabled", true );
                                                if (elmt2.locataire.prenom) {
                                                    console.log(
                                                        "prenom 1 : ",
                                                        elmt2.locataire
                                                    );
                                                    document.getElementById(
                                                        "locataire_etatlieu"
                                                    ).innerHTML = "";
                                                    $(
                                                        "#locataire_etatlieu"
                                                    ).append(
                                                        "<option value=" +
                                                        elmt2.locataire.id +
                                                        ' selected class="required">' +
                                                        elmt2.locataire
                                                            .prenom +
                                                        " " +
                                                        elmt2.locataire
                                                            .nom +
                                                        "</option>"
                                                    );
                                                    //    $('#locataire_etatlieu').val(elmt2.locataire.id).change() ;
                                                    //    $('#locataire_etatlieu').val(elmt2.locataire.prenom+ ' ' +elmt2.locataire.nom).change() ;
                                                }
                                                if (
                                                    elmt2.locataire
                                                        .nomentreprise
                                                ) {
                                                    document.getElementById(
                                                        "locataire_etatlieu"
                                                    ).innerHTML = "";
                                                    $(
                                                        "#locataire_etatlieu"
                                                    ).append(
                                                        "<option value=" +
                                                        elmt2.locataire.id +
                                                        ' selected class="required">' +
                                                        elmt2.locataire
                                                            .nomentreprise +
                                                        "</option>"
                                                    );

                                                    // $('#locataire_etatlieu').val(elmt2.locataire.id).change() ;
                                                }
                                            }
                                        }
                                    );

                                    $("#type_etatlieu").prop("disabled", true);
                                }
                            });
                        } else {
                            console.log("does not location");
                            $scope.dataPage["appartements"].forEach((elmt) => {
                                if (elmt.id == itemId) {
                                    console.log(
                                        "appartement 2 test  id : ",
                                        itemId
                                    );
                                    if (elmt.etatlieu == "0") {
                                        $("#type_etatlieu")
                                            .val("entrée")
                                            .change();
                                    } else if (elmt.etatlieu == "1") {
                                        $("#type_etatlieu")
                                            .val("sortie")
                                            .change();
                                    }
                                    console.log(elmt["contrats"]);
                                    elmt["contrats"].forEach((elmt2) => {
                                        if (elmt2.locataire.prenom) {
                                            console.log(elmt2.locataire.prenom);
                                            document.getElementById(
                                                "locataire_etatlieu"
                                            ).innerHTML = "";
                                            $("#locataire_etatlieu").append(
                                                "<option value=" +
                                                elmt2.locataire.id +
                                                ' selected class="required">' +
                                                elmt2.locataire.prenom +
                                                " " +
                                                elmt2.locataire.nom +
                                                "</option>"
                                            );

                                            //  $('#locataire_etatlieu').val(elmt2.locataire.prenom+ ' ' +elmt2.locataire.nom).change() ;
                                        }
                                        if (elmt2.locataire.nomentreprise) {
                                            document.getElementById(
                                                "locataire_etatlieu"
                                            ).innerHTML = "";
                                            $("#locataire_etatlieu").append(
                                                "<option value=" +
                                                elmt2.locataire.id +
                                                ' selected class="required">' +
                                                elmt2.locataire
                                                    .nomentreprise +
                                                "</option>"
                                            );
                                        }
                                    });

                                    $("#type_etatlieu").prop("disabled", true);
                                }
                            });
                        }

                        $("#appartement_etatlieu").prop("disabled", true);
                        $("#id_appartement_etatlieu").val(itemId);
                        //  $( '#locataire_etatlieu').prop( "disabled", true );

                        //  $scope.compositionappartement = [] ;
                        var Idappartement = itemId;
                        $scope.compositionappartement = [];
                        $scope.detailcompositionappartement = [];
                        var typeAvecS2 = "compositions";
                        Init.getElement(
                            "detailcompositions(appartement_id:" +
                            Idappartement +
                            ")",
                            listofrequests_assoc["detailcompositions"]
                        ).then(
                            function (data2) {
                                console.log("data2", data2);
                                data2.forEach((elmt2) => {
                                    //  $scope.detailcompositionappartement.push(elmt2) ;
                                    const resultId1 =
                                        $scope.detailcompositionappartement.find(
                                            (d) => d.id == elmt2.id
                                        );

                                    if (!resultId1) {
                                        $scope.detailcompositionappartement.push(
                                            elmt2
                                        );
                                    }
                                });
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );
                        rewriteReq2 =
                            typeAvecS2 +
                            "(appartement_id:" +
                            Idappartement +
                            ")";
                        Init.getElement(
                            rewriteReq2,
                            listofrequests_assoc[typeAvecS2]
                        ).then(
                            function (data) {
                                console.log("data", data);
                                $scope.compositionappartement = data;
                                data.forEach((elmt) => {
                                    Init.getElement(
                                        "detailcompositions(composition_id:" +
                                        elmt.id +
                                        ")",
                                        listofrequests_assoc[
                                        "detailcompositions"
                                        ]
                                    ).then(
                                        function (data2) {
                                            console.log("data2", data2);
                                            data2.forEach((elmt2) => {
                                                // $scope.detailcompositionappartement.push(elmt2) ;
                                                const resultId =
                                                    $scope.detailcompositionappartement.find(
                                                        (d) => d.id == elmt2.id
                                                    );

                                                if (!resultId) {
                                                    $scope.detailcompositionappartement.push(
                                                        elmt2
                                                    );
                                                }
                                            });
                                        },
                                        function (msg) {
                                            $scope.showToast("", msg, "error");
                                        }
                                    );
                                });
                                // $scope.detailspiece  = data;
                                // $scope.reInit("typeappartement_piece");
                                //    console.log($scope.detailspiece) ;

                                console.log($scope.compositionappartement);
                                console.log(
                                    $scope.detailcompositionappartement
                                );
                            },
                            function (msg) {
                                $scope.showToast("", msg, "error");
                            }
                        );
                        setTimeout(function () {
                            $scope.reInit("etatlieu");
                        }, 4000);
                    }

                    // chargement du contrat avec son locataire

                    if (
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("list-facturelocation") !== -1
                    ) {
                        console.log("entre ici " + itemId);
                        if ((type = "paiementloyer")) {
                            var rewriteReq =
                                "facturelocations(id:" + itemId + ")";
                            Init.getElement(
                                rewriteReq,
                                listofrequests_assoc["facturelocations"]
                            ).then(
                                function (data) {
                                    if (data && data.length > 0) {
                                        console.log(
                                            data[0]["contrat"]["locataire"][
                                            "id"
                                            ]
                                        );
                                        $("#locataire_paiementloyer")
                                            .val(
                                                data[0]["contrat"]["locataire"][
                                                "id"
                                                ]
                                            )
                                            .trigger("change");
                                    }
                                },
                                function (msg) {
                                    toastr.error(msg);
                                }
                            );
                            $("#facturelocation_id_" + type).val(itemId);
                        }
                    }
                }

                if (type.indexOf("caution") !== -1) {
                    console.log(itemId);
                    $scope.dataPage["contrats"].forEach((elmt) => {
                        if (elmt.id == itemId) {
                            $scope.IdAjoutParent = elmt.id;
                            document.getElementById(
                                "div_contrat_caution"
                            ).innerHTML =
                                "<label><strong>Contrat:</strong> " +
                                elmt.descriptif +
                                "</label>";
                            $("#contrat_caution").val(elmt.id);

                            if (elmt.locataire.prenom) {
                                document.getElementById(
                                    "div_locataire_caution"
                                ).innerHTML =
                                    "<label><strong>Locataire:</strong> " +
                                    elmt.locataire.prenom +
                                    "  " +
                                    elmt.locataire.nom +
                                    '</label> <input type="hidden" id="locataire_caution" name="locataire" class="input w-full border mt-2 flex-1" placeholder="locataire">';
                            }

                            if (elmt.locataire.nomentreprise) {
                                document.getElementById(
                                    "div_locataire_caution"
                                ).innerHTML =
                                    "<label><strong>Locataire:</strong> " +
                                    elmt.locataire.nomentreprise +
                                    '</label> <input type="hidden" id="locataire_caution" name="locataire" class="input w-full border mt-2 flex-1" placeholder="locataire">';
                            }
                            document.getElementById(
                                "div_appartement_caution"
                            ).innerHTML =
                                "<label><strong>Appartement:</strong> " +
                                elmt.appartement.nom +
                                '</label> <input type="hidden" id="appartement_caution"  value=' +
                                elmt.appartement.codeappartement +
                                ' name="appartement" class="input w-full border mt-2 flex-1" placeholder="appartement">';

                            document.getElementById(
                                "div_montantloyer_caution"
                            ).innerHTML =
                                "<label><strong>Loyer:</strong> " +
                                elmt.montantloyer +
                                '</label> <input type="hidden" id="montantloyer_caution" name="montantloyer" class="input w-full border mt-2 flex-1" placeholder="montant">';
                            $("#montantloyer_caution")
                                .val(elmt.montantloyer)
                                .change();

                            console.log($("#contrat_caution").val());
                            console.log($("#montantloyer_caution").val());
                        }
                    });
                }

                if (type.indexOf("assurance") !== -1) {
                    console.log(itemId);
                    $scope.dataPage["contrats"].forEach((elmt) => {
                        if (elmt.id == itemId) {
                            $scope.IdAjoutParent = elmt.id;
                            document.getElementById(
                                "div_contrat_assurance"
                            ).innerHTML =
                                "<label><strong>Contrat:</strong> " +
                                elmt.descriptif +
                                "</label>";
                            $("#contrat_assurance").val(elmt.id);

                            if (elmt.locataire.prenom) {
                                document.getElementById(
                                    "div_locataire_assurance"
                                ).innerHTML =
                                    "<label><strong>Locataire:</strong> " +
                                    elmt.locataire.prenom +
                                    "  " +
                                    elmt.locataire.nom +
                                    '</label> <input type="hidden" id="locataire_assurance" name="locataire" class="input w-full border mt-2 flex-1" placeholder="locataire">';
                            }
                            if (elmt.locataire.nomentreprise) {
                                document.getElementById(
                                    "div_locataire_assurance"
                                ).innerHTML =
                                    "<label><strong>Locataire:</strong> " +
                                    elmt.locataire.nomentreprise +
                                    '</label> <input type="hidden" id="locataire_assurance" name="locataire" class="input w-full border mt-2 flex-1" placeholder="locataire">';
                            }
                            document.getElementById(
                                "div_appartement_assurance"
                            ).innerHTML =
                                "<label><strong>Appartement:</strong> " +
                                elmt.appartement.nom +
                                '</label> <input type="hidden" id="appartement_assurance"  value=' +
                                elmt.appartement.codeappartement +
                                ' name="appartement" class="input w-full border mt-2 flex-1" placeholder="appartement">';

                            document.getElementById(
                                "div_montantloyer_assurance"
                            ).innerHTML =
                                "<label><strong>Loyer:</strong> " +
                                elmt.montantloyer +
                                '</label> <input type="hidden" id="montantloyer_caution" name="montantloyer" class="input w-full border mt-2 flex-1" placeholder="montant">';
                            $("#montantloyer_assussurance")
                                .val(elmt.montantloyer)
                                .change();

                            console.log($("#contrat_assurance").val());
                            console.log($("#montantloyer_assurance").val());
                        }
                    });
                }

                if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-appartement") !== -1
                ) {
                    $scope.reInit("contrat");
                    $scope.reInit("equipementpiece");
                    $scope.dataPage["appartements"].forEach((elmt) => {
                        if (elmt.id == itemId) {
                            $("#appartement_contrat_id").val(elmt.id);
                            $("#appartement_contrat").val(elmt.id).change();
                            $("#appartement_contrat").prop("disabled", true);

                            //                        document.getElementById('appartement_contrat').innerHTML = "<option value=\"\" selected class=\"required\">"+elmt.nom+"</option>" ;
                        }
                    });
                    $scope.dataPage["rappelpaiementloyers"] = [
                        {
                            id: "5",
                            libelle: "Le 05 de chaque mois",
                        },
                        {
                            id: "6",
                            libelle: "Le 06 de chaque mois",
                        },
                        {
                            id: "7",
                            libelle: "Le 07 de chaque mois",
                        },
                        {
                            id: "8",
                            libelle: "Le 08 de chaque mois",
                        },
                        {
                            id: "9",
                            libelle: "Le 09 de chaque mois",
                        },
                        {
                            id: "10",
                            libelle: "Le 10 de chaque mois",
                        },
                        {
                            id: "11",
                            libelle: "Le 11 de chaque mois",
                        },
                        {
                            id: "12",
                            libelle: "Le 12 de chaque mois",
                        },
                        {
                            id: "13",
                            libelle: "Le 13 de chaque mois",
                        },
                        {
                            id: "14",
                            libelle: "Le 14 de chaque mois",
                        },
                        {
                            id: "15",
                            libelle: "Le 15 de chaque mois",
                        },
                        {
                            id: "16",
                            libelle: "Le 16 de chaque mois",
                        },
                        {
                            id: "17",
                            libelle: "Le 17 de chaque mois",
                        },
                        {
                            id: "18",
                            libelle: "Le 18 de chaque mois",
                        },
                        {
                            id: "19",
                            libelle: "Le 19 de chaque mois",
                        },
                        {
                            id: "20",
                            libelle: "Le 20 de chaque mois",
                        },
                        {
                            id: "21",
                            libelle: "Le 21 de chaque mois",
                        },
                        {
                            id: "22",
                            libelle: "Le 22 de chaque mois",
                        },
                        {
                            id: "23",
                            libelle: "Le 23 de chaque mois",
                        },
                        {
                            id: "24",
                            libelle: "Le 24 de chaque mois",
                        },
                        {
                            id: "25",
                            libelle: "Le 25 de chaque mois",
                        },
                    ];
                }
            }

            if (!optionals.fromUpdate && !optionals.is_file_excel) {
                console.log("Not enter fromupdate " + conserveFilter);
                if (type.indexOf("etatlieu") !== -1) {
                    // $scope.notEnterEtatLieu = false;

                    $scope.compositionappartementchange = [];
                    $scope.detailcompositionappartementchange = [];
                    console.log(
                        "Not enter fromupdate 1 fromup : " +
                        optionals.fromUpdate +
                        " yo itemId " +
                        itemId
                    );
                    var id_etatlieu = $("#id_etatlieu").val();
                    if (!id_etatlieu) {
                        console.log("IT'S TRUE TRUE TRUE VERIFY");

                        console.log("Libasse DEV 1");
                        $("#appartement_" + type).on("change", function () {
                            console.log("Libasse DEV 2");
                            console.log(
                                "Not enter fromupdate 2" + optionals.fromUpdate
                            );

                            var Idvalueapp = $("#appartement_" + type).val();
                            console.log("APPPARTEMNET VALUE : " + Idvalueapp);
                            // $('#appartement_'+type).val(Idvalueapp).change();
                            var typeAvecS = "appartements";
                            rewriteReq = typeAvecS + "(id:" + Idvalueapp + ")";
                            // console.log(rewriteReq) ;
                            // if(!conserveFilter && itemId == null){
                            if (Idvalueapp) {
                                console.log("entre ici vrai ===");
                                Init.getElement(
                                    rewriteReq,
                                    listofrequests_assoc[typeAvecS]
                                ).then(
                                    function (data) {
                                        console.log("data", data);

                                        // $('#locataire_' + type).val()
                                        var elmt = data[0];
                                        if (elmt.etatlieu == "0") {
                                            $("#type_etatlieu")
                                                .val("entrée")
                                                .change();
                                        } else if (elmt.etatlieu == "1") {
                                            $("#type_etatlieu")
                                                .val("sortie")
                                                .change();
                                        }
                                        $getLocataireFromContrat = null;
                                        $getLocataireFromContrat = elmt[
                                            "contrats"
                                        ].find((data) => data.etat == 1);
                                        console.log(
                                            "locataire tab !!!!!!!!!!!" +
                                            $getLocataireFromContrat
                                        );
                                        if ($getLocataireFromContrat) {
                                            $("#locataire_" + type)
                                                .val(
                                                    $getLocataireFromContrat[
                                                        "locataire"
                                                    ].id
                                                )
                                                .change();
                                        }
                                        // elmt['contrats'].forEach((elmt2) => {

                                        // if(elmt2.etat === 1){
                                        // console.log(elmt2.locataire) ;
                                        // document.getElementById('locataire_etatlieu').innerHTML = "" ;
                                        // $('#locataire_etatlieu').append(
                                        //     "<option value="+elmt2.locataire.id+" selected class=\"required\">"+elmt2.locataire.prenom+ ' ' +elmt2.locataire.nom+"</option>"
                                        // );
                                        // $('#locataire_etatlieu').val(elmt2.locataire.id).change() ;
                                        // }
                                        // else if(elmt2.etat == 1 && elmt2.locataire.nomentreprise){
                                        //     document.getElementById('locataire_etatlieu').innerHTML = "" ;
                                        //     $('#locataire_etatlieu').append(
                                        //         "<option value="+elmt2.locataire.id+" selected class=\"required\">"+elmt2.locataire.nomentreprise+"</option>"
                                        //     );
                                        // }else{
                                        //     document.getElementById('locataire_etatlieu').innerHTML = "" ;
                                        // }

                                        // });

                                        $("#type_etatlieu").prop(
                                            "disabled",
                                            true
                                        );
                                        // $( '#locataire_etatlieu').prop( "disabled", true );
                                        // $scope.detailspiece  = data;
                                        // $scope.reInit("typeappartement_piece");
                                        //console.log($scope.detailspiece) ;
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );

                                var Idappartement = Idvalueapp;
                                $scope.compositionappartement = [];
                                $scope.detailcompositionappartement = [];
                                var typeAvecS2 = "compositions";
                                Init.getElement(
                                    "detailcompositions(appartement_id:" +
                                    Idappartement +
                                    ")",
                                    listofrequests_assoc["detailcompositions"]
                                ).then(
                                    function (data2) {
                                        console.log("data2", data2);
                                        var array = [];
                                        data2.forEach((elmt2) => {
                                            //array.push(elmt2);
                                            const resultId3 =
                                                $scope.detailcompositionappartement.find(
                                                    (d) => d.id == elmt2.id
                                                );

                                            if (!resultId3) {
                                                $scope.detailcompositionappartement.push(
                                                    elmt2
                                                );
                                            }
                                        });

                                        // $scope.detailcompositionappartement = array;
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );
                                rewriteReq2 =
                                    typeAvecS2 +
                                    "(appartement_id:" +
                                    Idappartement +
                                    ")";
                                Init.getElement(
                                    rewriteReq2,
                                    listofrequests_assoc[typeAvecS2]
                                ).then(
                                    function (data) {
                                        console.log("data", data);
                                        $scope.compositionappartement = data;
                                        data.forEach((elmt) => {
                                            Init.getElement(
                                                "detailcompositions(composition_id:" +
                                                elmt.id +
                                                ")",
                                                listofrequests_assoc[
                                                "detailcompositions"
                                                ]
                                            ).then(
                                                function (data2) {
                                                    console.log("data2", data2);
                                                    var array2 = [];
                                                    data2.forEach((elmt2) => {
                                                        // const result = null;
                                                        const result =
                                                            $scope.detailcompositionappartement.find(
                                                                (d) =>
                                                                    d.id ==
                                                                    elmt2.id
                                                            );

                                                        if (!result) {
                                                            $scope.detailcompositionappartement.push(
                                                                elmt2
                                                            );
                                                        }
                                                        console.log(
                                                            "result " +
                                                            JSON.stringify(
                                                                result
                                                            )
                                                        );
                                                    });
                                                    //    $scope.detailcompositionappartement = array2;
                                                },
                                                function (msg) {
                                                    $scope.showToast(
                                                        "",
                                                        msg,
                                                        "error"
                                                    );
                                                }
                                            );
                                        });
                                        // $scope.detailspiece  = data;
                                        // $scope.reInit("typeappartement_piece");
                                        //    console.log($scope.detailspiece) ;

                                        console.log(
                                            $scope.compositionappartement
                                        );
                                        console.log(
                                            $scope.detailcompositionappartement
                                        );
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );
                            }
                        });

                        setTimeout(function () {
                            $scope.reInit("etatlieu");
                        }, 2000);
                    }
                }
            }
            if (
                $scope.currentTemplateUrl
                    .toLowerCase()
                    .indexOf("list-contrat") !== -1
            ) {
                console.log("it's true");
                $scope.dataPage["rappelpaiementloyers"] = [
                    {
                        id: "5",
                        libelle: "Le 05 de chaque mois",
                    },
                    {
                        id: "6",
                        libelle: "Le 06 de chaque mois",
                    },
                    {
                        id: "7",
                        libelle: "Le 07 de chaque mois",
                    },
                    {
                        id: "8",
                        libelle: "Le 08 de chaque mois",
                    },
                    {
                        id: "9",
                        libelle: "Le 09 de chaque mois",
                    },
                    {
                        id: "10",
                        libelle: "Le 10 de chaque mois",
                    },
                    {
                        id: "11",
                        libelle: "Le 11 de chaque mois",
                    },
                    {
                        id: "12",
                        libelle: "Le 12 de chaque mois",
                    },
                    {
                        id: "13",
                        libelle: "Le 13 de chaque mois",
                    },
                    {
                        id: "14",
                        libelle: "Le 14 de chaque mois",
                    },
                    {
                        id: "15",
                        libelle: "Le 15 de chaque mois",
                    },
                    {
                        id: "16",
                        libelle: "Le 16 de chaque mois",
                    },
                    {
                        id: "17",
                        libelle: "Le 17 de chaque mois",
                    },
                    {
                        id: "18",
                        libelle: "Le 18 de chaque mois",
                    },
                    {
                        id: "19",
                        libelle: "Le 19 de chaque mois",
                    },
                    {
                        id: "20",
                        libelle: "Le 20 de chaque mois",
                    },
                    {
                        id: "21",
                        libelle: "Le 21 de chaque mois",
                    },
                    {
                        id: "22",
                        libelle: "Le 22 de chaque mois",
                    },
                    {
                        id: "23",
                        libelle: "Le 23 de chaque mois",
                    },
                    {
                        id: "24",
                        libelle: "Le 24 de chaque mois",
                    },
                    {
                        id: "25",
                        libelle: "Le 25 de chaque mois",
                    },
                ];
            }

            $("#modal_add" + (optionals.is_file_excel ? "list" : type)).modal(
                "show",
                {
                    backdrop: "static",
                }
            );
        };
        // showmodal add 2
        $scope.showModalAddPaiementEcheance = function (
            type,
            optionals = {
                is_file_excel: false,
                title: null,
                fromUpdate: false,
            },
            itemId = null,
            type_link = null
        ) {
            $(".1").hide();
            $(".2").hide();
            $(".entreprise").hide();
            // $('.filesinbox').show();
            $scope.hideButton = true;
            $scope.initVariableScope();
            $scope.reInit();
            $("#toutcocher").prop("checked", false);

            $scope.currentTitleModal = optionals.title;
            $scope.currentTypeModal = type;
            var fromPage = false;
            var conserveFilter = optionals.fromUpdate ? true : false;
            $scope.emptyform(
                optionals.is_file_excel ? "liste" : type,
                fromPage,
                conserveFilter
            );

            if (!optionals.is_file_excel) {
                if (type == "paiementloyer") {
                    alert("teststts");
                    console.log(
                        "je suis iciciic lib " + $scope.dataPage["contrats"][0]
                    );
                    $(".numerochequepaiementloyer").hide();
                    if (
                        $scope.dataPage["contrats"] &&
                        $scope.dataPage["contrats"][0]
                    ) {
                        var contratId = $routeParams.itemId;
                        $("#appartement_" + type).val(
                            $scope.dataPage["contrats"][0]["appartement"]["id"]
                        );
                        // $("#contrat_id_paiementloyer").val(contratId);
                        $("#locataire_" + type).val(
                            $scope.dataPage["contrats"][0]["locataire"]["id"]
                        );
                    }
                }
                // if (type == "paiementecheance") {
                //     $(".numerochequepaiementecheance").hide();
                // }

                if (type == "immeuble") {
                    $scope.dataPage["typepieces"].forEach((elmt) => {
                        $("#" + elmt.id + "_id_oui_nombre").hide();
                        $("#" + elmt.id + "_id_oui_nomsalledefete").hide();
                        $(".classeautre").hide();
                    });
                }
                if (type == "locationvente") {
                    $("#isRidwan_locationvente").val("1");
                }

                if (type == "locataire") {
                    $("#nomcompletpersonnepriseencharge_locataire").hide();
                    $("#telephonepersonnepriseencharge_locataire").hide();
                }

                if (type == "demanderesiliation") {
                    $("#raisonnonrespectdelai_demanderesiliation").hide();
                    $("#raison").hide();
                }
                if (type == "demandeintervention") {
                    $("#typeappartementdiv").hide();
                    $("#typelocatairediv").hide();
                    $("#typeimmeublediv").hide();

                    $(".appintervention").hide();
                    $(".immeubleintervention").hide();
                    $(".appintervention").hide();
                    $(".immeubleintervention").hide();
                }
                if (type == "intervention") {
                    $(".employeintervention").hide();
                    $(".prestataireintervention").hide();
                }

                if (type == "contrat") {
                    $(".locataireexistant").hide();
                    $(".nouveaulocataire").hide();
                    $("#nomcompletpersonnepriseencharge_locataire").hide();
                    $("#telephonepersonnepriseencharge_locataire").hide();

                    if ($(".appartement_append")) {
                        $(".appartement_append").each(function () {
                            $(this).remove();
                        });
                    }
                    $("#caution_document_contrat").hide();
                    var check_typelocataire = $("#check_typelocataire");
                    if (check_typelocataire.attr("checked") === "checked") {
                        check_typelocataire.removeAttr("checked");
                    }
                }
                if (type == "obligationadministrative") {
                    $(".immeubleObligationadministrative").hide();
                    $(".appartementObligationadministrative").hide();
                }
                if (type == "assurance") {
                    $(".assurancerenouvelle").hide();
                    $(".nonrenouvelle").hide();
                }

                if (type == "rapportintervention") {
                    $("#divappartement_rapportintervention").hide();
                }

                if (type == "annonce") {
                    $(".appartementannonce").hide();
                }

                if (type == "facture") {
                    $(".interventionfacture").hide();
                    $(".appartementfacture").hide();
                }

                //modaladd_user
                if (type.indexOf("user") !== -1) {
                    $(".prestataireuser").hide();
                    if ($scope.currentTemplateUrl.indexOf("list-user") == -1) {
                        // $scope.getelements("roles");
                    }
                }
                //modaladd_role
                else if (type.indexOf("role") !== -1) {
                    $scope.roleview = null;
                    $scope.role_permissions = [];
                    $scope.getelements("permissions");
                }

                if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-inbox") !== -1
                ) {
                    if (type.indexOf("inbox") !== -1) {
                        // console.log("IICI ICICIIC INBOX ");
                        $(".allfilesinbox").show();
                        $(".filesinbox").hide();
                    }
                }

                if (type.indexOf("facturelocation") !== -1) {
                    document.getElementById(
                        "datefacture_facturelocation"
                    ).valueAsDate = new Date();
                    console.log("icic paiement loyer 2023 ");

                    // $(document).ready(function() {
                    // Au chargement de la page, masquer champB
                    $(".moiscautionfacturelocation").hide();
                    // Lorsque la valeur du menu déroulant change
                    $("#typefacture_facturelocation").change(function () {
                        var selectedType = $(
                            "#typefacture_facturelocation"
                        ).text();
                        console.log("change typefcture ", selectedType);
                        if (selectedType === "caution") {
                            $(".moiscautionfacturelocation").show();
                        } else {
                            $(".moiscautionfacturelocation").hide();
                        }
                    });
                    // });
                }

                $scope.notEnterEtatLieu = false;
                $("#contrat_facturelocation").prop("disabled", false);
                $("#locataire_facturelocation").prop("disabled", false);

                if (itemId) {
                    if (
                        $scope.currentTemplateUrl
                            .toLowerCase()
                            .indexOf("list-detailslocationvente") !== -1
                    ) {
                        if (type.indexOf("paiementecheance") !== -1) {
                            $("#factureacompte_id_" + type).val(itemId);
                            $("#isacompte_paiementecheance").val(1);
                        }
                    }
                }

                if (type.indexOf("caution") !== -1) {
                    console.log(itemId);
                    $scope.dataPage["contrats"].forEach((elmt) => {
                        if (elmt.id == itemId) {
                            $scope.IdAjoutParent = elmt.id;
                            document.getElementById(
                                "div_contrat_caution"
                            ).innerHTML =
                                "<label><strong>Contrat:</strong> " +
                                elmt.descriptif +
                                "</label>";
                            $("#contrat_caution").val(elmt.id);

                            if (elmt.locataire.prenom) {
                                document.getElementById(
                                    "div_locataire_caution"
                                ).innerHTML =
                                    "<label><strong>Locataire:</strong> " +
                                    elmt.locataire.prenom +
                                    "  " +
                                    elmt.locataire.nom +
                                    '</label> <input type="hidden" id="locataire_caution" name="locataire" class="input w-full border mt-2 flex-1" placeholder="locataire">';
                            }

                            if (elmt.locataire.nomentreprise) {
                                document.getElementById(
                                    "div_locataire_caution"
                                ).innerHTML =
                                    "<label><strong>Locataire:</strong> " +
                                    elmt.locataire.nomentreprise +
                                    '</label> <input type="hidden" id="locataire_caution" name="locataire" class="input w-full border mt-2 flex-1" placeholder="locataire">';
                            }
                            document.getElementById(
                                "div_appartement_caution"
                            ).innerHTML =
                                "<label><strong>Appartement:</strong> " +
                                elmt.appartement.nom +
                                '</label> <input type="hidden" id="appartement_caution"  value=' +
                                elmt.appartement.codeappartement +
                                ' name="appartement" class="input w-full border mt-2 flex-1" placeholder="appartement">';

                            document.getElementById(
                                "div_montantloyer_caution"
                            ).innerHTML =
                                "<label><strong>Loyer:</strong> " +
                                elmt.montantloyer +
                                '</label> <input type="hidden" id="montantloyer_caution" name="montantloyer" class="input w-full border mt-2 flex-1" placeholder="montant">';
                            $("#montantloyer_caution")
                                .val(elmt.montantloyer)
                                .change();

                            console.log($("#contrat_caution").val());
                            console.log($("#montantloyer_caution").val());
                        }
                    });
                }

                if (type.indexOf("assurance") !== -1) {
                    console.log(itemId);
                    $scope.dataPage["contrats"].forEach((elmt) => {
                        if (elmt.id == itemId) {
                            $scope.IdAjoutParent = elmt.id;
                            document.getElementById(
                                "div_contrat_assurance"
                            ).innerHTML =
                                "<label><strong>Contrat:</strong> " +
                                elmt.descriptif +
                                "</label>";
                            $("#contrat_assurance").val(elmt.id);

                            if (elmt.locataire.prenom) {
                                document.getElementById(
                                    "div_locataire_assurance"
                                ).innerHTML =
                                    "<label><strong>Locataire:</strong> " +
                                    elmt.locataire.prenom +
                                    "  " +
                                    elmt.locataire.nom +
                                    '</label> <input type="hidden" id="locataire_assurance" name="locataire" class="input w-full border mt-2 flex-1" placeholder="locataire">';
                            }
                            if (elmt.locataire.nomentreprise) {
                                document.getElementById(
                                    "div_locataire_assurance"
                                ).innerHTML =
                                    "<label><strong>Locataire:</strong> " +
                                    elmt.locataire.nomentreprise +
                                    '</label> <input type="hidden" id="locataire_assurance" name="locataire" class="input w-full border mt-2 flex-1" placeholder="locataire">';
                            }
                            document.getElementById(
                                "div_appartement_assurance"
                            ).innerHTML =
                                "<label><strong>Appartement:</strong> " +
                                elmt.appartement.nom +
                                '</label> <input type="hidden" id="appartement_assurance"  value=' +
                                elmt.appartement.codeappartement +
                                ' name="appartement" class="input w-full border mt-2 flex-1" placeholder="appartement">';

                            document.getElementById(
                                "div_montantloyer_assurance"
                            ).innerHTML =
                                "<label><strong>Loyer:</strong> " +
                                elmt.montantloyer +
                                '</label> <input type="hidden" id="montantloyer_caution" name="montantloyer" class="input w-full border mt-2 flex-1" placeholder="montant">';
                            $("#montantloyer_assussurance")
                                .val(elmt.montantloyer)
                                .change();

                            console.log($("#contrat_assurance").val());
                            console.log($("#montantloyer_assurance").val());
                        }
                    });
                }

                if (
                    $scope.currentTemplateUrl
                        .toLowerCase()
                        .indexOf("list-appartement") !== -1
                ) {
                    $scope.reInit("contrat");
                    $scope.reInit("equipementpiece");
                    $scope.dataPage["appartements"].forEach((elmt) => {
                        if (elmt.id == itemId) {
                            $("#appartement_contrat_id").val(elmt.id);
                            $("#appartement_contrat").val(elmt.id).change();
                            $("#appartement_contrat").prop("disabled", true);

                            //                        document.getElementById('appartement_contrat').innerHTML = "<option value=\"\" selected class=\"required\">"+elmt.nom+"</option>" ;
                        }
                    });
                    $scope.dataPage["rappelpaiementloyers"] = [
                        {
                            id: "5",
                            libelle: "Le 05 de chaque mois",
                        },
                        {
                            id: "6",
                            libelle: "Le 06 de chaque mois",
                        },
                        {
                            id: "7",
                            libelle: "Le 07 de chaque mois",
                        },
                        {
                            id: "8",
                            libelle: "Le 08 de chaque mois",
                        },
                        {
                            id: "9",
                            libelle: "Le 09 de chaque mois",
                        },
                        {
                            id: "10",
                            libelle: "Le 10 de chaque mois",
                        },
                        {
                            id: "11",
                            libelle: "Le 11 de chaque mois",
                        },
                        {
                            id: "12",
                            libelle: "Le 12 de chaque mois",
                        },
                        {
                            id: "13",
                            libelle: "Le 13 de chaque mois",
                        },
                        {
                            id: "14",
                            libelle: "Le 14 de chaque mois",
                        },
                        {
                            id: "15",
                            libelle: "Le 15 de chaque mois",
                        },
                        {
                            id: "16",
                            libelle: "Le 16 de chaque mois",
                        },
                        {
                            id: "17",
                            libelle: "Le 17 de chaque mois",
                        },
                        {
                            id: "18",
                            libelle: "Le 18 de chaque mois",
                        },
                        {
                            id: "19",
                            libelle: "Le 19 de chaque mois",
                        },
                        {
                            id: "20",
                            libelle: "Le 20 de chaque mois",
                        },
                        {
                            id: "21",
                            libelle: "Le 21 de chaque mois",
                        },
                        {
                            id: "22",
                            libelle: "Le 22 de chaque mois",
                        },
                        {
                            id: "23",
                            libelle: "Le 23 de chaque mois",
                        },
                        {
                            id: "24",
                            libelle: "Le 24 de chaque mois",
                        },
                        {
                            id: "25",
                            libelle: "Le 25 de chaque mois",
                        },
                    ];

                    //   $('#appartement_' + type).val(+item['appartement'].id).change();
                    console.log(itemId);
                    //  $('#appartement_contrat').val(+itemId).change();
                    //  console.log($('#appartement_contrat').val()) ;
                    //   $('#appartement_contrat').prop( "disabled", true );
                }
            }

            if (!optionals.fromUpdate && !optionals.is_file_excel) {
                console.log("Not enter fromupdate " + conserveFilter);
                if (type.indexOf("etatlieu") !== -1) {
                    // $scope.notEnterEtatLieu = false;

                    $scope.compositionappartementchange = [];
                    $scope.detailcompositionappartementchange = [];
                    console.log(
                        "Not enter fromupdate 1 fromup : " +
                        optionals.fromUpdate +
                        " yo itemId " +
                        itemId
                    );
                    var id_etatlieu = $("#id_etatlieu").val();
                    if (!id_etatlieu) {
                        console.log("IT'S TRUE TRUE TRUE VERIFY");

                        console.log("Libasse DEV 1");
                        $("#appartement_" + type).on("change", function () {
                            console.log("Libasse DEV 2");
                            console.log(
                                "Not enter fromupdate 2" + optionals.fromUpdate
                            );

                            var Idvalueapp = $("#appartement_" + type).val();
                            console.log("APPPARTEMNET VALUE : " + Idvalueapp);
                            // $('#appartement_'+type).val(Idvalueapp).change();
                            var typeAvecS = "appartements";
                            rewriteReq = typeAvecS + "(id:" + Idvalueapp + ")";
                            // console.log(rewriteReq) ;
                            // if(!conserveFilter && itemId == null){
                            if (Idvalueapp) {
                                console.log("entre ici vrai ===");
                                Init.getElement(
                                    rewriteReq,
                                    listofrequests_assoc[typeAvecS]
                                ).then(
                                    function (data) {
                                        console.log("data", data);

                                        // $('#locataire_' + type).val()
                                        var elmt = data[0];
                                        if (elmt.etatlieu == "0") {
                                            $("#type_etatlieu")
                                                .val("entrée")
                                                .change();
                                        } else if (elmt.etatlieu == "1") {
                                            $("#type_etatlieu")
                                                .val("sortie")
                                                .change();
                                        }
                                        $getLocataireFromContrat = null;
                                        $getLocataireFromContrat = elmt[
                                            "contrats"
                                        ].find((data) => data.etat == 1);
                                        console.log(
                                            "locataire tab !!!!!!!!!!!" +
                                            $getLocataireFromContrat
                                        );
                                        if ($getLocataireFromContrat) {
                                            $("#locataire_" + type)
                                                .val(
                                                    $getLocataireFromContrat[
                                                        "locataire"
                                                    ].id
                                                )
                                                .change();
                                        }
                                        // elmt['contrats'].forEach((elmt2) => {

                                        // if(elmt2.etat === 1){
                                        // console.log(elmt2.locataire) ;
                                        // document.getElementById('locataire_etatlieu').innerHTML = "" ;
                                        // $('#locataire_etatlieu').append(
                                        //     "<option value="+elmt2.locataire.id+" selected class=\"required\">"+elmt2.locataire.prenom+ ' ' +elmt2.locataire.nom+"</option>"
                                        // );
                                        // $('#locataire_etatlieu').val(elmt2.locataire.id).change() ;
                                        // }
                                        // else if(elmt2.etat == 1 && elmt2.locataire.nomentreprise){
                                        //     document.getElementById('locataire_etatlieu').innerHTML = "" ;
                                        //     $('#locataire_etatlieu').append(
                                        //         "<option value="+elmt2.locataire.id+" selected class=\"required\">"+elmt2.locataire.nomentreprise+"</option>"
                                        //     );
                                        // }else{
                                        //     document.getElementById('locataire_etatlieu').innerHTML = "" ;
                                        // }

                                        // });

                                        $("#type_etatlieu").prop(
                                            "disabled",
                                            true
                                        );
                                        // $( '#locataire_etatlieu').prop( "disabled", true );
                                        // $scope.detailspiece  = data;
                                        // $scope.reInit("typeappartement_piece");
                                        //console.log($scope.detailspiece) ;
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );

                                var Idappartement = Idvalueapp;
                                $scope.compositionappartement = [];
                                $scope.detailcompositionappartement = [];
                                var typeAvecS2 = "compositions";
                                Init.getElement(
                                    "detailcompositions(appartement_id:" +
                                    Idappartement +
                                    ")",
                                    listofrequests_assoc["detailcompositions"]
                                ).then(
                                    function (data2) {
                                        console.log("data2", data2);
                                        var array = [];
                                        data2.forEach((elmt2) => {
                                            //array.push(elmt2);
                                            const resultId3 =
                                                $scope.detailcompositionappartement.find(
                                                    (d) => d.id == elmt2.id
                                                );

                                            if (!resultId3) {
                                                $scope.detailcompositionappartement.push(
                                                    elmt2
                                                );
                                            }
                                        });

                                        // $scope.detailcompositionappartement = array;
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );
                                rewriteReq2 =
                                    typeAvecS2 +
                                    "(appartement_id:" +
                                    Idappartement +
                                    ")";
                                Init.getElement(
                                    rewriteReq2,
                                    listofrequests_assoc[typeAvecS2]
                                ).then(
                                    function (data) {
                                        console.log("data", data);
                                        $scope.compositionappartement = data;
                                        data.forEach((elmt) => {
                                            Init.getElement(
                                                "detailcompositions(composition_id:" +
                                                elmt.id +
                                                ")",
                                                listofrequests_assoc[
                                                "detailcompositions"
                                                ]
                                            ).then(
                                                function (data2) {
                                                    console.log("data2", data2);
                                                    var array2 = [];
                                                    data2.forEach((elmt2) => {
                                                        // const result = null;
                                                        const result =
                                                            $scope.detailcompositionappartement.find(
                                                                (d) =>
                                                                    d.id ==
                                                                    elmt2.id
                                                            );

                                                        if (!result) {
                                                            $scope.detailcompositionappartement.push(
                                                                elmt2
                                                            );
                                                        }
                                                        console.log(
                                                            "result " +
                                                            JSON.stringify(
                                                                result
                                                            )
                                                        );
                                                    });
                                                    //    $scope.detailcompositionappartement = array2;
                                                },
                                                function (msg) {
                                                    $scope.showToast(
                                                        "",
                                                        msg,
                                                        "error"
                                                    );
                                                }
                                            );
                                        });
                                        // $scope.detailspiece  = data;
                                        // $scope.reInit("typeappartement_piece");
                                        //    console.log($scope.detailspiece) ;

                                        console.log(
                                            $scope.compositionappartement
                                        );
                                        console.log(
                                            $scope.detailcompositionappartement
                                        );
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );
                            }
                        });

                        setTimeout(function () {
                            $scope.reInit("etatlieu");
                        }, 2000);
                    }
                }
            }
            if (
                $scope.currentTemplateUrl
                    .toLowerCase()
                    .indexOf("list-contrat") !== -1
            ) {
                console.log("it's true");
                $scope.dataPage["rappelpaiementloyers"] = [
                    {
                        id: "5",
                        libelle: "Le 05 de chaque mois",
                    },
                    {
                        id: "6",
                        libelle: "Le 06 de chaque mois",
                    },
                    {
                        id: "7",
                        libelle: "Le 07 de chaque mois",
                    },
                    {
                        id: "8",
                        libelle: "Le 08 de chaque mois",
                    },
                    {
                        id: "9",
                        libelle: "Le 09 de chaque mois",
                    },
                    {
                        id: "10",
                        libelle: "Le 10 de chaque mois",
                    },
                    {
                        id: "11",
                        libelle: "Le 11 de chaque mois",
                    },
                    {
                        id: "12",
                        libelle: "Le 12 de chaque mois",
                    },
                    {
                        id: "13",
                        libelle: "Le 13 de chaque mois",
                    },
                    {
                        id: "14",
                        libelle: "Le 14 de chaque mois",
                    },
                    {
                        id: "15",
                        libelle: "Le 15 de chaque mois",
                    },
                    {
                        id: "16",
                        libelle: "Le 16 de chaque mois",
                    },
                    {
                        id: "17",
                        libelle: "Le 17 de chaque mois",
                    },
                    {
                        id: "18",
                        libelle: "Le 18 de chaque mois",
                    },
                    {
                        id: "19",
                        libelle: "Le 19 de chaque mois",
                    },
                    {
                        id: "20",
                        libelle: "Le 20 de chaque mois",
                    },
                    {
                        id: "21",
                        libelle: "Le 21 de chaque mois",
                    },
                    {
                        id: "22",
                        libelle: "Le 22 de chaque mois",
                    },
                    {
                        id: "23",
                        libelle: "Le 23 de chaque mois",
                    },
                    {
                        id: "24",
                        libelle: "Le 24 de chaque mois",
                    },
                    {
                        id: "25",
                        libelle: "Le 25 de chaque mois",
                    },
                ];
            }

            $("#modal_add" + (optionals.is_file_excel ? "list" : type)).modal(
                "show",
                {
                    backdrop: "static",
                }
            );
        };
        // Hide modal
        $("body").on("click", function (event) {
            var type = $scope.currentTemplateUrl.split("list-");
            if (type[1] == "traiteur") {
                type[1] = "proforma";
            } else if (type[1]) {
                if ($("#sousdepartement").hasClass("active")) {
                    type[1] = "sousdepartement";
                }
                if ($("#sousfamille").hasClass("active")) {
                    type[1] = "sousfamille";
                }
                if ($("#list-menu").hasClass("active")) {
                    type[1] = "menu";
                }
            }
            // console.log("ici les details => ", event.target.id, type[1])
            if (
                $("#modal_add" + type[1]).hasClass("modal") &&
                $("#modal_add" + type[1]).hasClass("show")
            ) {
                var idmodal = "modal_add" + type[1];

                // console.log('Validate', $scope.checkIfModalHasData(event,type[1]));

                if (idmodal === event.target.id) {
                    var has_data_form = $scope.checkIfModalHasData(
                        event,
                        type[1]
                    );
                    if (has_data_form) {
                        currentModal = $(this);
                        title = "Fermeture du modal";
                        msg = "Voulez-vous vraiment quitter le modal ... ?";

                        swalWithBootstrapButtons
                            .fire({
                                title: title,
                                text: msg,
                                icon: "question",
                                showCancelButton: true,
                                reverseButtons: true,
                            })
                            .then((result) => {
                                if (result.isConfirmed) {
                                    if (
                                        $("#" + event.target.id).hasClass(
                                            "modal"
                                        ) &&
                                        $("#" + event.target.id).hasClass(
                                            "show"
                                        )
                                    ) {
                                        setTimeout(() => {
                                            $(
                                                "#" + event.target.id
                                            ).removeClass("show");

                                            $("#" + event.target.id)
                                                .removeAttr("style")
                                                .removeClass("modal__overlap")
                                                .removeClass("overflow-y-auto");

                                            // Add scroll to highest z-index modal if exist
                                            $(".modal").each(function () {
                                                if (
                                                    parseInt(
                                                        $(this).css("z-index")
                                                    ) === getHighestZindex()
                                                ) {
                                                    $(this).addClass(
                                                        "overflow-y-auto"
                                                    );
                                                }
                                            });

                                            if (getHighestZindex() == 50) {
                                                $("body")
                                                    .removeClass(
                                                        "overflow-y-hidden"
                                                    )
                                                    .css("padding-right", "");
                                            }

                                            // Return back modal element to it's first place
                                            // $('[data-modal-replacer="' + $("#"+event.target.id).attr('id') + '"]').replaceWith("#"+event.target.id)
                                        }, 200);
                                    }
                                } else if (
                                    result.dismiss === Swal.DismissReason.cancel
                                ) {
                                }
                            });
                    } else {
                        if (
                            $("#" + event.target.id).hasClass("modal") &&
                            $("#" + event.target.id).hasClass("show")
                        ) {
                            setTimeout(() => {
                                $("#" + event.target.id).removeClass("show");

                                $("#" + event.target.id)
                                    .removeAttr("style")
                                    .removeClass("modal__overlap")
                                    .removeClass("overflow-y-auto");

                                // Add scroll to highest z-index modal if exist
                                $(".modal").each(function () {
                                    if (
                                        parseInt($(this).css("z-index")) ===
                                        getHighestZindex()
                                    ) {
                                        $(this).addClass("overflow-y-auto");
                                    }
                                });

                                if (getHighestZindex() == 50) {
                                    $("body")
                                        .removeClass("overflow-y-hidden")
                                        .css("padding-right", "");
                                }

                                // Return back modal element to it's first place
                                // $('[data-modal-replacer="' + $("#"+event.target.id).attr('id') + '"]').replaceWith("#"+event.target.id)
                            }, 200);
                        }
                    }

                    //console.log('modal**************', currentModal.data);
                    event.preventDefault();
                }
                /*else if (idmodal === event.target.id ){
    $scope.closeModal("#"+event.target.id)
}*/
            } else if (event.target.id == "modal_addlist") {
                console.log("ici les details => ", event.target.id, type[1]);
                $scope.closeModal("#modal_addlist");
            }
        });

        const swalWithBootstrapButtons = Swal.mixin({
            confirmButtonText: '<i class="fa fa-thumbs-up"></i> OUI',
            cancelButtonText: '<i class="fa fa-thumbs-down"></i> NON',
            customClass: {
                confirmButton: "button bg-success text-white",
                cancelButton: "button bg-danger text-white mr-2",
            },
            buttonsStyling: false,
        });

        $scope.closeModal = function (idmodal) {
            console.log(idmodal);
            if ($(idmodal).hasClass("modal") && $(idmodal).hasClass("show")) {
                setTimeout(() => {
                    $(idmodal).removeClass("show");

                    $(idmodal)
                        .removeAttr("style")
                        .removeClass("modal__overlap")
                        .removeClass("overflow-y-auto");

                    // Add scroll to highest z-index modal if exist
                    $(".modal").each(function () {
                        if (
                            parseInt($(this).css("z-index")) ===
                            getHighestZindex()
                        ) {
                            $(this).addClass("overflow-y-auto");
                        }
                    });

                    if (getHighestZindex() == 50) {
                        $("body")
                            .removeClass("overflow-y-hidden")
                            .css("padding-right", "");
                    }

                    // Return back modal element to it's first place
                    // $('[data-modal-replacer="' + $(idmodal).attr('id') + '"]').replaceWith(idmodal)
                }, 200);
            }
        };

        // hide modal link
        $("body").on("click", '[data-dismiss="modal"]', function () {
            let idmodal = $(this).closest(".modal")[0].id;

            console.log("ici le modal", idmodal);

            if (
                $("#" + idmodal).hasClass("modal") &&
                $("#" + idmodal).hasClass("show")
            ) {
                setTimeout(() => {
                    if (idmodal == "modal_addappartement") {
                        console.log("here");
                        $(".divapp").hide();
                        $(".divapp2").remove();
                    }

                    if (idmodal == "modal_addimmeuble") {
                        $scope.dataPage["typepieces"].forEach((elmt) => {
                            $("#" + elmt.id + "_id_oui").prop("checked", false);

                            $("#" + elmt.id + "_id_oui_nombre").val("");
                            $("#" + elmt.id + "_id_oui_nombre").show();
                        });
                    }

                    $("#" + idmodal).removeClass("show");

                    $("#" + idmodal)
                        .removeAttr("style")
                        .removeClass("modal__overlap")
                        .removeClass("overflow-y-auto");

                    // Add scroll to highest z-index modal if exist
                    $(".modal").each(function () {
                        if (
                            parseInt($(this).css("z-index")) ===
                            getHighestZindex()
                        ) {
                            $(this).addClass("overflow-y-auto");
                        }
                    });

                    if (getHighestZindex() == 50) {
                        $("body")
                            .removeClass("overflow-y-hidden")
                            .css("padding-right", "");
                    }

                    // Return back modal element to it's first place
                    // $('[data-modal-replacer="' + $("#"+idmodal).attr('id') + '"]').replaceWith("#"+idmodal)
                }, 200);
            }
        });

        // Add element in database and in scope
        $scope.addElement = function (
            e,
            type,
            optionals = { from: "modal", is_file_excel: false }
        ) {
            if (e != null) {
                e.preventDefault();
            }

            if (type == "caution") {
                console.log($("#contrat_caution").val());
                console.log($("#montantloyer_caution").val());
            }

            var form = $(
                "#form_add" + (optionals.is_file_excel ? "liste" : type)
            );
            //   console.log(form) ;
            var formdata = window.FormData ? new FormData(form[0]) : null;
            var send_data = formdata !== null ? formdata : form.serialize();

            // test
            if (type == "locationvente") {
                type = "contrat";
            }
            if (type == "facturelocation") {
                var periodes = $("#periodes_facturelocation").val();
                var newArr = [];
                $.each(periodes, function (key, value) {
                    var arr = { periode_id: value };
                    newArr.push(arr);
                });
                $scope.dataInTabPane["periodefacturelocation_facturelocation"][
                    "data"
                ] = newArr;
            }
            //  if (type == "paiementloyer") {
            //     var periodes = $("#periodes_paiementloyer").val();
            //     var newArr = [];
            //     $.each(periodes, function (key, value) {
            //         var arr = { periode_id: value };
            //         newArr.push(arr);
            //     });
            //     $scope.dataInTabPane["periodepaiementloyer_paiementloyer"][
            //         "data"
            //     ] = newArr;
            //     console.log(
            //         "test paiement loyer : ==== " +
            //             $("#periodes_paiementloyer").val()
            //     );
            //     console.log("test paiement loyer : ==== " + newArr);
            // }

            if (type == "entite") {
                var periodes = $("#equipes_entite").val();
                var newArr = [];
                $.each(periodes, function (key, value) {
                    var arr = { user_id: value };
                    newArr.push(arr);
                });
                $scope.dataInTabPane["users_entite"]["data"] = newArr;
                console.log("test paiement loyer : ==== ");
                console.log("test paiement loyer : ==== " + newArr);
            }
            console.log("Values : ", send_data.values());

            // A ne pas supprimer
            send_dataObj = form.serializeObject();
            continuer = true;
            console.log(send_dataObj, "send_dataObj");

            $.each($scope.dataInTabPane, function (keyItem, valueItem) {
                tagType = "_" + type;
                if (keyItem.indexOf(tagType) !== -1) {
                    send_data.append(
                        keyItem.substring(0, keyItem.length - tagType.length),
                        JSON.stringify($scope.dataInTabPane[keyItem]["data"])
                    );
                    console.log(
                        "********************",
                        keyItem.substring(0, keyItem.length - tagType.length),
                        JSON.stringify($scope.dataInTabPane[keyItem]["data"])
                    );
                    console.log("tab 1 type: " + tagType);
                }
            });

            if (type === "menu") {
                $scope.dataInTabPane["tranche_horaires_menu"]["data"] =
                    $scope.dataPage["tranchehoraires"];
            }

            $.each($scope.dataInTabPane, function (keyItem, valueItem) {
                tagType = "_" + type;
                if (keyItem.indexOf(tagType) !== -1) {
                    send_data.append(
                        keyItem.substring(0, keyItem.length - tagType.length),
                        JSON.stringify($scope.dataInTabPane[keyItem]["data"])
                    );
                    console.log(
                        "******************** t2",
                        keyItem.substring(0, keyItem.length - tagType.length),
                        JSON.stringify($scope.dataInTabPane[keyItem]["data"])
                    );

                    console.log("Type---->" + type);
                }
            });

            if (type.indexOf("role") !== -1) {
                send_data.append("permissions", $scope.role_permissions);
                console.log(
                    "role_permissions",
                    $scope.role_permissions,
                    "...",
                    send_data.get("role_permissions")
                );

                if (
                    $scope.role_permissions.length == 0 &&
                    optionals.is_file_excel == false
                ) {
                    $scope.showToast(
                        "",
                        "Vous devez ajouter au moins une permission au présent role",
                        "error"
                    );
                    continuer = false;
                }
            }
            if (type === "factureintervention") {
                console.log('test test: ', $scope.dataPage["interventions"].length);
                if ($scope.dataPage["interventions"].length > 0) {
                    continuer = false;
                    $scope.showToast(
                        "",
                        "vous devez ajouter toutes les interventions",
                        "error"
                    );
                }
            }

            console.log(form);
            //continuer = false
            if (form.validate() && continuer) {
                console.log("validate & continuer");
                form.parent().parent().blockUI_start();
                var hide_modal = true;
                console.log("send_data");
                // return false
                Init.saveElementAjax(
                    type,
                    send_data,
                    optionals.is_file_excel
                ).then(
                    function (data) {
                        console.log("Valeur de data = ", type, data);
                        if (send_data.id) {
                            $scope.conserveFilter = true;
                        }
                        form.parent().parent().blockUI_stop();
                        if (data.data != null && !data.errors) {
                            // $scope.getelements(type+"s");
                            if (optionals.is_file_excel) {
                                $scope.closeModal("#modal_addlist");
                            }
                            $scope.rappelLocataireData = [];
                            if (
                                type == "appartement" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-immeuble"
                                ) !== -1
                            ) {
                                $scope.pageChanged("immeuble");
                            } else if (
                                type == "appartement" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-villa"
                                ) !== -1
                            ) {
                                $scope.pageChanged("villa");
                            } else if (type == "appartement") {
                                $(".divapp").hide();
                                $(".divapp2").remove();

                                $scope.pageChanged(type);
                            } else if (type == "immeuble") {
                                $scope.dataPage["typepieces"].forEach(
                                    (elmt) => {
                                        $("#" + elmt.id + "_id_oui").prop(
                                            "checked",
                                            false
                                        );

                                        $("#" + elmt.id + "_id_oui_nombre").val(
                                            ""
                                        );
                                        $(
                                            "#" + elmt.id + "_id_oui_nombre"
                                        ).show();
                                    }
                                );

                                $scope.pageChanged(type);
                            } else if (
                                type == "caution" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-contrat"
                                ) !== -1
                            ) {
                                $scope.pageChanged("contrat");
                            } else if (
                                type == "assurance" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-contrat"
                                ) !== -1
                            ) {
                                $scope.pageChanged("contrat");
                            } else if (
                                type == "etatlieu" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-appartement"
                                ) !== -1
                            ) {
                                $scope.pageChanged("appartement");
                            } else if (
                                type == "etatlieu" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-contrat"
                                ) !== -1
                            ) {
                                $scope.pageChanged("contrat");
                            } else if (
                                type == "contrat" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-appartement"
                                ) !== -1
                            ) {
                                $scope.pageChanged("appartement");
                            } else if (
                                type == "etatlieu" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-demanderesiliation"
                                ) !== -1
                            ) {
                                $scope.pageChanged("demanderesiliation");
                            } else if (
                                type == "gestionnaire" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-proprietaire"
                                ) !== -1
                            ) {
                                $scope.pageChanged("proprietaire");
                            } else if (
                                type == "securite" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-immeuble"
                                ) !== -1
                            ) {
                                $scope.pageChanged("immeuble");
                            } else if (
                                type == "equipementappartement" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-appartement"
                                ) !== -1
                            ) {
                                $scope.pageChanged("appartement");
                            } else if (
                                type == "compositionappartement" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-appartement"
                                ) !== -1
                            ) {
                                $scope.pageChanged("appartement");
                            } else if (
                                type == "contrat" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-locationvente"
                                ) !== -1
                            ) {
                                $scope.pageChanged("locationvente");
                                $scope.closeModal("#modal_addlocationvente");
                            }
                            else if (
                                type == "paiementecheance" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailslocationvente"
                                ) !== -1
                            ) {
                                console.log('donnees: ', data.data);
                                $scope.pageChanged(
                                    "avisecheance",
                                    (optionals = {
                                        justWriteUrl: null,
                                        option: null,
                                        saveStateOfFilters: false,
                                    }),
                                    $routeParams.itemId
                                );

                                var contratIdId = $routeParams.itemId;
                                $scope.pageChanged(
                                    "locationvente",
                                    (optionals = {
                                        justWriteUrl: null,
                                        option: null,
                                        saveStateOfFilters: false,
                                    }),
                                    contratIdId
                                );

                                var avisecheance_id = $scope.detail_avisecheance_id;
                                console.log('item: ', avisecheance_id);

                                $scope.getelements(
                                    "paiementecheances",
                                    (optionals = {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    }),
                                    "avisecheance_id:" + avisecheance_id
                                );
                                // console.log('itemId: ', $routeParams.itemId);

                                window.open("paiementecheance/recu/" + data.data);
                            } else if (
                                type == "paiementintervention" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-factureintervention"
                                ) !== -1
                            ) {
                                $scope.pageChanged("factureintervention");
                            } else if (
                                type == "signaturecontrat" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-contrat"
                                ) !== -1
                            ) {
                                $scope.pageChanged("contrat");
                                $scope.closeModal("#modal_addsignaturecontrat");
                            } else if (
                                type == "facturelocation" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "contrats",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementloyers",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.getelements(
                                    "facturelocations",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                            } else if (
                                type == "factureeaux" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "contrats",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementloyers",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.getelements(
                                    "factureeaux",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                            } else if (
                                type == "paiementloyer" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                console.log("data ++++ +++ ", data.data["paiementloyers"][0]['id']);
                                console.log(
                                    "data ++++ +++ " +
                                    data.data["paiementloyers"][0][
                                    "contrat"
                                    ]["id"]
                                );
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "contrats",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementloyers",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.getelements(
                                    "facturelocations",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );

                                window.open("paiementloyer/recu/" + data.data["paiementloyers"][0]['id']);
                                // window.open(    "generate-pdf-one-paiementloyer/" + data.data["paiementloyers"][0]['id']);

                            } else if (
                                type == "signaturecontrat" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-contrat"
                                ) !== -1
                            ) {
                                $scope.pageChanged("contrat");
                                $scope.closeModal("#modal_addsignaturecontrat");
                            } else if (
                                type == "facturelocation" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " +
                                    data.data["facturelocations"][0][
                                    "contrat_id"
                                    ]
                                );
                                var contratIdId =
                                    data.data["facturelocations"][0][
                                    "contrat_id"
                                    ];
                                $scope.getelements(
                                    "contrats",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementloyers",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.getelements(
                                    "facturelocations",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                            } else if (
                                type == "facturelocation" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailslocationvente"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " + $routeParams.itemId
                                );
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "locationventes",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementloyers",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.getelements(
                                    "facturelocations",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                            } else if (
                                type == "avisecheance" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailslocationvente"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " + $routeParams.itemId
                                );
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "locationventes",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                //  $scope.getelements('paiementloyers' , {queries: null, typeIds: null, otherFilters: null},"contrat_id:"+contratIdId);
                                $scope.pageChanged(
                                    "avisecheance",
                                    (optionals = {
                                        justWriteUrl: null,
                                        option: null,
                                        saveStateOfFilters: false,
                                    }),
                                    contratIdId
                                );
                            } else if (
                                type == "paiementloyer" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                console.log("data ++++ +++ ");
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "contrats",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementloyers",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.getelements(
                                    "facturelocations",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                            } else if (
                                type == "paiementloyer" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailslocationvente"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " + $routeParams.itemId
                                );
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "locationventes",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementloyers",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.getelements(
                                    "facturelocations",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                            } else if (
                                type == "paiementecheance" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailslocationvente"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " + $routeParams.itemId
                                );
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "locationventes",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementecheances",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.pageChanged(
                                    "avisecheance",
                                    (optionals = {
                                        justWriteUrl: null,
                                        option: null,
                                        saveStateOfFilters: false,
                                    }),
                                    contratIdId
                                );
                                $scope.getelements(
                                    "factureacomptes",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                            } else if (
                                type == "factureacompte" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailslocationvente"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " + $routeParams.itemId
                                );
                                var contratIdId = $routeParams.itemId;
                                $scope.getelements(
                                    "locationventes",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                                $scope.getelements(
                                    "paiementecheances",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.getelements(
                                    "factureacomptes",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                            } else if (
                                type == "etatlieu" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                console.log("data ++++ +++ " + $routeParams);

                                var data = data.data["etatlieus"][0];
                                var typeAvecS = "contrats";
                                rewriteReq =
                                    typeAvecS +
                                    "(appartement_id:" +
                                    data["appartement"]["id"] +
                                    ",locataire_id:" +
                                    data["locataire"]["id"] +
                                    ")";
                                Init.getElement(
                                    rewriteReq,
                                    listofrequests_assoc[typeAvecS]
                                ).then(
                                    function (data) {
                                        console.log(
                                            "data etat ",
                                            JSON.stringify(data[0])
                                        );
                                        $scope.getelements(
                                            "contrats",
                                            {
                                                queries: null,
                                                typeIds: null,
                                                otherFilters: null,
                                            },
                                            "id:" + data[0]["id"]
                                        );
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );
                            } else if (
                                type == "etatlieu" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailslocationvente"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " + data.data["etatlieus"][0]
                                );

                                var data = data.data["etatlieus"][0];
                                var typeAvecS = "locationventes";
                                rewriteReq =
                                    typeAvecS +
                                    "(appartement_id:" +
                                    data["appartement"]["id"] +
                                    ",locataire_id:" +
                                    data["locataire"]["id"] +
                                    ")";
                                Init.getElement(
                                    rewriteReq,
                                    listofrequests_assoc[typeAvecS]
                                ).then(
                                    function (data) {
                                        console.log(
                                            "data etat ",
                                            JSON.stringify(data[0])
                                        );
                                        $scope.getelements(
                                            "locationventes",
                                            {
                                                queries: null,
                                                typeIds: null,
                                                otherFilters: null,
                                            },
                                            "id:" + data[0]["id"]
                                        );
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );
                            } else if (
                                type == "inbox" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " +
                                    data.data["inboxs"][0]["locataire_id"]
                                );
                                var datas = data.data["inboxs"][0];
                                var typeAvecS = "contrats";
                                rewriteReq =
                                    typeAvecS +
                                    "(appartement_id:" +
                                    datas["appartement_id"] +
                                    ",locataire_id:" +
                                    datas["locataire_id"] +
                                    ")";
                                Init.getElement(
                                    rewriteReq,
                                    listofrequests_assoc[typeAvecS]
                                ).then(
                                    function (res) {
                                        console.log(
                                            "data etat ",
                                            JSON.stringify(res[0])
                                        );
                                        $scope.getelements(
                                            "contrats",
                                            {
                                                queries: null,
                                                typeIds: null,
                                                otherFilters: null,
                                            },
                                            "id:" + res[0]["id"]
                                        );
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );
                            } else if (
                                type == "inbox" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailslocationvente"
                                ) !== -1
                            ) {
                                console.log(
                                    "data ++++ +++ " +
                                    data.data["inboxs"][0]["locataire_id"]
                                );
                                console.log(
                                    "data ++++ app id :  " +
                                    data.data["inboxs"][0]["appartement_id"]
                                );
                                var datas = data.data["inboxs"][0];
                                var typeAvecS = "locationventes";
                                rewriteReq =
                                    typeAvecS +
                                    "(appartement_id:" +
                                    datas["appartement_id"] +
                                    ",locataire_id:" +
                                    datas["locataire_id"] +
                                    ")";
                                Init.getElement(
                                    rewriteReq,
                                    listofrequests_assoc[typeAvecS]
                                ).then(
                                    function (res) {
                                        console.log(
                                            "data etat ",
                                            JSON.stringify(res)
                                        );
                                        $scope.getelements(
                                            "locationventes",
                                            {
                                                queries: null,
                                                typeIds: null,
                                                otherFilters: null,
                                            },
                                            "id:" + res[0]["id"]
                                        );
                                    },
                                    function (msg) {
                                        $scope.showToast("", msg, "error");
                                    }
                                );
                            } else if (
                                type == "factureintervention" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-etatlieu"
                                ) !== -1
                            ) {
                                $scope.pageChanged("etatlieu");
                            } else if (
                                type == "facturelocation" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                $scope.pageChanged("detailscontrat");
                            } else if (
                                type == "demanderesiliation" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                var contratIdId = $routeParams.itemId;

                                $scope.getelements(
                                    "contrats",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "id:" + contratIdId
                                );
                            } else if (
                                type == "devi" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-demandeintervention"
                                ) !== -1
                            ) {
                                $scope.pageChanged("demandeintervention");
                            } else if (
                                type == "factureintervention" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-etatlieu"
                                ) !== -1
                            ) {
                                $scope.pageChanged("etatlieu");
                            } else if (
                                type == "devi" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-etatlieu"
                                ) !== -1
                            ) {
                                $scope.pageChanged("etatlieu");
                            } else if (type == "annulationpaiementavis") {
                                var contratIdId = $routeParams.itemId;
                                $scope.pageChanged(
                                    "avisecheance",
                                    (optionals = {
                                        justWriteUrl: null,
                                        option: null,
                                        saveStateOfFilters: false,
                                    }),
                                    contratIdId
                                );
                            } else if (type == "annulationpaiementloyer") {
                                var contratIdId = $routeParams.itemId;

                                $scope.getelements(
                                    "facturelocations",
                                    {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    },
                                    "contrat_id:" + contratIdId
                                );
                                $scope.pageChanged(
                                    "avisecheance",
                                    (optionals = {
                                        justWriteUrl: null,
                                        option: null,
                                        saveStateOfFilters: false,
                                    }),
                                    contratIdId
                                );
                            } else if (
                                type == "etatlieu" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailsdemanderesiliation"
                                ) !== -1
                            ) {
                                $scope.getelements(
                                    "demanderesiliations",
                                    (optionals = {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    }),
                                    "id:" + $routeParams.itemId
                                );
                            } else if (
                                (type == "devi" ||
                                    type == "factureintervention") &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailsdemanderesiliation"
                                ) !== -1
                            ) {
                                $scope.getelements(
                                    "demanderesiliations",
                                    (optionals = {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    }),
                                    "id:" + $routeParams.itemId
                                );
                            } else if (
                                type == "avenant" &&
                                $scope.currentTemplateUrl.indexOf(
                                    "list-detailscontrat"
                                ) !== -1
                            ) {
                                $scope.getelements(
                                    "avenants",
                                    (optionals = {
                                        queries: null,
                                        typeIds: null,
                                        otherFilters: null,
                                    }),
                                    "contrat_id:" + $routeParams.itemId
                                );
                            } else {
                                $scope.pageChanged(type);
                            }

                            $scope.showToast(
                                !data.message
                                    ? !send_dataObj.id
                                        ? "AJOUT"
                                        : "MODIFICATION"
                                    : "",
                                !data.message ? "" : data.message,
                                "success"
                            );
                            $(
                                "#modal_add" +
                                (optionals.is_file_excel ? "list" : type)
                            ).modal("hide");
                            $scope.closeModal(
                                "#modal_add" +
                                (optionals.is_file_excel ? "list" : type)
                            );
                        } else {
                            let errs = null;
                            if (typeof data.errors == "object") {
                                errs = Object.keys(data.errors);
                                //console.log(data) ;

                                errs.forEach((elmt) => {
                                    $scope.showToast(
                                        "",
                                        '<span class="h4 text-dark">' +
                                        data.errors[elmt] +
                                        "</span>",
                                        "error"
                                    );
                                });
                            } else {
                                //console.log(data) ;
                                $scope.showToast(
                                    '<span class="h4">' +
                                    data.errors +
                                    "</span>",
                                    "error",
                                    ""
                                );
                            }
                        }
                    },
                    function (msg) {
                        if (typeof data === "undefined") {
                            form.parent().parent().blockUI_stop();

                            $scope.showToast(
                                !send_data.id ? "AJOUT" : "MODIFICATION",
                                '<span class="h4">Erreur depuis le serveur, veuillez contactez l\'administrateur</span>',
                                "error"
                            );
                        }
                    }
                );
            }
        };

        $scope.checkIfModalHasData = function (
            e,
            type,
            optionals = { from: "modal", is_file_excel: false }
        ) {
            if (e != null) {
                e.preventDefault();
            }

            var form = $(
                "#form_add" + (optionals.is_file_excel ? "liste" : type)
            );
            var formdata = window.FormData ? new FormData(form[0]) : null;
            var send_data = formdata !== null ? formdata : form.serialize();

            var x = form.serializeArray();
            send_dataObj = form.serializeObject();
            var fo = "";
            $.each(x, function (i, field) {
                fo = " " + " " + field.value + " ";
            });
            fo = fo.trim();
            // console.log('SSSS==>', send_dataObj)

            //Verification sur les donnees inoput

            if (!fo || fo == "" || fo == " ") {
                continuer = false;
            } else {
                continuer = true;
            }

            //Verification sur les select2
            $("select[id$=" + type + "]").each(function () {
                currentvalue = $(this).val();

                if (currentvalue) {
                    continuer = true;
                }
            });

            var date = null;
            var dataArray = [];

            //Verification sur les tab Pane
            if (type === "menu") {
                $scope.dataInTabPane["tranche_horaires_menu"]["data"] =
                    $scope.dataPage["tranchehoraires"];
            }
            $.each($scope.dataInTabPane, function (keyItem, valueItem) {
                tagType = "_" + type;
                if (keyItem.indexOf(tagType) !== -1) {
                    dataArray = $scope.dataInTabPane[keyItem]["data"];
                    if (dataArray && dataArray.length > 0) {
                        // console.log('Data===>',dataArray);
                        continuer = true;
                    }
                }
            });
            // console.log('Data===>',dataArray);

            return continuer;
        };

        //--Pour supprimer un élément--//
        $scope.deleteElement = function (type, itemId, action = null) {
            var msg = "Voulez-vous vraiment effectuer cette suppression ?";
            var title = "SUPPRESSION";

            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: title,
                message: msg,
                position: "center",
                buttons: [
                    [
                        '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );

                            Init.removeElement(type, itemId).then(
                                function (data) {
                                    console.log("deleted", data, type);
                                    if (data.data && !data.errors) {
                                        // $scope.pageChanged(type);
                                        if (
                                            $scope.currentTemplateUrl.indexOf(
                                                "list-detailscontrat"
                                            ) !== -1
                                        ) {
                                            var contrat = $routeParams.itemId;
                                            $scope.getelements(
                                                "facturelocations",
                                                {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                },
                                                "contrat_id:" + contrat
                                            );
                                        } else if (
                                            $scope.currentTemplateUrl.indexOf(
                                                "list-detailslocationvente"
                                            ) !== -1
                                        ) {
                                            console.log(
                                                "data ++++ +++ " +
                                                $routeParams.itemId
                                            );
                                            var contratIdId =
                                                $routeParams.itemId;

                                            $scope.getelements(
                                                "locationventes",
                                                {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                },
                                                "id:" + contratIdId
                                            );
                                            $scope.getelements(
                                                "paiementloyers",
                                                {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                },
                                                "contrat_id:" + contratIdId
                                            );
                                            $scope.getelements(
                                                "facturelocations",
                                                {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                },
                                                "contrat_id:" + contratIdId
                                            );
                                            $scope.getelements(
                                                "factureacomptes",
                                                (optionals = {
                                                    queries: null,
                                                    typeIds: null,
                                                    otherFilters: null,
                                                }),
                                                "contrat_id:" + contratIdId
                                            );

                                            if (type == "avisecheance") {
                                                $scope.pageChanged(
                                                    "avisecheance",
                                                    (optionals = {
                                                        justWriteUrl: null,
                                                        option: null,
                                                        saveStateOfFilters: false,
                                                    }),
                                                    contratIdId
                                                );
                                            }

                                            if (type == "paiementecheance") {
                                                $scope.pageChanged(
                                                    "avisecheance",
                                                    (optionals = {
                                                        justWriteUrl: null,
                                                        option: null,
                                                        saveStateOfFilters: false,
                                                    }),
                                                    contratIdId
                                                );

                                                // $scope.closeModal("#modal_detailsavisecheance");

                                                var avisecheance_id = $scope.detail_avisecheance_id;
                                                console.log('item: ', avisecheance_id);

                                                $scope.getelements(
                                                    "paiementecheances",
                                                    (optionals = {
                                                        queries: null,
                                                        typeIds: null,
                                                        otherFilters: null,
                                                    }),
                                                    "avisecheance_id:" + avisecheance_id
                                                );
                                            }

                                        } else if (
                                            $scope.currentTemplateUrl.indexOf(
                                                "list-locationvente"
                                            ) !== -1
                                        ) {
                                            $scope.pageChanged("locationvente");
                                        } else {
                                            $scope.pageChanged(type);
                                        }
                                        $scope.showToast(
                                            title,
                                            "Succès",
                                            "success"
                                        );
                                    } else {
                                        $scope.showToast(
                                            title,
                                            data.errors_debug,
                                            "error"
                                        );
                                    }
                                },
                                function (msg) {
                                    $scope.showToast(title, msg, "error");
                                }
                            );
                        },
                        true,
                    ],
                    [
                        '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                    ],
                ],
                onClosing: function (instance, toast, closedBy) {
                    console.log("Closing | closedBy: " + closedBy);
                },
                onClosed: function (instance, toast, closedBy) {
                    console.log("Closed | closedBy: " + closedBy);
                },
            });
        };

        $scope.desactivElement = function (
            type,
            itemId,
            action = null,
            index = null,
            list = false,
            tab = false
        ) {
            console.log("Etat=======>", $scope.chstat.statut, type);
            console.log($scope.chstat);
            var msg = "";
            var title = "";
            var typeQuery = type;
            var confirmation = true;

            msg =
                $scope.chstat.statut == 1
                    ? "Voulez-vous vraiment effectuer cette activation ?"
                    : "Voulez-vous vraiment effectuer cette desactivation ?";
            title = $scope.chstat.statut == 1 ? "ACTIVATION" : "DESACTIVATION";

            if (type == "devi") {
                msg = "Voulez-vous vraiment effectuer cette validation ?";
                title = "VALIDATION";
            }
            if (type == "avenant") {
                msg =
                    $scope.chstat.statut == 2
                        ? "Voulez-vous vraiment effectuer cette activation ?"
                        : "Voulez-vous vraiment effectuer cette desactivation ?";
                title =
                    $scope.chstat.statut == 2 ? "ACTIVATION" : "DESACTIVATION";
            }
            if (type == "appartement") {
                msg = "Voulez-vous vraiment archiver cet appartement ?";
                title = "VALIDATION";
            }
            var send_data = {
                id: $scope.chstat.id,
                status: $scope.chstat.statut,
                substatut: $scope.chstat.substatut,
                commentaire: $("#commentaire_chstat").val(),
                objet: itemId,
                type: type,
            };
            console.log('send data: ', send_data);

            if (confirmation) {
                iziToast.question({
                    timeout: 0,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    id: "question",
                    zindex: 999,
                    title: title,
                    message: msg,
                    position: "center",
                    buttons: [
                        [
                            '<button class="font-bold btn btn-success" style="color: green!important">Confirmer</button>',
                            function (instance, toast) {
                                Init.changeStatut(typeQuery, send_data).then(
                                    function (data) {
                                        if (data.data && !data.errors) {
                                            instance.hide(
                                                {
                                                    transitionOt: "fadeOut",
                                                },
                                                toast,
                                                "button"
                                            );

                                            $scope.showToast(
                                                title,
                                                "Réussi",
                                                "success"
                                            );

                                            if (typeQuery == "avenant") {
                                                $scope.getelements(
                                                    "avenants",
                                                    (optionals = {
                                                        queries: null,
                                                        typeIds: null,
                                                        otherFilters: null,
                                                    }),
                                                    "contrat_id:" +
                                                    $routeParams.itemId
                                                );
                                            }

                                            if (typeQuery == "devi") {
                                                if (
                                                    $scope.currentTemplateUrl.indexOf(
                                                        "list-detailsdemanderesiliation"
                                                    ) !== -1
                                                ) {
                                                    $scope.getelements(
                                                        "demanderesiliations",
                                                        (optionals = {
                                                            queries: null,
                                                            typeIds: null,
                                                            otherFilters: null,
                                                        }),
                                                        "id:" +
                                                        $routeParams.itemId
                                                    );
                                                } else {
                                                    $scope.pageChanged(
                                                        "demandeintervention"
                                                    );
                                                    $scope.pageChanged(
                                                        "etatlieu"
                                                    );
                                                }
                                            } else {
                                                $scope.pageChanged(typeQuery);
                                            }
                                        } else {
                                            $scope.showToast(
                                                title,
                                                data.errors_debug,
                                                "error"
                                            );
                                        }
                                    },
                                    function (msg) {
                                        $scope.showToast(title, msg, "error");
                                    }
                                );
                            },
                            true,
                        ],
                        [
                            '<button class="btn btn-danger" style="color: red!important">Annuler</button>',
                            function (instance, toast) {
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );
                            },
                            false,
                        ],
                    ],
                    onClosing: function (instance, toast, closedBy) {
                        console.log("Closing | closedBy: " + closedBy);
                    },
                    onClosed: function (instance, toast, closedBy) {
                        console.log("Closed | closedBy: " + closedBy);
                    },
                });
            } else {
                console.log("------list-------");
                console.log(list);

                Init.changeStatut(typeQuery, send_data).then(
                    function (data) {
                        if (data.data && !data.errors) {
                            if (list == false) {
                                $scope.pageChanged(type);

                                if (type == "devi") {
                                    $scope.pageChanged("demandeintervention");
                                    $scope.pageChanged("etatlieu");
                                }
                                $scope.showToast(title, "Réussi", "success");
                            }
                        } else {
                            $scope.showToast(title, data.errors_debug, "error");
                        }
                    },
                    function (msg) {
                        $scope.showToast(title, msg, "error");
                    }
                );
            }
        };

        $scope.ckeckRadio = function (tag) {
            var value = $("#" + tag).val();
            console.log(value);
        };
    }
);

function getHighestZindex() {
    let zIndex = 50;
    $(".modal").each(function () {
        if (
            $(this).css("z-index") !== "auto" &&
            $(this).css("z-index") > zIndex
        ) {
            zIndex = parseInt($(this).css("z-index"));
        }
    });

    return zIndex;
}

// Vérification de l'extension des elements uploadés
function isValide(fichier) {
    var Allowedextensionsimg = new Array(
        "jpg",
        "JPG",
        "jpeg",
        "JPEG",
        "gif",
        "GIF",
        "png",
        "PNG",
        "svg",
        "SVG"
    );
    var Allowedextensionsvideo = new Array("mp4");
    for (var i = 0; i < Allowedextensionsimg.length; i++)
        if (fichier.lastIndexOf(Allowedextensionsimg[i]) != -1) {
            return 1;
        }
    for (var j = 0; j < Allowedextensionsvideo.length; j++)
        if (fichier.lastIndexOf(Allowedextensionsvideo[j]) != -1) {
            return 2;
        }
    return 0;
}

//$scope.testt  = 1;
// FileReader pour la photo //

function showInputquesionnaire(item, reponse) {
    console.log("here");

    if (reponse == "oui") {
        //  console.log($("#"+item.id)) ;
        console.log(reponse);
        console.log($("#" + item.id + "_nombre"));
        $("#" + item.id + "_nombre").show();
        if ($("#" + item.id + "_nomsalledefete")) {
            $("#" + item.id + "_nomsalledefete").show();
        }
        //       $('#'+item.id+'_nomsalledefete').show();
        //   console.log("#"+item.id) ;
    } else if (reponse == "non") {
        // console.log(item.name) ;
        console.log(reponse);
        //    console.log("#"+item.name) ;
        console.log($("#" + item.id + "_oui_nombre"));
        $("#" + item.id + "_oui_nombre").hide();
        if ($("#" + item.id + "_oui_nomsalledefete")) {
            $("#" + item.id + "_oui_nomsalledefete").hide();
        }
    }
}

function showInputdelai(item, reponse) {
    console.log("here");

    if (reponse == "non") {
        console.log(item);
        console.log(reponse);
        $("#raison").show();
        $("#" + item.name).show();
        console.log("#" + item.name);
    } else if (reponse == "oui") {
        console.log(item.name);
        console.log(reponse);
        console.log("#" + item.name);
        $("#raison").hide();
        $("#" + item.name).hide();
    }
}

function showInput(item, reponse, type) {
    console.log("here");

    if (type == "demandeintervention") {
        if (reponse == "particulier") {
            console.log(item);
            console.log(reponse);
            $(".immeubleintervention").hide();
            $("#immeuble_demandeintervention").val("");
            $(".appintervention").show();
        }
        if (reponse == "generale") {
            $(".immeubleintervention").show();
            $("#appartement_demandeintervention").val("");
            $("#locataire_demandeintervention").val("");
            $(".appintervention").hide();
        }
    }

    if (type == "typeintervenant") {
        if (reponse == "prestataire") {
            $("#membreequipegestion_intervention").val("");
            $(".employeintervention").hide();
            $(".prestataireintervention").show();
        }
        if (reponse == "employe") {
            $("#prestataire_intervention").val("");
            $(".prestataireintervention").hide();
            $(".employeintervention").show();
        }
    }

    if (type == "typelocataire") {
        $(".1").hide();
        $(".2").hide();
        $(".entreprise").hide();
        if (reponse == "locataireexistant") {
            $("#membreequipegestion_intervention").val("");
            $(".nouveaulocataire").hide();
            $(".locataireexistant").show();
        }
        if (reponse == "nouveaulocataire") {
            $("#prestataire_intervention").val("");
            $(".locataireexistant").hide();
            $(".nouveaulocataire").show();
            $("#nomcompletpersonnepriseencharge_locataire").hide();
            $("#telephonepersonnepriseencharge_locataire").hide();
        }
    }

    if (type == "contratassurance") {
        if (reponse == "renouvelle") {
            $("#prestataire_assurance").val("");
            $(".nonrenouvelle").hide();
            $(".assurancerenouvelle").show();
        }
        if (reponse == "nonrenouvelle") {
            $("#assurancerenouvelle_assurance").val("");
            $(".assurancerenouvelle").hide();
            $(".nonrenouvelle").show();
        }
    }

    if (type == "obligationadministrative") {
        if (reponse == "immeuble") {
            $("#appartement_obligationadministrative").val("");
            $(".appartementObligationadministrative").hide();
            $(".immeubleObligationadministrative").show();
        }
        if (reponse == "appartement") {
            $("#appartement_obligationadministrative").val("");
            $("#immeuble_obligationadministrative").val("");
            $(".immeubleObligationadministrative").show();
            $(".appartementObligationadministrative").show();
        }
    }

    if (type == "annonce") {
        if (reponse == "immeuble") {
            $("#appartement_annonce").val("");
            $("#immeuble_annonce").val("");
            $(".appartementannonce").show();
        }
        if (reponse == "immeubles" || reponse == "marketing") {
            $("#appartement_annonce").val("");
            $("#immeuble_annonce").val("");
            $(".appartementannonce").hide();
        }
    }

    if (type == "immeuble") {
        if ($("#check_prestataireoui").is(":checked")) {
            $("#prestataire_immeuble_securite_immeuble").prop("disabled", true);
            $("#horaireprestataire_immeuble_securite_immeuble").prop(
                "disabled",
                true
            );
            $("#etatprestataire_immeuble_securite_immeuble").prop(
                "disabled",
                true
            );
            $(".classeprestataire").hide();
            $("#prestatairesecurite_immeuble").val("");

            $("#designationsecurite_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );
            $("#adressesecurite_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );
            $("#telephone1securite_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );
            $("#telephone2securite_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );
            $("#horairesecurite_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );
            $("#etatsecurite_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );

            $(".classeautre").show();
        } else {
            $("#designationsecurite_immeuble_securite_immeuble").prop(
                "disabled",
                true
            );
            $("#adressesecurite_immeuble_securite_immeuble").prop(
                "disabled",
                true
            );
            $("#telephone1securite_immeuble_securite_immeuble").prop(
                "disabled",
                true
            );
            $("#telephone2securite_immeuble_securite_immeuble").prop(
                "disabled",
                true
            );
            $("#horairesecurite_immeuble_securite_immeuble").prop(
                "disabled",
                true
            );
            $("#etatsecurite_immeuble_securite_immeuble").prop(
                "disabled",
                true
            );
            $(".classeautre").hide();

            //  $('#prestatairesecurite_immeuble').val("");
            $("#prestataire_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );
            $("#horaireprestataire_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );
            $("#etatprestataire_immeuble_securite_immeuble").prop(
                "disabled",
                false
            );
            $(".classeprestataire").show();
        }
    }

    if (type == "locataire") {
        if ($("#check_entrepriseoui").is(":checked")) {
            $("#ninea_locataire").prop("disabled", false);
            $("#documentninea_locataire").prop("disabled", false);
            $("#numerorg_locataire").prop("disabled", false);
            $("#documentnumerorg_locataire").prop("disabled", false);
            $("#documentstatut_locataire").prop("disabled", false);
            $(".entreprise").show();
        } else {
            $("#ninea_locataire").prop("disabled", true);
            $("#documentninea_locataire").prop("disabled", true);
            $("#numerorg_locataire").prop("disabled", true);
            $("#documentnumerorg_locataire").prop("disabled", true);
            $("#documentstatut_locataire").prop("disabled", true);
            $(".entreprise").hide();
            $("#ninea_locataire").val("");
            $("#documentninea_locataire").val("");
            $("#numerorg_locataire").val("");
            $("#documentnumerorg_locataire").val("");
            $("#documentstatut_locataire").val("");
        }
    }
}

function typeLocataire(selectedId) {
    console.log(selectedId.value);
    if (selectedId.value == 1) {
        $(".2").hide();
        $(".1").show();
    } else if (selectedId.value == 2) {
        $(".1").hide();
        $(".2").show();
    }
}
function onActivateCopreneur(selectedId) {
    console.log(selectedId.value);
    console.log(selectedId.checked);
    if (selectedId.checked) {
        $(".3").show();
    } else {
        $(".3").hide();
    }
}

function onActivateCopreneurLocationvente(selectedId) {
    console.log(selectedId.value);
    console.log(selectedId.checked);
    if (selectedId.checked) {
        $(".displaycopreneurlvt").show();
    } else {
        $(".displaycopreneurlvt").hide();
    }
}

function typeEntite(selectedId) {
    console.log(selectedId.value + " selectedId");
    if (selectedId.value == "SCI" || selectedId.value == "SERTEM") {
        $(".2").hide();
        $(".1").show();

        $("#div_prix_appartement").hide();

    } else if (selectedId.value == "RID") {
        $(".1").hide();
        $(".2").show();
    }
}

function Chargerimage(idform, tag = null) {
    var tagBalise = "img";
    console.log(idform);
    var rechercheTag = "";
    if (tag) {
        tagBalise = tag;

        rechercheTag = tag.split(idform);

        if (rechercheTag.length > 1) {
            tagBalise = rechercheTag[0];
        }
    }

    var fichier = document.getElementById(tagBalise + "" + idform);
    console.log("Chargerphoto", fichier);
    isValide(fichier.value) != 0
        ? ((fileReader = new FileReader()),
            isValide(fichier.value) == 1
                ? ((fileReader.onload = function (event) {
                    $("#aff" + tagBalise + idform).attr(
                        "src",
                        event.target.result
                    );
                }),
                    fileReader.readAsDataURL(fichier.files[0]),
                    idform == "produit"
                        ? $("#" + tagBalise + "produit_recup").val("")
                        : "")
                : null)
        : (alert(
            "L'extension du fichier choisi ne correspond pas aux règles sur les fichiers pouvant être uploader"
        ),
            $("#" + tagBalise + "" + idform).val(""),
            $("#aff" + tagBalise + "" + idform).attr("src", ""),
            $(".input-modal").val(""));
}

function pdfFileChanged() {
    // var fileInput = document.getElementById('pdf_file');
    // var pdfPreview = document.getElementById('pdfPreview');
    console.log("iciciciic premier test    ue u wcye  ");
    // if (fileInput.files.length > 0) {
    //     var file = fileInput.files[0];
    //     var fileUrl = URL.createObjectURL(file);
    //     console.log("iciciciic "+fileUrl);
    //     pdfPreview.innerHTML = 'Lien du fichier PDF : <a href="' + fileUrl + '" target="_blank">Télécharger le fichier PDF</a>';
    //     pdfPreview.style.display = 'block';
    // }

    const preview = document.querySelector("iframe");
    const file = document.querySelector("#pdf_file").files[0];
    console.log("iciciciic premier test    ue u wcye  " + JSON.stringify(file));
    const reader = new FileReader();
    var filename = file.name;

    reader.addEventListener(
        "load",
        function () {
            // convert file to base64 string
            preview.src = reader.result;
            console.log(
                "iciciciic premier test    ue u wcye  " + JSON.stringify(reader)
            );
        },
        false
    );

    if (file) {
        console.log("iciciciic premier test    ue u wcye  ");
        reader.readAsDataURL(file);
        console.log(
            "iciciciic premier test    ue u wcye  " + JSON.stringify(reader)
        );
    }
}

// Js pour immeuble début

// Js pour immeuble fin
