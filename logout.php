<?php
/*require_once 'data.php';*/
require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

unset($_SESSION['username']);
header("Location: /index.php");

?>