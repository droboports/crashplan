<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename=".ui_info"');

readfile('/var/lib/crashplan/.ui_info');
?>