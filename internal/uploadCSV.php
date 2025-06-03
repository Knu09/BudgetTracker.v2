<?php
session_start();

// UPLOAD_DIR and targetFilePath might not be needed if you don't move the file
// define('UPLOAD_DIR', __DIR__ . '/uploads/'); 
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
$allowed_extensions = ['csv'];

function set_flash_message($message, $type = 'info')
{
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

// The mkdir for UPLOAD_DIR might not be needed
// if (!is_dir(UPLOAD_DIR)) { ... }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {




    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $error_messages = [ /* ... */]; // Make sure this array is defined
        set_flash_message($error_messages[$_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE] ?? 'Unknown upload error.', 'error');
        header('Location: /index.php?page=import-form');
        exit;

    }


    $file = $_FILES['file'];
    $filename = $file['name'];
    $fileTmpName = $file['tmp_name']; // This is what we'll read from
    $fileSize = $file['size'];
    $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if ($fileSize > MAX_FILE_SIZE) {
        set_flash_message('File is too large.', 'error');
        header('Location: /index.php?page=import-form');
        exit;
    }

    if (!in_array($fileExtension, $allowed_extensions)) {
        set_flash_message('Invalid file type. Only CSV files are allowed for this importer.', 'error');
        header('Location: /index.php?page=import-form');
        exit;
    }

    // --- IMPORTANT SECURITY CHECK if not using move_uploaded_file() ---
    if (!is_uploaded_file($fileTmpName)) {
        set_flash_message('Security error: File was not uploaded via HTTP POST.', 'error');
        header('Location: /index.php?page=import-form');
        exit;
    }
    // The move_uploaded_file block is removed
    // $safeFilename = uniqid('csv_upload_', true) . '.' . $fileExtension;
    // $targetFilePath = UPLOAD_DIR . $safeFilename;
    // if (!move_uploaded_file($fileTmpName, $targetFilePath)) { ... }




    // --- FORM DATA ---
    $hasHeader = isset($_POST['header_row']);


    // --- CSV PROCESSING with fgetcsv() ---
    $importedBudgets = [];
    $budgetNameColumnIndex = -1; // Should be set if headers are found
    $amountColumnIndex = -1;   // Should be set if headers are found
    $rowCount = 0;

    // Assuming $hasHeader is set from $_POST['header_row'] correctly before this point.
