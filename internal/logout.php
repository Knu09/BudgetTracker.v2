<?php

session_start();

session_unset();
session_destroy();

header("Location: /web/templates/pages/login_page.php");
exit();
