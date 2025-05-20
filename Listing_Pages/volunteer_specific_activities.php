<!-- PHP Code -->
<?php
    // Include header
    include("../Misc/header.php");

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();
    $user_id = $user_data['user_id'];

    // Updating all backend processes
    update_backend_data();

    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];
    }

    // Retrieve filter form data
    $order_filter = $_POST['order_filter'] ?? 'registration_date_desc';
    $status_filter = $_POST['status_filter'] ?? 'all_activities';
    $occupancy_filter = $_POST['occupancy_filter'] ?? 'all_activities';
    $domains_filter = $_POST['domains_filter'] ?? [];
    $time_periods_filter = $_POST['time_periods_filter'] ?? [];
    $available_days_filter = $_POST['available_days_filter'] ?? [];

    // Default sql query
    $sql_filter_query = "SELECT DISTINCT a.* FROM Activities a 
                                JOIN Activity_Time_Periods atp ON a.id = atp.activity_id 
                                JOIN Activity_Domains ad ON a.id = ad.activity_id
                                Join Volunteer_Activity_Junction vaj ON a.id = vaj.activity_id";


    // Initialize Where clause
    $sql_filter_query .= " WHERE 1=1 AND vaj.volunteer_id = '$volunteer_id'";

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
    $all_activities_data_rows = fetch_data_rows($sql_filter_query);

