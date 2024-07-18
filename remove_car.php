<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $car_id = $data['car_id'];

    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = :car_id AND id NOT IN (SELECT car_id FROM rentals WHERE return_date IS NULL)");
    if ($stmt->execute(['car_id' => $car_id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>