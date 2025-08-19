$(function(){
    var detailTable = $('#po-details');
    var counter = $('tbody tr',detailTable).length + 1;
    var allMaterials = []; // Store all available materials from PO
    var currentPO = null; // Store current PO ID
    var firstSelectedOrigin = null; // Store the origin of the first selected material
    
    // Initialize for edit page - check if there are existing materials and set origin
    function initializeExistingMaterials() {
        // First check existing material rows (those with data-existing-origin)
        var existingRows = $('tbody tr[data-existing-origin]', detailTable);
        if(existingRows.length > 0 && firstSelectedOrigin === null) {
            var firstExistingOrigin = existingRows.first().data('existing-origin');
            if(firstExistingOrigin) {
                firstSelectedOrigin = firstExistingOrigin;
                return;
            }
        }
        
        // Then check select dropdowns for materials
        var existingSelects = $('tbody tr select.rawMaterial', detailTable);
        if(existingSelects.length > 0) {
            existingSelects.each(function(){
                var select = $(this);
                if(select.val() && firstSelectedOrigin === null) {
                    var selectedOption = select.children("option:selected");
                    if(selectedOption.length > 0 && selectedOption.data('origin')) {
                        firstSelectedOrigin = selectedOption.data('origin');
                        return false; // break loop
                    }
                }
            });
        }
    }
    
    // Initialize on page load
    initializeExistingMaterials();
    
    // Initialize current PO if one is already selected (for edit mode)
    var selectedPO = $('#rawMaterial').val();
    if(selectedPO) {
        currentPO = selectedPO;
    }
    
    $('#rawMaterial').on('change',function(){
        var self = $(this);
        if(!self.val()){
            // Reset when PO is deselected
            allMaterials = [];
            currentPO = null;
            // Don't reset firstSelectedOrigin here if we're in edit mode with existing materials
            var hasExistingMaterials = $('tbody tr select.rawMaterial:not([value=""]):not([value])', detailTable).length > 0 || 
                                     $('tbody tr[data-existing-origin]', detailTable).length > 0;
            if(!hasExistingMaterials) {
                firstSelectedOrigin = null;
            }
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
                // Don't reset origin when PO changes if we have existing materials in edit mode
                var hasExistingMaterials = $('tbody tr select.rawMaterial:not([value=""]):not([value])', detailTable).length > 0 ||
                                         $('tbody tr[data-existing-origin]', detailTable).length > 0;
                if(!hasExistingMaterials) {
                    firstSelectedOrigin = null;
                }
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
            // Re-initialize after PO change in case we're in edit mode
            initializeExistingMaterials();

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
        
        // For new rows (not the first row), they should show filtered materials if origin is set
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
        var rowToRemove = $(this).closest('tr');
        var isFirstRow = rowToRemove.is('#first') || rowToRemove.index() === $('tbody tr', detailTable).length - 1;
        
        // If removing the first row and it has a selected material, we need to handle origin reset
        if(isFirstRow) {
            var selectedMaterial = $('select.rawMaterial', rowToRemove).val();
            if(selectedMaterial) {
                // Check if there are other selected materials to determine new origin
                var newOrigin = null;
                var hasOtherSelectedMaterials = false;
                
                // Check existing material rows first
                $('tbody tr[data-existing-origin]', detailTable).each(function(){
                    var existingOrigin = $(this).data('existing-origin');
                    if(existingOrigin) {
                        hasOtherSelectedMaterials = true;
                        if(newOrigin === null) {
                            newOrigin = existingOrigin;
                        }
                    }
                });
                
                // Then check other select dropdowns (excluding the one being removed)
                $('tbody tr select.rawMaterial', detailTable).not($('select.rawMaterial', rowToRemove)).each(function(){
                    var selectedValue = $(this).val();
                    if(selectedValue && selectedValue !== "") {
                        hasOtherSelectedMaterials = true;
                        if(newOrigin === null) {
                            var selectedOption = $(this).children("option:selected");
                            if(selectedOption.length > 0 && selectedOption.data('origin')) {
                                newOrigin = selectedOption.data('origin');
                            }
                        }
                    }
                });
                
                if(!hasOtherSelectedMaterials) {
                    firstSelectedOrigin = null;
                    console.log('First row removed with no other materials, resetting origin filter');
                } else if(newOrigin !== null) {
                    firstSelectedOrigin = newOrigin;
                    console.log('First row removed, setting origin to remaining material origin:', newOrigin);
                }
            }
        }
        
        // Remove the row
        rowToRemove.remove();
        
        // If we removed the first row and there are still rows, make the next row the "first" row
        if(isFirstRow && $('tbody tr', detailTable).length > 0) {
            $('tbody tr:last-child', detailTable).attr('id', 'first');
        }
        
        // Refresh all select options
        refreshAllMaterialSelects();
    });

    detailTable.on('change','select.rawMaterial',function(){
        var option = $(this).children("option:selected");
        var row = $(this).closest('tr');
        var isFirstRow = row.is('#first') || row.index() === $('tbody tr', detailTable).length - 1;
        
        $('.row_remaining input',row).val(option.data('rem'));
        $('.row_unit input',row).val(option.data('unit'));
        $('.row_qty input',row).val('');
        
        // Always update origin when selecting from the first row (not just when null)
        if(isFirstRow && option.val()) {
            var newOrigin = option.data('origin');
            if(firstSelectedOrigin !== newOrigin) {
                firstSelectedOrigin = newOrigin;
                console.log('First row material changed, updating origin to:', firstSelectedOrigin);
                // Refresh other rows to show only materials from this new origin
                refreshAllMaterialSelects();
            }
        }
        
        // If material is deselected from first row, check if we need to reset origin
        if(isFirstRow && (!option.val() || option.val() === "")) {
            // Check if any other materials are still selected
            var hasOtherSelectedMaterials = false;
            var newOrigin = null;
            
            // Check existing material rows first
            $('tbody tr[data-existing-origin]', detailTable).each(function(){
                var existingOrigin = $(this).data('existing-origin');
                if(existingOrigin) {
                    hasOtherSelectedMaterials = true;
                    if(newOrigin === null) {
                        newOrigin = existingOrigin;
                    }
                }
            });
            
            // Then check other select dropdowns
            $('tbody tr select.rawMaterial', detailTable).not(this).each(function(){
                var selectedValue = $(this).val();
                if(selectedValue && selectedValue !== "") {
                    hasOtherSelectedMaterials = true;
                    if(newOrigin === null) {
                        var selectedOption = $(this).children("option:selected");
                        if(selectedOption.length > 0 && selectedOption.data('origin')) {
                            newOrigin = selectedOption.data('origin');
                        }
                    }
                }
            });
            
            if(!hasOtherSelectedMaterials) {
                firstSelectedOrigin = null;
                console.log('First row deselected and no other materials, resetting origin filter');
                // Refresh all rows to show all materials
                refreshAllMaterialSelects();
            } else if(newOrigin !== null && firstSelectedOrigin !== newOrigin) {
                firstSelectedOrigin = newOrigin;
                console.log('First row deselected but maintaining origin from other materials:', newOrigin);
                refreshAllMaterialSelects();
            }
        }
        
        // If not the first row, just refresh to update available options
        if(!isFirstRow) {
            refreshAllMaterialSelects();
        }
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
        
        console.log('Refreshing selects with firstSelectedOrigin:', firstSelectedOrigin);
        
        // Update each select dropdown individually based on its position
        $('tbody tr select.rawMaterial', detailTable).each(function(){
            var currentSelect = $(this);
            var isFirstRow = currentSelect.closest('tr').is('#first') || 
                           currentSelect.closest('tr').index() === $('tbody tr', detailTable).length - 1;
            
            if(isFirstRow) {
                // First row always gets all materials
                console.log('Updating first row with all materials');
                updateSingleSelect(currentSelect, allMaterials);
            } else if(firstSelectedOrigin) {
                // Other rows get filtered materials if origin is set
                console.log('Updating other row with filtered materials');
                $.get('/api/po/materials-by-origin',{
                    'po': currentPO,
                    'origin': firstSelectedOrigin
                }).done(function(data){
                    updateSingleSelect(currentSelect, data);
                }).fail(function(){
                    console.error('Failed to load materials by origin for row');
                    updateSingleSelect(currentSelect, allMaterials);
                });
            } else {
                // No origin set yet, show all materials
                console.log('No origin set, updating row with all materials');
                updateSingleSelect(currentSelect, allMaterials);
            }
        });
    }
    
    // Helper function to update a single select dropdown with given materials
    function updateSingleSelect(selectElement, materials) {
        if(materials.length === 0) {
            console.log('No materials to update select with');
            return;
        }
        
        // Get all currently selected material IDs
        var selectedMaterials = [];
        $('tbody tr select.rawMaterial', detailTable).each(function(){
            if($(this).val()) {
                selectedMaterials.push($(this).val());
            }
        });
        
        var currentValue = selectElement.val();
        
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
        selectElement.html(options.join(''));
        selectElement.val(currentValue);
    }

    // Helper function to update all select dropdowns with given materials
    function updateAllSelects(materials) {
        if(materials.length === 0) {
            console.log('No materials to update selects with');
            return;
        }
        
        console.log('Updating all selects with', materials.length, 'materials');
        
        // Get all currently selected material IDs
        var selectedMaterials = [];
        $('tbody tr select.rawMaterial', detailTable).each(function(){
            if($(this).val()) {
                selectedMaterials.push($(this).val());
            }
        });
        
        console.log('Currently selected materials:', selectedMaterials);
        
        // Update each select dropdown
        $('tbody tr select.rawMaterial', detailTable).each(function(){
            updateSingleSelect($(this), materials);
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
