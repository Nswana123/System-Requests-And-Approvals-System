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

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card bg-body shadow-sm">
            <div class="card-header p-3 text-white text-center" style="border-radius: 5px; background-color: #007bff;">
                <h4>Update Request Type</h4>
            </div>
            <div class="card-body">
            <form method="POST" action="{{ route('request-types-update.updateRequestedSystems', $systems_name->id) }}">
    @csrf
    @method('PUT') <!-- Use PUT for updating -->
    <div class="row mt-3">
        <!-- System Name Input -->
        <div class="col-lg">
            <label for="systems_name" class="form-label">System Name</label>
            <input 
                type="text" 
                class="form-control bg-body shadow-sm @error('systems_name') is-invalid @enderror" 
                name="systems_name" 
                value="{{ old('systems_name', $systems_name->systems_name) }}" 
                required 
                autocomplete="off" 
                placeholder="Enter the system name">
            @error('systems_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Request Type Dropdown -->
        <div class="col-lg">
            <label for="request_type_id" class="form-label">Request Type</label>
            <select 
                class="form-control bg-body shadow-sm @error('request_type_id') is-invalid @enderror" 
                name="request_type_id" 
                id="sla_id" 
                required>
                <option value="" disabled>Select Request Type</option>
                @foreach($request_type as $type)
                    <option 
                        value="{{ $type->id }}" 
                        {{ old('request_type_id', $systems_name->request_type_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->request_name }}
                    </option>
                @endforeach
            </select>
            @error('request_type_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="row mt-4">
        <div class="col text-end">
            <button type="submit" class="btn btn-primary shadow-lg">
                Update Systems Access
            </button>
        </div>
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
<script>
    
</script>
</body>
</html>
