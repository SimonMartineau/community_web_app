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

function fetch_Volunteer_data($volunteer_id){
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





function update_backend_data(){
    // This function updates the backend data of the website and database.
    update_volunteer_data();
    update_check_data();
}

function update_volunteer_data(){
    // This function updates the points, hours_required and hours_completed for Volunteers table.

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



function update_check_data(){
    // This function updates the hours_required and hours_completed for Checks table.

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
?>