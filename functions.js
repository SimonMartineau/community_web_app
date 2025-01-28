function showWidgets_volunteer_page(type){
    // Hide all widget containers and the buttons initially
    document.getElementById('checks_widgets').style.display = 'none';
    document.getElementById('purchases_widgets').style.display = 'none';
    document.getElementById('volunteer_specific_checks_button').style.display = 'none';
    document.getElementById('volunteer_specific_purchases_button').style.display = 'none';

    // Show the selected widget container and the appropriate button
    if (type === 'checks') {
        document.getElementById('checks_widgets').style.display = 'block';
        document.getElementById('volunteer_specific_checks_button').style.display = 'inline-block'; // Show the button when "Show Checks" is clicked
    } else if (type === 'purchases') {
        document.getElementById('purchases_widgets').style.display = 'block';
        document.getElementById('volunteer_specific_purchases_button').style.display = 'inline-block'; // Show the button when "Show Purchases" is clicked
    }
}

// Set "checks" as the default when the page loads
window.onload = function() {
    showWidgets_volunteer_page('checks');
};



function showWidgets_check_page(type) {
    // Hide all widget containers initially
    document.getElementById('volunteer_widget').style.display = 'none';
    document.getElementById('purchases_widgets').style.display = 'none';
    const activitiesWidget = document.getElementById('activities_widgets');
    if (activitiesWidget) {
        activitiesWidget.style.display = 'none';
    }

    // Show the selected widget container
    if (type === 'volunteer') {
        document.getElementById('volunteer_widget').style.display = 'block';
    } else if (type === 'purchases') {
        document.getElementById('purchases_widgets').style.display = 'block';
    } else if (type === 'activities' && activitiesWidget) {
        activitiesWidget.style.display = 'block';
    }
}

// Set "volunteer" as the default when the page loads
window.onload = function () {
    showWidgets_check_page('volunteer');
};

