@extends('main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Import Purchase Orders from Excel</h3>
                    <div class="card-tools">
                        <a href="{{ route('purchase-orders.template') }}" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                    <form id="importForm" action="{{ route('purchase-orders.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="excel_file">Excel File <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
                                            <label class="custom-file-label" for="excel_file">Choose file...</label>
                                        </div>
                                    </div>
                                    @error('excel_file')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="importBtn">
                                <i class="fas fa-upload"></i> Import Purchase Orders
                            </button>
                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="progressModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="progressModalLabel">Processing Import...</h5>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-3">Please wait while we process your Excel file...</p>
            </div>
        </div>
    </div>
</div>

<!-- Results Modal -->
<div class="modal fade" id="resultsModal" tabindex="-1" role="dialog" aria-labelledby="resultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultsModalLabel">Import Results</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resultsContent">
                <!-- Results will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-primary">View Purchase Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });

    // Handle form submission
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        // Show progress modal
        $('#progressModal').modal('show');
        
        // Disable submit button
        $('#importBtn').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#progressModal').modal('hide');
                showResults(response);
            },
            error: function(xhr) {
                $('#progressModal').modal('hide');
                $('#importBtn').prop('disabled', false);
                
                let response = xhr.responseJSON;
                if (response && response.message) {
                    showResults({
                        success: false,
                        message: response.message,
                        data: response.data || {},
                        errors: response.data ? response.data.errors : (response.errors || [])
                    });
                } else {
                    alert('An error occurred while processing the file. Please try again.');
                }
            }
        });
    });

    function showResults(response) {
        let content = '';
        
        if (response.success) {
            content = `
                <div class="alert alert-success">
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    ${response.message}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-file-invoice"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Purchase Orders Created</span>
                                <span class="info-box-number">${response.data ? response.data.po_count : (response.po_count || 0)}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Order Details Created</span>
                                <span class="info-box-number">${response.data ? response.data.detail_count : (response.detail_count || 0)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            content = `
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    ${response.message}
                </div>
            `;
        }
        
        if (response.errors && response.errors.length > 0) {
            content += `
                <div class="alert alert-warning">
                    <h6><i class="icon fas fa-exclamation-triangle"></i> Warnings/Errors:</h6>
                    <ul class="mb-0">
            `;
            response.errors.slice(0, 10).forEach(function(error) {
                content += `<li>${error}</li>`;
            });
            if (response.errors.length > 10) {
                content += `<li>... and ${response.errors.length - 10} more errors</li>`;
            }
            content += `</ul></div>`;
        }
        
        $('#resultsContent').html(content);
        $('#resultsModal').modal('show');
        
        // Re-enable submit button
        $('#importBtn').prop('disabled', false);
    }
});
</script>
@endsection
