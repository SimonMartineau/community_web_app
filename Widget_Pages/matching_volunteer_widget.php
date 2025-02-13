<?php

    // Checking if volunteer is assigned to activity
    $volunteer_activity_match_data = fetch_data(
        "SELECT * FROM Volunteer_Activity_Junction
                WHERE volunteer_id = '$volunteer_id'
                AND activity_id = '$activity_id'"
    );

    // Storing volunteer activity junction status in a variable
    if (!empty($volunteer_activity_match_data)) {
        // Junction exists
        $volunteer_activity_assigned = true;
    } else{
        // Junction does not exist
        $volunteer_activity_assigned = false;
    }

?>

<a href="../Profile_Pages/volunteer_profile.php?volunteer_id=<?php echo $volunteer_id; ?>" style="text-decoration: none;">
    <div id="widget">
        <div class="widget_row">

            <div class="icon_container">
                <span class="material-symbols-outlined">person</span>
            </div>

            <div class="name_container">
                <span class="widget_name"><?php echo $volunteer_data_row['first_name'] . " " . $volunteer_data_row['last_name'] ?></span>
            </div>
            <div class="info_container">
                <p class="widget_info">
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value"><?php echo $volunteer_data_row['points'] ?> Points Left</span>
                    </span>
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">schedule</span></span>
                        <span class="info_value"><?php echo $volunteer_data_row['hours_completed'] ?>/<?php echo $volunteer_data_row['hours_required'] ?> Hours Completed</span>
                    </span>
                </p>
            </div>

            <div class="status_container">
                <p class="widget_info">
                    <?php if ($volunteer_data_row['hours_required'] == 0): ?>
                        <span class="info_line warning"><span class="material-symbols-outlined">warning</span> Warning: Volunteer doesn't currently have a contract.</span>
                    <?php endif; ?>
                    <?php if ($volunteer_data_row['points'] < 0): ?>
                        <span class="info_line warning"><span class="material-symbols-outlined">warning</span> Warning: Volunteer has spent too many points.</span>
                    <?php endif; ?>
                </p>
            </div>
            <!-- Button placed inside the widget. -->
            <!-- Contract if volunteer is assigned to activity -->
            <?php 
                // If the volunteer is assigned to the activity
                if($volunteer_activity_assigned == true){
                    // Show the unassign button
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" onsubmit="return confirm('Are you sure you want to unassign <?php echo $volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'] . ' from ' . $activity_data_row['activity_name']?>?')">
                            <button class="widget_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="unassign_volunteer_activity" value="1">
                                <!-- Hidden input to send volunteer_id -->
                                <input type="hidden" name="volunteer_id" value="<?php echo $volunteer_id; ?>">
                                Unassign Volunteer from Activity
                            </button>
                        </form>
                    </div>
                <?php
                } else{
                // Show the assign button
                ?>
                    <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                        <form method="POST" action="../Profile_Pages/activity_profile.php?activity_id=<?php echo $activity_id; ?>" onsubmit="return confirm('Are you sure you want to assign <?php echo $volunteer_data_row['first_name'] . ' ' . $volunteer_data_row['last_name'] . ' to ' . $activity_data_row['activity_name']?>?')">
                            <button class="widget_button">
                                <!-- Hidden input to confirm source -->
                                <input type="hidden" name="assign_volunteer_activity" value="1">
                                <!-- Hidden input to send volunteer_id -->
                                <input type="hidden" name="volunteer_id" value="<?php echo $volunteer_id; ?>">
                                Assign Volunteer to Activity
                            </button>
                        </form>
                    </div>
                <?php
                }
            ?>
            <!-- Button placed inside the widget. We call stopPropagation() in the onclick to avoid triggering the link. -->
            <button class="widget_button" type="button" onclick="toggleDetails(event, '<?php echo $volunteer_id; ?>')">
                More Details
            </button>
        </div>

        <div id="extra_details_row-<?php echo $volunteer_id; ?>" class="widget_row" style="display: none; align-items: flex-start;">
            <div class="widget_section">
                <h2 style="font-size: 20px; color: #555;">Volunteer Info</h2>
                <p class="widget_info">
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value">Address: <?php echo $volunteer_data_row['address'] ?></span>
                    </span>
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value">Zip-code: <?php echo $volunteer_data_row['zip_code'] ?></span>
                    </span>
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value">Phone: <?php echo $volunteer_data_row['telephone_number'] ?></span>
                    </span>
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value">Email: <?php echo $volunteer_data_row['email'] ?></span>
                    </span>
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value">Volunteer Manager: <?php echo $volunteer_data_row['organizer_name'] ?></span>
                    </span>
                </p>
            </div>

            <div class="widget_section">
            <h2 style="font-size: 20px; color: #555;">Interests</h2>
                <?php if (!empty($interest_data)): ?>
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        <?php foreach ($interest_data as $interest): ?>
                            <li><?php echo htmlspecialchars($interest['interest'] ?: 'No specific interest provided'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No interests provided.</p>
                <?php endif; ?>
            </div>


            <div class="widget_section">
                <h2 style="font-size: 20px; color: #555;">Weekly Availability</h2>
                <?php
                // Define the weekdays and time periods
                $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $time_periods = ['Morning', 'Afternoon', 'Evening'];
                
                // Create a matrix for availability
                $availability_matrix = [];
                foreach ($weekdays as $weekday) {
                    foreach ($time_periods as $time_period) {
                        $availability_matrix[$weekday][$time_period] = '';
                    }
                }
                
                // Populate the matrix based on availability_data
                foreach ($availability_data as $availability) {
                    $weekday = $availability['weekday'];
                    $time_period = $availability['time_period'];
                    if (isset($availability_matrix[$weekday][$time_period])) {
                        $availability_matrix[$weekday][$time_period] = '✔';
                    }
                }
                ?>
                
                <table style="width: 50%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr style="background-color: #f1f1f1;">
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Weekday</th>
                            <?php foreach ($time_periods as $time_period): ?>
                                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;"><?php echo htmlspecialchars($time_period); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($weekdays as $weekday): ?>
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($weekday); ?></td>
                                <?php foreach ($time_periods as $time_period): ?>
                                    <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                                        <?php echo htmlspecialchars($availability_matrix[$weekday][$time_period]); ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
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

.widget_section {
  flex: 1; /* All sections take equal width */
  padding: 10px;  
}
#widget {
    margin: 10px auto;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    font-family: sans-serif;
    color: #333; /* Default text color */
    background: #fff;
    display: flex;
    align-items: center;
    transition: transform 0.2s ease;
    width: 1000px;
    flex-direction: column;
}

