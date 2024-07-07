<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,400i,600,700" rel="stylesheet">

        <link rel="stylesheet" href="{{asset("/front/css/open-iconic-bootstrap.min.css")}}">
        <link rel="stylesheet" href="{{asset("/front/css/animate.css")}}">
        
        <link rel="stylesheet" href="{{asset("/front/css/owl.carousel.min.css")}}">
        <link rel="stylesheet" href="{{asset("/front/css/owl.theme.default.min.css")}}">
        <link rel="stylesheet" href="{{asset("/front/css/magnific-popup.css")}}">
    
        <link rel="stylesheet" href="{{asset("/front/css/aos.css")}}">
    
        <link rel="stylesheet" href="{{asset("/front/css/ionicons.min.css")}}">
    
        <link rel="stylesheet" href="{{asset("/front/css/bootstrap-datepicker.css")}}">
        <link rel="stylesheet" href="{{asset("/front/css/jquery.timepicker.css")}}">
    
        
        <link rel="stylesheet" href="{{asset("/front/css/flaticon.css")}}">
        <link rel="stylesheet" href="{{asset("/front/css/icomoon.css")}}">
        <link rel="stylesheet" href="{{asset("/front/css/style.css")}}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        @include('front.components.header')
        {{ $slot }}
        @include('front.components.footer')

        <script src="{{asset("/front/js/jquery.min.js")}}"></script>
        <script src="{{asset("/front/js/jquery-migrate-3.0.1.min.js")}}"></script>
        <script src="{{asset("/front/js/popper.min.js")}}"></script>
        <script src="{{asset("/front/js/bootstrap.min.js")}}"></script>
        <script src="{{asset("/front/js/jquery.easing.1.3.js")}}"></script>
        <script src="{{asset("/front/js/jquery.waypoints.min.js")}}"></script>
        <script src="{{asset("/front/js/jquery.stellar.min.js")}}"></script>
        <script src="{{asset("/front/js/owl.carousel.min.js")}}"></script>
        <script src="{{asset("/front/js/jquery.magnific-popup.min.js")}}"></script>
        <script src="{{asset("/front/js/aos.js")}}"></script>
        <script src="{{asset("/front/js/jquery.animateNumber.min.js")}}"></script>
        <script src="{{asset("/front/js/bootstrap-datepicker.js")}}"></script>
        <script src="{{asset("/front/js/jquery.timepicker.min.js")}}"></script>
        <script src="{{asset("/front/js/scrollax.min.js")}}"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
        <script src="{{asset("/front/js/google-map.js")}}"></script>
        <script src="{{asset("/front/js/main.js")}}"></script>
          
    </body>
</html>
