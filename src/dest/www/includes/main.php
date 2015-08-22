<div class="container">
  <div class="row">
    <div class="col-xs-12">

  <!-- summary -->
  <div class="panel-group" id="summary">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#summary" href="#summarybody">Summary of changes</a></h4>
      </div>
      <div id="summarybody" class="panel-collapse collapse in">
        <div class="panel-body">
          <p>To access CrashPlan on your Drobo from your desktop computer you need to:</p>
          <ol>
            <li>Change the file <code>.ui_info</code> on your desktop machine to match the one on the Drobo.</li>
            <li>Change the file <code>ui.properties</code> on your desktop machine to indicate the Drobo's IP address.</li>
          </ol>
          <p>Quick reference:</p>
          <form class="form-horizontal">
            <div class="form-group">
              <label for="ui_info" class="col-sm-2 control-label">ui_info</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="ui_info" value="<?php echo file_get_contents('/var/lib/crashplan/.ui_info'); ?>" readonly />
              </div>
            </div>
            <div class="form-group">
              <label for="ui_properties" class="col-sm-2 control-label">ui.properties</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="ui_properties" value="serviceHost=<?php echo $_SERVER['SERVER_ADDR']; ?>" readonly />
              </div>
            </div>
          </form>
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
                    <input type="text" class="form-control" id="ui_info2" value="<?php echo file_get_contents('/var/lib/crashplan/.ui_info'); ?>" readonly />
                  </div>
                </div>
              </form>
              <p><strong>This file will have to be updated if your Drobo changes its <code>.ui_info</code> information. Check this page if you are unable to connect to your Drobo.</strong></p>
              <p>Save the file, and and proceed to <code>ui.properties</code>.</p>
              <p>Alternatively you can download the file by clicking the link below:</p>
              <a class="btn btn-success" download=".ui_info" href="ui_info.php"><span class="glyphicon glyphicon-download"></span> Download ui_info</a>
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
              <p><code>serviceHost=<?php echo $_SERVER['SERVER_ADDR']; ?></code></p>
              <p><strong>This line will have to be updated if your Drobo's IP address is changed.</strong></p>
              <p>Save the file, and start the CrashPlan client. After a few moments you should see a login screen for your Drobo.</p>
            </div>
          </div>

        </div><!-- panel-body -->
      </div><!-- panel-collapse -->
    </div><!-- panel -->
  </div><!-- panel-group -->

  <div class="row">
    <div class="col-xs-12">
      <p>More information:</p>
      <ul>
        <li><a href="http://support.code42.com/CrashPlan/Latest/Configuring/Using_CrashPlan_On_A_Headless_Computer" target="_new">Using CrashPlan on a headless computer</a></li>
      </ul>
    </div>
  </div>

    </div><!-- col -->
  </div><!-- row -->
</div><!-- container -->
