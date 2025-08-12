@extends('layout')
@section('content')
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url({{ asset('assets/media//bg/bg-3.jpg') }});">
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__logo">
                            <a href="javascript:void(0)">
                                <img style="width:250px;" src="{{ asset('prometeon-pirelli-2.jpg') }}">
                            </a>
                        </div>
                        <div class="kt-login__signin">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">Sign In - Logistics Inbound</h3>
                            </div>
                            @error('name')
                                <div class="alert alert-danger text-center">{{ $message }}</div>
                            @enderror
                            <form class="kt-form" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="User Name" name="name" autocomplete="off" required>
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="password" placeholder="Password" name="password" required>
                                </div>
                                <div class="kt-login__actions">
                                    <button id="kt_login_signin_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<link href="assets/css/pages/login/login-3.css" rel="stylesheet" type="text/css" />
@endpush
