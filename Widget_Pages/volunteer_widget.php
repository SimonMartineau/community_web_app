<!-- Imports -->
<link rel="stylesheet" href="/CivicLink_Web_App/Styles/style.css">
<script src="../JavaScript/functions.js"></script>

<!-- PHP Code -->
<?php

    // Check if widget is in activity profile or not
    if (isset($_GET['activity_id'])) {
        $activity_id = $_GET['activity_id'];

        // Widget is in activity profile, show assign button.
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
    } else {
        // Widget is not in activity profile, do not show assign button.
        $show_assign_button = false;
    }
?>



<!-- HTML Code -->
<a href="/CivicLink_Web_App/Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
    <div id="widget">
        <div class="widget_row">

            <!-- Volunteer Icon -->
            <div class="icon_container">
                <span class="material-symbols-outlined">person</span>
            </div>

            <!-- Volunteer Name -->
            <div class="name_container">
                <span class="widget_name"><?php echo $volunteer_data_row['first_name'] . " " . $volunteer_data_row['last_name'] ?></span>
            </div>

            <!-- Volunteer Basic Info -->
            <div class="info_container">
                <p class="widget_info">
                    <!-- Volunteer Points Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value"><?= $volunteer_data_row['points'] ?> <?= __('Points Left') ?></span>
                    </span>

                    <!-- Volunteer Hours Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">schedule</span></span>
                        <span class="info_value"><?= $volunteer_data_row['hours_completed'] ?>/<?= $volunteer_data_row['hours_required'] ?> <?= __('Hours Assigned') ?></span>
                    </span>
                </p>
            </div>

            <!-- Volunteer Status -->
            <div class="status_container">
                <p class="widget_info">
                    <?php if ($volunteer_data_row['trashed'] == 1): ?>
                        <span class="info_line warning"><span class="material-symbols-outlined">delete</span> <?= __('Profile is trashed.') ?></span>
                    <?php endif; ?>
                    <?php if ($volunteer_data_row['points'] < 0): ?>
                        <span class="info_line warning"><span class="material-symbols-outlined">warning</span> <?= __('Profile has errors.') ?></span>
                    <?php endif; ?>
                    <?php if ($volunteer_data_row['hours_required'] == 0): ?>
                        <span class="info_line caution"><span class="material-symbols-outlined">info</span> <?= __('No contract in place.') ?></span>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Assign / Unassign Buttons -->
            <?php if ($show_assign_button): ?>
                <?php if ($volunteer_activity_assigned): ?>
                    <div style="text-align:right; padding:10px 20px; display:inline-block;">
                        <form method="POST" action="../Profile_Pages/activity_profile.php?activity_id=<?= $activity_id ?>"
                            onsubmit="return confirm('<?= __('Are you sure you want to unassign') ?> <?= $volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'] ?> <?= __('from') ?> <?= $activity_data_row['activity_name'] ?>?')">
                            <button class="widget_button">
                                <input type="hidden" name="unassign_volunteer_activity" value="1">
                                <input type="hidden" name="volunteer_id" value="<?= $volunteer_id ?>">
                                <?= __('Unassign Volunteer from Activity') ?>
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div style="text-align:right; padding:10px 20px; display:inline-block;">
                        <form method="POST" action="../Profile_Pages/activity_profile.php?activity_id=<?= $activity_id ?>"
                            onsubmit="return confirm('<?= __('Are you sure you want to assign') ?> <?= $volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'] ?> <?= __('to') ?> <?= $activity_data_row['activity_name'] ?>?')">
                            <button class="widget_button">
                                <input type="hidden" name="assign_volunteer_activity" value="1">
                                <input type="hidden" name="volunteer_id" value="<?= $volunteer_id ?>">
                                <?= __('Assign Volunteer to Activity') ?>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <button class="widget_button" style="max-width:30px;" type="button" onclick="toggleDetails(event, '<?= $volunteer_id ?>')">
                <?= __('More Details') ?>
            </button>
        </div>

        <!-- Extra Details Row -->
        <div id="extra_details_row-<?php echo $volunteer_id; ?>" class="widget_row" style="display: none; align-items: flex-start;">
            <div class="widget_section">
                <h2 style="font-size:20px; color:#555;"><?= __('Volunteer Info') ?></h2>
                <p class="widget_info">
                    <!-- Volunteer Address Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">home</span></span>
                        <span class="info_value"><?= __('Address:') ?> <?= htmlspecialchars($volunteer_data_row['address']) ?></span>
                    </span>

                    <!-- Volunteer ZIP-Code Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">globe_location_pin</span></span>
                        <span class="info_value"><?= __('Zip-code:') ?> <?= htmlspecialchars($volunteer_data_row['zip_code']) ?></span>
                    </span>

                    <!-- Volunteer Phone Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">call</span></span>
                        <span class="info_value"><?= __('Phone:') ?> <?= htmlspecialchars($volunteer_data_row['telephone_number']) ?></span>
                    </span>

                    <!-- Volunteer Email Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">mail</span></span>
                        <span class="info_value"><?= __('Email:') ?> <?= htmlspecialchars($volunteer_data_row['email']) ?></span>
                    </span>

                    <!-- Volunteer Manager Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">support_agent</span></span>
                        <span class="info_value"><?= __('Volunteer Manager:') ?> <?= htmlspecialchars($volunteer_data_row['volunteer_manager']) ?></span>
                    </span>

                    <!-- Volunteer Contract Notification -->
                    <?php if ($volunteer_data_row['hours_required'] > 0): ?>
                        <span class="info_line valid"><span class="material-symbols-outlined">info</span> <?= __('Volunteer currently has a contract.') ?></span>
                    <?php endif; ?>
                </p>

                <!-- Volunteer Notifications -->
                <?php if ($volunteer_data_row['hours_required'] == 0 || $volunteer_data_row['points'] < 0): ?>
                    <h2 style="font-size:20px; color:#555;"><?= __('Notifications') ?></h2>
                <?php endif; ?>
                <p>
                    <!-- Volunteer Hasn't Contract Warning -->
                    <?php if ($volunteer_data_row['hours_required'] == 0): ?>
                        <span class="info_line caution"><span class="material-symbols-outlined">info</span> <?= __('Volunteer doesn\'t currently have a contract.') ?></span>
                    <?php endif; ?>

                    <!-- Volunteer Points Spent Warning -->
                    <?php if ($volunteer_data_row['points'] < 0): ?>
                        <span class="info_line warning"><span class="material-symbols-outlined">warning</span> <?= __('Volunteer has spent too many points.') ?></span>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Volunteer Interests -->
            <div class="widget_section">
                <h2 style="font-size:20px; color:#555;"><?= __('Interests') ?></h2>
                <?php if (!empty($interest_data_rows)): ?>
                    <ul style="list-style-type:disc; padding-left:20px;">
                        <?php foreach ($interest_data_rows as $interest): ?>
                            <li><?= htmlspecialchars(__($interest['interest']) ?: __('No specific interest provided')) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><?= __('No interests provided.') ?></p>
                <?php endif; ?>
            </div>

            <!-- Volunteer Availability -->
            <div class="widget_section">
                <h2 style="font-size:20px; color:#555;"><?= __('Weekly Availability') ?></h2>
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
                    
                    // Populate the matrix based on availability_data_rows
                    foreach ($availability_data_rows as $availability) {
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
            
        </div>
    </div>
</a>