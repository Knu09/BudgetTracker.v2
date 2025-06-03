
<?php
session_start(); // MUST BE THE VERY FIRST THING

?>


<?php
// This assumes session_start() has already been called at the very top
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $type = $_SESSION['flash_type'] ?? 'info';

    // Determine Tailwind CSS classes based on $type
    $alertClasses = 'border-l-4 p-4 relative'; // Added 'relative' for positioning the button
    $textClasses = '';
    $buttonHoverClasses = '';

    if ($type === 'success') {
        $alertClasses .= ' bg-green-100 border-green-500 text-green-700';
        $textClasses = 'text-green-700';
        $buttonHoverClasses = 'hover:bg-green-200 hover:text-green-600';
    } elseif ($type === 'error') {
        $alertClasses .= ' bg-red-100 border-red-500 text-red-700';
        $textClasses = 'text-red-700';
        $buttonHoverClasses = 'hover:bg-red-200 hover:text-red-600';
    } elseif ($type === 'warning') {
        $alertClasses .= ' bg-yellow-100 border-yellow-500 text-yellow-700';
        $textClasses = 'text-yellow-700';
        $buttonHoverClasses = 'hover:bg-yellow-200 hover:text-yellow-600';
    } else { // Default to 'info'
        $alertClasses .= ' bg-blue-100 border-blue-500 text-blue-700';
        $textClasses = 'text-blue-700';
        $buttonHoverClasses = 'hover:bg-blue-200 hover:text-blue-600';
    }

    // Give the flash message a unique ID for JavaScript targeting
    $flashMessageId = 'flash-message-'.uniqid();
?>

    <div id="<?php echo $flashMessageId; ?>" class="<?php echo $alertClasses; ?>" role="alert">
        <div class="flex justify-between items-start"> <!-- Flex container for message and button -->
            <div> <!-- Message content container -->
                <p class="font-bold"><?php echo ucfirst(htmlspecialchars($type)); ?></p>
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
            <button type="button"
                    class="ml-4 -mt-1 -mr-1 p-1 rounded-md <?php echo $textClasses; ?> <?php echo $buttonHoverClasses; ?> focus:outline-none focus:ring-2 focus:ring-offset-2 <?php echo ($type === 'success' ? 'focus:ring-green-500' : ($type === 'error' ? 'focus:ring-red-500' : ($type === 'warning' ? 'focus:ring-yellow-500' : 'focus:ring-blue-500'))); ?>"
                    onclick="document.getElementById('<?php echo $flashMessageId; ?>').style.display='none';"
                    aria-label="Dismiss">
                <span class="sr-only">Dismiss</span>
                <!-- Heroicon name: solid/x (or any other X icon) -->
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

<?php
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}
?>



<?php if (isset($_SESSION['imported_data']) && !empty($_SESSION['imported_data'])): ?>
    <?php
        // Generate a unique ID for this container
        $importedDataContainerId = 'imported-data-container-' . uniqid();
    ?>
    <div id="<?php echo $importedDataContainerId; ?>" class="container mx-auto mt-4 bg-white p-6 rounded-lg shadow-lg relative"> <!-- Added 'relative' -->
        <div class="flex justify-between items-start mb-2"> <!-- Flex container for title and button -->
            <h3 class="text-md font-semibold">Imported Budget Items (First 10):</h3>
            <button type="button"
                    class="ml-4 -mt-1 p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    onclick="document.getElementById('<?php echo $importedDataContainerId; ?>').style.display='none';"
                    aria-label="Dismiss imported items table">
                <span class="sr-only">Dismiss</span>
                <!-- Heroicon name: solid/x (or any other X icon) -->
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

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
        <?php unset($_SESSION['imported_data']); // This still runs after the block is rendered ?>
    </div>
<?php endif; ?>

<div id="import" class="flex my-4">
    <div class="bg-white mx-auto p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Import CSV File</h2>
        <p class="text-xs text-gray-500 mb-2">Note: This importer currently only processes CSV files.</p>
        <form action="/internal/uploadCSV.php" method="post" enctype="multipart/form-data">
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