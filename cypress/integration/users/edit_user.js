describe('Edit Users test', function () {

    beforeEach(function () {
        cy.exec("php artisan test:edit_user");
        cy.exec("php artisan user:add_permission User-list");
        cy.exec("php artisan user:add_permission User-edit");
        cy.visit('http://127.0.0.1:8000');
        cy.url().should('contain', '/login');
        cy.get(':nth-child(2) > .form-control').type('test');
        cy.get(':nth-child(3) > .form-control').type('123456');
        cy.get('#kt_login_signin_submit').click();
        cy.get('#kt_header_mobile_toggler').should('be.visible');
        cy.get('#kt_header_mobile_toggler').click();
        cy.contains('Users').click();
        cy.url().should('contain','/users');
        cy.get('a[href="http://127.0.0.1:8000/users/2/edit"]').click();
        cy.url().should('contain', 'http://127.0.0.1:8000/users/2/edit');

    })
    it('checks required fields', function () {
        cy.get('input[name="full_name"]').clear().type('  ')
        cy.get('input[name="name"]').clear().type('  ')
        cy.get('.btn.btn-success').click();
        cy.url().should('contain','http://127.0.0.1:8000/users/2/edit');
        cy.get('.text-danger').should('contain','The full name field is required.')
        cy.get('.text-danger').should('contain','User Name is Required')
    })

    it('checks minimum length',function(){
        cy.get('input[name="name"]').clear().type('tes')
        cy.get('input[name="password"]').clear().type('12345');
        cy.get('input[name="password_confirmation"]').clear().type('12345');
        cy.get('.btn.btn-success').click();
        cy.get('.text-danger').should('contain','The name must be at least 4 characters.')
        cy.get('.text-danger').should('contain','The password must be at least 6 characters.')
    })

    it('matching password confirmation', function () {
        cy.get('input[name="password"]').type('123456');
        cy.get('input[name="password_confirmation"]').type('123466');
        cy.get('.btn.btn-success').click();
        cy.get('.text-danger').should('contain','The password confirmation does not match.')
    })
    it('checks unique fields',function(){
        cy.exec('php artisan test:create_user2');
        cy.get('input[name="name"]').clear().type('testing2');
        cy.get('input[name="email"]').clear().type('test2@testing.com');
        cy.get('.btn.btn-success').click();
        cy.get('.text-danger').should('contain','The email has already been taken.')
        cy.exec('php artisan test:delete_user2');
    })

    it('check non-required fields and default inactive for non selected role ',function(){
        cy.get('input[name="employee_no"]').clear();
        cy.get('select[name="role_id"]').select('Select Role...')
        cy.get('input[name="email"]').clear();
        cy.get('.btn.btn-success').click();
        cy.get('.swal2-popup').should('be.visible');
        cy.get('.swal2-confirm.swal2-styled').click();
        cy.request('get','/api/user/test').then((response)=>{
            expect(response.body).to.have.property('is_active', false)
        })
        cy.exec("php artisan test:edit_user");
    })


})

