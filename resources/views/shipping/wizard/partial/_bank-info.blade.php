<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Bank Name:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ optional($clear->bank)->name }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Invoice No:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ $clear->invoice_no }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Invoice Date:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ $clear->invoice_date }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Amount:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ $clear->amount }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Currency:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ optional($clear->invoiceCurrency)->currency }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Bank Letter Date:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ $clear->bank_letter_date }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Delivery Bank Date:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ $clear->delivery_bank_date }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Bank In Date:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ $clear->bank_in_date }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Bank Out Date:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ $clear->bank_out_date }}" />
        </div>
    </div>
</div>
<div class='form-group row'>
    <label class='col-lg-4 col-form-label'>Bank Receive Date:</label>
    <div class='col-lg-6'>
        <div class="input-group">
            <input type="text" class="form-control" disabled value="{{ $clear->bank_rec_date }}" />
        </div>
    </div>
</div>
