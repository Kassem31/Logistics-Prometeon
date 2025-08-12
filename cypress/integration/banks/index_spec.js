describe('List Banks', function () {
  before(function(){
    cy.exec("php artisan test:fake_banks");
    cy.exec("php artisan user:add_permission Bank-list");
  });
  beforeEach(function(){
    cy.visit('/');
    cy.login();
  });
  it('visits pos lists',function(){
    cy.get('#kt_header_mobile_toggler').should('be.visible');
    cy.get('#kt_header_mobile_toggler').click();
    cy.contains('Banks').click();
    cy.get('.kt-menu__subnav > .kt-menu__item > .kt-menu__link > .kt-menu__link-text').click({force:true});
    cy.url().should('contain','/banks');
    cy.get('.btn-secondary').should('not.exist')
    cy.get('.page-link').should('be.visible');
  })
  it('checks permissions',function(){
    cy.exec("php artisan user:remove_permission Bank-list");
    cy.assert_user_url_permission_bb('Bank-list','/banks');
  })
  after(function(){
    cy.exec("php artisan migrate:refresh && php artisan db:seed");
  });
})
