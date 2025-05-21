<!-- PHP Code -->
<?php
    // Include header
    include("../Misc/header.php");

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();
    $user_id = $user_data['user_id'];

    // Updating all backend processesstyle="max-width: 30px;"
    update_backend_data();

    // Initializing marching activities filter variables
    $interest_filter = 'checked';
    $weekday_filter = 'checked';
    $time_period_filter = 'checked';

    // Default volunteer data
    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];
        $volunteer_data_row = fetch_volunteer_data_row($user_id,$volunteer_id);
        $volunteer_interests_data_rows = fetch_volunteer_interest_data_rows($user_id,$volunteer_id);
        $volunteer_availability_data_rows = fetch_volunteer_availability_data_rows($user_id,$volunteer_id);

        // Collect volunteer data
        $contracts_data_rows = fetch_data_rows("
            SELECT * 
            FROM Contracts 
            WHERE volunteer_id='$volunteer_id' 
            ORDER BY id desc 
            LIMIT 7"
        );

        // Collect volunteer purchases data
        $purchases_data_rows = fetch_data_rows("
            SELECT * 
            FROM Purchases 
            WHERE volunteer_id='$volunteer_id' 
            ORDER BY id desc 
            LIMIT 7"
        );

        // Collect volunteer's activities data
        $activities_data_rows = fetch_data_rows("
            SELECT a.* 
            FROM Activities a
            JOIN Volunteer_Activity_Junction vaj ON vaj.activity_id = a.id
            WHERE vaj.volunteer_id='$volunteer_id' 
            ORDER BY id desc 
            LIMIT 7"
        );
    }

    // Getting the activity domains in a string
    $interests = [];
    foreach ($volunteer_interests_data_rows as $volunteer_interests_data_row){
        $interests[] = $volunteer_interests_data_row['interest'];
    }
    $volunteer_interests_sql = "'" . implode("', '", $interests) . "'";

    $weekday_time_period_availability = [];
    $weekday_availability = [];
    $time_period_availability = [];
    foreach ($volunteer_availability_data_rows as $volunteer_availability_data_row){
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

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Retrieve filter form data
        $interest_filter = $_POST['interest_filter'] ?? '';
        $weekday_filter = $_POST['weekday_filter'] ?? '';
        $time_period_filter = $_POST['time_period_filter'] ?? '';
    }

    // Default matching volunteers data
    $sql_filter_query = "
            SELECT DISTINCT a.* 
            FROM Activities a
            JOIN Activity_Domains ad ON a.id = ad.activity_id
            JOIN Activity_Time_Periods atp ON a.id = atp.activity_id
            WHERE a.trashed = 0
            AND a.user_id = '$user_id'
            AND a.number_of_participants < a.number_of_places
            AND a.activity_date >= CURDATE()
            AND EXISTS (
                SELECT 1
                FROM Contracts c
                WHERE c.volunteer_id = '$volunteer_id'
                    AND a.activity_date BETWEEN c.start_date AND c.end_date
            )            
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
        AND NOT EXISTS (
            SELECT 1 FROM Volunteer_Activity_Junction vaj 
            WHERE vaj.activity_id = a.id 
            AND vaj.volunteer_id = '$volunteer_id'
        )
        ORDER BY a.id DESC
    ";

    $all_matching_activities_data_rows = fetch_data_rows($sql_filter_query);

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ensure the delete volunteer button has been pressed
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_volunteer']) && $_POST['delete_volunteer'] === '1') {

            // SQL query into Purchases
            $trash_volunteer_query = "UPDATE `Volunteers` SET `trashed`='1' WHERE `id`='$volunteer_id'";
            $DB->save($trash_volunteer_query);

            // Changing the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
            die; // Ending the script
        }

        // Ensure the restore volunteer button has been pressed
        if (isset($_POST['restore_volunteer']) && $_POST['restore_volunteer'] === '1') {

            // SQL query into Purchases
            $restore_volunteer_query = "UPDATE `Volunteers` SET `trashed`='0' WHERE `id`='$volunteer_id'";
            $DB->save($restore_volunteer_query);

            // Changing the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
            die; // Ending the script
        }


        // Ensure the assign activity button has been pressed
        if (isset($_POST['assign_volunteer_activity']) && $_POST['assign_volunteer_activity'] === '1') {

            $activity_id = $_POST['activity_id'];

            // SQL query into Purchases
            $assign_volunteer_to_activity_query = "INSERT INTO Volunteer_Activity_Junction (user_id, volunteer_id, contract_id, activity_id) 
                                                    VALUES ('$user_id', '$volunteer_id', -1, '$activity_id')";
            $DB->save($assign_volunteer_to_activity_query);

            // Updating all backend processes
            update_backend_data();

            // Changing the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
            die; // Ending the script
        }

        // Ensure the unassign activity button has been pressed
        if (isset($_POST['unassign_volunteer_activity']) && $_POST['unassign_volunteer_activity'] === '1') {

            $activity_id = $_POST['activity_id'];

            // SQL query into Purchases
            $unassign_volunteer_from_activity_query = "DELETE FROM Volunteer_Activity_Junction 
                                                        WHERE volunteer_id = '$volunteer_id'
                                                        AND activity_id = '$activity_id'";
            $DB->save($unassign_volunteer_from_activity_query);

            // Updating all backend processes
            update_backend_data();

            // Changing the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
            die; // Ending the script
        }
    }
