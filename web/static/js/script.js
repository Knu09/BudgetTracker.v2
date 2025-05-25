document.addEventListener("DOMContentLoaded", () => {
    // SideNavBar
    let toggleButton = document.querySelector("#menuToggleBtn");
    toggleButton.addEventListener("click", () => {
        let sidebar = document.querySelector("#sidebar");
        sidebar.classList.toggle("translate-x-[-100%]");
        sidebar.classList.toggle("w-[0px]");
    });

    // Get elements
    const budgetForm = document.getElementById("budget-form");
    const budgetList = document.getElementById("budget-list");
    const budgetTotal = document.getElementById("budget-total");

    const expenseForm = document.getElementById("expense-form");
    const expenseList = document.getElementById("expense-list");
    const expenseTotal = document.getElementById("expense-total");

    const balanceTotal = document.getElementById("balance-total");

    let budgetEntries = [];
    let expenses = [];

    // Handle Budget Submission
    budgetForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const name = document.getElementById("budget-name").value;
        const amount = parseFloat(
            document.getElementById("budget-amount").value,
        );
        if (isNaN(amount) || amount <= 0) return;

        // Store budget details
        budgetEntries.push({ name, amount });
        displayBudgets();
        updateTotals();

        budgetForm.reset();
    });

    // Handle Expense Submission
    expenseForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const name = document.getElementById("expense-name").value;
        const amount = parseFloat(
            document.getElementById("expense-amount").value,
        );
        if (isNaN(amount) || amount <= 0) return;

        // Store expense details
        expenses.push({ name, amount });
        displayExpenses();
        updateTotals();

        expenseForm.reset();
    });

    // Display Budget Entries
    function displayBudgets() {
        budgetList.innerHTML = "";
        budgetEntries.forEach((entry) => {
            const row = document.createElement("tr");
            row.classList.add(
                "border-b",
                "border-secondary",
                "hover:bg-gray-100",
            );
            row.innerHTML = `
            <td class="px-6 py-4">${entry.name}</td>
            <td class="px-6 py-4">$${entry.amount.toFixed(2)}</td>`;
            budgetList.appendChild(row);
        });
    }

    // Display Expenses
    function displayExpenses() {
        expenseList.innerHTML = "";
        expenses.forEach((expense) => {
            const row = document.createElement("tr");
            row.classList.add(
                "border-b",
                "border-secondary",
                "hover:bg-gray-100",
            );
            row.innerHTML = `
                <td class="px-6 py-4">${expense.name}</td>
                <td class="px-6 py-4">$${expense.amount.toFixed(2)}</td>
            `;
            expenseList.appendChild(row);
        });
    }

    // Update Total Values
    function updateTotals() {
        const totalBudget = budgetEntries.reduce(
            (sum, entry) => sum + entry.amount,
            0,
        );
        const totalExpenses = expenses.reduce(
            (sum, expense) => sum + expense.amount,
            0,
        );

        budgetTotal.textContent = totalBudget.toFixed(2);
        expenseTotal.textContent = totalExpenses.toFixed(2);
        balanceTotal.textContent = (totalBudget - totalExpenses).toFixed(2);
    }
});
