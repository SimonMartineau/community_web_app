<!-- PHP Code -->
<?php
    // Include header
    include("../Header/header.php");

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/edit_contract_data.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();
    $user_id = $user_data['user_id'];

    // Get contract_id from the URL
    if (isset($_GET['contract_id'])) {
        $contract_id = $_GET['contract_id'];
    }

    // Fetch SQL data
    $contract_data_row = fetch_contract_data_row($user_id,$contract_id);

    // Default entry values on page startup.
    $start_date = $contract_data_row['start_date'];
    $end_date = $contract_data_row['end_date'];
    $points_deposit = $contract_data_row['points_deposit'];
    $hours_required = $contract_data_row['hours_required'];
    $entry_clerk = $contract_data_row['entry_clerk'];
    $additional_notes = $contract_data_row['additional_notes'];
    
    
    // Check if user has submitted info, we update entries.
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Create a Edit_Contract object for form evaluation
        $contract = new Edit_Contract($user_id);
        $submit_success = $contract->evaluate($contract_id, $_POST); // Evaluate the form

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $points_deposit = $_POST['points_deposit'];
            $hours_required = $_POST['hours_required'];
            $entry_clerk = $_POST['entry_clerk'];
            $additional_notes = $_POST['additional_notes'];
            
        } else{
            // There are no errors with the form submit, we can change the page.
            header("Location: ../Profile_Pages/contract_profile.php?contract_id=" . $contract_id);
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
        <title><?= __('CivicLink | Edit Contract Data') ?></title>
        <link rel="icon" href="../Images/favicon.ico" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Middle Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major Rectangle Area -->
            <div id="major_rectangle">
                
                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;"><?= __('Edit Contract Data') ?></span>
                </div>

                <!-- Error Message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?php echo isset($submit_success) ? __("Missing information. Could not send. Please try again.") : ""; ?>
                    </span>
                </div>

                <!-- Form Area -->
                <form method="post" action="../Edit_Form_Pages/edit_contract_data.php?contract_id=<?php echo $contract_id; ?>" class="form-layout" form>

                    <!-- Start Date Input -->
                    <div class="form-field">
                        <label for="start_date">
                            <?= __('Start Date:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the date the contract is issued. Select the calendar icon to choose a date.') ?></span>
                            </span>
                        </label>
                        <input name="start_date" type="date" value="<?= $start_date ?>">
                        <span id="error_message"><?= isset($contract) ? $contract->start_date_error_mes : '' ?></span>
                    </div>

                    <!-- End Date Input -->
                    <div class="form-field">
                        <label for="end_date">
                            <?= __('End Date:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the date the contract is valid until. Select the calendar icon to choose a date.') ?></span>
                            </span>
                        </label>
                        <input name="end_date" type="date" value="<?= $end_date ?>">
                        <span id="error_message"><?= isset($contract) ? $contract->validity_date_error_mes : '' ?></span>
                    </div>

                    <!-- Points Text Input -->
                    <div class="form-field">
                        <label for="points_deposit">
                            <?= __('Points Deposit:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the number of points given to the volunteer for the contract (ex: 30). By default, it\'s 30 points.') ?></span>
                            </span>
                        </label>
                        <input name="points_deposit" type="text" id="text_input" value="<?= $points_deposit ?>">
                        <span id="error_message"><?= isset($contract) ? $contract->points_deposit_error_mes : '' ?></span>
                    </div>

                    <!-- Time Requirement Input -->
                    <div class="form-field">
                        <label for="hours_required">
                            <?= __('Hours Required:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the number of hours required for the contract (ex: 6). By default, it\'s 6 hours.') ?></span>
                            </span>
                        </label>
                        <input name="hours_required" type="text" id="text_input" value="<?= $hours_required ?>">
                        <span id="error_message"><?= isset($contract) ? $contract->hours_required_error_mes : '' ?></span>
                    </div>

                    <!-- Entry Clerk Text Input -->
                    <div class="form-field">
                        <label for="entry_clerk">
                            <?= __('Entry Clerk:') ?>
                            <span class="hint">?
                                <span class="hint-text"><?= __('Enter the name of the person filling out this form (ex: "Jane Smith").') ?></span>
                            </span>
                        </label>
                        <input name="entry_clerk" type="text" id="text_input" value="<?= $entry_clerk ?>">
                        <span id="error_message"><?= isset($contract) ? $contract->entry_clerk_error_mes : '' ?></span>
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