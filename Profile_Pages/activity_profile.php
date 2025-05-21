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

    // Updating all backend processes
    update_backend_data();

    // Initializing marching volunteer filter variables
    $interest_filter = 'checked';
    $weekday_filter = 'checked';
    $time_period_filter = 'checked';

    // Default activity data
    if (isset($_GET['activity_id'])) {
        $activity_id = $_GET['activity_id'];

        // Collecting activity data (only 1 row needed)
        $activity_data_row = fetch_data_rows(
            "SELECT * FROM Activities
                    WHERE id = '$activity_id'"
        )[0];

        // Collecting activity time periods data
        $activity_time_periods_data_rows = fetch_data_rows(
            "SELECT * FROM Activity_Time_Periods
                    WHERE activity_id = '$activity_id'"
        );

        // Collecting activity domains data
        $activity_domains_data_rows = fetch_data_rows(
            "SELECT * FROM Activity_Domains
                    WHERE activity_id = '$activity_id'"
        );
    }

    // Getting the activity date
    $activity_date = $activity_data_row['activity_date'];

    // Getting the activity time periods in a string
    $time_periods = [];
    foreach ($activity_time_periods_data_rows as $activity_time_periods_data_row){
        $time_periods[] = $activity_time_periods_data_row['time_period'];
    }
    $activity_time_periods_sql = "'" . implode("', '", $time_periods) . "'";

    // Getting the activity domains in a string
    $domains = [];
    foreach ($activity_domains_data_rows as $activity_domains_data_row){
        $domains[] = $activity_domains_data_row['domain'];
    }
    $activity_domains_sql = "'" . implode("', '", $domains) . "'";
    
    $all_current_participants_data_rows = fetch_data_rows("
        SELECT DISTINCT v.* 
        FROM Volunteers v
        JOIN Volunteer_Activity_Junction vaj ON v.id = vaj.volunteer_id
        WHERE vaj.activity_id = '$activity_id'
        ORDER BY v.id DESC
    ");

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Retrieve filter form data
        $interest_filter = $_POST['interest_filter'] ?? '';
        $weekday_filter = $_POST['weekday_filter'] ?? '';
        $time_period_filter = $_POST['time_period_filter'] ?? '';
    }

    // Default sql query
    $sql_filter_query = "
        SELECT DISTINCT v.* 
        FROM Volunteers v
        JOIN Volunteer_Availability va ON v.id = va.volunteer_id
        JOIN Volunteer_Interests vi ON v.id = vi.volunteer_id
        WHERE v.trashed = 0
        AND v.user_id = '$user_id'
        AND v.hours_completed < v.hours_required
        AND EXISTS (
            SELECT 1
            FROM Contracts c
            WHERE c.volunteer_id = v.id
                AND '$activity_date' BETWEEN c.start_date AND c.end_date
        )  
    ";

    // Interest filter
    $sql_filter_query .= ($interest_filter ? " AND vi.interest IN ($activity_domains_sql) " : '');

    // Weekday filter
    $sql_filter_query .= ($weekday_filter ? " AND DAYNAME('$activity_date') = va.weekday " : '');

    // Time period filter
    $sql_filter_query .= ($time_period_filter ? " AND va.time_period IN ($activity_time_periods_sql) " : '');

    // Completing sql query
    $sql_filter_query .= " 
        AND NOT EXISTS (
            SELECT 1 FROM Volunteer_Activity_Junction vaj 
            WHERE vaj.volunteer_id = v.id 
            AND vaj.activity_id = '$activity_id'
        )
        ORDER BY v.id DESC";

    $all_matching_participants_data_rows = fetch_data_rows($sql_filter_query);


    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ensure the delete activity button has been pressed
        if (isset($_POST['delete_activity']) && $_POST['delete_activity'] === '1') {

            // SQL query into Purchases
            $trash_activity_query = "UPDATE `Activities` SET `trashed`='1' WHERE `id`='$activity_id'";
            $DB->save($trash_activity_query);

            // Changing the page.
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity_id);
            die; // Ending the script
        }

        // Ensure the restore activity button has been pressed
        if (isset($_POST['restore_activity']) && $_POST['restore_activity'] === '1') {

            // SQL query into Purchases
            $restore_activity_query = "UPDATE `Activities` SET `trashed`='0' WHERE `id`='$activity_id'";
            $DB->save($restore_activity_query);

            // Changing the page.
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity_id);
            die; // Ending the script
        }


        // Ensure the delete activity button has been pressed
        if (isset($_POST['assign_volunteer_activity']) && $_POST['assign_volunteer_activity'] === '1') {

            $volunteer_id = $_POST['volunteer_id'];

            // SQL query into Purchases
            $assign_volunteer_to_activity_query = "INSERT INTO Volunteer_Activity_Junction (user_id, volunteer_id, contract_id, activity_id) 
                                                    VALUES ('$user_id', '$volunteer_id', -1, '$activity_id')";
            $DB->save($assign_volunteer_to_activity_query);

            // Changing the page.
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity_id);
            die; // Ending the script
        }

        // Ensure the restore activity button has been pressed
        if (isset($_POST['unassign_volunteer_activity']) && $_POST['unassign_volunteer_activity'] === '1') {

            $volunteer_id = $_POST['volunteer_id'];

            // SQL query into Purchases
            $unassign_volunteer_from_activity_query = "DELETE FROM Volunteer_Activity_Junction 
                                                        WHERE volunteer_id = '$volunteer_id'
                                                        AND activity_id = '$activity_id'";
            $DB->save($unassign_volunteer_from_activity_query);

            // Changing the page.
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity_id);
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
        <title><?= __('CivicLink | Activity Profile') ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/style.css">
    </head>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <script src="../JavaScript/functions.js"></script>

        <!-- Cover Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Edit Activity Button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_activity_data.php?activity_id=<?php echo $activity_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">edit_document</span>
                        <span><?php echo __('Edit Activity Profile'); ?></span>
                    </button>
                </a>
            </div>

            <!-- Trash/Restore Activity -->
            <?php 
                // If the activity is not trashed, propose delete option
                if($activity_data_row['trashed'] == 0){
                    // Show delete button (default case)
            ?>
                <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                    <form method="POST" action="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" onsubmit="return confirm('<?php echo __('Are you sure you want to delete this profile? It will be placed in the trash.'); ?>')">
                        <button id="submenu_button">
                            <!-- Hidden input to confirm source -->
                            <input type="hidden" name="delete_activity" value="1">
                            <span class="material-symbols-outlined" style="margin-right: 8px;">delete</span>
                            <span><?php echo __('Trash Activity'); ?></span>
                        </button>
                    </form>
                </div>
            <?php
                } else {
                    // Propose restore option for trashed activity
            ?>
                <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                    <form method="POST" action="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" onsubmit="return confirm('<?php echo __('Are you sure you want to restore this profile from trash?'); ?>')">
                        <button id="submenu_button">
                            <!-- Hidden input to confirm source -->
                            <input type="hidden" name="restore_activity" value="1">
                            <span class="material-symbols-outlined" style="margin-right: 8px;">restore_from_trash</span>
                            <span><?php echo __('Restore Activity'); ?></span>
                        </button>
                    </form>
                </div>
                <?php
                }
            ?>

                    
            <!-- Below Cover Area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left Area; Activity Information Area -->
                <div id="medium_rectangle" style="flex:0.57;">

                    <!-- Section Title of Contact Section -->
                    <div id="section_title">
                        <span><?php echo __('Activity Profile'); ?></span>
                    </div>

                    <!-- Activity Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;"><?php echo __('Information'); ?></h2>

                        <!-- Activity Is Trashed -->
                        <?php if ($activity_data_row['trashed'] == 1): ?>
                            <span class="warning" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;"><?php echo __('delete'); ?></span>
                                <?php echo __('Activity is trashed.'); ?>
                            </span>
                        <?php else: ?>
                            <!-- Activity is Upcoming -->
                            <?php if ($activity_data_row['activity_date'] > date('Y-m-d')): ?>
                                <span class="upcoming" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                    <span class="material-symbols-outlined" style="margin-right: 5px;"><?php echo __('event_upcoming'); ?></span>
                                    <?php echo __('Upcoming activity.'); ?>
                                </span>
                            <?php endif; ?>

                            <!-- Activity is Today -->
                            <?php if ($activity_data_row['activity_date'] == date('Y-m-d')): ?>
                                <span class="today" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                    <span class="material-symbols-outlined" style="margin-right: 5px;"><?php echo __('today'); ?></span>
                                    <?php echo __('Activity is today.'); ?>
                                </span>
                            <?php endif; ?>

                            <!-- Activity is Past -->
                            <?php if ($activity_data_row['activity_date'] < date('Y-m-d')): ?>
                                <span class="valid" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                    <span class="material-symbols-outlined" style="margin-right: 5px;"><?php echo __('check_circle'); ?></span>
                                    <?php echo __('Activity is complete.'); ?>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <p><strong><?php echo __('Name:'); ?></strong> <?php echo htmlspecialchars($activity_data_row['activity_name']); ?></p>
                        <p><strong><?php echo __('Number of Places:'); ?></strong> 
                            <?php 
                                $count = $activity_data_row['number_of_places'];
                                echo htmlspecialchars($count) . ' ' . __($count == 1 ? 'Place' : 'Places'); 
                            ?>
                        </p>
                        <p><strong><?php echo __('Number of Participants:'); ?></strong> 
                            <?php 
                                $count = $activity_data_row['number_of_participants'];
                                echo htmlspecialchars($count) . ' ' . __($count == 1 ? 'Participant' : 'Participants'); 
                            ?>
                        </p>
                        <p><strong><?php echo __('Duration:'); ?></strong> 
                            <?php 
                                $duration = $activity_data_row['activity_duration'];
                                echo htmlspecialchars($duration) . ' ' . __($duration == 1 ? 'Hour' : 'Hours'); 
                            ?>
                        </p>
                        <p><strong><?php echo __('Date:'); ?></strong> <?php echo htmlspecialchars(formatDate($activity_data_row['activity_date'])); ?></p>
                        <p><strong><?php echo __('Location:'); ?></strong> 
                            <?php 
                                $location = $activity_data_row['activity_location'];
                                echo htmlspecialchars($location == "" ? __('Not Added') : $location); 
                            ?>
                        </p>
                        <p><strong><?php echo __('Entry Clerk:'); ?></strong> <?php echo htmlspecialchars($activity_data_row['entry_clerk']); ?></p>
                    </div>

                    <!-- Time Periods -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;"><?php echo __('Time Periods'); ?></h2>
                        <?php if (!empty($activity_time_periods_data_rows)): ?>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <?php foreach ($activity_time_periods_data_rows as $activity_time_periods_data_row): ?>
                                    <li><?php echo htmlspecialchars(__($activity_time_periods_data_row['time_period']) ?: __('No specific time period provided')); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p><?php echo __('No time period provided.'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Domains -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;"><?php echo __('Domains'); ?></h2>
                        <?php if (!empty($activity_domains_data_rows)): ?>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <?php foreach ($activity_domains_data_rows as $activity_domains_data_row): ?>
                                    <li><?php echo htmlspecialchars(__($activity_domains_data_row['domain']) ?: __('No specific interest provided')); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p><?php echo __('No domain provided.'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Additional Details -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;"><?php echo __('Additional Details'); ?></h2>
                        <p><strong><?php echo __('Additional Notes:'); ?></strong> <?php echo htmlspecialchars($activity_data_row['additional_notes']) ?: __('None'); ?></p>
                        <p><strong><?php echo __('Registration Date:'); ?></strong> <?php echo htmlspecialchars(formatDate($activity_data_row['registration_date'])); ?></p>
                        <p><strong><?php echo __('Profile In Trash:'); ?></strong> <?php echo htmlspecialchars($activity_data_row['trashed'] ? __('Yes') : __('No')); ?></p>
                    </div>
                </div>


                <!-- Right Area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget Display -->
                    <div id="medium_rectangle">

                        <!-- Toggle Buttons -->
                        <div id="widget_toggle_buttons">
                            <button id="show_participants_button" class="active" onclick="ToggleWidgets('current_participants', this)">
                                <?php echo __('Show Participants'); ?>
                            </button>
                            <button id="matching_volunteers_button" onclick="ToggleWidgets('matching_volunteers', this)">
                                <?php echo __('Show Matching Volunteers'); ?>
                            </button>
                        </div>

                        <!-- Display Participants Widgets --> 
                        <div id="current_participants_widgets" class="widget-container">
                            <?php
                                // Counting the number of elements post filter
                                if (empty($all_current_participants_data_rows)) {
                                    echo __('This activity doesn\'t have any participants yet.');
                                } else {
                                    $count = count($all_current_participants_data_rows);
                                    echo sprintf(
                                        __('This activity has %d %s.'),
                                        $count,
                                        ($count == 1) ? __('participant') : __('participants')
                                    );
                                }

                                // Display the widgets
                                if ($all_current_participants_data_rows) {
                                    foreach ($all_current_participants_data_rows as $volunteer_data_row) {
                                        $volunteer_id = $volunteer_data_row['id'];
                                        $interest_data_rows = fetch_volunteer_interest_data_rows($user_id, $volunteer_id);
                                        $availability_data_rows = fetch_volunteer_availability_data_rows($user_id, $volunteer_id);
                                        include("../Widget_Pages/volunteer_widget.php");
                                    }
                                }
                            ?>
                        </div>

                        <!-- Display Matching Volunteers Widgets --> 
                        <div id="matching_volunteers_widgets" class="widget-container" style="display: none;">

                            <!-- Filter Form -->
                            <form id="filterForm" action="" method="post">

                                <!-- Interests Filter -->
                                <label class="switch">
                                    <input type="checkbox" name="interest_filter" <?php echo ($interest_filter ? 'checked' : ''); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span><?php echo __('Matching Interests'); ?>
                                    <span class="hint">?
                                        <span class="hint-text"><?php echo __('If checked, only volunteers with matching interests with the activity will be shown.'); ?></span>
                                    </span>
                                </span>
                                <br>

                                <!-- Weekday Filter -->
                                <label class="switch">
                                    <input type="checkbox" name="weekday_filter" <?php echo ($weekday_filter ? 'checked' : ''); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span><?php echo __('Matching Weekdays'); ?>
                                    <span class="hint">?
                                        <span class="hint-text"><?php echo __('If checked, only volunteers available on the same weekday as the activity will be shown.'); ?></span>
                                    </span>
                                </span>
                                <br>

                                <!-- Time Period Filter -->
                                <label class="switch">
                                    <input type="checkbox" name="time_period_filter" <?php echo ($time_period_filter ? 'checked' : ''); ?>>
                                    <span class="slider round"></span>
                                </label>
                                <span><?php echo __('Matching Time Periods'); ?>
                                    <span class="hint">?
                                        <span class="hint-text"><?php echo __('If checked, only volunteers available on the same time periods as the activity will be shown.'); ?></span>
                                    </span>
                                </span>
                                <br>

                                <!-- Submit Button -->
                                <div style="text-align: center;">
                                    <button name="apply_filter" type="submit" style="padding: 10px 20px; background-color: #405d9b; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                                        <?php echo __('Apply Filter'); ?>
                                    </button>
                                </div>
                            </form>

                            <!-- Show Matching Volunteers If POST -->
                            <?php
                                // After your form processing logic, add this PHP code
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_filter'])) {
                                    echo '<script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var matchingButton = document.getElementById("matching_volunteers_button");                                        
                                        ToggleWidgets("matching_volunteers", matchingButton);
                                    });
                                    </script>';
                                }
                            ?>
                            
                            <?php
                                // Counting the number of elements post filter
                                if (empty($all_matching_participants_data_rows)) {
                                    echo __('No volunteers found.');
                                } else {
                                    $count = count($all_matching_participants_data_rows);
                                    echo __('This activity has') . ' ' . $count . ' ' . 
                                        ($count == 1 
                                            ? __('volunteer that matches.') 
                                            : __('volunteers that match.'));
                                }
                                ?>

                                <span class="hint">?
                                    <span class="hint-text"><?php echo __(__('Only volunteers with active contracts will be available to match with an activity.')); ?></span>
                                </span>

                                <?php

                                // Display the widgets
                                if ($all_matching_participants_data_rows) {
                                    foreach ($all_matching_participants_data_rows as $volunteer_data_row) {
                                        $volunteer_id = $volunteer_data_row['id'];
                                        $interest_data_rows = fetch_volunteer_interest_data_rows($user_id, $volunteer_id);
                                        $availability_data_rows = fetch_volunteer_availability_data_rows($user_id, $volunteer_id);
                                        include("../Widget_Pages/volunteer_widget.php");
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