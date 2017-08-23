# Make Files Immutable

To install, copy archive to installation server.
`wget https://raw.github.com/djfordz/cpanel_chattr_plugin/master/nemj_chattr.tar.gz`

untar the archive
`tar -xvf nemj_chattr.tar.gz`

cd into nemj_chattr directory
`cd nemj_chattr`

make install script executable 
`chmod +x install.sh`

run install script
`./install.sh`

A new icon called File Lock will appear in cpanel user list under security group.

To use, simply select directory or file you would like to make immutable, and select the checkbox, it will then make the entire directory or just the file immutable.

Selecting a directory will recursively mutate all files and sub directories.

It is just like using the command `chattr -R +i /path/` on the command line as root, except safer as root privileges are not needed for the cpanel user, only the function that is called is authorized to escalate privilege to run that command only.

Please submit issue if you find a bug.
