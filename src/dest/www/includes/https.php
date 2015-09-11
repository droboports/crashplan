<?php if ($appprotos[0] == "https") { ?>
<p>To ensure data privacy, <?php echo $appname; ?> is only accessible over HTTPS. If no SSL certificates are available, <?php echo $appname; ?> will automatically generate a self-signed certificate. <strong>Using self-signed certificates will cause warnings from some web browsers:</strong></p>
<ul>
  <li>In Google Chrome, there will be a page indicating that &quot;your connection is not private&quot;. Click the &quot;Advanced&quot; link at the bottom of that page, and then the &quot;Proceed to <?php echo $droboip; ?> (unsafe)&quot; link further below.</li>
  <li>In Firefox, there will be a page indicating that &quot;this connection is untrusted&quot;. Click the &quot;I understand the risks&quot; link at the bottom of that page, then the &quot;Add Exception...&quot; button further below, and then the &quot;Confirm Security Exception&quot; button at the bottom of the dialog window.</li>
  <li>In Safari, there will be a dialog window indicating that &quot;Safari can&apos;t verify the identity of the wesite &ldquo;<?php echo $droboip; ?>&rdquo;.&quot;. Click the &quot;Continue&quot; button.</li>
</ul>
<p>To avoid browser warnings, please:</p>
<ul>
  <li>Either replace the server certificate and private key with a certificate from a certification authority (see below),</li>
  <li>Or add the self-signed certificate to your browser&apos;s certificate store.</li>
</ul>
<?php } ?>