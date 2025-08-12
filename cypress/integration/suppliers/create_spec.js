describe('create shipping line test', function () {
    beforeEach(function () {
      cy.exec('php artisan user:add_permission Supplier-list');
      cy.exec('php artisan user:add_permission Supplier-create');
      cy.visit('/');
      cy.url().should('contain', '/login');
      cy.get(':nth-child(2) > .form-control').type('test')
      cy.get(':nth-child(3) > .form-control').type('123456')
      cy.get('#kt_login_signin_submit').click();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.contains('Suppliers').click();
      cy.url().should('contain','/suppliers');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/suppliers/create');
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    before(function(){
      cy.exec("php artisan test:create_supplier");
    })
    it('checks required fields', function () {
        cy.get('input[name="name"]').clear();
        cy.get('input[name="phone"]').clear();
        cy.get('input[name="sap_code"]').clear();
        cy.get('input[name="contact_person"]').clear();
        cy.get('select[name="country_id"] > option[value=""]').invoke('attr', 'selected',true);
        cy.get('.btn.btn-success').click();
        cy.url().should('contain','/suppliers/create');
        cy.get('.text-danger').should('contain','The name field is required.')
        cy.get('.text-danger').should('contain','The sap code field is required.')
    })

    it('check unique fields', function () {
        cy.get('input[name="name"]').clear().type('test');
        cy.get('input[name="phone"]').clear();
        cy.get('input[name="sap_code"]').clear().type('testing');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/suppliers/create');
        cy.get('.text-danger').should('contain','The sap code has already been taken.')
    })
    it('check nullable fields', function () {
        cy.get('input[name="name"]').clear().type('test');
        cy.get('input[name="phone"]').clear();
        cy.get('input[name="sap_code"]').clear().type('newold');
        cy.get('input[name="contact_person"]').clear();
        cy.get('select[name="country_id"] > option[value=""]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/suppliers');

        cy.get('.alert').should('contain','Supplier Created Successfully')
        cy.server();
        cy.request('get','/api/suppliers/newold').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'test')
          expect(response.body).to.have.property('sap_code', 'newold')
          expect(response.body).to.have.property('phone', null)
          expect(response.body).to.have.property('country_id', null)
          expect(response.body).to.have.property('contact_person', null)
        });
    })

    it('check all fields', function () {
        cy.get('input[name="is_active"]').invoke('attr', 'value',0);
        cy.get('input[name="is_active"]').invoke('attr', 'checked',false);
        cy.get('input[name="is_group"]').invoke('attr', 'value',0);
        cy.get('input[name="is_group"]').invoke('attr', 'checked',false);
        cy.get('input[name="name"]').clear().type('newtest');
        cy.get('input[name="sap_code"]').clear().type('123456789');
        cy.get('input[name="phone"]').clear().type('12354689');
        cy.get('input[name="contact_person"]').clear().type('new person');
        cy.get('select[name="country_id"] > option[value="2"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/suppliers');

        cy.get('.alert').should('contain','Supplier Created Successfully')
        cy.server();
        cy.request('get','/api/suppliers/123456789').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('name', 'newtest')
          expect(response.body).to.have.property('sap_code', '123456789')
          expect(response.body).to.have.property('phone', '12354689')
          expect(response.body).to.have.property('country_id', 2)
          expect(response.body).to.have.property('is_active', 0)
          expect(response.body).to.have.property('is_group', 0)
          expect(response.body).to.have.property('contact_person', 'new person')
        });
    })
    it('checks edit permissions',function(){
      cy.exec("php artisan user:remove_permission Supplier-create");
      cy.visit('/suppliers');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.visit('/suppliers/create',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission Supplier-list");
      cy.visit('/suppliers',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
