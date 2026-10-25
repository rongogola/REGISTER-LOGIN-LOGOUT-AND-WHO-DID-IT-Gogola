<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db_config.php';

$donuts_stmt = $pdo->query("SELECT d.*, u.username AS creator FROM donuts d LEFT JOIN users u ON d.added_by = u.user_id");
$donuts = $donuts_stmt->fetchAll();

$ing_stmt = $pdo->query("SELECT i.*, d.donut_name, u.username AS creator FROM ingredients i 
                          JOIN donuts d ON i.donut_id = d.donut_id 
                          LEFT JOIN users u ON i.added_by = u.user_id");
$ingredients = $ing_stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Donut Shop Admin Panel</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fffcf9; margin: 30px; color: #4a4a4a; }
        .header { background: #ffd3e2; color: #6d4c41; padding: 20px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(255, 211, 226, 0.3); }
        .header h2 { margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 0.5px; }
        .header a { color: #6d4c41; text-decoration: none; font-weight: bold; border: 2px solid #6d4c41; padding: 6px 14px; border-radius: 20px; transition: all 0.3s ease; background: rgba(255, 255, 255, 0.3); }
        .header a:hover { background: #6d4c41; color: white; }
        .section { background: white; padding: 25px; margin-top: 25px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); border: 1px solid #f5ede6; }
        .section h3 { margin-top: 0; color: #7c90c1; font-size: 18px; border-bottom: 2px solid #ebf0f9; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; border-radius: 8px; overflow: hidden; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid #f7f1eb; }
        th { background-color: #fcf6f0; color: #8a7a72; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { font-size: 15px; }
        .creator-tag { background: #e2f0d9; color: #4472c4; padding: 4px 10px; border-radius: 12px; font-size: 13px; font-weight: 600; }
        .timestamp-tag { color: #9e9e9e; font-family: monospace; font-size: 13px; }
        .btn { background: #b4ceda; color: #2c4c5e; padding: 8px 16px; text-decoration: none; border-radius: 20px; font-size: 14px; font-weight: bold; display: inline-block; transition: all 0.2s ease; border: none; box-shadow: 0 3px 8px rgba(180, 206, 218, 0.4); }
        .btn:hover { background: #9bbccb; transform: translateY(-1px); }
        .btn-edit { background: #e1f5fe; color: #0288d1; box-shadow: none; padding: 5px 12px; border-radius: 6px; }
        .btn-edit:hover { background: #b3e5fc; transform: none; }
        .btn-danger { background: #ffebee; color: #c62828; padding: 5px 12px; border-radius: 6px; box-shadow: none; }
        .btn-danger:hover { background: #ffcdd2; transform: none; }
        .actions-cell { width: 130px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Ron's Donut Shop 🍩</h2>
        <span>Logged in as: <strong style="text-decoration: underline; color: #5d4037;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong> | <a href="logout.php">Logout</a></span>
    </div>

    <div class="section">
        <h3>Created Donuts (Parent Entity)</h3>
        <a href="add_donut.php" class="btn">+ Add New Donut</a>
        <table>
            <thead>
                <tr>
                    <th>Donut Name</th>
                    <th>Price</th>
                    <th>Added By (User Entity)</th>
                    <th>Last Updated Timestamp</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($donuts)): ?>
                <tr><td colspan="5" style="text-align: center; color: #b0a49e; padding: 30px; font-style: italic;">No donuts found yet. Click "+ Add New Donut" to populate your business database.</td></tr>
                <?php else: ?>
                    <?php foreach ($donuts as $donut): ?>
                    <tr>
                        <td style="font-weight: 500; color: #5c4e46;"><?php echo htmlspecialchars($donut['donut_name']); ?></td>
                        <td style="font-weight: 600; color: #a1785c;">$<?php echo htmlspecialchars($donut['price']); ?></td>
                        <td><span class="creator-tag">👤 <?php echo htmlspecialchars($donut['creator'] ?? 'System'); ?></span></td>
                        <td><span class="timestamp-tag">🕒 <?php echo $donut['last_updated']; ?></span></td>
                        <td class="actions-cell">
                            <a href="edit_donut.php?id=<?php echo $donut['donut_id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_donut.php?id=<?php echo $donut['donut_id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this donut record?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Ingredient Catalog (Child Entity)</h3>
        <a href="add_ingredient.php" class="btn" style="background: #e8def8; color: #4f378b; box-shadow: 0 3px 8px rgba(232, 222, 248, 0.5);">+ Add New Ingredient</a>
        <table>
            <thead>
                <tr>
                    <th>Ingredient Name</th>
                    <th>Quantity Available</th>
                    <th>Associated Donut</th>
                    <th>Added By (User Entity)</th>
                    <th>Last Updated Timestamp</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ingredients)): ?>
                <tr><td colspan="6" style="text-align: center; color: #b0a49e; padding: 30px; font-style: italic;">No ingredients linked to your donuts yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($ingredients as $ing): ?>
                    <tr>
                        <td style="font-weight: 500; color: #5c4e46;"><?php echo htmlspecialchars($ing['ingredient_name']); ?></td>
                        <td><span style="background: #fff3e0; color: #e65100; padding: 3px 8px; border-radius: 4px; font-size: 14px; font-weight: 500;"><?php echo htmlspecialchars($ing['quantity']); ?></span></td>
                        <td style="color: #6d4c41; font-weight: 500;">🍩 <?php echo htmlspecialchars($ing['donut_name']); ?></td>
                        <td><span class="creator-tag">👤 <?php echo htmlspecialchars($ing['creator'] ?? 'System'); ?></span></td>
                        <td><span class="timestamp-tag">🕒 <?php echo $ing['last_updated']; ?></span></td>
                        <td class="actions-cell">
                            <a href="edit_ingredient.php?id=<?php echo $ing['ingredient_id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_ingredient.php?id=<?php echo $ing['ingredient_id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this ingredient record?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>