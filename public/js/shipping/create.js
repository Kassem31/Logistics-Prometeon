$(function(){
    var detailTable = $('#po-details');
    var counter = $('tbody tr',detailTable).length + 1;
    var allMaterials = []; // Store all available materials from PO
    var currentPO = null; // Store current PO ID
    var firstSelectedOrigin = null; // Store the origin of the first selected material
    
    $('#rawMaterial').on('change',function(){
        var self = $(this);
        if(!self.val()){
            // Reset when PO is deselected
            allMaterials = [];
            currentPO = null;
            firstSelectedOrigin = null;
            return false;
        }
        
        currentPO = self.val();
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
                // Store all materials for later use when adding rows
                allMaterials = data;
                firstSelectedOrigin = null; // Reset origin when PO changes
                for(var i=0 ;i < data.length;++i){
                    options.push(`<option value='${data[i].id}' data-rem='${data[i].remaining}' data-unit='${data[i].shipping_unit.name}' data-origin='${data[i].origin_country_id}' ># ${data[i].row_no} - ${data[i].raw_material.hs_code} -${data[i].raw_material.name}</option>`)
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
        
        // Check if we have a PO selected
        if(!currentPO) {
            swal.fire({
                title:"No PO Selected",
                text:'Please select a PO first',
                type: 'warning'
            });
            return false;
        }
        
        var row = parent.clone();
        counter += 1;
        cleanRow(row);
        
        // If we have a first selected origin, use the new API to get materials from that origin only
        if(firstSelectedOrigin) {
            $('#loadingInfo').html('Loading materials for selected origin...');
            
            $.get('/api/po/materials-by-origin',{
                'po': currentPO,
                'origin': firstSelectedOrigin
            }).done(function(data){
                // Get all currently selected material IDs to exclude them
                var selectedMaterials = [];
                $('tbody tr select.rawMaterial', detailTable).each(function(){
                    if($(this).val()) {
                        selectedMaterials.push($(this).val());
                    }
                });
                
                // Build options for the new row, excluding already selected materials
                var options = ['<option value="">Select Raw Material ...</option>'];
                for(var i = 0; i < data.length; i++){
                    var material = data[i];
                    // Only add if this material is not already selected
                    if(selectedMaterials.indexOf(material.id.toString()) === -1) {
                        options.push(`<option value='${material.id}' data-rem='${material.remaining}' data-unit='${material.shipping_unit.name}' data-origin='${material.origin_country_id}' ># ${material.row_no} - ${material.raw_material.hs_code} -${material.raw_material.name}</option>`);
                    }
                }
                
                // Update the select in the new row
                $('.row_material select', row).html(options.join(''));
                
                $('tbody',detailTable).append(row);
                
            }).fail(function(){
                swal.fire({
                    title:"Error",
                    text:'Failed to load materials for the selected origin',
                    type: 'error'
                });
            }).always(function(){
                $('#loadingInfo').html('');
            });
        } else {
            // No origin selected yet, use all materials
            // Get all currently selected material IDs to exclude them
            var selectedMaterials = [];
            $('tbody tr select.rawMaterial', detailTable).each(function(){
                if($(this).val()) {
                    selectedMaterials.push($(this).val());
                }
            });
            
            // Build options for the new row, excluding already selected materials
            var options = ['<option value="">Select Raw Material ...</option>'];
            for(var i = 0; i < allMaterials.length; i++){
                var material = allMaterials[i];
                // Only add if this material is not already selected
                if(selectedMaterials.indexOf(material.id.toString()) === -1) {
                    options.push(`<option value='${material.id}' data-rem='${material.remaining}' data-unit='${material.shipping_unit.name}' data-origin='${material.origin_country_id}' ># ${material.row_no} - ${material.raw_material.hs_code} -${material.raw_material.name}</option>`);
                }
            }
            
            // Update the select in the new row
            $('.row_material select', row).html(options.join(''));
            
            $('tbody',detailTable).append(row);
        }

    });

    detailTable.on('click','.remove-btn',function(e){
        $(this).closest('tr').remove();
        
        // Check if any materials are still selected
        var hasSelectedMaterials = false;
        $('tbody tr select.rawMaterial', detailTable).each(function(){
            if($(this).val()) {
                hasSelectedMaterials = true;
                return false; // break loop
            }
        });
        
        // Reset origin tracking if no materials are selected
        if(!hasSelectedMaterials) {
            firstSelectedOrigin = null;
            console.log('Reset origin tracking - no materials selected');
        }
        
        // Refresh all select options to make removed material available again
        refreshAllMaterialSelects();
    });

    detailTable.on('change','select.rawMaterial',function(){
        var option = $(this).children("option:selected");
        var row = $(this).closest('tr');
        $('.row_remaining input',row).val(option.data('rem'));
        $('.row_unit input',row).val(option.data('unit'));
        $('.row_qty input',row).val('');
        
        // Track the origin of the first selected material
        if(option.val() && firstSelectedOrigin === null) {
            firstSelectedOrigin = option.data('origin');
            console.log('First selected origin:', firstSelectedOrigin);
        }
        
        // Refresh all other material selects to hide/show options based on current selections
        refreshAllMaterialSelects();
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

    // Function to refresh all material select dropdowns
    function refreshAllMaterialSelects() {
        if(!currentPO) return;
        
        // If we have a selected origin, use the filtered API
        if(firstSelectedOrigin) {
            $.get('/api/po/materials-by-origin',{
                'po': currentPO,
                'origin': firstSelectedOrigin
            }).done(function(data){
                updateAllSelects(data);
            }).fail(function(){
                console.error('Failed to load materials by origin');
                // Fallback to all materials
                updateAllSelects(allMaterials);
            });
        } else {
            // No origin selected yet, use all materials
            updateAllSelects(allMaterials);
        }
    }
    
    // Helper function to update all select dropdowns with given materials
    function updateAllSelects(materials) {
        if(materials.length === 0) return;
        
        // Get all currently selected material IDs
        var selectedMaterials = [];
        $('tbody tr select.rawMaterial', detailTable).each(function(){
            if($(this).val()) {
                selectedMaterials.push($(this).val());
            }
        });
        
        // Update each select dropdown
        $('tbody tr select.rawMaterial', detailTable).each(function(){
            var currentSelect = $(this);
            var currentValue = currentSelect.val();
            
            // Build options excluding already selected materials (except current)
            var options = ['<option value="">Select Raw Material ...</option>'];
            for(var i = 0; i < materials.length; i++){
                var material = materials[i];
                var materialId = material.id.toString();
                
                // Include if: not selected by others OR is the current selection
                if(selectedMaterials.indexOf(materialId) === -1 || materialId === currentValue) {
                    options.push(`<option value='${material.id}' data-rem='${material.remaining}' data-unit='${material.shipping_unit.name}' data-origin='${material.origin_country_id}' ># ${material.row_no} - ${material.raw_material.hs_code} -${material.raw_material.name}</option>`);
                }
            }
            
            // Update the select and restore the current value
            currentSelect.html(options.join(''));
            currentSelect.val(currentValue);
        });
    }

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
