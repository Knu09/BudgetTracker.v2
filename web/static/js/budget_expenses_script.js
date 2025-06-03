document.addEventListener("DOMContentLoaded", () => {
    const budgetForm = document.getElementById("budget-form");
    if (budgetForm) {
        budgetForm.addEventListener("submit", async function (e) {
            e.preventDefault();
            const name = document.getElementById("budget-name").value;
            const amount = document.getElementById("budget-amount").value;

            const response = await fetch(
                "../../../internal/budget_expenses_server.php",
                {
                    method: "POST",
                    body: new URLSearchParams({ type: "budget", name, amount }),
                },
            );

            const result = await response.json();
            alert(result.message || result.error);
            if (result.success) {
                 window.location.reload(); // <<< RELOAD THE PAGE HERE
                document.getElementById("budget-modal").close();
                
            }
        });
    }

    const expenseForm = document.getElementById("expense-form");
    if (expenseForm) {
        expenseForm.addEventListener("submit", async function (e) {
            e.preventDefault();
            const name = document.getElementById("expense-name").value;
            const amount = document.getElementById("expense-amount").value;

            const response = await fetch(
                "../../../internal/budget_expenses_server.php",
                {
                    method: "POST",
                    body: new URLSearchParams({
                        type: "expense",
                        name,
                        amount,
                    }),
                },
            );

            const result = await response.json();
            alert(result.message || result.error);
            if (result.success) {
                window.location.reload(); // <<< RELOAD THE PAGE HERE
                document.getElementById("expense-modal").close();
                 
            }
        });
    }


      document.body.addEventListener('click', async function(event) {
        if (event.target.classList.contains('remove-item-btn')) {
            const button = event.target;
            const itemId = button.dataset.id;
            const itemType = button.dataset.type;
            const itemName = button.dataset.name;

            if (!itemId || !itemType) {
                console.error('Remove button is missing data-id or data-type.');
                return;
            }

            if (confirm(`Are you sure you want to remove "${itemName}" (${itemType})?`)) {
                try {
                    const response = await fetch(
                        "../../../internal/budget_expenses_server.php", // Ensure this path is correct
                        {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                action: "remove", // New action type for the server
                                type: itemType,
                                id: itemId,
                            }),
                        }
                    );

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();

                    if (result.message) {
                        alert(result.message); // Or use a nicer notification
                    } else if (result.error) {
                        alert(result.error);
                    } else {
                        alert("An unknown response was received from the server.");
                    }

                    if (result.success) {
                        // Option 1: Reload the page (simplest)
                        window.location.reload();

                        // Option 2: Remove the row from the table directly (smoother UX)
                        // const rowToRemove = button.closest('tr');
                        // if (rowToRemove) {
                        //     rowToRemove.remove();
                        //     // You would also need to recalculate totals on the client-side
                        //     // or make another AJAX call to get updated totals.
                        //     // This part can get more complex.
                        // } else {
                        //     window.location.reload(); // Fallback if row isn't found
                        // }
                    }
                } catch (error) {
                    console.error("Fetch error during remove:", error);
                    alert("An error occurred while removing the item. Details: " + error.message);
                }
            }
        }
    });

});
