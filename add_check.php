<?php

    // Include classes
    include("classes/connect.php");
    include("classes/add_check.php");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }

    // Default entry values on page startup.
    $issuance_date = date(format: "Y-m-d");
    $validity_date = date("Y-m-d", strtotime("+30 days")); // Add 30 days to the current date
    $points_deposit = "30";
    $required_time = "6";
    $organizer_name = "";
    $additional_notes = "";

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $check = new Add_Check();
        $submit_success = $check->evaluate($id, $_POST);

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $issuance_date = $_POST['issuance_date'];
            $validity_date = $_POST['validity_date'];
            $points_deposit = $_POST['points_deposit'];
            $required_time = $_POST['required_time'];
            $organizer_name = $_POST['organizer_name'];
            $additional_notes = $_POST['additional_notes'];

        } else{
            // Changing the page.
            header("Location: volunteer_profile.php?id=" . $id);
            die; // Ending the script
        }    
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Check | Give and Receive</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Middle area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major rectangle area -->
            <div id="major_rectangle">

                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Check Form</span>
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
                    <form method="post" action="add_check.php?id=<?php echo $id; ?>">

                        <!-- Issuance date input -->
                        <div class="input_container">
                            Issuance date: <input name="issuance_date" type="date" value="<?php echo $issuance_date ?>" value="<?php echo $issuance_date ?>">
                            <span id="error_message"><?php echo isset($check) ? $check->issuance_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Validity date input -->
                        <div class="input_container">
                            Validity date: <input name="validity_date" type="date" value="<?php echo $validity_date ?>" value="<?php echo $validity_date ?>">
                            <span id="error_message"><?php echo isset($check) ? $check->validity_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Points text input -->
                        <div class="input_container">
                            <input name="points_deposit" type="text" id="text_input" placeholder="Number of points" value="<?php echo $points_deposit ?>">
                            <span id="error_message"><?php echo isset($check) ? $check->points_deposit_error_mes : ''; ?></span>
                        </div>
                        <br><br>
                        
                        <!-- Time requirement input -->
                        <div class="input_container">
                            <input name="required_time" type="text" id="text_input" placeholder="Number of hours to do" value="<?php echo $required_time ?>">
                            <span id="error_message"><?php echo isset($check) ? $check->required_time_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Organizer Name text input -->
                        <div class="input_container">
                            <input name="organizer_name" type="text" id="text_input" placeholder="Organizer Name" value="<?php echo $organizer_name ?>">
                            <span id="error_message"><?php echo isset($check) ? $check->organizer_name_error_mes : ''; ?></span>
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