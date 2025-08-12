describe('create inbound test', function () {
    before(function(){
        cy.exec('php artisan user:add_permission ShippingBasic-list');
        cy.exec('php artisan user:add_permission ShippingBasic-create');
        cy.exec('php artisan user:add_permission ShippingBasic-edit');
        cy.exec('php artisan test:create_raw_material');
        cy.exec('php artisan test:create_user_materialgroup');
        cy.exec('php artisan test:edit_supplier')
        cy.exec("php artisan test:create_fake_po");
    })
    beforeEach(function () {
      cy.visit('/');
      cy.login();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      // cy.get('#kt_header_mobile_toggler').click();
      // cy.get('.kt-menu__subnav > .kt-menu__item > .kt-menu__link > .kt-menu__link-text').click({force:true});
      //cy.url().should('contain','/inbound');
      cy.visit('/inbound');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/inbound/create');
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The PO field is required.')
    })
    it('check all fields', function () {
        cy.get(':nth-child(2) > .col-lg-6 > .form-control').clear().type('inboundno')
        cy.get('#rawMaterial option[value="1000"]').invoke('attr', 'selected',true);
        cy.get('#rawMaterial').trigger('change',{force:true});
        cy.wait(10000);
        cy.get('.row_material > div > .form-control option[value="100"]').should('be.visible')
        cy.get('.row_material > div > .form-control option[value="100"]').invoke('attr', 'selected',true);
        cy.get('.row_material > div > .form-control option[value="100"]').trigger('change',{force:true});
        cy.wait(10000);
        cy.get('.row_qty > div > .form-control').clear().type(120)

        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.contains('Invalid Qty').should('be.visible');
        cy.get('.swal2-confirm').click()
        cy.url().should('contain', '/inbound/create');
        cy.get('.row_qty > div > .form-control').clear().type(20)
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.alert').should('contain','Inbound Created Successfully')
        cy.server();
        cy.request('get','/api/inbound/inboundno').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('po_header_id', 1000)
          expect(response.body).to.have.property('inbound_no','inboundno')
          expect(response.body.details[0]).to.have.property('po_detail_id', 100)
          expect(response.body.details[0]).to.have.property('qty', "20.00")
        });
    })
    it('checks create permissions',function(){
      cy.assert_user_url_permission_bb('ShippingBasic-create','/inbound/create');
      cy.visit('/inbound');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
       cy.assert_user_url_permission_bb('ShippingBasic-list','/inbound');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
