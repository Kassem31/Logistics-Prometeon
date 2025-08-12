/// <reference types="Cypress" />
beforeEach(function () {
  cy.exec("php artisan user:add_permission User-list");
  cy.exec("php artisan user:add_permission User-create");
  cy.visit('/');
  cy.wait(2000);
  cy.url().should('contain', '/login');
  cy.get(':nth-child(2) > .form-control').type('test')
  cy.get(':nth-child(3) > .form-control').type('123456')
  cy.get('#kt_login_signin_submit').click();
  cy.get('#kt_header_mobile_toggler').should('be.visible');
  cy.get('#kt_header_mobile_toggler').click();
  cy.contains('Users').click();
  cy.url().should('contain','/users');
  cy.get('.kt-portlet__head-toolbar > .btn').click();
  cy.url().should('contain', '/users/create');
  cy.get('div.kt-portlet > form').invoke('attr', 'noValidate','true');
})
before(function(){
  cy.exec('php artisan test:create_user');

})
describe('Create user', function () {
  it('checks required fields', function () {
    cy.get('div.kt-portlet > form').should('have.attr', 'noValidate','true');
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.get('.swal2-confirm').click();
    cy.url().should('contain','/users/create');
    cy.get('.text-danger').should('contain','The full name field is required.')
    cy.get('.text-danger').should('contain','User Name is Required')
    cy.get('.text-danger').should('contain','The password field is required.')
  })
  it('checks username minimum length',function(){
    cy.get('input[name="full_name"]').type('Tonaguy');
    cy.get('input[name="name"]').type('q');
    cy.get('input[name="password"]').type('Tonaguy');
    cy.get('input[name="password_confirmation"]').type('Tonaguy');
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.get('.swal2-confirm').click();
    cy.url().should('contain','/users/create');
    cy.get('.text-danger').should('contain','The name must be at least 4 characters.')
  })
  it('checks password length',function(){
    cy.get('input[name="full_name"]').type('Tonaguy');
    cy.get('input[name="name"]').type('q');
    cy.get('input[name="password"]').type('123');
    cy.get('input[name="password_confirmation"]').type('123');
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.get('.swal2-confirm').click();
    cy.get('.text-danger').should('contain','The password must be at least 6 characters.')
  })
  it('checks password match',function(){
    cy.get('input[name="full_name"]').type('Tonaguy');
    cy.get('input[name="name"]').type('q');
    cy.get('input[name="password"]').type('123456');
    cy.get('input[name="password_confirmation"]').type('123466');
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.get('.swal2-confirm').click();
    cy.get('.text-danger').should('contain','The password confirmation does not match.')
  })
  it('checks unique email',function(){

    cy.get('input[name="email"]').type('test@testing.com');
    cy.get('input[name="full_name"]').type('Tonaguy');
    cy.get('input[name="name"]').type('q');
    cy.get('input[name="password"]').type('123456');
    cy.get('input[name="password_confirmation"]').type('123456');
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.get('.swal2-confirm').click();
    cy.get('.text-danger').should('contain','The email has already been taken.')
  })
  it('checks unique username',function(){
    cy.get('input[name="full_name"]').type('Tonaguy');
    cy.get('input[name="name"]').type('admin');
    cy.get('input[name="password"]').type('123456');
    cy.get('input[name="password_confirmation"]').type('123456');
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.get('.swal2-confirm').click();
    cy.get('.text-danger').should('contain','User Name has already been taken')
  })
  it('checks unassigned role warning',function(){
    cy.get('input[name="full_name"]').type('Tonaguy');
    cy.get('input[name="name"]').type('useruser');
    cy.get('input[name="password"]').type('123456');
    cy.get('input[name="password_confirmation"]').type('123456');
    cy.get('.text-danger').invoke('attr', 'selected',true);
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.get('.swal2-popup').should('be.visible')
  })
  it('checks passing nullable fields',function(){
    cy.get('input[name="full_name"]').type('Tonaguy');
    cy.get('input[name="name"]').type('tonaguy');
    cy.get('input[name="password"]').type('123456');
    cy.get('input[name="password_confirmation"]').type('123456');
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.get('.swal2-confirm').click();
    cy.url().should('contain','/users');
    cy.get('.alert').should('contain','User Created Successfully')
    cy.server();
    cy.request('get','/api/user/tonaguy').then((response)=>{
      expect(response.body).to.have.property('full_name', 'Tonaguy')
      expect(response.body).to.have.property('name', 'tonaguy')
      expect(response.body).to.have.property('is_active', false)
    });
  })
  it('checks all fields',function(){
    cy.get('input[name="full_name"]').type('Tonaguy');
    cy.get('input[name="name"]').type('tonagyy');
    cy.get('input[name="password"]').type('123456');
    cy.get('input[name="password_confirmation"]').type('123456');
    cy.get('input[name="email"]').type('tonaguy@test.com');
    cy.get('#roleId > option[value="1"]').invoke('attr', 'selected',true);
    cy.get('input[name="is_active"]').invoke('attr', 'value','1');
    cy.get('input[name="employee_no"]').type(123);
    cy.get('#saveBtn').click();
    cy.wait(1000);
    cy.url().should('contain','/users');
    cy.get('.alert').should('contain','User Created Successfully')
    cy.server();
    cy.request('get','/api/user/tonagyy').then((response)=>{
      console.log(response.body);
      expect(response.body).to.have.property('full_name', 'Tonaguy')
      expect(response.body).to.have.property('name', 'tonagyy')
      expect(response.body).to.have.property('employee_no', '123')
      expect(response.body).to.have.property('email','tonaguy@test.com')
      expect(response.body.roles[0]).to.have.property('id', 1)
      expect(response.body).to.have.property('is_active', false)
    });
  })
  it('checks create permissions',function(){
    cy.exec("php artisan user:remove_permission User-create");
    cy.visit('/users');
    //cy.get('.kt-portlet__head-toolbar > .btn').should('not.exist');
    cy.visit('/users/create',{ failOnStatusCode: false});
    cy.get('.code').should('contain','403')
    cy.exec("php artisan user:remove_permission User-list");
    cy.visit('/users',{ failOnStatusCode: false});
    cy.contains('403').should('be.visible');
  });
  after(function(){
    cy.exec("php artisan migrate:refresh && php artisan db:seed");
  });
})
