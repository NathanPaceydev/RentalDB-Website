<?php
// Check if 'group_id' is present and not empty in the URL query parameters.
if (!isset($_GET['group_id']) || empty($_GET['group_id'])) {
    echo "No group selected."; // Output an error message if 'group_id' is missing.
    exit; // Stop further script execution.
}

// Store the group ID from the URL parameter for later use.
$groupId = $_GET['group_id'];
include 'pdo.php'; // Include the PDO database connection script.

try {
    // Prepare a SQL statement to fetch the group preferences for a specific group ID.
    $stmt = $pdo->prepare("SELECT * FROM RentalGroup WHERE GroupCode = ?");
    $stmt->execute([$groupId]); // Execute the prepared statement with the group ID.
    $group = $stmt->fetch(); // Fetch the group data.

    // Prepare another SQL statement to fetch student details belonging to the specific group.
    $studentStmt = $pdo->prepare("
        SELECT sr.StudentRenterID, p.fName, p.lName, p.phoneNumber, sr.ProgramOfStudy, sr.ExpectedGraduationYear 
        FROM StudentRenter sr 
        JOIN Person p ON sr.StudentRenterID = p.ID 
        WHERE sr.GroupCode = ?
    ");
    $studentStmt->execute([$groupId]); // Execute the statement with the group ID.
    $students = $studentStmt->fetchAll(); // Fetch all student data for the group.
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage(); // Output an error message if there's a PDO exception.
    exit; // Stop further script execution.
}

// Check if the server request method is POST, which indicates form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Prepare an SQL statement to update group preferences based on the form submission.
        $updateStmt = $pdo->prepare("
            UPDATE RentalGroup SET 
            DesiredPropertyType = ?, 
            DesiredNumberOfBedrooms = ?, 
            DesiredNumberOfBathrooms = ?, 
            ParkingPreference = ?, 
            LaundryPreference = ?, 
            MaxPrice = ?, 
            AccessibilityPreference = ?
            WHERE GroupCode = ?
        ");
        // Execute the prepared statement with the data received from the form.
        $updateStmt->execute([
            $_POST['DesiredPropertyType'], 
            $_POST['DesiredNumberOfBedrooms'], 
            $_POST['DesiredNumberOfBathrooms'], 
            $_POST['ParkingPreference'], 
            $_POST['LaundryPreference'], 
            $_POST['MaxPrice'], 
            $_POST['AccessibilityPreference'], 
            $groupId
        ]);
        
        // Redirect to the same page to refresh the group details after an update.
        header("Location: group_detail.php?group_id=$groupId");
        exit; // Stop further script execution after redirection.
    } catch (\PDOException $e) {
        echo "Error: " . $e->getMessage(); // Output an error message if there's a PDO exception during the update.
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental Group Details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico"> <!-- Path to your favicon.ico -->

</head>
<body>
    <p><a href="groups.php">Back to Rental Groups</a></p>

    <h1>Rental Group Details (Group Code: <?php echo htmlspecialchars($groupId); ?>)</h1>
    <button id="editDetailsButton" onclick="makeEditable()">Edit Details</button>

    <!-- Static details table with an ID -->
    <table id="detailsTable">
        <thead>
            <tr>
                <th>Preference</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Desired Property Type</td>
                <td><?php echo htmlspecialchars($group['DesiredPropertyType']); ?></td>
            </tr>
            <tr>
                <td>Desired Number of Bedrooms</td>
                <td><?php echo htmlspecialchars($group['DesiredNumberOfBedrooms']); ?></td>
            </tr>
            <tr>
                <td>Desired Number of Bathrooms</td>
                <td><?php echo htmlspecialchars($group['DesiredNumberOfBathrooms']); ?></td>
            </tr>
            <tr>
                <td>Parking Preference</td>
                <td><?php echo htmlspecialchars($group['ParkingPreference']); ?></td>
            </tr>
            <tr>
                <td>Laundry Preference</td>
                <td><?php echo htmlspecialchars($group['LaundryPreference']); ?></td>
            </tr>
            <tr>
                <td>Maximum Price</td>
                <td>$<?php echo htmlspecialchars($group['MaxPrice']); ?></td>
            </tr>
            <tr>
                <td>Accessibility Preference</td>
                <td><?php echo htmlspecialchars($group['AccessibilityPreference']); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Editable form initially hidden -->
    <form id="editFormTable" style="display: none;" action="group_detail.php?group_id=<?php echo $groupId; ?>" method="post">
        <table>
            <thead>
                <tr>
                    <th>Preference</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Desired Property Type</td>
                    <td>
                        <select name="DesiredPropertyType">
                            <option value="house" <?php echo $group['DesiredPropertyType'] == 'house' ? 'selected' : ''; ?>>House</option>
                            <option value="apartment" <?php echo $group['DesiredPropertyType'] == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                            <option value="room" <?php echo $group['DesiredPropertyType'] == 'room' ? 'selected' : ''; ?>>Room</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Desired Number of Bedrooms</td>
                    <td><input type="number" name="DesiredNumberOfBedrooms" value="<?php echo htmlspecialchars($group['DesiredNumberOfBedrooms']); ?>" min="0"></td>
                </tr>
                <tr>
                    <td>Desired Number of Bathrooms</td>
                    <td><input type="number" name="DesiredNumberOfBathrooms" value="<?php echo htmlspecialchars($group['DesiredNumberOfBathrooms']); ?>" min="0"></td>
                </tr>
                <tr>
                    <td>Parking Preference</td>
                    <td>
                        <select name="ParkingPreference">
                            <option value="yes" <?php echo $group['ParkingPreference'] == 'yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo $group['ParkingPreference'] == 'no' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Laundry Preference</td>
                    <td>
                        <select name="LaundryPreference">
                            <option value="ensuite" <?php echo $group['LaundryPreference'] == 'ensuite' ? 'selected' : ''; ?>>Ensuite</option>
                            <option value="shared" <?php echo $group['LaundryPreference'] == 'shared' ? 'selected' : ''; ?>>Shared</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Maximum Price</td>
                    <td>$<input type="number" name="MaxPrice" value="<?php echo htmlspecialchars($group['MaxPrice']); ?>" step="0.01" min="0"></td>
                </tr>
                <tr>
                    <td>Accessibility Preference</td>
                    <td>
                        <select name="AccessibilityPreference">
                            <option value="yes" <?php echo $group['AccessibilityPreference'] == 'yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo $group['AccessibilityPreference'] == 'no' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Submit Changes">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>


    <h2>Students in Group</h2>
    <table id="studentsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Program of Study</th>
                <th>Expected Graduation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars($student['StudentRenterID']); ?></td>
                <td><?php echo htmlspecialchars($student['fName'] . " " . $student['lName']); ?></td>
                <td><?php echo htmlspecialchars($student['phoneNumber']); ?></td>
                <td><?php echo htmlspecialchars($student['ProgramOfStudy']); ?></td>
                <td><?php echo htmlspecialchars($student['ExpectedGraduationYear']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

   
</body>
</html>
<script>
function makeEditable() {
    var details = document.getElementById('detailsTable'); // Correctly reference the static details table
    var editForm = document.getElementById('editFormTable');
    details.style.display = 'none'; // Hide the details view
    editForm.style.display = 'block'; // Show the editable form
}
</script>



