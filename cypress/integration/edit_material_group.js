describe('create material group', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission MaterialGroup-list');
        cy.exec('php artisan user:add_permission MaterialGroup-edit');
        cy.exec('php artisan test:create_material_group');
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click();
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').should('be.visible');
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').click();
        cy.contains('Material Groups').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/material-groups');
        cy.get('a[href="http://127.0.0.1:8000/material-groups/9999/edit"]').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/material-groups/9999/edit');
    })

    it('check required fields', function () {
        cy.get('input[name="name"]').clear().type('  ');
        cy.get('button[type="submit"]').click();
        cy.get('.text-danger').should('contain','The name field is required.')
    })

    it.only('checks create permissions',function(){
        cy.exec("php artisan user:remove_permission MaterialGroup-edit");
        cy.visit('http://127.0.0.1:8000/material-groups/9999/edit',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')

    })


    afterEach(function(){
        cy.exec('php artisan migrate:refresh  && php artisan db:seed');
    })


})
