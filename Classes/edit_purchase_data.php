<?php

// Class to edit purchase in database
class Edit_Purchase{

    // Error messages for each form field
    public $item_names_error_mes = "";
    public $total_cost_error_mes = "";
    public $purchase_date_error_mes = "";
    public $entry_clerk_error_mes = "";
    public $user_id;

    public function __construct($user_id){
        $this->user_id = $user_id;
    }


    // Analyses data sent by user
    public function evaluate($purchase_id, $data){

        // Initialize error contract variable
        $error = false;

        // Check item names
        if (isset($_POST['item_names'])) {
            $value = $_POST['item_names'];
            if (empty($value)) {
                $this->item_names_error_mes = __('*Item names is empty.<br>');
                $error = true; // There is an error
            }
        }

        // Check total cost
        if (isset($_POST['total_cost'])) {
            $value = $_POST['total_cost'];
            if (empty($value)) {
                $this->total_cost_error_mes = __('*Total cost is empty.<br>');
                $error = true; // There is an error
            } elseif (!is_numeric($value)) {
                $this->total_cost_error_mes = __('*Please enter a valid number.<br>');
                $error = true; // There is an error
            } elseif ($value < 0) {
                $this->total_cost_error_mes = __('*Please enter a positive number.<br>');
                $error = true; // There is an error
            } elseif ($value > 1000) {
                $this->total_cost_error_mes = __('*Please enter a number less than 1000.<br>');
                $error = true; // There is an error
            }
        }

        // Check purchase date
        if (isset($_POST['purchase_date'])) {
            $value = $_POST['purchase_date'];
            if (empty($value)) {
                $this->purchase_date_error_mes = __('*Purchase date is empty.<br>');
                $error = true; // There is an error
            }
        }

        // Check entry clerk name
        if (isset($_POST['entry_clerk'])) {
            $value = $_POST['entry_clerk'];
            if (empty($value)) {
                $this->entry_clerk_error_mes = __('*Entry clerk is empty.<br>');
                $error = true; // There is an error
            }
        }

        // Ensure all expected keys exist
        if (! (isset($data['item_names']) 
            && isset($data['total_cost']) 
            && isset($data['purchase_date']) 
            && isset($data['entry_clerk']))) {
            $error = true; // There is an error
        } 

        // If no error, create add volunteer. Otherwise, echo error
        if(!$error){
            // No error
            $this->edit_purchase($purchase_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    // Function to edit purchase in database
    public function edit_purchase($purchase_id, $data){

        // Creating all the varaibles for the SQL input
        $user_id = $this->user_id;
        $item_names = $data['item_names'];
        $total_cost = $data['total_cost'];
        $purchase_date = $data['purchase_date'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];

        // Initialise Database object
        $DB = new Database();

        // SQL prepared statement into Purchases
        $update_query = "UPDATE Purchases 
            SET item_names = ?, 
                total_cost = ?, 
                purchase_date = ?, 
                entry_clerk = ?, 
                additional_notes = ? 
            WHERE id = ? AND user_id = ?";

        $types = "sisssii"; // Types of data to be inserted
        $params = [$item_names, $total_cost, $purchase_date, $entry_clerk, $additional_notes, $purchase_id, $user_id]; // Parameters to be inserted

        // Save data to Database through prepared statements
        $DB->save_prepared($update_query, $types, $params);
    }

}

?>