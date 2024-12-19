<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\request_tbl;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;  
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
     public function getSidebarData()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $permissions = $user->user_dept->permissions;
        $totalRequests = request_tbl::whereDate('created_at', $today)->count();
        $closedRequests = request_tbl::where('status', 'closed')
            ->count();
        $OpenRequests = request_tbl::where('status', 'open')
            ->count();
        $approved = request_tbl::whereIn('status', ['first_approval','second_approval'])
            ->count();
            $rejectedRequests = request_tbl::where('status', 'rejected')
            ->count();
      
        return view('dashboard', compact('permissions','totalRequests','closedRequests','OpenRequests','approved','rejectedRequests'));
    }
}
