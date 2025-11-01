<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Tickets - Support Ticket System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'gradient': 'gradient 15s ease infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'fade-in': 'fadeIn 0.5s ease-out',
                    },
                    keyframes: {
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        }
                    },
                    backgroundSize: {
                        '400%': '400% 400%',
                    }
                }
            }
        }
    </script>
    <style>
        .white-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            animation: fadeIn 0.5s ease-out;
        }

        .table-row-hover {
            transition: all 0.2s ease;
        }

        .table-row-hover:hover {
            background-color: #f9fafb;
            transform: scale(1.01);
        }

        .priority-high { color: #dc2626; }
        .priority-medium { color: #ea580c; }
        .priority-low { color: #ca8a04; }
        .priority-critical { color: #991b1b; font-weight: 600; }

        .status-open { background: #fee2e2; color: #dc2626; }
        .status-progress { background: #fef3c7; color: #d97706; }
        .status-closed { background: #dcfce7; color: #16a34a; }
        .status-resolved { background: #dbeafe; color: #2563eb; }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>
<body class="bg-white min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <a href="{{ route('tickets.index') }}" class="text-xl font-bold text-gray-800 hover:text-gray-600 transition-all duration-300">
                            Support Ticket System
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('tickets.index') }}" class="text-gray-900 bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium">
                        My Tickets
                    </a>
                    <a href="{{ route('tickets.create') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all duration-200">
                        Create Ticket
                    </a>

                    <!-- Notifications -->
                    <div class="relative">
                        <button class="relative p-2 text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden md:block text-sm font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 white-card rounded-lg shadow-lg border border-gray-200 overflow-hidden z-50">
                            <div class="p-2">
                                                                <a href="{{ route('tickets.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md transition-all duration-200">
                                    My Tickets
                                </a>
                                <a href="{{ route('tickets.create') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md transition-all duration-200">
                                    Create Ticket
                                </a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md transition-all duration-200">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8 animate-slide-up">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">My Tickets</h1>
                    <p class="text-gray-600">Manage and track your support tickets</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('tickets.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-gray-800 text-white font-medium rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-700 transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Ticket
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 white-card rounded-lg p-4 border border-green-200 bg-green-50 animate-slide-up">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="white-card rounded-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Tickets</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $tickets->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="white-card rounded-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Open</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $tickets->where('status.name', 'Open')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="white-card rounded-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">In Progress</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $tickets->where('status.name', 'In Progress')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="white-card rounded-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Closed</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $tickets->where('status.name', 'Closed')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="white-card rounded-lg overflow-hidden animate-slide-up" style="animation-delay: 0.2s;">
            @if ($tickets->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Last Activity</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($tickets as $index => $ticket)
                                <tr class="table-row-hover hover:bg-gray-50" style="animation-delay: {{ $index * 0.1 }}s;">
                                    <td class="px-6 py-4">
                                        <div>
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-sm font-medium text-gray-800 hover:text-gray-900 transition-colors duration-200">
                                                {{ $ticket->subject }}
                                            </a>
                                            @if (Auth::user()->hasRole(['super_admin', 'admin', 'agent']) && isset($ticket->user))
                                                <p class="text-xs text-gray-500 mt-1">From: {{ $ticket->user->name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-700">{{ $ticket->category->name }}</span>
                                            @if ($ticket->department)
                                                <span class="text-xs text-gray-500 ml-2">• {{ $ticket->department->name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center text-sm font-medium
                                            @if ($ticket->priority === 'Critical') priority-critical
                                            @elseif ($ticket->priority === 'High') priority-high
                                            @elseif ($ticket->priority === 'Medium') priority-medium
                                            @else priority-low
                                            @endif
                                        ">
                                            @if ($ticket->priority === 'Critical')
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif ($ticket->priority === 'High')
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif ($ticket->priority === 'Medium')
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 100 4h2a2 2 0 100 4h2a1 1 0 100 2 2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 8h12v8H4V8z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium status-badge
                                            @if ($ticket->status->name === 'Open') status-open
                                            @elseif ($ticket->status->name === 'In Progress') status-progress
                                            @elseif ($ticket->status->name === 'Closed') status-closed
                                            @else status-resolved
                                            @endif
                                        ">
                                            {{ $ticket->status->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700">{{ $ticket->created_at->format('M j, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                {{ $ticket->comments_count ?? 0 }}
                                            </div>
                                            @if ($ticket->latestComment && $ticket->latestComment->created_at)
                                                <span class="text-xs text-gray-500">
                                                    {{ $ticket->latestComment->created_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('tickets.show', $ticket) }}"
                                           class="inline-flex items-center text-sm text-gray-800 hover:text-gray-900 font-medium transition-colors duration-200">
                                            View
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No tickets found</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first support ticket.</p>
                    <a href="{{ route('tickets.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-gray-800 text-white font-medium rounded-lg shadow-lg hover:bg-gray-700 transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Your First Ticket
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($tickets->hasPages())
            <div class="mt-6 white-card rounded-lg p-4 animate-slide-up" style="animation-delay: 0.4s;">
                {{ $tickets->links('pagination::tailwind') }}
            </div>
        @endif
    </main>

    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Add subtle animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-slide-up');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Add interactive hover effects
        document.querySelectorAll('.hover-lift').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px) scale(1.02)';
            });

            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>