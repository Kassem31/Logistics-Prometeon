@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Show Inbound Bank: {{ $inbound->inbound_no }}
                    </h3>
                </div>
            </div>
            <form action='#' class='kt-form kt-form--label-right' >
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Inbound No.:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control' placeholder='Inbound No.' value="{{ $inbound->inbound_no }}" disabled>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>ACID Number:</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control' 
                                           value="{{ $inbound->acid_number ?: 'Not set' }}" disabled>
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
<script src="{{ asset('js/log.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/shipping/show.js') }}" type="text/javascript"></script>

@endpush
