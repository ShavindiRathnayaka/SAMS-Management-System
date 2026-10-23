
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
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
        }

        .card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        h1 { color: #1e293b; font-size: 26px; }

        p {
            color: #64748b;
            line-height: 1.6;
        }

        label {
            display: block;
            margin: 20px 0 8px;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 15px;
        }

        button {
            width: 100%;
            margin-top: 18px;
            padding: 13px;
            border: none;
            border-radius: 7px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .message {
            margin-top: 18px;
            overflow-wrap: anywhere;
            font-size: 14px;
        }

        a { color: #2563eb; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Forgot Password?</h1>
        <p>Enter your registered email address to request a password reset.</p>

        <form method="POST" action="">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
            >
            <button type="submit">Request Reset</button>
        </form>

        <?php if ($message !== ""): ?>
            <p class="message">
                <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <?php if ($resetLink !== ""): ?>
            <p class="message">
                <strong>Local testing link (not for production):</strong><br>
                <a href="<?= htmlspecialchars($resetLink, ENT_QUOTES, "UTF-8") ?>">
                    Open Password Reset Page
                </a>
            </p>
            <p>This development link expires in 15 minutes.</p>
        <?php endif; ?>

        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
