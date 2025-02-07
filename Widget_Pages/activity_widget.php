<a href="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_data_row['id']; ?>" style="text-decoration: none;">
    <div id="widget" class="volunteer_widget">
        <div class="name_container">
            <span class="widget_name"><span class="material-symbols-outlined">garden_cart</span><?php echo $activity_data_row['activity_name'] ?></span>
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

        <div class="info_container">
            <p class="widget_info">
                
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
    width: 350px;
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