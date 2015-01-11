#!/usr/bin/env sh
#
# CrashPlan service

# import DroboApps framework functions
source /etc/service.subr

### app-specific section

# DroboApp framework version
framework_version="2.0"

# app description
name="crashplan"
version="3.7.0"
description="Cloud backup service"
depends="locale"

# framework-mandated variables
pidfile="/tmp/DroboApps/${name}/pid.txt"
logfile="/tmp/DroboApps/${name}/log.txt"
statusfile="/tmp/DroboApps/${name}/status.txt"
errorfile="/tmp/DroboApps/${name}/error.txt"

# app-specific variables
prog_dir="$(dirname $(readlink -fn ${0}))"
tmpdir="${prog_dir}/tmp"
locale=""
localedef=""
daemon=""
classpath="${prog_dir}/app/lib/com.backup42.desktop.jar:${prog_dir}/app/lang"
mainclass="com.backup42.service.CPService"

# Check Locale presence
_find_locale() {
  if [[ -d "${DROBOAPPS_DIR}/locale" ]]; then
    locale="${DROBOAPPS_DIR}/locale/bin/locale"
    localedef="${DROBOAPPS_DIR}/locale/bin/localedef"
  else
    echo "Cannot find Locale support. Please install the Locale app." > "${statusfile}"
    echo 3 > "${errorfile}"
    exit 1
  fi
}

_create_locale() {
  if [[ -z "$(${locale} -a | grep ^en_US.utf8)" ]]; then "${localedef}" -f UTF-8 -i en_US en_US.UTF-8; fi
}

_set_watch_files() {
  echo 1048576 > /proc/sys/fs/inotify/max_user_watches
}

# Check Java presence
_find_java() {
  if [[ -x "${DROBOAPPS_DIR}/java8/bin/java" ]]; then
    daemon="${DROBOAPPS_DIR}/java8/bin/java"
  elif [[ -x "${DROBOAPPS_DIR}/java7/bin/java" ]]; then
    daemon="${DROBOAPPS_DIR}/java7/bin/java"
  elif [[ -x "${DROBOAPPS_DIR}/java6/bin/java" ]]; then
    daemon="${DROBOAPPS_DIR}/java6/bin/java"
  elif [[ -x "${DROBOAPPS_DIR}/java/bin/java" ]]; then
    daemon="${DROBOAPPS_DIR}/java/bin/java"
  else
    echo "Cannot find Java. Please install a Java VM." > "${statusfile}"
    echo 3 > "${errorfile}"
    exit 1
  fi
}

start() {
  set -u # exit on unset variable
  set -e # exit on uncaught error code
  set -x # enable script trace
  rm -f "${errorfile}"
  rm -f "${statusfile}"
  _find_locale
  _create_locale
  _set_watch_files
  _find_java
  # source for SRV_JAVA_OPTS
  source "${prog_dir}/app/bin/run.conf"
  SRV_JAVA_OPTS="${SRV_JAVA_OPTS} -Djava.io.tmpdir=$tmpdir"
  export LC_ALL="en_US.UTF-8"
  export LANG="en_US.UTF-8"
  export LD_LIBRARY_PATH="${prog_dir}/lib:${prog_dir}/app:${LD_LIBRARY_PATH:-}"
  cd "${prog_dir}/app"
  setsid "${daemon}" ${SRV_JAVA_OPTS} -classpath "${classpath}" "${mainclass}" &
  if [ $! -gt 0 ]; then
    local pid=$!
    echo $pid > ${pidfile}
    renice 19 ${pid}
  fi
}

### common section

# script hardening
set -o errexit  # exit on uncaught error code
set -o nounset  # exit on unset variable

# ensure log folder exists
if ! grep -q ^tmpfs /proc/mounts; then mount -t tmpfs tmpfs /tmp; fi
logfolder="$(dirname ${logfile})"
[[ ! -d "${logfolder}" ]] && mkdir -p "${logfolder}"

# redirect all output to logfile
exec 3>&1 1>> "${logfile}" 2>&1

# log current date, time, and invocation parameters
echo $(date +"%Y-%m-%d %H-%M-%S"): ${0} ${@}

# _is_running
# args: path to pid file
# returns: 0 if pid is running, 1 if not running or if pidfile does not exist.
_is_running() {
  /sbin/start-stop-daemon -K -s 0 -x "${daemon}" -p "${pidfile}" -q
}

_service_start() {
  if _is_running "${pidfile}"; then
    echo ${name} is already running >&3
    set +e
    return 1
  fi
  set +x # disable script trace
  set +e # disable error code check
  set +u # disable unset variable check
  start_service
}

_service_stop() {
  if ! /sbin/start-stop-daemon -K -x "${daemon}" -p "${pidfile}" -v; then echo "${name} is not running" >&3; fi
}

_service_restart() {
  _service_stop
  sleep 3
  _service_start
}

_service_status() {
  status >&3
}

_service_help() {
  echo "Usage: $0 [start|stop|restart|status]" >&3
  set +e
  exit 1
}

# enable script tracing
set -o xtrace

case "${1:-}" in
  start|stop|restart|status) _service_${1} ;;
  *) _service_help ;;
esac
