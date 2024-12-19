
<!DOCTYPE html>
<html>
<head>
<title>Systems Requests And Approvals</title>
    @include('dashboard.style')
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/dashboard/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        </style>
</head>
<body>
<x-app-layout>
 
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
    <div class="col">
        <div class="card bg-body shadow-sm">
            <div class="card-body">
            <form action="{{ route('settings.update', $user_dept->id) }}" method="POST">
    @csrf
    @method('PUT') <!-- Important for updating a record -->

    <div class="row">
        <div class="col">
            <label>User Department Name</label>
            <input type="text" 
                   class="form-control bg-body shadow-sm @error('dept_name') is-invalid @enderror" 
                   name="dept_name" 
                   value="{{ old('dept_name', $user_dept->dept_name) }}"  required autocomplete="off"> <!-- Use old() with fallback -->
                 
            @error('dept_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col">
            <label>User Position</label>
            <input type="text" 
                   class="form-control bg-body shadow-sm @error('position') is-invalid @enderror" 
                   name="position" 
                   value="{{ old('position', $user_dept->position) }}"  required autocomplete="off"> <!-- Use old() with fallback -->
                  
            @error('poeition')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <button type="submit" class="btn btn-primary float-end">Update</button>
        </div>
    </div>
</form>

            </div>
        </div>
    </div>
</div> 
</div>
@include('dashboard.script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>    
    </script>
    </x-app-layout>
</body>
</html>
