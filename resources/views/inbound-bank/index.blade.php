@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Inbound Banks
                    </h3>
                </div>
            </div>
            <form action='{{ route('inbound-banks.index') }}' class='kt-form kt-form--label-right' method='get' >
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <table class='table table-bordered'>
                                <thead>
                                    <tr class="table-active">
                                        <th class="text-center" style="width:75px;">#</th>
                                        <th>Inbound No</th>
                                        <th>PO Number</th>
                                        <th class="text-center">Person in Charge</th>
                                        <th class='text-center' style='width:140px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary">
                                        <td ></td>
                                        <td >
                                            <input type="text" class="form-control" name="inbound" placeholder="Search . . ." autocomplete="off" value="{{request()->input('inbound')}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="po" placeholder="Search . . ." autocomplete="off" value="{{request()->input('po')}}">
                                        </td>
                                        <td></td>
                                        <td class="text-center">
                                            <button type="submit" class="btn btn-success btn-icon"><i class="fa fa-search"></i></button>
                                            <a href="{{ route('inbound-banks.index') }}" class="btn btn-danger btn-icon"><i class="fa fa-ban"></i></a>
                                        </td>
                                    </tr>
                                    @foreach ($inbounds as $item)
                                        <tr>
                                            <td class="kt-font-bolder align-middle text-center">{{App\Helpers\Utils::rowNumber($inbounds,$loop)}}</td>
                                            <td class="kt-font-bolder align-middle">{{ $item->inbound_no }}</td>
                                            <td class="kt-font-bolder align-middle">{{ optional($item->po_header)->po_number }}</td>
                                            <td class="kt-font-bolder align-middle">{{ optional(optional($item->po_header)->pic)->full_name }}</td>
                                            <td class='text-center'>
                                                @permission('ShippingClearance-edit')
                                                <a href='{{ route('inbound-banks.edit',['inbound_bank'=>$item->id]) }}' class='btn btn-danger'>Edit</a>
                                                @endpermission
                                                @permission('ShippingClearance-list')
                                                <a href='{{ route('inbound-banks.show',['inbound_bank'=>$item->id]) }}' class='btn btn-success'>Show</a>
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
                                {{ $inbounds->appends(request()->query())->links() }}
                            </div>
                        <div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/shipping/index.js') }}" type="text/javascript"></script>
@endpush
