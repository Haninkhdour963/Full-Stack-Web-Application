<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        :root {
            --color-mint: #ffffff;
            --color-sky: #B9E5E8;
            --color-ocean: #7AB2D3;
            --color-navy: #4A628A;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--color-mint);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(74, 98, 138, 0.1);
        }

        .auth-image {
            width: 45%;
            background: linear-gradient(135deg, var(--color-ocean), var(--color-navy));
            padding: 60px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
        }

        .auth-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--color-sky) 30%, transparent);
            opacity: 0.1;
        }

        .auth-image h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
        }

        .auth-image p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.9;
            position: relative;
        }

        .auth-content {
            width: 55%;
            padding: 60px;
            background: white;
        }

        .auth-tabs {
            display: inline-flex;
            background: var(--color-mint);
            padding: 5px;
            border-radius: 12px;
            margin-bottom: 40px;
        }

        .auth-tabs button {
            padding: 12px 30px;
            border: none;
            background: none;
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-navy);
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .auth-tabs button.active {
            background: white;
            box-shadow: 0 2px 8px rgba(74, 98, 138, 0.1);
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .input-group {
            margin-bottom: 24px;
        }

        .input-group label {
            display: block;
            font-weight: 600;
            color: var(--color-navy);
            margin-bottom: 8px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #E5E9F2;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: var(--color-ocean);
            outline: none;
            box-shadow: 0 0 0 4px rgba(122, 178, 211, 0.1);
        }

        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 30px;
        }

        .social-button {
            padding: 12px 24px;
            border: 2px solid #E5E9F2;
            border-radius: 12px;
            background: white;
            color: var(--color-navy);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .social-button:hover {
            border-color: var(--color-ocean);
            background: var(--color-mint);
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #E5E9F2;
        }

        .divider span {
            color: #8896AB;
            font-weight: 500;
        }

        .submit-button {
            width: 100%;
            padding: 16px;
            background: var(--color-navy);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-button:hover {
            background: var(--color-ocean);
        }

        .form-footer {
            margin-top: 30px;
            text-align: center;
            color: #8896AB;
        }

        .form-footer a {
            color: var(--color-navy);
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            color: var(--color-ocean);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--color-ocean);
        }

        .error-message {
            color: #FF6B6B;
            font-size: 0.875rem;
            margin-top: 6px;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            pointer-events: none;
            opacity: 0.1;
        }

        @media (max-width: 1024px) {
            .auth-container {
                flex-direction: column;
            }
            .auth-image,
            .auth-content {
                width: 100%;
                padding: 40px;
            }
            .auth-image {
                text-align: center;
                padding: 60px 40px;
            }
        }

        @media (max-width: 640px) {
            .social-buttons {
                grid-template-columns: 1fr;
            }
            .auth-tabs button {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
            .auth-image h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-image">
            <div>
                <h1 style="text-align:center">   Tas'heel</h1>
                <p>Enter your credentials to access your account and continue your journey with us.</p>
            </div>
            <div class="floating-shapes">
                <!-- Add SVG shapes or patterns here for visual interest -->
            </div>
        </div>

        <div class="auth-content">
            <div class="auth-tabs">
                <button class="active" id="loginTab">Sign In</button>
                <button id="registerTab">Create Account</button>
            </div>

            <!-- Login Form -->
            <div class="form-section active" id="loginForm">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    

                    
                    <div class="input-group">
                        <label>Email address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="submit-button">Sign In</button>

                    <div class="form-footer">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Forgot your password?</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Register Form -->
            <div class="form-section" id="registerForm">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>Email address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>Mobile Phone</label>
                        <input type="tel" name="mobile_phone" value="{{ old('mobile_phone') }}" required>
                        @error('mobile_phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>Phone Number (optional)</label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>User Role</label>
                        <select name="user_role" required>
                            <option value="">Select Role</option>
                            <option value="admin" {{ old('user_role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="client" {{ old('user_role') === 'client' ? 'selected' : '' }}>Client</option>
                            <option value="technician" {{ old('user_role') === 'technician' ? 'selected' : '' }}>Technician</option>
                        </select>
                        @error('user_role')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>Profile Image</label>
                        <input type="file" name="profile_image">
                        @error('profile_image')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="submit-button">Create Account</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.add('active');
            loginForm.classList.remove('active');
        });

        // Show registration form if there are any registration errors
        @if($errors->hasAny(['name', 'mobile_phone', 'phone_number', 'user_role', 'profile_image', 'password_confirmation']))
            registerTab.click();
        @endif
    </script>
</body>
</html>