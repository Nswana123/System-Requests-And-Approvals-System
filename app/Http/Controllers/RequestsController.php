<?php
namespace App\Mail;
namespace App\Http\Controllers;
use App\Mail\RequestApprovalNotification;
use App\Mail\RequestAssignmentMail;
use App\Mail\RequestRejectedMail;
use App\Mail\RequestCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\user_dept;
use App\Models\request_type;
use App\Models\request_tbl;
use App\Models\approval_tbl;
use App\Models\assignment_tbl;
use App\Models\attachment;
use App\Models\systems_name_tbl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class RequestsController extends Controller
{
    //
    public function systemsRequests(){
        $request_type = request_type::get();
        $system_name = systems_name_tbl::get();
        
        $user_dept = user_dept::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
       
        return view('requests.systemsRequests', compact('user_dept','permissions','request_type','system_name'));
    }
    public function filterSystemsByRequestType(Request $request) {
        $request_type_id = $request->input('request_type_id');
        $systems = systems_name_tbl::where('request_type_id', $request_type_id)->get();
        return response()->json($systems);
    }
    public function RequestStore(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'request_type_id' => 'required|exists:request_type,id',
        'systems_name_id'=> 'nullable|exists:systems_name_tbl,id',
        'account_type' => 'required|string|in:Own,onbehalf',
    'fname' => 'nullable|string|max:255',
        'lname' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'mobile' => 'nullable|string|max:20',
        'department' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:200048',
    ]);
    
    // Generate a case ID for the request reference
    $request_refference = $this->generateCaseId();
    
    // Save the request to the `request_tbl`
    $request_type = request_tbl::create([
        'request_refference' => $request_refference,
        'request_type_id' => $validated['request_type_id'],
        'systems_name_id' => $validated['systems_name_id'],
        'account_type' => $validated['account_type'],
        'fname' => $validated['account_type'] === 'onbehalf' ? $validated['fname'] : null,
        'lname' => $validated['account_type'] === 'onbehalf' ? $validated['lname'] : null,
        'email' => $validated['account_type'] === 'onbehalf' ? $validated['email'] : null,
        'mobile' => $validated['account_type'] === 'onbehalf' ? $validated['mobile'] : null,
        'department' => $validated['account_type'] === 'onbehalf' ? $validated['department'] : null,
        'position' => $validated['account_type'] === 'onbehalf' ? $validated['position'] : null,
        'description' => $validated['description'],
        'user_id' => Auth::id(),
    ]);

    // Check for attachments and store them
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $originalName = $file->getClientOriginalName(); // Get the original file name
            $path = $file->store('attachments', 'public'); // Store files in the public storage
    
            Attachment::create([
                'request_id' => $request_type->id,
                'file_path' => $path, // Save the file path
                'file_name' => $originalName, // Save the original file name
            ]);
        }
    }

    $loggedInUser = Auth::user();

// Get the department name of the logged-in user
$deptName = user_dept::where('id', $loggedInUser->dept_id)->value('dept_name');

