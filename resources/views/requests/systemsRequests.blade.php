<!DOCTYPE html>
<html>
<head>
    <title>Systems Requests And Approvals</title>
    @include('dashboard.style')
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/dashboard/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        nav {
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>
 
@include('dashboard.sidebar')
<div class="home-section">
@include('dashboard.header')
<div class="home-content p-3">



<div class="row">
    <div class="col-lg-10 mx-auto">
    @if ($errors->any())
    <div class="alert alert-warning">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif 
        <div class="card bg-body shadow-sm">
            <div class="card-header p-3 text-white text-center" style="border-radius: 5px; background-color: #007bff;">
                <h4>Create New Request</h4>
            </div>
            <div class="card-body">
            <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <label for="requestType">Request Type</label>
                            <select class="form-control bg-body shadow-sm" name="request_type_id" id="requestType">
    <option value="" selected>Select Request Type</option>
    @foreach($request_type as $type)
        <option value="{{ $type->id }}" {{ old('request_type_id') == $type->id ? 'selected' : '' }}>
            {{ $type->request_name }}
        </option>
    @endforeach
</select>
@error('request_type_id')
    <span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="accountType">Requested Account</label>
                            <select class="form-select bg-body shadow-sm" name="account_type" id="accountType">
                                <option value="">Select Requested Account</option>
                                <option value="Own" {{ old('account_type') == 'Own' ? 'selected' : '' }}>Own Account</option>
                                <option value="onbehalf" {{ old('account_type') == 'onbehalf' ? 'selected' : '' }}>Not Own Account</option>
                            </select>
                            @error('account_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
<!-- Hidden fields -->
<div id="onbehalfFields" class="mt-3" style="display: none;">
    <div class="row">
        <div class="col-lg-6">
            <label for="fname">First Name</label>
            <input type="text" class="form-control bg-body shadow-sm @error('fname') is-invalid @enderror" name="fname" id="fname" autocomplete="off">
            @error('fname')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-lg-6">
            <label for="lname">Last Name</label>
            <input type="text" class="form-control bg-body shadow-sm @error('lname') is-invalid @enderror" name="lname" id="lname" autocomplete="off">
            @error('lname')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6">
            <label for="email">Email</label>
            <input type="email" class="form-control bg-body shadow-sm @error('email') is-invalid @enderror" name="email" id="email" autocomplete="off">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-lg-6">
            <label for="department">Mobile Number</label>
            <input type="text" class="form-control bg-body shadow-sm @error('mobile') is-invalid @enderror" name="mobile" id="mobile" autocomplete="off">
            @error('mobile')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
    <div class="col-lg-6">
            <label for="department">Department</label>
            <input type="text" class="form-control bg-body shadow-sm @error('department') is-invalid @enderror" name="department" id="department" autocomplete="off">
            @error('department')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-lg">
        <label for="department">Position</label>
            <input type="text" class="form-control bg-body shadow-sm @error('position') is-invalid @enderror" name="position" id="department" autocomplete="off">
            @error('position')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="row mt-4">
    <!-- Left Column -->
    <div class="col-lg-6">
        <!-- Request Type -->
        <div class="mb-3">
            <label for="requestType" class="form-label">Systems Name</label>
            <select class="form-control bg-body shadow-sm" name="systems_name_id" id="systemsName">
    <option value="" selected>Systems Name</option>
    <!-- Options will be dynamically populated -->
</select>
@error('systems_name_id')
    <span class="text-danger">{{ $message }}</span>
@enderror
        </div>

        <!-- Request Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Request Description</label>
            <textarea 
                class="form-control bg-body shadow-sm" 
                name="description" 
                rows="4" 
                id="description"
                placeholder="Enter the request description">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-6">
        <!-- Attachments -->
        <fieldset class="border p-3">
            <legend class="w-auto">Attachments</legend>
            <div class="mb-3">
                <label for="attachments" class="form-label">Upload Attachments</label>
                <input 
                    type="file" 
                    class="form-control bg-body shadow-sm" 
                    name="attachments[]" 
                    id="attachments" 
                    accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" 
                    multiple 
                    onchange="previewFiles(event)">
                @error('attachments')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- Container for previews -->
            <div id="previews" class="mt-2" style="display: flex; flex-wrap: wrap;"></div>
        </fieldset>
    </div>
</div>

                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary shadow-lg">Submit Request</button>
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>

</div>
</div>
@include('dashboard.script')
<script>
        $(document).ready(function () {
        $('#requestType').change(function () {
            const requestTypeId = $(this).val();
            if (requestTypeId) {
                $.ajax({
                    url: "{{ route('filter.systems') }}",
                    type: "GET",
                    data: { request_type_id: requestTypeId },
                    success: function (data) {
                        $('#systemsName').empty().append('<option value="" selected>Systems Name</option>');
                        $.each(data, function (index, system) {
                            $('#systemsName').append('<option value="' + system.id + '">' + system.systems_name + '</option>');
                        });
                    },
                    error: function () {
                        alert('Failed to fetch systems.');
                    }
                });
            } else {
                $('#systemsName').empty().append('<option value="" selected>Systems Name</option>');
            }
        });
    });

    document.getElementById('accountType').addEventListener('change', function () {
        const onbehalfFields = document.getElementById('onbehalfFields');
        if (this.value === 'onbehalf') {
            onbehalfFields.style.display = 'block'; // Show the fields
        } else {
            onbehalfFields.style.display = 'none'; // Hide the fields
        }
    });
    function previewFiles(event) {
        const previewsContainer = document.getElementById('previews');
        previewsContainer.innerHTML = ''; // Clear previous previews

        Array.from(event.target.files).forEach(file => {
            const fileType = file.type;

            if (fileType.startsWith('image/')) {
                // Image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.marginRight = '10px';
                    img.style.objectFit = 'cover';
                    previewsContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                // File preview
                const filePreview = document.createElement('div');
                filePreview.textContent = file.name;
                filePreview.style.marginRight = '10px';
                filePreview.style.padding = '5px';
                filePreview.style.border = '1px solid #ddd';
                filePreview.style.borderRadius = '5px';
                filePreview.style.backgroundColor = '#f9f9f9';
                previewsContainer.appendChild(filePreview);
            }
        });
    }
</script>
</body>
</html>
