beforeEach(function () {
    cy.visit('/');
    cy.url().should('contain', '/login');
})
describe('Login', function () {
    it('shows error for both required fields', function () {
        cy.get('#kt_login_signin_submit').click();
        cy.url().should('contain', '/login');
    })
    it('checks empty password field', function () {
        cy.get(':nth-child(2) > .form-control').type('admin')
        cy.contains('Sign In').click();
        cy.url().should('contain', '/login');
    })

    it('checks empty user name field', function () {
        cy.get(':nth-child(3) > .form-control').type('666666');
        cy.get('#kt_login_signin_submit').click();
        cy.url().should('contain', '/login');
    })

    it('checks valid user name and invalid password', function () {
        cy.get(':nth-child(2) > .form-control').type('admin')
        cy.get(':nth-child(3) > .form-control').type('666666')
        cy.get('#kt_login_signin_submit').click()
        cy.url().should('contain', '/login');
        cy.get('.alert').should('contain', 'Invalid Username or password.')
    })
    it('checks invalid user name and invalid password', function () {
        cy.get(':nth-child(2) > .form-control').type('amin')
        cy.get(':nth-child(3) > .form-control').type('666666')
        cy.get('#kt_login_signin_submit').click()
        cy.url().should('contain', '/login');
        cy.get('.alert').should('contain', 'Invalid Username or password.')
    })

    it('checks successful Login with valid user name and valid password', function () {
        cy.get(':nth-child(2) > .form-control').type('admin')
        cy.get(':nth-child(3) > .form-control').type('123456')
        cy.get('#kt_login_signin_submit').click()
        cy.url().should('contain', '/');
        cy.get('.flaticon-more-1').click();
        cy.get('.kt-header__topbar-wrapper > img').click();
        cy.get('.kt-notification__custom > .btn').click();
        cy.url().should('contain', '/login');
    })

    it('password is asterisks', function () {
        cy.get(':nth-child(3) > .form-control').invoke('attr', 'type').should('contain', 'password')
    })


})
