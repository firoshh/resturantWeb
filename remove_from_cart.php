<?php
session_start();
if (isset($_POST['item_id'])) {
    $itemId = intval($_POST['item_id']);
    if (isset($_SESSION['cart'][$itemId])) {
        unset($_SESSION['cart'][$itemId]);
        header('Location: cart.php');
        exit;
    }
}
header('Location: cart.php');
exit;
?>
