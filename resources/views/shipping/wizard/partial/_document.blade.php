<div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
    <div class="kt-form__section kt-form__section--first">
        <div class="kt-wizard-v2__form">
            <h3 class="kt-section__title">Documents Cycle :</h3>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Supplier Invoice Copy :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="document[invoice_copy]" readonly placeholder="Supplier Invoice Copy"
                         id="invoice_copy" value="{{ old('document.invoice_copy',$document->invoice_copy) }}" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.invoice_copy')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='invoice_copy'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Purchasing Confirmation :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="document[purchase_confirmation]" readonly id="purchase_confirmation"
                        placeholder="Purchasing Confirmation" id="purchase_confirmation"
                         value="{{ old('document.purchase_confirmation',$document->purchase_confirmation) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.purchase_confirmation')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='purchase_confirmation'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>PTG Original Invoice :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="document[original_invoice]" readonly id="original_invoice"
                        placeholder="PTG Original Invoice" value="{{ old('document.original_invoice',$document->original_invoice) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.original_invoice')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='original_invoice'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>PTG Stamped Invoice :</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="document[stamped_invoice]" readonly id="stamped_invoice"
                         placeholder="PTG Stamped Invoice" value="{{ old('document.stamped_invoice',$document->stamped_invoice) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.stamped_invoice')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='stamped_invoice'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Copy Docs :</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="document[copy_docs]" readonly placeholder="Copy Docs" id="copy_docs"
                         value="{{ old('document.copy_docs',$document->copy_docs) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.copy_docs')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='copy_docs'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Original Docs :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="document[original_docs]" readonly id="original_docs"
                        placeholder="Original Docs" value="{{ old('document.original_docs',$document->original_docs) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.original_docs')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='original_docs'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Copy Docs to Broker :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="document[copy_docs_broker]" readonly id="copy_docs_broker"
                        placeholder="Copy Docs to Broker" value="{{ old('document.copy_docs_broker',$document->copy_docs_broker) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.copy_docs_broker')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='copy_docs_broker'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Original Docs to Broker :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="document[original_docs_broker]" readonly id="original_docs_broker"
                        placeholder="Original Docs to Broker" value="{{ old('document.original_docs_broker',$document->original_docs_broker) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.original_docs_broker')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='original_docs_broker'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Stamped Invoice to Broker :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="document[stamped_invoice_broker]" readonly id="stamped_invoice_broker"
                        placeholder="Stamped Invoice to Broker" value="{{ old('document.stamped_invoice_broker',$document->stamped_invoice_broker) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('document.stamped_invoice_broker')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='stamped_invoice_broker'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{ asset('js/shipping/document.js') }}" type="text/javascript"></script>
@endpush

