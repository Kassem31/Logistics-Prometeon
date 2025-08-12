@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Edit Material Group : {{ $model->name }}
                    </h3>
                </div>
            </div>
            <form action='{{ route('material-groups.update',['material_group'=>$model]) }}' class='kt-form kt-form--label-right' method='POST' >
                @csrf
                @method('put')
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class='form-group row'>
                                <label class='col-lg-2 col-form-label'>Name:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('name') is-invalid @enderror' placeholder='Name' name='name' value="{{ old('name',$model->name) }}">
                                    @error('name')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Assign Person In Charge:</label>
                                <div class="col-lg-6">
                                    <select class="form-control kt-selectpicker" multiple name="users[]">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id,old('users',$modelUsers)) ? 'selected' : ''}}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
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
                                <a href='{{ route('material-groups.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
@endpush
