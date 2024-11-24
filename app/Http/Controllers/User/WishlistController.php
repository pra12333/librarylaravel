<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Wishlist;
use App\Models\User;

class WishlistController extends Controller
{
    public function add($bookId)
    {
        \Log::info('Received bookId:', ['book_id' => $bookId]);
    
        $user = Auth::user();
        $book = Book::find($bookId);
    
        if (!$book) {
            \Log::error('Book not found for ID: ' . $bookId);
            return response()->json(['success' => false, 'message' => 'Book not found.']);
        }
    
        if ($user->wishlists()->where('book_id', $bookId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Book is already in your wishlist']);
        }
    
        $user->wishlists()->create([
            'book_id' => $bookId,
        ]);
    
        return response()->json(['success' => true, 'message' => 'Book added to the wishlist']);
    }

    public function index() {
        // fetch the books from the wishlist for the authenticated user
        $user = Auth::user();
        $roleAdmin = User::ROLE_ADMIN;
        $roleSuperAdmin = User::ROLE_SUPERADMIN;
        $userId = Auth::id();
        $wishlistBooks = Wishlist::where('user_id',$userId)
         ->with('book')
         ->get();

         // pass the book to the view
         return view('User.wishlist',compact('wishlistBooks','user','roleAdmin','roleSuperAdmin'));
    }

    public function remove(Request $request,$id) {
        $userId = Auth::id(); // get the current logged in user id

        // find the wishlist item for the given book and the user
        $wishlistItem = Wishlist::where('user_id',$userId)->where('book_id',$id)->first();

        if ($wishlistItem) {
            $wishlistItem->delete(); // remove the item from the wishlist
            return redirect()->route('wishlist.index')->with('success','book removed from wishlist successfully');
        }

        return redirect()->route('wishlist.index')->with('error','Book not found in the wishlist');
    }
}
