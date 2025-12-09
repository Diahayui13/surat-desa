<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Aplikasi Surat Desa</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: white;
            min-height: 100vh;
        }
        
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 1100px;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
        }
        
        @media (max-width: 768px) {
            .register-card {
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
        
        .info-list {
            margin-top: 30px;
            text-align: left;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .info-item i {
            font-size: 20px;
        }
        
        .right-section {
            padding: 50px 45px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            .right-section {
                padding: 40px 30px;
                max-height: none;
            }
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 35px;
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
            margin-bottom: 5px;
        }
        
        .form-header p {
            font-size: 14px;
            color: #718096;
        }
        
        .form-group {
            margin-bottom: 20px;
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
        
        .register-button {
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
            margin-top: 10px;
        }
        
        .register-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .register-button:active {
            transform: translateY(0);
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #718096;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .login-link a:hover {
            color: #764ba2;
        }
        
        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .error-list li {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }
        
        /* Custom Scrollbar */
        .right-section::-webkit-scrollbar {
            width: 8px;
        }
        
        .right-section::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .right-section::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Left Section (Desktop Only) -->
            <div class="left-section">
                <div class="logo-circle">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div class="welcome-text">
                    <h2>Selamat Datang di</h2>
                    <p><strong style="font-size: 20px;">E-Sumbertlaseh</strong></p>
                    <p style="margin-top: 15px; font-size: 14px; opacity: 0.9;">
                        Daftar untuk mengakses layanan pengajuan surat desa secara online
                    </p>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>Proses cepat & mudah</span>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>Lacak status pengajuan</span>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>Cetak surat otomatis</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Section (Form) -->
            <div class="right-section">
                <div class="form-header">
                    <i class="fa-solid fa-user-plus"></i>
                    <h3>Buat Akun Baru</h3>
                    <p>Isi form di bawah untuk mendaftar</p>
                </div>
                
                <!-- Error Messages -->
                @if ($errors->any())
                <div class="error-message">
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                        <li>
                            <i class="fa-solid fa-exclamation-circle"></i>
                            <span>{{ $error }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   placeholder="Masukkan nama lengkap">
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"  
                                   required 
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
                                   placeholder="Minimal 8 karakter">
                            <i class="fa-solid fa-eye toggle-password" 
                               onclick="togglePassword('password', this)"></i>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   placeholder="Ulangi password">
                            <i class="fa-solid fa-eye toggle-password" 
                               onclick="togglePassword('password_confirmation', this)"></i>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="register-button">
                        Daftar
                    </button>
                    
                    <!-- Login Link -->
                    <div class="login-link">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(inputId, icon) {
            const passwordInput = document.getElementById(inputId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>