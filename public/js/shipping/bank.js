$(function(){
    var customSystem = $('#customSystem');
    $('.date').datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'

    });
    var lg = $('#lg');
    var form4 = $('#form4');
    var form6 = $('#form6');
    var transit = $('#transit');
    customSystem.on('change',function(e){
        var option = $(this).children("option:selected").html();
        $('#customSystemSpan').html(option);
        if(option.toLowerCase().includes('db')){
            form4.removeClass('hidden');
            lg.addClass('hidden');
            form6.addClass('hidden');
            transit.addClass('hidden');
        }else if(option.toLowerCase().includes('final')){
            form6.removeClass('hidden');
            lg.addClass('hidden');
            form4.addClass('hidden');
            transit.addClass('hidden');
        }else if(option.toLowerCase().includes('transit')){
            transit.removeClass('hidden');
            lg.addClass('hidden');
            form4.addClass('hidden');
            form6.addClass('hidden');
        }
        else{
            lg.removeClass('hidden');
            transit.addClass('hidden');
            form4.addClass('hidden');
            form6.addClass('hidden');
        }
    });

});
