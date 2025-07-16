// financial_health_summary.js

document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.time-filters .filter-button');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove 'active' class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));

            // Add 'active' class to the clicked button
            button.classList.add('active');

            // In a real application, you would load different data here
            // based on whether 'Daily', 'Weekly', or 'Monthly' was selected.
            const selectedFilter = button.textContent;
            console.log(`Displaying data for: ${selectedFilter}`);
            // Example: updateChartData(selectedFilter);
        });
    });
});