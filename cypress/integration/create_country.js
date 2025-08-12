describe('create country test', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission Country-list');
        cy.exec('php artisan user:add_permission Country-create');
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click();
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').should('be.visible');
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').click();
        cy.contains('Countries').click();
        cy.url().should('contain', '/countries');
        cy.get('a[href="http://127.0.0.1:8000/countries/create"]').click();
        cy.url().should('contain', '/countries/create');
    })

    it('check required fields', function () {
        cy.get('button[type="submit"]').click();
        cy.get('.text-danger').should('contain','The name field is required.')
        cy.get('.text-danger').should('contain','The prefix field is required.')
        cy.get('.text-danger').should('contain','The currency field is required.')
    })

    it('check Unique fields', function () {
        cy.get('input[name="name"]').type('Egypt');
        cy.get('input[name="prefix"]').type('EG');
        cy.get('button[type="submit"]').click();
        cy.url().should('contain', 'countries/create');
        cy.contains('The name has already been taken.').should('be.visible');
        cy.contains('The prefix has already been taken.').should('be.visible');
    })

    it('check inputs data type validation', function(){
        cy.get('input[name="name"]').type('666');
        cy.get('input[name="prefix"]').type('666');
        cy.get('input[name="currency"]').type('666');
        cy.url().should('contain','countries/create');
        cy.get('.text-danger').should('contain','please enter valid name.')
        cy.get('.text-danger').should('contain','please enter valid prefix.')
        cy.get('.text-danger').should('contain','please enter valid currency.')
    })


    it('checks create permissions',function(){
        cy.exec("php artisan user:remove_permission Country-create");
        cy.visit('/countries');
        cy.visit('/countries/create',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
        cy.exec("php artisan user:remove_permission Country-list");
        cy.visit('/countries',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
    })


    after(function(){
        cy.exec("php artisan migrate:refresh && php artisan db:seed");
    })


})

