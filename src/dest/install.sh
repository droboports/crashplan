#!/usr/bin/env sh
#
# install script

prog_dir="$(dirname "$(realpath "${0}")")"
name="$(basename "${prog_dir}")"
old_data_dir="/mnt/DroboFS/System/${name}"
data_dir="/mnt/DroboFS/Shares/DroboApps/.AppData/${name}"
tmp_dir="/tmp/DroboApps/${name}"
logfile="${tmp_dir}/install.log"
statusfile="${tmp_dir}/status.txt"
errorfile="${tmp_dir}/error.txt"

# boilerplate
if [ ! -d "${tmp_dir}" ]; then mkdir -p "${tmp_dir}"; fi
exec 3>&1 4>&2 1>> "${logfile}" 2>&1
echo "$(date +"%Y-%m-%d %H-%M-%S"):" "${0}" "${@}"
set -o errexit  # exit on uncaught error code
set -o nounset  # exit on unset variable
set -o xtrace   # enable script tracing

# check firmware version
if ! /usr/bin/DroboApps.sh sdk_version &> /dev/null; then
  echo "Unsupported Drobo firmware, please upgrade to the latest version." > "${statusfile}"
  echo "4" > "${errorfile}"
  exit 1
fi

# install apache 2.x
/usr/bin/DroboApps.sh install_version apache 2

# copy default configuration files
find "${prog_dir}" -type f -name "*.default" -print | while read deffile; do
  basefile="$(dirname "${deffile}")/$(basename "${deffile}" .default)"
  if [ ! -f "${basefile}" ]; then
    cp -vf "${deffile}" "${basefile}"
  fi
done

# $1: source
# $2: destination
_migrate_dir() {
  local basedir
  local dstdir
  local basefile
  local dstfile

  if [ -z "$1" ] || [ -z "$2" ]; then
    return 1
  fi

  if [ ! -d "$2" ]; then
    mkdir -p "$2"
  fi

  if [ -d "$1" ] && [ ! -h "$1" ]; then
    find "$1" -mindepth 1 -maxdepth 1 -type d -print | while read srcdir; do
      basedir="$(basename "${srcdir}")"
      dstdir="$2/${basedir}"
      if [ -d "${dstdir}" ]; then
        _migrate_dir "${srcdir}" "${dstdir}"
      else
        mv "${srcdir}" "${dstdir}"
      fi
    done
    find "$1" -mindepth 1 -maxdepth 1 -type f -print | while read srcfile; do
      basefile="$(basename "${srcfile}")"
      dstfile="$2/${basefile}"
      if [ -f "${dstfile}" ]; then
        dstfile="${dstfile}-$(stat -c %Z "${srcfile}")"
      fi
      mv "${srcfile}" "${dstfile}"
    done
    rmdir "$1"
  fi
}

# migrate data folder to /mnt/DroboFS/Shares/DroboApps/.AppData/${name}
_migrate_dir /var/lib/crashplan "${data_dir}"
_migrate_dir "${prog_dir}/app/conf" "${data_dir}/conf"
_migrate_dir "${prog_dir}/app/backupArchives" "${data_dir}/backupArchives"
_migrate_dir "${old_data_dir}" "${data_dir}"

ln -fs "${data_dir}" /var/lib/
ln -fs "${data_dir}/conf" "${prog_dir}/app/"
ln -fs "${data_dir}/backupArchives" "${prog_dir}/app/"

# migrate logs to /tmp/DroboApps/${name}
rm -fR "${prog_dir}/app/log"
ln -fs "${tmp_dir}" "${prog_dir}/app/log"
