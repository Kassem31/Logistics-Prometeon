describe('Edit banks test', function () {

    beforeEach(function () {

       cy.exec("php artisan migrate --seed");
        cy.exec("php artisan user:add_permission Bank-list");
        cy.exec("php artisan user:add_permission Bank-edit");
       cy.exec('php artisan test:edit_bank');
        cy.visit('http://127.0.0.1:8000/');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test');
        cy.get(':nth-child(3) > .form-control').type('123456');
        cy.get('#kt_login_signin_submit').click();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('Banks').click();
        cy.url().should('contain','/banks');
        cy.get('.btn-secondary').should('be.visible');
        cy.visit('banks/1000/edit');

    })
    before(function(){

    })
    it('checks required fields', function () {
        cy.get('input[name="name"]').clear().type('  ');
        cy.get('.btn.btn-success').click();
        cy.url().should('contain','/banks/1000/edit');
        cy.get('.text-danger').should('contain','The name field is required.')
    })


    it('check status change radio button', function () {
        cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
        cy.get('.btn.btn-success').click();
        cy.url().should('contain','/banks');
        cy.server();
        cy.request('get','/api/banks/1000').then((response)=>{
            expect(response.body).to.have.property('is_active', 0);

        });


    })

    it('checks edit permissions',function(){
        cy.exec("php artisan user:remove_permission Bank-edit");
        cy.visit('/banks');
        cy.get('.btn-secondary').should('not.exist');
        cy.visit('/banks/1000/edit',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
        cy.exec("php artisan user:remove_permission Bank-list");
        cy.visit('/banks',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
    });

})
