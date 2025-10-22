<div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
    <div class="kt-form__section kt-form__section--first">
        <div class="kt-wizard-v2__form">
            <h3 class="kt-section__title">Estimated Time :</h3>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>(ETS) Estimated time Sailing :</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="book[ets]" readonly placeholder="Select ETS Date"
                        id="ets" value="{{ old('book.ets',$book->ets) }}" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('book.ets')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>(ETA) Estimated time of Arrival :</label>
                <div class='col-lg-6'>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="book[eta]" readonly
                        id="eta" placeholder="Select ETA Date" value="{{ old('book.ets',$book->eta) }}"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('book.eta')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn"
                    data-model='{{ $inbound->id }}' data-field='eta'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>(E.T.T) Estimated travel time period:</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly value="{{ round(abs($book->ett)) }}" id="ett"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Day(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="kt-section__title">Actual Time :</h3>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>(ATS) Actual time Sailing :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control ats-flatpickr" name="book[ats]" id="ats"
                        placeholder="Select ATS Date" value="{{ old('book.ats', $book->ats) }}" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('book.ats')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>(ATA) Actual Time of Arrival :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control ata-flatpickr" name="book[ata]" id="ata"
                        placeholder="Select ATA Date" value="{{ old('book.ata', $book->ata) }}" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        @error('book.ata')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-elevate btn-circle btn-icon log-btn" data-model='{{ $inbound->id }}' data-field='ata'>
                        <i class="flaticon-time-1"></i>
                    </button>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>(A.T.T) Actual travel time period :</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly value="{{ round(abs($book->att)) }}" id="att"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Day(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="kt-section__title">&nbsp;</h3>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Sailing Deviation:</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly value="{{ round(abs($book->deviation)) }}" id="deviation"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Day(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class='form-group row'>
                <label class='col-lg-4 col-form-label'>Arrival Deviation</label>
                <div class='col-lg-6'>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly value="{{ round(abs($book->sailing_days)) }}" id="sailingDays"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Day(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/shipping/book.js') }}"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush

@push('styles')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
/* Style Flatpickr to match the rest of the form */
.ats-flatpickr,
.ata-flatpickr {
    border: 1px solid #e2e5ec;
    border-radius: 4px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
    color: #5a6169;
    background-color: #fff;
    background-clip: padding-box;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    cursor: pointer;
}

.ats-flatpickr:focus,
.ata-flatpickr:focus {
    border-color: #5d78ff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(93, 120, 255, 0.25);
}

/* Ensure the calendar icon in the append area is clickable */
.input-group-append .input-group-text {
    cursor: pointer;
    border-left: 0;
}

/* Custom Flatpickr styling to match the theme */
.flatpickr-calendar {
    border-radius: 6px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border: 1px solid #e2e5ec;
}

.flatpickr-day.disabled,
.flatpickr-day.disabled:hover {
    background: #f5f5f5 !important;
    color: #ccc !important;
    cursor: not-allowed !important;
    text-decoration: line-through;
    position: relative;
}

.flatpickr-day.disabled::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 2px,
        rgba(204, 204, 204, 0.4) 2px,
        rgba(204, 204, 204, 0.4) 4px
    );
    pointer-events: none;
}

.flatpickr-day.today {
    border-color: #5d78ff;
    background: rgba(93, 120, 255, 0.1);
}

.flatpickr-day.selected {
    background: #5d78ff;
    border-color: #5d78ff;
}

/* Custom validation message styling */
.date-input-error {
    border-color: #fd397a !important;
    box-shadow: 0 0 0 0.2rem rgba(253, 57, 122, 0.25) !important;
}

/* Completely disable future dates in datepicker for other date fields */
.datepicker .day.disabled,
.datepicker .day.disabled:hover,
.datepicker .day.disabled:focus,
.datepicker .day.disabled:active {
    background: #f5f5f5 !important;
    color: #999 !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
    text-decoration: line-through !important;
    opacity: 0.3 !important;
}
</style>
@endpush


