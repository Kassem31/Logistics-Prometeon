$(function(){
    var orderDate = $('#orderDate');
    var dueDate = $('#dueDate');
    var itemDueDates = $('.item-due-date');
    var amendmentDates = $('.amendment-date');
    
    orderDate.datepicker({
        format: 'dd/mm/yyyy',
        datesDisabled:'+1d',
        endDate: '+1d',
        clearBtn:true,
        orientation:'bottom left'
    });
    
    dueDate.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'
    });
    
    // Initialize date pickers for existing item due dates and amendment dates
    itemDueDates.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'
    });
    
    amendmentDates.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
        orientation:'bottom left'
    });
    // var rawMaterial = $('select.rawMaterial');
    var pic = $('#pic');
    pic.on('changed.bs.select',function(e){
        $.get('/api/pic/materials/',{'user':e.target.value})
        .done(function(data){
            var options = [];
            options.push(`<option value=''>Select Raw Material ...</option>`)
            for(var i=0 ;i < data.length;++i){
                options.push(`<option value='${data[i].id}'>${data[i].sap_code} - ${data[i].name}</option>`)
            }
            $('select.rawMaterial').html(options.join(''));
            //rawMaterial.selectpicker('refresh');
        });
    });
    $('.kt-select2').select2({
        width:'100%'
    });
    var detailTable = $('#po-details');
    var counter = $('tbody tr',detailTable).length + 1;
    detailTable.on('click','.add-btn',function(e){
        var row = $(this).closest('tr').clone();
        counter += 1;
        var el = $('select.rawMaterial',row);
        var name = el.attr('name');
        el.val('');
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');
        el.attr('name',name.replace(/\d/g,counter));

        el = $('input.qty',row);
        el.val('');
        name = el.attr('name');
        el.attr('name',name.replace(/\d/g,counter));
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');

        el = $('select.shipping_unit_id',row);
        name = el.attr('name');
        el.attr('name',name.replace(/\d/g,counter));
        el.val('');
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');
        
        // Handle origin country dropdown
        el = $('select.origin_country_id',row);
        name = el.attr('name');
        el.attr('name',name.replace(/\d/g,counter));
        el.val('');
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');
        
        // Handle item due date
        el = $('input.item-due-date',row);
        name = el.attr('name');
        el.attr('name',name.replace(/\d/g,counter));
        el.val('');
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');
        // Reinitialize datepicker for the new field
        el.datepicker({
            format: 'dd/mm/yyyy',
            clearBtn:true,
            orientation:'bottom left'
        });
        
        // Handle amendment date
        el = $('input.amendment-date',row);
        name = el.attr('name');
        el.attr('name',name.replace(/\d/g,counter));
        el.val('');
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');
        // Reinitialize datepicker for the new field
        el.datepicker({
            format: 'dd/mm/yyyy',
            clearBtn:true,
            orientation:'bottom left'
        });
        
        $('tbody',detailTable).append(row);

    });

    detailTable.on('click','.remove-btn',function(e){
        $(this).closest('tr').remove();
    });
});
