<?php

// Class to edit contract data
class Edit_Contract{

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
    public function evaluate($contract_id, $data){

        // Initialize error contract variable
    $error = false;

    // Check Start Date
    if (isset($_POST['start_date'])){
        $value = $_POST['start_date'];
        if (empty($value)){
            $this->start_date_error_mes = __('*Start Date is empty.<br>');
            $error = true;
        }
    }

    // Check End Date
    if (isset($_POST['end_date'])){
        $value = $_POST['end_date'];
        if (empty($value)){
            $this->validity_date_error_mes = __('*End Date is empty.<br>');
            $error = true;
        }
    }

    // Check points deposit
    if (isset($_POST['points_deposit'])){
        $value = $_POST['points_deposit'];
        if (empty($value)){
            $this->points_deposit_error_mes = __('*Points amount is empty.<br>');
            $error = true;
        } elseif (!is_numeric($value)){
            $this->points_deposit_error_mes = __('*Please enter a valid number.<br>');
            $error = true;
        } elseif ($value < 0){
            $this->points_deposit_error_mes = __('*Please enter a positive number.<br>');
            $error = true;
        } elseif ($value > 1000){
            $this->points_deposit_error_mes = __('*Please enter a number less than 1000.<br>');
            $error = true;
        }
    }

    // Check hours required
    if (isset($_POST['hours_required'])){
        $value = $_POST['hours_required'];
        if (empty($value)){
            $this->hours_required_error_mes = __('*Hours required is empty.<br>');
            $error = true;
        } elseif (!is_numeric($value)){
            $this->hours_required_error_mes = __('*Please enter a valid number.<br>');
            $error = true;
        } elseif ($value < 0){
            $this->hours_required_error_mes = __('*Please enter a positive number.<br>');
            $error = true;
        } elseif ($value > 1000){
            $this->hours_required_error_mes = __('*Please enter a number less than 1000.<br>');
            $error = true;
        }
    }

    // Check entry clerk name
    if (isset($_POST['entry_clerk'])){
        $value = $_POST['entry_clerk'];
        if (empty($value)){
            $this->entry_clerk_error_mes = __('*Entry clerk is empty.<br>');
            $error = true;
        }
    }

    // Ensure all expected keys exist
    if (! (isset($data['start_date'])
        && isset($data['end_date'])
        && isset($data['points_deposit'])
        && isset($data['hours_required'])
        && isset($data['entry_clerk'])) ) {
        return false;
    }

        // If no error, create add volunteer. Otherwise, echo error
        if(!$error){
            // No error
            $this->edit_contract($contract_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    // Function to edit contract in database
    public function edit_contract($contract_id, $data){
    
        // Creating all the varaibles for the SQL input
        $user_id = $this->user_id;
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $points_deposit = $data['points_deposit'];
        $hours_required = $data['hours_required'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];
        // points_spent does not get updated here.

        // Initialise Database object
        $DB = new Database();

        // SQL prepared statement into Contracts
        $update_query = "UPDATE Contracts 
            SET start_date = ?,
                end_date = ?,
                points_deposit = ?,
                hours_required = ?,
                entry_clerk = ?,
                additional_notes = ?
            WHERE id = ? AND user_id = ?";
        $types = "ssiissii"; // Types of data to be inserted
        $parameters = [$start_date, $end_date, $points_deposit, $hours_required, $entry_clerk, 
                        $additional_notes, $contract_id, $user_id]; // Parameters to be inserted
        
        // Send prepared statement to Database
        $DB->save_prepared($update_query, $types, $parameters);
    
    }

}

?>