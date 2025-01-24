<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_volunteer_data();

    // Default entry values on page startup.
    $order_filter = "date_of_inscription_desc";
    $trash_filter = "only_active_volunteers";
    $time_filter = "all_volunteers";
    $area_filter = "all_areas";
    $gender_filter = "any_volunteer";
    $interests_filter = [];
    $available_days_filter = [];


    // Default page volunteer data
    $all_volunteer_data = fetch_data("select * from Volunteers where `trashed` = '0' order by id desc");

    // Getting filter form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve filter form data
        $order_filter = $_POST['order_filter'] ?? '';
        $trash_filter = $_POST['trash_filter'] ?? '';
        $time_filter = $_POST['time_filter'] ?? '';
        $area_filter = $_POST['area_filter'] ?? '';
        $gender_filter = $_POST['gender_filter'] ?? '';
        $interests_filter = $_POST['interests_filter'] ?? [];
        $available_days_filter = $_POST['available_days_filter'] ?? [];

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

        // Trash filter
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
                case 'time_completed':
                    // Volunteers who have a time contract and have completed the hours.
                    $sql_filter_query .= " AND v.hours_required > 0 AND v.hours_required <= hours_completed";
                    break;
                case 'time_not_completed':
                    // Volunteers who have a time contract and have not yet completed the hours.
                    $sql_filter_query .= " AND v.hours_required > 0 AND v.hours_required > hours_completed";
                    break;
                case 'no_check':
                    // Volunteers who do not currently have a check
                    $sql_filter_query .= " AND v.hours_required = 0";
                    break;
                case 'all_volunteers':
                    // No additional condition needed (show all volunteers)
                    break;
            }
        }

        // Area filter
        if (!empty($area_filter)){
            switch ($area_filter){
                case 'area_1':
                    $sql_filter_query .= " AND v.assigned_area = 'Area 1'";
                    break;
                case 'area_2':
                    $sql_filter_query .= " AND v.assigned_area = 'Area 2'";
                    break;
                case 'area_3':
                    $sql_filter_query .= " AND v.assigned_area = 'Area 3'";
                    break;
                case 'area_4':
                    $sql_filter_query .= " AND v.assigned_area = 'Area 4'";
                    break;
                case 'all_areas':
                    // No additional condition needed (show all volunteers)
                    break;
            }
        }

        // Gender filter
        if (!empty($gender_filter)){
            switch ($gender_filter){
                case 'only_male':
                    $sql_filter_query .= " AND v.gender = 'Male'";
                    break;
                case 'only_female':
                    $sql_filter_query .= " AND v.gender = 'Female'";
                    break;
                case 'only_other':
                    $sql_filter_query .= " AND v.gender = 'Other'";
                    break;
                case 'all_volunteers':
                    // No additional condition needed (show all volunteers)
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
                case 'first_name_asc':
                    $sql_filter_query .= " ORDER BY v.first_name ASC";
                    break;
                case 'first_name_desc':
                    $sql_filter_query .= " ORDER BY v.first_name DESC";
                    break;
                case 'last_name_asc':
                    $sql_filter_query .= " ORDER BY v.last_name ASC";
                    break;
                case 'last_name_desc':
                    $sql_filter_query .= " ORDER BY v.last_name DESC";
                    break;
                case 'age_asc':
                    $sql_filter_query .= " ORDER BY v.date_of_birth ASC";
                    break;
                case 'age_desc':
                    $sql_filter_query .= " ORDER BY v.date_of_birth DESC";
                    break;
            }
        }


        $all_volunteer_data = fetch_data($sql_filter_query);

    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Volunteers | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Add volunteer button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Add_Form_Pages/add_volunteer.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Add Volunteer
                    </button>
                </a>
            </div>

            <!-- See all Checks button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Listing_Pages/all_checks.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        See All Checks
                    </button>
                </a>
            </div>

            <!-- See all Purchases button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Listing_Pages/all_purchases.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        See All Purchases
                    </button>
                </a>
            </div>
     
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
                                    <option value="registration_date_desc" <?php echo ($order_filter == 'registration_date_desc') ? 'selected' : ''; ?>>Registration Date (Newest to Oldest)</option>
                                    <option value="registration_date_asc" <?php echo ($order_filter == 'registration_date_asc') ? 'selected' : ''; ?>>Registration Date (Oldest to Newest)</option>
                                    <option value="first_name_asc" <?php echo ($order_filter == 'first_name_asc') ? 'selected' : ''; ?>>First Name (A-Z)</option>
                                    <option value="first_name_desc" <?php echo ($order_filter == 'first_name_desc') ? 'selected' : ''; ?>>First Name (Z-A)</option>
                                    <option value="last_name_asc" <?php echo ($order_filter == 'last_name_asc') ? 'selected' : ''; ?>>Last Name (A-Z)</option>
                                    <option value="last_name_desc" <?php echo ($order_filter == 'last_name_desc') ? 'selected' : ''; ?>>Last Name (Z-A)</option>
                                    <option value="age_asc" <?php echo ($order_filter == 'age_asc') ? 'selected' : ''; ?>>Age (Youngest to Oldest)</option>
                                    <option value="age_desc" <?php echo ($order_filter == 'age_desc') ? 'selected' : ''; ?>>Age (Oldest to Youngest)</option>
                                </select>
                            </div>

                            <!-- Trash filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="trash_filter" style="font-weight: bold;">Volunteer Status:</label><br>
                                <select name="trash_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="only_active_volunteers" <?php echo ($trash_filter == 'only_active_volunteers') ? 'selected' : ''; ?>>Only Active Volunteers</option>
                                    <option value="only_in_trash" <?php echo ($trash_filter == 'only_in_trash') ? 'selected' : ''; ?>>Only In Trash</option>
                                    <option value="all_volunteers" <?php echo ($trash_filter == 'all_volunteers') ? 'selected' : ''; ?>>All Volunteers</option>
                                </select>
                            </div>

                            <!-- Time filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="time_filter" style="font-weight: bold;">Contract Status:</label><br>
                                <select name="time_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="all_volunteers" <?php echo ($time_filter == 'all_volunteers') ? 'selected' : ''; ?>>All Volunteers</option>
                                    <option value="time_completed" <?php echo ($time_filter == 'time_completed') ? 'selected' : ''; ?>>Check Time Requirement Completed</option>
                                    <option value="time_not_completed" <?php echo ($time_filter == 'time_not_completed') ? 'selected' : ''; ?>>Check Time Requirement Not Yet Completed</option>
                                    <option value="no_check" <?php echo ($time_filter == 'no_check') ? 'selected' : ''; ?>>Volunteer Doesn't Currently Have A Check</option>
                                </select>
                            </div>

                            <!-- Area filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="area_filter" style="font-weight: bold;">Assigned Area:</label><br>
                                <select name="area_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="all_areas" <?php echo ($area_filter == 'all_areas') ? 'selected' : ''; ?>>All Areas</option>
                                    <option value="area_1" <?php echo ($area_filter == 'area_1') ? 'selected' : ''; ?>>Area 1</option>
                                    <option value="area_2" <?php echo ($area_filter == 'area_2') ? 'selected' : ''; ?>>Area 2</option>
                                    <option value="area_3" <?php echo ($area_filter == 'area_3') ? 'selected' : ''; ?>>Area 3</option>
                                    <option value="area_4" <?php echo ($area_filter == 'area_4') ? 'selected' : ''; ?>>Area 4</option>
                                </select>
                            </div>

                            <!-- Gender filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="gender_filter" style="font-weight: bold;">Gender:</label><br>
                                <select name="gender_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="all_volunteers" <?php echo ($gender_filter == 'all_volunteers') ? 'selected' : ''; ?>>All Volunteers</option>
                                    <option value="only_male" <?php echo ($gender_filter == 'only_male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="only_female" <?php echo ($gender_filter == 'only_female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="only_other" <?php echo ($gender_filter == 'only_other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <!-- Interests filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Interests:</label><br>
                                <div>
                                    <label><input type="checkbox" name="interests_filter[]" value="Organization of community events" <?php echo (isset($_POST['interests_filter']) && in_array('Organization of community events', $_POST['interests_filter'])) ? 'checked' : ''; ?>> Organization of community events</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Library support" <?php echo (isset($_POST['interests_filter']) && in_array('Library support', $_POST['interests_filter'])) ? 'checked' : ''; ?>> Library support</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Help in the community store" <?php echo (isset($_POST['interests_filter']) && in_array('Help in the community store', $_POST['interests_filter'])) ? 'checked' : ''; ?>> Help in the community store</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Support in the community grocery store" <?php echo (isset($_POST['interests_filter']) && in_array('Support in the community grocery store', $_POST['interests_filter'])) ? 'checked' : ''; ?>> Support in the community grocery store</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Cleaning and maintenance of public spaces" <?php echo (isset($_POST['interests_filter']) && in_array('Cleaning and maintenance of public spaces', $_POST['interests_filter'])) ? 'checked' : ''; ?>> Cleaning and maintenance of public spaces</label><br>
                                    <label><input type="checkbox" name="interests_filter[]" value="Participation in urban gardening projects" <?php echo (isset($_POST['interests_filter']) && in_array('Participation in urban gardening projects', $_POST['interests_filter'])) ? 'checked' : ''; ?>> Participation in urban gardening projects</label><br>
                                </div>
                            </div>

                            <!-- Day availability filter -->
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Available Days:</label><br>
                                <div>
                                    <label><input type="checkbox" name="available_days_filter[]" value="monday" <?php echo (isset($_POST['available_days_filter']) && in_array('monday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Monday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="tuesday" <?php echo (isset($_POST['available_days_filter']) && in_array('tuesday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Tuesday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="wednesday" <?php echo (isset($_POST['available_days_filter']) && in_array('wednesday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Wednesday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="thursday" <?php echo (isset($_POST['available_days_filter']) && in_array('thursday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Thursday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="friday" <?php echo (isset($_POST['available_days_filter']) && in_array('friday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Friday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="saturday" <?php echo (isset($_POST['available_days_filter']) && in_array('saturday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Saturday</label><br>
                                    <label><input type="checkbox" name="available_days_filter[]" value="sunday" <?php echo (isset($_POST['available_days_filter']) && in_array('sunday', $_POST['available_days_filter'])) ? 'checked' : ''; ?>> Sunday</label>
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

                        <!-- Section title of recent social activities section -->
                        <div id="section_title">
                            <span>Volunteers</span>
                        </div>

                        <!-- Display volunteer widgets --> 
                        <?php
                            if($all_volunteer_data){
                                foreach($all_volunteer_data as $volunteer_data_row){
                                    $volunteer_id = $volunteer_data_row['id'];
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