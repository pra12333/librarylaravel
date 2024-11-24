<?php

namespace App\Http\Controllers;

use App\Models\RecentActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $errors = session('errors') ? session('errors') : new \Illuminate\Support\MessageBag;
        return view('login', compact('errors'));
    }

    public function login(Request $request)
{ 
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        // Add user role to the description
        $role = $user->role; // Assuming `role` is a field in the user table that stores 'admin', 'superadmin', or 'user'
        
        $data = [
            'user_id' => $user->id,
            'description' => 'User ' . $user->name . ' (' . $role . ') logged in', // Include role in the description
            'expires_at' => Carbon::now()->addHours(48),
        ];

        // dd($data); // Inspect the data before creating the record

        RecentActivity::create($data);

        return redirect()->intended('user/homepage');
    }

    return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
}

    
    public function showRegister()
    {
        return view('normaluserregister');
    }

    public function register(Request $request)
{
    \Log::info('Register function started');

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
    ]);

    $token = Str::random(60);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(8)), // Temporary password
            'email_verified_at' => null,
            'remember_token' => $token,
        ]);

        \Log::info('User registered: ' . $user->email);

        // Send the email with the set password link
        Mail::send('emails.verify-email', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Verify your email address');
        });

        \Log::info('Verification email sent to: ' . $request->email);
    } catch (\Exception $e) {
        \Log::error('Error during registration: ' . $e->getMessage());
    }

    return redirect()->route('login')->with('success', 'We sent you an activation code. Check your email.');
}



    public function verifyEmail($token)
    {
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid token.');
        }

        return view('set-password', ['token' => $token]);
    }


    public function setPassword(Request $request)
    {
        \Log::debug('test');

        $validatedData = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        $user = User::where('remember_token', $request->token)->first();

        \Log::debug('test2');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid token.');
        }
        \Log::debug('test3');

        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->remember_token = null;
        $user->save();

        \Log::debug('test4');

        return redirect()->route('login')->with('success', 'Your password has been set. You can now login.');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Public.bookborrow')->with('success', 'You have been logged out');
    }

    public function topPageLogout(Request $request)
    {
        $successMessage = 'You have been logged out';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Public.bookborrow')->with('success', $successMessage);
    }
}

