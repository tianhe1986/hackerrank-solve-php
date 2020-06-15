<?php
function funnyString($s) {
    $n = strlen($s);
    // 从两头向中间遍历，直到碰到不一致或两者会合为止
    $start = 0;
    $end = $n - 2;
    // 检查差值是否对称
    while ($start < $end) {
        if (abs(ord($s[$start]) - ord($s[$start+1])) != abs(ord($s[$end]) - ord($s[$end+1]))) {
            return 'Not Funny';
        }
        $start++;
        $end--;
    }
    
    return 'Funny';
}
