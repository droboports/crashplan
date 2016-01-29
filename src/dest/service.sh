#!/usr/bin/env sh
#
# CrashPlan service

# import DroboApps framework functions
. /etc/service.subr

framework_version="2.1"
name="crashplan"
version="4.5.2"
description="Online Data Backup - Offsite, Onsite, And Cloud."
depends="java8 locale"
webui="WebUI"

prog_dir="$(dirname "$(realpath "${0}")")"
java_tmp_dir="${prog_dir}/tmp"
data_dir="/mnt/DroboFS/Shares/DroboApps/.AppData/${name}"
tmp_dir="/tmp/DroboApps/${name}"
pidfile="${tmp_dir}/pid.txt"
logfile="${tmp_dir}/log.txt"
statusfile="${tmp_dir}/status.txt"
errorfile="${tmp_dir}/error.txt"
uifile="${data_dir}/.ui_info"
locale="${DROBOAPPS_DIR}/locale/bin/locale"
localedef="${DROBOAPPS_DIR}/locale/bin/localedef"
daemon="${DROBOAPPS_DIR}/java8/bin/java"
classpath="${prog_dir}/app/lib/com.backup42.desktop.jar:${prog_dir}/app/lang"
mainclass="com.backup42.service.CPService"

# check firmware version
_firmware_check() {
  local rc
  local semver
  rm -f "${statusfile}" "${errorfile}"
  if [ -z "${FRAMEWORK_VERSION:-}" ]; then
    echo "Unsupported Drobo firmware, please upgrade to the latest version." > "${statusfile}"
    echo "4" > "${errorfile}"
    return 1
  fi
  semver="$(/usr/bin/semver.sh "${framework_version}" "${FRAMEWORK_VERSION}")"
  if [ "${semver}" == "1" ]; then
    echo "Unsupported Drobo firmware, please upgrade to the latest version." > "${statusfile}"
    echo "4" > "${errorfile}"
    return 1
  fi
  return 0
}

# perform crashplan upgrades
_perform_upgrades() {
  find "${prog_dir}/app/upgrade" -mindepth 1 -maxdepth 1 -type d ! -name UpgradeUI -print | sort | while read updir; do
    if [ -f "${updir}/upgrade.sh" ]; then
      sed -e 's|RM=.*|RM="rm -f"|g' \
          -e 's|MV=.*|MV="mv -f"|g' \
          -e "s|INIT_SCRIPT=.*|INIT_SCRIPT=\"${prog_dir}/service.sh\"|g" \
          -e "s|/bin/ps|${prog_dir}/libexec/ps|g" \
          -e "s|^function ||g" \
          -i "${updir}/upgrade.sh"
      mv -f "${updir}/upgrade.sh" "${updir}/upgrade.sh.started"
      cd "${updir}"
      if /bin/sh ./upgrade.sh.started; then
        mv -f "${updir}/upgrade.sh.started" "${updir}/upgrade.sh.done"
      fi
    fi
  done
}

start() {
  _firmware_check

  if [ ! -f "${prog_dir}/app/lib/com.backup42.desktop.jar" ]; then
    _perform_upgrades
  fi

  echo 1048576 > /proc/sys/fs/inotify/max_user_watches
  if [ ! -d "${java_tmp_dir}" ]; then
    mkdir -p "${java_tmp_dir}"
  fi
  chmod 777 "${java_tmp_dir}"

  if ! ("${locale}" -a | grep -q ^en_US.utf8); then
    "${localedef}" -f UTF-8 -i en_US en_US.UTF-8
  fi

  export LC_ALL="en_US.UTF-8"
  export LANG="en_US.UTF-8"
  export LD_LIBRARY_PATH="${prog_dir}/lib:${prog_dir}/app:${LD_LIBRARY_PATH:-}"
  export HOME="${data_dir}"
  . "${prog_dir}/app/bin/run.conf"
  SRV_JAVA_OPTS="${SRV_JAVA_OPTS:-} -Duser.home=${data_dir} -Djava.io.tmpdir=${java_tmp_dir} -Djava.library.path=${prog_dir}/lib -Djava.net.preferIPv4Stack=true"
  cd "${prog_dir}/app"
  setsid "${daemon}" ${SRV_JAVA_OPTS} -classpath "${classpath}" "${mainclass}" &
  if [ $! -gt 0 ]; then
    local pid=$!
    echo "${pid}" > "${pidfile}"
    renice 19 "${pid}"
    ( sleep 10; echo "Crashplan is ready; click Configure to get connection details" > "${statusfile}" ) &
  fi
}

# boilerplate
if [ ! -d "${tmp_dir}" ]; then mkdir -p "${tmp_dir}"; fi
exec 3>&1 4>&2 1>> "${logfile}" 2>&1
STDOUT=">&3"
STDERR=">&4"
echo "$(date +"%Y-%m-%d %H-%M-%S"):" "${0}" "${@}"
set -o errexit  # exit on uncaught error code
set -o nounset  # exit on unset variable
set -o xtrace   # enable script tracing

main "${@}"
