<?php
session_start();
include 'header.php'; // Include the common header
include 'config.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'staff', 'customer'])) {
    // Redirect to login page with a message
    header("Location: login.php?redirect=preorder.php&message=You%20need%20to%20be%20logged%20in%20to%20pre-order.");
    exit();
}

// Fetch menu items for pre-order form
$menu_items = $conn->query("SELECT id, item_name FROM menu_items");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $preorder_date = date('Y-m-d'); // or use another date picker method
    $status = 'Pending'; // Default status

    // Fetch item name
    $stmt = $conn->prepare("SELECT item_name FROM menu_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $item_name = $item['item_name'];
    $stmt->close();

    // Insert pre-order into database
    $stmt = $conn->prepare("INSERT INTO preorders (user_id, item_name, quantity, preorder_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $item_name, $quantity, $preorder_date, $status);
    if ($stmt->execute()) {
        echo "Pre-order placed successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Order - The Gallery Café</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
  
    </header>

    <main>
        <h1>Pre-Order</h1>
        <form action="preorder.php" method="POST">
            <label for="item_id">Select Item:</label>
            <select id="item_id" name="item_id" required>
                <?php while ($row = $menu_items->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>">
                        <?php echo htmlspecialchars($row['item_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" required>

            <input type="submit" value="Place Pre-Order">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 The Gallery Café. All rights reserved.</p>
        <p>
            <a href="privacy_policy.php">Privacy Policy</a> | 
            <a href="terms_of_service.php">Terms of Service</a> | 
            <a href="info.php">About Us</a> | 
            <a href="contact.php">Contact Us</a>

        </p>
    </footer>
</body>
</html>
