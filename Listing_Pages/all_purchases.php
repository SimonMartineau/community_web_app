<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Collect volunteer data
    $all_purchases_data = fetch_data("
        SELECT p.* 
        FROM Purchases p
        INNER JOIN Volunteers m ON p.volunteer_id = m.id
        WHERE m.trashed = 0
        ORDER BY p.id DESC"
    );

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
                                <label for="sort" style="font-weight: bold;">Sort Volunteers By:</label><br>
                                <select name="sort" id="sort" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="alphabetically_a_z">Alphabetically (a-z)</option>
                                    <option value="alphabetically_z_a">Alphabetically (z-a)</option>
                                    <option value="issuance_date_asc">Issuance date (asc)</option>
                                    <option value="issuance_date_desc">Issuance date (desc)</option>
                                    <option value="validity_date_asc">Validity date (asc)</option>
                                    <option value="validity_date_desc">Validity date (desc)</option>
                                </select>
                            </div>

                            <!-- Points deposit filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="sort" style="font-weight: bold;">Points deposit:</label><br>
                                <input name="points_deposit" type="text" style="width: 96%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                            </div>

                            <!-- Hours required filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="gender" style="font-weight: bold;">Hours required:</label><br>
                                <input name="hours_required" type="text" style="width: 96%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                            </div>

                            <!-- Earliest date filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="sort" style="font-weight: bold;">Earliest date:</label><br>
                                <input name="earliest_date" type="date" value="<?php echo $earliest_date ?>" value="<?php echo $earliest_date ?>"style="width: 96%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                            </div>

                            <!-- Latest date filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="sort" style="font-weight: bold;">Latest date:</label><br>
                                <input name="latest_date" type="date" value="<?php echo $latest_date ?>" value="<?php echo $latest_date ?>"style="width: 96%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
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
                            <span>Purchases</span>
                        </div>

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