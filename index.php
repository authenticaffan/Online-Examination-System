<?php
// PHP variables
$site_title = "ExamPro - Online Examination System | College Project";
$current_year = date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --background: #ffffff;
            --foreground: #0f172a;
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-foreground: #ffffff;
            --secondary: #f1f5f9;
            --secondary-foreground: #1e293b;
            --muted: #f1f5f9;
            --muted-foreground: #64748b;
            --accent: #f1f5f9;
            --accent-foreground: #1e293b;
            --destructive: #ef4444;
            --destructive-foreground: #ffffff;
            --border: #e2e8f0;
            --input: #e2e8f0;
            --ring: #3b82f6;
            --radius: 0.5rem;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            color: var(--foreground);
            background-color: var(--background);
            line-height: 1.5;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header */
        .header {
            position: sticky;
            top: 0;
            z-index: 50;
            width: 100%;
            border-bottom: 1px solid var(--border);
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .navbar {
            display: flex;
            height: 4rem;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }

        .nav-links {
            display: none;
            flex-direction: column;
            gap: 1rem;
            position: absolute;
            top: 4rem;
            right: 1rem;
            background-color: var(--background);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            border-radius: var(--radius);
            z-index: 1000;
        }

        .nav-links.active {
            display: flex;
        }

        @media (min-width: 768px) {
            .nav-links {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                position: static;
                flex-direction: row;
                box-shadow: none;
                padding: 0;
            }
        }

        .nav-link {
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1;
            transition: all 0.2s;
            cursor: pointer;
            padding: 0.5rem 1rem;
            height: 2.5rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            height: 3rem;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--primary-foreground);
            border: 1px solid var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary);
            color: var(--primary-foreground);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--secondary-foreground);
            border: 1px solid var(--secondary);
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        .btn-white {
            background-color: white;
            color: var(--primary);
            border: 1px solid white;
        }

        .btn-white:hover {
            background-color: rgba(255, 255, 255, 0.9);
        }

        .btn-outline-white {
            background-color: transparent;
            color: white;
            border: 1px solid white;
        }

        .btn-outline-white:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .btn-icon {
            padding: 0;
            width: 2.5rem;
        }

        .mobile-menu-btn {
            display: block;
        }

        @media (min-width: 768px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        .mobile-menu-btn {
            background: none;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            width: 100%;
            padding: 3rem 0;
            background: linear-gradient(to right, #ebf2ff, #eef2ff);
        }

        @media (min-width: 768px) {
            .hero {
                padding: 6rem 0;
            }
        }

        @media (min-width: 1024px) {
            .hero {
                padding: 8rem 0;
            }
        }

        .hero-content {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                align-items: center;
            }
        }

        .hero-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1rem;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.025em;
        }

        @media (min-width: 640px) {
            .hero-title {
                font-size: 2.5rem;
            }
        }

        @media (min-width: 768px) {
            .hero-title {
                font-size: 3rem;
            }
        }

        @media (min-width: 1024px) {
            .hero-title {
                font-size: 3.5rem;
            }
        }

        .hero-subtitle {
            color: var(--muted-foreground);
            font-size: 1rem;
            max-width: 600px;
        }

        @media (min-width: 768px) {
            .hero-subtitle {
                font-size: 1.25rem;
            }
        }

        .hero-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        @media (min-width: 640px) {
            .hero-buttons {
                flex-direction: row;
            }
        }

        .hero-stats {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin-top: 1rem;
        }

        .hero-stats div {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .hero-image {
            display: flex;
            justify-content: center;
            order: -1;
        }

        @media (min-width: 1024px) {
            .hero-image {
                order: 1;
            }
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: var(--radius);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Section Styles */
        section {
            width: 100%;
            padding: 3rem 0;
        }

        @media (min-width: 768px) {
            section {
                padding: 6rem 0;
            }
        }

        @media (min-width: 1024px) {
            section {
                padding: 8rem 0;
            }
        }

        .section-title {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.025em;
            margin-bottom: 0.5rem;
        }

        @media (min-width: 768px) {
            .section-title h2 {
                font-size: 2.5rem;
            }
        }

        .section-title p {
            color: var(--muted-foreground);
            max-width: 700px;
            font-size: 1rem;
        }

        @media (min-width: 768px) {
            .section-title p {
                font-size: 1.25rem;
            }
        }

        /* Features Section */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .feature-card {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
            background-color: white;
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            background-color: #ebf5ff;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .feature-description {
            color: var(--muted-foreground);
        }

        /* Access Section */
        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .access-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .access-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .access-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1.5rem;
            background-color: white;
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .access-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .access-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 4rem;
            height: 4rem;
            border-radius: 9999px;
            background-color: #ebf5ff;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .access-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .access-description {
            color: var(--muted-foreground);
            margin-bottom: 1.5rem;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(to right, #3b82f6, #4f46e5);
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .cta-title {
                font-size: 2.5rem;
            }
        }

        .cta-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (min-width: 768px) {
            .cta-subtitle {
                font-size: 1.25rem;
            }
        }

        .cta-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .cta-buttons {
                flex-direction: row;
                justify-content: center;
            }
        }

        /* About Section */
        .college-info {
            margin-top: 3rem;
            padding: 1.5rem;
            background-color: #f8fafc;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .college-info h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .college-info p {
            color: var(--muted-foreground);
            margin-bottom: 1rem;
        }

        .college-info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .college-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .college-info h4 {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .college-info ul {
            list-style-type: disc;
            list-style-position: inside;
            color: var(--muted-foreground);
        }

        .college-info li {
            margin-bottom: 0.25rem;
        }

        /* Contact Section */
        .contact-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .contact-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .contact-card {
            padding: 1.5rem;
            background-color: white;
            border-radius: var (--radius);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
        }

        .contact-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            background-color: #ebf5ff;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        /* Footer */
        .footer {
            background-color: #0f172a;
            color: #e2e8f0;
            padding: 3rem 0;
        }

        @media (min-width: 768px) {
            .footer {
                padding: 6rem 0;
            }
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .footer-content {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }

        .footer-description {
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .footer-social a {
            color: #94a3b8;
            transition: color 0.2s;
        }

        .footer-social a:hover {
            color: white;
        }

        .footer-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: #94a3b8;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #1e293b;
            text-align: center;
            color: #94a3b8;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        /* Utility Classes */
        .text-center {
            text-align: center;
        }

        .w-full {
            width: 100%;
        }

        .mt-3 {
            margin-top: 0.75rem;
        }

        .hover\:underline:hover {
            text-decoration: underline;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    ExamPro
                </a>
                <div class="nav-links" id="navLinks">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#access" class="nav-link">Access</a>
                    <a href="#about" class="nav-link">About</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </div>
                <div class="flex items-center gap-4">
                    <!-- <a href="#register" class="btn btn-primary hidden-mobile">Get Started</a> -->
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Online Examination System</h1>
                    <p class="hero-subtitle">A comprehensive platform for creating, managing, and taking exams online with real-time results and analytics.</p>
                    <div class="hero-buttons">
                        <a href="student/register.php" class="btn btn-primary btn-lg">Register Now</a>
                        <a href="student/login.php" class="btn btn-outline btn-lg">Student Login</a>
                    </div>
                    <div class="hero-stats">
                        <!-- <div>
                            <i class="fas fa-users"></i>
                            <span>10,000+ Students</span>
                        </div> -->
                        <!-- <div>
                            <i class="fas fa-graduation-cap"></i>
                            <span>100+ Institutions</span>
                        </div> -->
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://img.freepik.com/free-vector/online-certification-illustration_23-2148575636.jpg" alt="Online Examination System">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <div class="section-title">
                <h2>Powerful Features</h2>
                <p>Our online examination system comes with everything you need to create, manage, and take exams efficiently.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Timed Assessments</h3>
                    <p class="feature-description">Set time limits for exams with automatic submission when time expires. Students can see remaining time during the exam.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-random"></i>
                    </div>
                    <h3 class="feature-title">Randomized Questions</h3>
                    <p class="feature-description">Create question banks and randomize questions and answer options to prevent cheating.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="feature-title">Instant Results</h3>
                    <p class="feature-description">Get immediate results and detailed analytics after exam completion. Review performance metrics and identify areas for improvement.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Secure Testing</h3>
                    <p class="feature-description">Advanced security features including browser lockdown, IP restrictions, and plagiarism detection.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Mobile Friendly</h3>
                    <p class="feature-description">Take exams on any device with our responsive design that works on desktops, tablets, and smartphones.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-export"></i>
                    </div>
                    <h3 class="feature-title">Export Results</h3>
                    <p class="feature-description">Export results and analytics in various formats including PDF, Excel, and CSV for further analysis.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Access Section -->
    <section id="access" class="bg-slate-50">
        <div class="container">
            <div class="section-title">
                <h2>Access Your Portal</h2>
                <p>Different roles for different needs. Choose the portal that matches your role.</p>
            </div>
            <div class="access-cards">
                <div class="access-card">
                    <div class="access-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="access-title">Students</h3>
                    <p class="access-description">Take exams, view results, track progress, and access study materials.</p>
                    <a href="student/login.php" class="btn btn-primary">Student Login</a>
                    <div class="mt-3">
                        <a href="student/register.php" class="hover:underline">New student? Register here</a>
                    </div>
                </div>
                <div class="access-card">
                    <div class="access-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="access-title">Teachers</h3>
                    <p class="access-description">Create exams, manage questions, grade responses, and analyze student performance.</p>
                    <a href="teacher/login.php" class="btn btn-primary">Teacher Login</a>
                </div>
                <!-- <div class="access-card">
                    <div class="access-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3 class="access-title">Administrators</h3>
                    <p class="access-description">Manage users, monitor system usage, configure settings, and generate reports.</p>
                    <a href="admin/login.php" class="btn btn-primary">Admin Login</a>
                </div> -->
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2 class="cta-title">Ready to Get Started?</h2>
            <p class="cta-subtitle">Join thousands of students and educators who are already using our platform for online examinations.</p>
            <div class="cta-buttons">
                <a href="student/register.php" class="btn btn-white btn-lg">Register Now</a>
                <a href="#contact" class="btn btn-outline-white btn-lg">Contact Us</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="section-title">
                <h2>About Our System</h2>
                <p>Learn more about our comprehensive online examination platform</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="feature-title">Our Mission</h3>
                    <p class="feature-description">To provide a seamless, secure, and efficient online examination experience for educational institutions worldwide.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="feature-title">Our Story</h3>
                    <p class="feature-description">Currently, we are implementing this project in our college, with aspirations to grow into a trusted platform for schools and universities.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Our Team</h3>
                    <p class="feature-description">A dedicated team of student developers, testers and devops professionals working together to create the best examination platform possible.</p>
                </div>
            </div>
            
            <div class="college-info">
                <h3>College Project Information</h3>
                <p>This platform was developed as part of a final year project at CAHCET. Our team of computer science students designed and implemented this system under the guidance of our faculty advisors.</p>
                <div class="college-info-grid">
                    <div>
                        <h4>Project Team:</h4>
                        <ul>
                            <li>K Mohamed Affan (Team Lead)</li>
                            <li>V Mohammed Abuzer (Developer)</li>
                            <li>G Kareem Wasique (Tester)</li>
                        </ul>
                    </div>
                    <div>
                        <h4>Faculty Advisors:</h4>
                        <ul>
                            <li>Prof. Dr.R.Z. Inamul Hussain (Project Guide)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-slate-50">
        <div class="container">
            <div class="section-title">
                <h2>Contact Us</h2>
                <p>Have questions? We're here to help. Reach out to our team for support.</p>
            </div>
            <div class="contact-cards">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="feature-title">Email Support</h3>
                    <p class="feature-description">Get in touch with our support team via email.</p>
                    <a href="mailto:21644.affan.cse@cahcet.edu.in" class="btn btn-primary">Email Us</a>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3 class="feature-title">Phone Support</h3>
                    <p class="feature-description">Speak directly with our student support team.</p>
                    <a href="tel:+918940142875" class="btn btn-primary">Call Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div>
                    <div class="footer-logo">
                        <i class="fas fa-graduation-cap"></i>
                        ExamPro
                    </div>
                    <p class="footer-description">A comprehensive online examination system for educational institutions.</p>
                </div>
                <div>
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#access">Access</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="footer-title">Pages</h3>
                    <ul class="footer-links">
                        <li><a href="student/login.php">Student</a></li>
                        <li><a href="teacher/login.php">Teacher</a></li>
                    </ul>
                </div>
                <!--<div>
                    <h3 class="footer-title">Legal</h3>
                    <ul class="footer-links">
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">GDPR</a></li>
                    </ul>
                </div> -->
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo $current_year; ?> ExamPro - College Project. All rights reserved.</p>
                <p class="text-sm mt-2">Developed by Computer Science Students at CAHCET</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');

        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            
            // Change icon based on menu state
            const icon = mobileMenuBtn.querySelector('i');
            if (navLinks.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if (navLinks.classList.contains('active')) {
                        navLinks.classList.remove('active');
                        const icon = mobileMenuBtn.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });
        });
    </script>
</body>
</html>