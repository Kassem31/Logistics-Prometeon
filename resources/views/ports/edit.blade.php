@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Edit Port : {{ $model->name }}
                    </h3>
                </div>
            </div>
            <form action='{{ route('ports.update',['port'=>$model]) }}' class='kt-form kt-form--label-right' method='POST' >
                @csrf
                @method('put')
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
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
                                <label class='col-lg-3 col-form-label'>Country:</label>
                                <div class='col-lg-6'>
                                    <select class='form-control @error('country_id') is-invalid @enderror' name='country_id'>
                                        <option value="">Select Country ...</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == old('country_id',$model->country_id) ? 'selected':''}}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
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
                                <a href='{{ route('ports.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
