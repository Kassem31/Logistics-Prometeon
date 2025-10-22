$(function(){
    // Initialize ATA date filters with restriction to current and past dates only
    $('.ata-datepicker').datepicker({
        format: 'dd/mm/yyyy',
        clearBtn: true,
        orientation: 'bottom left',
        endDate: '0d' // Restrict to today and earlier dates (no future dates)
    });
    
    // Initialize ATS date filters without restrictions
    $('input[name="atsfrom"], input[name="atsto"]').datepicker({
        format: 'dd/mm/yyyy',
        clearBtn: true,
        orientation: 'bottom left'
    });
    
    // Initialize any other date inputs without restrictions (excluding ATA inputs)
    $('.date input').not('.ata-datepicker').not('input[name="atsfrom"]').not('input[name="atsto"]').datepicker({
        format: 'dd/mm/yyyy',
        clearBtn: true,
        orientation: 'bottom left'
    });
});
