<?php
function __($text) {
    if (!isset($_SESSION['lang']) || $_SESSION['lang'] === 'en') {
        return $text;
    }

    static $translations = null;

    if ($translations === null && $_SESSION['lang'] === 'pt') {
        $translations = include 'portugese.php';
    }

    return $translations[$text] ?? $text;
}
?>