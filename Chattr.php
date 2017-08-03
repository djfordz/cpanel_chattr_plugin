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

    public function listFiles($path = 'false')
    {
	if($path == 'false') {
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
	
	foreach($files['cpanelresult']['result'] as $file) {
	    foreach($file as $v) {
		if ($v['type'] == 'file') {
		    $this->files[] = array('dir' => $v['absdir'], 'path' => $v['fullpath'], 'perms' => $v['nicemode']);
		} else if ($v['type'] == 'dir') {
		    $this->dir[] = array('dir' => $v['absdir'], 'path' => $v['fullpath'], 'perms' => $v['nicemode']);
		}

		if(!empty($v['fullpath']) && !empty($v['nicemode'])) {
	            $this->list[] = array('dir' => $v['absdir'], 'type' => $v['type'], 'path' => $v['fullpath'], 'perms' => $v['nicemode']);
		}

	        if(is_dir($v['fullpath'])) {
		    $this->listFiles($v['fullpath']);
	        } 
	    }
	}
	
	return $this->list;
    }

   public function listTopDirs() {
	$path = $this->userPath;

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

	foreach($files['cpanelresult']['result'] as $file) {
		foreach($file as $v) {
			$list[] = array('dir' => $v['absdir'], 'path' => $v['fullpath'], 'perms' => $v['nicemode']);
		}
	}
	return $list;
    }
}
