describe('create shipping', function () {
    beforeEach(function () {

        cy.exec('php artisan user:add_permission ShippingBasic-list');
        cy.exec('php artisan user:add_permission ShippingBasic-create');
        cy.exec('php artisan user:add_permission ShippingBasic-edit');
        cy.visit('/');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test');
        cy.get(':nth-child(3) > .form-control').type('123456');
        cy.get('#kt_login_signin_submit').click();
        cy.visit('http://127.0.0.1:8000/inbound/1000/edit');
        cy.contains('Shipping Details').click();
        cy.wait(2000)
        cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    before(function(){
        cy.exec('php artisan migrate:fresh --seed');
        cy.exec('php artisan test:create_raw_material');
        cy.exec('php artisan test:create_supplier1');
        cy.exec('php artisan test:create_user_material');
        cy.exec('php artisan test:create_user_materialgroup1');
        cy.exec('php artisan test:create_fake_po');
        cy.exec("php artisan test:create_fake_inbound");
        cy.exec('php artisan test:create_forwarder');
        cy.exec("php artisan test:create_insurance");
        cy.exec("php artisan test:create_container_size");
    })

    it("check required fields",function () {
        cy.contains('Save').click();
        cy.contains('Shipping Containers Details :\n').should('be.visible');
    })

    it("check shipping line field",function () {
        cy.get('input[name="container[1][container_no]"]').type('100',{force:true});
        cy.get('select[name="container[1][load_type_id]"]').select('Full Container Loading',{force:true});
        cy.get('select[name="container[1][container_size_id]"]').select('300',{force:true});

        cy.get('select[id="shippingLine"]').select('Other',{force:true});
        cy.contains('Save').click();
        cy.contains('Other Shipping Line is Required when Other is selected').should('be.visible');
        cy.contains('Shipping Containers Details :\n').should('be.visible');
        cy.get('select[id="originCountry"]').select('Egypt',{force:true});
        cy.get('select[name="shipping[loading_port_id]"]').select('Alexandria',{force:true});
        cy.get('select[name="shipping[inco_term_id]"').select('Cost and Freight',{force:true});
        cy.get('select[name="shipping[inco_forwarder_id]"').select('testing',{force:true});
        cy.get('input[name="shipping[rate]"]').type('10',{force:true});
        cy.get('select[name="shipping[currency_id]"]').select('EGP',{force:true});
        cy.get('input[name="shipping[vessel_name]"]').type('10',{force:true});
        cy.get('input[name="shipping[bl_number]"]').type('test',{force:true});
        cy.get('select[name="shipping[insurance_company_id]"]').select('testing',{force:true});
              cy.get('input[id="insurance_date"]').then((el)=>{
            el.prop('readonly',false).val('1/1/2020');
        });
        cy.get('input[name="shipping[insurance_cert_no]"]').type('test',{force:true});
        cy.get('select[id="shippingLine').select('Other',{force:true});
        cy.get('#otherShippingLine > .col-lg-6 > .form-control').type('test',{force:true});
        cy.contains('Save').click();
        cy.contains('Clearance Details').click();
        cy.contains('Clearance Details :').should('be.visible');

        })


})
