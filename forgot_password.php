<?php
session_start();
require_once "db.php";

$conn->query("CREATE TABLE IF NOT EXISTS password_resets (
    reset_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id),
    CONSTRAINT fk_password_resets_user
        FOREIGN KEY (user_id) REFERENCES `user`(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$message = "";
$resetLink = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Invalid email format: [" .
        htmlspecialchars($email, ENT_QUOTES, "UTF-8") . "]";
} else {
        $stmt = $conn->prepare(
            "SELECT user_id FROM `user` WHERE email_id = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash("sha256", $token);
            $expiresAt = date("Y-m-d H:i:s", time() + 900);

            $deleteStmt = $conn->prepare(
                "DELETE FROM password_resets WHERE user_id = ?"
            );
            $deleteStmt->bind_param("i", $user["user_id"]);
            $deleteStmt->execute();
            $deleteStmt->close();

            $insertStmt = $conn->prepare(
                "INSERT INTO password_resets
                (user_id, token_hash, expires_at)
                VALUES (?, ?, ?)"
            );
            $insertStmt->bind_param(
                "iss",
                $user["user_id"],
                $tokenHash,
                $expiresAt
            );
            $insertStmt->execute();
            $insertStmt->close();

            // Local testing only. Configure email delivery before deployment.
            $resetLink = "http://localhost:8080/SAMS/reset_password.php?token="
                . urlencode($token);
        }

        $message = "If the email address exists, a reset request has been created.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SAMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', Arial, sans-serif; background: #f8fafc; color: #0f172a; min-height: 100vh; display: flex; flex-direction: column; }
        .navbar { min-height: 70px; background: #0f172a; color: #fff; padding: 0 8%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .logo { font-size: 24px; font-weight: 700; letter-spacing: 1.5px; }
        .navbar a { color: #94a3b8; text-decoration: none; font-size: 15px; font-weight: 500; transition: color .3s; }
        .navbar a:hover { color: #fff; }
        .container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px; background: radial-gradient(circle at top right, #dbeafe 0, transparent 35%), linear-gradient(135deg, #f8fafc, #eff6ff); }
        .card { background: #fff; width: 100%; max-width: 420px; padding: 45px 40px; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,.05), 0 8px 10px -6px rgba(0,0,0,.05); }
        .card h1 { color: #0f172a; font-size: 26px; font-weight: 700; text-align: center; margin-bottom: 8px; }
        .card .subtitle { color: #64748b; text-align: center; margin-bottom: 28px; font-size: 15px; line-height: 1.6; }
        .message { color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 14px; text-align: center; overflow-wrap: anywhere; }
        .message.small { text-align: left; }
        form { display: flex; flex-direction: column; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 14px; font-weight: 600; color: #334155; }
        .form-group input { width: 100%; height: 48px; padding: 0 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; color: #0f172a; background: #fff; transition: border-color .3s, box-shadow .3s; }
        .form-group input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,.15); }
        button { width: 100%; height: 48px; margin-top: 4px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background .3s; }
        button:hover { background: #1d4ed8; }
        .back-link { display: block; text-align: center; margin-top: 26px; color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500; }
        .back-link:hover { color: #2563eb; text-decoration: underline; }
        .message a { color: #2563eb; font-weight: 600; }
        .footer { padding: 24px; text-align: center; color: #94a3b8; font-size: 13px; border-top: 1px solid #e2e8f0; }
        @media (max-width: 480px) { .card { padding: 35px 24px; } .navbar { padding: 0 6%; } }
    

/* SAMS readability update */
html { font-size: 16px; }
body { font-size: 16px; }
.card h1, .card h2 { font-size: 27px; }
.card p, .description { font-size: 15px; }
label { font-size: 15px; }
input, select, textarea { font-size: 16px; }
button, .btn { font-size: 16px; }
.message, .message a, .back-link { font-size: 14px; }
.navbar a, .footer { font-size: 14px; }
@media (max-width: 480px) { .card h1, .card h2 { font-size: 24px; } }

</style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">SAMS</div>
        <a href="index.php">Home</a>
    </nav>
    <main class="container">
        <div class="card">
            <h1>Forgot Password?</h1>
            <p class="subtitle">Enter your registered email address to request a password reset.</p>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <button type="submit">Request Reset</button>
            </form>

            <?php if ($message !== ""): ?>
                <p class="message"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
            <?php endif; ?>

            <?php if ($resetLink !== ""): ?>
                <p class="message small">
                    <strong>Local testing link (not for production):</strong><br>
                    <a href="<?= htmlspecialchars($resetLink, ENT_QUOTES, "UTF-8") ?>">Open Password Reset Page</a><br>
                    This development link expires in 15 minutes.
                </p>
            <?php endif; ?>

            <a href="login.php" class="back-link">Back to Login</a>
        </div>
    </main>
    <footer class="footer">
        <p>&copy; 2026 SAMS. All rights reserved.</p>
    </footer>
</body>
</html>