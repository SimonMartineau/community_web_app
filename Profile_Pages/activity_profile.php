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
        $activity_domains_data = fetch_data(
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

    <style></style>

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
                } else{
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
                        <?php if (!empty($activity_domains_data)): ?>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <?php foreach ($activity_domains_data as $activity_domains_data_row): ?>
                                    <li><?php echo htmlspecialchars($activity_domains_data_row['domain'] ?: 'No specific interest provided'); ?></li>
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
                            <button onclick="showWidgets_activity_page('current_participants')">Show Participants</button>
                            <button onclick="showWidgets_activity_page('matching_participants')">Show Matching Volunteers</button>
                        </div> 
                        

                        <?php
                            // Getting the activity date
                            $activity_date = $activity_data_row['activity_date'];

                            // Getting the activity time periods in a string
                            $time_periods = [];
                            foreach ($activity_time_periods_data as $activity_time_periods_data_row){
                                $time_periods[] = $activity_time_periods_data_row['time_period'];
                            }
                            $activity_time_periods_sql = "'" . implode("', '", $time_periods) . "'";

                            // Getting the activity domains in a string
                            $domains = [];
                            foreach ($activity_domains_data as $activity_domains_data_row){
                                $domains[] = $activity_domains_data_row['domain'];
                            }
                            $activity_domains_sql = "'" . implode("', '", $domains) . "'";
                            
                            $all_current_participants_data = fetch_data("
                                SELECT DISTINCT v.* 
                                FROM Volunteers v
                                JOIN Volunteer_Activity_Junction vaj ON v.id = vaj.volunteer_id
                                WHERE vaj.activity_id = '$activity_id'
                                ORDER BY v.id DESC
                            ");
                            

                            // Collect marching volunteers data
                            $all_matching_participants_data = fetch_data("
                                SELECT DISTINCT v.* 
                                FROM Volunteers v
                                JOIN Volunteer_Availability va ON v.id = va.volunteer_id
                                JOIN Volunteer_Interests vi ON v.id = vi.volunteer_id
                                WHERE v.trashed = 0
                                AND DAYNAME('$activity_date') = va.weekday
                                AND va.time_period IN ($activity_time_periods_sql)
                                AND vi.interest IN ($activity_domains_sql)
                                AND v.hours_completed < v.hours_required
                                AND NOT EXISTS (
                                    SELECT 1 FROM Volunteer_Activity_Junction vaj 
                                    WHERE vaj.volunteer_id = v.id 
                                    AND vaj.activity_id = '$activity_id'
                                )
                                ORDER BY v.id DESC
                            ");
                        ?>

                        <!-- Display participants widgets --> 
                        <div id="show_current_volunteers_widgets" class="widget-container">
                            <?php
                                // Counting the number of elements post filter
                                if (empty($all_current_participants_data)) {
                                    echo "This activity doesn't have any participants yet.";
                                } else {
                                    echo "This activity has " . count($all_current_participants_data) . ((count($all_current_participants_data) == 1) ?" participant." : " participants.");
                                }
                           

                                // Display the widgets
                                if($all_current_participants_data){
                                    foreach($all_current_participants_data as $volunteer_data_row){
                                        $volunteer_id = $volunteer_data_row['id'];
                                        include("../Widget_Pages/matching_volunteer_widget.php");
                                    }
                                }
                            ?>
                        </div>

                        <!-- Display matching volunteers widgets --> 
                        <div id="show_matching_volunteers_widgets" class="widget-container" style="display: none;">
                            <?php
                                // Counting the number of elements post filter
                                if (empty($all_matching_participants_data)) {
                                    echo "No volunteers found.";
                                } else {
                                    echo "This activity has " . count($all_matching_participants_data) . ((count($all_matching_participants_data) == 1) ? " volunteer" : " volunteers") . " that match.";
                                } 

                                // Display the widgets
                                if($all_matching_participants_data){
                                    foreach($all_matching_participants_data as $volunteer_data_row){
                                        $volunteer_id = $volunteer_data_row['id'];
                                        include("../Widget_Pages/matching_volunteer_widget.php");
                                    }
                                }
                            ?>
                        </div>

                        
        
                    </div>


                </div>

            </div>
            
        </div>
        
    </body>
</html>