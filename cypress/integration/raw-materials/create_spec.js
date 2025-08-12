describe('create raw material test', function () {
    beforeEach(function () {
      cy.exec('php artisan user:add_permission RawMaterial-list');
      cy.exec('php artisan user:add_permission RawMaterial-create');
      cy.visit('/');
      cy.url().should('contain', '/login');
      cy.get(':nth-child(2) > .form-control').type('test')
      cy.get(':nth-child(3) > .form-control').type('123456')
      cy.get('#kt_login_signin_submit').click();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.contains('Raw Materials').click();
      cy.url().should('contain','/raw-materials');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/raw-materials/create');
    //  cy.visit('/raw-materials/create')
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    before(function(){
      cy.exec("php artisan test:create_raw_material");
    })

    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The name field is required.')
        cy.get('.text-danger').should('contain','The Material Group field is required.')
        cy.get('.text-danger').should('contain','The sap code field is required.')
        cy.url().should('contain','/raw-materials/create')
    })

    it('check nullable fields', function () {
        cy.get('input[name="name"]').type('test');
        cy.get('input[name="sap_code"]').type('test');
        cy.get('select[name="material_group_id"] option[value="1"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.contains('Raw Material Created Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/raw-materials/test').then((response)=>{
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('sap_code', 'test')
          expect(response.body).to.have.property('material_group_id', 1)
        });
    })
    it('check unique fields', function () {
        cy.get('input[name="name"]').type('testing');
        cy.get('input[name="sap_code"]').type('testing');
        cy.get('input[name="hs_code"]').type('testing');
        cy.get('select[name="material_group_id"] option[value="3"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The sap code has already been taken.')
    })
    it('check all fields', function () {
        cy.get('input[name="name"]').type('tes');
        cy.get('input[name="sap_code"]').type('tes');
        cy.get('input[name="hs_code"]').type('tes');
        cy.get('select[name="material_group_id"] option[value="3"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.contains('Raw Material Created Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/raw-materials/tes').then((response)=>{
          expect(response.body).to.have.property('name', 'tes')
          expect(response.body).to.have.property('hs_code', 'tes')
          expect(response.body).to.have.property('sap_code', 'tes')
          expect(response.body).to.have.property('material_group_id', 3)
        });
    })
    it('checks create permissions',function(){
      cy.exec("php artisan user:remove_permission RawMaterial-create");
      cy.visit('/raw-materials');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.visit('/raw-materials/create',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission RawMaterial-list");
      cy.visit('/raw-materials',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
