<?php
    $search = filter_input(INPUT_POST, 'search_text', FILTER_SANITIZE_SPECIAL_CHARS);
    header("Location: categories.php?searchResult=$search");
    exit;
?>