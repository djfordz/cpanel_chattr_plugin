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

    public function fileattr($file) {
        $attrs = shell_exec("lsattr -a $file");
        if($a = strpos($attrs, 'i') == 4) {
            return true;
        }

        return false;
    }

    public function dirattr($dir) {
        $attrs = explode("\n", shell_exec("lsattr -a $dir"));
        foreach($attrs as $attr) {
            $path = strstr($attr, '/');
            $ndir = $dir . '/.' ;
            if(strcmp($path, $ndir) === 0) {
                if(strpos($attr, 'i') == 4) {
                    return $path; 
                }
            }
        }

        return false;
    }

    public function mutate($path) {
        if(is_dir($path)) {
            if($this->getAttr($path) == 16) {
                $this->disableAttrRecurse($path);
            } else {
                $this->enableAttrRecurse($path); 
            }
             
        } else {
            print_r($this->getAttr($path));
            if($this->getAttr($path) == 16) {
                $this->disableAttr($path); 
            } else {
                $this->enableAttr($path);
            }
            
        }
    }

    protected function enableAttrRecurse($path) {

        $result = $this->cpanel->uapi(
            'NemjChattr', 'enable_recurse',
            array(
                'path' => $path
            )
        );

        print_r($result['cpanelresult']['result']['data']);
    }

    protected function disableAttrRecurse($path) {

        $result = $this->cpanel->uapi(
            'NemjChattr', 'disable_recurse',
            array(
                'path' => $path
            )
        );

        print_r($result['cpanelresult']['result']['data']);
    }

    protected function getAttr($path) {

        $result = $this->cpanel->uapi(
            'NemjChattr', 'get',
            array(
                'path' => $path
            )
        );
        return $result['cpanelresult']['result']['data'];
    }
   
    protected function enableAttr($path) {
        $result = $this->cpanel->uapi(
            'NemjChattr', 'enable',
            array(
                'path' => $path
            )
        );

        print_r($result['cpanelresult']['result']['data']);
    }
    
    protected function disableAttr($path) {
        $result = $this->cpanel->uapi(
            'NemjChattr', 'disable',
            array(
                'path' => $path
            )
        );

        print_r($result['cpanelresult']['result']['data']);
    }
}
