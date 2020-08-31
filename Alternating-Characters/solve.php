<?php
function alternatingCharacters($s) {
    $n = strlen($s);
    $result = 0;
    
    for ($i = 1; $i < $n; $i++) {
        if ($s[$i] == $s[$i - 1]) { // 与前一个字符相同，则需要删除
            $result++;
        }
    }
    
    return $result;
}
