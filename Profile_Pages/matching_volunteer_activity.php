<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    // Getting volunteer data
    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];

        $volunteer_data = fetch_volunteer_data($volunteer_id);
        $interest_data = fetch_volunteer_interest_data($volunteer_id);
        $availability_data = fetch_volunteer_availability_data($volunteer_id);
    }

    // Getting activity data
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

    // Checking if volunteer is assigned to activity
    $volunteer_activity_match_data = fetch_data(
        "SELECT * FROM Volunteer_Activity_Junction
                WHERE volunteer_id = '$volunteer_id'
                AND activity_id = '$activity_id'"
    );

    // Storing volunteer activity junction status in a variable
    if (!empty($volunteer_activity_match_data)) {
        // Junction exists
        $volunteer_activity_assigned = true;
    } else{
        // Junction does not exist
        $volunteer_activity_assigned = false;
    }


    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Ensure the delete activity button has been pressed
        if (isset($_POST['assign_volunteer_activity']) && $_POST['assign_volunteer_activity'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $assign_volunteer_to_activity_query = "insert into Volunteer_Activity_Junction (volunteer_id, check_id, activity_id) 
                                                    values ('$volunteer_id', -1, '$activity_id')";
            $DB->update($assign_volunteer_to_activity_query);

            // Changing the page.
            header("Location: ../Profile_Pages/matching_volunteer_activity.php?volunteer_id=" . $volunteer_id . "&activity_id=" . $activity_id);
            die; // Ending the script
        }

        // Ensure the restore activity button has been pressed
        if (isset($_POST['unassign_volunteer_activity']) && $_POST['unassign_volunteer_activity'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $unassign_volunteer_from_activity_query = "delete from Volunteer_Activity_Junction 
                                                        where volunteer_id = '$volunteer_id'
                                                        AND activity_id = '$activity_id'";
            $DB->update($unassign_volunteer_from_activity_query);

            // Changing the page.
            header("Location: ../Profile_Pages/matching_volunteer_activity.php?volunteer_id=" . $volunteer_id . "&activity_id=" . $activity_id);
            die; // Ending the script
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Volunteer Profile | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <script src="../functions.js"></script>

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Check if volunteer is assigned to activity -->
            <?php 
                // If the volunteer is assigned to the activity
                if($volunteer_activity_assigned == true){
                    // Show the unassign button
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/matching_volunteer_activity.php?volunteer_id=<?php echo $volunteer_id; ?>&activity_id=<?php echo $activity_id; ?>">
                            <button id="submenu_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="unassign_volunteer_activity" value="1">
                                Unassign Volunteer from Activity
                            </button>
                        </form>
                    </div>
                <?php
                } else{
                // Show the assign button
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/matching_volunteer_activity.php?volunteer_id=<?php echo $volunteer_id; ?>&activity_id=<?php echo $activity_id; ?>">
                            <button id="submenu_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="assign_volunteer_activity" value="1">
                                Assign Volunteer to Activity
                            </button>
                        </form>
                    </div>
                <?php
                }
            ?>

            <!-- Go to volunteer profile button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Go To Volunteer Profile
                    </button>
                </a>
            </div>

            <!-- Go to activity profile button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Go To Activity Profile
                    </button>
                </a>
            </div>

                    
            <!-- Below cover area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left area; Volunteer information area -->
                <div id="medium_rectangle" style="flex:0.5;  padding-right: 20px;">

                    <!-- Section title of contact section -->
                    <div id="section_title">
                        <span>Volunteer Info</span>
                    </div>

                    <!-- Personal Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Personal Information</h2>
                        <p><strong>First Name:</strong> <?php echo htmlspecialchars($volunteer_data['first_name']); ?></p>
                        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($volunteer_data['last_name']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($volunteer_data['gender']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars(formatDate($volunteer_data['date_of_birth'])); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($volunteer_data['address']); ?></p>
                        <p><strong>Zip Code:</strong> <?php echo htmlspecialchars($volunteer_data['zip_code']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($volunteer_data['telephone_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($volunteer_data['email']); ?></p>
                    </div>
                    
                    <!-- Volunteer Contributions -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Volunteer Contributions</h2>
                        <?php if ($volunteer_data['hours_required'] == 0): ?>
                            <strong style="color: rgb(226, 65, 65); width: 100%;">Volunteer doesn't currently have a check.</strong><br>
                        <?php endif; ?>
                        <?php if ($volunteer_data['points'] < 0): ?>
                            <strong style="color: rgb(226, 65, 65); width: 100%;">Warning: Volunteer has spent too many points.</strong><br>
                        <?php endif; ?>
                        <p><strong>Points:</strong> <span><?php echo htmlspecialchars($volunteer_data['points'] . " Points"); ?></span></p>
                        <p><strong>Hours Required:</strong> <span><?php echo htmlspecialchars($volunteer_data['hours_required'] . " Hours"); ?></span></p>
                        <p><strong>Hours Completed:</strong> <span><?php echo htmlspecialchars($volunteer_data['hours_completed'] . " Hours"); ?></span></p>
                        <p><strong>Organizer Name:</strong> <?php echo htmlspecialchars($volunteer_data['organizer_name']); ?></p>
                    </div>

                    <!-- Interests -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Interests</h2>
                        <?php if (!empty($interest_data)): ?>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <?php foreach ($interest_data as $interest): ?>
                                    <li><?php echo htmlspecialchars($interest['interest'] ?: 'No specific interest provided'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No interests provided.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Weekly Availability -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Weekly Availability</h2>
                        <?php
                        // Define the weekdays and time periods
                        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $time_periods = ['Morning', 'Afternoon', 'Evening'];
                        
                        // Create a matrix for availability
                        $availability_matrix = [];
                        foreach ($weekdays as $weekday) {
                            foreach ($time_periods as $time_period) {
                                $availability_matrix[$weekday][$time_period] = '';
                            }
                        }
                        
                        // Populate the matrix based on availability_data
                        foreach ($availability_data as $availability) {
                            $weekday = $availability['weekday'];
                            $time_period = $availability['time_period'];
                            if (isset($availability_matrix[$weekday][$time_period])) {
                                $availability_matrix[$weekday][$time_period] = '✔';
                            }
                        }
                        ?>
                        
                        <table style="width: 50%; border-collapse: collapse; margin-top: 10px;">
                            <thead>
                                <tr style="background-color: #f1f1f1;">
                                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Weekday</th>
                                    <?php foreach ($time_periods as $time_period): ?>
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;"><?php echo htmlspecialchars($time_period); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weekdays as $weekday): ?>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($weekday); ?></td>
                                        <?php foreach ($time_periods as $time_period): ?>
                                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                                                <?php echo htmlspecialchars($availability_matrix[$weekday][$time_period]); ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Additional Details -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Additional Details</h2>
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($volunteer_data['additional_notes']) ?: 'None'; ?></p>
                        <p><strong>Registration Date:</strong> <?php echo htmlspecialchars(formatDate($volunteer_data['registration_date'])); ?></p>
                        <p><strong>Profile In Trash:</strong> <?php echo htmlspecialchars($volunteer_data['trashed'] ? "Yes" : "No"); ?></p>
                    </div>
                    
                </div>



                <!-- Right area -->
                <div style="flex:0.5; padding-left: 20px;">

                    <!-- Left area; Activity information area -->
                <div id="medium_rectangle">

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



                </div>


                </div>

            </div>
            
        </div>
        
    </body>
</html>