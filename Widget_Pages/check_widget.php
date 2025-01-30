<a href="../Profile_Pages/check_profile.php?check_id=<?php echo $check_id; ?>" style="text-decoration: none;">
    <div id="widget" class="check_widget">
        <h3 class="widget_name"><?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s " . $month . " Check" ?></h3>
        <p class="widget_info">
            <strong>Issuance date:</strong> <?php echo formatDate($check_data_row['issuance_date'])?><br>
            <strong>Validity date:</strong> <?php echo formatDate($check_data_row['validity_date'])?><br>
            <strong>Points deposit:</strong> <?php echo $check_data_row['points_deposit'] . " Points"?><br>
            <strong>Points spent:</strong> <?php echo $check_data_row['points_spent'] . " Points"?><br>
            <strong>Hours required:</strong> <?php echo $check_data_row['hours_required'] . " Hours"?><br>
            <strong>Hours completed:</strong> <?php echo $check_data_row['hours_completed'] . " Hours"?><br>
            <?php if ($check_data_row['check_active'] == 1): ?>
                <span style="color: green; font-weight: bold;">Current Volunteer's Check</span><br>
            <?php else: ?>
                <span style="color: orange; font-weight: bold;">Past Volunteer's Check</span><br>
            <?php endif; ?>
            <?php if ($check_data_row['points_deposit'] - $check_data_row['points_spent'] < 0): ?>
                <strong style="color: rgb(226, 65, 65); width: 100%;">Warning: Volunteer has spent too many points for this check.</strong><br>
            <?php endif; ?>
        </p>
    </div>
</a>