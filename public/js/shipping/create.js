$(function(){
    var detailTable = $('#po-details');
    var counter = $('tbody tr',detailTable).length + 1;
    $('#rawMaterial').on('change',function(){
        var self = $(this);
        if(!self.val()){return false;}
        var options = [];
        options.push('<option value="">Select Raw Material ...</option>')
        $('#loadingInfo').html('Loading PO Details ...');
        $.get('/api/po/materials',{
            'po':self.val()
        }).done(function(data){
            if(data.length <= 0){
                swal.fire({
                    title:"PO has no Materials",
                    text:'',
                    type: 'warning'
                });
                return false;
            }else{
                for(var i=0 ;i < data.length;++i){
                    options.push(`<option value='${data[i].id}' data-rem='${data[i].remaining}' data-unit='${data[i].shipping_unit.name}' ># ${data[i].row_no} - ${data[i].raw_material.hs_code} -${data[i].raw_material.name}</option>`)
                }
            }
        }).always(function(){
            $('#loadingInfo').html('');
            var row = $('tbody tr:last-child',detailTable);
            counter = 1;
            cleanRow(row);
            $('.row_material select').html(options.join(''));
            $('tbody tr',detailTable).replaceAll(row);

        });
    });
    detailTable.on('click','.add-btn',function(e){
        var self = $(this);
        var parent = self.closest('tr');
        var selectMaterial = $('select.rawMaterial',parent);
        if(!selectMaterial.val()){
            swal.fire({
                title:"No Material is selected",
                text:'',
                type: 'warning'
            });
            return false;
        }
        var row = parent.clone();
        $(`option[value=${selectMaterial.val()}]`,row).remove();
        counter += 1;
        cleanRow(row);
        $('tbody',detailTable).append(row);

    });

    detailTable.on('click','.remove-btn',function(e){
        $(this).closest('tr').remove();
    });

    detailTable.on('change','select.rawMaterial',function(){
        var option = $(this).children("option:selected");
        var row = $(this).closest('tr');
        $('.row_remaining input',row).val(option.data('rem'));
        $('.row_unit input',row).val(option.data('unit'));
        $('.row_qty input',row).val('');
    });

    detailTable.on('blur','input.qty',function(){
        var self = $(this);
        var row = self.closest('tr');

        if(!$('.row_material select',row).val() && self.val()){
            swal.fire({
                title:"No Material is selected",
                text:'',
                type: 'warning'
            }).then(()=>{
                self.val('');
                $('.row_material select',row).focus();
            });
            return false;
        }
        var select = $('.row_material select',row);
        var selectOption = select.children("option:selected");
        var remainingQty = Number(selectOption.data('rem'));
        var remaining = $('.row_remaining input',row);

        var qty = Number(self.val());
        if(isNaN(qty)){ //|| qty <=0
            swal.fire({
                title:"Invalid Qty",
                text:'Qty Must be an Number greater than zero',
                type: 'warning'
            }).then(()=>{
                self.focus();
                self.select();

            });
            return false;
         }

        if(qty > remainingQty){
            swal.fire({
                title:"Invalid Qty",
                text:'Qty Must be an less than or equal to remaining value',
                type: 'warning'
            }).then(()=>{
                self.focus();
                self.select();
            });
            return false;
        }
        var newRemaining = (remainingQty - qty) || ''
        remaining.val(newRemaining);
    });

    var originCountry = $('#originCountry');
    var pol = $('#pol');
    originCountry.on('changed.bs.select',function(e){
        pol.prop('disabled',true);
        $.get('/api/country/ports',{'country':e.target.value})
        .done(function(data){
            var options = [];
            for(var i=0 ;i < data.length;++i){
                options.push(`<option value='${data[i].id}'>${data[i].name}</option>`)
            }
            pol.html(options.join(''));
            pol.prop('disabled',false);
            pol.selectpicker('refresh');

        });
    });

    var orderDate = $('#orderDate');
    var dueDate = $('#dueDate');
    orderDate.datepicker({
        format: 'dd/mm/yyyy',
        datesDisabled:'+1d',
        endDate: '+1d',
        clearBtn:true,
    });
    dueDate.datepicker({
        format: 'dd/mm/yyyy',
        datesDisabled:'-1d',
        startDate: '+1d',
        clearBtn:true,
    });

    var shippingLine = $('#shippingLine');
    var otherShippingLine = $('#otherShippingLine');
    shippingLine.on('changed.bs.select',function(e){
        if(e.target.value == '0'){
            otherShippingLine.removeClass('hidden');
        }else{
            otherShippingLine.addClass('hidden');
        }
    });

    function cleanRow(row){
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

        el = $('input.remaining',row);
        el.val('');
        name = el.attr('name');
        el.attr('name',name.replace(/\d/g,counter));
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');

        el = $('input.unit',row);
        el.val('');
        el.removeClass('is-invalid');
        el.closest('div').find('span.text-danger').html('');
    }
    // var rawMaterial = $('#rawMaterial');
    // var pic = $('#pic');
    // rawMaterial.on('changed.bs.select',function(e){
    //     $.get('/api/group/users',{'material':e.target.value})
    //     .done(function(data){
    //         var options = [];
    //         for(var i=0 ;i < data.length;++i){
    //             options.push(`<option value='${data[i].id}'>${data[i].full_name}</option>`)
    //         }
    //         pic.html(options.join(''));
    //         pic.selectpicker('refresh');
    //     });
    // });
});
