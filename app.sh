# $1: file
# $2: url
# $3: folder
_download_deb() {
  [[ ! -d "download" ]]      && mkdir -p "download"
  [[ ! -d "target" ]]        && mkdir -p "target"
  [[ ! -f "download/${1}" ]] && wget -O "download/${1}" "${2}"
  [[   -d "target/${3}" ]]   && rm -v -fr "target/${3}"
  [[ ! -d "target/${3}" ]]   && mkdir -p "target/${3}" && dpkg -x "download/${1}" "target/${3}"
  return 0
}

JAVA_VERSION="8u66-b01-4"
JAVA_INCLUDE="${PWD}/target/openjdk-8-jdk_${JAVA_VERSION}/usr/lib/jvm/java-8-openjdk-armel/include"
JAVA_INCLUDE_LINUX="${JAVA_INCLUDE}/linux"

### JRE INCLUDES ###
_build_jre() {
local VERSION="${JAVA_VERSION}"
local FOLDER="openjdk-8-jdk_${VERSION}"
local FILE="${FOLDER}_armel.deb"
local URL="http://ftp.debian.org/debian/pool/main/o/openjdk-8/${FILE}"

_download_deb "${FILE}" "${URL}" "${FOLDER}"
}

### JTUX ###
_build_jtux() {
local FOLDER="jtux"
local FILE="${FOLDER}.tar.gz"
local URL="http://basepath.com/aup/jtux/${FILE}"

_download_tgz "${FILE}" "${URL}" "${FOLDER}"
cp src/Makefile "target/${FOLDER}/"
cp src/makefile.patch "target/${FOLDER}/"

pushd "target/${FOLDER}"
patch < makefile.patch
make JAVA_INCLUDE="${JAVA_INCLUDE}"
mkdir -p "${DEST}/lib"
cp -v libjtux.so "${DEST}/lib/"
popd
}

### FAST-MD5 ###
_build_fastmd5() {
local VERSION="2.7.1"
local FOLDER="fast-md5"
local FILE="${FOLDER}-${VERSION}.zip"
local URL="http://www.twmacinta.com/myjava/${FILE}"

_download_zip "${FILE}" "${URL}" "${FOLDER}"
pushd "target/${FOLDER}"
mkdir -p "${DEST}/lib"
"${CC}" ${CFLAGS} -shared -I. -I"${JAVA_INCLUDE}" -I"${JAVA_INCLUDE_LINUX}" src/lib/arch/linux_x86/MD5.c -o "${DEST}/lib/libmd5.so"
popd
}

### LIBFFI ###
_build_libffi() {
local VERSION="3.0.13"
local FOLDER="libffi-${VERSION}"
local FILE="${FOLDER}.tar.gz"
local URL="ftp://sourceware.org/pub/libffi/${FILE}"

_download_tgz "${FILE}" "${URL}" "${FOLDER}"
pushd "target/${FOLDER}"
./configure --host="${HOST}" --prefix="${DEPS}" --libdir="${DEST}/lib" --disable-static
make
make install
mkdir -vp "${DEPS}/include/"
mv -v "${DEST}/lib/${FOLDER}/include"/* "${DEPS}/include/"
rm -vfR "${DEST}/lib/${FOLDER}" "${DEST}/lib/pkgconfig"
ln -s "libffi.so.6.0.1" "${DEST}/lib/libffi.so.5"
popd
}

### CRASHPLAN ###
_build_crashplan() {
local VERSION="4.4.1"
local FOLDER="crashplan-install"
local FILE="CrashPlan_${VERSION}_Linux.tgz"
local URL="http://download.code42.com/installs/linux/install/CrashPlan/${FILE}"
local TARGET="${PWD}/target/${FOLDER}"
local DATE="$(date +%Y%m%d)"

_download_tgz "${FILE}" "${URL}" "${FOLDER}"
mkdir -p "${DEST}/app"
pushd "${DEST}/app"
cat "${TARGET}/CrashPlan_${VERSION}.cpi" | gzip -dc - | cpio -i --no-preserve-owner
cp -vf "${TARGET}/scripts/run.conf" "bin/run.conf.default"
sed -e "s/Xmx1024m/Xmx128m/g" -i "${DEST}/app/bin/run.conf.default"
sed -e "s|^ps axw|${DEST}/libexec/ps axw|" -i "${DEST}/app/bin/restartLinux.sh"
cat > install.vars << EOF
TARGETDIR=${DEST}/app
BINSDIR=${DEST}/app/bin
MANIFESTDIR=${DEST}/app/manifest
INITDIR=${DEST}
RUNLVLDIR=
INSTALLDATE=${DATE}
JAVACOMMON=/mnt/DroboFS/Shares/DroboApps/java8/bin/java
EOF
cat "${TARGET}/install.defaults" >> install.vars
popd
}

### LIBJNA ###
_build_libjna() {
rm -vf "${DEST}/app/lib/jna-platform.jar"
}

### BUILD ###
_build() {
  _build_jre
  _build_jtux
  _build_fastmd5
  _build_libffi
  _build_crashplan
  _build_libjna
  _package
}
