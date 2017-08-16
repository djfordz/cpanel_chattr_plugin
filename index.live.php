<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once '/usr/local/cpanel/php/cpanel.php';
require_once 'Chattr.php';
require_once 'chattr.css';
require_once 'chattr.js';

$cpanel = new CPANEL();

$chattr = new Chattr($cpanel);

print $cpanel->header("File Lock", "nemj-chattr");

$dirs = $chattr->listDirs();
$files = $chattr->listFiles();

if (isset($_GET['path']) && $_GET['mutate'] == 'false') {
    $path = $_GET['path'];
    $dirs = $chattr->listDirs(strip_tags(trim($path)));
    $files = $chattr->listFiles(strip_tags(trim($path)));

    
} else if(isset($_GET['path']) && $_GET['mutate'] == 'true') {
    $chattr->mutate(strip_tags(trim($_GET['path'])));
}

?>

<div id="nemj-wrapper">
    <div id="desc">
        <p>Make files immutable.</p>
    </div>

    <div id="nemj-list">
        <div id="title">
        <h4><?php echo $chattr->userPath; ?></h4><h4>Perms</h4><h4>Immutable</h4>
        </div>
        <ul id="list">
            <?php 
                $i = 0; 
                foreach ($dirs as $dir) { $i++; ?> 
                    <li id="<?php echo 'li-' . $i; ?>">
                        <input class="dir expand" type="submit" value="+" name="expand" />
                        <input class="dir path" type="text" value="<?php  echo $dir['path']; ?>" name="path" readonly />
                        <input class="dir perms" type="text" value="<?php  echo $dir['perms']; ?>" name="perms" readonly />
                        <?php if($chattr->dirattr($dir['path'])) { ?>
                            <input class="check" type="checkbox" name="mutate" checked />
                        <?php } else { ?>
                            <input class="check" type="checkbox" name="mutate" />     
                        <?php } ?>
                    </li>
            <?php 
                } 

                
                $i = 0;
                foreach($files as $file) { $i++; ?>
                    
                     <li id="<?php echo 'li-' . $i; ?>">
                        <input class="file path" type="text" value="<?php  echo $file['path']; ?>" name="path" readonly />
                        <input class="file perms" type="text" value="<?php  echo $file['perms']; ?>" name="perms" readonly />
                        <?php if($chattr->fileattr($file['path'])) { ?>
                            <input class="check" type="checkbox" name="mutate" checked />
                        <?php } else { ?>
                            <input class="check" type="checkbox" name="mutate" />     
                        <?php } ?>
                    </li>
            <?php } ?>
        </ul>
    </div>
</div>

<script>
var url = window.location.pathname;
var element = document.querySelectorAll('li');
var i = 0;
dirs = <?php echo json_encode($dirs); ?>;
files = <?php echo json_encode($files); ?>;

element.forEach(function(el) {
    if (el.id.includes('li-')) {
        el.childNodes[1].addEventListener('click', function(event) {
            dirs.forEach(function(dir) {
                listDir(el, dir);
            });
        });
        if (el.childNodes.length === 9) {
            el.childNodes[7].addEventListener('click', function(event) {
                dirs.forEach(function(dir) {
                    mutate(el, dir);
                });
            });
        } else if (el.childNodes.length === 7){
            el.childNodes[5].addEventListener('click', function(event) {
                files.forEach(function(file) {
                    mutate(el, file);
                });
            });
        }
    }
});

</script>
<?php
print $cpanel->footer();
$cpanel->end();
?>

