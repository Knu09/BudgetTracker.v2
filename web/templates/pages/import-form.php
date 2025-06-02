<?php
require_once __DIR__ . '/../layouts/page_layout.php';

ob_start();
?>

<div id="import" class="flex items-center justify-center min-h-screen bg-gray-200">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Import CSV/XLS File</h2>
        <form action="/upload" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="csv-file" class="block text-sm font-medium text-gray-700 mb-1">Choose CSV/XLS file:</label>
                <input type="file" name="file" id="csv-file" accept=".csv, text/csv, .xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    class="block w-full border rounded p-2 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold
                            file:bg-black file:text-white hover:file:bg-gray-800"/>
            </div>
            <div class="mb-4">
                <label for="date-format" class="block text-sm font-medium text-gray-700">Date format (optional):</label>
                <input type="text" id="date-format" name="date_format" placeholder="DD/MM/YYYY"
                    class="mt-1 block w-full border rounded p-2" />
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="header-row" name="header_row" class="mr-2" />
                <label for="header-row" class="text-sm text-gray-700">File has header row</label>
            </div>
            <button type="submit" class="w-full bg-black text-white p-2 rounded hover:bg-gray-800 transition-colors">IMPORT</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();

PageLayout("Import/Export CSV", $content);
?>
