<?php
session_start();
require 'includes/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM cars WHERE id NOT IN (SELECT car_id FROM rentals WHERE return_date IS NULL)");
$stmt->execute();
$available_cars = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("SELECT c.* FROM cars c JOIN rentals r ON c.id = r.car_id WHERE r.user_id = :user_id AND r.return_date IS NULL");
$stmt->execute(['user_id' => $user_id]);
$current_rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("SELECT c.*, r.return_date FROM cars c JOIN rentals r ON c.id = r.car_id WHERE r.user_id = :user_id AND r.return_date IS NOT NULL");
$stmt->execute(['user_id' => $user_id]);
$rental_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Car Rental System</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>

        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 80%;
            max-width: 1200px;
            margin: auto;
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .table-container {
            margin-top: 20px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Car Rental System</h2>
        <form action="logout.php" method="post">
            <button type="submit" class="login-button">Logout</button>
        </form>
        
        <div class="table-container">
            <h3>Available Cars for Rental</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Manufacturer</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Type</th>
                        <th>Fuel Type</th>
                        <th>Transmission</th>
                        <th>Mileage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($available_cars as $car): ?>
                        <tr class="car-row" data-car-id="<?= $car['id'] ?>">
                            <td><?= htmlspecialchars($car['manufacturer']) ?></td>
                            <td><?= htmlspecialchars($car['brand']) ?></td>
                            <td><?= htmlspecialchars($car['model']) ?></td>
                            <td><?= htmlspecialchars($car['type']) ?></td>
                            <td><?= htmlspecialchars($car['fuel_type']) ?></td>
                            <td><?= htmlspecialchars($car['transmission']) ?></td>
                            <td><?= htmlspecialchars($car['mileage']) ?> km</td>
                            <td><button class="button rent-car" data-car-id="<?= $car['id'] ?>">Rent</button></td>
                        </tr>
                        <tr class="car-details" data-car-id="<?= $car['id'] ?>" style="display: none;">
                            <td colspan="8">
                                <strong>Type:</strong> <?= htmlspecialchars($car['type']) ?><br>
                                <strong>Fuel Type:</strong> <?= htmlspecialchars($car['fuel_type']) ?><br>
                                <strong>Transmission:</strong> <?= htmlspecialchars($car['transmission']) ?><br>
                                <strong>Mileage:</strong> <?= htmlspecialchars($car['mileage']) ?> km<br>
                                <strong>Registration Plate:</strong> <?= htmlspecialchars($car['registration_plate']) ?><br>
                                <strong>Photo:</strong> <img src="uploads/<?= htmlspecialchars($car['photo']) ?>" alt="Car Photo" class="car-photo">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <h3>Cars Currently Rented</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Manufacturer</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Type</th>
                        <th>Fuel Type</th>
                        <th>Transmission</th>
                        <th>Mileage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($current_rentals as $car): ?>
                        <tr class="car-row" data-car-id="<?= $car['id'] ?>">
                            <td><?= htmlspecialchars($car['manufacturer']) ?></td>
                            <td><?= htmlspecialchars($car['brand']) ?></td>
                            <td><?= htmlspecialchars($car['model']) ?></td>
                            <td><?= htmlspecialchars($car['type']) ?></td>
                            <td><?= htmlspecialchars($car['fuel_type']) ?></td>
                            <td><?= htmlspecialchars($car['transmission']) ?></td>
                            <td><?= htmlspecialchars($car['mileage']) ?> km</td>
                            <td><button class="button release-car" data-car-id="<?= $car['id'] ?>">Release</button></td>
                        </tr>
                        <tr class="car-details" data-car-id="<?= $car['id'] ?>" style="display: none;">
                            <td colspan="8">
                                <strong>Type:</strong> <?= htmlspecialchars($car['type']) ?><br>
                                <strong>Fuel Type:</strong> <?= htmlspecialchars($car['fuel_type']) ?><br>
                                <strong>Transmission:</strong> <?= htmlspecialchars($car['transmission']) ?><br>
                                <strong>Mileage:</strong> <?= htmlspecialchars($car['mileage']) ?> km<br>
                                <strong>Registration Plate:</strong> <?= htmlspecialchars($car['registration_plate']) ?><br>
                                <strong>Photo:</strong> <img src="uploads/<?= htmlspecialchars($car['photo']) ?>" alt="Car Photo" class="car-photo">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <h3>Rental History</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Manufacturer</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Type</th>
                        <th>Fuel Type</th>
                        <th>Transmission</th>
                        <th>Mileage</th>
                        <th>Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rental_history as $car): ?>
                        <tr class="car-row" data-car-id="<?= $car['id'] ?>">
                            <td><?= htmlspecialchars($car['manufacturer']) ?></td>
                            <td><?= htmlspecialchars($car['brand']) ?></td>
                            <td><?= htmlspecialchars($car['model']) ?></td>
                            <td><?= htmlspecialchars($car['type']) ?></td>
                            <td><?= htmlspecialchars($car['fuel_type']) ?></td>
                            <td><?= htmlspecialchars($car['transmission']) ?></td>
                            <td><?= htmlspecialchars($car['mileage']) ?> km</td>
                            <td><?= htmlspecialchars($car['return_date']) ?></td>
                        </tr>
                        <tr class="car-details" data-car-id="<?= $car['id'] ?>" style="display: none;">
                            <td colspan="8">
                                <strong>Type:</strong> <?= htmlspecialchars($car['type']) ?><br>
                                <strong>Fuel Type:</strong> <?= htmlspecialchars($car['fuel_type']) ?><br>
                                <strong>Transmission:</strong> <?= htmlspecialchars($car['transmission']) ?><br>
                                <strong>Mileage:</strong> <?= htmlspecialchars($car['mileage']) ?> km<br>
                                <strong>Registration Plate:</strong> <?= htmlspecialchars($car['registration_plate']) ?><br>
                                <strong>Photo:</strong> <img src="uploads/<?= htmlspecialchars($car['photo']) ?>" alt="Car Photo" class="car-photo">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
