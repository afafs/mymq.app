<!DOCTYPE html>
@php
    $selLang = App\Models\Language::where('code', request()->input('language'))->first();
@endphp
<html lang="{{ !empty($selLang) ? $selLang->code : 'en' }}" @if (!empty($selLang) && $selLang->rtl == 1) dir="rtl" @endif>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <title>{{ $bs->website_title }} - Admin</title>
    <link rel="icon" href="{{ asset('assets/front/img/' . $bs->favicon) }}">
    @includeif('admin.partials.styles')

    @if (!empty($selLang) && $selLang->rtl == 1)
        <style>
            #editModal form input,
            #editModal form textarea,
            #editModal form select {
                direction: rtl;
            }

            #editModal form .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endif

    @yield('styles')

    @if (!empty($selLang) && $selLang->rtl == 1)
    <!--====== RTL Style css ======-->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-rtl.css') }}">
    @else
    <style>
        .navbar-expand-lg .navbar-nav .dropdown-menu {
            left: auto;
            right: 0;
        }
    </style>
    @endif

</head>

<body @if (request()->cookie('admin-theme') == 'dark') data-background-color="dark" @endif>
    <div class="wrapper">

        {{-- top navbar area start --}}
        @includeif('admin.partials.top-navbar')
        {{-- top navbar area end --}}


        {{-- side navbar area start --}}
        @includeif('admin.partials.side-navbar')
        {{-- side navbar area end --}}


        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>
            @includeif('admin.partials.footer')
        </div>

    </div>
    
    @includeif('admin.partials.scripts')

     {{-- Loader --}}
    <div class="request-loader">
        <img src="{{ asset('assets/admin/img/loader.gif') }}" alt="">
    </div>
    {{-- Loader --}}

   
</body>

</html>
