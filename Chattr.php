<?php

class Chattr
{
    protected $cpanel;

    var $userPath;

    protected $list = array();
    protected $files = array();
    protected $dir = array();

    public function __construct($cpanel)
    {
        $this->cpanel = $cpanel;
        $processUser = posix_getpwuid(posix_geteuid());
        $user = $processUser['name'];
        $this->userPath = "/home/$user";
    }

    public function listAll($path = false)
    {
        if($path === false) {
            $path = $this->userPath;
        }

            $files = $this->cpanel->uapi(
                'Fileman', 'list_files',
                array(
                    'dir'                           => $path,
                    'types'                         => 'dir|file',
                    'limit_to_list'                 => '0',
                    'show_hidden'                   => '1',
                    'check_for_leaf_directories'    => '1',
                    'include_mime'                  => '0',
                    'include_hash'                  => '0',
                    'include_permissions'           => '1'
                )
            );

        $list = array();
        
        foreach($files['cpanelresult']['result']['data'] as $file) {
            if(isset($file) && is_array($file)) {
                $this->list[] = array(
                    'dir'   => $file['absdir'], 
                    'type'  => $file['type'], 
                    'path'  => $file['fullpath'], 
                    'perms' => $file['nicemode']
                );

                if(is_dir($file['fullpath'])) {
                    $this->listFiles($file['fullpath']);
                } 
            }
        }
        
        return $this->list;
    }

    public function listDirs($dir = false)
    {
        if($dir == false) {
            $dir = $this->userPath;
        }

            $dirs = $this->cpanel->uapi(
                'Fileman', 'list_files',
                array(
                    'dir'                           => $dir,
                    'types'                         => 'dir',
                    'limit_to_list'                 => '0',
                    'show_hidden'                   => '1',
                    'check_for_leaf_directories'    => '1',
                    'include_mime'                  => '0',
                    'include_hash'                  => '0',
                    'include_permissions'           => '1'
                )
            );

        $list = array();
        
        foreach($dirs['cpanelresult']['result']['data'] as $d) {
            if(isset($d) && is_array($d)) {
                $list[] = array(
                    'dir'   => $d['absdir'], 
                    'type'  => $d['type'], 
                    'path'  => $d['fullpath'], 
                    'perms' => $d['nicemode']
                );
            }
        }
        
        return $list;
    }

    public function listFiles($dir = false)
    {
        if($dir == false) {
            $dir = $this->userPath;
        }

            $files = $this->cpanel->uapi(
                'Fileman', 'list_files',
                array(
                    'dir'                           => $dir,
                    'types'                         => 'file',
                    'limit_to_list'                 => '0',
                    'show_hidden'                   => '1',
                    'check_for_leaf_directories'    => '1',
                    'include_mime'                  => '0',
                    'include_hash'                  => '0',
                    'include_permissions'           => '1'
                )
            );

        $list = array();
        
        foreach($files['cpanelresult']['result']['data'] as $file) {
            if(isset($file) && is_array($file)) {
                $list[] = array(
                    'dir'   => $file['absdir'], 
                    'type'  => $file['type'], 
                    'path'  => $file['fullpath'], 
                    'perms' => $file['nicemode']
                );
            }
        }
        
        return $list;
    }

    protected function lsAttr($path) {

        $result = $this->cpanel->uapi(
            'NemjChattr', 'get',
            array(
                'path' => $path
            )
        );
        return $result['cpanelresult']['result']['data'];
    }
   
}
