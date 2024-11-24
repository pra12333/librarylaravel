<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\BookRequest;

class AdminController extends Controller
{
    public function showAdminManagement(){
        $admins = Admin::all();
        return view('Admin.usermanagement',compact('admins'));
    }

    public function editAdmin($id) {
        $admin = Admin::findOrFail($id); 
        return view('Admin.editAdmin',compact('admin'));
    }

    public function updateAdmin(Request $request,$id){
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:admins,email,' .$id,
            'password' =>'nullable|string|min:8|confirmed',
        ]);

        $admin =Admin::findOrFail($id);
        $admin->name =$request->input('name');
        $admin->email =$request->input('email');

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->input('password'));
        }

        $admin->save();

        return redirect()->route('Admin.usermanagement')->with('success','Admin updated successfully');
    }

    public function destroyAdmin($id) {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('Admin.usermanagement')->with('success','Admin deleted successfully');
    }
    public function showSuperAdmins()
    {
        // Fetch all superadmins from the database
        $superAdmins = User::where('role', User::ROLE_SUPERADMIN)->get();
    
        // Log the data for debugging
        \Log::info('Rendering superadmin_panel view with data:', ['superAdmins' => $superAdmins]);
    
        // Return the view with the superAdmins data
        return view('Admin.superadmin_panel', compact('superAdmins'));
    }
    

    
}
