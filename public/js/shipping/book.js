$(function(){
    var startDate = moment($('#orderDate').datepicker('getDate')).format('D/MM/YYYY');

    var ets = $('#ets');
    var eta = $('#eta');
    var ats = $('#ats');
    var ata = $('#ata');

    ets.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'
    });
    eta.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'
    });

    ats.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'
    });
    ata.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'
    });
    
    var preEts = ets.datepicker('getDate');
    var preAts = ats.datepicker('getDate');

    // Function to parse date from input value (handles both datepicker and manual input)
    function parseInputDate(inputElement) {
        var value = inputElement.val();
        if (!value || value.trim() === '') return null;
        
        // Try to parse the date in dd/mm/yyyy format
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

        // Calculate Sailing Deviation (difference between ATS and ETA)
        if(atsDate && etaDate) {
            calcDays(atsDate, etaDate, 'deviation');
        } else {
            clearDays('deviation');
        }

        // Calculate Actual Sailing Days (from ATS to current date)
        if(atsDate) {
            calcDays(atsDate, moment().toDate(), 'sailingDays');
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
    
    ats.datepicker().on('changeDate',(e)=>{
        if(e.date){
            setAta(e.date);
        }
        updateAllCalculations(); // Trigger real-time calculation
    });
    
    ata.datepicker().on('changeDate',(e)=>{
        updateAllCalculations(); // Trigger real-time calculation
    });
    
    ata.datepicker().on('clearDate',(e)=>{
        updateAllCalculations(); // Trigger real-time calculation
    });
    
    ats.datepicker().on('clearDate',(e)=>{
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
    
    function setAta(date){
        atsDate = moment(date).add(1,'days');
        ataDate = ata.datepicker('getDate');
        if(ataDate != null && moment(ataDate).isBefore(atsDate)){
            swal.fire('Invalid Date','ATA must be after ATS','warning');
            if(preAts != null){
                resetDate(ats,preAts);
            }else{
                ats.datepicker('clearDates');
            }
        }else{
            ata.datepicker('setStartDate',atsDate.format('D/MM/YYYY'));
            preAts = date;
        }
    }

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
