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
                    <span style="font-size: 24px; font-weight: bold;">Add Volunteer Form</span>
                </div>

                <!-- Form area -->
                <div id="form_section">

                    <!-- Form text input -->
                    <form method="post" action="check.php">

                        <!-- Issuance date input -->
                        <div class="input_container">
                            Issuance date: <input name="issuance_date" type="date" value="<?php echo $issuance_date ?>">
                        </div>
                        <br><br>

                        <!-- Validity date input -->
                        <div class="input_container">
                            Validity date: <input name="validity_date" type="date" value="<?php echo $validity_date ?>">
                        </div>
                        <br><br>

                        <!-- Points text input -->
                        <div class="input_container">
                            <input name="points" type="text" id="text_input" placeholder="Number of points">
                        </div>
                        <br><br>
=
                        <!-- Hours requirement input -->
                        <div class="input_container">
                            <input name="hours_requirement" type="text" id="text_input" placeholder="Number of hours to do">
                        </div>
                        <br><br>

                        <!-- Organizer Name text input -->
                        <div class="input_container">
                            <input name="organizer_name" type="text" id="text_input" placeholder="Organizer Name" value="<?php echo $organizer_name ?>">
                        </div>
                        <br><br>

                        <!-- Submit button -->
                        <input type="submit" id="submit_button" value="Submit">
                        <br><br>
                    </form>
                </div>

                
            </div>
        </div>
            
        
    </body>
</html>