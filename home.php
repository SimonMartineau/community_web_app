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
    </head>

    <style>
        #recent_volunteers_bar{
            min-height: 400px;
            background-color: white;
            margin-top: 20px;
            padding: 8px; /*Determines how an element will sit in a container */
            border-radius: 8px;
        }

        #volunteer_box {
            margin: 10px auto; /* Center the box horizontally */
            padding: 15px; /* Add padding for better spacing */
            border: 1px solid #ddd; /* Add a light border */
            border-radius: 8px; /* Round the corners */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for a modern look */
            background-color: rgb(196, 253, 148); /* Light background for contrast */
            font-family: Arial, sans-serif; /* Use a clean font */
            color: #333; /* Dark text color for readability */
        }

        .volunteer_name {
            font-size: 1.5em; /* Larger font size for the name */
            margin-bottom: 10px; /* Add space below the name */
            color: #405d9b; /* Optional: Match your theme color */
        }

        .volunteer_info {
            font-size: 0.9em; /* Slightly smaller font for details */
            line-height: 1.6; /* Increase line height for readability */
        }

        .volunteer_info strong {
            color: #405d9b; /* Highlight labels for better distinction */
        }

        #post_button{
            float: right;
            background-color: #405d9b;
            border: none;
            color: white;
            padding: 4px;
            font-size: 14px;
            border-radius: 2px;
            width: 40px;
        }

        #activities_display{
            margin-top: 20px;
            background-color: white;
            padding: 10px;
            border-radius: 8px;
        }

        #post{
            padding: 4px;
            font-size: 13px;
            display: flex;
            margin-bottom: 20px;

        }

        #section_title {
        text-align: center; /* Center the title */
        margin: 20px 0; /* Add space above and below */
        font-family: Arial, sans-serif; /* Use a clean font */
        }

        #section_title span {
            font-size: 1.2em; /* Larger font size for emphasis */
            font-weight: bold; /* Make the text bold */
            color: #405d9b; /* Theme color for text */
            padding: 10px 20px; /* Add some padding around the text */
            background: linear-gradient(to right, #f0f8ff, #dbe9f9); /* Subtle gradient background */
            border-radius: 10px; /* Rounded corners for the background */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            display: inline-block; /* Ensure the background fits tightly */
        }

        #activity_box {
        margin: 10px auto; /* Center the box horizontally */
        padding: 15px; /* Add padding for spacing */
        border: 1px solid #ddd; /* Light border */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        background-color:rgb(184, 247, 243); /* Light background */
        font-family: Arial, sans-serif; /* Clean font */
        color: #333; /* Dark text color */
        }

        .activity_name {
            font-size: 1.5em; /* Larger font for activity name */
            margin-bottom: 10px; /* Space below the name */
            color: #3c7a47; /* Optional: Green theme color */
        }

        .activity_info {
            font-size: 0.9em; /* Smaller font for details */
            line-height: 1.6; /* Line height for readability */
        }

        .activity_info strong {
            color: #3c7a47; /* Highlight labels for distinction */
        }
    </style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <div style="background-color: white;">
                <br>
            </div>
            <img src="images/mountain.jpg" style="width:100%">
        
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Recent volunteers area -->
                <div style="min-height: 400px; flex:1;">

                    <div id="recent_volunteers_bar">

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
                    <div id="activities_display">
                        <!-- Section title of recent social activities section -->
                            <div id="section_title">
                                <span>Recently Added Social Activities</span>
                            </div>
                            

                        <!-- Social activity 1 -->
                        <div id="activity_box">
                            <h3 class="activity_name">Community Cleanup</h3>
                            <p class="activity_info">
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
                        <div id="activity_box">
                            <h3 class="activity_name">Community Cleanup</h3>
                            <p class="activity_info">
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

                        <!-- Social activity 3 -->
                        <div id="activity_box">
                            <h3 class="activity_name">Community Cleanup</h3>
                            <p class="activity_info">
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

                        <!-- Social activity 4 -->
                        <div id="activity_box">
                            <h3 class="activity_name">Community Cleanup</h3>
                            <p class="activity_info">
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