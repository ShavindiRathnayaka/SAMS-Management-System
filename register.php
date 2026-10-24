
<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($full_name === "" || $email === "" || $password === "" || $confirm_password === "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must contain at least 8 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare(
            "SELECT user_id FROM `USER` WHERE email_id = ?"
        );

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "This email is already registered.";
                $stmt->close();
            } else {
                $stmt->close();

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $role = "Lecturer";

                $insert = $conn->prepare(
                    "INSERT INTO `USER` (full_name, email_id, password, role)
                     VALUES (?, ?, ?, ?)"
                );

                if ($insert) {
                    $insert->bind_param(
                        "ssss",
                        $full_name,
                        $email,
                        $hashed_password,
                        $role
                    );

                    if ($insert->execute()) {
                        $success = "Account created successfully. You can now log in.";
                    } else {
                        $error = "Unable to create account. Please try again.";
                    }

                    $insert->close();
                } else {
                    $error = "Unable to process registration.";
                }
            }
        } else {
            $error = "Unable to check email address.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMS - Create Account</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            min-height: 70px;
            background: #0f172a;
            color: white;
            padding: 0 8%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .navbar a {
            color: #cbd5e1;
            text-decoration: none;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 35px 20px;
        }

        .register-box {
            background: white;
            width: 100%;
            max-width: 460px;
            padding: 36px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin: 0 0 8px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 15px;
        }

        input {
            width: 100%;
            height: 46px;
            padding: 0 13px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 16px;
        }

        input:focus {
            outline: 2px solid #bfdbfe;
            border-color: #2563eb;
        }

        button {
            width: 100%;
            height: 48px;
            background: #2563eb;
            color: white;
            border: 0;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .links {
            text-align: center;
            margin-top: 22px;
            font-size: 15px;
        }

        .links a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        footer {
            padding: 22px;
            text-align: center;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .register-box {
                padding: 26px 20px;
            }

            h1 {
                font-size: 25px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">SAMS</div>
        <a href="index.php">Home</a>
    </nav>

    <main>
        <div class="register-box">
            <h1>Create Account</h1>
            <p class="subtitle">Register for the SAMS System</p>

            <?php if ($error !== ""): ?>
                <div class="message error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <?php if ($success !== ""): ?>
                <div class="message success" role="status">
                    <?= htmlspecialchars($success, ENT_QUOTES, "UTF-8") ?>
                    <a href="login.php">Go to Login</a>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name"
                           maxlength="150" autocomplete="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           maxlength="254" autocomplete="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           minlength="8" autocomplete="new-password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password"
                           name="confirm_password" minlength="8"
                           autocomplete="new-password" required>
                </div>

                <button type="submit">Create Account</button>
            </form>

            <div class="links">
                Already have an account?
                <a href="login.php">Login</a>
            </div>
        </div>
    </main>

    <footer>&copy; 2026 SAMS. All rights reserved.</footer>
</body>
</html>
