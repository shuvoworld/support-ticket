<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Middleware is applied in the routes file

    /**
     * Display a listing of the user's tickets.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole(['super_admin', 'admin', 'agent'])) {
            // Admins and agents can see tickets based on their department assignments
            if ($user->hasRole(['super_admin', 'admin'])) {
                // Super admins and admins can see all tickets
                $tickets = Ticket::with(['status', 'category', 'department', 'user', 'latestComment'])
                ->withCount(['comments' => function ($query) {
                    $query->where('is_internal', false);
                }])
                ->latest()
                ->paginate(10);
            } else {
                // Agents can only see tickets from their assigned departments
                $agentDepartmentIds = $user->departments->pluck('id');
                $tickets = Ticket::where(function ($query) use ($agentDepartmentIds) {
                    $query->whereIn('department_id', $agentDepartmentIds)
                          ->orWhereNull('department_id'); // Also see unassigned tickets
                })
                ->with(['status', 'category', 'department', 'user', 'latestComment'])
                ->withCount(['comments' => function ($query) {
                    $query->where('is_internal', false);
                }])
                ->latest()
                ->paginate(10);
            }
        } else {
            // Regular users can only see their own tickets
            $tickets = Ticket::where('user_id', $user->id)
                ->with(['status', 'category', 'department', 'latestComment'])
                ->withCount(['comments' => function ($query) {
                    $query->where('is_internal', false);
                }])
                ->latest()
                ->paginate(10);
        }

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $categories = Category::all();
        $departments = Department::where('is_active', true)->get();
        return view('tickets.create', compact('categories', 'departments'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'category_id' => 'required|exists:categories,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        // Get the "Open" status
        $openStatus = Status::where('name', 'Open')->first();

        $ticket = Ticket::create([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'priority' => $validated['priority'],
            'category_id' => $validated['category_id'],
            'department_id' => $validated['department_id'] ?? null,
            'user_id' => Auth::id(),
            'status_id' => $openStatus->id, // Default to Open status
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        // Check if the user owns this ticket or is an agent/admin
        $isOwner = $ticket->user_id === $user->id;
        $isAgent = $user->hasRole(['super_admin', 'admin', 'agent']);

        if (!$isOwner && !$isAgent) {
            abort(403);
        }

        // For agents, check if they have access to this ticket's department
        if ($isAgent && !$user->hasRole(['super_admin', 'admin'])) {
            // Non-admin agents can only see tickets from their assigned departments
            $agentDepartmentIds = $user->departments->pluck('id');

            if ($ticket->department_id && !in_array($ticket->department_id, $agentDepartmentIds->toArray())) {
                abort(403, 'You do not have access to tickets from this department.');
            }
        }

        $ticket->load(['status', 'category', 'department', 'comments' => function ($query) use ($isOwner) {
            if ($isOwner) {
                // Users can only see public comments
                $query->where('is_internal', false);
            }
            // Agents can see all comments
            $query->latest();
        }, 'comments.user']);

        return view('tickets.show', compact('ticket', 'isOwner', 'isAgent'));
    }

    /**
     * Add a comment to a ticket.
     */
    public function addComment(Request $request, Ticket $ticket)
    {
        // Check if the user owns this ticket or is an agent/admin
        $isOwner = $ticket->user_id === Auth::id();
        $isAgent = Auth::user()->hasRole(['super_admin', 'admin', 'agent']);

        if (!$isOwner && !$isAgent) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'is_internal' => 'sometimes|boolean',
        ]);

        $isInternal = $isAgent && ($request->input('is_internal', false));

        Comment::create([
            'content' => $validated['content'],
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'is_internal' => $isInternal,
        ]);

        // Update ticket status if this is the first agent comment
        if ($isAgent && !$isInternal && $ticket->status->name === 'Open') {
            // Update to "In Progress" when agent first responds
            $inProgressStatus = \App\Models\Status::where('name', 'In Progress')->first();
            if ($inProgressStatus) {
                $ticket->update(['status_id' => $inProgressStatus->id]);
            }
        }

        $message = $isInternal ? 'Internal note added successfully!' : 'Comment added successfully!';
        $redirectRoute = $isAgent ? 'filament.admin.resources.tickets.view' : 'tickets.show';

        return redirect()->route($redirectRoute, $ticket)
            ->with('success', $message);
    }
}