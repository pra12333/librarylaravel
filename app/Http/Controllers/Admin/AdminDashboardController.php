<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\RecentActivity;
use App\Models\ReservedBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\BookRequest;


class AdminDashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        // fetch the currently authenticated user

        $user = Auth::user();

        // fetch all pending book requests

        // $bookRequests = BookRequest::where('status','pending')->get();
        // $pendingCount = BookRequest::where('status','pending')->count();

        // fetch super admin details 

        $superadmin = User::where('role','SuperAdmin')->first();
        // Fetch filters from the request
        $userFilter = $request->input('user_filter');
        $bookFilter = $request->input('book_filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query for filtering users based on the selected role
        $usersQuery = User::query();
        if ($userFilter) {
            $usersQuery->where('role', $userFilter);
        }
        $totalUsers = $usersQuery->count();

        // Query for filtering books based on the selected genre or other criteria
        $booksQuery = Book::query();
        if ($bookFilter) {
            $booksQuery->where('genreid', $bookFilter);
        }
        if ($startDate && $endDate) {
            $booksQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $totalBooks = $booksQuery->count();

        // Query for filtering borrowed books
        $borrowedBooksQuery = BorrowedBook::query();
        if ($startDate && $endDate) {
            $borrowedBooksQuery->whereBetween('borrowed_at', [$startDate, $endDate]);
        }
        if ($userFilter) {
            $borrowedBooksQuery->whereHas('user', function($query) use ($userFilter) {
                $query->where('role', $userFilter);
            });
        }
        if ($bookFilter) {
            $borrowedBooksQuery->whereHas('book', function($query) use ($bookFilter) {
                $query->where('genreid', $bookFilter);
            });
        }
        $borrowedBooks = $borrowedBooksQuery->count();

        // Query for filtering reserved books
        $reservedBooksQuery = ReservedBook::query();
        if ($startDate && $endDate) {
            $reservedBooksQuery->whereBetween('reserved_at', [$startDate, $endDate]);
        }
        if ($userFilter) {
            $reservedBooksQuery->whereHas('user', function($query) use ($userFilter) {
                $query->where('role', $userFilter);
            });
        }
        if ($bookFilter) {
            $reservedBooksQuery->whereHas('book', function($query) use ($bookFilter) {
                $query->where('genreid', $bookFilter);
            });
        }
        $reservedBooks = $reservedBooksQuery->count();

        // Query for filtering overdue books
        $overdueBooksQuery = BorrowedBook::query();
        if ($startDate && $endDate) {
            $overdueBooksQuery->whereBetween('borrowed_at', [$startDate, $endDate]);
        }
        $overdueBooks = $overdueBooksQuery
            ->whereNull('returned_at')
            ->where('borrowed_at', '<', now()->subDays(7))
            ->count();

        // Fetch recent activities (this is a placeholder)
        $recentActivities = RecentActivity::where('expires_at','>',Carbon::now())
                           ->orderBy('created_at','desc')
                           ->get();
              

        // Fetch unique roles and genres for filtering options
        $userRoles = User::select('role')->distinct()->get();
        $bookGenres = Book::select('genreid')->distinct()->get();

        return view('Admin.dashboard', compact(
            'totalUsers', 
            'totalBooks', 
            'borrowedBooks', 
            'reservedBooks', 
            'overdueBooks',
            'userRoles',
            'bookGenres',
            'recentActivities',
            // 'bookRequests',
            // 'pendingCount'

        ));
    }
}
