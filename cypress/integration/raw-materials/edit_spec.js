describe('edit raw material test', function () {

      beforeEach(function () {
          cy.exec("php artisan user:add_permission RawMaterial-list");
          cy.exec("php artisan user:add_permission RawMaterial-edit");
          cy.visit('/');
          cy.url().should('contain', '/login');
          cy.get(':nth-child(2) > .form-control').type('test');
          cy.get(':nth-child(3) > .form-control').type('123456');
          cy.get('#kt_login_signin_submit').click();
          cy.get('#kt_header_mobile_toggler').should('be.visible');
          cy.get('#kt_header_mobile_toggler').click();
          cy.contains('Raw Materials').click();
          cy.url().should('contain','/raw-materials');
        //  cy.get('.btn-secondary').should('be.visible')
          cy.visit('/raw-materials/1000/edit');
      })
      before(function(){
        cy.exec("php artisan test:edit_raw_material");
        cy.exec("php artisan test:create_raw_material");
      })

      it('check mandatory fields', function () {
          cy.get('input[name="name"]').clear();
          cy.get('input[name="sap_code"]').clear();
          cy.get('select[name="material_group_id"] option[value=""]').invoke('attr', 'selected',true);
          cy.get('.row > :nth-child(2) > .btn-success').click();
          cy.get('.text-danger').should('contain','The name field is required.')
          cy.get('.text-danger').should('contain','The Material Group field is required.')
          cy.get('.text-danger').should('contain','The sap code field is required.')
          cy.url().should('contain','/raw-materials/1000/edit')
      })

      it('check unique fields', function () {
          cy.get('input[name="name"]').clear().type('test');
          cy.get('input[name="sap_code"]').clear().type('testing');
          cy.get('input[name="hs_code"]').clear().type('test');
          cy.get('select[name="material_group_id"] option[value="2"]').invoke('attr', 'selected',true);
          cy.get('.row > :nth-child(2) > .btn-success').click();
          cy.get('.text-danger').should('contain','The sap code has already been taken.')
      })

      it('check nullable fields', function () {
          cy.get('input[name="name"]').clear().type('tester');
          cy.get('input[name="sap_code"]').clear().type('tester');
          cy.get('input[name="hs_code"]').clear();
          cy.get('select[name="material_group_id"] option[value="2"]').invoke('attr', 'selected',true);
          cy.get('.row > :nth-child(2) > .btn-success').click();
          cy.contains('Raw Material Updated Successfully').should('be.visible');
          cy.server();
          cy.request('get','/api/raw-materials/tester').then((response)=>{
            expect(response.body).to.have.property('name', 'tester')
            expect(response.body).to.have.property('hs_code', null)
            expect(response.body).to.have.property('sap_code', 'tester')
            expect(response.body).to.have.property('material_group_id', 2)
          });
      })

      it('check all fields', function () {
          cy.get('input[name="name"]').clear().type('test');
          cy.get('input[name="sap_code"]').clear().type('test');
          cy.get('input[name="hs_code"]').clear().type('test');
          cy.get('select[name="material_group_id"] option[value="2"]').invoke('attr', 'selected',true);
          cy.get('.row > :nth-child(2) > .btn-success').click();
          cy.contains('Raw Material Updated Successfully').should('be.visible');
          cy.server();
          cy.request('get','/api/raw-materials/test').then((response)=>{
            expect(response.body).to.have.property('name', 'test')
            expect(response.body).to.have.property('hs_code', 'test')
            expect(response.body).to.have.property('sap_code', 'test')
            expect(response.body).to.have.property('material_group_id', 2)
          });
      })
      it('checks edit permissions',function(){
        cy.exec("php artisan user:remove_permission RawMaterial-edit");
        cy.visit('/raw-materials');
        cy.get('.btn-secondary').should('not.exist');
        cy.visit('/raw-materials/1000/edit',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
        cy.exec("php artisan user:remove_permission RawMaterial-list");
        cy.visit('/raw-materials',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
      });
      after(function(){
        cy.exec('php artisan migrate:refresh --seed');
      })
})
