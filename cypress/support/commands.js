// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add("login", (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add("drag", { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add("dismiss", { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This is will overwrite an existing command --
// Cypress.Commands.overwrite("visit", (originalFn, url, options) => { ... })

Cypress.Commands.add('assert_user_url_permission_bb',function(permission_name,url){
  cy.exec('php artisan user:remove_permission '+permission_name)
  cy.visit(url,{ failOnStatusCode: false});
  cy.get('.code').should('contain','403')
});


Cypress.Commands.add("visit site", () => {
    cy.visit('/');
    cy.url().should('contain', '/login');
})

Cypress.Commands.add("login", (username = 'test', password = '123456') => {
    cy.get(':nth-child(2) > .form-control').type(username);
    cy.get(':nth-child(3) > .form-control').type(password);
    cy.get('#kt_login_signin_submit').click();
    cy.url().should('contain', '/');
})

Cypress.Commands.add("click master data page", (page_name) => {
    cy.get('button[class="kt-header-mobile__toolbar-toggler"]').click();
    cy.get(page_name).click();
    cy.contains(page_name).click();
})
