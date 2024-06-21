<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SubAdmin extends Admin
{
    use HasFactory;

    protected $table = 'admins';

    // Subadmin creation
    public static function addSubadmin($data)
    {
        return parent::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'subadmin',
            'permissions' => $data['permissions'],
            'status' => true,
        ]);

    }

    // Archive subadmin
    public static function archive($id)
    {
        // Find subadmin where role is subadmin
        $subAdmin = parent::where('role', 'subadmin')->find($id);
        // verifying subadmin exists
        if (!$subAdmin) {
            return false;
        }
        // Delete subadmin
        $subAdmin->delete();
        return $subAdmin;
    }

    // unarchive subadmin
    public static function unarchive($id)
    {
        // fetch only trashed users where id matches the given id
        $subAdmin = parent::onlyTrashed()->where('role', 'subadmin')->find($id);
        if (!$subAdmin) {
            return false;
        }
        // restore the trashed subadmin
        $subAdmin->restore();
        return $subAdmin;
    }

    // activate subadmin
    public static function activate($id)
    {
        // search subadmin with id and status false
        $subAdmin = parent::where('status', false)->find($id);
        if (!$subAdmin) {
            return false;
        }
        // update subadmin status
        $subAdmin->status = true;
        $subAdmin->save();
        return $subAdmin;
    }

    // deactivate subadmin
    public static function deactivate($id)
    {
        // find subadmin with id and status true
        $subAdmin = parent::where('status', true)->find($id);
        if (!$subAdmin) {
            return false;
        } else {
            // update subadmin status
            $subAdmin->status = false;
            $subAdmin->save();
            return $subAdmin;
        }
    }
}
