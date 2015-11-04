<?php 
$app = "crashplan";
$appname = "Crashplan";
$appversion = "4.4.1-59";
$appsite = "https://www.code42.com/crashplan/";
$apphelp = "https://support.code42.com/CrashPlan";
$apppage = "https://www.crashplan.com/account/login.vtl";

$applogs = array("/tmp/DroboApps/".$app."/log.txt",
                 "/tmp/DroboApps/".$app."/app.log",
                 "/tmp/DroboApps/".$app."/service.log.0",
                 "/tmp/DroboApps/".$app."/history.log.0",
                 "/tmp/DroboApps/".$app."/backup_files.log.0",
                 "/tmp/DroboApps/".$app."/restore_files.log.0",
                 "/tmp/DroboApps/".$app."/install.log",
                 "/tmp/DroboApps/".$app."/update.log");

$droboip = $_SERVER['SERVER_ADDR'];
$portscansite = "http://mxtoolbox.com/SuperTool.aspx?action=scan%3a".$publicip."&run=toolpage";

$uiinfo = file_get_contents('/var/lib/crashplan/.ui_info');
$uiinfo = str_replace("0.0.0.0", $droboip, $uiinfo);
?>
