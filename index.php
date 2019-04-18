<?php
require_once 'data.php';
require_once 'functions.php';
require_once 'helpers.php';

$content = include_template('index.php', 
    [
        'lots' => $lots
    ]);

$layout_content = include_template('layout.php', 
    [
        'content' => $content,
        'title' => 'Главная страница',
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'category' => $category
    ]);

print($layout_content);


?>