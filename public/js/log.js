 var modal = $('#log_modal');
var modalTitle = $('#logModalTitle');
var logContent = $('#log_content');

$('.log-btn').on('click',function(e){
    var self = $(this);
    var field = self.data('field');
    var parent = self.closest('.form-group');
    modalTitle.html("Log of " + $('label',parent).html());
    $.post('/api/log',{
        'shipping':self.data('model'),
        'field':field
    }).done(function(response){
        logContent.html(response);
    });
    $('#log_modal').modal('show');
});
