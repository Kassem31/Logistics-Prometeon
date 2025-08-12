describe('create insurance company', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission InsuranceCompany-list');
        cy.exec('php artisan user:add_permission InsuranceCompany-create');
        cy.visit('/');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('Insurance Company').click();
        cy.url().should('contain','http://127.0.0.1:8000/insurance-companies');
        cy.get('.kt-portlet__head-toolbar > .btn').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/insurance-companies/create');
    })

    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The name field is required.')
    })

    it('check status change radio button', function () {
        cy.get('input[name="name"]').type('newtest');
        cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
        cy.get('.btn.btn-success').click();
        cy.url().should('contain','/insurance-companies');
        cy.server();
        cy.request('get','/api/insurance_companies/1').then((response)=>{
            expect(response.body).to.have.property('is_active', 0);

        });
        cy.exec('php artisan migrate:refresh --seed');
    })
    it('check all fields', function () {
        cy.get('input[name="name"]').type('newtest');
        cy.get('input[name="is_active"]').invoke('attr', 'value',0);
        cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
        cy.get('.btn.btn-success').click();
          cy.get('.alert').should('contain','Insurance Company Created Successfully\n')

    })

    it('checks create permissions',function(){
        cy.exec("php artisan user:remove_permission InsuranceCompany-create");
        cy.visit('/insurance-companies');
        cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
        cy.visit('/insurance-companies/create',{ failOnStatusCode: false});
        cy.get('.code').should('contain','403')
        cy.exec("php artisan user:remove_permission InsuranceCompany-list");
        cy.visit('/insurance-companies',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
    });
    after(function(){
        cy.exec('php artisan migrate:refresh --seed');
    })

})
