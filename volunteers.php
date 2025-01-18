<?php

    // Include classes
    include("classes/connect.php");
    include("classes/functions.php");

    // Collect volunteer data
    $all_volunteer_data = fetch_all_volunteer_data();

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Volunteers | Give and Receive</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Add volunteer button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="add_volunteer.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Add Volunteer
                    </button>
                </a>
            </div>
     
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Left area -->
                <div style="flex:0.6;">

                    <!-- Filter form area -->
                    <div id="medium_rectangle">

                        <!-- Section title of filter area -->
                        <div id="section_title">
                            <span>Filter</span>
                        </div>

                        <!-- Filter form -->
                        <form action="" method="post">
                            <!-- Sort by options -->
                            <div style="margin-bottom: 15px;">
                                <label for="sort" style="font-weight: bold;">Sort Volunteers By:</label><br>
                                <select name="sort" id="sort" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="alphabetically_a_z">Alphabetically (a-z)</option>
                                    <option value="alphabetically_z_a">Alphabetically (z-a)</option>
                                    <option value="date_of_inscription_asc">Date of Inscription (asc)</option>
                                    <option value="date_of_inscription_desc">Date of Inscription (desc)</option>
                                    <option value="age_asc">Age (asc)</option>
                                    <option value="age_desc">Age (desc)</option>
                                </select>
                            </div>

                            <!-- Time filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="sort" style="font-weight: bold;">Has Completed Volunteer Hours:</label><br>
                                <select name="sort" id="sort" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="any">Any</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                            <!-- Gender filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="gender" style="font-weight: bold;">Gender:</label><br>
                                <select name="gender" id="gender" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="">Any</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>

                            <!-- Interests filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Interests:</label><br>
                                <div>
                                    <label><input type="checkbox" name="available_days[]" value="monday"> Organization of community events</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="tuesday"> Library support</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="wednesday"> Help in the community store</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="thursday"> Support in the community grocery store</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="friday"> Cleaning and maintenance of public spaces</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="saturday"> Participation in urban gardening projects</label><br>
                                </div>
                            </div>

                            <!-- Day availability filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Available Days:</label><br>
                                <div>
                                    <label><input type="checkbox" name="available_days[]" value="monday"> Monday</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="tuesday"> Tuesday</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="wednesday"> Wednesday</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="thursday"> Thursday</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="friday"> Friday</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="saturday"> Saturday</label><br>
                                    <label><input type="checkbox" name="available_days[]" value="sunday"> Sunday</label>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <div style="text-align: center;">
                                <button type="submit" style="padding: 10px 20px; background-color: #405d9b; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                                    Apply Filter
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Right area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Volunteer widget display -->
                    <div id="medium_rectangle">

                        <!-- Section title of recent social activities section -->
                        <div id="section_title">
                            <span>Volunteers</span>
                        </div>

                        <!-- Display volunteer widgets --> 
                        <?php
                            if($all_volunteer_data){
                                foreach($all_volunteer_data as $volunteer_data_row){
                                    include("volunteer_widget.php");
                                }
                            }
                        ?>
                        

                    </div>

                </div>
            </div>
            
        </div>
        
    </body>
</html>