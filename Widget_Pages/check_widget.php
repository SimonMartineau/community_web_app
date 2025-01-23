<a href="../Profile_Pages/check_profile.php?check_id=<?php echo $check_id; ?>" style="text-decoration: none;">
    <div id="widget" class="check_widget">
        <h3 class="widget_name"><?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s " . $month . " Check" ?></h3>
        <p class="widget_info">
            <strong>Issuance date:</strong> <?php echo $check_data_row['issuance_date']?><br>
            <strong>Validity date:</strong> <?php echo $check_data_row['validity_date']?><br>
            <strong>Points deposit:</strong> <?php echo $check_data_row['points_deposit'] . " Points"?><br>
            <strong>Hours required:</strong> <?php echo $check_data_row['hours_required'] . " Hours"?><br>
            <strong>Hours completed:</strong> <?php echo $check_data_row['hours_completed'] . " Hours"?><br>

        </p>
    </div>
</a>