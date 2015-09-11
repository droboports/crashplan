<?php if (! $apprunning) { ?>
  <p><strong>I cannot connect to <?php echo $appname; ?> on the Drobo.</strong></p>
  <p>Make sure that <?php echo $appname; ?> is running. Currently it seems to be <strong>stopped</strong>.</p>
<?php } ?>
  <p><strong>I cannot connect to <?php echo $appname; ?> on the Drobo from the internet.</strong></p>
<?php if ($publicip == "") { ?>
  <p>Make sure that your internet connection is working. Currently it seems the Drobo cannot retrieve its public IP address.</p>
<?php } ?>
  <p>Make sure that your ports are correctly forwarded and <a href="<?php echo $portscansite; ?>" target="_new">reachable from the internet</a>. If not, please contact your ISP to unblock them.</p>