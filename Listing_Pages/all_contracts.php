<!-- PHP Code -->
<?php
    // Start session
    session_start();

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

    // Check if the filter form is submitted, "apply_filter" is the name of the submit button
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply_filter'])) {
        $_SESSION['all_contracts_order_filter'] = $_POST['order_filter'] ?? '';
        $_SESSION['all_contracts_trash_filter'] = $_POST['trash_filter'] ?? '';  
        $_SESSION['all_contracts_active_contract_filter'] = $_POST['active_contract_filter'] ?? '';
        $_SESSION['all_contracts_earliest_date_filter'] = $_POST['earliest_date_filter'] ?? '';
        $_SESSION['all_contracts_latest_date_filter'] = $_POST['latest_date_filter'] ?? '';
    }

    if (isset($_GET['reset_filters'])) {
        unset($_SESSION['all_contracts_order_filter']);
        unset($_SESSION['all_contracts_trash_filter']);
        unset($_SESSION['all_contracts_active_contract_filter']);
        unset($_SESSION['all_contracts_earliest_date_filter']);
        unset($_SESSION['all_contracts_latest_date_filter']);
    
        // Redirect to the same page to remove the query string
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Default entry values on page startup.
    $order_filter = $_SESSION['all_contracts_order_filter'] ?? "start_date_desc";
    $trash_filter = $_SESSION['all_contracts_trash_filter'] ?? "active_volunteers_only";
    $active_contract_filter = $_SESSION['all_contracts_active_contract_filter'] ?? "active_contracts_only";
    $earliest_date_filter = $_SESSION['all_contracts_earliest_date_filter'] ?? '';
    $latest_date_filter = $_SESSION['all_contracts_latest_date_filter'] ?? '';

    // Default sql query
    $sql_filter_query = "SELECT DISTINCT c.* FROM Contracts c JOIN Volunteers v ON v.id = c.volunteer_id WHERE 1=1 AND c.user_id = '$user_id'";

    // Volunteer status filter
    if (!empty($trash_filter)){
        switch ($trash_filter){
            case 'active_volunteers_only':
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
        $sql_filter_query .= " AND '$earliest_date_filter' < c.start_date";
    }

    // Latest date filter
    if (!empty($latest_date_filter)){
        $sql_filter_query .= " AND  c.end_date < '$latest_date_filter'";
    }

    // Order of appearance filter
    if (!empty($order_filter)){
        switch ($order_filter){
            case 'start_date_desc':
                $sql_filter_query .= " ORDER BY c.start_date DESC";
                break;
            case 'start_date_asc':
                $sql_filter_query .= " ORDER BY c.start_date ASC";
                break;
            case 'validity_date_desc':
                $sql_filter_query .= " ORDER BY c.end_date DESC";
                break;
            case 'validity_date_asc':
                $sql_filter_query .= " ORDER BY c.end_date ASC";
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

?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contracts | Give and Receive</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/style.css">
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
                                    <option value="start_date_desc" <?php echo ($order_filter == 'start_date_desc') ? 'selected' : ''; ?>>Start Date (Latest to Oldest)</option>
                                    <option value="start_date_asc" <?php echo ($order_filter == 'start_date_asc') ? 'selected' : ''; ?>>Start Date (Oldest to Latest)</option>
                                    <option value="validity_date_desc" <?php echo ($order_filter == 'validity_date_desc') ? 'selected' : ''; ?>>End Date (Latest to Oldest)</option>
                                    <option value="validity_date_asc" <?php echo ($order_filter == 'validity_date_asc') ? 'selected' : ''; ?>>End Date (Oldest to Latest)</option>
                                    <option value="first_name_asc" <?php echo ($order_filter == 'first_name_asc') ? 'selected' : ''; ?>>First Name (A-Z)</option>
                                    <option value="last_name_asc" <?php echo ($order_filter == 'last_name_asc') ? 'selected' : ''; ?>>Last Name (A-Z)</option>
                                </select>
                            </div>

                            <!-- Volunteer Status Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="trash_filter" style="font-weight: bold;">Volunteer Status:</label><br>
                                <select name="trash_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="active_volunteers_only" <?php echo ($trash_filter == 'active_volunteers_only') ? 'selected' : ''; ?>>Only Active Volunteers</option>
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
                                    $volunteer_data_row = fetch_volunteer_data_row($user_id,$contract_data_row['volunteer_id']);
                                    $date = new DateTime($contract_data_row['start_date']);
                                    $month = $date->format('F'); // Full month name (ex: "January")
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