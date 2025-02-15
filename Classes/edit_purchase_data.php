<?php

class Edit_Purchase{
    public $item_names_error_mes = "";
    public $total_cost_error_mes = "";
    public $purchase_date_error_mes = "";
    public $entry_clerk_error_mes = "";



    // Analyses data sent by user
    public function evaluate($purchase_id, $data){

        $error = false; // Initialize error contract variable

        // Check item names
        if (isset($_POST['item_names'])){
            $value = $_POST['item_names'];
            if (empty($value)){
                $this->item_names_error_mes = "*Item names is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check total cost
        if (isset($_POST['total_cost'])){
            $value = $_POST['total_cost'];
            if (empty($value)){
                $this->total_cost_error_mes = "*Total cost is empty.<br>";
                $error = true; // There is an error
            } elseif (!is_numeric($value)){
                $this->total_cost_error_mes = "*Please enter a valid number.<br>";
                $error = true; // There is an error
            } elseif ($value < 0){
                $this->total_cost_error_mes = "*Please enter a positive number.<br>";
                $error = true; // There is an error
            } elseif ($value > 1000){
                $this->total_cost_error_mes = "*Please enter a number less than 1000.<br>";
                $error = true; // There is an error
            }
        }

        // Check purchase date
        if (isset($_POST['purchase_date'])){
            $value = $_POST['purchase_date'];
            if (empty($value)){
                $this->purchase_date_error_mes = "*Purchase date is empty.<br>";
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
            $this->edit_purchase($purchase_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function edit_purchase($purchase_id, $data){
        // Creating all the varaibles for the SQL input
        $item_names = $data['item_names'];
        $total_cost = $data['total_cost'];
        $purchase_date = $data['purchase_date'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];

        // Initialise Database object
        $DB = new Database();

        // SQL query into Volunteers
        $purchase_query = "UPDATE Purchases 
                  SET item_names = '$item_names', 
                      total_cost = '$total_cost', 
                      purchase_date = '$purchase_date', 
                      entry_clerk = '$entry_clerk', 
                      additional_notes = '$additional_notes'
                  WHERE id = '$purchase_id';";
        $DB->update($purchase_query);

    }

}

?>