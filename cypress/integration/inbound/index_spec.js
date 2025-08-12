describe('List shippings', function () {
  before(function(){
    cy.exec('php artisan test:create_user');
    cy.exec('php artisan test:assign_user_material');
    cy.exec("php artisan test:edit_raw_material");
    cy.exec("php artisan test:fake_shipping_basics");
    cy.exec("php artisan user:add_permission ShippingBasic-list");
  });
  beforeEach(function(){
    cy.visit('/');
    cy.url().should('contain', '/login');
    cy.get(':nth-child(2) > .form-control').type('test')
    cy.get(':nth-child(3) > .form-control').type('123456')
    cy.get('#kt_login_signin_submit').click();
  });
  it('visits shippings lists',function(){
    cy.contains('Shipping').click({force:true});
    cy.contains('List Shipping').click({force:true});
    cy.url().should('contain','/shipping');
    cy.get('.btn-secondary').should('not.exist')
    cy.get('.page-link').should('be.visible');
  })
  it('checks permissions',function(){
    cy.exec("php artisan user:remove_permission ShippingBasic-list");
    cy.visit('/shipping',{ failOnStatusCode: false});
    cy.contains('403').should('be.visible');
  })
  after(function(){
    cy.exec("php artisan migrate:refresh && php artisan db:seed");
  });
})
