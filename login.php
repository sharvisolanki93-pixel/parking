<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ParkSmart</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-gradient"></div>
    <div class="sphere sphere-1"></div>
    <div class="sphere sphere-2"></div>

    <div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
        <div class="glass-card" style="width: 100%; max-width: 400px;">
            <div class="logo" style="text-align: center; margin-bottom: 2rem;">ParkSmart</div>
            <h2 style="text-align: center; margin-bottom: 2rem;">Welcome Back</h2>

            <?php if ($error): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger); color: var(--danger); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="john@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" name="login" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Login</button>
            </form>

            <p style="text-align: center; margin-top: 2rem; color: var(--text-gray);">
                Don't have an account? <a href="register.php" style="color: var(--primary); text-decoration: none;">Register</a>
            </p>
        </div>
    </div>
</body>
</html>
