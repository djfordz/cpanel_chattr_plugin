<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '/usr/local/cpanel/php/cpanel.php';
require_once 'Chattr.php';
require_once 'chattr.css';

$cpanel = new CPANEL();

$chattr = new Chattr($cpanel);

print $cpanel->header("File Lock", "nemj-chattr");

$dirs = $chattr->listDirs();
$files = $chattr->listFiles();

if (isset($_GET['path']) && $_GET['mutate'] == 'false') {
    $path = $_GET['path'];
    $dirs = $chattr->listDirs($path);
    $files = $chattr->listFiles($path);

    
} else if(isset($_GET['path']) && $_GET['mutate'] == 'true') {
    $chattr->mutate($_GET['path']);
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
recurseDirs(element);
var test = [];


function recurseDirs(element) {
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
}

function listDir(el, dir) {
    
    if(el.childNodes[1].value === '+') {
        el.childNodes[1].value = '-';
        value = el.childNodes[3].value;
        var dirPath = value.split('/');
        var dirName;
        if(dir.path) {
            dirName = dir.path.split('/');
        } else {
            dirName = dir.value.split('/');
        }
        dirPath.shift();
        dirName.shift();
        if(dir.path) {
            if (dir.path.includes(value) && dirName.length == dirPath.length) {
                call(el, {path:dir.path});
            }
        } else {
            if(dir.value.includes(value) && dirName.length == dirPath.length) {
                call(el, {path:dir.value});
            }
        }
         
        checked = el.childNodes[7].checked;
    } else if (el.childNodes[1].value === '-') {
        var len = el.children.length;
        if(el.childNodes[1].value === '-') {
            el.childNodes[1].value = '+';
        }
        for(var l = 0; l < len; l++) {
            if(el.lastChild.tagName == 'DIV') {
                el.removeChild(el.lastChild);
            }
        }
    }
}

function call(el, dir) {
    var xhr = new XMLHttpRequest();
    xhr.responseType = "document";
    xhr.open('GET', url + '?path=' + dir.path + '&mutate=false', true);
    xhr.send(null);
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4) {
            var x = xhr.response;
            var element = x.querySelectorAll('li');
            element.forEach(function(v) {
                if(v.id.includes('li-')) {
                    var div = document.createElement('div');
                    div.className = 'dir-' + i;
                    div.appendChild(v);
                    div.style.marginLeft = '10px';
                    el.appendChild(div);
                    if(v.parentNode.className.includes('dir-')) {
                        if(v.childNodes[1].className.includes('dir')) {
                            v.childNodes[1].addEventListener('click', function(event) {
                                listDir(v, this.nextElementSibling);
                            });
                            v.childNodes[7].addEventListener('click', function(event) {
                                mutate(v, this.parentNode.childNodes[3]);
                            });
                        } else if(v.childNodes[1].className.includes('file')) {
                            v.childNodes[5].addEventListener('click', function(event) {
                                mutate(v, this.parentNode.childNodes[1]);
                            });
                        }
                    }   
                }
                i++;
            });
        } 
    }
}

function mutate(el, dir) {
    var value;
    if(el.childNodes[1].value === '+' || el.childNodes[1].value === '-') {
        value = el.childNodes[3].value;
    } else {
        value = el.childNodes[1].value;
    }
    var dirName;
    if(dir.path) {
        dirName = dir.path.split('/');
    } else {
        dirName = dir.value.split('/');
    }

    var dirPath = value.split('/');
    dirPath.shift();
    dirName.shift();
    if(dir.path) {
        if (dir.path.includes(value) && dirName.length == dirPath.length) {
            var xhr = new XMLHttpRequest();
            xhr.responseType = "document";
            xhr.open('GET', url + '?path=' + dir.path + '&mutate=true', true);
            xhr.send(null);
        }    
    } else {
        if (dir.value.includes(value) && dirName.length == dirPath.length) {
            var xhr = new XMLHttpRequest();
            xhr.responseType = "document";
            xhr.open('GET', url + '?path=' + dir.value + '&mutate=true', true);
            xhr.send(null);
        }
    }
    
    
    if(el.childNodes[1].value === '+' || el.childNodes[1].value === '-') {
        var nodes = el.querySelectorAll('input[type=checkbox]');
        if(el.childNodes[7].checked) {
            nodes.forEach(function(node) {
                node.checked = true;
            });
        } else {
            nodes.forEach(function(node) {
                node.checked = false;
            });
        }
    }

}

</script>
<?php
print $cpanel->footer();
$cpanel->end();
?>

