<?php

// Class to add contract to database
class Add_Contract{

    // Error messages for each form field
    public $start_date_error_mes = "";
    public $validity_date_error_mes = "";
    public $points_deposit_error_mes = "";
    public $hours_required_error_mes = "";
    public $entry_clerk_error_mes = "";
    public $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }


    // Analyses data sent by user
    public function evaluate($volunteer_id, $data){

        // Initialize error contract variable
        $error = false;

        // Check Start Date
        if (isset($_POST['start_date'])){
            $value = $_POST['start_date'];
            if (empty($value)){
                $this->start_date_error_mes = "*Start Date is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check End Date
        if (isset($_POST['end_date'])){
            $value = $_POST['end_date'];
            if (empty($value)){
                $this->validity_date_error_mes = "*End Date is empty.<br>";
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

        // Check if the necessary keys exist in the data array
            if (isset($data['start_date']) && isset($data['end_date']) && isset($data['points_deposit']) && isset($data['hours_required']) && isset($data['entry_clerk'])) {
                // All keys exist, proceed with adding the contract
            } else {
                // One or more keys do not exist, handle the error
                return false;
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


    // Function to add contract to database
    public function add_contract($volunteer_id, $data){

        // Creating all the varaibles for the SQL input
        $user_id = $this->user_id;
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $points_deposit = $data['points_deposit'];
        $points_spent = 0;
        $hours_required = $data['hours_required'];
        $hours_completed = 0;
        $entry_clerk = $data['entry_clerk'];
        $contract_active = 1;
        $additional_notes = $data['additional_notes'];

        // Initialise Database object
        $DB = new Database();

        // SQL prepared statement into Contracts
        $contract_query = "INSERT INTO Contracts (user_id, volunteer_id, start_date, end_date, points_deposit, points_spent, hours_required, hours_completed, entry_clerk, contract_active, additional_notes)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $types = "iissiiiisss"; // Types of data to be inserted
        $params = [$user_id, $volunteer_id, $start_date, $end_date, $points_deposit, $points_spent, $hours_required, 
                    $hours_completed, $entry_clerk, $contract_active, $additional_notes]; // Parameters to be inserted

        // Send prepared statement to Database
        $DB->save_prepared($contract_query, $types, $params);
    }

}

?>