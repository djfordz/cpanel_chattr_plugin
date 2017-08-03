#!/bin/bash

set -e # Abort script at first error

cwd=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
install_plugin='/usr/local/cpanel/scripts/install_plugin'
dst='/usr/local/cpanel/base/frontend/paper_lantern/nemj_chattr'

if [ $EUID -ne 0 ]; then
    echo 'Script requires root privileges, run it as root or with sudo'
    exit 1
fi

if [ ! -f /usr/local/cpanel/version ]; then
    echo 'cPanel installation not found'
    exit 1
fi

if [ -d $dst ]; then
    echo "Existing installation found, try running the uninstall script first"
fi

mkdir -v $dst

cp -v ${cwd}/index.live.php $dst
cp -v ${cwd}/Chattr.php $dst
cp -v ${cwd}/chattr.css $dst

themes=('paper_lantern')

for theme in ${themes[@]}; do
    $install_plugin ${cwd}/plugins/${theme} --theme $theme
done

echo 'Installation finished without errors'
