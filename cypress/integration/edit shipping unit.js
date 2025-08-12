describe('create container size', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission ShippingUnit-list');
        cy.exec('php artisan user:add_permission ShippingUnit-create');
        cy.exec('php artisan test:create_country');
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click();
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').should('be.visible');
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').click();
        cy.contains('Shipping Units').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/shipping-unit');
        cy.get('a[href="http://127.0.0.1:8000/shipping-unit/4/edit"]').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/shipping-unit/9999/edit');
    })

    it('check required and non fields', function () {
        cy.get('input[name="name"]').clear().type('   ');
        cy.get('button[type="submit"]').click();
        cy.get('.text-danger').should('contain','The name field is required.')
        cy.get('input[name="name"]').clear().type('test1');
        cy.get('input[name="prefix"]').clear().type('  ');
        cy.get('button[type="submit"]').click();
        cy.get('contains','Shipping Unit Updated Successfully').should('be visible');
    })

    it('checks create permissions',function(){
        cy.exec("php artisan user:remove_permission ShippingUnit-edit");
        cy.visit('http://127.0.0.1:8000/shipping-unit/9999/edit',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')

    })


    after(function(){
        cy.exec("php artisan migrate:refresh && php artisan db:seed");
    })


})
