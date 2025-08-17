@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Inbounds
                    </h3>
                </div>
                @permission('ShippingBasic-create')
                <div class='kt-portlet__head-toolbar'>
                    <a href='{{ route('inbound.create') }}' class='btn btn-primary kt-margin-r-10'>
                        <i class='la la-plus'></i>
                        <span class='kt-hidden-mobile'>Create Inbound</span>
                    </a>
                </div>
                @endpermission
            </div>
            <form action='{{ route('inbound.index') }}' class='kt-form kt-form--label-right' method='get' >
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <div class='kt-section__body'>
                            <table class='table table-bordered'>
                                <thead>
                                    <tr class="table-active">
                                        <th>#</th>
                                        <th>Inbound No</th>
                                        <th>PO Number</th>
                                        <th class="text-center">Person in Charge</th>
                                        <th class="text-center">ATS</th>
                                        <th class="text-center">ATA</th>
                                        <th class="text-center">Clearance Days</th>
                                        <th class="text-center">Current Step</th>
                                        <th class="text-center">Shippment Status</th>
                                        <th class="text-center">Progress</th>
                                        <th class='text-center' style='width:140px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary">
                                        <td></td>
                                        <td style="width:120px;">
                                            <input type="text" class="form-control" name="inbound" placeholder="Search . . ." autocomplete="off" value="{{request()->input('inbound')}}">
                                        </td>
                                        <td style="width:120px;">
                                            <input type="text" class="form-control" name="po" placeholder="Search . . ." autocomplete="off" value="{{request()->input('po')}}">
                                        </td>
                                        <td style="max-width: 150px;">
                                            @if(count($persons) > 0)
                                            <select class="form-control kt-selectpicker" data-live-search="true" multiple name="pic[]">
                                                @foreach ($persons as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id,request()->input('pic',[])) ? 'selected':'' }}>{{ $item->full_name }}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </td>
                                        <td style="width:295px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="atsfrom" class="form-control datepicker"  value="{{request()->input('atsfrom') }}"
                                                        readonly placeholder="From"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="atsto" class="form-control datepicker"  value="{{request()->input('atsto') }}"
                                                        readonly placeholder="To"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width:295px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="atafrom" class="form-control datepicker"  value="{{request()->input('atafrom') }}"
                                                        readonly placeholder="From"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="atato" class="form-control datepicker"  value="{{request()->input('atato') }}"
                                                        readonly placeholder="To"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td style="width:110px;">
                                            <select class="form-control kt-selectpicker" data-live-search="true" multiple name="step[]">
                                                @foreach ($steps as $key=>$item)
                                                    <option value="{{ $key }}" {{ in_array($key,request()->input('step',[])) ? 'selected':'' }}>{{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="min-width:90px;">
                                            <select class="form-control kt-selectpicker" data-live-search="true" multiple name="status[]">
                                                @foreach (['Arrived','In-Transit','Unknown'] as $item)
                                                    <option value="{{ $item }}" {{ in_array($item,request()->input('status',[])) ? 'selected':'' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td></td>
                                        <td class="text-center">
                                            <button type="submit" class="btn btn-success btn-icon"><i class="fa fa-search"></i></button>
                                            <a href="{{ route('inbound.index') }}" class="btn btn-danger btn-icon"><i class="fa fa-ban"></i></a>
                                        </td>
                                    </tr>
                                    @foreach ($inbounds as $item)
                                        <tr>
                                            <td class="kt-font-bolder align-middle">{{App\Helpers\Utils::rowNumber($inbounds,$loop)}}</td>
                                            <td class="kt-font-bolder align-middle">{{ $item->inbound_no }}</td>
                                            <td class="kt-font-bolder align-middle">{{ optional($item->po_header)->po_number }}</td>
                                            <td class="kt-font-bolder align-middle">{{ optional(optional($item->po_header)->pic)->full_name }}</td>
                                            <td class="kt-font-bolder align-middle text-center">{{ optional($item->booking)->ats }}</td>
                                            <td class="kt-font-bolder align-middle text-center">{{ optional($item->booking)->ata }}</td>
                                            <td class="kt-font-bolder align-middle text-center">{{ $item->clearance_days }}</td>
                                            <td class="kt-font-bolder align-middle text-center">
                                                <span class="kt-badge kt-badge--inline kt-badge--info">{{ $item->getCurrentStatus() }}</span>
                                            </td>
                                            <td class="kt-font-bolder align-middle text-center">
                                                    <span class="kt-badge kt-badge--inline kt-badge--{{ $item->getShippmentStatusStyle() }}">{{ $item->getShippmentStatus() }}</span>
                                                </td>
                                            <td class="kt-font-bolder align-middle">
                                                <div class="progress progress-lg">
                                                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: {{ $item->getPercent() }}%;" aria-valuenow="{{ $item->getPercent() }}" aria-valuemin="0" aria-valuemax="100">{{ $item->getPercentDisplay() }}</div>
                                                </div>
                                            </td>

                                            <td class='text-center'>
                                                @permission('ShippingBasic-edit')
                                                <a href='{{ route('inbound.edit',['inbound'=>$item->id]) }}' class='btn btn-danger'>Edit</a>
                                                @endpermission
                                                @permission('ShippingBasic-list')
                                                <a href='{{ route('inbound.show',['inbound'=>$item->id]) }}' class='btn btn-success'>Show</a>
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
