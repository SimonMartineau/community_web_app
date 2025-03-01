<!-- PHP Code -->
<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    if (isset($_GET['purchase_id'])) {
        $purchase_id = $_GET['purchase_id'];

        $purchase_data_row = fetch_purchase_data_row($purchase_id);
        $volunteer_id = $purchase_data_row['volunteer_id'];
        $volunteer_data_row = fetch_volunteer_data_row($volunteer_id); // We link the correct owner of the purchase.

        $contract_id = $purchase_data_row['contract_id'];
        $contract_data_row = fetch_contract_data_row($contract_id);
    }

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Ensure the delete purchase button has been pressed
        if (isset($_POST['delete_purchase']) && $_POST['delete_purchase'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Purchases
            $delete_purchase_query = "delete from Purchases where id='$purchase_id'";
            $DB->update($delete_purchase_query);

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
        <title>Purchase Profile | Give and Receive</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../style.css">
    </head>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <script src="../functions.js"></script>

        <!-- Header Bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Edit Purchase Button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_purchase_data.php?purchase_id=<?php echo $purchase_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">edit_document</span>
                        <span>Edit Purchase Info</span>
                    </button>
                </a>
            </div>

            <!-- Delete Purchase Button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <form method="POST" action="../Profile_Pages/purchase_profile.php?purchase_id=<?php echo $purchase_id; ?>" onsubmit="return confirm('Are you sure you want to delete this purchase?')">
                    <button id="submenu_button">
                        <!-- Hidden input to confirm source -->
                        <input type="hidden" name="delete_purchase" value="1">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">delete_forever</span>
                        <span>Delete Purchase</span>
                    </button>
                </form>
            </div>
                    
            <!-- Below Cover Area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left Area : Purchase information area -->
                <div id="medium_rectangle" style="flex:0.57;">

                    <!-- Section Title of Contact Section -->
                    <div id="section_title">
                        <span>Purchase Info</span>
                    </div>

                    <!-- Warnings -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <?php if ($purchase_data_row['contract_id'] == -1): ?>
                            <h2 style="font-size: 20px; color: #555;">Warnings</h2>
                            <span class="warning" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;">warning</span>
                                Purchase date is not in any contract.
                            </span>
                            <span style="display: flex; align-items: center; width: 100%;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;">subdirectory_arrow_right</span>
                                Please change the purchase date so it's inside a contract.
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Purchase Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Information</h2>
                        <p><strong>Item Names:</strong> <?php echo htmlspecialchars($purchase_data_row['item_names']); ?></p>
                        <p><strong>Total Cost:</strong> <?php echo htmlspecialchars($purchase_data_row['total_cost']) . " Points"; ?></p>
                        <p><strong>Purchase Date:</strong> <?php echo htmlspecialchars(formatDate($purchase_data_row['purchase_date'])); ?></p>
                        <p><strong>Entry Clerk:</strong> <?php echo htmlspecialchars($purchase_data_row['entry_clerk']); ?></p>
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($purchase_data_row['additional_notes']) ?: 'None'; ?></p>
                    </div>
                </div>

                <!-- Right Area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget Display -->
                    <div id="medium_rectangle">

                        <!-- Toggle Buttons -->
                        <div id="widget_toggle_buttons">
                            <button class="active" onclick="ToggleWidgets('volunteer', this)">Show Volunteer</button>
                            <button onclick="ToggleWidgets('contract', this)">Show Contract</button>
                        </div>

                        <!-- Display Volunteer Widget -->
                        <div id="volunteer_widgets" class="widget-container">
                            <?php
                            if ($volunteer_data_row) {
                                $volunteer_id = $volunteer_data_row['id'];
                                $interest_data_rows = fetch_volunteer_interest_data_rows($volunteer_id);
                                $availability_data_rows = fetch_volunteer_availability_data_rows($volunteer_id);
                                include("../Widget_Pages/volunteer_widget.php");
                            }
                            ?>
                        </div>

                        <!-- Display Contract Widget -->
                        <div id="contract_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($contract_data_row) {
                                $volunteer_data_row = fetch_volunteer_data_row($volunteer_id);
                                $date = new DateTime($contract_data_row['issuance_date']);
                                $month = $date->format('F'); // Full month name (e.g., "January")
                                include("../Widget_Pages/contract_widget.php");
                            }
                            ?>
                        </div>
                        
                    </div>

                </div>

            </div>
            
        </div>
        
    </body>
</html>