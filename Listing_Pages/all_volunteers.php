<!-- PHP Code -->
<?php
    // Start session
    session_start();

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();

    // Updating all backend processes
    update_backend_data();

    // Check if the filter form is submitted, "apply_filter" is the name of the submit button
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply_filter'])) {
        $_SESSION['all_volunteer_order_filter'] = $_POST['order_filter'] ?? '';
        $_SESSION['all_volunteer_trash_filter'] = $_POST['trash_filter'] ?? '';  
        $_SESSION['all_volunteer_time_filter'] = $_POST['time_filter'] ?? '';
        $_SESSION['all_volunteer_interests_filter'] = $_POST['interests_filter'] ?? [];
        $_SESSION['all_volunteer_available_days_filter'] = $_POST['available_days_filter'] ?? [];
    }

    if (isset($_GET['reset_filters'])) {
        unset($_SESSION['all_volunteer_order_filter']);
        unset($_SESSION['all_volunteer_trash_filter']);
        unset($_SESSION['all_volunteer_time_filter']);
        unset($_SESSION['all_volunteer_interests_filter']);
        unset($_SESSION['all_volunteer_available_days_filter']);
    
        // Redirect to clear the query string
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Default entry values on page startup.
    $order_filter = $_SESSION['all_volunteer_order_filter'] ?? "registration_date_desc";
    $trash_filter = $_SESSION['all_volunteer_trash_filter'] ?? "only_active_volunteers";
    $time_filter = $_SESSION['all_volunteer_time_filter'] ?? "all_volunteers";
    $interests_filter = $_SESSION['all_volunteer_interests_filter'] ?? [];
    $available_days_filter = $_SESSION['all_volunteer_available_days_filter'] ?? [];

    // Default sql query
    $sql_filter_query = "SELECT DISTINCT v.* FROM Volunteers v";

    // Add JOIN only if interests filter is not empty
    if (!empty($interests_filter)) {
        $sql_filter_query .= " JOIN Volunteer_Interests vi ON v.id = vi.volunteer_id";
    }

    // Add JOIN only if availability filter is not empty
    if (!empty($available_days_filter)) {
        $sql_filter_query .= " JOIN Volunteer_Availability va ON v.id = va.volunteer_id";
    }

    // Initialize Where clause
    $sql_filter_query .= " WHERE 1=1";

    // Volunteer status filter
    if (!empty($trash_filter)){
        switch ($trash_filter){
            case 'only_active_volunteers':
                $sql_filter_query .= " AND v.trashed = '0'";
                break;
            case 'only_in_trash':
                $sql_filter_query .= " AND v.trashed = '1'";
                break;
            case 'all_volunteers':
                // No additional condition needed (show all volunteers)
                break;
        }
    }

    // Time filter
    if (!empty($time_filter)) {
        switch ($time_filter) {
            case 'all_volunteers':
                // No additional condition needed (show all volunteers)
                break;
            case 'time_completed':
                // Volunteers who have a time contract and have completed the hours.
                $sql_filter_query .= " AND v.hours_required > 0 AND v.hours_required <= hours_completed";
                break;
            case 'time_not_completed':
                // Volunteers who have a time contract and have not yet completed the hours.
                $sql_filter_query .= " AND v.hours_required > 0 AND v.hours_required > hours_completed";
                break;
            case 'no_contract':
                // Volunteers who do not currently have a contract
                $sql_filter_query .= " AND v.hours_required = 0";
                break;
        }
    }

    // Add interests filter
    if (!empty($interests_filter)) {
        $sql_filter_query .= " AND (";
        foreach ($interests_filter as $interest) {
            $sql_filter_query .= " vi.interest = '$interest' OR";
        }
        $sql_filter_query = rtrim($sql_filter_query, "OR") . ")"; // Remove the last "OR" and close the parentheses
    }

    // Add available days filter
    if (!empty($available_days_filter)) {
        $sql_filter_query .= " AND (";
        foreach ($available_days_filter as $weekday) {
            $sql_filter_query .= " va.weekday = '$weekday' OR";
        }
        $sql_filter_query = rtrim($sql_filter_query, "OR") . ")"; // Remove the last "OR" and close the parentheses
    }

    // Order of appearance filter
    if (!empty($order_filter)){
        switch ($order_filter){
            case 'registration_date_desc':
                $sql_filter_query .= " ORDER BY v.registration_date DESC";
                break;
            case 'registration_date_asc':
                $sql_filter_query .= " ORDER BY v.registration_date ASC";
                break;
            case 'points_asc':
                $sql_filter_query .= " ORDER BY v.points ASC";
                break;
            case 'points_desc':
                $sql_filter_query .= " ORDER BY v.points DESC";
                break;
            case 'hours_completed_asc':
                $sql_filter_query .= " ORDER BY v.hours_completed ASC";
                break;
            case 'hours_completed_desc':
                $sql_filter_query .= " ORDER BY v.hours_completed DESC";
                break;
            case 'first_name_asc':
                $sql_filter_query .= " ORDER BY v.first_name ASC";
                break;
            case 'last_name_asc':
                $sql_filter_query .= " ORDER BY v.last_name ASC";
                break;
        }
    }

    // Final query
    $all_volunteer_data_rows = fetch_data_rows($sql_filter_query);

?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Volunteers | Give and Receive</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header Bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Add Volunteer Button -->
            <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                <a href="../Add_Form_Pages/add_volunteer.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">person_add</span>
                        <span>New Volunteer</span>
                    </button>
                </a>
            </div>

            <!-- Below Cover Area -->
            <div style="display: flex;">

                <!-- Left Area -->
                <div style="flex:0.6;">

                    <!-- Filter Form Area -->
                    <div id="medium_rectangle">

                        <!-- Section Title of Filter Area -->
                        <div id="section_title">
                            <span>Filter</span>
                        </div>

                        <!-- Filter Form -->
                        <form action="" method="post">
                            <!-- Sort by Options -->
                            <div style="margin-bottom: 15px;">
                                <label for="order_filter" style="font-weight: bold;">Sort Volunteers By:</label><br>
                                <select name="order_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="registration_date_desc" <?php echo ($order_filter == 'registration_date_desc') ? 'selected' : ''; ?>>Registration Date (Latest to Oldest)</option>
                                    <option value="registration_date_asc" <?php echo ($order_filter == 'registration_date_asc') ? 'selected' : ''; ?>>Registration Date (Oldest to Latest)</option>
                                    <option value="points_asc" <?php echo ($order_filter == 'points_asc') ? 'selected' : ''; ?>>Points (Lowest to Highest)</option>
                                    <option value="points_desc" <?php echo ($order_filter == 'points_desc') ? 'selected' : ''; ?>>Points (Highest to Lowest)</option>
                                    <option value="hours_completed_asc" <?php echo ($order_filter == 'hours_completed_asc') ? 'selected' : ''; ?>>Hours Completed (Lowest to Highest)</option>
                                    <option value="hours_completed_desc" <?php echo ($order_filter == 'hours_completed_desc') ? 'selected' : ''; ?>>Hours Completed (Highest to Lowest)</option>
                                    <option value="first_name_asc" <?php echo ($order_filter == 'first_name_asc') ? 'selected' : ''; ?>>First Name (A-Z)</option>
                                    <option value="last_name_asc" <?php echo ($order_filter == 'last_name_asc') ? 'selected' : ''; ?>>Last Name (A-Z)</option>
                                </select>
                            </div>

                            <!-- Volunteer Status Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="trash_filter" style="font-weight: bold;">Volunteer Status:</label><br>
                                <select name="trash_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="only_active_volunteers" <?php echo ($trash_filter == 'only_active_volunteers') ? 'selected' : ''; ?>>Only Active Volunteers</option>
                                    <option value="only_in_trash" <?php echo ($trash_filter == 'only_in_trash') ? 'selected' : ''; ?>>Only In Trash</option>
                                    <option value="all_volunteers" <?php echo ($trash_filter == 'all_volunteers') ? 'selected' : ''; ?>>All Volunteers</option>
                                </select>
                            </div>

                            <!-- Time Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="time_filter" style="font-weight: bold;">Contract Status:</label><br>
                                <select name="time_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="all_volunteers" <?php echo ($time_filter == 'all_volunteers') ? 'selected' : ''; ?>>All Contracts</option>
                                    <option value="time_completed" <?php echo ($time_filter == 'time_completed') ? 'selected' : ''; ?>>Contract Completed</option>
                                    <option value="time_not_completed" <?php echo ($time_filter == 'time_not_completed') ? 'selected' : ''; ?>>Contract In Progress</option>
                                    <option value="no_contract" <?php echo ($time_filter == 'no_contract') ? 'selected' : ''; ?>>Volunteer Doesn't Have A Contract</option>
                                </select>
                            </div>

                            <!-- Interests Filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Interests:</label><br>
                                <div>
                                    <label><input type="checkbox" name="interests_filter[]" value="Organization of community events" <?php echo (isset($_SESSION['all_volunteer_interests_filter']) && in_array('Organization of community events', $_SESSION['all_volunteer_interests_filter'])) ? 'checked' : ''; ?>> Organization of community events</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Library support" <?php echo (isset($_SESSION['all_volunteer_interests_filter']) && in_array('Library support', $_SESSION['all_volunteer_interests_filter'])) ? 'checked' : ''; ?>> Library support</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Help in the community store" <?php echo (isset($_SESSION['all_volunteer_interests_filter']) && in_array('Help in the community store', $_SESSION['all_volunteer_interests_filter'])) ? 'checked' : ''; ?>> Help in the community store</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Support in the community grocery store" <?php echo (isset($_SESSION['all_volunteer_interests_filter']) && in_array('Support in the community grocery store', $_SESSION['all_volunteer_interests_filter'])) ? 'checked' : ''; ?>> Support in the community grocery store</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Cleaning and maintenance of public spaces" <?php echo (isset($_SESSION['all_volunteer_interests_filter']) && in_array('Cleaning and maintenance of public spaces', $_SESSION['all_volunteer_interests_filter'])) ? 'checked' : ''; ?>> Cleaning and maintenance of public spaces</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Participation in urban gardening projects" <?php echo (isset($_SESSION['all_volunteer_interests_filter']) && in_array('Participation in urban gardening projects', $_SESSION['all_volunteer_interests_filter'])) ? 'checked' : ''; ?>> Participation in urban gardening projects</label><br>
                                </div>
                            </div>

                            <!-- Day Availability Filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Available Days:</label><br>
                                <div>
                                    <label><input type="checkbox" name="available_days_filter[]" value="monday" <?php echo (isset($_SESSION['all_volunteer_available_days_filter']) && in_array('monday', $_SESSION['all_volunteer_available_days_filter'])) ? 'checked' : ''; ?>> Monday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="tuesday" <?php echo (isset($_SESSION['all_volunteer_available_days_filter']) && in_array('tuesday', $_SESSION['all_volunteer_available_days_filter'])) ? 'checked' : ''; ?>> Tuesday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="wednesday" <?php echo (isset($_SESSION['all_volunteer_available_days_filter']) && in_array('wednesday', $_SESSION['all_volunteer_available_days_filter'])) ? 'checked' : ''; ?>> Wednesday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="thursday" <?php echo (isset($_SESSION['all_volunteer_available_days_filter']) && in_array('thursday', $_SESSION['all_volunteer_available_days_filter'])) ? 'checked' : ''; ?>> Thursday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="friday" <?php echo (isset($_SESSION['all_volunteer_available_days_filter']) && in_array('friday', $_SESSION['all_volunteer_available_days_filter'])) ? 'checked' : ''; ?>> Friday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="saturday" <?php echo (isset($_SESSION['all_volunteer_available_days_filter']) && in_array('saturday', $_SESSION['all_volunteer_available_days_filter'])) ? 'checked' : ''; ?>> Saturday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="sunday" <?php echo (isset($_SESSION['all_volunteer_available_days_filter']) && in_array('sunday', $_SESSION['all_volunteer_available_days_filter'])) ? 'checked' : ''; ?>> Sunday</label>
                                </div>
                            </div>

                            <!-- Reset Filters Link -->
                            <div>
                                <a href="?reset_filters=1" class="reset-link">Reset Filter</a>
                            </div>

                            <!-- Submit Button -->
                            <div style="text-align: center;">
                                <button name="apply_filter" type="submit" style="padding: 10px 20px; background-color: #405d9b; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                                    Apply Filter
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
                            <span>Volunteers</span>
                        </div>

                        <!-- Counting Number of Elements Post Filter -->
                        <?php 
                        if (empty($all_volunteer_data_rows)) {
                            echo "No volunteers found.";
                        } elseif (count($all_volunteer_data_rows) == 1){
                            echo count($all_volunteer_data_rows) . " volunteer found.";
                        } else {
                            echo count($all_volunteer_data_rows) . " volunteers found.";
                        } ?>

                        <!-- Display Volunteer Widgets --> 
                        <?php
                            if($all_volunteer_data_rows){
                                foreach($all_volunteer_data_rows as $volunteer_data_row){
                                    $volunteer_id = $volunteer_data_row['id'];
                                    $interest_data_rows = fetch_volunteer_interest_data_rows($volunteer_id);
                                    $availability_data_rows = fetch_volunteer_availability_data_rows($volunteer_id);
                                    include("../Widget_Pages/volunteer_widget.php");
                                }
                            }
                        ?>
                        

                    </div>

                </div>
            </div>
            
        </div>
    </body>
</html>