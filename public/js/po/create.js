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

    var pic = $('#pic');
    var detailTable = $('#po-details');
    var counter = $('tbody tr',detailTable).length + 1;

    // Function to update person in charge dropdown based on selected materials
    function updatePersonInChargeDropdown() {
        var selectedMaterials = [];
        
        // Collect all selected material IDs from all rows
        $('select.rawMaterial', detailTable).each(function() {
            var materialId = $(this).val();
            if (materialId && selectedMaterials.indexOf(materialId) === -1) {
                selectedMaterials.push(materialId);
            }
        });

        if (selectedMaterials.length === 0) {
            // No materials selected, clear person dropdown
            pic.html('<option value="">Select Person In Charge ...</option>');
            pic.selectpicker('refresh');
            return;
        }

        // Get persons based on selected materials
        $.get('/purchase-orders/persons-by-materials', {
            'material_ids': selectedMaterials
        })
        .done(function(data) {
            var options = ['<option value="">Select Person In Charge ...</option>'];
            for(var i = 0; i < data.persons.length; i++) {
                var selected = pic.val() == data.persons[i].id ? 'selected' : '';
                options.push(`<option value='${data.persons[i].id}' ${selected}>${data.persons[i].name}</option>`);
            }
            pic.html(options.join(''));
            pic.selectpicker('refresh');
        })
        .fail(function() {
            console.error('Failed to fetch persons by materials');
        });
    }

    // Listen for material selection changes
    detailTable.on('change', 'select.rawMaterial', function() {
        updatePersonInChargeDropdown();
    });

    // Legacy support for when person is selected first (this should trigger material filtering)
    pic.on('changed.bs.select',function(e){
        var personId = e.target.value;
        if (!personId) return;
        
        $.get('/purchase-orders/materials-by-person', {'person_id': personId})
        .done(function(data) {
            var options = ['<option value="">Select Raw Material ...</option>'];
            for(var i = 0; i < data.materials.length; i++) {
                options.push(`<option value='${data.materials[i].id}'>${data.materials[i].sap_code} - ${data.materials[i].name}</option>`);
            }
            // Update all material dropdowns to show only compatible materials
            $('select.rawMaterial').each(function() {
                var currentValue = $(this).val();
                $(this).html(options.join(''));
                // Try to keep the current selection if it's still valid
                if (currentValue && data.materials.find(m => m.id == currentValue)) {
                    $(this).val(currentValue);
                }
            });
        });
    });

    $('.kt-select2').select2({
        width:'100%'
    });
    
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
        // Update person dropdown when a material row is removed
        updatePersonInChargeDropdown();
    });

    // Custom form validation to ensure person is selected when materials are present
    $('form').on('submit', function(e) {
        var hasMaterials = false;
        $('select.rawMaterial', detailTable).each(function() {
            if ($(this).val()) {
                hasMaterials = true;
                return false; // break
            }
        });

        if (hasMaterials && !pic.val()) {
            e.preventDefault();
            alert('Please select a Person In Charge when materials are selected.');
            return false;
        }
    });

    // Initialize person dropdown on page load if materials are already selected
    if ($('select.rawMaterial').length > 0) {
        updatePersonInChargeDropdown();
    }
});
