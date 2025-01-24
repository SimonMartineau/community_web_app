<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Default entry values on page startup
    $order_filter = "date_of_inscription_desc";
    $trash_filter = "only_active_volunteers";
    $earliest_date_filter = "";
    $latest_date_filter = "";

    // Default page checks data
    $all_checks_data = fetch_data("
        SELECT c.* 
        FROM Checks c
        INNER JOIN Volunteers m ON c.volunteer_id = m.id
        WHERE m.trashed = 0
        ORDER BY c.issuance_date DESC"
    );


    // Getting filter form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Retrieve filter form data
        $order_filter = $_POST['order_filter'] ?? '';
        $trash_filter = $_POST['trash_filter'] ?? '';
        $earliest_date_filter = $_POST['earliest_date_filter'] ?? '';
        $latest_date_filter = $_POST['latest_date_filter'] ?? '';

        // Default sql query
        $sql_filter_query = "SELECT DISTINCT c.* FROM Checks c JOIN Volunteers v ON v.id = c.volunteer_id WHERE 1=1 ";

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

        // Earliest date filter
        if (!empty($earliest_date_filter)){
            $sql_filter_query .= " AND '$earliest_date_filter' < c.issuance_date";
        }

        // Latest date filter
        if (!empty($latest_date_filter)){
            $sql_filter_query .= " AND  c.validity_date < '$latest_date_filter'";
        }

        // Order of appearance filter
        if (!empty($order_filter)){
            switch ($order_filter){
                case 'issuance_date_desc':
                    $sql_filter_query .= " ORDER BY c.issuance_date DESC";
                    break;
                case 'issuance_date_asc':
                    $sql_filter_query .= " ORDER BY c.issuance_date ASC";
                    break;
                case 'validity_date_desc':
                    $sql_filter_query .= " ORDER BY c.validity_date DESC";
                    break;
                case 'validity_date_asc':
                    $sql_filter_query .= " ORDER BY c.validity_date ASC";
                    break;
                case 'addition_order_desc':
                    $sql_filter_query .= " ORDER BY c.id DESC";
                    break;
                case 'addition_order_asc':
                    $sql_filter_query .= " ORDER BY c.id ASC";
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
            }
        }

        // Final query
        $all_checks_data = fetch_data($sql_filter_query);

    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checks | Give and Receive</title>
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
                                <label for="order_filter" style="font-weight: bold;">Sort Checks By:</label><br>
                                <select name="order_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="issuance_date_desc" <?php echo ($order_filter == 'issuance_date_desc') ? 'selected' : ''; ?>>Issuance Date (Newest to Oldest)</option>
                                    <option value="issuance_date_asc" <?php echo ($order_filter == 'issuance_date_asc') ? 'selected' : ''; ?>>Issuance Date (Oldest to Newest)</option>
                                    <option value="validity_date_desc" <?php echo ($order_filter == 'validity_date_desc') ? 'selected' : ''; ?>>Validity Date (Newest to Oldest)</option>
                                    <option value="validity_date_asc" <?php echo ($order_filter == 'validity_date_asc') ? 'selected' : ''; ?>>Validity Date (Oldest to Newest)</option>
                                    <option value="addition_order_desc" <?php echo ($order_filter == 'addition_order_desc') ? 'selected' : ''; ?>>Order of Addition (Newest to Oldest)</option>
                                    <option value="addition_order_asc" <?php echo ($order_filter == 'addition_order_asc') ? 'selected' : ''; ?>>Order of Addition (Oldest to Newest)</option>
                                    <option value="first_name_asc" <?php echo ($order_filter == 'first_name_asc') ? 'selected' : ''; ?>>First Name (A-Z)</option>
                                    <option value="first_name_desc" <?php echo ($order_filter == 'first_name_desc') ? 'selected' : ''; ?>>First Name (Z-A)</option>
                                    <option value="last_name_asc" <?php echo ($order_filter == 'last_name_asc') ? 'selected' : ''; ?>>Last Name (A-Z)</option>
                                    <option value="last_name_desc" <?php echo ($order_filter == 'last_name_desc') ? 'selected' : ''; ?>>Last Name (Z-A)</option>
                                </select>
                            </div>

                            <!-- Volunteer status filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="trash_filter" style="font-weight: bold;">Volunteer Status:</label><br>
                                <select name="trash_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="only_active_volunteers" <?php echo ($trash_filter == 'only_active_volunteers') ? 'selected' : ''; ?>>Only Active Volunteers</option>
                                    <option value="only_in_trash" <?php echo ($trash_filter == 'only_in_trash') ? 'selected' : ''; ?>>Only In Trash</option>
                                    <option value="all_volunteers" <?php echo ($trash_filter == 'all_volunteers') ? 'selected' : ''; ?>>All Volunteers</option>
                                </select>
                            </div>

                            <!-- Earliest date filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="earliest_date_filter" style="font-weight: bold;">Earliest date:</label><br>
                                <input name="earliest_date_filter" type="date" value="<?php echo $earliest_date_filter ?>" style="width: 96%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                            </div>

                            <!-- Latest date filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="latest_date_filter" style="font-weight: bold;">Latest date:</label><br>
                                <input name="latest_date_filter" type="date" value="<?php echo $latest_date_filter ?>" style="width: 96%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
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
                            <span>Checks</span>
                        </div>

                        <!-- Display checks widgets --> 
                        <?php
                            if($all_checks_data){
                                foreach($all_checks_data as $check_data_row){
                                    $check_id = $check_data_row['id'];
                                    $volunteer_data = fetch_volunteer_data($check_data_row['volunteer_id']);
                                    $date = new DateTime($check_data_row['issuance_date']);
                                    $month = $date->format('F'); // Full month name (e.g., "January")
                                    include("../Widget_Pages/check_widget.php");
                                }
                            }
                        ?>
        

                    </div>

                </div>
            </div>
            
        </div>
        
    </body>
</html>