?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= __('CivicLink | Volunteer Profile') ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/style.css">
    </head>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <script src="../JavaScript/functions.js"></script>

        <!-- Cover Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Edit Volunteer Button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_volunteer_data.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">edit_document</span>
                        <span><?= __('Edit Volunteer Profile') ?></span>
                    </button>
                </a>
            </div>

            <!-- Add Contract Button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Add_Form_Pages/add_contract.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">contract</span>
                        <span><?= __('New Contract') ?></span>
                    </button>
                </a>
            </div>

            <!-- Add Purchase Button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Add_Form_Pages/add_purchase.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">add_shopping_cart</span>
                        <span><?= __('New Purchase') ?></span>
                    </button>
                </a>
            </div>

            <!-- Trash/Restore Volunteer Button -->
            <?php 
                if($volunteer_data_row['trashed'] == 0){
            ?>
                <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                    <form method="POST" action="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" onsubmit="return confirm('<?= __('Are you sure you want to delete this profile? It will be placed in the trash.') ?>')">
                        <input type="hidden" name="delete_volunteer" value="1">
                        <button type="submit" id="submenu_button">
                            <span class="material-symbols-outlined" style="margin-right: 8px;">delete</span>
                            <span><?= __('Trash Profile') ?></span>
                        </button>
                    </form>
                </div>
            <?php
                } else {
            ?>
                <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                    <form method="POST" action="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" onsubmit="return confirm('<?= __('Are you sure you want to restore this profile from trash?') ?>')">
                        <input type="hidden" name="restore_volunteer" value="1">
                        <button id="submenu_button">
                            <span class="material-symbols-outlined" style="margin-right: 8px;">restore_from_trash</span>
                            <span><?= __('Restore Profile') ?></span>
                        </button>
                    </form>
                </div>
            <?php
                }
            ?>

                    
            <!-- Below Cover Area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left Area : Volunteer information area -->
                <div id="medium_rectangle" style="flex:0.57;">

                    <!-- Section Title of Contact Section -->
                    <div id="section_title">
                        <span><?= __('Volunteer Profile') ?></span>
                    </div>

                    <!-- Notifications -->
                    <?php if ($volunteer_data_row['hours_required'] == 0 || $volunteer_data_row['points'] < 0 || $volunteer_data_row['trashed'] == 1): ?>
                        <h2 style="font-size: 20px; color: #555;"><?= __('Notifications') ?></h2>
                    <?php endif; ?>

                    <!-- Profile is Trashed -->
                    <?php if ($volunteer_data_row['trashed'] == 1): ?>
                        <span class="warning" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                            <span class="material-symbols-outlined" style="margin-right: 5px;">delete</span>
                            <?= __('Profile is trashed.') ?>
                        </span><br>
                    <?php endif; ?>

                    <?php if ($volunteer_data_row['hours_required'] == 0): ?>
                        <span class="caution" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                            <span class="material-symbols-outlined" style="margin-right: 5px;">info</span> 
                            <?= __("Volunteer doesn't currently have a contract.") ?>
                        </span>
                        <span style="display: flex; align-items: center; width: 100%;">
                            <span class="material-symbols-outlined" style="margin-right: 5px;">subdirectory_arrow_right</span>
                            <?= __('Click on New Contract to assign a contract.') ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($volunteer_data_row['points'] < 0): ?>
                        <span class="warning" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                            <span class="material-symbols-outlined" style="margin-right: 5px;">warning</span> 
                            <?= __('Volunteer has spent too many points.') ?>
                        </span>
                        <span style="display: flex; align-items: center; width: 100%;">
                            <span class="material-symbols-outlined" style="margin-right: 5px;">subdirectory_arrow_right</span>
                            <?= __('Please lower the points spent or increase the points deposit in the current contract.') ?>
                        </span>
                    <?php endif; ?>

                    <!-- Personal Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;"><?= __('Personal Information') ?></h2>
                        <p><strong><?= __('First Name:') ?></strong> <?= htmlspecialchars($volunteer_data_row['first_name']) ?></p>
                        <p><strong><?= __('Last Name:') ?></strong> <?= htmlspecialchars($volunteer_data_row['last_name']) ?></p>
                        <p><strong><?= __('Gender:') ?></strong> <?= htmlspecialchars(__($volunteer_data_row['gender'])) ?></p>
                        <p><strong><?= __('Date of Birth:') ?></strong> <?= htmlspecialchars(formatDate($volunteer_data_row['date_of_birth'])) ?></p>
                        <p><strong><?= __('Address:') ?></strong> <?= htmlspecialchars($volunteer_data_row['address']) ?></p>
                        <p><strong><?= __('Zip Code:') ?></strong> <?= htmlspecialchars($volunteer_data_row['zip_code']) ?></p>
                        <p><strong><?= __('Phone:') ?></strong> <?= htmlspecialchars($volunteer_data_row['telephone_number']) ?></p>
                        <p><strong><?= __('Email:') ?></strong> <?= htmlspecialchars($volunteer_data_row['email']) ?></p>
                    </div>

                    <!-- Volunteer Contributions -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;"><?= __('Volunteer Data') ?></h2>
                        <p><strong><?= __('Hours Required:') ?></strong> <span><?= htmlspecialchars($volunteer_data_row['hours_required'] . ' ' . __('Hours')) ?></span></p>
                        <p><strong><?= __('Hours Assigned:') ?></strong> <span><?= htmlspecialchars($volunteer_data_row['hours_completed'] . ' ' . __('Hours')) ?></span></p>
                        <p><strong><?= __('Points Left:') ?></strong> <span><?= htmlspecialchars($volunteer_data_row['points'] . ' ' . __('Points')) ?></span></p>
                        <p><strong><?= __('Volunteer Manager:') ?></strong> <?= htmlspecialchars($volunteer_data_row['volunteer_manager']) ?></p>
                        <p><strong><?= __('Entry Clerk:') ?></strong> <?= htmlspecialchars($volunteer_data_row['entry_clerk']) ?></p>
                    </div>

                    <!-- Interests -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;"><?= __('Interests') ?></h2>
                        <?php if (!empty($volunteer_interests_data_rows)): ?>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <?php foreach ($volunteer_interests_data_rows as $interest): ?>
                                    <li><?= htmlspecialchars(__($interest['interest']) ?: __('No specific interest provided')) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p><?= __('No interests provided.') ?></p>
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
                        foreach ($volunteer_availability_data_rows as $availability) {
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
                                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;"><?= __('Weekday') ?></th>
                                    <?php foreach ($time_periods as $time_period): ?>
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;"><?php echo htmlspecialchars(__($time_period)); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weekdays as $weekday): ?>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars(__($weekday)); ?></td>
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
                        <h2 style="font-size: 20px; color: #555;"><?= __('Additional Details') ?></h2>
                        <p><strong><?= __('Additional Notes:') ?></strong> 
                            <?php echo htmlspecialchars($volunteer_data_row['additional_notes']) ?: __('None'); ?>
                        </p>
                        <p><strong><?= __('Registration Date:') ?></strong> 
                            <?php echo htmlspecialchars(formatDate($volunteer_data_row['registration_date'])); ?>
                        </p>
                        <p><strong><?= __('Profile In Trash:') ?></strong> 
                            <?php echo htmlspecialchars($volunteer_data_row['trashed'] ? __('Yes') : __('No')); ?>
                        </p>
                    </div>
                </div>


                <!-- Right Area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget Display -->
                    <div id="medium_rectangle">

                        <!-- Toggle Buttons -->
                        <div id="widget_toggle_buttons">
                            <button id="recent_contracts_button" class="active" onclick="ToggleWidgets('contracts', this)">
                                <?= __('Latest Contracts') ?>
                            </button>
                            <button id="recent_purchases_button" onclick="ToggleWidgets('purchases', this)">
                                <?= __('Latest Purchases') ?>
                            </button>
                            <button id="recent_activities_button" onclick="ToggleWidgets('activities', this)">
                                <?= __('Registered Activities') ?>
                            </button>
                            <button id="matching_activities_button" onclick="ToggleWidgets('matching_activities', this)">
                                <?= __('Matching Activities') ?>
                            </button>
                        </div>

                        <!-- Display Contracts Widgets -->
                        <div id="contracts_widgets" class="widget-container">
                            <?php
                            if ($contracts_data_rows) {
                                foreach ($contracts_data_rows as $contract_data_row) {
                                    $contract_id = $contract_data_row['id'];
                                    $volunteer_data_row = fetch_volunteer_data_row($user_id,$contract_data_row['volunteer_id']);
                                    $date = new DateTime($contract_data_row['start_date']);
                                    $month = __($date->format('F'));
                                    include("../Widget_Pages/contract_widget.php");
                                }
                            }
                            ?>
                            <!-- All Volunteer Contracts Button -->
                            <div id="volunteer_specific_contracts_button" style="text-align: right; padding: 10px 20px; display: inline-block;">
                                <a href="../Listing_Pages/volunteer_specific_contracts.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
                                    <button name="volunteer_specific_contracts_button" id="submenu_button">
                                        <?= sprintf(__('See All %s\'s Contracts'), htmlspecialchars($volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'])) ?>
                                    </button>
                                </a>
                            </div>
                        </div>                    
                        
                        <!-- Display Purchase Widgets -->
                        <div id="purchases_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($purchases_data_rows) {
                                foreach ($purchases_data_rows as $purchase_data_row) {
                                    $purchase_id = $purchase_data_row['id'];
                                    $volunteer_data_row = fetch_volunteer_data_row($user_id, $purchase_data_row['volunteer_id']);
                                    include("../Widget_Pages/purchase_widget.php");
                                }
                            }
                            ?>
                            <!-- All Volunteer Purchases Button -->
                            <div id="volunteer_specific_purchases_button" style="text-align: right; padding: 10px 20px;">
                                <a href="../Listing_Pages/volunteer_specific_purchases.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
                                    <button name="volunteer_specific_purchases_button" id="submenu_button">
                                        <?= sprintf(__('See All %s\'s Purchases'), htmlspecialchars($volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'])) ?>
                                    </button>
                                </a>
                            </div>
                        </div>

                        <!-- Display Activities Widgets -->
                        <div id="activities_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($activities_data_rows) {
                                foreach ($activities_data_rows as $activity_data_row) {
                                    $activity_id = $activity_data_row['id'];
                                    $activity_time_periods_data_rows = fetch_data_rows("SELECT * FROM Activity_Time_Periods WHERE activity_id = '$activity_id'");
                                    $activity_domains_data_rows = fetch_data_rows("SELECT * FROM Activity_Domains WHERE activity_id = '$activity_id'");
                                    include("../Widget_Pages/activity_widget.php");
                                }
                            }
                            ?>
                            <!-- All Volunteer Activities Button (Initially hidden) -->
                            <div id="volunteer_specific_activities_button" style="text-align: right; padding: 10px 20px;">
                                <a href="../Listing_Pages/volunteer_specific_activities.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
                                    <button name="volunteer_specific_activities_button" id="submenu_button">
                                        <?= sprintf(__('See All %s\'s Activities'), htmlspecialchars($volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'])) ?>
                                    </button>
                                </a>
                            </div>
                        </div>

                        <!-- Display Matching Volunteers Widgets --> 
                        <div id="matching_activities_widgets" class="widget-container" style="display: none;">

                            <!-- Filter Form -->
                            <form id="filterForm" action="" method="post">
                                <!-- Interests Filter -->
                                <label class="switch">
                                    <input type="checkbox" name="interest_filter" <?= ($interest_filter ? 'checked' : ''); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span><?= __('Matching Interests') ?>
                                    <span class="hint">?
                                        <span class="hint-text"><?= __('If checked, only activities with matching interests with the volunteer will be shown.') ?></span>
                                    </span>
                                </span>
                                <br>

                                <!-- Weekday Filters -->
                                <label class="switch">
                                    <input type="checkbox" name="weekday_filter" <?= ($weekday_filter ? 'checked' : ''); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span><?= __('Matching Weekdays') ?>
                                    <span class="hint">?
                                        <span class="hint-text"><?= __('If checked, only activities taking place on the weekdays the volunteer is available will be shown.') ?></span>
                                    </span>
                                </span>
                                <br>

                                <!-- Time Period Filters -->
                                <label class="switch">
                                    <input type="checkbox" name="time_period_filter" <?= ($time_period_filter ? 'checked' : ''); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span><?= __('Matching Time Periods') ?>
                                    <span class="hint">?
                                        <span class="hint-text"><?= __('If checked, only activities with matching time periods with the volunteer will be shown.') ?></span>
                                    </span>
                                </span>
                                <br><br>

                                <!-- Submit Button -->
                                <div style="text-align: left;">
                                    <button type="submit" style="padding: 10px 20px; background-color: #405d9b; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                                        <?= __('Apply Filter') ?>
                                    </button>
                                </div>
                                <br>
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
                                $activity_match_count = count($all_matching_activities_data_rows);

                                if (empty($all_matching_activities_data_rows)) {
                                    echo __('No activities found.');
                                } else {
                                    echo sprintf(
                                        __('This volunteer has %d %s that match.'),
                                        $activity_match_count,
                                        $activity_match_count === 1 ? __('activity') : __('activities')
                                    );
                                }

                                // Display the widgets
                                if($all_matching_activities_data_rows){
                                    foreach($all_matching_activities_data_rows as $activity_data_row){
                                        $activity_id = $activity_data_row['id'];
                                        $activity_time_periods_data_rows = fetch_data_rows("SELECT * FROM Activity_Time_Periods WHERE activity_id = '$activity_id'");
                                        $activity_domains_data_rows = fetch_data_rows("SELECT * FROM Activity_Domains WHERE activity_id = '$activity_id'");
                                        include("../Widget_Pages/activity_widget.php");
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