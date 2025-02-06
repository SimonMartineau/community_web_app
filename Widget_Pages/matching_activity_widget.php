<a href="../Profile_Pages/matching_volunteer_activity.php?volunteer_id=<?php echo $volunteer_id; ?>&activity_id=<?php echo $activity_id; ?>" style="text-decoration: none;">
    <div id="widget" class="matching_activity_widget">
        <h3 class="widget_name"><?php echo $activity_data_row['activity_name'] ?></h3>
        <p class="widget_info">
            <strong>Number of places:</strong> <?php echo $activity_data_row['number_of_places']?><br>
            <strong>Number of participants:</strong> <?php echo $activity_data_row['number_of_participants']?><br>
            <strong>Duration:</strong> <?php echo $activity_data_row['activity_duration'] . " Hours"?><br>
            <strong>Date:</strong> <?php echo formatDate($activity_data_row['activity_date'])?><br>
        </p>
    </div>
</a>