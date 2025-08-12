@extends('main')
@section('container')
    <div class="row">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Reset User Password
                    </h3>
                </div>
            </div>
            <form action="{{ route('user.resetpassword') }}" class="kt-form kt-form--label-right" method="POST">
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="kt-section__body">
                            <div class='form-group row'>
                                 <label class='col-lg-3 col-form-label'>New Password:*</label>
                                 <div class='col-lg-6'>
                                     <input type='password' class='form-control @error('password') is-invalid @enderror' placeholder='New Password' name='password'>
                                     @error('password')
                                         <span class='form-text text-danger'>{{ $message }}</span>
                                     @enderror
                                 </div>
                            </div>
                            <div class='form-group row'>
                                 <label class='col-lg-3 col-form-label'>Confirm Password:</label>
                                 <div class='col-lg-6'>
                                     <input type='password' class='form-control' placeholder='Confirm Password' name='password_confirmation'>
                                     <span class='form-text text-danger'></span>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="{{ route('home') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

