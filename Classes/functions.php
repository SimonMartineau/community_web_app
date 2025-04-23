<?php

// This file contains all the functions used in the website.


// This function generates a random number of a given length.
function random_num($length){
    $text = "";
    if($length < 5){
        $length = 5;
    }
    $len = rand(4, $length);
    for($i = 0; $i < $len; $i++){
        $text .= rand(0, 9);
    }
    return $text;
}

function fetch_data_rows($query){

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}

function fetch_volunteer_data_row($volunteer_id){
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

function fetch_volunteer_interest_data_rows($volunteer_id){
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

function fetch_volunteer_availability_data_rows($volunteer_id){
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

function fetch_contract_data_row($contract_id){
    $query = "select * from Contracts where id='$contract_id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result[0]; // To only return the row
    }else{
        return false;
    }
}

function fetch_purchase_data_row($purchase_id){
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
    update_junction_data();
    update_purchase_data();
    update_contract_data();
    update_volunteer_data();
    update_activities_data();
}


// This function updates the backend data of Volunteers (points, hours_required, hours_completed).
function update_volunteer_data(){
    // Initialise Database object
    $DB = new Database();

    // SQL query to update points, hours_required for Volunteers
    $points_hours_required_query = 
           "UPDATE Volunteers v
            LEFT JOIN Contracts c ON v.id = c.volunteer_id AND c.contract_active = 1
            SET v.points = COALESCE(c.points_deposit - c.points_spent, 0),
                v.hours_required = COALESCE(c.hours_required, 0);";

    $DB->save($points_hours_required_query);

    // SQL query to update hours_completed for Volunteers
    $hours_completed_query = 
           "UPDATE Volunteers v
            LEFT JOIN Contracts c ON v.id = c.volunteer_id AND c.contract_active = 1
            SET v.hours_completed = COALESCE(c.hours_completed, 0);";

    $DB->save($hours_completed_query);
}


// This function updates the backend data of Contracts (hours_required, hours_completed, contract_active).
function update_contract_data(){
    // Initialise Database object
    $DB = new Database();

    // SQL query to update points_spent for Contracts
    $points_spent_query =
    "UPDATE Contracts
            SET points_spent = (
                SELECT IFNULL(SUM(total_cost), 0)
                FROM Purchases
                WHERE Purchases.contract_id = Contracts.id
            );";

    $DB->save($points_spent_query);

    // SQL query to update hours_completed for Contracts
    $hours_completed_query = 
        "UPDATE Contracts c
                SET hours_completed = (
                    SELECT IFNULL(SUM(a.activity_duration), 0)
                    FROM Activities a
                    JOIN Volunteer_Activity_Junction vaj ON vaj.activity_id = a.id
                    WHERE vaj.contract_id = c.id
                );";
    
    $DB->save($hours_completed_query);

    // SQL query to update contract_active for Contracts
    $contract_active_query = 
       "UPDATE Contracts c
        SET c.contract_active = CASE
                                WHEN CURRENT_DATE BETWEEN c.issuance_date AND c.validity_date
                                    AND c.issuance_date = (
                                        SELECT MAX(issuance_date)
                                        FROM Contracts
                                        WHERE volunteer_id = c.volunteer_id
                                        AND CURRENT_DATE BETWEEN issuance_date AND validity_date
                                    ) THEN 1
                                ELSE 0
                            END;";

    $DB->save($contract_active_query);
}


// This function updates the backend data of Purchases (contract_id).
function update_purchase_data(){
    // Initialise Database object
    $DB = new Database();

    // SQL query to update contract_id for Purchases, defaults to -1 if no corresponding contract
    $purchase_contract_id_query =
       "UPDATE Purchases p
        SET p.contract_id = COALESCE((
            SELECT c.id
            FROM Contracts c
            WHERE c.volunteer_id = p.volunteer_id
            AND p.purchase_date BETWEEN c.issuance_date AND c.validity_date
            LIMIT 1
        ),-1);";

    $DB->save($purchase_contract_id_query);
    
}


// This function updates the backend data of Volunteer_Activity_Junction (contract_id).
function update_junction_data(){    
    // Initialise Database object
    $DB = new Database();

    // This SQL query associated every assigned activity to a volunteer's contract if the purchase date matches. 
    // If not, default value (-1) is given to contract_id in Volunteer_Activity_Junction
    $contract_id_query = 
           "UPDATE Volunteer_Activity_Junction vaj
            JOIN Activities a ON vaj.activity_id = a.id
            SET vaj.contract_id = COALESCE((
                SELECT c.id
                FROM Contracts c
                WHERE c.volunteer_id = vaj.volunteer_id
                AND a.activity_date BETWEEN c.issuance_date AND c.validity_date
                LIMIT 1
            ), -1);";

    // Update the database
    $DB->save(query: $contract_id_query);
}


// This function updates the backend data of Activities (number_of_participants).
function update_activities_data(){ 
    // Initialise Database object
    $DB = new Database();
    
    // SQL query to update number_of_participants for Activities
    $number_of_participants_query = 
           "UPDATE Activities a 
            SET a.number_of_participants = (
                SELECT COUNT(*) 
                FROM Volunteer_Activity_Junction vaj
                WHERE vaj.activity_id = a.id
            );";

    // Update the database
    $DB->save(query: $number_of_participants_query);
}



function formatDate(?string $date_str): string {
    if (empty($date_str)) {
        return 'No specific date provided';
    }
    
    $date = DateTime::createFromFormat('Y-m-d', $date_str);
    return $date ? $date->format('l, jS \o\f F Y') : 'Invalid date format';
}

?>