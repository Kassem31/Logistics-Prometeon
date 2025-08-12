describe('Edit Insurance Company test', function () {

    beforeEach(function () {
        cy.visit('/');
        cy.login();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('Insurance Company').click();
        cy.url().should('contain','/insurance-companies');
        cy.get('.btn-secondary').should('be.visible');
        cy.visit('/insurance-companies/1000/edit');
        cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');

    })
    before(function(){
      cy.exec("php artisan user:add_permission InsuranceCompany-list");
      cy.exec("php artisan user:add_permission InsuranceCompany-edit");
      cy.exec('php artisan test:edit_insurance_company');
    })
    it('checks required fields', function () {
        cy.get('input[name="name"]').clear();
        cy.get('.btn.btn-success').click();
        cy.url().should('contain','/insurance-companies/1000/edit');
        cy.get('.text-danger').should('contain','The name field is required.')
    })
    it('check ALL fields', function () {
        cy.get('input[name="name"]').clear().type('test');
        cy.get('input[name="is_active"]').invoke('attr', 'value',0);
        cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/insurance-companies');

        cy.get('.alert').should('contain','Insurance Company Updated Successfully')
        cy.server();
        cy.request('get','/api/insurance_companies/1000').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('is_active', 0)

        });
    })
    it('checks edit permissions',function(){
      cy.assert_user_url_permission_bb('InsuranceCompany-edit','/insurance-companies/1000/edit');
      cy.visit('/insurance-companies');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
       cy.assert_user_url_permission_bb('InsuranceCompany-list','/insurance-companies');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
