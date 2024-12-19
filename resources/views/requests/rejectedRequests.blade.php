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


@php
                    $user = Auth::user();
                    $user_dept = $user->user_dept;
                @endphp
                @if(!($user_dept && ($user_dept->dept_name === 'super admin' || $user_dept->dept_name === 'IT System Admin')))
<div class="row">
    <div class="col-lg-10 mx-auto">
  
<div class="row mt-3">
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
            <div class="card-header p-3 text-white" style="border-radius: 5px; background-color: #007bff;">
                
                <div class="row">
                <div class="col">
                <h4>Department Rejected Requests</h4>
                </div>
                <div class="col float-left">
                <button type="button" class=" float-end btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  <i class='bx bx-filter'></i> Filter
</button>  
    </div>
  

            </div>
            </div>
              <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Your filter options go here -->
                    <form method="GET" action="">
                        
            
                        <div class="row">
                    
                        <div class="col-lg-12">
                            <label class="form-label">Request Refference</label>
                            <input type="text" class="form-control" name="request_refference">
                        </div>
                   
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
                </form>
    </div>
  </div>
    </div>
            <div class="card-body" style="height:750px">
                <div class="row">
                    <div class="outer-wrapper">
                        <div class="table-wrapper">
                        @if($dept_request_type->isEmpty())
            <p class="text-center">No available Data
        @else 
                        <table class="table table-hover" id="my-table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Request Reference</th>
            <th scope="col">Request Type</th>
            <th scope="col">System Name</th>
            <th scope="col">Priority</th>
            <th scope="col">Resolution Time</th>
            <th scope="col">Account Type</th>
            <th scope="col">Description</th>
            <th scope="col">Requested By</th>
            <th scope="col">Requested At</th>
            <th scope="col">Rejected By</th>
            <th scope="col">Rejected At</th>
            <th scope="col">Rejected Reason</th>
            
        
            <th scope="col">Full Name</th>
                <th scope="col">Email</th>
                <th scope="col">Mobile</th>
                <th scope="col">Department</th>
                <th scope="col">Position</th>
           
        </tr>
    </thead>
    <tbody>
        @foreach ($dept_request_type as $request_type)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <a href="{{ route('requests.editRejectedRequest', $request_type->id) }}" class="custom-link" title="Request Reference">{{ $request_type->request_refference }}</a>
                </td>
                <td>{{ $request_type->requestType->request_name }}</td>
                <td>{{ $request_type->systemsName->systems_name ?? 'N/A' }}</td>
                <td>
                @if($request_type->requestType )
                                                @if($request_type->requestType->priority == 'severe')
                                                    <span class="badge badge-danger">Severe</span>
                                                @elseif($request_type->requestType->priority == 'high')
                                                    <span class="badge badge-warning">High</span>
                                                @elseif($request_type->requestType->priority == 'medium')
                                                    <span class="badge badge-warning">Medium</span>
                                                @else
                                                    <span class="badge badge-success">Low</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary">N/A</span> <!-- If no SLA or category -->
                                            @endif
                </td>
                <td>{{ $request_type->requestType->ttr_in_hour }} hours</td>
                <td>{{ $request_type->account_type }}</td>
                <td>{{ Str::limit($request_type->description, 20, ' ...') }}</td>
                <td>
                    @if ($request_type->account_type === 'onbehalf')
                        {{ $request_type->fname ?? 'N/A' }} {{ $request_type->lname ?? 'N/A' }}
                    @else
                        {{ $request_type->user->fname ?? 'N/A' }} {{ $request_type->user->lname ?? 'N/A' }}
                    @endif
                </td>
                <td>{{ $request_type->created_at }}</td>
                @if ($request_type->approvals->isNotEmpty())
    <td>{{ $request_type->approvals->last()->user->fname }} {{ $request_type->approvals->last()->user->lname }}</td>
@else
    <td>No approval found</td>
@endif
                <td>
            @if ($request_type->approvals->isNotEmpty())
                {{ $request_type->approvals->last()->created_at->format('Y-m-d H:i:s') }}
            @else
                N/A
            @endif
        </td>
                <td>{{ $request_type->comment }}</td>
                @if ($request_type->account_type === 'onbehalf')
                <td>{{ $request_type->fname ?? 'N/A' }} {{ $request_type->fname ?? 'N/A' }}</td>
                    <td>{{ $request_type->email ?? 'N/A' }}</td>
                    <td>{{ $request_type->mobile ?? 'N/A' }}</td>
                    <td>{{ $request_type->department ?? 'N/A' }}</td>
                    <td>{{ $request_type->position ?? 'N/A' }}</td>
                @else
                <td>{{ $request_type->user->fname ?? 'N/A' }} {{ $request_type->user->lname ?? 'N/A' }}</td>
        <td>{{ $request_type->user->email ?? 'N/A' }}</td>   
        <td>{{ $request_type->user->mobile ?? 'N/A' }}</td>  
        <td>{{ $request_type->user->user_dept->dept_name ?? 'N/A' }}</td>   
        <td>{{ $request_type->user->user_dept->position ?? 'N/A' }}</td>  
                @endif
            </tr>
        @endforeach
    </tbody>
                                </table>

                                @endif       
                        </div>
                    </div>
                </div>
    
            </div>
    </div>
    </div>  
