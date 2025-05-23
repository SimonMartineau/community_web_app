<!-- PHP Code -->
<?php
    // Include header
    include(__DIR__ . "/../Header/header.php");

    // Include classes
    include(__DIR__ . "/../Classes/connect.php");
    include(__DIR__ . "/../Classes/add_purchase.php");
    include(__DIR__ . "/../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();
    $user_id = $user_data['user_id'];

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
        $purchase = new Add_Purchase($user_id);
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
        <title><?= __('CivicLink | Add Purchase') ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="/CivicLink_Web_App/Styles/style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Middle Area -->
        <div style="width: 1600px; min-height: 400px; margin:auto;">
            
            <!-- Major Rectangle Area -->
            <div id="major_rectangle">

                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;"><?= __('Purchase Form') ?></span>
                </div>

                <!-- Error Message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?= isset($submit_success) ? __('Missing information. Could not send. Please try again.') : '' ?>
                    </span>
                </div>

                <!-- Form Area -->
                <form method="post" action="../Add_Form_Pages/add_purchase.php?volunteer_id=<?php echo $volunteer_id; ?>" class="form-layout" form>

                    <!-- Item Names Text Input -->
                    <span id="error_message"><?= isset($purchase) ? $purchase->item_names_error_mes : '' ?></span>
                    <div class="form-field">
                        <label for="item_names">
                            <?= __('Item Names:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the names of the purchased items (ex: Apples).') ?></span>
                            </span>
                        </label>
                        <input name="item_names" type="text" id="text_input" value="<?= $item_names ?>">
                    </div>

                    <!-- Total Cost Text Input -->
                    <span id="error_message"><?= isset($purchase) ? $purchase->total_cost_error_mes : '' ?></span>
                    <div class="form-field">
                        <label for="total_cost">
                            <?= __('Total Cost:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the total cost (in points) of the purchase (ex: 3).') ?></span>
                            </span>
                        </label>
                        <input name="total_cost" type="text" id="text_input" value="<?= $total_cost ?>">
                    </div>

                    <!-- Purchase Date Input -->
                    <span id="error_message"><?= isset($purchase) ? $purchase->purchase_date_error_mes : '' ?></span>
                    <div class="form-field">
                        <label for="purchase_date">
                            <?= __('Purchase Date:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the date of the purchase. Click the calendar icon to choose a date.') ?></span>
                            </span>
                        </label>
                        <input type="date" name="purchase_date" value="<?= $purchase_date ?>">
                    </div>

                    <!-- Entry Clerk Text Input -->
                    <span id="error_message"><?= isset($purchase) ? $purchase->entry_clerk_error_mes : '' ?></span>
                    <div class="form-field">
                        <label for="entry_clerk">
                            <?= __('Entry Clerk:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the name of the person filling out this form (ex: "Jane Smith").') ?></span>
                            </span>
                        </label>
                        <input name="entry_clerk" type="text" id="text_input" value="<?= $entry_clerk ?>">
                    </div>

                    <!-- Additional Notes Text Input -->
                    <div class="form-field form-field-top">
                        <label for="additional_notes">
                            <?= __('Additional Notes:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter any additional notes or comments about the volunteer. This field is optional.') ?></span>
                            </span>
                        </label>                   
                        <textarea name="additional_notes" rows="10" cols="60" id="additional_notes" placeholder="<?= __('(Optional)') ?>"><?= $additional_notes ?></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="input_container">
                        <input type="submit" id="submit_button" value="<?= __('Submit') ?>">
                    </div>

                </form>

            </div>
        </div>
              
    </body>
</html>