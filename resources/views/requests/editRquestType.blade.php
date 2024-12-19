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
            <form method="POST" action="{{ route('request-types-update.update', $request_type->id) }}">
    @csrf
    <div class="row mt-3">
        <div class="col-lg">
            <label for="request_name">Request Type</label>
            <input 
                type="text" 
                class="form-control bg-body shadow-sm @error('request_name') is-invalid @enderror" 
                name="request_name" 
                value="{{ old('request_name', $request_type->request_name) }}" 
                required 
                autocomplete="off"
            >
            @error('request_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-lg">
            <label for="priority">Request Priority</label>
            <select 
                class="form-control bg-body @error('priority') is-invalid @enderror" 
                name="priority" 
                required 
                autocomplete="off"
            >
                <option value="severe" {{ old('priority', $request_type->priority) == 'severe' ? 'selected' : '' }}>Severe</option>
                <option value="high" {{ old('priority', $request_type->priority) == 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ old('priority', $request_type->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ old('priority', $request_type->priority) == 'low' ? 'selected' : '' }}>Low</option>
            </select>
            @error('priority')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-lg">
            <label for="ttr_in_hour">Resolution Time (hrs)</label>
            <input 
                type="text" 
                class="form-control bg-body shadow-sm @error('ttr_in_hour') is-invalid @enderror" 
                name="ttr_in_hour" 
                value="{{ old('ttr_in_hour', $request_type->ttr_in_hour) }}" 
                required 
                autocomplete="off"
            >
            @error('ttr_in_hour')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 text-right">
            <button type="submit" class="btn btn-primary shadow-lg">Update Request Type</button>
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
