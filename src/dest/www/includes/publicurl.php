<?php if ($publicip != "") { ?>
<p>This is currently the public URL of the Drobo-hosted <?php echo $appname; ?> server:</p>
<form class="form-horizontal">
  <div class="form-group">
    <label for="publicurl" class="col-sm-2 control-label">Public URL:</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="publicurl" value="<?php echo $publicurl; ?>" readonly />
    </div>
  </div>
</form>
<p>This URL can be used to configure mobile and desktop clients.</p>
<p>If your public IP address changes over time<?php if ($publicip != "") { ?> (currently <?php echo $publicip; ?>)<?php } ?>, please configure a <a href="http://www.howtogeek.com/66438/how-to-easily-access-your-home-network-from-anywhere-with-ddns/" target="_new">dynamic DNS address</a> to your public IP address.</p>
<?php } else { ?>
<p>No public IP address could be found. The Drobo-hosted <?php echo $appname; ?> server will not be accessible from the internet.</p>
<p>Once an internet connection is reestablished, the public URL of the Drobo-hosted <?php echo $appname; ?> server will look like this:</p>
<form class="form-horizontal">
  <div class="form-group">
    <label for="publicurl" class="col-sm-2 control-label">Public URL:</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="publicurl" value="<?php echo $publicurl; ?>" readonly />
    </div>
  </div>
</form>
<?php } ?>