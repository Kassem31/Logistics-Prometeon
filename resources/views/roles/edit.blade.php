@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
        <div class='kt-portlet__head'>
            <div class='kt-portlet__head-label'>
                <h3 class='kt-portlet__head-title'>
                    Edit Role : {{ $role->name }}
                </h3>
            </div>
        </div>
            <form action='{{ route('roles.update',['role'=>$role->id]) }}' class='kt-form kt-form--label-right' method='POST' id="createForm">
                @csrf
                @method('put')
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <div class='form-group row'>
                                 <label class='col-lg-2 col-form-label'>Role Name:*</label>
                                 <div class='col-lg-6'>
                                     <input type='text' class='form-control @error('name') is-invalid @enderror' placeholder='Role Name' name='name' value="{{ old('name',$role->name) }}" autocomplete="off">
                                     @error('name')
                                         <span class='form-text text-danger'>{{ $message }}</span>
                                     @enderror
                                 </div>
                            </div>
                        </div>
                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <h3 class="kt-section__title">Role Permissions:</h3>
                        <div class='kt-section__body'>
                            <table class='table table-bordered'>
                                <thead class='thead-dark'>
                                    <tr>
                                        <th style="width:350px;" class="align-middle">Permission Name</th>
                                        <th class="align-middle">Permission Actions
                                            <button type="button" id="selectAll" data-selected="0" class="btn btn-success btn-sm">Select All</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $group=>$actions)
                                        <tr>
                                            <td class="align-middle">{{ ucwords($group)}}</td>
                                            <td>
                                                <div class="kt-checkbox-list">
                                                    @foreach ($actions as $action)
                                                        <label class="kt-checkbox kt-checkbox--success">
                                                            <input type="checkbox" name="permissions[]" data-name="{{ $action->name }}" value="{{ $action->id }}" {{ in_array($action->id,$rolePermissions) ? 'checked':'' }}>
                                                             {{ $action->display_name }}
                                                            <span></span>
                                                        </label>
                                                    @endforeach
                                                </div>
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
                                <button type='submit' class='btn btn-success'>Save</button>
                                <a href='{{ route('roles.index') }}' class='btn btn-secondary'>Cancel</a>
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('styles')
    <style>
        .table th, .table td{
            font-size:1.2rem;
        }
        .kt-checkbox-list .kt-checkbox{
            margin-bottom: 15px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/roles/roles.js') }}"></script>
@endpush
