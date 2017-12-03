# Make Files Immutable

To install, copy release archive to installation server, extract and use `install.sh` script.

```
wget -O cpanel_chattr_plugin-1.0.0.tar.gz https://github.com/TransgressInc/cpanel_chattr_plugin/archive/1.0.0.tar.gz
tar -xvf cpanel_chattr_plugin-1.0.0.tar.gz && cd cpanel_chattr_plugin-1.0.0
chmod +x install.sh
./install.sh
```

or select [releases](https://github.com/TransgressInc/cpanel_chattr_plugin/releases) tab in github and download [release](https://github.com/TransgressInc/cpanel_chattr_plugin/releases)


A new icon called File Lock will appear in cpanel user list under security group.

To use, simply select directory or file you would like to make immutable, and select the checkbox, it will then make the entire directory or just the file immutable.

Selecting a directory will recursively mutate all files and sub directories.

It is just like using the command `chattr -R +i /path/` on the command line as root, except safer as root privileges are not needed for the cpanel user, only the function that is called is authorized to escalate privilege to run that command only.

Please submit issue if you find a bug.
