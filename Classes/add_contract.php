<?php

class Add_Contract{
    public $issuance_date_error_mes = "";
    public $validity_date_error_mes = "";
    public $points_deposit_error_mes = "";
    public $hours_required_error_mes = "";
    public $entry_clerk_error_mes = "";



    // Analyses data sent by user
    public function evaluate($volunteer_id, $data){

        $error = false; // Initialize error contract variable

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
            } elseif ($value < 0){
                $this->points_deposit_error_mes = "*Please enter a positive number.<br>";
                $error = true; // There is an error
            } elseif ($value > 1000){
                $this->points_deposit_error_mes = "*Please enter a number less than 1000.<br>";
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
            } elseif ($value < 0){
                $this->hours_required_error_mes = "*Please enter a positive number.<br>";
                $error = true; // There is an error
            } elseif ($value > 1000){
                $this->hours_required_error_mes = "*Please enter a number less than 1000.<br>";
                $error = true; // There is an error
            }
        }

        // Check entry clerk name
        if (isset($_POST['entry_clerk'])){
            $value = $_POST['entry_clerk'];
            if (empty($value)){
                $this->entry_clerk_error_mes = "*Entry clerk is empty.<br>";
                $error = true; // There is an error
            }
        }

        // If no error, create add volunteer. Otherwise, echo error
        if(!$error){
            // No error
            $this->add_contract($volunteer_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function add_contract($volunteer_id, $data){
        // Creating all the varaibles for the SQL input
        $issuance_date = $data['issuance_date'];
        $validity_date = $data['validity_date'];
        $points_deposit = $data['points_deposit'];
        $points_spent = 0;
        $hours_required = $data['hours_required'];
        $hours_completed = 0;
        $entry_clerk = $data['entry_clerk'];
        $contract_active = 1;
        $additional_notes = $data['additional_notes'];

        // Initialise Database object
        $DB = new Database();

        // SQL query into Contracts
        $contract_query = "insert into Contracts (volunteer_id, issuance_date, validity_date, points_deposit, points_spent, hours_required, hours_completed, entry_clerk, contract_active, additional_notes)
                  values ('$volunteer_id', '$issuance_date', '$validity_date', '$points_deposit', '$points_spent' ,'$hours_required', '$hours_completed', '$entry_clerk', '$contract_active', '$additional_notes')";
        $DB->save($contract_query);
    }

    
}

?>