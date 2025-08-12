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
    <label class='col-lg-4 col-form-label'>Bank In Date:</label>
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
    <label class='col-lg-4 col-form-label'>Bank Out Date:</label>
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
    <label class='col-lg-4 col-form-label'>Bank Receive Date:</label>
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