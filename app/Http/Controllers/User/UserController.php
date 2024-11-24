<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showBorrowedBooks()
    {
        return view('User.bookborrow');
    }

    public function showmyBorrowedBooks()
    {
        return view('User.myborrow');
    }

    public function showBookBorrow(){
        $books = Book::all(); //fetch all the books from the database
        return view('User.bookborrow',compact('books'));
    }
     
    public function showAccount()
    {
        // $user = Auth::user();
        // $roleAdmin = User::ROLE_ADMIN;
        // $roleSuperAdmin = User::ROLE_SUPERADMIN;
        return view('User.account');
    }

    public function showSearch(){
        return view('User.search');
    }


    public function updateProfilePicture(Request $request)
{
    // Check that the method is called
    \Log::info('updateProfilePicture method called');
    
    // Validate the file input
    $request->validate([
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Get authenticated user
    $user = Auth::user();

    // Handle the file upload
    if ($request->hasFile('profile_picture')) {
        \Log::info('File Details', [
            'Original Name' => $request->file('profile_picture')->getClientOriginalName(),
            'Size' => $request->file('profile_picture')->getSize(),
            'Mime Type' => $request->file('profile_picture')->getMimeType(),
        ]);

        // Store the file
        $file = $request->file('profile_picture');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/images', $filename);

        // Update user profile picture
        $user->profile_picture = $filename;
        $user->save();

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    return redirect()->back()->with('error', 'No file uploaded.');
}


    
public function removeProfilePicture()
{
    $user = Auth::user();

    if ($user->profile_picture) {
        // Delete the profile picture from storage
        Storage::delete('public/images/' . $user->profile_picture);

        // Remove profile picture from the user's record
        $user->profile_picture = null;
        $user->save();
    }

    return redirect()->back()->with('success', 'Profile picture removed successfully.');
}

// update profile information

public function updateProfile(Request $request)
{
    // Validation logic
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'phone' => 'nullable|string|regex:/^0\d{9,10}$/|max:11',
    ], [
        'phone.regex' => 'Please enter a valid  phone number',
    ]);

    // Update user's profile data
    $user = Auth::user();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully');
}


    public function updatePassword(Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
    
        // Check if current password matches
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
    
        // Update the password
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();
    
        return redirect()->back()->with('success', 'Password updated successfully.');
    }

}
