<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Surat Desa</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: white;
            min-height: 100vh;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .login-card {
                grid-template-columns: 1fr;
                max-width: 450px;
            }
            
            .left-section {
                display: none !important;
            }
        }
        
        .left-section {
            background: blue;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }
        
        .logo-circle {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .logo-circle i {
            font-size: 60px;
            background: orange;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .welcome-text h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .welcome-text p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .right-section {
            padding: 60px 50px;
        }
        
        @media (max-width: 768px) {
            .right-section {
                padding: 40px 30px;
            }
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .form-header i {
            font-size: 40px;
            color: blue;
            margin-bottom: 15px;
        }
        
        .form-header h3 {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f7fafc;
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-wrapper .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.3s;
        }
        
        .input-wrapper .toggle-password:hover {
            color: #667eea;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #4a5568;
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .forgot-link {
            font-size: 14px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .forgot-link:hover {
            color: #764ba2;
        }
        
        .login-button {
            width: 100%;
            padding: 16px;
            background: blue;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #718096;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .register-link a:hover {
            color: #764ba2;
        }
        
        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Left Section (Desktop Only) -->
            <div class="left-section">
                <div class="logo-circle">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div class="welcome-text">
                    <h2>Selamat Datang,</h2>
                    <p>Masuk untuk melanjutkan ke<br><strong>E-Sumbertlaseh</strong></p>
                    <p style="margin-top: 20px; font-size: 14px; opacity: 0.8;">
                        Login untuk mengajukan surat Anda
                    </p>
                </div>
            </div>
            
            <!-- Right Section (Form) -->
            <div class="right-section">
                <div class="form-header">
                    <i class="fa-solid fa-user-circle"></i>
                    <h3>Login ke Akun Anda</h3>
                </div>
                
                <!-- Error Messages -->
                @if ($errors->any())
                <div class="error-message">
                    <i class="fa-solid fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   placeholder="masukkan@email.com">
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="••••••••">
                            <i class="fa-solid fa-eye toggle-password" 
                               onclick="togglePassword()"></i>
                        </div>
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Ingat saya</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Lupa password?
                        </a>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="login-button">
                        Masuk
                    </button>
                    
                    <!-- Register Link -->
                    <div class="register-link">
                        Belum punya akun? 
                        <a href="{{ route('register') }}">Daftar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
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
    </script>
</body>
</html>