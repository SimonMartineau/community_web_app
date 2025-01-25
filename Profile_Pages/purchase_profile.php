<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    if (isset($_GET['purchase_id'])) {
        $purchase_id = $_GET['purchase_id'];

        $purchase_data = fetch_purchase_data($purchase_id);
        $volunteer_id = $purchase_data['volunteer_id'];
        $volunteer_data_row = fetch_volunteer_data($volunteer_id); // We link the correct owner of the purchase.

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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Purchase Profile | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style>        
        .information_section {
            margin-bottom: 20px;
        }
        
        .information_section strong {
            display: inline-block;
            width: 150px;
            color: #555;
        }

    </style>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Edit purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_purchase_data.php?purchase_id=<?php echo $purchase_id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Edit Purchase Info
                    </button>
                </a>
            </div>

            <!-- Delete purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <form method="POST" action="../Profile_Pages/purchase_profile.php?purchase_id=<?php echo $purchase_id; ?>" onsubmit="return confirm('Are you sure you want to delete this purchase?')">
                    <!-- Hidden input to confirm source -->
                    <input type="hidden" name="delete_purchase" value="1">
                    <button id="submenu_button">
                        Delete Purchase
                    </button>
                </form>
            </div>
                    
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Left area; Purchase information area -->
                <div id="medium_rectangle" style="flex:0.7;">

                    <!-- Section title of contact section -->
                    <div id="section_title">
                        <span>Purchase Info</span>
                    </div>

                    <!-- Purchase Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Personal Information</h2>
                        <p><strong>Item Names:</strong> <?php echo htmlspecialchars($purchase_data['item_names']); ?></p>
                        <p><strong>Total Cost:</strong> <?php echo htmlspecialchars($purchase_data['total_cost']) . " Points"; ?></p>
                        <p><strong>purchase Date:</strong> <?php echo htmlspecialchars($purchase_data['purchase_date']); ?></p>
                        <p><strong>Organizer Name:</strong> <?php echo htmlspecialchars($purchase_data['organizer_name']); ?></p>

                    </div>

                    <!-- Additional Details -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Additional Details</h2>
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($purchase_data['additional_notes']) ?: 'None'; ?></p>
                    </div>
                    
                </div>

                <!-- Right area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget display -->
                    <div id="medium_rectangle">

                        <!-- Section title of recent social activities section -->
                        <div id="section_title">
                            <span>Volunteer</span>
                        </div>

                        <!-- Display volunteer widgets --> 
                        <?php
                            include("../Widget_Pages/volunteer_widget.php");
                        ?>
                        
                    </div>

                </div>

            </div>
            
        </div>
        
    </body>
</html>