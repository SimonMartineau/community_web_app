function showWidgets_volunteer_page(type){
    // Hide all widget containers and the buttons initially
    document.getElementById('checks_widgets').style.display = 'none';
    document.getElementById('purchases_widgets').style.display = 'none';
    document.getElementById('activities_widgets').style.display = 'none';
    document.getElementById('volunteer_specific_checks_button').style.display = 'none';
    document.getElementById('volunteer_specific_purchases_button').style.display = 'none';
    document.getElementById('volunteer_specific_activities_button').style.display = 'none';


    // Show the selected widget container and the appropriate button
    if (type === 'checks') {
        document.getElementById('checks_widgets').style.display = 'block';
        document.getElementById('volunteer_specific_checks_button').style.display = 'inline-block'; // Show the button when "Show Checks" is clicked
    } else if (type === 'purchases') {
        document.getElementById('purchases_widgets').style.display = 'block';
        document.getElementById('volunteer_specific_purchases_button').style.display = 'inline-block'; // Show the button when "Show Purchases" is clicked
    } else if (type === 'activities') {
        document.getElementById('activities_widgets').style.display = 'block';
        document.getElementById('volunteer_specific_activities_button').style.display = 'inline-block'; // Show the button when "Show Activities" is clicked
    }
}



function showWidgets_check_page(type) {
    // Hide all widget containers initially
    document.getElementById('volunteer_widget').style.display = 'none';
    document.getElementById('purchases_widgets').style.display = 'none';
    document.getElementById('activities_widgets').style.display = 'none';

    // Show the selected widget container
    if (type === 'volunteer') {
        document.getElementById('volunteer_widget').style.display = 'block';
    } else if (type === 'purchases') {
        document.getElementById('purchases_widgets').style.display = 'block';
    } else if (type === 'activities') {
        document.getElementById('activities_widgets').style.display = 'block';
    } 
}


function showWidgets_purchase_page(type) {
    // Hide all widget containers initially
    document.getElementById('volunteer_widget').style.display = 'none';
    document.getElementById('check_widget').style.display = 'none';

    // Show the selected widget container
    if (type === 'volunteer') {
        document.getElementById('volunteer_widget').style.display = 'block';
    } else if (type === 'check') {
        document.getElementById('check_widget').style.display = 'block';
    }
}



function showWidgets_activity_page(type) {
    // Hide all widget containers initially
    document.getElementById('show_current_volunteers_widgets').style.display = 'none';
    document.getElementById('show_matching_volunteers_widgets').style.display = 'none';

    // Show the selected widget container
    if (type === 'current_participants') {
        document.getElementById('show_current_volunteers_widgets').style.display = 'block';
    } else if (type === 'matching_participants') {
        document.getElementById('show_matching_volunteers_widgets').style.display = 'block';
    }
}
