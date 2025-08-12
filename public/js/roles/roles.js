$(function(){
    var selectAllBtn = $('#selectAll');
    selectAllBtn.on('click',function(){
        var self = $(this);
        if(self.data('selected') == '0'){
            $(".kt-checkbox input[type=checkbox]").prop('checked',true);
            self.data('selected','1');
        }else{
            $(".kt-checkbox input[type=checkbox]").prop('checked',false);
            self.data('selected','0');
        }
    });
    var form = $('#createForm');

    form.on('submit',function(e){
        var items = $(".kt-checkbox input[type=checkbox]:checked");
        if(items.length <= 0){
            swal.fire({
                title:"No Permission Seleted",
                text:'Role must have permission',
                type: 'error',
            });
            return false;
        }
        return true;
    });

    var checkbox = $(".kt-checkbox input[type=checkbox]");
    checkbox.on('click',function(){
        var item = $(this);
        if(!item.data('name').includes('list') && item.prop('checked')){
            $('.kt-checkbox input[data-name*=list]',item.closest('.kt-checkbox-list')).prop('checked',true);

        }
    });
});

