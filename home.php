<?php

    // Include classes
    include("classes/connect.php");
    include("classes/volunteer_functions.php");

    // Collect volunteer data
    $volunteer_data = fetch_all_volunteer_data();

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
                        <div id="activity_widget">
                            <h3 class="activity_widget_name">Community Cleanup</h3>
                            <p class="activity_widget_info">
                                <strong>Duration:</strong> 3 hours<br>
                                <strong>Area:</strong> Central Park, Springfield<br>
                                <strong>Participants:</strong> 25 people<br>
                            </p>
                            <p class="activity_info">
                                <strong>Domain:</strong>
                                <ul class="activity_domain">
                                    <li>Organization of community events</li>
                                    <li>Cleaning and maintenance of public spaces</li>
                                    <li>Participation in urban gardening projects</li>
                                </ul>
                            </p>
                        </div>

                        <!-- Social activity 2 -->
                        <div id="activity_widget">
                            <h3 class="activity_widget_name">Community Cleanup</h3>
                            <p class="activity_widget_info">
                                <strong>Duration:</strong> 3 hours<br>
                                <strong>Area:</strong> Central Park, Springfield<br>
                                <strong>Participants:</strong> 25 people<br>
                            </p>
                            <p class="activity_info">
                                <strong>Domain:</strong>
                                <ul class="activity_domain">
                                    <li>Organization of community events</li>
                                    <li>Cleaning and maintenance of public spaces</li>
                                    <li>Participation in urban gardening projects</li>
                                </ul>
                            </p>
                        </div>
                        

                    </div>

                </div>
            </div>
            
        </div>
        
    </body>
</html>