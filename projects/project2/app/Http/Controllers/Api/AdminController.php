<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Admin;
use App\Models\SubAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    // Create Subadmin
    public function createSubAdmin(Request $request)
    {
         $data=$request->all();
        //  call add function from admin model
         $subAdmin = Admin::addSubadmin($data);
        return response()->json(['message' => 'Subadmin created successfully', 'subadmin' => $subAdmin], 201);
    }

    // Archive Subadmin
    public function archiveSubAdmin($id)
    {
        // Call to function in Admin Model
        $subAdmin = SubAdmin::archive($id);
        if(!$subAdmin){
            return Response::error('Subadmin doesnot exist',404);
        }
        return response()->json(['message' => 'Subadmin archived successfully'], 200);
    }


    public function unarchiveSubAdmin($id)
    {
        // Call to unarchive function in Admin
        $subadmin = SubAdmin::unarchive($id);
        if(!$subadmin){
            return Response::error('Subadmin does not exist',404);
        }
        return response()->json(['message' => 'Subadmin unarchived successfully'], 200);
    }

    public function activateSubAdmin($id)
    {
        $subAdmin=SubAdmin::activate($id);
        if(!$subAdmin){
            return Response::error('subadmin not found',404);
        }
        return response()->json(['message' => 'Subadmin activated successfully'], 200);
    }

    // deactivate subadmin
    public function deactivateSubAdmin($id)
    {
        // call to function from subadmin model
        $subAdmin=SubAdmin::deactivate($id);
        if(!$subAdmin){
            return Response::error('SubAdmin not found',404);
        }

        return response()->json(['message' => 'Subadmin deactivated successfully'], 200);
    }

    public function assignPermissions(Request $request, $id)
    {
        $this->validate($request, [
            'permissions' => 'required|array',
        ]);

        $admin = Admin::findOrFail($id);
        $admin->permissions = $request->permissions;
        $admin->save();

        return response()->json(['message' => 'Permissions assigned successfully', 'admin' => $admin], 200);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == true) {
            return Response::error('User already activated', 401);
        }
        $user->status = true;
        $user->save();

        return response()->json(['message' => 'User activated successfully'], 200);
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == true) {
            $user->status = false;
            $user->save();
            return response()->json(['message' => 'User deactivated successfully'], 200);
        }
        return Response::error('user already deactivated', 401);


    }
}
