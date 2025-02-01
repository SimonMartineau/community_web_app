<?php

function fetch_data($query){

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}

function fetch_volunteer_data($volunteer_id){
    $query = "select * from Volunteers where id='$volunteer_id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result[0]; // To only return the row
    }else{
        return false;
    }
}

function fetch_volunteer_interest_data($volunteer_id){
    $query = "select * from Volunteer_Interests where volunteer_id='$volunteer_id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}

function fetch_volunteer_availability_data($volunteer_id){
    $query = "select * from Volunteer_Availability where volunteer_id='$volunteer_id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}

function fetch_check_data($check_id){
    $query = "select * from Checks where id='$check_id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result[0]; // To only return the row
    }else{
        return false;
    }
}

function fetch_purchase_data($purchase_id){
    $query = "select * from Purchases where id='$purchase_id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result[0]; // To only return the row
    }else{
        return false;
    }
}



// This function updates the backend data of the website and database.
function update_backend_data(){
    update_volunteer_data();
    update_check_data();
    update_purchase_data();
    update_activities_data();
}

// This function updates the points, hours_required and hours_completed for Volunteers table.
function update_volunteer_data(){

    // Fetching all volunteer data
    $all_volunteer_data = fetch_data("select * from Volunteers");

    // Initialise Database object
    $DB = new Database();

    // Navigating each volunteer to update one by one
    foreach($all_volunteer_data as $volunteer_data){

        // Current volunteer id
        $volunteer_id = $volunteer_data['id'];

        // Looking if the volunteer has a check in effect
        $current_check_query = "SELECT * FROM Checks WHERE CURRENT_DATE BETWEEN issuance_date AND validity_date AND volunteer_id = '$volunteer_id'";
        $current_check_data = fetch_data($current_check_query);

        // If there is no check in effect, $current_check_data = false and we skip updating the points.
        if ($current_check_data && !empty($current_check_data)) {
            // Access the first row
            $current_check_row = $current_check_data[0];

            // Getting the points_deposit from the check.
            $points = $current_check_row['points_deposit'];
            $hours_required = $current_check_row['hours_required'];

            $points -= $current_check_row['points_spent'];

        } else{
                // The volunteer has no check so no points;
                $points = 0;
                $hours_required = 0;
        }

        // SQL query into Volunteers
        $volunteers_query = "UPDATE Volunteers 
            SET points = '$points',
                hours_required = '$hours_required'
            WHERE id = '$volunteer_id';";

        $DB->update($volunteers_query);
    }
}

// This function updates the hours_required and hours_completed for Checks table.
function update_check_data(){

    // Fetching all volunteer data
    $all_checks_data = fetch_data("select * from Checks");

    // Initialise Database object
    $DB = new Database();

    // Navigating each volunteer to update one by one
    foreach($all_checks_data as $check_data){

        // Current volunteer id
        $check_id = $check_data['id'];

        // Getting the issuance_date and validity_date from the current check
        $issuance_date = $check_data['issuance_date'];
        $validity_date = $check_data['validity_date'];

        $points_spent = fetch_data("
            SELECT SUM(total_cost) 
            FROM Purchases 
            WHERE check_id='$check_id'
            AND purchase_date BETWEEN '$issuance_date' AND '$validity_date'"
        )[0]['SUM(total_cost)'] ?? 0;

        // Placeholder for hours_completed
        $hours_completed = 0;

        $check_active = fetch_data("
            SELECT 
                CASE 
                    WHEN CURRENT_DATE BETWEEN '$issuance_date' AND '$validity_date' THEN 1
                    ELSE 0
                END AS check_active
            FROM Checks
            WHERE id = '$check_id'
        ")[0]['check_active'] ?? 0;

        // SQL query into Volunteers
        $volunteers_query = "UPDATE Checks 
            SET points_spent = '$points_spent',
                hours_completed = '$hours_completed',
                check_active = '$check_active'
            WHERE id = '$check_id';";

        $DB->update($volunteers_query);
    }
}

// This function updates the hours_required and hours_completed for Checks table.
// This function updates the backend data of purchases.
function update_purchase_data(){

    // Fetching all volunteer data
    $all_purchases_data = fetch_data("select * from Purchases");

    // Initialise Database object
    $DB = new Database();

    // Navigating each volunteer to update one by one
    foreach($all_purchases_data as $purchase_data){

        $purchase_id = $purchase_data['id'];
        $purchase_date = $purchase_data['purchase_date'];
        $volunteer_id = $purchase_data['volunteer_id'];

        $result = fetch_data("
            SELECT id 
            FROM Checks
            WHERE '$purchase_date' BETWEEN issuance_date AND validity_date
            AND '$volunteer_id' = volunteer_id");

        // Get the first matching check ID or -1 if none found
        $check_id = $result[0]['id'] ?? -1;

        // SQL query into Purchases
        $purchases_query = "UPDATE Purchases 
            SET check_id = '$check_id'
            WHERE id = '$purchase_id';";

        $DB->update($purchases_query);
    }
}

// This function updates the backend data of activities.
function update_activities_data(){    

    // Initialise Database object
    $DB = new Database();

    // This SQL query associated every assigned activity to a volunteer's check if the purchase date matches. 
    // If not, default value (-1) is given to check_id in Volunteer_Activity_Junction
    $update_volunteer_activity_junction_data = "UPDATE Volunteer_Activity_Junction vaj
                        JOIN Activities a ON vaj.activity_id = a.id
                        SET vaj.check_id = COALESCE((
                            SELECT c.id
                            FROM Checks c
                            WHERE c.volunteer_id = vaj.volunteer_id
                            AND a.activity_date BETWEEN c.issuance_date AND c.validity_date
                            LIMIT 1
                        ), -1);
    ";

    // Update the database
    $DB->update($update_volunteer_activity_junction_data);

}



function formatDate(?string $date_str): string {
    if (empty($date_str)) {
        return 'No specific date provided';
    }
    
    $date = DateTime::createFromFormat('Y-m-d', $date_str);
    return $date ? $date->format('l, jS \o\f F Y') : 'Invalid date format';
}

?>