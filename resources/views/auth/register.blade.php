<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account - SupportDesk</title>
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
            --filament-warning: f59e0b;
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
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out;
        }

        .pulse-hover:hover {
            animation: pulse 0.3s ease-in-out;
        }

        /* Enhanced Filament Card */
        .filament-card-enhanced {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-radius: 1rem;
        }

        /* Enhanced Filament Input System */
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

        /* Input Groups with Icons */
        .filament-input-group-enhanced {
            position: relative;
            display: flex;
            align-items: center;
        }

        .filament-input-prefix-enhanced {
            position: absolute;
            left: 1rem;
            pointer-events: none;
            color: #9ca3af;
            transition: color 0.3s ease;
        }

        .filament-input-enhanced:focus ~ .filament-input-prefix-enhanced {
            color: #6366f1;
        }

        .filament-input-with-prefix-enhanced {
            padding-left: 3.5rem;
        }

        .filament-input-suffix-enhanced {
            position: absolute;
            right: 1rem;
            color: #9ca3af;
            transition: color 0.3s ease;
        }

        .filament-input-with-suffix-enhanced {
            padding-right: 3.5rem;
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
            padding: 1rem 1.5rem;
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

        /* Enhanced Checkbox */
        .filament-checkbox-enhanced {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .filament-checkbox-enhanced:checked {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-color: #6366f1;
        }

        .filament-checkbox-enhanced:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Password Strength Meter Enhanced */
        .password-strength-enhanced {
            height: 0.5rem;
            width: 100%;
            border-radius: 9999px;
            background: #e5e7eb;
            overflow: hidden;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        .password-strength-enhanced .strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 9999px;
        }

        .password-strength-enhanced .weak {
            background: linear-gradient(90deg, #ef4444, #dc2626);
            width: 33.33%;
        }

        .password-strength-enhanced .medium {
            background: linear-gradient(90deg, #f59e0b, #d97706);
            width: 66.66%;
        }

        .password-strength-enhanced .strong {
            background: linear-gradient(90deg, #10b981, #059669);
            width: 100%;
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

        /* Enhanced Success Message */
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

        /* Responsive Design */
        @media (max-width: 640px) {
            .filament-card-enhanced {
                margin: 1rem;
                padding: 1.5rem;
            }

            .filament-input-enhanced {
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
            }

            .filament-btn-enhanced {
                padding: 0.875rem 1.25rem;
                font-size: 0.875rem;
            }
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

        /* Hover Effects */
        .hover-lift:hover {
            transform: translateY(-2px);
            transition: transform 0.3s ease;
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-2xl fade-in">
            <!-- Back to Home -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center text-white/80 hover:text-white transition-all duration-300 text-sm font-medium hover-lift">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Home
                </a>
            </div>

            <!-- Registration Card -->
            <div class="filament-card-enhanced p-12 slide-up">
                <!-- Logo and Header -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mb-6 pulse-hover">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">Create Account</h1>
                    <p class="text-lg text-gray-600">Join our support ticket system to get help quickly and efficiently</p>
                </div>

                <!-- Registration Form -->
                <form class="space-y-8" action="{{ route('register') }}" method="POST" id="registrationForm">
                    @csrf

                    <!-- Name Field -->
                    <div class="filament-field-enhanced">
                        <label for="name" class="filament-label-enhanced">
                            Full Name
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="filament-input-group-enhanced">
                            <div class="filament-input-prefix-enhanced">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" id="name" name="name" required
                                   class="filament-input-enhanced filament-input-with-prefix-enhanced {{ $errors->has('name') ? 'error' : '' }}"
                                   placeholder="Enter your full name"
                                   value="{{ old('name') }}"
                                   autocomplete="name">
                        </div>
                        @error('name')
                            <div class="filament-error-enhanced">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="filament-field-enhanced">
                        <label for="email" class="filament-label-enhanced">
                            Email Address
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="filament-input-group-enhanced">
                            <div class="filament-input-prefix-enhanced">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" required
                                   class="filament-input-enhanced filament-input-with-prefix-enhanced {{ $errors->has('email') ? 'error' : '' }}"
                                   placeholder="Enter your email address"
                                   value="{{ old('email') }}"
                                   autocomplete="email">
                        </div>
                        @error('email')
                            <div class="filament-error-enhanced">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="filament-field-enhanced">
                        <label for="password" class="filament-label-enhanced">
                            Password
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="filament-input-group-enhanced">
                            <div class="filament-input-prefix-enhanced">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                   class="filament-input-enhanced filament-input-with-prefix-enhanced filament-input-with-suffix-enhanced {{ $errors->has('password') ? 'error' : '' }}"
                                   placeholder="Create a strong password"
                                   autocomplete="new-password">
                            <div class="filament-input-suffix-enhanced">
                                <button type="button" id="togglePassword" class="hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <div class="filament-error-enhanced">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                        <div id="passwordStrength" class="password-strength-enhanced">
                            <div class="strength-bar"></div>
                        </div>
                        <p class="filament-description-enhanced">
                            Must be at least 8 characters with uppercase, lowercase, numbers, and symbols.
                        </p>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="filament-field-enhanced">
                        <label for="password_confirmation" class="filament-label-enhanced">
                            Confirm Password
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="filament-input-group-enhanced">
                            <div class="filament-input-prefix-enhanced">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="filament-input-enhanced filament-input-with-prefix-enhanced filament-input-with-suffix-enhanced {{ $errors->has('password_confirmation') ? 'error' : '' }}"
                                   placeholder="Confirm your password"
                                   autocomplete="new-password">
                            <div class="filament-input-suffix-enhanced">
                                <button type="button" id="toggleConfirmPassword" class="hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <div class="filament-error-enhanced">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="filament-field-enhanced">
                        <div class="flex items-start">
                            <input type="checkbox" id="terms" name="terms" required
                                   class="filament-checkbox-enhanced mt-1">
                            <label for="terms" class="ml-3 text-sm text-gray-600 leading-relaxed">
                                I agree to the
                                <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium transition-colors">Terms of Service</a>
                                and
                                <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium transition-colors">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <!-- General Error Messages -->
                    @if ($errors->any())
                        <div class="filament-alert-enhanced filament-alert-danger-enhanced" role="alert">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h3 class="font-medium">Please fix the following errors:</h3>
                                    <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit" class="filament-btn-enhanced filament-btn-primary-enhanced w-full hover-lift" id="submitBtn">
                        <div class="spinner-enhanced hidden" id="loadingSpinner"></div>
                        <span id="btnText">Create Account</span>
                    </button>

                    <!-- Login Link -->
                    <div class="text-center pt-8 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                                Sign in
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Success Message Container -->
            <div id="successMessage" class="success-notification-enhanced">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <div class="font-medium">Account created successfully!</div>
                        <div class="text-sm opacity-90">Redirecting to your dashboard...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        function togglePasswordVisibility(input, button) {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);

            if (type === 'password') {
                button.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>`;
            } else {
                button.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                </svg>`;
            }
        }

        togglePassword.addEventListener('click', () => togglePasswordVisibility(passwordInput, togglePassword));
        toggleConfirmPassword.addEventListener('click', () => togglePasswordVisibility(confirmPasswordInput, toggleConfirmPassword));

        // Enhanced Password strength meter
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.querySelector('#passwordStrength .strength-bar');
            const strengthContainer = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthBar.className = 'strength-bar';
                strengthContainer.style.display = 'none';
                return;
            }

            strengthContainer.style.display = 'block';
            strengthBar.className = 'strength-bar';

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            if (strength <= 2) {
                strengthBar.classList.add('weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('medium');
            } else {
                strengthBar.classList.add('strong');
            }
        });

        // Enhanced Form submission with loading state
        const form = document.getElementById('registrationForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const btnText = document.getElementById('btnText');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Basic validation
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            loadingSpinner.classList.remove('hidden');
            btnText.textContent = 'Creating Account...';

            // Submit the form
            form.submit();
        });

        // Enhanced Real-time validation feedback
        const inputs = form.querySelectorAll('input[required]');
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
        });

        // Show success message on page load if redirected back with success
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                const successMessage = document.getElementById('successMessage');
                successMessage.classList.add('show');

                setTimeout(() => {
                    successMessage.classList.remove('show');
                }, 5000);
            }
        });
    </script>
</body>
</html>