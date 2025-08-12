describe('create roles test', function () {
    beforeEach(function () {
      cy.exec('php artisan user:add_permission Role-list');
      cy.exec('php artisan user:add_permission Role-create');
      cy.visit('/');
      cy.url().should('contain', '/login');
      cy.get(':nth-child(2) > .form-control').type('test')
      cy.get(':nth-child(3) > .form-control').type('123456')
      cy.get('#kt_login_signin_submit').click();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.contains('Roles').click();
      cy.url().should('contain','/roles');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/roles/create');
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.swal2-popup').should('be.visible')
        cy.get('.swal2-confirm').click();
        cy.get('input[value="1"]').invoke('attr', 'checked',true);
        cy.wait(1000)
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The name field is required.')
    })

    it('check Unique fields', function () {
        cy.get('input[name="name"]').type('test');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.swal2-confirm').click();
        cy.wait(1000)
        cy.get('input[value="1"]').invoke('attr', 'checked',true);
        cy.wait(1000)
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/roles/create');
        cy.contains('The name has already been taken.').should('be.visible');
    })
    it('checks list when creating/editing + success', function(){
        cy.get('input[name="name"]').type('role');
        cy.get('input[value="2"]').invoke('attr', 'checked',true);
        cy.get('input[value="6"]').invoke('attr', 'checked',true);
        cy.wait(1000)
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.wait(1000)
        cy.contains('Roles Created Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/role/role').then((response)=>{
          expect(response.body).to.have.property('name', 'role')
          expect(response.body.permissions[0]).to.have.property('id', 2)
          expect(response.body.permissions[1]).to.have.property('id', 6)
        });
    })
    it('checks create permissions',function(){
      cy.exec("php artisan user:remove_permission Role-create");
      cy.visit('/roles');
      //cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.visit('/roles/create',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission Role-list");
      cy.visit('/roles',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
