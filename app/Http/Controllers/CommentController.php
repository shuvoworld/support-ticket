<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        // Validate user permissions
        if (!Auth::user()->hasRole(['super_admin', 'admin', 'agent'])) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to add comments.'
            ], 403);
        }

        // Check if ticket is closed
        if ($ticket->status->name === 'Closed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add comments to a closed ticket.'
            ], 422);
        }

        // Validate the request
        $validated = $request->validate([
            'content' => 'required|string|min:1',
            'is_internal' => 'boolean',
        ]);

        // Create the comment
        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'is_internal' => $validated['is_internal'] ?? false,
        ]);

        // Auto-update status to "In Progress" if agent responds to an open ticket with public comment
        if (Auth::user()->hasRole(['super_admin', 'admin', 'agent']) &&
            $ticket->status->name === 'Open' &&
            !$validated['is_internal']) {

            $inProgressStatus = Status::where('name', 'In Progress')->first();
            if ($inProgressStatus) {
                $ticket->update(['status_id' => $inProgressStatus->id]);
            }
        }

        // Load relationships for response
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => $validated['is_internal'] ? 'Internal note added successfully.' : 'Comment added successfully.',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'is_internal' => $comment->is_internal,
                'created_at' => $comment->created_at->format('M j, Y g:i A'),
                'user' => [
                    'name' => $comment->user->name,
                    'is_agent' => $comment->user->hasRole(['super_admin', 'admin', 'agent']),
                ]
            ]
        ]);
    }

    public function index(Ticket $ticket)
    {
        $comments = $ticket->comments()
            ->with('user')
            ->when(!Auth::user()->hasRole(['super_admin', 'admin', 'agent']), function ($query) {
                $query->where('is_internal', false);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'is_internal' => $comment->is_internal,
                    'created_at' => $comment->created_at->format('M j, Y g:i A'),
                    'user' => [
                        'name' => $comment->user->name,
                        'is_agent' => $comment->user->hasRole(['super_admin', 'admin', 'agent']),
                    ]
                ];
            })
        ]);
    }
}
