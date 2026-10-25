<?php
session_start();
require_once 'db_config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Donut Shop</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fffcf9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(109, 76, 65, 0.05); width: 320px; text-align: center; border: 1px solid #f5ede6; }
        h2 { color: #6d4c41; margin-bottom: 5px; font-weight: 600; }
        .subtitle { color: #a1785c; font-size: 14px; margin-bottom: 25px; }
        input { width: 100%; padding: 12px 16px; margin: 8px 0; box-sizing: border-box; border: 1px solid #ebdcd0; border-radius: 8px; background: #fdfbf9; font-size: 14px; outline: none; transition: border-color 0.2s; }
        input:focus { border-color: #ffd3e2; background: #fff; }
        button { background: #ffd3e2; color: #6d4c41; border: none; padding: 12px; width: 100%; cursor: pointer; font-weight: bold; border-radius: 20px; font-size: 15px; margin-top: 15px; box-shadow: 0 4px 10px rgba(255, 211, 226, 0.4); transition: all 0.2s; }
        button:hover { background: #ffc2d6; transform: translateY(-1px); }
        p { font-size: 14px; color: #8a7a72; margin-top: 20px; }
        a { color: #7c90c1; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Welcome Back 🍩</h2>
        <div class="subtitle">Log in to manage your bakery backend</div>
        <?php if($error) echo "<p style='color:#c62828; font-weight:500; background:#ffebee; padding:10px; border-radius:6px; font-size:13px;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <p>New to the shop? <a href="register.php">Create an account</a></p>
    </div>
</body>
</html>