<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '/usr/local/cpanel/php/cpanel.php';
require_once 'Chattr.php';
require_once 'chattr.css';

$cpanel = new CPANEL();

$chattr = new Chattr($cpanel);

print $cpanel->header("File Lock", "nemj-chattr");

$get_userdata = $cpanel->uapi(
    'DomainInfo', 'domains_data',
    array(
        'format' => 'hash',
    )
);
$dirs = $chattr->listDirs();
$files = $chattr->listFiles();

if (isset($_GET['path'])) {
    $path = $_GET['path'];
    $dirs = $chattr->listDirs($path);
    $files = $chattr->listFiles($path);
} 

?>

<div id="desc">
    <p>Make files immutable.</p>
</div>
<div id="nemj-wrapper">
    <div id="nemj-list">
	<div id="title">
        <h4>Path</h4><h4>Perms</h4>
	</div>
        <ul id="list">
            <?php 
                 
                $i = 0; 
                foreach ($dirs as $dir) { $i++; ?> 
                    <form>
                    <li id="<?php echo 'li-' . $i; ?>">
                        <input class="dir expand" type="submit" value="+" name="expand" />
                        <input class="dir path" type="text" value="<?php  echo $dir['path']; ?>" name="path" readonly />
                        <input class="dir perms" type="text" value="<?php  echo $dir['perms']; ?>" name="perms" readonly />
                        <input class="check" type="checkbox" name="mutate" />
                    </li>
                    </form>
            <?php 
                } 

                
                $i = 0;
                foreach($files as $file) { $i++; ?>
                     <li id="<?php echo 'li-' . $i; ?>">
                        <input class="file path" type="text" value="<?php  echo $file['path']; ?>" name="path" readonly />
                        <input class="file perms" type="text" value="<?php  echo $file['perms']; ?>" name="perms" readonly />
                        <input class="check" type="checkbox" name="mutate" />
                    </li>
            <?php } ?>
        </ul>
    </div>
</div>

<?php
print $cpanel->footer();
$cpanel->end();
?>

