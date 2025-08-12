@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Insurance Companies
                    </h3>
                </div>
                @permission('InsuranceCompany-create')
                <div class='kt-portlet__head-toolbar'>
                    <a href='{{ route('insurance-companies.create') }}' class='btn btn-primary kt-margin-r-10'>
                        <i class='la la-plus'></i>
                            <span class='kt-hidden-mobile'>Create Insurance Company</span>
                    </a>
                </div>
                @endpermission
            </div>
            <form action='' class='kt-form kt-form--label-right' method='GET' novalidate>
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <table class='table table-bordered'>
                                <thead>
                                    <tr class="table-active">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th class="text-center" style="width: 120px;" >Is Active</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary">
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control" name="name" placeholder="Search . . ." autocomplete="off" value="{{request()->input('name')}}">
                                        </td>
                                        <td style="width: 170px;">
                                            <select class="form-control" name="status">
                                                <option value="">Select Status . . .</option>
                                                <option value="active" {{ request()->input('status') == "active" ? 'selected':'' }}>Active</option>
                                                <option value="inactive" {{ request()->input('status') == "inactive" ? 'selected':'' }}>In-Active</option>
                                            </select>
                                        </td>
                                        <td class="text-center" style="width:120px;">
                                            <button type="submit" class="btn btn-success btn-icon"><i class="fa fa-search"></i></button>
                                            <a href="{{ route('insurance-companies.index') }}" class="btn btn-danger btn-icon"><i class="fa fa-ban"></i></a>
                                        </td>
                                    </tr>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop) }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <span style="width: 120px;">
                                                    @if($item->is_active)
                                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>
                                                    @else
                                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">In-Active</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class='text-center'>
                                                @permission('InsuranceCompany-edit')
                                                <a href='{{ route('insurance-companies.edit',['insurance_company'=>$item->id]) }}' class='btn btn-secondary'>Edit</a>
                                                @endpermission
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
