<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashed_password])) {
                $success = "Registration successful! You can now <a href='login.php' style='color: var(--secondary)'>Login</a>";
            } else {
                $error = "Something went wrong!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ParkSmart</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-gradient"></div>
    <div class="sphere sphere-1"></div>
    <div class="sphere sphere-2"></div>

    <div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
        <div class="glass-card" style="width: 100%; max-width: 450px;">
            <div class="logo" style="text-align: center; margin-bottom: 2rem;">ParkSmart</div>
            <h2 style="text-align: center; margin-bottom: 2rem;">Create Account</h2>

            <?php if ($error): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger); color: var(--danger); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid var(--success); color: var(--success); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="John Doe" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="john@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required placeholder="••••••••">
                </div>
                <button type="submit" name="register" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Sign Up</button>
            </form>

            <p style="text-align: center; margin-top: 2rem; color: var(--text-gray);">
                Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none;">Login</a>
            </p>
        </div>
    </div>
</body>
</html>
