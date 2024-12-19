<div class="home-content p-3">
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
        <div class="col">
            <div class="row">
                <div class="col-lg p-3">
                    <div class="card shadow-lg bg-white" style="border-radius:10px;overflow:hidden;">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="fs-6 fw-bold">Total Requests</h5>
                                </div>
                                <div class="col-4 mt-4">
                                 <i class='bx bxs-briefcase-alt-2 cart p-2'></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="number">{{ $totalRequests}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg p-3">
                    <div class="card shadow-lg bg-white" style="border-radius:10px;overflow:hidden;">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="fs-6 fw-bold">Open Requests</h5>
                                </div>
                                <div class="col-4 mt-4">
                                <i class='bx bx-folder-open cart p-2'></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="number">{{$OpenRequests }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mt-3">
            <div class="row">
                <div class="col-lg p-3">
                    <div class="card shadow-lg bg-white" style="border-radius:10px;overflow:hidden;">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="fs-6 fw-bold">Approved Requests</h5>
                                </div>
                                <div class="col-4 mt-4">
                                  <i class='bx bx-check-double cart p-2'></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="number">{{$approved}} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
            <div class="row">
                <div class="col-lg p-3">
                    <div class="card shadow-lg bg-white" style="border-radius:10px;overflow:hidden;">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="fs-6 fw-bold">Rejected Requests</h5>
                                </div>
                                <div class="col-4 mt-4">
                                  <i class='bx bx-x cart four p-2'></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="number"> {{$rejectedRequests}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg p-3">
                    <div class="card shadow-lg bg-white" style="border-radius:10px;overflow:hidden;">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="fs-6 fw-bold">Assigned Requests</h5>
                                </div>
                                <div class="col-4 mt-4">
                                 <i class='bx bx-mail-send cart text-success p-2'></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="number">{{ $closedRequests}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>