<?php

    // Include classes
    include("classes/connect.php");
    include("classes/functions.php");

    // Collect volunteer data
    $volunteer_data = fetch_data("select * from Members order by id desc limit 3");
    $checks_data = fetch_data("select * from Checks order by id desc limit 3");
    $purchases_data = fetch_data("select * from Purchases order by id desc limit 3");

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Give and Receive</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <div>
                <br>
            </div>
            <img src="images/mountain.jpg" style="width:100%; border-radius: 8px;">
        
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
                            if($volunteer_data){
                                foreach($volunteer_data as $volunteer_data_row){
                                    include("volunteer_widget.php");
                                }
                            }
                        ?>

                        <!-- Section title of recent checks section -->
                        <div id="section_title">
                            <span>Recently Added Checks</span>
                        </div>

                        <!-- Display checks widgets --> 
                        <?php
                            if($checks_data){
                                foreach($checks_data as $check_data_row){
                                    $member_data = fetch_member_data($check_data_row['member_id']);
                                    $date = new DateTime($check_data_row['issuance_date']);
                                    $month = $date->format('F'); // Full month name (e.g., "January")
                                    include("check_widget.php");
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
                            if($purchases_data){
                                foreach($purchases_data as $purchase_data_row){
                                    $member_data = fetch_member_data($purchase_data_row['member_id']);
                                    include("purchase_widget.php");
                                }
                            }
                        ?>
                        

                    </div>

                </div>
            </div>
            
        </div>
        
    </body>
</html>