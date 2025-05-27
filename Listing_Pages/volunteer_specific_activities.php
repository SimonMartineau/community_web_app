<!-- PHP Code -->
<?php
    // Include header
    include(__DIR__ . "/../Header/header.php");

    // Include necessary files
    include(__DIR__ . "/../Classes/connect.php");
    include(__DIR__ . "/../Classes/functions.php");

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

    // Listing variables
    $items_per_page = 30; // Adjust as needed
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $items_per_page;

    // Check if the filter form is submitted, "apply_filter" is the name of the submit button
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply_filter'])) {
        $_SESSION['volunteer_specific_order_filter'] = $_POST['order_filter'] ?? '';
        $_SESSION['volunteer_specific_status_filter'] = $_POST['status_filter'] ?? '';  
        $_SESSION['volunteer_specific_occupancy_filter'] = $_POST['occupancy_filter'] ?? '';
        $_SESSION['volunteer_specific_domains_filter'] = $_POST['domains_filter'] ?? [];
        $_SESSION['volunteer_specific_time_periods_filter'] = $_POST['time_periods_filter'] ?? [];  
        $_SESSION['volunteer_specific_available_days_filter'] = $_POST['available_days_filter'] ?? [];

        // Reset to the first page after applying filters
        $page = 1;
    }

    // Check if the reset_filters parameter is set in the URL, if so reset the filters to default   
    if (isset($_GET['reset_filters'])) {
        unset($_SESSION['volunteer_specific_order_filter']);
        unset($_SESSION['volunteer_specific_status_filter']);
        unset($_SESSION['volunteer_specific_occupancy_filter']);
        unset($_SESSION['volunteer_specific_domains_filter']);
        unset($_SESSION['volunteer_specific_time_periods_filter']);
        unset($_SESSION['volunteer_specific_available_days_filter']);
    
        // Redirect to the same page without the reset_filters parameter
        header("Location: ".$_SERVER['PHP_SELF'] . "?volunteer_id=" . urlencode($volunteer_id));
        exit;
    }

    // Retain previous filter values or set default
    $order_filter = $_SESSION['volunteer_specific_order_filter'] ?? 'activity_date_asc';
    $status_filter = $_SESSION['volunteer_specific_status_filter'] ?? 'all_activities';
    $occupancy_filter = $_SESSION['volunteer_specific_occupancy_filter'] ?? 'all_activities';
    $domains_filter = $_SESSION['volunteer_specific_domains_filter'] ?? [];
    $time_periods_filter = $_SESSION['volunteer_specific_time_periods_filter'] ?? [];
    $available_days_filter = $_SESSION['volunteer_specific_available_days_filter'] ?? [];

    // Default sql query
    $sql_filter_query = "SELECT DISTINCT a.* FROM Activities a 
                                JOIN Activity_Time_Periods atp ON a.id = atp.activity_id 
                                JOIN Activity_Domains ad ON a.id = ad.activity_id
                                Join Volunteer_Activity_Junction vaj ON a.id = vaj.activity_id";

    // Initialize the Where clause
    $sql_where_clause = " WHERE 1=1 AND vaj.volunteer_id = '$volunteer_id'";

    // Volunteer status filter
    if (!empty($status_filter)){
        switch ($status_filter){
            case 'only_active':
                $sql_where_clause .= " AND a.trashed = '0' AND a.activity_date >= CURDATE()";
                break;
            case 'only_past':
                $sql_where_clause .= " AND a.trashed = '0' AND a.activity_date < CURDATE()";
                break;
            case 'only_in_trash':
                $sql_where_clause .= " AND a.trashed = '1'";
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
                $sql_where_clause .= " AND a.number_of_places - a.number_of_participants > 0";
                break;
            case 'full':
                $sql_where_clause .= " AND a.number_of_places - a.number_of_participants <= 0";
                break;
            case 'empty':
                $sql_where_clause .= " AND a.number_of_participants = 0";
                break;
            case 'all_activities':
                // No additional condition needed (show all volunteers)
                break;
        }
    }

    // Add time periods filter
    if (!empty($time_periods_filter)) {
        $sql_where_clause .= " AND (";
        foreach ($time_periods_filter as $time_period) {
            $sql_where_clause .= " atp.time_period = '$time_period' OR";
        }
        $sql_where_clause = rtrim($sql_where_clause, "OR") . ")"; // Remove the last "OR" and close the parentheses
    }

    // Add available days filter
    if (!empty($available_days_filter)) {
        $sql_where_clause .= " AND (";
        foreach ($available_days_filter as $weekday) {
            $sql_where_clause .= " DAYNAME(a.activity_date) = '$weekday' OR";
        }
        $sql_where_clause = rtrim($sql_where_clause, "OR") . ")"; // Remove the last "OR" and close the parentheses
    }

    // Add domain filter
    if (!empty($domains_filter)) {
        $sql_where_clause .= " AND (";
        foreach ($domains_filter as $domain) {
            $sql_where_clause .= " ad.domain = '$domain' OR";
        }
        $sql_where_clause = rtrim($sql_where_clause, "OR") . ")"; // Remove the last "OR" and close the parentheses
    }

    // Order of appearance filter
    if (!empty($order_filter)){
        switch ($order_filter){
            case 'activity_date_asc':
                $sql_where_clause .= " ORDER BY a.activity_date ASC";
                break;
            case 'activity_date_desc':
                $sql_where_clause .= " ORDER BY a.activity_date DESC";
                break;
            case 'activity_duration_desc':
                $sql_where_clause .= " ORDER BY a.activity_duration DESC";
                break;
            case 'activity_duration_asc':
                $sql_where_clause .= " ORDER BY a.activity_duration ASC";
                break;
            case 'registration_date_desc':
                $sql_where_clause .= " ORDER BY a.registration_date DESC";
                break;
            case 'registration_date_asc':
                $sql_where_clause .= " ORDER BY a.registration_date ASC";
                break;
            case 'activity_name_asc':
                $sql_where_clause .= " ORDER BY a.activity_name ASC";
                break;
        }
    }

    // Append the WHERE clause to the main query
    $sql_filter_query .= $sql_where_clause;

    // After applying ORDER BY
    $sql_filter_query .= " LIMIT $items_per_page OFFSET $offset";
    
    // Final query
    $all_activities_data_rows = fetch_data_rows($sql_filter_query);

    // Build count query using the same WHERE and JOINs
    $count_query = "SELECT Count(DISTINCT a.id) as total
                        FROM Activities a 
                        JOIN Activity_Time_Periods atp ON a.id = atp.activity_id 
                        JOIN Activity_Domains ad ON a.id = ad.activity_id
                        Join Volunteer_Activity_Junction vaj ON a.id = vaj.activity_id";

    // Append all WHERE conditions used in the data query
    $count_query .= $sql_where_clause; // Assuming $where_clause holds all conditions

    // Execute count query
    $total_result = fetch_data_rows($count_query);
    $total_activities_count = $total_result[0]['total'] ?? 0;
    $total_pages = ceil($total_activities_count / $items_per_page);
