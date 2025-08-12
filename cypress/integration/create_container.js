describe('create container size', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission ContainerSize-list');
        cy.exec('php artisan user:add_permission ContainerSize-create');
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click();
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').should('be.visible');
        cy.get('button[class="kt-header-mobile__toolbar-toggler"]').click();
        cy.contains('Container Sizes').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/container-sizes');
        cy.contains('Create Container Size"]').click();
        cy.url().should('contain', 'container-sizes/create');
    })

    it('check required fields', function () {
        cy.get('button[type="submit"]').click();
        cy.get('.text-danger').should('contain','The size field is required.')
    })

    it('checks create permissions',function(){
        cy.exec("php artisan user:remove_permission ContainerSize-create");
        cy.visit('/container-sizes');
        cy.visit('container-sizes/create',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
        cy.exec("php artisan user:remove_permission ContainerSize-list");
        cy.visit('/container-sizes',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
    })


    after(function(){
        cy.exec("php artisan migrate:refresh && php artisan db:seed");
    })


})

