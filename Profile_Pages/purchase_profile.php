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

    if (isset($_GET['purchase_id'])) {
        $purchase_id = $_GET['purchase_id'];

        $purchase_data_row = fetch_purchase_data_row($user_id,$purchase_id);
        $volunteer_id = $purchase_data_row['volunteer_id'];
        $volunteer_data_row = fetch_volunteer_data_row($user_id,$volunteer_id); // We link the correct owner of the purchase.

        $contract_id = $purchase_data_row['contract_id'];
        $contract_data_row = fetch_contract_data_row($user_id,$contract_id);
    }

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Ensure the delete purchase button has been pressed
        if (isset($_POST['delete_purchase']) && $_POST['delete_purchase'] === '1') {

            // SQL query into Purchases
            $delete_purchase_query = "DELETE FROM Purchases WHERE id='$purchase_id'";
            $DB->save($delete_purchase_query);

            // Changing the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
            die; // Ending the script
        }
    }

?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= __('CivicLink | Purchase Profile') ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="/CivicLink_Web_App/Styles/style.css">
    </head>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <script src="../JavaScript/functions.js"></script>

        <!-- Cover Area -->
        <div style="width: 1600px; min-height: 400px; margin:auto;">
            <br>

            <!-- Edit Purchase Button -->
            <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                <a href="/CivicLink_Web_App/Edit_Form_Pages/edit_purchase_data.php?purchase_id=<?= $purchase_id ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">edit_document</span>
                        <span><?= __('Edit Purchase Profile') ?></span>
                    </button>
                </a>
            </div>

            <!-- Delete Purchase Button -->
            <div style="text-align: right; padding: 10px 20px; display: inline-block;">
                <form method="POST" action="../Profile_Pages/purchase_profile.php?purchase_id=<?= $purchase_id ?>" onsubmit="return confirm('<?= __('Are you sure you want to delete this purchase?') ?>')">
                    <button id="submenu_button">
                        <!-- Hidden input to confirm source -->
                        <input type="hidden" name="delete_purchase" value="1">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">delete_forever</span>
                        <span><?= __('Delete Purchase') ?></span>
                    </button>
                </form>
            </div>
                    
            <!-- Below Cover Area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left Area : Purchase information area -->
                <div id="medium_rectangle" style="flex:0.57;">

                    <!-- Section Title of Contact Section -->
                    <div id="section_title">
                        <span><?= __('Purchase Profile') ?></span>
                    </div>

                    <!-- Notifications -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <?php if ($purchase_data_row['contract_id'] == -1): ?>
                            <h2 style="font-size: 20px; color: #555;"><?= __('Notifications') ?></h2>
                            <span class="warning" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;">warning</span>
                                <?= __('Purchase date is not in any contract.') ?>
                            </span>
                            <span style="display: flex; align-items: center; width: 100%;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;">subdirectory_arrow_right</span>
                                <?= __('Please change the purchase date so it\'s inside a contract.') ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Purchase Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;"><?= __('Information') ?></h2>
                        <p><strong><?= __('Item Names:') ?></strong> <?php echo htmlspecialchars($purchase_data_row['item_names']); ?></p>
                        <p><strong><?= __('Total Cost:') ?></strong> <?php echo htmlspecialchars($purchase_data_row['total_cost']) . ' ' . __('Points'); ?></p>
                        <p><strong><?= __('Purchase Date:') ?></strong> <?php echo htmlspecialchars(formatDate($purchase_data_row['purchase_date'])); ?></p>
                        <p><strong><?= __('Entry Clerk:') ?></strong> <?php echo htmlspecialchars($purchase_data_row['entry_clerk']); ?></p>
                        <p><strong><?= __('Additional Notes:') ?></strong> <?php echo htmlspecialchars($purchase_data_row['additional_notes']) ?: __('None'); ?></p>
                    </div>
                </div>

                <!-- Right Area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget Display -->
                    <div id="medium_rectangle">

                        <!-- Toggle Buttons -->
                        <div id="widget_toggle_buttons">
                            <button class="active" onclick="ToggleWidgets('volunteer', this)"><?= __('Show Volunteer') ?></button>
                            <button onclick="ToggleWidgets('contract', this)"><?= __('Show Contract') ?></button>
                        </div>

                        <!-- Display Volunteer Widget -->
                        <div id="volunteer_widgets" class="widget-container">
                            <?php
                            if ($volunteer_data_row) {
                                $volunteer_id = $volunteer_data_row['id'];
                                $interest_data_rows = fetch_volunteer_interest_data_rows($user_id,$volunteer_id);
                                $availability_data_rows = fetch_volunteer_availability_data_rows($user_id,$volunteer_id);
                                include(__DIR__ . "/../Widget_Pages/volunteer_widget.php");
                            }
                            ?>
                        </div>

                        <!-- Display Contract Widget -->
                        <div id="contract_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($contract_data_row) {
                                $volunteer_data_row = fetch_volunteer_data_row($user_id,$volunteer_id);
                                $date = new DateTime($contract_data_row['start_date']);
                                $month = __($date->format('F'));
                                include(__DIR__ . "/../Widget_Pages/contract_widget.php");
                            }
                            ?>
                        </div>
                        
                    </div>

                </div>

            </div>
            
        </div>
        
    </body>
</html>