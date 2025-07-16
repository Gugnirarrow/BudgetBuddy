// saving_goals.js

document.addEventListener('DOMContentLoaded', () => {
    console.log("Saving Goals page loaded.");

    // --- Functionality for "Add Goals" button ---
    // const addGoalsButton = document.querySelector('.add-goals-button');
    // if (addGoalsButton) {
    //     addGoalsButton.addEventListener('click', () => {
    //         alert('Add Goals button clicked! (In a real app, this would open a form or add a new goal card)');
    //         window.location.href="add_form.php";
    //     });
    // }

    // --- Functionality for "Edit" buttons ---
    // Event delegation is often better for dynamically added elements,
    // but for existing elements, direct selection works.
    const editButtons = document.querySelectorAll('.goal-card .edit-button');
    editButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const goalCard = event.target.closest('.goal-card');
            const goalTitle = goalCard.querySelector('h3').textContent;
            alert(`Edit button clicked for goal: "${goalTitle}"`);
            // In a real application, you would:
            // 1. Populate a form with the current goal's data.
            // 2. Allow the user to modify the data.
            // 3. Update the goal card visually and potentially update data on a server.
        });
    });

    // --- Functionality for "Delete" buttons ---
    const deleteButtons = document.querySelectorAll('.goal-card .delete-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const goalCard = event.target.closest('.goal-card');
            const goalTitle = goalCard.querySelector('h3').textContent;
            const clickval = document.getElementById("click").value;
            var goalid   = document.getElementById("goalid").value;
            if (confirm(`Are you sure you want to delete the goal: "${goalTitle}"?`)) {          
                
                //goalCard.remove(); // Remove the goal card from the DOM
                //alert(`Goal "${goalTitle}" deleted.`);
                // In a real application, you would:
                // 1. Send a request to a server to delete the goal data.
                // 2. Handle the server's response (success/failure).
            }
        });
    });

    // --- Dynamic update for the "Enter a goal" card (Goal Card 3) ---
    // This assumes the user inputs saved amount and it reflects on the percentage.
    const goal3InputAmount = document.querySelector('.goal-card:last-child .input-field:nth-of-type(1)'); // Target "Target Amount"
    const goal3SavedSoFar = document.querySelector('.goal-card:last-child .saved-so-far-input');
    const goal3ProgressBar = document.querySelector('.goal-card:last-child .progress-bar');
    const goal3SavedPercentage = document.querySelector('.goal-card:last-child .saved-percentage');

    // Simple example: if a numerical value is entered in the target amount,
    // and a saved amount is present, update the percentage.
    // This is a simplified client-side calculation for visual feedback.
    if (goal3InputAmount && goal3SavedSoFar && goal3ProgressBar && goal3SavedPercentage) {
        // Let's add an event listener to update progress when the saved amount changes
        // For demonstration, let's assume a static saved amount for the last card for now,
        // as the wireframe shows "RM" and "0%" but no input for "Saved So Far".
        // If there were an input for "Saved So Far", we'd attach a listener to it.

        // Simulating an update if saved amount was entered.
        // For the "Enter a goal" card, the wireframe shows "RM" and "0%" but no input.
        // We'll set up a hypothetical update based on manual input or a simulated value.
        function updateGoal3Progress() {
            // In a real scenario, you'd get these from user inputs or data
            const targetAmountStr = goal3InputAmount.value.replace(/RM\s?/, '');
            const targetAmount = parseFloat(targetAmountStr) || 0;

            // The wireframe doesn't provide an input field for "Saved So Far" on the 3rd card,
            // only displays "RM 0%". To make it dynamic, one would be needed.
            // For now, let's simulate if a "Saved So Far" input was available or calculated.
            const savedSoFarText = goal3SavedSoFar.textContent.replace('RM ', '').trim();
            let savedAmount = 0;
            if (savedSoFarText && savedSoFarText.includes('%')) {
                // If it's a percentage, we can't get a fixed amount without total
                savedAmount = 0; // Default to 0 if only % is shown
            } else {
                savedAmount = parseFloat(savedSoFarText) || 0;
            }


            let percentage = 0;
            if (targetAmount > 0) {
                percentage = (savedAmount / targetAmount) * 100;
            }
            percentage = Math.min(100, Math.max(0, percentage)); // Cap between 0 and 100

            goal3ProgressBar.style.width = `${percentage}%`;
            goal3ProgressBar.textContent = `${Math.round(percentage)}%`;
            goal3SavedPercentage.textContent = `${Math.round(percentage)}%`;
        }

        // Example: If you wanted to update on input for target amount
        goal3InputAmount.addEventListener('input', updateGoal3Progress);
        // If there was an input for "Saved So Far" for goal card 3, you'd add a listener to it too.
        // For now, it's visually just showing "RM 0%".
    }
});