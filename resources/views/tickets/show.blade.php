<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ticket #{{ $ticket->id }} - Support Ticket System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    @if (session('success'))
        <div id="success-notification" class="fixed top-4 right-4 z-50 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out translate-x-full">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ session('success') }}
                                    </div>
                                </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notification = document.getElementById('success-notification');
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);

                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            });
        </script>
    @endif
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('tickets.index') }}" class="text-xl font-semibold text-gray-900">Support Ticket System</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="mb-6">
                <a href="{{ route('tickets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Back to My Tickets
                </a>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-lg leading-6 font-medium text-gray-900">
                            Ticket #{{ $ticket->id }}
                        </h1>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if ($ticket->status->name === 'Open') bg-red-100 text-red-800
                            @elseif ($ticket->status->name === 'In Progress') bg-yellow-100 text-yellow-800
                            @elseif ($ticket->status->name === 'Closed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif
                        ">
                            {{ $ticket->status->name }}
                        </span>
                    </div>
                    <div class="mt-2 max-w-2xl text-sm text-gray-500">
                        <p>Created: {{ $ticket->created_at->format('M j, Y g:i A') }}</p>
                        <p>Category: {{ $ticket->category->name }}</p>
                        @if ($ticket->department)
                            <p>Department: {{ $ticket->department->name }}</p>
                        @endif
                        <p>Priority:
                            <span class="
                                @if ($ticket->priority === 'Urgent') text-red-600 font-medium
                                @elseif ($ticket->priority === 'High') text-orange-600 font-medium
                                @elseif ($ticket->priority === 'Medium') text-yellow-600 font-medium
                                @else text-gray-600 font-medium
                                @endif
                            ">
                                {{ $ticket->priority }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Subject</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $ticket->subject }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="prose max-w-none">
                                    {!! nl2br(e($ticket->content)) !!}
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Conversation Section -->
            <div class="mt-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-gray-900">Conversation</h2>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($ticket->status->name === 'Open') bg-red-100 text-red-800
                            @elseif ($ticket->status->name === 'In Progress') bg-yellow-100 text-yellow-800
                            @elseif ($ticket->status->name === 'Closed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $ticket->status->name }}
                        </span>
                        @if ($ticket->comments->count() > 0)
                            <span class="text-sm text-gray-500">
                                {{ $ticket->comments->count() }} {{ $ticket->comments->count() == 1 ? 'message' : 'messages' }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Conversation Thread -->
                <div class="space-y-4 mb-6">
                    @if ($ticket->comments->count() > 0)
                        @foreach ($ticket->comments as $comment)
                            <div class="flex space-x-3 {{ $comment->is_internal ? 'opacity-75' : '' }}">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full
                                        @if ($comment->user->hasRole(['super_admin', 'admin', 'agent'])) bg-blue-500
                                        @elseif ($comment->is_internal) bg-orange-500
                                        @else bg-green-500
                                        @endif flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Message -->
                                <div class="flex-1 min-w-0">
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200
                                        @if ($comment->is_internal) border-orange-200 @endif">
                                        <div class="px-4 py-3">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $comment->user->name }}
                                                    </p>
                                                    @if ($comment->user->hasRole(['super_admin', 'admin', 'agent']))
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            Agent
                                                        </span>
                                                    @endif
                                                    @if ($comment->is_internal)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                            Internal
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500">
                                                    {{ $comment->created_at->format('M j, Y g:i A') }}
                                                </p>
                                            </div>

                                            <!-- Content -->
                                            <div class="text-sm text-gray-800 whitespace-pre-wrap">
                                                {{ $comment->content }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No messages yet</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Start the conversation by adding a message below.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Message Input -->
                @if ($ticket->status->name !== 'Closed')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <form action="{{ route('tickets.addComment', $ticket) }}" method="POST" class="p-4" id="comment-form">
                            @csrf
                            <div class="space-y-4">
                                <!-- Quick Status Actions for Agents -->
                                @if ($isAgent && $ticket->status->name === 'Open')
                                    <div class="flex items-center space-x-2 text-sm text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Your first response will automatically update the status to "In Progress"</span>
                                    </div>
                                @endif

                                <!-- Message Input -->
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isAgent ? 'Response' : 'Message' }}
                                    </label>
                                    <textarea name="content" id="content" rows="4" required
                                              class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                              placeholder="{{ $isAgent ? 'Type your response...' : 'Type your message...' }}"></textarea>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Internal Note Option for Agents -->
                                @if ($isAgent)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_internal" id="is_internal" value="1"
                                               class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                        <label for="is_internal" class="ml-2 block text-sm text-gray-700">
                                            This is an internal note (not visible to customer)
                                        </label>
                                    </div>
                                @endif

                                <!-- Submit Button -->
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        {{ $isOwner ? 'Your message will be visible to support agents' :
                                           ($isAgent ? 'Your response will be visible to the customer' : '') }}
                                    </div>
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        {{ $isAgent ? 'Send Response' : 'Send Message' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-gray-600">
                            This ticket is closed. No additional messages can be added.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Floating Action Button -->
    @if ($ticket->status->name !== 'Closed')
        <button onclick="openCommentModal()" class="fixed bottom-6 right-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-200 z-50 group">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <span class="absolute right-full mr-3 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-sm rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Add {{ $isAgent ? 'Comment' : 'Message' }}
            </span>
        </button>
    @endif

    <!-- Comment Modal -->
    <div id="commentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Comment</h3>
                    <button onclick="closeCommentModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form id="modalCommentForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="modalContent" class="block text-sm font-medium text-gray-700 mb-2">
                            Comment <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="modalContent"
                            name="content"
                            rows="4"
                            class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Type your comment..."
                            required
                        ></textarea>
                    </div>

                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="is_internal"
                            id="modalIsInternal"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        >
                        <label for="modalIsInternal" class="ml-2 block text-sm text-gray-700">
                            Internal note
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">
                        Only visible to agents
                    </p>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button
                            type="button"
                            onclick="closeCommentModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            id="modalSubmitBtn"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors disabled:opacity-50"
                        >
                            <span id="modalSubmitText">Send Comment</span>
                            <svg id="modalSpinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="alert-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ticketId = {{ $ticket->id }};
            const commentForm = document.querySelector('#comment-form');
            const modalCommentForm = document.querySelector('#modalCommentForm');
            const submitButton = commentForm?.querySelector('button[type="submit"]');
            const modalSubmitBtn = document.querySelector('#modalSubmitBtn');
            const commentsList = document.querySelector('.space-y-4');
            const commentsCount = document.querySelector('.text-xs.font-medium');

            // Handle inline form submission
            if (commentForm) {
                commentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitComment(commentForm);
                });
            }

            // Handle modal form submission
            if (modalCommentForm) {
                modalCommentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitComment(modalCommentForm, true);
                });
            }

            // Modal functions
            window.openCommentModal = function() {
                document.getElementById('commentModal').classList.remove('hidden');
                document.getElementById('modalContent').focus();
            };

            window.closeCommentModal = function() {
                document.getElementById('commentModal').classList.add('hidden');
                modalCommentForm.reset();
            };

            // Close modal when clicking outside
            document.getElementById('commentModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCommentModal();
                }
            });

            function submitComment(form, isModal = false) {
                const formData = new FormData(form);
                const submitData = {
                    content: formData.get('content'),
                    is_internal: formData.has('is_internal')
                };

                const button = isModal ? modalSubmitBtn : submitButton;
                const spinner = isModal ? 'modalSpinner' : null;
                const text = isModal ? 'modalSubmitText' : null;

                // Show loading state
                if (button) {
                    button.disabled = true;
                    if (isModal) {
                        document.getElementById(spinner).classList.remove('hidden');
                        document.getElementById(text).textContent = 'Sending...';
                    } else {
                        button.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending...
                        `;
                    }
                }

                fetch(`/tickets/${ticketId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(submitData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear form
                        form.reset();

                        // Show success message
                        showAlert(data.message, 'success');

                        // Close modal if it's a modal submission
                        if (isModal) {
                            closeCommentModal();
                        }

                        // Reload comments
                        loadComments();

                        // If ticket status changed, reload page
                        if (data.message.includes('In Progress')) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error submitting comment:', error);
                    showAlert('Error submitting comment. Please try again.', 'error');
                })
                .finally(() => {
                    // Reset button state
                    if (button) {
                        button.disabled = false;
                        if (isModal) {
                            document.getElementById(spinner).classList.add('hidden');
                            document.getElementById(text).textContent = 'Send Comment';
                        } else {
                            button.innerHTML = 'Send Response';
                        }
                    }
                });
            }

            function loadComments() {
                fetch(`/tickets/${ticketId}/comments`)
                    .then(response => response.json())
                    .then(data => {
                        renderComments(data.comments);
                    })
                    .catch(error => {
                        console.error('Error loading comments:', error);
                    });
            }

            function renderComments(comments) {
                if (!commentsList) return;

                if (comments.length === 0) {
                    commentsList.innerHTML = `
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No messages yet</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Start the conversation by adding a message below.
                            </p>
                        </div>
                    `;
                } else {
                    const commentsHtml = comments.map(comment => createCommentHtml(comment)).join('');
                    commentsList.innerHTML = commentsHtml;
                }

                // Update count if it exists
                if (commentsCount) {
                    const count = comments.length;
                    commentsCount.textContent = `${count} ${count === 1 ? 'message' : 'messages'}`;
                }
            }

            function createCommentHtml(comment) {
                const userInitial = comment.user.name.charAt(0).toUpperCase();
                const bgColor = comment.user.is_agent ? 'bg-blue-500' : 'bg-green-500';
                const agentBadge = comment.user.is_agent ? `
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                        Agent
                    </span>
                ` : '';
                const internalBadge = comment.is_internal ? `
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                        Internal
                    </span>
                ` : '';
                const cardBg = comment.is_internal ? 'bg-orange-50 border-orange-200' : 'bg-white border-gray-200';

                return `
                    <div class="flex space-x-3 ${comment.is_internal ? 'opacity-75' : ''}">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full ${bgColor} flex items-center justify-center">
                                <span class="text-sm font-medium text-white">${userInitial}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="bg-gray-50 rounded-lg border border-gray-200 ${cardBg}">
                                <div class="px-4 py-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm font-medium text-gray-900">${comment.user.name}</p>
                                            ${agentBadge}
                                            ${internalBadge}
                                        </div>
                                        <p class="text-xs text-gray-500">${comment.created_at}</p>
                                    </div>
                                    <div class="text-sm text-gray-800 whitespace-pre-wrap">
                                        ${comment.content}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            function showAlert(message, type = 'success') {
                const alertContainer = document.getElementById('alert-container');
                const alertId = 'alert-' + Date.now();

                const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
                const textColor = type === 'success' ? 'text-green-800' : 'text-red-800';
                const iconColor = type === 'success' ? 'text-green-400' : 'text-red-400';
                const icon = type === 'success'
                    ? '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                    : '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';

                const alertHtml = `
                    <div id="${alertId}" class="${bgColor} border rounded-md p-4" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="${iconColor}">${icon}</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium ${textColor}">${message}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button onclick="document.getElementById('${alertId}').remove()" type="button" class="${bgColor} inline-flex rounded-md p-1.5 ${textColor} hover:bg-opacity-50 focus:outline-none focus:ring-2 focus:ring-offset-2">
                                        <span class="sr-only">Dismiss</span>
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                alertContainer.insertAdjacentHTML('beforeend', alertHtml);

                // Auto-remove after 5 seconds
                setTimeout(() => {
                    const alert = document.getElementById(alertId);
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }
        });
    </script>
</body>
</html>