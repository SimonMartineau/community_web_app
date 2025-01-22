<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/add_purchase.php");

    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];
    }

    // Default entry values on page startup.
    $item_names = "";
    $total_cost = "";
    $purchase_date = date("Y-m-d");
    $organizer_name = "";
    $additional_notes = "";

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $purchase = new Add_Purchase();
        $submit_success = $purchase->evaluate($volunteer_id, $_POST);

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $item_names = $_POST['item_names'];
            $total_cost = $_POST['total_cost'];
            $purchase_date = $_POST['purchase_date'];
            $organizer_name = $_POST['organizer_name'];
            $additional_notes = $_POST['additional_notes'];

        } else{
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
        <title>Add Purchase | Give and Receive</title>
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
                    <span style="font-size: 24px; font-weight: bold;">Purchase Form</span>
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
                    <form method="post" action="../Add_Form_Pages/add_purchase.php?volunteer_id=<?php echo $volunteer_id; ?>">

                        <!-- Item names text input -->
                        <div class="input_container">
                            <input name="item_names" type="text" id="text_input" placeholder="Item Names" value="<?php echo $item_names ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->item_names_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Total cost text input -->
                        <div class="input_container">
                            <input name="total_cost" type="text" id="text_input" placeholder="Total Points Cost" value="<?php echo $total_cost ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->total_cost_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Purchase date input -->
                        <div class="input_container">
                            Purchase date: <input type="date" name="purchase_date" value="<?php echo $purchase_date ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->purchase_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>
                        
                        <!-- Organizer Name text input -->
                        <div class="input_container">
                            <input name="organizer_name" type="text" id="text_input" placeholder="Organizer Name" value="<?php echo $organizer_name ?>">
                            <span id="error_message"><?php echo isset($purchase) ? $purchase->organizer_name_error_mes : ''; ?></span>
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