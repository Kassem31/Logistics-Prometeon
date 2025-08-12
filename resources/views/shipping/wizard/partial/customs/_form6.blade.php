<div class="{{ Str::contains(strtolower($clear->customSystem->name),'final') ? '' : 'hidden'}}" id="form6">
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>Form 6 Issue Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[form6_issue_date]"
                 readonly placeholder="Form 6 Issue Date" value="{{ old('bank.form6_issue_date',$clear->form6_issue_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.form6_issue_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $inbound->id }}' data-field='form6_issue_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-4 col-form-label'>Form 6 receipt Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[form6_rec_date]"
                 readonly placeholder="Form 6 receipt Date" value="{{ old('bank.form6_rec_date',$clear->form6_rec_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.form6_rec_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $shipping->id }}' data-field='form6_rec_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
</div>
