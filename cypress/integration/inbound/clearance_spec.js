describe('booking data in shipping', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission ShippingBasic-list');
        cy.exec('php artisan user:add_permission ShippingBasic-create');
        cy.exec('php artisan user:add_permission ShippingBasic-edit');
        cy.visit('/');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test');
        cy.get(':nth-child(3) > .form-control').type('123456');
        cy.get('#kt_login_signin_submit').click();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('List Shipping').click({ force: true });
        cy.get(':nth-child(2) > :nth-child(10) > .btn').click()
        cy.url().should('contain','/shipping/1000/edit');
        cy.get('.kt-wizard-v2__nav-items > :nth-child(4)').click();
        cy.wait(2000)
        cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    before(function(){
      cy.exec('php artisan test:edit_broker');
      cy.exec('php artisan test:create_raw_material');
      cy.exec('php artisan test:create_supplier1');
      cy.exec('php artisan test:create_user_shipping');
      cy.exec('php artisan test:create_user_materialgroup');
      cy.exec('php artisan test:create_shipping')
      cy.exec('php artisan test:create_container_size');
      cy.exec("php artisan test:edit_container_load_type");
      cy.exec('php artisan test:edit_inco_forwarder');
      cy.exec('php artisan test:activate_inco_forwarder');
      cy.exec("php artisan test:edit_inco_term");
      cy.exec('php artisan test:create_shipping_basic');
      cy.exec('php artisan test:create_booking');
      cy.exec('php artisan test:create_document_cycle')

    })
    it('checks required',function(){
      cy.get('button[type="submit"]').click();
      cy.contains('The Custom System field is required.\n').should('be.visible')
      cy.contains('The Broker field is required.\n').should('be.visible')
      cy.get('#customSystem option[value="1"]').invoke('attr', 'selected',true);
      cy.get('#customSystem').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('button[type="submit"]').click();
      cy.contains('The Form for Broker Receipt field is required.\n').should('be.visible')
      cy.contains('The Broker Receipt field is required.\n').should('be.visible')
      cy.contains('The Amount field is required.\n').should('be.visible')
      cy.contains('The Invoice Number field is required.\n').should('be.visible')
      cy.contains('The Currency field is required.\n').should('be.visible')
      cy.get('#customSystem option[value="2"]').invoke('attr', 'selected',true);
      cy.get('#customSystem').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('button[type="submit"]').click();
      cy.contains('The L/G Request field is required.\n').should('be.visible')
      cy.contains('The L/G Issuance field is required.\n').should('be.visible')
      cy.contains('The L/G Amount field is required.\n').should('be.visible')
      cy.contains('The L/G Currency field is required.\n').should('be.visible')
      cy.contains('The L/G Sent to Bank field is required.\n').should('be.visible')
      cy.contains('The L/G Broker Receipt field is required.\n').should('be.visible')
    })
    it('checks data type',function(){
      cy.get('#customSystem option[value="1"]').invoke('attr', 'selected',true);
      cy.get('#customSystem').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('input[name="clear[invoice_no]"]').type('0kds');
      cy.get('input[name="clear[amount]"]').type('0kds');
      cy.get('button[type="submit"]').click();
      cy.contains('Enters Valid Invoice NUmber')
      cy.contains('Enters Valid Amount')
      cy.get('#customSystem option[value="2"]').invoke('attr', 'selected',true);
      cy.get('#customSystem').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('#lg_amount').type('0kds');
      cy.get('button[type="submit"]').click();
      cy.contains('Enters Valid L/G amount')
      cy.contains('Enters Valid Amount')
    })
    it('checks ATA',function(){
      cy.get('input[name="clear[ata]"]').should('be.visible');
      cy.exec('php artisan test:update_ata_date');
      cy.wait(2000)
      cy.visit('/shipping/1000/edit');
      cy.get('.kt-wizard-v2__nav-items > :nth-child(4)').click();
      cy.get('input[name="clear[ata]"]').should('not.exist');
    })
    it('checks nullable fields',function(){
      cy.get('select[name="clear[broker_id]"] option[value="1000"]').invoke('attr', 'selected',true);
      cy.get('select[name="clear[broker_id]"]').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('#customSystem option[value="1"]').invoke('attr', 'selected',true);
      cy.get('#customSystem').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('button[type="submit"]').click();

      cy.server();
      cy.request('get','/api/shipping/delivery/456123').then((response)=>{
        expect(response.body).to.equal('1')
      });
    })
    it('checks all fields',function(){

      cy.get('#clearAta').then((el)=>{
          el.prop('readonly',false).val('21/01/2020');
      });
      cy.get('#clearAta').trigger('changeDate',{force:true});
      cy.wait(2000);
      cy.get('select[name="clear[broker_id]"] option[value="1000"]').invoke('attr', 'selected',true);
      cy.get('select[name="clear[broker_id]"]').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('#customSystem option[value="1"]').invoke('attr', 'selected',true);
      cy.get('#customSystem').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('input[name="clear[invoice_no]"]').type('123123');
      cy.get('input[name="clear[amount]"]').type('123');

      cy.wait(2000);
      cy.get('#doDate').then((el)=>{
          el.prop('readonly',false).val('23/01/2020');
      });
      cy.get('#doDate').trigger('changeDate',{force:true});
      cy.wait(2000);
      cy.get('#registeration_date').then((el)=>{
          el.prop('readonly',false).val('25/01/2020');
      });
      cy.get('#registeration_date').trigger('changeDate',{force:true});
      cy.wait(2000);
      cy.get('#inspection_date').then((el)=>{
          el.prop('readonly',false).val('26/01/2020');
      });
      cy.get('#inspection_date').trigger('changeDate',{force:true});
      cy.wait(2000);
      cy.get('#withdraw_date').then((el)=>{
          el.prop('readonly',false).val('27/01/2020');
      });
      cy.get('#withdraw_date').trigger('changeDate',{force:true});
      cy.wait(2000);
      cy.get('#result_date').then((el)=>{
          el.prop('readonly',false).val('28/01/2020');
      });
      cy.get('#result_date').trigger('changeDate',{force:true});
      cy.wait(2000);

      cy.get('#form_date').then((el)=>{
          el.prop('readonly',false).val('30/01/2020');
      });
      cy.get('#form_date').trigger('changeDate',{force:true});
      cy.wait(2000);
      cy.get('#broker_receipt_date').then((el)=>{
          el.prop('readonly',false).val('31/01/2020');
      });
      cy.get('select[name="clear[form_currency_id]"]  option[value="1"]').invoke('attr', 'selected',true);
      cy.get('button[type="submit"]').click();
    })
    it('checks create permissions',function(){
      cy.exec('php artisan user:remove_permission ShippingBasic-create');
      cy.exec('php artisan user:remove_permission ShippingBasic-edit');
      cy.visit('/shipping');
      cy.get('.btn-secondary').should('not.exist');
      cy.visit('/shipping/1000/edit',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission ShippingBasic-list");
      cy.visit('/shipping',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
