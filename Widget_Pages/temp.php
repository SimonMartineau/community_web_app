<div class="volunteer_widget_container">
  <!-- The main widget (clicking most parts will follow the link) -->
  <a href="../Profile_Pages/matching_volunteer_activity.php?volunteer_id=<?php echo $volunteer_id; ?>&activity_id=<?php echo $activity_id; ?>" class="volunteer_link" style="text-decoration: none;">
    <div id="widget-<?php echo $volunteer_id; ?>" class="volunteer_widget">
      <div class="icon_container">
        <span class="material-symbols-outlined">person</span>
      </div>

      <div class="name_container">
        <span class="widget_name"><?php echo $volunteer_data_row['first_name'] . " " . $volunteer_data_row['last_name'] ?></span>
      </div>

      <div class="info_container">
        <p class="widget_info">
          <span class="info_line">
            <span class="info_label">
              <span class="material-symbols-outlined">loyalty</span>
            </span>
            <span class="info_value"><?php echo $volunteer_data_row['points'] ?> Points Left</span>
          </span>
          <span class="info_line">
            <span class="info_label">
              <span class="material-symbols-outlined">schedule</span>
            </span>
            <span class="info_value">
              <?php echo $volunteer_data_row['hours_completed'] ?>/<?php echo $volunteer_data_row['hours_required'] ?> Hours Completed
            </span>
          </span>
        </p>
      </div>

      <div class="status_container">
        <p class="widget_info">
          <?php if ($volunteer_data_row['hours_required'] == 0): ?>
            <span class="info_line warning">
              <span class="material-symbols-outlined">warning</span>
              Warning: Volunteer doesn't currently have a contract.
            </span>
          <?php endif; ?>
          <?php if ($volunteer_data_row['points'] < 0): ?>
            <span class="info_line warning">
              <span class="material-symbols-outlined">warning</span>
              Warning: Volunteer has spent too many points.
            </span>
          <?php endif; ?>
        </p>
      </div>

      <!-- Button placed inside the widget. We call stopPropagation() in the onclick to avoid triggering the link. -->
      <button type="button" class="toggle-details-btn" onclick="toggleDetails(event, '<?php echo $volunteer_id; ?>')">
        More Details
      </button>
    </div>
  </a>

  <!-- Extra details section; initially hidden -->
  <div id="extra-details-<?php echo $volunteer_id; ?>" class="extra-details">
    <p>
      <!-- Replace the following text with the desired extra details -->
      Here are additional details about the volunteer...
    </p>
  </div>
</div>



<style>
    .volunteer_widget_container {
  margin-bottom: 20px;
  position: relative;
}

/* Style for the main widget */
.volunteer_widget {
  position: relative;
  padding: 15px;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  /* Reserve space on the right for the button */
  padding-right: 100px;
}
/* Hidden extra details section */
.extra-details {
  display: none; /* Hidden by default */
  padding: 10px;
  border: 1px solid #ccc;
  margin-top: 5px;
  background-color: #fff;
}

.material-symbols-outlined {
    display: inline-flex; /* Important for icon alignment */
    align-items: center;
    justify-content: center;
    font-size: 1.5em; /* Match icon size to text */
    vertical-align: middle;
}


</style>


<script>
  function toggleDetails(event, volunteerId) {
    // Prevent the click from propagating to the parent link
    event.stopPropagation();
    event.preventDefault();

    // Get the extra details div by its ID
    var detailsDiv = document.getElementById('extra-details-' + volunteerId);

    // Toggle display: if hidden, show it; if visible, hide it.
    if (detailsDiv.style.display === "none" || detailsDiv.style.display === "") {
      detailsDiv.style.display = "block";
    } else {
      detailsDiv.style.display = "none";
    }
  }
</script>