?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= __('CivicLink | Activities') ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Cover Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>
    
            <!-- Below Cover Area -->
            <div style="display: flex;">

                <!-- Left Area -->
                <div style="flex:0.6;">

                    <!-- Filter Form Area -->
                    <div id="medium_rectangle">

                        <!-- Section Title of Filter Area -->
                        <div id="section_title">
                            <span><?= __('Filter') ?></span>
                        </div>

                        <!-- Filter Form -->
                        <form action="" method="post">
                            <!-- Sort by Options -->
                            <div style="margin-bottom: 15px;">
                                <label for="order_filter" style="font-weight: bold;"><?= __('Sort Volunteers By:') ?></label><br>
                                <select name="order_filter" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ccc;">
                                    <option value="registration_date_desc" <?= ($order_filter=='registration_date_desc')?'selected':''; ?>><?= __('Registration Date (Latest to Oldest)') ?></option>
                                    <option value="registration_date_asc" <?= ($order_filter=='registration_date_asc')?'selected':''; ?>><?= __('Registration Date (Oldest to Latest)') ?></option>
                                    <option value="activity_date_asc" <?= ($order_filter=='activity_date_asc')?'selected':''; ?>><?= __('Date (Oldest to Latest)') ?></option>
                                    <option value="activity_date_desc" <?= ($order_filter=='activity_date_desc')?'selected':''; ?>><?= __('Date (Latest to Oldest)') ?></option>
                                    <option value="activity_duration_desc" <?= ($order_filter=='activity_duration_desc')?'selected':''; ?>><?= __('Duration (Longest to Shortest)') ?></option>
                                    <option value="activity_duration_asc" <?= ($order_filter=='activity_duration_asc')?'selected':''; ?>><?= __('Duration (Shortest to Longest)') ?></option>
                                    <option value="activity_name_asc" <?= ($order_filter=='activity_name_asc')?'selected':''; ?>><?= __('Activity Name (A-Z)') ?></option>
                                </select>
                            </div>

                            <!-- Activity Status Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="status_filter" style="font-weight: bold;"><?= __('Activity Status:') ?></label><br>
                                <select name="status_filter" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ccc;">
                                    <option value="only_active" <?= ($status_filter=='only_active')?'selected':''; ?>><?= __('Only Active') ?></option>
                                    <option value="only_past" <?= ($status_filter=='only_past')?'selected':''; ?>><?= __('Only Past') ?></option>
                                    <option value="only_in_trash" <?= ($status_filter=='only_in_trash')?'selected':''; ?>><?= __('Only In Trash') ?></option>
                                    <option value="all_activities" <?= ($status_filter=='all_activities')?'selected':''; ?>><?= __('All Activities') ?></option>
                                </select>
                            </div>

                            <!-- Activity Occupancy Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="occupancy_filter" style="font-weight: bold;"><?= __('Activity Occupancy:') ?></label><br>
                                <select name="occupancy_filter" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ccc;">
                                    <option value="all_activities" <?= ($occupancy_filter=='all_activities')?'selected':''; ?>><?= __('All Activities') ?></option>
                                    <option value="not_full" <?= ($occupancy_filter=='not_full')?'selected':''; ?>><?= __('Not full') ?></option>
                                    <option value="full" <?= ($occupancy_filter=='full')?'selected':''; ?>><?= __('Full') ?></option>
                                    <option value="empty" <?= ($occupancy_filter=='empty')?'selected':''; ?>><?= __('Empty') ?></option>
                                </select>
                            </div>

                            <!-- Domain Filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;"><?= __('Domains:') ?></label><br>
                                <div>
                                    <label><input type="checkbox" name="domains_filter[]" value="Organization of community events" <?= (isset($_POST['domains_filter'])&&in_array('Organization of community events',$_POST['domains_filter']))?'checked':''; ?>> <?= __('Organization of community events') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Library support" <?= (isset($_POST['domains_filter'])&&in_array('Library support',$_POST['domains_filter']))?'checked':''; ?>> <?= __('Library support') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Help in the community store" <?= (isset($_POST['domains_filter'])&&in_array('Help in the community store',$_POST['domains_filter']))?'checked':''; ?>> <?= __('Help in the community store') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Support in the community grocery store" <?= (isset($_POST['domains_filter'])&&in_array('Support in the community grocery store',$_POST['domains_filter']))?'checked':''; ?>> <?= __('Support in the community grocery store') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Cleaning and maintenance of public spaces" <?= (isset($_POST['domains_filter'])&&in_array('Cleaning and maintenance of public spaces',$_POST['domains_filter']))?'checked':''; ?>> <?= __('Cleaning and maintenance of public spaces') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Participation in urban gardening projects" <?= (isset($_POST['domains_filter'])&&in_array('Participation in urban gardening projects',$_POST['domains_filter']))?'checked':''; ?>> <?= __('Participation in urban gardening projects') ?></label><br>
                                </div>
                            </div>

                            <!-- Time Periods Filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;"><?= __('Activity Period:') ?></label><br>
                                <div>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Morning" <?= (isset($_POST['time_periods_filter'])&&in_array('Morning',$_POST['time_periods_filter']))?'checked':''; ?>> <?= __('Morning') ?></label><br>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Afternoon" <?= (isset($_POST['time_periods_filter'])&&in_array('Afternoon',$_POST['time_periods_filter']))?'checked':''; ?>> <?= __('Afternoon') ?></label><br>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Evening" <?= (isset($_POST['time_periods_filter'])&&in_array('Evening',$_POST['time_periods_filter']))?'checked':''; ?>> <?= __('Evening') ?></label><br>
                                </div>
                            </div>

                            <!-- Day Availability Filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;"><?= __('Available Days:') ?></label><br>
                                <div>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Monday" <?= (isset($_POST['available_days_filter'])&&in_array('Monday',$_POST['available_days_filter']))?'checked':''; ?>> <?= __('Monday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Tuesday" <?= (isset($_POST['available_days_filter'])&&in_array('Tuesday',$_POST['available_days_filter']))?'checked':''; ?>> <?= __('Tuesday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Wednesday" <?= (isset($_POST['available_days_filter'])&&in_array('Wednesday',$_POST['available_days_filter']))?'checked':''; ?>> <?= __('Wednesday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Thursday" <?= (isset($_POST['available_days_filter'])&&in_array('Thursday',$_POST['available_days_filter']))?'checked':''; ?>> <?= __('Thursday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Friday" <?= (isset($_POST['available_days_filter'])&&in_array('Friday',$_POST['available_days_filter']))?'checked':''; ?>> <?= __('Friday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Saturday" <?= (isset($_POST['available_days_filter'])&&in_array('Saturday',$_POST['available_days_filter']))?'checked':''; ?>> <?= __('Saturday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Sunday" <?= (isset($_POST['available_days_filter'])&&in_array('Sunday',$_POST['available_days_filter']))?'checked':''; ?>> <?= __('Sunday') ?></label>
                                </div>
                            </div>

                            <!-- Reset Filters Link -->
                            <div>
                                <a href="" class="reset-link"><?= __('Reset Filter') ?></a>
                            </div>

                            <!-- Submit Button -->
                            <div style="text-align: center;">
                                <button type="submit" style="padding:10px 20px; background-color:#405d9b; color:white; border:none; border-radius:5px; font-size:16px; cursor:pointer;">
                                    <?= __('Apply Filter') ?>
                                </button>
                            </div>
                        </form>


                    </div>
                </div>

                <!-- Right Area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Volunteer Widget Display -->
                    <div id="medium_rectangle">

                        <!-- Section Title of Recent Activities Section -->
                        <div id="section_title">
                            <span><?= __('Activities') ?></span>
                        </div>

                        <!-- Counting Number of Elements Post Filter -->
                        <?php 
                        if (empty($all_activities_data_rows)) {
                            echo __('No activities found.');
                        } elseif (count($all_activities_data_rows) == 1) {
                            echo '1 ' . __('activity found.');
                        } else {
                            echo count($all_activities_data_rows) . ' ' . __('activities found.');
                        }
                        ?>

                        <!-- Display Activity Widgets --> 
                        <?php
                            if($all_activities_data_rows){
                                foreach($all_activities_data_rows as $activity_data_row){
                                    $activity_id = $activity_data_row['id'];
                                    $activity_time_periods_data_rows = fetch_data_rows("SELECT * FROM Activity_Time_Periods WHERE activity_id = '$activity_id'");
                                    $activity_domains_data_rows = fetch_data_rows("SELECT * FROM Activity_Domains WHERE activity_id = '$activity_id'");
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