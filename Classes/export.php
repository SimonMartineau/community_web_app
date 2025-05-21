<?php
// export.php

// Database credentials
$host = 'localhost';
$dbname = 'CivicLink_Database';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Connect via PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Send headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="CivicLink_Web_App_export.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Get list of tables in the database
$stmtTables = $pdo->query("SHOW TABLES");
$tables = $stmtTables->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    // Write a separator row with the table name
    fputcsv($output, []);
    fputcsv($output, ["-- Table: $table --"]);
    
    // Fetch all rows from the table
    $stmt = $pdo->query("SELECT * FROM `$table`");
    
    // On the first batch, output column headers
    $columns = array_keys($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
    if ($columns) {
        fputcsv($output, $columns);
        
        // Rewind cursor: re‑execute query to fetch all rows
        $stmt = $pdo->query("SELECT * FROM `$table`");

        // Stream each row
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }
    } else {
        // Empty table
        fputcsv($output, ['(no columns)']);
    }
}

// Close output (though script ends anyway)
fclose($output);
exit;
