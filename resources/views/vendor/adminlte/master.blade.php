<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    
    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')
    
    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        <link rel="stylesheet" type="text/css" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        
        <style type="text/css" media="screen">
            /* @font-face {
                font-family:'Noto Sans Myanmar';
                src:local('Noto Sans Myanmar'), url('https://www.mmwebfonts.com/fonts/NotoSansMyanmar-Regular.woff') format('woff'), url('https://www.mmwebfonts.com/fonts/NotoSansMyanmar-Regular.ttf') format('ttf');
            }

            @font-face {
                font-family:'Noto Sans Myanmar';
                src:local('Noto Sans Myanmar'), url('https://www.mmwebfonts.com/fonts/NotoSansMyanmar-Bold.woff') format('woff'), url('https://www.mmwebfonts.com/fonts/NotoSansMyanmar-Bold.ttf') format('ttf');
                font-weight:bold;
            }


            body{
                 font-family: "Noto Sans Myanmar","Pyidaungsu" !important;
            } */

            @font-face {
                font-family: "MyanmarSagar";
                src: url({{ asset('fonts/custom/myanmar_sagar.ttf') }});
            }

            @charset "UTF-8";

            html,
            body {
                /*font-family: MyanmarSagar, sans-serif !important;*/
                line-height: 1.72;
                -webkit-text-size-adjust: 100%;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            }

            .unicode {
                /*font-family: MyanmarSagar, sans-serif !important;*/
            }

            .help-block {
                color: red;
                /*font-family: MyanmarSagar, sans-serif !important;*/
                font-size: 12px;
            }

            label {
                font-size: 12px;
            }

            p,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            .h1,
            .h2,
            .h3,
            .h4,
            .h5,
            .h6,
            table,
            .table,
            input,
            select {
                /*font-family: MyanmarSagar, sans-serif !important;*/
            }

            /*  h3 {
              font-size: 16px  !important;
            }*/

            /* h5 {
                font-size: 14px  !important;
            }*/

            a,
            p,
            .btn,
            .page-link {
                font-size: 12px !important;
            }

            .size,
            .select2-selection__placeholder,
            .select2-selection__rendered,
            .select2-results__option {
                /*font-family: MyanmarSagar, sans-serif !important;*/
                font-size: 12px;
            }

            label,
            strong,
            input,
            select,
            textarea,
            table,
            tr,
            td {
                /*font-family: MyanmarSagar, sans-serif !important;*/
                font-size: 12px !important;
            }

            /*tr:hover td {
                background: #c7d4dd !important;
            }*/
             tr {
                cursor: pointer;
            }
            .styled-table {
            border-collapse: collapse;
            /*margin: 25px 0;*/
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }
            .styled-table thead tr {
                background-color: #009879;
                color: #ffffff;
                text-align: left;
            }
            .styled-table th,
            .styled-table td {
                padding: 8px 15px;
            }

            .styled-table tbody tr {
                border-bottom: 1px solid #dddddd;
            }

            /*.styled-table tbody tr:nth-of-type(even) {
                background-color: #c7d4dd;
            }*/

            .styled-table tbody tr:last-of-type {
                border-bottom: 2px solid #009879;
            }

            .tab {
                  overflow: hidden;
                  border: 1px solid #4287f5;
                  background-color: #f1f1f1;
                  /*border-radius: 10px;*/
                  /*height: 60px;*/
                }

                /* Style the buttons inside the tab */
                .tab button {
                  background-color: inherit;
                  float: left;
                  border: none;
                  outline: none;
                  cursor: pointer;
                  padding: 14px 16px;
                  transition: 0.3s;
                  font-size: 17px;
                }

                /* Change background color of buttons on hover */
                .tab button:hover {
                  background-color: #ddd;
                }

                /* Create an active/current tablink class */
                .tab button.active {
                  background-color: #ccc;
                }

                /* Style the tab content */
                .tabcontent {
                  display: none;
                  padding: 6px 12px;
                  border: 1px solid #ccc;
                  border-top: none;
                }

        </style>
    @else
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif
    
</head>

<body class="@yield('classes_body')" @yield('body_data')>
    
    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        {{-- Configured Scripts --}}
        @include('adminlte::plugins', ['type' => 'js'])

        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @else
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @endif

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

</body>

</html>
