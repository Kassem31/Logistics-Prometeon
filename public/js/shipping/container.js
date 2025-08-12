$(function(){
    var containerTable = $('#containers');
    var counter = $('tbody tr',containerTable).length + 1;
    // $('#containersCount').val($('tbody tr',containerTable).length);
    containerTable.on('click','.add-btn',function(e){
        var row = $(this).closest('tr').clone();
        counter += 1;
        var el = $('select.container_size',row);
        var name = el.attr('name');
        el.val('');
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');
        el.attr('name',name.replace(/\d/g,counter));

        el = $('input.container_no',row);
        el.val('');
        name = el.attr('name');
        el.attr('name',name.replace(/\d/g,counter));
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');

        el = $('select.container_load',row);
        name = el.attr('name');
        el.attr('name',name.replace(/\d/g,counter));
        el.val('');
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');
        $('tbody',containerTable).append(row);
        $('#containersCount').val($('tbody tr',containerTable).length);
    });

    containerTable.on('click','.remove-btn',function(e){
        $(this).closest('tr').remove();
        $('#containersCount').val($('tbody tr',containerTable).length);
    });

    $('#insurance_date').datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'

    });
    var incoSelect = $("#incoSelect");
    var insuranceElements = $('.insurance');
    var forwarder = $('.forwarder');
    var rate = $('.rate');
    var currency = $('.currency');
    
    // Only bind event if incoSelect exists (for backwards compatibility)
    if(incoSelect.length > 0) {
        incoSelect.on('change',function(e){
            var self = $(this);
            var selectedOption = self.find(':selected');
            var text = selectedOption.text().toLowerCase();
            console.log(text,text.includes("cif"));
            if(text.includes("cif")){
                insuranceElements.addClass('hidden');
            }else{
                insuranceElements.removeClass('hidden');
            }
            if(text.includes("cif") || text.includes("cfr") || text.includes("ddu")){
                forwarder.addClass('hidden');
                rate.addClass('hidden');
                currency.addClass('hidden');
            }else{
                forwarder.removeClass('hidden');
                rate.removeClass('hidden');
                currency.removeClass('hidden');
            }
        });
    }
});
