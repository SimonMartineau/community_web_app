<a href="../Profile_Pages/matching_volunteer_activity.php?volunteer_id=<?php echo $volunteer_id; ?>&activity_id=<?php echo $activity_id; ?>" style="text-decoration: none;">
    <div id="widget" class="volunteer_widget">
        <div class="icon_container" style="font-size: 1.5em; color: #405d9b; font-weight: 600;">
            <span class="material-symbols-outlined">garden_cart</span>
        </div>

        <div class="name_container">
            <span class="widget_name"><?php echo $activity_data_row['activity_name'] ?></span>
        </div>
        <div class="info_container">
            <p class="widget_info">
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                    <span class="info_value"><?php echo "Date : " . formatDate($activity_data_row['activity_date'])?></span>
                </span>
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">person</span></span>
                    <span class="info_value"><?php echo $activity_data_row['number_of_participants']?>/<?php echo $activity_data_row['number_of_places']?> Participants</span>
                </span>
            </p>
        </div>

        <div class="status_container">
            <p class="widget_info">
                
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