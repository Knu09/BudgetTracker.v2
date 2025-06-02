<?php
session_start();
$error = $_SESSION['error'] ?? null;
require_once __DIR__ . '/../layouts/page_layout.php';
unset($_SESSION['error']);
ob_start();
?>

<div id="login" class="flex w-full justify-center min-h-screen account-form">
    <div class="col-span-3"></div>

    <!-- register content -->
    <div class="col-span-6 flex flex-col items-center pt-12">
        <div class="mb-10">
            <a href="/">

                <img src="../../static/images/BRIANlysis_dark.svg" class="logo" />
            </a>

        </div>

        <div class="bg-white px-12 pt-5 pb-12 rounded-lg shadow-lg w-full">
            <h1 class="text-3xl mb-6 text-center ">Log in</h1>

            <!-- register form -->
            <form action="../../../internal/login_server.php" method="post">
                <!-- email field -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="name@mail.com"
                        class="mt-1 block w-full border rounded p-2" required />
                </div>

                <!-- password filed -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="password123"
                        class="mt-1 block w-full border rounded p-2" required />
                </div>

                <?php if ($error): ?>
                <div class="mb-4 text-red-600 font-semibold"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <!-- submit -->
                <button type="submit" class="w-full bg-black text-white p-2 rounded-lg mb-4 text-xl">Log in</button>
            </form>
            <div class="mb-2 flex items-center justify-center gap-2">
                <p class="text-md text-black">Don't have an account yet? </p>
                <a href="/web/templates/pages/register_page.php" class="text-md underline link-text"> Register</a>
            </div>
        </div>
    </div>
    <div class="col-span-3"></div>
</div>





<?php
$content = ob_get_clean();

PageLayout("Login", $content, false);
?>
