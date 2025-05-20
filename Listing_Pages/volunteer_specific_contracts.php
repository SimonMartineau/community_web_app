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
    $order_filter = $_POST['order_filter'] ?? 'start_date_desc';
    $active_contract_filter = $_POST['active_contract_filter'] ?? 'all_contracts';
    $earliest_date_filter = $_POST['earliest_date_filter'] ?? '';
    $latest_date_filter = $_POST['latest_date_filter'] ?? '';

    // Default sql query
    $sql_filter_query = "SELECT DISTINCT c.* FROM Contracts c WHERE c.volunteer_id = '$volunteer_id' ";

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
            case 'addition_order_desc':
                $sql_filter_query .= " ORDER BY c.id DESC";
                break;
            case 'addition_order_asc':
                $sql_filter_query .= " ORDER BY c.id ASC";
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
        <title><?= __('CivicLink | Contracts') ?></title>
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
                                <label for="order_filter" style="font-weight: bold;"><?= __('Sort Contracts By:') ?></label><br>
                                <select name="order_filter" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ccc;">
                                    <option value="start_date_desc" <?= ($order_filter=='start_date_desc')?'selected':''; ?>><?= __('Start Date (Latest to Oldest)') ?></option>
                                    <option value="start_date_asc" <?= ($order_filter=='start_date_asc')?'selected':''; ?>><?= __('Start Date (Oldest to Latest)') ?></option>
                                    <option value="validity_date_desc" <?= ($order_filter=='validity_date_desc')?'selected':''; ?>><?= __('End Date (Latest to Oldest)') ?></option>
                                    <option value="validity_date_asc" <?= ($order_filter=='validity_date_asc')?'selected':''; ?>><?= __('End Date (Oldest to Latest)') ?></option>
                                </select>
                            </div>

                            <!-- Active Contract Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="active_contract_filter" style="font-weight: bold;"><?= __('Contract Status:') ?></label><br>
                                <select name="active_contract_filter" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ccc;">
                                    <option value="active_contracts_only" <?= ($active_contract_filter=='active_contracts_only')?'selected':''; ?>><?= __('Active Contracts') ?></option>
                                    <option value="past_contracts_only" <?= ($active_contract_filter=='past_contracts_only')?'selected':''; ?>><?= __('Past Contracts') ?></option>
                                    <option value="all_contracts" <?= ($active_contract_filter=='all_contracts')?'selected':''; ?>><?= __('All Contracts') ?></option>
                                </select>
                            </div>

                            <!-- Earliest Date Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="earliest_date_filter" style="font-weight: bold;"><?= __('Earliest date:') ?></label><br>
                                <input name="earliest_date_filter" type="date" value="<?= $earliest_date_filter ?>" style="width:96%; padding:8px; border-radius:5px; border:1px solid #ccc;">
                            </div>

                            <!-- Latest Date Filter -->
                            <div style="margin-bottom: 15px;">
                                <label for="latest_date_filter" style="font-weight: bold;"><?= __('Latest date:') ?></label><br>
                                <input name="latest_date_filter" type="date" value="<?= $latest_date_filter ?>" style="width:96%; padding:8px; border-radius:5px; border:1px solid #ccc;">
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
                            <span><?= __('Contracts') ?></span>
                        </div>

                        <!-- Counting Number of Elements Post Filter -->
                        <?php 
                        if (empty($all_contracts_data_rows)) {
                            echo __('No purchases found.');
                        } elseif (count($all_contracts_data_rows) == 1) {
                            echo '1 ' . __('contract found.');
                        } else {
                            echo count($all_contracts_data_rows) . ' ' . __('contracts found.');
                        }
                        ?>

                        <!-- Display Contracts Widgets --> 
                        <?php
                            if($all_contracts_data_rows){
                                foreach($all_contracts_data_rows as $contract_data_row){
                                    $contract_id = $contract_data_row['id'];
                                    $volunteer_data_row = fetch_volunteer_data_row($user_id,$contract_data_row['volunteer_id']);
                                    $date = new DateTime($contract_data_row['start_date']);
                                    $month = __($date->format('F'));
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