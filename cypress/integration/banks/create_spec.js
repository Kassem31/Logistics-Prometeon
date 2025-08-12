describe('create Bank test', function () {
    before(function(){
        cy.exec('php artisan user:add_permission Bank-list');
        cy.exec('php artisan user:add_permission Bank-create');
    })
    beforeEach(function () {
      cy.visit('/');
      cy.login();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.get('.kt-menu__subnav > .kt-menu__item > .kt-menu__link > .kt-menu__link-text').click({force:true});
      cy.url().should('contain','/banks');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/banks/create');
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The name field is required.')
    })
    it('check nullable fields', function () {
        cy.get('input[name="name"]').type('test');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/banks');

        cy.get('.alert').should('contain','Bank Created Successfully')
        cy.server();
        cy.request('get','/api/bank/test').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('is_active',1)
        });
    })
    it('check all fields', function () {
        cy.get('input[name="name"]').clear().type('testing');
        cy.get('input[name="is_active"]').invoke('attr', 'value',0);
        cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/banks');

        cy.get('.alert').should('contain','Bank Created Successfully')
        cy.server();
        cy.request('get','/api/bank/testing').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'testing')
          expect(response.body).to.have.property('is_active',0)
        });
    })

    it('checks create permissions',function(){
      cy.assert_user_url_permission_bb('Bank-create','/banks/create');
      cy.visit('/banks');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
       cy.assert_user_url_permission_bb('Bank-list','/banks');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
