#!/bin/bash

set -e # Abort script at first error

cwd=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
uninstall_plugin='/usr/local/cpanel/scripts/uninstall_plugin'
dst='/usr/local/cpanel/base/frontend/paper_lantern/nemj_chattr'
api='/usr/local/cpanel/Cpanel/API'
adm='/usr/local/cpanel/bin/admin/Nemanja'

if [ $EUID -ne 0 ]; then
	echo 'Script requires root privileges, run it as root or with sudo'
	exit 1
fi

if [ ! -f /usr/local/cpanel/version ]; then
	echo 'cPanel installation not found'
	exit 1
fi

if [ ! -x $uninstall_plugin ]; then
	echo 'cPanel version 11.50 or later required'
	exit 1
fi

themes=('paper_lantern')

for theme in ${themes[@]}; do
	$uninstall_plugin ${cwd}/plugins/${theme} --theme $theme
done

if [ -d $dst ]; then
	rm -rfv $dst
fi

if [ -f ${adm}/Chattr ]; then
    rm -fv ${adm}/Chattr
    rm -fv ${adm}/Chattr.conf
fi

if [ -z "$(ls -A ${adm})" ]; then
    rm -rfv $adm
fi

if [ -f "${api}/NemjChattr.pm" ]; then
    rm -fv ${api}/NemjChattr.pm
fi

echo 'Uninstall finished without errors'

