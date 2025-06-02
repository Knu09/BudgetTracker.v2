<?php
function PageLayout($title, $children, $showLayout = true)
{
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= htmlspecialchars($title) ?></title>

        <!-- TailwindCss -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <script src="https://kit.fontawesome.com/3f41037839.js" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet" />
        <link rel="stylesheet" href="web/static/css/style.css" />
        <link rel="stylesheet" href="web/static/css/output.css" />
        <link rel="stylesheet" href="../../static/css/account-form.css" />
        <link rel="stylesheet" href="./../../static/css/login-style.css" />
        <script defer src="https://cdn.jsdelivr.net/npm/alpinjs@3.x.x/dist/cdn.min.js"></script>

    </head>

    <body class="h-screen font-[Poppins] bg-secondary" x-data="{open: true}">
        <?php if ($showLayout): ?>
            <div class="flex w-screen h-full">
                <?php include __DIR__ . '/../components/sidebar.php'; ?>
                <div class="grow">
                    <?php include __DIR__ . '/../components/header.php'; ?>
                    <main id="main-content">
                        <?= $children ?>
                    </main>
                </div>
            </div>
        <?php else: ?>
            <main id="main-content">
                <?= $children ?>
            </main>
        <?php endif; ?>
    </body>

    </html>
<?php
}
?>
