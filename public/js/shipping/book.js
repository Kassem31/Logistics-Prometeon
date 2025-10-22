$(function(){
    var startDate = moment($('#orderDate').datepicker('getDate')).format('D/MM/YYYY');

    var ets = $('#ets');
    var eta = $('#eta');
    var ats = $('#ats');
    var ata = $('#ata');

    // Initialize ETS and ETA with datepicker (estimated times can be future)
    ets.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn: true,
        orientation: 'bottom left'
    });
    eta.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn: true,
        orientation: 'bottom left'
    });

    // Initialize Flatpickr for ATS date field (Actual Time of Sailing)
    var atsDatePicker = flatpickr("#ats", {
        dateFormat: "d/m/Y",
        maxDate: new Date(), // Today is the maximum date allowed
        allowInput: true,
        clickOpens: true,
        locale: {
            firstDayOfWeek: 1 // Start week on Monday
        },
        onReady: function(selectedDates, dateStr, instance) {
            // Make the calendar icon clickable
            var calendarIcon = $(instance.element).closest('.input-group').find('.input-group-append');
            if (calendarIcon.length) {
                calendarIcon.on('click', function() {
                    instance.open();
                });
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
            // Validate that the selected date is not in the future
            if (selectedDates.length > 0 && selectedDates[0] > new Date()) {
                swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'ATS cannot be a future date. Actual sailing must have already occurred.',
                });
                instance.clear();
                return;
            }
            
            // Update ATA minimum date based on ATS selection
            if (selectedDates.length > 0) {
                var minDate = new Date(selectedDates[0]);
                minDate.setDate(minDate.getDate() + 1);
                ataDatePicker.set('minDate', minDate);
                
                // Check if current ATA value is before new minimum
                var currentATA = ataDatePicker.selectedDates[0];
                if (currentATA && currentATA <= selectedDates[0]) {
                    swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date',
                        text: 'ATA must be after ATS. Please select ATA again.'
                    });
                    ataDatePicker.clear();
                }
            } else {
                ataDatePicker.set('minDate', null);
            }
            
            updateAllCalculations();
        }
    });

    // Initialize Flatpickr for ATA date field (Actual Time of Arrival)
    var ataDatePicker = flatpickr("#ata", {
        dateFormat: "d/m/Y",
        maxDate: new Date(), // Today is the maximum date allowed
        allowInput: true,
        clickOpens: true,
        locale: {
            firstDayOfWeek: 1 // Start week on Monday
        },
        onReady: function(selectedDates, dateStr, instance) {
            // Make the calendar icon clickable
            var calendarIcon = $(instance.element).closest('.input-group').find('.input-group-append');
            if (calendarIcon.length) {
                calendarIcon.on('click', function() {
                    instance.open();
                });
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
            // Validate that the selected date is not in the future
            if (selectedDates.length > 0 && selectedDates[0] > new Date()) {
                swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'ATA cannot be a future date. Actual arrival must have already occurred.',
                });
                instance.clear();
                return;
            }
            
            // Check if ATA is after ATS
            var atsSelected = atsDatePicker.selectedDates[0];
            if (atsSelected && selectedDates.length > 0) {
                if (selectedDates[0] <= atsSelected) {
                    swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date',
                        text: 'ATA must be after ATS.'
                    });
                    instance.clear();
                    return;
                }
            }
            
            updateAllCalculations();
        }
    });
    
    var preEts = ets.datepicker('getDate');
    var preAts = null; // Will be handled by Flatpickr instances

    // Function to parse date from input value (handles both datepicker and Flatpickr inputs)
    function parseInputDate(inputElement) {
        var value = inputElement.val();
        if (!value || value.trim() === '') return null;
        
        // Check if it's a Flatpickr date input (d/m/Y format)
        if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(value)) {
            var dateMoment = moment(value, 'D/M/YYYY', true);
            if (dateMoment.isValid()) {
                return dateMoment.toDate();
            }
        }
        
        // Try to parse the date in dd/mm/yyyy format (for Bootstrap datepicker inputs)
        var dateMoment = moment(value, 'DD/MM/YYYY', true);
        if (dateMoment.isValid()) {
            return dateMoment.toDate();
        }
        
        // Fallback to datepicker getDate if moment parsing fails
        try {
            return inputElement.datepicker('getDate');
        } catch (e) {
            return null;
        }
    }

    // Function to update all calculations in real-time
    function updateAllCalculations() {
        console.log('Updating calculations...');
        
        var etsDate = parseInputDate(ets);
        var etaDate = parseInputDate(eta);
        var atsDate = parseInputDate(ats);
        var ataDate = parseInputDate(ata);

        console.log('Parsed dates:', {
            ets: etsDate,
            eta: etaDate,
            ats: atsDate,
            ata: ataDate
        });

        // Calculate E.T.T (Estimated Travel Time)
        if(etsDate && etaDate) {
            calcDays(etsDate, etaDate, 'ett');
        } else {
            clearDays('ett');
        }

        // Calculate A.T.T (Actual Travel Time)
        if(atsDate && ataDate) {
            calcDays(atsDate, ataDate, 'att');
        } else {
            clearDays('att');
        }

        // Calculate Sailing Deviation (difference between ATS and ETS)
        if(atsDate && etsDate) {
            calcDays(atsDate, etsDate, 'deviation');
        } else {
            clearDays('deviation');
        }

        // Calculate Arrival Deviation (difference between ATA and ETA)
        if(ataDate && etaDate) {
            calcDays(ataDate, etaDate, 'sailingDays');
        } else {
            clearDays('sailingDays');
        }
    }

    // Trigger calculations on any date field change (including manual input)
    ets.on('input change keyup blur', function() {
        setTimeout(updateAllCalculations, 200);
    });
    
    eta.on('input change keyup blur', function() {
        setTimeout(updateAllCalculations, 200);
    });
    
    ats.on('input change keyup blur', function() {
        setTimeout(updateAllCalculations, 200);
    });
    
    ata.on('input change keyup blur', function() {
        setTimeout(updateAllCalculations, 200);
    });

    // Initial calculation on page load
    setTimeout(updateAllCalculations, 500); // Delay to ensure page is fully loaded

    // Keep existing datepicker validation logic
    if(ets.datepicker('getDate')){
        var start = moment(ets.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
        eta.datepicker('setStartDate',start);
    }
    
    ets.datepicker().on('changeDate',(e)=>{
        if(e.date){
            setEta(e.date);
        }
        updateAllCalculations(); // Trigger real-time calculation
    });

    eta.datepicker().on('changeDate',(e)=>{
        updateAllCalculations(); // Trigger real-time calculation
    });

    eta.datepicker().on('clearDate',(e)=>{
        updateAllCalculations(); // Trigger real-time calculation
    });
    
    ets.datepicker().on('clearDate',(e)=>{
        updateAllCalculations(); // Trigger real-time calculation
    });
    function setEta(date){
        etsDate = moment(date).add(1,'days');
        etaDate = eta.datepicker('getDate');
        if(etaDate != null && moment(etaDate).isBefore(etsDate)){
            swal.fire('Invalid Date','ETA must be after ETS','warning');
            if(preEts != null){
                resetDate(ets,preEts);
            }else{
                ets.datepicker('clearDates');
            }
        }else{
            eta.datepicker('setStartDate',etsDate.format('D/MM/YYYY'));
            preEts = date;
        }
    }
    
    // setAta function is no longer needed as we handle ATA validation directly in the change event

    function resetDate(picker,old){
        picker.datepicker('setDate',moment(old).format('D/MM/YYYY'));
    }
    function clearDays(input){
        console.log('Clearing', input);
        $('#'+input).val('');
    }

    function calcDays(start,end,input){
       console.log('Calculating days for', input, 'from', start, 'to', end);
       if(start != null && end != null){
            var startMoment = moment(start);
            var endMoment = moment(end);
            
            if(startMoment.isValid() && endMoment.isValid()) {
                // calculate fractional days and round to integer
                const diff = endMoment.diff(startMoment, 'days', true);
                const result = Math.round(Math.abs(diff));
                console.log('Setting', input, 'to', result);
                $('#'+input).val(result);
            } else {
                console.log('Invalid dates for', input);
                $('#'+input).val('');
            }
       } else {
            console.log('Null dates for', input);
            $('#'+input).val('');
       }
    }
});
