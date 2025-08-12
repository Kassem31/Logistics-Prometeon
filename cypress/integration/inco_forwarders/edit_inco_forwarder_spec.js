describe('Edit Users test', function () {

    beforeEach(function () {
        cy.exec("php artisan user:add_permission IncoForwarder-list");
        cy.exec("php artisan user:add_permission IncoForwarder-edit");
        cy.visit('/');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test');
        cy.get(':nth-child(3) > .form-control').type('123456');
        cy.get('#kt_login_signin_submit').click();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('Inco Forwarder').click();
        cy.url().should('contain','/inco-forwarders');
        cy.get('.btn-secondary').should('be.visible');
        cy.visit('/inco-forwarders/1000/edit');
        cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');

    })
    before(function(){
      cy.exec('php artisan test:edit_inco_forwarder');
    })
    it('checks required fields', function () {
        cy.get('input[name="name"]').clear();
        cy.get('input[name="phone"]').clear();
        cy.get('input[name="email"]').clear();
        cy.get('input[name="contact_person"]').clear();
        cy.get('select[name="country_id"] > option[value=""]').invoke('attr', 'selected',true);
        cy.get('.btn.btn-success').click();
        cy.url().should('contain','/inco-forwarders/1000/edit');
        cy.get('.text-danger').should('contain','The name field is required.')
    })

    it('check datatype fields', function () {
        cy.get('input[name="name"]').clear().type('test');
        cy.get('input[name="email"]').clear().type('test');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/inco-forwarders/1000/edit');
        cy.get('.text-danger').should('contain','The email must be a valid email address.')
    })
    it('check nullable fields', function () {
        cy.get('input[name="name"]').clear().type('test');
        cy.get('input[name="phone"]').clear();
        cy.get('input[name="email"]').clear();
        cy.get('input[name="contact_person"]').clear();
        cy.get('select[name="country_id"] > option[value=""]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/inco-forwarders');

        cy.get('.alert').should('contain','Inco Forwarder Updated Successfully')
        cy.server();
        cy.request('get','/api/inco-forarders/test').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('email', null)
          expect(response.body).to.have.property('phone', null)
          expect(response.body).to.have.property('country_id', null)
          expect(response.body).to.have.property('contact_person', null)
        });
    })

    it('check all fields', function () {
        cy.get('input[name="name"]').clear().type('newtest');
        cy.get('input[name="phone"]').clear().type('123456789');
        cy.get('input[name="email"]').clear().type('test@test.com');
        cy.get('input[name="contact_person"]').clear().type('new person');
        cy.get('select[name="country_id"] > option[value="2"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/inco-forwarders');

        cy.get('.alert').should('contain','Inco Forwarder Updated Successfully')
        cy.server();
        cy.request('get','/api/inco-forarders/newtest').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'newtest')
          expect(response.body).to.have.property('email', 'test@test.com')
          expect(response.body).to.have.property('phone', '123456789')
          expect(response.body).to.have.property('country_id', 2)
          expect(response.body).to.have.property('contact_person', 'new person')
        });
    })

    it('checks edit permissions',function(){
      cy.exec("php artisan user:remove_permission IncoForwarder-edit");
      cy.visit('/inco-forwarders');
      cy.get('.btn-secondary').should('not.exist');
      cy.visit('/inco-forwarders/1000/edit',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission IncoForwarder-list");
      cy.visit('/inco-forwarders',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
