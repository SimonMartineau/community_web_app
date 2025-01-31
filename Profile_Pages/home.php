<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    // Collect volunteer data
    $all_volunteer_data = fetch_data("
        SELECT * 
        FROM Volunteers 
        WHERE `trashed` = 0 
        ORDER BY id desc 
        LIMIT 3"
    );

    // Collect activities data
    $all_activities_data = fetch_data("
        SELECT * 
        FROM Activities 
        WHERE `trashed` = '0' 
        ORDER BY id DESC
        LIMIT 3"
    );

    // Collect checks data
    $all_checks_data = fetch_data("
        SELECT c.* 
        FROM Checks c
        INNER JOIN Volunteers m ON c.volunteer_id = m.id
        WHERE m.trashed = 0
        ORDER BY c.id DESC 
        LIMIT 3"
    );

    // Collect purchases data
    $all_purchases_data = fetch_data("
        SELECT p.* 
        FROM Purchases p
        INNER JOIN Volunteers m ON p.volunteer_id = m.id
        WHERE m.trashed = 0
        ORDER BY p.id DESC 
        LIMIT 3"
    );

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <div>
                <br>
            </div>
        
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Contact content area -->
                <div id="major_rectangle">

                    <!-- Section title of contact section -->
                    <div id="section_title" style="margin-bottom: 20px;">
                        <span style="font-size: 24px; font-weight: bold;">Volunteer-Activity Management Application</span>
                    </div>

                    <img src="../Images/photo.jpg" style="width:100%; border-radius: 8px;">


                </div>

            </div>
            
        </div>
        
    </body>
</html>