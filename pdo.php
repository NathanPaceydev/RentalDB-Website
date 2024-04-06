<?php
// Database connection details
$host = 'localhost'; // Database host, typically localhost when running on the same server
$db   = 'rentalDB'; // The name of the database
$user = 'root'; // The username used to access the database
$pass = ''; // The password used to access the database
$charset = 'utf8mb4'; // Character set to support a wide range of characters

// PDO options for error handling and fetch mode
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Error mode to throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Default fetch mode to return associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false, // Turn off emulation mode for prepared statements
];

// Data Source Name (DSN) - includes the database type, host, database name, and charset
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Try to create a new PDO instance with the DSN and provided credentials
try {
     // Create a new PDO instance representing a connection to the database
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // If an error occurs, throw a PDOException with the error message and error code
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
