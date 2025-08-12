describe('List users', function () {
  before(function(){
    cy.exec("php artisan test:fake_inco_forwarders");
    cy.exec("php artisan user:add_permission IncoForwarder-list");
  });
  beforeEach(function(){
    cy.visit('/');
    cy.url().should('contain', '/login');
    cy.get(':nth-child(2) > .form-control').type('test')
    cy.get(':nth-child(3) > .form-control').type('123456')
    cy.get('#kt_login_signin_submit').click();
  });
  it('visits inco forwarders lists',function(){
    cy.get('#kt_header_mobile_toggler').should('be.visible');
    cy.get('#kt_header_mobile_toggler').click();
    cy.contains('Inco Forwarders').click();
    cy.url().should('contain','/inco-forwarders');
    cy.get('.btn-secondary').should('not.exist')
    cy.get('.page-link').should('be.visible');
  })
  it('checks permissions',function(){
    cy.exec("php artisan user:remove_permission IncoForwarder-list");
    cy.visit('/inco-forwarders',{ failOnStatusCode: false});
    cy.contains('403').should('be.visible');
  })
  after(function(){
    cy.exec("php artisan migrate:refresh && php artisan db:seed");
  });
})
