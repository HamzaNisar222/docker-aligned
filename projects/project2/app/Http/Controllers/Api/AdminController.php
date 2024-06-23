<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use App\Models\SubAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    // Create Subadmin
    public function createSubAdmin(Request $request)
    {
        $data = $request->all();
        //  call add function from admin model
        $subAdmin = Admin::addSubadmin($data);
        return response()->json(['message' => 'Subadmin created successfully', 'subadmin' => $subAdmin], 201);
    }

    // Archive Subadmin
    public function archiveSubAdmin($id)
    {
        // Call to function in Admin Model
        $subAdmin = SubAdmin::archive($id);
        if (!$subAdmin) {
            return Response::error('Subadmin doesnot exist', 404);
        }
        return response()->json(['message' => 'Subadmin archived successfully'], 200);
    }
    // Unarchive Subadmin
    public function unarchiveSubAdmin($id)
    {
        // Call to unarchive function in Admin
        $subadmin = SubAdmin::unarchive($id);
        if (!$subadmin) {
            return Response::error('Subadmin does not exist', 404);
        }
        return response()->json(['message' => 'Subadmin unarchived successfully'], 200);
    }
    // Activate subadmin
    public function activateSubAdmin($id)
    {
        $subAdmin = SubAdmin::activate($id);
        if (!$subAdmin) {
            return Response::error('subadmin not found', 404);
        }
        return response()->json(['message' => 'Subadmin activated successfully'], 200);
    }
    // deactivate subadmin
    public function deactivateSubAdmin($id)
    {
        // call to function from subadmin model
        $subAdmin = SubAdmin::deactivate($id);
        if (!$subAdmin) {
            return Response::error('SubAdmin not found', 404);
        }

        return response()->json(['message' => 'Subadmin deactivated successfully'], 200);
    }
    // Assign permissions
    public function assignPermissions(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->permissions = $request->permissions;
        $admin->save();

        return response()->json(['message' => 'Permissions assigned successfully', 'admin' => $admin], 200);
    }
    // Activate users
    public function activateUser($id)
    {
        //call to function from admin model
        Admin::activateUser($id);
    }
    // deactivate user
    public function deactivateUser($id)
    {
        //call to function from admin model
        Admin::deactivateUser($id);
    }
    // Delete User
    public function deleteUser($id)
    {
        //call to function from admin model
        User::deleteUser($id);
    }
}
