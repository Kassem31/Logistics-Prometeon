describe('edit container load type test', function () {

      beforeEach(function () {
          cy.exec("php artisan user:add_permission ContainerLoadType-list");
          cy.exec("php artisan user:add_permission ContainerLoadType-edit");
          cy.visit('/');
          cy.url().should('contain', '/login');
          cy.get(':nth-child(2) > .form-control').type('test');
          cy.get(':nth-child(3) > .form-control').type('123456');
          cy.get('#kt_login_signin_submit').click();
          cy.get('#kt_header_mobile_toggler').should('be.visible');
          cy.get('#kt_header_mobile_toggler').click();
          cy.contains('Container Load Types').click();
          cy.url().should('contain','/load-types');
        //  cy.get('.btn-secondary').should('be.visible')
          cy.visit('/load-types/1000/edit');
      })
      before(function(){
        cy.exec("php artisan test:edit_container_load_type");
      })

      it('check mandatory fields', function () {
          cy.get('input[name="name"]').clear();
          cy.get('input[name="prefix"]').clear();
          cy.get('.row > :nth-child(2) > .btn-success').click();
          cy.get('.text-danger').should('contain','The name field is required.')
          cy.get('.text-danger').should('contain','The prefix field is required.')
          cy.url().should('contain','/load-types/1000/edit')
      })

      it('check all fields', function () {
          cy.get('input[name="name"]').clear().type('test');
          cy.get('input[name="prefix"]').clear().type('test');
          cy.get('.row > :nth-child(2) > .btn-success').click();
          cy.contains('Container Load Type Updated Successfully').should('be.visible');
          cy.server();
          cy.request('get','/api/load-types/test').then((response)=>{
            expect(response.body).to.have.property('name', 'test')
            expect(response.body).to.have.property('prefix', 'test')
          });
      })
      it('checks edit permissions',function(){
        cy.exec("php artisan user:remove_permission ContainerLoadType-edit");
        cy.visit('/load-types');
        cy.get('.btn-secondary').should('not.exist');
        cy.visit('/load-types/1000/edit',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
        cy.exec("php artisan user:remove_permission ContainerLoadType-list");
        cy.visit('/load-types',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
      });
      after(function(){
        cy.exec('php artisan migrate:refresh --seed');
      })
})
