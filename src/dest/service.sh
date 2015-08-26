#!/usr/bin/env sh
#
# CrashPlan service

# import DroboApps framework functions
. /etc/service.subr

framework_version="2.1"
name="crashplan"
version="4.3.0-1"
description="Online Data Backup - Offsite, Onsite, And Cloud."
depends="java8 locale"
webui="WebUI"

prog_dir="$(dirname "$(realpath "${0}")")"
java_tmp_dir="${prog_dir}/tmp"
data_dir="/mnt/DroboFS/System/${name}"
tmp_dir="/tmp/DroboApps/${name}"
pidfile="${tmp_dir}/pid.txt"
logfile="${tmp_dir}/log.txt"
statusfile="${tmp_dir}/status.txt"
errorfile="${tmp_dir}/error.txt"
uifile="/mnt/DroboFS/System/crashplan/.ui_info"
locale="${DROBOAPPS_DIR}/locale/bin/locale"
localedef="${DROBOAPPS_DIR}/locale/bin/localedef"
daemon="${DROBOAPPS_DIR}/java8/bin/java"
classpath="${prog_dir}/app/lib/com.backup42.desktop.jar:${prog_dir}/app/lang"
mainclass="com.backup42.service.CPService"

# backwards compatibility
if [ -z "${FRAMEWORK_VERSION:-}" ]; then
  framework_version="2.0"
  . "${prog_dir}/libexec/service.subr"
fi

start() {
  rm -f "${statusfile}" "${errorfile}"

  if [ ! -f "${locale}" ]; then
    echo "Locale is not installed, please install and start crashplan again." > "${statusfile}"
    echo "1" > "${errorfile}"
    return 1
  fi

  if [ ! -f "${daemon}" ]; then
    echo "Java8 is not installed, please install and start crashplan again." > "${statusfile}"
    echo "1" > "${errorfile}"
    return 2
  fi

  if [ ! -f "${prog_dir}/app/lib/com.backup42.desktop.jar" ]; then
    echo "Crashplan update failed, please reinstall crashplan." > "${statusfile}"
    echo "1" > "${errorfile}"
    return 3
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
