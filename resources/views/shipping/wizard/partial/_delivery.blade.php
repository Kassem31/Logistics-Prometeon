<div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
    <div class="kt-form__section kt-form__section--first">
            @if($inbound->isComplete())
            <div class="alert alert-success" role="alert">
                <div class="alert-icon"></div>
                <div class="alert-text text-center">
                    <h1><i class="flaticon2-check-mark"></i> Shipping Process Complete</h1>
                </div>
            </div>
            @endif
            <h1 class="kt-font-success text-center"></h1>
        <div class="kt-wizard-v2__form">
            <h3 class="kt-section__title">Delivery Details :</h3>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>WH ATCO Date :</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="deliver[atco_date]" readonly id="atco_date"
                        value="{{ old('deliver.atco_date',$deliver->atco_date) }}" placeholder="WH ATCO Date" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('deliver.atco_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='atco_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>WH SAP Date :</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="deliver[sap_date]" readonly id="sap_date"
                        value="{{ old('deliver.sap_date',$deliver->sap_date) }}" placeholder="WH SAP Date" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('deliver.sap_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='sap_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>BWH Date :</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="deliver[bwh_date]" readonly id="bwh_date"
                        value="{{ old('deliver.bwh_date',$deliver->bwh_date) }}" placeholder="BWH Date" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('deliver.bwh_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='bwh_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/shipping/delivery.js') }}"></script>
@endpush
