describe('edit PO test', function () {
    before(function(){
        cy.exec('php artisan migrate:refresh --seed');
        cy.exec('php artisan user:add_permission POHeader-list');
        cy.exec('php artisan user:add_permission POHeader-edit');
        cy.exec('php artisan test:create_supplier1');
        cy.exec('php artisan test:create_user_shipping');
        cy.exec('php artisan test:create_user_materialgroup');
        cy.exec('php artisan test:create_raw_material');
        cy.exec('php artisan test:create_fake_po');
    })
    beforeEach(function () {
        cy.visit('http://127.0.0.1:8000');
        cy.login();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.get('.kt-menu__subnav > .kt-menu__item > .kt-menu__link > .kt-menu__link-text').click({force:true});
        cy.url().should('contain','/purchase-orders');
        cy.get('.kt-portlet__head-toolbar > .btn').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/purchase-orders/1000/edit');
    })
    it('check required fields', function () {
        cy.get('select[name="basic[supplier_id]"] > option[value=""]').invoke('attr', 'selected',true);
        cy.get('input[name="basic[po_number]"]').clear().type('  ');
        cy.get('input[title="Select Person In Charge ..."]').clear().type('  ');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The Po Number field is required.\n')
        cy.get('.text-danger').should('contain','The Supplier field is required.\n')
        cy.get('.text-danger').should('contain','The Person in Charge field is required.')
    })

    })
    it('check raw materials should not be editable', function () {;
        cy.get('select[name="detail[1][raw_material_id]"] > option[value="99999"]').invoke('attr', 'selected',true);
        cy.get('input[name="edit[100][qty]"]').clear().type(50);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/purchase-orders/1000/edit');


        });
    })

    it('checks edit permissions',function(){
        cy.exec("php artisan user:remove_permission POHeader-edit");
        cy.visit('http://127.0.0.1:8000/purchase-orders/1000/edit');
        cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');

    });


