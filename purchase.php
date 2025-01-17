<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Purchase | Give and Receive</title>
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
                    <form method="post" action="purchase.php">

                        <!-- Item name text input -->
                        <div class="input_container">
                            <input name="item_name" type="text" id="text_input" placeholder="Item Name" value="<?php echo $item_name ?>">
                        </div>
                        <br><br>

                        <!-- Item cost text input -->
                        <div class="input_container">
                            <input name="item_cost" type="text" id="text_input" placeholder="Item Points Cost" value="<?php echo $item_cost ?>">
                        </div>
                        <br><br>

                        <!-- Purchase date input -->
                        <div class="input_container">
                            Purchase date: <input type="date" name="purchase_date" value="<?php echo $purchase_date ?>">
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