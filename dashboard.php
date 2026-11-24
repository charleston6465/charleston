<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $new_email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!empty($new_email) && !empty($password)) {

        // Verify current user's password
        $stmt = $pdo->prepare(
        
        );

        $stmt->execute([$_SESSION["user_id"]]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {

            // Check if email belongs to another user
            $check = $pdo->prepare(
                "SELECT id FROM users WHERE email = ? AND id != ?"
            );

            $check->execute([
                $new_email,
                $_SESSION["user_id"]
            ]);

            if (!$check->fetch()) {

                // Update email
                $update = $pdo->prepare(
                    "UPDATE users SET email = ? WHERE id = ?"
                );

                $update->execute([
                    $new_email,
                    $_SESSION["user_id"]
                ]);

                $message = "Email changed successfully!";

            } else {
                $message = "Invalid Email or Password!";
            }

        } else {
            $message = "Invalid Email or Password!";
        }

    } else {
        $message = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Change Email</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-box">

    <h2>Change Your Email</h2>

    <?php if (!empty($message)): ?>
        <p>
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>New Email</label>

        <input type="email"
               name="email"
               placeholder="Enter new email"
               required>

        <br><br>

        <label>Current Password</label>

        <input type="password"
               name="password"
               placeholder="Enter current password"
               required>

        <br><br>

        <button type="submit">
            UPDATE EMAIL
        </button>

    </form>

    <a href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>