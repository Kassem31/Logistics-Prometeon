@extends('main')

@section('container')
<div class="row">
    <div class="col-lg-12">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Import Purchase Orders from Excel
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="alert alert-info">
                    <h4 class="alert-heading">Instructions:</h4>
                    <ul>
                        <li><strong>Download the template</strong> to see the expected Excel format</li>
                        <li><strong>Expected columns:</strong> Order Date, SAP Code, Material Group, Material, PO Number, PO, Due Date, Amendment Date, Qty, Shipping Unit, Supplier, Incoterm, Origin</li>
                        <li><strong>Suppliers</strong> will be matched by name</li>
                        <li><strong>Incoterms</strong> will be matched by name</li>
                        <li><strong>SAP Codes</strong> must exist in the raw materials table</li>
                        <li><strong>Person in charge</strong> will be assigned manually after import</li>
                        <li><strong>Multiple rows</strong> with the same PO Number will create one PO with multiple detail lines</li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('purchase-orders.template') }}" class="btn btn-outline-primary mb-4">
                            <i class="la la-download"></i>
                            Download Excel Template
                        </a>
                    </div>
                </div>

                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label for="excel_file">Excel File *</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">Maximum file size: 10MB. Accepted formats: .xlsx, .xls, .csv</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-upload"></i>
                        Import Purchase Orders
                    </button>
                    
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                        <i class="la la-arrow-left"></i>
                        Back to Purchase Orders
                    </a>
                </form>

                <div id="importProgress" class="mt-4" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%">
                            Processing...
                        </div>
                    </div>
                </div>

                <div id="importResults" class="mt-4" style="display: none;">
                    <!-- Results will be displayed here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('importForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const progressDiv = document.getElementById('importProgress');
    const resultsDiv = document.getElementById('importResults');
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Show progress and disable submit button
    progressDiv.style.display = 'block';
    resultsDiv.style.display = 'none';
    submitBtn.disabled = true;
    
    fetch('{{ route("purchase-orders.import") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        progressDiv.style.display = 'none';
        resultsDiv.style.display = 'block';
        
        if (data.success) {
            resultsDiv.innerHTML = `
                <div class="alert alert-success">
                    <h4 class="alert-heading">Import Successful!</h4>
                    <p>${data.message}</p>
                    <ul>
                        <li>Purchase Orders Created: ${data.data.po_count}</li>
                        <li>Purchase Order Details Created: ${data.data.detail_count}</li>
                    </ul>
                </div>
            `;
        } else {
            let errorsHtml = '';
            if (data.data && data.data.errors && data.data.errors.length > 0) {
                errorsHtml = '<ul>';
                data.data.errors.forEach(error => {
                    errorsHtml += `<li>${error}</li>`;
                });
                errorsHtml += '</ul>';
            }
            
            resultsDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h4 class="alert-heading">Import Failed</h4>
                    <p>${data.message}</p>
                    ${errorsHtml}
                </div>
            `;
        }
    })
    .catch(error => {
        progressDiv.style.display = 'none';
        resultsDiv.style.display = 'block';
        resultsDiv.innerHTML = `
            <div class="alert alert-danger">
                <h4 class="alert-heading">Error</h4>
                <p>An unexpected error occurred during import.</p>
            </div>
        `;
        console.error('Error:', error);
    })
    .finally(() => {
        submitBtn.disabled = false;
    });
});
</script>
@endsection
