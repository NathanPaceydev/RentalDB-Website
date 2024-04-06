<?php
// Include the database connection script.
include 'pdo.php';

// Attempt to query the database.
try {
    // Prepare a SQL statement to select all records from the RentalGroup table.
    $stmt = $pdo->query("SELECT * FROM RentalGroup");
    // Fetch all records from the result set as an array.
    $rentalGroups = $stmt->fetchAll();
} catch (\PDOException $e) {
    // If there is a PDO exception, output the error message.
    echo "Error: " . $e->getMessage();
    // Set the $rentalGroups variable to an empty array if the query fails.
    $rentalGroups = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental Groups</title>
    <!-- Link to the external stylesheet for the page -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Specify the favicon icon for the webpage -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
    <!-- Navigation link to go back to the homepage -->
    <p><a href="rental.php">Back to Home</a></p>

    <!-- Heading for the rental group listings -->
    <h1>Rental Group Listings</h1>
    <!-- Begin table structure for displaying rental groups -->
    <table>
        <thead>
            <!-- Table header row -->
            <tr>
                <th>Group Code</th>
                <th>View Details</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through each rental group and create a table row for each -->
            <?php foreach ($rentalGroups as $group): ?>
            <tr>
                <!-- Output the GroupCode for the current row -->
                <td><?php echo htmlspecialchars($group['GroupCode']); ?></td>
                <!-- Provide a link to the group_detail.php page with the GroupCode as a URL parameter -->
                <td><a href="group_detail.php?group_id=<?php echo $group['GroupCode']; ?>">View Details</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
