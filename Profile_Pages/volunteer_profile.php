<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    // Initializing marching activities filter variables
    $interest_filter = 'checked';
    $weekday_filter = 'checked';
    $time_period_filter = 'checked';


    if (isset($_GET['volunteer_id'])) {

        $volunteer_id = $_GET['volunteer_id'];
        $volunteer_data = fetch_volunteer_data($volunteer_id);
        $interest_data = fetch_volunteer_interest_data($volunteer_id);
        $availability_data = fetch_volunteer_availability_data($volunteer_id);

        // Collect volunteer data
        $contracts_data = fetch_data("
            SELECT * 
            FROM Contracts 
            WHERE volunteer_id='$volunteer_id' 
            ORDER BY id desc 
            LIMIT 7"
        );

        $purchases_data = fetch_data("
            SELECT * 
            FROM Purchases 
            WHERE volunteer_id='$volunteer_id' 
            ORDER BY id desc 
            LIMIT 7"
        );

        $activities_data = fetch_data("
            SELECT a.* 
            FROM Activities a
            JOIN Volunteer_Activity_Junction vaj ON vaj.activity_id = a.id
            WHERE vaj.volunteer_id='$volunteer_id' 
            ORDER BY id desc 
            LIMIT 7"
        );

        // Collecting activity domains data
        $volunteer_interests_data = fetch_data(
            "SELECT * FROM Volunteer_Interests
                    WHERE volunteer_id = '$volunteer_id'"
        );

        // Collecting activity availability data
        $volunteer_availability_data = fetch_data(
            "SELECT * FROM Volunteer_Availability
                    WHERE volunteer_id = '$volunteer_id'"
        );
    }


    // Getting the activity domains in a string
    $interests = [];
    foreach ($volunteer_interests_data as $volunteer_interests_data_row){
        $interests[] = $volunteer_interests_data_row['interest'];
    }
    $volunteer_interests_sql = "'" . implode("', '", $interests) . "'";

    $weekday_time_period_availability = [];
    $weekday_availability = [];
    $time_period_availability = [];
    foreach ($volunteer_availability_data as $volunteer_availability_data_row){
        $weekday = $volunteer_availability_data_row['weekday'];
        $time_period = $volunteer_availability_data_row['time_period'];
        $weekday_time_period = "{$weekday}-{$time_period}";

        // Add combined weekday-time_period.
        $weekday_time_period_availability[] = $weekday_time_period;

        // Add weekday if not already in the list
        if (!in_array($weekday, $weekday_availability)) {
            $weekday_availability[] = $weekday;
        }

        // Add time_period if not already in the list
        if (!in_array($time_period, $time_period_availability)) {
            $time_period_availability[] = $time_period;
        }
    }

    // For the weekday_time_period_availability array:
    $weekday_time_period_sql = "'" . implode("', '", $weekday_time_period_availability) . "'";

    // For the weekday_availability array:
    $weekday_availability_sql = "'" . implode("', '", $weekday_availability) . "'";

    // For the time_period_availability array:
    $time_period_availability_sql = "'" . implode("', '", $time_period_availability) . "'";

    // Default matching volunteers data
    $all_matching_activities_data = fetch_data("
        SELECT DISTINCT a.* 
        FROM Activities a
        JOIN Activity_Domains ad ON a.id = ad.activity_id
        JOIN Activity_Time_Periods atp ON a.id = atp.activity_id
        WHERE a.trashed = 0
        AND a.number_of_participants < a.number_of_places
        AND ad.domain IN ($volunteer_interests_sql)
        AND a.activity_date >= CURDATE()
        AND NOT EXISTS (
            SELECT 1 FROM Volunteer_Activity_Junction vaj 
            WHERE vaj.activity_id = a.id 
            AND vaj.volunteer_id = '$volunteer_id'
        )
        ORDER BY a.id DESC
    ");

    
    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Retrieve filter form data
        $interest_filter = $_POST['interest_filter'] ?? '';
        $weekday_filter = $_POST['weekday_filter'] ?? '';
        $time_period_filter = $_POST['time_period_filter'] ?? '';

        // Default matching volunteers data
        $sql_filter_query = "
            SELECT DISTINCT a.* 
            FROM Activities a
            JOIN Activity_Domains ad ON a.id = ad.activity_id
            JOIN Activity_Time_Periods atp ON a.id = atp.activity_id
            WHERE a.trashed = 0
            AND a.number_of_participants < a.number_of_places
        ";

        // Interest filter
        $sql_filter_query .= ($interest_filter ? " AND ad.domain IN ($volunteer_interests_sql) " : '');

        // Weekday + time_period filter
        if ($weekday_filter && $time_period_filter){
            $sql_filter_query .= " AND CONCAT(DAYNAME(a.activity_date), '-', atp.time_period) IN ($weekday_time_period_sql) ";
        } elseif($weekday_filter){
            // Weekday filter
            $sql_filter_query .= " AND DAYNAME(a.activity_date) IN ($weekday_availability_sql) ";
        } elseif($time_period_filter){
            // Time period filter
            $sql_filter_query .= " AND atp.time_period IN ($time_period_availability_sql) ";
        }




        $sql_filter_query .= "
            AND a.activity_date >= CURDATE() 
            AND NOT EXISTS (
                SELECT 1 FROM Volunteer_Activity_Junction vaj 
                WHERE vaj.activity_id = a.id 
                AND vaj.volunteer_id = '$volunteer_id'
            )
            ORDER BY a.id DESC
        ";

        $all_matching_activities_data = fetch_data($sql_filter_query);





        // Ensure the delete volunteer button has been pressed
        if (isset($_POST['delete_volunteer']) && $_POST['delete_volunteer'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $trash_volunteer_query = "UPDATE `Volunteers` SET `trashed`='1' WHERE `id`='$volunteer_id'";
            $DB->update($trash_volunteer_query);

            // Changing the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
            die; // Ending the script
        }

        // Ensure the restore volunteer button has been pressed
        if (isset($_POST['restore_volunteer']) && $_POST['restore_volunteer'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $restore_volunteer_query = "UPDATE `Volunteers` SET `trashed`='0' WHERE `id`='$volunteer_id'";
            $DB->update($restore_volunteer_query);

            // Changing the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
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
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Edit volunteer button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_volunteer_data.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">edit_document</span>
                        <span>Edit Volunteer Info</span>
                    </button>
                    
                </a>
            </div>

            <!-- Add contract button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Add_Form_Pages/add_contract.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">contract</span>
                        <span>New Contract</span>
                    </button>
                </a>
            </div>

            <!-- Add purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Add_Form_Pages/add_purchase.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">add_shopping_cart</span>
                        <span>New Purchase</span>
                    </button>
                </a>
            </div>

            <!-- Contract if volunteer is deleted or not -->
            <?php 
                // If the volunteer is not trashed, propose delete option
                if($volunteer_data['trashed'] == 0){
                    // Show delete button (default case)
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" onsubmit="return confirm('Are you sure you want to delete this profile? It will be placed in the trash.')">
                            <button id="submenu_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="delete_volunteer" value="1">
                                <span class="material-symbols-outlined" style="margin-right: 8px;">delete</span>
                                <span>Trash Profile</span>
                            </button>
                        </form>
                    </div>
                <?php
                } else {
                    // Propose restore option for trashed volunteer
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" onsubmit="return confirm('Are you sure you want to restore this profile from trash?')">
                            <button id="submenu_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="restore_volunteer" value="1">
                                <span class="material-symbols-outlined" style="margin-right: 8px;">restore_from_trash</span>
                                <span>Restore Profile</span>
                            </button>
                            
                        </form>
                    </div>
                <?php
                }
            ?>

                    
            <!-- Below cover area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left area; Volunteer information area -->
                <div id="medium_rectangle" style="flex:0.57;">

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
                            <strong style="color: rgb(226, 65, 65); width: 100%;">Volunteer doesn't currently have a contract.</strong><br>
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
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget display -->
                    <div id="medium_rectangle">

                        <!-- Toggle buttons -->
                        <div id="widget_toggle_buttons">
                            <button id="recent_contracts_button" class="active" onclick="ToggleWidgets('contracts', this)">Show Recent Contracts</button>
                            <button id="recent_purchases_button" onclick="ToggleWidgets('purchases', this)">Show Recent Purchases</button>
                            <button id="recent_activities_button" onclick="ToggleWidgets('activities', this)">Show Recent Activities</button>
                            <button id="matching_activities_button" onclick="ToggleWidgets('matching_activities', this)">Show Matching Activities</button>
                        </div>

                        <!-- Display contracts widgets -->
                        <div id="contracts_widgets" class="widget-container">
                            <?php
                            if ($contracts_data) {
                                foreach ($contracts_data as $contract_data_row) {
                                    $contract_id = $contract_data_row['id'];
                                    $volunteer_data = fetch_volunteer_data($contract_data_row['volunteer_id']);
                                    $date = new DateTime($contract_data_row['issuance_date']);
                                    $month = $date->format('F'); // Full month name (e.g., "January")
                                    include("../Widget_Pages/contract_widget.php");
                                }
                            }
                            ?>
                            <!-- All volunteer contracts button -->
                            <div id="volunteer_specific_contracts_button" style="text-align: right; padding: 10px 20px; display: inline-block;">
                                <a href="../Listing_Pages/volunteer_specific_contracts.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
                                    <button name="volunteer_specific_contracts_button" id="submenu_button">
                                        See All <?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s" ?> Contracts
                                    </button>
                                </a>
                            </div>
                        </div>                    

                        
                        <!-- Display purchase widgets -->
                        <div id="purchases_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($purchases_data) {
                                foreach ($purchases_data as $purchase_data_row) {
                                    $purchase_id = $purchase_data_row['id'];
                                    $volunteer_data = fetch_volunteer_data($purchase_data_row['volunteer_id']);
                                    include("../Widget_Pages/purchase_widget.php");
                                }
                            }
                            ?>
                            <!-- All volunteer purchases button -->
                            <div id="volunteer_specific_purchases_button" style="text-align: right; padding: 10px 20px;">
                                <a href="../Listing_Pages/volunteer_specific_purchases.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
                                    <button name="volunteer_specific_purchases_button" id="submenu_button">
                                        See All <?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s" ?> Purchases
                                    </button>
                                </a>
                            </div>
                        </div>

                        <!-- Display activities widgets -->
                        <div id="activities_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($activities_data) {
                                foreach ($activities_data as $activity_data_row) {
                                    $activity_id = $activity_data_row['id'];
                                    include("../Widget_Pages/matching_activity_widget.php");
                                }
                            }
                            ?>
                            <!-- All volunteer activities button (Initially hidden) -->
                            <div id="volunteer_specific_activities_button" style="text-align: right; padding: 10px 20px;">
                                <a href="../Listing_Pages/volunteer_specific_activities.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
                                    <button name="volunteer_specific_activities_button" id="submenu_button">
                                        See All <?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s" ?> Activities
                                    </button>
                                </a>
                            </div>
                        </div>

                        <!-- Display matching volunteers widgets --> 
                        <div id="matching_activities_widgets" class="widget-container" style="display: none;">

                            <form id="filterForm" action="" method="post">
                                <label class="switch">
                                    <input type="checkbox" name="interest_filter" <?php echo ($interest_filter ?'checked' : ''); ?>>>
                                    <span class="slider round"></span>
                                </label>
                                <span>Interest Filter</span>
                                <br>

                                <label class="switch">
                                    <input type="checkbox" name="weekday_filter" <?php echo ($weekday_filter ?'checked' : ''); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span>Weekday Filter</span>
                                <br>

                                <label class="switch">
                                    <input type="checkbox" name="time_period_filter" <?php echo ($time_period_filter ?'checked' : ''); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span>Time Period Filter</span>
                                <br>

                                <!-- Submit button -->
                                <div style="text-align: center;">
                                    <button type="submit" style="padding: 10px 20px; background-color: #405d9b; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                                        Apply Filter
                                    </button>
                                </div>
                            </form>

                            <!-- Show Matching Volunteers if POST -->
                            <?php
                                // After your form processing logic, add this PHP code
                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    echo '<script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var matchingButton = document.getElementById("matching_activities_button");
                                        ToggleWidgets("matching_activities", matchingButton);
                                    });
                                    </script>';
                                }
                            ?>
                                
                            <?php
                                // Counting the number of elements post filter
                                if (empty($all_matching_activities_data)) {
                                    echo "No activities found.";
                                } else {
                                    echo "This volunteer has " . count($all_matching_activities_data) . ((count($all_matching_activities_data) == 1) ? " activity that matches." : " activities that match");
                                } 

                                // Display the widgets
                                if($all_matching_activities_data){
                                    foreach($all_matching_activities_data as $activity_data_row){
                                        $activity_id = $activity_data_row['id'];
                                        include("../Widget_Pages/matching_activity_widget.php");
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