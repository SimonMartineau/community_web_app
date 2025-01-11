<?php

class Volunteers{

    public function fetch_volunteer_data(){
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










}


?>