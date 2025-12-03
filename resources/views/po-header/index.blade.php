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
                    <button type="button" class='btn btn-success kt-margin-r-10' data-toggle="modal" data-target="#importModal">
                        <i class='la la-upload'></i>
                        <span class='kt-hidden-mobile'>Import from Excel</span>
                    </button>
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
                                        <th>#Line</th>
                                        <th> Supplier</th>
                                        <th>Incoterm</th>
                                        <th>Origin</th>
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
                                        <td></td>
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
                                        <td style="max-width: 150px;">
                                            <select class="form-control kt-selectpicker" data-live-search="true" multiple name="origin[]">
                                                <option value="">Select Origin . . .</option>
                                                @foreach ($countries as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id,request()->input('origin',[])) ? 'selected':'' }}>{{ $item->name }}</option>
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
                                            <td>{{ $item->details->pluck('line_number')->filter()->implode(', ') }}</td>
                                            <td>{{ optional($item->supplier)->name }}</td>
                                            <td>{{ optional($item->incoterm)->prefix }}</td>
                                            <td>{{ $item->details->pluck('originCountry.name')->filter()->unique()->implode(', ') }}</td>
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="la la-upload"></i> Import Purchase Orders from Excel
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="importForm" action="{{ route('purchase-orders.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excel_file">Excel File <span class="text-danger">*</span></label>
                        <input type="file" class="kt-uppy__input-control" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required style="position: absolute; left: -9999px; width: 1px; height: 1px; opacity: 0;">
                        <div class="kt-uppy" id="kt_uppy_1">
                            <div class="kt-uppy__wrapper">
                                <div class="kt-uppy__drag-area" id="dragArea">
                                    <div class="kt-uppy__drag-area-icon">
                                        <i class="flaticon2-file"></i>
                                    </div>
                                    <div class="kt-uppy__drag-area-message">
                                        Drop files here or <span class="kt-link">browse</span>
                                    </div>
                                </div>
                                <div class="kt-uppy__files" id="fileList" style="display: none;">
                                    <div class="kt-uppy__file">
                                        <div class="kt-uppy__file-icon">
                                            <i class="flaticon2-file"></i>
                                        </div>
                                        <div class="kt-uppy__file-details">
                                            <div class="kt-uppy__file-name" id="fileName"></div>
                                            <div class="kt-uppy__file-size" id="fileSize"></div>
                                        </div>
                                        <div class="kt-uppy__file-action">
                                            <button type="button" class="btn btn-sm btn-clean btn-icon" id="removeFile">
                                                <i class="la la-remove"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="form-text text-muted">Accepted file types: .xlsx, .xls, .csv (Max size: 10MB)</span>
                        <div id="fileError" class="text-danger" style="display: none;"></div>
                                            </div>
                </div>
                <div class="modal-footer">
                    <div class="row" style="width: 100%;">
                        <div class="col-6">
                            <a href="{{ route('purchase-orders.template') }}" class="btn btn-info" target="_blank">
                                <i class="la la-download"></i> Download Template
                            </a>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="la la-close"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-success" id="importBtn" disabled>
                                <i class="la la-upload"></i> Import Purchase Orders
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="progressModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="progressModalLabel">
                    <i class="la la-spinner la-spin"></i> Processing Import...
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--success">
                    <div class="kt-spinner__dot"></div>
                    <div class="kt-spinner__dot"></div>
                    <div class="kt-spinner__dot"></div>
                </div>
                <p class="mt-4">Please wait while we process your Excel file...</p>
                <div class="progress mt-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Results Modal -->
<div class="modal fade" id="resultsModal" tabindex="-1" role="dialog" aria-labelledby="resultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultsModalLabel">
                    <i class="la la-check-circle"></i> Import Results
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resultsContent">
                <!-- Results will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="la la-close"></i> Close
                </button>
                <button type="button" class="btn btn-primary" onclick="location.reload()">
                    <i class="la la-refresh"></i> Refresh Page
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('styles')
<style>
.kt-uppy__drag-area {
    border: 2px dashed #e2e5ec;
    border-radius: 6px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f7f8fa;
    position: relative;
}

