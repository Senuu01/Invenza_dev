<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Invenza') }} - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: auto;
            min-height: 100vh;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 25%, #3b82f6 75%, #60a5fa 100%);
            background-attachment: fixed;
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 1;
        }
        
        /* Floating Shapes */
        .bg-shape {
            position: fixed;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
            z-index: 2;
        }
        
        .bg-shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .bg-shape:nth-child(2) {
            width: 80px;
            height: 80px;
            top: 70%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .bg-shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 40%;
            right: 10%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
        }
        
        /* Main Container */
        .login-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 10;
            animation: slideUp 0.8s ease-out;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .logo i {
            font-size: 32px;
            color: #3b82f6;
        }
        
        .logo-title {
            font-size: 36px;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .logo-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }
        
        /* Form Card */
        .form-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 
                0 32px 64px rgba(30, 58, 138, 0.2),
                0 16px 32px rgba(30, 64, 175, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        
        /* Form Elements */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            outline: none;
        }
        
        .form-input:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.15);
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 1);
        }
        
        .form-input:hover {
            border-color: #d1d5db;
        }
        
        .form-input.error {
            border-color: #ef4444;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
            transition: color 0.3s ease;
        }
        
        .form-input:focus + .input-icon {
            color: #1e40af;
        }
        
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            transition: color 0.3s ease;
        }
        
        .toggle-password:hover {
            color: #6b7280;
        }
        
        /* Alert Messages */
        .error-alert, .success-alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.3s ease-out;
        }

        .error-alert {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #dc2626;
        }

        .success-alert {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #15803d;
        }

        .error-alert i, .success-alert i {
            margin-right: 8px;
            font-size: 16px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Error Messages */
        .error-message {
            margin-top: 8px;
            color: #ef4444;
            font-size: 14px;
            display: flex;
            align-items: center;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .error-message i {
            margin-right: 6px;
        }
        
        /* Remember Me & Forgot Password */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .checkbox-wrapper input {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            accent-color: #3b82f6;
        }
        
        .checkbox-wrapper label {
            font-size: 14px;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
        }
        
        .forgot-link {
            color: #1e40af;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #1e3a8a;
        }
        
        /* Login Button */
        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white;
            border: none;
            padding: 18px 24px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 8px 24px rgba(30, 64, 175, 0.3),
                0 4px 12px rgba(30, 58, 138, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #1e3a8a 0%, #1e1e3a 100%);
            box-shadow: 
                0 12px 32px rgba(30, 64, 175, 0.4),
                0 6px 16px rgba(30, 58, 138, 0.25);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .login-btn i {
            margin-right: 8px;
        }
        
        /* Demo Credentials */
        .demo-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        
        .demo-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .demo-header i {
            color: #3b82f6;
            margin-right: 8px;
        }
        
        .demo-header h3 {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }
        
        .demo-item {
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 8px;
        }
        
        .demo-item:hover {
            background: rgba(30, 64, 175, 0.05);
            transform: translateX(4px);
        }
        
        .demo-item:last-child {
            margin-bottom: 0;
        }
        
        .demo-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .demo-user {
            display: flex;
            align-items: center;
        }
        
        .demo-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }
        
        .demo-avatar.admin {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
        }
        
        .demo-avatar.staff {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }
        
        .demo-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 2px;
        }
        
        .demo-info p {
            font-size: 12px;
            color: #6b7280;
        }
        
        .demo-arrow {
            color: #9ca3af;
            font-size: 12px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
        }
        
        .footer p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Success Message */
        .success-message {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #15803d;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }
        
        .success-message i {
            margin-right: 8px;
        }
        
        /* Loading State */
        .loading {
            position: relative;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }
        
        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link p {
            color: #6b7280;
            font-size: 14px;
        }
        
        .login-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #2563eb;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 10px 0;
            }
            
            .login-container {
                padding: 16px;
                max-width: 100%;
            }
            
            .form-card {
                padding: 24px;
                margin-bottom: 16px;
            }
            
            .demo-card {
                margin-bottom: 16px;
            }
            
            .logo-title {
                font-size: 28px;
            }
            
            .logo-section {
                margin-bottom: 30px;
            }
        }
        
        @media (max-height: 700px) {
            .logo-section {
                margin-bottom: 20px;
            }
            
            .form-card {
                padding: 30px;
                margin-bottom: 16px;
            }
            
            .demo-card {
                padding: 20px;
                margin-bottom: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Background Shapes -->
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-cube"></i>
            </div>
            <h1 class="logo-title">Invenza</h1>
            <p class="logo-subtitle">Welcome back to your dashboard</p>
        </div>

        <!-- Login Form -->
        <div class="form-card">
            <!-- Global Error Message -->
            @if (session('error'))
                <div class="error-alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Global Success Message -->
            @if (session('success'))
                <div class="success-alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               class="form-input @error('email') error @enderror"
                               placeholder="Enter your email address">
                        <i class="input-icon fas fa-envelope"></i>
                    </div>
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="form-input @error('password') error @enderror"
                               placeholder="Enter your password">
                        <i class="input-icon fas fa-lock"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-footer">
                    <div class="checkbox-wrapper">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span id="buttonText">Sign In to Dashboard</span>
                </button>

                <!-- Registration Link -->
                <div class="login-link">
                    <p>Don't have an account? <a href="{{ route('register') }}">Create one here</a></p>
                </div>
            </form>
        </div>


        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} Invenza. Crafted with excellence.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        
        // Form submission handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.login-btn');
            const buttonText = document.getElementById('buttonText');
            
            submitBtn.classList.add('loading');
            buttonText.textContent = 'Signing In...';
            submitBtn.disabled = true;
        });
        
        // Parallax effect for background shapes
        document.addEventListener('mousemove', (e) => {
            const shapes = document.querySelectorAll('.bg-shape');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 0.5;
                shape.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
            });
        });
    </script>
</body>
</html>
