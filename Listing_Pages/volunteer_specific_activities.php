<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];
    }

    // Default entry values on page startup.
    $order_filter = "date_of_inscription_desc";
    $status_filter = "all_activities";
    $occupancy_filter = "all_activities";
    $domains_filter = [];
    $time_periods_filter = [];
    $available_days_filter = [];

    // Collect volunteer's activities
    $all_activities_data = fetch_data("
        SELECT DISTINCT a.* FROM Activities a
        JOIN Activity_Time_Periods atp ON a.id = atp.activity_id
        JOIN Activity_Domains ad ON a.id = ad.activity_id
        Join Volunteer_Activity_Junction vaj ON a.id = vaj.activity_id
        WHERE `trashed` = '0' 
        AND vaj.volunteer_id = '$volunteer_id'
        ORDER BY id DESC"
    );


    // Getting filter form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve filter form data
        $order_filter = $_POST['order_filter'] ?? '';
        $status_filter = $_POST['status_filter'] ?? '';
        $occupancy_filter = $_POST['occupancy_filter'] ?? '';
        $domains_filter = $_POST['domains_filter'] ?? [];
        $time_periods_filter = $_POST['time_periods_filter'] ?? [];
        $available_days_filter = $_POST['available_days_filter'] ?? [];

        // Default sql query
        $sql_filter_query = "SELECT DISTINCT a.* FROM Activities a 
                                    JOIN Activity_Time_Periods atp ON a.id = atp.activity_id 
                                    JOIN Activity_Domains ad ON a.id = ad.activity_id
                                    Join Volunteer_Activity_Junction vaj ON a.id = vaj.activity_id
        ";


        // Initialize Where clause
        $sql_filter_query .= " WHERE 1=1 AND vaj.volunteer_id = '$volunteer_id' ";

        // Volunteer status filter
        if (!empty($status_filter)){
            switch ($status_filter){
                case 'only_active':
                    $sql_filter_query .= " AND a.trashed = '0' AND a.activity_date >= CURDATE()";
                    break;
                case 'only_past':
                    $sql_filter_query .= " AND a.trashed = '0' AND a.activity_date < CURDATE()";
                    break;
                case 'only_in_trash':
                    $sql_filter_query .= " AND a.trashed = '1'";
                    break;
                case 'all_activities':
                    // No additional condition needed (show all volunteers)
                    break;
            }
        }

        // Volunteer occupancy filter
        if (!empty($occupancy_filter)){
            switch ($occupancy_filter){
                case 'not_full':
                    $sql_filter_query .= " AND a.number_of_places - a.number_of_participants > 0";
                    break;
                case 'full':
                    $sql_filter_query .= " AND a.number_of_places - a.number_of_participants <= 0";
                    break;
                case 'empty':
                    $sql_filter_query .= " AND a.number_of_participants = 0";
                    break;
                case 'all_activities':
                    // No additional condition needed (show all volunteers)
                    break;
            }
        }

        // Add time periods filter
        if (!empty($time_periods_filter)) {
            $sql_filter_query .= " AND (";
            foreach ($time_periods_filter as $time_period) {
                $sql_filter_query .= " atp.time_period = '$time_period' OR";
            }
            $sql_filter_query = rtrim($sql_filter_query, "OR") . ")"; // Remove the last "OR" and close the parentheses
        }

        // Add available days filter
        if (!empty($available_days_filter)) {
            $sql_filter_query .= " AND (";
            foreach ($available_days_filter as $weekday) {
                $sql_filter_query .= " DAYNAME(a.activity_date) = '$weekday' OR";
            }
            $sql_filter_query = rtrim($sql_filter_query, "OR") . ")"; // Remove the last "OR" and close the parentheses
        }

        // Add domain filter
        if (!empty($domains_filter)) {
            $sql_filter_query .= " AND (";
            foreach ($domains_filter as $domain) {
                $sql_filter_query .= " ad.domain = '$domain' OR";
            }
            $sql_filter_query = rtrim($sql_filter_query, "OR") . ")"; // Remove the last "OR" and close the parentheses
        }

        // Order of appearance filter
        if (!empty($order_filter)){
            switch ($order_filter){
                case 'registration_date_desc':
                    $sql_filter_query .= " ORDER BY a.registration_date DESC";
                    break;
                case 'registration_date_asc':
                    $sql_filter_query .= " ORDER BY a.registration_date ASC";
                    break;
                case 'activity_duration_desc':
                    $sql_filter_query .= " ORDER BY a.activity_duration DESC";
                    break;
                case 'activity_duration_asc':
                    $sql_filter_query .= " ORDER BY a.activity_duration ASC";
                    break;
                case 'activity_date_desc':
                    $sql_filter_query .= " ORDER BY a.activity_date DESC";
                    break;
                case 'activity_date_asc':
                    $sql_filter_query .= " ORDER BY a.activity_date ASC";
                    break;
                case 'activity_name_asc':
                    $sql_filter_query .= " ORDER BY a.activity_name ASC";
                    break;
            }
        }

        // Final query
        $all_activities_data = fetch_data($sql_filter_query);
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Activities | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>
     
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Left area -->
                <div style="flex:0.6;">

                    <!-- Filter form area -->
                    <div id="medium_rectangle">

                        <!-- Section title of filter area -->
                        <div id="section_title">
                            <span>Filter</span>
                        </div>

                        <!-- Filter form -->
                        <form action="" method="post">
                            <!-- Sort by options -->
                            <div style="margin-bottom: 15px;">
                                <label for="order_filter" style="font-weight: bold;">Sort Volunteers By:</label><br>
                                <select name="order_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="registration_date_desc" <?php echo ($order_filter == 'registration_date_desc') ? 'selected' : ''; ?>>Registration Date (Latest to Oldest)</option>
                                    <option value="registration_date_asc" <?php echo ($order_filter == 'registration_date_asc') ? 'selected' : ''; ?>>Registration Date (Oldest to Latest)</option>
                                    <option value="activity_date_asc" <?php echo ($order_filter == 'activity_date_asc') ? 'selected' : ''; ?>>Date (Oldest to Latest)</option>
                                    <option value="activity_date_desc" <?php echo ($order_filter == 'activity_date_desc') ? 'selected' : ''; ?>>Date (Latest to Oldest)</option>
                                    <option value="activity_duration_desc" <?php echo ($order_filter == 'activity_duration_desc') ? 'selected' : ''; ?>>Duration (Longest to Shortest)</option>
                                    <option value="activity_duration_asc" <?php echo ($order_filter == 'activity_duration_asc') ? 'selected' : ''; ?>>Duration (Shortest to Longest)</option>                                    
                                    <option value="activity_name_asc" <?php echo ($order_filter == 'activity_name_asc') ? 'selected' : ''; ?>>Activity Name (A-Z)</option>
                                </select>
                            </div>
                            
                            <!-- Activity status filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="status_filter" style="font-weight: bold;">Activity Status:</label><br>
                                <select name="status_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="only_active" <?php echo ($status_filter == 'only_active') ? 'selected' : ''; ?>>Only Active</option>
                                    <option value="only_past" <?php echo ($status_filter == 'only_past') ? 'selected' : ''; ?>>Only Past</option>
                                    <option value="only_in_trash" <?php echo ($status_filter == 'only_in_trash') ? 'selected' : ''; ?>>Only In Trash</option>
                                    <option value="all_activities" <?php echo ($status_filter == 'all_activities') ? 'selected' : ''; ?>>All Activities</option>
                                </select>
                            </div>

                            <!-- Activity occupancy filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="occupancy_filter" style="font-weight: bold;">Activity Occupancy:</label><br>
                                <select name="occupancy_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="all_activities" <?php echo ($occupancy_filter == 'all_activities') ? 'selected' : ''; ?>>All Activities</option>
                                    <option value="not_full" <?php echo ($occupancy_filter == 'not_full') ? 'selected' : ''; ?>>Not full</option>
                                    <option value="full" <?php echo ($occupancy_filter == 'full') ? 'selected' : ''; ?>>Full</option>
                                    <option value="empty" <?php echo ($occupancy_filter == 'empty') ? 'selected' : ''; ?>>Empty</option>

                                </select>
                            </div>

                            <!-- Domain filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Domains:</label><br>
                                <div>
                                    <label><input type="checkbox" name="domains_filter[]" value="Organization of community events" <?php echo (isset($_POST['domains_filter']) && in_array('Organization of community events', $_POST['domains_filter'])) ? 'checked' : ''; ?>> Organization of community events</label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Library support" <?php echo (isset($_POST['domains_filter']) && in_array('Library support', $_POST['domains_filter'])) ? 'checked' : ''; ?>> Library support</label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Help in the community store" <?php echo (isset($_POST['domains_filter']) && in_array('Help in the community store', $_POST['domains_filter'])) ? 'checked' : ''; ?>> Help in the community store</label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Support in the community grocery store" <?php echo (isset($_POST['domains_filter']) && in_array('Support in the community grocery store', $_POST['domains_filter'])) ? 'checked' : ''; ?>> Support in the community grocery store</label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Cleaning and maintenance of public spaces" <?php echo (isset($_POST['domains_filter']) && in_array('Cleaning and maintenance of public spaces', $_POST['domains_filter'])) ? 'checked' : ''; ?>> Cleaning and maintenance of public spaces</label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Participation in urban gardening projects" <?php echo (isset($_POST['domains_filter']) && in_array('Participation in urban gardening projects', $_POST['domains_filter'])) ? 'checked' : ''; ?>> Participation in urban gardening projects</label><br>
                                </div>
                            </div>

                            <!-- Time periods filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Acivity Period:</label><br>
                                <div>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Morning" <?php echo (isset($_POST['time_periods_filter']) && in_array('Morning', $_POST['time_periods_filter'])) ? 'checked' : ''; ?>> Morning</label><br>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Afternoon" <?php echo (isset($_POST['time_periods_filter']) && in_array('Afternoon', $_POST['time_periods_filter'])) ? 'checked' : ''; ?>> Afternoon</label><br>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Evening" <?php echo (isset($_POST['time_periods_filter']) && in_array('Evening', $_POST['time_periods_filter'])) ? 'checked' : ''; ?>> Evening</label><br>
                                </div>
                            </div>

                            <!-- Day availability filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Available Days:</label><br>
                                <div>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Monday" <?php echo (isset($_POST['available_days_filter']) && in_array('Monday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Monday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Tuesday" <?php echo (isset($_POST['available_days_filter']) && in_array('Tuesday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Tuesday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Wednesday" <?php echo (isset($_POST['available_days_filter']) && in_array('Wednesday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Wednesday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Thursday" <?php echo (isset($_POST['available_days_filter']) && in_array('Thursday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Thursday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Friday" <?php echo (isset($_POST['available_days_filter']) && in_array('Friday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Friday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Saturday" <?php echo (isset($_POST['available_days_filter']) && in_array('Saturday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Saturday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Sunday" <?php echo (isset($_POST['available_days_filter']) && in_array('Sunday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Sunday</label>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <div style="text-align: center;">
                                <button type="submit" style="padding: 10px 20px; background-color: #405d9b; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                                    Apply Filter
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Right area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Volunteer widget display -->
                    <div id="medium_rectangle">

                        <!-- Section title of recent activities section -->
                        <div id="section_title">
                            <span>Activities</span>
                        </div>

                        <!-- Counting the number of elements post filter -->
                        <?php 
                        if (empty($all_activities_data)) {
                            echo "No activities found.";
                        } else {
                            echo count($all_activities_data) . " activities found.";
                        } ?>

                        <!-- Display activity widgets --> 
                        <?php
                            if($all_activities_data){
                                foreach($all_activities_data as $activity_data_row){
                                    $activity_id = $activity_data_row['id'];
                                    $activity_time_periods_data = fetch_data("select * from Activity_Time_Periods where activity_id = '$activity_id'");
                                    $activity_domains_data = fetch_data("select * from Activity_Domains where activity_id = '$activity_id'");
                                    include("../Widget_Pages/activity_widget.php");
                                }
                            }
                        ?>
        

                    </div>

                </div>
            </div>
            
        </div>
        
    </body>
</html>