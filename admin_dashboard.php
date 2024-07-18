<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM cars");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT r.*, u.username FROM rentals r JOIN users u ON r.user_id = u.id WHERE r.return_date IS NULL");
$rentedCars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
        form {
            margin-bottom: 20px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        .add-car-form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <form action="logout.php" method="post">
            <button type="submit">Logout</button>
        </form>

        <div class="table-container">
            <h2>Available Cars for Rental</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Manufacturer</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                    <tr class="car-row" data-car-id="<?php echo $car['id']; ?>">
                        <td><?php echo htmlspecialchars($car['manufacturer']); ?></td>
                        <td><?php echo htmlspecialchars($car['brand']); ?></td>
                        <td><?php echo htmlspecialchars($car['model']); ?></td>
                        <td>
                            <button class="remove-car" data-car-id="<?php echo $car['id']; ?>">Remove</button>
                        </td>
                    </tr>
                    <tr class="car-details" data-car-id="<?php echo $car['id']; ?>" style="display: none;">
                        <td colspan="4">
                            <strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?><br>
                            <strong>Fuel Type:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?><br>
                            <strong>Transmission:</strong> <?php echo htmlspecialchars($car['transmission']); ?><br>
                            <strong>Mileage:</strong> <?php echo htmlspecialchars($car['mileage']); ?><br>
                            <strong>Registration Plate:</strong> <?php echo htmlspecialchars($car['registration_plate']); ?><br>
                            <strong>Photo:</strong> <img src="uploads/<?php echo htmlspecialchars($car['photo']); ?>" alt="Car Photo" width="100">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="add-car-form">
            <h2>Add a Car</h2>
            <button id="show-add-car-form">Add a car</button>
            <form id="add-car-form" action="add_car.php" method="post" enctype="multipart/form-data" style="display:none;">
                <label for="manufacturer">Manufacturer</label>
                <input type="text" id="manufacturer" name="manufacturer" required>

                <label for="brand">Brand</label>
                <input type="text" id="brand" name="brand" required>

                <label for="model">Model</label>
                <input type="text" id="model" name="model" required>

                <label for="type">Type</label>
                <input type="text" id="type" name="type" required>

                <label for="fuel_type">Fuel Type</label>
                <input type="text" id="fuel_type" name="fuel_type" required>

                <label for="transmission">Transmission</label>
                <input type="text" id="transmission" name="transmission" required>

                <label for="mileage">Mileage</label>
                <input type="number" id="mileage" name="mileage" required>

                <label for="registration_plate">Registration Plate</label>
                <input type="text" id="registration_plate" name="registration_plate" required>

                <label for="photo">Photo</label>
                <input type="file" id="photo" name="photo" required>

                <label for="additional_info">Additional Information</label>
                <textarea id="additional_info" name="additional_info"></textarea>

                <button type="submit">Add Car</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Rented Cars</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Car ID</th>
                        <th>User</th>
                        <th>Rental Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rentedCars as $rentedCar): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rentedCar['car_id']); ?></td>
                        <td><?php echo htmlspecialchars($rentedCar['username']); ?></td>
                        <td><?php echo htmlspecialchars($rentedCar['rental_date']); ?></td>
                        <td>
                            <button class="release-car" data-car-id="<?php echo $rentedCar['car_id']; ?>">Release</button>
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
