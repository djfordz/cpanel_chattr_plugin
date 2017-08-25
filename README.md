# Make Files Immutable

To install, copy release archive to installation server.
`wget -O nemj_chattr-1.0.0.tar.gz https://github.com/djfordz/cpanel_chattr_plugin/archive/1.0.0.tar.gz`

or select releases tab in github and download release.
https://github.com/djfordz/cpanel_chattr_plugin/releases

untar the archive
`tar -xvf nemj_chattr-1.0.0.tar.gz`

cd into nemj_chattr directory
`cd cpanel_chattr_plugin-1.0.0`

make install script executable 
`chmod +x install.sh`

run install script
`./install.sh`

A new icon called File Lock will appear in cpanel user list under security group.

To use, simply select directory or file you would like to make immutable, and select the checkbox, it will then make the entire directory or just the file immutable.

Selecting a directory will recursively mutate all files and sub directories.

It is just like using the command `chattr -R +i /path/` on the command line as root, except safer as root privileges are not needed for the cpanel user, only the function that is called is authorized to escalate privilege to run that command only.

Please submit issue if you find a bug.