.kt-uppy__drag-area:hover,
.kt-uppy__drag-area--active {
    border-color: #5d78ff;
    background: #f0f3ff;
}

.kt-uppy__drag-area-icon {
    font-size: 48px;
    color: #a7abc3;
    margin-bottom: 15px;
}

.kt-uppy__drag-area--active .kt-uppy__drag-area-icon {
    color: #5d78ff;
}

.kt-uppy__drag-area-message {
    font-size: 16px;
    color: #595d6e;
}

.kt-link {
    color: #5d78ff;
    text-decoration: underline;
    cursor: pointer;
    font-weight: 500;
    position: relative;
    z-index: 10;
    display: inline-block;
    padding: 2px 4px;
}

.kt-link:hover {
    color: #4c63d2;
    text-decoration: underline;
}

.kt-uppy__input-control {
    /* File input is positioned absolutely outside the flow */
}

.kt-uppy__file {
    display: flex;
    align-items: center;
    padding: 15px;
    border: 1px solid #e2e5ec;
    border-radius: 6px;
    background: #fff;
}

.kt-uppy__file-icon {
    font-size: 24px;
    color: #a7abc3;
    margin-right: 15px;
}

.kt-uppy__file-details {
    flex: 1;
}

.kt-uppy__file-name {
    font-weight: 500;
    margin-bottom: 5px;
}

.kt-uppy__file-size {
    font-size: 12px;
    color: #a7abc3;
}

.kt-widget24 {
    background: #fff;
    border: 1px solid #e2e5ec;
    border-radius: 6px;
    padding: 20px;
    margin-bottom: 20px;
}

.kt-widget24__details {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}

.kt-widget24__title {
    font-size: 14px;
    font-weight: 500;
    margin: 0;
    color: #595d6e;
}

.kt-widget24__desc {
    font-size: 12px;
    color: #a7abc3;
}

.kt-widget24__stats {
    font-size: 24px;
    font-weight: 600;
}

.progress--sm {
    height: 4px;
}

.alert-solid-success {
    color: #1bc5bd;
    background-color: rgba(27, 197, 189, 0.1);
    border-color: rgba(27, 197, 189, 0.2);
}

.alert-solid-danger {
    color: #fd397a;
    background-color: rgba(253, 57, 122, 0.1);
    border-color: rgba(253, 57, 122, 0.2);
}

