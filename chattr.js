<script>
function listDir(el, dir) {
    if(el.childNodes[1].value === '+') {
        el.childNodes[1].value = '-';
        value = el.childNodes[3].dataset.value;
        var dirPath = value.split('/');
        var dirName;
        console.log(dir);
        if(dir.path) {
            dirName = dir.path.split('/');
        } else {
            dirName = dir.dataset.value.split('/');
        }
        dirPath.shift();
        dirName.shift();
        if(dir.path) {
            if (dir.path.includes(value) && dirName.length == dirPath.length) {
                call(el, {path:dir.path});
            }
        } else {
            if(dir.dataset.value.includes(value) && dirName.length == dirPath.length) {
                call(el, {path:dir.dataset.value});
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
        value = el.childNodes[3].dataset.value;
    } else {
        value = el.childNodes[1].dataset.value;
    }
    var dirName;
    if(dir.path) {
        dirName = dir.path.split('/');
    } else {
        dirName = dir.dataset.value.split('/');
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
        if (dir.dataset.value.includes(value) && dirName.length == dirPath.length) {
            var xhr = new XMLHttpRequest();
            xhr.responseType = "document";
            xhr.open('GET', url + '?path=' + dir.dataset.value + '&mutate=true', true);
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
