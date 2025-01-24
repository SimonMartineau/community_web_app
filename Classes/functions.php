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

            // Getting the issuance_date and validity_date from the current check
            $issuance_date = $current_check_row['issuance_date'];
            $validity_date = $current_check_row['validity_date'];

            // Looking at purchases done between issuance_date and validity_date.
            $current_purchase_query = "SELECT * FROM Purchases WHERE purchase_date BETWEEN '$issuance_date' AND '$validity_date' AND volunteer_id = '$volunteer_id'";
            $current_purchases_data = fetch_data($current_purchase_query);

            $total_cost = 0;

            if($current_purchases_data && !empty($current_purchases_data)){
                foreach($current_purchases_data as $purchase){
                    $total_cost += $purchase['total_cost'];
                }
                $points -= $total_cost;
            }

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
    echo "";
}
?>