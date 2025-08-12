<?php

namespace App\Filters\User;

use App\Filters\AbstractFilter;

class UserIndexFilter extends AbstractFilter{
    protected $filters = [
        'user_name'=>UserNameFilter::class,
        'full_name'=>FullNameFilter::class,
        'email'=>EmailFilter::class,
        'employee_number'=>EmployeeNumberFilter::class,
        'status'=>ActiveFilter::class,
        'role'=>RoleFilter::class
    ];
}
