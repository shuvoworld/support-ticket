<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Ticket Details Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r {{ $this->record->status->name === 'Open' ? 'from-red-500 to-red-600' : ($this->record->status->name === 'In Progress' ? 'from-yellow-500 to-yellow-600' : 'from-green-500 to-green-600') }} p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <x-filament::icon
                                icon="heroicon-o-ticket"
                                class="w-6 h-6 text-white"
                            />
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">
                                Ticket #{{ $this->record->id }}
                            </h1>
                            <p class="text-white/80 text-sm">
                                {{ $this->record->subject }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium">
                            {{ $this->record->status->name }}
                        </span>
                        <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium">
                            {{ $this->record->priority }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-100 rounded-lg p-2">
                            <x-filament::icon icon="heroicon-o-tag" class="w-5 h-5 text-green-600" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Category</p>
                            <p class="font-medium text-gray-900">{{ $this->record->category->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="bg-purple-100 rounded-lg p-2">
                            <x-filament::icon icon="heroicon-o-building-office" class="w-5 h-5 text-purple-600" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="font-medium text-gray-900">{{ $this->record->department?->name ?? 'Unassigned' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="bg-orange-100 rounded-lg p-2">
                            <x-filament::icon icon="heroicon-o-cog" class="w-5 h-5 text-orange-600" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Agent</p>
                            <p class="font-medium text-gray-900">{{ $this->record->agent?->name ?? 'Unassigned' }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Description</h3>
                    <div class="prose max-w-none">
                        {!! nl2br(e($this->record->content)) !!}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-6 border-t">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="text-sm font-medium text-gray-900">{{ $this->record->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="text-sm font-medium text-gray-900">{{ $this->record->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @if($this->record->closed_at)
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Closed At</p>
                            <p class="text-sm font-medium text-gray-900">{{ $this->record->closed_at->format('M j, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900 flex items-center">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="w-5 h-5 mr-2 text-gray-500" />
                        Conversation
                    </h2>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $comments->count() }} {{ $comments->count() == 1 ? 'message' : 'messages' }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <!-- Comments List -->
                <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                    @if($comments->count() > 0)
                        @foreach($comments as $comment)
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full
                                        @if($comment->user->hasRole(['super_admin', 'admin', 'agent'])) bg-blue-500
                                        @else bg-green-500
                                        @endif flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="px-4 py-3">
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
                                                </div>
                                                <p class="text-xs text-gray-500">
                                                    {{ $comment->created_at->format('M j, Y g:i A') }}
                                                </p>
                                            </div>

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
                            <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No messages yet</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Start the conversation by adding a message below.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Add Comment Form -->
                @if($this->record->status->name !== 'Closed')
                    <div class="border-t pt-6">
                        <form wire:submit="addComment">
                            {{ $this->form }}

                            <div class="flex justify-between items-center mt-4">
                                <div class="text-xs text-gray-500">
                                    Your message will be visible to support agents
                                </div>
                                <x-filament::button type="submit">
                                    <x-filament::icon icon="heroicon-o-paper-airplane" class="w-4 h-4 mr-2" />
                                    Send Message
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <x-filament::icon icon="heroicon-o-lock-closed" class="mx-auto h-8 w-8 text-gray-400 mb-2" />
                        <p class="text-sm text-gray-600">
                            This ticket is closed. No additional messages can be added.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>