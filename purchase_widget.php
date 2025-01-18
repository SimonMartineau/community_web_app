<a href="purchase_profile.php?id=<?php echo $volunteer_data_row['id']; ?>" style="text-decoration: none;">
    <div id="purchase_widget">
        <h3 class="purchase_widget_name"><?php echo $member_data['first_name'] . " " . $member_data['last_name'] . "'s Purchase" ?></h3>
        <p class="purchase_widget_info">
            <strong>Item Names:</strong> <?php echo $purchase_data_row['item_names']?><br>
            <strong>Total Cost:</strong> <?php echo $purchase_data_row['total_cost']?><br>

            <strong>Purchase Date:</strong> <?php echo $purchase_data_row['purchase_date']?><br>

        </p>
    </div>
</a>