<?php
// Include the database connection configuration
include 'pdo.php';

// Attempt to execute the SQL query within a try-catch block to handle exceptions
try {
    // Prepare an SQL statement to fetch details of rental properties
    // along with the concatenated owner names and the name of the property manager
    $stmt = $pdo->query("
        SELECT rp.PropertyID, rp.Street, rp.City, rp.PostalCode, rp.CostPerMonth, rp.PropertyType, 
               GROUP_CONCAT(DISTINCT p.fName, ' ', p.lName ORDER BY p.fName SEPARATOR ', ') AS OwnerNames, 
               pmgr.fName AS ManagerFirstName, pmgr.lName AS ManagerLastName
        FROM RentalProperty rp
        LEFT JOIN PropertyOwnerRelation por ON rp.PropertyID = por.PropertyID
        LEFT JOIN Owner o ON por.OwnerID = o.OwnerID
        LEFT JOIN Person p ON o.OwnerID = p.ID
        LEFT JOIN PropertyManagerRentalRelation pmrr ON rp.PropertyID = pmrr.PropertyID
        LEFT JOIN PropertyManager pm ON pmrr.ManagerID = pm.ManagerID
        LEFT JOIN Person pmgr ON pm.ManagerID = pmgr.ID
        GROUP BY rp.PropertyID
    ");
    // Fetch all the results of the query and store them in $properties
    $properties = $stmt->fetchAll();
} catch (\PDOException $e) {
    // If an exception occurs, print the error message
    echo "Error: " . $e->getMessage();
    // Set $properties to an empty array to prevent issues on the frontend
    $properties = [];
}
?>



<!-- DOCTYPE declaration for HTML5 documents -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character set declaration -->
    <meta charset="UTF-8">
    <!-- Link to external CSS stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Favicon for the website tab icon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">

    <!-- The title of the webpage -->
    <title>View Properties</title>
</head>

<body>
    <!-- Navigation link back to the homepage -->
    <p><a href="rental.php">Back to Home</a></p>

    <!-- Main heading for the properties listing page -->
    <h1>Property Listings</h1>
    <!-- Table to display property details -->
    <table>
        <!-- Table headings -->
        <thead>
            <tr>
                <th>ID</th>
                <th>Street</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Cost Per Month</th>
                <th>Property Type</th>
                <th>Owner(s)</th>
                <th>Manager</th>
            </tr>
        </thead>
        <!-- Table body -->
        <tbody>
            <!-- Loop through each property fetched from the database -->
            <?php foreach ($properties as $property): ?>
                <tr>
                    <!-- Display each piece of property information -->
                    <td><?php echo htmlspecialchars($property['PropertyID']); ?></td>
                    <td><?php echo htmlspecialchars($property['Street']); ?></td>
                    <td><?php echo htmlspecialchars($property['City']); ?></td>
                    <td><?php echo htmlspecialchars($property['PostalCode']); ?></td>
                    <td><?php echo htmlspecialchars($property['CostPerMonth']); ?></td>
                    <td><?php echo htmlspecialchars($property['PropertyType']); ?></td>
                    <td><?php echo htmlspecialchars($property['OwnerNames']); ?></td>
                    <td>
                        <?php 
                        // Display the manager's name if it exists, otherwise print 'N/A'
                        if (!empty($property['ManagerFirstName']) && !empty($property['ManagerLastName'])) {
                            echo htmlspecialchars($property['ManagerFirstName'] . ' ' . $property['ManagerLastName']);
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
