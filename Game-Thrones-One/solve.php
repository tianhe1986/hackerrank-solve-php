<?php
function gameOfThrones($s) {
    // 只有一个字母允许出现奇数个
    $numArr = array_fill(0, 26, 0);
    
    $n = strlen($s);
    $base = ord('a');
    for ($i = 0; $i < $n; $i++) {
        // 只用判断奇偶性，不需要具体个数，所以用异或即可
        $numArr[ord($s[$i]) - $base] ^= 1;
    }
    
    $count = 0;
    for ($i = 0; $i < 26; $i++) {
        $count += $numArr[$i];
    }
    
    return $count <= 1 ? 'YES' : 'NO';
}
