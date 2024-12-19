<div class="sidebar shadow-lg">
      <div class="logo-details">
        <i class="bx bx-user"></i>
        @php
                        $user = Auth::user();
                        $user_dept = $user->user_dept;
                    @endphp
        <span class="logo_name">{{ $user->user_dept->dept_name }}</span>
      </div>


      <ul class="side-nav">
    @if($permissions->contains('name', 'Dashboard'))
    <li class="shadow-lg">
        <a href="{{route('dashboard')}}" onclick="setActiveClass(this)" title="Dashboard">
            <i class="bx bx-grid-alt"></i>
            <span class="links_name">Dashboard</span>
        </a>
    </li>
    @endif

    @if($permissions->contains('name', 'New Request'))
    <li class="shadow-lg">
    <a href="{{route('systemsRequests')}}" onclick="setActiveClass(this)" title="New Request">
        <i class='bx bx-add-to-queue'></i>
        <span class="links_name">New Request</span>
    </a>
</li>
    @endif
 

    @if($permissions->contains('name', 'View Open Requests'))
    <li  class="shadow-lg">
        <a href="{{route('OpenRequest')}}" onclick="setActiveClass(this)" title="Open Requests">
        <i class='bx bx-folder-open'></i>
            <span class="links_name">Open Requests</span>
        </a>
    </li>
    @endif
    @if($permissions->contains('name', 'View Approved Requests'))
    <li  class="shadow-lg">
        <a href="{{route('ApprovedRequest')}}" onclick="setActiveClass(this)" title="Approved Requests">
        <i class='bx bx-check-double'></i>
            <span class="links_name">Approved Requests</span>
        </a>
    </li>
    @endif

    @if($permissions->contains('name', 'View Rejected Requests'))
    <li  class="shadow-lg">
        <a href="{{route('rejectedRequests')}}" onclick="setActiveClass(this)" title="Rejected Requests">
        <i class='bx bx-x'></i>
            <span class="links_name">Rejected Requests</span>
        </a>
    </li>
    @endif
    @if($permissions->contains('name', 'Unassigned Requests'))
    <li  class="shadow-lg">
        <a href="{{route('unassignedRequest')}}" onclick="setActiveClass(this)" title="Unassigned Requests">
            <i class='bx bxs-spreadsheet'></i>
            <span class="links_name">Unassigned Requests</span>
        </a>
    </li>
    @endif
    @if($permissions->contains('name', 'Assigned Requests'))
    <li  class="shadow-lg">
        <a href="{{route('assignedRequest')}}" onclick="setActiveClass(this)" title="Assigned Requests">
        <i class='bx bx-mail-send'></i>
            <span class="links_name">Assigned Requests</span>
        </a>
    </li>
    @endif
    @if($permissions->contains('name', 'Request Types'))
    <li class="shadow-lg">
        <a href="{{route('requestType')}}" onclick="setActiveClass(this)" title="Request Types">
        <i class='bx bx-git-pull-request'></i>
            <span class="links_name">Request Types</span>
        </a>
    </li>
    @endif
    @if($permissions->contains('name', 'System Access'))
    <li class="shadow-lg">
        <a href="{{route('requestedSystems')}}" onclick="setActiveClass(this)" title="Request Types">
        <i class='bx bx-plus'></i>
            <span class="links_name">Add Systems</span>
        </a>
    </li>
    @endif
    @if($permissions->contains('name', 'All Requests'))
    <li  class="shadow-lg">
        <a href="{{route('allRequest')}}" onclick="setActiveClass(this)" title="All Requests">
        <i class='bx bx-reply-all' ></i>
            <span class="links_name">All Requests</span>
        </a>
    </li>
    @endif
    @if($permissions->contains('name', 'Main Report'))
    <li  class="shadow-lg">
        <a href="{{route('mainreport')}}" onclick="setActiveClass(this)" title="Main Report">
        <i class='bx bxs-report' ></i>
            <span class="links_name">Main Report</span>
        </a>
    </li>
    @endif
</ul>

    </div>
