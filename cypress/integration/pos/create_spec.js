describe('create PO test', function () {
    before(function(){
        cy.exec('php artisan user:add_permission POHeader-list');
        cy.exec('php artisan user:add_permission POHeader-create');
        cy.exec('php artisan test:create_supplier1');
        cy.exec('php artisan test:create_user_shipping');
        cy.exec('php artisan test:create_user_materialgroup');
        cy.exec('php artisan test:create_raw_material');
    })
    beforeEach(function () {
      cy.visit('/');
      cy.login();
      cy.get('#kt_header_mobile_toggler').should('be.visible');
      cy.get('#kt_header_mobile_toggler').click();
      cy.get('.kt-menu__subnav > .kt-menu__item > .kt-menu__link > .kt-menu__link-text').click({force:true});
      cy.url().should('contain','/purchase-orders');
      cy.get('.kt-portlet__head-toolbar > .btn').click();
      cy.url().should('contain', '/purchase-orders/create');
      cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
    })
    it('check mandatory fields', function () {
        cy.get('#pic > option[value=""]').invoke('attr', 'selected',true);
        cy.get('select[name="basic[supplier_id]"] > option[value=""]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.get('.text-danger').should('contain','The basic.po number field is required.')
        cy.get('.text-danger').should('contain','The Supllier field is required.')
        cy.get('.text-danger').should('contain','The Order Date field is required.')
        cy.get('.text-danger').should('contain','The Due Date field is required.')
        cy.get('.text-danger').should('contain','The Person in Charge field is required.')
    })

    it('check datatype fields', function () {
        cy.get('input[name="detail[1][qty]"]').type('test');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/purchase-orders/create');
        cy.contains('The Qty must be a number.').should('be.visible');
        cy.get('input[name="detail[1][qty]"]').clear().type('0');
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/purchase-orders/create');
        cy.contains('The Qty must be greater than 0.').should('be.visible');
    })
    it('check nullable fields', function () {
        cy.get('input[name="basic[po_number]"]').type('test');
        cy.get('input[name="basic[order_date]"]').then((el)=>{
            el.prop('readonly',false).val('13/11/2020');
        });
        cy.get('input[name="basic[due_date]"').then((el)=>{
            el.prop('readonly',false).val('13/12/2020');
        });
        cy.get('#pic > option[value="100"]').invoke('attr', 'selected',true);
        cy.get('select[name="basic[supplier_id]"] > option[value="99999"]').invoke('attr', 'selected',true);
        cy.get('.row > :nth-child(2) > .btn-success').click();
        cy.url().should('contain', '/purchase-orders');

        cy.get('.alert').should('contain','Purchase Order Created Successfully')
        cy.server();
        cy.request('get','/api/purchase-orders/test').then((response)=>{
          console.log(response.body);
          expect(response.body).to.have.property('po_number', 'test')
          expect(response.body).to.have.property('supplier_id', 99999)
          expect(response.body).to.have.property('order_date', '13\/11\/2020')
          expect(response.body).to.have.property('order_date', '13\/12\/2020')
          expect(response.body).to.have.property('person_in_charge', 1000)
          expect(response.body).to.have.property('status', 'Open')
        });
    })
    it('check all fields', function () {
      cy.get('input[name="basic[po_number]"]').type('test');
      cy.get('input[name="basic[order_date]"]').then((el)=>{
          el.prop('readonly',false).val('13/11/2020');
      });
      cy.get('input[name="basic[due_date]"').then((el)=>{
          el.prop('readonly',false).val('13/12/2020');
      });
      cy.get('#pic > option[value="100"]').invoke('attr', 'selected',true);
      cy.get('select[name="basic[status]"] > option[value="Closed"]').invoke('attr', 'selected',true);
      cy.get('select[name="basic[supplier_id]"] > option[value="99999"]').invoke('attr', 'selected',true);
      cy.get('#pic').trigger('change',{force:true});
      cy.wait(2000);
      cy.get('select[name="detail[1][raw_material_id]"] > option[value="99999"]').invoke('attr', 'selected',true);
      cy.get('input[name="detail[1][qty]"]').clear().type(200)
      cy.get('select[name="detail[1][shipping_unit_id]"] > option[value="1"]').invoke('attr', 'selected',true);
      cy.get('.row > :nth-child(2) > .btn-success').click();
      cy.url().should('contain', '/purchase-orders');

      cy.get('.alert').should('contain','Purchase Order Created Successfully')
      cy.server();
      cy.request('get','/api/purchase-orders/test').then((response)=>{
        console.log(response.body);
        expect(response.body).to.have.property('po_number', 'test')
        expect(response.body).to.have.property('supplier_id', 99999)
        expect(response.body).to.have.property('order_date', '13\/11\/2020')
        expect(response.body).to.have.property('due_date', '13\/12\/2020')
        expect(response.body).to.have.property('person_in_charge_id', 100)
        expect(response.body).to.have.property('status', 'Closed')
        expect(response.body.details[0]).to.have.property('shipping_unit_id', 1)
        expect(response.body.details[0]).to.have.property('qty', '200.00')
        expect(response.body.details[0]).to.have.property('raw_material_id', 99999)

      });
    })

    it('checks create permissions',function(){
      cy.exec("php artisan user:remove_permission POHeader-create");
      cy.assert_user_url_permission_bb('Bank-list','/purchase-orders/create');
      cy.visit('/purchase-orders');
      cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
      cy.exec("php artisan user:remove_permission POHeader-list");
      cy.assert_user_url_permission_bb('Bank-list','/purchase-orders');
    });
    after(function(){
      cy.exec('php artisan migrate:refresh --seed');
    })
})
