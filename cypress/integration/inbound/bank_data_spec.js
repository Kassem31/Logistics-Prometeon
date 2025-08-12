describe('create inbound test', function () {
    before(function(){
        cy.exec('php artisan user:add_permission ShippingBasic-list');
        cy.exec('php artisan user:add_permission ShippingBasic-create');
        cy.exec('php artisan user:add_permission ShippingBasic-edit');
    })
    beforeEach(function () {
      cy.visit('/');
      cy.login();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      // cy.get('#kt_header_mobile_toggler').click();
      // cy.get('.kt-menu__subnav > .kt-menu__item > .kt-menu__link > .kt-menu__link-text').click({force:true});
      //cy.url().should('contain','/inbound');
      cy.visit('/inbound');
      cy.get(':nth-child(2) > :nth-child(10) > .btn-danger').click();
      cy.url().should('contain', '/inbound/1/edit');
      cy.get(':nth-child(5) > .kt-wizard-v2__nav-body').click();
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check present fields fields', function () {
        cy.get(':nth-child(4) > .kt-wizard-v2__nav-body').click();
        cy.get('#customSystem option[value="1"]').invoke('attr', 'selected',true);
        cy.get(':nth-child(2) > .btn-success').click();
        cy.get(':nth-child(5) > .kt-wizard-v2__nav-body').click();
        cy.get('input[name="bank[form4_issue_date]"]').should('be.visible')
        cy.get('input[name="bank[form4_rec_date]"]').should('be.visible')
        cy.get('input[name="bank[form4_number]"]').should('be.visible')

        cy.get(':nth-child(4) > .kt-wizard-v2__nav-body').click();
        cy.get('#customSystem option[value="2"]').invoke('attr', 'selected',true);
        cy.get(':nth-child(2) > .btn-success').click();
        cy.get(':nth-child(5) > .kt-wizard-v2__nav-body').click();
        cy.get('input[name="bank[form6_issue_date]"]').should('be.visible')
        cy.get('input[name="bank[form6_rec_date]"]').should('be.visible')

        cy.get(':nth-child(4) > .kt-wizard-v2__nav-body').click();
        cy.get('#customSystem option[value="3"]').invoke('attr', 'selected',true);
        cy.get(':nth-child(2) > .btn-success').click();
        cy.get(':nth-child(5) > .kt-wizard-v2__nav-body').click();
        cy.get('input[name="bank[transit_issue_date]"]').should('be.visible')
        cy.get('input[name="bank[transit_rec_date]"]').should('be.visible')
        cy.get('input[name="bank[transit_storage_letter]"]').should('be.visible')


        cy.get(':nth-child(4) > .kt-wizard-v2__nav-body').click();
        cy.get('#customSystem option[value="4"]').invoke('attr', 'selected',true);
        cy.get(':nth-child(2) > .btn-success').click();
        cy.get(':nth-child(5) > .kt-wizard-v2__nav-body').click();
        cy.get('input[name="bank[lg_number]"]').should('be.visible')
        cy.get('input[name="bank[lg_request_date]').should('be.visible')
        cy.get('input[name="bank[lg_issuance_date]"]').should('be.visible')
        cy.get('input[name="bank[lg_amount]"]').should('be.visible')
        cy.get('select[name="bank[lg_currency_id]"]').should('exist')
        cy.get('input[name="bank[lg_broker_receipt_date]"]').should('be.visible')
    })
    it('checks all fields',function(){

      cy.get('input[name="bank[lg_number]"]').then((el)=>{
          el.prop('readonly',false).val('1/1/2020');
      });
      cy.get('input[name="bank[lg_request_date]').then((el)=>{
          el.prop('readonly',false).val('1/1/2020');
      });
      cy.get('input[name="bank[lg_issuance_date]"]').then((el)=>{
          el.prop('readonly',false).val('1/1/2020');
      });
      cy.get('input[name="bank[lg_amount]"]').clear().type(123456);
      cy.get('select[name="bank[lg_currency_id]"] option[value="1"]').invoke('attr', 'selected',true);
      cy.get('input[name="bank[lg_broker_receipt_date]"]').then((el)=>{
          el.prop('readonly',false).val('1/1/2020');
      });
      cy.get(':nth-child(2) > .btn-success').click();
    })
})
