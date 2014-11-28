# $1: file
# $2: url
# $3: folder
_download_deb() {
  [[ ! -f "download/${1}" ]] && wget -O "download/${1}" "${2}"
  [[ -d "target/${3}" ]] && rm -v -fr "target/${3}"
  [[ ! -d "target/${3}" ]] && mkdir -p "target/${3}" && dpkg -x "download/${1}" "target/${3}"
  return 0
}

### JRE INCLUDES ###
_build_jre() {
local VERSION="6b33-1.13.5-1"
local FOLDER="openjdk-6-jdk_${VERSION}"
local FILE="${FOLDER}_armel.deb"
local URL="http://ftp.ch.debian.org/debian/pool/main/o/openjdk-6/${FILE}"

_download_deb "${FILE}" "${URL}" "${FOLDER}"
}

### JTUX ###
_build_jtux() {
local FOLDER="jtux"
local FILE="${FOLDER}.tar.gz"
local URL="http://basepath.com/aup/jtux/${FILE}"
local JAVA_INCLUDE="${PWD}/target/openjdk-6-jdk_6b33-1.13.5-1/usr/lib/jvm/java-6-openjdk-armel/include"

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
local JAVA_INCLUDE="${PWD}/target/openjdk-6-jdk_6b33-1.13.5-1/usr/lib/jvm/java-6-openjdk-armel/include"

_download_zip "${FILE}" "${URL}" "${FOLDER}"
pushd "target/${FOLDER}"
mkdir -p "${DEST}/lib"
"${CC}" ${CFLAGS} -Os -shared -I. -I${JAVA_INCLUDE} src/lib/arch/linux_x86/MD5.c -o "${DEST}/lib/libmd5.so"
popd
}

### LIBFFI ###
_build_libffi() {
local VERSION="3.0.10"
local FOLDER="libffi-${VERSION}"
local FILE="${FOLDER}.tar.gz"
local URL="ftp://sourceware.org/pub/libffi/${FILE}"

_download_tgz "${FILE}" "${URL}" "${FOLDER}"
pushd "target/${FOLDER}"
./configure --host=arm-none-linux-gnueabi --prefix="${DEPS}" --libdir="${DEST}/lib" --disable-static
make
make install
mkdir -vp "${DEPS}/include/"
mv -v "${DEST}/lib/${FOLDER}/include"/* "${DEPS}/include/"
rm -v -fR "${DEST}/lib/${FOLDER}" "${DEST}/lib/pkgconfig"
popd
}

### LIBJNA ###
_build_libjna() {
local VERSION="3.2.7-4"
local FOLDER="libjna-java_${VERSION}"
local FILE="${FOLDER}_armel.deb"
local URL="http://ftp.ch.debian.org/debian/pool/main/libj/libjna-java/${FILE}"

_download_deb "${FILE}" "${URL}" "${FOLDER}"
mkdir -p "${DEST}/lib"
cp -v "target/${FOLDER}/usr/lib/jni/libjnidispatch.so" "${DEST}/lib/"
}

### CRASHPLAN ###
_build_crashplan() {
local VERSION="3.6.4"
local FOLDER="CrashPlan-install"
local FILE="CrashPlan_${VERSION}_Linux.tgz"
local URL="http://download.code42.com/installs/linux/install/CrashPlan/${FILE}"
local TARGET="${PWD}/target/${FOLDER}"

_download_tgz "${FILE}" "${URL}" "${FOLDER}"
mkdir -p "${DEST}/app"
pushd "${DEST}/app"
cat "${TARGET}/CrashPlan_${VERSION}.cpi" | gzip -dc - | cpio -i --no-preserve-owner
cp -v -f "${TARGET}/scripts/run.conf" bin/

echo "" > install.vars
echo "TARGETDIR=${DEST}/app" >> install.vars
echo "BINSDIR=${DEST}/app/bin" >> install.vars
echo "MANIFESTDIR=${DEST}/app/manifest" >> install.vars
echo "INITDIR=" >> install.vars
echo "RUNLVLDIR=" >> install.vars
echo "INSTALLDATE=`date +%Y%m%d`" >> install.vars
echo "JAVACOMMON=/mnt/DroboFS/Shares/DroboApps/java7/bin/java" >> install.vars
cat "${TARGET}/install.defaults" >> install.vars

sed -i -e "s/ps axw/ps w/" "${DEST}/app/bin/restartLinux.sh"
sed -i -e "3i <serviceLog>" \
    -e "3i <fileHandler append=\"true\" count=\"1\" level=\"ALL\" limit=\"1048576\" pattern=\"/tmp/DroboApps/crashplan/service.log\"/>" \
    -e "3i </serviceLog>" \
    -e "3i <historyLog>" \
    -e "3i <fileHandler append=\"true\" count=\"1\" level=\"ALL\" limit=\"1048576\" pattern=\"/tmp/DroboApps/crashplan/history.log\"/>" \
    -e "3i </historyLog>" \
    -e "10i <backupFilesLog>" \
    -e "10i <fileHandler append=\"true\" count=\"1\" level=\"ALL\" limit=\"1048576\" pattern=\"/tmp/DroboApps/crashplan/backup_files.log\"/>" \
    -e "10i </backupFilesLog>" \
    -e "10i <restoreFilesLog>" \
    -e "10i <fileHandler append=\"false\" count=\"1\" level=\"ALL\" limit=\"1048576\" pattern=\"/tmp/DroboApps/crashplan/restore_files.log\"/>" \
    -e "10i </restoreFilesLog>" \
    "${DEST}/app/conf/default.service.xml"

popd
}

### BUILD ###
_build() {
  _build_jre
  _build_jtux
  _build_libffi
  _build_fastmd5
  _build_libjna
  _build_crashplan
  _package
}
