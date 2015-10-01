#!/usr/bin/env sh
#
# update script

prog_dir="$(dirname "$(realpath "${0}")")"
name="$(basename "${prog_dir}")"
tmp_dir="/tmp/DroboApps/${name}"
logfile="${tmp_dir}/update.log"

# boilerplate
if ! grep -q ^tmpfs /proc/mounts; then mount -t tmpfs tmpfs /tmp; fi
if [ ! -d "${tmp_dir}" ]; then mkdir -p "${tmp_dir}"; fi
exec 3>&1 4>&2 1>> "${logfile}" 2>&1
echo "$(date +"%Y-%m-%d %H-%M-%S"):" "${0}" "${@}"
set -o errexit  # exit on uncaught error code
set -o nounset  # exit on unset variable
set -o xtrace   # enable script tracing

/bin/sh "${prog_dir}/service.sh" stop

if [ -f "${prog_dir}/app/conf/my.service.xml" ]; then
  if grep -q "127\.0\.0\.1" "${prog_dir}/app/conf/my.service.xml" 2> /dev/null; then
    sed -e "s/127.0.0.1/0.0.0.0/g" -i "${prog_dir}/app/conf/my.service.xml"
  fi
fi
