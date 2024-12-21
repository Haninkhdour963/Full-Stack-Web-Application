<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- CSS Links -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>User Management</title>

    <!-- Plugins: CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">

    <!-- Layout styles -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" />

    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    @include('layouts.inc.admin-navbar')

    <div class="container-fluid page-body-wrapper">
  
    @if(Auth::check()) <!-- Check if the user is logged in -->
            @if(Auth::user()->isAdmin()) <!-- Check if the logged-in user is an Admin -->
                @include('layouts.inc.admin-sidebar')
            @elseif(Auth::user()->isTechnician()) <!-- Check if the logged-in user is a Technician -->
                @include('layouts.inc.technician-sidebar')
                @elseif(Auth::user()->isClient())
                @include('layouts.inc.client-sidebar')
            @else <!-- Default to Client sidebar if the user is neither Admin nor Technician -->
                @include('/')
            @endif
        @endif

        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
    @if(Auth::check()) <!-- Check if the user is logged in -->
            @if(Auth::user()->isAdmin()) <!-- Check if the logged-in user is an Admin -->
            @include('layouts.inc.admin-footer')
            @elseif(Auth::user()->isTechnician()) 
            @include('layouts.inc.technician-footer')
            @else
            @include('layouts.inc.client-footer')
            @endif
        @endif
   
    <!-- JavaScript Links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>

    <!-- Plugins: JS -->
    <script src="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('assets/js/misc.js')}}"></script>
    @stack('scripts')
</body>

</html>