// Fetch users in the same department name with the position 'HOD'
$users = User::join('user_dept', 'users.dept_id', '=', 'user_dept.id')
    ->where('user_dept.dept_name', $deptName) // Match department name
    ->where('user_dept.position', 'HOD')     // Position is HOD
    ->select('users.email')                  // Fetch only the email column
    ->get();
    // Prepare email data
    $data = [
        'requestReference' => $request_type->request_refference,
        'requestType' => $request_type->requestType->request_name,
        'priority' => $request_type->requestType->priority,
        'resolutionTime' => $request_type->requestType->ttr_in_hour,
        'accountType' => $request_type->account_type,
        'requestedBy' => $request_type->account_type === 'onbehalf'
            ? "{$request_type->fname} {$request_type->lname}"
            : auth()->user()->name,
        'description' => $request_type->description,
        'editLink' => route('requests.editOpenRequest', ['id' => $request_type->id]),
    ];
    
    // Collect the emails of the recipients
    $recipientEmails = [];
    
    // Send the email to filtered users
    foreach ($users as $user) {
        Mail::to($user->email)->send(new RequestCreated($data));
        $recipientEmails[] = $user->email; // Collect the email addresses
    }
    
    // Convert the emails into a string
    $emailsSent = implode(', ', $recipientEmails);
    
    // Success message with recipient emails
    $successMessage = 'Request submitted successfully! Ref No. ' . $request_type->request_refference . 
        '. Email sent to: ' . $emailsSent;
    
    // Redirect back with success message
    return redirect()->back()
        ->with('success', $successMessage);
}
    
    private function generateCaseId()
    {
        try {
            $prefix = 'Zed'; 
    
            do {
                $numeric_id = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
                $dateTimeSuffix = date('YmdHi');
                $request_refference = $prefix . $numeric_id . $dateTimeSuffix;
            
            } while (request_tbl::where('request_refference', $request_refference)->exists());
    
            return $request_refference;
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to generate Request ID. ' . $e->getMessage());
        }
    }
    public function requestType(){
        $request_type = request_type::get();
        $user_dept = user_dept::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
       
        return view('requests.requestType', compact('user_dept','permissions','request_type'));
    }
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'request_name' => 'required|string|max:255',
            'priority' => 'required|in:severe,high,medium,low',
            'ttr_in_hour' => 'required|numeric|min:1',
        ]);

        // Create a new RequestType entry
        request_type::create([
            'request_name' => $validated['request_name'],
            'priority' => $validated['priority'],
            'ttr_in_hour' => $validated['ttr_in_hour'],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Request type added successfully.');
    }
    public function editRquestType($id){
        $request_type = request_type::findOrFail($id);
        $user_dept = user_dept::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
       
        return view('requests.editRquestType', compact('user_dept','permissions','request_type'));
    }
    public function update(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'request_name' => 'required|string|max:255',
            'priority' => 'required|in:severe,high,medium,low',
            'ttr_in_hour' => 'required|numeric|min:1',
        ]);
    
        // Find and update the request type
        $request_type = request_type::findOrFail($id);
        $request_type->update([
            'request_name' => $validated['request_name'],
            'priority' => $validated['priority'],
            'ttr_in_hour' => $validated['ttr_in_hour'],
        ]);
    
        return redirect()->route('requestType')->with('success', 'Request type updated successfully.');

    }
    public function requestedSystems(){
        $request_type = request_type::get();
        $systems_name = systems_name_tbl::with('systemsName')->get();
        $user_dept = user_dept::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
       
        return view('requests.requestedSystems', compact('user_dept','permissions','systems_name','request_type'));
    }
    public function storeRequestedSystems(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'systems_name' => 'required|string|max:255',
            'request_type_id' => 'required',
        
        ]);

        // Create a new RequestType entry
        systems_name_tbl::create([
            'systems_name' => $validated['systems_name'],
            'request_type_id' => $validated['request_type_id'],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Systems Name added successfully.');
    }
    public function editRequestedSystems($id){
        $systems_name = systems_name_tbl::findOrFail($id);
        $request_type = request_type::get();
        $user_dept = user_dept::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
       
        return view('requests.editRequestedSystems', compact('user_dept','permissions','systems_name','request_type'));
    }
    public function updateRequestedSystems(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'systems_name' => 'required|string|max:255',
            'request_type_id' => 'required',
        
        ]);
    
        // Find and update the request type
        $systems_name = systems_name_tbl::findOrFail($id);
        $systems_name->update([
           'systems_name' => $validated['systems_name'],
            'request_type_id' => $validated['request_type_id'],
        ]);
    
        return redirect()->route('requestedSystems')->with('success', 'Request type updated successfully.');

    }
    public function OpenRequest(){
     $requestReference = request()->input('request_refference');

        // Build the query
        $loggedInUser = Auth::user();

        // Fetch the department name for the logged-in user
        $deptName = user_dept::where('id', $loggedInUser->dept_id)->value('dept_name');
        
        // Fetch open requests for users in the same department name
        $dept_request_type = request_tbl::with(['requestType', 'user'])
            ->whereHas('user', function ($query) use ($deptName) {
                $query->whereHas('department', function ($subQuery) use ($deptName) {
                    $subQuery->where('dept_name', $deptName); // Filter by dept_name
                });
            })
            ->where('status', 'open') // Only open requests
            ->when($requestReference, function ($query, $requestReference) {
                return $query->where('request_refference', $requestReference);
            })
            ->get();

        $request_types = request_tbl::with(['requestType', 'user'])
            ->when($requestReference, function ($query, $requestReference) {
                return $query->where('request_refference', $requestReference);
            })->where('status','open')
            ->get();
        $user_dept = user_dept::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
       
        return view('requests.OpenRequest', compact('user_dept','permissions','request_types','dept_request_type'));
    }
    public function editOpenRequest($id){
        $record  = request_tbl::findOrFail($id);
        $request_type = request_type::all(); 
        $user_dept = user_dept::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
       
        return view('requests.editOpenRequest', compact('user_dept','permissions','request_type','record'));
    }
    public function download($id)
{
    $attachment = Attachment::findOrFail($id);

    return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
}
public function ApprovedRequest(){
    $requestReference = request()->input('request_refference');

      
           $loggedInUser = Auth::user();

        // Fetch the department name for the logged-in user
        $deptName = user_dept::where('id', $loggedInUser->dept_id)->value('dept_name');
        
        // Fetch open requests for users in the same department name
        $dept_request_type = request_tbl::with(['requestType', 'user'])
            ->whereHas('user', function ($query) use ($deptName) {
                $query->whereHas('department', function ($subQuery) use ($deptName) {
                    $subQuery->where('dept_name', $deptName); // Filter by dept_name
                });
            })
            ->where('status', 'first_approval') // Only open requests
            ->when($requestReference, function ($query, $requestReference) {
                return $query->where('request_refference', $requestReference);
            })
            ->get();

       $request_types = request_tbl::with(['requestType', 'user'])
           ->when($requestReference, function ($query, $requestReference) {
               return $query->where('request_refference', $requestReference);
           })
           ->where('status','first_approval')
           ->get();
       $user_dept = user_dept::get();
       $user = auth()->user();
       $permissions = $user->user_dept->permissions;
       $userId = auth()->id();
      
       return view('requests.ApprovedRequest', compact('user_dept','permissions','request_types','dept_request_type'));
   }
   public function updateOpenRequest(Request $request, $id)
{
    try{
        $validator = Validator::make($request->all(), [
            
           
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }
    
   $record = request_tbl::findOrFail($id);
        $action = $request->input('action');
        if ($action == 'approved') {
           $record->status = "first_approval";
            
           // Save to int_ticket_resolutions first
           $approval = approval_tbl::create([
            'request_id' => $id, 
            'approver_id' => Auth::id(), 
            'status' => "first_approval",
        ]);

        $this->notifyDepartmentUsers($record);
    }

       $record->save();

        if ($action == 'approved') {
            return redirect('/requests.OpenRequest')->with('success', 'Request Approved successfully.');
        } 
    }
        catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to load Approved Request. ' . $e->getMessage());
        } 
}
private function notifyDepartmentUsers($record)
{
    $users = User::join('user_dept', 'users.dept_id', '=', 'user_dept.id')
    ->where(function ($query) {
        $query->where('user_dept.dept_name', 'super admin')
              ->orWhere('user_dept.dept_name', 'IT System Admin');
    })
    ->where('user_dept.position', 'HOD')
    ->select('users.*')
    ->get();
    $emailData = [
        'requestReference' => $record->request_refference,
        'requestType'      => $record->requestType->request_name,
        'priority'         => $record->requestType->priority,
        'resolutionTime'   => $record->requestType->ttr_in_hour,
        'accountType'      => $record->account_type,
        'requestedBy'      => $record->user_requested_by,
        'description'      => $record->description,
       'editLink' => route('requests.editFirstApproval', ['id' => $record->id]),
    ];

    foreach ($users as $user) {
        Mail::to($user->email)->send(new RequestApprovalNotification($emailData));
    }
    }

