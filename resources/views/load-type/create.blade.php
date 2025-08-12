@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Create Container Load Type
                    </h3>
                </div>
            </div>
            <form action='{{ route('load-types.store') }}' class='kt-form kt-form--label-right' method='POST' >
                @csrf
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Name:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('name') is-invalid @enderror' placeholder='Name' name='name'  value='{{ old('name') }}'>
                                    @error('name')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Prefix:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('prefix') is-invalid @enderror' placeholder='Prefix' name='prefix'  value='{{ old('prefix') }}'>
                                    @error('prefix')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
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
                                <a href='{{ route('load-types.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
