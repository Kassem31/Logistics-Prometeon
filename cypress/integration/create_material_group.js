describe('create material group', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission MaterialGroup-list');
        cy.exec('php artisan user:add_permission MaterialGroup-create');
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click();
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').should('be.visible');
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').click();
        cy.contains('Material Groups').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/material-groups');
        cy.contains('Create Material Group"]').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/material-groups/create');
    })

    it('check required fields', function () {
        cy.get('button[type="submit"]').click();
        cy.get('.text-danger').should('contain','The name field is required.')
    })

    it('checks create permissions',function(){
        cy.exec("php artisan user:remove_permission MaterialGroup-create");
        cy.visit('http://127.0.0.1:8000/material-groups');
        cy.visit('http://127.0.0.1:8000/material-groups/create',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
        cy.exec("php artisan user:remove_permission MaterialGroup-list");
        cy.visit('http://127.0.0.1:8000/material-groups',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
    })


    aftereach(function(){
        cy.exec('php artisan migrate:refresh  && php artisan db:seed');
    })


})

