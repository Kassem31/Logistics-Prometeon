
@extends('layout')
@section('content')
    <!-- begin:: Page -->

    <!--[html-partial:include:{"file":"partials/_header/base-mobile.html"}]/-->
    @include('partials/_header/base-mobile')
    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

                @include('partials/_header/base')
                <div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                            @if(session()->has('success'))
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-text">{{ session()->get('success')}}</div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @yield('container')
                        </div>
                    </div>
                </div>
                @include('partials/_footer/base')
            </div>
        </div>
    </div>

    <!-- end:: Page -->

    <!--[html-partial:include:{"file":"partials/_quick-panel.html"}]/-->
    {{-- @include('partials/_quick-panel') --}}
    <!--[html-partial:include:{"file":"partials/_scrolltop.html"}]/-->
    @include('partials/_scrolltop')
    <!--[html-partial:include:{"file":"partials/_chat.html"}]/-->
    {{-- @include('partials/_chat') --}}
@endsection

