<?php
function stringConstruction($s) {
    $map = [];
    $len = strlen($s);
    
    // 只有未出现过的字符才需要往里加
    for ($i = 0; $i < $len; $i++) {
        $map[$s[$i]] = true;
    }
    
    return count($map);
}
