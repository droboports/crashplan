<p>To access <?php echo $appname; ?> remotely, make sure the Drobo is reachable from the internet. The following <a href="https://en.wikipedia.org/wiki/List_of_TCP_and_UDP_port_numbers" target="_new">port</a> must be reachable from the internet (check your <a href="http://portforward.com/" target="_new">router and/or firewall documentation</a>), and must be forwarded to the Drobo:</p>
<div class="row">
  <div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
      <table class="table table-striped table-bordered table-condensed">
        <thead>
          <td class="text-center">Protocol</td>
          <td class="text-center">Port number</td>
          <td class="text-center">Reachability</td>
        </thead>
        <tbody>
<?php foreach ($appports as $idx => $port) { $proto = $appprotos[$idx]; ?>
          <tr>
            <td class="text-center"><?php echo $proto; ?></td>
            <td class="text-center"><?php echo $port; ?></td>
            <td class="text-center"><a role="button" class="btn btn-default btn-xs" href="http://mxtoolbox.com/SuperTool.aspx?action=<?php echo $proto; ?>%3a<?php echo $publicip; ?>%3a<?php echo $port; ?>&run=toolpage" target="_new"><span class="glyphicon glyphicon-circle-arrow-right"></span> Test</a></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
    </div>
    <div class="col-xs-2"></div>
  </div>
</div>
