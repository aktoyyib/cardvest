<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TDKQZXZ');</script>
<!-- End Google Tag Manager -->

  <meta charset="utf-8">
  <meta name="author" content="Softnio">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Fully featured and complete ICO Dashboard template for ICO backend management.">
  <!-- Fav Icon  -->
  <link rel="shortcut icon" href="{{ asset('images/logo-sm.png') }}">
  <!-- Site Title  -->
  <title>Cardvest - Buy & Sell Gift Cards</title>
  <!-- Vendor Bundle CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.css?ver=104') }}">
  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="{{ asset('assets/css/style-violet.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

</head>

<body class="page-user">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TDKQZXZ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

  @include('partials.header')

  <!-- .page-content -->
  @yield('content')

  @include('partials.footer')

  <!-- JavaScript (include all script here) -->
  <script src="{{ asset('assets/js/jquery.bundle.js?ver=104') }}"></script>
  <script src="{{ asset('assets/js/script.js?ver=104') }}"></script>
  <script type="text/javascript">

        $(window).on('load', function() {
            @if($errors)
            @foreach($errors->all() as $error)
            swal(
                'Ooops!!!',
                '{{ $error }}',
                'error',
            );
            @endforeach
            @endif

            @if (Session()->has('error'))
            swal(
                'Ooops!!!',
                '{{ session('error') }}',
                'error',
            );
            @elseif (Session()->has('success'))
            swal(
                'Thank you!',
                '{{ session('success') }}',
                'success',
            );
            @elseif (Session()->has('info'))
            swal(
                'Note',
                '{{ session('info') }}',
                'info',
            );
            @elseif (Session()->has('warning'))
            swal(
                'Warning',
                '{{ session('warning') }}',
                'warning',
            );
            @endif
            // $(document).ready(function(){
            //   $(window).load(function(){
            //     alert("Page loaded.");
            //   });
            // });
        });
    </script>
  @stack('scripts')
  <script src="//code.tidio.co/vuck458qqc6qwmrxgmpwgp8ndvamsbix.js"></script>
</body>

</html>