<!-- PHP Code -->
<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    // Default entry values on page startup
    $order_filter = "date_of_inscription_desc";
    $trash_filter = "only_active_volunteers";
    $active_contract_filter = "active_contracts_only";
    $earliest_date_filter = "";
    $latest_date_filter = "";

    // Default page contracts data
    $all_contracts_data_rows = fetch_data_rows("
        SELECT c.* 
        FROM Contracts c
        INNER JOIN Volunteers m ON c.volunteer_id = m.id
        WHERE m.trashed = 0
        AND contract_active = 1
        ORDER BY c.issuance_date DESC"
    );


    // Getting filter form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Retrieve filter form data
        $order_filter = $_POST['order_filter'] ?? '';
        $trash_filter = $_POST['trash_filter'] ?? '';
        $active_contract_filter = $_POST['active_contract_filter'] ?? '';
        $earliest_date_filter = $_POST['earliest_date_filter'] ?? '';
        $latest_date_filter = $_POST['latest_date_filter'] ?? '';

        // Default sql query
        $sql_filter_query = "SELECT DISTINCT c.* FROM Contracts c JOIN Volunteers v ON v.id = c.volunteer_id WHERE 1=1 ";

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

        // Active contract filter
        if (!empty($active_contract_filter)){
            switch ($active_contract_filter){
                case 'active_contracts_only':
                    $sql_filter_query .= " AND contract_active = 1";
                    break;
                case 'past_contracts_only':
                    $sql_filter_query .= " AND contract_active = 0";
                    break;
                case 'all_contracts':
                    // No filter added
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
                case 'first_name_asc':
                    $sql_filter_query .= " ORDER BY v.first_name ASC";
                    break;
                case 'last_name_asc':
                    $sql_filter_query .= " ORDER BY v.last_name ASC";
                    break;
            }
        }

        // Final query
        $all_contracts_data_rows = fetch_data_rows($sql_filter_query);

    }
?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contracts | Give and Receive</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header Bar -->
        <?php include("../Misc/header.php"); ?>

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
                            <span>Filter</span>
                        </div>

                        <!-- Filter Form -->
                        <form action="" method="post">
                            <!-- Sort By Options -->
                            <div style="margin-bottom: 15px;">
                                <label for="order_filter" style="font-weight: bold;">Sort Contracts By:</label><br>
                                <select name="order_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="issuance_date_desc" <?php echo ($order_filter == 'issuance_date_desc') ? 'selected' : ''; ?>>Issuance Date (Latest to Oldest)</option>
                                    <option value="issuance_date_asc" <?php echo ($order_filter == 'issuance_date_asc') ? 'selected' : ''; ?>>Issuance Date (Oldest to Latest)</option>
                                    <option value="validity_date_desc" <?php echo ($order_filter == 'validity_date_desc') ? 'selected' : ''; ?>>Validity Date (Latest to Oldest)</option>
                                    <option value="validity_date_asc" <?php echo ($order_filter == 'validity_date_asc') ? 'selected' : ''; ?>>Validity Date (Oldest to Latest)</option>
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

                            <!-- Active Contract Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="active_contract_filter" style="font-weight: bold;">Contract Status:</label><br>
                                <select name="active_contract_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="active_contracts_only" <?php echo ($active_contract_filter == 'active_contracts_only') ? 'selected' : ''; ?>>Active Contracts</option>
                                    <option value="past_contracts_only" <?php echo ($active_contract_filter == 'past_contracts_only') ? 'selected' : ''; ?>>Past Contracts</option>
                                    <option value="all_contracts" <?php echo ($active_contract_filter == 'all_contracts') ? 'selected' : ''; ?>>All Contracts</option>
                                </select>
                            </div>

                            <!-- Earliest Date Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="earliest_date_filter" style="font-weight: bold;">Earliest date:</label><br>
                                <input name="earliest_date_filter" type="date" value="<?php echo $earliest_date_filter ?>" style="width: 96%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                            </div>

                            <!-- Latest Date Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="latest_date_filter" style="font-weight: bold;">Latest date:</label><br>
                                <input name="latest_date_filter" type="date" value="<?php echo $latest_date_filter ?>" style="width: 96%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                            </div>

                            <!-- Submit Button -->
                            <div style="text-align: center;">
                                <button type="submit" style="padding: 10px 20px; background-color: #405d9b; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
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
                            <span>Contracts</span>
                        </div>

                        <!-- Counting Number of Elements Post Filter -->
                        <?php 
                        if (empty($all_contracts_data_rows)) {
                            echo "No purchases found.";
                        } else {
                            echo count($all_contracts_data_rows) . " contracts found.";
                        } ?>

                        <!-- Display Contracts Widgets --> 
                        <?php
                            if($all_contracts_data_rows){
                                foreach($all_contracts_data_rows as $contract_data_row){
                                    $contract_id = $contract_data_row['id'];
                                    $volunteer_data_row = fetch_volunteer_data_row($contract_data_row['volunteer_id']);
                                    $date = new DateTime($contract_data_row['issuance_date']);
                                    $month = $date->format('F'); // Full month name (e.g., "January")
                                    include("../Widget_Pages/contract_widget.php");
                                }
                            }
                        ?>
        

                    </div>

                </div>
            </div>
            
        </div>
    </body>
</html>