<?php

    // Include classes
    include("classes/connect.php");
    include("classes/functions.php");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $member_data = fetch_member_data($id);
        $interest_data = fetch_member_interest_data($id);
        $availability_data = fetch_member_availability_data($id);

        // Collect volunteer data
        $checks_data = fetch_data("select * from Checks where member_id='$id' order by id desc limit 3");
        $purchases_data = fetch_data("select * from Purchases where member_id='$id' order by id desc limit 3");
    }

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Ensure the delete volunteer button has been pressed
        if (isset($_POST['delete_volunteer']) && $_POST['delete_volunteer'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $delete_volunteer_query = "UPDATE `Members` SET `trashed`='1' WHERE `id`='$id'";
            $DB->update($delete_volunteer_query);

            // Changing the page.
            header("Location: volunteer_profile.php?id=" . $id);
            die; // Ending the script
        }

        // Ensure the restore volunteer button has been pressed
        if (isset($_POST['restore_volunteer']) && $_POST['restore_volunteer'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $restore_volunteer_query = "UPDATE `Members` SET `trashed`='0' WHERE `id`='$id'";
            $DB->update($restore_volunteer_query);

            // Changing the page.
            header("Location: volunteer_profile.php?id=" . $id);
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
        <link rel="stylesheet" href="style.css">
    </head>

    <style>        
        .information_section {
            margin-bottom: 20px;
        }
        
        .information_section strong {
            display: inline-block;
            width: 150px;
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
            background: linear-gradient(to right, #f0f8ff, #dbe9f9); /* Subtle gradient background */
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

        <script src="functions.js"></script>

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Edit volunteer button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="volunteer_edit_data.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Edit Volunteer Info
                    </button>
                </a>
            </div>

            <!-- Add check button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="add_check.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Add Check
                    </button>
                </a>
            </div>

            <!-- Add purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="add_purchase.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Add Purchase
                    </button>
                </a>
            </div>

            <!-- Check if volunteer is deleted or not -->
            <?php 
                // If the volunteer is not trashed, propose delete option
                if($member_data['trashed'] == 0){
                    // Show delete button (default case)
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="volunteer_profile.php?id=<?php echo $id; ?>" onsubmit="return confirm('Are you sure you want to delete this profile? It will be placed in the trash.')">
                            <button id="submenu_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="delete_volunteer" value="1">
                                Delete Volunteer
                            </button>
                        </form>
                    </div>
                <?php
                } else {
                    // Propose restore option for trashed volunteer
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="volunteer_profile.php?id=<?php echo $id; ?>" onsubmit="return confirm('Are you sure you want to restore this profile from trash?')">
                            <button id="submenu_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="restore_volunteer" value="1">
                                Restore Volunteer
                            </button>
                        </form>
                    </div>
                <?php
                }
            ?>

                    
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Left area; Volunteer information area -->
                <div id="medium_rectangle" style="flex:0.7;">

                    <!-- Section title of contact section -->
                    <div id="section_title">
                        <span>Volunteer Info</span>
                    </div>

                    <!-- Personal Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Personal Information</h2>
                        <p><strong>First Name:</strong> <?php echo htmlspecialchars($member_data['first_name']); ?></p>
                        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($member_data['last_name']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($member_data['gender']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($member_data['date_of_birth']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($member_data['address']); ?></p>
                        <p><strong>Zip Code:</strong> <?php echo htmlspecialchars($member_data['zip_code']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($member_data['telephone_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($member_data['email']); ?></p>
                    </div>
                    
                    <!-- Volunteer Contributions -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Volunteer Contributions</h2>
                        <p><strong>Points:</strong> <span><?php echo htmlspecialchars($member_data['points']); ?></span></p>
                        <p><strong>Hours Completed:</strong> <span><?php echo htmlspecialchars($member_data['hours_completed']); ?></span></p>
                        <p><strong>Assigned Area:</strong> <?php echo htmlspecialchars($member_data['assigned_area']); ?></p>
                        <p><strong>Organizer Name:</strong> <?php echo htmlspecialchars($member_data['organizer_name']); ?></p>
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
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($member_data['additional_notes']) ?: 'None'; ?></p>
                        <p><strong>Registration Date:</strong> <?php echo htmlspecialchars($member_data['registration_date']); ?></p>
                        <p><strong>Profile In Trash:</strong> <?php echo htmlspecialchars($member_data['trashed'] ? "Yes" : "No"); ?></p>
                    </div>
                    
                </div>


                <!-- Right area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget display -->
                    <div id="medium_rectangle">

                    <!-- Toggle buttons -->
                    <div id="widget_toggle_buttons">
                        <button onclick="showWidgets('checks')">Show Checks</button>
                        <button onclick="showWidgets('purchases')">Show Purchases</button>
                        <button onclick="showWidgets('activities')">Show Activities</button>
                    </div>


                    <!-- Display checks widgets -->
                    <div id="checks_widgets" class="widget-container">
                        <?php
                        if ($checks_data) {
                            foreach ($checks_data as $check_data_row) {
                                $member_data = fetch_member_data($check_data_row['member_id']);
                                $date = new DateTime($check_data_row['issuance_date']);
                                $month = $date->format('F'); // Full month name (e.g., "January")
                                include("check_widget.php");
                            }
                        }
                        ?>
                    </div>                    

                    <!-- All volunteer checks button (Initially hidden) -->
                    <div id="all_volunteer_checks_button" style="text-align: right; padding: 10px 20px; display: none;">
                        <a href="all_volunteer_checks.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                            <button name="all_volunteer_checks_button" id="submenu_button">
                                All <?php echo $member_data['first_name'] . " " . $member_data['last_name'] . "'s" ?> Checks
                            </button>
                        </a>
                    </div>

                    
                    <!-- Display purchase widgets -->
                    <div id="purchases_widgets" class="widget-container" style="display: none;">
                        <?php
                        if ($purchases_data) {
                            foreach ($purchases_data as $purchase_data_row) {
                                $member_data = fetch_member_data($purchase_data_row['member_id']);
                                include("purchase_widget.php");
                            }
                        }
                        ?>
                    </div>

                    <!-- All volunteer purchases button (Initially hidden) -->
                    <div id="all_volunteer_purchases_button" style="text-align: right; padding: 10px 20px;display: inline-block;">
                        <a href="all_volunteer_purchases.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                            <button name="all_volunteer_purchases_button" id="submenu_button">
                                All <?php echo $member_data['first_name'] . " " . $member_data['last_name'] . "'s" ?> Purchases
                            </button>
                        </a>
                    </div>


                </div>


                </div>

            </div>
            
        </div>
        
    </body>
</html>