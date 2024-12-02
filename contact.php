<?php
session_start();
include 'header.php'; // Include the common header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - The Gallery Café</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <!-- Header content if any -->
    </header>

    <main>
        <h1>Contact Us</h1>

        <!-- Contact Form -->
        <section class="contact-form">
            <h2>Get in Touch</h2>
            <form action="send_message.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </section>

        <!-- Restaurant Contact Information -->
        <section class="contact-info">
            <h2>Restaurant Contact Information</h2>
            <p><strong>Phone:</strong> +1 (123) 456-7890</p>
            <p><strong>Email:</strong> contact@thegallerycafe.com</p>
            <p><strong>Address:</strong> 123 Café St, Food City, FC 12345</p>
        </section>

        <!-- Location Map -->
        <section class="location-map">
            <h2>Find Us Here</h2>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8354345094277!2d144.95373541531832!3d-37.816279742387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d43f3d66f33%3A0x4a8c9a627d0b0b39!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1601427209403!5m2!1sen!2sau" 
                width="600" 
                height="450" 
                frameborder="0" 
                style="border:0;" 
                allowfullscreen="" 
                aria-hidden="false" 
                tabindex="0">
            </iframe>
        </section>

    </main>

    <footer>
        <p>&copy; 2024 The Gallery Café. All rights reserved.</p>
        <p>
            <a href="privacy_policy.php">Privacy Policy</a> | 
            <a href="terms_of_service.php">Terms of Service</a> | 
            <a href="info.php">About Us</a> | 
            

        </p>
    </footer>
</body>
</html>
