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

    if(ets.datepicker('getDate')){
        var start = moment(ets.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
        eta.datepicker('setStartDate',start);
    }
    ets.datepicker().on('changeDate',(e)=>{
        if(e.date){
            setEta(e.date);
        }
    });

    eta.datepicker().on('changeDate',(e)=>{
        calcDays(ets.datepicker('getDate'),eta.datepicker('getDate'),'ett');
        atsDate = ats.datepicker('getDate');
        etaDate = eta.datepicker('getDate');
        if(atsDate != null && eta != null){
            calcDays(atsDate,etaDate,'deviation');
        }

    });

    eta.datepicker().on('clearDate',(e)=>{
        clearDays('ett');
        clearDays('deviation');
    });
    ets.datepicker().on('clearDate',(e)=>{
        clearDays('ett');
    });
    ats.datepicker().on('changeDate',(e)=>{
        if(e.date){
            setAta(e.date);
        }
    });
    ata.datepicker().on('changeDate',(e)=>{
        if(e.date){
            calcDays(ats.datepicker('getDate'),e.date,'att');
        }
    });
    ata.datepicker().on('clearDate',(e)=>{
        clearDays('att');
    });
    ats.datepicker().on('clearDate',(e)=>{
        clearDays('att');
        clearDays('deviation');
        clearDays('sailingDays');
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
            calcDays(ets.datepicker('getDate'),etaDate,'ett');
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
            if(ataDate != null){
                calcDays(ats.datepicker('getDate'),ataDate,'att');
            }
            etaDate = eta.datepicker('getDate');
            if(etaDate != null){
                calcDays(ats.datepicker('getDate'),etaDate,'deviation');
            }
            calcDays(ats.datepicker('getDate'),moment(),'sailingDays');
            preAts = date;
        }
    }

    function resetDate(picker,old){
        picker.datepicker('setDate',moment(old).format('D/MM/YYYY'));
    }
    function clearDays(input){
        $('#'+input).val('');

    }

    function calcDays(start,end,input){
       if(start != null && end != null){
            start = moment(start);
            end = moment(end);
            // calculate fractional days and round to integer
            const diff = end.diff(start, 'days', true);
            $('#'+input).val(Math.round(diff));
       }
    }
});
