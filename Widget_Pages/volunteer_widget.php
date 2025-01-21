<a href="../Profile_Pages/volunteer_profile.php?id=<?php echo $volunteer_data_row['id']; ?>" style="text-decoration: none;">
    <div id="widget" class="volunteer_widget">
        <h3 class="widget_name"><?php echo $volunteer_data_row['first_name'] . " " . $volunteer_data_row['last_name'] ?></h3>
        <p class="widget_info">
            <strong>Email:</strong> <?php echo $volunteer_data_row['email']?><br>
            <strong>Address:</strong> <?php echo $volunteer_data_row['address']?><br>
            <strong>Phone:</strong> <?php echo $volunteer_data_row['telephone_number']?><br>
            <strong>Hours Completed:</strong> <?php echo $volunteer_data_row['hours_completed'] . " Hours"?><br>
            <strong>Points:</strong> <?php echo $volunteer_data_row['points'] . " Points"?><br>
        </p>
    </div>
</a>