<?php
session_start();

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
            <?php if (isset($_SESSION['user'])): ?>
                <form action="/internal/logout.php" method="post">
                    <button type="submit" class="text-white underline">Logout</button>
                </form>
            <?php else: ?>
                <a href="/web/templates/pages/register_page.php" class="underline">Sign up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
