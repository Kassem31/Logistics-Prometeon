describe('create shipping line test', function () {
    beforeEach(function () {
      cy.exec('php artisan user:add_permission ShippingLine-list');
      cy.exec('php artisan user:add_permission ShippingLine-create');
      cy.visit('/');
      cy.url().should('contain', '/login');
      cy.get(':nth-child(2) > .form-control').type('test')
      cy.get(':nth-child(3) > .form-control').type('123456')
      cy.get('#kt_login_signin_submit').click();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.contains('Shipping Line').click();
      cy.url().should('contain','/shipping-line');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/shipping-line/create');
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The name field is required.')
    })

    it('check datatype fields', function () {
        cy.get('input[name="name"]').type('test');
        cy.get('input[name="email"]').type('test');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/shipping-line/create');
        cy.contains('The email must be a valid email address.').should('be.visible');
    })
    it('check nullable fields', function () {
        cy.get('input[name="name"]').type('test');
        cy.get('input[name="phone"]').clear();
        cy.get('input[name="email"]').clear();
        cy.get('input[name="contact_person"]').clear();
        cy.get('select[name="country_id"] > option[value=""]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/shipping-line');

        cy.get('.alert').should('contain','Shipping Line Created Successfully')
        cy.server();
        cy.request('get','/api/shipping-line/test').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('email', null)
          expect(response.body).to.have.property('phone', null)
          expect(response.body).to.have.property('is_active', 1)
          expect(response.body).to.have.property('country_id', null)
          expect(response.body).to.have.property('contact_person', null)
        });
    })

    it('check all fields', function () {
        cy.get('input[name="name"]').type('newtest');
        cy.get('input[name="phone"]').type('123456789');
        cy.get('input[name="is_active"]').invoke('attr', 'value',0);
        cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
        cy.get('input[name="email"]').clear().type('test@test.com');
        cy.get('input[name="contact_person"]').clear().type('new person');
        cy.get('select[name="country_id"] > option[value="2"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/shipping-line');

        cy.get('.alert').should('contain','Shipping Line Created Successfully')
        cy.server();
        cy.request('get','/api/shipping-line/newtest').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'newtest')
          expect(response.body).to.have.property('email', 'test@test.com')
          expect(response.body).to.have.property('phone', '123456789')
          expect(response.body).to.have.property('country_id', 2)
          expect(response.body).to.have.property('is_active', 0)
          expect(response.body).to.have.property('contact_person', 'new person')
        });
    })

    it('checks create permissions',function(){
      cy.exec("php artisan user:remove_permission ShippingLine-create");
      cy.visit('/shipping-line');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.visit('/shipping-line/create',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission ShippingLine-list");
      cy.visit('/brokers',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
