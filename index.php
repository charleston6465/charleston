
<?php
// Start the session if it hasn't been started in db.php
session_start();

require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Safely retrieve and clean inputs
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Basic empty check before hitting the database
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Fetch only the required columns
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Set character encoding -->
    <meta charset="UTF-8">

    <!-- Make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Set page title -->
    <title>Car Parking Management System</title>

    <!-- Link external CSS file -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <!-- Main login container -->
    <div class="login-box">

        <!-- Website heading -->
        <h2>CAR PARKING SYSTEM</h2>

        <p>Admin Login</p>

        <!-- Display login error message -->
        <?php if (!empty($error)): ?>

            <p style="color:red;">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" action="">

            <!-- Email input field -->
            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter Email"
                required
            >
            <br>
<br>

            <!-- Password input field -->
            <label>Password</label>
            

            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required
            >

            <!-- Login submit button -->
            <button type="submit">
                LOGIN
            </button>

        </form>

        <!-- Footer text -->
        <p>Car Parking Management System</p>

    </div>

</body>
</html>

