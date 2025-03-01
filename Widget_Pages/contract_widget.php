<!-- Imports -->
<link rel="stylesheet" href="../style.css">
<script src="../functions.js"></script>

<!-- HTML Code -->
<a href="../Profile_Pages/contract_profile.php?contract_id=<?php echo $contract_id; ?>" style="text-decoration: none;">
    <div id="widget">
        <div class="widget_row">

            <!-- Contract Icon -->
            <div class="icon_container">
                <span class="material-symbols-outlined">contract</span>
            </div>

            <!-- Contract Name -->
            <div class="name_container">
                <span class="widget_name"><?php echo $volunteer_data_row['first_name'] . " " . $volunteer_data_row['last_name'] . "'s "?> <br><?php echo $month . " Contract" ?></span>
            </div>

            <!-- Contract Basic Info -->
            <div class="info_container">
                <p class="widget_info">
                    <!-- Contract Dates Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                        <span class="info_value"><?php echo formatDate($contract_data_row['issuance_date'])?> - <br> <?php echo formatDate($contract_data_row['validity_date'])?></span>
                    </span>

                    <!-- Contract Points Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value"><?php echo $contract_data_row['points_deposit'] - $contract_data_row['points_spent'] ?>/<?php echo $contract_data_row['points_deposit'] ?> Points Left</span>
                    </span>

                    <!-- Contract Hours Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">schedule</span></span>
                        <span class="info_value"><?php echo $contract_data_row['hours_completed'] ?>/<?php echo $contract_data_row['hours_required'] ?> Hours Completed</span>
                    </span>
                </p>
            </div>

            <!-- Contract Status -->
            <div class="status_container">
                <p class="widget_info">
                    <!-- Contract Active/Inactive Status -->
                    <?php if ($contract_data_row['contract_active'] == 1): ?>
                        <span class="info_line valid">
                            <span class="material-symbols-outlined">check_circle</span>
                            Contract is Active
                        </span>
                    <?php elseif ($contract_data_row['contract_active'] == 0): ?>
                        <span class="info_line caution">
                            <span class="material-symbols-outlined">do_not_disturb_on</span> 
                            Past Contract
                        </span>
                    <?php endif; ?>
                    
                    <!-- Contract Points Spent Warning -->
                    <?php if ($contract_data_row['points_deposit'] - $contract_data_row['points_spent'] < 0): ?>
                        <span class="info_line warning">
                            <span class="material-symbols-outlined">warning</span>
                            Volunteer has spent too many points.
                        </span>
                    <?php endif; ?>
                </p>
            </div>
            
        </div>
    </div>
</a>