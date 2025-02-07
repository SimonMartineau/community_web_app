<a href="../Profile_Pages/check_profile.php?check_id=<?php echo $check_id; ?>" style="text-decoration: none;">
    <div id="widget" class="volunteer_widget">

        <div class="icon_container" style="font-size: 1.5em; color: #405d9b; font-weight: 600;">
            <span class="material-symbols-outlined">contract</span>
        </div>

        <div class="name_container">
            <span class="widget_name"><?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s "?> <br><?php echo $month . " Check" ?></span>
        </div>
        <div class="info_container">
            <p class="widget_info">
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                    <span class="info_value"><?php echo formatDate($check_data_row['issuance_date'])?> To <br> <?php echo formatDate($check_data_row['validity_date'])?></span>
                </span>
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                    <span class="info_value"><?php echo $check_data_row['points_spent'] ?>/<?php echo $check_data_row['points_deposit'] ?> Points Left</span>
                </span>
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">schedule</span></span>
                    <span class="info_value"><?php echo $check_data_row['hours_completed'] ?>/<?php echo $check_data_row['hours_required'] ?> Hours Completed</span>
                </span>
            </p>
        </div>

        <div class="status_container">
            <p class="widget_info">
                <?php if ($check_data_row['check_active'] == 1): ?>
                    <span style="color: green; font-weight: bold;"><span class="material-symbols-outlined">check_circle</span> Active Check</span>
                <?php elseif($check_data_row['check_active'] == 0): ?>
                        <span style="color: orange; font-weight: bold;"><span class="material-symbols-outlined">do_not_disturb_on</span> Past Check</span>
                <?php endif; ?>
                
                <?php if ($check_data_row['points_deposit'] - $check_data_row['points_spent'] < 0): ?>
                    <span class="info_line warning"><span class="material-symbols-outlined">warning</span>Volunteer has spent too many points.</span>
                <?php endif; ?>
            </p>
        </div>
        
    </div>
</a>


<style>
.material-symbols-outlined {
    display: inline-flex; /* Important for icon alignment */
    align-items: center;
    justify-content: center;
    font-size: 1.5em; /* Match icon size to text */
    vertical-align: middle;
}

</style>