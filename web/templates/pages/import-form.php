
<?php
session_start(); // MUST BE THE VERY FIRST THING
ini_set('display_errors', 1); // Good for debugging
error_reporting(E_ALL);    // Good for debugging
?>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="container mx-auto mt-4">
        <div class="p-4 mb-4 text-sm rounded-lg
                        <?php if ($_SESSION['flash_type'] === 'error'): ?> bg-red-100 text-red-700
                        <?php elseif ($_SESSION['flash_type'] === 'success'): ?> bg-green-100 text-green-700
                        <?php else: ?> bg-blue-100 text-blue-700
                        <?php endif; ?>" role="alert">
            <?php echo htmlspecialchars($_SESSION['flash_message']); ?>
        </div>
    </div>
    <?php
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
?>
<?php endif; ?>

<?php if (isset($_SESSION['imported_data']) && !empty($_SESSION['imported_data'])): ?>
    <div class="container mx-auto mt-4 bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-md font-semibold mb-2">Imported Budget Items (First 10):</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $rowCount = 0; ?>
                    <?php foreach ($_SESSION['imported_data'] as $item): ?>
                        <?php if ($rowCount++ >= 10)
                            break; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo htmlspecialchars(number_format($item['amount'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php unset($_SESSION['imported_data']); ?>
    </div>
<?php endif; ?>

<div id="import" class="flex my-4">
    <div class="bg-white mx-auto p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Import CSV File</h2>
        <p class="text-xs text-gray-500 mb-2">Note: This importer currently only processes CSV files.</p>
        <form action="/BudgetTracker.v2/internal/uploadCSV.php" method="post" enctype="multipart/form-data">
            <div class="mb-4 cursor-pointer">
                <label for="csv-file" class="block text-sm font-medium text-gray-700 mb-1">Choose CSV file:</label>
                <input type="file" name="file" id="csv-file" accept=".csv, text/csv" class="block w-full border rounded p-2 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold
                                file:bg-black file:text-white hover:file:bg-gray-800" required />
            </div>
            <div class="mb-4">
                <label for="date-format" class="block text-sm font-medium text-gray-700">Date format (optional):</label>
                <input type="text" id="date-format" name="date_format" placeholder="DD/MM/YYYY"
                    class="mt-1 block w-full border rounded p-2" />
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="header-row" name="header_row" class="mr-2" checked />
                <!-- Default to checked -->
                <label for="header-row" class="text-sm text-gray-700">File has header row</label>
            </div>
            <button type="submit"
                class="w-full cursor-pointer bg-black text-white p-2 rounded hover:bg-gray-800 transition-colors">IMPORT</button>
        </form>
    </div>
</div>