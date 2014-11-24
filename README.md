crashplan
=========

This is a set of scripts to package a DroboApp from scratch, i.e., download sources, unpackage, compile, install, and package in a TGZ file. The `master` branch contains the Drobo5N version, the `drobofs` branch contains the DroboFS version.

## I just want to install the DroboApp, what do I do?

Check the [releases](https://github.com/droboports/crashplan/releases) page. If there are no releases available, then you have to compile.

Keep in mind that this DroboApp requires [java7](https://github.com/droboports/java7) and the [locale](https://github.com/droboports/locale) apps to be installed.

## How to compile

First make sure that you have a [working cross-compiling VM](https://github.com/droboports/droboports.github.io/wiki/Setting-up-a-VM).

Log in the VM, pick a temporary folder (e.g., `~/build`), and then do:

```
git clone https://github.com/droboports/crashplan.git
cd crashplan
./build.sh
ls -la *.tgz
```

Each invocation creates a log file with all the generated output.

* `./build.sh distclean` removes everything, including downloaded files.
* `./build.sh clean` removes everything but downloaded files.
* `./build.sh package` repackages the DroboApp, without recompiling.

## Sources

* crashplan: https://www.code42.com/crashplan/

## Acknowledgements

This DroboApp has been the result of much online searching. In particular, the patch for JTux was adapted from [here](https://crashplan.zendesk.com/entries/390250-crashplan-on-sheevaplug). The idea of using the Debian version of libjna comes from [here](http://www.opticality.com/blog/2011/07/16/installing-crashplan-on-a-pogoplug-pro/). Libffi as a requirement comes from [here](http://www.openstora.com/phpBB3/viewtopic.php?f=1&amp;t=904), as well as the idea of moving the tmp folder. Libmd5 was introduced in version 3.2, and the instructions came from [here](http://pcloadletter.co.uk/2012/01/30/crashplan-syno-package/).

## Help support the apps

If you like the repositories, or are using any of the apps from this site, please consider becoming a patreon or making a donation to support our efforts.

[![Patreon](http://www.patreon.com/images/logo_emblem.png)](http://www.patreon.com/Droboports)

[![Paypal](https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KYFBRYLKSGNKA)

[![Flattr](https://api.flattr.com/button/flattr-badge-large.png)](http://flattr.com/thing/177640/DroboPorts)

<sub>**Disclaimer**</sub>

<sub><sub>Drobo, DroboShare, Drobo FS, Drobo 5N, DRI and all related trademarks are the property of [Data Robotics, Inc](http://www.drobo.com/). This site is not affiliated, endorsed or supported by DRI in any way. The use of information and software provided on this website may be used at your own risk. The information and software available on this website are provided as-is without any warranty or guarantee. By visiting this website you agree that: (1) We take no liability under any circumstance or legal theory for any DroboApp, software, error, omissions, loss of data or damage of any kind related to your use or exposure to any information provided on this site; (2) All software are made “AS AVAILABLE” and “AS IS” without any warranty or guarantee. All express and implied warranties are disclaimed. Some states do not allow limitations of incidental or consequential damages or on how long an implied warranty lasts, so the above may not apply to you.</sub></sub>
