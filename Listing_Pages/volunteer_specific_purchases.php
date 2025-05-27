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
        $_SESSION['volunteer_specific_purchase_order_filter'] = $_POST['order_filter'] ?? '';
        $_SESSION['volunteer_specific_purchase_earliest_date_filter'] = $_POST['earliest_date_filter'] ?? '';
        $_SESSION['volunteer_specific_purchase_latest_date_filter'] = $_POST['latest_date_filter'] ?? '';

        // Reset to the first page after applying filters
        $page = 1;
    }

    // Check if the reset_filters parameter is set in the URL, if so reset the filters to default   
    if (isset($_GET['reset_filters'])) {
        unset($_SESSION['volunteer_specific_purchase_order_filter']);
        unset($_SESSION['volunteer_specific_purchase_earliest_date_filter']);
        unset($_SESSION['volunteer_specific_purchase_latest_date_filter']);
    
        // Redirect to avoid repeated resets on refresh
        header("Location: " . $_SERVER['PHP_SELF'] . "?volunteer_id=" . urlencode($volunteer_id));
        exit;
    }

    // Default entry values on page startup.
    $order_filter = $_SESSION['volunteer_specific_purchase_order_filter'] ?? "purchase_date_desc";
    $earliest_date_filter = $_SESSION['volunteer_specific_purchase_earliest_date_filter'] ?? '';
    $latest_date_filter = $_SESSION['volunteer_specific_purchase_latest_date_filter'] ?? '';

    // Default sql query
    $sql_filter_query = "SELECT DISTINCT p.* 
                            FROM Purchases p";

    // Initialize the Where clause
    $sql_where_clause = " WHERE 1=1 AND p.volunteer_id = '$volunteer_id'";

    // Earliest date filter
    if (!empty($earliest_date_filter)){
        $sql_where_clause .= " AND '$earliest_date_filter' < p.purchase_date";
    }

    // Latest date filter
    if (!empty($latest_date_filter)){
        $sql_where_clause .= " AND  p.purchase_date < '$latest_date_filter'";
    }

    // Order of appearance filter
    if (!empty($order_filter)){
        switch ($order_filter){
            case 'purchase_date_desc':
                $sql_where_clause .= " ORDER BY p.purchase_date DESC";
                break;
            case 'purchase_date_asc':
                $sql_where_clause .= " ORDER BY p.purchase_date ASC";
                break;
            case 'purchase_cost_asc':
                $sql_where_clause .= " ORDER BY p.total_cost ASC";
                break;
            case 'purchase_cost_desc':
                $sql_where_clause .= " ORDER BY p.total_cost DESC";
                break;
        }
    }

    // Append the WHERE clause to the main query
    $sql_filter_query .= $sql_where_clause;

    // After applying ORDER BY
    $sql_filter_query .= " LIMIT $items_per_page OFFSET $offset";
    
    // Final query
    $all_purchases_data_rows = fetch_data_rows($sql_filter_query);

    // Build count query using the same WHERE and JOINs
    $count_query = "SELECT COUNT(DISTINCT p.id) as total 
                        FROM Purchases p";

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
        <title><?= __('CivicLink | Purchases') ?></title>
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
                                <label for="order_filter" style="font-weight: bold;"><?= __('Sort Purchases By:') ?></label><br>
                                <select name="order_filter" style="width:100%; padding:8px; border-radius:5px; border:1px solid #ccc;">
                                    <option value="purchase_date_desc" <?= ($order_filter=='purchase_date_desc')?'selected':''; ?>><?= __('Purchase Date (Latest to Oldest)') ?></option>
                                    <option value="purchase_date_asc" <?= ($order_filter=='purchase_date_asc')?'selected':''; ?>><?= __('Purchase Date (Oldest to Latest)') ?></option>
                                    <option value="purchase_cost_asc" <?= ($order_filter=='purchase_cost_asc')?'selected':''; ?>><?= __('Purchase Cost (Lowest to Highest)') ?></option>
                                    <option value="purchase_cost_desc" <?= ($order_filter=='purchase_cost_desc')?'selected':''; ?>><?= __('Purchase Cost (Highest to Lowest)') ?></option>
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
                            <span><?= __('Purchases') ?></span>
                        </div>

                        <!-- Counting Number of Elements Post Filter -->
                        <?php
                            if ($total_activities_count == 0) {
                                echo __('No purchases found.');
                            } else {
                                if ($total_activities_count == 1) {
                                    echo sprintf(__('1 purchase found.'), $total_activities_count);
                                } else {
                                    $start = $offset + 1;
                                    $end   = min($offset + $items_per_page, $total_activities_count);
                                    echo sprintf(
                                    __('%1$d-%2$d of %3$d purchases.'),
                                    $start,
                                    $end,
                                    $total_activities_count
                                    );
                                }
                            }
                        ?>

                        <!-- Display Purchases Widgets --> 
                        <?php
                            if($all_purchases_data_rows){
                                foreach($all_purchases_data_rows as $purchase_data_row){
                                    $purchase_id = $purchase_data_row['id'];
                                    $volunteer_data_row = fetch_volunteer_data_row($user_id,$purchase_data_row['volunteer_id']);
                                    include(__DIR__ . "/../Widget_Pages/purchase_widget.php");
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