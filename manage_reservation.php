<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];
    $action = $_POST['action'];

    if ($action == 'confirm') {
        $stmt = $conn->prepare("UPDATE reservations SET status = 'confirmed' WHERE id = ?");
    } elseif ($action == 'modify') {
        $stmt = $conn->prepare("UPDATE reservations SET status = 'modified' WHERE id = ?");
    } else {
        $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
    }
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        echo "Reservation updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    header("Location: staff_dashboard.php");
    exit();
}
?>
