<?php
require_once 'web/templates/layouts/page_layout.php';

$is_htmx = isset($_SERVER['HTTP_HX_REQUEST']);
$page = $_GET['page'] ?? 'index';

ob_start();

switch ($page) {
    case 'import-form':
        require_once 'web/templates/pages/import-form.php';
        break;
    case 'login':
        require_once 'web/templates/pages/login_page.php';
        break;
    // add other pages here
    case 'index':
    default:
?>
        <script src="web/static/js/budget_expenses_script.js"></script>
        <div class="m-5 border border-line rounded-md bg-white p-5">
            <h2>Budget</h2>
            <!-- Trigger Button -->
            <button class="btn btn-success mb-2" onclick="document.getElementById('budget-modal').showModal()">Add Budget</button>

            <div class="budget-table my-4 relative overflow-x-auto">
                <table class="w-full text-md text-left rtl-text-right">
                    <thead class="text-sm uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Budget Name</th>
                            <th scope="col" class="px-6 py-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="budget-list"></tbody>
                </table>
            </div>
            <div class="total-amount text-end">
                Budget Total: $<span id="budget-total">0.00</span>
            </div>

            <h2 class="mt-4">Expenses</h2>
            <!-- Trigger Button -->
            <button class="btn btn-danger mb-2" onclick="document.getElementById('expense-modal').showModal()">Add Expense</button>

            <div class="expense-table my-4 relative overflow-x-auto">
                <table class="w-full text-md text-left rtl-text-right">
                    <thead class="text-sm uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Expense Name</th>
                            <th scope="col" class="px-6 py-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="expense-list"></tbody>
                </table>
            </div>
            <div class="total-amount text-end">
                Expenses Total: $<span id="expense-total">0.00</span>
            </div>

            <h1 class="mt-4">Balance</h1>
            <div class="total-balance">
                Balance Total: $<span id="balance-total">0.00</span>
            </div>
        </div>

        <!-- Budget Modal -->
        <dialog id="budget-modal" class="rounded-md p-5 max-w-md w-full backdrop:bg-black/50">
            <form id="budget-form" method="dialog" class="flex flex-col gap-2">
                <h3 class="text-lg font-bold">Add Budget</h3>
                <input type="text" id="budget-name" placeholder="Budget Name" required class="input input-bordered" />
                <input type="number" id="budget-amount" placeholder="Amount" required class="input input-bordered" />
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-success">Add</button>
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('budget-modal').close()">Cancel</button>
                </div>
            </form>
        </dialog>

        <!-- Expense Modal -->
        <dialog id="expense-modal" class="rounded-md p-5 max-w-md w-full backdrop:bg-black/50">
            <form id="expense-form" method="dialog" class="flex flex-col gap-2">
                <h3 class="text-lg font-bold">Add Expense</h3>
                <input type="text" id="expense-name" placeholder="Expense Name" required class="input input-bordered" />
                <input type="number" id="expense-amount" placeholder="Amount" required class="input input-bordered" />
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-danger">Add</button>
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('expense-modal').close()">Cancel</button>
                </div>
            </form>
        </dialog>
<?php
        break;
}

$content = ob_get_clean();

if (!$is_htmx) {
    PageLayout('Budget and Expense Tracker', $content);
} else {
    echo $content;
}
?>
