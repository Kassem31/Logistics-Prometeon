<div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
    <div class="kt-form__section kt-form__section--first">
        <div class="kt-wizard-v2__form">
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Select Bank:</label>
                <div class='col-lg-6'>
                    <select class='form-control' name='clear[bank_id]'>
                        <option value="">Select Bank ...</option>
                        @foreach ($banks as $item)
                            <option value="{{ $item->id }}" {{ old('clear.bank_id',$clear->bank_id) == $item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Invoice Number:</label>
                <div class='col-lg-6'>
                    <input type="text" class="form-control" name="bank[invoice_no]"
                         placeholder="Invoice Number" value="{{ old('bank.invoice_no',$clear->invoice_no) }}" />
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Invoice Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="bank[invoice_date]" readonly
                         placeholder="Invoice Date" value="{{ old('bank.invoice_date',$clear->invoice_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('bank.invoice_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Amount:</label>
                <div class='col-lg-6'>
                    <input type="text" class="form-control" name="bank[amount]"
                         placeholder="Amount" value="{{ old('bank.amount',$clear->amount) }}" />
                        @error('bank.amount')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Currency:</label>
                <div class='col-lg-6'>
                    <select class='form-control kt-selectpicker'
                    title="Select Currency ..." name='bank[invoice_currency_id]'>
                        @foreach ($currency as $item)
                            <option value="{{ $item->id }}" {{ old('bank.invoice_currency_id',$clear->invoice_currency_id) == $item->id ? 'selected' :'' }}>{{ $item->currency }}</option>
                        @endforeach
                    </select>
                    @error('bank.invoice_currency_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Bank Letter Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="bank[bank_letter_date]" readonly
                         placeholder="Bank Letter Date" value="{{ old('bank.bank_letter_date',$clear->bank_letter_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('bank.bank_letter_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Delivery Bank Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="bank[delivery_bank_date]" readonly
                         placeholder="Delivery Bank Date" value="{{ old('bank.delivery_bank_date',$clear->delivery_bank_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('bank.delivery_bank_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <h3 class="kt-section__title">Custom System: <span id="customSystemSpan">{{ $clear->customSystem->name }}</span></h3>
            <!-- Start Form 4 Div -->
            @include('shipping.wizard.partial.customs._form4')
            <!-- End Form 4 Div -->
            <!-- Start Form 6 Div -->
            @include('shipping.wizard.partial.customs._form6')
            <!-- End Form 6 Div -->
            <!-- Start Transit Div -->
            @include('shipping.wizard.partial.customs._transit')
            <!-- End Transit Div -->
            <!-- Start Temp Div -->
            @include('shipping.wizard.partial.customs._lg')
            <!-- End Temp Div -->
            <h3 class="kt-section__title">&nbsp;</h3>

            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Bank In Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="bank[bank_in_date]" readonly
                         placeholder="Bank In Date" value="{{ old('bank.bank_in_date',$clear->bank_in_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('bank.bank_in_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Bank Out Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="bank[bank_out_date]" readonly
                         placeholder="Bank Out Date" value="{{ old('bank.bank_out_date',$clear->bank_out_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('bank.bank_out_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-3 col-form-label'>Bank Receive Date:</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control date" name="bank[bank_rec_date]" readonly
                         placeholder="Bank Receive Date" value="{{ old('bank.bank_rec_date',$clear->bank_rec_date) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('bank.bank_rec_date')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/shipping/bank.js') }}"></script>
@endpush
