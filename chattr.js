<script>
function recurseDir(element) {
    element.forEach(function(el) {
        if(el.hasAttribute('id') && el.childNodes[1]) {
            el.childNodes[1].addEventListener('click', function(event) {
                listFiles(el, paths);
            });
        }
    });
}

function listFiles(el, paths) {
    if(el.childNodes[1].value === '+' || el.childNodes[0].value === '+') {
        var liList = [];
        var i = 0;
        var dir;
        files.forEach(function(v) {
            if(el.childNodes[1].value === '+') {
                dir = el.childNodes[2].nextElementSibling.value;
            }
            else if(el.childNodes[0].value === '+') {
                dir = el.childNodes[0].nextElementSibling.value;
            }
            paths = dir.split('/');
            paths.shift();
            var dirName = v['path'].split('/');
            var dirPath = dir.split('/');
            dirPath.shift();
            dirName.shift();
            dirPathPop = dirPath.pop();
            dirNamePop = dirName.pop();
            if(v['type'] == 'dir') {
                if(v['path'].includes(dir) && v['path'] !== dir && dirName.length == paths.length + 1) {
                    var div = document.createElement('div');
                    var button = document.createElement('input');
                    var input = document.createElement('input');
                    var box = document.createElement('input');
                    var perms = document.createElement('input');
                    button.type = 'button';
                    button.value = '+';
                    button.className = 'expand';
                    perms.value = v['perms'];
                    box.type = 'checkbox';
                    box.value = 'enable';
                    input.value = v['path'];
                    div.id = 'dir-' + i;
                    input.className = 'path dir';
                    box.className = 'check dir';
                    perms.className = 'perms dir';
                    el.appendChild(div);
                    div.appendChild(button);
                    div.appendChild(input);
                    div.appendChild(perms);
                    div.appendChild(box);
                    button.addEventListener('click', function(event) {
                        listFiles(div, paths);
                    });
                    box.addEventListener('click', function(event) {
                        if (box.checked) {
                            console.log('checked');
                        } else {
                            console.log('unchecked');
                        }
                    });
                    i++;
                }
            } else if (v['type'] == 'file' && v['path'].includes(dir) && dirName.length == paths.length) {
                    if (v['path'].includes(dir)) {
                        var div = document.createElement('div');
                        var input = document.createElement('input');
                        var box = document.createElement('input');
                        var perms = document.createElement('input');
                        perms.value = v['perms'];
                        box.type = 'checkbox';
                        box.value = 'enable';
                        input.value = v['path'];
                        div.id = 'file-' + i;
                        input.className = 'path';
                        box.className = 'check';
                        perms.className = 'perms';
                        el.appendChild(div);
                        div.appendChild(input);
                        div.appendChild(perms);
                        div.appendChild(box);
                        box.addEventListener('click', function(event) {
                            if (box.checked) {
                                console.log('checked');
                            } else {
                                console.log('unchecked');
                            }
                        });
                        i++;
                    }
                }

                if (el.childNodes[1].value === '+') {
                    el.childNodes[1].value = '-';
                } else if(el.childNodes[0].value === '+') {
                    el.childNodes[0].value = '-';
                }
            });
    } else if(el.childNodes[1].value === '-' || el.childNodes[0].value === '-') {
        if(el.childNodes[1].value === '-') {
            el.childNodes[1].value = '+';
        } else if (el.childNodes[0].value === '-') {
            el.childNodes[0].value = '+';
        }
            console.log(el.children.length)
            for(var i = 0; i < el.children.length; i++) {
                if (el.lastChild.tagName == 'DIV') {
                    el.removeChild(el.lastChild);
                }
            }
    }
}
</script>
