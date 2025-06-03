<?php
// Ensure session is started.
// It's good practice to check if it's already started,
// especially if this file might be included in various places.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$user_display_email = "Guest"; // Default

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    $user_display_email = $_SESSION['user_email'];
} elseif (isset($_SESSION['user_id'])) {
    $user_display_email = "Email not found";
}


?>


<nav class="bg-[#252525] text-white">
    <div class="flex flex-wrap items-center justify-between mx-auto p-6">
        <div class="flex gap-3">
            <button type="button" id="menuToggleBtn" name="menu" class="cursor-pointer" @click="open = !open">
                <span class="sr-only">Open sidebar</span>
                <i class="fa-solid fa-bars"></i>
            </button>
            <h3 class="font-medium">Budget and Expense</h3>
        </div>
        <div>





            <?php if (isset($_SESSION['user_id'])): // Check if user IS logged in ?>
                <span class="mr-4"><?php echo "Logged in as: " . htmlspecialchars($user_display_email); ?></span>

                <form action="/internal/logout.php" method="post" style="display: inline;">
                    <button type="submit" class="text-white underline">Logout</button>
                </form>
            <?php else: // User is NOT logged in ?>
                <a href="/web/templates/pages/login_page.php" class="underline mr-2">Login</a> <span class="mx-1">|</span>
                <a href="/web/templates/pages/register_page.php" class="underline">Sign up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>