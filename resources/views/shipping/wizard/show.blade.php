@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label' style="width:25%;">
                    <h3 class='kt-portlet__head-title' style="width:100%;">
                        @if(!is_null($inbound->inbound_no))
                        <span style="width:35%;display: inline-block;">Inbound:</span>
                        <span class="kt-badge kt-badge--info kt-badge--inline kt-badge--lg" style="width:100px;">{{ $inbound->inbound_no }}</span>
                        @else
                        Inbound
                        @endif
                        <br/>
                        <span style="width:35%;display: inline-block;margin-top:5px;">Shippment Status: </span>
                        <span class="kt-badge kt-badge--{{ $inbound->getShippmentStatusStyle() }} kt-badge--inline kt-badge--lg" style="width:100px;">
                            {{ $inbound->getShippmentStatus() }}</span>

                    </h3>

                </div>
                <div class="kt-portlet__head-toolbar" style="width:75%;">
                    <div class="kt-portlet__head-actions" style="width:100%;">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $currentPercent }}%;" aria-valuenow="{{ $currentPercent }}" aria-valuemin="0" aria-valuemax="100">{{ $currentPercent }}%</div>
                        </div>
                    </div>

                </div>
            </div>
            <form action='#' class='kt-form kt-form--label-right' method='get' novalidate>
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class="kt-grid  kt-wizard-v2 kt-wizard-v2--white" id="kt_wizard" data-ktwizard-state="step-first">
                                <div class="kt-grid__item kt-wizard-v2__aside">
                                    <!--begin: Form Wizard Nav -->
                                    @include('shipping.wizard.partial._wizard-nav')
                                    <!--end: Form Wizard Nav -->
                                </div>
                                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v2__wrapper" style="padding:15px;">
                                        @csrf
                                        <!--begin: Form Wizard Step 1 - Inbound Details-->
                                        @include('shipping.wizard.partial._inbound-edit')
                                        <!--end: Form Wizard Step 1-->
                                        <!--begin: Form Wizard Step 2 - Booking Data-->
                                        @if($inbound->canGoBooking())
                                            @include('shipping.wizard.partial._booking')
                                        @endif
                                        <!--end: Form Wizard Step 2-->
                                        <!--begin: Form Wizard Step 3 - Shipping Details-->
                                        @if($inbound->canGoShipping())
                                        @include('shipping.wizard.partial._shipping')
                                        @endif
                                        <!--end: Form Wizard Step 3-->
                                        <!--begin: Form Wizard Step 4 - Document Cycle-->
                                        @if ($inbound->canGoDocumentCycle())
                                            @include('shipping.wizard.partial._document')
                                        @endif
                                        <!--end: Form Wizard Step 4-->
                                        <!--begin: Form Wizard Step 5 - Clearance Details-->
                                        @if($inbound->canGoClearance())
                                        @include('shipping.wizard.partial._clearance')
                                        @endif
                                        <!--end: Form Wizard Step 5-->
                                        <!--begin: Form Wizard Step 6 - Delivery Details-->
                                        @if ($inbound->canGoDelivery())
                                            @include('shipping.wizard.partial._delivery')
                                        @endif
                                        <!--end: Form Wizard Step 6-->
                                        <!--begin: Form Wizard Step 7 - Bank-->
                                        @if($inbound->canGoBank())
                                        <div class="bankInfoDiv">
                                            @include('shipping.wizard.partial._bank')
                                        </div>
                                        @endif
                                        <!--end: Form Wizard Step 7-->
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
                                <a href='{{ route('inbound.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@include('_log');
@push('styles')
<link href="{{ asset('assets/css/pages/wizard/wizard-2.css') }}" rel="stylesheet" type="text/css" />
<style>
    .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover{
        background: none !important;
        color: #cccdd0 !important;
    }
    #otherShippingLine.hidden{
        display: none;
    }
    #lg.hidden,#form4.hidden,#form6.hidden,#transit.hidden{
        display: none;
    }
    button.btn.btn-icon.log-btn{
        display: block;
    }
    div.kt-portlet div.kt-portlet__head{
        min-height: 75px;
    }
</style>
@endpush
@push('scripts')
<script>
    var startStep = Number({{ $inbound->currentStep }});
    if(startStep > 7){
        startStep = 7;
    }
    var wizard = new KTWizard('kt_wizard',{startStep: startStep,clickableSteps: true});
    wizard.on('beforeNext', function(wizardObj) {
        if(wizard.currentStep >= startStep){
            wizardObj.stop();
        }
    });
</script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/moment.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/log.js') }}" type="text/javascript"></script>


<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/shipping/show.js') }}" type="text/javascript"></script>

@endpush
