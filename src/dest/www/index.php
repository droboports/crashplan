<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

require ('includes/header.php');
require ('includes/main.php');
require ('includes/footer.php');
?>
