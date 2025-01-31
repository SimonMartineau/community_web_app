<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    if (isset($_GET['check_id'])) {
        $check_id = $_GET['check_id'];

        $check_data = fetch_check_data($check_id);
        $volunteer_id = $check_data['volunteer_id'];
        $volunteer_data_row = fetch_volunteer_data($volunteer_id); // We link the correct owner of the check.

        $purchases_data = fetch_data("
            SELECT * 
            FROM Purchases 
            WHERE check_id='$check_id' 
            ORDER BY id desc 
            LIMIT 6"
        );
    }

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Ensure the delete check button has been pressed
        if (isset($_POST['delete_check']) && $_POST['delete_check'] === '1') {

            // Initialise Database object
            $DB = new Database();

            // SQL query into Checks
            $delete_check_query = "delete from Checks where id='$check_id'";
            $DB->update($delete_check_query);

            // Changing the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
            die; // Ending the script
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Check Profile | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <script src="../functions.js"></script>

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Edit check button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_check_data.php?check_id=<?php echo $check_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Edit Check Info
                    </button>
                </a>
            </div>

            <!-- Delete check button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <form method="POST" action="../Profile_Pages/check_profile.php?check_id=<?php echo $check_id; ?>" onsubmit="return confirm('Are you sure you want to delete this check?')">
                    <!-- Hidden input to confirm source -->
                    <input type="hidden" name="delete_check" value="1">
                    <button id="submenu_button">
                        Delete Check
                    </button>
                </form>
            </div>

            <!-- Below cover area -->
            <div style="display: flex; align-items: flex-start;">

                <!-- Left area; Check information area -->
                <div id="medium_rectangle" style="flex:0.7;">

                    <!-- Section title of contact section -->
                    <div id="section_title">
                        <span>Check Info</span>
                    </div>

                    <!-- Check Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Personal Information</h2>

                        <?php
                            // Determine the message and color based on $check_active
                            if ($check_data['check_active'] == 1) {
                                $message = "Current Volunteer's Check";
                                $messageColor = "green";
                            } else {
                                $message = "Past Volunteer's Check";
                                $messageColor = "orange";
                            }
                        ?>

                        <?php if ($check_data['points_deposit'] - $check_data['points_spent'] < 0): ?>
                            <strong style="color: rgb(226, 65, 65); width: 100%;">Warning: Volunteer has spent too many points for this check.</strong><br>
                        <?php endif; ?>

                        <!-- Display the message with dynamic color -->
                        <p style="font-size: 16px; color: <?php echo $messageColor; ?>; font-weight: bold;">
                            <?php echo $message; ?>
                        </p>

                        <p><strong>Issuance Date:</strong> <?php echo htmlspecialchars(string: formatDate($check_data['issuance_date'])); ?></p>
                        <p><strong>Validity Date:</strong> <?php echo htmlspecialchars(formatDate($check_data['validity_date'])); ?></p>
                        <p><strong>Points Deposit:</strong> <?php echo htmlspecialchars($check_data['points_deposit']) . " Points"; ?></p>
                        <p><strong>Points Spent:</strong> <?php echo htmlspecialchars($check_data['points_spent']) . " Points"; ?></p>
                        <p><strong>Hours Required:</strong> <?php echo htmlspecialchars($check_data['hours_required']) . " Hours"; ?></p>
                        <p><strong>Hours Completed:</strong> <?php echo htmlspecialchars($check_data['hours_completed']) . " Hours"; ?></p>
                        <p><strong>Organizer Name:</strong> <?php echo htmlspecialchars($check_data['organizer_name']); ?></p>

                    </div>

                    <!-- Additional Details -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Additional Details</h2>
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($check_data['additional_notes']) ?: 'None'; ?></p>
                    </div>
                    
                </div>

                <!-- Right area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget display -->
                    <div id="medium_rectangle">

                        <!-- Toggle buttons -->
                        <div id="widget_toggle_buttons">
                            <button onclick="showWidgets_check_page('volunteer')">Show Volunteer</button>
                            <button onclick="showWidgets_check_page('purchases')">Show Purchases</button>
                            <button onclick="showWidgets_check_page('activities')">Show Activities</button>
                        </div>


                        <!-- Display volunteer widget -->
                        <div id="volunteer_widget" class="widget-container">
                            <?php
                            if ($volunteer_data_row) {
                                include("../Widget_Pages/volunteer_widget.php");
                            }
                            ?>
                        </div>                    
                        
                        <!-- Display purchase widgets -->
                        <div id="purchases_widgets" class="widget-container" style="display: none;">
                            <?php
                            if ($purchases_data) {
                                foreach ($purchases_data as $purchase_data_row) {
                                    $purchase_id = $purchase_data_row['id'];
                                    $volunteer_data = fetch_volunteer_data($volunteer_id);
                                    include("../Widget_Pages/purchase_widget.php");
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