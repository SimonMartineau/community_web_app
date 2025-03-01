<!-- Imports -->
<link rel="stylesheet" href="../style.css">
<script src="../functions.js"></script>

<!-- PHP Code -->
<?php

    // Check if widget is in volunteer profile or not
    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];

        // Widget is in volunteer profile so show the assign button.
        $show_assign_button = true;

        // Checking if volunteer is assigned to activity
        $volunteer_activity_match_data_rows = fetch_data_rows(
            "SELECT * FROM Volunteer_Activity_Junction
                    WHERE volunteer_id = '$volunteer_id'
                    AND activity_id = '$activity_id'"
        );

        // Storing volunteer activity junction status in a variable
        if (!empty($volunteer_activity_match_data_rows)) {
            // Junction exists
            $volunteer_activity_assigned = true;
        } else{
            // Junction does not exist
            $volunteer_activity_assigned = false;
        }
    } else{
        // Widget is not in volunteer profile so do not show assign button.
        $show_assign_button = false;
    }
?>



<!-- HTML Code -->
<a href="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" style="text-decoration: none;">
    <div id="widget">
        <div class="widget_row">

            <!-- Activity Icon -->
            <div class="icon_container">
                <span class="material-symbols-outlined">construction</span>
            </div>

            <!-- Activity Name -->
            <div class="name_container">
                <span class="widget_name"><?php echo $activity_data_row['activity_name'] ?></span>
            </div>
            
            <!-- Activity Basic Info -->
            <div class="info_container">
                <p class="widget_info">
                    <!-- Activity Dates Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                        <span class="info_value"><?php echo "Date : " . formatDate($activity_data_row['activity_date'])?></span>
                    </span>

                    <!-- Activity Occupancy Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">person</span></span>
                        <span class="info_value"><?php echo $activity_data_row['number_of_participants']?>/<?php echo $activity_data_row['number_of_places']?> Participants</span>
                    </span>
                </p>
            </div>

            <!-- Activity Status Info -->
            <div class="status_container">
                <p class="widget_info">
                    <!-- Activity Is Trashed -->
                    <?php if ($activity_data_row['trashed'] == 1): ?>
                        <span class="info_line warning">
                            <span class="material-symbols-outlined">delete</span> Activity is trashed.
                        </span>
                    <?php else: ?>
                        <!-- Activity is Upcoming -->
                        <?php if ($activity_data_row['activity_date'] > date('Y-m-d')): ?>
                            <span class="info_line valid" style="color: green;">
                                <span class="material-symbols-outlined">check_circle</span>
                                    Upcoming Activity
                            </span>
                        <?php endif; ?>

                        <!-- Activity is Today -->
                        <?php if ($activity_data_row['activity_date'] == date('Y-m-d')): ?>
                            <span class="info_line valid" style="color: green;">
                                <span class="material-symbols-outlined">check_circle</span>
                                    Activity is Today
                            </span>
                        <?php endif; ?>

                        <!-- Activity is Past -->
                        <?php if ($activity_data_row['activity_date'] < date('Y-m-d')): ?>
                            <span class="info_line caution">
                                <span class="material-symbols-outlined">do_not_disturb_on</span>
                                    Past Activity
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Assign/Unassign Button -->
            <?php
                if ($show_assign_button == false) {
                    // Do not show the assign/unassign buttons
                } else {
                    // Show the assign/unassign buttons

                    // If the volunteer is assigned to the activity
                    if($volunteer_activity_assigned == true){
                        // Show the unassign button
            ?>
                    <!-- Show Unassign Button -->
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" onsubmit="return confirm('Are you sure you want to unassign <?php echo $volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'] . ' from ' . $activity_data_row['activity_name']?>?')">
                            <button class="widget_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="unassign_volunteer_activity" value="1">
                                <!-- Hidden input to send activity_id -->
                                <input type="hidden" name="activity_id" value="<?php echo $activity_id; ?>">
                                Unassign Volunteer from Activity
                            </button>
                        </form>
                    </div>

                <?php
                } elseif ($volunteer_activity_assigned == false){
                // Show the assign button
                ?>

                    <!-- Show Assign Button -->
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" onsubmit="return confirm('Are you sure you want to assign <?php echo $volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'] . ' to ' . $activity_data_row['activity_name']?>?')">
                            <button class="widget_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="assign_volunteer_activity" value="1">
                                <!-- Hidden input to send activity_id -->
                                <input type="hidden" name="activity_id" value="<?php echo $activity_id; ?>">
                                Assign Volunteer to Activity
                            </button>
                        </form>
                    </div>

                <?php
                    }
                }
            ?>

            <!-- Button placed inside the widget. We call stopPropagation() in the onclick to avoid triggering the link. -->
            <button class="widget_button" style="max-width: 30px;" type="button" onclick="toggleDetails(event, '<?php echo $activity_id; ?>')">
                More Details
            </button>
        </div>

        <!-- Extra Details Row -->
        <div id="extra_details_row-<?php echo $activity_id; ?>" class="widget_row" style="display: none; align-items: flex-start;">
            <div class="widget_section">
                <h2 style="font-size: 20px; color: #555;">Activity Info</h2>
                <p class="widget_info">
                    <!-- Activity Duration -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">timer</span></span>
                        <span class="info_value">Duration: <?php echo $activity_data_row['activity_duration'] . " Hours"?></span>
                    </span>

                    <!-- Activity Location -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">location_on</span></span>
                        <span class="info_value">Location: <?php echo (($activity_data_row['activity_location']=="")? 'Not added': $activity_data_row['activity_location']) ?></span>
                    </span>
                </p>
            </div>

            <!-- Activity Domains -->
            <div class="widget_section">
                <h2 style="font-size: 20px; color: #555;">Domains</h2>
                <?php if (!empty($activity_domains_data_rows)): ?>
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        <?php foreach ($activity_domains_data_rows as $activity_domains_data_row): ?>
                            <li><?php echo htmlspecialchars($activity_domains_data_row['domain'] ?: 'No specific interest provided'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No domain provided.</p>
                <?php endif; ?>
            </div>

            <!-- Activity Time Periods -->
            <div class="widget_section">
                <h2 style="font-size: 20px; color: #555;">Time Periods</h2>
                <?php if (!empty($activity_time_periods_data_rows)): ?>
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        <?php foreach ($activity_time_periods_data_rows as $activity_time_periods_data_row): ?>
                            <li><?php echo htmlspecialchars($activity_time_periods_data_row['time_period'] ?: 'No specific time period provided'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No time period provided.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</a>