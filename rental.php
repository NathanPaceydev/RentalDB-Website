<?php
// Include the PDO connection settings
include 'pdo.php';

try {
  // Prepare an SQL statement to fetch average rents for different types of properties
  // Each subquery calculates the average cost per month for a specific property type
  $stmt = $pdo->query("
    SELECT 
      (SELECT AVG(CostPerMonth) FROM RentalProperty WHERE PropertyType = 'house') AS AverageRentHouse,
      (SELECT AVG(CostPerMonth) FROM RentalProperty WHERE PropertyType = 'apartment') AS AverageRentApartment,
      (SELECT AVG(CostPerMonth) FROM RentalProperty WHERE PropertyType = 'room') AS AverageRentRoom
  ");

  // Execute the query and store the result set in the $averageRents variable
  $averageRents = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
  // If an error occurs, display the error message
  echo "Error: " . $e->getMessage();
  // Set the $averageRents variable to an empty array as a fallback
  $averageRents = [];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character set for the document -->
    <meta charset="UTF-8">
    <!-- Viewport meta tag to ensure proper rendering and touch zooming on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the webpage -->
    <title>Rental Management System</title>
    <!-- Link to the external stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Link to the favicon to be displayed in the tab -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>

<body>
    <!-- Header section of the page -->
    <header>
        <!-- Main heading -->
        <h1>Welcome to the Rental Management System</h1>
        <!-- Subheading or tagline -->
        <p>Your one-stop solution for rental property management.</p>
        <!-- Image used as a logo -->
        <img src="./images/housing.jpg" alt="Rental Housing" id="logo">
    </header>
    
    <!-- Main content of the page -->
    <main>
        <!-- Section describing the main features of the website -->
        <section id="features">
            <!-- Subheading for the section -->
            <h2>What You Can Do</h2>
            <!-- Individual feature description -->
            <div class="feature">
                <!-- Icon representing the feature -->
                <img src="./images/properties-icon.png" alt="View Properties" class="icon">
                <!-- Clickable link wrapping the feature name -->
                <a href="properties.php" class="button">
                    <h3>View Properties</h3>
                </a>
            </div>
            <!-- Description of the feature -->
            <p>Explore a wide range of properties available for rent.</p>
            
            <!-- Another feature description -->
            <div class="feature">
                <!-- Icon for the feature -->
                <img src="./images/groups-icon.png" alt="Manage Rentals" class="icon">
                <!-- Clickable feature name -->
                <a href="groups.php" class="button">
                    <h3>Manage Rental Groups</h3>
                </a>
            </div>
            <!-- Description for the second feature -->
            <p>Organize and update your rental group preferences with ease.</p>
        </section>

        <!-- Section providing an overview of average rents -->
        <section id="average-rents">
            <!-- Subheading for the section -->
            <h2>Average Monthly Rents</h2>
            <!-- Introductory paragraph for the section -->
            <p>Get an overview of the current rental market with average monthly rents for different property types.</p>
            <!-- Table displaying the average rents -->
            <table>
                <!-- Table headings -->
                <thead>
                    <tr>
                        <th>Houses</th>
                        <th>Apartments</th>
                        <th>Rooms</th>
                    </tr>
                </thead>
                <!-- Table body with data -->
                <tbody>
                    <tr>
                        <td>$<?php echo number_format((float)$averageRents['AverageRentHouse'], 2, '.', ''); ?></td>
                        <td>$<?php echo number_format((float)$averageRents['AverageRentApartment'], 2, '.', ''); ?></td>
                        <td>$<?php echo number_format((float)$averageRents['AverageRentRoom'], 2, '.', ''); ?></td>
                    </tr>
                </tbody>
            </table>
        </section

