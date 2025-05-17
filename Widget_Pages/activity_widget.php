<!-- Imports -->
<link rel="stylesheet" href="../Styles/style.css">
<script src="../JavaScript/functions.js"></script>

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
                <span class="material-symbols-outlined">volunteer_activism</span>
            </div>

            <!-- Activity Name -->
            <div class="name_container">
                <span class="widget_name"><?= htmlspecialchars($activity_data_row['activity_name']) ?></span>
            </div>

            <!-- Activity Basic Info -->
            <div class="info_container">
                <p class="widget_info">
                    <!-- Activity Dates Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                        <span class="info_value"><?= __('Date:') ?> <?= formatDate($activity_data_row['activity_date']) ?></span>
                    </span>

                    <!-- Activity Occupancy Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">person</span></span>
                        <span class="info_value"><?= $activity_data_row['number_of_participants'] ?>/<?= $activity_data_row['number_of_places'] ?> <?= __('Participants') ?></span>
                    </span>
                </p>
            </div>

            <!-- Activity Status Info -->
            <div class="status_container">
                <p class="widget_info">
                    <?php if ($activity_data_row['trashed'] == 1): ?>
                        <span class="info_line warning">
                            <span class="material-symbols-outlined">delete</span> <?= __('Activity is trashed.') ?>
                        </span>
                    <?php else: ?>
                        <?php
                            $today = date('Y-m-d');
                        ?>
                        <!-- Activity is Upcoming -->
                        <?php if ($activity_data_row['activity_date'] > $today): ?>
                            <span class="info_line upcoming">
                                <span class="material-symbols-outlined">event_upcoming</span>
                                <?= __('Upcoming activity.') ?>
                            </span>
                        <?php endif; ?>

                        <!-- Activity is Today -->
                        <?php if ($activity_data_row['activity_date'] == $today): ?>
                            <span class="info_line today">
                                <span class="material-symbols-outlined">today</span>
                                <?= __('Activity is today.') ?>
                            </span>
                        <?php endif; ?>

                        <!-- Activity is Past -->
                        <?php if ($activity_data_row['activity_date'] < $today): ?>
                            <span class="info_line valid">
                                <span class="material-symbols-outlined">check_circle</span>
                                <?= __('Activity is complete.') ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Assign/Unassign Button -->
            <?php if ($show_assign_button): ?>
                <?php if ($volunteer_activity_assigned): ?>
                    <!-- Show Unassign Button -->
                    <div style="text-align:right; padding:10px 20px; display:inline-block;">
                        <form method="POST" action="../Profile_Pages/volunteer_profile.php?volunteer_id=<?= $volunteer_id ?>"
                            onsubmit="return confirm('<?= __('Are you sure you want to unassign') ?> <?= $volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'] ?> <?= __('from') ?> <?= $activity_data_row['activity_name'] ?>?')">
                            <button class="widget_button">
                                <input type="hidden" name="unassign_volunteer_activity" value="1">
                                <input type="hidden" name="activity_id" value="<?= $activity_id ?>">
                                <?= __('Unassign Volunteer from Activity') ?>
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Show Assign Button -->
                    <div style="text-align:right; padding:10px 20px; display:inline-block;">
                        <form method="POST" action="../Profile_Pages/volunteer_profile.php?volunteer_id=<?= $volunteer_id ?>"
                            onsubmit="return confirm('<?= __('Are you sure you want to assign') ?> <?= $volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'] ?> <?= __('to') ?> <?= $activity_data_row['activity_name'] ?>?')">
                            <button class="widget_button">
                                <input type="hidden" name="assign_volunteer_activity" value="1">
                                <input type="hidden" name="activity_id" value="<?= $activity_id ?>">
                                <?= __('Assign Volunteer to Activity') ?>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <button class="widget_button" style="max-width:30px;" type="button" onclick="toggleDetails(event, '<?= $activity_id ?>')">
                <?= __('More Details') ?>
            </button>
        </div>

        <!-- Extra Details Row -->
        <div id="extra_details_row-<?php echo $activity_id; ?>" class="widget_row" style="display: none; align-items: flex-start;">
            <div class="widget_section">
                <h2 style="font-size: 20px; color: #555;"><?= __('Activity Info') ?></h2>
                <p class="widget_info">
                    <!-- Activity Duration -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">timer</span></span>
                        <span class="info_value"><?= __('Duration: ') ?><?php echo $activity_data_row['activity_duration'] . " " .__('Hours')?></span>
                    </span>

                    <!-- Activity Location -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">location_on</span></span>
                        <span class="info_value"><?= __('Location: ') ?><?php echo (($activity_data_row['activity_location']=="")? __('Not added'): $activity_data_row['activity_location']) ?></span>
                    </span>
                </p>
            </div>

            <!-- Activity Domains -->
            <div class="widget_section">
                <h2 style="font-size: 20px; color: #555;"><?= __('Domains') ?></h2>
                <?php if (!empty($activity_domains_data_rows)): ?>
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        <?php foreach ($activity_domains_data_rows as $activity_domains_data_row): ?>
                            <li><?php echo htmlspecialchars(__($activity_domains_data_row['domain']) ?: __('No specific interest provided')); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><?= __('No domain provided.') ?></p>
                <?php endif; ?>
            </div>

            <!-- Activity Time Periods -->
            <div class="widget_section">
                <h2 style="font-size: 20px; color: #555;"><?= __('Time Periods') ?></h2>
                <?php if (!empty($activity_time_periods_data_rows)): ?>
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        <?php foreach ($activity_time_periods_data_rows as $activity_time_periods_data_row): ?>
                            <li><?php echo htmlspecialchars(__($activity_time_periods_data_row['time_period']) ?: __('No specific time period provided')); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><?= __('No time period provided.') ?></p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</a>