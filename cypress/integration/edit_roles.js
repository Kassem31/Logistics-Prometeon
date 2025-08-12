describe('Edit roles test', function () {


    beforeEach(function () {
        cy.exec('php artisan user:add_permission Role-list');
        cy.exec('php artisan user:add_permission Role-edit');
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.exec("php artisan test:edit_user");
        cy.get(':nth-child(2) > .form-control').type('test');
        cy.get(':nth-child(3) > .form-control').type('123456');
        cy.get('#kt_login_signin_submit').click();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('Roles').click();
        cy.get('a[href="http://127.0.0.1:8000/roles/1/edit"]').click();
        cy.url().should('contain','http://127.0.0.1:8000/roles/1/edit')
        cy.exec('php artisan test:delete_user1');
        cy.exec('php artisan test:create_user1');
        cy.exec('php artisan test:delete_role');

    })

    it('check mandatory and unique field to be empty failure', function () {
        cy.get('input[name="name"]').clear().type('  ')
        cy.contains('Save').click();
        cy.url().should('contain','http://127.0.0.1:8000/roles/1/edit');
        cy.get('.text-danger').should('contain','The name field is required.')
    })

    it('check that non permitted pages is not accessible via URL', function () {
        cy.exec('php artisan user:remove_permission Role-list');
        cy.exec('php artisan user:remove_permission Role-edit');
        cy.visit('http://127.0.0.1:8000/roles', {failOnStatusCode: false});
        cy.get('.code').should('contain', '403');
        cy.exec('php artisan user:add_permission Role-list');
        cy.exec('php artisan user:add_permission Role-edit');
        cy.visit('http://127.0.0.1:8000/roles');
        cy.contains('Edit').should('be.visible');
    })

    it('ensure that the user can mark/unmark permissions and changes is done or not.', function () {
        cy.exec('php artisan test:create_role_testing');
        cy.exec('php artisan test:create_user_with_role');
        cy.visit('http://127.0.0.1:8000/roles');
        cy.get('a[href="http://127.0.0.1:8000/roles/9999/edit"]').click();
        cy.url().should('contain','http://127.0.0.1:8000/roles/9999/edit')
        cy.get('input[value="1"]').click({ force: true });
        cy.get('input[value="3"]').click({ force: true });
        cy.contains('Save').click();
        cy.get('.kt-header__topbar-username.kt-visible-desktop').click({ force: true });
        cy.contains('Sign Out').click({ force: true });
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test1');
        cy.get(':nth-child(3) > .form-control').type('9999');
        cy.get('#kt_login_signin_submit').click();
        cy.url().should('eq', 'http://127.0.0.1:8000/');
        cy.get('.kt-header-mobile__toolbar-toggler').click({force:true});
        cy.contains('Users').should('be.visible');
        cy.exec('php artisan test:delete_role');
    })


    afterEach(function () {
        cy.exec('php artisan user:remove_permission Role-list');
        cy.exec('php artisan user:remove_permission Role-edit');
        cy.exec('php artisan test:delete_user1');
        cy.exec('php artisan test:delete_role');
    })


})
