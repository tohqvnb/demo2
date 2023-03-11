<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    //
    public function index()
    {
        return Permission::all();
    }

    public function store(Request $request)
    {
        try {
            $name = $request->name;
            if (Permission::create(['name' => $name])) {
                return response()->json(['status' => 'success', 'message' => 'Permission created successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $Permission = Permission::findById($id);
            $name = $request->name;
            if ($Permission->update(['name' => $name])) {
                return response()->json(['status' => 'success', 'message' => 'Permission updated successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $Permission = Permission::findById($id);
            if ($Permission->delete()) {
                return response()->json(['status' => 'success', 'message' => 'Permission deleted successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
