<!DOCTYPE html>
<html lang="en">

@include('layouts.page.header')

<body>
    @include('layouts.page.navbar')

    @yield('content')

    @include('layouts.page.footer')

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assetsPages/lib/wow/wow.min.js') }}"></script>
    <script src="{{asset('assetsPages/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assetsPages/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assetsPages/lib/owlcarousel/owl.carousel.min.js')}}"></script>
   

   
    <!-- Template Javascript -->
    <script src="{{ asset('assetsPages/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
