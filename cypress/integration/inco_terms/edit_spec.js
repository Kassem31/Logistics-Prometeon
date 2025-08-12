describe('edit inco term test', function () {

      beforeEach(function () {
          cy.exec("php artisan user:add_permission IncoTerm-list");
          cy.exec("php artisan user:add_permission IncoTerm-edit");
          cy.visit('/');
          cy.url().should('contain', '/login');
          cy.get(':nth-child(2) > .form-control').type('test');
          cy.get(':nth-child(3) > .form-control').type('123456');
          cy.get('#kt_login_signin_submit').click();
          cy.get('#kt_header_mobile_toggler').should('be.visible');
          cy.get('#kt_header_mobile_toggler').click();
          cy.contains('Inco Terms').click();
          cy.url().should('contain','/inco-terms');
        //  cy.get('.btn-secondary').should('be.visible')
          cy.visit('/inco-terms/1000/edit');
      })
      before(function(){
        cy.exec("php artisan test:edit_inco_term");
      })

      it('check mandatory fields', function () {
          cy.get('input[name="name"]').clear();
          cy.get('input[name="prefix"]').clear();
          cy.get('.row > :nth-child(2) > .btn-success').click();
          cy.get('.text-danger').should('contain','The name field is required.')
          cy.get('.text-danger').should('contain','The prefix field is required.')
          cy.url().should('contain','/inco-terms/1000/edit')
      })
      it('check all fields', function () {
          cy.get('input[name="name"]').clear().type('test');
          cy.get('input[name="prefix"]').clear().type('test');
          cy.get('input[name="is_active"]').invoke('attr', 'value',0);
          cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
          cy.get('.row > :nth-child(2) > .btn-success').click();
          cy.contains('Inco Term Updated Successfully').should('be.visible');
          cy.server();
          cy.request('get','/api/inco-terms/test').then((response)=>{
            expect(response.body).to.have.property('name', 'test')
            expect(response.body).to.have.property('prefix', 'test')
            expect(response.body).to.have.property('is_active', 0)
          });
      })
      it('checks edit permissions',function(){
        cy.exec("php artisan user:remove_permission IncoTerm-edit");
        cy.visit('/inco-terms');
        cy.get('.btn-secondary').should('not.exist');
        cy.visit('/inco-terms/1000/edit',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
        cy.exec("php artisan user:remove_permission IncoTerm-list");
        cy.visit('/inco-terms',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
      });
      after(function(){
        cy.exec('php artisan migrate:refresh --seed');
      })
})
