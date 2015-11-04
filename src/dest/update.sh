#!/usr/bin/env sh
#
# update script

prog_dir="$(dirname "$(realpath "${0}")")"
name="$(basename "${prog_dir}")"
old_data_dir="/mnt/DroboFS/System/${name}"
data_dir="/mnt/DroboFS/Shares/DroboApps/.AppData/${name}"
tmp_dir="/tmp/DroboApps/${name}"
logfile="${tmp_dir}/update.log"

# boilerplate
if [ ! -d "${tmp_dir}" ]; then mkdir -p "${tmp_dir}"; fi
exec 3>&1 4>&2 1>> "${logfile}" 2>&1
echo "$(date +"%Y-%m-%d %H-%M-%S"):" "${0}" "${@}"
set -o errexit  # exit on uncaught error code
set -o nounset  # exit on unset variable
set -o xtrace   # enable script tracing

/bin/sh "${prog_dir}/service.sh" stop || true

# upgrade configurations
if [ -f "${prog_dir}/app/conf/my.service.xml" ]; then
  if grep -q "127\.0\.0\.1" "${prog_dir}/app/conf/my.service.xml" 2> /dev/null; then
    sed -e "s/127.0.0.1/0.0.0.0/g" -i "${prog_dir}/app/conf/my.service.xml"
  fi
  if grep -q "${old_data_dir}" "${prog_dir}/app/conf/my.service.xml" 2> /dev/null; then
    sed -e "s|${old_data_dir}|${data_dir}|g" -i "${prog_dir}/app/conf/my.service.xml"
  fi
fi

# remove old upgrades
find "${prog_dir}/app/upgrade" -mindepth 1 -maxdepth 1 -type d ! -name UpgradeUI -print | while read updir; do
  if [ -f "${updir}/upgrade.sh" ]; then
    rm -fr "${updir}"
  fi
done
