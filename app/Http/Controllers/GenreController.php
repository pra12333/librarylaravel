<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GenreController extends Controller
{
    public function create() {

        $adminUser = Auth::user();
        return view('Admin.genres.create',compact('adminUser'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Genre::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success','Genre added successfully');
}


}