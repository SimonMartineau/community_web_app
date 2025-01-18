<a href="purchase_profile.php?id=<?php echo $purchase_data_row['id']; ?>" style="text-decoration: none;">
    <div id="widget" class="purchase_widget">
        <h3 class="widget_name"><?php echo $member_data['first_name'] . " " . $member_data['last_name'] . "'s Purchase" ?></h3>
        <p class="widget_info">
            <strong>Item Names:</strong> <?php echo $purchase_data_row['item_names']?><br>
            <strong>Total Cost:</strong> <?php echo $purchase_data_row['total_cost'] . " Points"?><br>

            <strong>Purchase Date:</strong> <?php echo $purchase_data_row['purchase_date']?><br>

        </p>
    </div>
</a>