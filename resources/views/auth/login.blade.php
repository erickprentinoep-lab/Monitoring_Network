<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | NetAnalysis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Background animated grid */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 50px 50px;
            }
        }

        /* Glow orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            pointer-events: none;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #3b82f6, transparent);
            top: -100px;
            left: -100px;
            animation: float 8s ease-in-out infinite;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #06b6d4, transparent);
            bottom: -80px;
            right: -80px;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(30px);
            }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 12px;
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
        }

        .login-logo h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #f8fafc;
        }

        .login-logo p {
            font-size: 0.78rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            color: #f8fafc;
            font-size: 0.875rem;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control::placeholder {
            color: #475569;
        }

        .is-invalid {
            border-color: #ef4444 !important;
        }

        .error-msg {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 4px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 12px 16px;
            color: #ef4444;
            font-size: 0.82rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.4);
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin: 24px 0;
        }

        .demo-creds {
            background: rgba(6, 182, 212, 0.08);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 10px;
            padding: 14px;
        }

        .demo-creds h6 {
            font-size: 0.72rem;
            font-weight: 700;
            color: #06b6d4;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .cred-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .cred-item:last-child {
            margin-bottom: 0;
        }

        .cred-label {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .cred-value {
            font-size: 0.75rem;
            font-weight: 600;
            color: #f8fafc;
            font-family: monospace;
            background: rgba(255, 255, 255, 0.06);
            padding: 1px 8px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <div class="logo-icon"><i class="bi bi-router" style="color:#fff"></i></div>
                <h2>NetAnalysis</h2>
                <p>Manajemen & Analisis Performa Jaringan Internet</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" name="username" value="{{ old('username') }}"
                            class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan username" autocomplete="username">
                    </div>
                    @error('username')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password"
                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan password" autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
                </button>
            </form>

            <hr class="divider">

            <div class="demo-creds">
                <h6>🔑 Akun Demo</h6>
                <div class="cred-item">
                    <span class="cred-label">Admin (IT Support)</span>
                    <span class="cred-value">admin / admin123</span>
                </div>
                <div class="cred-item">
                    <span class="cred-label">Manager</span>
                    <span class="cred-value">manager / manager123</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>