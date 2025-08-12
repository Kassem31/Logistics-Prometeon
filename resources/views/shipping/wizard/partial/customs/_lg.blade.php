<div class="{{ Str::contains(strtolower($clear->customSystem->name),'temp') ? '' : 'hidden'}}" id="lg">
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>LG Number:</label>
        <div class='col-lg-6'>
            <input type='text' class='form-control'
            placeholder='LG Number' name='bank[lg_number]' value="{{ old('clear.lg_number',$clear->lg_number) }}">
            @error('bank.lg_number')
                <span class='form-text text-danger'>{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $inbound->id }}' data-field='lg_number'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>LG Request Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[lg_request_date]"
                 readonly placeholder="LG Request Date" value="{{ old('bank.lg_request_date',$clear->lg_request_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.lg_request_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $inbound->id }}' data-field='lg_request_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>LG Issue Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[lg_issuance_date]"
                 readonly placeholder="LG Issue Date" value="{{ old('bank.lg_issuance_date',$clear->lg_issuance_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.lg_issuance_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $inbound->id }}' data-field='lg_issuance_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>LG Amount:</label>
        <div class='col-lg-6'>
            <input type="text" class="form-control" name="bank[lg_amount]"
                 placeholder="LG Amount" value="{{ old('bank.lg_amount',$clear->lg_amount) }}" />
                @error('bank.lg_amount')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>LG Currency:</label>
        <div class='col-lg-6'>
            <select class='form-control kt-selectpicker'
            title="Select LG Currency ..." name='bank[lg_currency_id]'>
                @foreach ($currency as $item)
                    @if(old('bank.lg_currency_id',$clear->lg_currency_id) == null && $item->currency == "EGP")
                    <option value="{{ $item->id }}" selected>{{ $item->currency }}</option>
                    @else
                    <option value="{{ $item->id }}" {{ old('bank.lg_currency_id',$clear->lg_currency_id) == $item->id ? 'selected' :'' }}>{{ $item->currency }}</option>
                    @endif
                @endforeach
            </select>
            @error('bank.lg_currency_id')
                <span class='form-text text-danger'>{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>LG Receive Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[lg_broker_receipt_date]"
                 readonly placeholder="LG Receive Date" value="{{ old('bank.lg_broker_receipt_date',$clear->lg_broker_receipt_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.lg_broker_receipt_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $inbound->id }}' data-field='lg_broker_receipt_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
</div>
