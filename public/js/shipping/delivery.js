$(function(){
    var startDate = moment($('#doDate').datepicker('getDate')).format('D/MM/YYYY');
    var atco_date = $('#atco_date');
    var sap_date = $('#sap_date');
    var bwh_date = $('#bwh_date');


    atco_date.datepicker({
        format: 'dd/mm/yyyy',
        startDate: startDate,
        clearBtn:true,
    });
    sap_date.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        startDate: startDate,
    });

    bwh_date.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        startDate: startDate,
    });
});
