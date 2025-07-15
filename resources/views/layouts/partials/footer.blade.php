
<!--AngularJS-->
<script src="{{asset('assets/js/angular/angular.min.js')}}"></script>
<script src="{{asset('assets/js/angular/angular-cookies.min.js')}}"></script>
<script src="{{asset('assets/js/angular/angular-route.min.js')}}"></script>
<script src="{{asset('assets/js/angular/angular-sanitize.min.js')}}"></script>
<script src="{{asset('assets/js/angular/angular-loadscript.js')}}"></script>
<script src="{{asset('assets/js/angular/moment.min.js')}}"></script>
<script src="{{asset('assets/js/angular/angular-moment.min.js')}}"></script>
<script src="{{asset('assets/js/angular/angular-filter.min.js')}}"></script>
<script src="{{asset('assets/js/angular/angular-long-press.js')}}"></script>
<script src="{{asset('assets/js/angular/angular-touch.js')}}"></script>

<script src="{{asset('assets/js/angular/ui-bootstrap-tpls-2.5.0.min.js')}}" type="text/javascript"></script>
<!-- <script src="{{asset('assets/js/angular/dx.all.js')}}" type="text/javascript"></script> -->
<script src="{{asset('assets/js/angular/angular-bootstrap-toggle.min.js')}}" type="text/javascript"></script>

<!--<script src="{{asset('assets/js/pickers/dateTime/moment-with-locales.min.js')}}"></script>
<script src="{{asset('assets/js/pickers/daterange/daterangepicker.js')}}"></script>
<script src="{{asset('assets/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('assets/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('assets/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('assets/js/pickers/pickadate/legacy.js')}}"></script>-->

<!-- alert confirmation modal -->
<script src="{{asset('assets/js/sweetalert2.all.min.js')}}"></script>
<!-- Optional: include a polyfill for ES6 Promises for IE11 -->
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.6/signature_pad.umd.min.js" integrity="sha512-EfX4vFXXWtDM8PcSpNZK3oVNpU50itrpemKPX6/KJTZnT/wM81S5HGzHs+G9lqBBjemL4GYoWVCjdhGP8qTHdA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signet/0.4.8/signet.min.js" integrity="sha512-eS4pzCFF9XFkboChzFk7+92ObkEj1zDWx/NGp9VuIwIa+BwNB+MsvaDCG0+knctOqvc69TOJZ0t7Gktgm8v6Ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset("assets/js/angular/controller.js?v=").(Carbon\Carbon::now()->format('h:i:s__d.m.Y'))}}"></script>


<script src="{{asset('assets/js/app.js')}}"></script>

<script src="{{asset('assets/js/iziToast/dist/js/iziToast.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/fontawesome/js/all.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/js/app-laravel.js')}}"></script>
<script src="{{asset('assets/js/intlTelInput.min.js')}}"></script>

<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
        integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
        crossorigin=""></script>
        

<!--<script>
    window.laravel_echo_port="{{env("LARAVEL_ECHO_PORT")}}";
</script>
<script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>

<script src="{{ url('/assets/js/laravel-echo-setup.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    var i = 0;
    window.Echo.channel('user-channel')
        .listen('.UserEvent', (data) => {
            i++;
            $("#notification").append('<div class="alert alert-success">'+i+'.'+data.title+'</div>');
        });
</script>-->
