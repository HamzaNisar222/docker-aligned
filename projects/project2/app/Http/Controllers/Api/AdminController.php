<?php
namespace App\Http\Controllers\Api;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function createSubAdmin(Request $request)
    {
        $subAdmin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'subadmin',
            'permissions' => json_encode($request->permissions),
            'status' => true,
        ]);

        return response()->json(['message' => 'Subadmin created successfully', 'subadmin' => $subAdmin], 201);
    }

    public function deleteSubAdmin($id)
    {
        $subAdmin = Admin::findOrFail($id);
        if ($subAdmin->role == 'subadmin') {
            $subAdmin->delete();
        } else {
            return Response::error('cant delete ADMIN', 403);
        }

        return response()->json(['message' => 'Subadmin deleted successfully'], 200);
    }

    public function activateSubAdmin($id)
    {
        $subAdmin = Admin::findOrFail($id);
        if ($subAdmin->status == true) {
            return Response::error('SubAdmin Already Active', 400);
        }
        $subAdmin->status = true;
        $subAdmin->save();

        return response()->json(['message' => 'Subadmin activated successfully'], 200);
    }

    public function deactivateSubAdmin($id)
    {
        $subAdmin = Admin::findOrFail($id);
        if ($subAdmin->status == true) {
            $subAdmin->status = false;
            $subAdmin->save();

        } else {
            return Response::error('Admin Already Inactive', 400);
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
