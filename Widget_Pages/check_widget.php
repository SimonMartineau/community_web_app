<a href="../Profile_Pages/check_profile.php?check_id=<?php echo $check_id; ?>" style="text-decoration: none;">
    <div id="widget" class="volunteer_widget">
        <div class="name_container">
            <span class="widget_name"><span class="material-symbols-outlined">contract</span><?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s "?> <br><?php echo $month . " Check" ?></span>
        </div>
        <div class="info_container">
            <p class="widget_info">
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                    <span class="info_value"><?php echo formatDate($check_data_row['issuance_date'])?> To <?php echo formatDate($check_data_row['validity_date'])?></span>
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

        <div class="info_container">
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
.volunteer_widget {
    display: flex;
    align-items: center;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.volunteer_widget:hover {
    transform: translateY(-2px);
}

.name_container {
    padding-right: 25px;
    margin-right: 25px;
    border-right: 2px solid #eee;
    width: 300px;   
}

.widget_name {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2c3e50;
    letter-spacing: 0.5px;
    width: 100%; /* Allow it to take full width */
    word-wrap: break-word; /* Ensures long words wrap */
    overflow-wrap: break-word; /* Alternative for better browser support */
    white-space: normal; /* Allow wrapping */
}

.info_container {
    width: 300px;
}

.widget_info {
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info_line {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.95rem;
}

.material-symbols-outlined {
    display: inline-flex; /* Important for icon alignment */
    align-items: center;
    justify-content: center;
    font-size: 1.5em; /* Match icon size to text */
    vertical-align: middle;
}

.info_label {
    font-weight: 500;
    color: #7f8c8d;
    min-width: 20px;
}

.info_value {
    color: #2c3e50;
    font-weight: 500;
}

.warning {
    color: #e24141;
    font-weight: 500;
}

.warning i {
    margin-right: 8px;
    font-size: 0.9em;
}

</style>