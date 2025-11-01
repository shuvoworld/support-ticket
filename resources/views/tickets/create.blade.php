<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Ticket - SupportDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@tailwindcss/forms@0.5.7/dist/forms.min.js"></script>
    <style>
        /* Enhanced Filament Design System */
        :root {
            /* Filament Color Palette */
            --filament-primary: 6366f1;
            --filament-primary-hover: 4f46e5;
            --filament-danger: ef4444;
            --filament-danger-hover: dc2626;
            --filament-success: 10b981;
            --filament-success-hover: 059669;
            --filament-warning: f59e0b;
            --filament-warning-hover: d97706;
            --filament-gray-50: f9fafb;
            --filament-gray-100: f3f4f6;
            --filament-gray-200: e5e7eb;
            --filament-gray-300: d1d5db;
            --filament-gray-400: 9ca3af;
            --filament-gray-500: 6b7280;
            --filament-gray-600: 4b5563;
            --filament-gray-700: 374151;
            --filament-gray-800: 1f2937;
            --filament-gray-900: 111827;
            --filament-ring-offset: 0;
            --filament-ring: 6366f1;
            --filament-ring-opacity: 0.25;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Animation Classes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .slide-up {
            animation: slideUp 0.8s ease-out;
        }

        .slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }

        .pulse-hover:hover {
            animation: pulse 0.3s ease-in-out;
        }

        .shake-animation {
            animation: shake 0.5s ease-in-out;
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Enhanced Navigation */
        .nav-enhanced {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Cards */
        .filament-card-enhanced {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .filament-card-enhanced:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.15), 0 15px 15px -5px rgba(0, 0, 0, 0.06);
        }

        /* Enhanced Form Sections */
        .form-section {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
        }

        /* Enhanced Input System */
        .filament-input-enhanced {
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            line-height: 1.5;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            background: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .filament-input-enhanced:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        .filament-input-enhanced.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .filament-input-enhanced::placeholder {
            color: #9ca3af;
        }

        /* Enhanced Textarea */
        .filament-textarea-enhanced {
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            line-height: 1.6;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            background: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            resize: vertical;
            min-height: 150px;
        }

        .filament-textarea-enhanced:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .filament-textarea-enhanced.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .filament-textarea-enhanced::placeholder {
            color: #9ca3af;
        }

        /* Enhanced Select */
        .filament-select-enhanced {
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            line-height: 1.5;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            background: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23637481' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 8l5 5L5 8M3 5l5 5M5 8h8M5 8v8'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .filament-select-enhanced:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        .filament-select-enhanced.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        /* Enhanced Labels */
        .filament-label-enhanced {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        /* Enhanced Descriptions */
        .filament-description-enhanced {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
            line-height: 1.4;
        }

        /* Enhanced Error Messages */
        .filament-error-enhanced {
            font-size: 0.875rem;
            font-weight: 500;
            color: #ef4444;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Enhanced Button */
        .filament-btn-enhanced {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1;
            border: none;
            border-radius: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .filament-btn-primary-enhanced {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
        }

        .filament-btn-primary-enhanced:hover:not(:disabled) {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .filament-btn-primary-enhanced:active:not(:disabled) {
            transform: translateY(0);
        }

        .filament-btn-primary-enhanced:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .filament-btn-secondary-enhanced {
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .filament-btn-secondary-enhanced:hover:not(:disabled) {
            background: #f9fafb;
            border-color: #d1d5db;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Enhanced Alert */
        .filament-alert-enhanced {
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .filament-alert-danger-enhanced {
            border-color: #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        /* Enhanced Field Container */
        .filament-field-enhanced {
            margin-bottom: 1.5rem;
        }

        /* Success Message */
        .success-notification-enhanced {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateX(400px);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .success-notification-enhanced.show {
            transform: translateX(0);
        }

        /* Loading Spinner */
        .spinner-enhanced {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Character Counter */
        .char-counter {
            position: absolute;
            bottom: 0.5rem;
            right: 1rem;
            font-size: 0.75rem;
            color: #9ca3af;
            background: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
        }

        /* Priority Indicators */
        .priority-indicator {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
        }

        .priority-low {
            background: #10b981;
        }

        .priority-medium {
            background: #f59e0b;
        }

        .priority-high {
            background: #ef4444;
        }

        .priority-urgent {
            background: #dc2626;
            animation: pulse 1s infinite;
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .form-section {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }

            .filament-input-enhanced,
            .filament-textarea-enhanced,
            .filament-select-enhanced {
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
            }

            .filament-btn-enhanced {
                padding: 0.875rem 1.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <!-- Enhanced Navigation -->
    <nav class="nav-enhanced sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h3m1 8l-3-3H5a2 2 0 01-2 2v8a2 2 0 002 2h6a2 2 0 002-2v-8a2 2 0 00-2-2h-1l-3 3z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">SupportDesk</span>
                    </a>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <!-- User Dropdown -->
                    <div class="dropdown-enhanced">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-3 hover:bg-gray-100 rounded-lg px-3 py-2 transition-colors">
                            <div class="avatar-enhanced">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div id="dropdownMenu" class="dropdown-menu">
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7m-7 7h14"></path>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('tickets.index') }}" class="dropdown-item">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                My Tickets
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                                @csrf
                                <button type="submit" class="flex items-center w-full">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Header -->
        <div class="mb-8 slide-up">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-3">
                    Create New Support Ticket
                </h1>
                <p class="text-white/80 text-lg max-w-2xl mx-auto">
                    We're here to help! Fill out the form below and we'll get back to you as soon as possible.
                </p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('tickets.store') }}" method="POST" id="ticketForm" class="space-y-0 slide-in-left">
            @csrf

            <!-- Ticket Information Section -->
            <div class="form-section">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Ticket Information</h2>
                        <p class="text-gray-600 mt-1">Please provide details about your support request</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Subject Field -->
                    <div class="filament-field-enhanced">
                        <label for="subject" class="filament-label-enhanced">
                            Subject
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="subject" name="subject" required
                               class="filament-input-enhanced {{ $errors->has('subject') ? 'error' : '' }}"
                               placeholder="Brief description of your issue"
                               value="{{ old('subject') }}"
                               maxlength="100">
                        <div class="char-counter">
                            <span id="subjectCounter">0</span>/100
                        </div>
                        @error('subject')
                            <div class="filament-error-enhanced">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Priority Field -->
                    <div class="filament-field-enhanced">
                        <label for="priority" class="filament-label-enhanced">
                            Priority Level
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="priority" id="priority" required
                                    class="filament-select-enhanced {{ $errors->has('priority') ? 'error' : '' }}">
                                <option value="">Select priority level</option>
                                <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low - Non-urgent issue</option>
                                <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium - Needs attention</option>
                                <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High - Urgent attention</option>
                                <option value="Urgent" {{ old('priority') == 'Urgent' ? 'selected' : '' }}>Urgent - Critical issue</option>
                            </select>
                            <div class="priority-indicator priority-low" id="priorityIndicator"></div>
                        </div>
                        @error('priority')
                            <div class="filament-error-enhanced">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                        <p class="filament-description-enhanced">Select the appropriate priority level for your request</p>
                    </div>
                </div>
            </div>

            <!-- Categorization Section -->
            <div class="form-section">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Categorization</h2>
                        <p class="text-gray-600 mt-1">Help us route your ticket to the right team</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Field -->
                    <div class="filament-field-enhanced">
                        <label for="category_id" class="filament-label-enhanced">
                            Category
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="category_id" id="category_id" required
                                class="filament-select-enhanced {{ $errors->has('category_id') ? 'error' : '' }}">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="filament-error-enhanced">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Department Field -->
                    <div class="filament-field-enhanced">
                        <label for="department_id" class="filament-label-enhanced">
                            Department
                        </label>
                        <select name="department_id" id="department_id"
                                class="filament-select-enhanced {{ $errors->has('department_id') ? 'error' : '' }}">
                            <option value="">Select a department (optional)</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="filament-error-enhanced">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                        <p class="filament-description-enhanced">
                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Selecting a department helps route your ticket to the right team
                        </p>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="form-section">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 002-2V6a2 2 0 00-2-2h6a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 00-2-2h6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Issue Description</h2>
                        <p class="text-gray-600 mt-1">Please provide as much detail as possible to help us understand your issue</p>
                    </div>
                </div>

                <div class="filament-field-enhanced">
                    <label for="content" class="filament-label-enhanced">
                        Description
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea id="content" name="content" rows="6" required
                              class="filament-textarea-enhanced {{ $errors->has('content') ? 'error' : '' }}"
                              placeholder="Please describe your issue in detail, including any error messages, steps you've already taken, and what you were trying to do...">{{ old('content') }}"></textarea>
                    <div class="char-counter">
                        <span id="contentCounter">0</span>/1000
                    </div>
                    @error('content')
                        <div class="filament-error-enhanced">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="flex justify-end space-x-4 slide-up">
                <a href="{{ route('tickets.index') }}" class="filament-btn-enhanced filament-btn-secondary-enhanced">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="filament-btn-enhanced filament-btn-primary-enhanced pulse-hover" id="submitBtn">
                    <div class="spinner-enhanced hidden" id="loadingSpinner"></div>
                    <span id="btnText">Create Ticket</span>
                </button>
            </div>
        </form>

        <!-- Success Message Container -->
        <div id="successMessage" class="success-notification-enhanced">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <div class="font-medium">Ticket created successfully!</div>
                    <div class="text-sm opacity-90">Redirecting to your tickets...</div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Dropdown functionality
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.dropdown-enhanced');
            const menu = document.getElementById('dropdownMenu');

            if (!dropdown.contains(event.target)) {
                menu.classList.remove('show');
            }
        });

        // Character counters
        const subjectInput = document.getElementById('subject');
        const subjectCounter = document.getElementById('subjectCounter');
        const contentTextarea = document.getElementById('content');
        const contentCounter = document.getElementById('contentCounter');

        subjectInput.addEventListener('input', function() {
            const length = this.value.length;
            subjectCounter.textContent = length;
            subjectCounter.style.color = length > 90 ? '#ef4444' : '#9ca3af';
        });

        contentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            contentCounter.textContent = length;
            contentCounter.style.color = length > 900 ? '#ef4444' : '#9ca3af';
        });

        // Priority indicator
        const prioritySelect = document.getElementById('priority');
        const priorityIndicator = document.getElementById('priorityIndicator');

        prioritySelect.addEventListener('change', function() {
            const value = this.value;
            priorityIndicator.className = 'priority-indicator';

            if (value === 'Low') {
                priorityIndicator.classList.add('priority-low');
            } else if (value === 'Medium') {
                priorityIndicator.classList.add('priority-medium');
            } else if (priority === 'High') {
                priorityIndicator.classList.add('priority-high');
            } else if (priority === 'Urgent') {
                priorityIndicator.classList.add('priority-urgent');
            }
        });

        // Enhanced Form submission with loading state
        const form = document.getElementById('ticketForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const btnText = document.getElementById('btnText');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Basic validation
            const subject = document.getElementById('subject').value.trim();
            const content = document.getElementById('content').value.trim();
            const category = document.getElementById('category_id').value;
            const priority = document.getElementById('priority').value;

            if (!subject || !content || !category || !priority) {
                // Shake animation for empty fields
                form.classList.add('shake-animation');
                setTimeout(() => form.classList.remove('shake-animation'), 500);

                // Add error styling to empty fields
                if (!subject) document.getElementById('subject').classList.add('error');
                if (!content) document.getElementById('content').classList.add('error');
                if (!category) document.getElementById('category_id').classList.add('error');
                if (!priority) document.getElementById('priority').classList.add('error');

                return;
            }

            // Validate content length
            if (content.length < 10) {
                alert('Please provide more details about your issue (minimum 10 characters)');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            loadingSpinner.classList.remove('hidden');
            btnText.textContent = 'Creating Ticket...';

            // Submit the form
            form.submit();
        });

        // Enhanced Real-time validation feedback
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });

            // Real-time validation as user types
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('error');
                }
            });

            // Clear error on focus
            input.addEventListener('focus', function() {
                this.classList.remove('error');
            });
        });

        // Add keyboard shortcuts
        form.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S for submit
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                submitBtn.click();
            }
        });

        // Show success message on page load if redirected back with success
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                const successMessage = document.getElementById('successMessage');
                successMessage.classList.add('show');

                setTimeout(() => {
                    successMessage.classList.remove('show');
                    window.location.href = '{{ route('tickets.index') }}';
                }, 3000);
            }
        });

        // Add visual feedback for successful field completion
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim().length > 0 && !this.classList.contains('error')) {
                    this.style.borderColor = '#10b981';
                } else if (this.value.trim().length === 0) {
                    this.style.borderColor = '';
                }
            });
        });

        // Auto-save draft functionality (every 30 seconds)
        let draftTimer;
        function saveDraft() {
            const formData = new FormData(form);
            localStorage.setItem('ticketDraft', JSON.stringify({
                subject: document.getElementById('subject').value,
                content: document.getElementById('content').value,
                category_id: document.getElementById('category_id').value,
                department_id: document.getElementById('department_id').value,
                priority: document.getElementById('priority').value,
                timestamp: new Date().toISOString()
            }));
        }

        // Auto-save every 30 seconds
        draftTimer = setInterval(saveDraft, 30000);

        // Clear draft on successful submission
        form.addEventListener('submit', function() {
            localStorage.removeItem('ticketDraft');
            clearInterval(draftTimer);
        });

        // Load draft on page load
        window.addEventListener('load', function() {
            const draft = localStorage.getItem('ticketDraft');
            if (draft) {
                const draftData = JSON.parse(draft);
                if (draftData.timestamp && (new Date().getTime() - new Date(draftData.timestamp) < 3600000)) { // 1 hour old
                    document.getElementById('subject').value = draftData.subject || '';
                    document.getElementById('content').value = draftData.content || '';
                    document.getElementById('category_id').value = draftData.category_id || '';
                    document.getElementById('department_id').value = draftData.department_id || '';
                    document.getElementById('priority').value = draftData.priority || '';

                    // Update counters
                    subjectInput.dispatchEvent(new Event('input'));
                    contentTextarea.dispatchEvent(new Event('input'));
                    prioritySelect.dispatchEvent(new Event('change'));
                } else {
                    localStorage.removeItem('ticketDraft');
                }
            }
        });
    </script>
</body>
</html>