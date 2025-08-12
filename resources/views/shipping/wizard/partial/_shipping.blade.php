<div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
    <div class="kt-form__section kt-form__section--first">
        <div class="kt-wizard-v2__form">
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Number of Containers:</label>
                <div class='col-lg-6'>
                    <input type='text' class='form-control' id="containersCount" value="{{ $inbound->containers()->count() }}" disabled>
                </div>
            </div>
            <h3 class="kt-section__title">Shipping Containers Details :</h3>
            <div class='form-group row'>
                <div class="col-md-12">
                    @include('shipping.wizard.partial._containers')
                </div>
            </div>

            <h3 class="kt-section__title">Shipping Details :</h3>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Origin Country:</label>
                <div class='col-lg-6'>
                    @if($poOriginCountries->count() > 1)
                        <div class="alert alert-info">
                            <strong>Multiple Origin Countries:</strong>
                            @foreach($poOriginCountries as $country)
                                <span class="badge badge-primary mr-1">{{ $country->name }}</span>
                            @endforeach
                        </div>
                        <!-- Use the first country as default for ports -->
                        <input type="hidden" name="shipping[origin_country_id]" value="{{ $poOriginCountries->first()->id }}">
                    @elseif($poOriginCountries->count() == 1)
                        <input type='text' class='form-control' value="{{ $poOriginCountries->first()->name }}" disabled>
                        <input type="hidden" name="shipping[origin_country_id]" value="{{ $poOriginCountries->first()->id }}">
                    @else
                        <input type='text' class='form-control' value="No origin country specified" disabled>
                    @endif
                    <small class="form-text text-muted">Origin country is automatically set from the Purchase Order</small>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Port of loading (POL):</label>
                <div class='col-lg-6'>
                    <select class='form-control kt-selectpicker @error('shipping.loading_port_id') is-invalid @enderror' {{ is_null(old('shipping.origin_country_id',$shipping->origin_country_id)) ? 'disabled':'' }}
                     id="pol" title="Select Port of loading ..." name='shipping[loading_port_id]'>
                    @foreach ($ports as $item)
                        <option value="{{ $item->id }}" {{ old('shipping.loading_port_id',$shipping->loading_port_id) == $item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                    @endforeach
                    </select>
                    @error('shipping.loading_port_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Inco Terms:</label>
                <div class='col-lg-6'>
                    @if($poIncoterm)
                        <input type='text' class='form-control' value="{{ $poIncoterm->prefix }} - {{ $poIncoterm->name }}" disabled>
                        <input type="hidden" name="shipping[inco_term_id]" value="{{ $poIncoterm->id }}">
                    @else
                        <input type='text' class='form-control' value="No incoterm specified in Purchase Order" disabled>
                    @endif
                    <small class="form-text text-muted">Incoterm is automatically set from the Purchase Order</small>
                </div>
            </div>
            <div class='form-group row forwarder {{$hideForworder ? "hidden" : null}}'>
                <label class='col-lg-3 col-form-label'>Forwarder Name:</label>
                <div class='col-lg-6'>
                    <select class='form-control kt-selectpicker @error('shipping.inco_forwarder_id') is-invalid @enderror' 
                    title="Select Forwarder Name ..." name='shipping[inco_forwarder_id]'>
                    @foreach ($incoForwarder as $item)
                        <option value="{{ $item->id }}" {{ old('shipping.inco_forwarder_id',$shipping->inco_forwarder_id) == $item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                    @endforeach
                    </select>
                    @error('shipping.inco_forwarder_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row rate {{$hideForworder ? "hidden" : null}}'>
                <label class='col-lg-3 col-form-label'>Rate:</label>
                <div class='col-lg-6'>
                    <input type='text' class='form-control @error('shipping.rate') is-invalid @enderror' placeholder='rate'
                    value="{{ old('shipping.rate',$shipping->rate) }}" name='shipping[rate]'>
                    @error('shipping.rate')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row currency {{$hideForworder ? "hidden" : null}}'>
                <label class='col-lg-3 col-form-label'>Currency:</label>
                <div class='col-lg-6'>
                    <select class='form-control kt-selectpicker @error('shipping.currency_id') is-invalid @enderror'
                    title="Select Currency ..." name='shipping[currency_id]'>
                        @foreach ($currency as $item)
                            <option value="{{ $item->id }}" {{ old('shipping.currency_id',$shipping->currency_id) == $item->id ? 'selected' :'' }}>{{ $item->currency }}</option>
                        @endforeach
                    </select>
                    @error('shipping.currency_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>            
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Shipping Line:</label>
                <div class='col-lg-6'>
                    <select class='form-control kt-selectpicker @error('shipping.shipping_line_id') is-invalid @enderror' id="shippingLine"
                    title="Select Shipping Line ..." data-live-search="true" name='shipping[shipping_line_id]'>
                    @foreach ($lines as $item)
                        <option value="{{ $item->id }}" {{ old('shipping.shipping_line_id',$shipping->shipping_line_id) == $item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                    @endforeach
                        <option value="0" {{ old('shipping.shipping_line_id',$shipping->shipping_line_id) == '0' ? 'selected' :'' }}>Other</option>
                    </select>
                    @error('shipping.shipping_line_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row {{ old('shipping.shipping_line_id',$shipping->shipping_line_id) == '0' ? '' :'hidden' }}' id="otherShippingLine">
                <label class='col-lg-3 col-form-label'>Other Shipping Line:</label>
                <div class='col-lg-6'>
                    <input type='text' class='form-control @error('shipping.other_shipping_line') is-invalid @enderror'
                    value="{{ old('shipping.other_shipping_line',$shipping->other_shipping_line) }}" placeholder='Other Shipping Line' name='shipping[other_shipping_line]'>
                    @error('shipping.other_shipping_line')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Vessel Name:</label>
                <div class='col-lg-6'>
                    <input type='text' class='form-control @error('shipping.vessel_name') is-invalid @enderror'
                    value="{{ old('shipping.vessel_name',$shipping->vessel_name) }}" placeholder='Vessel Name' name='shipping[vessel_name]'>
                    @error('shipping.vessel_name')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>B/L Number:</label>
                <div class='col-lg-6'>
                    <input type='text' class='form-control @error('shipping.bl_number') is-invalid @enderror'
                    value="{{ old('bl_number',$shipping->bl_number) }}" placeholder='B/L Number' name='shipping[bl_number]'>
                    @error('shipping.bl_number')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row insurance {{$hideInsurance ? "hidden" : null}}'>
                <label class='col-lg-3 col-form-label'>Insurance Company:</label>
                <div class='col-lg-6'>
                    <select class='form-control kt-selectpicker @error('shipping.insurance_company_id') is-invalid @enderror'
                    title="Select Insurance Company ..." name='shipping[insurance_company_id]'>
                        @foreach ($insuranceCompanies as $item)
                            <option value="{{ $item->id }}" {{ old('shipping.insurance_company_id',$shipping->insurance_company_id) == $item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('shipping.insurance_company_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row insurance {{$hideInsurance ? "hidden" : null}}'>
                <label class='col-lg-3 col-form-label'>Insurance Date :</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="shipping[insurance_date]" readonly
                        id="insurance_date" placeholder="Select Insurance Date" value="{{ old('shipping.insurance_date',$shipping->insurance_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('book.insurance_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class='form-group row insurance {{$hideInsurance ? "hidden" : null}}'>
                <label class='col-lg-3 col-form-label'>Insurance Certificate:</label>
                <div class='col-lg-6'>
                    <input type='text' class='form-control @error('shipping.insurance_cert_no') is-invalid @enderror'
                    value="{{ old('shipping.insurance_cert_no',$shipping->insurance_cert_no) }}" placeholder='Insurance Certificate' name='shipping[insurance_cert_no]'>
                    @error('shipping.insurance_cert_no')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/shipping/container.js') }}"></script>
@endpush
@push('styles')
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
    #containers.table thead th{
        vertical-align: middle;
    }
    #containers.table tbody tr:not(:last-child) .row_action .add-btn{
        display: none;
    }
    #containers.table tbody tr:only-child .row_action .remove-btn{
        display: none;
    }
    #containers tbody{
        counter-reset: rows;
    }
    #containers tbody tr:before{
    }
    #containers tbody td.row_number:before{
        counter-increment: rows;

        content: counter(rows);
    }
</style>
@endpush
