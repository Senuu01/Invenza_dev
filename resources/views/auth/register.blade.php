<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Invenza') }} - Register</title>
    
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
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 25%, #3b82f6 75%, #60a5fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
        }
        
        /* Floating Shapes */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
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
        .register-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            position: relative;
            z-index: 10;
            animation: slideUp 0.8s ease-out;
            margin: 0 auto;
            box-sizing: border-box;
            min-height: auto;
            overflow-y: visible;
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
            width: 100%;
            max-width: none;
            box-sizing: border-box;
            min-height: auto;
            height: auto;
            overflow: visible;
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
        
        .form-input, .form-select {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            outline: none;
        }
        
        .form-select {
            cursor: pointer;
        }
        
        .form-input:focus, .form-select:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.15);
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 1);
        }
        
        .form-input:hover, .form-select:hover {
            border-color: #d1d5db;
        }
        
        .form-input.error, .form-select.error {
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
        
        .form-input:focus + .input-icon, .form-select:focus + .input-icon {
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
        
        /* Register Button */
        .register-btn {
            width: 100%;
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white !important;
            border: none;
            padding: 18px 24px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex !important;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 8px 24px rgba(30, 64, 175, 0.3),
                0 4px 12px rgba(30, 58, 138, 0.2);
            margin: 32px 0 20px 0;
            min-height: 54px;
            position: relative;
            z-index: 10;
            visibility: visible !important;
            opacity: 1 !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #1e3a8a 0%, #1e1e3a 100%);
            box-shadow: 
                0 12px 32px rgba(30, 64, 175, 0.4),
                0 6px 16px rgba(30, 58, 138, 0.25);
        }
        
        .register-btn:active {
            transform: translateY(0);
        }
        
        .register-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .register-btn i {
            margin-right: 8px;
        }
        
        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 20px;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        .login-link p {
            color: #6b7280;
            font-size: 14px;
        }
        
        .login-link a {
            color: #1e40af;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #1e3a8a;
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
        
        /* Role Selection */
        .role-cards {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-top: 8px;
        }
        
        .role-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            background: rgba(255, 255, 255, 0.5);
        }
        
        .role-card:hover {
            border-color: #1e40af;
            background: rgba(30, 64, 175, 0.05);
        }
        
        .role-card.selected {
            border-color: #1e40af;
            background: rgba(30, 64, 175, 0.1);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.15);
        }
        
        .role-card input {
            display: none;
        }
        
        .role-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 16px;
            color: white;
        }
        
        .role-card.admin .role-icon {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
        }
        
        .role-card.staff .role-icon {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }
        
        .role-card.customer .role-icon {
            background: linear-gradient(135deg, #10b981, #34d399);
        }
        
        .role-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }
        
        .role-desc {
            font-size: 12px;
            color: #6b7280;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .register-container {
                max-width: 95%;
                padding: 16px;
            }
            
            .form-card {
                padding: 32px 24px;
            }
        }
        
        @media (max-width: 480px) {
            .register-container {
                padding: 12px;
                max-width: 100%;
            }
            
            .form-card {
                padding: 24px 20px;
                margin: 0 5px;
            }
            
            .logo-title {
                font-size: 28px;
            }
            
            .role-cards {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            
            .form-input, .form-select {
                font-size: 16px;
                padding: 14px 14px 14px 45px;
            }
            
            .input-icon {
                left: 14px;
                font-size: 14px;
            }
            
            .toggle-password {
                right: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Background Shapes -->
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    
    <div class="register-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-cube"></i>
            </div>
            <h1 class="logo-title">Invenza</h1>
            <p class="logo-subtitle">Create your account</p>
        </div>

        <!-- Registration Form -->
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

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-wrapper">
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               class="form-input @error('name') error @enderror"
                               placeholder="Enter your full name">
                        <i class="input-icon fas fa-user"></i>
                    </div>
                    @error('name')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
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
                               autocomplete="new-password"
                               class="form-input @error('password') error @enderror"
                               placeholder="Create password">
                        <i class="input-icon fas fa-lock"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-wrapper">
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               class="form-input @error('password_confirmation') error @enderror"
                               placeholder="Confirm password">
                        <i class="input-icon fas fa-lock"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="form-group">
                    <label class="form-label">Account Type</label>
                    <div class="role-cards">
                        <div class="role-card admin selected" onclick="selectRole('admin', this)">
                            <input type="radio" name="role" value="admin" checked>
                            <div class="role-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="role-title">Administrator</div>
                            <div class="role-desc">Full system access</div>
                        </div>
                        <div class="role-card staff" onclick="selectRole('staff', this)">
                            <input type="radio" name="role" value="staff">
                            <div class="role-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="role-title">Staff Member</div>
                            <div class="role-desc">Standard access</div>
                        </div>
                        <div class="role-card customer" onclick="selectRole('customer', this)">
                            <input type="radio" name="role" value="customer">
                            <div class="role-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="role-title">Customer</div>
                            <div class="role-desc">Browse & purchase</div>
                        </div>
                    </div>
                    @error('role')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Register Button -->
                <button type="submit" class="register-btn" style="display: flex !important; opacity: 1 !important; visibility: visible !important; position: relative !important; z-index: 999 !important; margin: 30px 0 20px 0 !important; width: 100% !important;">
                    <i class="fas fa-user-plus" style="margin-right: 8px;"></i>
                    <span id="buttonText">Create Account</span>
                </button>

                <!-- Login Link -->
                <div class="login-link" style="display: block !important; opacity: 1 !important; visibility: visible !important;">
                    <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
                </div>
            </form>
        </div>


        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} Invenza. Crafted with excellence.</p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
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
        
        function selectRole(role, element) {
            // Remove selected class from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            element.classList.add('selected');
            
            // Check the radio button
            element.querySelector('input[type="radio"]').checked = true;
        }
        
        function fillCredentials(email, password, role) {
            document.getElementById('name').value = role === 'admin' ? 'Administrator User' : 'Staff Member';
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('password_confirmation').value = password;
            
            // Select the appropriate role
            const roleCard = document.querySelector(`.role-card.${role}`);
            if (roleCard) {
                selectRole(role, roleCard);
            }
            
            // Add visual feedback
            const fields = ['name', 'email', 'password', 'password_confirmation'];
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        field.style.transform = 'scale(1)';
                    }, 200);
                }
            });
        }
        
        // Form submission handler
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.register-btn');
            const buttonText = document.getElementById('buttonText');
            
            submitBtn.classList.add('loading');
            buttonText.textContent = 'Creating Account...';
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
