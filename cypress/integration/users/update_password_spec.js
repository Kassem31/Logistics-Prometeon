beforeEach(function () {
  cy.visit('/');
  cy.wait(2000);
  cy.url().should('contain', '/login');
  cy.get(':nth-child(2) > .form-control').type('test')
  cy.get(':nth-child(3) > .form-control').type('123456')
  cy.get('#kt_login_signin_submit').click();
  cy.get('.flaticon-more-1').should('be.visible');
  cy.get('.kt-header__topbar-wrapper > img').click({force:true});
  cy.get('.kt-notification__item-title').click();
  cy.url().should('contain', '/user/reset-password');
  cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
})
describe('update profile settings',function(){
  it('checks password length',function(){
    cy.get(':nth-child(1) > .col-lg-6 > .form-control').clear().type('123');
    cy.get(':nth-child(2) > .col-lg-6 > .form-control').clear().type('123');
    cy.get('.btn-success').click();
    cy.get('.text-danger').should('contain','The password must be at least 6 characters.');
  })
  it('checks password confirmation',function(){
    cy.get(':nth-child(1) > .col-lg-6 > .form-control').clear().type('123456');
    cy.get(':nth-child(2) > .col-lg-6 > .form-control').clear().type('12345');
    cy.get('.btn-success').click();
    cy.get('.text-danger').should('contain','The password confirmation does not match.');
  })
  it('checks password update button',function(){
    cy.get(':nth-child(1) > .col-lg-6 > .form-control').clear().type('123456789');
    cy.get(':nth-child(2) > .col-lg-6 > .form-control').clear().type('123456789');
    cy.get('.btn-success').click();
    cy.get('.kt-header__topbar-wrapper > img').click({force:true});
    cy.get('.kt-notification__custom > .btn').click();
    cy.url().should('contain', '/login');
    cy.get(':nth-child(2) > .form-control').type('test')
    cy.get(':nth-child(3) > .form-control').type('123456789')
    cy.get('#kt_login_signin_submit').click();
    cy.url();
    cy.get('.kt-header-mobile__logo > a > img').should('be.visible');
  })
  after(function(){
    cy.exec('php artisan migrate:refresh');
    cy.exec('php artisan db:seed');
  });
});