.alert-solid-warning {
    color: #ffa800;
    background-color: rgba(255, 168, 0, 0.1);
    border-color: rgba(255, 168, 0, 0.2);
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/po/index.js') }}" type="text/javascript"></script>

<script>
$(document).ready(function() {
    // File upload handling
    let selectedFile = null;
    
    // Direct file input change handler
    $('#excel_file').on('change', function() {
        console.log('File input changed:', this.files);
        const file = this.files[0];
        if (file) {
            selectedFile = file;
            showSelectedFile(file);
            $('#importBtn').prop('disabled', false);
            $('#fileError').hide();
        }
    });

    // Drag and drop functionality
    const dragArea = $('#dragArea');
    const fileInput = document.getElementById('excel_file');
    
    // Handle click on drag area to trigger file input
    dragArea.on('click', function(e) {
        console.log('Drag area clicked, target:', e.target);
        e.preventDefault();
        e.stopPropagation();
        console.log('Triggering file input...');
        fileInput.click();
    });
    
    // Handle click on browse text specifically
    $(document).on('click', '.kt-link', function(e) {
        console.log('Browse link clicked directly');
        e.preventDefault();
        e.stopPropagation();
        console.log('Triggering file input from browse link...');
        fileInput.click();
    });
    
    // Debug button for testing
    $('#debugFileBtn').on('click', function() {
        console.log('Debug button clicked - testing file input');
        console.log('File input element:', fileInput);
        console.log('File input style:', fileInput.style.cssText);
        console.log('File input offsetParent:', fileInput.offsetParent);
        fileInput.click();
    });
    
    dragArea.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('kt-uppy__drag-area--active');
    });
    
    dragArea.on('dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('kt-uppy__drag-area--active');
    });
    
    dragArea.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        // Only remove active state if we're leaving the drag area itself
        if (!$(this).is(e.target) && !$(this).has(e.target).length) {
            $(this).removeClass('kt-uppy__drag-area--active');
        }
    });
    
    dragArea.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('kt-uppy__drag-area--active');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (validateFile(file)) {
                selectedFile = file;
                // Create a new FileList-like object
                const dt = new DataTransfer();
                dt.items.add(file);
                $('#excel_file')[0].files = dt.files;
                showSelectedFile(file);
                $('#importBtn').prop('disabled', false);
                $('#fileError').hide();
            }
        }
    });
    
    $('#removeFile').on('click', function() {
        selectedFile = null;
        $('#excel_file').val('');
        $('#fileList').hide();
        $('#dragArea').show();
        $('#importBtn').prop('disabled', true);
    });
    
    function validateFile(file) {
        console.log('Validating file:', file.name, 'Type:', file.type, 'Size:', file.size);
        
        const allowedExtensions = /\.(xlsx|xls|csv)$/i;
        const allowedMimeTypes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv',
            'application/csv',
            'text/comma-separated-values'
        ];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        // Check file extension
        if (!allowedExtensions.test(file.name)) {
            $('#fileError').text('Please select a valid Excel or CSV file (.xlsx, .xls, .csv).').show();
            return false;
        }
        
        // Check file size
        if (file.size > maxSize) {
            $('#fileError').text('File size must be less than 10MB.').show();
            return false;
        }
        
        // Check MIME type if available (some browsers don't set it correctly)
        if (file.type && !allowedMimeTypes.includes(file.type)) {
            console.warn('MIME type not in allowed list, but extension is valid:', file.type);
        }
        
        return true;
    }
    
    function showSelectedFile(file) {
        $('#fileName').text(file.name);
        $('#fileSize').text(formatFileSize(file.size));
        $('#dragArea').hide();
        $('#fileList').show();
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Reset modal when it's closed
    $('#importModal').on('hidden.bs.modal', function() {
        selectedFile = null;
        $('#excel_file').val('');
        $('#fileList').hide();
        $('#dragArea').show();
        $('#importBtn').prop('disabled', true);
        $('#fileError').hide();
    });

    // Handle form submission
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        
        console.log('Form submission started');
        console.log('Selected file:', selectedFile);
        console.log('Form action URL:', $(this).attr('action'));
        
        if (!selectedFile) {
            $('#fileError').text('Please select a file to import.').show();
            return;
        }
        
        // Validate file again before submission
        if (!validateFile(selectedFile)) {
            return;
        }
        
        let formData = new FormData();
        formData.append('excel_file', selectedFile);
        
        // Get CSRF token from multiple possible sources
        let csrfToken = $('meta[name="csrf-token"]').attr('content') || 
                       $('input[name="_token"]').val() || 
                       $('[name="csrf-token"]').attr('content');
        
        if (!csrfToken) {
            console.error('CSRF token not found');
            $('#fileError').text('Security token not found. Please refresh the page.').show();
            return;
        }
        
        formData.append('_token', csrfToken);
        
        console.log('FormData created:', {
            file: formData.get('excel_file'),
            fileName: selectedFile.name,
            fileSize: selectedFile.size,
            token: formData.get('_token') ? 'Present' : 'Missing'
        });
        
        // Hide import modal and show progress modal
        $('#importModal').modal('hide');
        $('#progressModal').modal('show');
        
        // Disable submit button
        $('#importBtn').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 300000, // 5 minutes timeout
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            beforeSend: function(xhr) {
                console.log('AJAX request starting...');
                console.log('URL:', $(this).attr('action'));
                console.log('Request headers:', xhr.getAllResponseHeaders());
            },
            success: function(response, textStatus, xhr) {
                console.log('Import successful:', response);
                console.log('Response status:', xhr.status);
                console.log('Response headers:', xhr.getAllResponseHeaders());
                
                // Check if we got redirected (which would mean authentication issues)
                if (xhr.status === 200 && typeof response === 'string' && response.includes('<!DOCTYPE html>')) {
                    console.error('Received HTML instead of JSON - likely authentication issue');
                    showResults({
                        success: false,
                        message: 'Authentication error or session expired',
                        errors: [
                            'The server returned an HTML page instead of JSON.',
                            'This usually means you need to log in again.',
                            'Please refresh the page and try again.'
                        ]
                    });
                    return;
                }
                
                $('#progressModal').modal('hide');
                
                // Ensure response is properly formatted
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        console.error('Failed to parse response as JSON:', response.substring(0, 500));
                        showResults({
                            success: false,
                            message: 'Invalid response format from server',
                            errors: [
                                'Server returned HTML instead of JSON.',
                                'This might be an authentication or routing issue.',
                                'Please check if you are still logged in.'
                            ]
                        });
                        return;
                    }
                }
                
                if (!response || typeof response !== 'object') {
                    showResults({
                        success: false,
                        message: 'Invalid response received from server',
                        errors: ['Response type: ' + typeof response]
                    });
                    return;
                }
                
                showResults(response);
            },
            error: function(xhr, status, error) {
                console.error('Import failed:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    ajaxStatus: status,
                    ajaxError: error
                });
                
                $('#progressModal').modal('hide');
                $('#importBtn').prop('disabled', false);
                
                let errorMessage = 'An error occurred while processing the file.';
                let errors = [];
                
                try {
                    let response = xhr.responseJSON;
                    console.log('Parsed response:', response);
                    
                    if (response && response.message) {
                        errorMessage = response.message;
                    } else if (xhr.status === 422) {
                        errorMessage = 'Validation failed. Please check your file format.';
                        if (response && response.errors) {
                            errors = Object.values(response.errors).flat();
                        }
                    } else if (xhr.status === 413) {
                        errorMessage = 'File is too large. Please use a smaller file.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error occurred while processing the file.';
                    } else if (xhr.status === 0) {
                        errorMessage = 'Network error. Please check your connection.';
                    } else {
                        errorMessage = `Server returned error ${xhr.status}: ${xhr.statusText || 'Unknown error'}`;
                    }
                    
                    // Try to extract more details from response
                    if (response) {
                        if (response.data && response.data.errors) {
                            errors = errors.concat(response.data.errors);
                        } else if (response.errors) {
                            errors = errors.concat(response.errors);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    if (xhr.responseText) {
                        errorMessage += ' Raw response: ' + xhr.responseText.substring(0, 200);
                    }
                }
                
                showResults({
                    success: false,
                    message: errorMessage,
                    errors: errors.length > 0 ? errors : [
                        'Status: ' + (xhr.status || 'Unknown'),
                        'Error: ' + (error || 'Unknown error'),
                        'Please check the browser console for more details.'
                    ]
                });
            }
        });
    });

    function showResults(response) {
        console.log('Showing results:', response);
        
        let content = '';
        
        // Ensure response has proper structure
        if (!response || typeof response !== 'object') {
            response = {
                success: false,
                message: 'Invalid response received',
                errors: ['Response was: ' + JSON.stringify(response)]
            };
        }
        
        // Default values if properties are missing
        if (response.success === undefined) {
            response.success = false;
        }
        
        if (!response.message) {
            response.message = response.success ? 'Operation completed successfully' : 'Operation failed';
        }
        
        if (response.success) {
            content = `
                <div class="alert alert-success alert-solid-success" role="alert">
                    <div class="alert-icon"><i class="la la-check-circle"></i></div>
                    <div class="alert-text">
                        <h4>Import Successful!</h4>
                        ${response.message}
                    </div>
                </div>
            `;
        } else {
            content = `
                <div class="alert alert-danger alert-solid-danger" role="alert">
                    <div class="alert-icon"><i class="la la-warning"></i></div>
                    <div class="alert-text">
                        <h4>Import Failed!</h4>
                        ${response.message}
                    </div>
                </div>
            `;
        }        
        
        $('#resultsContent').html(content);
        $('#resultsModal').modal('show');
        
        // Re-enable submit button
        $('#importBtn').prop('disabled', false);
    }
});
</script>
@endpush
