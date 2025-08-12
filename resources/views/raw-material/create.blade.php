@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Create Raw material
                    </h3>
                </div>
            </div>
            <form action='{{ route('raw-materials.store') }}' class='kt-form kt-form--label-right' method='POST' >
                @csrf
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>SAP Code:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('sap_code') is-invalid @enderror' placeholder='SAP Code' name='sap_code' value="{{ old('sap_code') }}">
                                    @error('sap_code')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Name:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('name') is-invalid @enderror' placeholder='Name' name='name' value="{{ old('name') }}">
                                    @error('name')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Material Group:*</label>
                                <div class='col-lg-6'>
                                    <select class='form-control @error('material_group_id') is-invalid @enderror' name='material_group_id'>
                                        <option value="">Select Material Group ...</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}" {{ $group->id == old('material_group_id') ? 'selected': "" }}>{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('material_group_id')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>HS Code:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('hs_code') is-invalid @enderror' placeholder='HS Code' name='hs_code'  value='{{ old('hs_code') }}'>
                                    @error('hs_code')
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
                                <a href='{{ route('raw-materials.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
