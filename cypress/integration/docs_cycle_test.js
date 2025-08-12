describe('docs cycle test', function () {
    beforeEach(function () {
        cy.exec('php artisan user:add_permission ShippingBasic-list');
        cy.exec('php artisan user:add_permission ShippingBasic-create');
        cy.exec('php artisan user:add_permission ShippingBasic-edit');
        cy.exec('php artisan user:add_permission shipping_bookings');
        cy.exec('php artisan user:add_permission shipping_documents');
        cy.exec('php artisan test:create_user_shipping');
        cy.exec('php artisan test:create_raw_material');
        cy.exec('php artisan test:create_supplier1');
        cy.exec('php artisan test:create_user_materialgroup');
        cy.exec('php artisan test:create_shipping_test_docs');
        cy.exec('php artisan test:create_shipping_test_docs_2');
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click({ force: true });
        cy.get('.kt-header-mobile__toolbar-toggler').click({ force: true });
        cy.contains('List Shipping').click({ force: true });
        cy.url().should('contain', 'http://127.0.0.1:8000/shipping');
        cy.get('a[href="http://127.0.0.1:8000/shipping/9999/edit"]').click({force:true});
        cy.contains('Documents Cycle').click();
    })


    it("check sequential fields",function () {
        // cy.get('input[name="basic[order_date]"]').then((el)=>{
        //     el.prop('readonly',false);
        // }).type('10/10/2019',{force:true});
        //
        // cy.get('#dueDate').then((el)=>{
        //     el.prop('readonly',false).val('11/10/2019');
        // });
        // cy.get('select[name="basic[person_in_charge_id]"]').select('shipping test',{force:true});
        // cy.contains('Save').click();
        // cy.contains('Shipping Created Successfully').should('be.visible');
    })


    afterEach(function(){
       cy.exec('php artisan migrate:refresh --seed');
    })


})
