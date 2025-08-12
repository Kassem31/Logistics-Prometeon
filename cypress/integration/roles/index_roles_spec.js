describe('List users', function () {
  before(function(){
    cy.exec("php artisan test:fake_roles");
    cy.exec("php artisan user:add_permission Role-list");
  });
  beforeEach(function(){
    cy.visit('/');
    cy.url().should('contain', '/login');
    cy.get(':nth-child(2) > .form-control').type('test')
    cy.get(':nth-child(3) > .form-control').type('123456')
    cy.get('#kt_login_signin_submit').click();
  });
  it('visits roles lists',function(){
    cy.get('#kt_header_mobile_toggler').should('be.visible');
    cy.get('#kt_header_mobile_toggler').click();
    cy.contains('Roles').click();
    cy.url().should('contain','/roles');
    cy.get('.btn-secondary').should('not.exist')
    cy.get('.page-link').should('be.visible');
  })
  it('checks permissions',function(){
    cy.exec("php artisan user:remove_permission Role-list");
    cy.visit('/roles',{ failOnStatusCode: false});
    cy.contains('403').should('be.visible');
  })
  after(function(){
    cy.exec("php artisan migrate:refresh && php artisan db:seed");
  });
})
