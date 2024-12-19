<?php

namespace App\Http\Controllers;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\user_dept;
use App\Models\permissions;
use App\Models\role_permissions;
use App\Models\User;
use App\Models\ticket_sla;
use App\Models\ticket_category;
use App\Models\tickets;
use App\Models\attachment;
use App\Models\user_tickets;
use App\Models\assigned_tickets;
use App\Models\ticket_resolutions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
class SettingsController extends Controller
{
    // user group
    public function userGroup(){
        $user_dept = user_dept::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
       
        return view('settings.usergroup', compact('user_dept','permissions'));
    }
    public function createUserGroups(Request $request)
    {
        try {
            $request->validate([
                'dept_name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
            ]);

            $user_dept = new user_dept();
            $user_dept->dept_name = $request->input('dept_name');
            $user_dept->position = $request->input('position');
            $user_dept->save();

            return redirect()->back()->with('success', 'user Department created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to create user Department. ' . $e->getMessage());
        }
    }
    public function DeleteUserGroup($id)
    {
        $userGroup = user_dept::findOrFail($id);
        $userGroup->delete();
        return redirect()->route('settings.usergroup')->with('success', 'user Department deleted successfully.');
    }
    
    public function editUserGroup($id)
    {
        try {
            $user_dept = user_dept::findOrFail($id);
            $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
            return view('settings.editUsergroup', compact('user_dept','permissions'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to edit user Department. ' . $e->getMessage());
        }
    }
    public function updateUserGroup(Request $request, $id)
    {
        try {
            $request->validate([
             
            ]);
            $user_dept = user_dept::findOrFail($id);
            $user_dept->dept_name = $request->input('dept_name');
            $user_dept->position =$request->input('position');
            $user_dept->save();
            return redirect()->route('settings.usergroup')->with('success', 'user Department updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to update user Department. ' . $e->getMessage());
        }
    }
    // user permissions
    public function userRole(){
        $user_role = permissions::get();
        $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
        return view('settings.userRole', compact('user_role','permissions'));
    }
    public function createUserRole(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);
$names = explode(',', $request->input('name'));
foreach ($names as $name) {
    $trimmedName = trim($name);
    $user_role = new permissions();
    $user_role->name = $trimmedName;
    $user_role->description = $request->input('description');
    $user_role->save();
}

            return redirect()->back()->with('success', 'User role created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to create user Role. ' . $e->getMessage());
        }
    }
    public function DeleteUserRole($id)
    {
        $userRole = permissions::findOrFail($id);
        $userRole->delete();
        return redirect()->route('settings.userRole')->with('success', 'User Role deleted successfully.');
    }
    
    public function editUserRole($id)
    {
        try {
            $user_role = permissions::findOrFail($id);
            $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
            return view('settings.editUserRole', compact('user_role','permissions'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to edit user role. ' . $e->getMessage());
        }
    }
    public function updateUserRole(Request $request, $id)
    {
        try {
            $request->validate([
             
            ]);
            $user_role = permissions::findOrFail($id);
            $user_role->name = $request->input('name');
            $user_role->description=$request->input('description');
            $user_role->save();
            return redirect()->route('settings.userRole')->with('success', 'User role updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to update user role. ' . $e->getMessage());
        }
    }
    // user management
    public function user()
    {
        try {
            $users = User::with('user_dept')->get();
            $groups =user_dept::all();
            $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
            return view('settings.user', compact('groups','users','permissions'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to load users. ' . $e->getMessage());
        }
    }
    public function createUser(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'mobile' => 'required|string|max:10',
                'email' => 'required|email|max:255|unique:users,email',
                'location' => 'required|string',
                'work_location' => 'required|string',
                'dept_id' => 'required|exists:user_dept,id', // Ensure group exists
                'password' => 'required|string|min:8', // password confirmation
            ]);
            $plainPassword = $validatedData['password'];
            // Create the user
            $user = new User;
            $user->fname = $validatedData['fname'];
            $user->lname = $validatedData['lname'];
            $user->mobile = $validatedData['mobile'];
            $user->email = $validatedData['email'];
            $user->location = $validatedData['location'];
            $user->work_location = $validatedData['work_location'];
            $user->dept_id = $validatedData['dept_id'];
            $user->password = Hash::make($plainPassword); // Hash the password
            $user->save();
    
            // Send email notification with plain password
            Mail::to($user->email)->send(new UserCreatedMail($user, $plainPassword));
            return redirect()->back()->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to load users. ' . $e->getMessage());
        }
    }
    public function editUser($id)
    {
        try {
            $users = User::findOrFail($id);
            $groups =user_dept::all();
            $user = auth()->user();
        $permissions = $user->user_dept->permissions;
        $userId = auth()->id();
            return view('settings.editUser', compact('users','groups','permissions'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to edit user role. ' . $e->getMessage());
        }
    }
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $request->validate([
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'mobile' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'location' => 'required|string',
                'work_location' => 'required|string',
                'dept_id' => 'required|integer',
                'password' => 'nullable|string|min:8',
            ]);

            $user->fname = $request->input('fname');
            $user->lname = $request->input('lname');
            $user->mobile = $request->input('mobile');
            $user->email = $request->input('email');
            $user->location = $request->input('location');
            $user->work_location = $request->input('work_location');
            $user->dept_id = $request->input('dept_id');
    
           // Check if password is provided
           if ($request->filled('password')) {
            $plainPassword = $request->input('password'); // Capture plain password
            $user->password = Hash::make($plainPassword); // Hash the password for storage

            // Send email notification with plain password
            Mail::to($user->email)->send(new UserCreatedMail($user, $plainPassword));
        }
    
            $user->save();
            return redirect()->route('settings.user')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to update user group. ' . $e->getMessage());
        }
    }
    public function deactivate($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = 'inactive';
            $user->save();
            return redirect()->back()->with('success', 'User deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to deactivate user. ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = 'active';
            $user->save();
            return redirect()->back()->with('success', 'User activated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to activate user. ' . $e->getMessage());
        }
    }
    // systems settings
    public function systemSettings()
    {
        try {
            $roles = role_permissions::with('user_dept', 'permissions')->get();
            $groups =user_dept::all();
            $permission =permissions::all();
            $user = auth()->user();
            $permissions = $user->user_dept->permissions;
            $userId = auth()->id();
            return view('settings.systemSettings', compact('groups','roles','permission','permissions'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to load users. ' . $e->getMessage());
        }
    }
    public function createSettings(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_dept_id' => 'required|exists:user_dept,id',
                'permission_id' => 'required|array', 
             'permission_id.*' => 'exists:permissions,id',
            ]);
        
            $userGroupId = $request->input('user_dept_id');
            $permissionIds = $request->input('permission_id');
          
            foreach ($permissionIds as $permissionId) {
                DB::table('role_permissions')->updateOrInsert(
                    [
                        'user_dept_id' => $userGroupId,
                        'permission_id' => $permissionId
                    ]
                );
            }
            // Redirect back with a success message
            return back()->with('success', 'Role successfully assigned.');
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to load users. ' . $e->getMessage());
        }
    }
    public function DeleteSettings($id)
    {
        $userRole = role_permissions::findOrFail($id);
        $userRole->delete();
        return redirect()->route('settings.systemSettings')->with('success', 'User Role deleted successfully.');
    }
    //sidebar
    public function getSidebarData()
{
    $user = auth()->user();
    $permissions = $user->user_dept->permissions; // Get all permissions for the user group
    return view('/settings.systemSettings', compact('permissions'));
}

}
