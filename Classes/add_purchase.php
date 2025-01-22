<?php

class Add_Purchase{
    public $item_names_error_mes = "";
    public $total_cost_error_mes = "";
    public $purchase_date_error_mes = "";
    public $organizer_name_error_mes = "";



    // Analyses data sent by user
    public function evaluate($volunteer_id, $data){

        $error = false; // Initialize error check variable

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
            $this->add_purchase($volunteer_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function add_purchase($volunteer_id, $data){
        // Creating all the varaibles for the SQL input
        $item_names = $data['item_names'];
        $total_cost = $data['total_cost'];
        $purchase_date = $data['purchase_date'];
        $organizer_name = $data['organizer_name'];
        $additional_notes = $data['additional_notes'];

        // Initialise Database object
        $DB = new Database();

        // SQL query into Purchases
        $purchase_query = "insert into Purchases (volunteer_id, item_names, total_cost, purchase_date, organizer_name, additional_notes)
                  values ('$volunteer_id', '$item_names', '$total_cost', '$purchase_date', '$organizer_name', '$additional_notes')";
        $DB->save($purchase_query);
    }

    
}

?>