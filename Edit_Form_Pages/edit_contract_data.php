<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/edit_contract_data.php");
    include("../Classes/functions.php");

    if (isset($_GET['contract_id'])) {
        $contract_id = $_GET['contract_id'];

        $contract_data = fetch_contract_data($contract_id);
    }

    // Default entry values on page startup.
    $issuance_date = $contract_data['issuance_date'];
    $validity_date = $contract_data['validity_date'];
    $points_deposit = $contract_data['points_deposit'];
    $hours_required = $contract_data['hours_required'];
    $organizer_name = $contract_data['organizer_name'];
    $additional_notes = $contract_data['additional_notes'];
    
    // Check if user has submitted info, we update entries.
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $contract = new Edit_Contract();
        $submit_success = $contract->evaluate($contract_id, $_POST);

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $issuance_date = $_POST['issuance_date'];
            $validity_date = $_POST['validity_date'];
            $points_deposit = $_POST['points_deposit'];
            $hours_required = $_POST['hours_required'];
            $organizer_name = $_POST['organizer_name'];
            $additional_notes = $_POST['additional_notes'];
            
        } else{ // If there are no errors in the submission.
            // Changing the page.
            header("Location: ../Profile_Pages/contract_profile.php?contract_id=" . $contract_id);
            die; // Ending the script
        }    
    }
?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Contract Data | Give and Receive</title>
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
                    <span style="font-size: 24px; font-weight: bold;">Edit Contract Data</span>
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
                    <form method="post" action="../Edit_Form_Pages/edit_contract_data.php?contract_id=<?php echo $contract_id; ?>">

                        <!-- Issuance date input -->
                        <div class="input_container">
                            Issuance date: <input name="issuance_date" type="date" value="<?php echo $issuance_date ?>" value="<?php echo $issuance_date ?>">
                            <span id="error_message"><?php echo isset($contract) ? $contract->issuance_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Validity date input -->
                        <div class="input_container">
                            Validity date: <input name="validity_date" type="date" value="<?php echo $validity_date ?>" value="<?php echo $validity_date ?>">
                            <span id="error_message"><?php echo isset($contract) ? $contract->validity_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Points text input -->
                        <div class="input_container">
                            Points deposit: <input name="points_deposit" type="text" id="text_input" placeholder="Number of points" value="<?php echo $points_deposit ?>">
                            <span id="error_message"><?php echo isset($contract) ? $contract->points_deposit_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Time requirement input -->
                        <div class="input_container">
                            Hours required: <input name="hours_required" type="text" id="text_input" placeholder="Number of hours to do" value="<?php echo $hours_required ?>">
                            <span id="error_message"><?php echo isset($contract) ? $contract->hours_required_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Organizer Name text input -->
                        <div class="input_container">
                            Organizer name:
                            <input name="organizer_name" type="text" id="text_input" placeholder="Organizer Name" value="<?php echo $organizer_name ?>">
                            <span id="error_message"><?php echo isset($contract) ? $contract->organizer_name_error_mes : ''; ?></span>
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