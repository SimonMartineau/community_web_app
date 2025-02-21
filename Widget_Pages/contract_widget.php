<link rel="stylesheet" href="../style.css">
<script src="../functions.js"></script>

<a href="../Profile_Pages/contract_profile.php?contract_id=<?php echo $contract_id; ?>" style="text-decoration: none;">
    <div id="widget">
        <div class="widget_row">

            <div class="icon_container">
                <span class="material-symbols-outlined">contract</span>
            </div>

            <div class="name_container">
                <span class="widget_name"><?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s "?> <br><?php echo $month . " Contract" ?></span>
            </div>

            <div class="info_container">
                <p class="widget_info">
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                        <span class="info_value"><?php echo formatDate($contract_data_row['issuance_date'])?> - <br> <?php echo formatDate($contract_data_row['validity_date'])?></span>
                    </span>
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value"><?php echo $contract_data_row['points_deposit'] - $contract_data_row['points_spent'] ?>/<?php echo $contract_data_row['points_deposit'] ?> Points Left</span>
                    </span>
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">schedule</span></span>
                        <span class="info_value"><?php echo $contract_data_row['hours_completed'] ?>/<?php echo $contract_data_row['hours_required'] ?> Hours Completed</span>
                    </span>
                </p>
            </div>

            <div class="status_container">
                <p class="widget_info">
                    <?php if ($contract_data_row['contract_active'] == 1): ?>
                        <span style="color: green; font-weight: bold;"><span class="material-symbols-outlined">check_circle</span> Active Contract</span>
                    <?php elseif($contract_data_row['contract_active'] == 0): ?>
                            <span style="color: orange; font-weight: bold;"><span class="material-symbols-outlined">do_not_disturb_on</span> Past Contract</span>
                    <?php endif; ?>
                    
                    <?php if ($contract_data_row['points_deposit'] - $contract_data_row['points_spent'] < 0): ?>
                        <span class="info_line warning"><span class="material-symbols-outlined">warning</span>Volunteer has spent too many points.</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</a>