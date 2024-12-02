<?php
session_start();
include 'header.php'; // Include the common header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <!-- Header content if any -->
    </header>

    <main>
        <!-- Welcome Message -->
        <div class="welcome">
            <h1>Welcome to The Gallery Café</h1>
            <p>Discover our delicious menu and enjoy a memorable dining experience. Explore our selection of meals and beverages.</p>
            <a href="menu.php" class="see-menu-button">See the Menu</a>
        </div>

        <!-- Slideshow container -->
        <section class="slideshow-container">
            <div class="mySlides fade">
                <img src="images/pexels-pixabay-531829.jpg" alt="Image 1">
            </div>
            <div class="mySlides fade">
                <img src="images/delicious-fresh-chocolate-dessert-cup-beverage-restaurant.jpg" alt="Image 2">
            </div>
            <div class="mySlides fade">
                <img src="images/sandwich-cup-coffee-table.jpg" alt="Image 3">
            </div>
            <div class="mySlides fade">
                <img src="images/pexels-fotios-photos-907142.jpg" alt="Image 4">
            </div>
            <div class="mySlides fade">
                <img src="images/photo-homemade-ciabatta-bread-black-background.jpg" alt="Image 5">
            </div>

            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </section>

        <!-- Featured Menu Items -->
        <section class="featured-menu">
            <h2>Featured Items</h2>
            <div class="featured-items-container">
                <?php
                include 'config.php'; // Database connection

                // Fetch featured menu items
                $query = "SELECT id, item_name, item_description, item_image FROM menu_items LIMIT 3";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="featured-item">';
                        echo '<img src="images/' . htmlspecialchars($row['item_image']) . '" alt="' . htmlspecialchars($row['item_name']) . '">';
                        echo '<h3>' . htmlspecialchars($row['item_name']) . '</h3>';
                        echo '<p>' . htmlspecialchars($row['item_description']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No featured items available.</p>';
                }

                $conn->close();
                ?>
            </div>
        </section>

        <!-- Other sections... -->

    </main>
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

    <!-- Slideshow script -->
    <script src="script.js"></script>
</body>
</html>
