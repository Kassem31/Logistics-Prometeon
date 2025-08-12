@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Edit Inbound Bank: {{ $inbound->inbound_no }}
                    </h3>
                </div>
            </div>
            <form action='{{ route('inbound-banks.update',['inbound_bank'=>$inbound]) }}' class='kt-form kt-form--label-right' method='POST' >
                @csrf
                @method('put')
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Inbound No.:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('inbound.inbound_no') is-invalid @enderror' placeholder='Inbound No.'
                                    name='inbound[inbound_no]' value="{{ old('inbound.inbound_no',$inbound->inbound_no) }}">
                                    @error('inbound.inbound_no')
                                    <span class='form-text text-danger'>{{ $message }}</span>
                                @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>ACID Number:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control' readonly
                                           value="{{ $inbound->acid_number }}" 
                                           placeholder="No ACID Number set">
                                    <span class='form-text text-muted'>ACID Number is managed in the main Inbound form.</span>
                                </div>
                            </div>
                            @include("shipping.wizard.partial._bank")
                        </div> <!-- End section__body-->
                    </div>
                </div>
                <div class='kt-portlet__foot'>
                    <div class='kt-form__actions'>
                        <div class='row'>
                            <div class='col-lg-6'></div>
                            <div class='col-lg-6'>
                                <button type='submit' class='btn btn-success'>Save</button>
                                <a href='{{ route('inbound-banks.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@include('_log');

@push('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/log.js') }}" type="text/javascript"></script>

@endpush
