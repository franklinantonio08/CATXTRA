<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Option 1: CoreUI for Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.2.0/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-UkVD+zxJKGsZP3s/JuRzapi4dQrDDuEf/kHphzg8P3v8wuQ6m9RLjTkPGeFcglQU" crossorigin="anonymous">

    <!-- Option 2: CoreUI PRO for Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@4.3.4/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-B25jn3HrWNnbfszQBjQT5iHKf8BuG+Og9Al4zXNJgLl6orefC7UQYjD/Uxo1jMis" crossorigin="anonymous"> -->

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('../assets/favicon/apple-icon-57x57.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('../assets/favicon/apple-icon-60x60.png') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('../assets/favicon/apple-icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('../assets/favicon/apple-icon-76x76.png') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('../assets/favicon/apple-icon-114x114.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('../assets/favicon/apple-icon-120x120.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('../assets/favicon/apple-icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('../assets/favicon/apple-icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('../assets/favicon/apple-icon-180x180.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('../assets/favicon/android-icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('../assets/favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('../assets/favicon/favicon-96x96.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('../assets/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('../assets/favicon/manifest.json') }}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{ asset('../assets/favicon/ms-icon-144x144.png') }}">
<meta name="theme-color" content="#ffffff">

<link rel="stylesheet" href="{{ asset('../vendors/simplebar/css/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('../css/vendors/simplebar.css') }}">

<link href="{{ asset('../css/style.css') }}" rel="stylesheet">

<link href="{{ asset('../css/examples.css') }}" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

<!-- CSS only -->




<script>
      (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
          'gtm.start': new Date().getTime(),
          event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
          j = d.createElement(s),
          dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
          'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
      })(window, document, 'script', 'dataLayer', 'GTM-KX4JH47');
    </script>
<link href="{{ asset('../vendors/@coreui/chartjs/css/coreui-chartjs.css') }}" rel="stylesheet">

@yield('css')
  </head>
  <body>
    <!-- @section('sidebar')-->

      @include('includes.navadmin')

      @include('includes.headermenuadmin')

    <!-- @show -->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
              <!-- Start content -->
              <div class="content" style="overflow: auto !important;">
                  <div class="container">

                      

                      @yield('content')
                      


                  </div> <!-- container -->

              </div> <!-- content -->


    <footer class="footer">
        <div class="mx-auto">
           © Super Xtra. All Rights Reserved
        </div>
        
    </footer>
    </div>
        
        <script src="{{ asset('../vendors/@coreui/coreui-pro/js/coreui.bundle.min.js') }}"></script>
        <script src="{{ asset('../vendors/simplebar/js/simplebar.min.js') }}"></script>
        <script>
              if (document.body.classList.contains('dark-theme')) {
                var element = document.getElementById('btn-dark-theme');
                if (typeof(element) != 'undefined' && element != null) {
                  document.getElementById('btn-dark-theme').checked = true;
                }
              } else {
                var element = document.getElementById('btn-light-theme');
                if (typeof(element) != 'undefined' && element != null) {
                  document.getElementById('btn-light-theme').checked = true;
                }
              }
        
              function handleThemeChange(src) {
                var event = document.createEvent('Event');
                event.initEvent('themeChange', true, true);
        
                if (src.value === 'light') {
                  document.body.classList.remove('dark-theme');
                }
                if (src.value === 'dark') {
                  document.body.classList.add('dark-theme');
                }
                document.body.dispatchEvent(event);
              }
            </script>

        <script>
          var resizefunc = [];
          var pageLengthDataTable = 10;
        </script>
        
        <!-- JavaScript Bundle with Popper -->


        <script src="{{ asset('../js/jquery.min.js') }}"></script>

        <script src="{{ asset('../vendors/chart.js/js/chart.min.js') }}"></script> 
        <script src="{{ asset('../vendors/@coreui/chartjs/js/coreui-chartjs.js') }}"></script>
        <script src="{{ asset('../vendors/@coreui/utils/js/coreui-utils.js') }}"></script>
        <script src="{{ asset('../js/main.js') }}"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  

  
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> 

        <!-- <script src="{{ asset('../plugins/datatables/dataTables.buttons.min.js') }}"></script> 
        <script src="{{ asset('../plugins/datatables/buttons.colVis.min.js') }}"></script>-->
      
       
        <script src="{{ asset('../plugins/inputmask/inputmask.js') }}"></script>
        <script src="{{ asset('../plugins/inputmask/inputmask.regex.extensions.js') }}"></script>
        <script src="{{ asset('../plugins/inputmask/jquery.inputmask.js') }}"></script>

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script> -->


        <script src="{{ asset('../plugins/jquery-validation/dist/jquery.validate.js') }}"></script>
        <script src="{{ asset('../plugins/jquery-validation/dist/additional-methods.js') }}"></script>

        <script src="{{ asset('../js/comun/comun.js') }}"></script>

        
        

        <script>
            </script>

   

@yield('scripts')
  </body>
</html>