</div>
@else
<div class="row mt-3">
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
            <div class="card-header p-3 text-white" style="border-radius: 5px; background-color: #007bff;">
                
                <div class="row">
                <div class="col">
                <h4>All Rejected Requests</h4>
                </div>
                <div class="col float-left">
                <button type="button" class=" float-end btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  <i class='bx bx-filter'></i> Filter
</button>  
    </div>
  

            </div>
            </div>
              <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Your filter options go here -->
                    <form method="GET" action="">
                        
            
                        <div class="row">
                    
                        <div class="col-lg-12">
                            <label class="form-label">Request Refference</label>
                            <input type="text" class="form-control" name="request_refference">
                        </div>
                   
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
                </form>
    </div>
  </div>
    </div>
            <div class="card-body" style="height:750px">
                <div class="row">
                    <div class="outer-wrapper">
                        <div class="table-wrapper">
                        @if($request_types->isEmpty())
            <p class="text-center">No available Data
        @else    
                        <table class="table table-hover" id="my-table">
    <thead>
        <tr>
        <th scope="col">#</th>
            <th scope="col">Request Reference</th>
            <th scope="col">Request Type</th>
            <th scope="col">System Name</th>
            <th scope="col">Priority</th>
            <th scope="col">Resolution Time</th>
            <th scope="col">Account Type</th>
            <th scope="col">Description</th>
            <th scope="col">Requested By</th>
            <th scope="col">Requested At</th>
            <th scope="col">Rejected By</th>
            <th scope="col">Rejected At</th>
            <th scope="col">Rejected Reason</th>
            <th scope="col">Full Name</th>
                <th scope="col">Email</th>
                <th scope="col">Mobile</th>
                <th scope="col">Department</th>
                <th scope="col">Position</th>
          
        </tr>
    </thead>
    <tbody>
        @foreach ($request_types as $request_type)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <a href="{{ route('requests.editRejectedRequest', $request_type->id) }}" class="custom-link" title="Request Reference">{{ $request_type->request_refference }}</a>
                </td>
                <td>{{ $request_type->requestType->request_name }}</td>
                <td>{{ $request_type->systemsName->systems_name ?? 'N/A' }}</td>
                <td>
                @if($request_type->requestType )
                                                @if($request_type->requestType->priority == 'severe')
                                                    <span class="badge badge-danger">Severe</span>
                                                @elseif($request_type->requestType->priority == 'high')
                                                    <span class="badge badge-warning">High</span>
                                                @elseif($request_type->requestType->priority == 'medium')
                                                    <span class="badge badge-warning">Medium</span>
                                                @else
                                                    <span class="badge badge-success">Low</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary">N/A</span> <!-- If no SLA or category -->
                                            @endif
                </td>
                <td>{{ $request_type->requestType->ttr_in_hour }} hours</td>
                <td>{{ $request_type->account_type }}</td>
                <td>{{ Str::limit($request_type->description, 20, ' ...') }}</td>
                <td>
                    @if ($request_type->account_type === 'onbehalf')
                        {{ $request_type->fname ?? 'N/A' }} {{ $request_type->lname ?? 'N/A' }}
                    @else
                        {{ $request_type->user->fname ?? 'N/A' }} {{ $request_type->user->lname ?? 'N/A' }}
                    @endif
                </td>
                <td>{{ $request_type->created_at }}</td>
                @if ($request_type->approvals->isNotEmpty())
    <td>{{ $request_type->approvals->last()->user->fname }} {{ $request_type->approvals->last()->user->lname }}</td>
@else
    <td>No approval found</td>
@endif
                <td>
            @if ($request_type->approvals->isNotEmpty())
                {{ $request_type->approvals->last()->created_at->format('Y-m-d H:i:s') }}
            @else
                N/A
            @endif
        </td>
                <td>{{ $request_type->comment }}</td>
                @if ($request_type->account_type === 'onbehalf')
                <td>{{ $request_type->fname ?? 'N/A' }} {{ $request_type->fname ?? 'N/A' }}</td>
                    <td>{{ $request_type->email ?? 'N/A' }}</td>
                    <td>{{ $request_type->mobile ?? 'N/A' }}</td>
                    <td>{{ $request_type->department ?? 'N/A' }}</td>
                    <td>{{ $request_type->position ?? 'N/A' }}</td>
                @else
                <td>{{ $request_type->user->fname ?? 'N/A' }} {{ $request_type->user->lname ?? 'N/A' }}</td>
        <td>{{ $request_type->user->email ?? 'N/A' }}</td>   
        <td>{{ $request_type->user->mobile ?? 'N/A' }}</td>  
        <td>{{ $request_type->user->user_dept->dept_name ?? 'N/A' }}</td>   
        <td>{{ $request_type->user->user_dept->position ?? 'N/A' }}</td>  
                @endif
            </tr>
        @endforeach
    </tbody>
                                </table>

                       @endif        
                        </div>
                    </div>
                </div>
    
            </div>
    </div>
    </div>  
</div>
@endif
</div>
</div>
@include('dashboard.script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    
</script>
</body>
</html>
