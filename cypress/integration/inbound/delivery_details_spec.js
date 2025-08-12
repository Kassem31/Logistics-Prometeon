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
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.kt-badge--success').should('be.visible')
        cy.get('#atco_date').then((el)=>{
            el.prop('readonly',false).val('1/1/2020');
        });
        cy.get('#sap_date').then((el)=>{
            el.prop('readonly',false).val('12/1/2020');
        });
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.kt-badge--success').should('be.visible')
        cy.get('#bwh_date').then((el)=>{
            el.prop('readonly',false).val('14/1/2020');
        });
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.visit('/inbound');
        cy.contains('100%').should('be.visible');
    })
})
