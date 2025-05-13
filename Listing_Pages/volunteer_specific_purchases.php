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

    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];
    }

    // Retrieve filter form data
    $order_filter = $_POST['order_filter'] ?? 'purchase_date_desc';
    $earliest_date_filter = $_POST['earliest_date_filter'] ?? '';
    $latest_date_filter = $_POST['latest_date_filter'] ?? '';

    // Default sql query
    $sql_filter_query = "SELECT DISTINCT p.* FROM Purchases p WHERE p.volunteer_id = '$volunteer_id' ";

    // Earliest date filter
    if (!empty($earliest_date_filter)){
        $sql_filter_query .= " AND '$earliest_date_filter' < p.purchase_date";
    }

    // Latest date filter
    if (!empty($latest_date_filter)){
        $sql_filter_query .= " AND  p.purchase_date < '$latest_date_filter'";
    }

    // Order of appearance filter
    if (!empty($order_filter)){
        switch ($order_filter){
            case 'purchase_date_desc':
                $sql_filter_query .= " ORDER BY p.purchase_date DESC";
                break;
            case 'purchase_date_asc':
                $sql_filter_query .= " ORDER BY p.purchase_date ASC";
                break;
            case 'purchase_cost_asc':
                $sql_filter_query .= " ORDER BY p.total_cost ASC";
                break;
            case 'purchase_cost_desc':
                $sql_filter_query .= " ORDER BY p.total_cost DESC";
                break;
        }
    }

    // Final query
    $all_purchases_data_rows = fetch_data_rows($sql_filter_query);

?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CivicLink | Purchases</title>
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
                            <!-- Sort by Options -->
                            <div style="margin-bottom: 15px;">
                                <label for="order_filter" style="font-weight: bold;">Sort Volunteers By:</label><br>
                                <select name="order_filter" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                <option value="purchase_date_desc" <?php echo ($order_filter == 'purchase_date_desc') ? 'selected' : ''; ?>>Purchase Date (Latest to Oldest)</option>
                                    <option value="purchase_date_asc" <?php echo ($order_filter == 'purchase_date_asc') ? 'selected' : ''; ?>>Purchase Date (Oldest to Latest)</option>
                                    <option value="purchase_cost_asc" <?php echo ($order_filter == 'purchase_cost_asc') ? 'selected' : ''; ?>>Purchase Cost (Lowest to Highest)</option>
                                    <option value="purchase_cost_desc" <?php echo ($order_filter == 'purchase_cost_desc') ? 'selected' : ''; ?>>Purchase Cost (Highest to Lowest)</option>
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
                                <a href="" class="reset-link">Reset Filter</a>
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
                            <span>Purchases</span>
                        </div>

                        <!-- Counting Number of Elements Post Filter -->
                        <?php 
                        if (empty($all_purchases_data_rows)) {
                            echo "No purchases found.";
                        } else {
                            echo count($all_purchases_data_rows) . " purchases found.";
                        } ?>

                        <!-- Display Purchases Widgets --> 
                        <?php
                            if($all_purchases_data_rows){
                                foreach($all_purchases_data_rows as $purchase_data_row){
                                    $purchase_id = $purchase_data_row['id'];
                                    $volunteer_data_row = fetch_volunteer_data_row($user_id,$purchase_data_row['volunteer_id']);
                                    include("../Widget_Pages/purchase_widget.php");
                                }
                            }
                        ?>
        

                    </div>

                </div>
            </div>
        </div>
        
    </body>
</html>