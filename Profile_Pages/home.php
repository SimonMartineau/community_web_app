<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Collect volunteer data
    $all_volunteer_data = fetch_data("
        SELECT * 
        FROM Volunteers 
        WHERE `trashed` = 0 
        ORDER BY id desc 
        LIMIT 5"
    );

    $all_checks_data = fetch_data("
        SELECT c.* 
        FROM Checks c
        INNER JOIN Volunteers m ON c.volunteer_id = m.id
        WHERE m.trashed = 0
        ORDER BY c.id DESC 
        LIMIT 5"
    );

    $all_purchases_data = fetch_data("
        SELECT p.* 
        FROM Purchases p
        INNER JOIN Volunteers m ON p.volunteer_id = m.id
        WHERE m.trashed = 0
        ORDER BY p.id DESC 
        LIMIT 5"
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
            <img src="../Images/mountain.jpg" style="width:100%; border-radius: 8px;">
        
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Recent volunteers area -->
                <div style="min-height: 400px; flex:1;">

                    <div id="medium_rectangle">

                        <!-- Section title of recent volunteer section -->
                        <div id="section_title">
                            <span>Recently Added Volunteers</span>
                        </div>

                        <!-- Display volunteer widgets --> 
                        <?php
                            if($all_volunteer_data){
                                foreach($all_volunteer_data as $volunteer_data_row){
                                    include("../Widget_Pages/volunteer_widget.php");
                                }
                            }
                        ?>

                        <!-- Section title of recent checks section -->
                        <div id="section_title">
                            <span>Recently Added Checks</span>
                        </div>

                        <!-- Display checks widgets --> 
                        <?php
                            if($all_checks_data){
                                foreach($all_checks_data as $check_data_row){
                                    $volunteer_data = fetch_volunteer_data($check_data_row['volunteer_id']);
                                    $date = new DateTime($check_data_row['issuance_date']);
                                    $month = $date->format('F'); // Full month name (e.g., "January")
                                    include("../Widget_Pages/check_widget.php");
                                }
                            }
                        ?>

                    </div>
                </div>

                <!-- Recent social activities area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;"> <!-- Flex to divide between 2 div unequally-->

                    <!-- Social activities -->
                    <div id="medium_rectangle">
                        <!-- Section title of recent social activities section -->
                        <div id="section_title">
                            <span>Recently Added Social Activities</span>
                        </div>
                            

                        <!-- Social activity 1 -->
                        <div id="widget" class="activity_widget">
                            <h3 class="widget_name">Community Cleanup</h3>
                            <p class="widget_info">
                                <strong>Duration:</strong> 3 hours<br>
                                <strong>Area:</strong> Central Park, Springfield<br>
                                <strong>Participants:</strong> 25 people<br>
                            </p>
                            <p>
                                <strong>Domain:</strong>
                                <ul>
                                    <li>Organization of community events</li>
                                    <li>Cleaning and maintenance of public spaces</li>
                                    <li>Participation in urban gardening projects</li>
                                </ul>
                            </p>
                        </div>

                        <!-- Social activity 1 -->
                        <div id="widget" class="activity_widget">
                            <h3 class="widget_name">Community Cleanup</h3>
                            <p class="widget_info">
                                <strong>Duration:</strong> 3 hours<br>
                                <strong>Area:</strong> Central Park, Springfield<br>
                                <strong>Participants:</strong> 25 people<br>
                            </p>
                            <p>
                                <strong>Domain:</strong>
                                <ul>
                                    <li>Organization of community events</li>
                                    <li>Cleaning and maintenance of public spaces</li>
                                    <li>Participation in urban gardening projects</li>
                                </ul>
                            </p>
                        </div>

                        <!-- Section title of recent purchases section -->
                        <div id="section_title">
                            <span>Recent Purchases</span>
                        </div>

                        <!-- Display purchase widgets --> 
                        <?php
                            if($all_purchases_data){
                                foreach($all_purchases_data as $purchase_data_row){
                                    $volunteer_data = fetch_volunteer_data($purchase_data_row['volunteer_id']);
                                    include("../Widget_Pages/purchase_widget.php");
                                }
                            }
                        ?>
                        

                    </div>

                </div>
            </div>
            
        </div>
        
    </body>
</html>