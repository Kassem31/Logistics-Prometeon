@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Create Inco Forwarders
                    </h3>
                </div>
            </div>
            <form action='{{ route('inco-forwarders.store') }}' class='kt-form kt-form--label-right' method='POST' >
                @csrf
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
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
                                <label class='col-lg-3 col-form-label'>Contact Person:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('contact_person') is-invalid @enderror' placeholder='Contact Person' name='contact_person' value="{{ old('contact_person') }}">
                                    @error('contact_person')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Email:</label>
                                <div class='col-lg-6'>
                                    <input type='email' class='form-control @error('email') is-invalid @enderror' placeholder='Email' name='email' value="{{ old('email') }}">
                                    @error('email')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Phone:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('phone') is-invalid @enderror' placeholder='Phone' name='phone' value="{{ old('phone') }}">
                                    @error('phone')
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
                                            <option value="{{ $item->id }}" {{ $item->id == old('country_id') ? 'selected':''}}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Is Active:</label>
                                <div class="col-3">
                                    <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                                        <label>
                                            <input type="checkbox" name="is_active" {{ old('is_active',1) ? 'checked' : '' }}>
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
                                <a href='{{ route('inco-forwarders.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
