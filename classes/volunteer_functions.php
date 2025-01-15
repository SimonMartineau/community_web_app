<?php

function fetch_all_volunteer_data(){
    $query = "select * from Members order by id desc";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}


function fetch_member_data($id){
    $query = "select * from Members where id='$id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}

function fetch_member_interest_data($id){
    $query = "select * from Member_Interests where member_id='$id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}

function fetch_member_availability_data($id){
    $query = "select * from Member_Availability where member_id='$id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}


function fetch_interests($id){
    $query = "select interest from Member_Interests where member_id='$id'";

    $DB = new Database();
    $DB->save($query);
        
    $result = $DB->read($query);

    if($result){
        return $result;
    }else{
        return false;
    }
}

?>