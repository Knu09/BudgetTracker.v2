<?php
require_once 'web/templates/layouts/page_layout.php';

ob_start();
?>
<h1>Welcome to the Dashboard</h1>
<p>This is a sample content.</p>
<?php
$content = ob_get_clean();

PageLayout('Dashboard', $content);
?>
