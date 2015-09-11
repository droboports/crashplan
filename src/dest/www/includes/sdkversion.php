<?php
unset($out);
exec("/bin/sh /usr/bin/DroboApps.sh sdk_version", $out, $rc);
if ($rc === 0) {
  $sdkversion = $out[0];
} else {
  $sdkversion = "2.0";
}
?>