.widget_button {
    background: rgb(248, 245, 245);
    color: #405d9b;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    border-width: 1px;
    min-width: 80px;

}

/* Hover effect for buttons */
.widget_button:hover {
    background: linear-gradient(to right, #dbe9f9, #f0f8ff); /* Reverse the gradient */
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15); /* Slightly deeper shadow on hover */
    transform: translateY(-2px); /* Subtle lift effect */
}

/* Hover effect for the widget */
#widget:hover {
    background: linear-gradient(to right, #f0f8ff, #dbe9f9); /* Gradient background on hover */
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15); /* Deeper shadow on hover */
    transform: translateY(-2px); /* Slight lift effect */
}

.widget_row {
    display: flex;
    align-items: center;
    transition: transform 0.2s ease;
    width:1000px;
}

.icon_container {
    padding-right: 25px;
    margin-right: 25px;
    border-right: 2px solid #eee;
    width: 30px;  
    font-size: 1.5em; 
    color: #405d9b; 
    font-weight: 600; 
}

.name_container {
    padding-right: 25px;
    margin-right: 25px;
    border-right: 2px solid #eee;
    width: 250px;   
}

.widget_name {
    font-size: 1.4rem;
    font-weight: 600;
    color: #405d9b;
    letter-spacing: 0.5px;
    width: 100%; /* Allow it to take full width */
    word-wrap: break-word; /* Ensures long words wrap */
    overflow-wrap: break-word; /* Alternative for better browser support */
    white-space: normal; /* Allow wrapping */
}

.info_container {
    width: 320px;
}

.status_container {
    width: 260px;
    padding-left: 40px;
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


</style>
<script>
  function toggleDetails(event, volunteerId) {
    event.stopPropagation();
    event.preventDefault();

    // Get references to elements
    const button = event.target; // Get the clicked button
    const detailsDiv = document.getElementById('extra_details_row-' + volunteerId);

    // Toggle visibility
    const isVisible = detailsDiv.style.display === "flex";
    detailsDiv.style.display = isVisible ? "none" : "flex";

    // Update button text based on new state
    button.textContent = isVisible ? 'More Details' : 'Less Details';
  }
</script>


