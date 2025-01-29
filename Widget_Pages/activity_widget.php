<a href="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" style="text-decoration: none;">
    <div id="widget" class="activity_widget">
        <h3 class="widget_name"><?php echo $activity_data_row['activity_name'] ?></h3>
        <p class="widget_info">
            <strong>Number of Participants:</strong> <?php echo $activity_data_row['number_of_participants']?><br>
            <strong>Duration:</strong> <?php echo $activity_data_row['activity_duration'] . " Hours"?><br>
            <strong>Date:</strong> <?php echo formatDate($activity_data_row['activity_date'])?><br>
        </p>
        <p>
           <strong>Domain:</strong>
            <ul>
                <li>Organization of community events</li>
                <li>Cleaning and maintenance of public spaces</li>
                <li>Participation in urban gardening projects</li>
            </ul>
        </p>
    </div>
</a>