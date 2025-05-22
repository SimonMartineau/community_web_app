<?php
    // Start session
    session_start();

    // Include classes
    include(__DIR__ . "/connect.php");
    include(__DIR__ . "/functions.php");
    include(__DIR__ . "/../Languages/translate.php");

    // Connect to the database
    $DB = new Database();
    $user_data = $DB->check_login();
    $user_id = $user_data['user_id'];
    $mysqli = $DB->connect();

    if (mysqli_connect_errno()) {
        die('Connect error: ' . mysqli_connect_error());
    }

    // Set Excel headers
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="CivicLink_Web_App_export.xls"');

    // Start XML structure
    echo '<?xml version="1.0"?>';
    echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
            xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';

    $tablesResult = $mysqli->query("SHOW TABLES");
    while ($row = $tablesResult->fetch_row()) {
        $table = $row[0];
        if (strcasecmp($table, 'Users') === 0) continue;

        if (strcasecmp($table, 'Forgot_Password') === 0) continue;

        // Create worksheet for each table
        // Table names
        echo '<Worksheet ss:Name="' . __(htmlspecialchars(substr($table, 0, 31))) . '">';
        echo '<Table>';

        // Check for user_id column
        $colsResult = $mysqli->query("SHOW COLUMNS FROM $table LIKE 'user_id'");
        $hasUserId = ($colsResult->num_rows > 0);

        // Fetch data
        $sql = $hasUserId ? "SELECT * FROM $table WHERE user_id = $user_id" : "SELECT * FROM $table";
        $res2 = $mysqli->query($sql);

        if (!$res2) {
            echo '<Row><Cell><Data ss:Type="String">Error: ' . htmlspecialchars($mysqli->error) . '</Data></Cell></Row>';
            continue;
        }

        if ($res2->num_rows === 0) {
            echo '<Row><Cell><Data ss:Type="String">(no rows)</Data></Cell></Row>';
        } else {
            // Column headers
            echo '<Row>';
            while ($finfo = $res2->fetch_field()) {
                echo '<Cell><Data ss:Type="String">' . __(htmlspecialchars($finfo->name)) . '</Data></Cell>';
            }
            echo '</Row>';

            // Data rows
            $res2->data_seek(0);
            while ($data = $res2->fetch_assoc()) {
                echo '<Row>';
                foreach ($data as $value) {
                    $type = is_numeric($value) ? 'Number' : 'String';
                    echo '<Cell><Data ss:Type="' . $type . '">' . __(htmlspecialchars($value)) . '</Data></Cell>';
                }
                echo '</Row>';
            }
        }
        
        echo '</Table></Worksheet>';
        if ($res2) $res2->free();
    }

    echo '</Workbook>';
    $mysqli->close();
    exit;
?>