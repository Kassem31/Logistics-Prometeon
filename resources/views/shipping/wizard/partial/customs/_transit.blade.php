<div class="{{ Str::contains(strtolower($clear->customSystem->name),'transit') ? '' : 'hidden'}}" id="transit">
    <div class='form-group row'>
        <label class='col-lg-3 col-form-label'>Tranist Issue Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[transit_issue_date]"
                 readonly placeholder="Tranist Issue Date" value="{{ old('bank.transit_issue_date',$clear->transit_issue_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.transit_issue_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $inbound->id }}' data-field='transit_issue_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-3 col-form-label'>Transit Receipt Date:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control date" name="bank[transit_rec_date]"
                 readonly placeholder="Transit Receipt Date" value="{{ old('bank.transit_rec_date',$clear->transit_rec_date) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.transit_rec_date')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $shipping->id }}' data-field='transit_rec_date'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
    <div class='form-group row'>
        <label class='col-lg-3 col-form-label'>Storage Letter:</label>
        <div class='col-lg-6'>
            <div class="input-group">
                <input type="text" class="form-control" name="bank[transit_storage_letter]"
                placeholder="Storage Letter" value="{{ old('bank.transit_storage_letter',$clear->transit_storage_letter) }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
                @error('bank.transit_storage_letter')
                    <span class='form-text text-danger'>{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
            data-model='{{ $shipping->id }}' data-field='transit_storage_letter'>
                <i class="flaticon-time-1"></i>
            </button>
        </div>
    </div>
</div>
