<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donut_id = $_POST['donut_id'];
    $name = trim($_POST['ingredient_name']);
    $quantity = trim($_POST['quantity']);
    $user_id = $_SESSION['user_id']; 

    $stmt = $pdo->prepare("INSERT INTO ingredients (donut_id, ingredient_name, quantity, added_by) VALUES (?, ?, ?, ?)");
    $stmt->execute([$donut_id, $name, $quantity, $user_id]);
    header("Location: index.php");
    exit;
}

$donuts = $pdo->query("SELECT * FROM donuts")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Ingredient Record</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fffcf9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 35px; border-radius: 16px; box-shadow: 0 10px 30px rgba(109, 76, 65, 0.04); width: 340px; border: 1px solid #f5ede6; }
        h3 { margin-top: 0; color: #6d4c41; font-size: 20px; font-weight: 600; margin-bottom: 5px; }
        .desc { color: #9e9e9e; font-size: 13px; margin-bottom: 25px; line-height: 1.4; }
        label { display: block; font-size: 13px; font-weight: 600; color: #8a7a72; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        input, select { width: 100%; padding: 12px; margin-bottom: 20px; box-sizing: border-box; border: 1px solid #ebdcd0; border-radius: 8px; background: #fdfbf9; font-size: 14px; outline: none; -webkit-appearance: none; }
        input:focus, select:focus { border-color: #ffd3e2; background: #fff; }
        .btn-group { display: flex; gap: 10px; margin-top: 10px; }
        .btn { flex: 1; padding: 12px; border: none; font-weight: bold; border-radius: 20px; font-size: 14px; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.2s; }
        .btn-save { background: #e8def8; color: #4f378b; box-shadow: 0 4px 10px rgba(232, 222, 248, 0.4); }
        .btn-save:hover { background: #dfd0f6; transform: translateY(-1px); }
        .btn-cancel { background: #f5f5f5; color: #666; }
        .btn-cancel:hover { background: #e0e0e0; }
    </style>
</head>
<body>

    <div class="form-container">
        <h3>Link Ingredient ✨</h3>
        <div class="desc">Adding an ingredient logs authorship to your active user entity.</div>
        
        <form method="POST">
            <label>Associated Donut Variant</label>
            <select name="donut_id" required>
                <option value="">-- Choose Target Donut --</option>
                <?php foreach($donuts as $d): ?>
                    <option value="<?php echo $d['donut_id']; ?>"><?php echo htmlspecialchars($d['donut_name']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label>Ingredient Item Name</label>
            <input type="text" name="ingredient_name" placeholder="e.g., Chocolate Sprinkles" required>
            
            <label>Quantity / Measurement</label>
            <input type="text" name="quantity" placeholder="e.g., 500g or 2 Liters" required>
            
            <div class="btn-group">
                <a href="index.php" class="btn btn-cancel">Cancel</a>
                <button type="submit" class="btn btn-save">Save Ingredient</button>
            </div>
        </form>
    </div>

</body>
</html>