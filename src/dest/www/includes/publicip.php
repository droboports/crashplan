<?php
unset($out);
exec("/usr/bin/timeout -t 1 /usr/bin/wget -qO- http://ipecho.net/plain", $out, $rc);
if ($rc === 0) {
  $publicip = $out[0];
} else {
  $publicip = "";
}
?>