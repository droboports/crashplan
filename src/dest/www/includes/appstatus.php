<?php 
unset($out);
exec("/usr/bin/DroboApps.sh status_app ".$app, $out, $rc);
if (strpos($out[0], "enabled") !== FALSE) {
  $appenabled = TRUE;
} else {
  $appenabled = FALSE;
}
if (strpos($out[0], "running") !== FALSE) {
  $apprunning = TRUE;
} else {
  $apprunning = FALSE;
}
?>