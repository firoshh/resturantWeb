<?php
session_start();
include 'header.php'; // Include the common header
include 'config.php'; // Include database connection file

// Fetch promotions
$sql = "SELECT * FROM promotions";
$result = $conn->query($sql);

$promotions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $promotions[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotions - The Gallery Café</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <!-- Include header content here -->
    </header>

    <main>
        <h1>Promotions</h1>
        <p>Check out the latest promotions and special offers.</p>
        <section>
            <h2>Current Promotions</h2>
            <?php if (!empty($promotions)) : ?>
                <ul>
                    <?php foreach ($promotions as $promotion) : ?>
                        <li>
                            <h3><?php echo htmlspecialchars($promotion['promotion_name']); ?></h3>
                            <p><?php echo htmlspecialchars($promotion['promotion_description']); ?></p>
                            <p>Start Date: <?php echo htmlspecialchars($promotion['promotion_start_date']); ?></p>
                            <p>End Date: <?php echo htmlspecialchars($promotion['promotion_end_date']); ?></p>
                            <?php if ($promotion['promotion_image']) : ?>
                                <img src="images/promotions/<?php echo htmlspecialchars($promotion['promotion_image']); ?>" alt="<?php echo htmlspecialchars($promotion['promotion_name']); ?>" width="200">
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No current promotions.</p>
            <?php endif; ?>
        </section>
    </main>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    

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
