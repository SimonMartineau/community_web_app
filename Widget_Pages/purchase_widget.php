<a href="../Profile_Pages/purchase_profile.php?purchase_id=<?php echo $purchase_id; ?>" style="text-decoration: none;">
    <div id="widget" class="volunteer_widget">

        <div class="icon_container" style="font-size: 1.5em; color: #405d9b; font-weight: 600;">
            <span class="material-symbols-outlined">shopping_cart</span>
        </div>

        <div class="name_container">
            <span class="widget_name"><?php echo $volunteer_data['first_name'] . " " . $volunteer_data['last_name'] . "'s "?> <br> <?php echo "Purchase" ?></span>
        </div>

        <div class="info_container">
            <p class="widget_info">
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">shopping_basket</span></span>
                    <span class="info_value"><?php echo "Items : " . $purchase_data_row['item_names']?></span>
                </span>
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                    <span class="info_value"><?php echo "Cost : " .  $purchase_data_row['total_cost'] . " Points" ?></span>
                </span>
                <span class="info_line">
                    <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                    <span class="info_value"><?php echo "Date : " . formatDate($purchase_data_row['purchase_date'])?></span>
                </span>
            </p>
        </div>

        <div class="status_container">
            <p class="widget_info">                
                <?php if ($purchase_data_row['contract_id'] == -1): ?>
                    <span class="info_line warning"><span class="material-symbols-outlined">warning</span>Purchase date outside contract duration.</span>
                <?php endif; ?>
            </p>
        </div>
        
    </div>
</a>


<style>
    .material-symbols-outlined {
        display: inline-flex; /* Important for icon alignment */
        align-items: center;
        justify-content: center;
        font-size: 1.5em; /* Match icon size to text */
        vertical-align: middle;
    }
</style>