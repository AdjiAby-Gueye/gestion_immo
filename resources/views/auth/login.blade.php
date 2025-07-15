@extends('layouts.app')

@section('content')

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>GESTIMMO</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://firebasestorage.googleapis.com/v0/b/essai-ionic.appspot.com/o/bootstrap.min.css?alt=media&token=3927be71-079f-4ed9-b58f-9ecb311923ca" rel="stylesheet">
    <!-- Template Stylesheet -->
    <!-- <link href="css/style.css" rel="stylesheet"> -->
    <link href="https://firebasestorage.googleapis.com/v0/b/essai-ionic.appspot.com/o/style.css?alt=media&token=c6bcb9de-8528-42b7-ac5f-649fe43fa16f" rel="stylesheet">
</head>

<!--Coded with love by Mutiullah Samim-->
<body >

<div class="container mt-5">
        {{-- <div class="row g-0" >
            <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 100%;">
                <div class="position-relative h-100">
                    <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('assets/images/cover.jpg') }}" style="object-fit: cover;" alt="">
                </div>
            </div>
            <div class="col-lg-6 quote-text py-5 wow fadeIn" data-wow-delay="0.5s">
                <div class="p-lg-5 pe-lg-0">
                    <!-- <h2 class="text-primary">Connexion</h2> -->
                    <img class="logoimmo" src="{{asset('assets/images/gestirent.png')}}" alt="logo">
                    <form method="post" action="{{ url('/login') }}">
                        <div class="row g-3 mg10">
                            <div class="col-12 col-sm-1">
                                    <span>
                                        <i class="fa fa-user iconlogin"></i>
                                    </span>
                            </div>
                            <div class="col-12 col-sm-11">
                                <input type="email" class="form-control border-0 input_user" name="email" value="" placeholder="Identifiant" style="height: 55px; margin-bottom: 5px">

                            </div>
                            <div class="col-12 col-sm-1">
                                    <span>
                                        <i class="fa fa-lock iconlogin"></i>
                                    </span>
                            </div>
                            <div class="col-12 col-sm-11">
                                <input type="password" class="form-control border-0 input_pass"  name="password" id="password_connexion" placeholder="Mot de passe" style="height: 55px;">
                            </div>
                            <!--<div >
                                <i class="fa fa-eye iconlogin"></i>
                            </div>-->
                            <div class="col-12 col-sm-1"  style="margin-left: -10%;margin-top: 0%;">
                                <label for="check" class="pointer-hover btn-eye" style="margin-top: 20px">
                                    <span id="fa-eye" class="fa fa-eye iconlogin" style="font-size: 21px;color: #00000090"></span>
                                    <span id="fa-eye-slash" class="fa fa-eye-slash iconlogin" style="font-size: 21px;color: #00000090"></span>
                                    <div class="custom-control custom-checkbox mr-2 d-none">
                                        <input type="checkbox" onclick="ShowPassword()" id="check" class="custom-control-input"><label class="custom-control-label" for="mdp"></label>
                                    </div>
                                </label>
                            </div>
                            @if (count($errors) > 0)
                            <div style="margin: 0 auto">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li class="error">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="col-12 mg10">
                                <button class="btn btn-primary bouttonconnexion rounded-pill py-3 px-5 centre" type="submit">Connexion</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}

        <style>
            .password-toggle-icon {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  cursor: pointer;
}

