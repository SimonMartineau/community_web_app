<!-- PHP Code -->
<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    if (isset($_GET['contract_id'])) {
        $contract_id = $_GET['contract_id'];

        $contract_data_row = fetch_contract_data_row($contract_id);
        $volunteer_id = $contract_data_row['volunteer_id'];
        $volunteer_data_row = fetch_volunteer_data_row($volunteer_id); // We link the correct owner of the contract.

        $purchases_data_rows = fetch_data_rows("
            SELECT * 
            FROM Purchases 
            WHERE contract_id='$contract_id' 
            ORDER BY id desc "
        );

        $activities_data_rows = fetch_data_rows("
            SELECT a.* 
            FROM Activities a
            JOIN Volunteer_Activity_Junction vaj ON vaj.activity_id = a.id
            WHERE vaj.contract_id='$contract_id' 
            ORDER BY id desc "
        );
    }

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Ensure the delete contract button has been pressed
        if (isset($_POST['delete_contract']) && $_POST['delete_contract'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Contracts
            $delete_contract_query = "delete from Contracts where id='$contract_id'";
            $DB->save($delete_contract_query);

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
        <title>Contract Profile | Give and Receive</title>
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

            <!-- Edit Contract Button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_contract_data.php?contract_id=<?php echo $contract_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">edit_document</span>
                        <span>Edit Contract Info</span>
                    </button>
                </a>
            </div>

            <!-- Delete Contract Button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <form method="POST" action="../Profile_Pages/contract_profile.php?contract_id=<?php echo $contract_id; ?>" onsubmit="return confirm('Are you sure you want to delete this contract?')">
                    <button id="submenu_button">
                        <!-- Hidden input to confirm source -->
                        <input type="hidden" name="delete_contract" value="1">
                        <span class="material-symbols-outlined" style="margin-right: 8px;">delete_forever</span>
                        <span>Delete Contract</span>
                    </button>
                </form>
            </div>

            <!-- Below Cover Area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left Area; Contract Information Area -->
                <div id="medium_rectangle" style="flex:0.57;">

                    <!-- Section Title of Contact Section -->
                    <div id="section_title">
                        <span>Contract Info</span>
                    </div>

                    <!-- Warnings -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <?php if ($contract_data_row['points_deposit'] - $contract_data_row['points_spent'] < 0): ?>
                            <h2 style="font-size: 20px; color: #555;">Warnings</h2>
                            <span class="warning" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;">warning</span>
                                Volunteer has spent too many points for this contract.
                            </span>
                            <span style="display: flex; align-items: center; width: 100%;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;">subdirectory_arrow_right</span>
                                Please lower the points spent or increase the points deposit.
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Contract Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Information</h2>

                        <?php if ($contract_data_row['contract_active'] == 1) : ?>
                            <p><span class="upcoming" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;">contract</span>
                                    Contract is active.
                                </span>
                            </p>
                        <?php endif; ?>

                        <?php if ($contract_data_row['contract_active'] == 0) : ?>
                            <p><span class="valid" style="display: flex; align-items: center; width: 100%; font-weight: bold;">
                                <span class="material-symbols-outlined" style="margin-right: 5px;">check_circle</span>
                                    Contract is complete.
                                </span>
                            </p>
                        <?php endif; ?>

                        <!-- Display Contract Info -->
                        <p><strong>Issuance Date:</strong> <?php echo htmlspecialchars(string: formatDate($contract_data_row['issuance_date'])); ?></p>
                        <p><strong>Validity Date:</strong> <?php echo htmlspecialchars(formatDate($contract_data_row['validity_date'])); ?></p>
                        <p><strong>Points Deposit:</strong> <?php echo htmlspecialchars($contract_data_row['points_deposit']) . " Points"; ?></p>
                        <p><strong>Points Spent:</strong> <?php echo htmlspecialchars($contract_data_row['points_spent']) . " Points"; ?></p>
                        <p><strong>Hours Required:</strong> <?php echo htmlspecialchars($contract_data_row['hours_required']) . " Hours"; ?></p>
                        <p><strong>Hours Completed:</strong> <?php echo htmlspecialchars($contract_data_row['hours_completed']) . " Hours"; ?></p>
                        <p><strong>Entry Clerk:</strong> <?php echo htmlspecialchars($contract_data_row['entry_clerk']); ?></p>

                    </div>

                    <!-- Additional Details -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Additional Details</h2>
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($contract_data_row['additional_notes']) ?: 'None'; ?></p>
                    </div>
                    
                </div>

                <!-- Right Area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget Display -->
                    <div id="medium_rectangle">

                        <!-- Toggle Buttons -->
                        <div id="widget_toggle_buttons">
                            <button class="active" onclick="ToggleWidgets('volunteer', this)">Show Volunteer</button>
                            <button onclick="ToggleWidgets('purchases', this)">Show Purchases</button>
                            <button onclick="ToggleWidgets('activities', this)">Show Activities</button>
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
                        
                        <!-- Display Purchase Widgets -->
                        <div id="purchases_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($purchases_data_rows) {
                                foreach ($purchases_data_rows as $purchase_data_row) {
                                    $purchase_id = $purchase_data_row['id'];
                                    $volunteer_data_row = fetch_volunteer_data_row($volunteer_id);
                                    include("../Widget_Pages/purchase_widget.php");
                                }
                            }
                            ?>
                        </div>

                        <!-- Display Activities Widgets -->
                        <div id="activities_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($activities_data_rows) {
                                foreach ($activities_data_rows as $activity_data_row) {
                                    $activity_id = $activity_data_row['id'];
                                    $activity_time_periods_data_rows = fetch_data_rows("select * from Activity_Time_Periods where activity_id = '$activity_id'");
                                    $activity_domains_data_rows = fetch_data_rows("select * from Activity_Domains where activity_id = '$activity_id'");
                                    include("../Widget_Pages/activity_widget.php");
                                }
                            }
                            ?>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
        
    </body>
</html>