// Example: $hasHeader = isset($_POST['header_row']);

    if (($handle = fopen($fileTmpName, "r")) !== FALSE) {
        while (($rowData = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Basic check to skip completely empty rows
            if (empty(array_filter($rowData, function ($value) {
                return $value !== null && $value !== ''; }))) {
                echo "DEBUG ROW $rowCount: Skipping completely empty row.<br>";
                $rowCount++; // Increment before continuing
                continue;
            }

            echo "DEBUG ROW $rowCount: Raw Data: " . htmlspecialchars(implode(' | ', $rowData)) . "<br>";

            if ($rowCount === 0 && $hasHeader) {
                echo "DEBUG ROW $rowCount: Processing as HEADER row.<br>";
                // --- YOUR EXISTING HEADER PROCESSING LOGIC ---
                // (This part seemed to work in your previous debug, so I'll abbreviate)
                foreach ($rowData as $index => $headerCell) {
                    $normalizedHeader = strtolower(trim($headerCell));
                    if ($normalizedHeader === 'budget name')
                        $budgetNameColumnIndex = $index;
                    elseif ($normalizedHeader === 'amount')
                        $amountColumnIndex = $index;
                }
                echo "DEBUG ROW $rowCount: Header processing done. BudgetIdx: $budgetNameColumnIndex, AmountIdx: $amountColumnIndex<br>";
                // --- END OF YOUR HEADER PROCESSING LOGIC ---

                if ($budgetNameColumnIndex === -1 || $amountColumnIndex === -1) {
                    set_flash_message("Header row detected, but 'budget name' and/or 'amount' columns not found. Check column names.", 'error');
                    fclose($handle);
                    die("SCRIPT STOPPED AT HEADER: 'budget name' or 'amount' column not found in header. Check CSV and column names.");
                }
            } else { // This is a DATA row, or a file with no header
                echo "DEBUG ROW $rowCount: Processing as DATA row.<br>";
                echo "DEBUG ROW $rowCount: Current Indices - BudgetIdx: $budgetNameColumnIndex, AmountIdx: $amountColumnIndex<br>";

                if (!$hasHeader && $rowCount === 0) { // Only for the very first row IF no header is expected
                    echo "DEBUG ROW $rowCount: No header expected, first row. Setting default indices (0, 1).<br>";
                    $budgetNameColumnIndex = 0;
                    $amountColumnIndex = 1;
                }

                // Validate that column indices are set (should be from header or no-header default)
                if ($budgetNameColumnIndex === -1 || $amountColumnIndex === -1) {
                    echo "DEBUG ROW $rowCount: SKIPPING - Column indices are not properly set (-1).<br>";
                    $rowCount++; // Increment before continuing
                    continue;
                }

                // Check if $rowData has enough elements for the determined indices
                if (!isset($rowData[$budgetNameColumnIndex]) || !isset($rowData[$amountColumnIndex])) {
                    echo "DEBUG ROW $rowCount: SKIPPING - Row has insufficient columns for determined indices (BudgetIdx: $budgetNameColumnIndex, AmountIdx: $amountColumnIndex).<br>";
                    $rowCount++; // Increment before continuing
                    continue;
                }

                $budgetName = trim($rowData[$budgetNameColumnIndex]);
                $amountStr = trim($rowData[$amountColumnIndex]);
                echo "DEBUG ROW $rowCount: Extracted BudgetName: '" . htmlspecialchars($budgetName) . "', AmountStr: '" . htmlspecialchars($amountStr) . "'<br>";

                // Basic validation for empty strings
                if ($budgetName === '' || $amountStr === '') {
                    echo "DEBUG ROW $rowCount: SKIPPING - BudgetName or AmountStr is empty.<br>";
                    $rowCount++; // Increment before continuing
                    continue;
                }

                // Validate and convert amount
                $amount = filter_var($amountStr, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                echo "DEBUG ROW $rowCount: Amount after filter_var: '" . htmlspecialchars(is_null($amount) ? 'null' : $amount) . "'<br>";

                if ($amount === false || $amount === null || !is_numeric(str_replace(',', '', $amount))) { // More robust check for numeric after filter_var
                    echo "DEBUG ROW $rowCount: SKIPPING - Amount ('" . htmlspecialchars(is_null($amount) ? 'null' : $amount) . "') is not considered numeric.<br>";
                    $rowCount++; // Increment before continuing
                    continue;
                }
                $amount = (float) str_replace(',', '', $amount); // Ensure commas are removed for float conversion

                echo "DEBUG ROW $rowCount: ADDING TO \$importedBudgets: Name='" . htmlspecialchars($budgetName) . "', Amount=$amount<br>";
                $importedBudgets[] = [
                    'name' => $budgetName,
                    'amount' => $amount
                ];
            }
            $rowCount++; // Increment $rowCount AT THE END of each loop iteration
        }
        fclose($handle);

        // --- Logic to set flash messages based on $importedBudgets ---
        // (This part should remain as is)
        if (empty($importedBudgets) && $rowCount > ($hasHeader ? 1 : 0)) {
            set_flash_message('No valid budget items found in the CSV after processing. All rows may have been skipped due to validation issues.', 'info');
        } elseif (empty($importedBudgets) && $rowCount <= ($hasHeader ? 1 : 0)) {
            set_flash_message('CSV file appears to be empty or only contains a header.', 'info');
        } else {
            $_SESSION['imported_data'] = $importedBudgets;
            set_flash_message('Successfully imported ' . count($importedBudgets) . ' budget items.', 'success');
        }

            header('Location: /index.php?page=import-form');
            exit;

    } else {
        set_flash_message('Error opening the uploaded CSV file (temp).', 'error');
            header('Location: /index.php?page=import-form');
            exit;
    }

  

} else {
    set_flash_message('Invalid request method.', 'error');
    header('Location: /index.php?page=import-form');
    exit;
}
?>