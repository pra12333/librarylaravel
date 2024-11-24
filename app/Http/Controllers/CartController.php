<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\BorrowedBook;
use Carbon\Carbon;
use App\Models\User;
class CartController extends Controller
{
    // Function to check if a user has overdue books
    public function hasOverdueBooks($userId) {
        return BorrowedBook::where('user_id', $userId)
                            ->whereNull('returned_at')
                            ->where('borrowed_at', '<', Carbon::now()->subDays(7))
                            ->exists();
    }

    // Add to Cart function (Fix the missing $bookId parameter)
    public function addToCart($bookId) {
        $user = Auth::user();
    
        // Check if the user has overdue books
        if ($this->hasOverdueBooks($user->id)) {
            // Allow adding to the cart but prevent borrowing
            $book = Book::findOrFail($bookId);
            $cartItem = Cart::where('user_id', $user->id)
                            ->where('book_id', $bookId)
                            ->first();
    
            if ($cartItem) {
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'book_id' => $bookId,
                    'quantity' => 1,
                ]);
            }
    
            // Return JSON response for AJAX
            return response()->json(['success' => true, 'cart_count' => Cart::where('user_id', $user->id)->count(), 'message' => 'Book added to cart. You cannot borrow until you return overdue books.']);
        } else {
            // Normal case where user doesn't have overdue books
            $book = Book::findOrFail($bookId);
            $cartItem = Cart::where('user_id', $user->id)
                            ->where('book_id', $bookId)
                            ->first();
    
            if ($cartItem) {
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'book_id' => $bookId,
                    'quantity' => 1,
                ]);
            }
    
            // Return JSON response for AJAX
            return response()->json(['success' => true, 'cart_count' => Cart::where('user_id', $user->id)->count(), 'message' => 'Book added to cart successfully']);
        }
    }
    

    // View Cart function
    public function viewCart() {
        $user = Auth::user();
        $roleAdmin = User::ROLE_ADMIN;
        $roleSuperAdmin = User::ROLE_SUPERADMIN;

        \Log::info('User data:', ['user_id' => $user->id, 'name' => $user->name]);

        // Get all items in the cart for the logged-in user
        $cartItems = Cart::where('user_id', $user->id)
                          ->with('book') // eager load the related book data
                          ->get();

        \Log::info('Cart items:', ['cart_items' => $cartItems]);

        return view('User.cart', compact('cartItems','user','roleAdmin','roleSuperAdmin'));
    }

    // Remove from Cart function
   public function removeFromCart($id) {
    $cartItem = Cart::find($id);

    if($cartItem && $cartItem->user_id === Auth::id()){
        $cartItem->delete();

        // log the success message
        \Log::info('Book removed from the cart successfully', ['cart_item_id' => $cartItem->id]);

        // return a json response for ajax
        return response()->json(['success' => true,'message' => 'Book removed from the cart']);
    }else{
        // log the error message
        \Log::error('User not authorized or cart item not found', ['cart_item_id' => $id]);

        // return a json response with an error for ajax
        return response()->json(['success' => false, 'message' => 'You cannot remove this item']);

    }
   }
    // Borrow items from the cart
    public function borrowCart() {
        $user = Auth::user();

        // Check if the user has overdue books
        if ($this->hasOverdueBooks($user->id)) {
            return redirect()->route('user.viewCart')->with('error', 'You cannot borrow books until you return your overdue books');
        }

        // Get all cart items for the user
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.viewCart')->with('error', 'Your cart is empty');
        }

        foreach ($cartItems as $cartItem) {
            $book = Book::findOrFail($cartItem->book_id);
            if ($book->total_no_of_copies >= $cartItem->quantity) {
                // Reduce the number of available copies
                $book->total_no_of_copies -= $cartItem->quantity;
                $book->save();

                // Create a new borrowed book entry
                BorrowedBook::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'borrowed_at' => Carbon::now(),
                    'returned_at' => null, // not yet returned
                    'no_of_copies' => $cartItem->quantity,
                ]);

                // Remove the item from the cart
                $cartItem->delete();
            } else {
                return redirect()->route('user.viewCart')->with('error', 'Some books are no longer available');
            }
        }

        return redirect()->route('User.myborrow')->with('success', 'Books borrowed successfully');
    }

    // Update Cart function
    public function updateCart(Request $request, $cartId) {
        $user = Auth::user();
        $cartItem = Cart::where('id', $cartId)->where('user_id', $user->id)->first();
    
        if ($cartItem) {
            $book = $cartItem->book;
            if ($request->action === 'increase') {
                if ($cartItem->quantity < $book->total_no_of_copies) {
                    // Increase quantity and save
                    $cartItem->quantity += 1;
                    $cartItem->save();
                    return response()->json(['success' => true, 'newQuantity' => $cartItem->quantity]); // Corrected 'qantity' to 'quantity'
                } else {
                    return response()->json(['success' => false, 'message' => 'Not enough copies available']);
                }
            } elseif ($request->action === 'decrease') {
                if ($cartItem->quantity > 1) {
                    // Decrease quantity and save
                    $cartItem->quantity -= 1;
                    $cartItem->save();
                    return response()->json(['success' => true, 'newQuantity' => $cartItem->quantity]); // Corrected 'qantity' to 'quantity'
                } else {
                    return response()->json(['success' => false, 'message' => 'Quantity cannot be less than 1.']);
                }
            }
        }
    
        return response()->json(['success' => false, 'message' => 'Cart item not found.']);
    }
    
   
                public function getCartCount() {
                $user = Auth::user();
                $cartCount = Cart::where('user_id',$user->id)->count();
                return response()->json(['cart_count' => $cartCount]);

               }   
}
