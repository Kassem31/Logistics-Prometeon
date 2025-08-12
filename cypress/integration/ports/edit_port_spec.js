describe('Edit Users test', function () {

    beforeEach(function () {
        cy.exec("php artisan user:add_permission Port-list");
        cy.exec("php artisan user:add_permission Port-edit");
        cy.visit('/');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test');
        cy.get(':nth-child(3) > .form-control').type('123456');
        cy.get('#kt_login_signin_submit').click();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('Ports').click();
        cy.url().should('contain','/ports');
        cy.get('.btn-secondary').should('be.visible')
        cy.visit('/ports/1000/edit');
        cy.url().should('contain', '/ports/1000/edit');
    })
    before(function(){
      cy.exec("php artisan test:edit_port");
    })
    it('checks required fields', function () {
        cy.get('input[name="name"]').clear()
        cy.get('select[name="country_id"] option[value=""]').invoke('attr', 'selected',true);
        cy.get('.btn.btn-success').click();
        cy.url().should('contain','/ports/1000/edit');
        cy.get('.text-danger').should('contain','The name field is required.')
        cy.get('.text-danger').should('contain','The Country field is required.')
    })
    it('checks all fields', function () {
        cy.get('input[name="name"]').clear().type('oldport')
        cy.get('select[name="country_id"] option[value="2"]').invoke('attr', 'selected',true);
        cy.get('.btn.btn-success').click();
        cy.contains('Port Updated Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/port/oldport').then((response)=>{
          expect(response.body).to.have.property('name', 'oldport')
          expect(response.body).to.have.property('country_id', 2)
        });
    })
    it('checks edit permissions',function(){
      cy.exec("php artisan user:remove_permission Port-edit");
      cy.visit('/ports');
      cy.get('.btn-secondary').should('not.exist')
      cy.visit('/ports/1000/edit',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission Port-list");
      cy.visit('/ports',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
