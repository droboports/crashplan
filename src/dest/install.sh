#!/usr/bin/env sh
#
# install script

prog_dir="$(dirname "$(realpath "${0}")")"
name="$(basename "${prog_dir}")"
data_dir="/mnt/DroboFS/System/${name}"
tmp_dir="/tmp/DroboApps/${name}"
logfile="${tmp_dir}/install.log"

# boilerplate
if [ ! -d "${tmp_dir}" ]; then mkdir -p "${tmp_dir}"; fi
exec 3>&1 4>&2 1>> "${logfile}" 2>&1
echo "$(date +"%Y-%m-%d %H-%M-%S"):" "${0}" "${@}"
set -o errexit  # exit on uncaught error code
set -o nounset  # exit on unset variable
set -o xtrace   # enable script tracing

# copy default configuration files
find "${prog_dir}" -type f -name "*.default" -print | while read deffile; do
  basefile="$(dirname "${deffile}")/$(basename "${deffile}" .default)"
  if [ ! -f "${basefile}" ]; then
    cp -vf "${deffile}" "${basefile}"
  fi
done

if [ ! -d "${data_dir}/backupArchives" ]; then
  mkdir -p "${data_dir}/backupArchives"
fi

if [ -d /var/lib/crashplan -a ! -h /var/lib/crashplan ]; then
  mv -f /var/lib/crashplan/.identity "${data_dir}"
  rmdir /var/lib/crashplan
fi
ln -fs "${data_dir}" /var/lib/

if [ -d "${prog_dir}/app/backupArchives" -a ! -h "${prog_dir}/app/backupArchives" ]; then
  mv "${prog_dir}/app/backupArchives/"* "${data_dir}/backupArchives/"
  rmdir "${prog_dir}/app/backupArchives"
fi
ln -fs "${data_dir}/backupArchives" "${prog_dir}/app/"

#echo -n "4243,drobo" > "${data_dir}/.ui_info"
