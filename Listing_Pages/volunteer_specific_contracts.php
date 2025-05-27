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
        $_SESSION['volunteer_specific_contract_order_filter'] = $_POST['order_filter'] ?? '';
        $_SESSION['volunteer_specific_contract_active_contract_filter'] = $_POST['active_contract_filter'] ?? '';
        $_SESSION['volunteer_specific_contract_earliest_date_filter'] = $_POST['earliest_date_filter'] ?? '';
        $_SESSION['volunteer_specific_contract_latest_date_filter'] = $_POST['latest_date_filter'] ?? '';

        // Reset to the first page after applying filters
        $page = 1;
    }

    // Check if the reset_filters parameter is set in the URL, if so reset the filters to default   
    if (isset($_GET['reset_filters'])) {
        unset($_SESSION['volunteer_specific_contract_order_filter']);
        unset($_SESSION['volunteer_specific_contract_active_contract_filter']);
        unset($_SESSION['volunteer_specific_contract_earliest_date_filter']);
        unset($_SESSION['volunteer_specific_contract_latest_date_filter']);
    
        // Redirect to the same page to remove the query string
        header("Location: " . $_SERVER['PHP_SELF'] . "?volunteer_id=" . urlencode($volunteer_id));
        exit;
    }

    // Default entry values on page startup.
    $order_filter = $_SESSION['volunteer_specific_contract_order_filter'] ?? "start_date_desc";
    $active_contract_filter = $_SESSION['volunteer_specific_contract_active_contract_filter'] ?? "all_contracts";
    $earliest_date_filter = $_SESSION['volunteer_specific_contract_earliest_date_filter'] ?? '';
    $latest_date_filter = $_SESSION['volunteer_specific_contract_latest_date_filter'] ?? '';

    // Default sql query
    $sql_filter_query = "SELECT DISTINCT c.* 
                            FROM Contracts c";

    // Initialize the WHERE clause
    $sql_where_clause = " WHERE 1=1 AND c.volunteer_id = '$volunteer_id'";

    // Active contract filter
    if (!empty($active_contract_filter)){
        switch ($active_contract_filter){
            case 'active_contracts_only':
                $sql_where_clause .= " AND contract_active = 1";
                break;
            case 'past_contracts_only':
                $sql_where_clause .= " AND contract_active = 0";
                break;
            case 'all_contracts':
                // No filter added
                break;
        }
    }

    // Earliest date filter
    if (!empty($earliest_date_filter)){
        $sql_where_clause .= " AND '$earliest_date_filter' < c.start_date";
    }

    // Latest date filter
    if (!empty($latest_date_filter)){
        $sql_where_clause .= " AND  c.end_date < '$latest_date_filter'";
    }

    // Order of appearance filter
    if (!empty($order_filter)){
        switch ($order_filter){
            case 'start_date_desc':
                $sql_where_clause .= " ORDER BY c.start_date DESC";
                break;
            case 'start_date_asc':
                $sql_where_clause .= " ORDER BY c.start_date ASC";
                break;
            case 'validity_date_desc':
                $sql_where_clause .= " ORDER BY c.end_date DESC";
                break;
            case 'validity_date_asc':
                $sql_where_clause .= " ORDER BY c.end_date ASC";
                break;
            case 'addition_order_desc':
                $sql_where_clause .= " ORDER BY c.id DESC";
                break;
            case 'addition_order_asc':
                $sql_where_clause .= " ORDER BY c.id ASC";
                break;
        }
    }

    // Append the WHERE clause to the main query
    $sql_filter_query .= $sql_where_clause;

    // After applying ORDER BY
    $sql_filter_query .= " LIMIT $items_per_page OFFSET $offset";

    // Final query
    $all_contracts_data_rows = fetch_data_rows($sql_filter_query);

    // Build count query using the same WHERE and JOINs
    $count_query = "SELECT COUNT(DISTINCT c.id) as total 
                        FROM Contracts c 
                        JOIN Volunteers v ON v.id = c.volunteer_id";

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
        <title><?= __('CivicLink | Contracts') ?></title>
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
                                <a href="?reset_filters=1&volunteer_id=<?= $volunteer_id ?>" class="reset-link"><?= __('Reset Filter') ?></a>
                            </div>

                            <!-- Submit Button -->
                            <div style="text-align: center;">
                                <button name="apply_filter" type="submit" style="padding:10px 20px; background-color:#405d9b; color:white; border:none; border-radius:5px; font-size:16px; cursor:pointer;">
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
                            if ($total_activities_count == 0) {
                                echo __('No contracts found.');
                            } else {
                                if ($total_activities_count == 1) {
                                    echo sprintf(__('1 contract found.'), $total_activities_count);
                                } else {
                                    $start = $offset + 1;
                                    $end   = min($offset + $items_per_page, $total_activities_count);
                                    echo sprintf(
                                    __('%1$d-%2$d of %3$d contracts.'),
                                    $start,
                                    $end,
                                    $total_activities_count
                                    );
                                }
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
                                    include(__DIR__ . "/../Widget_Pages/contract_widget.php");
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