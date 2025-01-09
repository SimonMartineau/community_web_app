<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MyBook | Profile</title>
    </head>

    <style>
        #filter_bar{
            min-height: 400px;
            background-color: white;
            margin-top: 20px;
            padding: 8px; /*Determines how an element will sit in a container */
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

        /*No # because this is going to affect all textareas, not id=textarea */
        textarea{
            width: 100%;
            border: none;
            font-family: sans-serif;
            height: 60px;
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

        #post_bar{
            margin-top: 20px;
            background-color: white;
            padding: 10px;

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
            <br>

            <!-- Add social activity button -->
            <div style="text-align: right; padding: 10px 20px;">
                <a href="add_social_activity.php" style="text-decoration: none;">
                    <button style="
                        padding: 10px 20px; 
                        background-color: #405d9b; 
                        color: white; 
                        border: none; 
                        border-radius: 15px; 
                        font-size: 16px; 
                        cursor: pointer;">
                        Add Social Activity
                    </button>
                </a>
            </div>
        
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Filter area -->
                <div style="min-height: 400px; flex:0.6;">

                    <div id="filter_bar">

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
                                    <option value="alphabetically">Alphabetically</option>
                                    <option value="date_of_inscription">Date of Inscription</option>
                                    <option value="birthday">Birthday</option>
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

                <!-- Volunteer area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;"> <!-- Flex to divide between 2 div unequally-->

                    <!-- Volunteer display -->
                    <div id="post_bar">

                        <!-- Section title of recent social activities section -->
                        <div id="section_title">
                            <span>Social Activities</span>
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