.password-toggle-icon i {
  color: #555;
}
.form-control{
    height: 50px;
}
button {
    height: 50px;
    background-color: #0B477E;
}
.form-control:focus {
  outline: #ddd 2px ;

}
        </style>
        <div class="row  m">
            <div class="card mb-3 shadow p-md-5 p-sm-1" >
                <div class="row g-5">
                  <div class="col-md-6">
                    <img src="{{asset('assets/images/cover.jpg')}}" class="img-fluid  " alt="...">
                  </div>
                  <div class="col-md-6">
                    <div class="card-body d-flex flex-column gap-3">
                        <img src="{{asset('assets/images/gestirent.png')}}" class=" img-fluid " alt="...">
                      <h5 class="card-title"></h5>

                        <div class="">
                        {{-- start form --}}
                            <form method="post" action="{{ url('/login') }}">
                            <div class="form-group mb-3">
                                {{-- <label class="" for="inlineFormInputGroup"></label> --}}
                                <div class="input-group mb-2">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-user iconlogin"></i>
                                    </div>
                                  </div>
                                  <input type="text" class="form-control" name="email" id="inlineFormInputGroup" placeholder="email">
                                </div>
                              </div>
                              <div class="form-group mb-3">
                                {{-- <label class="" for="inlineFormInputGroup"></label> --}}
                                <div class="input-group mb-2">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-lock iconlogin"></i>
                                    </div>
                                  </div>
                                  <input type="password" class="form-control" id="password_connexion" name="password"  placeholder="Mot de passe">
                                  <span
                  class="password-toggle-icon"

                >
                  <i
                  onclick="ShowPassword()"
                    class="fa fa-eye"
                    id="fa-eye"
                    aria-hidden="true"
                  ></i>
                  <i
                  onclick="ShowPassword()"
                  id="fa-eye-slash"
                    class="fa fa-eye-slash"
                    aria-hidden="true"
                  ></i>
                </span>
                                </div>
                              </div>

                              @if (count($errors) > 0)
                              <div style="margin: 0 auto" class="text-danger  form-text">
                                  <ul>
                                      @foreach($errors->all() as $error)
                                      <li class="error">{{ $error }}</li>
                                      @endforeach
                                  </ul>
                              </div>
                              @endif

                              <div class="float-right">
                              <button type="submit" class="btn text-white mt-2 float-right">Se connecter</button>

                              </div>
        </form>
                              {{-- enfrom --}}
                        </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>

</div>

<!--<div class="container h-100">
    <div class="d-flex justify-content-center h-100">
        <div class="user_card">
            <div class="d-flex justify-content-center">
                <div class="brand_logo_container">
                    <img src="{{asset('assets/images/maison.png')}}" class="brand_logo" alt="Logo">
                </div>
            </div>
            <div class="d-flex justify-content-center form_container">
                <form method="post" action="{{ url('/login') }}">
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" name="email" class="form-control input_user" value="" placeholder="Identifiant">
                    </div>

                    <div class="input-group mb-2">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" name="password" id="password_connexion" class="form-control input_pass" value="" placeholder="Mot de passe">
                    </div>
                    <div class="d-flex justify-content-end" >
                        <label for="check" class="pointer-hover btn-eye">
                            <span id="fa-eye" class="fa fa-eye" style="font-size: 21px;color: #00000090"></span>
                            <span id="fa-eye-slash" class="fa fa-eye-slash" style="font-size: 21px;color: #00000090"></span>
                            <div class="custom-control custom-checkbox mr-2 d-none">
                                <input type="checkbox" onclick="ShowPassword()" id="check" class="custom-control-input"><label class="custom-control-label" for="mdp"></label>
                            </div>
                        </label>
                    </div>
                    @if (count($errors) > 0)
                    <div>
                        <ul>
                            @foreach($errors->all() as $error)
                            <li class="error">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">

                    </div>
                    <div class="d-flex justify-content-center mt-3 login_container">
                        <button  type="submit" name="login" class="btn login_btn">Connexion</button>
                    </div>
                </form>
            </div>

            <div class="mt-4">

                <div class="d-flex justify-content-center links">
                    <a href="#">Mot de passe oublié?</a>
                </div>
            </div>
        </div>
    </div>
</div>-->
</body>

<script>
    /*function ShowPassword() {
        var x = document.getElementById("password_connexion");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }*/
    var y = document.getElementById("fa-eye-slash");
    var z = document.getElementById("fa-eye");
    y.style.display = "none";
    z.style.display = "block";
    function ShowPassword() {
        var x = document.getElementById("password_connexion");
        if (x.type === "password") {
            x.type = "text";
            y.style.display = "block";
            z.style.display = "none";
        } else {
            x.type = "password";
            y.style.display = "none";
            z.style.display = "block";
        }
    }

</script>
</html>

@endsection

