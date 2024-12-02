<?php
session_start();
include 'config.php';
include 'header.php'; // Include the common header

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php"); // Redirect to admin login page
    exit();
}

// Handle adding a new user
if (isset($_POST['add_user'])) {
    $username = $_POST['new_username'];
    $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $role = $_POST['new_role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "User added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle adding a new food item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $item_name = $_POST['item_name'];
    $item_description = $_POST['item_description'];
    $item_price = $_POST['item_price'];
    $item_image = $_FILES['item_image']['name'];
    $item_cuisine = $_POST['item_cuisine'];

    // Upload image to a directory
    $target_dir = "images/";
    $target_file = $target_dir . basename($item_image);
    move_uploaded_file($_FILES['item_image']['tmp_name'], $target_file);

    $stmt = $conn->prepare("INSERT INTO menu_items (item_name, item_description, item_price, item_image, item_cuisine) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $item_name, $item_description, $item_price, $item_image, $item_cuisine);

    if ($stmt->execute()) {
        echo "Menu item added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle deleting a food item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_item'])) {
    $item_id = $_POST['item_id'];

    $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo "Menu item deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle updating reservation status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $new_status = $_POST['reservation_status'];

    $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $reservation_id);

    if ($stmt->execute()) {
        echo "Reservation status updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle deleting a reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        echo "Reservation deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle deleting a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle updating order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        echo "Order status updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle deleting an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo "Order deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle adding a new event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_image = $_FILES['event_image']['name'];

    // Upload image to a directory
    $target_dir = "images/events/";
    $target_file = $target_dir . basename($event_image);
    move_uploaded_file($_FILES['event_image']['tmp_name'], $target_file);

    $stmt = $conn->prepare("INSERT INTO events (event_name, event_description, event_date, event_image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $event_name, $event_description, $event_date, $event_image);

    if ($stmt->execute()) {
        echo "Event added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle deleting an event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_event'])) {
    $event_id = $_POST['event_id'];

    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        echo "Event deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle adding a new promotion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_promotion'])) {
    $promotion_name = $_POST['promotion_name'];
    $promotion_description = $_POST['promotion_description'];
    $promotion_start_date = $_POST['promotion_start_date'];
    $promotion_end_date = $_POST['promotion_end_date'];
    $promotion_image = $_FILES['promotion_image']['name'];

    // Upload image to a directory
    $target_dir = "images/promotions/";
    $target_file = $target_dir . basename($promotion_image);
    move_uploaded_file($_FILES['promotion_image']['tmp_name'], $target_file);

    $stmt = $conn->prepare("INSERT INTO promotions (promotion_name, promotion_description, promotion_start_date, promotion_end_date, promotion_image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $promotion_name, $promotion_description, $promotion_start_date, $promotion_end_date, $promotion_image);

    if ($stmt->execute()) {
        echo "Promotion added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle deleting a promotion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_promotion'])) {
    $promotion_id = $_POST['promotion_id'];

    $stmt = $conn->prepare("DELETE FROM promotions WHERE id = ?");
    $stmt->bind_param("i", $promotion_id);

    if ($stmt->execute()) {
        echo "Promotion deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch reservations
$sql = "SELECT * FROM reservations";
$result = $conn->query($sql);

$reservations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

// Fetch menu items
$sql_menu_items = "SELECT * FROM menu_items";
$result_menu_items = $conn->query($sql_menu_items);

$menu_items = [];
if ($result_menu_items->num_rows > 0) {
    while ($row = $result_menu_items->fetch_assoc()) {
        $menu_items[] = $row;
    }
}

// Fetch users
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

$users = [];
if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }
}

// Fetch orders
$sql_orders =  "SELECT * FROM orders";
$result_orders = $conn->query($sql_orders);

$orders = [];
if ($result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        $orders[] = $row;
    }
}


// Fetch events
$sql_events = "SELECT * FROM events";
$result_events = $conn->query($sql_events);

$events = [];
if ($result_events->num_rows > 0) {
    while ($row = $result_events->fetch_assoc()) {
        $events[] = $row;
    }
}

// Fetch promotions
$sql_promotions = "SELECT * FROM promotions";
$result_promotions = $conn->query($sql_promotions);

$promotions = [];
if ($result_promotions->num_rows > 0) {
    while ($row = $result_promotions->fetch_assoc()) {
        $promotions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <main>
        <h1>Admin Dashboard</h1>

        <!-- Add New User -->
        <section>
            <h2>Add New User</h2>
            <form action="admin_dashboard.php" method="POST">
                <label for="new_username">Username:</label>
                <input type="text" id="new_username" name="new_username" required>
                <label for="new_password">Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <label for="new_role">Role:</label>
                <select id="new_role" name="new_role" required>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                </select>
                <input type="submit" name="add_user" value="Add User">
            </form>
        </section>

<!-- Add New Menu Item -->
<section>
    <h2>Add New Menu Item</h2>
    <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required>
        
        <label for="item_description">Description:</label>
        <input type="text" id="item_description" name="item_description" required>
        
        <label for="item_price">Price:</label>
        <input type="number" id="item_price" name="item_price" step="0.01" required>
        
        <label for="item_cuisine">Cuisine Type:</label>
        <select id="item_cuisine" name="item_cuisine" required>
            <option value="Sri Lankan">Sri Lankan</option>
            <option value="Italian">Italian</option>
            <option value="Chinese">Chinese</option>
        </select>
        
        <label for="item_image">Image:</label>
        <input type="file" id="item_image" name="item_image" accept="image/*" required>
        
        <input type="submit" name="add_item" value="Add Item">
    </form>
</section>


        <!-- Manage Reservations -->
        <section>
            <h2>Manage Reservations</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Table Type</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php
                if (!empty($reservations)) {
                    foreach ($reservations as $reservation) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($reservation['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($reservation['table_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($reservation['reservation_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($reservation['reservation_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($reservation['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($reservation['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($reservation['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($reservation['status']) . "</td>";
                        echo "<td>";
                        echo "<form action='admin_dashboard.php' method='POST'>";
                        echo "<input type='hidden' name='reservation_id' value='" . htmlspecialchars($reservation['id']) . "'>";
                        echo "<select name='reservation_status' required>";
                        echo "<option value='Pending'" . ($reservation['status'] === 'Pending' ? " selected" : "") . ">Pending</option>";
                        echo "<option value='Reserved'" . ($reservation['status'] === 'Reserved' ? " selected" : "") . ">Reserved</option>";
                        echo "<option value='Cancelled'" . ($reservation['status'] === 'Cancelled' ? " selected" : "") . ">Cancelled</option>";
                        echo "</select>";
                        echo "<input type='submit' name='update_reservation' value='Update'>";
                        echo "</form>";
                        echo "<form action='admin_dashboard.php' method='POST' style='margin-top: 5px;'>";
                        echo "<input type='hidden' name='reservation_id' value='" . htmlspecialchars($reservation['id']) . "'>";
                        echo "<input type='submit' name='delete_reservation' value='Delete'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </section>

        <!-- Manage Menu Items -->
        <section>
            <h2>Manage Menu Items</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Cuisine Type</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                <?php
                if (!empty($menu_items)) {
                    foreach ($menu_items as $item) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['item_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['item_description']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['item_price']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['item_cuisine']) . "</td>";
                        echo "<td><img src='images/" . htmlspecialchars($item['item_image']) . "' alt='" . htmlspecialchars($item['item_name']) . "' width='50'></td>";
                        echo "<td>";
                        echo "<form action='admin_dashboard.php' method='POST' style='margin-top: 5px;'>";
                        echo "<input type='hidden' name='item_id' value='" . htmlspecialchars($item['id']) . "'>";
                        echo "<input type='submit' name='delete_item' value='Delete'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </section>

        <!-- Manage Orders -->
        <section>
    <h2>Manage Orders</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Order Date</th>
            <th>Order Time</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Item Name</th>
            <th>Action</th>
        </tr>
        <?php
        if (!empty($orders)) {
            foreach ($orders as $order) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($order['id']) . "</td>";
                echo "<td>" . htmlspecialchars($order['user_id']) . "</td>";
                echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                echo "<td>" . htmlspecialchars($order['order_time']) . "</td>";
                echo "<td>" . htmlspecialchars($order['total_amount']) . "</td>";
                echo "<td>" . htmlspecialchars($order['status']) . "</td>";
                echo "<td>" . htmlspecialchars($order['item_name']) . "</td>";
                echo "<td>";
                echo "<form action='admin_dashboard.php' method='POST'>";
                echo "<input type='hidden' name='order_id' value='" . htmlspecialchars($order['id']) . "'>";
                echo "<select name='order_status' required>";
                echo "<option value='Pending'" . ($order['status'] === 'Pending' ? " selected" : "") . ">Pending</option>";
                echo "<option value='Completed'" . ($order['status'] === 'Completed' ? " selected" : "") . ">Completed</option>";
                echo "<option value='Cancelled'" . ($order['status'] === 'Cancelled' ? " selected" : "") . ">Cancelled</option>";
                echo "</select>";
                echo "<input type='submit' name='update_order' value='Update'>";
                echo "</form>";
                echo "<form action='admin_dashboard.php' method='POST' style='margin-top: 5px;'>";
                echo "<input type='hidden' name='order_id' value='" . htmlspecialchars($order['id']) . "'>";
                echo "<input type='submit' name='delete_order' value='Delete'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</section>

        <!-- Manage Users -->
        <section>
            <h2>Manage Users</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                <?php
                if (!empty($users)) {
                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                        echo "<td>";
                        echo "<form action='admin_dashboard.php' method='POST' style='margin-top: 5px;'>";
                        echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($user['id']) . "'>";
                        echo "<input type='submit' name='delete_user' value='Delete'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </section>

        <!-- Manage Events -->
        <section>
            <h2>Manage Special Events</h2>
            <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" required>
                <label for="event_description">Description:</label>
                <input type="text" id="event_description" name="event_description" required>
                <label for="event_date">Date:</label>
                <input type="date" id="event_date" name="event_date" required>
                <label for="event_image">Image:</label>
                <input type="file" id="event_image" name="event_image" accept="image/*" required>
                <input type="submit" name="add_event" value="Add Event">
            </form>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                <?php
                if (!empty($events)) {
                    foreach ($events as $event) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($event['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['event_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['event_description']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['event_date']) . "</td>";
                        echo "<td><img src='images/events/" . htmlspecialchars($event['event_image']) . "' alt='" . htmlspecialchars($event['event_name']) . "' width='50'></td>";
                        echo "<td>";
                        echo "<form action='admin_dashboard.php' method='POST' style='margin-top: 5px;'>";
                        echo "<input type='hidden' name='event_id' value='" . htmlspecialchars($event['id']) . "'>";
                        echo "<input type='submit' name='delete_event' value='Delete'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </section>

        <!-- Manage Promotions -->
        <section>
            <h2>Manage Promotions</h2>
            <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
                <label for="promotion_name">Promotion Name:</label>
                <input type="text" id="promotion_name" name="promotion_name" required>
                <label for="promotion_description">Description:</label>
                <input type="text" id="promotion_description" name="promotion_description" required>
                <label for="promotion_start_date">Start Date:</label>
                <input type="date" id="promotion_start_date" name="promotion_start_date" required>
                <label for="promotion_end_date">End Date:</label>
                <input type="date" id="promotion_end_date" name="promotion_end_date" required>
                <label for="promotion_image">Image:</label>
                <input type="file" id="promotion_image" name="promotion_image" accept="image/*" required>
                <input type="submit" name="add_promotion" value="Add Promotion">
            </form>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                <?php
                if (!empty($promotions)) {
                    foreach ($promotions as $promotion) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($promotion['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($promotion['promotion_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($promotion['promotion_description']) . "</td>";
                        echo "<td>" . htmlspecialchars($promotion['promotion_start_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($promotion['promotion_end_date']) . "</td>";
                        echo "<td><img src='images/promotions/" . htmlspecialchars($promotion['promotion_image']) . "' alt='" . htmlspecialchars($promotion['promotion_name']) . "' width='50'></td>";
                        echo "<td>";
                        echo "<form action='admin_dashboard.php' method='POST' style='margin-top: 5px;'>";
                        echo "<input type='hidden' name='promotion_id' value='" . htmlspecialchars($promotion['id']) . "'>";
                        echo "<input type='submit' name='delete_promotion' value='Delete'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </section>


    </main>
</body>
</html>

<?php
$conn->close();
?>
