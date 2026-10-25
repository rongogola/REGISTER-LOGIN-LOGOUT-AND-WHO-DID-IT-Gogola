<?php
require_once 'db_config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (!empty($username) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);
            $message = "Success! <a href='login.php'>Click here to login</a>";
        } catch (PDOException $e) {
            $message = "Username already taken.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Donut Shop</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fffcf9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(109, 76, 65, 0.05); width: 320px; text-align: center; border: 1px solid #f5ede6; }
        h2 { color: #6d4c41; margin-bottom: 5px; font-weight: 600; }
        .subtitle { color: #a1785c; font-size: 14px; margin-bottom: 25px; }
        input { width: 100%; padding: 12px 16px; margin: 8px 0; box-sizing: border-box; border: 1px solid #ebdcd0; border-radius: 8px; background: #fdfbf9; font-size: 14px; outline: none; transition: border-color 0.2s; }
        input:focus { border-color: #ffd3e2; background: #fff; }
        button { background: #e8def8; color: #4f378b; border: none; padding: 12px; width: 100%; cursor: pointer; font-weight: bold; border-radius: 20px; font-size: 15px; margin-top: 15px; box-shadow: 0 4px 10px rgba(232, 222, 248, 0.5); transition: all 0.2s; }
        button:hover { background: #dfd0f6; transform: translateY(-1px); }
        p { font-size: 14px; color: #8a7a72; margin-top: 20px; }
        a { color: #7c90c1; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
        .msg { font-size: 14px; padding: 10px; border-radius: 6px; font-weight: 500; background: #fcf6f0; color: #6d4c41; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Join the Shop 🍩</h2>
        <div class="subtitle">Create an account to start record logging</div>
        <?php if($message) echo "<div class='msg'>$message</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Choose Username" required>
            <input type="password" name="password" placeholder="Choose Password" required>
            <button type="submit">Create Account</button>
        </form>
        <p>Already registered? <a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>