@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Edit Country : {{ $model->name }}
                    </h3>
                </div>
            </div>
            <form action='{{ route('countries.update',['country'=>$model]) }}' class='kt-form kt-form--label-right' method='POST' enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Flag</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="kt-avatar kt-avatar--outline" id="kt_user_add_avatar">
                                        <div class="kt-avatar__holder" style="background-image: url({{ $model->country_flag }})"></div>
                                        <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="Change Flag">
                                            <i class="fa fa-pen"></i>
                                            <input type="file" name="country_flag">
                                        </label>
                                        <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="Cancel Flag">
                                            <i class="fa fa-times"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Name:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('name') is-invalid @enderror' placeholder='Name' name='name' value="{{ old('name',$model->name) }}">
                                    @error('name')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Prefix:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('prefix') is-invalid @enderror' placeholder='Prefix' name='prefix'  value='{{ old('prefix',$model->prefix) }}'>
                                    @error('prefix')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Currency:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('currency') is-invalid @enderror' placeholder='Currency' name='currency'  value='{{ old('currency',$model->currency) }}'>
                                    @error('currency')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Currency Is Active:</label>
                                <div class="col-3">
                                    <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                                        <label>
                                            <input type="checkbox" name="currency_is_active" {{ old('currency_is_active',$model->currency_is_active) ? 'checked' : '' }}>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='kt-portlet__foot'>
                    <div class='kt-form__actions'>
                        <div class='row'>
                            <div class='col-lg-6'></div>
                            <div class='col-lg-6'>
                                <button type='submit' class='btn btn-success'>Save</button>
                                <a href='{{ route('countries.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('assets/js/pages/custom/user/add-user.js') }}" type="text/javascript"></script>
@endpush
