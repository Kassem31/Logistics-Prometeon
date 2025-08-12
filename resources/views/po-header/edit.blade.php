@extends('main')
@section('container')
    <div class='row'>
        <div class='kt-portlet'>
            <div class='kt-portlet__head'>
                <div class='kt-portlet__head-label'>
                    <h3 class='kt-portlet__head-title'>
                        Edit Purchase Order
                    </h3>
                </div>
            </div>
            {{-- {{ dd($errors) }} --}}
            <form action='{{ route('purchase-orders.update',['purchase_order'=>$po->id]) }}' class='kt-form kt-form--label-right' method='POST' novalidate>
                @csrf
                @method('PUT')
                <div class='kt-portlet__body'>
                    <div class='kt-section kt-section--first'>
                        <h3 class="kt-section__title">PO Header :</h3>
                        <div class='kt-section__body'>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>PO Number:*</label>
                                <div class='col-lg-6'>
                                    <input type='text' class='form-control @error('basic.po_number') is-invalid @enderror' placeholder='PO Number' name='basic[po_number]' value="{{ old('basic.po_number',$po->po_number) }}" required>
                                    @error('basic.po_number')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Supplier:*</label>
                                <div class='col-lg-6'>
                                    <select class='form-control kt-selectpicker @error('basic.supplier_id') is-invalid @enderror' name='basic[supplier_id]'
                                            data-live-search="true" title="Select Supplier ..." required>
                                            @foreach ($suppliers as $item)
                                                <option value="{{ $item->id }}" {{ old('basic.supplier_id',$po->supplier_id) == $item->id ? 'selected' :'' }}> {{ $item->name }}</option>
                                            @endforeach
                                    </select>
                                    @error('basic.supplier_id')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Order Date:*</label>
                                <div class='col-lg-6'>
                                    <div class="input-group date">
                                        <input type="text" class="form-control @error('basic.order_date') is-invalid @enderror'" name='basic[order_date]' readonly
                                        placeholder="Select Order Date" id="orderDate" required value="{{ old('basic.order_date',$po->order_date) }}"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="la la-calendar-check-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @error('basic.order_date')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Due Date:*</label>
                                <div class='col-lg-6'>
                                    <div class="input-group date">
                                        <input type="text" name="basic[due_date]" class="form-control @error('basic.due_date') is-invalid @enderror'"  value="{{ old('basic.due_date',$po->due_date) }}"
                                        readonly placeholder="Select Due Date" id="dueDate" required/>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="la la-calendar-check-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @error('basic.due_date')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Person In Charge:*</label>
                                <div class='col-lg-6'>
                                    <select class='form-control kt-selectpicker @error('basic.person_in_charge_id') is-invalid @enderror' id="pic"
                                    name='basic[person_in_charge_id]'  data-live-search="true" title="Select Person In Charge ..." required>
                                    @foreach ($persons as $item)
                                            <option value="{{ $item->id }}"  {{ old('basic.person_in_charge_id',$po->person_in_charge_id) == $item->id ? 'selected' :'' }}>{{ $item->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('basic.person_in_charge_id')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>Incoterm:*</label>
                                <div class='col-lg-6'>
                                    <select class='form-control kt-selectpicker @error('basic.incoterm_id') is-invalid @enderror' name='basic[incoterm_id]'
                                            data-live-search="true" title="Select Incoterm ..." required>
                                            @foreach ($incoTerms as $item)
                                                <option value="{{ $item->id }}" {{ old('basic.incoterm_id',$po->incoterm_id) == $item->id ? 'selected' :'' }}> {{ $item->name }}</option>
                                            @endforeach
                                    </select>
                                    @error('basic.incoterm_id')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-lg-3 col-form-label'>PO Status:</label>
                                <div class='col-lg-6'>
                                    <select class='form-control kt-selectpicker'
                                    name='basic[status]'>
                                    @foreach (App\Models\POHeader::STATUS as $item)
                                            <option value="{{ $item}}"  {{ old('basic.status',$po->status) == $item ? 'selected' :'' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error('basic.status')
                                        <span class='form-text text-danger'>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='kt-section kt-section--first'>
                        <h3 class="kt-section__title">PO Details :</h3>
                        @if($errors->has('detail'))
                        <h3 class="kt-section__title text-center text-danger"> <i class="flaticon-circle"></i> {{ $errors->first('detail') }}</h4>
                        @endif
                        <div class='kt-section__body'>
                            <table class='table table-bordered' id="po-details">
                                <thead>
                                    <tr class="table-active">
                                        <th class="text-center" style="width:80px">Row #</th>
                                        <th class="text-center">Material</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Shipping Unit</th>
                                        <th class="text-center">Origin</th>
                                        <th class="text-center">Due Date</th>
                                        <th class="text-center">Amendment Date</th>
                                        <th class='text-center' style='width:100px;'>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($po->details as $detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ old('edit.'.$detail->id.'.row_no',$detail->row_no)}}
                                        </td>
                                        <td class="text-center">
                                            <div>
                                                <select class='form-control rawMaterial @error('edit.'.$detail->id.'.raw_material_id') is-invalid @enderror'
                                                        name='edit[{{ $detail->id }}][raw_material_id]' {{ $detail->inboundDetails->count() > 0 ? 'disabled' : ''}}>
                                                    <option value="">Select Raw Material ...</option>
                                                    @foreach ($rawMaterials as $item)
                                                        <option value="{{ $item->id }}" {{ old('edit.'.$detail->id.'.raw_material_id',$detail->raw_material_id) == $item->id ? 'selected' :'' }}>{{ $item->hs_code }} - {{ $item->name }} </option>
                                                    @endforeach
                                                </select>
                                                @error('edit.'.$detail->id.'.raw_material_id')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div>
                                                <input type='text' class='form-control qty @error('edit.'.$detail->id.'.qty') is-invalid @enderror' {{ $detail->inboundDetails->count() > 0 ? 'disabled' : ''}}
                                                placeholder='qty' name='edit[{{ $detail->id }}][qty]' value="{{ old('edit.'.$detail->id.'.qty',$detail->qty) }}">
                                                @error('edit.'.$detail->id.'.qty')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div>
                                                <select class='form-control shipping_unit_id @error('edit.'.$detail->id.'.shipping_unit_id') is-invalid @enderror'
                                                    name='edit[{{ $detail->id }}][shipping_unit_id]' {{ $detail->inboundDetails->count() > 0 ? 'disabled' : ''}}>
                                                    <option value="">Select Shipping Unit ...</option>
                                                @foreach ($units as $item)
                                                    <option value="{{ $item->id }}" {{ old('edit.'.$detail->id.'.shipping_unit_id',$detail->shipping_unit_id) == $item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                                                @endforeach
                                                </select>
                                                @error('edit.'.$detail->id.'.shipping_unit_id')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div>
                                                <select class='form-control origin_country_id @error('edit.'.$detail->id.'.origin_country_id') is-invalid @enderror'
                                                    name='edit[{{ $detail->id }}][origin_country_id]' {{ $detail->inboundDetails->count() > 0 ? 'disabled' : ''}} required>
                                                    <option value="">Select Origin ...</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('edit.'.$detail->id.'.origin_country_id',$detail->origin_country_id) == $country->id ? 'selected' :'' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('edit.'.$detail->id.'.origin_country_id')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control item-due-date @error('edit.'.$detail->id.'.item_due_date') is-invalid @enderror" 
                                                           name="edit[{{ $detail->id }}][item_due_date]" readonly placeholder="Select Due Date" 
                                                           {{ $detail->inboundDetails->count() > 0 ? 'disabled' : ''}} required
                                                           value="{{ old('edit.'.$detail->id.'.item_due_date', $detail->item_due_date ? \Carbon\Carbon::parse($detail->item_due_date)->format('d/m/Y') : '') }}"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-calendar-check-o"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('edit.'.$detail->id.'.item_due_date')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control amendment-date @error('edit.'.$detail->id.'.amendment_date') is-invalid @enderror" 
                                                           name="edit[{{ $detail->id }}][amendment_date]" readonly placeholder="Select Amendment Date"
                                                           {{ $detail->inboundDetails->count() > 0 ? 'disabled' : ''}}
                                                           value="{{ old('edit.'.$detail->id.'.amendment_date', $detail->amendment_date ? \Carbon\Carbon::parse($detail->amendment_date)->format('d/m/Y') : '') }}"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-calendar-check-o"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('edit.'.$detail->id.'.amendment_date')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($detail->inboundDetails->count()<= 0)
                                            <div class="kt-checkbox-list">
                                                <label class="kt-checkbox kt-checkbox--danger">
                                                    <input type="checkbox" value="{{ $detail->id }}" name="delete[]"> Remove
                                                    <span></span>
                                                </label>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @forelse (old('detail',[]) as $key=>$item)
                                    <tr>
                                        <tr id="old">
                                            <td class="text-center row_number"></td>
                                            <td class="text-center row_material">
                                                <div>
                                                    <select class='form-control rawMaterial @error('detail.'.$key.'.raw_material_id') is-invalid @enderror'
                                                            name='detail[{{ $key }}][raw_material_id]' required>
                                                        <option value="">Select Raw Material ...</option>
                                                        @foreach ($rawMaterials as $item)
                                                            <option value="{{ $item->id }}" {{ old('detail.'.$key.'.raw_material_id') == $item->id ? 'selected' :'' }}>{{ $item->hs_code }} - {{ $item->name }} </option>
                                                        @endforeach
                                                    </select>
                                                    @error('detail.'.$key.'.raw_material_id')
                                                        <span class='form-text text-danger'>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td class="text-center row_qty">
                                                <div>
                                                    <input type='text' class='form-control qty @error('detail.'.$key.'.qty') is-invalid @enderror' placeholder='qty' name='detail[{{ $key }}][qty]' value="{{ old('detail.'.$key.'.qty') }}">
                                                    @error('detail.'.$key.'.qty')
                                                        <span class='form-text text-danger'>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td class="text-center row_unit">
                                                <div>
                                                    <select class='form-control shipping_unit_id @error('detail.'.$key.'.shipping_unit_id') is-invalid @enderror'
                                                        name='detail[{{ $key }}][shipping_unit_id]' >
                                                        <option value="">Select Shipping Unit ...</option>
                                                    @foreach ($units as $item)
                                                        <option value="{{ $item->id }}" {{ old('detail.'.$key.'.shipping_unit_id') == $item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                    </select>
                                                    @error('detail.'.$key.'.shipping_unit_id')
                                                        <span class='form-text text-danger'>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td class="text-center row_origin">
                                                <div>
                                                    <select class='form-control origin_country_id @error('detail.'.$key.'.origin_country_id') is-invalid @enderror'
                                                        name='detail[{{ $key }}][origin_country_id]' required>
                                                        <option value="">Select Origin ...</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {{ old('detail.'.$key.'.origin_country_id') == $country->id ? 'selected' :'' }}>{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('detail.'.$key.'.origin_country_id')
                                                        <span class='form-text text-danger'>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td class="text-center row_due_date">
                                                <div>
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control item-due-date @error('detail.'.$key.'.item_due_date') is-invalid @enderror" 
                                                               name="detail[{{ $key }}][item_due_date]" readonly placeholder="Select Due Date" required
                                                               value="{{ old('detail.'.$key.'.item_due_date') }}"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @error('detail.'.$key.'.item_due_date')
                                                        <span class='form-text text-danger'>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td class="text-center row_amendment_date">
                                                <div>
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control amendment-date @error('detail.'.$key.'.amendment_date') is-invalid @enderror" 
                                                               name="detail[{{ $key }}][amendment_date]" readonly placeholder="Select Amendment Date"
                                                               value="{{ old('detail.'.$key.'.amendment_date') }}"/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar-check-o"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @error('detail.'.$key.'.amendment_date')
                                                        <span class='form-text text-danger'>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td class="text-center row_action">
                                                <button type="button" class="btn btn-danger btn-icon btn-sm remove-btn"><i class="fa fa-minus"></i></button>
                                                <button type="button" class="btn btn-warning btn-icon btn-sm add-btn"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr id="first">
                                        <td class="text-center row_number"></td>
                                        <td class="text-center row_material">
                                            <div>
                                                <select class='form-control rawMaterial @error('detail.1.raw_material_id') is-invalid @enderror'
                                                        name='detail[1][raw_material_id]' required>
                                                    <option value="">Select Raw Material ...</option>
                                                    @foreach ($rawMaterials as $item)
                                                        <option value="{{ $item->id }}" {{ old('detail.1.raw_material_id') == $item->id ? 'selected' :'' }}>{{ $item->hs_code }} - {{ $item->name }} </option>
                                                    @endforeach
                                                </select>
                                                @error('detail.1.raw_material_id')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center row_qty">
                                            <div>
                                                <input type='text' class='form-control qty @error('detail.1.qty') is-invalid @enderror' placeholder='qty' name='detail[1][qty]' value="{{ old('detail.1.qty') }}">
                                                @error('detail.1.qty')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center row_unit">
                                            <div>
                                                <select class='form-control shipping_unit_id @error('detail.1.shipping_unit_id') is-invalid @enderror'
                                                    name='detail[1][shipping_unit_id]' >
                                                    <option value="">Select Shipping Unit ...</option>
                                                @foreach ($units as $item)
                                                    <option value="{{ $item->id }}" {{ old('detail.shipping_unit_id') == $item->id ? 'selected' :'' }}>{{ $item->name }}</option>
                                                @endforeach
                                                </select>
                                                @error('detail.1.shipping_unit_id')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center row_origin">
                                            <div>
                                                <select class='form-control origin_country_id @error('detail.1.origin_country_id') is-invalid @enderror'
                                                    name='detail[1][origin_country_id]' required>
                                                    <option value="">Select Origin ...</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('detail.1.origin_country_id') == $country->id ? 'selected' :'' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('detail.1.origin_country_id')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center row_due_date">
                                            <div>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control item-due-date @error('detail.1.item_due_date') is-invalid @enderror" 
                                                           name="detail[1][item_due_date]" readonly placeholder="Select Due Date" required
                                                           value="{{ old('detail.1.item_due_date') }}"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-calendar-check-o"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('detail.1.item_due_date')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center row_amendment_date">
                                            <div>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control amendment-date @error('detail.1.amendment_date') is-invalid @enderror" 
                                                           name="detail[1][amendment_date]" readonly placeholder="Select Amendment Date"
                                                           value="{{ old('detail.1.amendment_date') }}"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-calendar-check-o"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('detail.1.amendment_date')
                                                    <span class='form-text text-danger'>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="text-center row_action">
                                            <button type="button" class="btn btn-danger btn-icon btn-sm remove-btn"><i class="fa fa-minus"></i></button>
                                            <button type="button" class="btn btn-warning btn-icon btn-sm add-btn"><i class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    @endforelse

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
                                <a href='{{ route('purchase-orders.index') }}' class='btn btn-secondary'>Cancel</a>
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
    .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover{
        background: none !important;
        color: #cccdd0 !important;
    }
    #otherShippingLine.hidden{
        display: none;
    }
    button.btn.btn-icon.log-btn{
        display: none;
    }
    #po-details.table thead th{
        vertical-align: middle;
    }
    /* #po-details.table tbody tr:not(:last-child) .row_action .add-btn{
        display: none;
    } */
    #po-details.table tbody tr:last-child .row_action .remove-btn{
        display: none;
    }
    #po-details tbody{
        counter-reset: rows {{ $po->details->max('row_no') }};
    }
    #po-details tbody tr:before{
    }
    #po-details tbody td.row_number:before{
        counter-increment: rows;

        content: counter(rows);
    }
    .row_material,.row_unit,.row_origin{
        width: 15%;
    }
    .row_qty,.row_due_date,.row_amendment_date{
        width: 15%;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/po/create.js') }}" type="text/javascript"></script>
@endpush
