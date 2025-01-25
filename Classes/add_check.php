<?php

class Add_Check{
    public $issuance_date_error_mes = "";
    public $validity_date_error_mes = "";
    public $points_deposit_error_mes = "";
    public $hours_required_error_mes = "";
    public $organizer_name_error_mes = "";



    // Analyses data sent by user
    public function evaluate($volunteer_id, $data){

        $error = false; // Initialize error check variable

        // Check issuance date
        if (isset($_POST['issuance_date'])){
            $value = $_POST['issuance_date'];
            if (empty($value)){
                $this->issuance_date_error_mes = "*Issuance date is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check validity date
        if (isset($_POST['validity_date'])){
            $value = $_POST['validity_date'];
            if (empty($value)){
                $this->validity_date_error_mes = "*Validity date is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check points deposit
        if (isset($_POST['points_deposit'])){
            $value = $_POST['points_deposit'];
            if (empty($value)){
                $this->points_deposit_error_mes = "*Points amount is empty.<br>";
                $error = true; // There is an error
            } elseif (!is_numeric($value)){
                $this->points_deposit_error_mes = "*Please enter a valid number.<br>";
                $error = true; // There is an error
            }
        }

        // Check hours required
        if (isset($_POST['hours_required'])){
            $value = $_POST['hours_required'];
            if (empty($value)){
                $this->hours_required_error_mes = "*Hours required is empty.<br>";
                $error = true; // There is an error
            } elseif (!is_numeric($value)){
                $this->hours_required_error_mes = "*Please enter a valid number.<br>";
                $error = true; // There is an error
            }
        }

        // Check organizer name
        if (isset($_POST['organizer_name'])){
            $value = $_POST['organizer_name'];
            if (empty($value)){
                $this->organizer_name_error_mes = "*Organizer name is empty.<br>";
                $error = true; // There is an error
            }
        }

        // If no error, create add volunteer. Otherwise, echo error
        if(!$error){
            // No error
            $this->add_check($volunteer_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function add_check($volunteer_id, $data){
        // Creating all the varaibles for the SQL input
        $issuance_date = $data['issuance_date'];
        $validity_date = $data['validity_date'];
        $points_deposit = $data['points_deposit'];
        $points_spent = 0;
        $hours_required = $data['hours_required'];
        $hours_completed = 0;
        $organizer_name = $data['organizer_name'];
        $check_active = 1;
        $additional_notes = $data['additional_notes'];

        // Initialise Database object
        $DB = new Database();

        // SQL query into Checks
        $check_query = "insert into Checks (volunteer_id, issuance_date, validity_date, points_deposit, points_spent, hours_required, hours_completed, organizer_name, check_active, additional_notes)
                  values ('$volunteer_id', '$issuance_date', '$validity_date', '$points_deposit', '$points_spent' ,'$hours_required', '$hours_completed', '$organizer_name', '$check_active', '$additional_notes')";
        $DB->save($check_query);
    }

    
}

?>