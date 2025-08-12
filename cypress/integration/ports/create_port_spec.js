describe('create port test', function () {
    beforeEach(function () {
      cy.exec('php artisan user:add_permission Port-list');
      cy.exec('php artisan user:add_permission Port-create');
      cy.visit('/');
      cy.url().should('contain', '/login');
      cy.get(':nth-child(2) > .form-control').type('test')
      cy.get(':nth-child(3) > .form-control').type('123456')
      cy.get('#kt_login_signin_submit').click();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.contains('Ports').click();
      cy.url().should('contain','/ports');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/ports/create');
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The name field is required.')
        cy.get('.text-danger').should('contain','The Country field is required.')
    })

    it('check all fields', function () {
        cy.get('input[name="name"]').type('test');
        cy.get('select[name="country_id"] option[value="1"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.contains('Port Created Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/port/test').then((response)=>{
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('country_id', 1)
        });
    })
    it('checks create permissions',function(){
      cy.exec("php artisan user:remove_permission Port-create");
      cy.visit('/ports');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.visit('/ports/create',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission Port-list");
      cy.visit('/ports',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
