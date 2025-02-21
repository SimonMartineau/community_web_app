function ToggleWidgets(section, clickedButton) {
    // Hide all widget containers
    var containers = document.getElementsByClassName('widget-container');
    for (var i = 0; i < containers.length; i++) {
        containers[i].style.display = 'none';
    }
    
    // Show the selected container
    var containerToShow = document.getElementById(section + '_widgets');
    if (containerToShow) {
        containerToShow.style.display = 'block';
    }
    
    // Remove 'active' class from all buttons
    var buttons = document.querySelectorAll('#widget_toggle_buttons button');
    for (var j = 0; j < buttons.length; j++) {
        buttons[j].classList.remove('active');
    }
    
    // Add 'active' class to the clicked button
    clickedButton.classList.add('active');
}



function toggleDetails(event, Id) {
    event.stopPropagation();
    event.preventDefault();

    // Get references to elements
    const button = event.target; // Get the clicked button
    const detailsDiv = document.getElementById('extra_details_row-' + Id);

    // Toggle visibility
    const isVisible = detailsDiv.style.display === "flex";
    detailsDiv.style.display = isVisible ? "none" : "flex";

    // Update button text based on new state
    button.textContent = isVisible ? 'More Details' : 'Less Details';
  }