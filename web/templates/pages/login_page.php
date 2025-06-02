<?php 
require_once __DIR__ . '/../layouts/page_layout_login.php';

ob_start();
?>

 <div class="login-container">
        <h2>User Authentication</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="name@mail.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="password123" required>
            </div>

            <a href="#" class="forgot-password">Forgot password</a>

            <button type="submit" class="btn btn-primary">Sign In</button>

            <div class="separator">or</div>

            <button type="button" class="btn btn-secondary">Register</button>
        </form>
    </div>





<?php 
$content = ob_get_clean();

PageLayout("Login", $content);
?>

