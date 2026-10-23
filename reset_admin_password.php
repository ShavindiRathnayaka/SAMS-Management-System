
<?php
require_once "db.php";

$email = "admin@sams.com";
$newPassword = "admin123";

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "UPDATE `user` SET `password` = ? WHERE `email_id` = ?"
);

if (!$stmt) {
    die("Database query failed: " . $conn->error);
}

$stmt->bind_param("ss", $hashedPassword, $email);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Password updated successfully!";
    } else {
        echo "No user updated. Check the email address.";
    }
} else {
    echo "Password update failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
