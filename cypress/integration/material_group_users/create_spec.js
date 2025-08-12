describe('create material group users test', function () {
    beforeEach(function () {
      cy.exec('php artisan user:add_permission MaterialGroup-list');
      cy.exec('php artisan user:add_permission MaterialGroup-create');
      cy.visit('/');
      cy.url().should('contain', '/login');
      cy.get(':nth-child(2) > .form-control').type('test')
      cy.get(':nth-child(3) > .form-control').type('123456')
      cy.get('#kt_login_signin_submit').click();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.contains('Material Groups').click();
      cy.url().should('contain','/material-groups');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/material-groups/create');
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    before(function(){
      cy.exec("php artisan test:create_user_material");
      cy.exec("php artisan test:create_user1");
    })
    it('check assign fields', function () {
        cy.get('input[name="name"]').type('testing');
        cy.get('select[name="users[]"] option[value="2000"]').invoke('attr', 'selected',true);
        cy.get('select[name="users[]"] option[value="9999"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.contains('Material Group Created Successfully').should('be.visible');
        cy.server();
        cy.request('get','/api/material-groups/testing').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'testing')
          expect(response.body.users[0]).to.have.property('id', 9999)
          expect(response.body.users[1]).to.have.property('id', 2000)
        });
    })
    it('checks create permissions',function(){
      cy.exec("php artisan user:remove_permission MaterialGroup-create");
      cy.visit('/material-groups');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.visit('/material-groups/create',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission MaterialGroup-list");
      cy.visit('/material-groups',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
