@extends('main')
@section('container')

<div class='row'>
     <div class='kt-portlet'>
         <div class='kt-portlet__head'>
            <div class='kt-portlet__head-label'>
                <h3 class='kt-portlet__head-title'>
                    Users
                </h3>
            </div>
            @permission('User-create')
            <div class="kt-portlet__head-toolbar">
                <a href="{{ route('users.create') }}" class="btn btn-primary kt-margin-r-10">
                    <i class="la la-plus"></i>
                    <span class="kt-hidden-mobile">Create User</span>
                </a>
            </div>
            @endpermission
         </div>
         <form action='{{route('users.index')}}' class='kt-form kt-form--label-right' method='GET' novalidate>
             <div class='kt-portlet__body'>
                 <div class='kt-section kt-section--first'>
                     <div class='kt-section__body'>
                        <table class='table table-bordered'>
                            <thead>
                                <tr class="table-active">
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Employee Number</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th style="width: 100px;" class="text-center">Status</th>
                                    <th class="text-center" style="width:100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-primary">
                                    <td></td>
                                    <td>
                                        <input type="text" class="form-control" name="full_name" placeholder="Search . . ." autocomplete="off" value="{{request()->input('full_name')}}">
                                    </td>
                                    <td><input type="text" class="form-control" name="employee_number" placeholder="Search . . ." autocomplete="off" value="{{request()->input('employee_number')}}"></td>
                                    <td><input type="text" class="form-control" name="user_name" placeholder="Search . . ." autocomplete="off" value="{{request()->input('user_name')}}"></td>
                                    <td><input type="email" class="form-control" name="email" placeholder="Search . . ." autocomplete="off" value="{{request()->input('email')}}"></td>
                                    <td>
                                        <select class="form-control" name="role">
                                            <option value="">Select Role . . .</option>
                                            @foreach ($roles as $item)
                                                <option value="{{ $item->name }}" {{ request()->input('role') == $item->name ? 'selected':'' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="width: 170px;">
                                        <select class="form-control" name="status">
                                            <option value="">Select Status . . .</option>
                                            <option value="enabled" {{ request()->input('status') == "enabled" ? 'selected':'' }}>Enabled</option>
                                            <option value="disabled" {{ request()->input('status') == "disabled" ? 'selected':'' }}>Disabled</option>
                                        </select>
                                    </td>
                                    <td class="text-center" style="width:120px;">
                                        <button type="submit" class="btn btn-success btn-icon"><i class="fa fa-search"></i></button>
                                        <a href="{{ route('users.index') }}" class="btn btn-danger btn-icon"><i class="fa fa-ban"></i></a>
                                    </td>
                                </tr>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                        <td>
                                            <div class="kt-user-card-v2">
                                                <div class="kt-user-card-v2__pic">
                                                    <img src="{{ $item->avatar }}" alt="">
                                                </div>
                                                <div class="kt-user-card-v2__details">
                                                        {{ $item->full_name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->employee_no }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            @if($item->is_super_admin)
                                                Super Admin
                                            @else
                                                {{ ($item->roles && $item->roles->first()) ? $item->roles->first()->name : 'N/A' }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span style="width: 100px;">
                                                @if($item->is_active)
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Enabled</span>
                                                @else
                                                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Disabled</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($item->is_super_admin && Auth::user()->is_super_admin)
                                                <a href='{{ route('users.edit',['user'=>$item->id]) }}' class='btn btn-secondary'>Edit</a>
                                            @elseif(!$item->is_super_admin)
                                                @permission('User-edit')
                                                <a href='{{ route('users.edit',['user'=>$item->id]) }}' class='btn btn-secondary'>Edit</a>
                                                @endpermission
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                     </div>
                 </div>
             </div>
             <div class='kt-portlet__foot'>
                  <div class='kt-form__actions'>
                     <div class='row'>
                         <div class='col-lg-6'></div>
                         <div class='col-lg-6'>
                            {{$items->appends(request()->query())->links() }}
                         </div>
                     <div>
                 </div>
             </div>
        </form>
     </div>
</div>

@endsection
