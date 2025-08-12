<div class="{{ Str::contains(strtolower($clear->customSystem->name),'db') ? '' : 'hidden'}}" id="form4">
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>Form 4 Issue Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[form4_issue_date]"
                 readonly placeholder="Form 4 Issue Date" value="{{ old('bank.form4_issue_date',$clear->form4_issue_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.form4_issue_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $inbound->id }}' data-field='form4_issue_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>From 4 receipt Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[form4_rec_date]"
                 readonly placeholder="Form 4 receipt Date" value="{{ old('bank.form4_rec_date',$clear->form4_rec_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.form4_rec_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $shipping->id }}' data-field='form4_rec_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>Form 4 Number:</label>
        <div class='col-lg-6'>
            <input type='text' class='form-control'
            placeholder='Form 4 Number' name='bank[form4_number]' value="{{ old('clear.form4_number',$clear->form4_number) }}">
            @error('bank.form4_number')
                <span class='form-text text-danger'>{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $inbound->id }}' data-field='form4_number'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
</div>
