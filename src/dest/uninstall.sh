#!/usr/bin/env sh
#
# uninstall script

prog_dir="$(dirname "$(realpath "${0}")")"
name="$(basename "${prog_dir}")"
data_dir="/mnt/DroboFS/System/${name}"
tmp_dir="/tmp/DroboApps/${name}"
logfile="${tmp_dir}/install.log"

# boilerplate
if ! grep -q ^tmpfs /proc/mounts; then mount -t tmpfs tmpfs /tmp; fi
if [ ! -d "${tmp_dir}" ]; then mkdir -p "${tmp_dir}"; fi
exec 3>&1 4>&2 1>> "${logfile}" 2>&1
echo "$(date +"%Y-%m-%d %H-%M-%S"):" "${0}" "${@}"
set -o errexit  # exit on uncaught error code
set -o nounset  # exit on unset variable
set -o xtrace   # enable script tracing

if [ -e /var/lib/crashplan ]; then
  rm -f /var/lib/crashplan
fi
if [ -e /mnt/DroboFS/.crashplan/.ui_info ]; then
  rm -f /mnt/DroboFS/.crashplan/.ui_info
fi
if [ -d /mnt/DroboFS/.crashplan ]; then
  rmdir /mnt/DroboFS/.crashplan
fi
