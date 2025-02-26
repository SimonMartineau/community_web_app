<!-- PHP Code -->
<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/add_purchase.php");
    include("../Classes/functions.php");

    // Get volunteer_id from the URL
    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];
    }

    // Default entry values on page startup.
    $item_names = "";
    $total_cost = "";
    $purchase_date = date("Y-m-d"); // Default value is the current date
    $entry_clerk = "";
    $additional_notes = "";
    

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Create a Add_Purchase object for form evaluation
        $purchase = new Add_Purchase();
        $submit_success = $purchase->evaluate($volunteer_id, $_POST); // Evaluate the form

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
        <title>Add Purchase | Give and Receive</title>
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
                    <span style="font-size: 24px; font-weight: bold;">Purchase Form</span>
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
                    <form method="post" action="../Add_Form_Pages/add_purchase.php?volunteer_id=<?php echo $volunteer_id; ?>">

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