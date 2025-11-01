<x-filament::widget>
    <div class="space-y-6">
        <!-- Add Comment Form -->
        @if(auth()->user()->hasRole(['super_admin', 'admin', 'agent']) && $ticket?->status?->name !== 'Closed')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="w-5 h-5 mr-2 text-gray-500" />
                        <h3 class="text-lg font-medium text-gray-900">Add Comment</h3>
                    </div>

                    <form wire:submit="addComment">
                        <div class="space-y-4">
                            <!-- Comment Textarea -->
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Comment
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea
                                    wire:model="data.content"
                                    id="content"
                                    rows="3"
                                    class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Type your comment or response here..."
                                    required
                                ></textarea>
                                <p class="mt-1 text-sm text-gray-500">
                                    This will be visible to the customer unless marked as internal
                                </p>
                            </div>

                            <!-- Internal Note Checkbox -->
                            @if(auth()->user()->hasRole(['super_admin', 'admin', 'agent']))
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        wire:model="data.is_internal"
                                        id="is_internal"
                                        class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                                    >
                                    <label for="is_internal" class="ml-2 block text-sm text-gray-700">
                                        Internal Note
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Check this if this comment should only be visible to agents and admins
                                </p>
                            @endif
                        </div>

                        <div class="flex justify-between items-center mt-4">
                            <div class="text-xs text-gray-500">
                                @if(auth()->user()->hasRole(['super_admin', 'admin', 'agent']))
                                    Your response will be visible to the customer
                                @endif
                            </div>
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Add Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Comments List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="w-5 h-5 mr-2 text-gray-500" />
                        Conversation
                    </h3>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $comments->count() }} {{ $comments->count() == 1 ? 'message' : 'messages' }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                @if($comments->count() > 0)
                    <div class="space-y-4">
                        @foreach($comments as $comment)
                            <div class="flex space-x-3 {{ $comment->is_internal ? 'opacity-75' : '' }}">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full
                                        @if($comment->user->hasRole(['super_admin', 'admin', 'agent'])) bg-blue-500
                                        @elseif($comment->is_internal) bg-orange-500
                                        @else bg-green-500
                                        @endif flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Message -->
                                <div class="flex-1 min-w-0">
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 {{ $comment->is_internal ? 'border-orange-200 bg-orange-50' : '' }}">
                                        <div class="px-4 py-3">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $comment->user->name }}
                                                    </p>
                                                    @if($comment->user->hasRole(['super_admin', 'admin', 'agent']))
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            Agent
                                                        </span>
                                                    @endif
                                                    @if($comment->is_internal)
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
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No messages yet</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Start the conversation by adding a message below.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament::widget>