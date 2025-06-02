<?php
session_start();

define('UPLOAD_DIR', __DIR__ . '/uploads/'); // Use absolute path
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
$allowed_extensions = ['csv']; // Only CSV for fgetcsv

function set_flash_message($message, $type = 'info')
{
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

if (!is_dir(UPLOAD_DIR)) {
    if (!mkdir(UPLOAD_DIR, 0775, true)) {
        set_flash_message('Failed to create upload directory. Check permissions: ' . UPLOAD_DIR, 'error');
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        // ... (standard file upload error handling - see previous PHP example) ...
        $error_messages = [ /* ... */];
        set_flash_message($error_messages[$_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE] ?? 'Unknown upload error.', 'error');
        header('Location: index.php');
        exit;
    }

    $file = $_FILES['file'];
    $filename = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if ($fileSize > MAX_FILE_SIZE) {
        set_flash_message('File is too large.', 'error');
        header('Location: index.php');
        exit;
    }

    if (!in_array($fileExtension, $allowed_extensions)) {
        set_flash_message('Invalid file type. Only CSV files are allowed for this importer.', 'error');
        header('Location: index.php');
        exit;
    }

    $safeFilename = uniqid('csv_upload_', true) . '.' . $fileExtension;
    $targetFilePath = UPLOAD_DIR . $safeFilename;

    if (!move_uploaded_file($fileTmpName, $targetFilePath)) {
        set_flash_message('Failed to move uploaded file.', 'error');
        header('Location: index.php');
        exit;
    }

    // --- FORM DATA ---
    $hasHeader = isset($_POST['header_row']);
    // $customDateFormat = trim($_POST['date_format'] ?? ''); // Not used in this specific fgetcsv example for budget/amount

    // --- CSV PROCESSING with fgetcsv() ---
    $importedBudgets = [];
    $budgetNameColumnIndex = -1;
    $amountColumnIndex = -1;
    $rowCount = 0;

    if (($handle = fopen($targetFilePath, "r")) !== FALSE) {
        while (($rowData = fgetcsv($handle, 1000, ",")) !== FALSE) { // Read line, max 1000 chars, comma delimited
            if (empty(array_filter($rowData, function ($value) {
                return $value !== null && $value !== ''; }))) {
                continue; // Skip completely empty rows
            }

            if ($rowCount === 0 && $hasHeader) {
                // This is the header row, find our column indices
                foreach ($rowData as $index => $headerCell) {
                    $normalizedHeader = strtolower(trim($headerCell));
                    if ($normalizedHeader === 'budget name') {
                        $budgetNameColumnIndex = $index;
                    } elseif ($normalizedHeader === 'amount') {
                        $amountColumnIndex = $index;
                    }
                }
                if ($budgetNameColumnIndex === -1 || $amountColumnIndex === -1) {
                    set_flash_message("Header row detected, but 'budget name' and/or 'amount' columns not found. Check column names.", 'error');
                    fclose($handle);
                    unlink($targetFilePath); // Clean up
                    header('Location: index.php');
                    exit;
                }
            } else {
                // This is a data row (or a file with no header)
                if (!$hasHeader && $rowCount === 0) {
                    // No header, assume first column is budget name, second is amount
                    $budgetNameColumnIndex = 0;
                    $amountColumnIndex = 1;
                }

                if ($budgetNameColumnIndex === -1 || $amountColumnIndex === -1) {
                    // This should not happen if headers were processed correctly or no-header defaults are set
                    // Or if the CSV has fewer columns than expected for a no-header file
                    set_flash_message("Error determining column indices for 'budget name' or 'amount'.", 'error');
                    fclose($handle);
                    unlink($targetFilePath);
                    header('Location: index.php');
                    exit;
                }


                $budgetName = isset($rowData[$budgetNameColumnIndex]) ? trim($rowData[$budgetNameColumnIndex]) : null;
                $amountStr = isset($rowData[$amountColumnIndex]) ? trim($rowData[$amountColumnIndex]) : null;

                // Basic validation
                if ($budgetName === null || $budgetName === '' || $amountStr === null || $amountStr === '') {
                    // Optionally log skipped row or inform user differently
                    // For now, we just skip rows with missing essential data
                    // To be stricter: set_flash_message("Row " . ($rowCount + 1) . " has missing budget name or amount.", 'error'); and exit
                    continue;
                }

                // Convert amount to float, handle potential non-numeric values
                $amount = filter_var($amountStr, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if (!is_numeric($amount)) { // Check if it's a valid number after sanitizing
                    // Optionally log or error for this row
                    // For now, we skip if amount isn't a valid number
                    // set_flash_message("Row " . ($rowCount + 1) . " has an invalid amount: " . htmlspecialchars($amountStr), 'error'); and exit
                    continue;
                }
                $amount = (float) $amount;


                $importedBudgets[] = [
                    'name' => $budgetName,
                    'amount' => $amount
                ];
            }
            $rowCount++;
        }
        fclose($handle);

        // echo "<pre>"; // Makes the output more readable in a browser
        // echo "<h2>Imported Budgets (Debug Output):</h2>";
        // if (!empty($importedBudgets)) {
        //     print_r($importedBudgets);
        // } else {
        //     echo "No budget items were imported or extracted.";
        //     if ($rowCount === 0)
        //         echo "<br>The file might be empty or unreadable.";
        //     elseif ($hasHeader && $rowCount === 1)
        //         echo "<br>The file might only contain a header row.";
        //     elseif ($rowCount > 0)
        //         echo "<br>Processed " . $rowCount . " rows but no valid items found matching criteria.";

        // }
        // echo "</pre>";
        // For even more detail, especially with types:
// var_dump($importedBudgets);

        // IMPORTANT: For testing only. Remove or comment out before the redirect for normal operation.
// unlink($targetFilePath); // You might want to keep the file for inspection during debugging
       

        if (empty($importedBudgets) && $rowCount > ($hasHeader ? 1 : 0)) {
            set_flash_message('No valid budget items found in the CSV after processing.', 'info');
        } elseif (empty($importedBudgets) && $rowCount <= ($hasHeader ? 1 : 0)) {
            set_flash_message('CSV file appears to be empty or only contains a header.', 'info');
        } else {
            $_SESSION['imported_data'] = $importedBudgets;
            set_flash_message('Successfully imported ' . count($importedBudgets) . ' budget items.', 'success');
        }

    } else {
        set_flash_message('Error opening the uploaded CSV file.', 'error');
    }

    // Clean up the uploaded file
    if (file_exists($targetFilePath)) {
        unlink($targetFilePath);
    }

    header('Location: /BudgetTracker.v2/index.php?page=import-form');
    exit;

} else {
    set_flash_message('Invalid request method.', 'error');
    header('Location: index');
    exit;
}
?>