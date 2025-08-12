describe('booking data in shipping', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission ShippingBasic-list');
        cy.exec('php artisan user:add_permission ShippingBasic-create');
        cy.exec('php artisan user:add_permission ShippingBasic-edit');
        cy.visit('/');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test');
        cy.get(':nth-child(3) > .form-control').type('123456');
        cy.get('#kt_login_signin_submit').click();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('List Shipping').click({ force: true });
        cy.get(':nth-child(2) > :nth-child(10) > .btn').should('be.visible').click()
        cy.url().should('contain','/shipping/1000/edit');
        cy.get('.kt-wizard-v2__nav-items > :nth-child(2)').click();
        cy.wait(2000)
        cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    before(function(){
      cy.exec('php artisan test:create_raw_material');
      cy.exec('php artisan test:create_supplier1');
      cy.exec('php artisan test:create_user_shipping');
      cy.exec('php artisan test:create_user_materialgroup');
      cy.exec('php artisan test:create_shipping')
      cy.exec('php artisan test:create_container_size');
      cy.exec("php artisan test:edit_container_load_type");
      cy.exec('php artisan test:edit_inco_forwarder');
      cy.exec('php artisan test:activate_inco_forwarder');
      cy.exec("php artisan test:edit_inco_term");
      cy.exec('php artisan test:create_shipping_basic')
    })
    it('checks calculation',function(){
      cy.get('input[name="book[ets]"]').then((el)=>{
          el.prop('readonly',false).val('13/11/2019');
      });
      cy.get('input[name="book[eta]"]').then((el)=>{
          el.prop('readonly',false).val('12/12/2019');
      });
      cy.wait(2000);
  //    cy.get('#ett').invoke('val').should('contain', '29')
      cy.get('input[name="book[ats]"]').then((el)=>{
          el.prop('readonly',false).val('15/11/2019');
      });

      cy.get('input[name="book[ata]"]').then((el)=>{
          el.prop('readonly',false).val('31/12/2019');
      });
      cy.wait(2000);
    //  cy.get('input[name="book[att]"]').invoke('val').should('contain', '48')

      cy.get('input[name="book[ata]"]').then((el)=>{
          el.prop('readonly',false);
      }).clear();
      cy.wait(2000);
  //    cy.get('input[name="book[att]"]').invoke('val').should('contain', '26')


        cy.get('#deviation').invoke('val').should('contain', '28');
        cy.get('#sailingDays').invoke('val').should('contain', '31');


    })
    it('checks logs',function(){
      cy.get('input[name="book[eta]"]').then((el)=>{
          el.prop('readonly',false).val('13/11/2019');
      });
      cy.get('input[name="book[ata]"]').then((el)=>{
          el.prop('readonly',false).val('12/12/2019');
      });
      cy.get('button[type="submit"]').click();
      cy.server();
      // cy.request('get','/api/shipping/document_cycle/456123').then((response)=>{
      //   expect(response.body).to.equal('1')
      // });
      cy.get('.kt-wizard-v2__nav-items > :nth-child(2)').click();
      cy.get('.log-btn').should('be.visible');
    })
    it('checks next step phase',function(){
      cy.get('button[type="submit"]').click();
      cy.get('.kt-wizard-v2__nav-items > :nth-child(2)').click();
      cy.get('input[name="book[ats]"]').then((el)=>{
          el.prop('readonly',false).val('15/12/2019');
      });
      cy.get('button[type="submit"]').click();

      cy.server();

      cy.request('get','/api/shipping/getInfo/booking_data/456123').then((response)=>{
        console.log(response.body);
        expect(response.body[0]).to.have.property('ats', '15\/12\/2019')
      });
    })
    it('checks create permissions',function(){
      cy.exec('php artisan user:remove_permission ShippingBasic-create');
      cy.exec('php artisan user:remove_permission ShippingBasic-edit');
      cy.visit('/shipping');
      cy.get('.btn-secondary').should('not.exist');
      cy.visit('/shipping/1000/edit',{ failOnStatusCode: false});
      cy.get('.code').should('contain','403')
      cy.exec("php artisan user:remove_permission ShippingBasic-list");
      cy.visit('/shipping',{ failOnStatusCode: false});
      cy.contains('403').should('be.visible');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
