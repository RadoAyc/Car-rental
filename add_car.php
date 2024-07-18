<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manufacturer = $_POST['manufacturer'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $fuel_type = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $mileage = $_POST['mileage'];
    $registration_plate = $_POST['registration_plate'];
    $additional_info = $_POST['additional_info'];

    $photo = $_FILES['photo'];
    $photo_name = time() . '_' . $photo['name'];
    $photo_path = 'uploads/' . $photo_name;
    
    if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
        $stmt = $pdo->prepare("INSERT INTO cars (manufacturer, brand, model, type, fuel_type, transmission, mileage, registration_plate, photo, additional_info) VALUES (:manufacturer, :brand, :model, :type, :fuel_type, :transmission, :mileage, :registration_plate, :photo, :additional_info)");
        
        if ($stmt->execute([
            'manufacturer' => $manufacturer,
            'brand' => $brand,
            'model' => $model,
            'type' => $type,
            'fuel_type' => $fuel_type,
            'transmission' => $transmission,
            'mileage' => $mileage,
            'registration_plate' => $registration_plate,
            'photo' => $photo_name,
            'additional_info' => $additional_info
        ])) {
            header('Location: admin_dashboard.php');
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
