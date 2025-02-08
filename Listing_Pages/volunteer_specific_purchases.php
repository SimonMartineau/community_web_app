<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];
    }

    // Default entry values on page startup
    $order_filter = "purchase_date_desc";
    $earliest_date_filter = "";
    $latest_date_filter = "";

    // Collect volunteer data
    $all_purchases_data = fetch_data("
        SELECT p.* 
        FROM Purchases p
        WHERE p.volunteer_id='$volunteer_id' 
        ORDER BY p.purchase_date desc"
    );


    // Getting filter form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Retrieve filter form data
        $order_filter = $_POST['order_filter'] ?? '';
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
        $all_purchases_data = fetch_data($sql_filter_query);

    }

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Purchases | Give and Receive</title>
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
                                <option value="purchase_date_desc" <?php echo ($order_filter == 'purchase_date_desc') ? 'selected' : ''; ?>>Purchase Date (Latest to Oldest)</option>
                                    <option value="purchase_date_asc" <?php echo ($order_filter == 'purchase_date_asc') ? 'selected' : ''; ?>>Purchase Date (Oldest to Latest)</option>
                                    <option value="purchase_cost_asc" <?php echo ($order_filter == 'purchase_cost_asc') ? 'selected' : ''; ?>>Purchase Cost (Lowest to Highest)</option>
                                    <option value="purchase_cost_desc" <?php echo ($order_filter == 'purchase_cost_desc') ? 'selected' : ''; ?>>Purchase Cost (Highest to Lowest)</option>
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

                        <!-- Section title of recent activities section -->
                        <div id="section_title">
                            <span>Purchases</span>
                        </div>

                        <!-- Counting the number of elements post filter -->
                        <?php 
                        if (empty($all_purchases_data)) {
                            echo "No purchases found.";
                        } else {
                            echo count($all_purchases_data) . " purchases found.";
                        } ?>

                        <!-- Display purchases widgets --> 
                        <?php
                            if($all_purchases_data){
                                foreach($all_purchases_data as $purchase_data_row){
                                    $purchase_id = $purchase_data_row['id'];
                                    $volunteer_data = fetch_volunteer_data($purchase_data_row['volunteer_id']);
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