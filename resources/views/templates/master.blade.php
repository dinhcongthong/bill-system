<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
<head>
    <title>{{ isset($title) ? $title : 'Sale Management System' }}</title>
    
    <!--Bootsrap 4-->
    <link href="{{ asset('vendors/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    
    <!-- Font Awesome 5 Free -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/fontawesome/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:100,300,400,500,700,900|Noto+Serif+JP:200,300,400,500,600,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/linearicons/css/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/swiper/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap4-dialog/css/bootstrap-dialog.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/datatables/datatables.css') }}">
    
    
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home/poste.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home/poste-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home/clients.css') }}">
    
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!--Custom styles-->
    @yield('stylesheets')
</head>
<body>
    @yield('login')
    <div id="master-grand-wrapper" class="container-fluid bg-grey vh-100">
        @include('templates.header')
        
        <div class="row flex-nowrap flex-fill overflow-hidden">
            @include('sidebar.index')
            
            @yield('content')
        </div>
    </div>
    
    <script type="text/javascript" src="{{ asset('vendors/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/swiper/js/swiper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap4-dialog/js/bootstrap-dialog.min.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/client.js') }}"></script>
    <script>
        var base_url = "{{ URL::to('/') }}";
    </script>
    @yield('scripts')
</body>
</html>