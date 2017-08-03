<?php

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

?>

<div id="desc">
    <p>Lock Files and make immutable.</p>
</div>
<div id="nemj-wrapper">
    <div id="nemj-list">
	<div id="title">
        <h4>Path</h4><h4>Perms</h4>
	</div>
        <ul id="list">
            <?php 
		  $files = $chattr->listFiles(); 
		  $l = 0; 
                   foreach ($files as $i) { $l++; 
		     if($i['dir'] === $chattr->userPath) {
		     if ($i['type'] == 'dir') {  
			
	    ?>
                        <li id="<?php echo 'li-' . $l; ?>">
			    <input style="color:blue;" class="expand" id="expand" type="button" value="+" name="button" />
		            <input style="color:blue;" class="path" type="text" value="<?php  echo $i['path']; ?>" id="path" name="path" readonly />
			    <input style="color:blue;" class="perms" type="text" value="<?php  echo $i['perms']; ?>" id="perms" name="perms" readonly />
			    <input class="check" type="checkbox" name="mutate" value="enable" />
                        </li>
		    <?php } else if ($i['type'] == 'file') { ?>
			<li id="<?php echo 'li-' . $l; ?>">
			    <input class="path" type="text" value="<?php  echo $i['path']; ?>" id="path" name="path" readonly />
			    <input class="perms" type="text" value="<?php  echo $i['perms']; ?>" id="perms" name="perms" readonly />
			    <input class="check" type="checkbox" name="mutate" value="enable" />
                        </li>
 	    <?php } } } ?>
        </ul>
    </div>
</div>
<script>
var list = [];
var num = <?php echo json_encode($l); ?>;
var files = <?php echo json_encode($files); ?>;
for(var i = 0; i < num; i++) {
	el = document.getElementById('li-' + i);
	if(el !== null) {
	   list.push(el);
	}
}
list.forEach(function(el) {
    el.childNodes[1].addEventListener('click', function(event) {
    event.preventDefault();
	if(el.childNodes[1].value === '+') {
	var i = 0;
	files.forEach(function(v) {
		var dir = el.childNodes[2].nextElementSibling.value;
		if(v['type'] == 'file') {
		if(v['path'].includes(dir)) {
		  var input = document.createElement('input');
		  input.value = v['path'];
		  input.id = 'file-' + i;
		  input.className = 'path';
		  el.appendChild(input);
		  i++;
		}
		}
		el.childNodes[1].value = '-';			
	}); 
	}
	else if(el.childNodes[1].value === '-') {
		var i = 0;
		files.forEach(function(v) {
			if(v['type'] == 'file') {
				var input = document.getElementById('file-' + i);
				if(input instanceof Node) {
				el.removeChild(input);
				}
				i++;
			}
			el.childNodes[1].value = '+';
		});
	}
});
});
</script>

<?php
print $cpanel->footer();
$cpanel->end();
?>

