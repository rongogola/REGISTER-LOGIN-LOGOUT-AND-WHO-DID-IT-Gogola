<?php
session_start();
// Security check: Don't let unlogged clients submit data
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['donut_name']);
    $price = trim($_POST['price']);
    $user_id = $_SESSION['user_id']; // Track which active User Entity made this addition

    $stmt = $pdo->prepare("INSERT INTO donuts (donut_name, price, added_by) VALUES (?, ?, ?)");
    $stmt->execute([$name, $price, $user_id]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Donut record</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fffcf9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 35px; border-radius: 16px; box-shadow: 0 10px 30px rgba(109, 76, 65, 0.04); width: 340px; border: 1px solid #f5ede6; }
        h3 { margin-top: 0; color: #6d4c41; font-size: 20px; font-weight: 600; margin-bottom: 5px; }
        .desc { color: #9e9e9e; font-size: 13px; margin-bottom: 25px; line-height: 1.4; }
        label { display: block; font-size: 13px; font-weight: 600; color: #8a7a72; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; box-sizing: border-box; border: 1px solid #ebdcd0; border-radius: 8px; background: #fdfbf9; font-size: 14px; outline: none; }
        input:focus { border-color: #ffd3e2; background: #fff; }
        .btn-group { display: flex; gap: 10px; margin-top: 10px; }
        .btn { flex: 1; padding: 12px; border: none; font-weight: bold; border-radius: 20px; font-size: 14px; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.2s; }
        .btn-save { background: #ffd3e2; color: #6d4c41; box-shadow: 0 4px 10px rgba(255, 211, 226, 0.4); }
        .btn-save:hover { background: #ffc2d6; transform: translateY(-1px); }
        .btn-cancel { background: #f5f5f5; color: #666; }
        .btn-cancel:hover { background: #e0e0e0; }
    </style>
</head>
<body>

    <div class="form-container">
        <h3>Create Donut Entry ✨</h3>
        <div class="desc">Adding this item tags your account name to the master database.</div>
        
        <form method="POST">
            <label>Donut Variant Name</label>
            <input type="text" name="donut_name" placeholder="e.g., Strawberry Sprinkle" required>
            
            <label>Retail Price ($)</label>
            <input type="number" step="0.01" name="price" placeholder="0.00" required>
            
            <div class="btn-group">
                <a href="index.php" class="btn btn-cancel">Cancel</a>
                <button type="submit" class="btn btn-save">Save Entry</button>
            </div>
        </form>
    </div>

</body>
</html>