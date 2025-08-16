@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Create New Inbound
                    </h3>

                </div>
                <div class="kt-portlet__head-toolbar" style="width:75%;">
                        <div class="kt-portlet__head-actions" style="width:100%;">
                            <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                        </div>
                    </div>
            </div>
            <form action='{{ route('inbound.store') }}' class='kt-form kt-form--label-right' method='POST' novalidate>
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class="kt-grid  kt-wizard-v2 kt-wizard-v2--white" id="kt_wizard" data-ktwizard-state="step-first">
                                <div class="kt-grid__item kt-wizard-v2__aside">
                                    <!--begin: Form Wizard Nav -->
                                    @include('shipping.wizard.partial._create-wizard-nav')
                                    <!--end: Form Wizard Nav -->
                                </div>
                                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v2__wrapper" style="padding:15px;">
                                        @csrf
                                        <!--begin: Form Wizard Step 1-->
                                        @include('shipping.wizard.partial._inbound')
                                        <!--end: Form Wizard Step 1-->
                                        <!--begin: Form Wizard Step 2-->
                                        {{-- @include('shipping.wizard.partial._booking') --}}
                                        <!--end: Form Wizard Step 2-->
                                        <!--begin: Form Wizard Step 3-->
                                        {{-- @include('shipping.wizard.partial._document') --}}
                                        <!--end: Form Wizard Step 3-->
                                        <!--begin: Form Wizard Step 4-->
                                        {{-- @include('shipping.wizard.partial._clearance') --}}
                                        <!--end: Form Wizard Step 4-->
                                        <!--begin: Form Wizard Step 5-->
                                        {{-- @include('shipping.wizard.partial._delivery') --}}
                                        <!--end: Form Wizard Step 5-->
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
                                <input type="hidden" id="current_wizard_step" name="current_wizard_step" value="1">
                                <button type='submit' class='btn btn-success'>Save</button>
                                <a href='{{ route('inbound.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
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
    button.btn.btn-icon.log-btn{
        display: none;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script>var wizard = new KTWizard('kt_wizard',{startStep: 1,clickableSteps: false});</script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
@endpush
