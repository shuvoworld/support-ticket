<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's ticket statistics
        $userStats = [
            'total' => Ticket::where('user_id', $user->id)->count(),
            'open' => Ticket::where('user_id', $user->id)
                ->whereHas('status', function ($query) {
                    $query->where('name', 'Open');
                })->count(),
            'in_progress' => Ticket::where('user_id', $user->id)
                ->whereHas('status', function ($query) {
                    $query->where('name', 'In Progress');
                })->count(),
            'closed' => Ticket::where('user_id', $user->id)
                ->whereHas('status', function ($query) {
                    $query->where('name', 'Closed');
                })->count(),
        ];

        // Get recent tickets (last 5)
        $recentTickets = Ticket::where('user_id', $user->id)
            ->with(['status', 'category', 'department'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('userStats', 'recentTickets'));
    }
}