<!--  <?php

// namespace App\Http\Controllers;

// use App\Models\Admin;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Carbon;

// class AdminAuthController extends Controller

    // public function showLoginForm(){
    //     return view('Admin.login');

    // }

    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email','password');
          
        //dd($credentials);

    //     if (Auth::guard('admin')->attempt($credentials)) {
    //         // update the last login time
    //         $admin = Auth::guard('admin')->user();
    //         $admin->last_login_at = Carbon::now();
    //         $admin->save();
    //         return redirect()->intended('/admin/usermanagementadmin');
    //     }

    //     return redirect()->back()->withErrors(['error' => 'invalid credentials']);
    // }

    // public function showRegistrationForm(){
    //     if(Auth::guard('admin')->user()->is_super) {
    //         return view('Admin.register');
    //     }

    //     return redirect()->route('admin.dashboard')->with('error','unauthorized');
    // }

    // public function register(Request $request){
    //     if(!Auth::guard('admin')->user()->is_super) {
    //         return redirect()->route('admin.adminmanagement')->with('error','unauthorized');       
    //      }

    //      $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:admins',
    //         'password' => 'required|string|min:8|confirmed',
    //      ]);

    //      Admin::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'is_super' => false, // regular admins are not super
    //      ]);

    //      return redirect()->route('admin.adminmanagement')->with('success','admin registred successfully');
        
    // }

