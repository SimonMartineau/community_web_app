<!-- PHP Code -->
<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/edit_purchase_data.php");
    include("../Classes/functions.php");

    // Get purchase_id from the URL
    if (isset($_GET['purchase_id'])) {
        $purchase_id = $_GET['purchase_id'];
    }

    // Fetch SQL data
    $purchase_data_row = fetch_purchase_data_row($purchase_id);

    // Default entry values on page startup.
    $item_names = $purchase_data_row['item_names'];
    $total_cost = $purchase_data_row['total_cost'];
    $purchase_date = $purchase_data_row['purchase_date'];
    $entry_clerk = $purchase_data_row['entry_clerk'];
    $additional_notes = $purchase_data_row['additional_notes'];
    
    
    // Check if user has submitted info, we update entries.
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Create a Edit_Purchase object for form evaluation
        $purchase = new Edit_Purchase();
        $submit_success = $purchase->evaluate($purchase_id, $_POST); // Evaluate the form

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $item_names = $_POST['item_names'];
            $total_cost = $_POST['total_cost'];
            $purchase_date = $_POST['purchase_date'];
            $entry_clerk = $_POST['entry_clerk'];
            $additional_notes = $_POST['additional_notes'];
            
        } else{
            // There are no errors with the form submit, we can change the page.
            header("Location: ../Profile_Pages/purchase_profile.php?purchase_id=" . $purchase_id);
            die; // Ending the script
        }    
    }
?> 



<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Purchase Data | Give and Receive</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header Bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Middle Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major Rectangle Area -->
            <div id="major_rectangle">
                
                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Edit Purchase Data</span>
                </div>

                <!-- Error Message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?php echo isset($submit_success) ? "Missing information. Could not send. Please try again." : ""; ?>
                    </span>
                </div>

                <!-- Form Area -->
                <div id="form_section">

                    <!-- Form Text Input -->
                    <form method="post" action="../Edit_Form_Pages/edit_purchase_data.php?purchase_id=<?php echo $purchase_id; ?>">

                        <!-- Item Names Text Input -->
                        <div class="input_container">
                            Item names: 
                            <input name="item_names" type="text" id="text_input" value="<?php echo $item_names ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->item_names_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Total Cost Text Input -->
                        <div class="input_container">
                            Total cost: 
                            <input name="total_cost" type="text" id="text_input" value="<?php echo $total_cost ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->total_cost_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Purchase Date Input -->
                        <div class="input_container">
                            Purchase date: 
                            <input type="date" name="purchase_date" value="<?php echo $purchase_date ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->purchase_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Entry Clerk Text Input -->
                        <div class="input_container">
                            Entry clerk: 
                            <input name="entry_clerk" type="text" id="text_input" value="<?php echo $entry_clerk ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->entry_clerk_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Additional Notes Text Input -->
                        <div style="text-align: center">
                            Additional Notes:
                            <br>
                            <textarea name="additional_notes" rows="10" cols="60" id="additional_notes" placeholder="(Optional)"><?php echo $additional_notes ?></textarea>
                        </div>
                        <br><br>

                        <!-- Submit Button -->
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