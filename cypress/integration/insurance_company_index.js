describe('List insurance company', function () {
    before(function(){
        cy.exec("php artisan test:fake_insurance_company");
        cy.exec("php artisan user:add_permission InsuranceCompany-list");
    });
    beforeEach(function(){
        cy.visit('/');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click();
    });
    it('visits insurance company list',function(){
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('Insurance Company').click();
        cy.url().should('contain','http://127.0.0.1:8000/insurance-companies');
        cy.get('.btn-secondary').should('not.exist')
        cy.get('.page-link').should('be.visible');
    })
    it('checks permissions',function(){
        cy.exec("php artisan user:remove_permission InsuranceCompany-list");
        cy.visit('/insurance-companies',{ failOnStatusCode: false});
        cy.contains('403').should('be.visible');
    })
    after(function(){
        cy.exec("php artisan migrate:refresh && php artisan db:seed");
    });
})
