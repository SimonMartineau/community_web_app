<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/edit_purchase_data.php");
    include("../Classes/functions.php");

    if (isset($_GET['purchase_id'])) {
        $purchase_id = $_GET['purchase_id'];

        $purchase_data = fetch_purchase_data($purchase_id);
    }

    // Default entry values on page startup.
    $item_names = $purchase_data['item_names'];
    $total_cost = $purchase_data['total_cost'];
    $purchase_date = $purchase_data['purchase_date'];
    $entry_clerk = $purchase_data['entry_clerk'];
    $additional_notes = $purchase_data['additional_notes'];
    
    // Check if user has submitted info, we update entries.
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $purchase = new Edit_Purchase();
        $submit_success = $purchase->evaluate($purchase_id, $_POST);

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $item_names = $_POST['item_names'];
            $total_cost = $_POST['total_cost'];
            $purchase_date = $_POST['purchase_date'];
            $entry_clerk = $_POST['entry_clerk'];
            $additional_notes = $_POST['additional_notes'];
            
        } else{ // If there are no errors in the submission.
            // Changing the page.
            header("Location: ../Profile_Pages/purchase_profile.php?purchase_id=" . $purchase_id);
            die; // Ending the script
        }    
    }
?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Purchase Data | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Middle area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major rectangle area -->
            <div id="major_rectangle">
                
                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Edit Purchase Data</span>
                </div>

                <!-- Error message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?php echo isset($submit_success) ? "Missing information. Could not send. Please try again." : ""; ?>
                    </span>
                </div>

                <!-- Form area -->
                <div id="form_section">

                    <!-- Form text input -->
                    <form method="post" action="../Edit_Form_Pages/edit_purchase_data.php?purchase_id=<?php echo $purchase_id; ?>">

                        <!-- Item names text input -->
                        <div class="input_container">
                            Item names: 
                            <input name="item_names" type="text" id="text_input" value="<?php echo $item_names ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->item_names_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Total cost text input -->
                        <div class="input_container">
                            Total cost: 
                            <input name="total_cost" type="text" id="text_input" value="<?php echo $total_cost ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->total_cost_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Purchase date input -->
                        <div class="input_container">
                            Purchase date: 
                            <input type="date" name="purchase_date" value="<?php echo $purchase_date ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->purchase_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Entry Clerk text input -->
                        <div class="input_container">
                            Entry clerk: 
                            <input name="entry_clerk" type="text" id="text_input" value="<?php echo $entry_clerk ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->entry_clerk_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Additional notes text input -->
                        <div style="text-align: center">
                            Additional Notes:
                            <br>
                            <textarea name="additional_notes" rows="10" cols="60" id="additional_notes" placeholder="(Optional)"><?php echo $additional_notes ?></textarea>
                        </div>
                        <br><br>

                        <!-- Submit button -->
                        <div class="input_container">
                            <input type="submit" id="submit_button" value="Submit">
                        </div>
                        <br><br>
                        
                    </form>
                </div>

            </div>
        </div>
        
    </body>
</html>