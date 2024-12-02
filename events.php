<?php
session_start();
include 'config.php'; // Include database connection file
include 'header.php'; // Include the common header

// Fetch events
$sql = "SELECT * FROM events";
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Events - The Gallery Café</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <header>
        <!-- Include header content here -->
    </header>

    <main>
        <h1>Special Events</h1>
        <p>Information about upcoming events at The Gallery Café.</p>
        <section>
            <h2>Upcoming Events</h2>
            <?php if (!empty($events)) : ?>
                <ul>
                    <?php foreach ($events as $event) : ?>
                        <li>
                            <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                            <p><?php echo htmlspecialchars($event['event_description']); ?></p>
                            <p>Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
                            <?php if ($event['event_image']) : ?>
                                <img src="images/events/<?php echo htmlspecialchars($event['event_image']); ?>" alt="<?php echo htmlspecialchars($event['event_name']); ?>" width="200">
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No upcoming events.</p>
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
