@extends('main')
@section('container')
    <div class='row'>
         <div class='kt-portlet'>
             <div class='kt-portlet__head'>
                 <div class='kt-portlet__head-label'>
                     <h3 class='kt-portlet__head-title'>
                         Create User
                     </h3>
                 </div>
             </div>
             <form action='{{ route('users.store') }}' class='kt-form kt-form--label-right' method='POST' id="userForm" enctype="multipart/form-data" novalidate>
                @csrf
                 <div class='kt-portlet__body'>
                     <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="kt-avatar kt-avatar--outline" id="kt_user_add_avatar">
                                        <div class="kt-avatar__holder" style="background-image: url({{ asset('assets/media/users/default.jpg') }})"></div>
                                        <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="Change avatar">
                                            <i class="fa fa-pen"></i>
                                            <input type="file" name="avatar">
                                        </label>
                                        <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="Cancel avatar">
                                            <i class="fa fa-times"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Full Name:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('full_name') is-invalid @enderror' placeholder='Full Name' name='full_name' autocomplete="off" value="{{ old('full_name') }}" required>
                                    @error('full_name')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>User Name:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('name') is-invalid @enderror' placeholder='User Name' name='name' autocomplete="off" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                         </div>
                         <div class='form-group row'>
                            <label class='col-lg-3 col-form-label'>Password:*</label>
                            <div class='col-lg-6'>
                                <input type='password' class='form-control @error('password') is-invalid @enderror' placeholder='Password' name='password' autocomplete="off" required>
                                @error('password')
                                    <span class='form-text text-danger'>{{ $message }}</span>
                                @enderror
                            </div>
                         </div>
                         <div class='form-group row'>
                            <label class='col-lg-3 col-form-label'>Confirm Password:*</label>
                            <div class='col-lg-6'>
                                <input type='password' class='form-control' placeholder='Confirm Password' name='password_confirmation' required>
                                <span class='form-text text-danger'></span>
                            </div>
                         </div>
                         <div class='form-group row'>
                            <label class='col-lg-3 col-form-label'>Email:</label>
                            <div class='col-lg-6'>
                                <input type='email' class='form-control @error('email') is-invalid @enderror' placeholder='Email' name='email' value="{{ old('email') }}" autocomplete="off">
                                @error('email')
                                    <span class='form-text text-danger'>{{ $message }}</span>
                                @enderror
                            </div>
                         </div>
                         <div class='form-group row'>
                            <label class='col-lg-3 col-form-label'>Employee Number:</label>
                            <div class='col-lg-6'>
                                <input type='text' class='form-control @error('employee_no') is-invalid @enderror' placeholder='Employee Number' name='employee_no' value="{{ old('employee_no') }}" autocomplete="off">
                                @error('employee_no')
                                    <span class='form-text text-danger'>{{ $message }}</span>
                                @enderror
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Role:</label>
                            <div class="col-lg-6">
                                <select class="form-control" name="role_id" id="roleId">
                                    <option value="">Select Role...</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('role_id') ? 'selected' : ''}}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-3 col-form-label">Is Enabled:</label>
                            <div class="col-3">
                                <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                                    <label>
                                        <input type="checkbox" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                     </div>
                 </div>
                 <div class='kt-portlet__foot'>
                      <div class='kt-form__actions'>
                         <div class='row'>
                             <div class='col-lg-6'></div>
                             <div class='col-lg-6'>
                                 <button type='button' class='btn btn-success'  id="saveBtn">Save</button>
                                 <a href='{{ route('users.index') }}' class='btn btn-secondary'>Cancel</a>
                             </div>
                         <div>
                     </div>
                 </div>
            </form>
         </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('assets/js/pages/custom/user/add-user.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/users/user.js') }}"></script>
@endpush
