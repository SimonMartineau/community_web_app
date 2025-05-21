<!-- PHP Code -->
<?php
    // Include header
    include("../Misc/header.php");

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Default to English if not set
    $lang = $_SESSION['lang'] ?? 'en';

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();
    $user_id = $user_data['user_id'];
?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= __('CivicLink | About') ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Cover Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">

            <!-- About Content Area -->
            <div id="major_rectangle">
                
                <!-- Section Title of About Section -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;"><?= __('About') ?></span>
                </div>

                <!-- Enhanced CivicLink Content -->
                <p style="line-height: 1.6; font-size: 16px; color: #333;">
                    <?= __('Welcome to') ?> <strong>CivicLink</strong>, <?= __('the intuitive platform for association managers to organize volunteers, activities, contracts, and purchases—all in one place.') ?>
                </p>

                <h3 style="margin-top: 20px; color: #405d9b;"><?= __('Home') ?></h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong><?= __('Overview:') ?></strong> <?= __('View key metrics and statistics at a glance—volunteer and activity interests and availability data.') ?></li>
                </ul>

                <h3 style="margin-top: 20px; color: #405d9b;"><?= __('Volunteers') ?></h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong><?= __('Description:') ?></strong> <?= __('Browse all registered volunteers, view their info or add new volunteers.') ?></li>
                    <li><strong><?= __('Filters:') ?></strong> <?= __('Refine by status (active, trashed), contract status (completed, in progress, none), interests, or weekly availability.') ?></li>
                    <li><strong><?= __('Buttons:') ?></strong>
                        <ul style="margin-left:20px;">
                            <li><strong><?= __('Add Volunteer:') ?></strong> <?= __('Register new volunteers via the') ?> <a href="../Add_Form_Pages/add_volunteer.php" style="color: #405d9b; text-decoration: none;"><?= __('Add Volunteer') ?></a><?= __(' form.') ?></li>
                        </ul>
                    </li>
                </ul>

                <h3 style="margin-top: 20px; color: #405d9b;"><?= __('Activities') ?></h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong><?= __('Description:') ?></strong> <?= __('Explore all scheduled and past activities, complete with interest, dates, and location data.') ?></li>
                    <li><strong><?= __('Filters:') ?></strong> <?= __('Narrow by status (active, past, trashed), occupancy (full, available, empty), interests, or weekly schedule.') ?></li>
                    <li><strong><?= __('Buttons:') ?></strong>
                        <ul style="margin-left:20px;">
                            <li><strong><?= __('Add Activity:') ?></strong> <?= __('Create new events using the') ?> <a href="../Add_Form_Pages/add_activity.php" style="color: #405d9b; text-decoration: none;"><?= __('Add Activity') ?></a><?= __(' button.') ?></li>
                        </ul>
                    </li>
                </ul>

                <h3 style="margin-top: 20px; color: #405d9b;"><?= __('Contracts') ?></h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong><?= __('Description:') ?></strong> <?= __('Browse all contracts to view dates, points remaining and activities completed.') ?></li>
                    <li><strong><?= __('Filters:') ?></strong> <?= __('Narrow by contract date or status.') ?></li>
                </ul>

                <h3 style="margin-top: 20px; color: #405d9b;"><?= __('Purchases') ?></h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong><?= __('Description:') ?></strong> <?= __('Browse spending done by all volunteers.') ?></li>
                    <li><strong><?= __('Filters:') ?></strong> <?= __('Filter by purchase date, status and amount for quick audits.') ?></li>
                </ul>

                <h3 style="margin-top: 20px; color: #405d9b;"><?= __('Volunteer Profile') ?></h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong><?= __('Description:') ?></strong> <?= __('View a volunteer\'s data, contracts and purchases, or assign them a new contract.') ?></li>
                    <li><strong><?= __('Buttons:') ?></strong>
                        <ul style="margin-left:20px;">
                            <li><?= __('Edit Volunteer Profile: Update the volunteer\'s information, interests, or availability.') ?></li>
                            <li><?= __('New Contract: Assign a contract directly.') ?></li>
                            <li><?= __('New Purchase: Record expense related to this volunteer.') ?></li>
                            <li><?= __('Trash Profile: Deactivate a volunteer\'s profile (reversible).') ?></li>
                        </ul>
                    </li>
                    <li><strong><?= __('Sections:') ?></strong>
                        <ul style="margin-left: 20px;">
                            <li><?= __('Latest Contracts: View recent agreements.') ?></li>
                            <li><?= __('Latest Purchases: Review recent expenses.') ?></li>
                            <li><?= __('Registered Activities: See events the volunteer is signed up for.') ?></li>
                            <li><?= __('Matching Activities: Find opportunities aligned with interests and schedule and assign them.') ?></li>
                        </ul>
                    </li>
                </ul>

                <h3 style="margin-top: 20px; color: #405d9b;"><?= __('Activity Profile') ?></h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong><?= __('Description:') ?></strong> <?= __('View an activity\'s data, or assign them to a new volunteer.') ?></li>
                    <li><strong><?= __('Buttons:') ?></strong>
                        <ul style="margin-left:20px;">
                            <li><?= __('Edit Activity Profile: Update an activity\'s information, interests, or availability.') ?></li>
                            <li><?= __('Trash Profile: Deactivate an activity\'s profile (reversible).') ?></li>
                        </ul>
                    </li>
                    <li><strong><?= __('Sections:') ?></strong>
                        <ul style="margin-left: 20px;">
                            <li><?= __('Show Participants: View the volunteer\'s assigned to the activity.') ?></li>
                            <li><?= __('Matching Volunteers: Find volunteers aligned with interests and schedule and assign them.') ?></li>
                        </ul>
                    </li>
                </ul>


            </div>
        </div>
    
    </body>
</html>