<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $car_id = $data['car_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("UPDATE rentals SET return_date = NOW() WHERE car_id = :car_id AND user_id = :user_id AND return_date IS NULL");
    if ($stmt->execute(['car_id' => $car_id, 'user_id' => $user_id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
