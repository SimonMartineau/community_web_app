<a href="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
    <div id="widget" class="volunteer_widget">

        <div class="icon_container" style="font-size: 1.5em; color: #405d9b; font-weight: 600;">
            <span class="material-symbols-outlined">person</span>
        </div>

        <div class="name_container">
            <span class="widget_name"><?php echo $volunteer_data_row['first_name'] . " " . $volunteer_data_row['last_name'] ?></span>
        </div>
        <div class="info_container">
            <p class="widget_info">
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                    <span class="info_value"><?php echo $volunteer_data_row['points'] ?> Points Left</span>
                </span>
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">schedule</span></span>
                    <span class="info_value"><?php echo $volunteer_data_row['hours_completed'] ?>/<?php echo $volunteer_data_row['hours_required'] ?> Hours Completed</span>
                </span>
            </p>
        </div>

        <div class="status_container">
            <p class="widget_info">
                <?php if ($volunteer_data_row['hours_required'] == 0): ?>
                    <span class="info_line warning"><span class="material-symbols-outlined">warning</span> Warning: Volunteer doesn't currently have a check.</span>
                <?php endif; ?>
                <?php if ($volunteer_data_row['points'] < 0): ?>
                    <span class="info_line warning"><span class="material-symbols-outlined">warning</span> Warning: Volunteer has spent too many points.</span>
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