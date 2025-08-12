describe('edit custom system',function () {

    beforeEach(function () {
        cy.exec('php artisan user:add_permission CustomSystem-list');
        cy.exec('php artisan user:add_permission CustomSystem-edit');
        cy.exec('php artisan test:create_custom_system');
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click();
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').should('be.visible');
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').click();
        cy.contains('Custom Systems').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/custom-systems');
        cy.get('a[href="http://127.0.0.1:8000/custom-systems/9999/edit"]').click();
        cy.url().should('contain', 'custom-systems/create');
    })
    it('check required fields', function () {
        cy.get('button[type="submit"]').click();
        cy.get('.text-danger').should('contain','The name field is required.')

    });
    it('checks create permissions',function(){
        cy.exec("php artisan user:remove_permission CustomSystem-edit");
        cy.visit('/custom-systems');
        cy.visit('http://127.0.0.1:8000/custom-systems/9999/edit',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
    })
    after(function(){
        cy.exec('php artisan migrate:refresh --seed');
    })



});
