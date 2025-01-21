<?php

class Edit_Check{
    public $issuance_date_error_mes = "";
    public $validity_date_error_mes = "";
    public $points_deposit_error_mes = "";
    public $required_time_error_mes = "";
    public $organizer_name_error_mes = "";



    // Analyses data sent by user
    public function evaluate($id, $data){

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

        // Check required time
        if (isset($_POST['required_time'])){
            $value = $_POST['required_time'];
            if (empty($value)){
                $this->required_time_error_mes = "*Required time is empty.<br>";
                $error = true; // There is an error
            } elseif (!is_numeric($value)){
                $this->required_time_error_mes = "*Please enter a valid number.<br>";
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
            $this->edit_check($id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function edit_check($id, $data){
        // Creating all the varaibles for the SQL input
        $issuance_date = $data['issuance_date'];
        $validity_date = $data['validity_date'];
        $points_deposit = $data['points_deposit'];
        $required_time = $data['required_time'];
        $organizer_name = $data['organizer_name'];
        $additional_notes = $data['additional_notes'];

        // Initialise Database object
        $DB = new Database();

        // SQL query into Volunteers
        $check_query = "UPDATE Checks 
                  SET issuance_date = '$issuance_date', 
                      validity_date = '$validity_date', 
                      points_deposit = '$points_deposit', 
                      required_time = '$required_time', 
                      organizer_name = '$organizer_name', 
                      additional_notes = '$additional_notes'
                  WHERE id = '$id';";
        $DB->update($check_query);

    }

}

?>