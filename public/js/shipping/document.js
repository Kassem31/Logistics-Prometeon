$(function(){
    var startDate = moment($('#orderDate').datepicker('getDate')).format('D/MM/YYYY');
    var invoice_copy = $('#invoice_copy');
    var purchase_confirmation = $('#purchase_confirmation');
    var original_invoice = $('#original_invoice');
    var stamped_invoice = $('#stamped_invoice');
    var copy_docs = $('#copy_docs');
    var original_docs = $('#original_docs');
    var copy_docs_broker = $('#copy_docs_broker');
    var original_docs_broker = $('#original_docs_broker');
    var stamped_invoice_broker = $('#stamped_invoice_broker');

    invoice_copy.datepicker({
        format: 'dd/mm/yyyy',
        //startDate: startDate,
        clearBtn:true,
    });
    purchase_confirmation.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
    });

    original_invoice.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
    });

    stamped_invoice.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
    });

    copy_docs.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
    });

    original_docs.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
    });

    copy_docs_broker.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
    });

    original_docs_broker.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
    });

    stamped_invoice_broker.datepicker({
        format: 'dd/mm/yyyy',
        clearBtn:true,
    });

    // var oldInvoiceCopy = invoice_copy.datepicker('getDate');
    // if(oldInvoiceCopy){
    //     var start = moment(invoice_copy.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
    //     purchase_confirmation.datepicker('setStartDate',start);
    //     purchase_confirmation.prop('disabled',false);
    // }
    // var oldPurchaseConfirmation = purchase_confirmation.datepicker('getDate');
    // if(oldPurchaseConfirmation){
    //     var start = moment(purchase_confirmation.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
    //     original_invoice.datepicker('setStartDate',start);
    //     original_invoice.prop('disabled',false);
    // }
    // var oldOriginalInvoice = original_invoice.datepicker('getDate');
    // if(oldOriginalInvoice){
    //     var start = moment(original_invoice.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
    //     stamped_invoice.datepicker('setStartDate',start);
    //     stamped_invoice.prop('disabled',false);
    // }
    // var oldStampedInvoice = stamped_invoice.datepicker('getDate');
    // if(oldStampedInvoice){
    //     var start = moment(stamped_invoice.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
    //     copy_docs.datepicker('setStartDate',start);
    //     copy_docs.prop('disabled',false);
    // }
    // var oldCopyDocs = copy_docs.datepicker('getDate');
    // if(oldCopyDocs){
    //     var start = moment(copy_docs.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
    //     original_docs.datepicker('setStartDate',start);
    //     original_docs.prop('disabled',false);
    // }
    // var oldOriginalDocs = original_docs.datepicker('getDate');
    // if(oldOriginalDocs){
    //     var start = moment(original_docs.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
    //     copy_docs_broker.datepicker('setStartDate',start);
    //     copy_docs_broker.prop('disabled',false);
    // }
    // var oldDocsBroker = copy_docs_broker.datepicker('getDate');
    // if(oldDocsBroker){
    //     var start = moment(copy_docs_broker.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
    //     original_docs_broker.datepicker('setStartDate',start);
    //     original_docs_broker.prop('disabled',false);
    // }
    // var oldOriginalDocsBroker = original_docs_broker.datepicker('getDate');
    // if(oldOriginalDocsBroker){
    //     var start = moment(original_docs_broker.datepicker('getDate')).add(1,'days').format('D/MM/YYYY');
    //     stamped_invoice_broker.datepicker('setStartDate',start);
    //     stamped_invoice_broker.prop('disabled',false);
    // }

    // invoice_copy.datepicker().on('changeDate',(e)=>{
    //     if(e.date){
    //         setPurchaseConfirmation(invoice_copy,purchase_confirmation);
    //         purchase_confirmation.prop('disabled',false);
    //     }
    // });

    // purchase_confirmation.datepicker().on('changeDate',(e)=>{
    //     if(e.date){
    //         setOriginalInvoice(purchase_confirmation,original_invoice);
    //         original_invoice.prop('disabled',false);
    //     }
    // });

    // original_invoice.datepicker().on('changeDate',(e)=>{
    //     if(e.date){
    //         setStampedInvoice(original_invoice,stamped_invoice);
    //         stamped_invoice.prop('disabled',false);
    //     }
    // });

    // stamped_invoice.datepicker().on('changeDate',(e)=>{
    //     if(e.date){
    //         setCopyDocs(stamped_invoice,copy_docs);
    //         copy_docs.prop('disabled',false);
    //     }
    // });

    // copy_docs.datepicker().on('changeDate',(e)=>{
    //     if(e.date){
    //         setOriginalDocs(copy_docs,original_docs);
    //         original_docs.prop('disabled',false);
    //     }
    // });

    // original_docs.datepicker().on('changeDate',(e)=>{
    //     if(e.date){
    //         setCopyBrokerDocs(original_docs,copy_docs_broker);
    //         copy_docs_broker.prop('disabled',false);
    //     }
    // });

    // copy_docs_broker.datepicker().on('changeDate',(e)=>{
    //     if(e.date){
    //         setOriginalBrokerDocs(copy_docs_broker,original_docs_broker);
    //         original_docs_broker.prop('disabled',false);
    //     }
    // });

    // original_docs_broker.datepicker().on('changeDate',(e)=>{
    //     if(e.date){
    //         setStampedBrokerDocs(original_docs_broker,stamped_invoice_broker);
    //         stamped_invoice_broker.prop('disabled',false);
    //     }
    // });

    // function setPurchaseConfirmation(currentPicker,nextPicker){
    //     currentDate = moment(currentPicker.datepicker('getDate')).add(1,'days');
    //     nextDate = nextPicker.datepicker('getDate');
    //     if(nextDate != null && moment(nextDate).isBefore(currentDate)){
    //         swal.fire('Invalid Date','Supplier Invoice Copy date must be before Purchasing Confirmation date.','warning');
    //         resetDate(currentPicker,oldInvoiceCopy);
    //     }else{
    //         nextPicker.datepicker('setStartDate',currentDate.format('D/MM/YYYY'));
    //         oldInvoiceCopy = currentPicker.datepicker('getDate');
    //     }
    // }

    // function setOriginalInvoice(currentPicker,nextPicker){
    //     currentDate = moment(currentPicker.datepicker('getDate')).add(1,'days');
    //     nextDate = nextPicker.datepicker('getDate');
    //     if(nextDate != null && moment(nextDate).isBefore(currentDate)){
    //         swal.fire('Invalid Date','Purchasing Confirmation date must be before PTG Original Invoice date.','warning');
    //         resetDate(currentPicker,oldPurchaseConfirmation);
    //     }else{
    //         nextPicker.datepicker('setStartDate',currentDate.format('D/MM/YYYY'));
    //         oldPurchaseConfirmation = currentPicker.datepicker('getDate');
    //     }
    // }

    // function setStampedInvoice(currentPicker,nextPicker){
    //     currentDate = moment(currentPicker.datepicker('getDate')).add(1,'days');
    //     nextDate = nextPicker.datepicker('getDate');
    //     if(nextDate != null && moment(nextDate).isBefore(currentDate)){
    //         swal.fire('Invalid Date','PTG Original Invoice date must be before PTG Stamped Invoice date.','warning');
    //         resetDate(currentPicker,oldOriginalInvoice);
    //     }else{
    //         nextPicker.datepicker('setStartDate',currentDate.format('D/MM/YYYY'));
    //         oldOriginalInvoice = currentPicker.datepicker('getDate');
    //     }
    // }

    // function setCopyDocs(currentPicker,nextPicker){
    //     currentDate = moment(currentPicker.datepicker('getDate')).add(1,'days');
    //     nextDate = nextPicker.datepicker('getDate');
    //     if(nextDate != null && moment(nextDate).isBefore(currentDate)){
    //         swal.fire('Invalid Date','PTG Stamped Invoice date must be before Copy Docs date.','warning');
    //         resetDate(currentPicker,oldStampedInvoice);
    //     }else{
    //         nextPicker.datepicker('setStartDate',currentDate.format('D/MM/YYYY'));
    //         oldStampedInvoice = currentPicker.datepicker('getDate');
    //     }
    // }

    // function setOriginalDocs(currentPicker,nextPicker){
    //     currentDate = moment(currentPicker.datepicker('getDate')).add(1,'days');
    //     nextDate = nextPicker.datepicker('getDate');
    //     if(nextDate != null && moment(nextDate).isBefore(currentDate)){
    //         swal.fire('Invalid Date','Copy Docs date must be before Original Docs date.','warning');
    //         resetDate(currentPicker,oldCopyDocs);
    //     }else{
    //         nextPicker.datepicker('setStartDate',currentDate.format('D/MM/YYYY'));
    //         oldCopyDocs = currentPicker.datepicker('getDate');
    //     }
    // }

    // function setCopyBrokerDocs(currentPicker,nextPicker){
    //     currentDate = moment(currentPicker.datepicker('getDate')).add(1,'days');
    //     nextDate = nextPicker.datepicker('getDate');
    //     if(nextDate != null && moment(nextDate).isBefore(currentDate)){
    //         swal.fire('Invalid Date','Original Docs date must be before Copy Docs to Broker date.','warning');
    //         resetDate(currentPicker,oldOriginalDocs);
    //     }else{
    //         nextPicker.datepicker('setStartDate',currentDate.format('D/MM/YYYY'));
    //         oldOriginalDocs = currentPicker.datepicker('getDate');
    //     }
    // }

    // function setOriginalBrokerDocs(currentPicker,nextPicker){
    //     currentDate = moment(currentPicker.datepicker('getDate')).add(1,'days');
    //     nextDate = nextPicker.datepicker('getDate');
    //     if(nextDate != null && moment(nextDate).isBefore(currentDate)){
    //         swal.fire('Invalid Date','Copy Docs to Broker date must be before Original Docs to Broker date.','warning');
    //         resetDate(currentPicker,oldDocsBroker);
    //     }else{
    //         nextPicker.datepicker('setStartDate',currentDate.format('D/MM/YYYY'));
    //         oldDocsBroker = currentPicker.datepicker('getDate');
    //     }
    // }

    // function setStampedBrokerDocs(currentPicker,nextPicker){
    //     currentDate = moment(currentPicker.datepicker('getDate')).add(1,'days');
    //     nextDate = nextPicker.datepicker('getDate');
    //     if(nextDate != null && moment(nextDate).isBefore(currentDate)){
    //         swal.fire('Invalid Date','Original Docs to Broker date must be before Stamped Invoice to Broker date.','warning');
    //         resetDate(currentPicker,oldOriginalDocsBroker);
    //     }else{
    //         nextPicker.datepicker('setStartDate',currentDate.format('D/MM/YYYY'));
    //         oldOriginalDocsBroker = currentPicker.datepicker('getDate');
    //     }
    // }
    // function resetDate(picker,old){
    //     picker.datepicker('setDate',moment(old).format('D/MM/YYYY'));
    // }

});
