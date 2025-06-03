<?php




ob_start();


session_start();


// --- Authentication Check ---
// Check if the user_id is set in the session
if (!isset($_SESSION['user_id'])) {

    header("Location: /web/templates/pages/login_page.php");
    exit; // Important to stop script execution after a redirect header
} else {

}
// --- End Authentication Check ---



require_once 'web/templates/layouts/page_layout.php';
$conn = require_once 'internal/db_connection.php';

$is_htmx = isset($_SERVER['HTTP_HX_REQUEST']);
$page = $_GET['page'] ?? 'index';

$user_id = $_SESSION['user_id'] ?? null;

$user_email = $_SESSION['user_email'] ?? null;

$budgets = [];
$expenses = [];

if ($user_id) {
    // Fetch budgets
    $stmt = $conn->prepare("SELECT id, name, amount FROM budget WHERE user_id = ?"); // CORRECT: 'id' is selected
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $budgets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Fetch expenses
    $stmt = $conn->prepare("SELECT id, name, amount FROM expense WHERE user_id = ?"); // <<< PROBLEM HERE!
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $expenses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
$conn->close();

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
            <button class="btn btn-success mb-2" onclick="document.getElementById('budget-modal').showModal()">Add
                Budget</button>

            <div class="budget-table my-4 relative overflow-x-auto">
                <table class="w-full text-md text-left rtl-text-right">
                    <thead class="text-sm uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Budget Name</th>
                            <th scope="col" class="px-6 py-3">Amount</th>
                            <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody id="budget-list">
                        <?php
                        $total_budget = 0;
                        foreach ($budgets as $b):
                            $total_budget += $b['amount'];
                            ?>
                            <tr data-id="<?= htmlspecialchars($b['id']) ?>">
                                <td class="px-6 py-4"><?= htmlspecialchars($b['name']) ?></td>
                                <td class="px-6 py-4">$<?= number_format($b['amount'], 2) ?></td>
                                <td class="px-6 py-4 text-left">
                                    <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 font-medium"
                                        data-id="<?= htmlspecialchars($b['id']) ?>" data-type="budget"
                                        data-name="<?= htmlspecialchars($b['name']) ?>">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


                <div class="flex justify-end ">
                    <!-- Budget Total Section (from Option 1, slightly modified for context) -->
                    <div class="total-budget-section p-4 bg-gray-50 rounded-lg shadow text-right"> <!-- Added text-right -->
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Budget Total</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            $<span id="budget-total"><?= number_format($total_budget, 2) ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- BUDGET TRACKER -->



            <h2 class="mt-4">Expenses</h2>
            <!-- Trigger Button -->
            <button class="btn btn-danger mb-2" onclick="document.getElementById('expense-modal').showModal()">Add
                Expense</button>

            <div class="expense-table my-4 relative overflow-x-auto">
                <table class="w-full text-md text-left rtl-text-right">
                    <thead class="text-sm uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Expense Name</th>
                            <th scope="col" class="px-6 py-3">Amount</th>
                            <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                            <!-- Column for Actions -->
                        </tr>
                    </thead>
                    <tbody id="expense-list">
                        <?php
                        $total_expense = 0;
                        foreach ($expenses as $e): // Assuming $e has an 'id' key
                            $total_expense += $e['amount'];
                            ?>
                            <tr data-id="<?= htmlspecialchars($e['id']) ?>"> <!-- Optional: data-id on tr -->
                                <td class="px-6 py-4"><?= htmlspecialchars($e['name']) ?></td>
                                <td class="px-6 py-4">$<?= number_format($e['amount'], 2) ?></td>
                                <td class="px-6 py-4 text-left">
                                    <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 font-medium"
                                        data-id="<?= htmlspecialchars($e['id']) ?>" data-type="expense"
                                        data-name="<?= htmlspecialchars($e['name']) ?>">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>



            <!-- Parent Container -->
            <div class="flex justify-end ">

                <!-- Expense Total Section (from Option 1, slightly modified for context) -->
                <div class="total-expense-section p-4 bg-gray-50 rounded-lg shadow text-right"> <!-- Added text-right -->
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Expense Total</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-600">
                        $<span id="expense-total"><?= number_format($total_expense, 2) ?></span>
                    </p>
                </div>

            </div>


            <div class="total-balance-section mt-4 p-4 bg-green-50 rounded-lg shadow text-left">
                <!-- Or bg-blue-50, bg-gray-50 -->
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Balance</h3>
                <p class="mt-1 text-3xl font-semibold 
        <?php
        $balance = $total_budget - $total_expense;
        if ($balance >= 0) {
            echo 'text-green-600'; // Positive balance
        } else {
            echo 'text-red-600'; // Negative balance
        }
        ?>">
                    $<span id="balance-total"><?= number_format($balance, 2) ?></span>
                </p>
            </div>


        </div>

        <!-- Budget Modal -->
        <dialog id="budget-modal" class="
    rounded-md p-5 max-w-md w-full
    backdrop:bg-black/50
    fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
    max-h-[90vh] overflow-y-auto">
            <form id="budget-form" method="dialog" class="flex flex-col gap-2">
                <h3 class="text-lg font-bold">Add Budget</h3>
                <input type="text" id="budget-name" placeholder="Budget Name" required class="input input-bordered" />
                <input type="number" id="budget-amount" placeholder="Amount" required class="input input-bordered" />
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-success">Add</button>
                    <button type="button" class="btn btn-outline"
                        onclick="document.getElementById('budget-modal').close(); window.location.reload();">Cancel</button>
                </div>
            </form>
        </dialog>

        <!-- Expense Modal -->
        <dialog id="expense-modal" class="
    rounded-md p-5 max-w-md w-full
    backdrop:bg-black/50
    fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
    max-h-[90vh] overflow-y-auto">
            <form id="expense-form" method="dialog" class="flex flex-col gap-2">
                <h3 class="text-lg font-bold">Add Expense</h3>
                <input type="text" id="expense-name" placeholder="Expense Name" required class="input input-bordered" />
                <input type="number" id="expense-amount" placeholder="Amount" required class="input input-bordered" />
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-danger">Add</button>
                    <button type="button" class="btn btn-outline"
                        onclick="document.getElementById('expense-modal').close(); window.location.reload();">Cancel</button>
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