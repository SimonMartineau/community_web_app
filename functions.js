function showWidgets(type) {
    // Hide all widget containers and the buttons initially
    document.getElementById('checks_widgets').style.display = 'none';
    document.getElementById('purchases_widgets').style.display = 'none';
    document.getElementById('all_volunteer_checks_button').style.display = 'none';
    document.getElementById('all_volunteer_purchases_button').style.display = 'none';

    // Show the selected widget container and the appropriate button
    if (type === 'checks') {
        document.getElementById('checks_widgets').style.display = 'block';
        document.getElementById('all_volunteer_checks_button').style.display = 'inline-block'; // Show the button when "Show Checks" is clicked
    } else if (type === 'purchases') {
        document.getElementById('purchases_widgets').style.display = 'block';
        document.getElementById('all_volunteer_purchases_button').style.display = 'inline-block'; // Show the button when "Show Purchases" is clicked
    }
}

// Set "checks" as the default when the page loads
window.onload = function() {
    showWidgets('checks');
};
