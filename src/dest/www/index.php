<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

include('includes/sdkversion.php');
include('includes/publicip.php');
include('includes/variables.php');

$op = $_REQUEST['op'];
switch ($op) {
  case "start":
    unset($out);
    exec("/bin/sh /usr/bin/DroboApps.sh start_app ".$app, $out, $rc);
    if ($rc !== 0) {
      unset($out);
      exec("/usr/bin/setsid /mnt/DroboFS/Shares/DroboApps/".$app."/service.sh start 1> /dev/null 2>&1", $out, $rc);
    }
    if ($rc === 0) {
      $opstatus = "okstart";
    } else {
      $opstatus = "nokstart";
    }
    break;
  case "stop":
    unset($out);
    exec("/bin/sh /usr/bin/DroboApps.sh stop_app ".$app, $out, $rc);
    if ($rc !== 0) {
      unset($out);
      exec("/mnt/DroboFS/Shares/DroboApps/".$app."/service.sh stop", $out, $rc);
    }
    if ($rc === 0) {
      $opstatus = "okstop";
    } else {
      $opstatus = "nokstop";
    }
    break;
  case "logs":
    $opstatus = "logs";
    break;
  default:
    $opstatus = "noop";
    break;
}

include('includes/appstatus.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="-1" />
  <meta http-equiv="pragma" content="no-cache" />
  <title><?php echo $appname; ?> DroboApp</title>
  <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="css/custom.css" />
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>

<body>
<!-- logo bar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo $appsite; ?>" target="_new"><img alt="<?php echo $appname; ?>" src="img/app_logo.png" /></a>
    </div>
    <div class="collapse navbar-collapse" id="navbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a class="navbar-brand" href="http://www.drobo.com/" target="_new"><img alt="Drobo" src="img/drobo_logo.png" /></a></li>
      </ul>
    </div>
  </div>
</nav>
<!-- /logo bar -->

<!-- title and button bar -->
<div class="container top-toolbar">
  <div role="toolbar" class="btn-toolbar">
    <div role="group" class="btn-group">
      <p class="title">About <?php echo $app; ?> <?php echo $appversion; ?></p>
    </div>
    <div role="group" class="btn-group pull-right">
<?php if ($apprunning) { ?>
      <a role="button" class="btn btn-primary" href="?op=stop" onclick="$('#pleaseWaitDialog').modal(); return true"><span class="glyphicon glyphicon-stop"></span> Stop</a>
<?php } else { ?>
      <a role="button" class="btn btn-primary" href="?op=start" onclick="$('#pleaseWaitDialog').modal(); return true"><span class="glyphicon glyphicon-play"></span> Start</a>
<?php } ?>
      <a role="button" class="btn btn-primary" href="<?php echo $apppage; ?>" target="_new"><span class="glyphicon glyphicon-globe"></span> Management website</a>
      <a role="button" class="btn btn-primary" href="<?php echo $apphelp; ?>" target="_new"><span class="glyphicon glyphicon-question-sign"></span> Help</a>
    </div>
  </div>
</div>
<!-- /title bar -->

<!-- operation modal wait -->
<div role="dialog" id="pleaseWaitDialog" class="modal animated bounceIn" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <p id="myModalLabel">Operation in progress... please wait.</p>
        <div class="progress">
          <div class="progress-bar progress-bar-striped active" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /operation modal wait -->

<!-- page sections -->
<div class="container">

<!-- operation feedback -->
  <div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-6">
<?php switch ($opstatus) { ?>
<?php case "okstart": ?>
      <div class="alert alert-success fade in" id="opstatus">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $appname; ?> was successfully started.
      </div>
<?php break; case "nokstart": ?>
      <div class="alert alert-danger fade in" id="opstatus">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $appname; ?> failed to start. See logs below for more information.
      </div>
<?php break; case "okstop": ?>
      <div class="alert alert-success fade in" id="opstatus">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $appname; ?> was successfully stopped.
      </div>
<?php break; case "nokstop": ?>
      <div class="alert alert-danger fade in" id="opstatus">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $appname; ?> failed to stop. See logs below for more information.
      </div>
<?php break; } ?>
      <script>
      window.setTimeout(function() {
        $("#opstatus").fadeTo(500, 0).slideUp(500, function() {
          $(this).remove(); 
        });
      }, 2000);
      </script>
    </div><!-- col -->
    <div class="col-xs-3"></div>
  </div><!-- row -->
<!-- /operation feedback -->

  <div class="row">
    <div class="col-xs-12">

  <!-- description -->
  <div class="panel-group" id="description">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#description" href="#descriptionbody">Description</a></h4>
      </div>
      <div id="descriptionbody" class="panel-collapse collapse in">
        <div class="panel-body">
<?php include('includes/description.php'); ?>
          <div class="pull-right">
            <a role="button" class="btn btn-default" href="<?php echo $appsite; ?>" target="_new"><span class="glyphicon glyphicon-globe"></span> Learn more about <?php echo $appname; ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- getting started -->
  <div class="panel-group" id="gettingstarted">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#gettingstarted" href="#gettingstartedbody">Getting started</a></h4>
      </div>
      <div id="gettingstartedbody" class="panel-collapse collapse in">
        <div class="panel-body">
          <p>To access <?php echo $appname; ?> on your Drobo from your desktop computer you need to:</p>
          <ol>
            <li>Change the file <code>.ui_info</code> on your desktop machine to match the one on the Drobo.</li>
            <li>Change the file <code>ui.properties</code> on your desktop machine to indicate the Drobo&apos;s IP address.</li>
          </ol>
          <p>Quick reference:</p>
          <form class="form-horizontal">
            <div class="form-group">
              <label for="ui_info" class="col-sm-2 control-label">ui_info</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="ui_info" value="<?php echo $uiinfo; ?>" readonly />
              </div>
            </div>
            <div class="form-group">
              <label for="ui_properties" class="col-sm-2 control-label">ui.properties</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="ui_properties" value="serviceHost=<?php echo $droboip; ?>" readonly />
              </div>
            </div>
          </form>
          <p>Please make sure to secure access to your <?php echo $appname; ?> installation by enabling password access in
&quot;Settings&quot; &gt; &quot;Security&quot; &gt; &quot;Require account password to access <?php echo $appname; ?> desktop application&quot;</p>
          <p>Once logged in, if the folder selection screen shows the path <code>/mnt/DroboFS/Shares</code>, then <?php echo $appname; ?> is running on your Drobo.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ui_info -->
  <div class="panel-group" id="uiinfo">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#uiinfo" href="#uiinfobody">How to change .ui_info</a></h4>
      </div>
      <div id="uiinfobody" class="panel-collapse collapse">
        <div class="panel-body">
          <p>Please choose your desktop system:</p>

          <div class="panel-group" id="accordion">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#windowsvista">Windows Vista, 7, 8, 10, Server 2008, and Server 2012</a></h4>
              </div><!-- panel-heading -->
              <div id="windowsvista" class="panel-collapse collapse">
                <div class="panel-body">
                  <p>These are the locations for the file <code>.ui_info</code>. Use that path and filename when editing or saving the file.</p>
                  <p>When in doubt, use the path for everyone first.</p>
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="windowsvistaall" class="col-sm-2 control-label">Installed for everyone</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="windowsvistaall" value="C:\ProgramData\CrashPlan\.ui_info" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="windowsvistauser" class="col-sm-2 control-label">Installed per user</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="windowsvistauser" value="C:\Users\&lt;username&gt;\AppData\&lt;Local|Roaming&gt;\CrashPlan\.ui_info" readonly />
                      </div>
                    </div>
                  </form>
                </div><!-- panel-body -->
              </div><!-- panel-collapse -->
            </div><!-- panel -->

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#windowsxp">Windows XP</a></h4>
              </div><!-- panel-heading -->
              <div id="windowsxp" class="panel-collapse collapse">
                <div class="panel-body">
                  <p>These are the locations for the file <code>.ui_info</code>. Use that path and filename when editing or saving the file.</p>
                  <p>When in doubt, use the path for everyone first.</p>
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="windowsxpall" class="col-sm-2 control-label">Installed for everyone</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="windowsxpall" value="C:\Documents and Settings\All Users\Application Data\CrashPlan\.ui_info" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="windowsxpuser" class="col-sm-2 control-label">Installed per user</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="windowsxpuser" value="C:\Documents and Settings\&lt;username&gt;\Application Data\CrashPlan\.ui_info" readonly />
                      </div>
                    </div>
                  </form>
                </div><!-- panel-body -->
              </div><!-- panel-collapse -->
            </div><!-- panel -->

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#osx">OS X</a></h4>
              </div><!-- panel-heading -->
              <div id="osx" class="panel-collapse collapse">
                <div class="panel-body">
                  <p>These are the locations for the file <code>.ui_info</code>. Use that path and filename when editing or saving the file.</p>
                  <p>When in doubt, use the path for everyone first.</p>
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="osxall" class="col-sm-2 control-label">Installed for everyone</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="osxall" value="/Library/Application Support/CrashPlan/.ui_info" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="osxuser" class="col-sm-2 control-label">Installed per user</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="osxuser" value="~/Library/Application Support/CrashPlan/.ui_info" readonly />
                      </div>
                    </div>
                  </form>
                </div><!-- panel-body -->
              </div><!-- panel-collapse -->
            </div><!-- panel -->

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#linux">Linux/Solaris</a></h4>
              </div><!-- panel-heading -->
              <div id="linux" class="panel-collapse collapse">
                <div class="panel-body">
                  <p>This is the location for the file <code>.ui_info</code>. Use that path and filename when editing or saving the file.</p>
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="linuxall" class="col-sm-2 control-label">Installed for everyone</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="linuxall" value="/var/lib/crashplan/.ui_info" readonly />
                      </div>
                    </div>
                  </form>
                </div><!-- panel-body -->
              </div><!-- panel-collapse -->
            </div><!-- panel -->
          </div><!-- panel-group -->

          <div class="row">
            <div class="col-xs-12">
              <p>Open the <code>.ui_info</code> file in a text editor, and replace its content with the line below:</p>
              <form class="form-horizontal">
                <div class="form-group">
                  <label for="ui_info2" class="col-sm-2 control-label">Content of .ui_info</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="ui_info2" value="<?php echo $uiinfo; ?>" readonly />
                  </div>
                </div>
              </form>
              <p><strong>This file will have to be updated if your Drobo changes its <code>.ui_info</code> information. Check this page if you are unable to connect to your Drobo.</strong></p>
              <p>Save the file, and and proceed to <code>ui.properties</code>.</p>
              <p>Alternatively you can download the file by clicking the link below:</p>
              <a class="btn btn-default" download=".ui_info" href="ui_info.php"><span class="glyphicon glyphicon-download"></span> Download ui_info</a>
            </div>
          </div>

        </div><!-- panel-body -->
      </div><!-- panel-collapse -->
    </div><!-- panel -->
  </div><!-- panel-group -->

  <!-- ui.properties -->
  <div class="panel-group" id="uiproperties">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#uiproperties" href="#uipropertiesbody">How to change ui.properties</a></h4>
        <h3 class="panel-title"></h3>
      </div>
      <div id="uipropertiesbody" class="panel-collapse collapse">
        <div class="panel-body">
          <p>Please choose your desktop operating system:</p>

          <div class="panel-group" id="accordion2">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion2" href="#windows2">Windows</a>
                </h4>
              </div><!-- panel-heading -->
              <div id="windows2" class="panel-collapse collapse">
                <div class="panel-body">
                  <p>This is the location for the file <code>ui.properties</code>. Open this file using a text editor.</p>
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="uipropertieswin" class="col-sm-2 control-label">ui.properties</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="uipropertieswin" value="C:\Program Files\CrashPlan\conf\ui.properties" readonly />
                      </div>
                    </div>
                  </form>
                </div><!-- panel-body -->
              </div><!-- panel-collapse -->
            </div><!-- panel -->

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion2" href="#osx2">OS X</a>
                </h4>
              </div><!-- panel-heading -->
              <div id="osx2" class="panel-collapse collapse">
                <div class="panel-body">
                  <p>This is the location for the file <code>ui.properties</code>. Open this file using a text editor.</p>
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="uipropertiesosx" class="col-sm-2 control-label">ui.properties</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="uipropertiesosx" value="/Applications/CrashPlan.app/Contents/Resources/Java/conf/ui.properties" readonly />
                      </div>
                    </div>
                  </form>
                </div><!-- panel-body -->
              </div><!-- panel-collapse -->
            </div><!-- panel -->

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion2" href="#linux2">Linux</a>
                </h4>
              </div><!-- panel-heading -->
              <div id="linux2" class="panel-collapse collapse">
                <div class="panel-body">
                  <p>This is the location for the file <code>ui.properties</code>. Open this file using a text editor.</p>
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="uipropertieslinux" class="col-sm-2 control-label">ui.properties</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="uipropertieslinux" value="/usr/local/crashplan/conf/ui.properties" readonly />
                      </div>
                    </div>
                  </form>
                </div><!-- panel-body -->
              </div><!-- panel-collapse -->
            </div><!-- panel -->

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion2" href="#solaris2">Solaris</a>
                </h4>
              </div><!-- panel-heading -->
              <div id="solaris2" class="panel-collapse collapse">
                <div class="panel-body">
                  <p>This is the location for the file <code>ui.properties</code>. Open this file using a text editor.</p>
                  <form class="form-horizontal">
                    <div class="form-group">
                      <label for="uipropertiessolaris" class="col-sm-2 control-label">ui.properties</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="uipropertiessolaris" value="/opt/sfw/crashplan/conf/ui.properties" readonly />
                      </div>
                    </div>
                  </form>
                </div><!-- panel-body -->
              </div><!-- panel-collapse -->
            </div><!-- panel -->
          </div><!-- panel-group -->

          <div class="row">
            <div class="col-xs-12">
              <p>Open the <code>ui.properties</code> file in a text editor, look for the line that looks like:</p>
              <p><code>#serviceHost=127.0.0.1</code></p>
              <p>And replace it with:</p>
              <p><code>serviceHost=<?php echo $droboip; ?></code></p>
              <p><strong>This line will have to be updated if your Drobo&apos;s IP address is changed.</strong></p>
              <p>Save the file, and start the CrashPlan client. After a few moments you should see a login screen for your Drobo.</p>
            </div>
          </div>

        </div><!-- panel-body -->
      </div><!-- panel-collapse -->
    </div><!-- panel -->
  </div><!-- panel-group -->

  <!-- troubleshooting -->
  <div class="panel-group" id="troubleshooting">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#troubleshooting" href="#troubleshootingbody">Troubleshooting</a></h4>
      </div>
      <div id="troubleshootingbody" class="panel-collapse collapse">
        <div class="panel-body">
<?php include('includes/troubleshooting.php'); ?>
        </div>
      </div>
    </div>
  </div>

  <!-- logfiles -->
  <div class="panel-group" id="logfiles">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#logfiles" href="#logfilesbody">Log information</a></h4>
      </div>
      <div id="logfilesbody" class="panel-collapse collapse <?php if ($opstatus == "logs") { ?>in<?php } ?>">
        <div class="panel-body">
<?php include('includes/logfiles.php'); ?>
        </div>
      </div>
    </div>
  </div>

  <!-- changelog -->
  <div class="panel-group" id="changelog">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#changelog" href="#changelogbody">Summary of changes</a></h4>
      </div>
      <div id="changelogbody" class="panel-collapse collapse">
        <div class="panel-body">
<?php include('includes/changelog.php'); ?>
        </div>
      </div>
    </div>
  </div>

    </div><!-- col -->
  </div><!-- row -->
</div><!-- container -->
<!-- /page sections -->

<footer>
  <div class="container">
    <div class="pull-right">
      <small>All copyrighted materials and trademarks are the property of their respective owners.</small>
    </div>
  </div>
</footer>
</body>
</html>
