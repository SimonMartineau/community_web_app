<!-- Imports -->
<link rel="stylesheet" href="../Styles/style.css">
<script src="../functions.js"></script>

<!-- HTML Code -->
<a href="../Profile_Pages/purchase_profile.php?purchase_id=<?php echo $purchase_id; ?>" style="text-decoration: none;">
    <div id="widget">
        <div class="widget_row">

            <!-- Purchase Icon -->
            <div class="icon_container">
                <span class="material-symbols-outlined">shopping_cart</span>
            </div>

            <!-- Purchase Name -->
            <div class="name_container">
                <span class="widget_name"><?php echo $volunteer_data_row['first_name'] . " " . $volunteer_data_row['last_name'] . "'s "?> <br> <?php echo "Purchase" ?></span>
            </div>

            <!-- Purchase Basic Info -->
            <div class="info_container">
                <p class="widget_info">
                    <!-- Purchase Items Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">shopping_basket</span></span>
                        <span class="info_value"><?php echo "Items : " . $purchase_data_row['item_names']?></span>
                    </span>

                    <!-- Purchase Cost Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value"><?php echo "Cost : " .  $purchase_data_row['total_cost'] . " Points" ?></span>
                    </span>

                    <!-- Purchase Date Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                        <span class="info_value"><?php echo "Date : " . formatDate($purchase_data_row['purchase_date'])?></span>
                    </span>
                </p>
            </div>

            <!-- Purchase Status -->
            <div class="status_container">
                <p class="widget_info">
                    <!-- Purchase In/Out of Contract Status -->         
                    <?php if ($purchase_data_row['contract_id'] == -1): ?>
                        <span class="info_line warning"><span class="material-symbols-outlined">warning</span>Purchase date is not in any contract.</span>
                    <?php endif; ?>
                </p>
            </div>
            
        </div>
    </div>
</a>