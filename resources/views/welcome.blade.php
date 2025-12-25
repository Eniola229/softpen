<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SchoolCode Africa - Smart School Management System</title>
    <meta name="description" content="SchoolCode Africa helps schools record students and exams data with ease, it also includes CBT exam. Powered by SoftPen Technology" />
    <link rel="icon" type="image/png" href="assets/images/favicon.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #333;
        }

        /* Header/Navigation */
        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: #2d3748;
        }

        .logo span {
            color: #f59e0b;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #f59e0b;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 100px 20px;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.25rem;
            color: #4b5563;
            margin-bottom: 15px;
        }

        .powered-by {
            font-size: 1rem;
            color: #6b7280;
            font-style: italic;
            margin-bottom: 40px;
        }

        .hero-image {
            max-width: 600px;
            margin: 40px auto 0;
        }

        .hero-image img {
            width: 100%;
            height: auto;
        }

        /* Features Section */
        .features {
            padding: 80px 20px;
            background: #ffffff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 50px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
        }

        .feature-card {
            text-align: center;
            padding: 30px;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: #fef3c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #6b7280;
            line-height: 1.6;
        }

        /* Portal Section */
        .portals {
            padding: 80px 20px;
            background: #f9fafb;
        }

        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .portal-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .portal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #f59e0b;
        }

        .portal-icon {
            width: 70px;
            height: 70px;
            background: #fef3c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
        }

        .portal-card h3 {
            font-size: 1.75rem;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .portal-card p {
            color: #6b7280;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 14px 35px;
            background: #f59e0b;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #f59e0b;
        }

        .btn:hover {
            background: #d97706;
            border-color: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        /* Footer */
        .footer {
            background: #1f2937;
            color: #d1d5db;
            padding: 60px 20px 30px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h4 {
            color: #f9fafb;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .footer-section p {
            line-height: 1.8;
            margin-bottom: 10px;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid #374151;
            text-align: center;
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .nav-links {
                display: none;
            }

            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                SchoolCode <span>Africa</span>
            </div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#portals">Login</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Transform Your School Management Experience</h1>
            <p>SchoolCode Africa helps schools record students and exams data with ease, including comprehensive CBT examination systems.</p>
            <p class="powered-by">Powered by SoftPen Technology</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Why Choose SchoolCode Africa?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Easy Data Management</h3>
                    <p>Effortlessly manage student records, attendance, and academic performance in one centralized system.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💻</div>
                    <h3>CBT Examination</h3>
                    <p>Conduct computer-based tests with automatic grading and instant result generation.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📈</div>
                    <h3>Performance Tracking</h3>
                    <p>Monitor student progress with detailed analytics and comprehensive reporting tools.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Free Exam Practice</h3>
                    <p>Access free practice examinations for all classes. No registration required - start practicing immediately!</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure & Reliable</h3>
                    <p>Your data is protected with industry-standard security protocols and regular backups.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Mobile Friendly</h3>
                    <p>Access the platform from any device - desktop, tablet, or smartphone.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portals Section -->
    <section class="portals" id="portals">
        <div class="container">
            <h2 class="section-title">Access Your Portal</h2>
            <div class="portal-grid">
                <div class="portal-card">
                    <div class="portal-icon">👨‍🏫</div>
                    <h3>Staff Portal</h3>
                    <p>Access administrative tools, manage students, grade assignments, and oversee examinations.</p>
                    <a href="{{ route('staff/login') }}" class="btn">Staff Login</a>
                </div>

                <div class="portal-card">
                    <div class="portal-icon">🎓</div>
                    <h3>Student Portal</h3>
                    <p>View your courses, check results, submit assignments, and take scheduled examinations.</p>
                    <a href="{{ route('student/login') }}" class="btn">Student Login</a>
                </div>

                <div class="portal-card">
                    <div class="portal-icon">📝</div>
                    <h3>Exam Practice</h3>
                    <p>Sharpen your skills with practice tests, mock examinations, and study materials.</p>
                    <a href="{{ route('login') }}" class="btn">Practice Login</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h4>SchoolCode Africa</h4>
                <p>Empowering education through innovative technology solutions.</p>
                <p>Powered by SoftPen Technology</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <p><a href="#features" style="color: #d1d5db; text-decoration: none;">Features</a></p>
                <p><a href="#portals" style="color: #d1d5db; text-decoration: none;">Login Portals</a></p>
                <p><a href="#" style="color: #d1d5db; text-decoration: none;">Support</a></p>
            </div>
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>Email: schoolcode@schoolcode.africa</p>
                <p>Phone: +234 912 371 7415</p>
                <p>Location: Nigeria</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 SchoolCode Africa. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>