<?php

    // Include classes
    include("classes/connect.php");
    include("classes/functions.php");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $purchase_data = fetch_purchase_data($id);
        $volunteer_data_row = fetch_member_data($purchase_data['member_id']); // We link the correct owner of the purchase.

    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Purchase Profile | Give and Receive</title>
        <link rel="stylesheet" href="style.css">
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
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Edit purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="purchase_edit_data.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Edit Purchase Info
                    </button>
                </a>
            </div>

            <!-- Delete purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="purchase.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Delete Purchase
                    </button>
                </a>
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
                            include("volunteer_widget.php");
                        ?>
                        
                    </div>

                </div>

            </div>
            
        </div>
        
    </body>
</html>