describe('List users', function () {
  before(function(){
    cy.exec("php artisan test:fake_ports");
    cy.exec("php artisan user:add_permission Port-list");
  });
  beforeEach(function(){
    cy.visit('/');
    cy.url().should('contain', '/login');
    cy.get(':nth-child(2) > .form-control').type('test')
    cy.get(':nth-child(3) > .form-control').type('123456')
    cy.get('#kt_login_signin_submit').click();
  });
  it('visits ports lists',function(){
    cy.get('#kt_header_mobile_toggler').should('be.visible');
    cy.get('#kt_header_mobile_toggler').click();
    cy.contains('Ports').click();
    cy.url().should('contain','/ports');
    cy.get('.btn-secondary').should('not.exist')
    cy.get('.page-link').should('be.visible');
  })
  it('checks permissions',function(){
    cy.exec("php artisan user:remove_permission Port-list");
    cy.visit('/ports',{ failOnStatusCode: false});
    cy.contains('403').should('be.visible');
  })
  after(function(){
    cy.exec("php artisan migrate:refresh && php artisan db:seed");
  });
})
