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
    .attachment-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        
    }
    .attachment-item {
        flex: 1 0 100px; /* Flex grow, shrink, and basis */
        max-width: 100px;
        text-align: center;
    }
    .attachment-item img {
        width: 100px;
        height: 100px;
        object-fit: contain; /* Ensures the image fits the box */
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .attachment-item a {
        display: block;
        padding: 5px;
        text-decoration: none;
        color: #007bff;
    }
    .attachment-item img {
        width: 150px;
        height: 150px;
        object-fit: contain;
        transition: transform 0.5s ease-in-out; /* Smooth transition for zooming */
    }

    .attachment-item img:hover {
        transform: scale(5); /* Zoom in by 1.5 times when hovered */
    }
    .collapse {
    transition: height 300s ease;
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
                <div class="row">
                    <div class="col">
                        Created At: {{$record->created_at}}
                    </div>
                    <div class="col">
                        Request By: {{$record->user->fname}} {{$record->user->lname}}
                    </div>
                    <div class="col">
                        Request Refference: {{$record->request_refference}}
                    </div>
                </div>
            </div>
            <div class="card-body">
            <form > 
            <div class="row mt-3">
        <!-- Request Type -->
        <div class="col-lg-6">
            <label for="requestType">Request Type</label>
            <select class="form-control bg-body shadow-sm" name="request_type_id" id="sla_id">
                <option value="" selected>Select Request Type</option>
                @foreach($request_type as $type)
                    <option value="{{ $type->id }}" 
                        {{ (old('request_type_id') ?? $record->request_type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->request_name }}
                    </option>
                @endforeach
            </select>
            @error('request_type_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Requested Account -->
        <div class="col-lg-6">
            <label for="accountType">Requested Account</label>
            <select class="form-select bg-body shadow-sm" name="account_type" id="accountType">
                <option value="">Select Requested Account</option>
                <option value="Own" 
                    {{ (old('account_type') ?? $record->account_type ?? '') == 'Own' ? 'selected' : '' }}>Own Account</option>
                <option value="onbehalf" 
                    {{ (old('account_type') ?? $record->account_type ?? '') == 'onbehalf' ? 'selected' : '' }}>Not Own Account</option>
            </select>
            @error('account_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Own fields -->
    <div  class="mt-3" style="display: {{ (old('account_type') ?? $record->account_type ?? '') == 'Own' ? 'block' : 'none' }};">
        <div class="row">
            <div class="col-lg-6">
                <label for="fname">First Name</label>
                <input type="text" class="form-control bg-body shadow-sm @error('fname') is-invalid @enderror" 
                       name="fname" id="fname" 
                       value="{{ old('fname') ?? $record->user->fname ?? '' }}">
                @error('fname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control bg-body shadow-sm @error('lname') is-invalid @enderror" 
                       name="lname" id="lname" 
                       value="{{ old('lname') ?? $record->user->lname ?? '' }}">
                @error('lname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-6">
                <label for="fname">Email</label>
                <input type="text" class="form-control bg-body shadow-sm @error('email') is-invalid @enderror" 
                       name="email" id="email" 
                       value="{{ old('email') ?? $record->user->email ?? '' }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6">
                <label for="lname">Mobile</label>
                <input type="text" class="form-control bg-body shadow-sm @error('mobile') is-invalid @enderror" 
                       name="mobile" id="mobile" 
                       value="{{ old('mobile') ?? $record->user->mobile ?? '' }}">
                @error('mobile')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-6">
                <label for="fname">Department</label>
                <input type="text" class="form-control bg-body shadow-sm @error('department') is-invalid @enderror" 
                       name="department" id="department" 
                       value="{{ old('department') ?? $record->user->user_dept->dept_name ?? '' }}">
                @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6">
                <label for="lname">Position</label>
                <input type="text" class="form-control bg-body shadow-sm @error('position') is-invalid @enderror" 
                       name="position" id="position" 
                       value="{{ old('position') ?? $record->user->user_dept->position ?? '' }}">
                @error('position')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <!-- Remaining on-behalf fields -->
        <!-- ...repeat for email, mobile, department, position... -->
    </div>
     <!-- On behalf fields -->
     <div  class="mt-3" style="display: {{ (old('account_type') ?? $record->account_type ?? '') == 'onbehalf' ? 'block' : 'none' }};">
        <div class="row">
            <div class="col-lg-6">
                <label for="fname">First Name</label>
                <input type="text" class="form-control bg-body shadow-sm @error('fname') is-invalid @enderror" 
                       name="fname" id="fname" 
                       value="{{ old('fname') ?? $record->fname ?? '' }}">
                @error('fname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control bg-body shadow-sm @error('lname') is-invalid @enderror" 
                       name="lname" id="lname" 
                       value="{{ old('lname') ?? $record->lname ?? '' }}">
                @error('lname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-6">
                <label for="fname">Email</label>
                <input type="text" class="form-control bg-body shadow-sm @error('email') is-invalid @enderror" 
                       name="email" id="email" 
                       value="{{ old('email') ?? $record->email ?? '' }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6">
                <label for="lname">Mobile</label>
                <input type="text" class="form-control bg-body shadow-sm @error('mobile') is-invalid @enderror" 
                       name="mobile" id="mobile" 
                       value="{{ old('mobile') ?? $record->mobile ?? '' }}">
                @error('mobile')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-6">
                <label for="fname">Department</label>
                <input type="text" class="form-control bg-body shadow-sm @error('department') is-invalid @enderror" 
                       name="department" id="department" 
                       value="{{ old('department') ?? $record->department ?? '' }}">
                @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6">
                <label for="lname">Position</label>
                <input type="text" class="form-control bg-body shadow-sm @error('position') is-invalid @enderror" 
                       name="position" id="position" 
                       value="{{ old('position') ?? $record->position ?? '' }}">
                @error('position')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <!-- Remaining on-behalf fields -->
        <!-- ...repeat for email, mobile, department, position... -->
    </div>


    <!-- Request Description -->
    <div class="row mt-4">
    <!-- Left Column -->
    <div class="col-lg-6">
        <!-- Request Type -->
        <div class="mb-3">
            <label for="requestType" class="form-label">Systems Name</label>
            <input type="text" class="form-control bg-body shadow-sm @error('systems_name') is-invalid @enderror" 
                       name="systems_name" id="systems_name" 
                       value="{{ old('systemsName->systems_name') ?? $record->systemsName->systems_name ?? '' }}">
                @error('systems_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
        </div>

        <!-- Request Description -->
        <div class="mb-3">
        <label for="description">Request Description</label>
            <textarea class="form-control bg-body shadow-sm" 
                      name="description" rows="4" id="description">{{ old('description') ?? $record->description ?? '' }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
</div>

        <!-- Attachments -->
        <div class="col-lg-6">
        <fieldset class="border p-4 mb-4">
    <legend class="w-auto">Attachments</legend>

    <div class="mb-3">
        <!-- Display existing attachments -->
        @if($record->attachments->isNotEmpty())
                <div class="attachment-container mt-2" style="display: flex; flex-wrap: wrap; gap: 10px;">
                @foreach($record->attachments as $attachment)
            @php
                $filePath = 'storage/' . $attachment['file_path'];
                
                $fileExtension = pathinfo($attachment['file_name'], PATHINFO_EXTENSION);
            @endphp

            @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                <!-- Display image preview if the attachment is an image -->
                <div class="attachment-item">
       
        <div>
            <a href="{{ route('attachments.download', $attachment['id']) }}" >
            <img src="{{ asset($filePath) }}" alt="Attachment" style="max-width: 100px; max-height: 100px;" title="click to download">
            </a>
        </div>
    </div>
            @elseif(in_array($fileExtension, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']))
                <!-- Display as a link for other file types -->
                <div class="attachment-item">
                    <a href="{{ asset($filePath) }}" target="_blank">{{ $attachment['file_name'] ?? 'Download File' }}</a>
                </div>
            @else
                <!-- Fallback for unknown or unsupported file types -->
                <div class="attachment-item">
                    <p>Unsupported file type: {{ $fileExtension }}</p>
                </div>
            @endif
        @endforeach
    </div>
@else
                <p>No attachments available.</p>
        @endif
    </div>
</fieldset>

                        </div>
                    </div>

                    <div class="row mt-4">
                      
                        <div class="col-12 text-center">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTicketModal" data-id="{{ $record->id }}" title="Assign Ticket">
                         Close Request</a>
                        </div>
                       
                    </div>
                    </form>
                    <div class="modal fade" id="assignTicketModal" tabindex="-1" aria-labelledby="assignTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTicketModalLabel">Request Assigned</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignTicketForm" action="{{ route('updateAssignedRequests') }}" method="POST">
                    @csrf
                    <input type="hidden" name="request_id" id="request_id" value="{{ $record->id ?? '' }}">
                    <div class="row">
                        <div class="col-12">
                        <label for="dateTime">Accesss Duration Till</label>
                        <input type="date" id="date" name="access_duration" class="form-control bg-body shadow-sm" value="{{ old('access_duration') }}">
                <small id="dateTimeHelp" class="form-text text-muted">Please select the date</small>
                @error('access_duration')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                        </div>
                    </div>
                   
                    <div class="row mt-3">
                        <div class="col">
                        <textarea name="comment" class="form-control" placeholder="Enter remarks..."></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">

                <div class="col-lg-6">
                <label for="username">Assigned UserName</label>
            <input type="text" class="form-control bg-body shadow-sm @error('assigned_username') is-invalid @enderror" name="assigned_username" id="assigned_username" autocomplete="off">
            @error('assigned_username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
                </div>
                <div class="col-lg-6">
                <label for="assigned_role">Assigned Role</label>
                            <select class="form-control bg-body shadow-sm" name="assigned_role" id="assigned_role">
    <option value="" selected>Select User Role</option>
    @foreach($user_dept as $role)
        <option value="{{ $role->dept_name }}" {{ old('assigned_role') == $role->dept_name ? 'selected' : '' }}>
            {{ $role->dept_name }}
        </option>
    @endforeach
</select>
@error('assigned_role')
    <span class="text-danger">{{ $message }}</span>
@enderror
                    </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Close Request</button>
            </div>
            </form>
        </div>
    </div>
</div>
 

       
            </div>
        </div>
    </div>
</div>

</div>
</div>
@include('dashboard.script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>

    document.getElementById('accountType').addEventListener('change', function () {
        const onbehalfFields = document.getElementById('onbehalfFields');
        if (this.value === 'onbehalf') {
            onbehalfFields.style.display = 'block'; // Show the fields
        } else {
            onbehalfFields.style.display = 'none'; // Hide the fields
        }
    });
    function previewMultipleImages(event) {
        const previewsContainer = document.getElementById('previews');
        previewsContainer.innerHTML = ''; // Clear previous previews

        const files = event.target.files;

        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            const file = files[i];

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.maxWidth = '100px';
                img.style.height = '100px';
                img.style.marginRight = '10px';
                img.style.marginTop = '10px';
                previewsContainer.appendChild(img);
            };

            // Read each selected file
            reader.readAsDataURL(file);
        }
    }
</script>
</body>
</html>
