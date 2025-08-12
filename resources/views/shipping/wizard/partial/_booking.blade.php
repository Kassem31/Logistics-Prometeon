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
                    <div class="input-group date">
                        <input type="text" class="form-control" name="book[ats]" readonly id="ats"
                        placeholder="Select ATS Date" value="{{ old('book.ets',$book->ats) }}" />
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
                    <div class="input-group date">
                        <input type="text" class="form-control" name="book[ata]" readonly id="ata"
                        placeholder="Select ATA Date" value="{{ old('book.ata',$book->ata) }}"/>
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
                <label class='col-lg-4 col-form-label'>Actual Sailing Days:</label>
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
@endpush
