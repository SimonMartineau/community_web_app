<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    if (isset($_GET['activity_id'])) {
        $activity_id = $_GET['activity_id'];

        // Collecting activity data (only 1 row needed)
        $activity_data_row = fetch_data(
            "SELECT * FROM Activities
                    WHERE id = '$activity_id'"
        )[0];

        // Collecting activity time periods data
        $activity_time_periods_data = fetch_data(
            "SELECT * FROM Activity_Time_Periods
                    WHERE activity_id = '$activity_id'"
        );

        // Collecting activity domains data
        $activity_domain_data = fetch_data(
            "SELECT * FROM Activity_Domains
                    WHERE activity_id = '$activity_id'"
        );

    }

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Ensure the delete activity button has been pressed
        if (isset($_POST['delete_activity']) && $_POST['delete_activity'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $trash_activity_query = "UPDATE `Activities` SET `trashed`='1' WHERE `id`='$activity_id'";
            $DB->update($trash_activity_query);

            // Changing the page.
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity_id);
            die; // Ending the script
        }

        // Ensure the restore activity button has been pressed
        if (isset($_POST['restore_activity']) && $_POST['restore_activity'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $restore_activity_query = "UPDATE `Activities` SET `trashed`='0' WHERE `id`='$activity_id'";
            $DB->update($restore_activity_query);

            // Changing the page.
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity_id);
            die; // Ending the script
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Activity Profile | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style>        
        .information_section {
            margin-bottom: 20px;
        }
        
        .information_section strong {
            display: inline-block;
            width: 100%;
            color: #555;
        }

        #widget_toggle_buttons {
            display: flex; /* Enable flexbox */
            justify-content: center; /* Center buttons horizontally */
            align-items: center; /* Center buttons vertically (if needed) */
        }

        /* Styling for toggle buttons */
        #widget_toggle_buttons button {
            text-align: center; /* Center the text */
            font-family: sans-serif; /* Use the same font as the title */
            font-size: 1em; /* Adjust font size for a balanced look */
            font-weight: bold; /* Make the text bold */
            color: #405d9b; /* Match the theme color */
            padding: 10px 20px; /* Add padding for clickable space */
            background: linear-gradient(to right, #a1c4fd, #c2e9fb);
            border: none; /* Remove default borders */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            cursor: pointer; /* Indicate that it's clickable */
            margin: 20px 5px 20px 5px ; /* Add spacing between buttons */
            transition: all 0.3s ease; /* Smooth hover effect */
        }

        /* Hover effect for buttons */
        #widget_toggle_buttons button:hover {
            background: linear-gradient(to right, #dbe9f9, #f0f8ff); /* Reverse the gradient */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15); /* Slightly deeper shadow on hover */
            transform: translateY(-2px); /* Subtle lift effect */
        }

        /* Active button style */
        #widget_toggle_buttons button:active {
            transform: translateY(0); /* Reset the lift effect */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Slightly smaller shadow */
        }

    </style>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <script src="../functions.js"></script>

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Edit activity button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_activity_data.php?activity_id=<?php echo $activity_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Edit Activity Info
                    </button>
                </a>
            </div>

            <!-- Check if activity is deleted or not -->
            <?php 
                // If the activity is not trashed, propose delete option
                if($activity_data_row['trashed'] == 0){
                    // Show delete button (default case)
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" onsubmit="return confirm('Are you sure you want to delete this profile? It will be placed in the trash.')">
                            <button id="submenu_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="delete_activity" value="1">
                                Delete Activity
                            </button>
                        </form>
                    </div>
                <?php
                } else {
                    // Propose restore option for trashed activity
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" onsubmit="return confirm('Are you sure you want to restore this profile from trash?')">
                            <button id="submenu_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="restore_activity" value="1">
                                Restore Activity
                            </button>
                        </form>
                    </div>
                <?php
                }
            ?>

                    
            <!-- Below cover area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left area; Activity information area -->
                <div id="medium_rectangle" style="flex:0.7;">

                    <!-- Section title of contact section -->
                    <div id="section_title">
                        <span>Activity Info</span>
                    </div>

                    <!-- Personal Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Personal Information</h2>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($activity_data_row['activity_name']); ?></p>
                        <p><strong>Number of Places:</strong> 
                            <?php 
                                $count = $activity_data_row['number_of_places'];
                                echo htmlspecialchars($count) . ' ' . (($count == 1) ? 'Place' : 'Places'); 
                            ?>
                        </p>
                        <p><strong>Number of Participants:</strong> 
                            <?php 
                                $count = $activity_data_row['number_of_participants'];
                                echo htmlspecialchars($count) . ' ' . (($count == 1) ? 'Participant' : 'Participants'); 
                            ?>
                        </p>
                        <p><strong>Duration:</strong> 
                            <?php 
                                $duration = $activity_data_row['activity_duration'];
                                echo htmlspecialchars($duration) . ' ' . (($duration == 1) ? 'Hour' : 'Hours'); 
                            ?>
                        </p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars(formatDate($activity_data_row['activity_date'])); ?></p>
                        <p><strong>Location:</strong> 
                            <?php 
                                $location = $activity_data_row['activity_location'];
                                echo htmlspecialchars((($location == "") ? 'Not Added' : $location)); 
                            ?>
                        </p>
                    </div>

                    <!-- Time Periods -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Time Periods</h2>
                        <?php if (!empty($activity_time_periods_data)): ?>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <?php foreach ($activity_time_periods_data as $activity_time_periods_data_row): ?>
                                    <li><?php echo htmlspecialchars($activity_time_periods_data_row['time_period'] ?: 'No specific time period provided'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No time period provided.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Domains -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Domains</h2>
                        <?php if (!empty($activity_domain_data)): ?>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <?php foreach ($activity_domain_data as $activity_domain_data_row): ?>
                                    <li><?php echo htmlspecialchars($activity_domain_data_row['domain'] ?: 'No specific interest provided'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No domain provided.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Additional Details -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Additional Details</h2>
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($activity_data_row['additional_notes']) ?: 'None'; ?></p>
                        <p><strong>Registration Date:</strong> <?php echo htmlspecialchars(formatDate($activity_data_row['registration_date'])); ?></p>
                        <p><strong>Profile In Trash:</strong> <?php echo htmlspecialchars($activity_data_row['trashed'] ? "Yes" : "No"); ?></p>
                    </div>

                    
                </div>


                <!-- Right area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget display -->
                    <div id="medium_rectangle">

                    <!-- Toggle buttons -->
                    <div id="widget_toggle_buttons">
                        <button onclick="showWidgets_volunteer_page('current_activities')">Show Participants</button>
                        <button onclick="showWidgets_volunteer_page('matching_activities')">Show Matching Volunteers</button>
                    </div>
                   


                </div>


                </div>

            </div>
            
        </div>
        
    </body>
</html>