<?php

# Simple solution for preventing XSS attacks

if (!isset($_SESSION['csrf'])) {
    $csrf = md5(uniqid(rand(), TRUE));
    $_SESSION['csrf'] = $csrf;
    $_SESSION['csrf'] = time();
}
else
{
    $csrf = $_SESSION['csrf'];
}?>