?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= __('CivicLink | Activities') ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="/CivicLink_Web_App/Styles/style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Cover Area -->
        <div style="width: 1600px; min-height: 400px; margin:auto;">
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
                        <form action="?volunteer_id=<?php echo $volunteer_id; ?>&page=1" method="post">
                                
                            <!-- Sort by Options -->
                            <div style="margin-bottom: 15px;">
                                <label for="order_filter" style="font-weight: bold;"><?= __('Sort Activities By:') ?></label><br>
                                <select name="order_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="activity_date_asc" <?php echo ($order_filter == 'activity_date_asc') ? 'selected' : ''; ?>><?= __('Date (Oldest to Latest)') ?></option>
                                    <option value="activity_date_desc" <?php echo ($order_filter == 'activity_date_desc') ? 'selected' : ''; ?>><?= __('Date (Latest to Oldest)') ?></option>
                                    <option value="activity_duration_desc" <?php echo ($order_filter == 'activity_duration_desc') ? 'selected' : ''; ?>><?= __('Duration (Longest to Shortest)') ?></option>
                                    <option value="activity_duration_asc" <?php echo ($order_filter == 'activity_duration_asc') ? 'selected' : ''; ?>><?= __('Duration (Shortest to Longest)') ?></option>                                    
                                    <option value="registration_date_desc" <?php echo ($order_filter == 'registration_date_desc') ? 'selected' : ''; ?>><?= __('Registration Date (Latest to Oldest)') ?></option>
                                    <option value="registration_date_asc" <?php echo ($order_filter == 'registration_date_asc') ? 'selected' : ''; ?>><?= __('Registration Date (Oldest to Latest)') ?></option>
                                    <option value="activity_name_asc" <?php echo ($order_filter == 'activity_name_asc') ? 'selected' : ''; ?>><?= __('Activity Name (A-Z)') ?></option>
                                </select>
                            </div>
                            
                            <!-- Activity Status Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="status_filter" style="font-weight: bold;"><?= __('Activity Status:') ?></label><br>
                                <select name="status_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="only_active" <?php echo ($status_filter == 'only_active') ? 'selected' : ''; ?>><?= __('Only Active') ?></option>
                                    <option value="only_past" <?php echo ($status_filter == 'only_past') ? 'selected' : ''; ?>><?= __('Only Past') ?></option>
                                    <option value="only_in_trash" <?php echo ($status_filter == 'only_in_trash') ? 'selected' : ''; ?>><?= __('Only In Trash') ?></option>
                                    <option value="all_activities" <?php echo ($status_filter == 'all_activities') ? 'selected' : ''; ?>><?= __('All Activities') ?></option>
                                </select>
                            </div>

                            <!-- Activity Occupancy Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="occupancy_filter" style="font-weight: bold;"><?= __('Activity Occupancy:') ?></label><br>
                                <select name="occupancy_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="all_activities" <?php echo ($occupancy_filter == 'all_activities') ? 'selected' : ''; ?>><?= __('All Activities') ?></option>
                                    <option value="not_full" <?php echo ($occupancy_filter == 'not_full') ? 'selected' : ''; ?>><?= __('Not full') ?></option>
                                    <option value="full" <?php echo ($occupancy_filter == 'full') ? 'selected' : ''; ?>><?= __('Full') ?></option>
                                    <option value="empty" <?php echo ($occupancy_filter == 'empty') ? 'selected' : ''; ?>><?= __('Empty') ?></option>
                                </select>
                            </div>

                            <!-- Domain Filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;"><?= __('Domains:') ?></label><br>
                                <div>
                                    <label><input type="checkbox" name="domains_filter[]" value="Organization of community events" <?php echo (isset($_SESSION['volunteer_specific_domains_filter']) && in_array('Organization of community events', $_SESSION['volunteer_specific_domains_filter'])) ? 'checked' : ''; ?>> <?= __('Organization of community events') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Library support" <?php echo (isset($_SESSION['volunteer_specific_domains_filter']) && in_array('Library support', $_SESSION['volunteer_specific_domains_filter'])) ? 'checked' : ''; ?>> <?= __('Library support') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Help in the community store" <?php echo (isset($_SESSION['volunteer_specific_domains_filter']) && in_array('Help in the community store', $_SESSION['volunteer_specific_domains_filter'])) ? 'checked' : ''; ?>> <?= __('Help in the community store') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Support in the community grocery store" <?php echo (isset($_SESSION['volunteer_specific_domains_filter']) && in_array('Support in the community grocery store', $_SESSION['volunteer_specific_domains_filter'])) ? 'checked' : ''; ?>> <?= __('Support in the community grocery store') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Cleaning and maintenance of public spaces" <?php echo (isset($_SESSION['volunteer_specific_domains_filter']) && in_array('Cleaning and maintenance of public spaces', $_SESSION['volunteer_specific_domains_filter'])) ? 'checked' : ''; ?>> <?= __('Cleaning and maintenance of public spaces') ?></label><br>
                                    <label><input type="checkbox" name="domains_filter[]" value="Participation in urban gardening projects" <?php echo (isset($_SESSION['volunteer_specific_domains_filter']) && in_array('Participation in urban gardening projects', $_SESSION['volunteer_specific_domains_filter'])) ? 'checked' : ''; ?>> <?= __('Participation in urban gardening projects') ?></label><br>
                                </div>
                            </div>

                            <!-- Time Periods Filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;"><?= __('Activity Time Period:') ?></label><br>
                                <div>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Morning" <?php echo (isset($_SESSION['volunteer_specific_time_periods_filter']) && in_array('Morning', $_SESSION['volunteer_specific_time_periods_filter'])) ? 'checked' : ''; ?>> <?= __('Morning') ?></label><br>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Afternoon" <?php echo (isset($_SESSION['volunteer_specific_time_periods_filter']) && in_array('Afternoon', $_SESSION['volunteer_specific_time_periods_filter'])) ? 'checked' : ''; ?>> <?= __('Afternoon') ?></label><br>
                                    <label><input type="checkbox" name="time_periods_filter[]" value="Evening" <?php echo (isset($_SESSION['volunteer_specific_time_periods_filter']) && in_array('Evening', $_SESSION['volunteer_specific_time_periods_filter'])) ? 'checked' : ''; ?>> <?= __('Evening') ?></label><br>
                                </div>
                            </div>

                            <!-- Day Availability Filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;"><?= __('Activity Weekdays:') ?></label><br>
                                <div>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Monday" <?php echo (isset($_SESSION['volunteer_specific_available_days_filter']) && in_array('Monday', $_SESSION['volunteer_specific_available_days_filter'])) ? 'checked' : ''; ?>> <?= __('Monday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Tuesday" <?php echo (isset($_SESSION['volunteer_specific_available_days_filter']) && in_array('Tuesday', $_SESSION['volunteer_specific_available_days_filter'])) ? 'checked' : ''; ?>> <?= __('Tuesday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Wednesday" <?php echo (isset($_SESSION['volunteer_specific_available_days_filter']) && in_array('Wednesday', $_SESSION['volunteer_specific_available_days_filter'])) ? 'checked' : ''; ?>> <?= __('Wednesday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Thursday" <?php echo (isset($_SESSION['volunteer_specific_available_days_filter']) && in_array('Thursday', $_SESSION['volunteer_specific_available_days_filter'])) ? 'checked' : ''; ?>> <?= __('Thursday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Friday" <?php echo (isset($_SESSION['volunteer_specific_available_days_filter']) && in_array('Friday', $_SESSION['volunteer_specific_available_days_filter'])) ? 'checked' : ''; ?>> <?= __('Friday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Saturday" <?php echo (isset($_SESSION['volunteer_specific_available_days_filter']) && in_array('Saturday', $_SESSION['volunteer_specific_available_days_filter'])) ? 'checked' : ''; ?>> <?= __('Saturday') ?></label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="Sunday" <?php echo (isset($_SESSION['volunteer_specific_available_days_filter']) && in_array('Sunday', $_SESSION['volunteer_specific_available_days_filter'])) ? 'checked' : ''; ?>> <?= __('Sunday') ?></label>
                                </div>
                            </div>

                            <!-- Reset Filters Link -->
                            <div>
                                <a href="?reset_filters=1&volunteer_id=<?= $volunteer_id ?>" class="reset-link"><?= __('Reset Filter') ?></a>
                            </div>

                            <!-- Submit Button -->
                            <div style="text-align: center;">
                                <button name="apply_filter" type="submit" style="padding: 10px 20px; background-color: #405d9b; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
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
                            if ($total_activities_count == 0) {
                                echo __('No activities found.');
                            } else {
                                if ($total_activities_count == 1) {
                                    echo sprintf(__('1 activity found.'), $total_activities_count);
                                } else {
                                    $start = $offset + 1;
                                    $end   = min($offset + $items_per_page, $total_activities_count);
                                    echo sprintf(
                                    __('%1$d-%2$d of %3$d activities.'),
                                    $start,
                                    $end,
                                    $total_activities_count
                                    );
                                }
                            }
                        ?>

                        <!-- Display Activity Widgets --> 
                        <?php
                            if($all_activities_data_rows){
                                foreach($all_activities_data_rows as $activity_data_row){
                                    $activity_id = $activity_data_row['id'];
                                    $activity_time_periods_data_rows = fetch_data_rows("SELECT * FROM Activity_Time_Periods WHERE activity_id = '$activity_id'");
                                    $activity_domains_data_rows = fetch_data_rows("SELECT * FROM Activity_Domains WHERE activity_id = '$activity_id'");
                                    include(__DIR__ . "/../Widget_Pages/activity_widget.php");
                                }
                            }
                        ?>

                        <!-- Pagination -->
                        <?php
                        if ($total_pages > 1):
                            $current_params = $_GET;
                            unset($current_params['page']);
                            $query_string = http_build_query($current_params);

                            // How many links to show on either side of the current page
                            $window  = 2;
                            $start   = max(1, $page - $window);
                            $end     = min($total_pages, $page + $window);
                        ?>
                            <div class="pagination">
                                <!-- Previous button -->
                                <?php if ($page > 1): ?>
                                    <a href="?<?= $query_string ?>&page=<?= $page - 1 ?>" class="prev">‹ <?= __('Prev') ?></a>
                                <?php else: ?>
                                    <span class="disabled prev">‹ <?= __('Prev') ?></span>
                                <?php endif; ?>

                                <!-- Left ellipsis -->
                                <?php if ($start > 1): ?>
                                    <a href="?<?= $query_string ?>&page=1">1</a>
                                    <?php if ($start > 2): ?>
                                        <span class="ellipsis">…</span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- Page window -->
                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <?php if ($i == $page): ?>
                                        <span class="active"><?= $i ?></span>
                                    <?php else: ?>
                                        <a href="?<?= $query_string ?>&page=<?= $i ?>"><?= $i ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <!-- Right ellipsis -->
                                <?php if ($end < $total_pages): ?>
                                    <?php if ($end < $total_pages - 1): ?>
                                        <span class="ellipsis">…</span>
                                    <?php endif; ?>
                                    <a href="?<?= $query_string ?>&page=<?= $total_pages ?>"><?= $total_pages ?></a>
                                <?php endif; ?>

                                <!-- Next button -->
                                <?php if ($page < $total_pages): ?>
                                    <a href="?<?= $query_string ?>&page=<?= $page + 1 ?>" class="next"><?= __('Next') ?> ›</a>
                                <?php else: ?>
                                    <span class="disabled next"><?= __('Next') ?> ›</span>
                                <?php endif; ?>
                            </div>
                        <?php
                        endif;
                        ?>

                    </div>

                </div>
            </div>
        </div>
        
    </body>
</html>