public function requestRejection(Request $request) 
{


    try {
        $request->validate([
            'request_id' => 'required|exists:request_tbl,id',
        ]);

        $request_type = request_tbl::find($request->request_id);

        if ($request_type) {
            $request_type->status = 'rejected';
            $request_type->comment = $request->comment;
            $request_type->save();

            approval_tbl::create([
                'request_id' => $request->request_id,
                'approver_id' => Auth::id(),
                'status' => "rejected",
            ]);
        // Send the rejection email to the user who requested the request
        $user = $request_type->user;  // Access the user via the defined relationship
        if ($user) {
            // Send the rejection email
            Mail::to($user->email)->send(new RequestRejectedMail($request_type));

            // Include the user's email in the success message
            $email = $user->email;
        } else {
            $email = 'No email found for the user.';
        }

        // Redirect back with a success message including the user's email
        return redirect('/requests.OpenRequest')->with('success', "Request has been rejected and an email was sent to {$email}.");
    }

    } catch (\Exception $e) {
        return redirect()->back()->withErrors('Failed to reject request. ' . $e->getMessage());
    }
}
public function rejectedRequests(){
    $requestReference = request()->input('request_refference');

    
           $loggedInUser = Auth::user();

        // Fetch the department name for the logged-in user
        $deptName = user_dept::where('id', $loggedInUser->dept_id)->value('dept_name');
        
        // Fetch open requests for users in the same department name
        $dept_request_type = request_tbl::with(['requestType', 'user'])
            ->whereHas('user', function ($query) use ($deptName) {
                $query->whereHas('department', function ($subQuery) use ($deptName) {
                    $subQuery->where('dept_name', $deptName); // Filter by dept_name
                });
            })
            ->where('status', 'rejected') // Only open requests
            ->when($requestReference, function ($query, $requestReference) {
                return $query->where('request_refference', $requestReference);
            })
            ->get();

       $request_types = request_tbl::with(['requestType', 'user',  'approvals.user'])
           ->when($requestReference, function ($query, $requestReference) {
               return $query->where('request_refference', $requestReference);
           })
           ->where('status','rejected')
           ->get();
       $user_dept = user_dept::get();
       $user = auth()->user();
       $permissions = $user->user_dept->permissions;
       $userId = auth()->id();
      
       return view('requests.rejectedRequests', compact('user_dept','permissions','request_types','dept_request_type'));
   }
   public function editRejectedRequest($id){
    $record  = request_tbl::findOrFail($id);
    $request_type = request_type::all(); 
    $user_dept = user_dept::get();
    $user = auth()->user();
    $permissions = $user->user_dept->permissions;
    $userId = auth()->id();
   
    return view('requests.editRejectedRequest', compact('user_dept','permissions','request_type','record'));
}
public function editFirstApproval($id){
    $record  = request_tbl::findOrFail($id);
    $request_type = request_type::all(); 
    $user_dept = user_dept::get();
    $user = auth()->user();
    $permissions = $user->user_dept->permissions;
    $userId = auth()->id();
   
    return view('requests.editFirstApproval', compact('user_dept','permissions','request_type','record'));
}
public function updateFirstApproval(Request $request, $id)
{
    try{
        $validator = Validator::make($request->all(), [
            
           
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }
    
   $record = request_tbl::findOrFail($id);
        $action = $request->input('action');
        if ($action == 'approved') {
           $record->status = "second_approval";
            
           // Save to int_ticket_resolutions first
           $approval = approval_tbl::create([
            'request_id' => $id, 
            'approver_id' => Auth::id(), 
            'status' => "second_approval",
        ]);
    }

       $record->save();

        if ($action == 'approved') {
            return redirect('requests.unassignedRequest')->with('success', 'Request Approved successfully.');
        } 
    }
        catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to load Approved Request. ' . $e->getMessage());
        } 
}
public function unassignedRequest(){
    $requestReference = request()->input('request_refference');

    $request_types = request_tbl::with(['requestType', 'user',  'approvals.user'])
    ->when($requestReference, function ($query, $requestReference) {
        return $query->where('request_refference', $requestReference);
    })
    ->where('status','second_approval')
    ->get();
       $user_dept = user_dept::get();
       $user = auth()->user();
       $permissions = $user->user_dept->permissions;
       $userId = auth()->id();
      
       return view('requests.unassignedRequest', compact('user_dept','permissions','request_types'));
   }
   public function editUnassignedRequest($id){
    $record  = request_tbl::findOrFail($id);
    $request_type = request_type::all(); 
    $user_dept = user_dept::get();
    $user = auth()->user();
    $permissions = $user->user_dept->permissions;
    $userId = auth()->id();
   
    $users = DB::table('users')
    ->join('user_dept', 'users.dept_id', '=', 'user_dept.id')
    ->whereIn('user_dept.dept_name', ['IT System Admin', 'super admin'])
    ->select('users.*')
    ->get();
   

    return view('requests.editUnassignedRequest', compact('user_dept','permissions','request_type','record','users'));
}
public function RequestAssignment(Request $request)
{
    try {
        $request->validate([
            'request_id' => 'required|exists:request_tbl,id',
        ]);

        $request_type = request_tbl::find($request->request_id);

        if ($request_type) {
            $request_type->status = 'assigned';
            $request_type->save();

            assignment_tbl::create([
                'request_id' => $request->request_id,
                'assigned_user' => $request->assigned_user,
            ]);

            $user = User::find($request->assigned_user);

            if ($user && $user->email) {
                // Send the email
                Mail::to($user->email)->send(new RequestAssignmentMail($request_type, $user));

                // Include the user's email in the success message
                $email = $user->email;
            } else {
                $email = 'No email found for the user.';
            }

            return redirect('/requests.unassignedRequest')->with('success', "Request has been Assigned and an email was sent to {$email}.");
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', "Failed to assign request. " . $e->getMessage());
    }
}
public function assignedRequest(){
    $requestReference = request()->input('request_refference');

       
    $loggedInUser = Auth::user();

    $dept_request_type = request_tbl::with(['requestType', 'user'])
    ->where('status', 'assigned') // Only open requests
    ->whereHas('assignments', function ($query) use ($loggedInUser) {
        $query->where('assigned_user', $loggedInUser->id); // Match the logged-in user with assigned_user
    })
        ->when($requestReference, function ($query, $requestReference) {
            return $query->where('request_refference', $requestReference);
        })
        ->get();

       $request_types = request_tbl::with(['requestType', 'user',  'approvals.user'])
           ->when($requestReference, function ($query, $requestReference) {
               return $query->where('request_refference', $requestReference);
           })
           ->where('status','assigned')
           ->get();
       $user_dept = user_dept::get();
       $user = auth()->user();
       $permissions = $user->user_dept->permissions;
       $userId = auth()->id();
      
       return view('requests.assignedRequest', compact('user_dept','permissions','request_types','dept_request_type'));
   }
   public function editAssignedRequest($id){
    $record  = request_tbl::findOrFail($id);
    $request_type = request_type::all(); 

    $user_dept = user_dept::get();
    $user = auth()->user();
    $permissions = $user->user_dept->permissions;
    $userId = auth()->id();
   
    return view('requests.editAssignedRequest', compact('user_dept','permissions','request_type','record'));
} 
public function updateAssignedRequests(Request $request)
{
    try {
        $request->validate([
            'request_id' => 'required|exists:request_tbl,id',
        ]);

        $request_type = request_tbl::find($request->request_id);

        if ($request_type) {
            $request_type->status = 'closed';
            $request_type->access_duration = $request->access_duration;
            $request_type->closed_by = Auth::id();
            $request_type->comment = $request->comment;
            $request_type->assigned_username = $request->assigned_username;
            $request_type->assigned_role = $request->assigned_role;
            $request_type->save();

          

            
            return redirect('/requests.assignedRequest')->with('success', "Request has been Closed Successufully");
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', "Failed to close request. " . $e->getMessage());
    }
}

public function deactivate($id)
{
    $access = request_tbl::findOrFail($id);
    $access->access_state = 'Inactive';  // Set to Inactive
    $access->save();

    return redirect()->back()->with('success', 'Access has been deactivated.');
}

public function activate($id)
{
    $access = request_tbl::findOrFail($id);
    $access->access_state = 'Active';  // Set to Active
    $access->save();

    return redirect()->back()->with('success', 'Access has been activated.');
}
public function allRequest()
{
    $requestReference = request()->input('request_refference');

    $loggedInUser = Auth::user();

// Fetch the department name for the logged-in user
$deptName = user_dept::where('id', $loggedInUser->dept_id)->value('dept_name');

// Query for department-specific requests
$dept_request_type_query = request_tbl::with(['requestType', 'user', 'closedBy', 'approvals.user'])
    ->whereHas('user', function ($query) use ($deptName) {
        $query->whereHas('department', function ($subQuery) use ($deptName) {
            $subQuery->where('dept_name', $deptName); // Filter users by dept_name
        });
    })
    ->when(request('start_date') && request('end_date'), function ($query) {
        // Filter by date range if provided
        return $query->whereBetween('created_at', [request('start_date'), request('end_date')]);
    }, function ($query) {
        // Default to weekly data if no date filter is applied
        return $query->whereBetween('created_at', [now()->subWeek()->startOfDay(), now()->endOfDay()]);
    })
    ->when(request('request_refference'), function ($query) {
        // Filter by request reference if provided
        return $query->where('request_refference', request('request_refference'));
    });

    // Query for all requests
    $request_types_query = request_tbl::with(['requestType', 'user', 'closedBy', 'approvals.user'])
        ->when($requestReference, function ($query, $requestReference) {
            return $query->where('request_refference', $requestReference);
        })
        ->when(request('start_date') && request('end_date'), function ($query) {
            return $query->whereBetween('created_at', [request('start_date'), request('end_date')]);
        }, function ($query) {
            // Default to weekly data if no date filter is applied
            return $query->whereBetween('created_at', [now()->subWeek()->startOfDay(), now()->endOfDay()]);
        });

    // Get results and counts
    $dept_request_type = $dept_request_type_query->get();
    $dept_request_type_count = $dept_request_type_query->count();

    $request_types = $request_types_query->get();
    $request_types_count = $request_types_query->count();

    // Fetch other data
    $user_dept = user_dept::get();
    $user = auth()->user();
    $permissions = $user->user_dept->permissions;
    $userId = auth()->id();

    // Pass counts and data to the view
    return view('requests.allRequest', compact(
        'user_dept', 
        'permissions', 
        'request_types', 
        'dept_request_type', 
        'dept_request_type_count', 
        'request_types_count'
    ));
}
   public function mainreport(){
    $requestReference = request()->input('request_refference');

       // Build the query

       $request_types_query = request_tbl::with(['requestType', 'user', 'closedBy', 'approvals.user'])
       ->when($requestReference, function ($query, $requestReference) {
           return $query->where('request_refference', $requestReference);
       })
       ->when(request('start_date') && request('end_date'), function ($query) {
           return $query->whereBetween('created_at', [request('start_date'), request('end_date')]);
       }, function ($query) {
           // Default to weekly data if no date filter is applied
           return $query->whereBetween('created_at', [now()->subWeek()->startOfDay(), now()->endOfDay()]);
       })
       ->when(request('status'), function ($query, $status) {
           return $query->where('status', $status);
       })
       ->when(request('request_type_id'), function ($query, $requestTypeId) {
           return $query->where('request_type_id', $requestTypeId);
       });
   
   // Get the filtered results
   $request_types = $request_types_query->get();
   
   // Get the count of the filtered results
   $request_types_count = $request_types_query->count();
       $user_dept = user_dept::get();
       $user = auth()->user();
       $permissions = $user->user_dept->permissions;
       $userId = auth()->id();
       $types = request_type::get();
      
       return view('requests.mainreport', compact('user_dept','permissions','request_types','types','request_types_count'));
   }

   public function editAllRequest($id){
    $record  = request_tbl::findOrFail($id);
    $request_type = request_type::all(); 
    $user_dept = user_dept::get();
    $user = auth()->user();
    $permissions = $user->user_dept->permissions;
    $userId = auth()->id();
   
    return view('requests.editAllRequest', compact('user_dept','permissions','request_type','record'));
} 
}
