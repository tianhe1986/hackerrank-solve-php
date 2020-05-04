<?php
function pangrams($s) {
    $flag = [];
    
    $start = ord('a');
    
    // 转为小写
    $s = strtolower($s);
    
    $n = strlen($s);
    
    for ($i = 0; $i < $n; $i++) {
        $temp = ord($s[$i]) - $start;
        if ($temp >= 0 && $temp < 26) { // 是字母，才记录
            $flag[$temp] = true;
        }
    }
    
    return count($flag) == 26 ? 'pangram' : 'not pangram';
}
