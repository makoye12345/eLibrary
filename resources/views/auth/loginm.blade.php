<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: var(--dark-color);
        }
        
        .login-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }
        
        .main-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .sub-title {
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 20px;
        }
        
        .login-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .login-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .form-control {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .btn-login {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: #1a252f;
        }
        
        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .date-display {
            text-align: center;
            font-style: italic;
            color: #6c757d;
            margin-top: 20px;
        }
        
        .system-info {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .info-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-section h4 {
            font-size: 1.2rem;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .info-section ul {
            padding-left: 20px;
        }
        
        .info-section li {
            margin-bottom: 8px;
        }
        
        .download-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .download-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .main-title {
                font-size: 2rem;
            }
            
            .sub-title {
                font-size: 1.2rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="header">
            <h1 class="main-title">LIBRARY MANAGEMENT SYSTEM</h1>
            <h2 class="sub-title">DEUS UNIVERSITY OF TANZANIA</h2>
            <h3>Academic Year: 2024/2025</h3>
        </div>
        
        <div class="row">
            <div class="col-lg-6 order-lg-1 order-2">
                <div class="system-info">
                    <h3 class="info-title">Welcome to LMS</h3>
                    <p>The Library Management System (LMS) provides comprehensive access to all library resources.</p>
                    
                    <div class="info-section">
                        <h4>Students</h4>
                        <ul>
                            <li>Search and reserve books online</li>
                            <li>View borrowing history</li>
                            <li>Access e-resources</li>
                        </ul>
                    </div>
                    
                    <div class="info-section">
                        <h4>Librarians</h4>
                        <ul>
                            <li>Manage book inventory</li>
                            <li>Track book loans and returns</li>
                            <li>Generate reports</li>
                        </ul>
                    </div>
                    
                    <div class="info-section">
                        <h4>Other Features</h4>
                        <ul>
                            <li>Fine payment system</li>
                            <li>User management</li>
                            <li>System configuration</li>
                        </ul>
                    </div>
                    
                    <div class="info-section">
                        <h4>HOW TO GENERATE CONTROL NUMBER</h4>
                        <a href="#" class="download-link">Click here to Download</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 order-lg-2 order-1 mb-4 mb-lg-0">
                <div class="login-card">
                    <div class="login-header">
                        <h3 class="login-title">Login</h3>
                        <p>Please login to continue</p>
                    </div>
                    
                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        
                        <div class="login-footer">
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Remember me</label>
                            </div>
                            
                            <div>
                                <a href="/forgot-password">Forgot your password?</a>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-login">Login</button>
                        
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="/register">Register here</a></p>
                        </div>
                    </form>
                </div>
                
                <div class="date-display">
                    <p>May 25, 2025</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>