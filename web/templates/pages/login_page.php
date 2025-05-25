<?php 
require_once __DIR__ . '/../layouts/page_layout.php';

ob_start();
?>

<h2>Login</h2>
<form action="#" method="POST">
    <label>
        Username:
        <br />
        <input required type="text" name="username" placeholder="johndoe123" />
    </label>
    <label>
        Password:
        <br />
        <input required type="password" name="password" placeholder="Password" />
    </label>
    <button type="submit">Login</button>
</form>
<p>Not yet Registered? <a href="#">Register</a></p>

<?php 
$content = ob_get_clean();

PageLayout("Login", $content);
?>

