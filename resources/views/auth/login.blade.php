<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Youth Swimming Club</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .club-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 24px;
        }
        
        .login-header h1 {
            color: #333;
            margin-bottom: 5px;
            font-size: 24px;
            font-weight: 600;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            font-size: 14px;
        }
        
        .demo-info {
            margin-top: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .demo-info h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .demo-account {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .demo-account:last-child {
            margin-bottom: 0;
        }
        
        .demo-account strong {
            color: #667eea;
            min-width: 50px;
        }
        
        .demo-account .email {
            flex: 1;
            margin: 0 10px;
            color: #666;
            font-family: monospace;
        }
        
        .demo-account .password {
            color: #333;
            font-weight: 600;
            font-family: monospace;
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
            }
            
            .demo-account {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="club-logo">
                <i class="fas fa-swimming-pool"></i>
            </div>
            <h1>Youth Swimming Club</h1>
            <p>Sistem Manajemen Club</p>
        </div>
        
        @if ($errors->any())
            <div class="alert">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $errors->first() }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>
    </div>
</body>
</html>