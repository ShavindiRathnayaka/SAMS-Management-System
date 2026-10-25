<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Please enter your email and password.";
    } else {
        $stmt = $conn->prepare(
            "SELECT user_id, full_name, email_id, password, role
             FROM `USER`
             WHERE email_id = ?"
        );

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (
                $user &&
                password_verify($password, $user["password"])
            ) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["full_name"] = $user["full_name"];
                $_SESSION["email_id"] = $user["email_id"];
                $_SESSION["role"] = $user["role"];

                $stmt->close();

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid email address or password.";
            }

            $stmt->close();
        } else {
            $error = "Unable to process login. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SAMS - Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            min-height: 70px;
            background: #0f172a;
            color: #ffffff;
            padding: 0 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1.5px;
        }

        .navbar a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: #ffffff;
        }

        .login-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .login-box {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 45px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05),
                        0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        .login-box h1 {
            color: #0f172a;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }

        .login-box .subtitle {
            color: #64748b;
            text-align: center;
            margin-bottom: 32px;
            font-size: 15px;
        }

        .error-message {
            color: #b91c1c;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .login-box form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .form-group input {
            display: block;
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            color: #0f172a;
            background: #ffffff;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .login-box button {
            width: 100%;
            height: 48px;
            margin-top: 8px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-box button:hover {
            background: #1d4ed8;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 28px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .footer {
            padding: 24px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            border-top: 1px solid #e2e8f0;
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 35px 24px;
            }

            .login-box h1 {
                font-size: 24px;
            }

            .navbar {
                padding: 0 6%;
            }
        }
    

/* SAMS readability update: slightly larger text; original colors preserved */
html { font-size: 16px; }
body { font-size: 16px; }
.navbar a, .nav-links a { font-size: 15px; }
.login-box p, .login-box .subtitle, .description { font-size: 15px; }
label { font-size: 15px; }
input, select, textarea { font-size: 16px; }
button, .btn, .login-btn { font-size: 16px; }
.login-box h1 { font-size: 29px; }
.error, .success, .message, .forgot-link, .back-link { font-size: 14px; }
footer, .footer { font-size: 14px; }
@media (max-width: 480px) {
  .login-box h1 { font-size: 26px; }
  input, select, textarea { font-size: 16px; }
}

</style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">SAMS</div>
        <a href="index.php">Home</a>
    </nav>

    <main class="login-container">
        <div class="login-box">

            <h1>Welcome Back!</h1>
            <p class="subtitle">Login to SAMS System</p>

            <?php if ($error !== ""): ?>
                <div class="error-message" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <div style="text-align: right; margin-top: -12px;">
    <a href="forgot_password.php"
       style="color: #2563eb; text-decoration: none;
              font-size: 14px; font-weight: 500;">
        Forgot Password?
    </a>
</div>

                <button type="submit" name="login">Login</button>

            </form>

            <p style="text-align:center; margin-top:20px; font-size:15px;">
    Don't have an account?
    <a href="register.php"
       style="color:#2563eb; text-decoration:none; font-weight:600;">
        Create an Account
    </a>
</p>

<a href="index.php" class="back-link">Back to Home</a>

        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2026 SAMS. All rights reserved.</p>
    </footer>

</body>
</html>
