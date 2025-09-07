@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Purchase Orders
                    </h3>
                </div>
                @permission('POHeader-create')
                <div class='kt-portlet__head-toolbar'>
                    <a href='{{ route("purchase-orders.import") }}' class='btn btn-success kt-margin-r-10'>
                        <i class='la la-upload'></i>
                        <span class='kt-hidden-mobile'>Import from Excel</span>
                    </a>
                    <a href='{{ route('purchase-orders.create') }}' class='btn btn-primary kt-margin-r-10'>
                        <i class='la la-plus'></i>
                            <span class='kt-hidden-mobile'>Create Purchase Order</span>
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
                                        <th style="width:175px;">PO Number</th>
                                        <th> Supplier</th>
                                        <th>Incoterm</th>
                                        <th class="text-center">Order Date</th>
                                        <th class="text-center">Due Date</th>
                                        <th>Person in Charge</th>
                                        <th class="text-center" style="width: 120px;" >Status</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary">
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control" name="po" placeholder="Search . . ." autocomplete="off" value="{{request()->input('po')}}">
                                        </td>
                                        <td style="max-width: 150px;">
                                            <select class="form-control kt-selectpicker" data-live-search="true"  multiple name="supplier[]">
                                                <option value="">Select Supplier . . .</option>
                                                @foreach ($suppliers as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id,request()->input('supplier',[])) ? 'selected':'' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="max-width: 150px;">
                                            <select class="form-control kt-selectpicker" data-live-search="true" multiple name="incoterm[]">
                                                <option value="">Select Incoterm . . .</option>
                                                @foreach ($incoTerms as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id,request()->input('incoterm',[])) ? 'selected':'' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="width:320px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="orderdatefrom" class="form-control datepicker"  value="{{request()->input('orderdatefrom') }}"
                                                        readonly placeholder="Date From"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="orderdateto" class="form-control datepicker"  value="{{request()->input('orderdateto') }}"
                                                        readonly placeholder="Date To"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width:320px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="duedatefrom" class="form-control datepicker"  value="{{request()->input('duedatefrom') }}"
                                                        readonly placeholder="Date From"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="duedateto" class="form-control datepicker"  value="{{request()->input('duedateto') }}"
                                                        readonly placeholder="Date To"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="max-width: 150px;">
                                            @if(count($persons) > 0)
                                            <select class="form-control kt-selectpicker" data-live-search="true" multiple name="pic[]">
                                                <option value="">Select Person . . .</option>
                                                @foreach ($persons as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id,request()->input('pic',[])) ? 'selected':'' }}>{{ $item->full_name }}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </td>
                                        <td style="width: 170px;">
                                            <select class="form-control" name="status">
                                                <option value="">Select Status . . .</option>
                                                @foreach (App\Models\POHeader::STATUS as $item)
                                                <option value="{{ $item }}" {{ request()->input('status') == $item ? 'selected':'' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center" style="width:120px;">
                                            <button type="submit" class="btn btn-success btn-icon"><i class="fa fa-search"></i></button>
                                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-danger btn-icon"><i class="fa fa-ban"></i></a>
                                        </td>
                                    </tr>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop) }}</td>
                                            <td>{{ $item->po_number }}</td>
                                            <td>{{ optional($item->supplier)->name }}</td>
                                            <td>{{ optional($item->incoterm)->prefix }}</td>
                                            <td class="text-center">{{ $item->order_date }}</td>
                                            <td class="text-center">{{ $item->due_date }}</td>
                                            <td>{{ optional($item->pic)->full_name }}</td>
                                            <td class="text-center">
                                                <span style="width: 120px;">
                                                    @if($item->status == 'Open')
                                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{ $item->status }}</span>
                                                    @else
                                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{ $item->status }}</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class='text-center'>
                                                @permission('POHeader-edit')
                                                <a href='{{ route('purchase-orders.edit',['purchase_order'=>$item->id]) }}' class='btn btn-secondary'>Edit</a>
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
@push('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/po/index.js') }}" type="text/javascript"></script>
@endpush
