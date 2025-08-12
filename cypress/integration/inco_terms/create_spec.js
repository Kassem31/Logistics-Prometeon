describe('create inco term test', function () {
    beforeEach(function () {
      cy.exec('php artisan user:add_permission IncoTerm-list');
      cy.exec('php artisan user:add_permission IncoTerm-create');
      cy.visit('/');
      cy.url().should('contain', '/login');
      cy.get(':nth-child(2) > .form-control').type('test')
      cy.get(':nth-child(3) > .form-control').type('123456')
      cy.get('#kt_login_signin_submit').click();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.contains('Inco Terms').click();
      cy.url().should('contain','/inco-terms');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/inco-terms/create');
    //  cy.visit('/inco-terms/create')
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The name field is required.')
        cy.get('.text-danger').should('contain','The prefix field is required.')
        cy.url().should('contain','/inco-terms/create')
    })

    it('check nullable fields', function () {
        cy.get('input[name="name"]').type('test');
        cy.get('input[name="prefix"]').type('test');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.contains('Inco Term Created Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/inco-terms/test').then((response)=>{
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('prefix', 'test')
          expect(response.body).to.have.property('is_active', 1)
        });
    })

    it('check all fields', function () {
        cy.get('input[name="name"]').type('testing');
        cy.get('input[name="prefix"]').type('testing');
        cy.get('input[name="is_active"]').invoke('attr', 'value',0);
        cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.contains('Inco Term Created Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/inco-terms/testing').then((response)=>{
          expect(response.body).to.have.property('name', 'testing')
          expect(response.body).to.have.property('prefix', 'testing')
          expect(response.body).to.have.property('is_active', 0)
        });
    })
    it('checks create permissions',function(){
      cy.exec("php artisan user:remove_permission IncoTerm-create");
      cy.visit('/inco-terms');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.visit('/inco-terms/create',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission IncoTerm-list");
      cy.visit('/inco-terms',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
