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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$token = trim($_GET["token"] ?? $_POST["token"] ?? "");
$message = "";
$validReset = false;
$resetRow = null;

if ($token !== "") {
    $tokenHash = hash("sha256", $token);
    $stmt = $conn->prepare("SELECT reset_id, user_id FROM password_resets WHERE token_hash = ? AND expires_at > NOW() LIMIT 1");
    $stmt->bind_param("s", $tokenHash);
    $stmt->execute();
    $resetRow = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $validReset = (bool) $resetRow;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    if (!$validReset || !$resetRow) {
        $message = "This reset link is invalid or expired. Request a new one.";
    } elseif (strlen($password) < 8) {
        $message = "Use a password with at least 8 characters.";
    } elseif ($password !== $confirm) {
        $message = "The passwords do not match.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE `user` SET `password` = ? WHERE user_id = ?");
        $update->bind_param("si", $hash, $resetRow["user_id"]);
        if ($update->execute()) {
            $delete = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $delete->bind_param("i", $resetRow["user_id"]);
            $delete->execute();
            $delete->close();
            $message = "Password updated successfully. You can now log in.";
            $validReset = false;
        } else {
            $message = "Could not update the password. Please try again.";
        }
        $update->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password | SAMS</title>
<style>
*{box-sizing:border-box}body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;background:#f1f5f9;font-family:Arial,sans-serif;color:#1e293b}
.card{width:100%;max-width:430px;background:#fff;padding:34px;border-radius:14px;box-shadow:0 8px 30px #00000012}
h1{margin-top:0;font-size:26px}p{color:#64748b;line-height:1.6}.message{padding:12px;border-radius:8px;background:#eff6ff;color:#1d4ed8;overflow-wrap:anywhere}
label{display:block;margin:17px 0 7px;font-weight:600}input{width:100%;padding:12px;border:1px solid #cbd5e1;border-radius:7px;font-size:15px}
button{width:100%;margin-top:20px;padding:13px;border:0;border-radius:7px;background:#2563eb;color:#fff;font-size:16px;cursor:pointer}a{color:#2563eb}


/* SAMS readability update */
html { font-size: 16px; }
body { font-size: 16px; }
h1, h2 { font-size: 27px; }
p, .description { font-size: 15px; }
label { font-size: 15px; }
input, select, textarea { font-size: 16px; }
button, .btn { font-size: 16px; }
.error, .success, .message, small { font-size: 14px; }
@media (max-width: 480px) { h1, h2 { font-size: 24px; } }

</style>
</head>
<body><main class="card">
<h1>Reset Password</h1>
<p>Choose a new password for your SAMS account.</p>
<?php if ($message !== ""): ?><p class="message"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p><?php endif; ?>
<?php if ($validReset): ?>
<form method="post" action="reset_password.php">
<input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, "UTF-8") ?>">
<label for="password">New password</label>
<input type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>
<label for="confirm_password">Confirm password</label>
<input type="password" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password" required>
<button type="submit">Update password</button>
</form>
<?php endif; ?>
<p><a href="login.php">Back to Login</a></p>
</main></body></html>
