<?php
session_start();
include 'config.php';
include 'header.php';

// Get selected cuisine type from query parameters
$selectedCuisine = isset($_GET['cuisine']) ? $_GET['cuisine'] : '';

// Fetch menu items from the database with optional filtering
$query = "SELECT * FROM menu_items";
if ($selectedCuisine) {
    $query .= " WHERE item_cuisine = ?";
}
$stmt = $conn->prepare($query);
if ($selectedCuisine) {
    $stmt->bind_param("s", $selectedCuisine);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>

    </header>

    <main>
        <h1>Menu</h1>
        <!-- Filter Form -->
        <form method="GET" action="menu.php">
            <label for="cuisine">Filter by Cuisine Type:</label>
            <select id="cuisine" name="cuisine">
                <option value="">All</option>
                <option value="Sri Lankan" <?php echo ($selectedCuisine == 'Sri Lankan') ? 'selected' : ''; ?>>Sri Lankan</option>
                <option value="Chinese" <?php echo ($selectedCuisine == 'Chinese') ? 'selected' : ''; ?>>Chinese</option>
                <option value="Italian" <?php echo ($selectedCuisine == 'Italian') ? 'selected' : ''; ?>>Italian</option>
            </select>
            <input type="submit" value="Filter">
        </form>

        

        <div class="menu-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Extract item details
                    $id = $row['id'];
                    $name = htmlspecialchars($row['item_name']);
                    $price = htmlspecialchars($row['item_price']);
                    $description = htmlspecialchars($row['item_description']);
                    $image = htmlspecialchars($row['item_image']);
                    $availability = htmlspecialchars($row['item_availability']);
                    $cuisine = isset($row['item_cuisine']) ? htmlspecialchars($row['item_cuisine']) : 'Not specified';

                    // Display item
                    echo "<div class='menu-item'>";
                    echo "<img src='images/$image' alt='$name' width='100' height='100'>";
                    echo "<h2>$name</h2>";
                    echo "<p>Description: $description</p>";
                    echo "<p>Price: $$price</p>";
                    echo "<p>Availability: $availability</p>";
                    echo "<p>Cuisine: $cuisine</p>";
                    echo "<form action='cart_process.php' method='POST'>";
                    echo "<input type='hidden' name='item_id' value='$id'>";
                    echo "<label for='quantity_$id'>Quantity:</label>";
                    echo "<input type='number' id='quantity_$id' name='quantity' min='1' max='10' value='1' required>";
                    echo "<input type='submit' value='Add to Cart'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No items available.</p>";
            }

            // Close the connection
            $conn->close();
            ?>
        </div>
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
