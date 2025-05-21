<!-- Imports -->
<link rel="stylesheet" href="../Styles/style.css">
<script src="../JavaScript/functions.js"></script>

<!-- HTML Code -->
<a href="../Profile_Pages/contract_profile.php?contract_id=<?php echo $contract_id; ?>" style="text-decoration: none;">
    <div id="widget">
        <div class="widget_row">

            <!-- Contract Icon -->
            <div class="icon_container">
                <span class="material-symbols-outlined">contract</span>
            </div>

            <!-- Contract Name -->
            <div class="name_container">
                <span class="widget_name">
                    <?php
                        // Detect language (default to English)
                        $lang  = $_SESSION['lang'] ?? 'en';
                        $first = $volunteer_data_row['first_name'];
                        $last  = $volunteer_data_row['last_name'];

                        if ($lang === 'pt') {
                            if ($volunteer_data_row['gender'] == 'woman'){
                                echo "Contrato de {$month} da {$first} {$last}";
                            } else {
                                echo "Contrato de {$month} do {$first} {$last}";
                            }
                        } else {
                            // English: “[Name]’s [Month] Contract”
                            echo "{$first} {$last}'s {$month} Contract";
                        }
                    ?>
                </span>
            </div>

            <!-- Contract Basic Info -->
            <div class="info_container">
                <p class="widget_info">
                    <!-- Contract Dates Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">calendar_month</span></span>
                        <span class="info_value">
                            <?= formatDate($contract_data_row['start_date']) ?> –<br> <?= formatDate($contract_data_row['end_date']) ?>
                        </span>
                    </span>

                    <!-- Contract Points Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">loyalty</span></span>
                        <span class="info_value">
                            <?= $contract_data_row['points_deposit'] - $contract_data_row['points_spent'] ?>/<?= $contract_data_row['points_deposit'] ?> <?= __('Points Left') ?>
                        </span>
                    </span>

                    <!-- Contract Hours Info -->
                    <span class="info_line">
                        <span class="info_label"><span class="material-symbols-outlined">schedule</span></span>
                        <span class="info_value">
                            <?= $contract_data_row['hours_completed'] ?>/<?= $contract_data_row['hours_required'] ?> <?= __('Hours Assigned') ?>
                        </span>
                    </span>
                </p>
            </div>

            <!-- Contract Status -->
            <div class="status_container">
                <p class="widget_info">
                    <!-- Contract Active/Inactive Status -->
                    <?php if ($contract_data_row['contract_active'] == 1): ?>
                        <span class="info_line upcoming">
                            <span class="material-symbols-outlined">contract</span>
                            <?= __('Contract is active.') ?>
                        </span>
                    <?php elseif ($contract_data_row['contract_active'] == 0): ?>
                        <span class="info_line valid">
                            <span class="material-symbols-outlined">check_circle</span>
                            <?= __('Contract is complete.') ?>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Contract Points Spent Warning -->
                    <?php if ($contract_data_row['points_deposit'] - $contract_data_row['points_spent'] < 0): ?>
                        <span class="info_line warning">
                            <span class="material-symbols-outlined">warning</span>
                            <?= __('Volunteer has spent too many points.') ?>
                        </span>
                    <?php endif; ?>
                </p>
            </div>
            
        </div>
    </div>
</a>