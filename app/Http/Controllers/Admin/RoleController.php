<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    public function index()
    {
        return Role::all();
    }

    public function store(Request $request)
    {
        try {
            $name = $request->name;
            if (Role::create(['name' => $name])) {
                return response()->json(['status' => 'success', 'message' => 'Role created successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findById($id);
            $name = $request->name;
            if ($role->update(['name' => $name])) {
                return response()->json(['status' => 'success', 'message' => 'Role updated successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findById($id);
            if ($role->delete()) {
                return response()->json(['status' => 'success', 'message' => 'Role deleted successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    //Assigning Permissions to Roles
    public function givePermission(Request $request, $id)
    {
        $role = Role::findById($id);
        if($role->hasPermissionTo($request->permission)){
            return response()->json(['status' => 'error', 'message' => 'exit']);
        }
        $role->givePermissionTo($request->permission);
        return response()->json(['status' => 'success', 'message' => 'successfully']);
    }
}
