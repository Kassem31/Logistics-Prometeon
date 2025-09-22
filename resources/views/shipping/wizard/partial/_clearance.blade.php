<div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
    <div class="kt-form__section kt-form__section--first">
        <div class="kt-wizard-v2__form">
            <h3 class="kt-section__title">Clearance Details :</h3>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Broker:*</label>
                <div class='col-lg-6'>
                    <select class='form-control @error('clear.broker_id') is-invalid @enderror' name='clear[broker_id]'>
                        <option value="">Select Broker ...</option>
                        @foreach ($brokers as $item)
                            <option value="{{ $item->id }}" {{ $item->id == old('clear.broker_id',$clear->broker_id) ? 'selected' : ''}}> {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('clear.broker_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='broker_id'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Custom System:*</label>
                <div class='col-lg-6'>
                    <select class='form-control @error('clear.custom_system_id') is-invalid @enderror' name='clear[custom_system_id]' id="customSystem">
                        <option value="">Select Custom System ...</option>
                        @foreach ($customs as $item)
                            <option value="{{ $item->id }}" {{ $item->id == old('clear.custom_system_id',$clear->custom_system_id) ? 'selected' : ''}}> {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('clear.custom_system_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='custom_system_id'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Delivery Order Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="clear[do_date]" readonly
                        placeholder="Delivery Order Date"
                        value="{{ old('clear.do_date',$clear->do_date) }}" id="doDate"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('clear.do_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='do_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>

            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Registration Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="clear[registeration_date]" readonly id="registeration_date"
                         placeholder="Registration Date" value="{{ old('clear.registeration_date',$clear->registeration_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('clear.registeration_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='registeration_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Inspection Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="clear[inspection_date]" readonly id="inspection_date"
                        value="{{ old('clear.inspection_date',$clear->inspection_date) }}" placeholder="Inspection Date" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('clear.inspection_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='inspection_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Sampling Withdraw Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="clear[withdraw_date]" readonly id="withdraw_date"
                         value="{{ old('clear.withdraw_date',$clear->withdraw_date) }}"
                         placeholder="Withdraw Date" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('clear.withdraw_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='withdraw_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Analysis Result Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="clear[result_date]" readonly id="result_date"
                         placeholder="Result Date"  value="{{ old('clear.result_date',$clear->result_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('clear.result_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='result_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Received Accounting Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="clear[received_accounting_date]" readonly
                         placeholder="Received Accounting Date" value="{{ old('clear.received_accounting_date',$clear->received_accounting_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('clear.received_accounting_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='received_accounting_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Invoicing Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control date" name="clear[invoicing_date]" readonly
                         placeholder="Invoicing Date" value="{{ old('clear.invoicing_date',$clear->invoicing_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('clear.invoicing_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='invoicing_date'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            {{-- @if(!is_null($clear->custom_system_id))
                <h3 class="kt-section__title">Bank Info :</h3>
                @include('shipping.wizard.partial._bank-info')
            @endif --}}


        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/shipping/clearance.js') }}"></script>
@endpush
