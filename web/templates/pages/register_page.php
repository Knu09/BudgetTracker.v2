<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = $_SESSION['error'] ?? null;
require_once __DIR__ . '/../layouts/page_layout.php';
unset($_SESSION['error']);
ob_start();
?>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div x-data="{ showModal: false }" class="w-full">
    <div id="register" class="flex w-full justify-center min-h-screen account-form">

        <!-- register content -->
        <div class="flex flex-col items-center pt-12">
            <div class="mb-10">
                <a href="/">
                    <img src="/web/static/images/BRIANlysis_dark.svg" class="logo" />
                </a>

            </div>

            <div class="bg-white px-12 pt-5 pb-12 rounded-lg shadow-lg w-full">
                <h1 class="text-3xl mb-6 text-center ">Registration</h1>

                <!-- register form -->
                <form action="/internal/register_server.php" method="post">
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

                    <div class="mb-4 flex items-center">
                        <input type="checkbox" id="terms" name="terms" class="mr-4" required />
                        <label for="terms" class="text-md text-gray-500">I have read and accepted the Terms and the Privacy Policy</label>
                    </div>

                    <!-- tos -->
                    <div class="mb-6 flex items-center justify-center">
                        <a href="#" class="text-md link-text" @click.prevent="showModal = true">Terms of Service-Privacy Policy</a>
                    </div>

                    <?php if ($error): ?>
                    <div class="mb-4 text-red-600 font-semibold"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <!-- submit -->
                    <button type="submit" class="w-full bg-black text-white p-2 rounded-lg mb-4 text-xl">Sign Up</button>
                </form>
                <div class="mb-6 flex items-center justify-center gap-2">
                    <p class="text-md text-black">Already have an account? </p>
                    <a href="/web/templates/pages/login_page.php" class="text-md underline link-text"> Login</a>
                </div>
            </div>
        </div>
    </div>


    <!-- modal -->
    <div x-show="showModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50"
        x-cloak
        @keydown.escape.window="showModal = false">
        <div class="bg-white rounded-lg shadow-lg max-w-2xl p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500 hover:text-black" @click="showModal = false">&times;</button>
            <h2 class="text-xl font-bold mb-4">Terms of Service & Privacy Policy</h2>
            <p class="text-sm text-gray-700 overflow-y-auto max-h-96">
                <strong>1. Acceptance of Terms</strong><br>
                By accessing or using our services, you agree to be bound by these Terms of Service and our Privacy Policy.<br><br>

                <strong>2. Use of Service</strong><br>
                You agree to use the service only for lawful purposes and in a way that does not infringe the rights of others or restrict their use.<br><br>

                <strong>3. Privacy</strong><br>
                We collect, use, and protect your personal information as described in our Privacy Policy.<br><br>

                <strong>4. Intellectual Property</strong><br>
                All content and materials provided are owned by us or our licensors and protected by copyright and trademark laws.<br><br>

                <strong>5. Limitation of Liability</strong><br>
                We are not liable for any damages arising from your use of the service.<br><br>

                <strong>6. Changes to Terms</strong><br>
                We may update these terms from time to time. Continued use constitutes acceptance of any changes.<br><br>

                <strong>7. Contact Us</strong><br>
                For questions about these terms, contact support@example.com.<br><br>
            </p>
            <div class="mt-6 text-right">
                <button class="bg-black text-white px-4 py-2 rounded" @click="showModal = false">Close</button>
            </div>
        </div>
    </div>

</div>
<?php
$content = ob_get_clean();

PageLayout("Register", $content, false);
?>
