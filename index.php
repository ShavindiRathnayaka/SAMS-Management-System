<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SAMS - Home</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            min-height: 76px;
            background: #0f172a;
            color: #ffffff;
            padding: 0 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 25px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ffffff;
        }

        .nav-links .login-btn {
            background: #2563eb;
            color: #ffffff;
            padding: 11px 22px;
            border-radius: 8px;
        }

        .nav-links .login-btn:hover {
            background: #1d4ed8;
        }

        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 90px 20px;
            background:
                radial-gradient(circle at top right, #dbeafe 0, transparent 35%),
                linear-gradient(135deg, #f8fafc, #eff6ff);
        }

        .hero-content {
            max-width: 850px;
        }

        .tag {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            padding: 9px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }

        .hero h1 {
            font-size: clamp(36px, 5vw, 58px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -1.5px;
            margin-bottom: 24px;
        }

        .hero h1 span {
            color: #2563eb;
        }

        .hero p {
            max-width: 680px;
            margin: 0 auto 34px;
            color: #64748b;
            font-size: 17px;
            line-height: 1.9;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 15px 27px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.18);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            border-color: #2563eb;
            color: #2563eb;
        }

        .features {
            padding: 65px 8%;
            background: #ffffff;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 38px;
        }

        .section-heading h2 {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .section-heading p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.7;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .feature-card {
            padding: 30px 25px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            transition: transform 0.3s ease,
                        box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(15, 23, 42, 0.07);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            border-radius: 12px;
            font-size: 23px;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 18px;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.8;
        }

        .cta {
            padding: 65px 20px;
            text-align: center;
            background: #eff6ff;
        }

        .cta h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .cta p {
            color: #64748b;
            margin-bottom: 25px;
            line-height: 1.8;
        }

        .footer {
            padding: 24px 20px;
            text-align: center;
            color: #94a3b8;
            background: #0f172a;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 18px 6%;
                gap: 18px;
                flex-wrap: wrap;
            }

            .nav-links {
                gap: 15px;
                flex-wrap: wrap;
            }

            .hero {
                padding: 70px 20px;
            }

            .hero p {
                font-size: 15px;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .features {
                padding: 50px 6%;
            }
        }

        @media (max-width: 420px) {
            .nav-links {
                gap: 12px;
            }

            .nav-links a {
                font-size: 12px;
            }

            .logo {
                font-size: 22px;
            }

            .hero h1 {
                font-size: 35px;
            }

            .btn {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">SAMS</div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="#features">Features</a>
            <a href="login.php" class="login-btn">Login</a>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="hero-content">

                <div class="tag">
                    EXAMINATION MANAGEMENT SYSTEM
                </div>

                <h1>
                    Scrutiny Board<br>
                    <span>Management System</span>
                </h1>

                <p>
                    A centralized system designed to manage scrutiny boards,
                    examination papers, moderation activities, review processes
                    and final report submissions efficiently and systematically.
                </p>

                <div class="hero-buttons">
                    <a href="login.php" class="btn btn-primary">
                        Get Started &rarr;
                    </a>

                    <a href="#features" class="btn btn-secondary">
                        Explore Features
                    </a>
                </div>

            </div>
        </section>

        <section class="features" id="features">

            <div class="section-heading">
                <h2>System Features</h2>
                <p>
                    Simplifying examination scrutiny and review management.
                </p>
            </div>

            <div class="feature-grid">

                <div class="feature-card">
                    <div class="feature-icon">&#128101;</div>
                    <h3>Board Management</h3>
                    <p>
                        Organize scrutiny boards, manage memberships
                        and coordinate assigned board members.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">&#128196;</div>
                    <h3>Examination Papers</h3>
                    <p>
                        Maintain examination cycle information,
                        paper details and related examination records.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">&#128221;</div>
                    <h3>Review & Reports</h3>
                    <p>
                        Support scrutiny reviews, revision tracking,
                        report preparation and final submissions.
                    </p>
                </div>

            </div>
        </section>

        <section class="cta">
            <h2>Welcome to SAMS</h2>

            <p>
                Access the system to manage examination scrutiny
                activities in one organized place.
            </p>

            <a href="login.php" class="btn btn-primary">
                Login to System &rarr;
            </a>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2026 SAMS. All rights reserved.</p>
    </footer>

</body>
</html>
