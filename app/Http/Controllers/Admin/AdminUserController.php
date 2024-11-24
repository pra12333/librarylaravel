<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminUserController extends Controller
{
    public function showUserManagement(Request $request)
    {
        $query = $request->input('query');

        // Check if there's a search query
        if ($query) {
            // Search users by name
            $users = User::where('name', 'LIKE', "%{$query}%")->get();
        } else {
            // If no search query, fetch all users
            $users = User::all();
        }

        // get the authenticated users name (assumin this is the superadmin)

        $superAdminName = Auth::user()->name;

        return view('Admin.usermanagementadmin', compact('superAdminName','users'));
    }


    public function showUserRegister(){
        $user = Auth::user()->name; // get the name of the logged-in admin
        return view('Admin.userregister',compact('user'));
    }
    public function storeUser(Request $request){
        $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255|unique:users,email',
            'password' =>'required|string|min:8|confirmed',
            'role' => 'required|string|in:' . User::ROLE_ADMIN . ',' . User::ROLE_SUPERADMIN . ',' . User::ROLE_USER,
        ]);
        

        // check if the logged-in user is a superadmin

        if(Auth::user()->role !== User::ROLE_SUPERADMIN) {
            return redirect()->route('Admin.userregister')->with('error','you do not have ');
        }
       
        // create a new user with the role of admin
        $role = $request->input('role');
        \Log::info('Role before creation:', ['role' => $role]);
        
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // hash the password before saving
            'role' => $request->input('role'), // assisgn the role
        ]);

        return redirect()->route('Admin.userregister')->with('success','user registered successfully');
    
       // Find the existing user by email
       $user = User::where('email', $request->input('email'))->first();
    
       if($user) {
            // Update the user's role
            $user->role = $request->input('role');
            $user->save();
    
            // \Log::info('User role updated successfully', ['user_id' => $user->id]);
    
            return redirect()->route('Admin.userregister')->with('success', 'User role updated successfully');
       } else {
            // \Log::error('User not found', ['email' => $request->input('email')]);
            return redirect()->route('Admin.userregister')->with('error', 'User not found');
       }
    }

    public function edit($id){
        $user = User::findOrFail($id);
        $currentUserRole = Auth::user()->role;
    
        if ($currentUserRole === User::ROLE_ADMIN && $user->role === User::ROLE_SUPERADMIN) {
            return redirect()->route('Admin.usermanagementadmin')
                             ->with('error', 'You cannot edit a Super Admin because you are an Admin.');
        }
    
        return view('Admin.editUser', compact('user'));
    }
    
    

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);
    
        $user = User::findOrFail($id);
        $currentUserRole = Auth::user()->role;
    
        // Check if an admin is trying to update a superadmin
        if ($currentUserRole === User::ROLE_ADMIN && $user->role === User::ROLE_SUPERADMIN) {
            return redirect()->route('Admin.usermanagementadmin')
                             ->withErrors('You do not have permission to update this user.');
        }
    
        $user->update($request->all());
    
        return redirect()->route('Admin.usermanagementadmin')->with('success', 'User updated successfully');
    }
    
    

    public function destroy($id){
        $user = User::findOrFail($id);
        $currentUserRole = Auth::user()->role;
    
        if ($currentUserRole === User::ROLE_ADMIN && $user->role === User::ROLE_SUPERADMIN) {
            return redirect()->route('Admin.usermanagementadmin')
                             ->with('error', 'You cannot delete a Super Admin because you are an Admin.');
        }
    
        $user->delete();
    
        return redirect()->route('Admin.usermanagementadmin')->with('success', 'User deleted successfully');
    }
    
    


}    
    