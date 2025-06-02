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
        <div class="m-5 border border-line rounded-md bg-white p-5">
            <h2>Budget</h2>
            <form id="budget-form" class="flex flex-wrap gap-2">
                <input type="text" class="" id="budget-name" placeholder="Budget Name" required />
                <input type="number" id="budget-amount" placeholder="Amount" required />
                <button type="submit" class="btn btn-success">Add Budget</button>
            </form>
            <div class="budget-table my-4 relative overflow-x-auto">
                <table class="w-full text-md text-left rtl-text-right">
                    <thead class="text-sm uppercase bg-gray-50 ">
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
            <h2 class="mt">Expenses</h2>
            <form id="expense-form" class="flex flex-wrap gap-2">
                <input type="text" id="expense-name" placeholder="Expense Name" required />
                <input type="number" id="expense-amount" placeholder="Amount" required />
                <button type="submit" class="btn btn-danger">Add Expense</button>
            </form>
            <div class="expense-table my-4 relative overflow-x-auto">
                <table class="w-full text-md text-left rtl-text-right ">
                    <thead class="text-sm uppercase bg-gray-50 ">
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
            <h1>Balance</h1>
            <div class="total-balance">
                Balance Total: $<span id="balance-total">0.00</span>
            </div>
        </div>
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
