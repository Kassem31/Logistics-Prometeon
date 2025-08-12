<div class="kt-wizard-v2__content" data-ktwizard-type="step-content">
        <div class="kt-form__section kt-form__section--first">
            <div class="kt-wizard-v2__form">
                <h3 class="kt-section__title">Shipping Details :</h3>
                <div class='form-group row'>
                    <label class='col-lg-3 col-form-label'>Inbound No.:</label>
                    <div class='col-lg-6'>
                        <input type='text' class='form-control @error('inbound.inbound_no') is-invalid @enderror' placeholder='Inbound No.'
                        name='inbound[inbound_no]' value="{{ old('inbound.inbound_no',$inbound->inbound_no) }}">
                        @error('inbound.inbound_no')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                    </div>
                </div>
                <div class='form-group row'>
                    <label class='col-lg-3 col-form-label'>Select PO:*</label>
                    <div class='col-lg-6'>
                    <select class='form-control kt-selectpicker @error('inbound.po_header_id') is-invalid @enderror' id="rawMaterial"
                    name='inbound[po_header_id]' data-live-search="true" title="Select PO ..." required>
                        @foreach ($pos as $item)
                            <option value="{{ $item->id }}" {{ old('inbound.po_header_id',$inbound->po_header_id) == $item->id ? 'selected' :'' }}>{{ $item->po_number }} </option>
                        @endforeach
                    </select>
                    @error('inbound.po_header_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                    </div>
                    <div class="col-md-2">
                        <span class='form-text text-info' id="loadingInfo"></span>
                    </div>
                </div>
                <h3 class="kt-section__title">Order Information :</h3>
                <div class='kt-section__body'>
                    <table class='table table-bordered' id="po-details">
                        <thead>
                            <tr class="table-active">
                                <th class="text-center" style="width:80px">#</th>
                                <th class="text-center">Material</th>
                                <th class="text-center">Qty</th>
                                <td class="text-center">Remaining</td>
                                <th class="text-center">Shipping Unit</th>
                                <th class='text-center' style='width:100px;'>

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inbound->details as $item)
                            <tr>
                                <td class="text-center row_number"></td>
                                <td class="text-center">
                                    <div>
                                        <input class="form-control" disabled
                                        value=" # {{ optional($item->poDetail)->row_no }} - {{ optional(optional($item->poDetail)->rawMaterial)->hs_code }} - {{ optional(optional($item->poDetail)->rawMaterial)->name }}">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div>
                                        <input type='text' class='form-control'  value="{{ $item->qty }}" disabled>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <input type='text' class='form-control'  value="{{ optional($item->poDetail)->remaining }}" disabled>
                                </td>
                                <td class="text-center">
                                    <div>
                                        <input type='text' class='form-control' value="{{ optional(optional($item->poDetail)->shippingUnit)->name}}" disabled>
                                    </div>
                                </td>
                                <td class="text-center row_action">
                                    <div class="kt-checkbox-list">
                                        <label class="kt-checkbox kt-checkbox--danger">
                                            <input type="checkbox" value="{{ $item->id }}" name="delete[]"> Remove
                                            <span></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr id="first">
                                <td class="text-center row_number"></td>
                                <td class="text-center row_material">
                                    <div>
                                        <select class='form-control rawMaterial @error('detail.'.$counter.'.po_detail_id') is-invalid @enderror'
                                                name='detail[{{ $counter }}][po_detail_id]' required>
                                            <option value="">Select Raw Material ...</option>
                                            @foreach ($oldRawMaterials as $item)
                                            <option value="{{ $item->id }}"
                                                data-rem='{{ $item->remaining }}' data-unit='{{ optional($item->shippingUnit)->name }}'>
                                                # {{ $item->row_no }} - {{ optional($item->rawMaterial)->hs_code }} - {{ optional($item->rawMaterial)->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('detail.'.$counter.'.po_detail_id')
                                            <span class='form-text text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </td>
                                <td class="text-center row_qty">
                                    <div>
                                        <input type='text' class='form-control qty @error('detail.'.$counter.'.qty') is-invalid @enderror' placeholder='qty' name='detail[{{ $counter }}][qty]' value="{{ old('detail.'.$counter.'.qty') }}">
                                        @error('detail.'.$counter.'.qty')
                                            <span class='form-text text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </td>
                                <td class="text-center row_remaining">
                                    <input type='text' class='form-control remaining' name="detail[{{ $counter }}][remaining]" readonly>
                                </td>
                                <td class="text-center row_unit">
                                    <div>
                                        <input type='text' class='form-control unit' name="detail[{{ $counter }}][unit]" readonly>
                                    </div>
                                </td>
                                <td class="text-center row_action">
                                    <button type="button" class="btn btn-danger btn-icon btn-sm remove-btn"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-warning btn-icon btn-sm add-btn"><i class="fa fa-plus"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h3 class="kt-section__title">ACID Information :</h3>
                <div class='form-group row'>
                    <label class='col-lg-3 col-form-label'>ACID Number:*</label>
                    <div class='col-lg-6'>
                        <input type='text' class='form-control @error('inbound.acid_number') is-invalid @enderror' 
                               placeholder='Enter ACID Number' name='inbound[acid_number]' 
                               value="{{ old('inbound.acid_number', $inbound->acid_number) }}" 
                               maxlength="50" required>
                        @error('inbound.acid_number')
                            <span class='form-text text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- <h3 class="kt-section__title">Bank Information (Read-Only):</h3>
                <div class="bankInfoDiv inboundHeader" style="opacity: 0.7;">
                    @include('shipping.wizard.partial._bank-info')
                </div> --}}
            </div>
        </div>
    </div>
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
    #po-details.table tbody tr:not(:last-child) .row_action .add-btn{
        display: none;
    }
    #po-details.table tbody tr:only-child .row_action .remove-btn{
        display: none;
    }
    #po-details tbody{
        counter-reset: rows;
    }
    #po-details tbody tr:before{
    }
    #po-details tbody td.row_number:before{
        counter-increment: rows;

        content: counter(rows);
    }
    
</style>
@endpush
@push('scripts')
    <script src="{{ asset('js/shipping/create.js') }}"></script>
@endpush
