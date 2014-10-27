#!/usr/bin/env sh
#
# CrashPlan service

# import DroboApps framework functions
. /etc/service.subr

# DroboApp framework version
framework_version="2.0"

# app description
name="crashplan"
version="3.6.4"
description="Cloud backup service"
depends="locale java7"

# framework-mandated variables
pidfile="/tmp/DroboApps/${name}/pid.txt"
logfile="/tmp/DroboApps/${name}/log.txt"
statusfile="/tmp/DroboApps/${name}/status.txt"
errorfile="/tmp/DroboApps/${name}/error.txt"

# app-specific variables
prog_dir=$(dirname $(readlink -fn ${0}))
tmpdir="${prog_dir}/tmp"
locale="/mnt/DroboFS/Shares/DroboApps/locale/bin/locale"
localedef="/mnt/DroboFS/Shares/DroboApps/locale/bin/localedef"
java="/mnt/DroboFS/Shares/DroboApps/java7/bin/java"
classpath="${prog_dir}/app/lib/com.backup42.desktop.jar:${prog_dir}/app/lang"
mainclass="com.backup42.service.CPService"

# script hardening
set -o errexit  # exit on uncaught error code
set -o nounset  # exit on unset variable
#set -o pipefail # propagate last error code on pipe

# ensure log folder exists
grep -q ^tmpfs /proc/mounts || mount -t tmpfs tmpfs /tmp
logfolder="$(dirname ${logfile})"
[[ ! -d "${logfolder}" ]] && mkdir -p "${logfolder}"

# redirect all output to logfile
exec 3>&1 1>> "${logfile}" 2>&1

# log current date, time, and invocation parameters
echo $(date +"%Y-%m-%d %H-%M-%S"): ${0} ${@}

# enable script tracing
set -o xtrace

_set_watch_files() {
  echo 1048576 > /proc/sys/fs/inotify/max_user_watches
  return 0
}

_create_locale() {
  if [ ! -x "${locale}" -o ! -x "${localedef}" ]; then
    echo "Locale support missing. Please install the locale DroboApp." >&3
    exit 1
  fi
  "${locale}" -a | grep -q "^en_US.utf8"
  [ $? -ne 0 ] && "${localedef}" -f UTF-8 -i en_US en_US.UTF-8
  return 0
}

start() {
  local pid
  _set_watch_files
  _create_locale
  # source for SRV_JAVA_OPTS
  . "${prog_dir}/app/bin/run.conf"
  SRV_JAVA_OPTS="${SRV_JAVA_OPTS} -Djava.io.tmpdir=$tmpdir"
  export LC_ALL="en_US.UTF-8"
  export LANG="en_US.UTF-8"
  export LD_LIBRARY_PATH="${prog_dir}/lib:${prog_dir}/app:$LD_LIBRARY_PATH"
  cd "${prog_dir}/app"
  setsid ${java} ${SRV_JAVA_OPTS} -classpath "${classpath}" "${mainclass}"&
  if [ $! -gt 0 ]; then
    pid=$!
    echo $pid > ${pidfile}
    renice 19 ${pid}
  fi
}

_service_start() {
  set +e
  set +u
  start_service
  set -u
  set -e
}

_service_stop() {
  /sbin/start-stop-daemon -K -x "${java}" -p "${pidfile}" -v || echo "${name} is not running" >&3
}

_service_restart() {
  service_stop
  sleep 3
  service_start
}

_service_status() {
  status >&3
}

_service_help() {
  echo "Usage: $0 [start|stop|restart|status]" >&3
  set +e
  exit 1
}

case "${1:-}" in
  start|stop|restart|status) _service_${1} ;;
  *) _service_help ;;
esac
