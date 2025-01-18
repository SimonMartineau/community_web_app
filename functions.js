function showWidgets(type) {
    // Hide all widget containers
    document.getElementById('checks_widgets').style.display = 'none';
    document.getElementById('purchases_widgets').style.display = 'none';

    // Show the selected widget container
    if (type === 'checks') {
        document.getElementById('checks_widgets').style.display = 'block';
    } else if (type === 'purchases') {
        document.getElementById('purchases_widgets').style.display = 'block';
    }
}
