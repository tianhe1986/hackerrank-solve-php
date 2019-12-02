<?php
function repeatedString($s, $n) {
    $len = strlen($s);
    
    // 到每个位置为止，a出现的次数
    $numArr = [0];
    for ($i = 0; $i < $len; $i++) {
        $numArr[] = ($s[$i] == 'a' ? $numArr[$i] + 1 : $numArr[$i]);
    }
    
    // 剩余长度
    $mod = $n % $len;
    
    // 字符串重复次数
    $count = ($n - $mod)/ $len;
    
    return $numArr[$mod] + $count * $numArr[$len];
}
