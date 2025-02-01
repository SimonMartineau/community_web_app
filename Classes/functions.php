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
    update_junction_data();
    update_activities_data();
}


// This function updates the backend data of Volunteers (points, hours_required, hours_completed).
function update_volunteer_data(){
    // Initialise Database object
    $DB = new Database();

    // SQL query to update points, hours_required for Volunteers
    $points_hours_required_query = 
           "UPDATE Volunteers v
            LEFT JOIN Checks c ON v.id = c.volunteer_id AND c.check_active = 1
            SET v.points = COALESCE(c.points_deposit - c.points_spent, 0),
                v.hours_required = COALESCE(c.hours_required, 0);";

    $DB->update($points_hours_required_query);

    // SQL query to update hours_completed for Volunteers
    $hours_completed_query = 
           "UPDATE Volunteers v
            LEFT JOIN Checks c ON v.id = c.volunteer_id AND c.check_active = 1
            SET v.hours_completed = COALESCE(c.hours_completed, 0);";

    $DB->update($hours_completed_query);
}


// This function updates the backend data of Checks (hours_required, hours_completed, check_active).
function update_check_data(){
    // Initialise Database object
    $DB = new Database();

    // SQL query to update points_spent for Checks
    $points_spent_query =
    "UPDATE Checks
            SET points_spent = (
                SELECT IFNULL(SUM(total_cost), 0)
                FROM Purchases
                WHERE Purchases.check_id = Checks.id
            );";

    $DB->update($points_spent_query);

    // SQL query to update hours_completed for Checks
    $hours_completed_query = 
        "UPDATE Checks c
                SET hours_completed = (
                    SELECT IFNULL(SUM(a.activity_duration), 0)
                    FROM Activities a
                    JOIN Volunteer_Activity_Junction vaj ON vaj.activity_id = a.id
                    WHERE vaj.check_id = c.id
                );";
    
    $DB->update($hours_completed_query);

    // SQL query to update check_active for Checks
    $check_active_query = 
       "UPDATE Checks c
        SET c.check_active = CASE
                                WHEN CURRENT_DATE BETWEEN c.issuance_date AND c.validity_date
                                    AND c.issuance_date = (
                                        SELECT MAX(issuance_date)
                                        FROM Checks
                                        WHERE volunteer_id = c.volunteer_id
                                        AND CURRENT_DATE BETWEEN issuance_date AND validity_date
                                    ) THEN 1
                                ELSE 0
                            END;";

    $DB->update($check_active_query);
}


// This function updates the backend data of Purchases (check_id).
function update_purchase_data(){
    // Initialise Database object
    $DB = new Database();

    // SQL query to update check_id for Purchases, defaults to -1 if no corresponding check
    $purchase_check_id_query =
       "UPDATE Purchases p
        SET p.check_id = COALESCE((
            SELECT c.id
            FROM Checks c
            WHERE c.volunteer_id = p.volunteer_id
            AND p.purchase_date BETWEEN c.issuance_date AND c.validity_date
            LIMIT 1
        ),-1);";

    $DB->update($purchase_check_id_query);
    
}


// This function updates the backend data of Volunteer_Activity_Junction (check_id).
function update_junction_data(){    
    // Initialise Database object
    $DB = new Database();

    // This SQL query associated every assigned activity to a volunteer's check if the purchase date matches. 
    // If not, default value (-1) is given to check_id in Volunteer_Activity_Junction
    $check_id_query = 
           "UPDATE Volunteer_Activity_Junction vaj
            JOIN Activities a ON vaj.activity_id = a.id
            SET vaj.check_id = COALESCE((
                SELECT c.id
                FROM Checks c
                WHERE c.volunteer_id = vaj.volunteer_id
                AND a.activity_date BETWEEN c.issuance_date AND c.validity_date
                LIMIT 1
            ), -1);";

    // Update the database
    $DB->update(query: $check_id_query);
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
    $DB->update(query: $number_of_participants_query);
}



function formatDate(?string $date_str): string {
    if (empty($date_str)) {
        return 'No specific date provided';
    }
    
    $date = DateTime::createFromFormat('Y-m-d', $date_str);
    return $date ? $date->format('l, jS \o\f F Y') : 'Invalid date format';
}

?>