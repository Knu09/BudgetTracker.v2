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
                document.getElementById("expense-modal").close();
            }
        });
    }
});
