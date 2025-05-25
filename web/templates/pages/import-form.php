<?php
require_once __DIR__ . '/../layouts/page_layout.php';

ob_start();
?>

<div class="flex items-center justify-center min-h-screen bg-gray-200">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Import CSV/XLS File</h2>
        <form action="/upload" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <input type="file" name="file" class="block w-full border rounded p-2" />
            </div>
            <div class="mb-4">
                <label for="date-format" class="block text-sm font-medium text-gray-700">Date format:</label>
                <input type="text" id="date-format" name="date_format" placeholder="DD/MM/YYYY"
                    class="mt-1 block w-full border rounded p-2" />
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="header-row" name="header_row" class="mr-2" />
                <label for="header-row" class="text-sm text-gray-700">Header row present</label>
            </div>
            <button type="submit" class="w-full bg-black text-white p-2 rounded">IMPORT</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();

PageLayout("Import/Export CSV", $content);
?>
