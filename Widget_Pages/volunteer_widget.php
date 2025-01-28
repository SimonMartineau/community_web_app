<a href="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
    <div id="widget" class="volunteer_widget">
        <h3 class="widget_name"><?php echo $volunteer_data_row['first_name'] . " " . $volunteer_data_row['last_name'] ?></h3>
        <p class="widget_info">
            <strong>Email:</strong> <?php echo $volunteer_data_row['email']?><br>
            <strong>Address:</strong> <?php echo $volunteer_data_row['address']?><br>
            <strong>Phone:</strong> <?php echo $volunteer_data_row['telephone_number']?><br>
            <strong>Points:</strong> <?php echo $volunteer_data_row['points'] . " Points"; ?><br>
            <?php if ($volunteer_data_row['hours_required'] == 0): ?>
                <strong style="color: rgb(226, 65, 65)">Volunteer doesn't currently have a check.</strong><br>
            <?php else: ?>
                <strong>Hours Required:</strong> <?php echo $volunteer_data_row['hours_required'] . " Hours"; ?><br>
                <strong>Hours Completed:</strong> <?php echo $volunteer_data_row['hours_completed'] . " Hours"; ?><br>
            <?php endif; ?>
            <?php if ($volunteer_data_row['points'] < 0): ?>
                <strong style="color: rgb(226, 65, 65); width: 100%;">Warning: Volunteer has spent too many points.</strong><br>
            <?php endif; ?>
        </p>
    </div>
</a>