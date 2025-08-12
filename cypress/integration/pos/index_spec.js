describe('List POs', function () {
  before(function(){
    cy.exec('php artisan test:create_raw_material');
    cy.exec('php artisan test:create_user_materialgroup');
    cy.exec('php artisan test:edit_supplier')
    cy.exec("php artisan test:fake_pos");
    cy.exec("php artisan user:add_permission POHeader-list");
  });
  beforeEach(function(){
    cy.visit('/');
    cy.login();
  });
  it('visits pos lists',function(){
    cy.get('#kt_header_mobile_toggler').click();
    cy.contains('Inbound').click();
    cy.get('.kt-menu__subnav > .kt-menu__item > .kt-menu__link > .kt-menu__link-text').click({force:true});
    cy.url().should('contain','/purchase-orders');
    cy.get('.btn-secondary').should('not.exist')
    cy.get('.page-link').should('be.visible');
  })
  it('checks permissions',function(){
    cy.exec("php artisan user:remove_permission POHeader-list");
    cy.assert_user_url_permission_bb('POHeader-list','/purchase-orders');
  })
  after(function(){
    cy.exec("php artisan migrate:refresh && php artisan db:seed");
  });
})
