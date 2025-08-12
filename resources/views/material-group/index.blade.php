@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Material Groups
                    </h3>
                </div>
                @permission('MaterialGroup-create')
                <div class='kt-portlet__head-toolbar'>
                    <a href='{{ route('material-groups.create') }}' class='btn btn-primary kt-margin-r-10'>
                        <i class='la la-plus'></i>
                        <span class='kt-hidden-mobile'>Create Material Group</span>
                    </a>
                </div>
                @endpermission
            </div>
            <form action='' class='kt-form kt-form--label-right' method='POST' >
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <table class='table table-bordered'>
                                <thead>
                                    <tr class="table-active">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th class="text-center">Person In Charge Count</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-center">{{ $item->users->count() }}</td>
                                            <td class='text-center'>
                                                @permission('MaterialGroup-edit')
                                                <a href='{{ route('material-groups.edit',['material_group'=>$item->id]) }}' class='btn btn-secondary'>Edit</a>
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
                                {{ $items->links() }}
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
