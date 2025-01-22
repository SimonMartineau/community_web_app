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

function fetch_all_volunteer_data(){
    $query = "select * from Volunteers order by id desc";

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

?>