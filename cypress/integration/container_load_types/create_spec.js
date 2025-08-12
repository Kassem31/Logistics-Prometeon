describe('create container load type test', function () {
    beforeEach(function () {
      cy.exec('php artisan user:add_permission ContainerLoadType-list');
      cy.exec('php artisan user:add_permission ContainerLoadType-create');
      cy.visit('/');
      cy.url().should('contain', '/login');
      cy.get(':nth-child(2) > .form-control').type('test')
      cy.get(':nth-child(3) > .form-control').type('123456')
      cy.get('#kt_login_signin_submit').click();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.contains('Container Load Types').click();
      cy.url().should('contain','/load-types');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/load-types/create');
    //  cy.visit('/load-types/create')
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The name field is required.')
        cy.get('.text-danger').should('contain','The prefix field is required.')
        cy.url().should('contain','/load-types/create')
    })

    it('check all fields', function () {
        cy.get('input[name="name"]').type('test');
        cy.get('input[name="prefix"]').type('test');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.contains('Container Load Type Created Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/load-types/test').then((response)=>{
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('prefix', 'test')
        });
    })
    it('checks create permissions',function(){
      cy.exec("php artisan user:remove_permission ContainerLoadType-create");
      cy.visit('/load-types');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.visit('/load-types/create',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission ContainerLoadType-list");
      cy.visit('/load-types',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
