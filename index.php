<?php
require_once 'web/templates/layouts/page_layout.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$requestPath = trim($requestPath, '/');
// $page = $_GET['page'] ?? 'index';

$page_title = '';
$page_content = '';
$show_main_layout = false; // To control if sidebar/header are shown

switch ($requestPath) {
    case 'import-form':
        $page_title = 'Import/Export CSV';
        ob_start();
        require_once 'web/templates/pages/import-form.php';
        $page_content = ob_get_clean();
        break;

    case 'login':
        $page_title = 'Login';
        ob_start();
        require_once 'web/templates/pages/login.php';
        $page_content = ob_get_clean();
        $show_main_layout = true; 
        break;

    case 'dashboard':
        $page_title = 'Import';
        ob_start();
        require_once 'web/templates/pages/import-form.php';
        $page_content = ob_get_clean();
        // $show_main_layout = true; // (default)
        break;
}

PageLayout($page_title, $page_content, false);
?>